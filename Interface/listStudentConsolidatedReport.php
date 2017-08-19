<?php
//-------------------------------------------------------
//  This File contains consolidated report for the student.
//
// Author :Rajeev Aggarwal
// Created on : 22-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentConsolidatedReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Consolidated Report </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
valShow=1;

function isDuplicate(arr){
    var dupArray=arr.split(',');
    var len=dupArray.length;
    for(var i=0;i<len;i++){
        for(var j=i+1;j<len;j++){
            if(dupArray[i]==dupArray[j]){
                messageBox("Duplicate range is not allowed");
                return false;
            }
        }
    }
    return true;
}
function changeParameter(valueSelected){

    if(valueSelected==1){

        document.testWiseMarksReportForm.marksFor.disabled=true;
        document.testWiseMarksReportForm.average.disabled=false;
        document.testWiseMarksReportForm.percentage.disabled=false;
        document.testWiseMarksReportForm.percentage.value='';
        document.testWiseMarksReportForm.average.length = null;
        addOption(document.testWiseMarksReportForm.average, '', 'Select');
        addOption(document.testWiseMarksReportForm.average, '3', 'Above Attendance');
        addOption(document.testWiseMarksReportForm.average, '4', 'Below Attendance');
    }
    else if(valueSelected==2){

        document.testWiseMarksReportForm.marksFor.disabled=false;
        document.testWiseMarksReportForm.average.disabled=false;
        document.testWiseMarksReportForm.percentage.disabled=false;
        document.testWiseMarksReportForm.percentage.value='';
        document.testWiseMarksReportForm.average.length = null;
        addOption(document.testWiseMarksReportForm.average, '', 'Select');
        addOption(document.testWiseMarksReportForm.average, '1', 'Above Marks');
        addOption(document.testWiseMarksReportForm.average, '2', 'Below Marks');
    }
    else{

        document.testWiseMarksReportForm.average.value='';
        document.testWiseMarksReportForm.percentage.value='';
        document.testWiseMarksReportForm.average.disabled=true;
        document.testWiseMarksReportForm.percentage.disabled=true;
        document.testWiseMarksReportForm.marksFor.disabled=false;

        document.testWiseMarksReportForm.average.length = null;
        addOption(document.testWiseMarksReportForm.average, '', 'Select');
        addOption(document.testWiseMarksReportForm.average, '1', 'Above Marks');
        addOption(document.testWiseMarksReportForm.average, '2', 'Below Marks');
        addOption(document.testWiseMarksReportForm.average, '3', 'Above Attendance');
        addOption(document.testWiseMarksReportForm.average, '4', 'Below Attendance');
    }
}
function validateAddForm(frm) {

    var fieldsArray = new Array(new Array("degree","<?php echo SELECT_DEGREE;?>"));
    var testRange=trim(document.testWiseMarksReportForm.range.value);
    var tR=testRange.split(',');
    var len1=tR.length;
    var dupString='';
    var marks='' ;
    for(var i=0;i<len1;i++){
        var tRange=tR[i].split('-');
        marks = marks + tRange;
        marks +=',';
        var len2=tRange.length;
        if(len2!=2){
           messageBox("<?php echo INVALID_MARKS_RANGE;?>");
           document.testWiseMarksReportForm.range.focus();
           return false;
        }
        if(dupString!=''){
          dupString +=',';
        }
        dupString +=parseFloat(trim(tRange[0]),2)+""+parseFloat(trim(tRange[1]),2);
    }
    
    var marksArray = marks.split(',');
    var markslen = marksArray.length - 1;
    for(k=0;k<markslen;k++){
        for(l=k+1;l<markslen;l++){
            if(parseFloat(marksArray[k]) > parseFloat(marksArray[l])){
                messageBox("<?php echo RANGE_SHOULD_BE_IN_ASSENDING_ORDER; ?>")
                document.testWiseMarksReportForm.range.focus();
                return false;
            }
        }
    }
    if(!isDuplicate(dupString)){
        document.testWiseMarksReportForm.range.focus();
        return false;
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

    frm = document.testWiseMarksReportForm;


    if (frm.percentage.value == '' && frm.average.value != '') {
        messageBox("Please enter percentage value");
        frm.percentage.focus();
        return false;
    }
    else if (frm.percentage.value != '' && frm.average.value == '') {
        messageBox("Please select criteria");
        frm.average.focus();
        return false;
    }

    if(document.testWiseMarksReportForm.average.value){

        if(document.testWiseMarksReportForm.percentage.value==''){

            messageBox("Please enter percentage value");
            document.testWiseMarksReportForm.percentage.focus();
            return false;
        }
        else{
                if(document.testWiseMarksReportForm.percentage.value>100){

                messageBox("Percentage value cannot be greater than 100");
                document.testWiseMarksReportForm.percentage.focus();
                return false;
            }

        }
    }
    document.getElementById("nameRow2").style.display='';
    showReport();
}

function showReport() {

    document.getElementById('resultRow').style.display='';
    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;
    var degree      = form.degree.value;
    var subjectTypeId = form.subjectTypeId.value;
    var subjectId = form.subjectId.value;
    var groupId   = form.groupId.value;
    var reportFor = form.reportFor.value;
    var marksFor  = form.marksFor.value;
    var average   = form.average.value;
    var sortField1= form.sortField1.value;
    sortOrderBy1='ASC';
    if(document.testWiseMarksReportForm.sortOrderBy1[1].checked==true) {
      sortOrderBy1='DESC';
    }
    sortOrderBy = sortOrderBy1;

    showGraceMarks=0;
    if(document.testWiseMarksReportForm.showGraceMarks[1].checked==true) {
      showGraceMarks=1;
    }

    showGrades=0;
    if(document.testWiseMarksReportForm.showGrades[1].checked==true) {
      showGrades=1;
    }


    var percentage= form.percentage.value;
    var name = document.getElementById('reportFor');
    document.getElementById('showSubjectEmployeeList11').style.display='none';


    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjectType.php';
    var pars = 'timeTable='+timeTable+'&degree='+degree+'&subjectTypeId='+subjectTypeId+'&subjectId='+subjectId+'&groupId='+groupId+'&reportFor='+reportFor+'&marksFor='+marksFor+'&average='+average+'&percentage='+percentage+'&reportForName='+name.options[name.selectedIndex].text+'&sortField='+sortField1+'&sortOrderBy='+sortOrderBy+'&showGraceMarks='+showGraceMarks+'&showGrades='+showGrades;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initConsolidatedReport.php';
    new Ajax.Request(url,
       {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
         var ret=trim(transport.responseText).split('!~~!~~!');
         //if(trim(ret[0])){
           hideWaitDialog(true);
           //document.getElementById('results').innerHTML=trim(ret[0]);
           document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"  ;
           document.getElementById('showSubjectEmployeeList').style.display='';
           if(trim(ret[0]) && (typeof trim(ret[0]) != "undefined")) {
             document.getElementById("subjectTeacherInfo").innerHTML=ret[1];
           }
           else {
              document.getElementById("subjectTeacherInfo").innerHTML="Teacher Details not found";
           }
           if(trim(ret[0])) {
              document.getElementById('results').innerHTML=trim(ret[0]);
            }
            else {
                var tableData = "<table width='100%' border='0' cellspacing='2' cellpadding='0'>";
                tableData += "<tr class='rowheading'><td width='2%' valign='middle' class='dataFont'><b>&nbsp;#</b>";
                tableData += "<td width='12%' valign='middle' class='dataFont'><b>&nbsp;Univ. Roll No.</b>";
                tableData += "<td valign='middle' align='left' width='10%' class='dataFont' ><b>Roll No.</b></td>";
                tableData += "<td valign='middle' align='left' width='15%'  class='dataFont'><b>Student Name</b></td>";
                tableData += "</tr><tr>";
                tableData += "<td valign='middle' align='left' width='15%' colspan='4' class='dataFont'><center>No Details Found</center></td>";
                tableData += "</tr></table>";
                document.getElementById('results').innerHTML=tableData;
            }
            hideWaitDialog(true);
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
   showDetail=1;
   valShow=1;
}

function resetStudyPeriod() {
    document.testWiseMarksReportForm.subjectTypeId.selectedIndex = 0;
}

function printReport() {

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;
    var degree      = form.degree.value;
    var subjectTypeId = form.subjectTypeId.value;
    var subjectId = form.subjectId.value;
    var groupId   = form.groupId.value;
    var reportFor = form.reportFor.value;
    var marksFor  = form.marksFor.value;
    var average   = form.average.value;
    var percentage= form.percentage.value;
    var range = form.range.value;
    var name = document.getElementById('reportFor');
    var name1 = document.getElementById('degree');
    var name2 = document.getElementById('subjectTypeId');

    var name3 = document.getElementById('subjectId');
    var name4 = document.getElementById('groupId');
    var name5 = document.getElementById('marksFor');

    var sortField1= form.sortField1.value;
    sortOrderBy1='ASC';
    if(document.testWiseMarksReportForm.sortOrderBy1[1].checked==true) {
      sortOrderBy1='DESC';
    }
    sortOrderBy = sortOrderBy1;

    showGraceMarks=0;
    if(document.testWiseMarksReportForm.showGraceMarks[1].checked==true) {
      showGraceMarks=1;
    }

    showGrades=0;
    if(document.testWiseMarksReportForm.showGrades[1].checked==true) {
      showGrades=1;
    }

    var pars = 'timeTable='+timeTable+'&degree='+degree+'&subjectTypeId='+subjectTypeId+'&subjectId='+subjectId+'&groupId='+groupId+'&reportFor='+reportFor+'&marksFor='+marksFor+'&average='+average+'&percentage='+percentage+'&reportForName='+name.options[name.selectedIndex].text+'&degreeName='+name1.options[name1.selectedIndex].text+'&typeName='+name2.options[name2.selectedIndex].text+'&subjectName='+name3.options[name3.selectedIndex].text+'&groupName='+name4.options[name4.selectedIndex].text+'&markName='+name5.options[name5.selectedIndex].text+'&sortField='+sortField1+'&sortOrderBy='+sortOrderBy+'&showGraceMarks='+showGraceMarks+'&showGrades='+showGrades+'&range='+range;

    path='<?php echo UI_HTTP_PATH;?>/studentConsolidatedReportPrint.php?'+pars;
    window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=800,left=100,top=100");
}

function printReportCSV() {

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;
    var degree      = form.degree.value;
    var subjectTypeId = form.subjectTypeId.value;
    var subjectId = form.subjectId.value;
    var groupId   = form.groupId.value;
    var reportFor = form.reportFor.value;
    var marksFor  = form.marksFor.value;
    var average   = form.average.value;
    var percentage= form.percentage.value;
    var range = form.range.value;
    var name = document.getElementById('reportFor');
    var name1 = document.getElementById('degree');
    var name2 = document.getElementById('subjectTypeId');

    var name3 = document.getElementById('subjectId');
    var name4 = document.getElementById('groupId');
    var name5 = document.getElementById('marksFor');

    var sortField1= form.sortField1.value;
    sortOrderBy1='ASC';
    if(document.testWiseMarksReportForm.sortOrderBy1[1].checked==true) {
      sortOrderBy1='DESC';
    }
    sortOrderBy = sortOrderBy1;

    showGraceMarks=0;
    if(document.testWiseMarksReportForm.showGraceMarks[1].checked==true) {
      showGraceMarks=1;
    }

    showGrades=0;
    if(document.testWiseMarksReportForm.showGrades[1].checked==true) {
      showGrades=1;
    }

    var pars = 'timeTable='+timeTable+'&degree='+degree+'&subjectTypeId='+subjectTypeId+'&subjectId='+subjectId+'&groupId='+groupId+'&reportFor='+reportFor+'&marksFor='+marksFor+'&average='+average+'&percentage='+percentage+'&reportForName='+name.options[name.selectedIndex].text+'&degreeName='+name1.options[name1.selectedIndex].text+'&typeName='+name2.options[name2.selectedIndex].text+'&subjectName='+name3.options[name3.selectedIndex].text+'&groupName='+name4.options[name4.selectedIndex].text+'&markName='+name5.options[name5.selectedIndex].text+'&sortField='+sortField1+'&sortOrderBy='+sortOrderBy+'&showGraceMarks='+showGraceMarks+'&showGrades='+showGrades+'&range='+range;

    path='<?php echo UI_HTTP_PATH;?>/studentConsolidatedReportCSV.php?'+pars;
    window.location = path;
    //window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=800,left=100,top=100");
}

function hideResults() {
    document.getElementById('resultRow').style.display='none';
    document.getElementById('showSubjectEmployeeList').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    showDetail = 0;
}

function getLabelClass(){

    form = document.testWiseMarksReportForm;
    var timeTable = form.timeTable.value;

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
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.degree, j[i].classId, j[i].className);
                }
                // now select the value
                //document.testWiseMarksReportForm.groupId.value = j[0].groupId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}
function getClassSubjects() {

    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;

    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassGetSubjectType.php';
    var pars = 'class1='+degree;
    if (degree == '') {
        document.testWiseMarksReportForm.subjectId.length = null;
        document.testWiseMarksReportForm.groupId.length = null;
        addOption(document.testWiseMarksReportForm.subjectId, '', 'ALL');
        addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
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
                addOption(document.testWiseMarksReportForm.subjectId, '', 'ALL');
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.subjectId, j.subjectArr[i].subjectId, j.subjectArr[i].subjectCode);
                }

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

           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function getTypeGroups() {

    form = document.testWiseMarksReportForm;
    var degree = form.degree.value;
    var subjectTypeId = form.subjectTypeId.value;
    if (degree == '') {
        return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetSubjectTypeClass.php';
    var pars = 'class1='+degree+'&subjectTypeId='+subjectTypeId;

    document.testWiseMarksReportForm.groupId.length = null;
    addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
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
                //alert(len);
                document.testWiseMarksReportForm.subjectId.length = null;
                addOption(document.testWiseMarksReportForm.subjectId, '', 'ALL');
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
                }
                // now select the value
                //document.testWiseMarksReportForm.groupId.value = j[0].groupId;
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
    var pars = 'class1='+degree+'&subjectId='+subjectId;
    document.testWiseMarksReportForm.groupId.length = null;
    addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
    if (degree == '') {
        document.testWiseMarksReportForm.groupId.length = null;
        addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
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
                addOption(document.testWiseMarksReportForm.groupId, '', 'ALL');
                if (len > 0) {
                    //addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) {
                    addOption(document.testWiseMarksReportForm.groupId, j[i].groupId, j[i].groupName);
                }
                // now select the value
                //document.testWiseMarksReportForm.groupId.value = j[0].groupId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });

       hideResults();
}

function getShowDetail() {

   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;
   }
}

function getTimeTableClasses() {
    form = document.testWiseMarksReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
    var pars = 'labelId='+form.timeTable.value;

    if (form.timeTable.value=='') {
        form.degree.length = null;
        addOption(form.degree, '', 'Select');
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
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            form.degree.length = null;
            for(i=0;i<len;i++) {
                addOption(form.degree, j[i].classId, j[i].className);
            }
            // now select the value
            form.degree.value = j[0].classId;
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });

}
window.onload=function(){
   getLabelClass();
}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listStudentConsolidatedReportContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>