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
    $orderID    = (isset($_POST['orderID']))? $_POST['orderID'] : '';
   	$revision   = (isset($_POST['revision']))? $_POST['revision'] : '';

    require_once    'include/class.reviewOrderController.inc.php';
    $objReviewOrderController = new c_reviewOrderController( $orderID, $revision );

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
        <script type="text/javascript" src="js/authorizeOrder.js"></script> 

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

                            <h1>Authorize Order</h1>
                            <hr />


                            <fieldset id='fldsetTheOrder'>
                                <legend id='legendTheOrder'><?php echo "Order ID: {$orderID} - submitted: {$objReviewOrderController->dateSubmitted} (revision: {$objReviewOrderController->revision})"; ?></legend>

                                <!-- Orderer Name -->
                                <div id='cntOrdererName' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Orderer Name:</label></div>
                                    <div class='msgArea'><input name='name' id='name' type ='text' value='<?php echo $objReviewOrderController->employeeName ?>' class='gray' disabled=disabled /></div>
                                </div>

                                <!-- Order Class -->
                                <div id='cntOrderClass' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Category:</label></div>
                                    <div class='msgArea'><input name='category' id='category' type ='text' value='<?php echo $objReviewOrderController->categoryName ?>' disabled=disabled /></div>
                                </div>

                                <!-- Description -->
                                <div id='cntDescription' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Description:<br/>(+Reason)</label></div>
                                    <div class='msgArea'><textarea name='description' id='description' maxlength='255' disabled=disabled><?php echo $objReviewOrderController->description  ?></textarea></div>
                                </div>

                                <!-- Orderer Items -->
                                <br />
                                <div id='cntOrderItems' class='wrapperOrderFormField'>
                                    <table id='tblOrderItemsReview'>
                                    <tbody>
                                        <tr>
                                            <th>Order Item Name</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                        <!-- automatically generate with php -->
                                        <?php
                                            $objReviewOrderController->displayOrderItems();
                                        ?>
                                        <!-- end automatically generate with php -->

                                        <tr class='orderTotal'>
                                            <td colspan='2'>Total</td>
                                            <td><?php echo $objReviewOrderController->totalAmount;  ?></td>
                                        </tr>

                                    </tbody>
                                    </table>


                                </div>
                            </fieldset> <!-- the order -->


                            <fieldset id='fldsetAuthorize'>
                                <legend id='legendAuthorize'>Authorize</legend>

                                <form name='frmAuthorizeOrder' action='managerAuthorizeOrders.php' target='' method='post'>

                                    <input type='hidden' name='frmName' value='frmAuthorizeOrder'>
                                    <input type='hidden' name='actionTaken' value=''>

                                    <input type='hidden' name='orderID' value='<?php echo $orderID; ?>'>
                                    <input type='hidden' name='revision' value='<?php echo $revision; ?>'>


                                    <!-- Manager's note -->
                                    <div id='cntManagersNote' class='wrapperOrderFormField'>
                                        <div class='lblArea'><label>Manager's:<br/>Note</label></div>
                                        <div class='msgArea'><textarea name='note' id='note' maxlength='255'></textarea></div>
                                    </div>

                                    <!-- Submit -->
                                    <br />
                                    <input type='button name='btnApprove' id='btnApprove' value='Approve Order' />
                                    <input type='button name='btnReject' id='btnReject' value='Reject Order' />

                                </form>
                            </fieldset>
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

