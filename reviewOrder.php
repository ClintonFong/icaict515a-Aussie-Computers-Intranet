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
        <link rel='stylesheet' type='text/css' href='css/orderForms.css' />
        <link rel='stylesheet' type='text/css' href='css/newOrder.css' />

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/reviewOrder.js"></script> 


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
                                $objGeneralHouseKeeping->displayEmployeeLeftMenu(2);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

                        <div id='cntOrderForm'>
                            <form name='frmReviewOrder' action='' target='_self' method='post'>

                                <input type='hidden' name='frmName'         value='frmReviewOrder'>
                                <input type='hidden' name='actionTaken'     value='submit-new-order'>
                                <input type='hidden' name='json_orderItems' value=''>


                                <h1>Order Review</h1>
                                <h3>Order ID: <?php echo "{$objReviewOrderController->orderID}"; ?></h3>
                                <h3>Date Submitted: <?php echo "{$objReviewOrderController->dateSubmitted}"; ?></h3>
                                <hr />
                                <!-- Orderer Name -->
                                <div id='cntOrdererName' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Orderer Name:</label></div>
                                    <div class='msgArea'><input name='name' id='name' type ='text' value='<?php echo $objReviewOrderController->employeeName ?>' class='gray' disabled=disabled /></div>
                                </div>

                                <!-- Manager Name -->
                                <div id='cntManager' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Manager:</label></div>
                                    <div class='msgArea'><input name='manager' id='manager' type ='text' value='<?php echo $objReviewOrderController->managerName  ?>' class='gray' disabled=disabled /></div>
                                </div>

                                <!-- Order Category -->
                                <div id='cntOrderCategory' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Category:</label></div>
                                    <div class='msgArea'><input name='category' id='category' type ='text' value='<?php echo $objReviewOrderController->categoryName  ?>' class='gray' disabled=disabled /></div>
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

                                <!-- Manager's note -->
                                <div id='cntManagersNote' class='reviewManagersNote'>
                                    <div class='lblArea'><label>Manager's:<br/>Note</label></div>
                                    <div class='msgArea'><textarea name='note' id='note' maxlength='255' disabled=disabled><?php $objReviewOrderController->displayOrderNotes(); ?></textarea></div>
                                </div>

                                

                                <!-- Close -->
                                <br /><br />
                                <input type='button name='btnClose' id='btnClose' value='Close' />
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

