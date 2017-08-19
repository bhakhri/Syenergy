<?php
//-----------------------------------------------------------------------------
//  To generate Studnet Counseling functionality      
//
// Author :Parveen Sharma
// Created on : 26-Dec-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentCounseling');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Counseling </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">


 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxCounselingStudentList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 48;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'Asc';
 //This function Validates Form 
 
queryString='';
queryString1 = '';

function validateAddForm() {
    page=1; 
    queryString = '';
    queryString1 = '';
   
   document.getElementById('startingRecord').value = trim(document.getElementById('startingRecord').value);
   document.getElementById('totalRecords').value = trim(document.getElementById('totalRecords').value);
   
   document.getElementById('rankFrom').value = trim(document.getElementById('rankFrom').value);
   document.getElementById('rankTo').value = trim(document.getElementById('rankTo').value);
   
   
    if(true == isEmpty(trim(document.getElementById('startingRecord').value))){
        messageBox("<?php echo COUNSELING_START_RECORD ?>");
        document.allDetailsForm.startingRecord.focus();
        return false;
    }
    
    if(false == isNumeric(document.getElementById('startingRecord').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.allDetailsForm.startingRecord.focus(); 
       return false;  
    }
    
    if(document.getElementById('startingRecord').value <= 0 ) {
       messageBox("<?php echo "Value for 'Total No. of Students in Counseling From' field should be 1 "; ?>");
       document.allDetailsForm.startingRecord.focus(); 
       return false;  
    }
    
    if(true == isEmpty(trim(document.getElementById('totalRecords').value))){
        messageBox("<?php echo COUNSELING_END_RECORD ?>");
        document.allDetailsForm.totalRecords.focus();
        return false;
    }
    
    
    if(false == isNumeric(document.getElementById('totalRecords').value) ) {
       messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
       document.allDetailsForm.totalRecords.focus(); 
       return false;  
    }
    
    if(document.getElementById('totalRecords').value <= 0 ) {
       messageBox("<?php echo "Value for 'Total No. of Students in Counseling To' field should be 1 "; ?>");
       document.allDetailsForm.totalRecords.focus(); 
       return false;  
    }
    
    if(trim(document.getElementById('startingRecord').value)!='' && trim(document.getElementById('totalRecords').value)!=''){   
        if(parseFloat(trim(document.getElementById('startingRecord').value)) > parseFloat(trim(document.getElementById('totalRecords').value))) {
           messageBox("<?php echo " Total No. of Students in Counseling From cannot be greater than Counseling To"; ?>");
           document.allDetailsForm.startingRecord.focus(); 
           return false;   
        }
    }
    
  /*  if(trim(document.getElementById('entranceExam').value)=='') {
       messageBox("<?php echo "Select Competition Exam. By";?>");  
       document.getElementById('entranceExam').focus();
       return false;
    } */
    
    if(!isEmpty(document.getElementById('startDate').value)) {
       if(isEmpty(document.getElementById('endDate').value)) {
         messageBox("<?php echo EMPTY_TO_DATE;?>");  
         document.getElementById('startDate').focus();
         return false;
       }  
    }    
       
    if(!isEmpty(document.getElementById('endDate').value)) {
       if(isEmpty(document.getElementById('startDate').value)) {
         messageBox("<?php echo EMPTY_FROM_DATE;?>");  
         document.getElementById('endDate').focus();
         return false;
       }   
    }  
       
    if(!isEmpty(document.getElementById('startDate').value) && !isEmpty(document.getElementById('endDate').value)) { 
        if(!dateDifference(eval("document.getElementById('startDate').value"),eval("document.getElementById('endDate').value"),'-') ) {
           messageBox ("<?php echo DATE_CONDITION1;?>");
           eval("document.getElementById('startDate').focus();");
           return false;
        } 
    } 
    
    if(!dateDifference(eval("document.getElementById('currentDate').value"),eval("document.getElementById('startDate').value"),'-') ) {  
       messageBox("<?php echo CURRENT_DATE_CHECK; ?>");      
       document.getElementById('startDate').focus();
       return false;
    }
    
    if(trim(document.getElementById('rankFrom').value)!='' ){
       if(false == isNumeric(document.getElementById('rankFrom').value) ) {
         messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
         document.allDetailsForm.rankFrom.focus(); 
         return false;  
       }
    }
    
    if(trim(document.getElementById('rankTo').value)!='' ){
       if(false == isNumeric(document.getElementById('rankTo').value) ) {
         messageBox("<?php echo ENTER_NUMERIC_VALUE ?>");
         document.allDetailsForm.rankTo.focus(); 
         return false;  
       }
    }
    
    if(trim(document.getElementById('rankFrom').value)=='' && trim(document.getElementById('rankTo').value)!='') {
       messageBox("<?php echo "Enter Rank From "; ?>");
       document.allDetailsForm.rankFrom.focus(); 
       return false;   
    }
    
    if(trim(document.getElementById('rankFrom').value)!='' && trim(document.getElementById('rankTo').value)=='') {
       messageBox("<?php echo "Enter Rank To "; ?>");
       document.allDetailsForm.rankTo.focus(); 
       return false;   
    }
    
    if(trim(document.getElementById('rankFrom').value)!='' && trim(document.getElementById('rankTo').value)!=''){   
        if(parseFloat(trim(document.getElementById('rankFrom').value)) > parseFloat(trim(document.getElementById('rankTo').value))) {
           messageBox("<?php echo "Rank From cannot be greater than Rank To"; ?>");
           document.allDetailsForm.rankFrom.focus(); 
           return false;   
        }
    }
    hideDetails();  
    
    refreshResultData();
    return false;
}

//this function fetches records corresponding to student fees detail
function refreshResultData(){
  
   url='<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxCounselingStudentList.php';     
                       
   var tableColumns = new Array(
                     new Array('srNo','#','width="2%" align="left"',false), 
                     new Array('checkAll','<nobr>&nbsp;<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" checked=\"checked\" onclick=\"doAll();\"></nobr>&nbsp;','width="3%" align="center"',false), 
                     new Array('studentName','Name','width="12%" align="left"',true),
                     new Array('studentEmail','Email','width="12%" align="left"',true),
                     new Array('contact','Contact','width="12%" align="left"',true),
                     new Array('compExamBy','Comp. Exam. By','width="11%" align="left"',true),
                     new Array('compExamRollNo','Comp. Exam. Roll No.','width="14%" align="left"',true), 
                     new Array('compExamRank','Rank','width="7%" align="left"',true),
                     new Array('candidateStatus','Status','width="8%"','',true)  
                  );
                       
    if(document.getElementById("sortField1").value==1) {
       sortField = "compExamRank";   
    }
    else if(document.getElementById("sortField1").value==2) {
       sortField = "studentName";   
    }
    
    if(document.getElementById("sortOrderBy1").value==1) {
       sortOrderBy = "DESC";  
    }
    else if(document.getElementById("sortOrderBy1").value==2) {
       sortOrderBy = "ASC";  
    }

   queryString = generateQueryString('allDetailsForm');  
   
   queryString1  = "&startDate="+document.getElementById("startDate").value+"&endDate="+document.getElementById("endDate").value;
   
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'',sortField,sortOrderBy,'results','','',true,'listObj6',tableColumns,'','',queryString);
   sendRequest(url, listObj6,'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);
   
   document.getElementById("resultRow").style.display='';
   document.getElementById('nameRow').style.display='';
   document.getElementById('nameRow2').style.display='';
}

function doAll(){
    formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
                formx.elements[i].checked=false;
            }
        }
    }
}

function addCounseling() {
     url='<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxSendStudentMessageAll.php';        
     
     studentId = "";
     formx = document.allDetailsForm;
    
   //  if(formx.checkbox2.checked){
        
        for(var i=1;i<formx.length;i++){
            
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true) {
                if(studentId=='') {
                   studentId = formx.elements[i].value; 
                }
                else {
                   studentId = studentId+","+formx.elements[i].value;
                }
            }
        }
   //  }

   if (studentId == "") {
	   alert('No data to save');
	   return false;
   }
   
    
     pars = "&studentId="+studentId+queryString1;

     new Ajax.Request(url,
     {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function() {
               showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             var ret=trim(transport.responseText).split('!~!~!');
             var eStr='';
             var fl=0;    
             if("<?php echo SUCCESS;?>" == ret[0]) {                     
               flag = true;
               if(ret[2]!=''){
                 eStr +='\nEmail not sent to these students :\n'+ret[2];
                 fl=1;
               }
               
               if(fl==1) {
                 if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){  
                   window.location = "<?php echo UI_HTTP_PATH ?>/adminMessageDocument.php?type=s&smsStudentIds="+ret[1]+"&emailStudentIds="+ret[2];
                 }
               }
               else {
                 messageBox("<?php echo MSG_SENT_OK; ?>"+eStr); 
                 sendRequest(url, listObj6,'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,true);     
               }
             } 
             else {
                messageBox(ret[0]); 
             }
             resetForm(); //it is not called because there is paging
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
} 


function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}


window.onload=function(){
     var roll = document.getElementById("rollNo");
     //roll.focus();
     //autoSuggest(roll);
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentEnquiry/listCounselingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>


<?php 
//$History: studentCounseling.php $
//
//*****************  Version 3  *****************
//User: Parveen      Date: 4/14/10    Time: 11:23a
//Updated in $/LeapCC/Interface
//validation and format updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Interface
//query & condition format updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 3/22/10    Time: 4:05p
//Created in $/LeapCC/Interface
//initial checkin
//

?>