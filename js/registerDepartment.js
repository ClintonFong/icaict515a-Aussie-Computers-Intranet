//---------------------------------------------------------------------------------------------
// JQuery functions for registerUser.php
//---------------------------------------------------------------------------------------------

var errorHighlightColor = '#FeFe00';
var resetHighlightColor = '#ffffff';
var toggleIsSeller      = false;

jQuery(function($)
{
    
    
	//---------------------------------------------------------------------------------------------
	$('#phone').on('input paste', function( event ) 
    {
        event.target.value = truncatePhoneExt( event.target.value );
    });

	//---------------------------------------------------------------------------------------------
	$('#btnCancel').click( function( event ) 
    {
    	document.forms['frmRegisterDept'].frmName.value	    = 'frmRegisterDept';
        document.forms['frmRegisterDept'].actionTaken.value = 'cancel';
        document.forms['frmRegisterDept'].action 			= 'adminDepartments.php';
        document.forms['frmRegisterDept'].target            ='_self';
        document.forms['frmRegisterDept'].submit();

    }); // $('#btnCancel').click

	//---------------------------------------------------------------------------------------------
	$('#btnDeptRegister').click( function( event ) 
    {
//        alert("#btnAccountUpdate");
        var bOk = true;
        var strErrorMessage = '';

        // reset all the background colors
        $('#deptName').css('background-color',         resetHighlightColor );
        $('#deptManager').css('background-color',      resetHighlightColor );
        $('#deptBudget').css('background-color',       resetHighlightColor );


        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmRegisterDept'].deptName.value == '' )
        {
            bOk = false;
            strErrorMessage = '*Department name is required\n';
            $('#deptName').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmRegisterDept'].deptBudget.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Department budget is required\n';
            $('#deptBudget').css('background-color', errorHighlightColor );
        }


        if (!bOk)
        {
            alert (strErrorMessage);
        }
        else 
        {
    	    document.forms['frmRegisterDept'].frmName.value	    = 'frmRegisterDept';
            document.forms['frmRegisterDept'].actionTaken.value = 'register-dept';
            document.forms['frmRegisterDept'].action 			= 'adminDepartments.php';
            document.forms['frmRegisterDept'].target            ='_self';
            document.forms['frmRegisterDept'].submit();

        }
	
	}); // $('#btnAccountRegister').click



    
}); // jQuery(function($)

