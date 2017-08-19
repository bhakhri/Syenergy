<?php
// THIS FILE IS USED AS TEMPLATE FOR student Achievements/Offence Details 
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/StudentActivity/ajaxInitData.php");  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Achievements/Offence of Students </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript"> 
  var tableColumns = new Array(new Array('srNo','#','width="2%" valign="middle"',false), 
                               new Array('rollNo','Roll No','width="8%" valign="middle"',true),  
                               new Array('studentName','Name','width="15%" valign="middle"',true),   
                               new Array('className','Class','width="13%" valign="middle"',true),   
                               new Array('groupName','Group','width="8%" valign="middle"',true),   
                               new Array('offenseName','Offense','width="12%" valign="middle"',true),
                               new Array('offenseDate','Date','width="8%" valign="middle" align="center"',true) , 
                               new Array('reportedBy','Reported By','width="15%" valign="middle"',true) , 
                               new Array('remarks','Remarks','width="20%" valign="middle" align="left"',true));

 //This function Validates Form 
 
var listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxStudentOffence.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'searchForm'; // name of the form which will be used for search
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'Asc';

function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMessageValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Parveen Sharma
// Created on : (27.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetOffenseDetails.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {disciplineId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divMessage');
                        messageBox("This Offense/Achievements Record Does Not Exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                          //return false;
                   }
                    j = trim(transport.responseText);
                    document.getElementById('message').innerHTML= j;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
   
   if(trim(document.getElementById('rollNo').value)=='') {
       if(document.getElementById('classId').value==''){
            messageBox("<?php echo SELECT_CLASS; ?>");
            document.getElementById('classId').focus();
            return false;
       }
       
       if(document.getElementById('subject').value==''){
            messageBox("<?php echo SELECT_SUBJECT; ?>");
            document.getElementById('subject').focus();
            return false;
       } 
       
       if(document.getElementById('group').value==''){
            messageBox("<?php echo SELECT_GROUP; ?>");
            document.getElementById('group').focus();
            return false;
       } 
  }
  
  document.getElementById("nameRow").style.display='';
  document.getElementById("nameRow2").style.display='';
  document.getElementById("resultRow").style.display='';
  // url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
  listObj6 = new initPage(listURL,recordsPerPage,linksPerPage,1,'searchForm','offenseName','ASC','resultsDiv','','',true,'listObj6',tableColumns,'','','&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value));
  sendRequest(listURL, listObj6, '')
   return false;  
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function populateSubjects(classId){
    clearData(1);
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups(classId,subjectId) {
   clearData(2); 
   
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   
   if(classId=="" || subjectId==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: subjectId,
                 classId  : classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


function printReport() {                              
    if(document.getElementById('classId').value!='') {
       className=document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    }
    else {
        className= "";
    }
     if(document.getElementById('subject').value!='') {
       subjectName=document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    }
    else {
        subjectName="";
    }
    if(document.getElementById('group').value!='') {
       groupName=document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    }
    else {
        groupName="";
    }
    var rollNo = document.getElementById('rollNo').value;    
    
    str = '&className='+className+'&subjectName='+subjectName +'&groupName='+groupName; 
    path='<?php echo UI_HTTP_PATH;?>/Teacher/listAchievementsOffencePrint.php?classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all fee collection to csv*/
function printReportCSV() {

    if(document.getElementById('classId').value!='') {
       className=document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    }
    else {
        className= "";
    }
     if(document.getElementById('subject').value!='') {
       subjectName=document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    }
    else {
        subjectName="";
    }
    if(document.getElementById('group').value!='') {
       groupName=document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    }
    else {
        groupName="";
    }
    var rollNo = document.getElementById('rollNo').value;    
    
    str = '&className='+className+'&subjectName='+subjectName +'&groupName='+groupName; 
    
    path='<?php echo UI_HTTP_PATH;?>/Teacher/listAchievementsOffencePrintCSV.php?classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&studentRollNo='+trim(document.getElementById('rollNo').value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.location=path;
}

function clearData(mode){
    hideResults();
    if(mode==1){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
   else if(mode==2){
       document.getElementById('group').options.length=1;
   } 
}
  
window.onload=function(){
    document.getElementById('classId').focus();
    document.searchForm.reset();
}

</script>
</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listAchievementsOffenceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>

<?php
// $History: listAchievementsOffence.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/29/09    Time: 4:13p
//Created in $/LeapCC/Interface/Teacher
//inital checkin
//

?>