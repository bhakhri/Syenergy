<?php
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 29-05-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PromoteStudents');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Promote Students </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('className','Class','width=25%  align=left',' align=left',false), new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), new Array('groupName','Group','width="10%"  align=left',' align=left',false), new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false));

 //This function Validates Form
var listURL='<?php echo HTTP_LIB_PATH;?>/Student/initPromoteStudents.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'promoteStudentsForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form

var myArray = new Array();
var myStudentCountArray = new Array();
var myNextClassArray = new Array();

function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("class1","<?php echo SELECT_DEGREE;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	form = document.promoteStudentsForm;
	showPromotionDiv();
	//promoteStudents();

}


function showPromotionDiv() {
	tableData = '';
	tableData2 = '';
	tableData3 = '';
	frm = document.promoteStudentsForm;
	class1 = frm.class1.value;
	document.promotionFormMain.class1.value = class1;
	document.getElementById("promotionDetailDiv").style.display='';
	document.getElementById("promotionDetailDiv2").style.display='none';
	document.getElementById("promotionDetailDiv3").style.display='none';
	displayWindow('promotionDiv',270,550);
}

function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function confirmDetails() {
	frm = document.promoteStudentsForm;
	class1 = frm.class1.value;
	class1Text = frm.class1.options[frm.class1.selectedIndex].text;
	tableData2 = '';
	tableData2 = "<table width='100%' border='0'><tr><td style='width:400px;'>Class to be promoted:</td><td><b> "+class1Text+"</b></td></tr>";
	tableData2 += "<tr><td style='width:400px;'>This class contains : </td><td><b>"+myStudentCountArray[class1]+" Students.</b></td></tr>";
	tableData2 += "<tr><td>These <b>"+myStudentCountArray[class1]+" Students</b> will be promoted to : </td><td><b>"+myNextClassArray[class1]+".</td></tr>";
	document.getElementById("promotionDetailDiv2").innerHTML=tableData2;
	document.getElementById("promotionDetailDiv2").style.display='';
	confirmDetails2();
}
function confirmDetails2() {
	/*
	frm = document.promoteStudentsForm;
	class1 = frm.class1.value;
	class1Text = frm.class1.options[frm.class1.selectedIndex].text;
	*/
	document.getElementById("promotionDetailDiv3").style.display='';
}

function printReport() {
	form = document.promoteStudentsForm;
	path='<?php echo UI_HTTP_PATH;?>/scPromoteStudentsFailedReportPrint.php?class1='+form.class1.value;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}

function getClassesForPromotion() {
	form = document.promoteStudentsForm;
	form.class1.length = null;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/getClassesForPromotion.php';
	pars = '1=1';
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
				addOption(form.class1, '', 'Select');

				/*
				if (len > 0) {
					addOption(document.testWiseMarksReportForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) {

					addOption(form.class1, j[i]['classId'], j[i]['className']);
					newVar = j[i]['classId'].toString();
					myArray[newVar] = j[i]['marksTransferred'];
					myStudentCountArray[newVar] = j[i]['studentCount'];
					myNextClassArray[newVar] = j[i]['nextClassName'];
				}
				// now select the value
				form.class1.value = '';

		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function promoteStudents() {
	/*
	frm = document.promoteStudentsForm;
	class1 = frm.class1.value;
	classMarksTransferred = myArray[class1];
	*/
	pars = generateQueryString('promotionFormMain');
	xReturn = false;
	xReturn = confirm("<?php echo PROMOTE_STUDENTS_CONFIRM;?>");
	if(xReturn == true) {

		new Ajax.Request(listURL,
		{
			method:'post',
			parameters: pars,
			asynchronous: false,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){
				hideWaitDialog(true);
				j= trim(transport.responseText);
				messageBox(j);
				if (j == "<?php echo STUDENTS_PROMOTED;?>") {
					hiddenFloatingDiv("promotionDiv");
					document.promotionFormMain.reset();
				}
				getClassesForPromotion();
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listPromoteStudents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
	getClassesForPromotion();
</script>
</body>
</html>

<?php

//$History: promoteStudents.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 5/29/09    Time: 11:34a
//Created in $/LeapCC/Interface
//file added for student promotion
//




?>
