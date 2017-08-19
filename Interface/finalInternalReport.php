<?php

//-------------------------------------------------------
//  This File contains Validation and ajax function used in StudentLabels Form
//
//
// Author :Ajinder Singh
// Created on : 12-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
$linkHeading="Final Marks Report"; 
global $sessionHandler;
$roleId=$sessionHandler->getSessionVariable('RoleId');
if($roleId==2){
  $linkHeading="Display Final Internal Marks Report";  
  define('MODULE','DisplayFinalInternalReport');
  define('ACCESS','view');
  define('MANAGEMENT_ACCESS',1);  
  UtilityManager::ifTeacherNotLoggedIn(true);
}
else{
  define('MODULE','FinalInternalReport');
  define('ACCESS','view');
  define('MANAGEMENT_ACCESS',1);  
  UtilityManager::ifNotLoggedIn(true);
}
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME.": ".$linkHeading; ?></title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=right','align=right',false),
                               new Array('conductingAuthority2','Conducting Authority','width=18% align=left','align=left',true),
                               new Array('subjectCode','Subject','width="15%" align=left','align=left',true),
                               new Array('testTypeName','Test Type','width="18%" align="left"','align="left"',false),
                               new Array('weightageAmount','Weightage Amt.','width="18%" align="right"','align="right"',false),
                               new Array('weightagePercentage','Weightage %','width="18%" align="right"','align="right"',false));

 //This function Validates Form
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'testWiseMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {

    if("<?php echo $roleId ?>" != 2) {  
       var fieldsArray = new Array(new Array("employeeId","<?php echo SELECT_EMPLOYEE;?>"), 
                                   new Array("degree","<?php echo SELECT_DEGREE;?>"), 
                                   new Array("subjectId","<?php echo SELECT_SUBJECT;?>"), 
                                   new Array("groupId","<?php echo SELECT_GROUP;?>") );
    }
    else {
        var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), 
                                    new Array("subjectId","<?php echo SELECT_SUBJECT;?>"), 
                                    new Array("groupId","<?php echo SELECT_GROUP;?>") );
    }

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    //openStudentLists(frm.name,'rollNo','Asc');

    hideResults();

    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';


    showReport(page);
    //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function showReport(page) {
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var subjectId = form.subjectId.value;
    var groupId = form.groupId.value;
    var timetable = form.timeTable.value;
    var sorting = form.sorting.value;
    var roundMethod = form.roundMethod.value;  
    //var ordering = form.ordering.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
    var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
    var showMarks = form.showMarks[0].checked==true?'0':'1';
    var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    var showExternalMarks = form.showExternalMarks[0].checked==true?'0':'1';
    
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalInternalReportNew.php';
    
    var pars =  'timetable='+timetable+'&degree='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&showExternalMarks='+showExternalMarks;
    pars += '&sorting='+sorting+'&showGraceMarks='+showGraceMarks+'&showMarks='+showMarks+'&showUnivRollNo='+showUnivRollNo;
    pars += '&ordering='+ordering+'&page='+page+"&roundMethod="+roundMethod;
    
    if("<?php echo $roleId ?>" != 2) {  
      pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport) {
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                totalRecords = parseInt(j['totalRecords']);
                
                
                document.getElementById("resultsDiv").innerHTML = j['resultData'];
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("courseAverage").innerHTML = j['average'];
                document.getElementById("teachers").innerHTML = j['teachers'];
                document.getElementById("subjectName").innerHTML = j['subjectName'];

                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if(totalPages > completePages) {
                   completePages++;
                }
                if(totalRecords > parseInt("<?php echo RECORDS_PER_PAGE; ?>")) {
                  pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                  document.getElementById("pagingDiv").innerHTML = pagingData;
                }

           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function getLabelEmployee() {

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelMarksTransferredEmployee.php';
    var pars = 'timeTable='+timeTable;
   
    document.testWiseMarksReportForm.employeeId.length = null;
    addOption(document.testWiseMarksReportForm.employeeId, '', 'Select');
   
    document.testWiseMarksReportForm.degree.length = null;
    addOption(document.testWiseMarksReportForm.degree, '', 'Select');
    
    document.testWiseMarksReportForm.subjectId.length = null;
    addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
    
    document.testWiseMarksReportForm.groupId.length = null;
    addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
    
    
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
                document.testWiseMarksReportForm.employeeId.length = null;        
                if(len > 0) {
                  addOption(document.testWiseMarksReportForm.employeeId, 'all', 'All');
                }
                else {
                  addOption(document.testWiseMarksReportForm.employeeId, '', 'Select');  
                }
                for(i=0;i<len;i++) {
                  addOption(document.testWiseMarksReportForm.employeeId, j[i].employeeId, j[i].employeeName);
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function getLabelClass(){

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalClass.php';
    
    var pars = 'timeTable='+timeTable; 
    if("<?php echo $roleId ?>" != 2) {  
       pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    document.testWiseMarksReportForm.degree.length = null;
    addOption(document.testWiseMarksReportForm.degree, '', 'Select');
    
    document.testWiseMarksReportForm.subjectId.length = null;
    addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
    
    document.testWiseMarksReportForm.groupId.length = null;
    addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
    
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

                document.testWiseMarksReportForm.degree.length = null;
                addOption(document.testWiseMarksReportForm.degree, '', 'Select');
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
                }
                // now select the value
                //document.testWiseMarksReportForm.degree.value = j[0].classId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function resetStudyPeriod() {
    document.testWiseMarksReportForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {
    
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var subjectId = form.subjectId.value;
    var groupId = form.groupId.value;
    var timetable = form.timeTable.value;
    var sorting = form.sorting.value;
    var roundMethod = form.roundMethod.value;
    
    //var ordering = form.ordering.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
    var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
    var showMarks = form.showMarks[0].checked==true?'0':'1';
    var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    var showExternalMarks = form.showExternalMarks[0].checked==true?'0':'1';
    
    
    var pars =  'timetable='+timetable+'&degree='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&showExternalMarks='+showExternalMarks;
    pars += '&sorting='+sorting+'&showGraceMarks='+showGraceMarks+'&showMarks='+showMarks+'&showUnivRollNo='+showUnivRollNo;
    pars += '&ordering='+ordering+"&roundMethod="+roundMethod; 
    if("<?php echo $roleId ?>" != 2) {  
       pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    var path='<?php echo UI_HTTP_PATH;?>/finalInternalReportPrint.php?'+pars;
   hideUrlData(path,true);
}

function printCSV() {
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var subjectId = form.subjectId.value;
    var groupId = form.groupId.value;
    var timetable = form.timeTable.value;
    var sorting = form.sorting.value;
    var roundMethod = form.roundMethod.value;   
     
    //var ordering = form.ordering.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
    var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
    var showMarks = form.showMarks[0].checked==true?'0':'1';
    var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    var showExternalMarks = form.showExternalMarks[0].checked==true?'0':'1';
    
    var pars =  'timetable='+timetable+'&degree='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&showExternalMarks='+showExternalMarks;
    pars += '&sorting='+sorting+'&showGraceMarks='+showGraceMarks+'&showMarks='+showMarks+'&showUnivRollNo='+showUnivRollNo;
    pars += '&ordering='+ordering+"&roundMethod="+roundMethod;   
 
    if("<?php echo $roleId ?>" != 2) {  
       pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    window.location = 'finalInternalReportPrintCSV.php?'+pars;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function getClassSubjects() {
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var timeTable = form.timeTable.value; 
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalClassSubjects.php';
    
    var pars = 'degree='+degree+'&timeTable='+timeTable;
    if("<?php echo $roleId ?>" != 2) {  
       pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    
    if (degree == '') {
        document.testWiseMarksReportForm.subjectId.length = null;
        document.testWiseMarksReportForm.groupId.length = null;
        addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
        addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
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
                document.testWiseMarksReportForm.subjectId.length = null;
                addOption(document.testWiseMarksReportForm.subjectId, '', 'Select');
                /*
                if (len > 0) {
                    addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
                }
                */
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
                }
                // now select the value
               // document.testWiseMarksReportForm.subjectId.value = j[0].subjectId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function getGroups() {
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var subjectId = form.subjectId.value;
    if (degree == '' || subjectId == '') {
        return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalClassGroups.php';
    
    var timeTable = form.timeTable.value;    
    var pars = 'degree='+degree+'&subjectId='+subjectId+'&timeTable='+timeTable;
    
    if("<?php echo $roleId ?>" != 2) {  
       pars = pars +'&employeeId='+document.testWiseMarksReportForm.employeeId.value;        
    }
    
    if (degree == '') {
        document.testWiseMarksReportForm.groupId.length = null;
        addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
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
                document.testWiseMarksReportForm.groupId.length = null;
                addOption(document.testWiseMarksReportForm.groupId, '', 'Select');
                if (len > 0) {
                    addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.groupId, j[i].groupId, j[i].groupName);
                }
                // now select the value
                document.testWiseMarksReportForm.groupId.value = 'all';
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}
window.onload=function(){
    //loads the data
    document.testWiseMarksReportForm.timeTable.focus();
    if("<?php echo $roleId ?>" != 2) {  
      getLabelEmployee();
    }
    getLabelClass();
}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listFinalInternalReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
