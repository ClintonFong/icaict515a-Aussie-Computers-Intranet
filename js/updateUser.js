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
    	document.forms['frmUpdateUser'].frmName.value	    = 'frmUpdateUser';
        document.forms['frmUpdateUser'].actionTaken.value   = 'close';
        document.forms['frmUpdateUser'].action 			    = 'adminUsers.php';
        document.forms['frmUpdateUser'].target              ='_self';
        document.forms['frmUpdateUser'].submit();

    }); // $('#btnCancel').click

    
	//---------------------------------------------------------------------------------------------
	$('#btnAccountUpdate').click( function( event ) 
    {
//        alert("#btnAccountUpdate");
        var bOk = true;
        var strErrorMessage = '';

        // reset all the background colors
        $('#firstname').css('background-color',         resetHighlightColor );
        $('#lastname').css('background-color',          resetHighlightColor );
        $('#mobilePhone').css('background-color',       resetHighlightColor );


        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmUpdateUser'].firstname.value == '' )
        {
            bOk = false;
            strErrorMessage = '*First name is required\n';
            $('#firstname').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmUpdateUser'].lastname.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Last name is required\n';
            $('#lastname').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmUpdateUser'].phone.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Primary Phone number is required\n';
            $('#phone').css('background-color', errorHighlightColor );
        }

        if (!bOk)
        {
            alert (strErrorMessage);
        }
        else 
        {
            doAjaxUpdateAccount(); // make the ajax call to update the account
        }
	
	

	}); // $('#btnAccountUpdate').click

	//---------------------------------------------------------------------------------------------
	$('#btnPasswordUpdate').click( function( event ) 
    {
        //alert("#btnPasswordUpdate");
        var bOk = true;
        var strErrorMessage = '';

        // reset all the background colors
        $('#oldPassword').css('background-color',       resetHighlightColor );
        $('#newPassword').css('background-color',       resetHighlightColor );
        $('#confirmPassword').css('background-color',   resetHighlightColor );


        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmUpdateUser'].oldPassword.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Old Password is required\n';
            $('#oldPassword').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmUpdateUser'].newPassword.value == '' )
        {
            bOk = false;
            strErrorMessage += '*New Password is required\n';
            $('#newPassword').css('background-color', errorHighlightColor );
        }

        if ( !isPasswordSecureEnough( document.forms['frmUpdateUser'].newPassword.value ) )
        {
            bOk = false;
            strErrorMessage += '*Password is Not Secure Enough - requires at least 7 characters, with at least 1 number and a letter\n';
            $('#newPassword').css('background-color', errorHighlightColor );
        }


        if ( document.forms['frmUpdateUser'].newPassword.value != document.forms['frmUpdateUser'].confirmPassword.value )
        {
            bOk = false;
            strErrorMessage += '*Passwords do not match';
            $('#confirmPassword').css('background-color', errorHighlightColor );
        }

        if (!bOk)
        {
            alert (strErrorMessage);
        }
        else 
        {
            doAjaxUpdatePassword(); // make the ajax call to update the password
        }
	
	}); // $('#btnPasswordUpdate').click

    

    
}); // jQuery(function($)





//---------------------------------------------------------------------------------------------
// AJAX function calls
//---------------------------------------------------------------------------------------------

$.ajaxSetup(
{
    cache: false
});


//---------------------------------------------------------------------------------------------
function doAjaxUpdateAccount()
{
//	alert('doAjaxUpdateAccount');
	
	$('#ajaxUpdateAccountMessageResponse').html( 'Updating account...please wait' );

	var dataSend = 	'action=update-account' +
					'&userID='              + document.forms['frmUpdateUser'].userID.value       +
					'&firstname='           + document.forms['frmUpdateUser'].firstname.value    +
					'&lastname='            + document.forms['frmUpdateUser'].lastname.value     +
					'&email='               + document.forms['frmUpdateUser'].signinEmail.value  +
					'&phone='               + document.forms['frmUpdateUser'].phone.value        +
					'&accessLevel='         + document.forms['frmUpdateUser'].accessLevel.value  +
					'&deptID='              + document.forms['frmUpdateUser'].department.value;
    
    //alert(dataSend );
	$.ajax({
			'type'		:	'POST',
			'url'		: 	'ajaxScripts/ajaxUpdateUser.php',
			'data'		:	dataSend,
			'success'	:	function(data) 
                            {
                                //alert('successful');
                                //alert(data);
                                
                                $('#ajaxUpdateAccountMessageResponse').html( stripDataFromAjaxData(data) );

				            }
		   });

} // doAjaxUpdateAccount


//---------------------------------------------------------------------------------------------
function doAjaxUpdatePassword()
{
	//alert('doAjaxUpdatePassword');
	
	$('#ajaxUpdatePasswordMessageResponse').html( 'Updating password...please wait' );
    
	var dataSend = 	'action=update-password'    +
					'&userID='                  + document.forms['frmUpdateUser'].userID.value          +
                    '&oldPassword='             + document.forms['frmUpdateUser'].oldPassword.value     +
                    '&newPassword='             + document.forms['frmUpdateUser'].newPassword.value;

    
    //alert(dataSend );
	$.ajax({
			'type'		:	'POST',
			'url'		: 	'ajaxScripts/ajaxUpdateUser.php',
			'data'		:	dataSend,
			'success'	:	function(data) 
                            {
                                //alert('successful');
                                //alert(data);
                                $('#ajaxUpdatePasswordMessageResponse').html( stripDataFromAjaxData(data) );
				            }
		   });

} // doAjaxUpdatePassword




