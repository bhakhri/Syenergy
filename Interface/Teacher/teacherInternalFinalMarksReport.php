<?php 
//----------------------------------------------------------------
//  This File contains Student Internal Final Marks Foxpro Report
//
// Author :Parveen Sharma
// Created on : 28-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FinalMarksFoxproReport');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Final Marks Foxpro Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 
//This function Validates Form 
queryString = '';
recordsPerPage = 10000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'internalMarksFoxproFrm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
    
    form = document.internalMarksFoxproFrm; 
     
    timeTable = form.timeTable.value;
    classId = form.degree.value;
       
    if(timeTable=='') {
      messageBox("<?php echo SELECT_TIME_TABLE;?>");
      form.timeTable.focus();
      return false;
    }
    
    if(classId=='') { 
      messageBox("<?php echo SELECT_CLASS;?>");
      form.degree.focus();  
      return false;
    }
    getFinalMarks();
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function getFinalMarks() {
   
    var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/initStudentFinalMarksReport.php';   
     
    queryString = '';
    hideResults();
    page=1;
    
    form = document.internalMarksFoxproFrm; 
    timeTable = form.timeTable.value;
    classId = form.degree.value;
    
    queryString = 'timeTable='+timeTable+'&classId='+classId+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&page='+page;
          
    new Ajax.Request(url,
    {
          method:'post',
          asynchronous:false,
          parameters: queryString, 
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
               document.getElementById("nameRow").style.display='';
               document.getElementById("nameRow2").style.display='';
               document.getElementById("resultRow").style.display='';
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
}

function getLabelClass(){
    var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/initGetLabelFoxproClass.php';
    
    form = document.internalMarksFoxproFrm;
    var timeTable = form.timeTable.value;
    var pars = 'timeTable='+timeTable;
    
    document.internalMarksFoxproFrm.degree.length = null; 
    document.internalMarksFoxproFrm.subjectId.length = null;
    addOption(document.internalMarksFoxproFrm.degree, '', 'Select');
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.internalMarksFoxproFrm.degree.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(document.internalMarksFoxproFrm.degree, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(document.internalMarksFoxproFrm.degree, '', 'Select'); 
                }
                // now select the value                                     
                document.internalMarksFoxproFrm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       getClassSubjects();
}

function getClassSubjects() {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetFoxproSubjects.php';
    form = document.internalMarksFoxproFrm;
    
    form.subjectId.length = null;
    
    var degree = form.degree.value;
 
    var pars = '&degree='+degree;
    
    if(degree == '') {
      return false;
    }
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.internalMarksFoxproFrm.subjectId.length = null;
                for(i=0;i<len;i++) { 
                    addOption(document.internalMarksFoxproFrm.subjectId, j[i].subjectId, j[i].subjectCode);
                }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function printReport() {
   
    var formx = document.internalMarksFoxproFrm;
    var classId=formx.class1.value;
    var className = document.getElementById('class1'); 
    var className=className.options[className.selectedIndex].text;   
    
    path='<?php echo UI_HTTP_PATH;?>/Teacher/teacherInternalFinalMarksPrint.php?classId='+classId+'&className='+className;
    a = window.open(path,"StudentFinalMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printReportCSV() {
   
    var formx = document.internalMarksFoxproFrm;
    var classId=formx.class1.value;
    var className = document.getElementById('class1'); 
    var className=className.options[className.selectedIndex].text;   
    
    path='<?php echo UI_HTTP_PATH;?>/Teacher/teacherInternalFinalMarksCSV.php?classId='+classId+'&className='+className;
    a = window.open(path,"StudentFinalMarksReport","status=1,menubar=1,scrollbars=1, width=900");
}

window.onload=function(){
   //loads the data
   document.internalMarksFoxproFrm.timeTable.focus();
   getLabelClass();
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/teacherInternalFinalMarksContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
//$History: teacherInternalFinalMarksReport.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/16/09    Time: 2:54p
//Created in $/LeapCC/Interface/Teacher
//inital checkin
//

?>
