<?php 
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 02-May-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UploadExternalMarks');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transfer Marks </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('className','Class','width=25%  align=left',' align=left',false), new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), new Array('groupName','Group','width="10%"  align=left',' align=left',false), new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false)); 

 //This function Validates Form 
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
	totalClasses = form.elements['class1'].length;
	selectedClasses = '';
	for(i=0;i<totalClasses;i++) {
		if (form.elements['class1'][i].selected == true) {
			if (selectedClasses != '') {
				selectedClasses += ',';
			}
			selectedClasses += form.elements['class1'][i].value;
		}
	}

	if (selectedClasses == '') {
		messageBox("<?php echo SELECT_DEGREE;?>");
		return false;

	}
	form = document.marksNotEnteredForm;
	showClassSubjects();

}

function downloadSample() {
	frm = document.marksNotEnteredForm.name;
	pars = generateQueryString(frm);
	document.getElementById('generateCSV').href='downloadExternalMarksSampleFile.php?'+pars;
}

function uploadSampleFile() {
	frm = document.marksNotEnteredForm;
	frm.isFileUploaded.value = 1;
}

function showClassSubjects() {
	frm = document.marksNotEnteredForm.name;
	var pars = generateQueryString(frm);
	listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetClassSubjects.php';

	new Ajax.Request(listURL,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var tableData = '<table border="0" cellpadding="1" cellspacing="1">';
			tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text">#</td><td width="8%" class="searchhead_text">Subject Code</td><td width="20%" class="searchhead_text">Subject Name</td><td width="20%" class="searchhead_text">Order</td></tr>';
			var j = eval('(' + transport.responseText + ')');
			totalSubjects = j['subjects'].length;
			for(i=0;i<totalSubjects;i++) {
				m = i + 1;
				var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
				subjectId = j['subjects'][i]['subjectId'];
				subjectCode = j['subjects'][i]['subjectCode'];
				subjectName = j['subjects'][i]['subjectName'];
				tableData += '<tr '+bg+'><td width="2%" class="padding_top">'+m+'</td><td width="8%" class="padding_top">'+subjectCode+'</td><td width="20%" class="padding_top">'+subjectName+'</td><td width="20%" class="padding_top"><input type="text" name="text_'+subjectId+'" value="'+m+'" class="htmlElement" size="6" /></td></tr>';
			}
			tableData += "</table>";
			document.getElementById("resultRow").style.display = '';
			document.getElementById("nameRow2").style.display = '';
			document.getElementById("resultsDiv").innerHTML = tableData;

		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});

	
	
}
function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
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


function getClassesForTransfer() {
	form = document.marksNotEnteredForm;
	form.class1.length = null;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/getClassesForTransfer.php';
	pars = generateQueryString('marksNotEnteredForm');
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
				form.class1.length = null;
				//addOption(form.elements['class1[]'], '', 'Select');
				
				/*
				if (len > 0) {
					addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) { 
					
					addOption(form.elements['class1'], j[i]['classId'], j[i]['className']);
				}
				// now select the value
				form.class1.value = '';
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

window.onload = function() {
	document.getElementById('labelId').focus();
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listUploadExternalMarks.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: uploadExternalMarks.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 5/02/09    Time: 6:43p
//Updated in $/LeapCC/Interface
//updated access define.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/02/09    Time: 6:39p
//Created in $/LeapCC/Interface
//file added for external marks upload.
//







?>
