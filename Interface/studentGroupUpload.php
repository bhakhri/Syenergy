<?php 
//-------------------------------------------------------
//  This File contains starting code for group uploading
//
//
// Author :Jaineesh
// Created on : 8-Oct-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadStudentGroup');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Student Group</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var  valShow=0; 
var tableHeadArray = new Array(	new Array('srNo','#','width="5%" align=left','align=left',false), 
								new Array('className','Class','width=25%  align=left',' align=left',false), 
								new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), 
								new Array('groupName','Group','width="10%"  align=left',' align=left',false), 
								new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), 
								new Array('testName','Test Name','width="20%"  align=left',' align=left',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/ScStudent/scInitTransferMarks.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'marksNotEnteredForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form 


function validateAddForm(form) {
    var fieldsArray = new Array(new Array("class1","<?php echo SELECT_DEGREE;?>"));

	//degree
	totalClasses = form.elements['class1[]'].length;
	selectedClasses = '';
	for(i=0;i<totalClasses;i++) {
		if (form.elements['class1[]'][i].selected == true) {
			if (selectedClasses != '') {
				selectedClasses += ',';
			}
			selectedClasses += form.elements['class1[]'][i].value;
		}
	}

	if (selectedClasses == '') {
		messageBox("<?php echo SELECT_DEGREE;?>");
		return false;

	}
	form = document.marksNotEnteredForm;
	transferMarks();

}
function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function printReport() {
	form = document.marksNotEnteredForm;
	path='<?php echo UI_HTTP_PATH;?>/marksNotEnteredReportPrint.php?class1='+form.class1.value;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}

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

function dataPassed(str) {
	if (str == "<?php echo SUCCESS;?>") {
		messageBox(str);
	}
}

window.onload = function () {
   getShowDetail(); 
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
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentGroupUpload/listStudentGroupUpload.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: studentGroupUpload.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 10/14/09   Time: 5:29p
//Created in $/LeapCC/Interface
//new file for group uploading
//
//
?>
