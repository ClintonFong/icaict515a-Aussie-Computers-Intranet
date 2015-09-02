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
    require_once    'include/class.newOrderController.inc.php';
    $objNewOrderController = new c_newOrderController( $objLoginController->deptID );

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
        <link rel='stylesheet' type='text/css' href='css/popupFillOrder.css' />

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/newOrder.js"></script> 
        <script type="text/javascript" src="js/popupFillOrder.js"></script> 

        <script type='text/javascript'>
            arrCategoryItems = <?php echo json_encode( $objNewOrderController->arrCategoryItems ); ?>;
        </script>


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
                                $objGeneralHouseKeeping->displayEmployeeLeftMenu(1);
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

                        <div id='cntOrderForm'>
                            <form name='frmSubmitNewOrder' action='employee.php' target='_self' method='post'>

                                <input type='hidden' name='frmName'         value='frmSubmitNewOrder'>
                                <input type='hidden' name='actionTaken'     value='submit-new-order'>
                                <input type='hidden' name='json_orderItems' value=''>


                                <h1>New Order</h1>
                                <hr />
                                <!-- Orderer Name -->
                                <div id='cntOrdererName' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Orderer Name:</label></div>
                                    <div class='msgArea'><input name='name' id='name' type ='text' value='<?php echo strtoupper($objLoginController->firstname) . ', ' . ucfirst($objLoginController->lastname); ?>' class='gray' disabled=disabled /></div>
                                </div>

                                <!-- Manager Name -->
                                <div id='cntManager' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Manager:</label></div>
                                    <div class='msgArea'>
                                        <select id='manager' name='manager' disabled=disabled> 
                                            <?php 
                                                $objNewOrderController->displaySelectOptionsManagers();
                                            ?>
                                        </select>
                                        <a id='changeManager'>Change Manager</a>
                                        <!--<input name='manager' id='manager' type ='text' value='Clinton Fong' class='gray' disabled=disabled />-->

                                    </div>
                                </div>

                                <!-- Order Category -->
                                <div id='cntOrderCategory' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Category:</label></div>
                                    <select name='orderCategory' id='orderCategory'>
                                        <?php
                                            $objNewOrderController->displaySelectOptionsCategories();
                                        ?>
                                    </select>
                                </div>

                                <!-- Description -->
                                <div id='cntDescription' class='wrapperOrderFormField'>
                                    <div class='lblArea'><label>Description:<br/>(+Reason)</label></div>
                                    <div class='msgArea'><textarea name='description' id='description' maxlength='255'>&nbsp;</textarea></div>
                                </div>

                                <!-- Orderer Items -->
                                <br />
                                <div id='cntOrderItems' class='wrapperOrderFormField'>
                                    <table id='tblOrderItemsNew'>
                                    <tbody>
                                        <tr>
                                            <th>Select</th>
                                            <th>Order Item Name</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                        </tr>
                                        <!-- automatically generate with php -->
                                        <?php
                                            $objNewOrderController->displayNextTROrderItem();
                                        ?>
                                        <!-- end automatically generate with php -->

                                        <tr class='orderTotal'>
                                            <td colspan='3'>Total</td>
                                            <td><input name='totalAmount' id='totalAmount' type ='text' value='' disabled=disabled></td>
                                        </tr>

                                    </tbody>
                                    </table>

                                    <div id='cntOrderItemButtons'>
                                        <a id='removeItem'>Remove Selected Item</a>
                                        <a id='addItem'>Add Item</a>
                                        <a id='recalculate'>Re-Calculate</a>
                                    </div>

                                </div>

                                <!-- Submit / Cancel -->
                                <br /><br />
                                <input type='button name='btnCancel' id='btnCancel' value='Cancel Order' />
                                <input type='button name='btnSubmit' id='btnSubmit' value='Submit Order' />
                                <div id='ajaxChangeToCategoryMessageResponse'></div>
                            </form>
                        </div>

                    </div>	

                    <!-- ----------------------------------------------------------------------- -->
                    <!-- Popup Divs 
                    <!-- ----------------------------------------------------------------------- -->

                    <!-- Enter Item & Quote for Other -->
                    <div id='cntEnterOtherPopup'>
                        <a href='#' class='close'><img src='images/close_pop.png' class='btnClose' title='Close Window' alt='Close' /></a>

                        <form name='frmEnterOther' action='#' method='post' class='enterOther'>

                            <span>Please enter your Item & Quote</span>

                            <fieldset class='textbox'>
                                <div id='cntEnterOtherName'>
                                    <div class='lblArea'><label>Item name: </label></div>
                                    <div class='msgArea'><input id='itemName' name='itemName' value='' type='text' autocomplete='on' placeholder='name'></div>
                                </div>
                                <div id='cntEnterOtherPrice'>
                                    <div class='lblArea'><label>Item unit price: </label></div>
                                    <div class='msgArea'><input id='itemUnitPrice' name='itemUnitPrice' class='isMoneyKey' value='' type='text' autocomplete='on' placeholder='unit price ($)'></div>
                                </div>

                                <button id='btnOKOther' class='button' type='button'>OK</button>
                                <button id='btnCancelOther' class='button' type='button'>Cancel</button><br>
                            </fieldset>
                        </form>
		            </div>
                    <!-- End Enter Item & Quote for Other -->


                    <!-- Enter Quote for No prices -->
                    <div id='cntEnterQuotePopup'>
                        <a href='#' class='close'><img src='images/close_pop.png' class='btnClose' title='Close Window' alt='Close' /></a>

                        <form name='frmEnterQuote' action='#' method='post' class='enterQuote'>

                            <span>Please enter Quote for Item</span>

                            <fieldset class='textbox'>
                                <div id='cntEnterQuotePrice'>
                                    <div class='lblArea'><label>Item quote price: </label></div>
                                    <div class='msgArea'><input id='itemQuotePrice' name='itemQuotePrice' class='isMoneyKey' value='' type='text' autocomplete='on' placeholder='quote unit price ($)'></div>
                                </div>

                                <button id='btnOKQuote' class='button' type='button'>OK</button>
                                <button id='btnCancelQuote' class='button' type='button'>Cancel</button><br>
                            </fieldset>
                        </form>
		            </div>
                    <!-- End Enter Item & Quote for Other -->

                </div>		
            </div>
		    <hr>
		
		    <?php 
                $objGeneralHouseKeeping->displayFooter();
            ?>
		
        </div>
    </body>
</html>

