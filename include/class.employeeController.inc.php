<?php
require_once    'class.basicDB.inc.php';

$AcessLevelNames = array(   AL_EMPLOYEE             => "Staff", 
                            AL_PROCUREMENT_MEMBER   => "Procurement Staff",
                            AL_MANAGER              => "Manager",
                            AL_ADMIN                => "Administrator"   );

//---------------
class structContactInfo
{
    public $userID          = -1;
    public $deptName        = "";
    public $firstname       = "";
    public $lastname        = "";
    public $accessLevelStr  = "";
    public $email           = "";
    public $phone           = "";

/*
	//---------------------------------------------------------------------------------------------
    // debugging tools
	//---------------------------------------------------------------------------------------------
    function __displayAttributes()
    {
        echo "<br>
   	        userID          = {$this->userID}<br>
   	        deptName        = {$this->deptName}<br>
   	        firstname       = {$this->firstname}<br>
   	        lastname        = {$this->lastname}<br>
   	        accessLevelStr  = {$this->accessLevelStr}<br>
   	        email           = {$this->email}<br>
   	        phone           = {$this->phone}<br>
            ";

    } // __displayAttributes
*/
   
    
} // structContactInfo

//---------------
class structOrder
{
    public $orderID         = -1;
   	public $employeeID      = -1;
   	public $managerID       = -1;
   	public $categoryID      = -1;
   	public $description     = '';
   	public $totalAmount     = '';
    public $revision        = 0;
   	public $arrOrderItems   = array();    /*    json decoded
                                                ------------
                                                $arrOrderItems[0]->orderItemID
                                                $arrOrderItems[0]->name
                                                $arrOrderItems[0]->qty
                                                $arrOrderItems[0]->amount
                                            */
/*
	//---------------------------------------------------------------------------------------------
    // debugging tools
	//---------------------------------------------------------------------------------------------
    function __displayAttributes()
    {
        echo "<br>
   	        orderID         = {$this->orderID}<br>
   	        employeeID      = {$this->employeeID}<br>
   	        managerID       = {$this->managerID}<br>
   	        categoryID      = {$this->categoryID}<br>
   	        description     = {$this->description}<br>
   	        fTotalAmount    = {$this->fTotalAmount}<br>
   	        revision        = {$this->revision}<br>
            ";

        print_r( $this->arrOrderItems );

    } // __displayAttributes
*/

} // structOrder

//---------------------------------------------------------------------------------------------
class c_employeeController extends c_basicDB
{
   	public $employeeID      = -1;
    public $ContactInfo     = array();


    
	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $employeeID = 0 )
	{
		parent::__construct();
		
        $this->employeeID = $employeeID;
		
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct


    //---------------------------------------------------------------------------------------------
    // loadContactListing
    //
    // Description: queries and loads employee contact info from the database into array $this->ContactInfo
    //---------------------------------------------------------------------------------------------
    function loadContactListing()
    {
        assert( isset( $this->dbConnection ) );

        global $AcessLevelNames;
        
        $bSuccessful = FALSE;
        
        $stmtQuery  = "SELECT employee_id, icaict515a_departments.name, firstname, lastname, access_level, email, phone";
        $stmtQuery .= " FROM icaict515a_employees LEFT JOIN icaict515a_departments ON icaict515a_employees.department_id = icaict515a_departments.department_id";

        if ($stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
		    if( $bSuccess = $stmt->execute())
            {
                $stmt->bind_result( $db_userID, $db_deptName, $db_firstname, $db_lastname, $db_accessLevel, $db_email, $db_phone );

		        while( $stmt->fetch() ) 
		        {
                    $bSuccessful = TRUE;
                    
                    $db_accessLevel = ($db_accessLevel != "")? $db_accessLevel : 0;

                    $contactInfo = new structContactInfo();
                        
                    $contactInfo->userID            = $db_userID;
                    $contactInfo->deptName          = $db_deptName;
                    $contactInfo->firstname         = $db_firstname;
                    $contactInfo->lastname          = $db_lastname;
                    $contactInfo->accessLevelStr    = $AcessLevelNames[ $db_accessLevel ];
                    $contactInfo->email             = $db_email;
                    $contactInfo->phone             = $db_phone;
                    
                    $this->ContactInfo[] = $contactInfo;

		        } 
            }
	        $stmt->close(); 	// Free resultset 
        }

    	return $bSuccessful;
        
    } // loadContactListing

    

    //---------------------------------------------------------------------------------------------
    // registerNewOrder
    //
    // Description: register new order
	//---------------------------------------------------------------------------------------------

	function registerNewOrder( $order )
	{
        //echo "In registerNewOrder";

        assert( isset( $this->dbConnection) );

        $bRegisterSuccessful    = FALSE;

        $this->dbConnection->autocommit(FALSE); // begin transaction

        if( $bRegisterSuccessful = $this->insertOrderDB( $order ) )
        {
            $bRegisterSuccessful = $this->insertOrderItemsDB( $order );
        }

        // commit or rollback
        if( $bRegisterSuccessful )  { $this->dbConnection->commit();    }   // commit the transaction
        else                        { $this->dbConnection->rollback();  }   // rollback the transaction

        return $bRegisterSuccessful;

	} // registerNewOrder

	//---------------------------------------------------------------------------------------------
    function insertOrderDB( $order )
    {
        $bRegisterSuccessful    = FALSE;

		$stmtQuery  = "INSERT INTO icaict515a_orders (employee_id, manager_id, category_id, description, amount, order_status, revision, datetime_submitted) VALUES ";
        $stmtQuery .= "(?, ?, ?, ?, ?, '0', ?, now()) ";

        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $order->employeeID   = $this->scrubInput( $order->employeeID );
            $order->managerID    = $this->scrubInput( $order->managerID );
            $order->categoryID   = $this->scrubInput( $order->categoryID );
            $order->description  = $this->scrubInput( $order->description );
            $order->totalAmount  = $this->scrubInput( $order->totalAmount );

            $stmt->bind_param("iiissi",  $order->employeeID, 
                                         $order->managerID, 
                                         $order->categoryID, 
                                         $order->description, 
                                         $order->totalAmount,
                                         $order->revision );

		    $bSuccess = $stmt->execute();

            $order->orderID = -1;
            if( $bSuccess && ($this->dbConnection->affected_rows > 0) )
            { 
                $order->orderID = $this->dbConnection->insert_id;
                $bRegisterSuccessful = TRUE; 
            }
        }

        return $bRegisterSuccessful;

    } // insertOrderDB
    
	//---------------------------------------------------------------------------------------------
    function insertOrderItemsDB( $order )
    {
        $bRegisterSuccessful    = TRUE;       

		$stmtQuery  = "INSERT INTO icaict515a_order_items (order_id, name, quantity, amount, revision) VALUES ";
        $stmtQuery .= "(?, ?, ?, ?, ? ) ";


        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {

            $order->orderID     = $this->scrubInput( $order->orderID );
            $order->revision    = $this->scrubInput( $order->revision);

            foreach( $order->arrOrderItems as $orderItem )
            {
                $orderItem->name        = $this->scrubInput( $orderItem->name );
                $orderItem->qty         = $this->scrubInput( $orderItem->qty );
                $orderItem->amount      = $this->scrubInput( $orderItem->amount);

                $stmt->bind_param("isidi",  $order->orderID, 
                                            $orderItem->name, 
                                            $orderItem->qty, 
                                            $orderItem->amount, 
                                            $order->revision );

		        $bSuccess = $stmt->execute();

                if( !$bSuccess || ($this->dbConnection->affected_rows <= 0) )
                {
                    $bRegisterSuccessful = FALSE;
                    break;
                }

            } // foreach
        }

        return $bRegisterSuccessful;


    } // insertOrderDB
    



	//---------------------------------------------------------------------------------------------

} // class c_employeeController

?>
