<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Offense Report
//
//
// Author :Jaineesh
// Created on : 15.04.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OffenseReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Offense Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('rollNo','Roll No.','width=8%','',true),
								new Array('studentName','Student Name','width=10%','',true),
								new Array('studentMobileNo','Mobile No.','width="8%"','align=right',true),
								new Array('studentEmail','Email','width="8%"','align=left',true),
								new Array('totalOffenses','No. of Offenses','width="10%"','align=right',true),
								new Array('viewDetail','Action','width="10%"','align=center',false)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/Offense/initOffenseListReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'OffenseReportForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
    var fieldsArray = new Array(new Array("noOfOffense","<?php echo SELECT_NO_OFFENSE;?>"),
								new Array("instances","<?php echo SELECT_INSTANCE;?>")
								);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		else if(fieldsArray[i][0]=="instances") {
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
				}
            }
    }
	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}


function printReport(studentId,classId,offenseId) {
	form = document.OffenseReportForm;
	path='<?php echo UI_HTTP_PATH;?>/offenseReportPrint.php?studentId='+studentId+'&classId='+classId+'&offenseId='+offenseId;
	a = window.open(path,"OffenseReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printOffenseReport() {
	form = document.OffenseReportForm;
	path='<?php echo UI_HTTP_PATH;?>/offenseListReportPrint.php?noOfOffense='+document.OffenseReportForm.noOfOffense.value+'&instances='+document.OffenseReportForm.instances.value+'&offenseCategory='+document.OffenseReportForm.offenseCategory.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	a = window.open(path,"OffenseReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    var qstr="noOfOffense="+trim(document.OffenseReportForm.noOfOffense.value);
    qstr=qstr+"&instances="+document.OffenseReportForm.instances.value+"&offenseCategory="+document.OffenseReportForm.offenseCategory.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/offenseListReportCSV.php?'+qstr;
	window.location = path;
}

function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Offense/listOffenseReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listOffenseReport.php $
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