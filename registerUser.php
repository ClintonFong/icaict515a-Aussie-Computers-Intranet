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
	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();

   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';
   	$updateUserID   = (isset($_POST['updateUserID']))? $_POST['updateUserID'] : '';





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
        <link rel='stylesheet' type='text/css' href='css/registerUser.css' />

        <!-- include jQuery & JQuery UI & idleTimeout library -->
		<link type="text/css" rel="stylesheet" href="jquery-ui-1.11.0.custom/jquery-ui.theme.css"  />
		<script src="jquery-ui-1.11.0.custom/external/jquery/jquery.js" type="text/javascript"></script>
		<script src="jquery-ui-1.11.0.custom/jquery-ui.min.js" type="text/javascript"></script>
		<script src="js/store.js" type="text/javascript"></script>
		<script src="js/jquery-idleTimeout-with-countdown.js" type="text/javascript"></script>
        <script src="js/initializeIdleTimeout.js" type="text/javascript" ></script> 

        <script type='text/javascript' src='js/main.js'></script>
        <script type="text/javascript" src="js/registerUser.js"></script> 

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

                    <form name='frmRegisterUser' action='adminUsers.php' target='_self' method ='post'>

                        <input name='frmName' type='hidden' value='frmRegisterUser' />
                        <input name='actionTaken' type='hidden' value='register-user' />

                        <div id='cntImage'>
                            <div id='cntImage2'><img src='images/pushbutton.jpg' alt='register image2' /></div>
                        </div>

                        <div id='cntRegisterMember'>
                            <fieldset id='fldsetAccountRegister'>
                                <legend id='legendAccountRegister'>Account Register</legend>

                                <div id='cntAccountRegisterDetails'>
                                    <label class='required'>First Name:</label>                        
                                    <input name='firstname' id='firstname' class='isValidNormalCharKey' type ='text' value='<?php echo $objAdminController->firstname; ?>' /><br />

                                    <label class='required'>Last Name:</label>                        
                                    <input name='lastname' id='lastname' class='isValidNormalCharKey' type ='text' value='<?php echo $objAdminController->lastname; ?>' /><br />

                                    <label class='required' >Sign-in Email:</label>                        
                                    <input name='email' id='email' type ='text' value ='<?php echo $objAdminController->email; ?>' /><br />

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
                                    <br />

                                    <label class='required' >Password:</label>                        
                                    <input name='password' id='password' type ='password' value='' /><br />
                                    <label class='required'>Confirm Password:</label>                        
                                    <input name='confirmPassword' id='confirmPassword' type ='password' value='' /><br />
                                   

                                    <input name='btnCancel' id='btnCancel' type ='button' value='Cancel' />
                                    <input name='btnAccountRegister' id='btnAccountRegister' type ='button' value='Register &#9658;' /><br />
                                    <div id='ajaxUpdateAccountMessageResponse'></div>
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

