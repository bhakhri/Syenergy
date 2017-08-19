<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DutyLeaveEvents');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Duty Leave Events Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('eventTitle','Event','width="35%"','',true) , 
                               new Array('startDate','Start Date','width="10%"','align="center"',true), 
                               new Array('endDate','End Date','width="10%"','align="center"',true) , 
                               new Array('labelName','Time Table','width="15%"','align="center"',true) , 
                               new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/DutyLeaveEvents/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEvent';   
editFormName   = 'EditEvent';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteEvent';
divResultName  = 'results';
page=1; //default page
sortField = 'eventTitle';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
       new Array("eventTitle","<?php echo ENTER_DUTY_LEAVE_EVENT_NAME;?>"),
       new Array("labelId","<?php echo SELECT_TIME_TABLE;?>")
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
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='eventTitle' ) {
                messageBox("<?php echo DUTY_LEAVE_EVENT_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
     
    }
    if(act=='Add') {
       if(!dateDifference(document.getElementById('startDate1').value,document.getElementById('endDate1').value,'-')){
           messageBox("<?php echo DUTY_LEAVE_START_DATE_CHECK?>");
           document.getElementById('endDate1').focus();
           return false;
       } 
        addEvent();
        return false;
    }
    else if(act=='Edit') {
        if(!dateDifference(document.getElementById('startDate2').value,document.getElementById('endDate2').value,'-')){
           messageBox("<?php echo DUTY_LEAVE_START_DATE_CHECK?>");
           document.getElementById('endDate2').focus();
           return false;
       }
        editEvent();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEvent() {
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeaveEvents/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 eventTitle: (document.AddEvent.eventTitle.value), 
                 startDate: document.getElementById('startDate1').value, 
                 endDate: document.getElementById('endDate1').value,
                 labelId : document.AddEvent.labelId.value
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
                             hiddenFloatingDiv('AddEvent');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo DUTY_LEAVE_EVENT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo DUTY_LEAVE_EVENT_ALREADY_EXIST ;?>"); 
                         document.AddEvent.eventTitle.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddEvent.eventTitle.focus(); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteEvent(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeaveEvents/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addEvent" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d');?>"
function blankValues() {
   document.AddEvent.reset();
   document.AddEvent.eventTitle.value = '';
   document.getElementById('startDate1').value = serverDate;
   document.getElementById('endDate1').value = serverDate;
   document.AddEvent.eventTitle.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editEvent() {
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeaveEvents/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 eventId: (document.EditEvent.eventId.value), 
                 eventTitle: (document.EditEvent.eventTitle.value), 
                 startDate: document.getElementById('startDate2').value, 
                 endDate: document.getElementById('endDate2').value,
                 labelId : document.EditEvent.labelId.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditEvent');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo DUTY_LEAVE_EVENT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo DUTY_LEAVE_EVENT_ALREADY_EXIST ;?>"); 
                         document.EditEvent.eventTitle.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditEvent.eventTitle.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editEvent" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         document.EditEvent.reset();
         var url = '<?php echo HTTP_LIB_PATH;?>/DutyLeaveEvents/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditEvent');
                        messageBox("<?php echo DUTY_LEAVE_EVENT_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+transport.responseText+')');
                   
                   document.EditEvent.eventTitle.value = j.eventTitle;
                   document.EditEvent.startDate2.value = j.startDate;
                   document.EditEvent.endDate2.value = j.endDate;
                   document.EditEvent.eventId.value = j.eventId;
                   document.EditEvent.labelId.value = j.timeTableLabelId;
                   document.EditEvent.eventTitle.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listEventPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"EventReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listEventCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/DutyLeaveEvents/listDutyLeaveEventsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listEvent.php $ 
?>
