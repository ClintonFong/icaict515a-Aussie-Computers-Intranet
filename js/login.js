//---------------------------------------------------------------------------------------------
// JQuery functions for login.php
//---------------------------------------------------------------------------------------------

var toggleIsSeller = false;

jQuery(function($)
{

    var errorHighlightColor = '#FFF8E2';
    var resetHighlightColor = '#ffffff';

    if( $('#registerAttempt').val() == '1' )
    {
        $('#cntSigninBox').css( 'display', 'none' );
        $('#cntRegisterBox').css( 'display', 'block' );

    }


	//---------------------------------------------------------------------------------------------
	$('#btnSignin').click( function( event ) 
    {
        //alert('btnSignin');

        var bOk = true;
        var strErrorMessage = '';

        $('#signinSigninEmail').css('background-color', resetHighlightColor );
        $('#signinPassword').css('background-color',   resetHighlightColor );

        if ( document.forms['frmSignin'].signinEmail.value == '' )
        {
            bOk = false;
            strErrorMessage = '*Sign-in Email is required';
            $('#signinEmail').css('background-color', errorHighlightColor );

        }
        if ( document.forms['frmSignin'].password.value == '' )
        {
            bOk = false;
            strErrorMessage += '\n*Password is required';
            $('#signinPassword').css('background-color', errorHighlightColor );
        }

        if (!bOk)
        {
            alert (strErrorMessage);
        }
        else 
        {
            document.forms['frmSignin'].submit();
        }
	
	}); // $('#btnSignin').click


	//---------------------------------------------------------------------------------------------
	$('#aRegister').click( function( event ) 
    {
        $('#cntSigninBox').css('display', 'none' );
        $('#cntRegisterBox').css('display', 'block');

    }); // $('#aRegister').click

	//---------------------------------------------------------------------------------------------
	$('#aSignin').click( function( event ) 
    {
        $('#cntSigninBox').css('display', 'block');
        $('#cntRegisterBox').css('display', 'none');

    }); // $('#aRegister').click

	//---------------------------------------------------------------------------------------------
    // Register Seller
	//---------------------------------------------------------------------------------------------
    $('#isSeller').click( function( event )
    {
        toggleIsSeller = (!toggleIsSeller);

        if( toggleIsSeller )
        {
            $('#cntRegisterSellerDetails').css('display', 'block' );
            $('#fldsetRegisterSeller').css('height', '205px' );

        }
        else
        {
            $('#cntRegisterSellerDetails').css('display', 'none' );
            $('#fldsetRegisterSeller').css('height', '60px' );
        }
    });
    
}); // $(document).ready(function()


// end JQuery functions
//---------------------------------------------------------------------------------------------

function doForgotPassword()
{
    //alert('doForgetPassword');

    var email = prompt('Please enter your Sign-In Email to Reset your Password');

    if (email != null)
    {
        document.forms['frmForgotPassword'].email.value = email;
        document.forms['frmForgotPassword'].submit();
    }

} // doForgotPassword