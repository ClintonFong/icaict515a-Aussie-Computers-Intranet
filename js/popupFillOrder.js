//---------------------------------------------------------------------------------------------
// JQuery functions for popups for employeeNewOrder.php
//---------------------------------------------------------------------------------------------


jQuery(function($)
{
    
    //---------------------------------------------------------------------------------------------
    // Enter other popup
    //---------------------------------------------------------------------------------------------
    $('#cntEnterOtherPopup a.close').click( function()   // When clicking on the button close (the cross at the top right corner)
    { 
        return $('#btnCancelOther').click();

    }); // $('#cntEnterOtherPopup a.close').click

    //---------------------------------------------------------------------------------------------
    $('#btnCancelOther').click( function()   
    {
	    $('div#cntEnterOtherPopup').fadeOut(300 , function() { $('#mask').remove(); }); 
        $('select#itemName'+idxCurRowItem).val( currentCategoryItemID );
        //$('select#itemName'+idxCurRowItem).val( $('select#itemName'+idxCurRowItem).data('selected') );

        return false;

    }); // $('#btnCancelOther').click


    //---------------------------------------------------------------------------------------------
    $('#btnOKOther').click( function()   // When clicking on the button close (the cross at the top right corner)
    { 
        if( ($('input#itemName').val() == '') || ($('input#itemUnitPrice').val() == '') )
        {
            alert('Please enter values for Item Name and Price');
        }
        else
        {
            // hide popup and remove mask
            //
	        $('div#cntEnterOtherPopup').fadeOut(300 , function() {  $('#mask').remove();  }); 

            // add to the option list
            //
            var optTxt = "<option value='" + idxOtherOption + "' >Other - " + $('input#itemName').val() + "</option>";
            $('select.itemSelect').append(optTxt);
            $('select#itemName'+idxCurRowItem).val(idxOtherOption);

            // get figures
            var fUnitPrice  = parseInt( $('#itemUnitPrice').val(), 10 );
            var nQty        = ($('#qty'+idxCurRowItem).val() != '')? parseInt( $('#qty'+idxCurRowItem).val(), 10 ) : 1;
    

            // Amount
            var fAmount  = nQty * fUnitPrice;

            $('#amount'+idxCurRowItem).val(fAmount.toFixed(2)); 


            // add to arrCategoryItems list
            //
            var structItem = { 'categoryItemID' : idxOtherOption.toString(),
                               'name'           : 'Other - ' + $('input#itemName').val(),
                               'price'          : fUnitPrice.toString() };
            arrCategoryItems.push( structItem );

            idxOtherOption++;

        }

	    return true;

    }); // $('#btnOKOther').click

    // End Enter other popup
    //---------------------------------------------------------------------------------------------


    //---------------------------------------------------------------------------------------------
    // Enter Quote Popup
    //---------------------------------------------------------------------------------------------
    $('#cntEnterQuotePopup a.close').click( function()   // When clicking on the button close (the cross at the top right corner)
    { 
        return $('#btnCancelQuote').click();

    }); // $('cntEnterQuotePopup a.close').click

    //---------------------------------------------------------------------------------------------
    $('#btnCancelQuote').click( function()   
    {
	    $('div#cntEnterQuotePopup').fadeOut(300 , function() { $('#mask').remove(); }); 
        return false;

    }); // $('#btnCancelOther').click
  
    //---------------------------------------------------------------------------------------------
    $('#btnOKQuote').click( function()   // When clicking on the button close (the cross at the top right corner)
    { 
        if( $('input#itemQuotePrice').val() == '' )
        {
            alert('Please enter values for Item Name and Price');
        }
        else
        {
            // hide popup and remove mask
            //
	        $('#cntEnterQuotePopup').fadeOut(300 , function() {  $('#mask').remove();  }); 


            // get figures
            var fUnitPrice  = parseInt( $('#itemQuotePrice').val(), 10 );
            var nQty        = ($('#qty'+idxCurRowItem).val() != '')? parseInt( $('#qty'+idxCurRowItem).val(), 10 ) : 1;
    

            // Amount
            var fAmount  = nQty * fUnitPrice;

            $('#amount'+idxCurRowItem).val(fAmount.toFixed(2)); 


            // add to the option list
            //
            var itemName = $('select#itemName'+idxCurRowItem+' :selected').text() + " - $" + $('input#itemQuotePrice').val() + " Quote";

            var optTxt = "<option value='" + idxOtherOption + "' >" + itemName + "</option>";
            $('select.itemSelect').append(optTxt);
            $('select#itemName'+idxCurRowItem).val(idxOtherOption);


            // add to arrCategoryItems list
            //
            var structItem = { 'categoryItemID' : idxOtherOption.toString(),
                               'name'           : itemName,
                               'price'          : fUnitPrice.toString() };
            arrCategoryItems.push( structItem );

            idxOtherOption++;


        }

	    return true;

    }); // $('#btnOKQuote').click

    // End Enter Quote Popup
    //---------------------------------------------------------------------------------------------


}); // $(document).ready(function()

// end JQuery functions

