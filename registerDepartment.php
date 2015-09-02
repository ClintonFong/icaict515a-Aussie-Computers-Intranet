<?php
    use DebugBar\StandardDebugBar;

	session_start();

    //--------------------------------------------
    // check if logged in, otherwise throw them out.
    //--------------------------------------------
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
	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();


   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';
   	$updateUserID   = (isset($_POST['updateUserID']))? $_POST['updateUserID'] : '';
  
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
        <link rel='stylesheet' type='text/css' href='css/registerDepartment.css' />

        <!-- include jQuery & JQuery UI & idleTimeout library -->
		<link type="text/css" rel="stylesheet" href="jquery-ui-1.11.0.custom/jquery-ui.theme.css"  />
		<script src="jquery-ui-1.11.0.custom/external/jquery/jquery.js" type="text/javascript"></script>
		<script src="jquery-ui-1.11.0.custom/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/store.js" type="text/javascript"></script>
		<script src="js/jquery-idleTimeout-with-countdown.js" type="text/javascript"></script>
        <script src="js/initializeIdleTimeout.js" type="text/javascript" ></script> 

        <script type='text/javascript' src='js/main.js'></script>
        <script type="text/javascript" src="js/registerDepartment.js"></script> 

    </head>

    <body>

        <div id='wrapper'>

            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->arrHeaderMenuItems['Admin Panel']  = 'admin.php';
                $objGeneralHouseKeeping->displayHeader();
            ?>
	        <!-- end header -->

            <!-- Main Content -->
		    <div id='main-content'>

		        <div id='main-panel'>

                    <form name='frmRegisterDept' action='adminDepartment.php' target='_self' method ='post'>

                        <input name='frmName' type='hidden' value='frmRegisterDept' />
                        <input name='actionTaken' type='hidden' value='register-dept' />

                        <div id='cntImage'>
                            <div id='cntImage2'><img src='images/pushbutton.jpg' alt='register image2' /></div>
                        </div>

                        <div id='cntRegisterDept'>
                            <fieldset id='fldsetDeptRegister'>
                                <legend id='legendDeptRegister'>Department Register</legend>
                                <div id='cntDeptRegisterDetails'>

                                    <label class='required'>Department Name:</label>                        
                                    <input name='deptName' id='deptName' class='isValidNormalCharKey' type ='text' value='<?php echo $objAdminController->deptName; ?>' /><br />
                                    
                                    <label>Department Manager:</label>                        
                                    <select name='deptManager' id='deptManager'>
                                        <option value='0'>&nbsp;</option>
                                        <?php
                                            echo $objAdminController->displaySelectOptionsDeptManagers();
                                        ?>
                                    </select><br />

                                    <label class='required'>Department Budget:</label>                        
                                    <input name='deptBudget' id='deptBudget' type ='text' value='<?php echo $objAdminController->deptBudget; ?>' onkeypress='return isMoneyKey(event)' /><br />
                                   
                                    <input name='btnCancel' id='btnCancel' type ='button' value='Cancel' />
                                    <input name='btnDeptRegister' id='btnDeptRegister' type ='button' value='Register &#9658;' /><br />
                                    <div id='ajaxUpdateDeptMessageResponse'></div>
                                </div>
                            </fieldset>

                        </div>

                       
                    </form>


                </div>		
            </div>
		    <hr>
		
		    <?php 
                $objGeneralHouseKeeping->displayFooter();
            ?>
		
        </div>
    </body>
</html>

