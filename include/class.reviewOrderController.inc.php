<?php

require_once    'class.basicDB.inc.php';

//----------------------
class structOrderItem
{
    public $orderItemID     = '';
    public $name            = '';
    public $quantity        = '';
    public $amount          = '';
}

//----------------------
class structOrderNote
{
    public $orderNoteID     = '';
    public $note            = '';
}

//---------------------------------------------------------------------------------------------
class c_reviewOrderController extends c_basicDB
{
    public $orderID         = -1;
    public $revision        = -1;
    public $managerID       = -1;
    public $employeeID      = -1;
    public $categoryID      = -1;
    public $description     = '';
    public $totalAmount     = -1;
    public $status          = '';
    public $dateSubmitted   = '';

    public $managerName     = '';
    public $employeeName    = '';
    public $categoryName    = '';

    public $arrOrderItems   = array();
    public $arrOrderNotes   = array();


	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $orderID, 
                          $revision )
	{
		parent::__construct();

		$this->orderID  = $orderID;
		$this->revision = $revision;

        $this->loadOrder();
        $this->loadOrderItems();
        $this->loadOrderNotes();
	
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct



	//---------------------------------------------------------------------------------------------
    // loadOrder
	//---------------------------------------------------------------------------------------------

    function loadOrder( $orderID = 0,
                        $revision = 0 )
    {

        assert( isset( $this->dbConnection ) );

        $orderID    = ( $orderID <= 0 )? $this->orderID : $orderID;
        $revision   = ( $revision <= 0 )? $this->revision : $revision;

		$stmtQuery  = "SELECT icaict515a_orders.employee_id, manager_id, description, ROUND(amount, 2) as amount, order_status, revision, DATE_FORMAT(datetime_submitted, '%Y-%m-%d') as dateSubmitted, ";
        $stmtQuery .= " manager.firstname as managerFirstname, manager.lastname as managerLastname, employee.firstname as employeeFirstname, employee.lastname as employeeLastname, ";
        $stmtQuery .= " categories.category_id, categories.name as categoryName ";
        $stmtQuery .= " FROM icaict515a_orders";
        $stmtQuery .= " LEFT JOIN icaict515a_employees manager  ON manager.employee_id = icaict515a_orders.manager_id";
        $stmtQuery .= " LEFT JOIN icaict515a_employees employee ON employee.employee_id = icaict515a_orders.employee_id";
        $stmtQuery .= " LEFT JOIN icaict515a_categories categories ON categories.category_id = icaict515a_orders.category_id";
        $stmtQuery .= " WHERE icaict515a_orders.order_id = {$orderID}";
        $stmtQuery .= " AND icaict515a_orders.revision = {$revision}";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    if ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $this->managerName          = strtoupper($row['managerLastname']) . ", " . ucfirst($row['managerFirstname']);
                $this->managerID            = $row['manager_id'];
                
                $this->employeeName         = strtoupper($row['employeeLastname']) . ", " . ucfirst($row['employeeFirstname']);
                $this->employeeID           = $row['employee_id'];

                $this->categoryID           = $row['category_id'];
                $this->categoryName         = $row['categoryName'];

                $this->description          = $row['description'];
                $this->totalAmount          = $row['amount'];
                $this->status               = $row['order_status'];
                $this->revision             = $revision;
                $this->orderID              = $orderID;
                $this->dateSubmitted        = $row['dateSubmitted'];

                //$this->__displayAttributes();

		    } // if
        	
		    $resultQuery->close(); 	// Free resultset 
        }

    } // loadOrder

	//---------------------------------------------------------------------------------------------
    // loadOrderItems
	//---------------------------------------------------------------------------------------------

    function loadOrderItems( $orderID = 0,
                             $revision = 0 )
    {

        assert( isset( $this->dbConnection ) );

        $orderID    = ( $orderID <= 0 )? $this->orderID : $orderID;
        $revision   = ( $revision <= 0 )? $this->revision : $revision;

		$stmtQuery  = "SELECT order_item_id, name, quantity, ROUND(amount, 2) as amount FROM icaict515a_order_items";
        $stmtQuery .= " WHERE icaict515a_order_items.order_id = {$orderID}";
        $stmtQuery .= " AND icaict515a_order_items.revision = {$revision}";

        //echo $stmtQuery;

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $orderItem = new structOrderItem();

                $orderItem->orderItemID     = $row['order_item_id'];
                $orderItem->name            = $row['name'];
                $orderItem->quantity        = $row['quantity'];
                $orderItem->amount          = $row['amount'];

                $this->arrOrderItems[]      = $orderItem;

		    } // if
        	
		    $resultQuery->close(); 	// Free resultset 
        }

        //$this->__displayOrderItems();

    } // loadOrderItems


  	//---------------------------------------------------------------------------------------------
    function displayOrderItems()
    {
        //echo "In displayOrders - hello";

        foreach( $this->arrOrderItems as $orderItem )
        {
            $this->displayNextTROrderItem( $orderItem );
        }

    } // displayOrderItems

    //---------------------------------------------------------------------------------------------
    function displayNextTROrderItem( $orderItem )
    {
        $strDisplay = "
            <tr id='tr{$orderItem->orderItemID}' class='order'> \n
                <td>{$orderItem->name}</td>\n
                <td>{$orderItem->quantity}</td>\n
                <td>{$orderItem->amount}</td>\n
            </tr>\n
            ";

        echo $strDisplay;

    } // displayOrderItem


	//---------------------------------------------------------------------------------------------
    // Notes
	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // loadOrderNotes
	//---------------------------------------------------------------------------------------------

    function loadOrderNotes( $orderID = 0 )
    {

        assert( isset( $this->dbConnection ) );

        $orderID    = ( $orderID <= 0 )? $this->orderID : $orderID;

		$stmtQuery  = "SELECT order_note_id, note FROM icaict515a_order_notes";
        $stmtQuery .= " WHERE icaict515a_order_notes.order_id = {$orderID}";

        //echo $stmtQuery;

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $orderNote = new structOrderNote();

                $orderNote->orderNoteID     = $row['order_note_id'];
                $orderNote->note            = $row['note'];

                $this->arrOrderNotes[]      = $orderNote;

		    } // if
        	
		    $resultQuery->close(); 	// Free resultset 
        }

        //$this->__displayOrderItems();

    } // loadOrderItems

  	//---------------------------------------------------------------------------------------------
    function displayOrderNotes()
    {
//        echo "In displayOrders - hello";

        foreach( $this->arrOrderNotes as $orderNote )
        {
            echo "{$orderNote->note}\n";
        }



    } // displayOrderNotes



	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // debugging tools
	//---------------------------------------------------------------------------------------------
    function __displayAttributes()
    {
        echo "<br>
                managerID = {$this->managerID}<br>
                mangerName = {$this->managerName}<br>
                <br>
                employeeID  = {$this->employeeID}<br>
                employeeName = {$this->employeeName}<br>
                <br>
                categoryID  = {$this->categoryID}<br>
                categoryName = {$this->categoryName}<br>
                <br>
                description = {$this->description}<br>
                <br>
                total amount = {$this->totalAmount}<br>
                status = {$this->status}<br>
                revision = {$this->revision}<br>
                orderID = {$this->orderID}<br>
                dateSubmitted = {$this->dateSubmitted}<br>
                <br>
            ";

    } // __displayAttributes

	//---------------------------------------------------------------------------------------------
    function __displayOrderItems()
    {
        foreach( $this->arrOrderItems as $orderItem )
        {
            echo "<br>
                orderItemID = {$orderItem->orderItemID}<br>
                name = {$orderItem->name}<br>
                quantity = {$orderItem->quantity}<br>
                amount = {$orderItem->amount}<br>
                <br>
                ";
        }


    } // __displayOrderItems

} // class c_reviewOrdersController 

?>
