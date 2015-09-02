<?php
require_once    'class.basicDB.inc.php';

class c_adminController extends c_basicDB
{
    public $userID          = '-1';
    public $firstname       = '';
    public $lastname        = '';
    public $email           = '';
    public $phone           = '';
    public $accessLevel     = '0';
    public $password        = '';
    

    public $deptID          = '-1'; // used for both user & department register/updates
    public $deptName        = '';
    public $deptManagerID   = '';
    public $deptBudget      = '';


    
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
    // registerNewDepartment
    //
    // Description: register new department
	//---------------------------------------------------------------------------------------------
	function registerNewDepartment( $deptName,
                                    $deptManagerID,
                                    $deptBudget,
                                    &$deptID )
	{
        //echo "In registerNewDepartment";

        assert( isset( $this->dbConnection) );

        $bRegisterSuccessful    = FALSE;

        if( !$this->dbConnection->connect_errno )
        {
            $bUniqueName    = FALSE;
            $deptName       = $this->scrubInput( $deptName );
    	    $stmtQuery      = "SELECT count(*) as num_members FROM icaict515a_departments WHERE name='{$deptName}'";

     	    if( $resultQuery = $this->dbConnection->query( $stmtQuery ) )
            {
		        $row = $resultQuery->fetch_array( MYSQL_ASSOC );
                $bUniqueName = ($row['num_members'] == 0);
                $resultQuery->close();
            }

            // proceed if unique email
            if( $bUniqueName )
            {
		        $stmtQuery      = "INSERT INTO icaict515a_departments (name, manager_id, budget) VALUES ";
                $stmtQuery     .= "(?, ?, ?) ";


                if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
                {
                    $deptName       = $this->scrubInput( $deptName );
                    $deptManagerID  = $this->scrubInput( $deptManagerID );
                    $deptBudget     = $this->scrubInput( $deptBudget );

                    $stmt->bind_param("sss", $deptName, $deptManagerID, $deptBudget);

		            $bSuccess = $stmt->execute();

                    $deptID = -1;
                    if( $bSuccess && ($this->dbConnection->affected_rows > 0) )
                    { 
                        $deptID = $this->dbConnection->insert_id;
                        $bRegisterSuccessful = TRUE; 
                    }
                }
            }
        }    

        // store attributes
        //

        $this->deptName         = $deptName;
        $this->deptManagerID    = $deptManagerID;
        $this->deptBudget       = $deptBudget;
        $this->deptID           = $deptID;

        return $bRegisterSuccessful;

	} // registerNewDepartment


    //---------------------------------------------------------------------------------------------
    // loadDeptDetails
    //
    // Description: retrieves the users details
	//---------------------------------------------------------------------------------------------
	function loadDeptDetails( $deptID )
	{
        //echo "In loadDeptDetails";
        assert( isset( $this->dbConnection ) );

		$stmtQuery  = "SELECT name, icaict515a_departments.manager_id, budget";
        $stmtQuery .= " FROM icaict515a_departments LEFT JOIN icaict515a_employees ON icaict515a_departments.manager_id = icaict515a_employees.employee_id ";
        $stmtQuery .= " WHERE icaict515a_departments.department_id=?";

        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $deptID = $this->scrubInput( $deptID );
            $stmt->bind_param( "i", $deptID ); 

		    if( $stmt->execute() )
            {
                $stmt->bind_result( $db_name, $db_managerID, $db_budget );

                if( $stmt->fetch() )
                {
                    $this->deptID           = $deptID;
                    $this->deptName         = $db_name;
                    $this->deptManagerID    = $db_managerID;
			        $this->deptBudget       = $db_budget;
                }
            }
        }

	} // loadDeptDetails
    


    //---------------------------------------------------------------------------------------------
    // displayDepartmentsForTable
    //
    // Description: displays departments in a table
	//---------------------------------------------------------------------------------------------
	function displayDepartmentsForTable()
    {
		$strItemsForWebpage = "";
		$stmtQuery  = "SELECT icaict515a_departments.department_id, name, icaict515a_employees.firstname, icaict515a_employees.lastname, budget";
        $stmtQuery .= " FROM icaict515a_departments LEFT JOIN icaict515a_employees ON icaict515a_departments.manager_id = icaict515a_employees.employee_id ";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $nameManager         = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);
                $formatBudget        = number_format( $row['budget'], 2, '.', '' );

                $strItemsForWebpage .= "<tr id='{$row['department_id']}'>\n
                                            <td> {$row['department_id']} </td>\n
			                                <td> {$row['name']} </td>\n
			                                <td> {$nameManager} </td>\n
			                                <td> {$formatBudget} </td>\n
                                        </tr>\n
                                          ";
                                
		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

		echo $strItemsForWebpage;


    } // displayDepartmentsForTable


    //---------------------------------------------------------------------------------------------
    // displaySelectOptionsDeptManagers
    //
    // Description: display an option list of managers
    //---------------------------------------------------------------------------------------------
    function displaySelectOptionsDeptManagers()
    {
		$strDisplay = "";
		$stmtQuery  = "SELECT employee_id, firstname, lastname FROM icaict515a_employees";
        $stmtQuery .= " WHERE access_level='" . AL_MANAGER ."'";
        $stmtQuery .= " ORDER BY lastname DESC";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $name        = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);
                $strSelected = ($this->deptManagerID == $row['employee_id'])? 'selected=selected'   : '';
                $strDisplay .= "<option value='{$row['employee_id']}' {$strSelected} >{$name}</option>\n";
		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

		echo $strDisplay;


    } // displaySelectOptionsDeptManagers

    //---------------------------------------------------------------------------------------------
    // displaySelectOptionsDepartments
    //
    // Description: display an option list of departments
    //---------------------------------------------------------------------------------------------
    function displaySelectOptionsDepartments()
    {
		$strDisplay = "";
		$stmtQuery  = "SELECT department_id, name FROM icaict515a_departments";
        $stmtQuery .= " ORDER BY name DESC";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $strSelected = ($this->deptID == $row['department_id'])? 'selected=selected'   : '';
                $strDisplay .= "<option value='{$row['department_id']}' {$strSelected}>{$row['name']}</option>\n";

		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

		echo $strDisplay;

    } // displaySelectOptionsDepartments



    //---------------------------------------------------------------------------------------------
    // registerNewMember
    //
    // Description: register new member
	//---------------------------------------------------------------------------------------------
	function registerNewMember( $firstname,
                                $lastname,
                                $email,
                                $phone,
                                $accessLevel,
                                $password,
                                $deptID,
                                &$userID )
	{
        //echo "In registerNewMember";

        assert( isset( $this->dbConnection) );

        $bRegisterSuccessful    = FALSE;

        if( !$this->dbConnection->connect_errno )
        {
            $bUniqueEmail   = FALSE;
            $email          = $this->scrubInput( $email );
    	    $stmtQuery      = "SELECT count(*) as num_members FROM icaict515a_employees WHERE email='{$email}'";

     	    if( $resultQuery = $this->dbConnection->query( $stmtQuery ) )
            {
		        $row = $resultQuery->fetch_array( MYSQL_ASSOC );
                $bUniqueEmail = ($row['num_members'] == 0);
                $resultQuery->close();
            }

            // proceed if unique email
            if( $bUniqueEmail )
            {
		        $stmtQuery      = "INSERT INTO icaict515a_employees (firstname, lastname, email, phone, access_level, password, department_id, logged_in ) VALUES ";
                $stmtQuery     .= "(?, ?, ?, ?, ?, ?, ?, '" . LOGGED_IN . "') ";


                if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
                {
                    $firstname      = $this->scrubInput( $firstname );
                    $lastname       = $this->scrubInput( $lastname );
                    $phone          = $this->scrubInput( $phone );
                    $accessLevel    = $this->scrubInput( $accessLevel );
                    $sha256Password = hash('sha256', $password);
                    $deptID         = $this->scrubInput( $deptID );

                    $stmt->bind_param( "sssssss", $firstname, $lastname, $email, $phone, $accessLevel, $sha256Password, $deptID );

		            $bSuccess = $stmt->execute();

                    $userID = -1;
                    if( $bSuccess && ($this->dbConnection->affected_rows > 0) )
                    { 
                        $userID = $this->dbConnection->insert_id;
                        $bRegisterSuccessful = TRUE; 
                    }
                }
            }
        }    

        // store attributes
        //

        $this->firstname    = $firstname;
        $this->lastname     = $lastname;
        $this->email        = $email;
        $this->phone        = $phone;
        $this->password     = $sha256Password;
        $this->userID       = $userID;

        return $bRegisterSuccessful;

	} // registerNewMember

    //---------------------------------------------------------------------------------------------
    // loadUserDetails
    //
    // Description: retrieves the users details for update
	//---------------------------------------------------------------------------------------------
	function loadUserDetails( $userID )
	{
        //echo "loadMemberDetails";
        assert( isset( $this->dbConnection ) );

		$stmtQuery  = "SELECT department_id, firstname, lastname, email, phone, access_level FROM icaict515a_employees";
        $stmtQuery .= " WHERE employee_id=?";
        
        if( $stmt = $this->dbConnection->prepare( $stmtQuery ) )
        {
            $userID = $this->scrubInput( $userID );
            $stmt->bind_param( "i", $userID ); 

		    if( $stmt->execute() )
            {
                $stmt->bind_result( $db_deptID, $db_firstname, $db_lastname, $db_email, $db_phone, $db_access_level );

                if( $stmt->fetch() )
                {
                    $this->deptID       = $db_deptID;
			        $this->firstname    = $db_firstname;
			        $this->lastname     = $db_lastname;
			        $this->email        = $db_email;
			        $this->phone        = $db_phone;
			        $this->accessLevel  = $db_access_level;
                }
            }

        }

	} // loadUserDetails
    

    //---------------------------------------------------------------------------------------------
    // displayUsersForTable
    //
    // Description: displays users in a table
	//---------------------------------------------------------------------------------------------
	function displayUsersForTable()
    {
		$strItemsForWebpage = "";
		$stmtQuery  = "SELECT employee_id, firstname, lastname, access_level, email FROM icaict515a_employees";
        $stmtQuery .= " ORDER BY lastname DESC";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $name                = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);

                $readableAccessLevel = $this->getReadableAccessLevel( $row['access_level'] );

                $strItemsForWebpage .= "<tr id='{$row['employee_id']}'>\n
                                            <td> {$row['employee_id']} </td>\n
			                                <td> {$name} </td>\n
			                                <td> {$readableAccessLevel} </td>\n
                                            <td> {$row['email']}</td>\n
                                        </tr>\n
                                          ";
                                
		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

		echo $strItemsForWebpage;


    } // displayUsersForTable

    //---------------------------------------------------------------------------------------------
    // getReadableAccessLevel
    //
    // Description: returns the access level readable & meaningful to the user
	//---------------------------------------------------------------------------------------------
	function getReadableAccessLevel( $nAccessLevel )
    {
        switch( $nAccessLevel )
        {
            case AL_EMPLOYEE:           return 'Employee';
            case AL_PROCUREMENT_MEMBER: return 'Procurement';
            case AL_MANAGER:            return 'Manager';
            case AL_ADMIN:              return 'Administrator';
        }
        return 'Unknown Level';
        
    } // getReadableAccessLevel


    //---------------------------------------------------------------------------------------------
    // displaySelectOptionsAccessLevels
    //
    // Description: display a list of access levels
    //---------------------------------------------------------------------------------------------
    function displaySelectOptionsAccessLevels()
    {
        $sAdminSelected         = ($this->accessLevel == AL_ADMIN)? 'selected=selected'   : '';
        $sManagerSelected       = ($this->accessLevel == AL_MANAGER)? 'selected=selected'   : '';
        $sEmployeeSelected      = ($this->accessLevel == AL_EMPLOYEE)? 'selected=selected'   : '';
        $sProcurementSelected   = ($this->accessLevel == AL_PROCUREMENT_MEMBER)? 'selected=selected'   : '';

        $strDisplay = "
                        <option value='9' {$sAdminSelected}>Admin</option> \n
                        <option value='5' {$sManagerSelected}>Manager</option> \n
                        <option value='0' {$sEmployeeSelected}>Employee</option> \n
                        <option value='1' $sProcurementSelected>Procurement Member</option> \n
                      ";

        echo $strDisplay;

    } // displaySelectOptionsAccessLevels




} // class c_adminController

?>
