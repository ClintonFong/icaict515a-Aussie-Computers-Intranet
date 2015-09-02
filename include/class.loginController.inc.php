<?php

require_once    'class.basicDB.inc.php';

class c_loginController extends c_basicDB
{

    public $userID          = -1;
    public $deptID          = -1;
    public $firstname       = ''; 
    public $lastname        = ''; 
    public $accessLevel     = '';
    public $email           = '';

    public $managerID           = -1;
    public $mangerFirstname     = ''; 
    public $managerLastname     = ''; 
    public $managerAccessLevel  = '';
    public $managerEmail        = '';



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
    // isLoginValid
    //
    // Description: Validates login details
	//---------------------------------------------------------------------------------------------
	function isLoginValid( $email,
                           $password )
	{
        assert( isset( $this->dbConnection ) );

        $bLoginSuccessful = FALSE;

        $whereField = (is_numeric($email))? 'employee_id' : 'email';

		// $stmtQuery  = "SELECT member_id, firstname, password FROM members WHERE email='{$loginEmail}'";
        // usage of prepare and bind is more secure rather than straight query
        
        $stmtQuery  = "SELECT employee_id, department_id, firstname, lastname, password, access_level, email";
        $stmtQuery .= " FROM icaict515a_employees";
        $stmtQuery .= " WHERE {$whereField}=?";

        if ($stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $bindParamType = (is_numeric($email))? 'i' : 's';
            $email = $this->scrubInput( $email );
            $stmt->bind_param( $bindParamType, $email );

		    if( $bSuccess = $stmt->execute())
            {
                $stmt->bind_result( $db_userID, $db_deptID, $db_firstname, $lastname, $db_password, $db_accessLevel, $db_email );

		        if( $stmt->fetch() ) 
		        {
//                    echo $db_member_id;
//                    echo "password={$db_password}: db_password={$db_password}";
                    $sha256Password =  hash('sha256', $password);

			        if( $db_password == $sha256Password )
                    {
                        $bLoginSuccessful = TRUE;

                        $this->userID       = $db_userID;
                        $this->deptID       = $db_deptID;
                        $this->firstname    = $db_firstname;
                        $this->lastname     = $db_lastname;
                        $this->accessLevel  = $db_accessLevel;
                        $this->email        = $db_email;
                    }

		        } 
            }
	        $stmt->close(); 	// Free resultset 
        }

    	return $bLoginSuccessful;

	} // isLoginValid


    //---------------------------------------------------------------------------------------------
    // flagLoggedIn
    //
    // Description: Flags the database that this user has successfully logged in 
	//---------------------------------------------------------------------------------------------
	function flagLoggedIn( $userID )
	{
//        echo "In flagLoggedIn";
        assert( isset( $this->dbConnection) );

        $bSuccess   = FALSE;
		$stmtQuery  = "UPDATE icaict515a_employees SET logged_in='" . LOGGED_IN . "' WHERE employee_id=?";

        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $userID = $this->scrubInput( $userID );
            $stmt->bind_param("i", $userID );
            $bSuccess = $stmt->execute();
   	        $stmt->close(); 	// Free resultset 
        }
		return $bSuccess;
    
	} // flagLoggedIn

    //---------------------------------------------------------------------------------------------
    // flagLoggedOut
    //
    // Description: Flags the database that this user has successfully logged out
	//---------------------------------------------------------------------------------------------
	function flagLoggedOut( $userID )
	{
        //echo "In flagLoggedOut";
        assert( isset( $this->dbConnection) );

        $bSuccess   = FALSE;
		$stmtQuery  = "UPDATE icaict515a_employees SET logged_in='" . LOGGED_OUT . "' WHERE employee_id=?";

        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $userID = $this->scrubInput( $userID );
            $stmt->bind_param("i", $userID );
            $bSuccess = $stmt->execute();
	        $stmt->close(); 	// Free resultset 
        }
		return $bSuccess;

	} // flagLoggedOut

   	//---------------------------------------------------------------------------------------------
    // isUserLoggedIn
    //
    // Description: returns true if the member is logged in, otherwise false
	//---------------------------------------------------------------------------------------------
	function isUserLoggedIn( $userID )
	{
        assert( isset( $this->dbConnection ) );

        $bUserLoggedIn = FALSE;

		$stmtQuery  = "SELECT employee_id, department_id, firstname, lastname, logged_in, access_level";
        $stmtQuery .= " FROM icaict515a_employees WHERE employee_id=?";

        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $userID = $this->scrubInput( $userID );
            $stmt->bind_param("i", $userID );

		    if( $bSuccess = $stmt->execute())
            {
                $stmt->bind_result( $db_userID, $db_deptID, $db_firstname, $db_lastname, $db_loggedIn, $db_accessLevel );

		        if ( $stmt->fetch() ) 
		        {
			        if ( $db_loggedIn == LOGGED_IN )
                    {
                        $bUserLoggedIn      = TRUE;
                        $this->userID       = $db_userID;
                        $this->deptID       = $db_deptID;
                        $this->firstname    = $db_firstname;
                        $this->lastname     = $db_lastname;
                        $this->accessLevel  = $db_accessLevel;
                    }

		        } 
            }
	        $stmt->close(); 	// Free resultset 
        }
    	return $bUserLoggedIn;

	} // isUserLoggedIn

   	//---------------------------------------------------------------------------------------------
    // getUsersManager
    //
    // Description: returns the manager of the current user (user must have already logged in)
	//---------------------------------------------------------------------------------------------
	function getUsersManager()
	{
        assert( isset( $this->dbConnection ) );

        $bUserLoggedIn = FALSE;

        // reset values
        $this->managerID        = -1;
        $this->managerFirstname =  $this->managerLastname = $this->managerAccessLevel  = '';

		$stmtQuery  = "SELECT employee_id, firstname, lastname, access_level, email";
        $stmtQuery .= " FROM icaict515a_departments, icaict515a_employees";
        $stmtQuery .= " WHERE icaict515a_departments.manager_id = icaict515a_employees.employee_id";
        $stmtQuery .= " AND icaict515a_departments.department_id=?";


        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $deptID = $this->scrubInput( $this->deptID );
            $stmt->bind_param("i", $deptID );

		    if( $bSuccess = $stmt->execute())
            {
                $stmt->bind_result( $db_managerID, $db_firstname, $lastname, $db_accessLevel, $db_email );

		        if ( $stmt->fetch() ) 
		        {
                    $this->managerID            = $db_managerID;
                    $this->managerFirstname     = $db_firstname;
                    $this->managerLastname      = $db_lastname;
                    $this->managerAccessLevel   = $db_accessLevel;
                    $this->managerEmail         = $db_email;
		        } 
            }
	        $stmt->close(); 	// Free resultset 
        }
    	return $this->managerID;

	} // getUsersManager

	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // debugging tools
	//---------------------------------------------------------------------------------------------
    function __displayAttributes()
    {
        echo "<br>
            userID = {$this->userID}<br>
            deptID = {$this->deptID}<br>
            firstname = {$this->firstname}<br>
            lastname = {$this->lastname}<br>
            accessLevel = {$this->accessLevel}<br>
            <br>
            managerID = {$this->managerID}<br>
            managerFirstName = {$this->mangerFirstname}<br>
            managerLastName = {$this->managerLastname}<br>
            managerAccessLevel = {$this->managerAccessLevel}<br>
            <br>
            ";

    } // __displayAttributes
    
} // class c_loginController

?>
