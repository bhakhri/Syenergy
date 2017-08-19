<?php 
// This file generate a list Student Rank wise Report
//
// Author :Parveen Sharma
// Created on : 12-12-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentRank');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Exam Rankwise Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initStudentRankWiseReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'studentRankForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

function refreshTable() {

    var classResult = '';
       
    frm = document.studentRankForm;       
    var tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                                 new Array('studentName','Name','width="18%" align="left"',true), 
                                 //new Array('className','Class','width="18%" align="left"',true),
                                 new Array('universityRollNo','URoll. No.','width="11%" align="left"',true),
                                 new Array('rollNo','CRoll No.','width="11%" align="left"',true),
                                 new Array('compExamBy','Exam. By','width="12%" align="left"',true),
                                 new Array('compExamRollNo','Exam. RNo.','width="12%" align="left"',true),
                                 new Array('compExamRank','Rank','width="9%" align="left"',true));
  
    var cnt = frm.studentExamResultId.length;
    if(cnt>0) {
      for(var i=0;i<cnt;i++) {
          str1 = "m"+frm.studentExamResultId.options[i].value; 
          str = "e"+frm.studentExamResultId.options[i].value; 
          if(frm.studentExamResultId.options[i].value!='all') {
            tableColumns.push(new Array(str1,frm.studentExamResultId.options[i].text+"&nbsp;",'width="8%" align="right"',false));
            tableColumns.push(new Array(str,"%age&nbsp;",'width="8%" align="right"',false));
            if(classResult == '') {
              classResult = str; 
            }
            else {
              classResult = classResult +','+str;  
            }
          }
      }          
    }                                       
    timeTable = frm.timeTable.value;
    classId  = frm.classId.value;
    
    //queryString = '&timeTable='+timeTable+'&classId='+classId+'&classResultId='+classResult;  
    queryString = generateQueryString('studentRankForm'); 
    queryString = queryString+'&classResultId='+classResult; 
    listObj4 = new initPage(listURL,recordsPerPage,linksPerPage,1,'','studentName','ASC','resultsDiv','','',true,'listObj4',tableColumns,'','',queryString);
    sendRequest(listURL, listObj4,'',true)
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
}

function validateAddForm(frm) {
      
   /* if(document.getElementById("timeTable").value == '') {
         messageBox("<?php echo SELECT_TIME_TABLE; ?>");  
         document.getElementById('timeTable').focus();         
         return false;
      }
   */   
      if(document.getElementById("classId").value == '') {
         messageBox("<?php echo SELECT_DEGREE; ?>");  
         document.getElementById('classId').focus(); 
         return false;
      }
      
      if(document.getElementById('rank').value!='') {
          if((!isInteger(document.getElementById('rankValue').value))) {
             messageBox("<?php echo ENTER_NUMBER;?>");
             document.getElementById('rankValue').focus();
             return false;
          }
          
          if(document.getElementById('rankValue').value!='' && document.getElementById('rank').value=='') {
             messageBox("<?php echo "Select Rank";?>");
             document.getElementById('rank').focus();
             return false;
          }
          
          if(document.getElementById('rankValue').value=='' && document.getElementById('rank').value!='') {
             messageBox("<?php echo "Enter Rank Value";?>");
             document.getElementById('rankValue').focus();
             return false;
          }
      }
      
     hideResults();  
     refreshTable();
}

function checkStatus() {
    if(document.getElementById('rank').value!='') {   
      document.getElementById('rankValue').disabled=false;
    }
    else {
      document.getElementById('rankValue').value='';  
      document.getElementById('rankValue').disabled=true;
    }
    
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/studentRankWiseReportPrint.php?&sortOrderBy='+sortOrderBy+'&sortField='+sortField+queryString;
    window.open(path,"StudentRankWiseReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printReportCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/studentRankWiseReportPrintCSV.php?&sortOrderBy='+sortOrderBy+'&sortField='+sortField+queryString;
    window.location=path;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function getLabelClass(){
   
     var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/ajaxGetTimeTableClass.php';
    
     frm = document.studentRankForm;
     var timeTable = frm.timeTable.value;
     
     frm.classId.length = null; 
     addOption(frm.classId, '', 'Select');

     if(timeTable=='') {
       return false;  
     }
     
     var rval=timeTable.split('~');

     var pars = 'timeTableLabelId='+rval[0];     
     
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
                frm.classId.length = null;                  
                if(len>0) { 
                  for(i=0;i<len;i++) { 
                    addOption(frm.classId, j[i].classId, j[i].className);
                  }
                }
                else {
                  addOption(frm.classId, '', 'Select'); 
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

window.onload=function() {
   //loads the data
   getLabelClass(); 
   checkStatus();
   document.studentRankForm.timeTable.focus();
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentRankWiseContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
//$History: studentRankWiseReport.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 4/08/10    Time: 3:04p
//Updated in $/LeapCC/Interface
//checkStatus function added
//
//*****************  Version 8  *****************
//User: Parveen      Date: 4/08/10    Time: 2:42p
//Updated in $/LeapCC/Interface
//time table label base report format updated
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/11/10    Time: 3:42p
//Updated in $/LeapCC/Interface
//sorting order format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/11/10    Time: 3:12p
//Updated in $/LeapCC/Interface
//tag name updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/24/09   Time: 3:20p
//Updated in $/LeapCC/Interface
//printReport function updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/24/09   Time: 2:49p
//Updated in $/LeapCC/Interface
//sorting order updated
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/04/09    Time: 12:46p
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/12/09    Time: 2:15p
//Created in $/LeapCC/Interface
//file added
//

?>
