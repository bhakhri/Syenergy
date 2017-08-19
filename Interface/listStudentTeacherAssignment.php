<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Teacher Assignment
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentTeacherAssignment');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Teacher Assignment Detail Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
global $sessionHandler;  
$roleId = $sessionHandler->getSessionVariable('RoleId'); 
?> 
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxExtraClassAttendanceList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddExtraClass';   
editFormName   = 'EditExtraClass';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteExtraClass';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'assignedOn';
sortOrderBy    = 'ASC';
queryString1=''; 

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      


function hideResults() {
    queryString1 ="";
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function refreshInboxData(){

  var url = '<?php echo HTTP_LIB_PATH;?>/StudentTeacherAssignment/ajaxInitTeacherAssignmentList.php';
  
  queryString1 = generateQueryString('listForm');   
  
  var tableColumns = new Array(new Array('srNo','#','width="2%" align="left"',false),
                               new Array('assignedOn','Assigned','width="10%" align="center"',true),
                               new Array('employeeName','Teacher','width="10%" align="left"',true),
                               new Array('topicTitle','Topic','width="15%" align="left"',true),
                               new Array('topicDescription','Description','width="25%" align="left"',true),
                               new Array('tobeSubmittedOn','Due Date','width="10%" align="center"',true),
                               new Array('addedOn','Added','width="8%" align="center"',true),
                               new Array('totalAssignment','Total','width="8%" align="right"',true),
                               new Array('isVisible','Visible','width="9%" align="center"',true),
                               new Array('action1','Action','width="4%" align="center"',false));

  //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
  listObj = new initPage(url,recordsPerPage,linksPerPage,1,'',sortField,sortOrderBy,divResultName,'','',true,'listObj',tableColumns,'','','&'+queryString1);
  
  document.getElementById("resultRow").style.display='';
  document.getElementById('nameRow').style.display='';
  document.getElementById('nameRow2').style.display='';
  //sendRequest(url, listObj,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true );
  sendRequest(url, listObj, '',true );
  return false;
}


function validateShowList() {
  
   hideResults();     
     
   if(document.listForm.timeTableLabelId.value=='') {
     messageBox ("Please Select Time Table"); 
     document.listForm.timeTableLabelId.focus();
     return false;   
   }
   
   if(document.getElementById('searchDateFilter').value!='') {
     if(document.getElementById('searchFromDate').value=='' || document.getElementById('searchToDate').value=='') {
        if(document.getElementById('searchFromDate').value=='') {
           messageBox ("Please Select From Date"); 
           document.getElementById('searchFromDate').focus(); 
           return false;
        }  
        if(document.getElementById('searchToDate').value=='') {
           messageBox ("Please Select To Date"); 
           document.getElementById('searchToDate').focus();
           return false; 
        }  
      }  
        if(!dateDifference(document.getElementById('searchFromDate').value,document.getElementById('searchToDate').value,'-') ) {
           messageBox ("From Date cannot be greater than To Date"); 
           document.getElementById('searchFromDate').focus();  
           return false;
        } 
   }
   page=1;  
   
   refreshInboxData();
   
   return false;
}


function getDateClear(str) {
    
   if(str=='') {
      document.getElementById('searchFromDate').value = '';  
      document.getElementById('searchToDate').value = ''; 
      document.getElementById('lblDt').style.display='none';
   } 
   else {
      document.getElementById('searchFromDate').value = '';  
      document.getElementById('searchToDate').value = '';   
      document.getElementById('lblDt').style.display='';
   }      
}

function getDateLeftClear(str) {
    
   if(str=='1') {
      document.getElementById('searchLeftFromDate').value = '';  
      document.getElementById('searchLeftToDate').value = ''; 
      document.getElementById('lblLeftStaff').style.display='none';
   } 
   else {
      document.getElementById('searchLeftFromDate').value = '';  
      document.getElementById('searchLeftToDate').value = ''; 
      document.getElementById('lblLeftStaff').style.display='';
   }
   
}


// Function Mentioned 
window.onload=function() {
   getSearchValue('all');  
}

function getSearchValue(str) {
   
    var url ='<?php echo HTTP_LIB_PATH;?>/StudentTeacherAssignment/ajaxTimeTableSearchValues.php';     
    
    if(str=='all' || str =='E') {
      document.listForm.employeeId.length = null;
      addOption(document.listForm.employeeId, '', 'Select');  
      
      document.listForm.classId.length = null;   
      addOption(document.listForm.classId, '', 'Select');  
      
      document.listForm.subjectId.length = null;   
      addOption(document.listForm.subjectId, '', 'Select'); 
      
      document.listForm.groupId.length = null;   
      addOption(document.listForm.groupId, '', 'Select');  
    }
    
    if(str=='all' || str =='C') {
      document.listForm.classId.length = null;   
      addOption(document.listForm.classId, '', 'Select');  
      
      document.listForm.subjectId.length = null;   
      addOption(document.listForm.subjectId, '', 'Select'); 
      
      document.listForm.groupId.length = null;   
      addOption(document.listForm.groupId, '', 'Select');  
    }

    if(str=='all' || str =='S') {
      document.listForm.subjectId.length = null;   
      addOption(document.listForm.subjectId, '', 'Select'); 
      
      document.listForm.groupId.length = null;   
      addOption(document.listForm.groupId, '', 'Select');  
    }
    
    if(str=='all' || str =='G') {
      document.listForm.groupId.length = null;   
      addOption(document.listForm.groupId, '', 'Select');  
    }
    
    param = generateQueryString('listForm')+"&valType="+str;   
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,
      parameters: param, 
      onCreate: function() {
          showWaitDialog(true);
      },
      onSuccess: function(transport){
        hideWaitDialog(true);
         
        var ret=trim(transport.responseText).split('~~');
            
        var j0 = eval(ret[0]);
        var j1 = eval(ret[1]);
        var j2= eval(ret[2]);
        var j3 = eval(ret[3]);
        
         
        // add option Select initially
        if(str=='all' || str=='E') { 
          document.listForm.employeeId.length = null;    
          if(j0.length>0) {
            addOption(document.listForm.employeeId,'', 'All');      
          }
          else {
            addOption(document.listForm.employeeId, '', 'Select');        
          }
          for(i=0;i<j0.length;i++) { 
            addOption(document.listForm.employeeId, j0[i].employeeId, j0[i].employeeName);
          }
          str='all';
        }
        
        if(str=='all' || str=='C') { 
          document.listForm.classId.length = null;    
          if(j1.length>0) {
            addOption(document.listForm.classId,'', 'All');      
          }
          else {
            addOption(document.listForm.classId, '', 'Select');        
          }
          for(i=0;i<j1.length;i++) { 
            addOption(document.listForm.classId, j1[i].classId, j1[i].className);
          }
          str='all';
        }
        
        if(str=='all' || str=='S') { 
          document.listForm.subjectId.length = null;    
          if(j2.length>0) {
            addOption(document.listForm.subjectId,'', 'All');      
          }
          else {
            addOption(document.listForm.subjectId, '', 'Select');        
          }
          for(i=0;i<j2.length;i++) { 
            addOption(document.listForm.subjectId, j2[i].subjectId, j2[i].subjectCode);
          }
          str='all';
        }
        
        if(str=='all' || str=='G') { 
          document.listForm.groupId.length = null;    
          if(j3.length>0) {
            addOption(document.listForm.groupId, '', 'All');      
          }
          else {
            addOption(document.listForm.groupId, '', 'Select');        
          }
          for(i=0;i<j3.length;i++) { 
             addOption(document.listForm.groupId, j3[i].groupId, j3[i].groupName);
          }
          str='all';
        }
    },
    onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}

function showWindow(id,dv) {
   document.getElementById('divHeaderId').innerHTML='&nbsp; Display Assignment Status';
   //displayWindow(dv,winLayerWidth,winLayerHeight);
   populateAssignment(id,dv);
}


function populateAssignment(id,dv) {
 var url = '<?php echo HTTP_LIB_PATH;?>/StudentTeacherAssignment/ajaxGetStudentAssignmentValues.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {assignmentId: id},
     onCreate: function() {

         showWaitDialog(true);
     },
     onSuccess: function(transport){

         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {

             hiddenFloatingDiv('TaxHeadActionDiv');
            messageBox("<?php echo TAX_HEAD_NOT_EXIST; ?>");
            getTaxHeadData();
         }

         var j= trim(transport.responseText).evalJSON();
         var tbHeadArray = new Array(
             new Array('srNo','#','width="2%"','',false),
             new Array('studentName','Name','width="35%"','',true),
             new Array('rollNo','Roll No.','width="15%"','',true) ,
             new Array('regNo','Regn. No.','width="15%"','',true) ,
             new Array('universityRollNo','Univ. Roll No.','width="15%"','',true),
             new Array('submitDate','Submitted On','width="15%"','align="center"',true),
             new Array('replyAttachmentFile','Attachment ','width="15%"','align="center"',true)
         );
         printResultsNoSorting('results12', j.info, tbHeadArray);
         document.getElementById('classId1').innerHTML=j.assignmentinfo[0].className
         document.getElementById('subject1').innerHTML=j.assignmentinfo[0].subjectCode;
         document.getElementById('group1').innerHTML=j.assignmentinfo[0].groupShort
         document.getElementById('isVisibleDiv').innerHTML=j.assignmentinfo[0].isVisible;
         document.getElementById("DateAssignedOn").innerHTML = j.assignmentinfo[0].assignedOn;
         document.getElementById("DateDueOn").innerHTML = j.assignmentinfo[0].tobeSubmittedOn;
         document.getElementById("DateAddedOn").innerHTML = j.assignmentinfo[0].addedOn;
         document.getElementById("AssignmentTopic").innerHTML = j.assignmentinfo[0].topicTitle;
         document.getElementById("AssignmentDescription").innerHTML =  j.assignmentinfo[0].topicDescription;
         if(j.assignmentinfo[0].attachmentFile){
             document.getElementById('editLogoPlaceDetail').innerHTML = "<a href='<?php echo IMG_HTTP_PATH?>/TeacherAssignment/"+j.assignmentinfo[0].attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
         }
         else{
             document.getElementById('editLogoPlaceDetail').innerHTML = '--';
         }
         displayWindow(dv,winLayerWidth,winLayerHeight);

     },
    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function  download(str){
 var address="<?php echo IMG_HTTP_PATH;?>/TeacherAssignment/"+str;
 window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

/* function to print TestType report*/
function printReport() {
    
    var qstr = "sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField+"&"+queryString1;
    var path='<?php echo UI_HTTP_PATH;?>/allocateAssignmentReportPrint.php?'+qstr;
    window.open(path,"AllocateAssignmentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printReportCSV() {
     
    var qstr = "sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField+"&"+queryString1;
    window.location='<?php echo UI_HTTP_PATH;?>/allocateAssignmentReportCSV.php?'+qstr;
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentTeacherAssignment/listStudentTeacherAssignmentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
