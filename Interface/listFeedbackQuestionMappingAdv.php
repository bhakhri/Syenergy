<?php
//-------------------------------------------------------------------------
// THIS FILE SHOWS A LIST OF FEED BACK Questions(ADV) and their mapping
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_QuestionMappingMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Questions Mapping(Advanced) </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

recordsPerPage = 1000;//<?php echo RECORDS_PER_PAGE;?>;
linksPerPage = 1000;//<?php echo LINKS_PER_PAGE;?>;
/*
page=1; //default page
sortField = 'feedbackQuestion';
sortOrderBy    = 'ASC';
*/

function getQuestionData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionMappingList.php';

  var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false),
                        new Array('questions','<input type=\"checkbox\" id=\"quesList\" name=\"quesList\" onclick=\"selectQuestions(this.checked);\">','width="3%" align="left"',false),
                        new Array('feedbackQuestion','Question','width="65%" align="left"',true),
                        new Array('answerSetName','Answer Set','width="10%" align="left"',true),
                        new Array('printOrder','Print Order','width="10%" align="left"',false)
                       );

 if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
 }

 if(document.getElementById('labelId').value==''){
        messageBox("<?php echo SELECT_ADV_LABEL_NAME?>");
        document.getElementById('labelId').focus();
        return false;
 }

 if(document.getElementById('catId').value==''){
    messageBox("<?php echo SELECT_ADV_CAT_NAME?>");
    document.getElementById('catId').focus();
    return false;
 }
 if(document.getElementById('questionSetId').value==''){
        messageBox("<?php echo SELECT_ADV_QUESTION_SET_NAME?>");
        document.getElementById('questionSetId').focus();
        return false;
 }

 var catId   = document.getElementById('catId').value;
 var labelId = document.getElementById('labelId').value;
 var questionSetId = document.getElementById('questionSetId').value;

 vanishData(1);

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','feedbackQuestion','ASC','results','','',true,'listObj',tableColumns,'','','&labelId='+labelId+'&catId='+catId+'&questionSetId='+questionSetId);
 sendRequest(url, listObj, '',false);

 if(listObj.totalRecords>0){
   vanishData(2);
 }

 return false;

}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm() {

    if(document.getElementById('labelId').value==''){
        messageBox("<?php echo SELECT_ADV_LABEL_NAME?>");
        document.getElementById('labelId').focus();
        return false;
    }
    if(document.getElementById('catId').value==''){
        messageBox("<?php echo SELECT_ADV_CAT_NAME?>");
        document.getElementById('catId').focus();
        return false;
    }
    if(document.getElementById('questionSetId').value==''){
        messageBox("<?php echo SELECT_ADV_QUESTION_SET_NAME?>");
        document.getElementById('questionSetId').focus();
        return false;
    }


    //now do the mapping
    doQuestionMapping();

}


//---------------------------------------------------------------------
// THIS FUNCTION IS USED TO Mapp questions with label and category
// Author : Dipanjan Bhattacharjee
// Created on : (11.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------
function doQuestionMapping() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackQuestionsMapping.php';
         //check for selected questions
         var c1 = document.getElementById('results').getElementsByTagName('INPUT');
         var len=c1.length;
         var fl=0;
         var qString1='';
         var qString2='';
         var pString='';
         for(var i=0;i<len;i++){
          if (c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='questionsList'){
              if(c1[i].checked==true){
                fl=1;
                if(qString1!=''){
                    qString1 += ',';
                    qString2 += ',';
                }
                var val=c1[i].value.split('!~!~!');
                qString1 += val[0];
                qString2 += val[1];
             }
          }

          if (c1[i].type.toUpperCase()=='TEXT' && c1[i].name=='printOrder' && c1[i].disabled==false){
             if(trim(c1[i].value)==''){
                 messageBox("<?php echo ENTER_ADV_PRINT_ORDER; ?>");
                 c1[i].focus();
                 return false;
             }

             if(!isNumeric(c1[i].value)){
                 messageBox("<?php echo ENTER_ADV_PRINT_ORDER_IN_NUMERIC; ?>");
                 c1[i].focus();
                 return false;
             }
             if(parseInt(trim(c1[i].value),10)<=0){
                 messageBox("<?php echo ADV_PRINT_ORDER_GREATER_THAN_ZERO; ?>");
                 c1[i].focus();
                 return false;
             }

             if(pString!=''){
                 pString +=',';
             }
             pString +=trim(c1[i].value);
          }

        }

        var deallocationFlag=0;
        if(fl==0){
         var ret=confirm("<?php echo ADV_QUESTION_MAPPING_DEALLOCATION;?>");
         if(!ret){
            messageBox("<?php echo SELECT_ADV_QUESTION_LIST?>");
            document.getElementById('quesList').focus();
            return false;
         }
         else{
             deallocationFlag=1;
         }
        }


         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 labelId          : document.QuestionFrm.labelId.value,
                 catId            : document.QuestionFrm.catId.value,
                 questionSetId    : document.QuestionFrm.questionSetId.value,
                 questionList1    : qString1,
                 questionList2    : qString2,
                 printOrderList   : pString,
                 deallocationFlag : deallocationFlag,
				 opName			  : 'view'
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        if(deallocationFlag==1){
                          messageBox("<?php echo ADV_QUESTIONS_DEALLOCATED?>");
                        }
                        else{
                          messageBox("<?php echo ADV_QUESTIONS_MAPPED?>");
                        }
                         vanishData(1);
                     }
                     else if("<?php echo ADV_QUESTIONS_MAPPING_RESTRICTION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_QUESTIONS_MAPPING_RESTRICTION ;?>");
                         //document.QuestionFrm.labelId.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        //document.QuestionFrm.labelId.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function selectQuestions(state){
     var c1 = document.getElementById('results').getElementsByTagName('INPUT');
     var len=c1.length;
     for(var i=0;i<len;i++){
       if (c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='questionsList'){
         var index=c1[i].id.split('questionsList')[1];
         c1[i].checked=state;
         makePrintOrderToggle(index,state);
      }
    }
}

//to make print order textboxes enable disable
function makePrintOrderToggle(index,state){
   document.getElementById('printOrder'+index).disabled=!state;
   if(!state){
     //document.getElementById('printOrder'+index).value='';
   }
}

//make result div BLANK
function vanishData(mode){
  if(mode==1){
   document.getElementById('results').innerHTML='';
   document.getElementById('saveTrId').style.display='none';
  }
  else{
      document.getElementById('saveTrId').style.display='';
    }
}

//To get Label on basis of timeTable
function getSurveyLabel() {
    form = document.QuestionFrm;
    form.labelId.length = 1;
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetLabels.php';
    var pars = 'timeTableLabelId='+form.timeTableLabelId.value;
    vanishData(1);

    if (form.timeTableLabelId.value=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.labelId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

window.onload=function(){
   getSurveyLabel();
}

</script>

</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedBackQuestionMappingAdvContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listFeedbackQuestionMappingAdv.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 20/02/10   Time: 12:25
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002923,0002322,0002921,0002920,0002919,
//0002918,0002917,0002916,0002915,0002914,
//0002912,0002911,0002913
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/02/10    Time: 13:38
//Updated in $/LeapCC/Interface
//Corrected case of "Library" path
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 19/01/10   Time: 15:24
//Updated in $/LeapCC/Interface
//Corrected validation message
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 1/19/10    Time: 3:10p
//Updated in $/LeapCC/Interface
//updated file name 'ajaxInitGetLabels.php'
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 1/18/10    Time: 2:42p
//Updated in $/LeapCC/Interface
//made updations under feedback module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/01/10   Time: 10:54
//Created in $/LeapCC/Interface
//Created module "Feedback Question Mapping (Advanced)"
?>