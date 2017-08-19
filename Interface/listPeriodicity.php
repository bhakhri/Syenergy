<?php

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in Periodicity Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodicityMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Periodicity/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Periodicity Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('periodicityName','Name','width="25%"','',true) ,
                               new Array('periodicityCode','Abbr.','width="25%"','',true),
                               new Array('periodicityFrequency','Annual Frequency','width="20%"','align="right"',true) ,
                               new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Periodicity/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddPeriodicity';
editFormName   = 'EditPeriodicity';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deletePeriodicity';
divResultName  = 'results';
page=1; //default page
sortField = 'periodicityName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag=false;
//This function Displays Div Window

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//This function Validates Form

function validateAddForm(frm, act) {


    var fieldsArray = new Array(new Array("periodicityName","<?php echo ENTER_PERIODICITY_NAME;?>"),
                                new Array("periodicityCode","<?php echo ENTER_PERIODICITY_ABBR;?>"),
                                new Array("periodicityFrequency","<?php echo ENTER_PERIODICITY_FREQUENCY;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='periodicityFrequency') {
                //winmessageBox ("Enter string",fieldsArray[i][0]);
                messageBox ("<?php echo ENTER_ALPHABETS_NUMERIC;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         else if(fieldsArray[i][0]=="periodicityFrequency" && (!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))){
                messageBox("<?php echo ENTER_PERIODICITY_NUMBER;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
         }
         else if(fieldsArray[i][0]=="periodicityFrequency" && (eval("frm."+(fieldsArray[i][0])+".value") < 1)){
                messageBox("<?php echo 'Enter valid value for periodicity';?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
         }

    }
    if(act=='Add') {
        addPeriodicity();
        return false;
    }
    else if(act=='Edit') {
        editPeriodicity();
        return false;
    }
}

//This function adds form through ajax

function addPeriodicity() {
         url = '<?php echo HTTP_LIB_PATH;?>/Periodicity/ajaxInitAdd.php';
                  new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodicityName: trim(document.addPeriodicity.periodicityName.value),
                          periodicityCode: trim(document.addPeriodicity.periodicityCode.value),
                          periodicityFrequency: trim(document.addPeriodicity.periodicityFrequency.value) },
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
                             hiddenFloatingDiv('AddPeriodicity');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo ENTER_PERIODICITY_NAME;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_NAME;?>");
                        document.addPeriodicity.periodicityName.focus();
                     }
                     else if("<?php echo ENTER_PERIODICITY_CODE;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_CODE;?>");
                        document.addPeriodicity.periodicityCode.focus();
                     }
                     else if("<?php echo ENTER_PERIODICITY_FREQUENCY;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_FREQUENCY;?>");
                        document.addPeriodicity.periodicityFrequency.focus();
                     }
                     else if("<?php echo ENTER_PERIODICITY_NUMBER;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_NUMBER;?>");
                        document.addPeriodicity.periodicityFrequency.focus();
                     }
                     else if("<?php echo ENTER_PERIODICITY_ABBR;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_ABBR;?>");
                        document.addPeriodicity.periodicityCode.focus();
                     }
                     else if("<?php echo PERIODICITY_ABBR_EXIST;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo PERIODICITY_ABBR_EXIST;?>");
                        document.addPeriodicity.periodicityCode.focus();
                     }
                     else if("<?php echo PERIODICITY_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo PERIODICITY_ALREADY_EXIST;?>");
                        document.addPeriodicity.periodicityName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
// This function calls delete file through ajax

function deletePeriodicity(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/Periodicity/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodicityId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                     hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
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

function blankValues() {
   document.addPeriodicity.periodicityCode.value = '';
   document.addPeriodicity.periodicityName.value = '';
   document.addPeriodicity.periodicityFrequency.value = '';
   document.addPeriodicity.periodicityName.focus();
}

//This function edit form through ajax

function editPeriodicity() {
         url = '<?php echo HTTP_LIB_PATH;?>/Periodicity/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodicityId: trim(document.editPeriodicity.periodicityId.value),
                          periodicityName: trim(document.editPeriodicity.periodicityName.value),
                          periodicityCode: trim(document.editPeriodicity.periodicityCode.value),
                          periodicityFrequency: trim(document.editPeriodicity.periodicityFrequency.value)},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
                    if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                       hiddenFloatingDiv('EditPeriodicity');
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       return false;
                       //location.reload();
                    }
                    else if("<?php echo ENTER_PERIODICITY_NAME;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_NAME;?>");
                        document.editPeriodicity.periodicityName.focus();
                    }
                    else if("<?php echo ENTER_PERIODICITY_CODE;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_CODE;?>");
                        document.editPeriodicity.periodicityCode.focus();
                    }
                    else if("<?php echo ENTER_PERIODICITY_FREQUENCY;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_FREQUENCY;?>");
                        document.editPeriodicity.periodicityFrequency.focus();
                    }
                    else if("<?php echo ENTER_PERIODICITY_NUMBER;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_NUMBER;?>");
                        document.editPeriodicity.periodicityFrequency.focus();
                    }
                    else if("<?php echo ENTER_PERIODICITY_ABBR;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo ENTER_PERIODICITY_ABBR;?>");
                        document.editPeriodicity.periodicityCode.focus();
                    }
                    else if("<?php echo PERIODICITY_ABBR_EXIST;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo PERIODICITY_ABBR_EXIST;?>");
                        document.editPeriodicity.periodicityCode.focus();
                    }
                    else if("<?php echo PERIODICITY_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                        messageBox ("<?php echo PERIODICITY_ALREADY_EXIST;?>");
                        document.editPeriodicity.periodicityName.focus();
                    }
                    else {
                        messageBox(trim(transport.responseText));
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function populates values in edit form through ajax

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Periodicity/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodicityId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');

                   document.editPeriodicity.periodicityCode.value = j.periodicityCode;

                   document.editPeriodicity.periodicityName.value = j.periodicityName;
                   document.editPeriodicity.periodicityFrequency.value = j.periodicityFrequency;

                   document.editPeriodicity.periodicityId.value = j.periodicityId;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayPeriodicityReport.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayPeriodicityReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayPeriodicityCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Periodicity/listPeriodicityContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>
</body>
</html>
<?php

////$History: listPeriodicity.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/20/09   Time: 11:57a
//Updated in $/LeapCC/Interface
//search by format updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 8/06/09    Time: 4:25p
//Updated in $/LeapCC/Interface
//periodicityFrequency sorting  true (table array)
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/03/09    Time: 5:41p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000602, 0000832, 0000831, 0000830
//
//*****************  Version 4  *****************
//User: Parveen      Date: 7/21/09    Time: 4:03p
//Updated in $/LeapCC/Interface
//issue fix (557, 559-564) format & validation checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/21/09    Time: 10:24a
//Updated in $/LeapCC/Interface
//bug fix (Message change)
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
//*****************  Version 15  *****************
//User: Parveen      Date: 11/05/08   Time: 5:20p
//Updated in $/Leap/Source/Interface
//added module, access
//
//*****************  Version 14  *****************
//User: Arvind       Date: 8/19/08    Time: 7:02p
//Updated in $/Leap/Source/Interface
//used common error message for validations
//
//*****************  Version 13  *****************
//User: Arvind       Date: 8/07/08    Time: 3:22p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/01/08    Time: 7:22p
//Updated in $/Leap/Source/Interface
//added validation of periodicityfrequency
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/01/08    Time: 7:19p
//Updated in $/Leap/Source/Interface
//enhancement added oncreate
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function
//
//*****************  Version 9  *****************
//User: Arvind       Date: 7/18/08    Time: 2:18p
//Updated in $/Leap/Source/Interface
//added messageBox in place ofalert
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/18/08    Time: 11:43a
//Updated in $/Leap/Source/Interface
//added a flag variable
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/10/08    Time: 12:33p
//Updated in $/Leap/Source/Interface
//modified the widths of the fields in the ajax table and added a
//validation for periodicity fraction
//
//*****************  Version 6  *****************
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
//*****************  Version 5  *****************
//User: Arvind       Date: 6/17/08    Time: 3:16p
//Updated in $/Leap/Source/Interface
//modification
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/14/08    Time: 7:15p
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/13/08    Time: 12:52p
//Updated in $/Leap/Source/Interface
//comments modified
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:03p
//Updated in $/Leap/Source/Interface
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:17p
//Created in $/Leap/Source/Interface
//New Files Checkin

?>
