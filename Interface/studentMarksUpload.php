<?php 
//-------------------------------------------------------
//  This File contains starting code for student external marks uploading
//
//
// Author :Jaineesh
// Created on : 8-Oct-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadStudentExternalMarks');
define('ACCESS','add');
global $sessionHandler; 
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId=='2') {      // Teacher Login
  UtilityManager::ifTeacherNotLoggedIn(); 
}
else {
  UtilityManager::ifNotLoggedIn(); 
}
UtilityManager::headerNoCache();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload External Marks</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var  valShow=0; 

function transferMarks() {
	if(confirm("<?php echo MARKS_TRANSFER_CONFIRM;?>")) {
		frm = document.marksNotEnteredForm.name;
		var pars = generateQueryString(frm);

		new Ajax.Request(listURL,
		{
			method:'post',
			parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				messageBox(trim(transport.responseText));
				//hideResults();
				//blankValues();
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function checkAllowedExtensions(value){
  //get the extension of the file 
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var extArr= 'xls';
  var fl=0;

 if(val.toUpperCase()==extArr.toUpperCase()) {
	  fl=1;
  }

 if(fl){
   return true;
  }
 else{
  return false;
 }
}

function doSubmitAction() {
	if(document.getElementById('degree').value == "") {
		messageBox("<?php echo 'Please select class' ?>");
		document.getElementById('degree').focus();
		return false;
	}
	document.getElementById('addForm').target = 'uploadTargetAdd';
	document.getElementById('addForm').action = '<?php echo HTTP_LIB_PATH;?>/StudentMarksUpload/exportStudentMarksCSV.php';
	formSubmissionTime = '<?php echo time();?>';
	document.getElementById('addForm').submit();
}

function onSubmitAcion() {
	if(document.getElementById('showError').value == 'file') {
		document.getElementById('statusDiv').style.display = 'none';	
	}
	else {
		document.getElementById('statusDiv').style.display = '';
	}
	
        for(i=0;i<document.getElementById('dataCount').value;i++) {
		if(document.getElementById('file_'+i).value != '') {
			if(!checkAllowedExtensions(document.getElementById('file_'+i).value)) {
				messageBox("<?php echo INCORRECT_EXTENSION_MARKS_UPLOAD; ?>");
				return false;
			}
		}
	}
	document.getElementById('addForm').target = 'uploadTargetAdd';
	document.getElementById('addForm').action = '<?php echo HTTP_LIB_PATH;?>/StudentMarksUpload/fileUpload.php';
	formSubmissionTime = '<?php echo time();?>';
	document.getElementById('addForm').submit();
}

function reset1() {
	tt = document.getElementById("degree").value;
 document.getElementById('statusDiv').style.display = 'none';
 for(i=0;i<document.getElementById('dataCount').value;i++) {
	document.getElementById('testType_'+i).value = "";
	document.getElementById('file_'+i).value = "";
 }
document.addForm.reset();
document.getElementById("degree").value = tt;
}

function getClass() {
	document.getElementById('marksDiv').style.innerHTML = '';
	document.getElementById('marksDiv').style.display = 'none';
	document.getElementById('statusDiv').style.innerHTML = '';
	document.getElementById('statusDiv').style.display = 'none';
}


function getClassSubjects() {
	if(document.getElementById('timeTable').value == '') {
		alert('Select Time Table');
		document.getElementById('timeTable').focus();
		return false;
	}
	if(document.getElementById('degree').value == '') {
		alert('Select class');
		document.getElementById('degree').focus();
		return false;
	}
	document.getElementById('marksDiv').style.display = '';
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentMarksUpload/getClassSubjects.php';
	var pars = 'degree='+document.getElementById('degree').value;
    pars = pars+'&timeTable='+document.getElementById('timeTable').value;    
    
	new Ajax.Request(url, 
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = trim(transport.responseText);
			// now select the value
			document.getElementById('marksDiv').innerHTML = 'block';
			document.getElementById('marksDiv').innerHTML = trim(transport.responseText);
			//var subjectId=document.getElementById('subjectId').value = j[0].subjectId;
                       var cnt=document.getElementById('dataCount').value; 
             	       var selectItems;
 		       var subject='';
                       var items;
                       for(var i=0;i<cnt;i++){ 
                              selectItems=document.getElementById('testType_'+i);
                              items=selectItems.getElementsByTagName('option'); 
                              if(!(items.length>1)){
                                if(subject=''){
                                subject=document.getElementById('subjectCode_'+i).textContent;}
                                else{
                                subject +=document.getElementById('subjectCode_'+i).textContent;}
                             }
                          }
                       
                      
                     if(!(items.length>1)){
                       document.getElementById('alertUser').style.display=''; 
                       document.getElementById('alertUser').innerHTML="NOTE**:<br>External Marks Not Defined For: "+subject;                     
                       }
                      
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	changeColor(currentThemeId);
}

function getLabelClass(){
	document.getElementById('marksDiv').style.innerHTML = '';
	document.getElementById('marksDiv').style.display = 'none';
	document.getElementById('statusDiv').style.innerHTML = '';
	document.getElementById('statusDiv').style.display = 'none';
	
	form = document.addForm;
	var timeTable = form.timeTable.value;
	 
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentMarksUpload/initGetLabelClass.php';
	var pars = 'timeTable='+timeTable;
	document.addForm.degree.length = null; 
	addOption(document.addForm.degree, '', 'Select Class');
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
			 
			document.addForm.degree.length = null;
			addOption(document.addForm.degree, '', 'Select Class');
			if (len > 0) {
				//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
			}
			for(i=0;i<len;i++) { 
				addOption(document.addForm.degree, j[i].classId, j[i].className);
			}
			// now select the value
			//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
	   },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


window.onload = function () {
    //if (document.getElementById('labelId').value == '') {
   //  return false;
   //}
   //document.getElementById('degree').value = '';
   getShowDetail();
   getLabelClass();
   document.getElementById('timeTable').focus();
   return false;
}



function getShowDetail() {
   document.getElementById("idSubjects").innerHTML="Expand Sample Format for .xls file and instructions"; 
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif"
   document.getElementById("showSubjectEmployeeList11").style.display='none';
   if(valShow==1) {
     document.getElementById("showSubjectEmployeeList11").style.display='';
     document.getElementById("idSubjects").innerHTML="Collapse Sample Format for .xls file and instructions";
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"
     valShow=0;
   }
   else {
     valShow=1;  
   }
   return false; 
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentMarksUpload/listStudentMarksUpload.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 

//$History: studentMarksUpload.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/16/10    Time: 3:45p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003278, 0002889, 0003279, 0003271
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 4/13/10    Time: 7:13p
//Updated in $/LeapCC/Interface
//fixed bugs. FCNS No. 1574
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 2/17/10    Time: 7:24p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0002885, 0002887, 0002886, 0002888, 0002889 and add time
//table filter also
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 2/12/10    Time: 4:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.  0002857, 0002861, 0002860, 0002863, 0002862
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 2/10/10    Time: 7:05p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0002849, 0002846
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/09/10    Time: 6:45p
//Updated in $/LeapCC/Interface
//modification in code for change color of button
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/09/10    Time: 5:56p
//Created in $/LeapCC/Interface
//new for student external marks upload
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:39a
//Created in $/LeapCC/Interface
//new file for student roll no. uploading
//
//
?>
