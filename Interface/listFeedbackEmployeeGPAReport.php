<?php
//---------------------------------------------------------------------------
// THIS FILE is used for assigning survey to emps/student/parents
// Author : Dipanjan Bhattacharjee
// Created on : (13.01.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_EmployeeGPAReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Employee GPA Report (Advanced)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var topPos = 0;
var leftPos = 0;
recordsPerPage = <?php echo RECORDS_PER_PAGE ;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//To get Label on basis of timeTable
function getSurveyLabel(value) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSurveyLabels.php';
    
    var form = document.allDetailsForm;
    form.labelId.length = 1;
    form.employeeId.length = 1;
    
    vanishData();
    if (value=='') {
        return false;
    }
    var pars = 'timeTableLabelId='+value+'&type=2';
   
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
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.labelId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getEmployees(value1,value2) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetEmployees.php';
    
    var form = document.allDetailsForm;
    form.employeeId.length = 1;
    
    vanishData();
    if (value1=='' || value2=='') {
        return false;
    }
   
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:false,
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2
        },
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.employeeId, j[i].employeeId, j[i].employeeName);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getCategory(value1,value2,value3) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetCategory.php';
    
    var form = document.allDetailsForm;
    form.catId.length = 1;
    
    vanishData();
    if (value1=='' || value2=='') {
        return false;
    }
   
    new Ajax.Request(url,
    {
        method:'post', 
        asynchronous:false,
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2,
                 employeeId       : value3
                 
        },
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.catId, j[i].feedbackCategoryId, j[i].feedbackCategoryName);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}


/*
//To get Label on basis of timeTable
function getSurveyLabel(value) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSurveyLabels.php';
    
    var form = document.allDetailsForm;
    form.labelId.length = 1;
    form.catId.length = 1;
    vanishData();
    
    if (value=='') {
        return false;
    }
    var pars = 'timeTableLabelId='+value;
   
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
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.labelId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getCategories(value1,value2) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSurveyCategories.php';
    
    var form = document.allDetailsForm;
    form.catId.length = 1;
    vanishData();
    
    if (value1=='' || value2=='') {
        return false;
    }
    var pars = 'timeTableLabelId='+value1+'&labelId='+value2;
   
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
            var len = j.length;
            for(i=0;i<len;i++) {
                addOption(form.catId, j[i].feedbackCategoryId, j[i].feedbackCategoryName);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

*/


function showReport() {
  
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
  var labelId=document.getElementById('labelId').value;
  var catId=document.getElementById('catId').value;
  var employeeId=document.getElementById('employeeId').value;
  
  if(timeTableLabelId==''){
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
  }
  
  if(labelId==''){
      messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
      document.getElementById('labelId').focus();
      return false;
  }
  

  vanishData();

  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetEmployeeGPAReport.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : timeTableLabelId,
                 labelId          : labelId,
                 categoryId       : catId,
                 employeeId       : employeeId  
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.getElementById('reportDiv').innerHTML=trim(transport.responseText);
                    document.getElementById('printRowId').style.display=''; 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
     if(document.getElementById('helpChk').checked == false) {
         return false;
     }
    //document.getElementById('divHelpInfo').innerHTML=title;      
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function vanishData(){
   document.getElementById('reportDiv').innerHTML='';
   document.getElementById('printRowId').style.display='none'; 
}



/* function to print report*/
function printReport() {
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
  var labelId=document.getElementById('labelId').value;
  var catId=document.getElementById('catId').value;
  
  if(timeTableLabelId==''){
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
  }
  
  if(labelId==''){
      messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
      document.getElementById('labelId').focus();
      return false;
  }
  
   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&catId='+catId+'&timeTableName='+timeTableName+'&labelName='+labelName+'&employeeId='+document.getElementById('employeeId').value;
   var path='<?php echo UI_HTTP_PATH;?>/employeeGPAReportPrint.php?'+qstr;

   window.open(path,"ClassGpaReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
   
}


/* function to output data to a CSV*/
function printCSV() {
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
  var labelId=document.getElementById('labelId').value;
  var catId=document.getElementById('catId').value;
  
  if(timeTableLabelId==''){
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      document.getElementById('timeTableLabelId').focus();
      return false;
  }
  
  if(labelId==''){
      messageBox("<?php echo SELECT_ADV_LABEL_NAME;?>");
      document.getElementById('labelId').focus();
      return false;
  }
   
  var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&catId='+catId+'&employeeId='+document.getElementById('employeeId').value;
  window.location='employeeGPAReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackEmployeeGPAReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php                              
// $History: listFeedbackClassFinalReport.php $ 
?>