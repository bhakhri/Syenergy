<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in cgpa calculation
//
//
// Author :Ajinder Singh
// Created on : 16-feb-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CgpaCalculation');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: CGPA Calculation </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=right','align=right',false), new Array('conductingAuthority2','Conducting Authority','width=18% align=left','align=left',true), new Array('subjectCode','Subject','width="15%" align=left','align=left',true), new Array('testTypeName','Test Type','width="18%" align="left"','align="left"',false), new Array('weightageAmount','Weightage Amt.','width="18%" align="right"','align="right"',false), new Array('weightagePercentage','Weightage %','width="18%" align="right"','align="right"',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Student/initCgpaCalculation.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'totalMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';


function validateAddForm(frm) {
	page = 1;
    var fieldsArray = new Array(new Array("labelId","<?php echo SELECT_LABEL;?>"), new Array("class1","<?php echo SELECT_DEGREE;?>"));

	form = document.totalMarksReportForm;

	var len = fieldsArray.length;
	for(i=0;i<len;i++) {
		if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
			messageBox(fieldsArray[i][1]);
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
		}
	}

	calculateCGPA();

	//openStudentLists(frm.name,'rollNo','Asc');    
/*	
	hideResults();
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	showReport(page);
*/
}

function calculateCGPA() {
	if(confirm("<?php echo APPLY_CGPA_CONFIRM;?>")) {
		frm = document.totalMarksReportForm.name;
		var pars = generateQueryString(frm);

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
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function getMarksTotalClasses() {
	//hideResults();
	form = document.totalMarksReportForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/getMarksTotalClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.class1.length = null;
		addOption(form.class1, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
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
				addOption(form.class1, 'all', 'All');
			}
			*/
			
			for(i=0;i<len;i++) {
				addOption(form.class1, j[i].classId, j[i].className);
			}
			// now select the value
			form.class1.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listCgpaCalculation.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: scCgpaCalculation.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/16/09    Time: 4:58p
//Updated in $/Leap/Source/Interface
//updated access defines
//


?>
