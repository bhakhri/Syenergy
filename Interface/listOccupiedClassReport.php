<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Occupied/Free Report
//
//
// Author :Jaineesh
// Created on : 15.04.09
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','OccupiedFreeClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Occupied/Free Class(s)/Room(s) Wise Report </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="2%"','',false),
								new Array('className','class(s)','width=8%','',true)
							);

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/OccupiedClassReports/listOccupiedClassReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'classOccupiedForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'rollNo';
sortOrderBy    = 'ASC';

function validateAddForm(frm) {
    
	/*var fieldsArray = new Array(new Array("noOfOffense","<?php echo SELECT_NO_OFFENSE;?>"),
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
    }*/
	//openStudentLists(frm.name,'rollNo','Asc');    
		document.getElementById("nameRow").style.display='';
		document.getElementById("nameRow2").style.display='';
		document.getElementById("resultRow").style.display='';
		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
}

function getSelectedList() {

	if(document.getElementById('labelId').value == '') {
		messageBox("<?php echo SELECT_TIME_TABLE ?>");
		document.getElementById('labelId').focus();
		return false;
	}
	
	if(document.getElementById('periodSlot').value == '') {
		messageBox("<?php echo SELECT_PERIODSLOT ?>");
		document.getElementById('periodSlot').focus();
		return false;
	}

	if(document.getElementById('periods').value == '') {
		messageBox("<?php echo SELECT_PERIOD ?>");
		document.getElementById('periods').focus();
		return false;
	}

	if(document.getElementById('showReport').value == 'classwise') {
		getList();
	}
	else {
		getRoomList();
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

	var url = '<?php echo HTTP_LIB_PATH;?>/OccupiedClassReports/listOccupiedClassReport.php';
	var pars = generateQueryString('classOccupiedForm');
	var tableColumns = new Array(
                        new Array('srNo','#','width="5%" align="left"',false), 
                        new Array('className','Class(s)','width=30%','',true),
						new Array('groupName','Group','width=30%','',true),
						new Array('groupStudent','Group Student','width=20%','',true)
						//new Array('day','Day','width=80%','',true),
						//new Array('period','Period','width=80%','',true)
                       );

	//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','resultsDiv','','',true,'listObj',tableColumns,'editWindow','',pars);
	sendRequest(url, listObj, '');
}


function getRoomList() {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';

	var url = '<?php echo HTTP_LIB_PATH;?>/OccupiedClassReports/listOccupiedClassReport.php';
	var pars = generateQueryString('classOccupiedForm');
	var tableColumns = new Array(
                        new Array('srNo','#','width="5%" align="left"',false), 
                        new Array('roomName','Room(s)','width=20%','',true),
						new Array('buildingName','Building','width=20%','',true),
						new Array('blockName','Block','width=20%','',true),
						new Array('capacity','Capacity','width=10% align=right','',true),
						new Array('examCapacity','Exam Capacity','width=10% align=right','',true)
                       );

	//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
	listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','roomName','ASC','resultsDiv','','',true,'listObj',tableColumns,'editWindow','',pars);
	sendRequest(url, listObj, '');
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getPeriods() {
	form = document.classOccupiedForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/OccupiedClassReports/getNumbers.php';
	var pars = 'periodSlotId='+form.periodSlot.value;
	if (form.periodSlot.value=='') {
		form.periods.length = null;
		addOption(form.periods, '', 'Select');
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
			if(j==0) {
				form.periods.length = null;
				addOption(form.periods, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.periods.length = null;
			addOption(form.periods, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.periods, j[i].periodId, j[i].periodNumber);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
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

window.onload = function() {
	//alert(document.classOccupiedForm.labelId.value);

	 url = '<?php echo HTTP_LIB_PATH;?>/OccupiedClassReports/ajaxGetTimeTableType.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {timeTableLabelId: document.classOccupiedForm.labelId.value},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
                        messageBox("<?php echo TIME_TABLE_LABEL_NOT_EXIST;?>");
						return false;
                   }
                    j = eval('('+transport.responseText+')');
					if(j.timeTableType == 2) {
						document.getElementById('dateSpan').style.display = '';
						document.getElementById('makeDateSpan').style.display = '';
					}
					else {
						document.getElementById('daySpan').style.display = '';
						document.getElementById('makeDaySpan').style.display = '';
					}
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });


}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/OccupiedClassReports/listOccupiedClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

////$History: listOccupiedClassReport.php $
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/17/10    Time: 5:29p
//Updated in $/LeapCC/Interface
//fixed bug nos.0003287, 0003286, 0003290
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
//
?>