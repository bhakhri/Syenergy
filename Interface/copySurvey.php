<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CopySurveyMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Copy  Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

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
function validateAddForm() {
    if(document.getElementById('copySurvey').value==''){
        messageBox("Select target survey ");
        document.getElementById('copySurvey').focus();
        return false;
    }
  if(!(checkQuestions())){  //checkes whether any student/parent checkboxes selected or not
     messageBox("<?php echo SELECT_ATLEASTONE_CHECKBOX ; ?>");    
     document.getElementById('questionList').focus();
     return false;
  }
  
  doCoping();
  return false;
}

//this function is used  to copy question from one label to another label
function doCoping(){
    var str='';
    if(!chkObject('questions')){
      str=document.listFrm.questions.value
    }
    
    formx = document.listFrm; 
    var l=formx.questions.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.questions[ i ].checked){
            if(str!=''){
                str +=',';
            }
            str +=formx.questions[ i ].value;
        }
    }
   // alert(str);
	//alert(document.getElementById('sourceSurvey').value);
	copyOptionValue = 0;
	if(document.getElementById('copyOption').checked){
	
		copyOptionValue = 1;
	}
	
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxCopyQuestions.php';
    new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 categoryId     : document.getElementById('surveyType').value, 
                 sourceSurveyId : document.getElementById('sourceSurvey').value,
                 targetSurveyId : document.getElementById('copySurvey').value,
                 questionIds    : str,
				 copyOptionValue: copyOptionValue 	
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText).split('!@!');
                     if("<?php echo SUCCESS;?>" == ret[0]) {
                        if(ret.length > 1){      //some question are not copied
                         var ret1=ret[1].split('~#~');
                         var str='';
                         for(var i=0;i<ret1.length;i++){
                          str +='('+ret1[i]+')'+'\n';
                         }
                         messageBox("These questions are copied \n "+str);
                        }
                        else{
                           messageBox("All questions are copied");    
                        }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                     hideData(3);
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
    
}

function getSourceSurvey(value){
    hideData(1);
    if(value==''){
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetSourceSurveys.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                   surveyType: value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText).split('~');
                     
                     var j = eval(ret[0]);
                     var len=j.length;
                     for(var i=0;i<len;i++){
                         addOption(document.getElementById('sourceSurvey'),j[i].feedbackSurveyId,j[i].feedbackSurveyLabel)
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}


function getCopySurvey(value){
    hideData(2);
    if(value==''){
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetCopySurveys.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                   surveyType: document.getElementById('surveyType').value,
                   sourceSurveyId: value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText);
                     var j = eval(ret);
                     var len=j.length;
                     
                      for(var i=0;i<len;i++){
                       addOption(document.getElementById('copySurvey'),j[i].feedbackSurveyId,j[i].feedbackSurveyLabel)
                      } 

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    
}


function getData(){
    if(document.getElementById('surveyType').value==''){
        messageBox("Select survey type");
        document.getElementById('surveyType').focus();
        return false;
    }
    if(document.getElementById('sourceSurvey').value==''){
        messageBox("Select survey source");
        document.getElementById('sourceSurvey').focus();
        return false;
    }
    if(document.getElementById('copySurvey').value==''){
        messageBox("Select target survey ");
        document.getElementById('copySurvey').focus();
        return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxQuestionsList.php';
    var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false),
                        new Array('questions','<input type=\"checkbox\" id=\"questionList\" name=\"questionList\" onclick=\"selectQuestions();\">','width="2%"','align=\"left\"',false),  
                        new Array('feedbackCategoryName','Category','width="10%" align="left"',true),
                        new Array('feedbackQuestion','Questions','width="15%" align="left"',true)
                       );

    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','feedbackQuestion','ASC','results','','',true,'listObj',tableColumns,'','','&surveyId='+document.getElementById('sourceSurvey').value);
    sendRequest(url, listObj, ' ',false);
    
    if(listObj.totalRecords>0){
     document.getElementById('buttonId').style.display='';
    }
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (24.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){
  obj = document.listFrm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectQuestions(){
    
    //state:checked/not checked
    var state=document.getElementById('questionList').checked;
    if(!chkObject('questions')){
     document.listFrm.questions.checked =state;
     return true;  
    }
    formx = document.listFrm; 
    var l=formx.questions.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.questions[ i ].checked=state;
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkQuestions(){
    
    var fl=0; 
    if(!chkObject('questions')){
     if(document.listFrm.questions.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm; 
    var l=formx.questions.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.questions[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
}

function hideData(mode){
    if(mode==1){
      document.getElementById('sourceSurvey').options.length=0;  
      document.getElementById('copySurvey').options.length=0;
      
      addOption(document.getElementById('sourceSurvey'),'','Select');
      addOption(document.getElementById('copySurvey'),'','Select');
      
    }
    else if(mode==2){
        document.getElementById('copySurvey').options.length=0;
        addOption(document.getElementById('copySurvey'),'','Select');
    }
    
    document.getElementById('results').innerHTML='';
    document.getElementById('buttonId').style.display='none';
}


window.onload=function(){
    document.getElementById('surveyType').selectedIndex=0;
    document.getElementById('surveyType').focus();
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedBack/copySurveyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: copySurvey.php $ 
//
//*****************  Version 1  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Created in $/LeapCC/Interface
//Copied "Feedback Master Modules" from Leap to LeapCC
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 5/19/09    Time: 10:47a
//Updated in $/Leap/Source/Interface
//Added functionality to add question options(grades) along with feedback
//question
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 15/04/09   Time: 17:58
//Updated in $/Leap/Source/Interface
//Fixed paging bugs
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:53
//Updated in $/Leap/Source/Interface
//Modified title
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:47
//Updated in $/Leap/Source/Interface
//Modified access parameters
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 15/04/09   Time: 14:44
//Created in $/Leap/Source/Interface
//Created "Copy Survey" Module
?>