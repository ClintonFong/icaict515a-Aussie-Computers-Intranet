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
    	document.forms['frmRegisterUser'].frmName.value	    = 'frmRegisterUser';
        document.forms['frmRegisterUser'].actionTaken.value = 'cancel';
        document.forms['frmRegisterUser'].action 			= 'adminUsers.php';
        document.forms['frmRegisterUser'].target            ='_self';
        document.forms['frmRegisterUser'].submit();

    }); // $('#btnCancel').click

	//---------------------------------------------------------------------------------------------
	$('#btnAccountRegister').click( function( event ) 
    {
//        alert("#btnAccountUpdate");
        var bOk = true;
        var strErrorMessage = '';

        // reset all the background colors
        $('#firstname').css('background-color',         resetHighlightColor );
        $('#lastname').css('background-color',          resetHighlightColor );
        $('#email').css('background-color',             resetHighlightColor );
        $('#phone').css('background-color',             resetHighlightColor );
        $('#password').css('background-color',          resetHighlightColor );
        $('#confirmPassword').css('background-color',   resetHighlightColor );


        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmRegisterUser'].firstname.value == '' )
        {
            bOk = false;
            strErrorMessage = '*First name is required\n';
            $('#firstname').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmRegisterUser'].lastname.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Last name is required\n';
            $('#lastname').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmRegisterUser'].email.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Email is required\n';
            $('#email').css('background-color', errorHighlightColor );
        }
      
        if ( !isValidEmail( document.forms['frmRegisterUser'].email.value ) )
        {
            bOk = false;
            strErrorMessage += '*Email is invalid\n';
            $('#email').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmRegisterUser'].phone.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Phone number is required\n';
            $('#phone').css('background-color', errorHighlightColor );
        }



        // check values and mark the ones that needs fixing
        //
        if ( document.forms['frmRegisterUser'].password.value == '' )
        {
            bOk = false;
            strErrorMessage += '*Password is required\n';
            $('#password').css('background-color', errorHighlightColor );
        }

        if ( !isPasswordSecureEnough( document.forms['frmRegisterUser'].password.value ) )
        {
            bOk = false;
            strErrorMessage += '*Password is Not Secure Enough - requires at least 7 characters, with at least 1 number and a letter\n';
            $('#password').css('background-color', errorHighlightColor );
        }

        if ( document.forms['frmRegisterUser'].password.value != document.forms['frmRegisterUser'].confirmPassword.value )
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
            
    	    document.forms['frmRegisterUser'].frmName.value	    = 'frmRegisterUser';
            document.forms['frmRegisterUser'].actionTaken.value = 'register-user';
            document.forms['frmRegisterUser'].action 			= 'adminUsers.php';
            document.forms['frmRegisterUser'].target            ='_self';
            document.forms['frmRegisterUser'].submit();

        }
	
	}); // $('#btnAccountRegister').click



    
}); // jQuery(function($)

