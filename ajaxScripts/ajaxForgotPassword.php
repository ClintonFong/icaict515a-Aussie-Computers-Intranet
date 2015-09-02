<?php
//header("Content-type: text/xml");

require_once    '../include/common.inc.php';
require_once    '../PHPMailer_5.2.4/class.phpmailer.php';

// decide what action to take depending on the client request
$strResponseMessage = "Request Undefined";
$strResponseData    = "";
$strNewPassword     = "";

if ( isset( $_REQUEST['action'] ) )
{	

    switch ($_REQUEST['action'])
    {
	    case "forgot-password" :	// handles the forgot password request
            if ( isset($_REQUEST['email'] ) )
            {
                if( resetPasswordDB( $_REQUEST['email'], $strNewPassword, $strResponseMessage, $strResponseData ) )
                {
                    sendEmailPasswordChanged( $_REQUEST['email'], $strNewPassword, $strResponseMessage, $strResponseData );
                }

                if ( $strResponseMessage != 'Success' ) { $strResponseData .= "<br>Please contact us to resolve this matter"; }
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
function resetPasswordDB( $email,
                          &$strNewPassword,
                          &$strResponseMessage,
                          &$strResponseData )
{
    assert( isset( $email) );
    
    $strResponseMessage = "Unsuccessful";
    $strResult          = "Reset password unsuccessful";
    
	$dbConnection = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
        $strNewPassword = generateTemporaryPassword();
        $sha256Password =  hash('sha256', $strNewPassword);

		$stmtQuery      = "UPDATE icaict515a_employees SET password='{$sha256Password}' WHERE email=?";

        if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $email = scrubInput( $email, $dbConnection );
            $stmt->bind_param('s', $email );

		    $bSuccess = $stmt->execute();

            if( $bSuccess && ($stmt->affected_rows > 0) )
            { 
                $strResponseMessage = "Success"; 
                $strResult          = "Password has been reset to a temporary password.";
            }
            $stmt->close();
        }
        $dbConnection->close();
	}

    $strResponseData = $strResult;
	
    return ( $strResponseMessage == "Success" );

} // resetPasswordDB


// --------------------------------------------------------------------------------------------------------------
// sendEmailPasswordChanged
// --------------------------------------------------------------------------------------------------------------
function sendEmailPasswordChanged( $email,
                                   $strNewPassword,
                                   &$strResponseMessage,
                                   &$strResponseData )
{
    assert( isset( $email) );
    
    $bGetNameSuccess    = FALSE;
    $strResponseMessage = "Unsuccessful";
    $strName            = getMemberNameDB( $email, $bGetNameSuccess );
    $strResult          = "Sending reset email notification for new password unsuccessful.";

    if( $bGetNameSuccess )
    {

        $mail             = new PHPMailer();

        //$mail->IsSMTP();                                            // telling the class to use SMTP
        $mail->SMTPDebug    = 0;                                    // enables SMTP debug information (for testing)
        //$mail->SMTPAuth     = TRUE;                                 // enable SMTP authentication
        //$mail->SMTPSecure   = "ssl";                                // sets the prefix to the server
        //$mail->Host         = "smtp.gmail.com";                     // sets GMAIL as the SMTP server
        //$mail->Port         = 465;                                  // set the SMTP port for the GMAIL server
        $mail->IsHTML(TRUE);

        // gmail account to use to send the email
        //$mail->Username     = "fongclinton.mail.gateway@gmail.com"; 
        //$mail->Password     = "Password001";  

        //$mail->SetFrom("fongclinton.mail.gateway@gmail.com");
        //$mail->AddAddress("Sharon.Carrasco@evocca.com.au", "Sharon Carrasco");
        $mail->AddAddress( $email, $strName );
        //$mail->AddCC("info@clintonfong.com", "Clinton Fong");

        $mail->Subject  = "Find Your Feet - Password reset";
        $msg            = "Dear {$strName},<br><br>";
	    $msg           .= "As requested, your new Temporary Password for Sign-in at findyourfeet.com website is <span style='color:#78655F'>{$strNewPassword}</span><br>";
	    $msg           .= "Please change this password as soon as possible when you next Sign-in.<br><br>";
        $msg           .= "Your friendly support team at findyourfeet.com";

        $mail->Body    = $msg;

	    // Mail it
        if( $mail->Send() )
        {
            $strResponseMessage = "Success";
            $strResult          = "Email with new password has been sent to you.";
        }
        else
        {
            //$strResult         .= " Mailer error: {$mail->ErrorInfo}";
        }
    }
		
    $strResponseData .= "<br>{$strResult}";

    return ( $strResponseMessage == "Success" );

} // sendEmailPasswordChanged

// --------------------------------------------------------------------------------------------------------------
// getMemberNameDB
// --------------------------------------------------------------------------------------------------------------
function getMemberNameDB( $email, 
                          &$bSuccess )
{
    assert( isset( $email) );

    $strName        = "";
    $bSuccess       = FALSE;
	$dbConnection   = getDBConnection( $strResponseMessage ); 

    if( !$dbConnection->connect_errno )
    {
		$stmtQuery  = "SELECT firstname, lastname FROM icaict515a_employees WHERE email=?";

		if( $stmt = $dbConnection->prepare( $stmtQuery ) )
        {
            $email = scrubInput( $email, $dbConnection );
            $stmt->bind_param('s', $email);

            if( $stmt->execute() )
            {
                $stmt->bind_result( $db_firstname, $db_lastname );
		        if( $stmt->fetch() ) 
		        {
                    $strName = $db_firstname . " " . $db_lastname;
                    $bSuccess = TRUE; 
		        } 
            }
		    $stmt->close(); 	// Free resultset 
        }
        $dbConnection->close();
	}

    return $strName;

} // getMemberNameDB

// --------------------------------------------------------------------------------------------------------------
// generateTemporaryPassword
// --------------------------------------------------------------------------------------------------------------
function generateTemporaryPassword() 
{
    $alphabet       = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $alphaLength    = strlen( $alphabet ) - 1;
    $newPassword    = "";

    for ($i = 0; $i < TEMPORARY_PASSWORD_LENGTH; $i++) 
    {
        $n = rand(0, $alphaLength);
        $newPassword .= $alphabet[$n];
    }
    return $newPassword;

} // generateTemporaryPassword

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