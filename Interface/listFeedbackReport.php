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
define('MODULE','Feedback_Report');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Feedback Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('rollNo','Roll No.','width="10%"','',true),  
                               new Array('studentName','Student Name ','width="15%"','',true),
                               new Array('className','Class Name','width="22%"','',true),
                               new Array('tSubjectName','Subject','width="18%"','',true), 
                               //new Array('subjectName','Subject Name ','width="15%"','',true), 
                               //new Array('subjectCode','Subject Code','width="12%"','',true), 
                               new Array('employeeName','Teacher','width="12%"','',true),
                               new Array('feedbackCategoryName','Category','width="12%"','',true),   
                               new Array('points','Points','width="15%" align="right"','align="right"',true));
                               
                               

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedbackReport.php';  
listURL = '<?php echo HTTP_LIB_PATH;?>/ajaxGetFeedbackScoreReport.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'questionName';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button  
// ajax search results ---end ///


//To get Label on basis of timeTable
function getSurveyLabel(value) {
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetSurveyLabels.php';
    
    var form = document.allDetailsForm;
    form.labelId.length = 1;
    form.employeeId.length = 1;
    
    document.allDetailsForm.labelId.length = null; 
    addOption(document.allDetailsForm.labelId, '', 'Select');
    
    document.allDetailsForm.classId.length = null; 
    addOption(document.allDetailsForm.classId, '', 'Select');
    
    document.allDetailsForm.employeeId.length = null; 
    addOption(document.allDetailsForm.employeeId, '', 'Select');
    
    document.allDetailsForm.categoryId.length = null; 
    addOption(document.allDetailsForm.categoryId, '', 'Select');
    
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
            document.allDetailsForm.labelId.length = null; 
            addOption(document.allDetailsForm.labelId, '', 'Select'); 
            if(len>0) {    
              for(i=0;i<len;i++) {
                addOption(form.labelId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
              }
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function getClasss(value1,value2) {
    //var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxInitGetClasss.php';
    var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetClassesEmployeeCategory.php';
    
    var frm = document.allDetailsForm;
    frm.classId.length = 1;
    frm.employeeId.length = 1;
   
    document.allDetailsForm.classId.length = null; 
    addOption(document.allDetailsForm.classId, '', 'Select');
     
    document.allDetailsForm.employeeId.length = null; 
    addOption(document.allDetailsForm.employeeId, '', 'Select');
    
    document.allDetailsForm.categoryId.length = null; 
    addOption(document.allDetailsForm.categoryId, '', 'Select');
    
    
    var value3 = '';     
    
    vanishData();
    if (value1=='' || value2=='') {
        return false;
    }
   
    new Ajax.Request(url,
    {
        method:'post', 
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2
        },
        asynchronous:true,  
        onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            
            var ret=trim(transport.responseText).split('!~!~!');  
            
            document.allDetailsForm.classId.length = null; 
            addOption(document.allDetailsForm.classId, '', 'Select');
             
            document.allDetailsForm.employeeId.length = null; 
            addOption(document.allDetailsForm.employeeId, '', 'Select');
            
            document.allDetailsForm.categoryId.length = null; 
            addOption(document.allDetailsForm.categoryId, '', 'Select');
            
            if(ret.length > 0 ) {
                var j = eval('(' + ret[0] + ')');
                len = j.length;
                frm.classId.length = null;
                if (len > 0) {
                  addOption(frm.classId, 'all', 'All');
                }
                else {
                  addOption(frm.classId, '', 'Select');  
                }
                for(i=0;i<len;i++) { 
                   addOption(frm.classId, j[i].classId, j[i].className);
                }
            
                var j = eval('(' + ret[1] + ')');
                len = j.length;
                frm.employeeId.length = null;
                if (len > 0) {
                  addOption(frm.employeeId, 'all', 'All');
                }
                else {
                  addOption(frm.employeeId, '', 'Select');  
                }
                for(i=0;i<len;i++) {        
                   addOption(frm.employeeId, j[i].employeeId, j[i].employeeName);
                }
                
                var j = eval('(' + ret[2] + ')');
                len = j.length;
                frm.categoryId.length = null;
                if (len > 0) {
                  addOption(frm.categoryId, 'all', 'All');
                }
                else {
                  addOption(frm.categoryId, '', 'Select');  
                }
                for(i=0;i<len;i++) {        
                   addOption(frm.categoryId, j[i].feedbackCategoryId, j[i].feedbackCategoryName);
                }
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
        parameters: {
                 timeTableLabelId : value1,
                 labelId          : value2,
                 classId          : value3
        },
        asynchronous:true, 
        onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            document.allDetailsForm.employeeId.length = null; 
            if(len>0) {
                addOption(form.employeeId, 'all', 'All');
                for(i=0;i<len;i++) {
                    addOption(form.employeeId, j[i].employeeId, j[i].employeeName);
                }
            }
            else {
              addOption(document.allDetailsForm.employeeId, '', 'Select');  
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function showScoreReport() {
  
      var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedbackScoreReport.php'; 
    
      var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
      var labelId=document.getElementById('labelId').value;
      var classId=document.getElementById('classId').value;
      var employeeId=document.getElementById('employeeId').value;
      var categoryId=document.getElementById('categoryId').value;   
      
      vanishData();
      
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
  
      var params = generateQueryString('allDetailsForm');
      
      new Ajax.Request(url,
      {
        method:'post',
        parameters: params, 
        asynchronous:true, 
        onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
           hideWaitDialog(true);
           if(trim(transport.responseText)==false) {
             messageBox("<?php echo "No Data Found"; ?>");  
           }
           else {
             document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
             document.getElementById("nameRow").style.display='';
             document.getElementById("nameRow2").style.display='';
             document.getElementById("resultRow").style.display='';
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
  var categoryId=document.getElementById('categoryId').value;   
  
  vanishData();
  
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
  
 /*
  if(classId==''){
      messageBox("<?php echo SELECT_CLASS;?>");
      document.getElementById('classId').focus();
      return false;
  }
 */ 
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
  document.getElementById("nameRow").style.display='';
  document.getElementById("nameRow2").style.display='';
  document.getElementById("resultRow").style.display='';
  return false;
   
}

function vanishData() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';
}


/* function to print report*/
function printReport() {
   var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
   var labelId=document.getElementById('labelId').value;
   var classId=document.getElementById('classId').value;
   var employeeId=document.getElementById('employeeId').value;
   var categoryId=document.getElementById('categoryId').value;   
   
   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   var teacherName=escape(document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text);
   var className=escape(document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text);
   var categoryName=escape(document.getElementById('categoryId').options[document.getElementById('categoryId').selectedIndex].text);    
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&employeeId='+employeeId+'&classId='+classId+'&categoryId='+categoryId;
   qstr =qstr+'&timeTableName='+timeTableName+'&labelName='+labelName+'&teacherName='+teacherName+'&className='+className;
   qstr =qstr+'&categoryName='+categoryName;
   var path='<?php echo UI_HTTP_PATH;?>/listFeedbackReportPrint.php?'+qstr;

   window.open(path,"FeedbackReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printReportCSV() {
   
   var timeTableLabelId=document.getElementById('timeTableLabelId').value;   
   var labelId=document.getElementById('labelId').value;
   var classId=document.getElementById('classId').value;
   var employeeId=document.getElementById('employeeId').value;
   var categoryId=document.getElementById('categoryId').value;   
   
   var timeTableName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
   var labelName=escape(document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text);
   var teacherName=escape(document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text);
   var className=escape(document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text);
   var categoryName=escape(document.getElementById('categoryId').options[document.getElementById('categoryId').selectedIndex].text);    
   
   var qstr='timeTableLabelId='+timeTableLabelId+'&labelId='+labelId+'&employeeId='+employeeId+'&classId='+classId+'&categoryId='+categoryId;
   qstr =qstr+'&timeTableName='+timeTableName+'&labelName='+labelName+'&teacherName='+teacherName+'&className='+className;
   qstr =qstr+'&categoryName='+categoryName;
   
   window.location='listFeedbackReportCSV.php?'+qstr;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedbackReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
