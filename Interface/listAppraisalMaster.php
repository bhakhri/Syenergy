<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AppraisalQuestionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Appraisal Question Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('appraisalText','Question','width="30%"','',true), 
                                new Array('appraisalWeightage','Weightage','width="5%"','align="right"',true), 
                                new Array('isActive','Active','width="3%"','',true), 
                                new Array('appraisalProof','Proof','width="3%"','',true), 
                                new Array('appraisalProofName','Proof Form','width="10%"','',true), 
                                new Array('action','Action','width="2%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Question/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddAppraisalQuestion';   
editFormName   = 'EditAppraisalQuestion';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteAppraisalQuestion';
divResultName  = 'results';
page=1; //default page
sortField = 'appraisalText';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
            new Array("question","<?php echo ENTER_APPRAISAL_QUESTION;?>"),
            new Array("weightage","<?php echo ENTER_APPRAISAL_QUESTION_WEIGHTAGE;?>"),
            new Array("titleId","<?php echo SELECT_APPRAISAL_TITLE;?>"),
            new Array("tabId","<?php echo SELECT_APPRAISAL_TAB;?>")
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
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='question' ) {
                messageBox("<?php echo ENTER_APPRAISAL_QUESTION_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
           if(!isNumeric(trim(eval("frm."+(fieldsArray[i][0])+".value"))) && fieldsArray[i][0]=='weightage' ) {
                messageBox("Enter numeric value"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
     
    }
    if(act=='Add') {
        if(document.AddAppraisalQuestion.isProof[0].checked==true){
           if(document.AddAppraisalQuestion.proofId.value==''){
               messageBox("<?php echo SELECT_APPRAISAL_PROOF_FORM;?>"); 
               document.AddAppraisalQuestion.proofId.focus();
               return false;
           }
        }
        addAppraisalQuestion();
        return false;
    }
    else if(act=='Edit') {
        if(document.EditAppraisalQuestion.isProof[0].checked==true){
           if(document.EditAppraisalQuestion.proofId.value==''){
               messageBox("<?php echo SELECT_APPRAISAL_PROOF_FORM;?>"); 
               document.EditAppraisalQuestion.proofId.focus();
               return false;
           }
        }
        editAppraisalQuestion();
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
function addAppraisalQuestion() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Question/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 question  : trim(document.AddAppraisalQuestion.question.value),
                 weightage : trim(document.AddAppraisalQuestion.weightage.value),
                 isActive  : document.AddAppraisalQuestion.isActive[0].checked==true?1:0,
                 isProof   : document.AddAppraisalQuestion.isProof[0].checked==true?1:0,
                 proofId   : document.AddAppraisalQuestion.proofId.value,
                 titleId   : document.AddAppraisalQuestion.titleId.value,
                 tabId     : document.AddAppraisalQuestion.tabId.value
                 
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
                             hiddenFloatingDiv('AddAppraisalQuestion');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo APPRAISAL_QUESTION_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_QUESTION_ALREADY_EXIST ;?>"); 
                         document.AddAppraisalQuestion.question.focus();
                     }
                     else if("<?php echo APPRAISAL_PROOF_USED;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_PROOF_USED ;?>"); 
                         document.AddAppraisalQuestion.proofId.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddAppraisalQuestion.question.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A NEW CITY
// id=cityId
// Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteAppraisalQuestion(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Question/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 appraisalId: id
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addAppraisalQuestion" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddAppraisalQuestion.reset();
   document.AddAppraisalQuestion.question.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editAppraisalQuestion() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Question/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 appraisalId : (document.EditAppraisalQuestion.appraisalId.value), 
                 question    : trim(document.EditAppraisalQuestion.question.value),
                 weightage   : trim(document.EditAppraisalQuestion.weightage.value),
                 isActive    : document.EditAppraisalQuestion.isActive[0].checked==true?1:0,
                 isProof     : document.EditAppraisalQuestion.isProof[0].checked==true?1:0,
                 proofId     : document.EditAppraisalQuestion.proofId.value,
                 titleId     : document.EditAppraisalQuestion.titleId.value,
                 tabId       : document.EditAppraisalQuestion.tabId.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditAppraisalQuestion');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo APPRAISAL_QUESTION_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_QUESTION_ALREADY_EXIST ;?>"); 
                         document.EditAppraisalQuestion.question.focus();
                     }
                     else if("<?php echo APPRAISAL_PROOF_USED;?>" == trim(transport.responseText)){
                         messageBox("<?php echo APPRAISAL_PROOF_USED ;?>"); 
                         document.EditAppraisalQuestion.proofId.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditAppraisalQuestion.question.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editAppraisalQuestion" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         document.EditAppraisalQuestion.reset();
         document.EditAppraisalQuestion.proofId.disabled=true;
         document.EditAppraisalQuestion.proofId.value='';
         
         var url = '<?php echo HTTP_LIB_PATH;?>/Appraisal/Question/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 appraisalId : id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditAppraisalQuestion');
                        messageBox("<?php echo APPRAISAL_QUESTION_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   
                   document.EditAppraisalQuestion.question.value  = j.appraisalText;
                   document.EditAppraisalQuestion.weightage.value = j.appraisalWeightage;
                   
                   if(j.isActive==1){
                     document.EditAppraisalQuestion.isActive[0].checked=true;  
                   }
                   else{
                     document.EditAppraisalQuestion.isActive[1].checked=true;
                   }
                   if(j.appraisalProof==1){
                     document.EditAppraisalQuestion.isProof[0].checked=true;
                     document.EditAppraisalQuestion.proofId.disabled=false;
                     document.EditAppraisalQuestion.proofId.value=j.appraisalProofId;
                   }
                   else{
                     document.EditAppraisalQuestion.isProof[1].checked=true;
                     document.EditAppraisalQuestion.proofId.disabled=true;
                     document.EditAppraisalQuestion.proofId.value='';
                   }
                   document.EditAppraisalQuestion.titleId.value=j.appraisalTitleId;
                   document.EditAppraisalQuestion.tabId.value=j.appraisalTabId;
                   
                   document.EditAppraisalQuestion.appraisalId.value=j.appraisalId;
                   
                   document.EditAppraisalQuestion.question.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function toggleProof(state,mode){
   if(mode==1){
       document.AddAppraisalQuestion.proofId.value='';
       document.AddAppraisalQuestion.proofId.disabled=!state;
   }
   else{
      document.EditAppraisalQuestion.proofId.value=''; 
      document.EditAppraisalQuestion.proofId.disabled=!state; 
   } 
}


function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listAppraisalQuestionPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"AppraisalQuestionReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listAppraisalQuestionCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Appraisal/Question/listAppraisalQuestionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listAppraisalQuestion.php $ 
?>