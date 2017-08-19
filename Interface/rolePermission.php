<?php
//-------------------------------------------------------
//  This File is used for role permission
//
//
// Author :Ajinder Singh
// Created on : 06-nov-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RolePermissions');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/RolePermission/scInitList.php");

/*THIS CODE IS ADDED TO MAKE ROLE DROPDOWN PRE-SELECTED WHEN ROLEID IS SUPPLIED FROM ROLE MASTER PAGE*/
$roleId=trim($REQUEST_DATA['roleId']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Role Permissions </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="10%"','',false), new Array('bankName','Bank Name','width="40%"','',true) , new Array('  	bankAbbr','Abbr','width="40%"','',true), new Array('action','Action','width="10%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Bank/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBank';   
editFormName   = 'EditBank';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBank';
divResultName  = 'results';
page=1; //default page
sortField = 'bankName';
sortOrderBy    = 'ASC';
selectedRows = '';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
	displayWindow(dv,w,h);
    populateValues(id);   
}



function validateAddForm(frm) {
    //messageBox (act)
    var fieldsArray = new Array(new Array("roleId","Select Role Name"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
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
function getPermissions() {
/*
	form = document.listForm.name;
	var queryString = generateQueryString(form);
	alert(queryString);
*/
   uncheckAll();
   form = document.listForm;
   if(form.roleId.value==''){
       return false;
   }
   
   var url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxGetRolePermissions.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: {roleId: form.roleId.value},
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){
                
			    hideWaitDialog(true);
			    j = trim(transport.responseText).evalJSON();
				total = j.userPermissionArray[0].length;
				for(i=0;i<total;i++) {
					moduleName = j.userPermissionArray[0][i].moduleName;
					viewPermission = j.userPermissionArray[0][i]['viewPermission'];
					anyPermission = false;
					if (parseInt(viewPermission) == 1) {
						str = moduleName+'_viewPermission';
						if (!eval("form."+str)) {
							continue;
						}
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
						if (!eval("form."+str)) {
							continue;
						}
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
						if (!eval("form."+str)) {
							continue;
						}
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
						if (!eval("form."+str)) {
							continue;
						}
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

   url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxSaveRolePermissions.php';
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

/*THIS CODE IS ADDED TO MAKE ROLE DROPDOWN PRE-SELECTED WHEN ROLEID IS SUPPLIED FROM ROLE MASTER PAGE*/
window.onload=function(){
  if("<?php echo $roleId;?>" != ""){
      document.listForm.roleId.value="<?php echo $roleId; ?>";
      getPermissions();//fetch permission corresponding to this role
  }
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/RolePermission/listRolePermission.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: rolePermission.php $
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:46
//Updated in $/LeapCC/Interface
//Made UI changes in Role Master module
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/25/09    Time: 3:04p
//Updated in $/LeapCC/Interface
//fixed issue of role permissions not getting selected.
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 12:03p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 1/19/09    Time: 4:30p
//Updated in $/LeapCC/Interface
//changed folder name
?>
