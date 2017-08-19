<?php
//-------------------------------------------------------
//  This File contains starting code for marks transfer
//
//
// Author :Ajinder Singh
// Created on : 28-Dec-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransferInternalMarksAdvanced');
define('ACCESS','add');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transfer Internal Marks (Advanced) </title>
<style>
.htmlElementGroup
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	height:20px;
	width:120px;
}
</style>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="5%" align=left','align=left',false), new Array('className','Class','width=25%  align=left',' align=left',false), new Array('subjectCode','Subject','width="20%"  align=left',' align=left',false), new Array('groupName','Group','width="10%"  align=left',' align=left',false), new Array('employeeName','Faculty','width="20%"  align=left',' align=left',false), new Array('testName','Test Name','width="20%"  align=left',' align=left',false));

 //This function Validates Form
var listURL='<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/initTransferInternalMarks.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'transferMarksForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'Asc';
 //This function Validates Form
currentProcess = '';

var resourceAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

function addDetailRows(value){
	var tbl=document.getElementById('anyid');
	var tbody = document.getElementById('anyidBody');
	//var tblB    = document.createElement("tbody");
	if(!isInteger(value)){
		return false;
	}

	if(resourceAddCnt>0){     //if user reenter no of rows
		cleanUpTable();
	}
	resourceAddCnt=parseInt(value);
	createRows(0,resourceAddCnt,0);
}

function deleteRow(value) {
	var rval=value.split('~');
	var tbody1 = document.getElementById('anyidBody');
	var tr=document.getElementById('row'+rval[0]);
	tbody1.removeChild(tr);

	if(isMozilla) {
		if((tbody1.childNodes.length-2)==0){
		resourceAddCnt=0;
		}
	}
	else {
		if((tbody1.childNodes.length-1)==0) {
			resourceAddCnt=0;
		}
	}
}


//to add one row at the end of the list
function addOneRow(cnt,whereToAdd) {
	//set value true to check that the records were retrieved but not posted bcos user marked them deleted
	document.getElementById('deleteFlag').value=true;

	if(cnt=='')
	cnt=1;
	if(isMozilla) {
		if(document.getElementById('anyidBody').childNodes.length <= 1){
		resourceAddCnt=0;
		}
	}
	else {
		if(document.getElementById('anyidBody').childNodes.length <= 1){
		resourceAddCnt=0;
		}
	}
	resourceAddCnt++;
	//createRows(resourceAddCnt,cnt,eval("document.getElementById('teacher').innerHTML"),eval("document.getElementById('studentGroup').innerHTML"),eval("document.getElementById('room').innerHTML"));
	if (whereToAdd == 'percent') {
		createRowsPercent(resourceAddCnt,cnt);
	}
	else if (whereToAdd == 'slabs') {
		createRowsSlabs(resourceAddCnt,cnt);
	}
}



function createRowsSlabs(start,rowCnt){
       // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');


     for(var i=0;i<rowCnt;i++){
      var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));

      var cell1=document.createElement('td');
      var cell2=document.createElement('td');
      var cell3=document.createElement('td');
      var cell4=document.createElement('td');
      var cell5=document.createElement('td');
	  var cell6=document.createElement('td');

      cell1.setAttribute('align','center');
      cell2.setAttribute('align','left');
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','left');
      cell5.setAttribute('align','left');
	  cell6.setAttribute('align','center');

      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
     // var txt0=document.createTextNode(i+1);

      var txt1=document.createElement('input');
      var txt2=document.createElement('input');
      var txt3=document.createElement('input');
	  var txt4=document.createElement('input');
      var txt5=document.createElement('a');


      txt1.setAttribute('id','lectureDelivered'+parseInt(start+i,10));
      txt1.setAttribute('name','lectureDelivered[]');
      //txt1.setAttribute('onBlur','valInteger(this)');
      txt1.className='inputbox1';
      txt1.setAttribute('size','"5"');
      txt1.setAttribute('maxlength','"3"');
      //txt4.onBlur='isIntegerComma(this)';
      txt1.setAttribute('type','text');

      txt2.setAttribute('id','lectureAttendedFrom'+parseInt(start+i,10));
      txt2.setAttribute('name','lectureAttendedFrom[]');
      //txt2.setAttribute('onBlur','valInteger(this)');
      txt2.setAttribute('maxlength','"3"');
      txt2.className='inputbox1';
      txt2.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt2.setAttribute('type','text');

	  txt3.setAttribute('id','lectureAttendedTo'+parseInt(start+i,10));
      txt3.setAttribute('name','lectureAttendedTo[]');
      //txt3.setAttribute('onBlur','valInteger(this)');
      txt3.setAttribute('maxlength','"3"');
      txt3.className='inputbox1';
      txt3.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt3.setAttribute('type','text');

      txt4.setAttribute('id','marksScored'+parseInt(start+i,10));
      txt4.setAttribute('name','marksScored[]');
      //txt4.setAttribute('onBlur','valDecimal(this)');
      txt4.setAttribute('maxlength','"6"');
      txt4.className='inputbox1';
      txt4.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt4.setAttribute('type','text');

      txt5.setAttribute('id','rd');
      txt5.className='inputbox1';
      txt5.setAttribute('title','Delete');
      txt5.innerHTML='X';
      txt5.style.cursor='pointer';
      txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff


      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);
	  cell6.appendChild(txt5);

      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);
	  tr.appendChild(cell6);

      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;

      tbody.appendChild(tr);
     }
     tbl.appendChild(tbody);
}

//to clean up table rows
function cleanUpTable() {
	var tbody = document.getElementById('anyidBody');
	for(var k=0;k<=resourceAddCnt;k++) {
		try{
			tbody.removeChild(document.getElementById('row'+k));
		}
		catch(e){
			//alert(k);  // to take care of deletion problem
		}
	}
}

var bgclass='';
function createRowsPercent(start,rowCnt){
        //alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');


     for(var i=0;i<rowCnt;i++){
      var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));

      var cell1=document.createElement('td');
      var cell2=document.createElement('td');
      var cell3=document.createElement('td');
      var cell4=document.createElement('td');
      var cell5=document.createElement('td');

      cell1.setAttribute('align','center');
      cell2.setAttribute('align','center');
      cell3.setAttribute('align','center');
      cell4.setAttribute('align','center');
      cell5.setAttribute('align','center');

      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
     // var txt0=document.createTextNode(i+1);

      var txt1=document.createElement('input');
      var txt2=document.createElement('input');
      var txt3=document.createElement('input');
      var txt4=document.createElement('a');


      txt1.setAttribute('id','percentFrom'+parseInt(start+i,10));
      txt1.setAttribute('name','percentFrom[]');
      //txt1.setAttribute('onBlur','valInteger(this)');
      txt1.className='inputbox1';
      txt1.setAttribute('size','"5"');
      txt1.setAttribute('maxlength','"3"');
      //txt4.onBlur='isIntegerComma(this)';
      txt1.setAttribute('type','text');

      txt2.setAttribute('id','percentTo'+parseInt(start+i,10));
      txt2.setAttribute('name','percentTo[]');
      //txt2.setAttribute('onBlur','valInteger(this)');
      txt2.setAttribute('maxlength','"3"');
      txt2.className='inputbox1';
      txt2.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt2.setAttribute('type','text');

      txt3.setAttribute('id','marksScored'+parseInt(start+i,10));
      txt3.setAttribute('name','marksScored[]');
      //txt3.setAttribute('onBlur','valDecimal(this)');
      txt3.setAttribute('maxlength','"6"');
      txt3.className='inputbox1';
      txt3.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt3.setAttribute('type','text');

      txt4.setAttribute('id','rd');
      txt4.className='inputbox1';
      txt4.setAttribute('title','Delete');
      txt4.innerHTML='X';
      txt4.style.cursor='pointer';
      txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff


      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);

      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);

      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;

      tbody.appendChild(tr);
     }
     tbl.appendChild(tbody);
}


function validateAddForm(form) {
	transferMarks();
}

function hideDetails() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}

function showDetails() {
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
}

function printReport() {
	form = document.transferMarksForm;
	path='<?php echo UI_HTTP_PATH;?>/marksNotEnteredReportPrint.php?class1='+form.class1.value;
	window.open(path,"MarksNotEnteredReport","status=1,menubar=1,scrollbars=1, width=900");
}



function getClassSubjects() {
	form = document.transferMarksForm;
	labelId = form.labelId.value;
	classId = form.class1.value;
	if (labelId == '') {
		return false;
	}
	if(classId == '') {
		messageBox("<?php echo SELECT_CLASS ?>");
		document.getElementById('class1').focus();
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getClassSubjectsTestTypes.php';
	pars = generateQueryString('transferMarksForm');
	class1 = form.class1.value;
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
			   allRes = trim(transport.responseText);
			   if (allRes == "<?php echo TRANSFER_PROCESS_ALREADY_RUNNING_IN_SAME_SESSION;?>") {
				   messageBox(allRes);
				   return false;
			   }
				var j = eval('(' + transport.responseText + ')');
				var totalSubjects = j['subjects'].length;
				var transferSubjects = j['transferSubjects'].length;


				var tableData = globalTB;
				document.getElementById('headingDiv').innerHTML = 'Subjects To Class Details:';
				tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text "><input type="checkbox" name="classSub" onClick="selClassSubjects();"></td><td width="2%" class="searchhead_text ">#</td><td width="5%" class="searchhead_text ">Code</td><td width="20%" class="searchhead_text ">Subject Name</td><td width="2%" class="searchhead_text">Subject Type</td><td width="5%" class="searchhead_text ">Int.+ Att. Marks</td><td width="5%" class="searchhead_text ">Optional</td><td width="5%" class="searchhead_text ">Major/Minor</td><td width="5%" class="searchhead_text ">Int. Test-Type Sum</td><td width="5%" class="searchhead_text ">Att. Test-Type Sum</td><td width="5%" class="searchhead_text ">View / Edit Test Type</td></tr>';

				if (totalSubjects == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					tableData += '<tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>'+noDataFoundVar+'</td></tr>';
				}
				else {
					for(i=0;i<totalSubjects;i++) {
						rowCtr = i + 1;
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						subjectId = j['subjects'][i]['subjectId'];
						subjectCode = j['subjects'][i]['subjectCode'];
						subjectName = j['subjects'][i]['subjectName'];
						internalTotalMarks = j['subjects'][i]['internalTotalMarks'];
						externalTotalMarks = j['subjects'][i]['externalTotalMarks'];
						optional = j['subjects'][i]['optional'];
						hasParentCategory = j['subjects'][i]['hasParentCategory'];
						subjectType = j['subjects'][i]['subjectType'];
						internalTestTypeSum = j['subjects'][i]['internalTestTypeSum'];
						externalTestTypeSum = j['subjects'][i]['externalTestTypeSum'];
						attendanceTestTypeSum = j['subjects'][i]['attendanceTestTypeSum'];
						optionalShow = 'No';
						if (optional == '1' || optional == 1) {
							optionalShow = 'Yes';
						}
						hasParentCategoryShow = 'No';
						if (hasParentCategory == '1' || hasParentCategory == 1) {
							hasParentCategoryShow = 'Yes';
						}

						trfChecked = "";
						for (trfSub = 0; trfSub < transferSubjects; trfSub++) {
							trfSubjId = j['transferSubjects'][trfSub];
							if (subjectId == trfSubjId) {
								trfChecked = " checked ";
								break;
							}
						}


						tableData += '<td align="left" valign="middle"><input type="checkbox" '+trfChecked+' name="classSubjects[]" value="'+subjectId+'" />&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+rowCtr+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectCode+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectName+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectType+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="right">'+internalTotalMarks+'&nbsp;</td>';
						//tableData += '<td class="padding_top"  align="right">'+externalTotalMarks+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+optionalShow+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+hasParentCategoryShow+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="right">'+internalTestTypeSum+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="right">'+attendanceTestTypeSum+'&nbsp;</td>';
						//tableData += '<td class="padding_top"  align="right">'+externalTestTypeSum+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="right"><img src="<?php echo STORAGE_HTTP_PATH;?>/Images/edit2.gif" onClick=\'showTestTypes('+class1+','+subjectId+')\'">&nbsp;&nbsp;</td>';
						tableData += '</tr>';
					}
				}
				tableData += "</table>";
				currentProcess = "showClassSubjects";
				xData = showButtonTable();
				tableData += xData;

				showDetails();
				document.getElementById("resultsDiv").innerHTML = tableData;
				document.getElementById('mainFormTR').style.display = 'none';
				document.getElementById('currentSelectionTR').style.display = '';
				document.getElementById('currentSelectionTR2').style.display = '';
				document.getElementById('currentSelectionTR3').style.display = '';
				document.getElementById('currentSelectionTR4').style.display = '';
				document.getElementById('currentSelectionTR5').style.display = '';
				currentSelectionTRData = '<b>Time Table&nbsp;:&nbsp;</b>';
				currentSelectionTRData2 = ''+ form.labelId.options[form.labelId.selectedIndex].text;
				currentSelectionTRData3 = '&nbsp;&nbsp;<strong>Class&nbsp;:&nbsp;</strong>';
				currentSelectionTRData4 = ''+ form.class1.options[form.class1.selectedIndex].text;
				currentSelectionTRData5 =  "<input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/change.gif' onClick='return showMainTable();return false;'/>";
				document.getElementById('currentSelectionTR').innerHTML = currentSelectionTRData;
				document.getElementById('currentSelectionTR2').innerHTML = currentSelectionTRData2;
				document.getElementById('currentSelectionTR3').innerHTML = currentSelectionTRData3;
				document.getElementById('currentSelectionTR4').innerHTML = currentSelectionTRData4;
				document.getElementById('currentSelectionTR5').innerHTML = currentSelectionTRData5;

		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
		changeColor(currentThemeId);
}

function showMainTable() {
	document.getElementById('currentSelectionTR').innerHTML = '';
	document.getElementById('currentSelectionTR').style.display = 'none';
	document.getElementById('currentSelectionTR2').style.display = '';
	document.getElementById('currentSelectionTR2').style.display = 'none';
	document.getElementById('currentSelectionTR3').style.display = '';
	document.getElementById('currentSelectionTR3').style.display = 'none';
	document.getElementById('currentSelectionTR4').style.display = '';
	document.getElementById('currentSelectionTR4').style.display = 'none';
	document.getElementById('currentSelectionTR5').style.display = '';
	document.getElementById('currentSelectionTR5').style.display = 'none';
	document.getElementById('mainFormTR').style.display = '';
	hideDetails();
	stopTransferProcess();
}

function stopTransferProcess() {
	form = document.transferMarksForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/stopTransferProcess.php';
	pars = generateQueryString('transferMarksForm');
	class1 = form.class1.value;
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
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function checkSubjects() {
	noSubjectSelected = true;
	for(var i=1;i<form.length;i++){
		if(form.elements[i].type=="checkbox" && form.elements[i].name=="classSubjects[]" && form.elements[i].checked == true){
			return true;
		}
	}
	messageBox("<?php echo SELECT_ATLEAST_ONE_SUBJECT;?>");
	return false;
}

//for making initial attendance set percent screen
function getAttendanceMarksPercent() {
	if (currentProcess == "showClassSubjects") {
		if (false == checkSubjects()) {
			return false;
		}
	}
	currentProcess = "attendanceMarksPercent";

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/ajaxAttendanceMarksPercent.php';

	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');
	//pars += '&currentProcess='+currentProcess;

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
			   res = trim(transport.responseText);
			   if (res.indexOf("No Subject") != -1) {
				   messageBox(res);
			   }
			   else {
				var attMarksPer = eval('(' + transport.responseText + ')');
				var totalSubjects = attMarksPer['subjects'].length;
				document.getElementById('headingDiv').innerHTML = 'Attendance Marks Percent Details:';
				//tableData = "<table width='100%'><tr><td width='50%' >";
				var tableData = globalTB;
				tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text"><input type="checkbox" name="attPerSub" onClick="selAttPerSub();" /></td><td width="2%" class="searchhead_text ">#</td><td width="5%" class="searchhead_text ">Code</td><td width="20%" class="searchhead_text ">Subject Name</td><td width="5%" class="searchhead_text ">Att. Weightage</td><td width="5%" class="searchhead_text" nowrap>Attendance Set</td></tr>';

				if (totalSubjects == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					tableData += '<tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>'+noDataFoundVar+'</td></tr>';
				}
				else {
					for(i=0;i<totalSubjects;i++) {
						rowCtr = i + 1;
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						subjectId = attMarksPer['subjects'][i]['subjectId'];
						subjectCode = attMarksPer['subjects'][i]['subjectCode'];
						subjectName = attMarksPer['subjects'][i]['subjectName'];
						weightageAmount = attMarksPer['subjects'][i]['weightageAmount'];
						attendanceSetName = attMarksPer['subjects'][i]['attendanceSetName'];

						if (attendanceSetName == "null" || attendanceSetName == null) {
							attendanceSetName = "--";
						}


						tableData += '<td class="padding_top"  align="left"><input type="checkbox" name="attPerSubjects[]" value="'+subjectId+'" />&nbsp;&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+rowCtr+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectCode+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectName+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+weightageAmount+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+attendanceSetName+'&nbsp;</td>';
						tableData += '</tr>';
					}

					var totalAttendanceSets = attMarksPer['attendanceSets'].length;
					if (totalAttendanceSets > 0) {
						tableData += "<tr>";
						tableData += "<td height='20' colspan='3' style='vertical-align:middle;' nowrap>Apply &nbsp;<select name='attendanceSetPercentToApply' class='inputbox1' style='width:200px;'>";
						tableData += '<option  value="">Select</option>';
						for (m=0; m < totalAttendanceSets; m++) {
							var attendanceSetId = attMarksPer['attendanceSets'][m]['attendanceSetId'];
							var attendanceSetName = attMarksPer['attendanceSets'][m]['attendanceSetName'];
							tableData += '<option value="'+attendanceSetId+'">'+attendanceSetName+'</option>';
						}
						tableData += "</select>";
						tableData += "</td><td height='20' colspan='3' style='vertical-align:middle;'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/save.gif' onClick='return applyAttendancePercentSet();return false;'/>";
						tableData += "</td>";
						tableData += "</tr>";
					}
				}
				tableData += "</table>";
				var totalAttendanceSets = attMarksPer['attendanceSets'].length;

				tableData += "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
				tableData += "<tr>";
				tableData += "<td height='20'>";
				tableData += "<table width='100%' border='0' cellspacing='0' cellpadding='0' height='20'  class='contenttab_border'>";
				tableData += "<tr>";
				tableData += "<td colspan='1' class='content_title'>Attendance Set [Percent] Details:</td>";
				tableData += "<td colspan='1' class='content_title' align='right'></td>";
				tableData += "</tr></table>";
				tableData += "</td></tr>";
				tableData += "<tr><td colspan='1' class='contenttab_row'>";
				tableData += "<table border='0'><tr><td width='130' class=contenttab_internal_rows'><b>Attendance Set</b></td>";
				tableData += "<td><b>&nbsp;:&nbsp;</b><select name='attendanceSetPercent' class='inputbox1' style='width:300px;' onChange='cleanUpTable();getAttendanceSetPercentRowDetails();'>";
				tableData += '<option  value="">Select</option>';
				for (m=0; m < totalAttendanceSets; m++) {
					var attendanceSetId = attMarksPer['attendanceSets'][m]['attendanceSetId'];
					var attendanceSetName = attMarksPer['attendanceSets'][m]['attendanceSetName'];
					tableData += '<option value="'+attendanceSetId+'">'+attendanceSetName+'</option>';
				}
				tableData += '<option  value="CNP">Create New</option>';
				tableData += "</select>";
				tableData += "</td><td><span id='attendancePercentButtonTop'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return showAttendanceSetPercentDetails();return false;' /></span></td></tr><tr id='attendanceSetPercentRow' style='display:none;'><td class=contenttab_internal_rows'><b>Attendance Set Name</b></td><td><b>&nbsp;:&nbsp;</b><input type='text' class='inputbox1' name='attendanceSetPercentName' style='width:300px;' maxlength='100' /></td><td><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return showAttendanceSetPercentDetails();return false;' /></td></table>";

				tableData += "<table width='100%' border='0' cellspacing='2' cellpadding='0' id='anyid'><tbody id='anyidBody'><tr class='rowheading'><td width='5%' class='searchhead_text'><b>Sr.</b></td><td width='20%' class='searchhead_text' align='center'><b>Percent From</b></td><td width='20%' class='searchhead_text' align='center'><b>Percent To</b></td><td width='20%' class='searchhead_text'  align='center'><b>Marks</b><td  class='searchhead_text' width='5%'  align='center'><b>Remove</b></td></tr></tbody></table><input type='hidden' name='deleteFlag' id='deleteFlag' value='' /><div id='addRowDiv' style='display:none;'><table width='100%' border='0' cellspacing='2' cellpadding='0'><tr><td align='left'><h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href='javascript:addOneRow(1,\"percent\");' title='Add One Row'><b>+</b></a></h3></td><td align='right'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/save.gif' onClick='return saveAttendanceSetPercentDetails();return false;' /></td></tr></table></div>";
				xData = showButtonTable();
				tableData += xData;
				showDetails();
				document.getElementById("resultsDiv").innerHTML = tableData;

			   }
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
		changeColor(currentThemeId);
}

function applyAttendancePercentSet() {
	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/applyAttendanceSetPercent.php';

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
			   res = trim(transport.responseText);
			   messageBox(res);
			   if (res == "<?php echo SUCCESS;?>") {
				getAttendanceMarksPercent();
			   }

		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
		changeColor(currentThemeId);


}


function getAttendanceSetPercentRowDetails() {
	tmform = document.transferMarksForm;
	if (tmform.attendanceSetPercent.value == '') {
		document.getElementById('attendanceSetPercentRow').style.display = 'none';
		hideAttendanceSetPercentDetails();
		return false;
	}
	if (tmform.attendanceSetPercent.value == 'CNP') {
		document.getElementById('attendanceSetPercentRow').style.display = '';
		document.getElementById('attendancePercentButtonTop').innerHTML = '';
		tmform.attendanceSetPercentName.focus();
		hideAttendanceSetPercentDetails();
	}
	else {
		tmform.attendanceSetPercentName.value = '';
		document.getElementById('attendanceSetPercentRow').style.display = 'none';
		document.getElementById('attendancePercentButtonTop').innerHTML = "<input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return getAttendanceSetPercentDetails();return false;' />";
	}
   changeColor(currentThemeId);
	return false;
}

//for filling the attendance set percent data
function getAttendanceSetPercentDetails() {
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getAttendanceSetPercentDetails.php';
	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}

	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');
	callSetDetails = false;

	//alert(pars);

	cleanUpTable();

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
			   res = trim(transport.responseText);
               attSetPerDetails = eval('('+trim(transport.responseText)+')');
               len = attSetPerDetails.attendancePercentArr.length;

                if(len>0) {
                    addOneRow(len,"percent");
                    resourceAddCnt=len;
                    //document.getElementById('trAttendance').style.display='';
                    for(i=0;i<len;i++) {
                        varFirst = i+1;
                        perFrom = 'percentFrom'+varFirst;
                        perTo = 'percentTo'+varFirst;
                        marksScored = 'marksScored'+varFirst;
                        eval("document.getElementById(perFrom).value = attSetPerDetails['attendancePercentArr'][i]['percentFrom']");
                        eval("document.getElementById(perTo).value = attSetPerDetails['attendancePercentArr'][i]['percentTo']");
                        eval("document.getElementById(marksScored).value = attSetPerDetails['attendancePercentArr'][i]['marksScored']");
								callSetDetails = true;
                   }
               }
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
		if (callSetDetails == true) {
			showAttendanceSetPercentDetails();
		}
		 changeColor(currentThemeId);
}


function saveAttendanceSetPercentDetails() {

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/saveAttendanceSetPercent.php';

	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

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
			 saveAttendanceMarksPercentRes = trim(transport.responseText);
			 messageBox(saveAttendanceMarksPercentRes);
			 if (saveAttendanceMarksPercentRes == "<?php echo SUCCESS;?>") {
				getAttendanceMarksPercent();
			 }
			 changeColor(currentThemeId);
	   },
	   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}


function showAttendanceSetPercentDetails() {
	tmform = document.transferMarksForm;
	if (tmform.attendanceSetPercent.value == '') {
		document.getElementById('addRowDiv').style.display = 'none';
		messageBox("<?php echo NO_OPTION_SELECTED;?>");
		return false;
	}
	document.getElementById('addRowDiv').style.display = '';
}

function hideAttendanceSetPercentDetails() {
	document.getElementById('addRowDiv').style.display = 'none';
}

function selAttPerSub() {
	form = document.transferMarksForm;
	if(form.attPerSub.checked){
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox" && form.elements[i].name=="attPerSubjects[]"){
				form.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox"){
				form.elements[i].checked=false;
			}
		}
	}
}

function selTrfSubjects() {
	form = document.transferMarksForm;
	if(form.trfSubChk.checked){
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox" && form.elements[i].name=="transferSubjects[]"){
				form.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox"){
				form.elements[i].checked=false;
			}
		}
	}
}


function getAttendanceMarksSlabs() {
	if (currentProcess == "showClassSubjects") {
		if (false == checkSubjects()) {
			return false;
		}
	}

	currentProcess = "attendanceMarksSlabs";

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getAttendanceMarksSlabs.php';

	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');
	//pars += '&currentProcess='+currentProcess;

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
			   res = trim(transport.responseText);
			   if (res == "<?php echo NO_SUBJECT_FOUND_FOR_ATTENDANCE_MARKS_SLABS;?>") {
				   messageBox(res);
			   }
			   else {
				var attMarksPer = eval('(' + transport.responseText + ')');
				var totalSubjects = attMarksPer['subjects'].length;
				document.getElementById('headingDiv').innerHTML = 'Attendance Marks Slab Details:';
				//tableData = "<table width='100%'><tr><td width='50%' >";
				var tableData = globalTB;
				tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text"><input type="checkbox" name="attSlabSub" onClick="selAttSlabSub();" /></td><td width="2%" class="searchhead_text ">#</td><td width="5%" class="searchhead_text ">Code</td><td width="20%" class="searchhead_text ">Subject Name</td><td width="5%" class="searchhead_text ">Att. Weightage</td><td width="5%" class="searchhead_text" nowrap>Attendance Set</td></tr>';

				if (totalSubjects == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					tableData += '<tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>'+noDataFoundVar+'</td></tr>';
				}
				else {
					for(i=0;i<totalSubjects;i++) {
						rowCtr = i + 1;
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						subjectId = attMarksPer['subjects'][i]['subjectId'];
						subjectCode = attMarksPer['subjects'][i]['subjectCode'];
						subjectName = attMarksPer['subjects'][i]['subjectName'];
						weightageAmount = attMarksPer['subjects'][i]['weightageAmount'];
						attendanceSetName = attMarksPer['subjects'][i]['attendanceSetName'];

						if (attendanceSetName == "null" || attendanceSetName == null) {
							attendanceSetName = "--";
						}


						tableData += '<td class="padding_top"  align="left"><input type="checkbox" name="attSlabSubjects[]" value="'+subjectId+'" />&nbsp;&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+rowCtr+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectCode+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectName+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+weightageAmount+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+attendanceSetName+'&nbsp;</td>';
						tableData += '</tr>';
					}

					var totalAttendanceSets = attMarksPer['attendanceSets'].length;
					if (totalAttendanceSets > 0) {
						tableData += "<tr>";
						tableData += "<td height='20' colspan='3' style='vertical-align:middle;' nowrap>Apply &nbsp;<select name='attendanceSetSlabToApply' class='inputbox1' style='width:200px;'>";
						tableData += '<option  value="">Select</option>';
						for (m=0; m < totalAttendanceSets; m++) {
							var attendanceSetId = attMarksPer['attendanceSets'][m]['attendanceSetId'];
							var attendanceSetName = attMarksPer['attendanceSets'][m]['attendanceSetName'];
							tableData += '<option value="'+attendanceSetId+'">'+attendanceSetName+'</option>';
						}
						tableData += "</select>";
						tableData += "</td><td height='20' colspan='3' style='vertical-align:middle;'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/save.gif' onClick='return applyAttendanceSlabSet();return false;'/>";
						tableData += "</td>";
						tableData += "</tr>";
					}
				}
				tableData += "</table>";
				var totalAttendanceSets = attMarksPer['attendanceSets'].length;

				tableData += "<table width='100%' border='0' cellspacing='0' cellpadding='0'>";
				tableData += "<tr>";
				tableData += "<td height='20'>";
				tableData += "<table width='100%' border='0' cellspacing='0' cellpadding='0' height='20'  class='contenttab_border'>";
				tableData += "<tr>";
				tableData += "<td colspan='1' class='content_title'>Attendance Set [Slab] Details:</td>";
				tableData += "<td colspan='1' class='content_title' align='right'></td>";
				tableData += "</tr></table>";
				tableData += "</td></tr>";
				tableData += "<tr><td colspan='1' class='contenttab_row'>";
				tableData += "<table border='0'><tr><td width='130' class=contenttab_internal_rows'><b>Attendance Set</b></td>";
				tableData += "<td><b>&nbsp;:&nbsp;</b><select name='attendanceSetSlab' class='inputbox1' style='width:300px;' onChange='cleanUpTable();getAttendanceSetSlabRowDetails();'>";
				tableData += '<option  value="">Select</option>';
				for (m=0; m < totalAttendanceSets; m++) {
					var attendanceSetId = attMarksPer['attendanceSets'][m]['attendanceSetId'];
					var attendanceSetName = attMarksPer['attendanceSets'][m]['attendanceSetName'];
					tableData += '<option value="'+attendanceSetId+'">'+attendanceSetName+'</option>';
				}
				tableData += '<option  value="CNP">Create New</option>';
				tableData += "</select>";
				tableData += "</td><td><span id='attendanceSlabButtonTop'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return showAttendanceSetSlabDetails();return false;' /></span></td></tr><tr id='attendanceSetSlabRow' style='display:none;'><td class=contenttab_internal_rows'><b>Attendance Set Name</b></td><td><b>&nbsp;:&nbsp;</b><input type='text' class='inputbox1' name='attendanceSetSlabName' style='width:300px;' maxlength='100' /></td><td><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return showAttendanceSetSlabDetails();return false;' /></td></table>";

				tableData += "<table width='100%' border='0' cellspacing='2' cellpadding='0' id='anyid'><tbody id='anyidBody'><tr class='rowheading'><td width='5%' class='searchhead_text'><b>Sr.</b></td><td width='20%' class='searchhead_text' align='center'><b>Lect. Delivered</b></td><td width='20%' class='searchhead_text' align='center'><b>Attended From</b></td><td width='20%' class='searchhead_text' align='center'><b>Attended To</b></td><td width='20%' class='searchhead_text'  align='center'><b>Marks</b><td  class='searchhead_text' width='5%'  align='center'><b>Remove</b></td></tr></tbody></table><input type='hidden' name='deleteFlag' id='deleteFlag' value='' /><div id='addRowDiv' style='display:none;'><table width='100%' border='0' cellspacing='2' cellpadding='0'><tr><td align='left'><h3>&nbsp;&nbsp;Add Rows:&nbsp;&nbsp;<a href='javascript:addOneRow(1,\"slabs\");' title='Add One Row'><b>+</b></a></h3></td><td align='right'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/save.gif' onClick='return saveAttendanceSetSlabDetails();return false;' /></td></tr></table></div>";
				xData = showButtonTable();
				tableData += xData;
				showDetails();
				document.getElementById("resultsDiv").innerHTML = tableData;
				changeColor(currentThemeId);
			   }
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


function applyAttendanceSlabSet() {
	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/applyAttendanceSetSlab.php';

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
			   res = trim(transport.responseText);
			   messageBox(res);
			   if (res == "<?php echo SUCCESS;?>") {
				getAttendanceMarksSlabs();
			   }
			   changeColor(currentThemeId);
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}

function getAttendanceSetSlabRowDetails() {
	tmform = document.transferMarksForm;
	if (tmform.attendanceSetSlab.value == '') {
		document.getElementById('attendanceSetSlabRow').style.display = 'none';
		hideAttendanceSetSlabDetails();
		return false;
	}
	if (tmform.attendanceSetSlab.value == 'CNP') {
		document.getElementById('attendanceSetSlabRow').style.display = '';
		document.getElementById('attendanceSlabButtonTop').innerHTML = '';
		tmform.attendanceSetSlabName.focus();
		hideAttendanceSetSlabDetails();
	}
	else {
		tmform.attendanceSetSlabName.value = '';
		document.getElementById('attendanceSetSlabRow').style.display = 'none';
		document.getElementById('attendanceSlabButtonTop').innerHTML = "<input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/show_list.gif' onClick='return getAttendanceSetSlabDetails();return false;' />";
	}
	return false;
}


//for filling the attendance set slab data
function getAttendanceSetSlabDetails() {
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getAttendanceSetSlabDetails.php';
	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}

	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

	//alert(pars);

	cleanUpTable();

	callSlabDetails = false;

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
			   res = trim(transport.responseText);
               attSetSlabDetails = eval('('+trim(transport.responseText)+')');
               len = attSetSlabDetails.lecturePercentArr.length;

                if(len>0) {
                    addOneRow(len,"slabs");
                    resourceAddCnt=len;
                    //document.getElementById('trAttendance').style.display='';
                    for(i=0;i<len;i++) {
                        varFirst = i+1;
                        lectureDelivered = 'lectureDelivered'+varFirst;
                        lectureAttendedFrom = 'lectureAttendedFrom'+varFirst;
						lectureAttendedTo = 'lectureAttendedTo'+varFirst;
                        marksScored = 'marksScored'+varFirst;
                        eval("document.getElementById(lectureDelivered).value = attSetSlabDetails['lecturePercentArr'][i]['lectureDelivered']");
                        eval("document.getElementById(lectureAttendedFrom).value = attSetSlabDetails['lecturePercentArr'][i]['lectureAttendedFrom']");
						eval("document.getElementById(lectureAttendedTo).value = attSetSlabDetails['lecturePercentArr'][i]['lectureAttendedTo']");
                        eval("document.getElementById(marksScored).value = attSetSlabDetails['lecturePercentArr'][i]['marksScored']");
                   }
					callSlabDetails = true;
               }
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
		if (callSlabDetails == true) {
		   showAttendanceSetSlabDetails();
		}
		changeColor(currentThemeId);
}


function showAttendanceSetSlabDetails() {
	tmform = document.transferMarksForm;
	if (tmform.attendanceSetSlab.value == '') {
		document.getElementById('addRowDiv').style.display = 'none';
		messageBox("<?php echo NO_OPTION_SELECTED;?>");
		return false;
	}
	document.getElementById('addRowDiv').style.display = '';
}

function hideAttendanceSetSlabDetails() {
	document.getElementById('addRowDiv').style.display = 'none';
}

function selAttSlabSub() {
	form = document.transferMarksForm;
	if(form.attSlabSub.checked){
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox" && form.elements[i].name=="attSlabSubjects[]"){
				form.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox"){
				form.elements[i].checked=false;
			}
		}
	}
}

function saveAttendanceSetSlabDetails() {

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/saveAttendanceSetSlabs.php';

	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

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
			 saveAttendanceMarksSlabRes = trim(transport.responseText);
			 messageBox(saveAttendanceMarksSlabRes);
			 if (saveAttendanceMarksSlabRes == "<?php echo SUCCESS;?>") {
				getAttendanceMarksSlabs();
			 }
			 changeColor(currentThemeId);
	   },
	   onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


  function transferMarks() {
	if(confirm("<?php echo MARKS_TRANSFER_CONFIRM;?>")) {
		tmform = document.transferMarksForm;
		ttForm = document.testTypeForm;
		labelId = form.labelId.value;
		if (labelId == '') {
			return false;
		}

		pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

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
				res = trim(transport.responseText);
				//displayWindow('marksTransfer',500,250);
				if(res.indexOf('<?php echo SUCCESS;?>') != -1) {
					document.getElementById('marksTransferMessage').innerHTML = '';

					resObj = eval('('+res+')');

					finalReportData = '<table border="0" cellpadding="1" cellspacing="1" width="100%"><tr><td class="marksTransferSuccess">&nbsp;&nbsp;Marks have been transferred successfully</td></tr></table><br><table border="0" cellpadding="1" cellspacing="1" width="100%"><tr><td  class="padding" align="right"><nobr><b>Show Marks</b></nobr></td><td class="padding">:</td><td><input type="radio" name="showMarks" id="showMarks1" checked="checked" />Weighted Marks [Marks propotionate to Test-type marks]&nbsp;<input type="radio" name="showMarks" id="showMarks2"  />Actual Marks</td></tr></table><br>';
					finalReportData += '<table border="0" cellpadding="1" cellspacing="1" width="100%" class="contenttab_border">';
					finalReportData += '<tr><td colspan="5" width="100%" class="content_title">View / Check Final Internal Report </td></tr><tr class="rowheading"><td class="searchhead_text">Subject Code</td><td colspan="2" class="searchhead_text">With Grace Marks</td><td colspan="2" class="searchhead_text">Without Grace Marks</td></tr>';
					resObjArray = resObj[1];
					for(tt = 0; tt < resObjArray.length; tt++) {
						subIdCodeArray = resObjArray[tt].split('#');
						subId = subIdCodeArray[0];
						subCode = subIdCodeArray[1];

						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						finalReportData += '<tr '+bg+'>';
						finalReportData += '<td width="20%" class="padding_top">'+subCode+'</td><td width="40%" colspan="2"><img name="studentListSubmit" value="studentListSubmit" title="Print With Grace Marks" src="<?php echo IMG_HTTP_PATH;?>/print1.gif" onClick="return getPrint('+subId+',1);return false;"/> &nbsp;&nbsp;<img name="studentListSubmit" title="Export to Excel With Grace Marks" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/excelIcon.gif" onClick="return getExcel('+subId+',1);return false;"/></td><td class="padding_top" width="40%" colspan="2"><img name="studentListSubmit" title="Print Without Grace Marks" src="<?php echo IMG_HTTP_PATH;?>/print1.gif" onClick="return getPrint('+subId+',0);return false;"/> &nbsp;&nbsp;<img name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/excelIcon.gif" title="Export to Excel Without Grace Marks" onClick="return getExcel('+subId+',0);return false;"/></td></tr>';
					}


					finalReportData += '</table>';
					 document.getElementById('finalInternalReportDiv').innerHTML = finalReportData;
				}
				else {
					//
					if (document.transferMarksForm.errors.value == 'screen') {
						document.getElementById('finalInternalReportDiv').innerHTML = '';
						document.getElementById('marksTransferMessage').innerHTML = res;
					}
					else {
						document.getElementById('finalInternalReportDiv').innerHTML = '';
						document.getElementById('marksTransferMessage').innerHTML = '';
						window.location = '<?php echo HTTP_PATH;?>/Templates/Xml/marksTransferIssues.doc';
					}
					changeColor(currentThemeId);
				}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
}

function getPrint(subjectId,str) {
	tmform = document.transferMarksForm;
	classId = tmform.class1.value;
	if (str == 1) {
		grace = 'yes';
	}
	else {
		grace = 'no';
	}
	var showMarks = form.showMarks[0].checked==true?'0':'1';
	path='<?php echo UI_HTTP_PATH;?>/finalInternalReportPrint.php?degree='+classId+'&subjectId='+subjectId+'&groupId=all&sorting=uRollNo&ordering=asc&grace='+grace+'&showMarks='+showMarks;
	window.open(path,"MarksDistributionReport","status=1,menubar=1,scrollbars=1, width=900");
}


function getExcel(subjectId,str) {
	tmform = document.transferMarksForm;
	classId = tmform.class1.value;
	if (str == 1) {
		grace = 'yes';
	}
	else {
		grace = 'no';
	}
	window.location = 'finalInternalReportPrintCSV.php?degree='+classId+'&subjectId='+subjectId+'&groupId=all&sorting=cRollNo&ordering=asc&grace='+grace;
}


function getTransferMarksData() {
	if (currentProcess == "showClassSubjects") {
		if (false == checkSubjects()) {
			return false;
		}
	}
	currentProcess = "transferMarks";

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getTransferMarksData.php';

	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		return false;
	}
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');
	//pars += '&currentProcess='+currentProcess;

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
			   res = trim(transport.responseText);
			   if (res == "<?php echo NO_SUBJECT_FOUND_FOR_MARKS_TRANSFER;?>") {
				   messageBox(res);
			   }
			   else {
				var transferSubjects = eval('(' + transport.responseText + ')');
				var totalSubjects = transferSubjects['subjects'].length;
				document.getElementById('headingDiv').innerHTML = 'Transfer Marks Details:';
				//tableData = "<table width='100%'><tr><td width='50%' >";
				var tableData = globalTB;
				tableData += '<tr class="rowheading"><td width="2%" class="searchhead_text "><input type="checkbox" name="trfSubChk" onClick="selTrfSubjects();"></td><td width="2%" class="searchhead_text ">#</td><td width="5%" class="searchhead_text ">Code</td><td width="20%" class="searchhead_text ">Subject Name</td><td width="5%" class="searchhead_text ">Subject Type</td><td width="5%" class="searchhead_text ">Rounding</td></tr>';

				if (totalSubjects == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					tableData += '<tr '+bg+'>';
					tableData += '<td class="padding_top" align=center colspan=5>'+noDataFoundVar+'</td></tr>';
				}
				else {
					for(i=0;i<totalSubjects;i++) {
						rowCtr = i + 1;
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						tableData += '<tr '+bg+'>';
						subjectId = transferSubjects['subjects'][i]['subjectId'];
						subjectCode = transferSubjects['subjects'][i]['subjectCode'];
						subjectName = transferSubjects['subjects'][i]['subjectName'];
						subjectType = transferSubjects['subjects'][i]['subjectType'];
						rounding = '<select name="rounding_'+subjectId+'" class="htmlElement2"><option value="ceilTotal">Round Up on Grand Total</option><option value="ceilTestType">Round Up on Test Types</option><option value="roundTotal">Normal Round on Grand Total</option><option value="roundTestType">Normal Round on Test Types</option><option value="noRound">No Rounding</option></select>';


						tableData += '<td align="left" valign="middle"><input type="checkbox" checked name="transferSubjects[]" value="'+subjectId+'" />&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+rowCtr+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectCode+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectName+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+subjectType+'&nbsp;</td>';
						tableData += '<td class="padding_top"  align="left">'+rounding+'&nbsp;</td>';

						tableData += '</tr>';
					}
				}
				tableData += "<tr><td colspan='3' nowrap>&nbsp;&nbsp;<strong>Errors :</strong> &nbsp;</strong><select name='errors' class='htmlElement2'><option value='screen'>Show on screen</option><option value='docFile'>Show in downloadable .doc file</option></select></td><td colspan='3'><input type='image' name='studentListSubmit' value='studentListSubmit' src='<?php echo IMG_HTTP_PATH;?>/transfer_marks.gif' onClick='return transferMarks();return false;' /></td></tr>";

				tableData += "</table>";

				xData = showButtonTable();
				tableData += xData;

				tableData += "<table border='0' width='100%'><tr><td valign='top' colspan='1' class=''  width='100%'><div id='marksTransferMessage'></div><div id='finalInternalReportDiv'></div></td></tr></table>";


				showDetails();
				document.getElementById("resultsDiv").innerHTML = tableData;
				changeColor(currentThemeId);
			   }
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}

function classSubjectLink(alignment) {
	return "<td width='33%' align='"+alignment+"'><input type='image' src='<?php echo IMG_HTTP_PATH;?>/edit_test_types.gif' onClick='javascript:showDiv(\"showClassSubjects\");return false;' /></td>";
}

function attendanceMarksPercentLink(alignment) {
	return "<td width='33%' align='"+alignment+"'><input type='image' onClick='javascript:showDiv(\"attendanceMarksPercent\");return false;' src='<?php echo IMG_HTTP_PATH;?>/apply_percentage_criteria.gif' /></td>";

}

function attendanceMarksSlabLink(alignment) {
	return "<td width='33%' align='"+alignment+"'><input type='image' onClick='javascript:showDiv(\"attendanceMarksSlabs\");return false;' src='<?php echo IMG_HTTP_PATH;?>/apply_slab_criteria.gif' /></td>";
}

function transferMarksLink(alignment) {
	return "<td  width='33%' align='"+alignment+"'><input type='image' onClick='javascript:showDiv(\"transferMarks\");return false;' src='<?php echo IMG_HTTP_PATH;?>/go_to_transfer_marks.gif' /></td>";
}

function showButtonTable() {
	var thisStr = "<table border='0' width='100%'><tr>";
	if (currentProcess == "showClassSubjects") {
		thisStr +=  attendanceMarksPercentLink('left') + attendanceMarksSlabLink('center')+ transferMarksLink('right');
	}
	else if (currentProcess == "attendanceMarksPercent") {
		thisStr += classSubjectLink('left') + attendanceMarksSlabLink('center')+ transferMarksLink('right');
	}
	else if (currentProcess == "attendanceMarksSlabs") {
		thisStr += classSubjectLink('left') + attendanceMarksPercentLink('center') + transferMarksLink('right');
	}
	else if (currentProcess == "transferMarks") {
		thisStr += classSubjectLink('left') + attendanceMarksPercentLink('center')+ attendanceMarksSlabLink('right');
	}
	thisStr += "</tr></table>";
	return thisStr;
}

function showDiv(thisDiv) {
	if (thisDiv == "showClassSubjects") {
		getClassSubjects();
	}
	else if (thisDiv == "attendanceMarksPercent") {
		getAttendanceMarksPercent();
	}
	else if (thisDiv == "attendanceMarksSlabs") {
		getAttendanceMarksSlabs();
	}
	else if (thisDiv == "transferMarks") {
		getTransferMarksData();
	}

}

function selClassSubjects() {
	form = document.transferMarksForm;
	if(form.classSub.checked){

		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox" && form.elements[i].name=="classSubjects[]"){
				form.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox"){
				form.elements[i].checked=false;
			}
		}
	}
}

function showTestTypes(classId,subjectId) {
	form = document.transferMarksForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getTestTypeDetails.php';
	pars = generateQueryString('transferMarksForm')+'&subjectId='+subjectId;
	class1 = form.class1.value;
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
				var testTypeResponse = eval('(' + transport.responseText + ')');
				var totalTestTypes = testTypeResponse['testTypeDetails'].length;
				//alert(testTypeResponse['subjectArray'][0]['subjectCode']);
				subjectCode = testTypeResponse['subjectArray'][0]['subjectCode'];
				subjectName = testTypeResponse['subjectArray'][0]['subjectName'];


				testTypeData = '<table border="0">';
				testTypeData += '<tr><td class="padding_top"><b>Subject Code</b></td><td><b> : '+ subjectCode + '</b></td></tr>';
				testTypeData += '<tr><td class="padding_top"><b>Subject Name</b></td><td><b> : '+ subjectName + '</b></td></tr>';
				testTypeData += '</table>';
				testTypeData += '<input type="hidden" name="currentSubjectHidden" value="'+subjectId+'" />';


				testTypeData += globalTB;
				//document.getElementById('headingDiv').innerHTML = 'Subjects To Class Details:';
				testTypeData += '<tr class="rowheading"><td width="2%" class="searchhead_text ">#</td><td width="30%" class="searchhead_text ">Component</td><td width="20%" class="searchhead_text ">Criteria</td><td width="20%" class="searchhead_text ">Count</td><td width="15%" class="searchhead_text ">Weightage</td></tr>';

				if (totalTestTypes == 0) {
					var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
					testTypeData += '<tr '+bg+'>';
					testTypeData += '<td class="padding_top" align=center colspan=5>'+noDataFoundVar+'</td></tr>';
				}
				else {
					for(i=0;i<totalTestTypes;i++) {
						rowCtr = i + 1;
						var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
						testTypeData += '<tr '+bg+'>';
						testTypeCategoryId = testTypeResponse['testTypeDetails'][i]['testTypeCategoryId'];
						testTypeName = testTypeResponse['testTypeDetails'][i]['testTypeName'];
						examType = testTypeResponse['testTypeDetails'][i]['examType'];
						isAttendanceCategory = testTypeResponse['testTypeDetails'][i]['isAttendanceCategory'];
						evaluationCriteriaIdSelected = testTypeResponse['testTypeDetails'][i]['evaluationCriteriaId'];
						cntTestType = testTypeResponse['testTypeDetails'][i]['cnt'];
						weightageAmount = testTypeResponse['testTypeDetails'][i]['weightageAmount'];
						var cntString = "  ";
						var wtgString = " ";
						if (cntTestType == "null" || cntTestType == null || cntTestType == 0) {
							cntTestType = '';
							cntString = " disabled ";
						}
						if (weightageAmount == "null" || weightageAmount == null) {
							weightageAmount = '';
							wtgString = " disabled ";
						}

						testTypeData += '<td class="padding_top"  align="left">'+rowCtr+'&nbsp;</td>';
						testTypeData += '<td class="padding_top"  align="left">'+testTypeName+'&nbsp;</td>';
						testTypeData += '<td class="padding_top"  align="left">';
						testTypeData += '<select onChange="selVal('+testTypeCategoryId+')" class="selectfield" style="width:220px;" name="ttc_'+testTypeCategoryId+'"><option value="">Select</option>';
						var sel = "";


						if (isAttendanceCategory == "1" || isAttendanceCategory == 1) {
							evLength = testTypeResponse['evAttArray'].length;
							for (x=0; x < evLength; x++) {
								evaluationCriteriaId = testTypeResponse['evAttArray'][x]['evaluationCriteriaId'];
								evaluationCriteriaName = testTypeResponse['evAttArray'][x]['evaluationCriteriaName'];

								if (evaluationCriteriaIdSelected == evaluationCriteriaId) {
									sel = "selected = selected";
									//cntString = "";
								}
								else {
									sel = "";
								}
								testTypeData += '<option '+sel+' value="'+evaluationCriteriaId+'">'+evaluationCriteriaName+'</option>';
							}
						}
						else {
							evLength = testTypeResponse['evNonAttArray'].length;
							for (x=0; x < evLength; x++) {
								evaluationCriteriaId = testTypeResponse['evNonAttArray'][x]['evaluationCriteriaId'];
								evaluationCriteriaName = testTypeResponse['evNonAttArray'][x]['evaluationCriteriaName'];
								if (evaluationCriteriaIdSelected == evaluationCriteriaId) {
									sel = "selected = selected";
									//cntString = "";
								}
								else {
									sel = "";
								}
								testTypeData += '<option  '+sel+' value="'+evaluationCriteriaId+'">'+evaluationCriteriaName+'</option>';
							}
						}
						testTypeData += '</select>';
						testTypeData += '</td>';
						testTypeData += '<td class="padding_top"  align="left"><input type="text" '+ cntString +' class="inputbox1" name="cnt_'+testTypeCategoryId+'" value="'+cntTestType+'" /></td>';
						testTypeData += '<td class="padding_top"  align="left"><input type="text" '+ wtgString +' class="inputbox1" name="wtg_'+testTypeCategoryId+'" value="'+weightageAmount+'" /></td>';
						testTypeData += '</tr>';
					}
					testTypeData += "</table><br>";

					totalOtherSubjects = testTypeResponse['otherSubjects'].length;
					if (totalOtherSubjects > 0) {
						testTypeData += globalTB;
						testTypeData += '<tr><td colspan="3"><u><b>Copy Evaluation Scheme of '+subjectCode+' To:</b></u></td></tr>';
						testTypeData += '<tr class="rowheading"><td colspan="1"><input type="checkbox" name="copyEvScheme" onClick="selSameTypeSubs();"/></td><td colspan="1" class="searchhead_text">Code</td><td colspan="1" class="searchhead_text">Subject Name</td></tr>';
						for (m=0; m < totalOtherSubjects; m++) {
							var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
							testTypeData += '<tr '+bg+'>';
							thisSubjectId = testTypeResponse['otherSubjects'][m]['subjectId'];
							testTypeData += '<td><input type="checkbox" name="copyEvTo[]" value="'+thisSubjectId+'" /></td>';
							testTypeData += '<td class="padding_top"  align="left">'+ testTypeResponse['otherSubjects'][m]['subjectCode'] + '</td>';
							testTypeData += '<td class="padding_top"  align="left"> '+ testTypeResponse['otherSubjects'][m]['subjectName'] + '</td></tr>';
						}
					}
						testTypeData += '<tr>';
						testTypeData += '<td colspan="3"><input type="image" name="studentListSubmit" value="studentListSubmit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return saveTestTypes();return false;" /></td></tr>';
						testTypeData += '</table>';
					document.getElementById('testTypeDivDetails').innerHTML = testTypeData;
					   displayWindow('testTypeDiv',700,400);
					   changeColor(currentThemeId);
				}
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}

function selSameTypeSubs() {
	form = document.testTypeForm;
	if(form.copyEvScheme.checked){
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox" && form.elements[i].name=="copyEvTo[]"){
				form.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<form.length;i++){
			if(form.elements[i].type=="checkbox"){
				form.elements[i].checked=false;
			}
		}
	}
}



function selVal(ttCatId) {
	ttForm = document.testTypeForm;
	testTypeEle = eval("ttForm.ttc_"+ttCatId+".value");
	if (testTypeEle == '') {
		eval("ttForm.cnt_"+ttCatId+".value=''");
		eval("ttForm.cnt_"+ttCatId+".disabled = true");
		eval("ttForm.wtg_"+ttCatId+".value=''");
		eval("ttForm.wtg_"+ttCatId+".disabled = true");
	}
	else if (testTypeEle == 1 || testTypeEle == 8) {
		eval("ttForm.cnt_"+ttCatId+".disabled = false");
		eval("ttForm.wtg_"+ttCatId+".disabled = false");
	}
	else if (testTypeEle == 2 || testTypeEle == 3 || testTypeEle == 5 || testTypeEle == 6 || testTypeEle == 7 ) {
		eval("ttForm.cnt_"+ttCatId+".value=''");
		eval("ttForm.wtg_"+ttCatId+".value=''");
		eval("ttForm.cnt_"+ttCatId+".disabled = true");
		eval("ttForm.wtg_"+ttCatId+".disabled = false");
	}

}

function saveTestTypes() {
	tmform = document.transferMarksForm;
	ttForm = document.testTypeForm;
	pars = generateQueryString('transferMarksForm') + '&' + generateQueryString('testTypeForm');

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/saveTestTypes.php';
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
				var testTypeSaveRes = trim(transport.responseText);
				messageBox(testTypeSaveRes);
				if (testTypeSaveRes == "<?php echo SUCCESS;?>") {
					getClassSubjects();
					hiddenFloatingDiv('testTypeDiv');
				}
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

}


function getClassesForTransfer() {
	form = document.transferMarksForm;
	labelId = form.labelId.value;
	if (labelId == '') {
		form.class1.length = null;
		addOption(form.elements['class1'], '', 'Select');
		return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/TransferMarksAdvanced/getClassesForTransfer.php';
	pars = generateQueryString('transferMarksForm');
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
				addOption(form.elements['class1'], '', 'Select');
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

window.onbeforeunload = function (e) {
	stopTransferProcess();
};

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TransferMarksAdvanced/listTransferInternalMarksAdvanced.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
// for VSS
//$History: transferInternalMarksAdvanced.php $
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 2/16/10    Time: 1:13p
//Updated in $/LeapCC/Interface
//done changes FCNS No. 1298
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:42p
//Updated in $/LeapCC/Interface
//done changes to make new module for marks transfer
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:15p
//Created in $/LeapCC/Interface
//file added for transfer marks advanced module.
//






?>
