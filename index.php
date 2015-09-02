<?php
    session_start();

    //----------------------------------------------
    // check if logged in, otherwise throw them out.
    //----------------------------------------------
	require_once 	'include/class.loginController.inc.php';

   	$employeeID         = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	
    $objLoginController = new c_loginController();
    if( !$objLoginController->isUserLoggedIn( $employeeID ) )
    {
        header( "Location: login.php" ); // redirect to login page
    }
  
    //--------------------------------------------
    // general house keeping for header and menus
    //--------------------------------------------
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );


    //--------------------------------------------
    

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
        <!-- <link rel='stylesheet' type='text/css' href='css/commonMenu.css' /> -->

    </head>

    <body>

        <div id='wrapper'>
            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->bHeaderSignIn = TRUE;
                $objGeneralHouseKeeping->displayHeader();
            ?>
            <!-- header -->

	
            <!-- Main Content -->
		    <div id='main-content'>



		        <div id='main-panel'>
                    <div id='left-panel'>
                        <ul id='left-menu'>
                            <li><a class='selected-menu'></a></li>
                            <li><a href='contactus.php'></a></li>
                        </ul>
                    </div>

                    <div id='right-panel'>

                        <br>
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

