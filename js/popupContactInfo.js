//---------------------------------------------------------------------------------------------
// JQuery functions for forgot password popup
//---------------------------------------------------------------------------------------------

$(document).ready( function() 
{
    //---------------------------------------------------------------------------------------------
	$('tr.displayContactDetails').dblclick(function() 
    {
        var idx  = this.id.replace("contactInfo", "");

        $('#firstname').attr( 'value', ContactInfoListing[idx].firstname );
        $('#lastname').attr( 'value', ContactInfoListing[idx].lastname );
        $('#phone').attr( 'value', ContactInfoListing[idx].phone );
        $('#email').attr( 'value', ContactInfoListing[idx].email );
        $('#department').attr( 'value', ContactInfoListing[idx].deptName );
        $('#accessLevel').attr( 'value', ContactInfoListing[idx].accessLevelStr );

		// Getting the variable's value from a link 
		var contactInfoBox = '#contactInfoBox.contactInfoPopup';

		//Fade in the Popup and add close button
		$(contactInfoBox).fadeIn(300);
		
		//Set the center alignment padding + border
		var popMargTop = ($(contactInfoBox).height() + 24) / 2; 
		var popMargLeft = ($(contactInfoBox).width() + 24) / 2; 
		
		$(contactInfoBox).css(
        { 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		// Add the mask to body
		$('body').append("<div id='mask'></div>");
		$('#mask').fadeIn(300);
		

		return false;

	}); // $('#aContactInfo').click(function() 
	
    //---------------------------------------------------------------------------------------------
    $('a.close').click( function()   // When clicking on the button close (the cross at the top right corner)
    { 
        return closeContactInfoBox();

    }); // $('a.close').click( function()  

    //---------------------------------------------------------------------------------------------
    $('#btnClose').click( function()   
    { 
        return closeContactInfoBox();
    });

}); // $(document).ready( function() 

//---------------------------------------------------------------------------------------------
function closeContactInfoBox()
{
	$('.contactInfoPopup').fadeOut(300 , function() 
    {
		$('#mask').remove();  
	}); 
	return false;

} // closeContactInfoBox()


