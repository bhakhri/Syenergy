<?php 
//-------------------------------------------------------------------
// This File contains the teacher load time table 
// Author :Parveen Sharma
// Created on : 19-01-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayLoadTeacherTimeTable');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Teacher Load Time Table </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?>

<script language="javascript">

//This function Validates Form 
/*
var listURL='<?php echo HTTP_LIB_PATH;?>/TimeTable/initTeacherLoadTimeTableReport.php'; */
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'teacherLoadTimeTable'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'Name';
sortOrderBy    = 'Asc';


//this function fetches Lecture Data 
function refreshLectureData(){
      
      var url='<?php echo HTTP_LIB_PATH;?>/TimeTable/initTeacherLoadTimeTableReport.php';
      
      tableColumns = new Array(new Array('srNo','#', 'width="2%"  align="left"',false), 
                               new Array('Name','Teacher Name','width="12%" align="left"',true), 
                               new Array('Subjects','Subjects','width="20%" align="left"',true));
                                   
      var lectureGroupType='';
      var cnt = document.teacherLoadTimeTable.lectureGroupType.length;
      
    if(cnt>0) {
         for(var i=0;i<cnt;i++) {
            tableColumns.push(new Array('ss'+document.teacherLoadTimeTable.lectureGroupType.options[i].value,document.teacherLoadTimeTable.lectureGroupType.options[i].text,'width="5%" align="right"',true));
            if(lectureGroupType=='') {
              lectureGroupType = document.teacherLoadTimeTable.lectureGroupType.options[i].value;
            }
            else {
              lectureGroupType = lectureGroupType + ', '+document.teacherLoadTimeTable.lectureGroupType.options[i].value;
            }
         }          
         tableColumns.push(new Array('TotalLoad', 'Total',  'width="5%" align="right"',true)); 
         tableColumns.push(new Array('Details',   'Action','width="5%" align="center"',false)); 
      }         
   
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';

      labelId  = (document.teacherLoadTimeTable.labelId.value);
      branchId = (document.teacherLoadTimeTable.branchId.value);
      teacherId = (document.teacherLoadTimeTable.teacherId.value);
        
      //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
      listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','Name','ASC','resultsDiv','','',true,'listObj4',tableColumns,'','','&labelId='+labelId+'&branchId='+branchId+'&teacherId='+teacherId+'&groupType='+lectureGroupType);
      sendRequest(url, listObj4, '',true)
} 



//This function Validates Form 
function validateAddForm(frm) {
 
     if(trim(document.getElementById('labelId').value)==""){
         messageBox("Please select Time table label");
         document.getElementById('labelId').focus();
         return false;
     }   

    refreshLectureData();
    /*  document.getElementById("nameRow").style.display='';
        document.getElementById("nameRow2").style.display='';
        document.getElementById("resultRow").style.display='';
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    */    
}

function getEmployee() {
    hideResults();
    var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetEmployee.php';
    document.teacherLoadTimeTable.teacherId.length = null;
    addOption(document.teacherLoadTimeTable.teacherId, '-1', 'Select'); 
    
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {branchId: (document.teacherLoadTimeTable.branchId.value)},
             onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            if(len>0) {
              document.teacherLoadTimeTable.teacherId.length = null;
              addOption(document.teacherLoadTimeTable.teacherId, '', 'All');
              for(i=0;i<len;i++) { 
                str = j[i].employeeName+' ('+j[i].employeeCode+')'; 
                addOption(document.teacherLoadTimeTable.teacherId, j[i].employeeId, str);
              }
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}
 

function printReport() {                              
     
    if(trim(document.getElementById('labelId').value)==""){
       messageBox("Please select Time table label");
       document.getElementById('labelId').focus();
       return false;
    }   

    labelId  = (document.teacherLoadTimeTable.labelId.value);
    branchId = (document.teacherLoadTimeTable.branchId.value);
    teacherId = (document.teacherLoadTimeTable.teacherId.value);
      
    
    lectureGroupTypeName='';
    lectureGroupType='';
    var cnt = document.teacherLoadTimeTable.lectureGroupType.length;
    if(cnt>0) {
         for(var i=0;i<cnt;i++){
            if(lectureGroupTypeName=='') {
              lectureGroupType  = document.teacherLoadTimeTable.lectureGroupType.options[i].value;
              lectureGroupTypeName = document.teacherLoadTimeTable.lectureGroupType.options[i].text;
            }
            else {
              lectureGroupType  = lectureGroupType + ','+document.teacherLoadTimeTable.lectureGroupType.options[i].value;
              lectureGroupTypeName = lectureGroupTypeName + ','+document.teacherLoadTimeTable.lectureGroupType.options[i].text;
            }
         }          
    }         
    str = 'teacherId='+teacherId+'&branchId='+branchId+'&labelId='+labelId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
    path='<?php echo UI_HTTP_PATH;?>/displayLoadTeacherTimeTablePrint.php?'+str;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all fee collection to csv*/
function printReportCSV() {

    if(trim(document.getElementById('labelId').value)==""){
       messageBox("Please select Time table label");
       document.getElementById('labelId').focus();
       return false;
    }   

    labelId  = (document.teacherLoadTimeTable.labelId.value);
    branchId = (document.teacherLoadTimeTable.branchId.value);
    teacherId = (document.teacherLoadTimeTable.teacherId.value);
    
    
    lectureGroupTypeName='';
    lectureGroupType='';
    
    var cnt = document.teacherLoadTimeTable.lectureGroupType.length;
    if(cnt>0) {
         for(var i=0;i<cnt;i++){
            if(lectureGroupTypeName=='') {
              lectureGroupType  = document.teacherLoadTimeTable.lectureGroupType.options[i].value;
              lectureGroupTypeName = document.teacherLoadTimeTable.lectureGroupType.options[i].text;
            }
            else {
              lectureGroupType  = lectureGroupType + ','+document.teacherLoadTimeTable.lectureGroupType.options[i].value;
              lectureGroupTypeName = lectureGroupTypeName + ','+document.teacherLoadTimeTable.lectureGroupType.options[i].text;
            }
         }          
    }
             
    str = 'teacherId='+teacherId+'&branchId='+branchId+'&labelId='+labelId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
    path='<?php echo UI_HTTP_PATH;?>/displayLoadTeacherTimeTablePrintCSV.php?'+str;

	window.location=path;
}

function showDetails(id,name,timeTableType,load) {

    path='<?php echo UI_HTTP_PATH;?>/teacherTimeTablePrint.php?load='+load+'&teacherId='+id+'&teacherName='+name+'&labelId='+document.getElementById('labelId').value+'&timeTableType='+timeTableType;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

window.onload=function(){
    getEmployee();
}

</script> 
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/listLoadTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//History: $displayLoadTeacherTimeTable.php
//
//

?>