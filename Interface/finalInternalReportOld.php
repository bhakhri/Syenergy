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
define('MODULE','FinalInternalReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
$linkHeading="Display Final Internal Marks Report";
if ($sessionHandler->getSessionVariable('RoleId') != 2) {
    UtilityManager::ifNotLoggedIn();
    $linkHeading="Final Internal Marks Report";
}

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
    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"), new Array("subjectId","<?php echo SELECT_SUBJECT;?>"), new Array("groupId","<?php echo SELECT_GROUP;?>") );

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
    //var ordering = form.ordering.value;
    var ordering = form.ordering[0].checked==true?'asc':'desc';
     var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
     var showMarks = form.showMarks[0].checked==true?'0':'1';
     var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalInternalReport.php';
    var pars = 'timetable='+timetable+'&degree='+degree+'&subjectId='+subjectId+'&groupId='+groupId+'&sorting='+sorting+'&showGraceMarks='+showGraceMarks+'&showMarks='+showMarks+'&ordering='+ordering+'&page='+page;


     if (degree == '' || subjectId == '' || groupId == '') {
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
                totalRecords = parseInt(j['totalRecords']);

                totalTests = j['testTypes'].length;
                if (totalTests == 0) {
                    var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                    var tableData = globalTB;
                    tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text reportBorder">#</td>';
                    if (showUnivRollNo == '1') {
                        tableData += '<td width="8%" class="searchhead_text reportBorder">U.Roll No.</td>';
                    }
                    tableData += '<td width="8%" class="searchhead_text reportBorder">Roll No.</td>';
                    tableData += '<td width="20%" class="searchhead_text reportBorder">Student Name</td>';
                    tableData += '<td width=62% class="searchhead_text reportBorder">&nbsp;</td></tr><tr '+bg+'>';
                    tableData += '<td class="padding_top" align=center colspan=5>No details found</td></tr>';
                }
                else {
                    var tableData = globalTB;
                    tableData += '<tr class="rowheading"><td rowspan="3" width="2%" class="searchhead_text">#</td>';
                    if (showUnivRollNo == '1') {
                        tableData += '<td rowspan="3" width="8%" class="searchhead_text">U.Roll No.</td>';
                    }
                    tableData += '<td rowspan="3" width="8%" class="searchhead_text">Roll No.</td>';
                    tableData += '<td width="20%" rowspan="3" class="searchhead_text">Student Name</td>';


                    allTests = 0;
                    for(i=0;i<totalTests;i++) {
                        testTypeId = j['testTypes'][i]['testTypeId'];
                        testTypeCategoryId = j['testTypes'][i]['testTypeCategoryId'];
                        isAttendanceCategory = j['testTypes'][i]['isAttendanceCategory'];
                        testTypeName = j['testTypes'][i]['testTypeName'];
                        if (isAttendanceCategory == 1 || isAttendanceCategory == "1") {
                            allTests += 5;//for attendance

                        }
                        else {
                            testTypeCategoryIdTests = j[testTypeCategoryId].length;
                            allTests += testTypeCategoryIdTests;
                            allTests++;//for evaluated marks
                        }
                    }
                    perTestSpace = parseInt(62/allTests)+'%';
                    //tableData += '<tr class="rowheading">';
                    for(i=0;i<totalTests;i++) {
                        testTypeId = j['testTypes'][i]['testTypeId'];
                        testTypeCategoryId = j['testTypes'][i]['testTypeCategoryId'];
                        isAttendanceCategory = j['testTypes'][i]['isAttendanceCategory'];
                        testTypeName = j['testTypes'][i]['testTypeName'];
                        if (isAttendanceCategory == 1 || isAttendanceCategory == "1") {
                            tableData += '<td align="center" class=" searchhead_text" colspan = "6">Attendance&nbsp;</td>';

                        }
                        else {
                            testTypeCategoryIdTests = j[testTypeCategoryId].length;
                            thisColSpan = testTypeCategoryIdTests + 1;
                            tableData += '<td align="center" class=" searchhead_text" colspan = "'+thisColSpan+'">'+testTypeName+'&nbsp;</td>';
                            allTests += testTypeCategoryIdTests;
                            allTests++;//for evaluated marks
                        }
                    }
                    tableData += '<td align="center" class=" searchhead_text" colspan = "1">External</td>';
                    
                    //showGraceMarksValue = form.showGraceMarks.value;
                    showGraceMarksValue = form.showGraceMarks[1].checked;
                    //if (showGraceMarksValue == 'yes') {
                    if (showGraceMarksValue == true) {
                        tableData += '<td align="center" class=" searchhead_text" colspan="3" colspan = "1">Grace</td>';
                    }
                    tableData += '<td align="center" class=" searchhead_text" colspan = "1">Total</td>';  

                    tableData += '<td align="center" class=" searchhead_text" rowspan="3" colspan = "1">G.Total</td>';
                    tableData += '</tr>';
                    tableData += '<tr class="rowheading">';
                    totalTestTypeMaxMarks = 0;

                    for(i=0;i<totalTests;i++) {
                        testTypeId = j['testTypes'][i]['testTypeId'];
                        testTypeCategoryId = j['testTypes'][i]['testTypeCategoryId'];
                        isAttendanceCategory = j['testTypes'][i]['isAttendanceCategory'];
                        testTypeName = j['testTypes'][i]['testTypeName'];
                        testTypeMaxMarks = j['testTypes'][i]['maxMarks'];
                        testTypeAbbr = j['testTypes'][i]['testTypeAbbr'];
                        totalTestTypeMaxMarks += parseInt(testTypeMaxMarks);
                        if (isAttendanceCategory == 1 || isAttendanceCategory == "1") {
                            tableData += '<td align="center" rowspan="2"  class=" searchhead_text" colspan = "1">Held</td>';
                            tableData += '<td align="center" rowspan="2" class=" searchhead_text" colspan = "1">Attended</td>';
                            tableData += '<td align="center" rowspan="2" class=" searchhead_text" colspan = "1">DL</td>';
                            tableData += '<td align="center" rowspan="2" class=" searchhead_text" colspan = "1">Total</td>';
                            tableData += '<td align="center" rowspan="2" class=" searchhead_text" colspan = "1">%</td>';
                            tableData += '<td align="center" rowspan="2" class=" searchhead_text" colspan = "1">M.M.<br> '+Math.round(testTypeMaxMarks*10)/10+'</td>';

                        }
                        else {
                            testTypeCategoryIdTests = j[testTypeCategoryId].length;
                            for (m = 0; m < testTypeCategoryIdTests; m++) {
                                testCode = testTypeAbbr+''+j[testTypeCategoryId][m]['testIndex'];
                                tableData += '<td align="center" class=" searchhead_text" rowspan="1">'+testCode+'&nbsp;</td>';
                            }
                            tableData += '<td align="center" class=" searchhead_text" rowspan="2" colspan = "1">M.M.<br> '+Math.round(testTypeMaxMarks*10)/10+'</td>';
                        }
                    }

                    tableData += '<td align="center" class=" searchhead_text" rowspan="2" colspan = "1"></td>';  
                    if (showGraceMarksValue == true) {    
                      tableData += '<td align="center" class=" searchhead_text" rowspan="2"  colspan = "1">Internal</td>';
                      tableData += '<td align="center" class=" searchhead_text" rowspan="2"  colspan = "1">External</td>';
                      tableData += '<td align="center" class=" searchhead_text" rowspan="2"  colspan = "1">Total</td>';
                    }
                    
                    tableData += '<td align="center" class=" searchhead_text" rowspan="2" colspan = "1">'+totalTestTypeMaxMarks+'</td>';
                    tableData += '</tr>';
                    tableData += '<tr class="rowheading">';
                    for(i=0;i<totalTests;i++) {
                        testTypeId = j['testTypes'][i]['testTypeId'];
                        testTypeCategoryId = j['testTypes'][i]['testTypeCategoryId'];
                        isAttendanceCategory = j['testTypes'][i]['isAttendanceCategory'];
                        testTypeName = j['testTypes'][i]['testTypeName'];
                        testTypeMaxMarks = parseInt(j['testTypes'][i]['maxMarks']);
                        testTypeAbbr = j['testTypes'][i]['testTypeAbbr'];
                        indiTestMaxMarks = j['testTypes'][i]['testMaxMarks'];
                        if (isAttendanceCategory == 1 || isAttendanceCategory == "1") {
                            continue;

                        }
                        else {
                            testTypeCategoryIdTests = j[testTypeCategoryId].length;
                            for (m = 0; m < testTypeCategoryIdTests; m++) {
                                testCode = testTypeAbbr+''+j[testTypeCategoryId][m]['testIndex'];

                                //testMaxMarks = j[testTypeCategoryId][m]['maxMarks'];

                                tableData += '<td align="center" class=" searchhead_text">'+Math.round(indiTestMaxMarks*10)/10+'&nbsp;</td>';
                            }
                        }
                    }
                    tableData += '</tr>';


                    var resultDataLength = j['resultData'].length;

                    for(x = 0; x < resultDataLength; x++) {
                        studentTotalMarks = 0;
                        var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                        tableData += '<tr '+bg+'>';
                        tableData += '<td >'+j['resultData'][x]['srNo']+'&nbsp;</td>';
                        if (j['resultData'][x]['universityRollNo'] == "undefined" || typeof j['resultData'][x]['universityRollNo'] == "undefined") {
                            j['resultData'][x]['universityRollNo'] = '---';
                        }
                        if (showUnivRollNo == '1') {
                            tableData += '<td align="center">'+j['resultData'][x]['universityRollNo']+'&nbsp;</td>';
                        }

                        //tableData += '<td >'+j['resultData'][x]['rollNo']+'&nbsp;</td>';
                        /*if (j['resultData'][x]['universityRollNo'] == "undefined" || typeof j['resultData'][x]['universityRollNo'] == "undefined") {
                            j['resultData'][x]['universityRollNo'] = '---';
                        }
                        if (showUnivRollNo == '1') {
                            tableData += '<td align="center">'+j['resultData'][x]['universityRollNo']+'&nbsp;</td>';
                        }*/
                        
                        tableData += '<td >'+j['resultData'][x]['rollNo']+'&nbsp;</td>';
                        tableData += '<td >'+j['resultData'][x]['studentName']+'&nbsp;</td>';
                        
                        graceMarks = j['resultData'][x]['ms_grace'];
                        graceMarksInt = j['resultData'][x]['ms_int_grace'];   
                        graceMarksExt = j['resultData'][x]['ms_ext_grace'];   
                        graceMarksTot = j['resultData'][x]['ms_tot_grace'];   
                        
                        externalMaxMarks = j['resultData'][x]['ext_maxMarks'];   
                        externalMarks = j['resultData'][x]['ext_scored'];   
                        
                        
                        studentFinalTotalMarks = j['resultData'][x]['ms_total'];

                        for(i=0;i<totalTests;i++) {
                            testTypeId = j['testTypes'][i]['testTypeId'];
                            testTypeCategoryId = j['testTypes'][i]['testTypeCategoryId'];
                            isAttendanceCategory = j['testTypes'][i]['isAttendanceCategory'];
                            testTypeName = j['testTypes'][i]['testTypeName'];
                            testTypeMaxMarks = j['testTypes'][i]['maxMarks'];
                            testTypeAbbr = j['testTypes'][i]['testTypeAbbr'];
                            if (isAttendanceCategory == 1 || isAttendanceCategory == "1") {
                                lectureDelivered = j['resultData'][x]['lectureDelivered'];
                                if (lectureDelivered == null || lectureDelivered == "null") {
                                    lectureDelivered = '---';
                                }
                                lectureAttended = j['resultData'][x]['lectureAttended'];
                                if (lectureAttended == null || lectureAttended == "null") {
                                    lectureAttended = '---';
                                }
                                totalAttended = j['resultData'][x]['totalAttended'];
                                if (totalAttended == null || totalAttended == "null") {
                                    totalAttended = '---';
                                }
                                dutyLeave = j['resultData'][x]['dutyLeave'];
                                align = "right";
                                if (dutyLeave == null || dutyLeave == "null" || dutyLeave == "0.00") {
                                    dutyLeave = '---';
                                    align = "center";
                                }
                                tableData += '<td align="right"  colspan = "1">'+lectureDelivered+'</td>';
                                tableData += '<td align="right"   colspan = "1">'+lectureAttended+'</td>';
                                tableData += '<td align="'+align+'"   colspan = "1">'+dutyLeave+'</td>';
                                tableData += '<td align="right"   colspan = "1">'+totalAttended+'</td>';
                                percentAttended = 0;
                                if (j['resultData'][x]['lectureDelivered'] != null && j['resultData'][x]['lectureDelivered'] != "null") {
                                    percentAttended = Math.ceil(j['resultData'][x]['totalAttended'] * 100 / j['resultData'][x]['lectureDelivered']);
                                }
                                tableData += '<td align="right"   colspan = "1">'+percentAttended+'</td>';
                                ms_attendance = Math.round(j['resultData'][x]['ms_attendance']*10)/10;
                                if (ms_attendance == null || ms_attendance == "null") {
                                    ms_attendance = '---';
                                }
                                else {
                                    studentTotalMarks += ms_attendance;
                                }
                                tableData += '<td align="right"  colspan = "1"><B>'+ms_attendance+'</B></td>';
                            }
                            else {
                                testTypeCategoryIdTests = j[testTypeCategoryId].length;
                                for (m = 0; m < testTypeCategoryIdTests; m++) {
                                    testMaxMarks = j[testTypeCategoryId][m]['maxMarks'];
                                    testIndex = j[testTypeCategoryId][m]['testIndex'];
                                    testName = 'ms_'+testTypeCategoryId+'_'+testIndex;
                                    testMarksName = 'ms_'+testTypeCategoryId;
                                    studentMarks = j['resultData'][x][testName];
                                    //studentMarks = j['resultData'][x][testName];
                                    align = "right";
                                    studentTestMaxMarks = '';
                                    if (studentMarks == null || studentMarks == "null" || studentMarks == "N/A") {
                                        studentMarks = "---";
                                        align = "center";
                                    }
                                    else if (studentMarks == "A") {
                                        align = "center";
                                        studentMarks = "<u>"+studentMarks+"</u>";
                                    }
                                    else {
                                        studentMarks = Math.round(studentMarks*10)/10;
                                    }
                                    tableData += '<td align="'+align+'" nowrap>'+studentMarks+'</td>';
                                }
                                studentMarks = Math.round(j['resultData'][x][testMarksName]*10)/10;
                                tableData += '<td align="right"><B>'+studentMarks+'</B>&nbsp;</td>';
                                studentTotalMarks += parseFloat(studentMarks);
                            }
                        }
                        studentTotalMarks = Math.round(studentTotalMarks*10)/10;
                        
                        tableData += '<td align="right">'+externalMarks+'&nbsp;</td>';
                        if(externalMarks!='<?php echo NOT_APPLICABLE_STRING ?>') {
                          studentFinalTotalMarks = parseFloat(studentFinalTotalMarks) + parseFloat(externalMarks);    
                        }
                        
                        
                        if (graceMarks == null || graceMarks == "null") {
                            graceMarks = 0;
                        }
                        
                        
                        //if (showGraceMarksValue == 'yes') {
                        if (showGraceMarksValue == true) {
                            //tableData += '<td align="right">'+graceMarks+'&nbsp;</td>';
                            tableData += '<td align="right">'+graceMarksInt+'&nbsp;</td>';
                            tableData += '<td align="right">'+graceMarksExt+'&nbsp;</td>';
                            tableData += '<td align="right">'+graceMarksTot+'&nbsp;</td>';
                            grandTotal = parseFloat(studentFinalTotalMarks) + parseFloat(graceMarks);
                        }
                        else {
                            grandTotal = studentFinalTotalMarks;
                        }

                        tableData += '<td align="right"><B>'+studentTotalMarks+'</B>&nbsp;</td>';
                        
                        tableData += '<td align="right"><B>'+grandTotal+'</B>&nbsp;</td>';
                        tableData += '</tr>';
                    }
                }
                tableData += "</table>";


                document.getElementById("resultsDiv").innerHTML = tableData;


                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("courseAverage").innerHTML = j['average'];
                document.getElementById("teachers").innerHTML = j['teachers'];
                document.getElementById("subjectName").innerHTML = j['subjectName'];

                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (allTests > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                }

           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

}

function getLabelClass(){

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetLabelMarksTransferredClass.php';
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
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
                }
                // now select the value
                document.testWiseMarksReportForm.degree.value = j[0].classId;
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
    var showGrace=form.showGraceMarks[1].checked==true?'yes':'no';
    var ordering = form.ordering[0].checked==true?'asc':'desc';
     var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
     var showMarks = form.showMarks[0].checked==true?'0':'1';
      var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initFinalInternalReport.php';

    path='<?php echo UI_HTTP_PATH;?>/finalInternalReportPrint.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sorting='+form.sorting.value+'&ordering='+ordering+'&grace='+showGrace+'&showMarks='+showMarks+'&showUnivRollNo='+showUnivRollNo;
    window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    form = document.testWiseMarksReportForm;
    var showGrace=form.showGraceMarks[1].checked==true?'yes':'no';
    var ordering = form.ordering[0].checked==true?'asc':'desc';
    var showGraceMarks = form.showGraceMarks[0].checked==true?'0':'1';
    var showMarks = form.showMarks[0].checked==true?'0':'1';
    var showUnivRollNo = form.showUnivRollNo[0].checked==true?'0':'1';
    x = Math.random() * Math.random();
    window.location = 'finalInternalReportPrintCSV.php?degree='+form.degree.value+'&subjectId='+form.subjectId.value+'&groupId='+form.groupId.value+'&sorting='+form.sorting.value+'&ordering='+ordering+'&grace='+showGrace+'&showMarks='+showMarks+'&showUnivRollNo='+showUnivRollNo;
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function getClassSubjects() {
    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjects.php';
    var timeTable = form.timeTable.value;
    var pars = 'timeTable='+timeTable+'&degree='+degree;
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
                document.testWiseMarksReportForm.subjectId.value = j[0].subjectId;
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
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetSubjectTestGroups.php';
    var pars = 'degree='+degree+'&subjectId='+subjectId;
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
        getLabelClass();
        getClassSubjects();
        getGroups();

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

<?php

////$History: finalInternalReport.php $
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 4/01/10    Time: 13:16
//Updated in $/LeapCC/Interface
//Made UI Changes
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 7/12/09    Time: 15:12
//Updated in $/LeapCC/Interface
//Corrected javascript  code for grand total
//
//*****************  Version 15  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Interface
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 11/23/09   Time: 10:45a
//Updated in $/LeapCC/Interface
//done changes for final internal report
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 11/12/09   Time: 11:14a
//Updated in $/LeapCC/Interface
//done changes to fix following bug no.s:
//0001987
//0001986
//0001985
//0001984
//0001983
//0001777
//
//*****************  Version 12  *****************
//User: Ajinder      Date: 10/28/09   Time: 1:49p
//Updated in $/LeapCC/Interface
//done changes for making on/off for grace marks.
//
//*****************  Version 11  *****************
//User: Gurkeerat    Date: 10/08/09   Time: 5:05p
//Updated in $/LeapCC/Interface
//resolved issue 0001717
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 10/06/09   Time: 1:30p
//Updated in $/LeapCC/Interface
//added window.onload=function()
//
//*****************  Version 9  *****************
//User: Parveen      Date: 10/03/09   Time: 4:09p
//Updated in $/LeapCC/Interface
//It checks the value of hasAttendance, hasMarks field for every subject
//
//*****************  Version 8  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Interface
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/17/09    Time: 4:20p
//Updated in $/LeapCC/Interface
//applied access rights for admin only.
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/07/09    Time: 6:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 6/01/09    Time: 6:43p
//Updated in $/LeapCC/Interface
//corrected from class1 to degree as part of checking/fixing all reports.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 5/21/09    Time: 7:01p
//Updated in $/LeapCC/Interface
//added code for sorting and ordering.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 5/16/09    Time: 10:42a
//Updated in $/LeapCC/Interface
//fixed the issue of showing attendance percentage.
//changed round() function to ceil()
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/12/09    Time: 6:41p
//Updated in $/LeapCC/Interface
//code updated to make final internal marks report teacher compatible.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/07/09    Time: 8:12p
//Created in $/LeapCC/Interface
//file added for final internal report.
//




?>