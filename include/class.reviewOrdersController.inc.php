<?php

require_once    'class.basicDB.inc.php';

define('MAX_ORDERS_DISPLAY', 12);

//----------------------
class structOrder
{
    public $date          = '';
    public $orderID       = '';
    public $name          = ''; // can be either manager or employee's name
    public $amount        = '';
    public $status        = '';

}

//---------------------------------------------------------------------------------------------
class c_reviewOrdersController extends c_basicDB
{
    public $employeeID  = -1;
    public $accessLevel = AL_EMPLOYEE;
    public $arrOrders   = array();

    public $nOrdersDisplayed = 0;


	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $employeeID = 0,
                          $accessLevel = AL_EMPLOYEE )
	{
		parent::__construct();

		$this->employeeID   = $employeeID;
        $this->accessLevel  = $accessLevel;

        if( $accessLevel == AL_MANAGER ) { $this->loadAllOrdersForManager(); }
        else                             { $this->loadAllOrdersForEmployee(); }
	
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct



	//---------------------------------------------------------------------------------------------
    // loadAllOrdersForEmployee
	//---------------------------------------------------------------------------------------------
    function loadAllOrdersForEmployee( $employeeID = 0 )
    {
        assert( isset( $this->dbConnection) );

        $employeeID = ( $employeeID <= 0 )? $this->employeeID : $employeeID;

		$stmtQuery  = "SELECT order_id, ROUND(amount, 2) as amount, order_status, revision, DATE_FORMAT(datetime_submitted, '%Y-%m-%d') as dateSubmitted, firstname, lastname";
        $stmtQuery .= " FROM icaict515a_orders LEFT JOIN icaict515a_employees ON icaict515a_orders.manager_id = icaict515a_employees.employee_id";
        $stmtQuery .= " WHERE   icaict515a_orders.employee_id = {$employeeID}";
        $stmtQuery .= " ORDER BY order_id DESC";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $order = new structOrder();

                $order->name        = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);

                $order->date        = $row['dateSubmitted'];
                $order->orderID     = $row['order_id'];
                $order->amount      = $row['amount'];
                $order->status      = $row['order_status'];
                $order->revision    = $row['revision'];

                $this->arrOrders[]  = $order;

		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }
    } // loadAllOrdersForEmployee

	//---------------------------------------------------------------------------------------------
    // loadAllOrdersForManager
	//---------------------------------------------------------------------------------------------
    function loadAllOrdersForManager( $employeeID = 0 )
    {
        assert( isset( $this->dbConnection) );

        $employeeID = ( $employeeID <= 0 )? $this->employeeID : $employeeID;

		$stmtQuery  = "SELECT order_id, ROUND(amount, 2) as amount, order_status, revision, DATE_FORMAT(datetime_submitted, '%Y-%m-%d') as dateSubmitted, firstname, lastname";
        $stmtQuery .= " FROM icaict515a_orders LEFT JOIN icaict515a_employees ON icaict515a_orders.employee_id = icaict515a_employees.employee_id";
        $stmtQuery .= " WHERE   icaict515a_orders.manager_id = {$employeeID}";
        $stmtQuery .= " ORDER BY order_id DESC";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {

		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $order = new structOrder();

                $order->name        = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);

                $order->date        = $row['dateSubmitted'];
                $order->orderID     = $row['order_id'];
                $order->amount      = $row['amount'];
                $order->status      = $row['order_status'];
                $order->revision    = $row['revision'];

                $this->arrOrders[]  = $order;

		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }
    } // loadAllOrdersForManager

    
	//---------------------------------------------------------------------------------------------
    function displayOrders()
    {
        //echo "In displayOrders - hello";
        $iOrdersDisplayed = 0;
        foreach( $this->arrOrders as $order )
        {
            $this->displayNextTROrder( $order );
            if( ++$iOrdersDisplayed >= MAX_ORDERS_DISPLAY ) break;
        }
        $this->nOrdersDisplayed = $iOrdersDisplayed;

    } // displayOrders

	//---------------------------------------------------------------------------------------------
    function displayNextTROrder( $order )
    {
        $imgStatus = 'Unknown';
        switch( $order->status )
        {
            case OS_SUBMITTED : $imgStatus = "<img src='images/question-mark.png' alt='pending' class='centered' />"; break;
            case OS_APPROVED  : $imgStatus = "<img src='images/tick.png' alt='yes' class='centered' />";              break;
            case OS_REJECTED  : $imgStatus = "<img src='images/cross.png' alt='no' class='centered' />";              break;
            case OS_PROCESSED : $imgStatus = "<img src='images/processing.png' alt='processing' class='centered' />"; break;
            case OS_SAVED     : $imgStatus = "<img src='images/saved.png' alt='saved' class='centered' />";           break;
        }

        $strDisplay = "
            <tr id='tr{$order->orderID}' class='order'> \n
                <td>{$order->date}</td>\n
                <td>{$order->orderID}</td>\n
                <td>{$order->name}</td>\n
                <td>{$order->amount}</td>\n
                <td class='revision'>{$order->revision}</td>\n
                <td>{$imgStatus}</td>\n
            </tr>\n
            ";

        echo $strDisplay;

    } // displayNextTROrder


} // class c_reviewOrdersController 

?>
