<?php
    //
    // contactInfo.php
    //
    // by Clinton Fong
    //

    //error_reporting( E_ALL | E_STRICT );
    //ini_set('display_errors', 1);

    session_start();

    $employeeID         = (isset($_SESSION['icaict515a-employee-id']))? $_SESSION['icaict515a-employee-id'] : "-1";	

    require_once 	'include/class.loginController.inc.php';
    $objLoginController = new c_loginController();
    $isUserLoggedIn     = $objLoginController->isUserLoggedIn( $employeeID ); 
    
	require_once 	'include/class.generalHouseKeeping.inc.php';
    $objGeneralHouseKeeping = new c_generalHouseKeeping( $objLoginController->firstname );

    require_once    'include/class.employeeController.inc.php';
    $employeeController = new c_employeeController();
    $employeeController->loadContactListing();
    
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
        <link rel='stylesheet' type='text/css' href='css/contactInfo.css' />


        <script src='jquery-ui-1.11.0.custom/external/jquery/jquery.js' type='text/javascript'></script>
        <script type="text/javascript" src="js/main.js"></script> 
        <script type='text/javascript' src='js/popupContactInfo.js'></script>


    </head>

    <body>

        <div id='wrapper'>
            <!-- header -->
		    <?php
                $objGeneralHouseKeeping->bHeaderSignIn = !$isUserLoggedIn;
                $objGeneralHouseKeeping->displayHeader("contactInfo");
            ?>
            <!-- end header -->

	
            <!-- Main Content -->
		    <div id='main-content'>

		        <div id='main-panel'>

                    <div id='cntContactListingBox'>

						<label>Double click on Selected Contact to View Details</label><br>
                        <div id='cntContactListing'>

                            <table>
                                <tbody>
                                    <tr>
                                        <th>Surname</th>
                                        <th>Firstname</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                    </tr>
                                    <?php
                                        $iRow = 0;
                                        foreach( $employeeController->ContactInfo as $contactInfo )
                                        {
                                            echo "<tr id='contactInfo{$iRow}' class='displayContactDetails'>\n";
                                            echo "  <td>{$contactInfo->lastname}</td>\n";
                                            echo "  <td>{$contactInfo->firstname}</td>\n";
                                            echo "  <td>{$contactInfo->phone}</td>\n";
                                            echo "  <td>{$contactInfo->email}</td>\n";
                                            echo "</tr>\n";
                                                
                                            $iRow++;
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Contact Detail -->
                    <div id='contactInfoBox' class='contactInfoPopup'>
                        <a href='#' class='close'><img src='images/close_pop.png' class='btnClose' title='Close Window' alt='Close' /></a>
                        <fieldset class='textbox'>
                            <h1>Contact Details</h1>
                            <hr /><br />
            	            <label>First Name:</label><input id='firstname' value='' type='text' disabled><br />
                            <label>Last Name:</label><input id='lastname' value='' type='text' disabled><br />
                            <label>Email:</label><input id='email' value='' type='text' disabled><br />
                            <label>Phone:</label><input id='phone' value='' type='text' disabled><br />
                            <label>Department:</label><input id='department' value='' type='text' disabled><br />
                            <label>Access Level:</label><input id='accessLevel' value='' type='text' disabled><br />
                            <br />
                            <button id='btnClose' class='button' type='button'>Close</button><br>
                        </fieldset>

		            </div>



                </div>		
            </div>
            <!-- end Main Content -->

		    <hr>
		
		    <?php 
                $objGeneralHouseKeeping->displayFooter();

                // transfer the $ContactInfo array to JavaScript for client to be able to access the information
                //
                echo "<script>";
                echo "var ContactInfoListing = " . json_encode( $employeeController->ContactInfo );
                echo "</script>";
            ?>
		
        </div>
    </body>
</html>

