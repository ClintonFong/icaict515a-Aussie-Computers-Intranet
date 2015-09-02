//---------------------------------------------------------------------------------------------
// JQuery functions 
//---------------------------------------------------------------------------------------------

jQuery(function($)
{
    initializeSetup();

    /* 
        Department click functions 
    */
    //---------------------------------------------------------------------------------------------
    $('#registerNewDepartment').click( function(event)
    {
    	document.forms['frmAdmin'].frmName				= 'admin';
        document.forms['frmAdmin'].action 				= 'registerDepartment.php';
        document.forms['frmAdmin'].updateDeptID.value   = -1;
        document.forms['frmAdmin'].target               ='_self';
        document.forms['frmAdmin'].submit();

    }); // $('#registerNewDepartment').click


    //---------------------------------------------------------------------------------------------
    $('#tblDepartments tr').dblclick( function(event) //-------- dblclick event
    {
        var deptID   = $(this).attr('id');

        if( deptID > 0 )
        {
        	document.forms['frmAdmin'].frmName				= 'admin';
		    document.forms['frmAdmin'].action 			    = 'updateDepartment.php';
            document.forms['frmAdmin'].updateDeptID.value   = deptID;
            document.forms['frmAdmin'].target               ='_self';
            document.forms['frmAdmin'].submit();
        }
        return false;

        return onDblClickTR();

    }); //$('tr').dblclick



    /* 
        User click functions 
    */
    //---------------------------------------------------------------------------------------------
    $('#registerNewUser').click( function(event)
    {
    	document.forms['frmAdmin'].frmName				= 'admin';
        document.forms['frmAdmin'].action 				= 'registerUser.php';
        document.forms['frmAdmin'].updateUserID.value   = -1;
        document.forms['frmAdmin'].target               ='_self';
        document.forms['frmAdmin'].submit();

    }); // $('#sellYourItem').click


    //---------------------------------------------------------------------------------------------
    $('#tblUsers tr').dblclick( function(event) //-------- dblclick event
    {
        var userID   = $(this).attr('id');

        if ( userID > 0 )
        {
        	document.forms['frmAdmin'].frmName				= 'admin';
		    document.forms['frmAdmin'].action 			    = 'updateUser.php';
            document.forms['frmAdmin'].updateUserID.value   = userID;
            document.forms['frmAdmin'].target               ='_self';
            document.forms['frmAdmin'].submit();
        }
        return false;

        return onDblClickTR();

    }); //$('tr').dblclick


}); // jQuery(function($)

//---------------------------------------------------------------------------------------------
function initializeSetup()
{
    //alert("In initializeSetup");

} // initializeSetup