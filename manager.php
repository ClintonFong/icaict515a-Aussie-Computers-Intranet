<?php
    session_start();

    //----------------------------------------------
    // check if logged in, otherwise throw them out.
    //----------------------------------------------
	require_once 	'include/class.loginController.inc.php';

   	$employeeID         = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	
    $objLoginController = new c_loginController();
    if( !$objLoginController->isUserLoggedIn( $employeeID ) || ($objLoginController->accessLevel < AL_MANAGER) )
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
        <link rel='stylesheet' type='text/css' href='css/manager.css' />

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 

    </head>

    <body>

        <div id='wrapper'>

            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->displayHeader();
            ?>
	        <!-- end header -->

		    <div id='main-content'>


                <!-- Main Content -->

		        <div id='main-panel'>
                    <div id='left-panel'>

                        <div id='cntLeftMenu'>
                            <?php
                                $objGeneralHouseKeeping->displayManagerLeftMenu(0);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>


                    </div>	

                </div>		
            </div>
		    <hr>
		
		    <?php 
                $objGeneralHouseKeeping->displayFooter();
            ?>
		
        </div>
    </body>
</html>

