<?php

require_once    'class.basicDB.inc.php';

//----------------------
class structOrderToAuthorize
{
    public $date          = '';
    public $orderID       = '';
    public $employeeID    = ''; // the orderer's id
    public $employeeName  = '';
    public $categoryID    = '';
    public $categoryName  = '';
    public $amount        = '';
    public $revision      = '';

} // structOrderToAuthorize

//---------------------------------------------------------------------------------------------
class c_authorizeOrdersController extends c_basicDB
{
    public $employeeID  = -1;       // the manager's id
    public $arrOrders   = array();


	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $employeeID = 0 )
	{
		parent::__construct();

		$this->employeeID = $employeeID;
        //$this->loadAllOrdersForAuthorization();
	
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct



	//---------------------------------------------------------------------------------------------
    // loadAllOrdersForAuthorization
	//---------------------------------------------------------------------------------------------
    function loadAllOrdersForAuthorization( $employeeID = 0 )
    {
        assert( isset( $this->dbConnection) );

        $employeeID = ( $employeeID <= 0 )? $this->employeeID : $employeeID;    // the manager's id

		$stmtQuery  = "SELECT order_id, icaict515a_orders.employee_id, ROUND(amount, 2) as amount, revision, DATE_FORMAT(datetime_submitted, '%Y-%m-%d') as dateSubmitted,";
        $stmtQuery .= " icaict515a_orders.category_id, icaict515a_categories.name, icaict515a_employees.firstname, icaict515a_employees.lastname";
        $stmtQuery .= " FROM icaict515a_orders";
        $stmtQuery .= " LEFT JOIN icaict515a_employees  ON icaict515a_orders.employee_id = icaict515a_employees.employee_id";
        $stmtQuery .= " LEFT JOIN icaict515a_categories ON icaict515a_orders.category_id = icaict515a_categories.category_id";
        $stmtQuery .= " WHERE   icaict515a_orders.manager_id = {$employeeID}";
        $stmtQuery .= " AND     icaict515a_orders.order_status = '" . OS_SUBMITTED . "' ";
        $stmtQuery .= " ORDER BY order_id DESC";


		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $order = new structOrderToAuthorize();

                $order->employeeID      = $row['employee_id'];
                $order->employeeName    = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);

                $order->categoryID      = $row['category_id'];
                $order->categoryName    = $row['name'];

                $order->date        = $row['dateSubmitted'];
                $order->orderID     = $row['order_id'];
                $order->amount      = $row['amount'];
                $order->revision    = $row['revision'];

                $this->arrOrders[]  = $order;

		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

    } // loadAllOrdersForAuthorization

	//---------------------------------------------------------------------------------------------
    function displayOrders()
    {
        //echo "In displayOrders - hello";

        foreach( $this->arrOrders as $order )
        {
            $this->displayNextTROrder( $order );
        }

    } // displayOrders

	//---------------------------------------------------------------------------------------------
    function displayNextTROrder( $order )
    {
        $strDisplay = "
            <tr id='tr{$order->orderID}' class='order'> \n
                <td>{$order->employeeName}</td>\n
                <td>{$order->date}</td>\n
                <td>{$order->categoryName}</td>\n
                <td>{$order->amount}</td>\n
                <td class='revision'>{$order->revision}</td>\n
            </tr>\n
            ";

        echo $strDisplay;

    } // displayNextTROrder

	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // Rejecting & Approving orders
    //
	//---------------------------------------------------------------------------------------------
    function AuthorizeOrder( $orderID,
                             $revision,
                             $status,
                             $note )
    {
        $bSuccess = FALSE;
        $this->dbConnection->autocommit(FALSE); // begin transaction

        if( $bSuccess = $this->updateOrderDB( $orderID, $revision, $status ) )
        {
            $note = trim( $note );
            if( $note != '' ) 
            {            
                $bSucccess = $this->insertNoteDB( $orderID, $note );
            }
        }

        // commit or rollback
        if( $bSuccess )  { $this->dbConnection->commit();    }   // commit the transaction
        else              { $this->dbConnection->rollback();  }   // rollback the transaction

        return $bSuccess;

    } // AuthorizeOrder

	//---------------------------------------------------------------------------------------------
    function updateOrderDB( $orderID,
                            $revision,
                            $status )
    {
        $bSuccess = FALSE;

        $stmtQuery  = "UPDATE icaict515a_orders SET order_status=? ";
        $stmtQuery .= " WHERE order_id=? AND revision=?";
/*        
        echo $stmtQuery;
        echo "orderID ={$orderID}<br>";
        echo "revision ={$revision}<br>";
        echo "status={$status}<br>";
*/
		if( $stmt = $this->getDBConnection()->prepare( $stmtQuery ) )
        {
            $orderID    = $this->scrubInput( $orderID );
            $revision   = $this->scrubInput( $revision );
            $status     = $this->scrubInput( $status );

            $stmt->bind_param('iii', $status, $orderID, $revision );
		    $bOk = $stmt->execute();

            if( $bOk && ($stmt->affected_rows > 0) )
            { 
                $bSuccess = TRUE;
            }
            $stmt->close();
        }
        return $bSuccess;

    } // updateOrderDB

	//---------------------------------------------------------------------------------------------
    function insertNoteDB( $orderID,
                           $note )
    {
        $bSuccess = FALSE;

        $stmtQuery  = "INSERT INTO icaict515a_order_notes (order_id, note)";
        $stmtQuery .= " VALUES (?, ?)";
/*
        echo $stmtQuery;
        echo "orderID ={$orderID}<br>";
        echo "note ={$note}<br>";
*/
		if( $stmt = $this->getDBConnection()->prepare( $stmtQuery ) )
        {
            $orderID    = $this->scrubInput( $orderID );
            $note       = $this->scrubInput( $note );

            $stmt->bind_param('is', $orderID, $note );

		    $bOk = $stmt->execute();

            if( $bOk && ($stmt->affected_rows > 0) )
            { 
                $bSuccess = TRUE;
            }
            $stmt->close();
        }
        return $bSuccess;

    } // insertNoteDB



} // class c_authorizeOrdersController

?>
