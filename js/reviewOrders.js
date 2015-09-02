//---------------------------------------------------------------------------------------------
// JQuery functions for reviewOrders.php
//---------------------------------------------------------------------------------------------
var MAX_ORDERS_DISPLAY = 12;
var idxStartNextOrderDisplay = 0;
var idxStartPrevOrderDisplay = 0;

//var idxPagePrevOrderDisplay = 0;
var arrIdxStartPrevOrderDisplay = [];

var json_orders;

jQuery(function($)
{

    navigationHouseKeepingInitial();
    
	//---------------------------------------------------------------------------------------------
	$('table#tblOrdersReview').on('dblclick', 'tr.order', function( event ) 
    {
        var trID        = $(this).attr('id')
        var orderID     = trID.replace(/\D/g,'');
        var revision    = $('tr#'+trID+' td.revision').html();

        //alert( orderID );
        //alert( revision );

    	document.forms['frmReviewOrders'].orderID.value	    = orderID;
    	document.forms['frmReviewOrders'].revision.value	= revision;

    	document.forms['frmReviewOrders'].frmName.value	    = 'frmReviewOrders';
        document.forms['frmReviewOrders'].actionTaken.value = 'review-order';
        document.forms['frmReviewOrders'].action 			= 'reviewOrder.php';
        document.forms['frmReviewOrders'].target            ='_self';
        document.forms['frmReviewOrders'].submit();

    }); // $('table#tblOrdersReview').on('dblclick', 'tr.order', 

	//---------------------------------------------------------------------------------------------
	$('select#filter').change( function( event ) 
    {
        var filter = $('select#filter :selected').val();

        idxStartNextOrderDisplay = 0;    // reset reference to json_orders
        idxStartPrevOrderDisplay = 0;
        arrIdxStartPrevOrderDisplay = [];

        removeAllOrdersTR();
        rebuildNextTRWithFilter( filter );

    }); // $('select#filter').click


	//---------------------------------------------------------------------------------------------
    $('#next').click( function( event )
    {
        var filter = $('select#filter :selected').val();

        removeAllOrdersTR();
        rebuildNextTRWithFilter( filter );

    }); // $('#next').click( function( event )

	//---------------------------------------------------------------------------------------------
    $('#prev').click( function( event )
    {
        var filter = $('select#filter :selected').val();

        arrIdxStartPrevOrderDisplay.pop();                              // clear the current page idx from the array
        idxStartNextOrderDisplay = arrIdxStartPrevOrderDisplay.pop();   // go back to the previous page

        removeAllOrdersTR();
        rebuildNextTRWithFilter( filter );

    }); // $('#next').click( function( event )

    
}); // jQuery(function($)


//---------------------------------------------------------------------------------------------
function removeAllOrdersTR()
{
    try 
    {
        var table           = document.getElementById('tblOrdersReview');
        var idxLastRow      = table.rows.length - 1; // minus the title row
        var nRowsDeleted    = 0;

        for(var i = idxLastRow; i > 0; i--) 
        {
            var row = table.rows[i];
            table.deleteRow(i);
        }

//        $("#cntOrderForm").css('height', '433px');

        idxLastRowItem = -1;
    }
    catch(e) 
    {
        alert(e);
    }

} // removeAllOrdersTR()

//---------------------------------------------------------------------------------------------
function rebuildNextTRWithFilter( filter )
{
    arrIdxStartPrevOrderDisplay.push( idxStartNextOrderDisplay );

    var iOrdersDisplayed = 0; 
     
    for( idxOrderDisplay in json_orders )
    {
        if( parseInt(idxOrderDisplay, 10) < parseInt(idxStartNextOrderDisplay, 10) ) { continue; } // skip past previously viewed orders to starting point

        if( (filter == '') || (json_orders[idxOrderDisplay].status == filter ) )
        {
            addOrderTR( idxOrderDisplay );
            if( ++iOrdersDisplayed >= MAX_ORDERS_DISPLAY ) break;

        }
    }
    idxStartNextOrderDisplay = parseInt(idxOrderDisplay, 10) + 1; // since idxOrderDisplay did not get a chance to increment before exiting the loop

    // setting counters and display of page buttons 
    navigationHouseKeeping();

} // rebuildNextTRWithFilter

//---------------------------------------------------------------------------------------------
function navigationHouseKeepingInitial()
{
    if( json_orders.length > MAX_ORDERS_DISPLAY )     { $('#next').css('display', 'block'); }
    arrIdxStartPrevOrderDisplay.push( 0 ); // initialize prev first page return to idx 

} // navigationHouseKeepingInitial

//---------------------------------------------------------------------------------------------
function navigationHouseKeeping()
{
    // update next button display
    if( idxStartNextOrderDisplay < json_orders.length ) { $('#next').css('display', 'block');   }    // display next button 
    else                                                { $('#next').css('display', 'none');    }    // hide next button 

    // update prev button display
    //
    if( arrIdxStartPrevOrderDisplay.length > 1 )        { $('#prev').css('display', 'block');   }   // display prev button
    else                                                { $('#prev').css('display', 'none');    }   // hide prev button


} // navigationHouseKeeping

//---------------------------------------------------------------------------------------------
function addOrderTR( idxOrder )
{
    var order = json_orders[idxOrder];

    var imgStatus = 'unknown'
    switch( order.status )
    {
        case OS_SUBMITTED : imgStatus = "<img src='images/question-mark.png' alt='pending' class='centered' />"; break;
        case OS_APPROVED  : imgStatus = "<img src='images/tick.png' alt='yes' class='centered' />";              break;
        case OS_REJECTED  : imgStatus = "<img src='images/cross.png' alt='no' class='centered' />";              break;
        case OS_PROCESSED : imgStatus = "<img src='images/processing.png' alt='processing' class='centered' />"; break;
        case OS_SAVED     : imgStatus = "<img src='images/saved.png' alt='saved' class='centered' />";           break;
    }


    var newRow = " \n \
            <tr id='tr" + order.orderID + "' class='order'> \n \
                <td>" + order.date +"</td> \n \
                <td>" + order.orderID +"</td> \n \
                <td>" + order.name +"</td> \n \
                <td>" + order.amount +"</td> \n \
                <td class='revision'>" + order.revision +"</td> \n \
                <td>" + imgStatus +"</td> \n \
            </tr> \n \
            ";
        
	$("#tblOrdersReview tr:last").after( newRow );


} // addItemTR

