<?php
//-------------------------------------------------------
//  This File contains consolidated report for the student.
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentGazetteReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Gazette Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
page=1; //default page

function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("timeTable","<?php echo SELECT_TIME_TABLE;?>"),
                                new Array("degree","<?php echo SELECT_CLASS;?>"));


    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    showReport(page);
}

function hideResults() {
     document.getElementById('totalStudents').innerHTML='';
     document.getElementById("resultsDiv").innerHTML='';
     document.getElementById("pagingDiv").innerHTML='';
     document.getElementById('resultRow').style.display='none';
    document.getElementById("nameRow2").style.display='none';
    document.getElementById("nameRow").style.display='none'; 
}

function showReport(page) {
hideResults();
    //document.getElementById('totalStudents').innerHTML='0';
    form = document.testWiseMarksReportForm;
    totalLen = form.elements['subjectId[]'].length;
    totalSubjectSelected = 0;
     for(i=0;i<totalLen;i++) {
       form.elements['subjectId[]'][i].selected = true;  
       //if(form.elements['subjectId[]'][i].selected == true) {
       //  totalSubjectSelected++;
       //}
     }
     /*
     if (totalSubjectSelected == 0) {
         messageBox("Please select atleast 1 subject");
         form.elements['subjectId[]'].focus();
         return false;
     }
     */
     
    document.getElementById('resultRow').style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("nameRow").style.display='';
    pars = generateQueryString('testWiseMarksReportForm')+'&page='+page;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentFinalResultReport/initGazetteReport.php';
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
             
             totalStudents = j['totalStudents'];
             document.getElementById('totalStudents').innerHTML=totalStudents;
                if (totalStudents == 0) {
                    var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                    var tableData = globalTB;
                    tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text" class="reportBorder">#</td><td width="8%" class="searchhead_text reportBorder">Roll No.</td><td width="8%" class="searchhead_text reportBorder">Name</td>';
                    
                    tableData += '<td width=62% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
                    tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
                    tableData += '</table>';
                    document.getElementById("resultsDiv").innerHTML = tableData;
                }
                else {
                    document.getElementById("resultsDiv").innerHTML = j['resultList'];    
                }
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;  
              /*
                totalPages = totalStudents / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalStudents / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                //if(totalSubjects > 0) {
                  pagingData = pagination2(page, totalStudents, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                  document.getElementById("pagingDiv").innerHTML = pagingData;
                //}
              */  
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
   showDetail=1;
   valShow=1;
}

function resetStudyPeriod() {
    document.testWiseMarksReportForm.subjectTypeId.selectedIndex = 0;
}
  
function printNameReport() {

    form = document.testWiseMarksReportForm;
    pars = generateQueryString('testWiseMarksReportForm');
    path='<?php echo UI_HTTP_PATH;?>/gazetteReportNamePrint.php?'+pars;
    window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=800,left=100,top=100");
}

function printReport() {

    form = document.testWiseMarksReportForm;
    pars = generateQueryString('testWiseMarksReportForm');
    
    path='<?php echo UI_HTTP_PATH;?>/displayGazetteReport.php?'+pars;
    window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=800,left=100,top=100");
}

function printReportCSV() {
form = document.testWiseMarksReportForm;
    pars = generateQueryString('testWiseMarksReportForm');
    path='<?php echo UI_HTTP_PATH;?>/displayGazetteReportCSV.php?'+pars;
    window.location = path;   
   // window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=800,left=100,top=100");
}

function getLabelClass(){

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;
    if (timeTable == '') {
        return false;
    }

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelClass.php';
    var pars = 'timeTable='+timeTable;
    document.testWiseMarksReportForm.degree.length = null;
    addOption(document.testWiseMarksReportForm.degree, '', 'Select');
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
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
                }
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}
function getClassSubjects() {

    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentFinalResultReport/initClassGetAllSubjects.php';
    var pars = 'class1='+degree;
    if (degree == '') {
        document.testWiseMarksReportForm.subjectId.length = null;
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

                j = trim(transport.responseText).evalJSON();
                len = j.subjectArr.length;
                document.testWiseMarksReportForm.subjectId.length = null;
                // add option Select initially
                //addOption(document.testWiseMarksReportForm.subjectId, '', 'ALL');
                for(i=0;i<len;i++) {
                  addOption(document.testWiseMarksReportForm.subjectId, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
                }

                /*
                len = j.typeArr.length;
                document.testWiseMarksReportForm.subjectTypeId.length = null;
                // add option Select initially
                addOption(document.testWiseMarksReportForm.subjectTypeId, '', 'ALL');
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.subjectTypeId, j.typeArr[i].subjectTypeId, j.typeArr[i].subjectTypeName);
                }

                len = j.groupArr.length;
                document.testWiseMarksReportForm.groupId.length = null;
                addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.groupId, j.groupArr[i].groupId, j.groupArr[i].groupName);
                }
                */
                
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}



</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentFinalResultReport/listAwardGazetteContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
