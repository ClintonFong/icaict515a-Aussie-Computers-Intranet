<?php
    
class c_generalHouseKeeping
{ 
    // Header attributes
    // -----------------
    public $arrHeaderMenuItems  = array();
    public $bHeaderSignIn       = FALSE;
    public $username            = "";

    // Footer attributes
    // -----------------

    // Left Menu attributes
    // --------------------
    public $accessLevel         = '';
    public $leftMenuTitle       = '';
    public $selectedMenuItem    = 0;

	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $username = '' )
	{
        $this->username = $username;

	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
	} // __destruct

	//---------------------------------------------------------------------------------------------
    // Header
	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------------------
    //
    // Functions
    //
    //---------------------------------------------------------------------------------------------
    function getTopMenuItems( $arrMenuItems )
    {
        //global $arrMenuItems;
        $strAdditionalLinks = "";

        if ( isset($arrMenuItems) )
        {
            $i = 1;
            $nLength = count( $arrMenuItems );
            foreach( $arrMenuItems as $key => $value )
            {
                if( $i == $nLength) { $strAdditionalLinks .= "<li><a href='{$value}' style='background-image:none'>{$key}</a></li>"; }
                else                { $strAdditionalLinks .= "<li><a href='{$value}'>{$key}</a></li>"; }
                $i++;
            }
        }

        return $strAdditionalLinks;

    } // getTopMenuItems


    //---------------------------------------------------------------------------------------------
    // display navigation menu above the main menu
    //
    function getTopMenu( $arrMenuItems )
    {
        $strMenu = "";

        if( count($arrMenuItems) > 0 )
        {
            $strMenu = " \n 
                <div id='cntTopMenu'>\n
			        <ul class='menu menu-member'>" 
                        . $this->getTopMenuItems( $arrMenuItems ) . "
			        </ul>
                </div>\n
	        ";
        }

        return $strMenu;

    } // displayTopMenu

    // End Functions

    //---------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------
    //---------------------------------------------------------------------------------------------

    // Display the header
    //
    function displayHeader( $currentPage = "" )
    {
        echo "	
	        <div id='header'>\n
                <div id='logo'>\n
    	            <img id='imgLogo' src='images/logo-1-h175.gif' alt='Aussie Computer Corporation' />\n
                </div>\n
		        <div id='banner'>\n
                    <h1 id='h1Logo'>Aussie Computer Corporation</h1>\n
                    " . $this->getTopMenu( $this->arrHeaderMenuItems ) . "
		        </div>\n
	        </div>\n
            \n";


        // Displays the Main Navigation Bar
        //
        $thisPageHome           = ( $currentPage == "" )? "class='selected-menu'" : "";
        $thisPageContactInfo    = ( $currentPage == "contactInfo" )? "class='selected-menu'" : "";
        $thisPageSignIn         = ( $currentPage == "signIn" )? "class='selected-menu'" : "";
        
        $strSignIn  = ($this->bHeaderSignIn)? "Sign-In"   : "Sign-out";
        $strWelcome = ($this->bHeaderSignIn)? ""          : "<div id='cntWelcomeMember'>Hello {$this->username}</div>";
        $strHomeRef = ($this->bHeaderSignIn)? "index.php" : "employee.php";
            
        echo "
            <div id='cntMainNavBar'>\n
                <div id='cntMainNavMenu'>\n
                    <ul id='nav-menu'>\n
                        <li {$thisPageHome}'><a href='{$strHomeRef}'>Home</a></li>\n
                        <li {$thisPageContactInfo}><a href='contactInfo.php'>Contact Info</a></li>\n
                        <li {$thisPageSignIn}><a href='login.php'>{$strSignIn}</a></li>\n
                    </ul>\n
                </div>\n
                {$strWelcome} \n
            </div>\n
            ";

    } // displayHeader

	//---------------------------------------------------------------------------------------------
    // Footer
	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // displayFooter
	//---------------------------------------------------------------------------------------------
    function displayFooter()
    {
        echo "	
		        <div id='footer'>
			        <div id='copyright-info'>
				        <label id='copyright'>Copyright &copy; 2014</label>
				        <label id='designed-by'>Designed by: Clinton Fong</label>
				        <label id='email'>info@clintonfong.com</label>
			        </div>
		        </div>
	        ";

    } // displayFooter

	//---------------------------------------------------------------------------------------------
    // Left Menu
	//---------------------------------------------------------------------------------------------
	//---------------------------------------------------------------------------------------------
    // displayLeftMenu
	//---------------------------------------------------------------------------------------------

    function displayLeftMenu( $accessLevel,
                              $selectedMenuItem = -1 )
    {
        $this->accessLevel      = $accessLevel;
        $this->selectedMenuItem = $selectedMenuItem;


        switch( $accessLevel )
        {
            case AL_EMPLOYEE:
                return $this->displayEmployeeLeftMenu();
                break;

            case AL_PROCUREMENT_MEMBER:
                return $this->displayProcurementMemberLeftMenu();
                break;

            case AL_MANAGER:
                return $this->displayManagerLeftMenu();
                break;

            case AL_ADMIN:
                return $this->displayAdminLeftMenu();
                break;

            default:
                // unknown access level
        }    

        return "";

    } // displayLeftMenu

    //---------------------------------------------------------------------------------------------
    // displayEmployeeLeftMenu
    //---------------------------------------------------------------------------------------------
    function displayEmployeeLeftMenu( $selectedMenuItem = -1)
    {
        //echo "In displayEmployeeLeftMenu";

        $this->selectedMenuItem = ($selectedMenuItem == -1)? $this->selectedMenuItem : $selectedMenuItem;

        $leftMenu  = "<div class='menu-title'><label>Orders</label></div>\n";
        $leftMenu .= "<ul id='left-menu'>\n";

        switch( $this->selectedMenuItem )
        {        
            case 0:
                $leftMenu .= "<li><a href='employeeNewOrder.php'>Place New Order</a></li>\n
                              <li><a  href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 1:
                $leftMenu .= "<li class='selected-menu'><a>New Order <div>&#9658;</div></a></li>\n
                              <li><a href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 2:
                $leftMenu .= "<li><a href='employeeNewOrder.php'>New Order</a></li>\n
                              <li class='selected-menu'><a>Review Orders <div>&#9658;</div></a></li>\n";
                break;
        }

        $leftMenu .= "</ul>";

        echo $leftMenu;

    } // displayEmployeeLeftMenu

    //---------------------------------------------------------------------------------------------
    // displayProcurementMemberLeftMenu
    //---------------------------------------------------------------------------------------------
    function displayProcurementMemberLeftMenu( $selectedMenuItem = -1)
    {
        $this->selectedMenuItem = ($selectedMenuItem == -1)? $this->selectedMenuItem : $selectedMenuItem;

        $leftMenu  = "<div class='menu-title'><label>Orders</label></div>\n";
        $leftMenu .= "<ul id='left-menu'>\n";

        switch( $this->selectedMenuItem )
        {        
            case 0:
                $leftMenu .= "<li><a href='procurementProcessOrder.php'>Process Order</a></li>\n
                              <li><a href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 1:
                $leftMenu .= "<li class='selected-menu'><a>Process Order <div>&#9658;</div></a></li>\n
                              <li><a href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 2:
                $leftMenu .= "<li><a href='procurementProcessOrder.php'>Process Order</a></li>\n
                              <li class='selected-menu'><a>Review Orders <div>&#9658;</div></a></li>\n";
                break;
        }

        $leftMenu .= "</ul>";

        echo $leftMenu;

    } // displayProcurementMemberLeftMenu

    //---------------------------------------------------------------------------------------------
    // displayManagerLeftMenu
    //---------------------------------------------------------------------------------------------
    function displayManagerLeftMenu( $selectedMenuItem = -1)
    {
        $this->selectedMenuItem = ($selectedMenuItem == -1)? $this->selectedMenuItem : $selectedMenuItem;

        $leftMenu  = "<div class='menu-title'><label>Managers Tray</label></div>\n";
        $leftMenu .= "<ul id='left-menu'>\n";

        switch( $this->selectedMenuItem )
        {        
            case 0:
                $leftMenu .= "<li><a href='managerAuthorizeOrders.php'>Authorize Orders</a></li>\n
                              <li><a href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 1:
                $leftMenu .= "<li class='selected-menu'><a>Authorize Orders <div>&#9658;</div></a></li>\n
                              <li><a href='reviewOrders.php'>Review Orders</a></li>\n";
                break;

            case 2:
                $leftMenu .= "<li><a href='managerAuthorizeOrders.php'>Authorize Orders</a></li>\n
                              <li class='selected-menu'><a>Review Orders <div>&#9658;</div></a></li>\n";
                break;
        }

        $leftMenu .= "</ul>";

        echo $leftMenu;

    } // displayManagerLeftMenu


    //---------------------------------------------------------------------------------------------
    // displayAdminLeftMenu
    //---------------------------------------------------------------------------------------------
    function displayAdminLeftMenu( $selectedMenuItem = -1)
    {
        $this->selectedMenuItem = ($selectedMenuItem == -1)? $this->selectedMenuItem : $selectedMenuItem;

        $leftMenu  = "<div class='menu-title'><label>Admin Panel</label></div>\n";
        $leftMenu .= "<ul id='left-menu'>\n";

        switch( $this->selectedMenuItem )
        {        
            case 0:
                $leftMenu .= "<li><a href='adminDepartments.php'>Departments</a></li>\n
                              <li><a href='adminUsers.php'>Users</a></li>\n";
                break;

            case 1:
                $leftMenu .= "<li class='selected-menu'><a>Departments <div>&#9658;</div></a></li>\n
                              <li><a href='adminUsers.php'>Users</a></li>\n";
                break;

            case 2:
                $leftMenu .= "<li><a href='adminDepartments.php'>Departments</a></li>\n
                              <li class='selected-menu'><a>Users <div>&#9658;</div></a></li>\n";
                break;
        }

        $leftMenu .= "</ul>";

        echo $leftMenu;

    } // displayAdminLeftMenu

    //---------------------------------------------------------------------------------------------
    // displayReviewOrderLeftMenu
    // 
    //  Description: displays the appropriate left menu for appropriate user
    //---------------------------------------------------------------------------------------------
    function displayReviewOrderLeftMenu( $accessLevel )
    {
        $this->displayLeftMenu( $accessLevel, 2 );

    } // displayReviewOrderLeftMenu

} // class c_generalHouseKeeping

?>
