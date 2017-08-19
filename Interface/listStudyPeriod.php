<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF STUDY PERIOD ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudyPeriodMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/StudyPeriod/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Study Period Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                    new Array('srNo','#','width="1%"','',false), 
                    new Array('periodValue','Period Value','width="10%"','align="right"',true), 
                    new Array('periodicityCode','Periodicity','width="35%"','',true) , 
                    new Array('periodName','Period Name','width="35%"','',true) , 
                    new Array('action','Action','width="2%"','align="center"',false)
            );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudyPeriod/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddStudyPeriod';   
editFormName   = 'EditStudyPeriod';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteStudyPeriod';
divResultName  = 'results';
page=1; //default page
sortField = 'periodValue';
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
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("periodValue","<?php echo ENTER_PERIOD_VALUE; ?>"),
    new Array("periodicityName","<?php echo SELECT_PERIODICITY; ?>"),
    new Array("periodName","<?php echo ENTER_PERIOD_NAME; ?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='periodName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php PERIOD_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         if(fieldsArray[i][0]=="periodicityName" && eval("frm."+(fieldsArray[i][0])+".value")=="")  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
         }
         if(fieldsArray[i][0]=="periodValue" && !isInteger(eval("frm."+(fieldsArray[i][0])+".value")) )  {
            //alert(fieldsArray[i][1]);
            messageBox("Enter numeric value for period value");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
         }            
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addStudyPeriod();
        return false;
    }
    else if(act=='Edit') {
        editStudyPeriod();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW StudyPeriod
//
//Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addStudyPeriod() {
         url = '<?php echo HTTP_LIB_PATH;?>/StudyPeriod/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodName: (document.AddStudyPeriod.periodName.value), periodValue: (document.AddStudyPeriod.periodValue.value), periodicityId: (document.AddStudyPeriod.periodicityName.value)},
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
                             hiddenFloatingDiv('AddStudyPeriod');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo STUDY_PERIOD_ALREADY_EXIST;?>" == trim(transport.responseText)){
                             messageBox("<?php echo STUDY_PERIOD_ALREADY_EXIST ;?>"); 
                             document.AddStudyPeriod.periodValue.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW StudyPeriod
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteStudyPeriod(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/StudyPeriod/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studyPeriodId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddStudyPeriod" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddStudyPeriod.periodName.value = '';
   document.AddStudyPeriod.periodValue.value = '';
   document.AddStudyPeriod.periodicityName.selectedIndex=0;
   document.AddStudyPeriod.periodValue.focus();
}


//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO fill up periodName textbox upon user selection of periodValue and periodicity
//
//Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
function createPeriodName(frm){
    
    if(frm=="Add"){
        if(document.AddStudyPeriod.periodicityName.value !=""){
         document.AddStudyPeriod.periodName.value=document.AddStudyPeriod.periodValue.value + " " + document.AddStudyPeriod.periodicityName.options[document.AddStudyPeriod.periodicityName.selectedIndex].text;
        } 
       else{
           document.AddStudyPeriod.periodName.value=document.AddStudyPeriod.periodValue.value;
       }  
    }
   else{
       if(document.EditStudyPeriod.periodicityName.value !=""){
       document.EditStudyPeriod.periodName.value=document.EditStudyPeriod.periodValue.value + " " + document.EditStudyPeriod.periodicityName.options[document.EditStudyPeriod.periodicityName.selectedIndex].text;
       }
      else{
          document.EditStudyPeriod.periodName.value=document.EditStudyPeriod.periodValue.value; 
          
      }  
   } 
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A StudyPeriod
//
//Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editStudyPeriod() {
         url = '<?php echo HTTP_LIB_PATH;?>/StudyPeriod/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studyPeriodId: (document.EditStudyPeriod.studyPeriodId.value), periodName: (document.EditStudyPeriod.periodName.value), periodValue: (document.EditStudyPeriod.periodValue.value), periodicityId: (document.EditStudyPeriod.periodicityName.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditStudyPeriod');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo STUDY_PERIOD_ALREADY_EXIST;?>" == trim(transport.responseText)){
                             messageBox("<?php echo STUDY_PERIOD_ALREADY_EXIST ;?>"); 
                             document.EditStudyPeriod.periodValue.focus();
                       }  
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditStudyPeriod" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (2.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/StudyPeriod/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studyPeriodId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditStudyPeriod');
                        messageBox("<?php echo STUDY_PERIOD_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditStudyPeriod.periodName.value = j.periodName;
                   document.EditStudyPeriod.periodValue.value = j.periodValue;
                   document.EditStudyPeriod.periodicityName.value = j.periodicityId;
                   document.EditStudyPeriod.periodValue.focus();
                   
                   document.EditStudyPeriod.studyPeriodId.value = j.studyPeriodId;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print city report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/studyPeriodReportPrint.php?'+qstr;
    window.open(path,"StudyPeriod","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
  
    window.location='studyPeriodReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/StudyPeriod/listStudyPeriodContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script> 
<?php 
// $History: listStudyPeriod.php $ 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 3/08/09    Time: 14:28
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000825,0000826,0000833,0000834,0000835,0000836,0000837
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/07/09   Time: 9:46
//Updated in $/LeapCC/Interface
//corrected javascript code
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 7/07/09    Time: 16:21
//Updated in $/LeapCC/Interface
//modified study period module
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/07/09    Time: 19:29
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0000483,0000484,0000487,000489,0000485,0000486,0000488,
//0000490,0000491,0000492
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/07/09    Time: 15:24
//Updated in $/LeapCC/Interface
//corrected javascrip error
//
//*****************  Version 4  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Interface
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 3  *****************
//User: Administrator Date: 27/05/09   Time: 15:47
//Updated in $/LeapCC/Interface
//Corrected message display
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
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:58a
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:33p
//Updated in $/Leap/Source/Interface
//Added functionality for study period report print
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/20/08    Time: 4:12p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/20/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/31/08    Time: 11:30a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/11/08    Time: 3:59p
//Updated in $/Leap/Source/Interface
//Added the functionality : create  periodName from periodValue and
//periodicityName
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/09/08    Time: 6:23p
//Updated in $/Leap/Source/Interface
//Added javascript validations and restructure add and edit divs to 
//have period value->periodicity->period name
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/02/08    Time: 6:48p
//Updated in $/Leap/Source/Interface
//Created "StudyPeriod Master"  Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/02/08    Time: 3:59p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>