<?php
//-------------------------------------------------------
//  This File is used for teacher role permission
//
//
// Author :Rajeev Aggarwal
// Created on : 29-05-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TeacherRolePermissions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Role Permissions </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

selectedRows = '';
function validateAddForm(frm) {

	savePermissions();
}
function uncheckAll() {

	form = document.listForm;
	for(i=0;i<form.length;i++) {
		if (form.elements[i].type == "checkbox") {
				form.elements[i].checked=false;
		}
	}
	if (selectedRows != '') {
		selectedArray = selectedRows.split(',');
		for(i=0; i<selectedArray.length;i++) {
			thisVals = selectedArray[i].split('#');
			document.getElementById(thisVals[0]).className=thisVals[1];
		}
	}
}
function getPermissions(id) {
/*
	form = document.listForm.name;
	var queryString = generateQueryString(form);
	alert(queryString);
*/
   uncheckAll();
   form = document.listForm;
   url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxTeacherGetRolePermissions.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: {roleId: id},
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
			    j = trim(transport.responseText).evalJSON();
				total = j.userPermissionArray[0].length;
				for(i=0;i<total;i++) {
					moduleName = j.userPermissionArray[0][i].moduleName;
					viewPermission = j.userPermissionArray[0][i].viewPermission;
					anyPermission = false;
					if (parseInt(viewPermission) == 1) {
						str = moduleName+'_viewPermission';
						strStatus = eval("form."+str+".disabled");
						if(strStatus == true) {
							eval("form."+str+".checked = false");
						}
						else {
							eval("form."+str+".checked = true");
						}
						anyPermission = true;
					}
					editPermission = j.userPermissionArray[0][i]['editPermission'];
					if (parseInt(editPermission) == 1) {
						str = moduleName+'_editPermission';
						strStatus = eval("form."+str+".disabled");
						if(strStatus == true) {
							eval("form."+str+".checked = false");
						}
						else {
							eval("form."+str+".checked = true");
						}
						anyPermission = true;
					}
					addPermission = j.userPermissionArray[0][i]['addPermission'];
					if (parseInt(addPermission) == 1) {
						str = moduleName+'_addPermission';
						strStatus = eval("form."+str+".disabled");
						if(strStatus == true) {
							eval("form."+str+".checked = false");
						}
						else {
							eval("form."+str+".checked = true");
						}
						anyPermission = true;
					}
					deletePermission = j.userPermissionArray[0][i]['deletePermission'];
					if (parseInt(deletePermission) == 1) {
						str = moduleName+'_deletePermission';
						strStatus = eval("form."+str+".disabled");
						if(strStatus == true) {
							eval("form."+str+".checked = false");
						}
						else {
							eval("form."+str+".checked = true");
						}
						anyPermission = true;
					}
					if (anyPermission == true) {
						if (selectedRows != '') {
							selectedRows +=',';
						}
						oldClass = document.getElementById(moduleName).className;
						selectedRows += moduleName+'#'+oldClass;
						document.getElementById(moduleName).className='highlightPermission';
					}
				}

				totalDashboard = j.info[0].length;
				for(i=0;i<totalDashboard;i++) {

					str = "chb"+j.info[0][i].frameId;
					eval("form."+str+".checked = true");
					oldClass = document.getElementById(str).className;

					if (selectedRows != '') {

						selectedRows +=',';
					}
					selectedRows += str+'#'+oldClass;
					document.getElementById(str).className='highlightPermission';
				}
	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function check(moduleName, bgClass) {
   form = document.listForm;
   str1 = moduleName+'_viewPermission';
   str2 = moduleName+'_editPermission';
   str3 = moduleName+'_addPermission';
   str4 = moduleName+'_deletePermission';


   if (eval("form."+str1+".checked") == true || eval("form."+str2+".checked") == true || eval("form."+str3+".checked") == true || eval("form."+str4+".checked") == true) {
	   document.getElementById(moduleName).className='highlightPermission';
   }
   else {
	   document.getElementById(moduleName).className=bgClass;
   }
}


function savePermissions() {

	form = document.listForm.name;
	var queryString = generateQueryString(form);

   url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxTeacherSaveRolePermissions.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: queryString,
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){

			    hideWaitDialog(true);
                //alert(transport.responseText);
			    j = trim(transport.responseText);
				alert(j);

	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

window.onload=function(){
  getPermissions(2);
}
</script>

</head>
<body>
	<?php
   require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/RolePermission/listTeacherRolePermission.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: teacherPermission.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 6/04/09    Time: 11:03a
//Created in $/LeapCC/Interface
//Intial Checkin to implement 'Teacher,Parent,student and management'
//role permission
?>