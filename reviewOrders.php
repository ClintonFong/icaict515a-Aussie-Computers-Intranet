<?php
    session_start();

    //--------------------------------------------
    // check if logged in, otherwise throw them out.
    //--------------------------------------------
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
	require_once 	'include/class.reviewOrdersController.inc.php';
    $objReviewOrdersController = new c_reviewOrdersController( $employeeID, $objLoginController->accessLevel );
    

?>

<!DOCTYPE html>

<html lang='en'>
    <head>
        <meta charset='utf-8' />
        <meta name='description' content='Aussie Computer Corporation' />
        <meta name='keywords' content='Aussie Computer Corporation' />
        <meta name='author' content='Clinton Fong' />

        <title>Aussie Computer Corporation - Review Orders</title>

        <link rel='stylesheet' type='text/css' href='css/main.css' />
        <link rel='stylesheet' type='text/css' href='css/orderForms.css' />
        <link rel='stylesheet' type='text/css' href='css/reviewOrders.css' />

        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type="text/javascript" src="js/reviewOrders.js"></script> 

        <script type='text/javascript'>
            json_orders             = <?php echo json_encode( $objReviewOrdersController->arrOrders ) ?>
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
                                $objGeneralHouseKeeping->displayReviewOrderLeftMenu( $objLoginController->accessLevel );
                            ?>
                        </div>
                    </div>

                    <div id='right-panel'>

                        <div id='cntOrderForm'>
                            <form name='frmReviewOrders' action='reviewOrder.php' target='_self' method='post'>

                                <input type='hidden' name='frmName'  value='frmReviewOrders'>
                                <input type='hidden' name='actionTaken'  value=''>

                                <input type='hidden' name='orderID'  value=''>
                                <input type='hidden' name='revision' value=''>

                                <h1>Review Orders</h1>
                                <hr />

                                <div id='reviewFilter'>
                                    <div id='cntOrderClass' class='wrapperOrderFormField'>
                                        <div class='lblArea'><label>Filter by:</label></div>
                                        <select name='filter' id='filter'>
                                            <option value=''>No Filter</option>
                                            <option value='<?php echo OS_SUBMITTED; ?>'>Pending</option>
                                            <option value='<?php echo OS_APPROVED; ?>'>Approved</option>
                                            <option value='<?php echo OS_REJECTED; ?>'>Rejected</option>
                                            <option value='<?php echo OS_PROCESSED; ?>'>Processed</option>
                                            <option value='<?php echo OS_SAVED; ?>'>Saved</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Review Items -->
                                <br />
                                <div id='cntOrderItems' class='wrapperOrderFormField'>
                                    <table id='tblOrdersReview'>
                                    <tbody>
                                        <tr>
                                            <th>Date</th>
                                            <th>Order<br />ID</th>
                                            <th>
                                                <?php
                                                    if  ($objLoginController->accessLevel == AL_MANAGER)      { echo "Employee's Name"; }
                                                    else                                                      { echo "Manager's Name"; }
                                                ?>
                                            </th>
                                            <th>Amount</th>
                                            <th>Rev</th>
                                            <th>Status</th>
                                        </tr>
                                        <!-- automatically generate with php -->
                                        <?php
                                            $objReviewOrdersController->displayOrders( $objLoginController->accessLevel );
                                        ?>
                                        <!-- end automatically generate with php -->

                                    </tbody>
                                    </table>

                                </div>

                                <!-- Next / Prev Page -->
                                <br /><br />
                                <div id='cntPrevNext'>
                                    <a name='prev' id='prev'>&#9668; Prev</a>
                                    <a name='next' id='next'>Next &#9658;</a>
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

    <script type='text/javascript'>
        idxStartNextOrderDisplay    = <?php echo $objReviewOrdersController->nOrdersDisplayed; ?>;
    </script>


</html>

