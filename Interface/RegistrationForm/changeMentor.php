<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ChangeMentor');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Change Mentor</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                     new Array('srNo','#','width="3%"','',false), 
                     new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                     new Array('className','Class','width="18%"','',true),
                     new Array('rollNo','Roll No','width="10%"','',true), 
                     new Array('studentName','Name','width="15%"','',true), 
                     new Array('fatherName','Father`s Name','width="15%"','',true),  
                     new Array('studentMobileNo','Contact No.','width=12%','',true),
                     new Array('permAddress','Address','width="25%"','',false)
                  );

//This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ChangeMentor/ajaxInitMentorList.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'listFrm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form               

 
function doAll(){

   formx = document.listFrm;
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
 
//-------------------------------------------------------
//THIS FUNCTION IS USED TO fetch previous classes
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function getNewMentor(currentMentorId) {
        form = document.listFrm;
        form.newMentorId.length = null; 
        addOption(form.newMentorId, '', 'Select');
	     
		//var newMentorshipId = form.currentMentorId.value
        var url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ChangeMentor/ajaxGetNewMentor.php';
        new Ajax.Request(url,
         {
             method:'post',
             parameters: {
                 currentMentorId: currentMentorId
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var j = eval('(' + trim(transport.responseText) + ')');
					form.newMentorId.length = null; 
					addOption(form.newMentorId, '', 'Select');
                     var len=j.length;
                     for(i=0;i<len;i++){
						if(j[i].userId!=form.currentMentorId.value) {
                          addOption(form.newMentorId, j[i].userId, j[i].employeeNameCode);
						}
                     }
                     
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO fetch previous classes
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function getCurrentMentor(labelId) {
        
	form = document.listFrm;

	form.currentMentorId.length = null; 
	addOption(form.currentMentorId, '', 'Select');

	var url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ChangeMentor/ajaxGetCurrentMentor.php';
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {
             id: 1
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
		    form.currentMentorId.length = null; 
		    addOption(form.currentMentorId, '', 'Select');
             var len=j.length;
             for(i=0;i<len;i++){
                instituteCode = j[i].instituteCode; 
                if(j[i].isTeaching=='1') {
                  str = j[i].employeeName1+" (Teaching - "+instituteCode+") ";  
                }
                else {
                  str = j[i].employeeName1+" (Non Teaching - "+instituteCode+") ";  
                } 
                addOption(form.currentMentorId, j[i].userId, str);
             }
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO copy groups
//Author : Dipanjan Bhattacharjee
// Created on : (23.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
 function newMentorAlloted() {
     
     var url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/ChangeMentor/ajaxUpdateMentor.php';
     
     var currentMentorId=document.getElementById('currentMentorId').value;
     var newMentorId=document.getElementById('newMentorId').value;
    
     if(currentMentorId==''){
         messageBox("<?php echo "Select Current Mentor" ?>");
         document.getElementById('currentMentorId').focus();
         return false;
     }
     if(newMentorId==''){
         messageBox("<?php echo "Select New Mentor" ?>");
         document.getElementById('newMentorId').focus();
         return false;
     }
     
     studentCheck = "";
     selected = 0;
     formx = document.listFrm;
     for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")) {
                if(studentCheck=='') {
                   studentCheck=formx.elements[i].value; 
                }
                else {
                    studentCheck = studentCheck + ',' +formx.elements[i].value; 
                }
                selected++;
            }
        }
     }
     if(selected==0)    {
       alert("Please select atleast 1 record!");
       return false;
     }
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {
              currentMentorId : currentMentorId,
              newMentorId : newMentorId,
              studentCheck : studentCheck
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
			 messageBox(trim(transport.responseText));
             getCurrentMentor();
             getClearList();
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
       });
}

 window.onload=function(){
   getCurrentMentor();
 }
 
 function getClearList() {
    
    if(document.getElementById('currentMentorId').value == '') {
      document.getElementById('showMentorList1').style.display="none";    
      document.getElementById('showMentorList2').style.display="none";
      document.getElementById('resultsDiv').innerHTML='';  
    }
    else {
      document.getElementById('showMentorList1').style.display="";    
      document.getElementById('showMentorList2').style.display="";
      document.getElementById('resultsDiv').innerHTML='';    
      sendReq(listURL,divResultName,searchFormName,'',true);  
    }
 }

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/RegistrationForm/ScMentorship/changeMentorContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: copyGroups.php $ 
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 24/12/09   Time: 14:59
//Updated in $/LeapCC/Interface
//Corrected messages
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/12/09   Time: 19:15
//Created in $/LeapCC/Interface
//Done group coping module
?>
