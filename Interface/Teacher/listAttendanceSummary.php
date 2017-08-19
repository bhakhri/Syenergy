<?php
//used for displaying Attendance summary
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AttendanceSummary');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Display Attendance  Summary </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript"> 
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE; ?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
page=1; //default page
sortField = 'testAbbr';
sortOrderBy    = 'ASC';

searchFormName = 'searchForm'; // name of the form which will be used for search


var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
  if(document.getElementById('timeTableLabelId').value !=""){
          document.getElementById('printDiv1').style.display='none';
          getClassAttendanceList(document.getElementById('classId').value,document.getElementById('subject').value,document.getElementById('group').value);  
          document.getElementById('printDiv1').style.display='block';
       }
   else{
           messageBox("Select time table to get attendance details");
           document.getElementById('timeTableLabelId').focus();
      } 
}

function getClassAttendanceList(classId,subjectId,groupId){
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAttendanceSummery.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('className','Class','width="12%" align="left"',true),
                        new Array('subjectCode','Subject','width="15%" align="left"',true),
                        new Array('groupName','Group','width="6%" align="left"',true),
                        new Array('totalDelivered','Lecture Delivered','width="5%" align="right"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','finalMarksResultDiv','','',true,'listObj1',tableColumns,'','','&classId='+classId+'&subjectId='+subjectId+'&groupId='+groupId+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value);
 sendRequest(url, listObj1, ' ',false);
 document.getElementById('totalDelivered').innerHTML='<b>Total Lecture Delivered : '+j.TD;

}


function autoPopulateClass(timeTableLabelId){
    clearData(1);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetClass.php';
    
    if(timeTableLabelId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: timeTableLabelId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
                    if(j.length>0){
                      var objOption = new Option('All',0);
                      document.searchForm.classId.options.add(objOption);
                      document.searchForm.classId.selectedIndex=1;
                    }
                    for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].className,j[c].classId);
                        document.searchForm.classId.options.add(objOption);
                    }
                    if(j.length>0){
                        populateSubjects(document.searchForm.classId.value);
                    }
                    
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


function populateSubjects(classId){
    clearData(2);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetAllSubjects.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: classId,
                 timeTableLabelId :document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')');
                    
                    if(j.length>0){
                      var objOption = new Option('All',0);
                      document.searchForm.subject.options.add(objOption);
                      document.searchForm.subject.selectedIndex=1;
                    }
                    for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                    }
                    if(j.length>0){
                       populateGroups(document.searchForm.classId.value,document.searchForm.subject.value) 
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups(classId,subjectId) {
   clearData(3); 
   
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAllGroupPopulate.php';
   
   if(classId=="" || subjectId=="" || document.getElementById('timeTableLabelId').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: subjectId,
                 classId  : classId,
                 timeTableLabelId :document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 
 
                     if(j.length>0){
                      var objOption = new Option('All',0);
                      document.searchForm.group.options.add(objOption);
                      document.searchForm.group.selectedIndex=1;
                     }
                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                     }
 
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}





function clearData(mode){
    document.getElementById('finalMarksResultDiv').innerHTML='';
    document.getElementById('totalDelivered').innerHTML='';
    if(mode==1){
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==2){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==3){
        document.getElementById('group').options.length=1;
    }
   else if(mode==4){
       document.getElementById('group').options.length=1;
   }
}


/* function to print attendance summary report*/
function printAttendanceReport() {
    
    if(document.getElementById('timeTableLabelId').value !=""){
     var qstr="&subjectId="+document.getElementById('subject').value;
     qstr=qstr+"&groupId="+document.getElementById('group').value;
     qstr=qstr+"&classId="+document.getElementById('classId').value;
     qstr=qstr+"&timeTableLabelId="+document.getElementById('timeTableLabelId').value;
     qstr=qstr+"&groupName="+document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
     qstr=qstr+"&subjectName="+document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
     qstr=qstr+"&className="+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
     qstr=qstr+"&timeTableName="+document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
     qstr=qstr+"&page="+listObj1.page+"&sortOrderBy="+listObj1.sortOrderBy+"&sortField="+listObj1.sortField;
     path='<?php echo UI_HTTP_PATH;?>/Teacher/attendanceSummeryReportPrint.php?listStudent=1'+qstr;
     window.open(path,"StudentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
   else{
       messageBox("Select time table to get attendance details");
       document.getElementById('timeTableLabelId').focus();
   }  
    
}

/* function to export to csv attendance summary report*/
function csvAttendanceReport() {
    
    if(document.getElementById('timeTableLabelId').value !=""){
     var qstr="&subjectId="+document.getElementById('subject').value;
     qstr=qstr+"&groupId="+document.getElementById('group').value;
     qstr=qstr+"&classId="+document.getElementById('classId').value;
     qstr=qstr+"&timeTableLabelId="+document.getElementById('timeTableLabelId').value;
     qstr=qstr+"&groupName="+document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
     qstr=qstr+"&subjectName="+document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
     qstr=qstr+"&className="+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
     qstr=qstr+"&timeTableName="+document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;
     qstr=qstr+"&page="+listObj1.page+"&sortOrderBy="+listObj1.sortOrderBy+"&sortField="+listObj1.sortField;
     window.location='attendanceSummeryReportCSV.php?queryString='+qstr;
    }
   else{
       messageBox("Select time table to get attendance details");
       document.getElementById('timeTableLabelId').focus();
   }   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
    }
}
  
window.onload=function(){
    document.getElementById('timeTableLabelId').focus();
    document.searchForm.reset();
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listAttendanceSummeryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>