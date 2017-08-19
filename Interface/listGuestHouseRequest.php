<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseRequest');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Request Guest House Allocation </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('bookingNo','Booking No.','width="5%"','',true),
                                new Array('guestName','Guest Name','width="10%"','',true),
                                new Array('hostelName','Guest House','width="8%"','',true),
                                new Array('roomName','Room','width="5%"','',true),
                                new Array('arrivalDate','Arr. Date','width="7%"','align="center"',true),
                                new Array('aTime','Arr. Time','width="7%"','align="center"',true),
                                //new Array('arrivalTime','Time','width="5%"','align="center"',false),
                                new Array('departureDate','Dep. Date','width="8%"','align="center"',true),
                               // new Array('departureTime','Time','width="5%"','align="center"',false),
                                new Array('dTime','Dep. Time','width="7%"','align="center"',true),
                                new Array('isAllocated','Status','width="7%"','',true),
                                new Array('actionString','Action','width="2%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGuestHouse';
editFormName   = 'EditGuestHouse';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGuestHouse';
divResultName  = 'results';
page=1; //default page
sortField = 'bookingNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,mode) {
    if(mode==3){
       if(!confirm("Do you want to cancel this booking?")){
           return false;
       }
       cancelBooking(id);
    }
    else{
     displayWindow('GuestHouseDiv',315,210);
     populateValues(id,mode);
    }
}

function cancelBooking(id){
        var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/cancelBooking.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId  : id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d');?>";
function validateAddForm(frm) {

    var fieldsArray = new Array(
        new Array("guestName","<?php echo ENTER_GUEST_NAME;?>"),
        new Array("startTime","<?php echo ENTER_START_TIME ?>"),
        new Array("endTime","<?php echo ENTER_END_TIME ?>"),
        new Array("budgetHead","<?php echo SELET_BUDGET_HEAD;?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='guestName' ) {
                messageBox("<?php echo GUEST_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
    }
   if(document.guestHouse.alternativeArrangement.disabled==true && document.guestHouse.bookingId.value!='') { 
    if(!isAlphaNumericdot(document.getElementById('startTime').value)) {
            messageBox("<?php echo ACCEPT_INTEGER ?>");
            document.getElementById('startTime').focus();
            return false;

    }
    if (!isTime2(document.getElementById('startTime').value)) {
        messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
        document.getElementById('startTime').focus();
        return false;
    }
    if (trim(document.getElementById('startTime').value)=='00:00' || trim(document.getElementById('startTime').value)=='0:00' || trim(document.getElementById('startTime').value)=='00:0' || trim(document.getElementById('startTime').value)=='0:0') {
        messageBox("Invalid time");
        document.getElementById('startTime').focus();
        return false;
    }
  
    if(!isAlphaNumericdot(document.getElementById('endTime').value)) {
            messageBox("<?php echo ACCEPT_INTEGER ?>");
            document.getElementById('endTime').focus();
            return false;
    }
    if (!isTime2(document.getElementById('endTime').value)) {
        messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
        document.getElementById('endTime').focus();
        return false;
    }
    if (trim(document.getElementById('endTime').value)=='00:00' || trim(document.getElementById('endTime').value)=='0:00' || trim(document.getElementById('endTime').value)=='00:0' || trim(document.getElementById('endTime').value)=='0:0') {
        messageBox("Invalid time");
        document.getElementById('endTime').focus();
        return false;
    }
    if(!dateDifference(document.getElementById('arrivalDate').value,document.getElementById('departureDate').value,'-')){
        messageBox("<?php echo ARRIVAL_DEPARTURE_DATE_RESTRICTION;?>");
        document.getElementById('arrivalDate').focus();
        return false;
    }
   }

    if(document.guestHouse.bookingId.value==''){
        addGuestHouse();
        return false;
    }
    if(document.guestHouse.bookingId.value!=''){
        if(document.guestHouse.alternativeArrangement.disabled==false){
            if(trim(document.guestHouse.alternativeArrangement.value)==''){
                messageBox("<?php echo ENTER_ALTERNATIVE_ARRANGEMENT;?>");
                document.guestHouse.alternativeArrangement.focus();
                return false;
            }
        }
        editGuestHouse();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addGuestHouse() {
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 guestName     : trim(document.guestHouse.guestName.value),
                 arrivalDate   : (document.getElementById('arrivalDate').value),
                 departureDate : (document.getElementById('departureDate').value),
                 budgetHead    : (document.guestHouse.budgetHead.value),
                 startTime     : (document.guestHouse.startTime.value),
                 startAmPm     : (document.guestHouse.startAmPm.value),
                 endTime       : (document.guestHouse.endTime.value),
                 endAmPm       : (document.guestHouse.endAmPm.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('GuestHouseDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.guestHouse.guestName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
// id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteGuestHouse(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {

         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else {
                         messageBox(trim(transport.responseText));
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addGuestHouse" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.guestHouse.reset();
   document.guestHouse.bookingId.value='';
   document.guestHouse.guestName.disabled  = false;
   document.guestHouse.startTime.disabled  = false;
   document.guestHouse.startAmPm.disabled  = false;
   document.guestHouse.endTime.disabled    = false;
   document.guestHouse.endAmPm.disabled    = false;
   document.guestHouse.budgetHead.disabled = false;
   document.guestHouse.alternativeArrangement.disabled = true;
   document.getElementById('alterArrTrId').style.display='none';
   document.guestHouse.guestName.focus();
   document.getElementById('bookingNumRowId').style.display='none';
   document.getElementById('divHeaderId').innerHTML='&nbsp;Add Request for Guest House';
   document.getElementById('reasonTrId').style.display='none';
   document.getElementById('rejectionReason').innerHTML='';
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editGuestHouse() {
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxInitEdit.php';
         var alternativeArrangement='';
         if(document.guestHouse.alternativeArrangement.disabled==false){
             alternativeArrangement=trim(document.guestHouse.alternativeArrangement.value);
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId  : (document.guestHouse.bookingId.value),
                 guestName     : (document.guestHouse.guestName.value),
                 arrivalDate   : (document.getElementById('arrivalDate').value),
                 departureDate : (document.getElementById('departureDate').value),
                 budgetHead    : (document.guestHouse.budgetHead.value),
                 startTime     : (document.guestHouse.startTime.value),
                 startAmPm     : (document.guestHouse.startAmPm.value),
                 endTime       : (document.guestHouse.endTime.value),
                 endAmPm       : (document.guestHouse.endAmPm.value),
                 alternativeArrangement : alternativeArrangement
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('GuestHouseDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.guestHouse.guestName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "editGuestHouse" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id,mode) {
         document.guestHouse.reset();
         document.getElementById('divHeaderId').innerHTML='&nbsp;Edit Request for Guest House';
         document.getElementById('reasonTrId').style.display='none';
         document.getElementById('rejectionReason').innerHTML='';
         
         document.getElementById('bookingNumRowId').style.display='';
         if(mode==1){
            document.guestHouse.guestName.disabled  = false;
            document.guestHouse.startTime.disabled  = false;
            document.guestHouse.startAmPm.disabled  = false;
            document.guestHouse.endTime.disabled    = false;
            document.guestHouse.endAmPm.disabled    = false;
            document.guestHouse.budgetHead.disabled = false;
            document.guestHouse.alternativeArrangement.disabled = true;
            document.getElementById('alterArrTrId').style.display='none';
         }
         else{
            document.guestHouse.guestName.disabled  = true;
            document.guestHouse.guestName.disabled  = true;
            document.guestHouse.startTime.disabled  = true;
            document.guestHouse.startAmPm.disabled  = true;
            document.guestHouse.endTime.disabled    = true;
            document.guestHouse.endAmPm.disabled    = true;
            document.guestHouse.budgetHead.disabled = true;
            document.guestHouse.alternativeArrangement.disabled = false;
            document.getElementById('alterArrTrId').style.display='';
         }
         
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('GuestHouseDiv');
                        messageBox("<?php echo BOOKING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    }
                   var j = eval('('+trim(transport.responseText)+')');

                   document.guestHouse.guestName.value = j.guestName;
                   document.getElementById('arrivalDate').value = j.arrivalDate;
                   document.getElementById('departureDate').value = j.departureDate;
                   document.guestHouse.budgetHead.value = j.budgetHeadId;
                   document.guestHouse.startTime.value = j.arrivalTime;
                   document.guestHouse.startAmPm.value = j.arrivalAmPm;
                   document.guestHouse.endTime.value = j.departureTime;
                   document.guestHouse.endAmPm.value = j.departureAmPm;
                   document.guestHouse.bookingNumber.value = j.bookingNo;
                   document.guestHouse.bookingId.value = j.allocationId;
                   
                   if(mode==1){
                    document.guestHouse.guestName.focus();
                   }
                   else{
                    document.guestHouse.alternativeArrangement.value=j.alternativeArrangement;
                    document.guestHouse.alternativeArrangement.focus();
                   }
                   if(j.isAllocated==2){
                     document.getElementById('reasonTrId').style.display='';
                     document.getElementById('rejectionReason').innerHTML=j.reason;  
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/listGuestHousePrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"GuestHouseReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {

    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listGuestHouseCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/GuestHouse/listGuestHouseContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php
// $History: listGuestHouse.php $
?>