<?php

require_once    'class.basicDB.inc.php';

//---------------------
class structLineManager
{
    public $managerID               = -1;
    public $managerName             = '';
    public $deptID                  = -1;
    public $deptName                = '';
}
//----------------------
class structCategoryItem
{
    public $categoryItemID          = -1;
    public $name                    = '';
    public $price                   = 0.0;
}
//---------------------------------------------------------------------------------------------
class c_newOrderController extends c_basicDB
{

    public $orderID                 = -1;
    public $employeeID              = -1;
    public $deptID                  = -1;
    public $categoryID              = -1;

    public $arrLineManagers         = array();
    public $arrCategories           = array();
    public $arrCategoryItems        = array();


    private $nNumOrderItems         = 0;

	//---------------------------------------------------------------------------------------------
	// constructors 
	//---------------------------------------------------------------------------------------------
	function __construct( $deptID = 0 )
	{
		parent::__construct();

		$this->deptID = $deptID;

        $this->loadLineManagers();
        $this->loadCategories();
        $this->loadCategoryItems();
		
	} // __construct

	//---------------------------------------------------------------------------------------------
	// destructors
	//---------------------------------------------------------------------------------------------
	function __destruct()
	{
		parent::__destruct();	
		
	} // __destruct

	//---------------------------------------------------------------------------------------------
    // loadCategories
	//---------------------------------------------------------------------------------------------
    function loadCategories()
    {
        assert( isset( $this->dbConnection) );
        
		$stmtQuery  = "SELECT category_id, name FROM icaict515a_categories";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
            $iFirst = 0;
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $this->arrCateogries[$row['category_id']] = $row['name'];

                // grabbing the id of first element for later
                if( $iFirst == 0 ) 
                { 
                    $this->categoryID = $row['category_id']; 
                    $iFirst++;
                }
                
		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

    } // loadCategories


	//---------------------------------------------------------------------------------------------
    function displaySelectOptionsCategories()
    {
        $i = 0;
		$strDisplay = "";

        foreach( $this->arrCateogries as $categoryID => $category )
        {
            $strSelected = ($i == 0)? 'selected=selected' : '';
            $strDisplay .= "<option value='{$categoryID}' {$strSelected}>{$category}</option>\n";
            $i++;
        }

		echo $strDisplay;

    } // displaySelectOptionsCategories
   

	//---------------------------------------------------------------------------------------------
    // loadCategoryItems
	//---------------------------------------------------------------------------------------------
    function loadCategoryItems()
    {
        assert( isset( $this->dbConnection) );

        if( $this->categoryID > 0 )
        {
		    $stmtQuery  = "SELECT category_item_id, name, price FROM icaict515a_category_items";
            $stmtQuery .= " WHERE category_id='{$this->categoryID}'";

		    if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
            {
		        while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		        {
                    $orderItem  = new structCategoryItem();

                    $orderItem->categoryItemID      = $row['category_item_id'];
                    $orderItem->name                = $row['name'];
                    $orderItem->price               = $row['price'];

                    $this->arrCategoryItems[]       = $orderItem;

		        } // while
        	
		        $resultQuery->close(); 	// Free resultset 
            }
        }

    } // loadCategoryItems
    

	//---------------------------------------------------------------------------------------------
    function displayNextTROrderItem()
    {
        $strDisplayCheckBox = ($this->nNumOrderItems == 0)? '' : "<input  name='isChecked{$this->nNumOrderItems}' id='isChecked{$this->nNumOrderItems}' type='checkbox'>";

        $strDisplay = "
            <tr> \n
                <td id='tdIsChecked{$this->nNumOrderItems}'>{$strDisplayCheckBox}</td>\n
                <td id='tdItem{$this->nNumOrderItems}'>\n
                    <select name='itemName{$this->nNumOrderItems}' id='itemName{$this->nNumOrderItems}' class='itemSelect'>\n
                        " . $this->getSelectOptionsCategoryItems() . "
                    </select>\n
                </td>\n
                <td id='tdQty{$this->nNumOrderItems}'><input name='qty{$this->nNumOrderItems}' id='qty{$this->nNumOrderItems}' type='text' value='' class='inputQty isIntegerKey'></td>\n
                <td id='tdAmount{$this->nNumOrderItems}'><input name='amount{$this->nNumOrderItems}' id='amount{$this->nNumOrderItems}' type='text' value='' class='amount'></td>\n
            </tr>\n
            ";

        $this->nNumOrderItems++;

        echo $strDisplay;

    } // displayOrderItem

	//---------------------------------------------------------------------------------------------
    function getSelectOptionsCategoryItems()
    {
		$strDisplay = "";
        $bFirst = TRUE;

        foreach( $this->arrCategoryItems as $categoryItem )
        {
            $strSelected  = ($bFirst)? 'selected=selected' : '';
            $strDisplay .= "<option value='{$categoryItem->categoryItemID}' {$strSelected}>{$categoryItem->name}</option>\n";
            $bFirst = FALSE;
        }
        $strDisplay .= "<option value='0'>Other</option>"; // add the other options selection

		return $strDisplay;

    } // getSelectOptionsCategoryItems


	//---------------------------------------------------------------------------------------------
    // loadLineManagers 
    //
    // Description: a line manager is a manager of a department that managers people. Project managers
    //              are not managers.
    //              A line manager works for managers/departments above him/her (the people who do their review and pay)
    //              The line manager managers the people and departments below him/her either directly or indirectly through sub-managers
    //              So based on this line of reasoning, all line of managers are department managers, so a retrieval of all deparment managers
    //              would be appropriate here.
	//---------------------------------------------------------------------------------------------
    function loadLineManagers()
    {
        assert( isset( $this->dbConnection) );

		$stmtQuery  = "SELECT icaict515a_departments.department_id, name, employee_id, firstname, lastname";
        $stmtQuery .= " FROM icaict515a_departments, icaict515a_employees";
        $stmtQuery .= " WHERE icaict515a_departments.manager_id = icaict515a_employees.employee_id";
        $stmtQuery .= " ORDER BY name, lastname, firstname";

		if( $resultQuery = $this->getDBConnection()->query( $stmtQuery ) )
        {
		    while ($row = $resultQuery->fetch_array( MYSQL_ASSOC ) ) 
		    {
                $managerName = strtoupper($row['lastname']) . ", " . ucfirst($row['firstname']);

                $manager = new structLineManager();

                $manager->managerID         = $row['employee_id'];
                $manager->managerName       = $managerName;
                $manager->deptID            = $row['department_id'];
                $manager->deptName          = $row['name'];

                $this->arrLineManagers[]    = $manager;

		    } // while
        	
		    $resultQuery->close(); 	// Free resultset 
        }

    } // loadLineManagers


	//---------------------------------------------------------------------------------------------
    function displaySelectOptionsManagers()
    {

		$strDisplay = "";

        foreach( $this->arrLineManagers as $manager )
        {
            $strSelected = ($this->deptID == $manager->deptID)? 'selected=selected' : '';
            $strDisplay .= "<option value='{$manager->managerID}' {$strSelected}>{$manager->managerName} ({$manager->deptName})</option>\n";
        }
		echo $strDisplay;


    } // displaySelectOptionsCategories


} // class c_newOrderController

?>
