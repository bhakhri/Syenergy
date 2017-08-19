<?php 
//-------------------------------------------------------
//  This is a Interface file to upload candidate data (Excel File)
//
// Author :Vimal Sharma
// Created on : 07-Feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadCandidateDetails');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Import Candidate Details From Excel </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
var  valShow=0; 
var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), 
                               new Array('','Class','width=25%  align=left',' align=left',false), 
                               new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), 
                               new Array('groupName','Group','width="10%"  align=left',' align=left',false), 
                               new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), 
                               new Array('testName','Test Name','width="20%"  align=left',' align=left',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/initUploadCandidate.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'uploadCandidate'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'candidateName';
sortOrderBy    = 'Asc';
 //This function Validates Form 


function validateAddForm(form) {
/*    var fieldsArray = new Array(new Array("class1","<?php echo SELECT_DEGREE;?>"));

	//degree
	form = document.addForm;
	totalClasses = form.elements['subjectId[]'].length;
	selectedClasses = '';
	for(i=0;i<totalClasses;i++) {
		if (form.elements['subjectId[]'][i].selected == true) {
			if (selectedClasses != '') {
				selectedClasses += ',';
			}
			selectedClasses += form.elements['subjectId[]'][i].value;
		}
	}

	if (selectedClasses == '') {
		messageBox("<?php echo SELECT_DEGREE;?>");
		return false;

	}
	form = document.addForm;
  */
}

function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

 
function dataPassed(str) {
	if (str == "<?php echo ADMISSION_UPLOAD_DONE;?>") {
	  messageBox(str);
	}
    
}
window.onload = function () {
	//fdocument.getElementById('labelId').focus();
}


function divShow() {
    showWaitDialog(true);
}

function divHide() {
   hideWaitDialog(true);
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
    require_once(TEMPLATES_PATH . "/StudentEnquiry/candidateUpload.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
