<?php
    use DebugBar\StandardDebugBar;

	session_start();

    //--------------------------------------------
    // check if logged in, otherwise throw them out.
    //--------------------------------------------
	require_once 	'include/class.loginController.inc.php';

   	$employeeID         = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	
    $objLoginController = new c_loginController();
    if( !$objLoginController->isUserLoggedIn( $employeeID ) || ($objLoginController->accessLevel != AL_ADMIN) )
    {
        header( "Location: login.php" ); // redirect to login page
    }

    //--------------------------------------------
    // general house keeping for header and menus
    //--------------------------------------------
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );


    //--------------------------------------------
   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';
   	$updateDeptID   = (isset($_POST['updateDeptID']))? $_POST['updateDeptID'] : '';

	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();
    $objAdminController->loadDeptDetails( $updateDeptID );


    //var_dump( $objAdminController );
  
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
        <link rel='stylesheet' type='text/css' href='css/updateDepartment.css' />

        <!-- include jQuery & JQuery UI & idleTimeout library -->
		<link type="text/css" rel="stylesheet" href="jquery-ui-1.11.0.custom/jquery-ui.theme.css"  />
		<script src="jquery-ui-1.11.0.custom/external/jquery/jquery.js" type="text/javascript"></script>
		<script src="jquery-ui-1.11.0.custom/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/store.js" type="text/javascript"></script>
		<script src="js/jquery-idleTimeout-with-countdown.js" type="text/javascript"></script>
        <script src="js/initializeIdleTimeout.js" type="text/javascript" ></script> 

        <script type='text/javascript' src='js/main.js'></script>
        <script type='text/javascript' src='js/updateDepartment.js'></script>


    </head>

    <body>

        <div id='wrapper'>
            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->arrHeaderMenuItems['Admin Panel']  = 'admin.php';
                $objGeneralHouseKeeping->displayHeader();
            ?>
            <!-- header -->

	
            <!-- Main Content -->
		    <div id='main-content'>
		        <div id='main-panel'>
                    <form name='frmUpdateDept' action='' target='_self' method ='post'>
					
                        <input name='frmName' type='hidden' value='frmUpdateDept' />
                        <input name='actionTaken' type='hidden' value='none' />

                        <input name='deptID' type='hidden' value='<?php echo $updateDeptID; ?>' />

                        <div id='cntImage'>
                            <div id='cntImage1'>
                                <a id='close' class="tooltip">
                                    <img src='images/close_pop.png' alt='close'  />
                                    <span>
                                        <strong>Close Screen</strong><br /> 
                                        This closes the 'Update User' window that you are currently on and returns you back to the 'Admin' Listing of Departments. 
                                    </span>
                                </a>
                            </div>
                            <div id='cntImage2'><img src='images/myAccount.gif' alt='my department image' /></div>
                        </div>



                        <div id='cntUpdateDept'>
                            <fieldset id='fldsetDeptUpdate'>
                                <legend id='legendDeptUpdate'>Department Update</legend>
                                <div id='cntDeptUpdateDetails'>

                                    <label class='required'>Department Name:</label>                        
                                    <input name='deptName' id='deptName' class='isValidNormalCharKey' type ='text' value='<?php echo $objAdminController->deptName; ?>' /><br />
                                    
                                    <label>Department Manager:</label>                        
                                    <select name='deptManager' id='deptManager'>
                                        <option value='0'>&nbsp;</option>
                                        <?php
                                            $objAdminController->displaySelectOptionsDeptManagers();
                                        ?>
                                    </select><br />

                                    <label class='required'>Department Budget:</label>                        
                                    <input name='deptBudget' id='deptBudget' type ='text' value='<?php echo $objAdminController->deptBudget; ?>' onkeypress='return isMoneyKey(event)' /><br />

                                    <input name='btnDeptUpdate' id='btnDeptUpdate' type ='button' value='Update &#9658;' /><br />
                                    <div id='ajaxUpdateDeptMessageResponse'></div>
                                </div>
                            </fieldset>

                        </div>

                       
                    </form>

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

