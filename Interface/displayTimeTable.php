<?php 
//-------------------------------------------------------
// This File contains the time table Reports 
// Author :Parveen Sharma
// Created on : 04-04-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisplayTimeTableReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo SITE_NAME;?>: Display Multi Utility Time Table</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 

//------------------------------------------------------------------------------------------------
// This Function  creates blank TDs
//
// Author : Dipanjan Bhattacharjee
// Created on : 31.07.08
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------   
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>

<script language="javascript">

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET count value from multiselect control
// Author : Parveen Sharma
// Created on : 24-Sep-2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
// elmentName: id of the multiselect control
//----------------------------------------------------------------------------------------------------
 
function getCountSeprated(elmentName)
{
    var countValue=0;
    var c=eval("document.getElementById('"+elmentName+"').length");
    for(i=0;i<c;i++){
       if(eval("document.allDetailsForm."+elmentName+"[i].selected")){
          countValue++; 
       }  
    }  
    return countValue;
}

var queryString ='';
var groupId='';
var roomId='';
var subjectId='';
var employeeId='';

function getTimeTableData() {
     hideDetails();
     
     queryString =''; 
     classId='';
     groupId='';
     roomId='';
     subjectId='';
     employeeId='';
     dayWeeks='';
     
     if(trim(document.getElementById('timeTableLabelId').value)==""){
         messageBox("Please select Time table label");
         document.getElementById('labelId').focus();
         return false;
     }   
     if(trim(document.getElementById('reportResult').value)==""){
         messageBox("Please select report result type");
         document.getElementById('reportResult').focus();
         return false;
     }   

     if(trim(document.getElementById('dayWeeks').value)=="" &&  trim(document.getElementById('classId').value)=="" && trim(document.getElementById('subjectId').value)=="" && trim(document.getElementById('groupId').value)=="" && trim(document.getElementById('roomId').value)=="" && trim(document.getElementById('employeeId').value)==""){
         alert("Please select atleast one option (Class/Subject/Teacher/Room/Groups/Weeks)!");   
         document.getElementById('classId').focus();
         return false;
     }   

     document.getElementById("results").style.display='';
     
     if(document.getElementById('classId').value!='') {
        classId = getCommaSeprated("classId");
     }
     else {
          len= document.getElementById('classId').options.length;
          t=document.getElementById('classId');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(classId=='') 
                 classId = t.options[k].value;
               else
                 classId = classId + ', '+t.options[k].value;
            }
         }
     }
     
     
     if(document.getElementById('dayWeeks').value!='') {
        dayWeeks = getCommaSeprated("dayWeeks");
     }
     else {
          len= document.getElementById('dayWeeks').options.length;
          t=document.getElementById('dayWeeks');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(dayWeeks=='') 
                 dayWeeks = t.options[k].value;
               else
                 dayWeeks = dayWeeks + ', '+t.options[k].value;
            }
         }
     }     

     
     if(document.getElementById('groupId').value!='') {
        /*if(getCountSeprated("groupId")>10) {
           alert("Please select ten section at one time.");   
           document.getElementById('groupId').focus();
           return false;
        } */
        groupId = getCommaSeprated("groupId");
     }
     else {
          len= document.getElementById('groupId').options.length;
          t=document.getElementById('groupId');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(groupId=='') 
                 groupId = t.options[k].value;
               else
                 groupId = groupId + ', '+t.options[k].value;
            }
         }
     }
     
     if(document.getElementById('subjectId').value!='') {
        subjectId = getCommaSeprated("subjectId");
     }
     else {
          len= document.getElementById('subjectId').options.length;
          t=document.getElementById('subjectId');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(subjectId=='') 
                 subjectId = t.options[k].value;
               else
                 subjectId = subjectId + ', '+t.options[k].value;
            }
         }
     }
     
     if(document.getElementById('roomId').value!='') {
        roomId = getCommaSeprated("roomId");
     }
     else {
          len= document.getElementById('roomId').options.length;
          t=document.getElementById('roomId');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(roomId=='') 
                 roomId = t.options[k].value;
               else
                 roomId = roomId + ', '+t.options[k].value;
            }
         }
     }
     
     if(document.getElementById('employeeId').value!='') {
        employeeId = getCommaSeprated("employeeId");
     }
     else {
          len= document.getElementById('employeeId').options.length;
          t=document.getElementById('employeeId');
          if(len>0) {
            for(k=0;k<len;k++) { 
               if(employeeId=='') 
                 employeeId = t.options[k].value;
               else
                 employeeId = employeeId + ', '+t.options[k].value;
            }
         }
     }
     
     queryString  ="timeTableLabelId="+document.allDetailsForm.timeTableLabelId.value;
     queryString +="&reportResult="+document.allDetailsForm.reportResult.value;
     queryString +="&subjectId="+subjectId;
     queryString +="&employeeId="+employeeId;
     queryString +="&roomId="+roomId;
     queryString +="&groupId="+groupId; 
     queryString +="&classId="+classId; 
     queryString +="&dayWeeks="+dayWeeks;
     
     
     timeTable = document.allDetailsForm.timeTableLabelId.value;
     var rval=timeTable.split('~');
     timeTableLabelId = rval[0];
     if(rval.length==4) {
       timeTableType = rval[3];
     }
     else {
       timeTableType = 1;  
     }
     
     if(timeTableType==2) {   
       queryString +="&reportView=1";
     }
     else {
       queryString +="&reportView="+document.allDetailsForm.reportView.value;  
     }
     
     url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxTimeTableDetailsReport.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters:{ timeTableLabelId: timeTableLabelId,
                      reportResult: document.allDetailsForm.reportResult.value,
                      reportView: document.allDetailsForm.reportView.value,
                      subjectId: subjectId,
                      employeeId: employeeId,
                      roomId: roomId,
                      groupId: groupId,
                      classId: classId,
                      dayWeeks: dayWeeks,
                      timeTableType: timeTableType,
                      fromDate: document.allDetailsForm.fromDate.value,
                      toDate: document.allDetailsForm.toDate.value
                    },
         asynchronous: true,
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById("nameRow").style.display='';
             document.getElementById("nameRow2").style.display='';
             document.getElementById("resultRow").style.display='';
             document.getElementById('results').innerHTML=trim(transport.responseText);
          },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
       //changeColor(currentThemeId);
}

function printReport() {

    var form = document.allDetailsForm;
    
    params = generateQueryString('allDetailsForm');
    //params=queryString;
    path='<?php echo UI_HTTP_PATH;?>/detailsTimeTablePrint.php?'+params;
    //path='<?php echo UI_HTTP_PATH;?>/detailsTimeTablePrint.php?employeeId='+employeeId+'&dayWeeks='+dayWeeks+'&classId='+classId+'&roomId='+roomId+'&subjectId='+subjectId+'&groupId='+groupId+'&timeTableLabelId='+form.timeTableLabelId.value+'&reportResult='+form.reportResult.value+'&reportView='+form.reportView.value;
    window.open(path,"TimeTableReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function printReportDoc() {
    var form = document.allDetailsForm;
    
    params = generateQueryString('allDetailsForm');    
    //params=queryString; 
    path='<?php echo UI_HTTP_PATH;?>/detailsTimeTableDocument.php?'+params;
    //path='<?php echo UI_HTTP_PATH;?>/detailsTimeTableDocument.php?employeeId='+employeeId+'&dayWeeks='+dayWeeks+'&classId='+classId+'&roomId='+roomId+'&subjectId='+subjectId+'&groupId='+groupId+'&timeTableLabelId='+form.timeTableLabelId.value+'&reportResult='+form.reportResult.value+'&reportView='+form.reportView.value;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    //window.location='<?php echo HTTP_PATH;?>/Templates/Xml/timeTable.doc';
    window.location=path;
}


function printReportCSV() {
    var form = document.allDetailsForm;
    
     params = generateQueryString('allDetailsForm');   
    //params=queryString; 
    path='<?php echo UI_HTTP_PATH;?>/detailsTimeTableReportCSV.php?'+params;
    //path='<?php echo UI_HTTP_PATH;?>/detailsTimeTableDocument.php?employeeId='+employeeId+'&dayWeeks='+dayWeeks+'&classId='+classId+'&roomId='+roomId+'&subjectId='+subjectId+'&groupId='+groupId+'&timeTableLabelId='+form.timeTableLabelId.value+'&reportResult='+form.reportResult.value+'&reportView='+form.reportView.value;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location=path;
}


function refreshTimeTableData() {
    hideDetails();
    
    setFormat();
    
    timeTable = document.allDetailsForm.timeTableLabelId.value;
    var rval=timeTable.split('~');
    timeTableLabelId = rval[0];

    var url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxGetTimeTableDetails.php';
    
    document.allDetailsForm.classId.length = null;   
    document.allDetailsForm.subjectId.length = null;   
    document.allDetailsForm.employeeId.length = null;   
    document.allDetailsForm.roomId.length = null;   
    document.allDetailsForm.groupId.length = null;   
    
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {timeTableLabelId: timeTableLabelId },  
         asynchronous: true,  
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var ret=trim(transport.responseText).split('~~');
            
            var j0 = eval(ret[0]);
            var j1 = eval(ret[1]);
            var j2= eval(ret[2]);
            var j3 = eval(ret[3]);
            var j4= eval(ret[4]);
            
            // add option Select initially
            for(i=0;i<j0.length;i++) { 
              addOption(document.allDetailsForm.classId, j0[i].classId, j0[i].className);
            }
            
            for(i=0;i<j1.length;i++) { 
              addOption(document.allDetailsForm.subjectId, j1[i].subjectId, j1[i].subjectName);
            }
            
            for(i=0;i<j2.length;i++) { 
               addOption(document.allDetailsForm.employeeId, j2[i].employeeId, j2[i].employeeName);
            }
           
            for(i=0;i<j3.length;i++) { 
               addOption(document.allDetailsForm.roomId, j3[i].roomId, j3[i].roomName);
            }
            
            for(i=0;i<j4.length;i++) { 
               addOption(document.allDetailsForm.groupId, j4[i].groupId, j4[i].groupShort);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function setFormat() {
   
   document.getElementById("timeTableViewId").style.display='';  
   document.getElementById("timeTableTypeId").style.display='none';  
   timeTable = document.allDetailsForm.timeTableLabelId.value;
   if(timeTable=='') {
     return false; 
   }
   
   var rval=timeTable.split('~');
   timeTableLabelId = rval[0];
   if(rval.length==4) {
     timeTableType = rval[3];
   }
   else {
      timeTableType = 1;  
   }
   
 
   if(timeTableType==2) {
      document.getElementById("timeTableViewId").style.display='none';
      document.getElementById("timeTableTypeId").style.display='';   
      if(rval[1]!='0000-00-00' && rval[1]!='') {
        document.getElementById('fromDate').value=rval[1];
      }
      if(rval[2]!='0000-00-00' && rval[2]!='') {
        document.getElementById('toDate').value=rval[2];
      }
   }
}

window.onload=function() {    
  refreshTimeTableData();
}

</script> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/displayTimeTableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
    //History: $

?>