//---------------------------------------------------------------------------------------------
// JQuery functions for reviewOrder.php
//---------------------------------------------------------------------------------------------


jQuery(function($)
{
    adjustHeightOfForm();

	//---------------------------------------------------------------------------------------------
    $('#btnReject').click(function( event )
    {
    	document.forms['frmAuthorizeOrder'].frmName.value	    = 'frmAuthorizeOrder';
        document.forms['frmAuthorizeOrder'].actionTaken.value   = 'reject-order';
        document.forms['frmAuthorizeOrder'].action 			    = 'managerAuthorizeOrders.php';
        document.forms['frmAuthorizeOrder'].target              ='_self';
        document.forms['frmAuthorizeOrder'].submit();

    }); // $('#btnClose').click

	//---------------------------------------------------------------------------------------------
	$('#btnApprove').click( function( event ) 
    {
    	document.forms['frmAuthorizeOrder'].frmName.value	    = 'frmAuthorizeOrder';
        document.forms['frmAuthorizeOrder'].actionTaken.value   = 'approve-order';
        document.forms['frmAuthorizeOrder'].action 			    = 'managerAuthorizeOrders.php';
        document.forms['frmAuthorizeOrder'].target              ='_self';
        document.forms['frmAuthorizeOrder'].submit();

    }); // $('#btnApprove').click

    
}); // $(document).ready(function()

// end JQuery functions


//---------------------------------------------------------------------------------------------
function adjustHeightOfForm()
{
    var table               = document.getElementById('tblOrderItemsReview');
    var nHeightRows         = (table.rows.length * 32);
    var nHeightOrderField   = nHeightRows + 200; //231;
    var nHeightForm         = nHeightOrderField + 260;

    $("#fldsetTheOrder").css('height', nHeightOrderField+'px');
    $("#cntOrderForm").css('height', nHeightForm+'px');

    var nMainContentMinHeight = parseInt( $("#cntOrderForm").css('height'), 10 ) + 45;
    if( parseInt( $("#main-content").css('height'), 10) < nMainContentMinHeight  ) 
    {
        $("#main-content").css('height', nMainContentMinHeight+'px' )
    }

} // adjustHeightOfForm




