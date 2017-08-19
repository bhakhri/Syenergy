<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementGenerateStudentResultList');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Generate Student Result List </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                               new Array('srNo','#','width="2%"','',false),
                               new Array('studentName','Student','width="15%"','',true) , 
                               new Array('dob','DOB','width="5%"','align="center"',true), 
                               new Array('college','College','width="10%"','',true) , 
                               new Array('clearTest','<input type=\"checkbox\" id=\"studentTestListMaster\" name=\"studentTestListMaster\" onclick=\"selectStudentTest();\">Cleared Test','width="10%"','align=\"left\"',false),
                               new Array('clearGD','<input type=\"checkbox\" id=\"studentGDListMaster\" name=\"studentGDListMaster\" onclick=\"selectStudentGD();\">Cleared G.D.','width="10%"','align=\"left\"',false),
                               new Array('clearInterview','<input type=\"checkbox\" id=\"studentInterviewListMaster\" name=\"studentInterviewListMaster\" onclick=\"selectStudentInterview();\">Cleared Interview','width="12%"','align=\"left\"',false),
                               new Array('clearSelection','<input type=\"checkbox\" id=\"studentSelectionListMaster\" name=\"studentSelectionListMaster\" onclick=\"selectStudentSelection();\">Selected','width="10%"','align=\"left\"',false)
                             );

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Placement/Student/generateStudenetResultList.php';
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
    
    page=1; //default page
    sortField = 'studentName';
    sortOrderBy    = 'ASC';
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    document.getElementById('saveTRId').style.display='';
    
    document.getElementById('printIcon').style.display='';
    document.getElementById('excelIcon').style.display='';
    dataUpdated=0;
}

function doResultListing(){
    
    if(document.getElementById('placementDriveId').value==''){
        messageBox("<?php echo SELECT_PLACEMENT_DRIVE?>");
        document.getElementById('placementDriveId').focus();
        return false;
    }
   
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   var fl=0;
   var deAllocflag=0;
   var studentString1='',studentString2='',studentString3='',studentString4='';
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentTestList'){
           fl=1;
           if(studentString1!=''){
               studentString1 +=',';
           }
           studentString1 +=frmObj[i].value+'_'+(frmObj[i].checked==true?1:0);
       }
       
      if(frmObj[i].name=='studentGDList'){
           fl=1;
           if(studentString2!=''){
               studentString2 +=',';
           }
           studentString2 +=frmObj[i].value+'_'+(frmObj[i].checked==true?1:0);
      }
      
      if(frmObj[i].name=='studentIntvList'){
           fl=1;
           if(studentString3!=''){
               studentString3 +=',';
           }
           studentString3 +=frmObj[i].value+'_'+(frmObj[i].checked==true?1:0);
      }
      
      if(frmObj[i].name=='studentSelectionList'){
           fl=1;
           if(frmObj[i].checked==true){
             deAllocflag=1;  
           }
           if(studentString4!=''){
               studentString4 +=',';
           }
           studentString4 +=frmObj[i].value+'_'+(frmObj[i].checked==true?1:0);
      }
       
   }
   if(fl==0){
       messageBox("<?php echo NO_DATA_SUBMIT; ?>");
       return false;
   }
   if(deAllocflag==0){
       if(!confirm("No students are selected.\nAre you sure?")){
           return false;
       }
   }
   
    
   var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Student/doResult.php';
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: document.getElementById('placementDriveId').value, 
                 studentString1 : studentString1,
                 studentString2 : studentString2,
                 studentString3 : studentString3,
                 studentString4 : studentString4
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText);
                     if(ret=="<?php echo SUCCESS;?>"){
                         messageBox("<?php echo PLACEMENT_RESULT_LIST_GENERATED; ?>");
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
                         }
                         else{
                           document.getElementById('eleTdId').innerHTML='No';
                           document.getElementById('eleTdId1').innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";
                           document.getElementById('eleTdId2').innerHTML='';
                           document.getElementById('eleTdId3').innerHTML='';
                           document.getElementById('eleTdId4').innerHTML='';
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

var dataUpdated=0;
function vanishPrintExcel(){
    document.getElementById('printIcon').style.display='none';
    document.getElementById('excelIcon').style.display='none';
    dataUpdated=1;
}

function selectStudentTest(){
   var state=document.getElementById('studentTestListMaster').checked;
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentTestList'){
           frmObj[i].checked=state;
           vanishPrintExcel();
       }
   }
}

function selectStudentGD(){
   var state=document.getElementById('studentGDListMaster').checked;
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentGDList'){
           frmObj[i].checked=state;
           vanishPrintExcel();
       }
   }
}

function selectStudentInterview(){
   var state=document.getElementById('studentInterviewListMaster').checked;
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentIntvList'){
           frmObj[i].checked=state;
           vanishPrintExcel();
       }
   }
}

function selectStudentSelection(){
   var state=document.getElementById('studentSelectionListMaster').checked;
   var frmObj=document.searchForm.elements;
   var len=frmObj.length;
   for(var i=0;i<len;i++){
       if(frmObj[i].name=='studentSelectionList'){
           frmObj[i].checked=state;
           vanishPrintExcel();
       }
   }
}


/* function to print report*/
function printReport() {
    var placementDriveName=document.getElementById('placementDriveId').options[document.getElementById('placementDriveId').selectedIndex].text;
    var qstr="placementDriveName="+placementDriveName+"&placementDriveId="+document.getElementById('placementDriveId').value+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/Placement/studentResultReportPrint.php?'+qstr;
    window.open(path,"StudentResultReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
   var placementDriveName=document.getElementById('placementDriveId').options[document.getElementById('placementDriveId').selectedIndex].text;
   var qstr="placementDriveName="+placementDriveName+"&placementDriveId="+document.getElementById('placementDriveId').value+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
   window.location='<?php echo UI_HTTP_PATH;?>/Placement/studentResultReportCSV.php?'+qstr;
}

</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Placement/Student/generateStudentResultListContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listEvent.php $ 
?>