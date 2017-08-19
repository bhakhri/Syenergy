<?php
//-------------------------------------------------------
// THIS FILE SHOWS A MEDICAL LEAVE CONFLICT REPORT
// Author : Aditi Miglani
// Created on : 26 Sept 2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MedicalLeaveConflictAdminReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

function createMedicalLeaveStatusString(){
    global $globalMedicalLeaveStatusArray;
    $returnString='<option value="-1">Select</option>';
    foreach($globalMedicalLeaveStatusArray AS $key=>$value){
        $returnString .='<option value="'.$key.'" >'.$value.'</option>';
    }
    return '<select name="commonMedicalLeaveStatus" id="commonMedicalLeaveStatus" class="inputbox" style="width:100px;" onchange="changeMedicalLeaveStatus(this.value);">'.$returnString.'</select>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Medical Leave Conflict Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray; 

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 2000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentDutyMedicalLeaves/ajaxMedicalLeaveConflictReport.php';
searchFormName = 'conflictReportForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var serverDate="<?php echo date('Y-m-d');?>";

function changeMedicalLeaveStatus(val){
  var c1 = document.getElementById(divResultName).getElementsByTagName('SELECT');
  var len=c1.length;
  for(var i=0;i<len;i++){
      if(c1[i].name=='medicalLeaveStatus'){
          c1[i].value=val;
          checkBulkAttendanceRestriction(c1[i].id,c1[i].value);
      }
  }
}

// Function to Expand and collapse instructions for Conflict and Non Conflict Report
var  valShow=0;

function getShowDetail() {
  displayWindow('showHelpMessage',200,200); 
}

// End of Function to Expand and collapse instructions for Conflict and Non Conflict Report

function validateData(){
   if(document.getElementById('labelId').value==''){
     messageBox("<?php echo SELECT_TIME_TABLE;?>");
     document.getElementById('labelId').focus();
     return false;
   }
   
   if(document.getElementById('classId').value==''){
     messageBox("<?php echo SELECT_CLASS;?>");
     document.getElementById('classId').focus();
     return false;
   }

   if(document.getElementById('subjectId').value==''){
     messageBox("<?php echo SELECT_SUBJECT;?>");
     document.getElementById('subjectId').focus();
     return false;
   }

   hideResults();
   page=1;
   sortField   = 'studentName'; 
   sortOrderBy = 'ASC';
   if(document.conflictReportForm.showConflict[0].checked==true || document.conflictReportForm.showConflict[2].checked==true){
       tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('subjectCode','Subject','width="10%"','',true), 
                                new Array('rollNo','Roll No.','width="8%"','',true), 
                                new Array('studentName','Name','width="12%"','',true), 
                                new Array('medicalLeaveDate','Date','width="6%"','align="center"',true), 
                                new Array('periodNumber','Period','width="5%"','',true), 
                                new Array('conflictedWith','Conflicted with','width="8%"','',false), 
                                new Array('actionString','Action&nbsp;<?php echo createMedicalLeaveStatusString();?>','width="12%"','',false) 
                              );
       
   }
   else{
     tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('subjectCode','Subject','width="10%"','',true), 
                                new Array('rollNo','Roll No.','width="8%"','',true), 
                                new Array('studentName','Name','width="12%"','',true), 
                                new Array('medicalLeaveDate','Date','width="6%"','align="center"',true), 
                                new Array('periodNumber','Period','width="5%"','',true), 
                                new Array('actionString','Action&nbsp;<?php echo createMedicalLeaveStatusString();?>','width="12%"','',false) 
                              );  
   }
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   scanForBulkAttendance();
   document.getElementById('savePrintRowId').style.display='';
   document.getElementById('printSpanId').style.display='';
   
}

var lectureAttendedObject  = new Object();
var lectureDeliveredObject = new Object();
function checkBulkAttendanceRestriction(id,value){
   document.getElementById('printSpanId').style.display='none'; 
   var eleArray=id.split('_');
   if(eleArray[6]==1){
       if(value=="<?php echo MEDICAL_LEAVE_APPROVE;?>"){
        var ele=eleArray[0]+'_'+eleArray[1]+'_'+eleArray[2];
        var lecAtt=lectureAttendedObject[ele];
        var lecDel=lectureDeliveredObject[ele];
        if((lecAtt+1)>lecDel){
            messageBox("<?php echo MEDICAL_LEAVE_RESTRICTION;?>");
            document.getElementById(id).focus();
            return false;
        }
       }
       else{
           return true;
       }
   }
   else{
       return true;
   }
}

function scanForBulkAttendance(){
    lectureAttendedObject  = new Object();
    lectureDeliveredObject = new Object();
    var elements=document.getElementById('results').getElementsByTagName('SELECT');
    var len=elements.length;
    for(var i=0;i<len;i++){
        if(elements[i].name=='medicalLeaveStatus'){
          var eleArray=elements[i].id.split('_');
          if(eleArray[6]==1){
            var ele=eleArray[0]+'_'+eleArray[1]+'_'+eleArray[2];
            if(isNaN(parseInt(lectureAttendedObject[ele]))){
              lectureAttendedObject[ele]=eleArray[7];
            }
            else{
              lectureAttendedObject[ele]=eleArray[7]+lectureAttendedObject[ele];  
            }
            if(isNaN(parseInt(lectureDeliveredObject[ele]))){
              lectureDeliveredObject[ele]=eleArray[8];
            }
            else{
              lectureDeliveredObject[ele]=eleArray[8]+lectureDeliveredObject[ele];  
            }
          }
        }
    }
}

function doMedicalLeave(){
    var elements=document.getElementById('results').getElementsByTagName('SELECT');
    var len=elements.length;
    var inputString='';
    for(var i=0;i<len;i++){
        if(elements[i].name=='medicalLeaveStatus'){
            if(elements[i].value=='-1'){
                messageBox("<?php echo SELECT_MEDICAL_LEAVE_STATUS;?>");
                elements[i].focus();
                return false;
            }
            if(inputString!=''){
                inputString +=',';
            }
            inputString +=elements[i].id+'_'+elements[i].value;
        }
    }
    if(inputString==''){
        messageBox("<?php echo NO_DATA_SUBMIT;?>");
        return false;
    }  
    
     var url = '<?php echo HTTP_LIB_PATH;?>/StudentDutyMedicalLeaves/ajaxDoMedicalLeave.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             classId     : document.getElementById("classId").value,
	     rollNo     : document.getElementById("rollNo").value,
             inputString : inputString
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true); 
                var ret=trim(transport.responseText);
                if(ret=="<?php echo SUCCESS;?>"){
                    messageBox("<?php echo MEDICAL_LEAVES_GIVEN;?>");
                    hideResults();
                }
		        else if(ret=="<?php echo FAILURE;?>"){
                    messageBox("<?php echo FAILURE;?>");
                    hideResults();
                }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function getTimeTableClasses() {
    hideResults();
    var form = document.conflictReportForm;
    var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
    var pars = 'labelId='+form.labelId.value;
    form.classId.options.length = 1;
    if (form.labelId.value=='') {
        return false;
    }

    new Ajax.Request(url,
    {
        method:'post',
        asynchronous : false,
        parameters: pars,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('(' + trim(transport.responseText) + ')');
            var len = j.length;
            
            for(i=0;i<len;i++) {
                addOption(form.classId, j[i].classId, j[i].className);
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}



function getClassSubjects() {
	form = document.conflictReportForm;
	var degree = form.classId.value;
	var labelId = form.labelId.value;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initClassAttendanceSubject.php';
	var pars = 'timeTable='+labelId+'&degree='+degree;
	if (degree == '') {
		document.conflictReportForm.subjectId.length = null;
		addOption(document.conflictReportForm.subjectId, '', 'Select');
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
				document.conflictReportForm.subjectId.length = null;
				if(len>0) {
                   addOption(document.conflictReportForm.subjectId, 'all', 'All');
				   for(i=0;i<len;i++) { 
				     addOption(document.conflictReportForm.subjectId, j[i].subjectId, j[i].subjectCode);
				   }
                 }
                 else {
                   addOption(document.conflictReportForm.subjectId, '', 'Select');
                 }
				// now select the value
			 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	   hideResults();
}


function hideResults(){
    document.getElementById('results').innerHTML='';
    document.getElementById('savePrintRowId').style.display='none';
    document.getElementById('printSpanId').style.display='none';
}
/* function to output data for printable version*/
function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/medicalLeaveConflictReportPrint.php?'+generateQueryString('conflictReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path +='&labelName='+document.getElementById('labelId').options[document.getElementById('labelId').selectedIndex].text;
    path +='&className='+document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    path +='&subjectName='+document.getElementById('subjectId').options[document.getElementById('subjectId').selectedIndex].text;
    window.open(path,"MedicalLeaveConflictReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    var path='<?php echo UI_HTTP_PATH;?>/medicalLeaveConflictReportCSV.php?'+generateQueryString('conflictReportForm')+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.location = path;
}

</script>
</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/StudentDutyMedicalLeaves/medicalLeaveConflictReportContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
