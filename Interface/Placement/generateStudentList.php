<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementGenerateStudentList');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Generate Student List </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                               new Array('srNo','#','width="2%"','',false),
                               new Array('students','<input type=\"checkbox\" id=\"studentListMaster\" name=\"studentListMaster\" onclick=\"selectStudents();\">','width="2%"','align=\"left\"',false),
                               new Array('studentName','Student','width="15%"','',true) , 
                               new Array('dob','DOB','width="5%"','align="center"',true), 
                               new Array('college','College','width="10%"','',true) , 
                               new Array('marks10th','Marks(10th)','width="10%"','align="right"',true) , 
                               new Array('marks12th','Marks(12th)','width="10%"','align="right"',true) , 
                               new Array('marksLastSem','Marks(BE/B.Tech)','width="10%"','align="right"',true),
                               new Array('marksGraduation','Marks(Graduation)','width="10%"','align="right"',true)  
                             );

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Placement/Student/generateStudenetList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

function generateStudentList() {
    if(document.getElementById('placementDriveId').value==''){
        messageBox("<?php echo SELECT_PLACEMENT_DRIVE?>");
        document.getElementById('placementDriveId').focus();
        return false;
    }
    if(document.getElementById('graceMarks').disabled==false){
        if(trim(document.getElementById('graceMarks').value)==''){
            messageBox("<?php ENTER_GRACE_MARKS_FOR_PLACEMENT;?>");
            document.getElementById('graceMarks').focus();
            return false;
        }
        if(!isDecimal(trim(document.getElementById('graceMarks').value))){
            messageBox("<?php ENTER_DECIMAL_VALUE;?>");
            document.getElementById('graceMarks').focus();
            return false;
        }
        
        if(trim(document.getElementById('graceMarks').value)<0){
            messageBox("Negative values are not allowed");
            document.getElementById('graceMarks').focus();
            return false;
        }
    }
    
    page=1; //default page
    sortField = 'studentName';
    sortOrderBy    = 'ASC';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    document.getElementById('saveTRId').style.display='';
}


 function doEligibilityListing(){
    
    if(document.getElementById('placementDriveId').value==''){
        messageBox("<?php echo SELECT_PLACEMENT_DRIVE?>");
        document.getElementById('placementDriveId').focus();
        return false;
    }
    if(document.getElementById('graceMarks').disabled==false){
        if(trim(document.getElementById('graceMarks').value)==''){
            messageBox("<?php ENTER_GRACE_MARKS_FOR_PLACEMENT;?>");
            document.getElementById('graceMarks').focus();
            return false;
        }
        if(!isDecimal(trim(document.getElementById('graceMarks').value))){
            messageBox("<?php ENTER_DECIMAL_VALUE;?>");
            document.getElementById('graceMarks').focus();
            return false;
        }
        
        if(trim(document.getElementById('graceMarks').value)<0){
            messageBox("Negative values are not allowed");
            document.getElementById('graceMarks').focus();
            return false;
        }
    }
   
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   var fl=0;
   var deallocFlag=0;
   var studentString='';
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentList'){
           fl=1;
           if(studentString!=''){
               studentString +=',';
           }
           if(frmObj[i].checked==true){
             deallocFlag=1;  
           }
           studentString +=frmObj[i].value+'_'+(frmObj[i].checked==true?1:0);
       }
   }
   if(fl==0){
       messageBox("<?php echo NO_DATA_SUBMIT; ?>");
       return false;
   }
   
   if(deallocFlag==0){
       if(!confirm("All students will be deallocated from this placement drive.\nAre you sure ?")){
           return false;
       }
   }
    
   var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Student/doEligibility.php';
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: document.getElementById('placementDriveId').value, 
                 studentString : studentString
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText);
                     if(ret=="<?php echo SUCCESS;?>"){
                         messageBox("<?php echo PLACEMENT_DRIVE_LIST_GENERATED; ?>");
                         vanishData();
                         document.getElementById('placementDriveId').value='';
                     }
                     else{
                         messageBox(ret);
                     }
                     
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

function fetchPlacementDriveDetails(value){
   vanishData();
   if(value==''){
       return false;
   } 
   var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxGetValues.php'; 
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: (value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText);
                     if(ret==0){
                         messageBox("<?php echo PLACEMENT_DRIVE_NOT_EXIST; ?>");
                     }
                     else{
                         var j = ret.evalJSON();
                         document.getElementById('summeryDiv').style.display='';
                         if(j.edit[0].eligibilityCriteria==1){
                           document.getElementById('eleTdId').innerHTML='Yes';
                           document.getElementById('eleTdId1').innerHTML=j.criteria[0].cutOffMarks10th+' in 10th , ';
                           document.getElementById('eleTdId2').innerHTML=j.criteria[0].cutOffMarks12th+' in 12th , ';
                           if(j.criteria[0].cutOffMarksLastSem!=''){
                            document.getElementById('eleTdId3').innerHTML=j.criteria[0].cutOffMarksLastSem+' in last sem.';
                           }
                           else{
                            document.getElementById('eleTdId3').innerHTML='';   
                           }
                           if(j.criteria[0].cutOffMarksGraduation!=''){
                            document.getElementById('eleTdId4').innerHTML=j.criteria[0].cutOffMarksGraduation+' in graduation';
                           }
                           else{
                            document.getElementById('eleTdId4').innerHTML='';
                           }
                           document.getElementById('graceMarks').disabled=false;
                           document.getElementById('graceMarks').value=0;
                         }
                         else{
                           document.getElementById('eleTdId').innerHTML='No';
                           document.getElementById('eleTdId1').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
                           document.getElementById('eleTdId2').innerHTML='';
                           document.getElementById('eleTdId3').innerHTML='';
                           document.getElementById('eleTdId4').innerHTML='';
                           document.getElementById('graceMarks').disabled=true;
                           document.getElementById('graceMarks').value='';
                         }
                         
                         if(j.edit[0].isTest==1){
                           document.getElementById('testTdId').innerHTML='Yes';  
                         }
                         else{
                           document.getElementById('testTdId').innerHTML='No';
                         }
                         
                         if(j.edit[0].individualInterview==1){
                           document.getElementById('interviewTdId').innerHTML='Yes';  
                         }
                         else{
                           document.getElementById('interviewTdId').innerHTML='No';
                         }
                          if(j.edit[0].hrInterview==1){
                           document.getElementById('interviewTdId1').innerHTML='Yes';  
                         }
                         else{
                           document.getElementById('interviewTdId1').innerHTML='No';
                         }
                         if(j.edit[0].groupDiscussion==1){
                           document.getElementById('gdTdId').innerHTML='Yes';  
                         }
                         else{
                           document.getElementById('gdTdId').innerHTML='No';
                         }
                     }
             }, 
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           }); 
}


function vanishData(){
    document.getElementById('graceMarks').disabled=true;
    document.getElementById('graceMarks').value='';
    document.getElementById(divResultName).innerHTML='';
    document.getElementById('summeryDiv').style.display='none';
    document.getElementById('eleTdId').innerHTML='';
    document.getElementById('testTdId').innerHTML='';
    document.getElementById('interviewTdId').innerHTML='';
    document.getElementById('gdTdId').innerHTML='';
    document.getElementById('saveTRId').style.display='none';
}


function vanishData2(){
    document.getElementById(divResultName).innerHTML='';
    document.getElementById('saveTRId').style.display='none';
}

function selectStudents(){
   var state=document.getElementById('studentListMaster').checked;
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentList'){
           frmObj[i].checked=state;
       }
   }
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Placement/Student/generateStudentListContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listEvent.php $ 
?>