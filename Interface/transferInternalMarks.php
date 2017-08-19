<?php 
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 18-Mar-2009
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransferInternalMarks');
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
var listURL='<?php echo HTTP_LIB_PATH;?>/Student/initTransferInternalMarks.php';
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
				res = trim(transport.responseText);
				//displayWindow('marksTransfer',500,250);
				if (res == "<?php echo SUCCESS;?>") {
					document.getElementById('marksTransferMessage').innerHTML = '';
					messageBox("<?php echo SUCCESS;?>");
				}
				else {
					//
                   /* 
					if (document.marksNotEnteredForm.errors.value == 'screen') {
						document.getElementById('marksTransferMessage').innerHTML = res;
					}
					else {
						document.getElementById('marksTransferMessage').innerHTML = '';
						window.location = '<?php echo HTTP_PATH;?>/Templates/Xml/marksTransferIssues.doc';
					}
                    */
                    if (document.marksNotEnteredForm.errors[0].checked==true) {
                        document.getElementById('marksTransferMessage').innerHTML = res;
                    }
                    else {
                        document.getElementById('marksTransferMessage').innerHTML = '';
                        window.location = '<?php echo HTTP_PATH;?>/Templates/Xml/marksTransferIssues.doc';
                    }
				}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}


function getClassesForTransfer() {
	form = document.marksNotEnteredForm;
	form.class1.length = null;
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getClassesForTransfer.php';
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
					
					addOption(form.elements['class1[]'], j[i]['classId'], j[i]['className']);
				}
				// now select the value
				form.class1.value = '';
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

var initialTextForMultiDropDowns = 'Click to select multiple degrees';
var selectTextForMultiDropDowns  = 'degree';
window.onload = function() {
	document.getElementById('labelId').focus();
    makeDDHide('class1','d2','d3');
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listTransferInternalMarks.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: transferInternalMarks.php $
//
//*****************  Version 10  *****************
//User: Ajinder      Date: 3/29/10    Time: 5:15p
//Updated in $/LeapCC/Interface
//fixed bugs. FCNS No. 1483
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 29/12/09   Time: 18:42
//Updated in $/LeapCC/Interface
//Corrected "Multiple dropdowns" look 
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 18/12/09   Time: 17:42
//Updated in $/LeapCC/Interface
//Made UI changes in transfer internal marks module
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/Interface
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/20/09   Time: 4:15p
//Updated in $/LeapCC/Interface
//done changes related to transfer marks - initial checkin
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 11/17/09   Time: 6:53p
//Updated in $/LeapCC/Interface
//done changes for marks transfer
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:23a
//Updated in $/LeapCC/Interface
//fixed bug no.1203
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:55p
//Updated in $/LeapCC/Interface
//done cosmetic changes
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/18/09    Time: 12:43p
//Created in $/LeapCC/Interface
//file added for transfer of internal marks
//






?>
