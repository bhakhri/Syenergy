<?php
//-------------------------------------------------------
// Purpose: To generate assign subject to class from the database, and have add/edit/delete, search
// functionality
//
// Author : Jaineesh
// Created on : (26.09.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleToClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Academic Head Privileges </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(	new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="4%"','align="center"',false),
								new Array('srNo','#','width="3%"','',false),
								new Array('className','Class Name','width="20%"','',false),
								new Array('groupType','Group Type','width="20%"','',false),
								new Array('group','Group','width="40%"','',false)
								);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RoleToClass/ajaxInitRoleToClassList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'className';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
       displayWindow(dv,w,h);
       populateValues(id);
}

function getClasses(){

	if(isEmpty(document.getElementById('roleId').value)) {
		messageBox("<?php echo SELECT_ROLE?>");
		document.listForm.roleId.focus();
		return false;
	 }
		if(isEmpty(document.getElementById('teacher').value)) {
		  messageBox("<?php echo SELECT_USER?>");
	   document.getElementById('saveDiv1').style.display='none';
	   document.getElementById('saveDiv2').style.display='none';
	   document.getElementById('showTitle').style.display='none';
	   document.getElementById('showData').style.display='none';
	   document.getElementById('results').innerHTML=" ";
	   document.listForm.teacher.focus();
	   return false;
      }
   else{
	   document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	   document.getElementById('saveDiv2').style.display='';
	   document.getElementById('showTitle').style.display='';
	   document.getElementById('showData').style.display='';
       sendReq(listURL,divResultName,'listForm','');
   }

}

function getGroupValue(val,val1,classId,type,frm,fieldGroup){

	totalDegreeId = document.listForm.elements[val].length;

	var name = document.getElementById(val1);
	//alert(name);
	selectedGroupType='';

	countGroupType=0;
	for(i=0;i<totalDegreeId;i++) {

			if (document.listForm.elements[val][i].selected == true) {
			if (selectedGroupType != '') {
				selectedGroupType += ',';

			}
			countGroupType++;
			selectedGroupType += document.listForm.elements[val][i].value;
			}
	}

    url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
    var fieldGroup = document.getElementById(fieldGroup);


	if(fieldGroup.options.length=0) {
		var objOption = new Option("Select","");
		fieldGroup.options.add(objOption);
		return false;
	}


	new Ajax.Request(url,
    {
		 method:'post',
		 parameters: {type: type,id: selectedGroupType,classId:classId},
		 asynchronous:false,
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){

			hideWaitDialog(true);
			var j = eval('('+transport.responseText+')');
			if (j.length != 0) {
			 for(var c=0;c<j.length;c++){
				if(type=="groupType"){
					 var objOption = new Option(j[c].groupName,j[c].groupId);
					 fieldGroup.options.add(objOption);
				}
			 }
			}
			else {
				return false;
			}
		 },
		 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function clearText(){

    document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';
    document.getElementById('saveDiv2').style.display='none';
	document.getElementById('showTitle').style.display='none';
	document.getElementById('showData').style.display='none';
	document.getElementById('results').innerHTML="";
}

function insertForm() {

	 url = '<?php echo HTTP_LIB_PATH;?>/RoleToClass/initRoleToClassAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('#listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo ASSIGN_ROLE_SUCCESS;?>" == trim(transport.responseText)) {
				 flag = true;
					 alert(trim(transport.responseText));
					 clearText();
					 return false;
			 }

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function validateAddForm(frm, act) {

	var selected=0;
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
			{selected++;}
		}
	}
	/*if(selected==0){

		alert("<?php echo SELECT_ATLEAST_CLASS?>");
		return false;
	}*/

	var j=0;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){
			  fl=0;

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){

				groupType= "groupType"+formx.elements[i].value;
				groupTypeValue = document.getElementById(groupType).value;

				//check for numeric value
				if(groupTypeValue == ''){

					messageBox("<?php echo SELECTED_GROUP_TYPE?>");
					document.getElementById(groupType).className = 'inputboxRed';
					document.getElementById(groupType).focus();
					return false;
				}

				group= "group"+formx.elements[i].value;
				//groupLength = "group"+formx.elements[i].value;
				//alert(groupLength);

				groupValue = document.getElementById(group).value;

				//check for numeric value
				if(groupValue == ''){
					thisClassName = formx.elements[i].title;
					messageBox("Group not created for Class "+thisClassName+". Even if the classes are held as a single unit, create a single group and assign it to all students of the class");
					document.getElementById(group).className = 'inputboxRed';
					document.getElementById(group).focus();
					return false;
				}
			}
		}
	}
    insertForm();
	return false;
}

function doAll(){

	formx = document.listForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
				formx.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<formx.length;i++){
			if(formx.elements[i].type=="checkbox"){
				formx.elements[i].checked=false;
			}
		}
	}
}

function checkSelect(){

	var selected=0;
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){
		if(formx.elements[i].type=="checkbox"){
			if((formx.elements[i].checked) && (formx.elements[i].name!="checkbox2")){
				selected++;
			}
		}
	}
	if(selected==0)	{
		messageBox("<?php echo SUBJECT_TO_CLASS_ONE?>");
		return false;
	}
}


function getTeacherData() {
	form = document.listForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/RoleToClass/getTeacherData.php';
	var pars = 'roleId='+form.roleId.value;

	if (form.roleId.value=='') {
		form.teacher.length = null;
		addOption(form.teacher, '', 'Select');
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
			if(j.length == 0) {
				form.teacher.length = null;
				addOption(form.teacher, '', 'Select');
				return false;
			}
			len = j.length;
			form.teacher.length = null;
			addOption(form.teacher, '','Select');
			for(i=0;i<len;i++) {
				addOption(form.teacher, j[i].employeeId, j[i].employeeName);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


function getSelected(valueSelected,classId){

	if(document.getElementById(valueSelected).checked){

		var val="groupType"+classId+"[]";
		var fieldGroup="group"+classId;
		var type="groupTypeClassName";

		totalDegreeId = document.listForm.elements[val].length;
		selectedGroupType='';

		countGroupType=0;
		for(i=0;i<totalDegreeId;i++) {

			if (selectedGroupType != '') {
				selectedGroupType += ',';

			}
			document.listForm.elements[val][i].selected=true
			countGroupType++;
			selectedGroupType += document.listForm.elements[val][i].value;
		}
		url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
		var fieldGroup = document.getElementById(fieldGroup);


		if(fieldGroup.options.length=0) {

			var objOption = new Option("Select","");
			fieldGroup.options.add(objOption);
			return false;
		}


		new Ajax.Request(url,
		{
			 method:'post',
			 parameters: {type: type,id: selectedGroupType,classId:classId},
			 asynchronous:false,
			 onCreate: function() {
				 showWaitDialog(true);
			 },
			 onSuccess: function(transport){

				hideWaitDialog(true);
				var j = eval('('+transport.responseText+')');
				//alert(j.length);
				if (j.length != 0) {

				 var val="group["+classId+"][]";
				 for(var c=0;c<j.length;c++){

					 var objOption = new Option(j[c].groupName,j[c].groupId);
					 fieldGroup.options.add(objOption);
					 document.listForm.elements[val][c].selected=true;
				 }
				}
				else {
					return false;
				}
			 },
			 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
	}else{


		var val="groupType"+classId+"[]";
		var fieldGroup="group"+classId;
		var type="groupType";

		totalDegreeId = document.listForm.elements[val].length;
		selectedGroupType='';

		countGroupType=0;
		for(i=0;i<totalDegreeId;i++) {

			if (selectedGroupType != '') {
				selectedGroupType += ',';

			}
			document.listForm.elements[val][i].selected=false
			//countGroupType++;
			//selectedGroupType += document.listForm.elements[val][i].value;
		}
		url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
		var fieldGroup = document.getElementById(fieldGroup);


		if(fieldGroup.options.length=0) {

			var objOption = new Option("Select","");
			fieldGroup.options.add(objOption);
			return false;
		}


		new Ajax.Request(url,
		{
			 method:'post',
			 parameters: {type: type,id: selectedGroupType,classId:classId},
			 asynchronous:false,
			 onCreate: function() {
				 showWaitDialog(true);
			 },
			 onSuccess: function(transport){

				hideWaitDialog(true);
				var j = eval('('+transport.responseText+')');
				//alert(j.length);
				if (j.length != 0) {

				 var val="group["+classId+"][]";
				 for(var c=0;c<j.length;c++){

					 var objOption = new Option(j[c].groupName,j[c].groupId);
					 fieldGroup.options.add(objOption);
					 document.listForm.elements[val][c].selected=true;
				 }
				}
				else {
					return false;
				}
			 },
			 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
	}
}


/* function to output data to a Print*/

function printReport() {

	var path='<?php echo UI_HTTP_PATH;?>/displayRoleToClassReport.php?employeeId='+document.listForm.teacher.value+'&roleId='+document.listForm.roleId.value;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"RoleToClassReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){

    }
}

function selUnselGroupType(classIdValue,checkUncheckValue) {
	var val="groupType"+classIdValue+"[]";
	totalDegreeId = document.listForm.elements[val].length;
	selectedGroupType='';
	countGroupType=0;
	for(i=0;i<totalDegreeId;i++) {
		if (selectedGroupType != '') {
			selectedGroupType += ',';
		}
		if (checkUncheckValue == 1) {
			document.listForm.elements[val][i].selected=true;
		}
		else {
			document.listForm.elements[val][i].selected=false;
		}
	}
	getGroupValue("groupType"+classIdValue+"[]","groupType"+classIdValue,classIdValue,"groupType","Add","group"+classIdValue)
}


function selUnselGroup(classIdValue,checkUncheckValue) {
	var val="groupType"+classIdValue+"[]";
	totalDegreeId = document.listForm.elements[val].length;
	selectedGroupType='';
	countGroupType=0;
	for(i=0;i<totalDegreeId;i++) {
		if (selectedGroupType != '') {
			selectedGroupType += ',';
		}
		if (checkUncheckValue == 1) {
			document.listForm.elements[val][i].selected=true;
		}
		else {
			document.listForm.elements[val][i].selected=false;
		}
	}
}

function selUnselGroup(classIdValue,checkUncheckValue) {
	var val="group["+classIdValue+"][]";
	totalDegreeId = document.listForm.elements[val].length;
	selectedGroupType='';
	countGroupType=0;
	for(i=0;i<totalDegreeId;i++) {
		if (selectedGroupType != '') {
			selectedGroupType += ',';
		}
		if (checkUncheckValue == 1) {
			document.listForm.elements[val][i].selected=true;
		}
		else {
			document.listForm.elements[val][i].selected=false;
		}
	}
}


</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/RoleToClass/listRoleToClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: roleToClass.php $
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 2/06/10    Time: 3:20p
//Updated in $/LeapCC/Interface
//fixed issue: 1722
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 10-01-23   Time: 3:53p
//Updated in $/LeapCC/Interface
//added Javascript check to select grouptype and group on single click
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/02/09   Time: 1:41p
//Updated in $/LeapCC/Interface
//show "select" in user drop down during selected user
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/20/09   Time: 11:17a
//Updated in $/LeapCC/Interface
//modification in message during non selection of group in HOD role
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/07/09   Time: 4:58p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001727, 0001725, 0001724, 0001723, 0001721, 0001720,
//0001719, 0001718, 0001729
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/05/09   Time: 6:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001684, 0001689, 0001688, 0001687, 0001685, 0001686,
//0001683, 0001629 and report for academic head privileges
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/03/09   Time: 3:45p
//Updated in $/LeapCC/Interface
//fixed bug no. 0001682
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 10/03/09   Time: 9:43a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001679, 0001678, 0001677, 0001676, 0001675
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 10/01/09   Time: 6:51p
//Updated in $/LeapCC/Interface
//changed queries and flow in send message to student, student report
//list according to HOD role and make new role advisory, modified in
//queries according to this role
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:47p
//Updated in $/LeapCC/Interface
//worked on role to class
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/30/09    Time: 5:56p
//Created in $/LeapCC/Interface
//new file for role privilleges
//
?>