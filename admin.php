<?php
    //
    // admin.php
    //
    // by Clinton Fong
    //

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
    // genergal house keeping for header and menus
    //--------------------------------------------
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );


    //--------------------------------------------


   	$frmName        = (isset($_POST['frmName']))? $_POST['frmName'] : '';
   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';
/*
   	$firstname      = (isset($_POST['firstname']))? $_POST['firstname'] : '';
   	$lastname       = (isset($_POST['lastname']))? $_POST['lastname'] : '';
   	$email          = (isset($_POST['email']))? $_POST['email'] : '';
   	$phone          = (isset($_POST['phone']))? $_POST['phone'] : '';
   	$accessLevel    = (isset($_POST['accessLevel']))? $_POST['accessLevel'] : '';
   	$password       = (isset($_POST['password']))? $_POST['password'] : '';


	require_once 	'include/class.adminController.inc.php';
    $objAdminController = new c_adminController();

    if( ($frmName == 'frmRegisterUser') && ($actionTaken == 'register-user') )
    {
        $objAdminController->registerNewMember( $firstname, $lastname, $email, $phone, $accessLevel, $password, $newEmployeeID );
    }
*/
   
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
                                $objGeneralHouseKeeping->displayAdminLeftMenu(0);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

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

