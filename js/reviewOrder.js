//---------------------------------------------------------------------------------------------
// JQuery functions for reviewOrder.php
//---------------------------------------------------------------------------------------------


jQuery(function($)
{
    adjustHeightOfForm();

	//---------------------------------------------------------------------------------------------
    $('#btnClose').click(function( event )
    {
    	document.forms['frmReviewOrder'].frmName.value	     = 'frmReviewOrder';
        document.forms['frmReviewOrder'].actionTaken.value   = 'close-order';
        document.forms['frmReviewOrder'].action 			 = 'reviewOrders.php';
        document.forms['frmReviewOrder'].target              ='_self';
        document.forms['frmReviewOrder'].submit();

    }); // $('#btnClose').click


    
}); // $(document).ready(function()

// end JQuery functions


//---------------------------------------------------------------------------------------------
function adjustHeightOfForm()
{
    var table        = document.getElementById('tblOrderItemsReview');
    var nHeight      = (table.rows.length * 31) + 430;

    $("#cntOrderForm").css('height', nHeight+'px');

    var nMainContentMinHeight = parseInt( $("#cntOrderForm").css('height'), 10 ) + 45;
    if( parseInt( $("#main-content").css('height'), 10) < nMainContentMinHeight  ) 
    {
        $("#main-content").css('height', nMainContentMinHeight+'px' )
    }

} // adjustHeightOfForm




