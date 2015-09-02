<?php
    //
    // employee.php
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
    if( !$objLoginController->isUserLoggedIn( $employeeID ))
    {
        header( "Location: login.php" ); // redirect to login page
    }
    elseif( $objLoginController->accessLevel == AL_ADMIN )
    {
        header( "Location: admin.php" ); // user is an administrator redirect to admin page
    }
    elseif( $objLoginController->accessLevel == AL_MANAGER )
    {
        header( "Location: manager.php" ); // user is an administrator redirect to admin page
    }

    //--------------------------------------------
    // genergal house keeping for header and menus
    //--------------------------------------------
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );

    //--------------------------------------------

   	$frmName        = (isset($_POST['frmName']))? $_POST['frmName'] : '';
   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';

//    echo "frmName = {$frmName}<br>";
//    echo "actionTaken = {$actionTaken}<br>";


    if( ( $frmName == 'frmSubmitNewOrder' ) && ( $actionTaken == 'submit-new-order') )
    {
	    require_once 	'include/class.employeeController.inc.php';
        $objemployeeController = new c_employeeController( $employeeID );

        $order = new structOrder();

        $order->employeeID         = $employeeID;
   	    $order->managerID          = (isset($_POST['manager']))? $_POST['manager'] : '';
   	    $order->categoryID         = (isset($_POST['orderCategory']))? $_POST['orderCategory'] : '';
   	    $order->description        = (isset($_POST['description']))? $_POST['description'] : '';
   	    $order->totalAmount        = (isset($_POST['totalAmount']))? $_POST['totalAmount'] : '';

   	    $json_orderItems           = (isset($_POST['json_orderItems']))? $_POST['json_orderItems'] : '';
        $order->arrOrderItems      = json_decode( $json_orderItems );

        if( $bOrderSuccess = $objemployeeController->registerNewOrder( $order ) )
        {

            // send email to manager....
	        require_once 	'include/class.mailController.inc.php';
            $objmailController = new c_mailController();

            $objmailController->fromEmployeeID          = $employeeID;
            $objmailController->fromEmployeeFirstname   = $objLoginController->firstname;
            $objmailController->fromEmployeeLastname    = $objLoginController->lastname;

            $objmailController->notifyManager( $order->orderID );
        }

/*
        echo "manager = {$manager}<br>";
        echo "category = {$category}<br>";
        echo "description = {$description}<br>";
        echo "totalAmount = {$totalAmount}<br>";
        print_r( $json_orderItems );
*/
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


    </head>

    <body>

        <div id='wrapper'>

            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->displayHeader();
            ?>
            <!-- end header -->

	
            <!-- Main Content -->
		    <div id='main-content'>

		        <div id='main-panel'>
                    <div id='left-panel'>

                        <div id='cntLeftMenu'>
                            <?php
                                $objGeneralHouseKeeping->displayEmployeeLeftMenu(0);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>
                        <br />
                        <?php
                            if( ( $frmName == 'frmSubmitNewOrder' ) && ( $actionTaken == 'submit-new-order') )
                            {
                                if( $bOrderSuccess )    { echo "<span class='dbFeedbackMessage'>Order Successfully placed... Order ID: {$order->orderID}<br><br>(Please take note of your Order ID to keep track of your order.)</span>"; }
                                else                    { echo "<span class='dbFeedbackMessage'>Order Placing Unsuccessful...</span>"; }
                            }
                        ?>
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

