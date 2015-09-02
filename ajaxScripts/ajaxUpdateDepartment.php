<?php
//header("Content-type: text/xml");

require_once    '../include/common.inc.php';

// decide what action to take depending on the client request
$strResponseMessage = "Request Undefined";
$strResponseData    = "";
$strResponseID      = "";
$strNewPassword     = "";

if ( isset( $_REQUEST['action'] ) )
{	
    switch ($_REQUEST['action'])
    {
	    case "update-department" :	// updates the members account details
            if ( isset($_REQUEST['deptID']) && isset($_REQUEST['deptName']) && isset($_REQUEST['deptManagerID']) && isset($_REQUEST['deptBudget']) )
            {
                updateDeptDB( $_REQUEST['deptID'], $_REQUEST['deptName'], $_REQUEST['deptManagerID'], $_REQUEST['deptBudget'] );
            }
            break;

	    default:
		    $strResponseMessage = "Unknown request";		
		
    } // switch


}

$strResponse  = "<message>{$strResponseMessage}</message>";
$strResponse .= "<data><![CDATA[{$strResponseData}]]></data>";
$strResponse .= "<id>{$strResponseID}</id>";
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
// updateDeptDB
// --------------------------------------------------------------------------------------------------------------
function updateDeptDB( $deptID,
                       $deptName,
                       $deptManagerID, 
                       $deptBudget )
{
    assert( isset( $deptID ) );
    assert( isset( $deptName ) );
    assert( isset( $deptManagerID ) );
    assert( isset( $deptBudget ) );

    global $strResponseMessage;
    global $strResponseData;

    $strResponseMessage = "Unsuccessful";
    $strResponseData    = "Update failed. Please contact Administrator to update details";
    
	$dbConnection = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
		$stmtQuery       = "UPDATE icaict515a_departments SET name=?, manager_id=?, budget=?";
        $stmtQuery      .= " WHERE department_id=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $deptID         = scrubInput( $deptID,          $dbConnection );
            $deptName       = scrubInput( $deptName,        $dbConnection );
            $deptManagerID  = scrubInput( $deptManagerID,   $dbConnection );
            $deptBudget     = scrubInput( $deptBudget,      $dbConnection );

            $stmt->bind_param("ssss", $deptName, $deptManagerID, $deptBudget, $deptID );

            if( $stmt->execute() )
            { 
                $strResponseMessage = "Success"; 
                if ($dbConnection->affected_rows > 0)   { $strResponseData = "Update Successful"; }
                else                                    { $strResponseData = "Nothing changed. Details are still the same."; }
            }
            $stmt->close(); 	
        }

        $dbConnection->close();
	}

    return ( $strResponseMessage == "Success" );

} // updateDeptDB

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