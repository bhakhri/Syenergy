<?php
//---------------------------------------------------------------------------
// THIS FILE is used for assigning survey to emps/student/parents
// Author : Dipanjan Bhattacharjee
// Created on : (13.01.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_ClassFinalReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Class Final Report (Advanced) Print</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
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

function getClasss(value1,value2) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetClasss.php';
    
    var form = document.allDetailsForm;
    form.classId.length = 1;
    form.employeeId.length = 1;
    
    vanishData();
    if (value1=='' || value2=='') {
        return false;
    }
   
    new Ajax.Request(url,
    {
        method:'post', 
        asynchronous:true,
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
                addOption(form.classId, j[i].classId, j[i].className);
            }

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getEmployees(value1,value2,value3) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetEmployees.php';
    
    var form = document.allDetailsForm;
    form.employeeId.length = 1;
    
    vanishData();
    if (value1=='' || value2=='' || value3=='') {
        return false;
    }
   
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous:true,
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2,
                 classId          : value3
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


function showReport() {
  
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
  var labelId=document.getElementById('labelId').value;
  var classId=document.getElementById('classId').value;
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
  
  if(classId==''){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
  }
 
  vanishData();

  var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetClassFinalReport.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : timeTableLabelId,
                 labelId          : labelId,
                 classId          : classId,
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

function vanishData(){
   document.getElementById('reportDiv').innerHTML='';
   document.getElementById('printRowId').style.display='none'; 
}



/* function to print report*/
function printReport() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
    var labelId=document.getElementById('labelId').value;
    var classId=document.getElementById('classId').value;
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
   
   if(classId==''){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
   }
  
   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   var teacherName=escape(document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text);
   var className=escape(document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text);
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&employeeId='+employeeId+'&classId='+classId+'&timeTableName='+timeTableName+'&labelName='+labelName+'&teacherName='+teacherName+'&className='+className;
   var path='<?php echo UI_HTTP_PATH;?>/classFinalReportPrint.php?'+qstr;

   window.open(path,"ClassGpaReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
   
}


/* function to output data to a CSV*/
function printCSV() {
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
    var labelId=document.getElementById('labelId').value;
    var classId=document.getElementById('classId').value;
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
   
   if(classId==''){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
   }
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&employeeId='+employeeId+'&classId='+classId;
   window.location='classFinalReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackClassFinalReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php                              
// $History: listFeedbackClassFinalReport.php $ 
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 25/02/10   Time: 13:55
//Created in $/LeapCC/Interface
//Created "Class Final Report"  for advanced feedback modules.
?>