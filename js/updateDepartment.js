//---------------------------------------------------------------------------------------------
// JQuery functions for updateUser.php
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
	$('a#close').click( function( event ) 
    {
    	document.forms['frmUpdateDept'].frmName.value	    = 'frmUpdateDept';
        document.forms['frmUpdateDept'].actionTaken.value   = 'close';
        document.forms['frmUpdateDept'].action 			    = 'adminDepartments.php';
        document.forms['frmUpdateDept'].target              ='_self';
        document.forms['frmUpdateDept'].submit();

    }); // $('#btnCancel').click

    
	//---------------------------------------------------------------------------------------------
	$('#btnDeptUpdate').click( function( event ) 
    {
//        alert("#btnAccountUpdate");
        var bOk = true;
        var strErrorMessage = '';

        // reset all the background colors
        $('#deptName').css('background-color',         resetHighlightColor );
        $('#deptBudget').css('background-color',       resetHighlightColor );


        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmUpdateDept'].deptName.value == '' )
        {
            bOk = false;
            strErrorMessage = '*Department name is required\n';
            $('#deptName').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmUpdateDept'].deptBudget.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Last name is required\n';
            $('#deptBudget').css('background-color', errorHighlightColor );
        }

        if (!bOk)
        {
            alert (strErrorMessage);
        }
        else 
        {
            doAjaxUpdateDept(); // make the ajax call to update the department
        }

	}); // $('#btnDeptUpdate').click
    
}); // jQuery(function($)





//---------------------------------------------------------------------------------------------
// AJAX function calls
//---------------------------------------------------------------------------------------------

$.ajaxSetup(
{
    cache: false
});


//---------------------------------------------------------------------------------------------
function doAjaxUpdateDept()
{
//	alert('doAjaxUpdateAccount');
	
	$('#ajaxUpdateDeptMessageResponse').html( 'Updating account...please wait' );

	var dataSend = 	'action=update-department' +
					'&deptID='              + document.forms['frmUpdateDept'].deptID.value       +
					'&deptName='            + document.forms['frmUpdateDept'].deptName.value     +
					'&deptManagerID='       + document.forms['frmUpdateDept'].deptManager.value  +
					'&deptBudget='          + document.forms['frmUpdateDept'].deptBudget.value;
    
    //alert(dataSend );
	$.ajax({
			'type'		:	'POST',
			'url'		: 	'ajaxScripts/ajaxUpdateDepartment.php',
			'data'		:	dataSend,
			'success'	:	function(data) 
                            {
                                //alert('successful');
                                //alert(data);
                                
                                $('#ajaxUpdateDeptMessageResponse').html( stripDataFromAjaxData(data) );

				            }
		   });

} // doAjaxUpdateDept


