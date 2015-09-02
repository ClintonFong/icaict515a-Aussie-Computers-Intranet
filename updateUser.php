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
   	$updateUserID   = (isset($_POST['updateUserID']))? $_POST['updateUserID'] : '';

	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();
    $objAdminController->loadUserDetails( $updateUserID );

    //var_dump( $objController );
  
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
        <link rel='stylesheet' type='text/css' href='css/updateUser.css' />

        <!-- include jQuery & JQuery UI & idleTimeout library -->
		<link type="text/css" rel="stylesheet" href="jquery-ui-1.11.0.custom/jquery-ui.theme.css"  />
		<script src="jquery-ui-1.11.0.custom/external/jquery/jquery.js" type="text/javascript"></script>
		<script src="jquery-ui-1.11.0.custom/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/store.js" type="text/javascript"></script>
		<script src="js/jquery-idleTimeout-with-countdown.js" type="text/javascript"></script>
        <script src="js/initializeIdleTimeout.js" type="text/javascript" ></script> 

        <script type='text/javascript' src='js/main.js'></script>
        <script type='text/javascript' src='js/updateUser.js'></script>


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
                    <form name='frmUpdateUser' action='' target='_self' method ='post'>
					
                        <input name='frmName' type='hidden' value='frmUpdateUser' />
                        <input name='actionTaken' type='hidden' value='none' />

                        <input name='userID' type='hidden' value='<?php echo $updateUserID; ?>' />

                        <div id='cntImage'>
                            <div id='cntImage1'>
                                <a id='close' class="tooltip">
                                    <img src='images/close_pop.png' alt='close'  />
                                    <span>
                                        <strong>Close Screen</strong><br /> 
                                        This closes the 'Update User' window that you are currently on and returns you back to the 'Admin' Listing of Users/Employees. 
                                    </span>
                                </a>
                            </div>
                            <div id='cntImage2'><img src='images/myAccount.gif' alt='my account image' /></div>
                        </div>



                        <div id='cntUpdateMember'>
                            <fieldset id='fldsetAccountUpdate'>
                                <legend id='legendAccountUpdate'>Account Update</legend>
                                <div id='cntAccountUpdateDetails'>
                                    <label class='required'>First Name:</label>                        
                                    <input name='firstname' id='firstname' type ='text' class='isValidNormalCharKey' value='<?php echo $objAdminController->firstname; ?>' /><br />
                                    <label class='required'>Last Name:</label>                        
                                    <input name='lastname' id='lastname' type ='text' class='isValidNormalCharKey' value='<?php echo $objAdminController->lastname; ?>' /><br />
                                    <label class='required' >Sign-in Email:</label>                        
                                    <input name='signinEmail' id='signinEmail' type ='text' value ='<?php echo $objAdminController->email; ?>' /><br />
                                    <label class='required' >Phone:</label>                        
                                    <input name='phone' id='phone' type ='text' value='<?php echo $objAdminController->phone; ?>' class='isPhoneExtKey' /><br /> 
                                    <label class='required' >Access Level:</label>                        
                                    <select name='accessLevel' id='accessLevel'>
                                        <?php 
                                            $objAdminController->displaySelectOptionsAccessLevels();
                                        ?>
                                    </select><br />
                                    <label class='required' >Department:</label>                        
                                    <select name='department' id='department'>
                                        <?php 
                                            $objAdminController->displaySelectOptionsDepartments();
                                        ?>
                                    </select><br />

                                    <input name='btnAccountUpdate' id='btnAccountUpdate' type ='button' value='Update &#9658;' /><br />
                                    <div id='ajaxUpdateAccountMessageResponse'></div>
                                </div>
                            </fieldset>

                            <fieldset id='fldsetPasswordUpdate'>
                                <legend id='legendPasswordUpdate'>Password Update</legend>
                                <div id='cntPasswordUpdateDetails'>
                                    <label class='required' >Old Password:</label>                        
                                    <input name='oldPassword' id='oldPassword' type ='password' value='' /><br />
                                    <br>
                                    <label class='required' >New Password:</label>                        
                                    <input name='newPassword' id='newPassword' type ='password' value='' /><br />
                                    <label class='required'>Confirm New Password:</label>                        
                                    <input name='confirmPassword' id='confirmPassword' type ='password' value='' /><br />
                                    <input name='btnPasswordUpdate' id='btnPasswordUpdate' type ='button' value='Update &#9658;' /><br />
                                    <div id='ajaxUpdatePasswordMessageResponse'></div>
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

