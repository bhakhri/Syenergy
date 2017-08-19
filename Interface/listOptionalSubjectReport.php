<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Optional Subject Report
//
//
// Author :Jaineesh
// Created on : 16.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OptionalGroupReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Optional/Compulsy Groups Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

function getSelectedList() {

	if(document.getElementById('labelId').value == '') {
		messageBox("<?php echo SELECT_TIME_TABLE ?>");
		document.getElementById('labelId').focus();
		return false;
	}

	if(document.getElementById('degree').value == '') {
		messageBox("<?php echo SELECT_Class ?>");
		document.getElementById('degree').focus();
		return false;
	}

	if(document.getElementById('option').value == '') {
		messageBox("<?php echo SELECT_OPTION ?>");
		document.getElementById('option').focus();
		return false;
	}
	else {
		if(document.getElementById('option').value == 'optional') {
			getList();
		}
		else {
			getWithoutOptionalList();
		}
	}
}

function clearDiv() {
	document.getElementById("nameRow").style.display='none';
	document.getElementById("nameRow2").style.display='none';
	document.getElementById("resultRow").style.display='none';

}
function getList() {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/listOptionalSubject.php';
	var pars = generateQueryString('optionalSubjectForm');
	var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('studentName','Student Name','width=15%',true),
						new Array('universityRollNo','University Roll No.','width=15%',true),
						new Array('rollNo','Roll No.','width=10%',true),
						new Array('subjectCode','Optional Subject','width=20%',true),
						new Array('groupName','Group','width=8%',true),
						new Array('parentSubject','Optional Subject Parent','width=20%',false)
						//new Array('day','Day','width=80%','',true),
						//new Array('period','Period','width=80%','',true)
                       );

	//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','studentName','ASC','resultsDiv','','',true,'listObj',tableColumns,'editWindow','',pars);
	sendRequest(url, listObj, '');
}

function getWithoutOptionalList() {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';

	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/listWithoutOptionalSubject.php';
	var pars = generateQueryString('optionalSubjectForm');
	var tableColumns = new Array(
                        new Array('srNo','#','width="5%" align="left"',false), 
                        new Array('studentName','Student Name','width=20%',true),
						new Array('universityRollNo','University Roll No.','width=15%',true),
						new Array('rollNo','Roll No.','width=15%',true),
						new Array('groupName','Group','width=20%',true)
						//new Array('day','Day','width=80%','',true),
						//new Array('period','Period','width=80%','',true)
                       );

	//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','studentName','ASC','resultsDiv','','',true,'listObj',tableColumns,'editWindow','',pars);
	sendRequest(url, listObj, '');
}

function getTimeTableClasses() {
	form = document.optionalSubjectForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
	var pars = 'labelId='+form.labelId.value;

	if (form.labelId.value=='') {
		form.degree.length = null;
		addOption(form.degree, '', 'Select');
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
			form.degree.length = null;
            addOption(form.degree, '', 'Select');    
			for(i=0;i<len;i++) {
				addOption(form.degree, j[i].classId, j[i].className);
			}
			// now select the value
			//form.degree.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


function printReport() {
	
	if(document.getElementById('option').value == "optional") {
		
	var pars = generateQueryString('optionalSubjectForm');
	path='<?php echo UI_HTTP_PATH;?>/optionalSubjectReportPrint.php?'+pars+'&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
	a = window.open(path,"OptionalSubject","status=1,menubar=1,scrollbars=1, width=900");
	}
	else if(document.getElementById('option').value == "withoutOptional") {
	var pars = generateQueryString('optionalSubjectForm');
	pars=pars+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
	path='<?php echo UI_HTTP_PATH;?>/compulsorySubjectReportPrint.php?'+pars;
	a = window.open(path,"CompulsorySubject","status=1,menubar=1,scrollbars=1, width=900");
	}
}

function printCSV() {
	if(document.getElementById('option').value == "optional") {
	var qstr = generateQueryString('optionalSubjectForm');
	qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
 window.location='<?php echo UI_HTTP_PATH;?>/optionalSubjectCSV.php?'+qstr;
	}
	else if(document.getElementById('option').value == "withoutOptional") {
		var qstr = generateQueryString('optionalSubjectForm');
		qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
		window.location='<?php echo UI_HTTP_PATH;?>/compulsorySubjectCSV.php?'+qstr;
	}
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

window.onload = function() {
	document.getElementById('labelId').focus();
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/optionalSubjectReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listOptionalSubjectReport.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/17/10    Time: 4:43p
//Created in $/LeapCC/Interface
//new file to show optional subject report
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/07/10    Time: 1:27p
//Updated in $/LeapCC/Interface
//show building & block 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/06/10    Time: 12:16p
//Created in $/LeapCC/Interface
//new interface file to show occupied/free classes/rooms
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/23/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0002338, 0002341, 0002336, 0002337
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/02/09    Time: 5:31p
//Created in $/LeapCC/Interface
//copy from sc for offense report
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/16/09    Time: 11:24a
//Updated in $/Leap/Source/Interface
//modification in code to show list of student of all classes with
//offenses
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/15/09    Time: 4:46p
//Created in $/Leap/Source/Interface
//new offense report against students
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 2:28p
//Created in $/Leap/Source/Interface
//copy from cc, get file functions for add, edit, delete & populate
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/02/09    Time: 4:34p
//Updated in $/LeapCC/Interface
//show some fields on list
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/02/09    Time: 1:20p
//Created in $/LeapCC/Interface
//new files for cleaning room
//
?>