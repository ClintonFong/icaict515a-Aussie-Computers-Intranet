<?php
    //
    // login.php
    //
    // by Clinton Fong
    //

    session_start();
	require_once 	'include/class.loginController.inc.php';
	require_once 	'include/class.generalHouseKeeping.inc.php';
    require_once    'PHPMailer_5.2.4/class.phpmailer.php';

    $objGeneralHouseKeeping = new c_generalHouseKeeping();

    //--------------------------------------------


   	$actionTaken        = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';
    $signinAttempt      = 0;
    $registerAttempt    = 0;

    $objLoginController  = new c_loginController();

    if ($actionTaken == 'validate-member-login')
    {
   	    $signinEmail    = (isset($_POST['signinEmail']))? $_POST['signinEmail'] : '';
   	    $password       = (isset($_POST['password']))? $_POST['password'] : '';

        if( $objLoginController->isLoginValid( $signinEmail, $password ) )
        {
            $objLoginController->flagLoggedIn( $objLoginController->userID );
            $_SESSION['icaict515a-employee-id'] = $objLoginController->userID; 
            header( "Location: employee.php" );      // redirect to staff page
        }
        else
        {
            $signinAttempt = 1;
        }
    }
    else
    {
       	$userID = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	

        if( $userID != '-1' )
        {
            // reset all session variables and flag database as user logged out
            // return to statelessness
            //
            $_SESSION['icaict515a-employee-id'] = '-1';   // remove the member ID
            $_SESSION = array();                        // clear the variables
            session_destroy();                          // destroy the session itself
            $objLoginController->flagLoggedOut( $userID );
            setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);
        }

    } // if

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
        <link rel='stylesheet' type='text/css' href='css/login.css' />
        <link rel='stylesheet' type='text/css' href='css/popupForgotPassword.css' />


        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/login.js"></script> 
        <script type='text/javascript' src='js/popupForgotPassword.js'></script>


    </head>

    <body>

        <div id='wrapper'>
            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->bHeaderSignIn = TRUE;
                $objGeneralHouseKeeping->displayHeader("signIn");
            ?>
            <!-- end header -->

	
            <!-- Main Content -->
		    <div id='main-content'>

		        <div id='main-panel'>
                    <div id='left-panel'>


                    </div>

                    <div id='right-panel'>

                        <div id='cntSigninBox'>
                            <form name='frmSignin' action='login.php' target='_self' method ='post'>

                                <input type='hidden' name='actionTaken' value='validate-member-login' />
                                <input type='hidden' name='signinAttempt' value='<?php echo $signinAttempt; ?>' id='signinAttempt' />

                                <fieldset id='fldsetSignin'>
                                    <legend id='legendSignin'>Employee / Manager Sign In</legend>
                                    <div id='cntSigninDetails'>
                                        <label>Sign-in Email / User ID:</label>                        
                                        <input name='signinEmail' id='signinSigninEmail' type ='text' value='<?php echo $signinEmail; ?>' /><br />
                                        <label>Password:</label>                        
                                        <input name='password' id='signinPassword' type ='password' value='' /><br />
                                        <input name='btnSignin' id='btnSignin' type ='button' value='Sign-in &#9658;' />
                                        <a id='aForgotPassword' href='#forgotPasswordBox'>Forgot your Password?</a> 
                                        <!-- <a id='aForgotPassword' href='javascript:doForgotPassword();'>Forgot your Password?</a> -->
                                        <?php
                                            if ($signinAttempt == 1) 
                                            {
                                                echo "<br><label id='lblErrMsgSignin' class='required important errMsg'>Login unsuccessful</label>";
                                            }
                                        ?>

                                    </div>
                                </fieldset>
                            </form>
                        </div>

                    </div>	

                    <!-- Forgot Email -->
                    <div id='forgotPasswordBox' class='forgotPasswordPopup'>
                        <a href='#' class='close'><img src='images/close_pop.png' class='btnClose' title='Close Window' alt='Close' /></a>
                        <form name='frmForgotPassword' action='#' method='post' class='signin'>

                            <fieldset class='textbox'>
            	                <label>
                                    <span>Please enter your Sign-in Email address<br> and your new password will be emailed to you.</span>
                                    <input id='forgotPasswordSigninEmail' name='signinEmail' value='' type='text' autocomplete='on' placeholder='Sign-in Email'>
                                </label>

                                <button id='btnSend' class='button' type='button'>Send</button><br>
                                <div id='ajaxForgotPasswordMessageResponse'></div>
                            </fieldset>

                        </form>
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

