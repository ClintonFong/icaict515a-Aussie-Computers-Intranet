<?php
    //
    // adminDepartments.php
    //
    // by Clinton Fong
    //

    session_start();

    //----------------------------------------------
    // check if logged in, otherwise throw them out.
    //----------------------------------------------
	require_once 	'include/class.loginController.inc.php';

   	$employeeID         = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	
    $objLoginController = new c_loginController();
    if( !$objLoginController->isUserLoggedIn( $employeeID ) || ($objLoginController->accessLevel < AL_ADMIN) )
    {
        header( "Location: login.php" ); // redirect to login page
    }

    //--------------------------------------------
    // general house keeping for header and menus
    //--------------------------------------------
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );


    //--------------------------------------------
   	$frmName        = (isset($_POST['frmName']))? $_POST['frmName'] : '';
   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';

   	$deptName       = (isset($_POST['deptName']))? $_POST['deptName'] : '';
   	$deptManager    = (isset($_POST['deptManager']))? $_POST['deptManager'] : '';
   	$deptBudget     = (isset($_POST['deptBudget']))? $_POST['deptBudget'] : '';

//echo ">>>>>>$deptName|||";
//echo ">>>>>>$deptManager|||";
//echo ">>>>>>$deptBudget|||";

	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();

    if( ($frmName == 'frmRegisterDept') && ($actionTaken == 'register-dept') )
    {
        $objAdminController->registerNewDepartment( $deptName, $deptManager, $deptBudget, $newDepartmentID );
    }

   
?>


<!DOCTYPE html>

<html lang='en'>
    <head>
        <meta charset='utf-8' />
        <meta name='description' content='Aussie Computer Corporation' />
        <meta name='keywords' content='Aussie Computer Corporation' />
        <meta name='author' content='Clinton Fong' />

        <title>Aussie Computer Corporation</title>

        <link rel='stylesheet' type='text/css' href='css/main.css' />
        <link rel='stylesheet' type='text/css' href='css/admin.css' />
        <!-- <link rel='stylesheet' type='text/css' href='css/commonMenu.css' /> -->

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/admin.js"></script> 

    </head>

    <body>

        <div id='wrapper'>
            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->displayHeader();
            ?>
            <!-- header -->

	
            <!-- Main Content -->
		    <div id='main-content'>

		        <div id='main-panel'>

                    <div id='left-panel'>

                        <div id='cntLeftMenu'>
                            <?php
                                $objGeneralHouseKeeping->displayAdminLeftMenu(1);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

                        <fieldset id='fldsetDepartments'>
                            <legend id='legendDepartments'>Manage Departments</legend>
                            <form name='frmAdmin' action='' target='_self' action='updateDepartment.php' method='post'>

                                <input type='hidden' name='frmName' value='admin'>
                                <input type='hidden' name='updateDeptID' value=''>


                                <br>
                                <input id='registerNewDepartment' type='button' value='Register New Department' /><span> Click on this button to register a new department</span>
                                <br>

                                <br>
                                <span>Current Departments - double click on the item you wish to update</span><br>
                                <br>
                                <div id='cntListDepartments'>
               
                                    <table id='tblDepartments' border='1px'>
                                    <tbody
                                        <tr>
                                            <th>ID</th>
                                            <th>Department<br/>Name</th>
                                            <th>Manager</th>
                                            <th>Budget</th>
                                        </tr>
                                        <?php                                       
                                            $objAdminController->displayDepartmentsForTable();
                                        ?>
                                    </tbody>
                                    </table>
                                </div>
                            </form>
                        </fldset>
                    </div>

                </div>		
            </div>
            <!-- end Main Content -->

		    <hr>
		
		    <?php 
                $objGeneralHouseKeeping->displayFooter();
            ?>
		
        </div>
    </body>
</html>

