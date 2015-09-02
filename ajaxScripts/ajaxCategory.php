<?php
//header("Content-type: text/xml");

require_once    '../include/common.inc.php';

//---------------------
// category item class 
//---------------------
class structCategoryItem
{
    public $categoryItemID          = -1;
    public $name                    = '';
    public $price                   = 0.0;
}
//---------------------

// decide what action to take depending on the client request
$strResponseMessage = "Request Undefined";
$strResponseData    = "";
$strNewPassword     = "";

if ( isset( $_REQUEST['action'] ) )
{	

    switch ($_REQUEST['action'])
    {
	    case "change-category" :	// handles the forgot password request
            if ( isset($_REQUEST['categoryID'] ) )
            {
                $strResponseMessage = getRequestedCategory( $_REQUEST['categoryID'], $strResponseData );
            }
            break;

	    default:
		    $strResponseMessage = "Unknown request";		
		
    } // switch


}

$strResponse  = "<message>{$strResponseMessage}</message>";
$strResponse .= "<data><![CDATA[{$strResponseData}]]></data>";
$strPackage   = "<package>{$strResponse}</package>";
echo $strPackage;

// --------------------------------------------------------------------------------------------------------------
// getDBConnection
// --------------------------------------------------------------------------------------------------------------
function getDBConnection(&$strResponseMessage)
{
    $dbConnection = new mysqli (DB_SERVER, USER_NAME, PASSWORD, DATABASE);

    if( $dbConnection->connect_errno )
    {
        $strResponseMessage = "Connection to database failed";
        trigger_error("Connection to database failed " . $dbConnection->connect_errno );
    }
    return $dbConnection;

} // getDBConnection

// --------------------------------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------------------------------
// resetPasswordDB
// --------------------------------------------------------------------------------------------------------------
function getRequestedCategory( $categoryID,
                               &$strResponseData )
{
    assert( isset( $categoryID) );
    
    $arrCategoryItems   = array();
    $strResponseMessage = "Unsuccessful";
    
	$dbConnection       = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
		$stmtQuery  = "SELECT category_item_id, name, price FROM icaict515a_category_items";
        $stmtQuery .= " WHERE category_id=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $categoryID = scrubInput( $categoryID, $dbConnection );
            $stmt->bind_param('s', $categoryID );

            if( $stmt->execute() )
            {
                $stmt->bind_result( $db_category_item_id, $db_name, $db_price );

		        while( $stmt->fetch() ) 
		        {
                    $orderItem  = new structCategoryItem();

                    $orderItem->categoryItemID  = $db_category_item_id;
                    $orderItem->name            = $db_name;
                    $orderItem->price           = $db_price;

                    $arrCategoryItems[]         = $orderItem;
		        } 
                $strResponseMessage = "Success"; 

            }
		    $stmt->close(); 	// Free resultset 
        }
        $dbConnection->close();
	}

    $strResponseData = json_encode( $arrCategoryItems );
	
    return $strResponseMessage;

} // resetPasswordDB



//---------------------------------------------------------------------------------------------
// srubInput 
//
// Description: scrubs down input value elimaate possible sql injection
//---------------------------------------------------------------------------------------------
function scrubInput($value, $dbConnection)
{
        
    //if( get_magic_quotes_gpc() )    { $value = stripslashes($value); }                                           // Stripslashes


    $value = $dbConnection->real_escape_string( $value );

    //if (!is_numeric($value)) { $value = "'" . $value . "'";  } // Quote if not a number

    return $value;

} // scrubInput



?>