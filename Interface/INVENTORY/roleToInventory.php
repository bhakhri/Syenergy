<?php
//-------------------------------------------------------
// Purpose: To generate assign subject to class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (28 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleToInventory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Requisition Approver Mapping </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','',false), 
								new Array('employeeName','Requestion by Employee','width="20%"','',false),
								new Array('role','Approver\'s Role','width="20%"','',false),
								new Array('user','Approver\'s Name','width="40%"','',false)
								);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RoleToInventory/ajaxInitRoleToInventoryList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName';
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
	   //document.getElementById('saveDiv').style.display='';
	   document.getElementById('saveDiv1').style.display='';
	//   document.getElementById('saveDiv2').style.display='';
	   //document.getElementById('showTitle').style.display='';	 	
	   document.getElementById('showData').style.display='';	 
       sendReq(listURL,divResultName,'listForm','');
		 
}


function clearText(){

    //document.getElementById('saveDiv').style.display='none';
    document.getElementById('saveDiv1').style.display='none';	 	
   // document.getElementById('saveDiv2').style.display='none';	 	
	document.getElementById('showTitle').style.display='none';	 	
	document.getElementById('showData').style.display='none';
	document.getElementById('results').innerHTML="";
}

function insertForm() {
 
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RoleToInventory/initRoleToInventoryAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo MAPPED_SUCCESS;?>" == trim(transport.responseText)) { 
				flag = true;
				alert(trim(transport.responseText));
				clearText();
				return false;
			 }
			 else {
				alert(trim(transport.responseText));
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}                    

function validateAddForm() {
    
    insertForm();
	return false;
}


function getUserRole(name,value) {
	//form = document.listForm;
	var user = name.split('role_');
	var employeeId = user[1];
	if(value == '') {
		document.getElementById('user_'+employeeId).length = null;
		addOption(document.getElementById('user_'+employeeId),'','Select');
		return false;
	}
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/RoleToInventory/getRoleUserData.php';
	var pars = 'roleId='+value;
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
			if(j.length == 0) {
				document.getElementById('user_'+employeeId).length = null;
				addOption(document.getElementById('user_'+employeeId),'','Select');
				return false;
			}
			len = j.length;
			document.getElementById('user_'+employeeId).length = null;
			addOption(document.getElementById('user_'+employeeId),'','Select');
			for(i=0;i<len;i++) {
				addOption(document.getElementById('user_'+employeeId), j[i].userId, j[i].userName);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
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
    require_once(INVENTORY_TEMPLATES_PATH . "/RoleToInventory/listRoleToInventoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: $
//
//
?>
