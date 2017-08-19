<?php 

//-------------------------------------------------------
//  This File contains the template file and data base file
//
// Author :Parveen Sharma
// Created on : 01.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeFeedbackReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Teacher Survey Feed Back Details  </title>
<?php 
    require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">

function getTeacher() {
    document.getElementById('results').innerHTML='';
    form = document.feedBackForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/scGetFeedbackTeacher.php';
    if (document.getElementById('feedbackSurveyId').value=='') {
        return false;
    }
    new Ajax.Request(url,
    {
        method:'post',
        parameters:{  feedbackSurveyId: (document.getElementById('feedbackSurveyId').value) }, 
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            var fieldTeacher = document.getElementById('teacherId');  
            
            var objOption = new Option("Select","");
            fieldTeacher.options.length=0; 
            fieldTeacher.options.add(objOption); 
            for(var c=0;c<j.length;c++){
                var objOption = new Option(j[c].employeeName,j[c].employeeId);
                fieldTeacher.options.add(objOption); 
             }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}

function getTeacherFeedBackData() {
 
  if(trim(document.getElementById('teacherId').value)=="" || trim(document.getElementById('teacherId').value)=="Select"){
     messageBox("Please select a teacher");
     document.getElementById('teacherId').focus();
     return false;
 }   
  document.getElementById('results').innerHTML='';
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitFeedBackAdd.php';
  new Ajax.Request(url,
   {
     method:'post',
     asynchronous:false,
     parameters: {
         teacherId: (trim(document.getElementById('teacherId').value)),
         feedbackSurveyId: (document.getElementById('feedbackSurveyId').value)
         },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById('results').innerHTML=trim(transport.responseText);
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
  
}

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/scTeacherFeedBackReportPrint.php?teacherId='+document.getElementById('teacherId').value+'&feedbackSurveyId='+document.getElementById('feedbackSurveyId').value;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function printStatistics(text,val,quest) {

	//alert(id);
	 
	var name = document.getElementById('feedbackSurveyId');
	var name1 = document.getElementById('teacherId');
    path='<?php echo UI_HTTP_PATH;?>/scTeacherFeedBackStatisticsPrint.php?text='+text+'&val='+val+'&quest='+quest+'&surveyName='+name.options[name.selectedIndex].text+'&teacherName='+name1.options[name1.selectedIndex].text;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=520, top=150,left=150");
}

/* function to print all fee collection to csv*/
function printReportCSV() {

     path='<?php echo UI_HTTP_PATH;?>/scTeacherFeedBackReportPrintCSV.php?teacherId='+document.getElementById('teacherId').value+'&feedbackSurveyId='+document.getElementById('feedbackSurveyId').value;  
    //alert(path);
	window.location=path;
    //document.getElementById('generateCSV').href=path;
}

function getTeacherClear() {
    document.getElementById('results').innerHTML='';
}

 window.onload=function(){
     getTeacher();
 } 
</script> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EmployeeReports/scTeacherFeedbackReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//$History: teacherFeedbackReport.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 5/21/09    Time: 6:33p
//Created in $/LeapCC/Interface
//Intial checkin
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 5/18/09    Time: 7:26p
//Updated in $/Leap/Source/Interface
//added Graphical view of feedback given question wise
//
//*****************  Version 11  *****************
//User: Parveen      Date: 5/02/09    Time: 11:19a
//Updated in $/Leap/Source/Interface
//new enhancement in report student assign added
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/27/09    Time: 12:12p
//Updated in $/Leap/Source/Interface
//added Management Define.
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 3/27/09    Time: 11:25a
//Updated in $/Leap/Source/Interface
//changed image source to input type
//
//*****************  Version 8  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:57a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 2/25/09    Time: 10:23a
//Updated in $/Leap/Source/Interface
//added vss code






?>