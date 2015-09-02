//---------------------------------------------------------------------------------------------
// JQuery functions for all ...Views.php
//---------------------------------------------------------------------------------------------

// order status
//-------------
var OS_SUBMITTED    = "0";
var OS_APPROVED     = "1";
var OS_REJECTED     = "-1";
var OS_PROCESSED    = "2";
var OS_SAVED        = "-2";


var PHONE_EXT_MAX_LENGTH = 10;

jQuery(function($)
{
    //---------------------------------------------------------------------------------------------
    // top menu functions
    //---------------------------------------------------------------------------------------------

    //---------------------------------------------------------------------------------------------
    $('#logout').click( function(event)
    {
		document.forms['frmTopMenuItems'].action 			= 'login.php';
        document.forms['frmTopMenuItems'].target            ='_self';
        document.forms['frmTopMenuItems'].submit();

    }); // $('#logout').click


    //---------------------------------------------------------------------------------------------
    // keypress functions
    //---------------------------------------------------------------------------------------------

    $('.isValidNormalCharKey').keypress( function(event)
    {
        return isValidNormalCharKey(event);
    });

    $('.isIntegerKey').keypress( function(event)
    {
        return isIntegerKey(event);
    });

    $('.isFloatKey').keypress( function(event)
    {
        return isFloatKey(event);
    });

    $('.isMoneyKey').keypress( function(event)
    {
        return isMoneyKey(event);
    });

    $('.isPhoneNumberKey').keypress( function(event)
    {
        return isPhoneNumberKey(event);
    });

    $('.isPhoneExtKey').keypress( function(event)
    {
        return isPhoneExtKey(event);
    });

});

//
// ascii codes : 
//   8 = BACKSPACE
//  32 = ' ' // space
//  40 = '('
//  41 = ')'
//  43 = '+'
//  46 = '.'
//  48 = '0'    57 = '9'
//  64 = '@'
//  65 = 'A'    90 = 'Z'
//  96 = '.'
//  97 = 'a'    122 = 'Z'
//


//---------------------------------------------------------------------------------------------
// isValidNormalCharKey
//
// Description: allows only valid normal character keys input 0-9, a-z, and A-Z
//---------------------------------------------------------------------------------------------
function isValidNormalCharKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    if (  (charCode ==  8)  ||                          // backspace
          (charCode ==  32) ||                          // space
         ((charCode  >= 48) && (charCode <= 57)) ||     // 0-9
         ((charCode  >= 65) && (charCode <= 90)) ||     // A-Z
         ((charCode  >= 97) && (charCode <= 122))       // a-z
         )
    {
        return true;
    }

    return false;

} // isValidNormalCharKey


//---------------------------------------------------------------------------------------------
// isIntegerKey
//
// Description: allows only integer input
//---------------------------------------------------------------------------------------------
function isIntegerKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    if (  (charCode !=  8) && 
         ((charCode  < 48) || (charCode > 57)) )
    {
        return false;
    }

    return true;

} // isIntegerKey


//---------------------------------------------------------------------------------------------
// isFloatKey
//
// Description: allows only float input
//---------------------------------------------------------------------------------------------
function isFloatKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    // check for extra decimal point
    //
    if( charCode == 46 ) // decimal point
    {
        if ( !(evt.target.value.indexOf('.') === -1) )
        { 
            return false;   // already found a decimal 
        }
    }

    // check if valid keystroke
    //
    if (  (charCode != 46) && (charCode > 31) &&
         ((charCode  < 48) || (charCode > 57) ) )
    {
        return false;
    }

    return true;

} // isFloatKey



//---------------------------------------------------------------------------------------------
// isMoneyKey
//
// Description: allows only float input
//---------------------------------------------------------------------------------------------
function isMoneyKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    // check for extra decimal point
    //
    if( charCode == 46 ) // decimal point
    {
        if ( !(evt.target.value.indexOf('.') === -1) )
        { 
            return false;   // already found a decimal 
        }
    }

    // check if valid keystroke
    //
    if (  (charCode != 46) && (charCode > 31) &&
         ((charCode  < 48) || (charCode > 57) ) )
    {
        return false;
    }

    // check for no more than 2 decimal places
    //
    if( charCode != 8 ) // only valid key here is backspace
    {
        integer     = evt.target.value.split('.')[0];
        mantissa    = evt.target.value.split('.')[1];

        if (typeof mantissa === 'undefined')    { mantissa = ''; }
        if (mantissa.length >= 2)               { return false;  }  // already exceeded number of decimal places
    }
        
    return true;

} // isMoneyKey



//---------------------------------------------------------------------------------------------
// isPhoneNumberKey
//
// Description: allows only phone number input
//---------------------------------------------------------------------------------------------
function isPhoneNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;
    
    if( charCode != 8 ) // only valid key here is backspace
    {
        if (  (charCode != 40) && (charCode > 31) && (charCode != 41) && (charCode != 43) && (charCode != 32) && 
             ((charCode  < 48) || (charCode > 57) ) )
        {
            return false;
        }
    }
    return true;

} // isPhoneNumberKey

//---------------------------------------------------------------------------------------------
// isPhoneExtKey
//
// Description: allows only phone number input
//---------------------------------------------------------------------------------------------
function isPhoneExtKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode;

    if( charCode != 8 ) // only valid key here is backspace
    {

        if (  (charCode != 40) && (charCode > 31) && (charCode != 41) && (charCode != 43) && (charCode != 32) && 
             ((charCode  < 48) || (charCode > 57) ) )
        {
            return false;
        }

        // check if 5 or less characters
        if( evt.target.value.length >= PHONE_EXT_MAX_LENGTH )
        {
            return false; 
        }
    }

    return true;

} // isPhoneExtKey

//---------------------------------------------------------------------------------------------
function truncatePhoneExt( phoneNo ) 
{
    return phoneNo.substring(0, Math.min(PHONE_EXT_MAX_LENGTH, phoneNo.length));

} // truncatePhoneExt

//---------------------------------------------------------------------------------------------
// Checks
//---------------------------------------
function isValidEmail( email )
{
    //var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //return regex.test(email);
    return true;

} // isValidEmail

//---------------------------------------
function isPasswordSecureEnough( password )
{
    var bSecureEnough = true;

    if( password.length < 7 )    { bSecureEnough = false; }
    else 
    {
        var bHasNumber = false;
        var bHasLetter = false;
        for( var i=0; i < password.length; i++ )
        {
            var c = password.charAt(i);
            if( $.isNumeric(c) )        { bHasNumber = true; }
            if( c.match(/[a-z]/i) )     { bHasLetter = true; }
        }
        bSecureEnough = ( bHasNumber && bHasLetter );
    }
    return bSecureEnough;

} // isPasswordSecureEnough



//---------------------------------------------------------------------------------------------
// XML with Ajax data
//---------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------
function stripMessageFromAjaxData( data )
{
    return getXMLDoc( data ).getElementsByTagName('message')[0].childNodes[0].nodeValue;

} // stripMessageFromAjaxData

//---------------------------------------------------------------------------------------------
function stripDataFromAjaxData( data )
{
    return getXMLDoc( data ).getElementsByTagName('data')[0].childNodes[0].nodeValue ;

} // stripDataFromAjaxData

//---------------------------------------------------------------------------------------------
function stripIDFromAjaxData( data )
{
    return getXMLDoc( data ).getElementsByTagName('id')[0].childNodes[0].nodeValue ;

} // stripIDFromAjaxData

//---------------------------------------------------------------------------------------------
// getXMLDoc
//
// Description: Parses and returns the XML Document for a given XML string
//              Extracted some of the code from http://www.w3schools.com/xml/xml_parser.asp
//---------------------------------------------------------------------------------------------
function getXMLDoc( data )
{
    var xmlDoc;

    if (window.DOMParser)
    {
        var parser  = new DOMParser();
        xmlDoc      = parser.parseFromString(data,'text/xml');
    }
    else // Internet Explorer
    {
        xmlDoc          = new ActiveXObject('Microsoft.XMLDOM');
        xmlDoc.async    = false;
        xmlDoc.loadXML(data);
    } 
    return xmlDoc;

} // getXMLDoc

//-------------------------------------------------------------------------------------------------------
// xmlToString - taken from http://stackoverflow.com/questions/6507293/convert-xml-to-string-with-jquery
//
// Description: converts xml data to a string
//-------------------------------------------------------------------------------------------------------
function xmlToString( xmlData ) 
{ 

    var xmlString;
    //IE
    if( window.ActiveXObject )
    {
        xmlString = xmlData.xml;
    }
    else // code for Mozilla, Firefox, Opera, etc.
    {
        xmlString = (new XMLSerializer()).serializeToString(xmlData);
    }
    return xmlString;

} // xmlToString
