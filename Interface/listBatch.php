<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Batch Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BatchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Batch/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Batch Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
                               new Array('batchName','Name ','width="35%"','',true),
                               new Array('studentId','Student','width="12%"','align="right"',true),
                               new Array('startDate','Start Date','width="12%"','align="center"',true),
                               new Array('endDate','End Date','width="12%"','align="center"',true),
                               new Array('batchYear','Batch Year','width="15%"','align="center"',true),
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Batch/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBatch';
editFormName   = 'EditBatch';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBatch';
divResultName  = 'results';
page=1; //default page
sortField = 'batchYear';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//This function Displays Div Window
function editWindow(id,dv,w,h) {

    displayWindow(dv,w,h);
    populateValues(id);
}

function validateAddForm(frm, act) {


    var fieldsArray = new Array(new Array("batchName","<?php echo ENTER_BATCH_NAME;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

        if(act=='Add') {
            if(isEmpty(eval("frm.startDate.value"))) {
                messageBox ("<?php echo ENTER_BATCH_START_DATE;?>");
                eval("frm.startDate.focus();");
                return false;
                break;
            }
            if(isEmpty(eval("frm.endDate.value"))) {
                messageBox ("<?php echo ENTER_BATCH_END_DATE;?>");
                eval("frm.endDate.focus();");
                return false;
                break;
            }
            if(!dateDifference(eval("frm.startDate.value"),eval("frm.endDate.value"),'-') ) {
                messageBox ("<?php echo DATE_CONDITION;?>");
                eval("frm.startDate.focus();");
                return false;
                break;
            }
        }
        else if(act=='Edit') {
            if(isEmpty(eval("frm.startDate1.value"))) {
                messageBox ("<?php echo ENTER_BATCH_START_DATE;?>");
                eval("frm.startDate1.focus();");
                return false;
                break;
            }
            if(isEmpty(eval("frm.endDate1.value"))) {
                messageBox ("<?php echo ENTER_BATCH_END_DATE;?>");
                eval("frm.endDate1.focus();");
                return false;
                break;
            }
            if(!dateDifference(eval("frm.startDate1.value"),eval("frm.endDate1.value"),'-') ) {
                messageBox ("<?php echo DATE_CONDITION;?>");
                eval("frm.startDate1.focus();");
                return false;
                break;
            }
       }
    }

    if(act=='Add') {
        addBatch();
        return false;
    }
    else if(act=='Edit') {
        editBatch();
        return false;
    }
}
function addBatch() {
         url = '<?php echo HTTP_LIB_PATH;?>/Batch/ajaxInitAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batchName: trim(document.addBatch.batchName.value),
                          startDate: trim(document.addBatch.startDate.value),
                          endDate: trim(document.addBatch.endDate.value)},
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
                             hiddenFloatingDiv('AddBatch');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addBatch.batchName.value = '';
   //document.addBatch.startDate.value = '';
   //document.addBatch.endDate.value = '';
   document.addBatch.batchName.focus();
}
function editBatch() {
         url = '<?php echo HTTP_LIB_PATH;?>/Batch/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batchName: trim(document.editBatch.batchName.value),
                          startDate: trim(document.editBatch.startDate1.value),
                          endDate: trim(document.editBatch.endDate1.value),
                          batchId: trim(document.editBatch.batchId.value)},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                     hideWaitDialog(true);

                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBatch');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                      else {
                         messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteBatch(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/Batch/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batchId: id},
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

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Batch/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {batchId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                   document.editBatch.batchName.value = j.batchName;
                   document.editBatch.startDate1.value = j.startDate;
                   document.editBatch.endDate1.value = j.endDate;
                   document.editBatch.batchId.value = j.batchId;
				   //document.editBatch.batchYear.value = j.batchYear;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listBatchPrint.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+ 
 sortField;
    window.open(path,"DisplayBatchList","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayBatchCSV.php?'+qstr;
	window.location = path;
}
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){
  var form = document.addBatch;
 }
 else{
     var form = document.editBatch;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Batch/listBatchContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>
<?php
//$History: listBatch.php $
//
//*****************  Version 11  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Interface
//search & conditions updated
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/18/09    Time: 5:42p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 413
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/17/09    Time: 2:26p
//Updated in $/LeapCC/Interface
//Gurkeerat: issue resolved 918
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/13/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 402,404
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/08/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//bug fix 505, 504, 503, 968, 961, 960, 959, 958, 957, 956, 955, 954,
//953, 952,
//951, 723, 722, 797, 798, 799, 916, 935, 936, 937, 938, 939, 940, 944
//(alignment, condition & formatting updated)
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/26/09    Time: 5:25p
//Updated in $/LeapCC/Interface
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 3:25p
//Updated in $/LeapCC/Interface
//printreport search condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Parveen      Date: 11/05/08   Time: 4:50p
//Updated in $/Leap/Source/Interface
//define module, access - added
//
//*****************  Version 11  *****************
//User: Arvind       Date: 10/15/08   Time: 11:09a
//Updated in $/Leap/Source/Interface
//added print function
//
//*****************  Version 10  *****************
//User: Arvind       Date: 9/10/08    Time: 5:48p
//Updated in $/Leap/Source/Interface
//modify
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/19/08    Time: 6:54p
//Updated in $/Leap/Source/Interface
//used common error message for validations
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/07/08    Time: 3:17p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/21/08    Time: 5:03p
//Updated in $/Leap/Source/Interface
//added a new field batch year
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/18/08    Time: 2:16p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/10/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//Removed instituteId field from the dynamic table  ,add and edit
//functions
//
//*****************  Version 3  *****************
//User: Arvind       Date: 7/07/08    Time: 5:26p
//Updated in $/Leap/Source/Interface
//added new validation of calendar and changed the calendar id in all the
//funcitons
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/30/08    Time: 4:22p
//Updated in $/Leap/Source/Interface
//1) Added a new javascript function which calls table listing through
//ajax and pagination function
//2) Added a delete funciton which call ajax file to delete
//3) Modifies add and edit funnction.
//    Data saved successfullyand
//   DO you want to add more ?
//  messageBox  is displayed in one messageBox  box
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:23p
//Created in $/Leap/Source/Interface
//first time checkin
?>