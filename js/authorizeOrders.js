//---------------------------------------------------------------------------------------------
// JQuery functions for authorizeOrders.php
//---------------------------------------------------------------------------------------------
var json_orders;

jQuery(function($)
{

	//---------------------------------------------------------------------------------------------
	$('table#tblPendingOrders').on('dblclick', 'tr.order', function( event ) 
    {
        var trID        = $(this).attr('id')
        var orderID     = trID.replace(/\D/g,'');
        var revision    = $('tr#'+trID+' td.revision').html();

        //alert( orderID );
        //alert( revision );

    	document.forms['frmAuthorizeOrders'].orderID.value	    = orderID;
    	document.forms['frmAuthorizeOrders'].revision.value	    = revision;

    	document.forms['frmAuthorizeOrders'].frmName.value	    = 'frmAuthorizeOrders';
        document.forms['frmAuthorizeOrders'].actionTaken.value  = 'authorize-order';
        document.forms['frmAuthorizeOrders'].action 		    = 'managerAuthorizeOrder.php';
        document.forms['frmAuthorizeOrders'].target             = '_self';
        document.forms['frmAuthorizeOrders'].submit();

    }); // $('table#tblPendingOrders').on('dblclick', 'tr.order', 

    
}); // jQuery(function($)

