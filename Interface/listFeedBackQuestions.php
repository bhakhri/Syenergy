<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackQuestionsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackQuestionsList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Questions Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('feedbackSurveyLabel','Label','width="20%"','',true) , 
                                new Array('feedbackCategoryName','Category','width="15%"','',true), 
                                new Array('feedbackQuestion','Question','width="55%"','',true) , 
                                new Array('action','Action','width="3%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackQuestionsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedBackQuestions';   
editFormName   = 'EditFeedBackQuestions';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeedBackQuestions';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackQuestion';
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
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    /*var spt=id.split('~');
    if(spt[1]!=-1){ //if it is used previously
      messageBox("<?php echo NO_EDIT;?>");   
      return false;
    }*/
  
    populateValues(id,dv,w,h);   
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("labelId","<?php echo SELECT_FEEDBACK_LABEL;?>"),
                                new Array("categoryId","<?php echo SELECT_FEEDBACK_CATEGORY;?>"),
                                new Array("questionTxt","<?php echo ENTER_FEEDBACK_QUESTION;?>") 
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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<5 && fieldsArray[i][0]=='questionTxt' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo FEEDBACK_QUESTIONS_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
     
    }
    if(act=='Add') {
        addFeedBackQuestions();
        return false;
    }
    else if(act=='Edit') {
        editFeedBackQuestions();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FeedBackQuestions
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackQuestions() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackQuestionsAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 labelId:    (document.AddFeedBackQuestions.labelId.value), 
                 categoryId: (document.AddFeedBackQuestions.categoryId.value), 
                 questionTxt:   trim(document.AddFeedBackQuestions.questionTxt.value)
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
                        else if("<?php echo FEEDBACK_QUESTIONS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo FEEDBACK_QUESTIONS_ALREADY_EXIST ;?>"); 
                         document.AddFeedBackQuestions.questionTxt.focus();
                        }   
                         else {
                             hiddenFloatingDiv('AddFeedBackQuestions');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A FeedBackQuestions
//  id=FeedBackQuestionsId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackQuestions(id) {
        /* var spt=id.split('~');
         if(spt[1]!=-1){ //if it used previously
          messageBox("<?php echo NO_DELETE;?>");   
          return false;
         }*/
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackQuestionsDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedbackQuestionId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addFeedBackQuestions" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackQuestions.labelId.value = '';
   document.AddFeedBackQuestions.categoryId.value = '';
   document.AddFeedBackQuestions.questionTxt.value = '';
   document.AddFeedBackQuestions.labelId.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A FeedBackQuestions
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackQuestions() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackQuestionsEdit.php';
         
         //var spt=document.EditFeedBackQuestions.feedbackQuestionId.value.split('~'); //extracting questionId
    
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 feedbackQuestionId: (document.EditFeedBackQuestions.feedbackQuestionId.value), 
                 labelId:        (document.EditFeedBackQuestions.labelId.value), 
                 categoryId:      (document.EditFeedBackQuestions.categoryId.value), 
                 questionTxt:   trim(document.EditFeedBackQuestions.questionTxt.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedBackQuestions');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo FEEDBACK_QUESTIONS_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo FEEDBACK_QUESTIONS_ALREADY_EXIST ;?>"); 
                       document.EditFeedBackQuestions.questionTxt.focus();
                    } 
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editFeedBackQuestions" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (15.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetFeedBackQuestionsValues.php';
         new Ajax.Request(url,
           {
             method:'post', 
                 asynchronous:false,
             parameters: {feedbackQuestionId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFeedBackQuestions');
                        messageBox("<?php echo FEEDBACK_QUESTIONS_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }

                  else if("<?php echo 'Data could not be edited due to records existing in linked tables';?>" == trim(transport.responseText)) {
                        //hiddenFloatingDiv('EditFeedBackQuestions');
                        messageBox(trim(transport.responseText));
                        return false;
                   }
                   else {
                   displayWindow(dv,w,h);
                   j = eval('('+transport.responseText+')');
                   document.EditFeedBackQuestions.labelId.value            = j.feedbackSurveyId;
                   document.EditFeedBackQuestions.categoryId.value         = j.feedbackCategoryId;
                   document.EditFeedBackQuestions.questionTxt.value        = j.feedbackQuestion;
                   document.EditFeedBackQuestions.feedbackQuestionId.value = j.feedbackQuestionId;
                   document.EditFeedBackQuestions.labelId.focus();
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


/* function to print FeedBack Questions report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackQuestionsReportPrint.php?'+qstr;
    window.open(path,"FeedBackCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackQuestionsReportCSV.php?'+qstr;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeedBack/listFeedBackQuestionsContents.php");
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
// $History: listFeedBackQuestions.php $ 
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 6  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 5  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Interface
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 9  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 1/16/09    Time: 1:32p
//Updated in $/Leap/Source/Interface
//modified left alignment
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 1/13/09    Time: 4:35p
//Updated in $/Leap/Source/Interface
//modified in message for delete
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Interface
//make changes for sending sendReq() function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 1/07/09    Time: 1:29p
//Updated in $/Leap/Source/Interface
//modified to check constraint
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:31
//Updated in $/Leap/Source/Interface
//Corrected delete functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/09/08   Time: 5:30p
//Updated in $/Leap/Source/Interface
//Added the functionality that once a question has been used in student
//feedback module it can not be edited or deleted
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/15/08   Time: 3:28p
//Updated in $/Leap/Source/Interface
//Corrected javascript alerts
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Interface
//Created FeedBack Masters
?>