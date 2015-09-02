<?php

require_once    'PHPMailer_5.2.4/class.phpmailer.php';
require_once    'class.basicDB.inc.php';

//----------
class structMessageDetails
{
    public $fromEmployeeID  = -1;   // although this id may not be used, we include it here for completeness and future possibilities
    public $fromFirstname   = '';
    public $fromLastname    = '';
    public $fromEmail       = '';   // although this email may not be used, we include it here for future possibilities

    public $toEmployeeID    = -1;
    public $toFirstname     = '';
    public $toLastname      = '';
    public $toEmail         = '';

    public $message         = '';

} // structMessageDetails

//---------------------------------------------------------------------------------------------
class c_mailController extends c_basicDB
{
    public $fromEmployeeID          = '';
    public $fromEmployeeFirstname   = '';
    public $fromEmployeeLastname    = '';

    public $fromManagerID           = '';
    public $fromMangerFirstname     = '';
    public $fromManagerLastname     = '';

    public $orderStatus             = '';

	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct()
	{
		parent::__construct();
		
		
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct

	//---------------------------------------------------------------------------------------------
    // notifyManager
    //
    // Description: notifies the manager
    // Requirements: member variables $fromEmployeeFirstname, and $fromEmployeeLastname has to be populated
	//---------------------------------------------------------------------------------------------
	function notifyManager( $orderID ) 
	{
        assert( $this->fromEmployeeFirstname != '' );
        assert( $this->fromEmployeeLastname  != '' );

        $messageDetails = new structMessageDetails();

        $messageDetails->fromEmployeeID         = $this->fromEmployeeID;
        $messageDetails->fromFirstname          = $this->fromEmployeeFirstname;
        $messageDetails->fromLastname           = $this->fromEmployeeLastname;

        if( $this->getToEmployeeDetailsDB( $orderID, AL_MANAGER, $messageDetails ) )      // get manager's contact details
        {
            // create message to manager
            //
            $messageDetails->message  = "Dear {$messageDetails->toFirstname}, <br><br>";
            $messageDetails->message .= "You have received a purchase order from {$messageDetails->fromFirstname} {$messageDetails->fromLastname} that requires your authorization.<br><br>";
            $messageDetails->message .= "From your friendly notifying agent.<br><br>";
            
            $this->sendemail( $messageDetails );
        }

	} // notifyManager



	//---------------------------------------------------------------------------------------------
    // notifyEmployee
    //
    // Description: notifies the employee
    // Requirements: member variables $fromManagerFirstname, and $fromManagerLastname has to be populated
	//---------------------------------------------------------------------------------------------
	function notifyEmployee( $orderID,
                             $orderStatus ) 
	{
        assert( $this->fromManagerFirstname != '' );
        assert( $this->fromManagerLastname  != '' );

        $strOrderStatus = ($orderStatus == OS_APPROVED)? 'APPROVED' : 'REJECTED';

        $messageDetails = new structMessageDetails();

        $messageDetails->fromEmployeeID         = $this->fromManagerID;
        $messageDetails->fromFirstname          = $this->fromManagerFirstname;
        $messageDetails->fromLastname           = $this->fromManagerLastname;

        if( $this->getToEmployeeDetailsDB( $orderID, AL_EMPLOYEE, $messageDetails ) )         // get employee's contact details
        {
                echo $messageDetails->toEmployeeID;
                echo $messageDetails->toFirstname;
                echo $messageDetails->toLastname;
                echo $messageDetails->toEmail;


            // create message to manager
            //
            $messageDetails->message  = "Dear {$messageDetails->toFirstname},<br><br>";
            $messageDetails->message .= "Your purchase order ID: {$orderID} has been {$strOrderStatus} by manager {$messageDetails->fromFirstname} {$messageDetails->fromLastname}.<br><br>";
            $messageDetails->message .= "From your friendly notifying agent.<br><br>";
            
            $this->sendemail( $messageDetails );
        }

	} // notifyEmployee


	//---------------------------------------------------------------------------------------------
    // notifyProcurementTeam
    //
    // Description: notifies the procurement team
    // Requirements: member variables $fromManagerFirstname, and $fromManagerLastname has to be populated
	//---------------------------------------------------------------------------------------------
	function notifyProcurementTeam( $orderID )
	{
        assert( $this->fromManagerFirstname != '' );
        assert( $this->fromManagerLastname  != '' );

        $strOrderStatus = ($orderStatus == 'OS_APPROVED')? 'APPROVED' : 'REJECTED';

        $messageDetails = new structMessageDetails();

        $messageDetails->fromEmployeeID         = $this->fromManagerID;
        $messageDetails->fromFirstname          = $this->fromManagerFirstname;
        $messageDetails->fromLastname           = $this->fromManagerLastname;

        // create message to procurement team
        //
        $messageDetails->message  = "Dear Procurement Team,<br><br>";
        $messageDetails->message .= "The purchase order ID: {$orderID} has been approved by manager {$messageDetails->fromFirstname} {$messageDetails->fromLastname} and is now ready to be processed.<br><br>";
        $messageDetails->message .= "From your friendly notifying agent.<br><br>";
            
        $messageDetails->toFirstname    = "Procurement";
        $messageDetails->toLastname     = "Team";
        $messageDetails->toEmail        = PROCUREMENT_TEAM_EMAIL;

        $this->sendemail( $messageDetails );

	} // notifyProcurementTeam


	//---------------------------------------------------------------------------------------------
    // getEmployeeDetailsDB
	//---------------------------------------------------------------------------------------------
    function getToEmployeeDetailsDB( $orderID,
                                     $accessLevel,
                                     $messageDetails )
    {
        assert( isset( $this->dbConnection ) );

        $sToWhoID = ($accessLevel == AL_MANAGER)? 'manager_id' : 'employee_id';

        $bSuccess = FALSE;

		$stmtQuery  = "SELECT icaict515a_employees.employee_id,  firstname, lastname, email";
        $stmtQuery .= " FROM icaict515a_orders ";
        $stmtQuery .= " LEFT JOIN icaict515a_employees ON icaict515a_employees.employee_id = icaict515a_orders.{$sToWhoID}";
        $stmtQuery .= " WHERE order_id = '{$orderID}'";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    if ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $bSuccess = TRUE;

                $messageDetails->toEmployeeID   = $row['employee_id'];
                $messageDetails->toFirstname    = $row['firstname'];
                $messageDetails->toLastname     = $row['lastname'];
                $messageDetails->toEmail        = $row['email'];

		    } // if
        	
		    $resultQuery->close(); 	// Free resultset 
        }
        return $bSuccess;

    } // getEmployeeDetailsDB

	//---------------------------------------------------------------------------------------------
    // sendEmail
    //
    // Description: sends the actual email to the relevant party with PHPMailer
	//---------------------------------------------------------------------------------------------
    function sendEmail( $messageDetails )
    {
        assert( isset( $email) );

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

        $mail->AddAddress( $messageDetails->toEmail, "{$messageDetails->toFirstname} {$messageDetails->toLastname}" );

        //$mail->AddCC("info@clintonfong.com", "Clinton Fong");

       // echo $messageDetails->toEmail;
       // echo $messageDetails->message;


        $mail->Subject  = "Order Notification";
        $mail->Body     = $messageDetails->message;

	    // Mail it
        return $mail->Send(); 


    } // sendEmail

} // class c_mailController

?>
