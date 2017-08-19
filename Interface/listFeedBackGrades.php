<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBackGrades
// Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackGradesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackGradeList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Grades Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('feedbackGradeLabel','Grade Label','width="28%"','',true) ,
    new Array('feedbackGradeValue','Grade Value','width="28%"','',true), 
    new Array('feedbackSurveyLabel','Feedback Label','width="28%"','',true), 
    new Array('action','Action','width="3%"','align="center"',false)
  );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackGradeList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedBackGrades';   
editFormName   = 'EditFeedBackGrades';
winLayerWidth  = 355; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeedBackGrades';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackSurveyLabel';
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
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("gradeLabel","<?php echo ENTER_GRADE_LABEL ;?>"),
                                new Array("gradeValue","<?php echo   ENTER_GRADE_VALUE; ;?>"),
                                new Array("surveyValue","<?php echo   SELECT_FEEDBACK_LABEL; ;?>")
        );

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='gradeLabel' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo GRADE_LABEL_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }   
           if(fieldsArray[i][0]=='gradeValue' && !isInteger(trim(eval("frm."+(fieldsArray[i][0])+".value")))) {
                alert("<?php echo ENTER_GRADE_LABEL_TO_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
     
    }
    if(act=='Add') {
        addFeedBackGrades();
        return false;
    }
    else if(act=='Edit') {
        editFeedBackGrades();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEGREE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackGrades() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackGradeAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 gradeLabel:   trim(document.AddFeedBackGrades.gradeLabel.value), 
                 gradeValue:   trim(document.AddFeedBackGrades.gradeValue.value),
                 surveyId:     trim(document.AddFeedBackGrades.surveyValue.value)
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
                             hiddenFloatingDiv('AddFeedBackGrades');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                        if("<?php echo FEEDBACK_GRADE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         document.AddFeedBackGrades.gradeLabel.focus();
                        }
                        else {
                         document.AddFeedBackGrades.gradeValue.focus();
                        }    
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A gradeLabel
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackGrades(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackGradeDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedbackGradeId: id},
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



//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddFeedBackGrades" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackGrades.gradeLabel.value = '';
   document.AddFeedBackGrades.gradeValue.value = '';
   document.AddFeedBackGrades.surveyValue.value = '';
   document.AddFeedBackGrades.gradeLabel.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackGrades() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackGradeEdit.php';
                  
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                 feedbackGradeId: (document.EditFeedBackGrades.feedbackGradeId.value),
                 gradeLabel:   trim(document.EditFeedBackGrades.gradeLabel.value), 
                 gradeValue:   trim(document.EditFeedBackGrades.gradeValue.value),
                 surveyId:     trim(document.EditFeedBackGrades.surveyValue.value) 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedBackGrades');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo FEEDBACK_GRADE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo FEEDBACK_GRADE_ALREADY_EXIST ;?>"); 
                         document.EditFeedBackGrades.gradeLabel.focus();
                    }  
                     else {
                        messageBox(trim(transport.responseText)); 
                        document.EditFeedBackGrades.gradeValue.focus();                        
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFeedBackGrades" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetFeedBackGradeValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedbackGradeId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFeedBackGrades');
                        messageBox("<?php echo GRADE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                     }
                     else if(trim(transport.responseText)=="<?php echo GRADE_CAN_NOT_MOD_DEL; ?>"){
                       messageBox("<?php echo GRADE_CAN_NOT_MOD_DEL; ?>");
                       //hiddenFloatingDiv('EditFeedBackGrades');
                     }
                    else{
                         displayWindow(dv,w,h); 
                         j = eval('('+trim(transport.responseText)+')');
                         document.EditFeedBackGrades.gradeLabel.value      = j.feedbackGradeLabel;
                         document.EditFeedBackGrades.gradeValue.value      = j.feedbackGradeValue;
                         document.EditFeedBackGrades.surveyValue.value      = j.feedbackSurveyId;
                         document.EditFeedBackGrades.feedbackGradeId.value = j.feedbackGradeId;
                         document.EditFeedBackGrades.gradeLabel.focus();
                    }     
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print FeedBack Grades report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackGradesReportPrint.php?'+qstr;
    window.open(path,"FeedBackCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackGradesReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedBack/listFeedBackGradesContents.php");
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
// $History: listFeedBackGrades.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 5  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 4  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Interface
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Interface
//modified in feedback label & role wise graph
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/22/09    Time: 1:23p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/14/09    Time: 6:03p
//Updated in $/Leap/Source/Interface
//modified in field focus
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 13/01/09   Time: 16:34
//Updated in $/Leap/Source/Interface
//Modified Code as one field is added in feedback_grade table
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Interface
//make changes for sending sendReq() function
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 15/12/08   Time: 11:08
//Updated in $/Leap/Source/Interface
//Fixed Bugs
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/21/08   Time: 12:10p
//Updated in $/Leap/Source/Interface
//Corrected problem corresponding to Issues [20-11-08] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/15/08   Time: 3:17p
//Updated in $/Leap/Source/Interface
//Corrected javascript alerts
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Interface
//Created FeedBack Masters
?>