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

   	$frmName        = (isset($_POST['frmName']))? $_POST['frmName'] : '';
   	$actionTaken    = (isset($_POST['actionTaken']))? $_POST['actionTaken'] : '';

	require_once 	'include/class.authorizeOrdersController.inc.php';
    $objAuthorizeOrdersController  = new c_authorizeOrdersController( $employeeID );

    $lastUpdateFeedback = '';

    if( $frmName == 'frmAuthorizeOrder' ) 
    {
        $bSuccessfulUpdate = FALSE;

        $orderID    = (isset($_POST['orderID']))? $_POST['orderID'] : '';
       	$revision   = (isset($_POST['revision']))? $_POST['revision'] : '';
       	$note       = (isset($_POST['note']))? $_POST['note'] : '';

        // preparing email to send to employee....
	    require_once 	'include/class.mailController.inc.php';
        $objmailController = new c_mailController();

        $objmailController->fromManagerID          = $employeeID;
        $objmailController->fromManagerFirstname   = $objLoginController->firstname;
        $objmailController->fromManagerLastname    = $objLoginController->lastname;

        if( $actionTaken == 'reject-order')      
        { 
            if( $bSuccessfulUpdate  = $objAuthorizeOrdersController->authorizeOrder( $orderID, $revision, OS_REJECTED, $note ) ) 
            {
                $lastUpdateFeedback = "<h3 class='green'>Last order successfully rejected</h3>";
                $objmailController->notifyEmployee( $orderID, OS_REJECTED );
            }
            else
            {
                $lastUpdateFeedback = "<h3 class='red'>Last order rejection failed - contact administrator</h3>";
            }

        }
        elseif( $actionTaken == 'approve-order') 
        { 
            if( $bSuccessfulUpdate = $objAuthorizeOrdersController->authorizeOrder( $orderID, $revision, OS_APPROVED, $note ) )
            {
                $lastUpdateFeedback = "<h3 class='green'>Last order successfully approved</h3>";
                $objmailController->notifyEmployee(         $orderID, OS_APPROVED );
                $objmailController->notifyProcurementTeam(  $orderID, OS_REJECTED );

            }
            else
            {
                $lastUpdateFeedback = "<h3 class='red'>Last order approval failed - contact administrator</h3>";
            }
        }
    }

    $objAuthorizeOrdersController->loadAllOrdersForAuthorization();
  

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
        <link rel='stylesheet' type='text/css' href='css/orderFormsManager.css' />

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/authorizeOrders.js"></script> 

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
                                $objGeneralHouseKeeping->displayManagerLeftMenu(1);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

                        <div id='cntOrderForm'>
                            <form name='frmAuthorizeOrders' action='authorizeOrder.php' target='_self' method='post'>

                                <input type='hidden' name='frmName'  value='frmAuthorizeOrders'>
                                <input type='hidden' name='actionTaken'  value=''>

                                <input type='hidden' name='orderID'  value=''>
                                <input type='hidden' name='revision' value=''>


                                <h1>Pending Orders</h1>
                                <?php echo $lastUpdateFeedback; ?>
                                <hr />


                                <!-- Pending Orders -->
                                <br />
                                <div id='cntPendingOrder' class='wrapperOrderFormField'>
                                    <table id='tblPendingOrders'>
                                    <tbody>
                                        <tr>
                                            <th>Orderer Name</th>
                                            <th>Date</th>
                                            <th>Category</th>
                                            <th>Amount</th>
                                        </tr>
                                        <!-- automatically generate with php -->
                                        <?php
                                            $objAuthorizeOrdersController->displayOrders();       
                                        ?>
                                        <!-- end automatically generate with php -->

                                    </tbody>
                                    </table>


                                </div>

                            </form>

                        </div>

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

