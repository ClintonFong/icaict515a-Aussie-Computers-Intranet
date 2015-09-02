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
	    case "update-account" :	// updates the members account details
            if ( isset($_REQUEST['userID']) && isset($_REQUEST['firstname']) && isset($_REQUEST['lastname']) && isset($_REQUEST['email']) && isset($_REQUEST['phone']) && isset($_REQUEST['accessLevel']) && isset($_REQUEST['deptID']) )
            {
                updateAccountDB( $_REQUEST['userID'], $_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['email'], $_REQUEST['phone'], $_REQUEST['accessLevel'], $_REQUEST['deptID'] );
            }
            break;

	    case "update-password" :	// updates the members password
            if ( isset($_REQUEST['userID']) && isset($_REQUEST['oldPassword']) && isset($_REQUEST['newPassword']) )
            {
                updatePassword( $_REQUEST['userID'], $_REQUEST['oldPassword'], $_REQUEST['newPassword'] );
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
// updateAccountDB
// --------------------------------------------------------------------------------------------------------------
function updateAccountDB( $userID,
                          $firstname, 
                          $lastname, 
                          $email, 
                          $phone,
                          $accessLevel,
                          $deptID )
{
    assert( isset( $userID ) );
    assert( isset( $firstname ) );
    assert( isset( $lastname ) );
    assert( isset( $email ) );
    assert( isset( $phone ) );  
    assert( isset( $accessLevel) );
    assert( isset( $deptID) );

    global $strResponseMessage;
    global $strResponseData;

    $strResponseMessage = "Unsuccessful";
    $strResponseData    = "Update failed. Please contact administrator to update details";
 
	$dbConnection = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
		$stmtQuery       = "UPDATE icaict515a_employees SET firstname=?, lastname=?, email=?, phone=?, access_level=?, department_id=?";
        $stmtQuery      .= " WHERE employee_id=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $firstname      = scrubInput( $firstname,   $dbConnection );
            $lastname       = scrubInput( $lastname,    $dbConnection );
            $phone          = scrubInput( $phone,       $dbConnection );
            $accessLevel    = scrubInput( $accessLevel, $dbConnection );
            $deptID         = scrubInput( $deptID,      $dbConnection );
            $userID         = scrubInput( $userID,      $dbConnection );

            $stmt->bind_param("sssssss", $firstname, $lastname, $email, $phone, $accessLevel, $deptID, $userID );

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

} // updateAccountDB

// --------------------------------------------------------------------------------------------------------------
// updatePassword
// --------------------------------------------------------------------------------------------------------------
function updatePassword( $userID, 
                         $oldPassword, 
                         $newPassword )
{
    //echo "In Update password";
    assert( isset( $userID) );
    assert( isset( $oldPassword) );
    assert( isset( $newPassword) );
    
    global $strResponseMessage;
    global $strResponseData;


    $bSuccess           = FALSE;
    $strResponseMessage = "Unsuccessful";
    $strResponseData    = "Update failed. Please contact Administration to update password.";
   
	$dbConnection   = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
        $sha256_OldPassword =  hash('sha256', $oldPassword);


        if( $oldPassword == $newPassword )
        {
            $strResponseData = "Please ensure that New & Old password entered are different.";
        }
        elseif ( !getDBPasswordDB( $dbConnection, $userID, $dbPassword ) )
        {
            $strResponseData    = "Database error. Please contact Administration to update password.";
        }
        elseif( $dbPassword != $sha256_OldPassword ) 
        {
            $strResponseData = "Old password is incorrect.";
        }
        elseif( updatePasswordDB( $dbConnection, $userID, $newPassword, $nAffectedRows ) )
        {
            $strResponseMessage = "Success";
            if( $nAffectedRows > 0 )  { $strResponseData    = "Password update successful"; }
            else                      { $strResponseData    = "Nothing changed. Old & New password are the same.";}
        }
        $dbConnection->close();
    }

} // updatePassword

// --------------------------------------------------------------------------------------------------------------
// updatePasswordDB
// --------------------------------------------------------------------------------------------------------------
function updatePasswordDB( $dbConnection,
                           $userID, 
                           $newPassword,
                           &$nAffectedRows )
{
    assert( isset( $dbConnection) );
    assert( isset( $userID ) );
    assert( isset( $newPassword) );

    $bSuccess       = FALSE;

    if( !$dbConnection->connect_errno )
    {
		$stmtQuery   = "UPDATE icaict515a_employees SET password=? WHERE employee_id=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $userID         = scrubInput( $userID, $dbConnection );
            $sha256Password = hash('sha256', $newPassword);

            $stmt->bind_param("si", $sha256Password, $userID );

            $bSuccess = $stmt->execute();
            $nAffectedRows = $stmt->affected_rows;
            $stmt->close(); 	
        }

	}

    return $bSuccess;

} // updatePasswordDB


// --------------------------------------------------------------------------------------------------------------
// getDBPasswordDB
//
// Description: returns true if successfully retrieved current password from database, otherwise false
// --------------------------------------------------------------------------------------------------------------
function getDBPasswordDB( $dbConnection,
                          $userID, 
                          &$dbPassword )
{
    assert( isset( $dbConnection) );
    assert( isset( $userID ) );

    $bSuccess       = FALSE;

    if( !$dbConnection->connect_errno )
    {
    	$stmtQuery  = "SELECT password FROM icaict515a_employees WHERE employee_id=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $userID = scrubInput( $userID, $dbConnection );
            $stmt->bind_param('i', $userID );

		    if( $bSuccess = $stmt->execute() )
            {
                $stmt->bind_result( $db_password );

		        if ( $stmt->fetch() ) 
		        {
                    $dbPassword = $db_password;
		        } 
            }
            $stmt->close(); 	
        }
	}

    return $bSuccess;

} // getDBPasswordDB

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