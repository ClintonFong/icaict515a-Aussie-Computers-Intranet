//---------------------------------------------------------------------------------------------
// JQuery functions for employeeNewOrder.php
//---------------------------------------------------------------------------------------------

var arrFinalOrderItems;
var arrCategoryItems;

var MAX_ROW_ITEMS           = 10;
var idxCurRowItem           = 0;
var idxLastRowItem          = 0;
var idxOtherOption          = 1000;

var currentCategoryID       = -1;
var currentCategoryItemID   = -1;

jQuery(function($)
{
    calculateTRItemAmount( idxLastRowItem ); // calculates the amount for the first item
    recalculateTotal(); 

    currentCategoryID = $('select#orderCategory :selected').val();

	//---------------------------------------------------------------------------------------------
    $('#cntOrderItems').on('keypress', '.isIntegerKey', function( event )  // for dynamically created items on order form
    {
        return isIntegerKey(event);  // in main.js
    });

	//---------------------------------------------------------------------------------------------
    $('a#changeManager').click( function( event )
    {
        if( confirm("Are you sure you wish to select another manager who isn't your department line manager?") )
        {
            $('#manager').attr('disabled', false );
            $('a#changeManager').css('display', 'none');
        }
    });

	//---------------------------------------------------------------------------------------------
    $('#cntOrderItems').on('focus', 'select.itemSelect', function( event ) 
    {
        //var idx = event.target.parentNode.id;
        //idxCurRowItem = idx = idx.replace(/\D/g,'');
        //currentCategoryItemID = $('select#itemName'+idxCurRowItem+' :selected').val();

        //$('select#itemName'+idxCurRowItem).data('selected', $(this).val() );

        currentCategoryItemID = $(this).val();

    }); // $('#cntOrderItems').on('focus', 'select.itemSelect', 
    
	//---------------------------------------------------------------------------------------------
	//$('select.itemSelect').change( function( event ) 
    $('#cntOrderItems').on('change', 'select.itemSelect', function( event ) 
    {
        var idx = event.target.parentNode.id;
        idxCurRowItem = idx = idx.replace(/\D/g,'');

        //alert( $(this).data('selected') ); 
        //alert( currentCategoryItemID );

        if( event.target.value == '0' ) { openEnterFieldDlg( '#cntEnterOtherPopup' ); }
        else 
        {
            // Unit price
            //
            var fUnitPrice = getUnitPrice( event.target.value );

            if( fUnitPrice == 0.0 )     { openEnterFieldDlg( '#cntEnterQuotePopup' ); }
            else
            {
                // Set Quantity to 1 if nothing
                if( $('#qty'+idx).val() == '' ) { $('#qty'+idx).val('1'); }

                // Amount
                var nQty     = parseInt( $('#qty'+idx).val(), 10 );
                var fAmount  = nQty * fUnitPrice;

                $('#amount'+idx).val(fAmount.toFixed(2)); 
            }
        }

        // need to blur to make it lose focus otherwise currentCategoryItemID from on focus does not get set
        $('#cntOrderItems select.itemSelect').blur();

	}); // $('#cntOrderItems').on('change', 'select.itemSelect',


	//---------------------------------------------------------------------------------------------
//	$('.inputQty').keyup( function( event ) 
    $('#cntOrderItems').on('keyup', '.inputQty', function( event ) 
    {
        var charCode = (event.which) ? event.which : event.keyCode;

        if( charCode == 13 )
        {
            $('.inputQty').blur();
        }

    }); // $('.inputQty').keyup

	//---------------------------------------------------------------------------------------------
//	$('.inputQty').blur( function( event ) 
    $('#cntOrderItems').on('blur', 'input.inputQty', function( event ) 
    {
        var idx = event.target.parentNode.id;
        idx = idx.replace(/\D/g,'');

        // Unit price
        //
        var fUnitPrice = getUnitPrice( $('#itemName'+idx).val() );

        // Quantity
        if( event.target.value == '' ) { event.target.value = 1; }
        var nQty = parseInt( event.target.value, 10 );


        // Amount
        var fAmount  = nQty * fUnitPrice;

        $('#amount'+idx).val(fAmount.toFixed(2)); 


    }); // $('.inputQty').blur

	//---------------------------------------------------------------------------------------------
    $('#cntOrderItems').on('click', 'input.amount', function( event ) 
    {
        var idx = event.target.parentNode.id;
        idx = idx.replace(/\D/g,'');
        idxCurRowItem = idx;

        //alert( $(this).val() );
        if( ( $(this).val() == '0.00' ) && ( parseInt($('#qty'+idx).val(), 10) > 0 )) 
        { 
            openEnterFieldDlg( '#cntEnterQuotePopup' );   
            $('#itemQuotePrice').focus();
        }
        else                            
        { 
            $(this).blur();                               
        }
    });

	//---------------------------------------------------------------------------------------------
    $("a#addItem").click(function()
    {
        addItemTR();

	}); // $("a#addItem").click

	//---------------------------------------------------------------------------------------------
    $("a#removeItem").click(function()
    {
        try 
        {
            var table           = document.getElementById('tblOrderItemsNew');
            var idxLastRow      = table.rows.length - 2; // since last row is the total
            var nRowsDeleted    = 0;
 
            for(var i = idxLastRow; i > 0; i--) 
            {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];

                if(null != chkbox && true == chkbox.checked) 
                {
                    table.deleteRow(i);
                    nRowsDeleted++;
                    idxLastRowItem--;    
                }
            }
            var nHeight = parseInt( $("#cntOrderForm").css('height'), 10 ) - (nRowsDeleted * 31);
            $("#cntOrderForm").css('height', nHeight+'px');

            recalculateTotal(); 
        }
        catch(e) 
        {
            alert(e);
        }

    }); // $("a#removeItem").click

    //---------------------------------------------------------------------------------------------
    $("a#recalculate").click(function()
    {
        recalculateTotal(); 
    });

    //---------------------------------------------------------------------------------------------
    // Changing Category
    //---------------------------------------------------------------------------------------------
    $('#cntOrderCategory').on('change', 'select#orderCategory', function( event )
    {
        var strMsg  = "By Changing 'Categories', the form will be refreshed to cater for the items in the newly selected category, ";
            strMsg += "and you will lose items already selected in the current category.\n\n";
            strMsg += "Press 'OK' to continue, 'Cancel' to remain on current category";

        if( !confirm(strMsg) )  { $(this).val( currentCategoryID );   }
        else                    
        { 
            currentCategoryID = $(this).val();
            doAjaxChangeToCategory( currentCategoryID );
        }

    }); //  $('#cntOrderCategory').on('change', 'select#orderCategory'

	//---------------------------------------------------------------------------------------------
    $('#btnCancel').click(function( event )
    {
    	document.forms['frmSubmitNewOrder'].frmName.value	    = 'frmSubmitNewOrder';
        document.forms['frmSubmitNewOrder'].actionTaken.value   = 'cancel-order';
        document.forms['frmSubmitNewOrder'].action 			    = 'employee.php';
        document.forms['frmSubmitNewOrder'].target              ='_self';
        document.forms['frmSubmitNewOrder'].submit();

    }); // $('#btnCancel').click

	//---------------------------------------------------------------------------------------------
    $('#btnSubmit').click(function( event )
    {
        //alert( idxCurRowItem );
        if( finalizeOrderItems() )
        {
            // enable attributes to allow this attribute to be posted with the submit
            $('#totalAmount').attr('disabled', false ); 
            $('#manager').attr('disabled', false ); 

    	    document.forms['frmSubmitNewOrder'].frmName.value	    = 'frmSubmitNewOrder';
            document.forms['frmSubmitNewOrder'].actionTaken.value   = 'submit-new-order';
            document.forms['frmSubmitNewOrder'].action 			    = 'employee.php';
            document.forms['frmSubmitNewOrder'].target              ='_self';
            document.forms['frmSubmitNewOrder'].submit();
        }
        else
        {
            alert( "Please re-check the quantity and amounts for your order");
        }

    });

    
}); // $(document).ready(function()

// end JQuery functions

//---------------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------
function calculateTRItemAmount( idxRowItem )
{
//    alert( idxRowItem  );
//    alert( $('#itemName'+idxRowItem).val() );

    // Unit price
    //
    var fUnitPrice = getUnitPrice( $('#itemName'+idxRowItem).val() );

    // Set Quantity to 1 if nothing
    //
    if( $('#qty'+idxRowItem).val() == '' ) { $('#qty'+idxRowItem).val('1'); }

    // Amount
    var nQty     = parseInt( $('#qty'+idxRowItem).val(), 10 );
    var fAmount  = nQty * fUnitPrice;

    $('input#amount'+idxRowItem).val(fAmount.toFixed(2)); 


} // calculateTRItemAmount

//---------------------------------------------------------------------------------------------

function getUnitPrice( itemID )
{
    var fUnitPrice = 0.0;

    // find idx of itemID 
    //
    var foundIdx = -1;
    for ( var idx in arrCategoryItems )
    {
        if( arrCategoryItems[idx].categoryItemID == itemID )
        {
            foundIdx = idx;
            break;
        }
    }

    // get the price at idx location in the array
    //
    if( foundIdx >= 0 )
    {
        fUnitPrice = arrCategoryItems[foundIdx].price;
    }

    return fUnitPrice;

} // getUnitPrice


//---------------------------------------------------------------------------------------------
function recalculateTotal()
{
    try 
    {
        var table           = document.getElementById('tblOrderItemsNew');
        var idxLastRow      = table.rows.length-1;
        var nRowsDeleted    = 0;
        var fTotal          = 0.0;
 
        for(var i=1; i < idxLastRow; i++) // skip the first(header) and last(total) row
        {
            var row         = table.rows[i];
            var inputBox    = row.cells[3].childNodes[0];
            if(null != inputBox) 
            {
                fTotal += parseFloat( inputBox.value );
            }
        }

        table.rows[idxLastRow].cells[1].childNodes[0].value = fTotal.toFixed(2);

    }
    catch(e) 
    {
        alert(e);
    }

} // recalculateTotal


//---------------------------------------------------------------------------------------------
function openEnterFieldDlg( divID ) 
{
		
	// Getting the variable's value from an ID that is passed in 
	var cntEnterPopup = divID; 

	//Fade in the Popup and add close button
	$(cntEnterPopup).fadeIn(300);
		
	//Set the center alignment padding + border
	var popMargTop = ($(cntEnterPopup).height() + 24) / 2; 
	var popMargLeft = ($(cntEnterPopup).width() + 24) / 2; 
		
	$(cntEnterPopup).css(
    { 
		'margin-top' : -popMargTop,
		'margin-left' : -popMargLeft
	});
		
	// Add the mask to body
	$('body').append("<div id='mask'></div>");
	$('#mask').fadeIn(300);
		
	return false;

} // openEnterFieldDlg
	
//---------------------------------------------------------------------------------------------
function buildOptionList()
{
    var optList = '';

    //alert( arrCategoryItems )
    for( var idx in arrCategoryItems )
    {
        optList += "<option value='" + arrCategoryItems[idx].categoryItemID.toString() +"'>" + arrCategoryItems[idx].name +"</option>\n";
    }
    optList += "<option value='0'>Other</option>\n";
    return optList;

} // buildOptionList

//---------------------------------------------------------------------------------------------
function removeAllItemTR()
{
    try 
    {
        var table           = document.getElementById('tblOrderItemsNew');
        var idxLastRow      = table.rows.length - 2; // since last row is the total
        var nRowsDeleted    = 0;

        for(var i = idxLastRow; i > 0; i--) 
        {
            var row = table.rows[i];
            table.deleteRow(i);
        }
        $("#cntOrderForm").css('height', '433px');

        idxLastRowItem = -1;
    }
    catch(e) 
    {
        alert(e);
    }

} // removeAllItemTR

//---------------------------------------------------------------------------------------------
function addItemTR()
{
    if( idxLastRowItem < ( MAX_ROW_ITEMS - 1) ) // since idxLastRowItem starts from 0 and MAX_ROW_ITEMS is counting from 1.
    {
        idxLastRowItem++;

        var displayCheckbox = (idxLastRowItem == 0)? '' : "<input  name='isChecked" + idxLastRowItem + "'       type='checkbox'>";
        var newRow = " \n \
                <tr> \n \
                    <td id='tdIsChecked" + idxLastRowItem + "'>" + displayCheckbox + "</td> \n \
                    <td id='tdItemName" +idxLastRowItem + "'> \n \
                        <select name='itemName" + idxLastRowItem + "' id='itemName" + idxLastRowItem + "' class='itemSelect'> \n \
                            " + buildOptionList() + " \n \
                        </select> \n \
                    </td> \n \
                    <td id='tdQty" + idxLastRowItem + "'><input name='qty" + idxLastRowItem + "' id='qty" + idxLastRowItem + "' type='text' value='' class='inputQty isIntegerKey'></td> \n \
                    <td id='tdAmount" + idxLastRowItem + "'><input name='amount" + idxLastRowItem + "' id='amount" + idxLastRowItem + "' type='text' value='' class='amount'></td> \n \
                </tr> \n \
                ";
        
		$("#tblOrderItemsNew tr:last").before( newRow );    // insert the item row


        // adjust relevant visual form/container heights/size
        //
        var nHeight = parseInt( $("#cntOrderForm").css('height'), 10 ) + 32;
        $("#cntOrderForm").css('height', nHeight+'px');

        var nMainContentMinHeight = parseInt( $("#cntOrderForm").css('height'), 10 ) + 45;
        if( parseInt( $("#main-content").css('height'), 10) < nMainContentMinHeight  ) 
        {
            $("#main-content").css('height', nMainContentMinHeight+'px' )
        }
        
        // do relevant calculations of amounts
        //
        calculateTRItemAmount( idxLastRowItem );
        recalculateTotal(); 
    }

} // addItemTR

//---------------------------------------------------------------------------------------------
function finalizeOrderItems()
{
    var bSuccess = true;
    arrFinalOrderItems = [];

    // traverse table
    var table           = document.getElementById('tblOrderItemsNew');
    var idxLastRow      = table.rows.length-1;
    var fTotal          = 0.0;
 
    for(var i=1; bSuccess && (i < idxLastRow); i++) // skip the first(header) and last(total) row
    {
        // initialize values
        //
        var orderItemID     = 0;   
        var orderItemName   = '';
        var qty             = 0;
        var amount          = 0.0;

        // extrapolate the values
        var row         = table.rows[i];
        
        // item id & name
        var selectID    = row.cells[1].childNodes[1].id;
        if( null != selectID )
        {
            orderItemID     = $('#'+selectID+' :selected').val();
            orderItemName   = $('#'+selectID+' :selected').text();
        }

        // item qty
        var inputBoxQty = row.cells[2].childNodes[0];
        if( null != inputBoxQty )
        {
            qty = parseInt( inputBoxQty.value, 10 );
        }

        // item amount
        var inputBoxAmount = row.cells[3].childNodes[0];
        if(null != inputBoxAmount) 
        {
            amount  = parseFloat( inputBoxAmount.value );
            fTotal += amount;
        }

        bSuccess = ( (parseInt(orderItemID, 10) > 0) && (orderItemName != '') && (qty > 0) && (amount > 0.0) )

        if( bSuccess )
        {
            var structOrderItem = { 'orderItemID' : orderItemID,
                                    'name'        : orderItemName,
                                    'qty'         : qty,
                                    'amount'      : amount };
            arrFinalOrderItems.push( structOrderItem );
        }
    }

    table.rows[idxLastRow].cells[1].childNodes[0].value = fTotal.toFixed(2);

    if( bSuccess )
    {
        document.forms['frmSubmitNewOrder'].totalAmount.value       = fTotal.toFixed(2);
        document.forms['frmSubmitNewOrder'].json_orderItems.value   = JSON.stringify( arrFinalOrderItems );
    }

    return bSuccess;

} // finalizeOrderItems

//---------------------------------------------------------------------------------------------
// AJAX function calls
//---------------------------------------------------------------------------------------------

$.ajaxSetup(
{
    cache: false
});


//---------------------------------------------------------------------------------------------
function doAjaxChangeToCategory( categoryID )
{
//	alert('doAjaxForgotPassword');
	
	document.getElementById('ajaxChangeToCategoryMessageResponse').innerHTML = 'Please wait...';
    
	var dataSend = 	'action=change-category' +
					'&categoryID='           + categoryID; 
    
    //alert(dataSend );
	$.ajax({
			'type'		:	'POST',
			'url'		: 	'ajaxScripts/ajaxCategory.php',
			'data'		:	dataSend,
			'success'	:	function(data) 
                            {
                                //alert('successful');
                                //alert(data);
                                $('#ajaxChangeToCategoryMessageResponse').html('');
                                
                                if( stripMessageFromAjaxData( data ) == 'Success' )
                                {
                                    arrCategoryItems = jQuery.parseJSON(stripDataFromAjaxData(data) );
                                    removeAllItemTR();
                                    addItemTR();

                                }
                                else
                                {
                                   // should not come here, if so there was a an error....
                                   $('#ajaxChangeToCategoryMessageResponse').css('color', '#ff0000' );
                                   $('#ajaxChangeToCategoryMessageResponse').html('Server Error...Request for new category unsuccessful.');
                                }                                
				            }
		   });

} // doAjaxChangeToCategory


