<?php
//-------------------------------------------------------
//  This File is used for role permission
//
//
// Author :Ajinder Singh
// Created on : 06-nov-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
ini_set("post_max_size", "15M");  
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


function validateAddForm(frm) {
    
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
		if(form.elements[i].type == "checkbox") {
		  form.elements[i].checked=false;
		  moduleName = (form.elements[i].name);
		  spanName = moduleName+"Span";
          eval("document.getElementById(spanName).className = ''");
		}
	}
}
	

function getPermissions() {

/*	form = document.listForm.name;
	var queryString = generateQueryString(form);
	alert(queryString);
*/
   uncheckAll();
   form = document.listForm;
   if(form.roleId.value==''){
       return false;
   }
    var  isInstitute='0';
    var url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxGetRolePermissions.php';
    new Ajax.Request(url,
    {
	     method:'post',
	     parameters: {roleId: form.roleId.value},
         onCreate: function () {
             showWaitDialog(true);
         },
	     onSuccess: function(transport){
           
	  //alert(transport.responseText);
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
						getCheck(str,0);
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
						getCheck(str,0);
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
						getCheck(str,0);
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
						getCheck(str,0);
						anyPermission = true;
					}

					//Checking New Tab should be checked or not
					//alert(j.info[0][i].frameName)				
					
	}

			
			//this loop will add check to those boxes home and student details
				 j1 = trim(transport.responseText).evalJSON();
				total1 = j1.info[0].length;
			
				for(i=0;i<total1;i++) { 
					modName=j.info[0][i].frameName1;
					check1Permission = j.info[0][i].dashboardPermission;
					if (check1Permission == "view") {
						str = 'chb'+modName+'_viewPermission';
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
						getCheck(str,0);
						anyPermission = true;
					}
					if (check1Permission == "edit") {
						str = 'chb'+modName+'_editPermission';
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
						getCheck(str,0);
						anyPermission = true;
					}
					if (check1Permission == "add") {
						str = 'chb'+modName+'_addPermission';
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
						getCheck(str,0);
						anyPermission = true;
					}
					if (check1Permission == "delete") {
						str = 'chb'+modName+'_deletePermission';
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
						getCheck(str,0);
						anyPermission = true;
					}

				}
			/*	totalDashboard = j.info[0].length;
				for(i=0;i<totalDashboard;i++) {
					str = "chb"+j.info[0][i].frameName;
					eval("form."+str+".checked=true");
					getCheck(str,0);
				}
			*/
			totalDashboard = j.info[0].length;
			for(i=0;i<totalDashboard;i++) {
					moduleName = "chb"+j.info[0][i].frameName;
					viewPermission = j.info[0][i].viewPermission;
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
						getCheck(str,0);
						anyPermission = true;
					}
					editPermission  = j.info[0][i].editPermission;
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
						getCheck(str,0);
						anyPermission = true;
					}
					addPermission = j.info[0][i].addPermission;
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
						getCheck(str,0);
						anyPermission = true;
					}
					deletePermission = j.info[0][i].deletePermission;
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
						getCheck(str,0);
						anyPermission = true;
					}
				}
	
	     },
	     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function doAll(tempModuleName){
	
	form = document.listForm;
	str1 = tempModuleName+'_deletePermission';
    str2 = tempModuleName+'_editPermission';
    str3 = tempModuleName+'_addPermission';
	str4 = tempModuleName+'_viewPermission';
	strCheck1 = eval("form."+str1+".checked");
	strCheck2 = eval("form."+str2+".checked");
	strCheck3 = eval("form."+str3+".checked");
	strCheck4 = eval("form."+str4+".checked");
	strDisable1 = eval("form."+str1+".disabled");
	strDisable2 = eval("form."+str2+".disabled");
	strDisable3 = eval("form."+str3+".disabled");
	strDisable4 = eval("form."+str4+".disabled");
	
	if( (strCheck1 == true && strDisable1 == false) || (strCheck2 == true && strDisable2 == false  ) || (strCheck3 == true && strDisable3 == false) || 
		(strCheck4 == true  && strDisable4 == false) ) {
		  eval("form."+str1+".checked = false");
		  eval("form."+str2+".checked = false");
		  eval("form."+str3+".checked = false");
		  eval("form."+str4+".checked = false");
	}
	else {
		if (strDisable1 == false) {
		  eval("form."+str1+".checked = true");
		}
		if (strDisable2 == false) {
		  eval("form."+str2+".checked = true");
		}
		if (strDisable3 == false) {
		  eval("form."+str3+".checked = true");
		}
		if (strDisable4 == false) {
		  eval("form."+str4+".checked = true");
		}
	}
	
	getCheck(str1,0);
	getCheck(str2,0);
	getCheck(str3,0);
	getCheck(str4,0);
	return false;	
}



function doAllChild(tempModuleName,checkedStatus) {
	
	form = document.listForm;
	str1 = tempModuleName+'_deletePermission';
    str2 = tempModuleName+'_editPermission';
    str3 = tempModuleName+'_addPermission';
	str4 = tempModuleName+'_viewPermission';
	strCheck1 = eval("form."+str1+".checked");
	strCheck2 = eval("form."+str2+".checked");
	strCheck3 = eval("form."+str3+".checked");
	strCheck4 = eval("form."+str4+".checked");
	strDisable1 = eval("form."+str1+".disabled");
	strDisable2 = eval("form."+str2+".disabled");
	strDisable3 = eval("form."+str3+".disabled");
	strDisable4 = eval("form."+str4+".disabled");

	if(strDisable1 == false) {
		eval("form."+str1+".checked = "+checkedStatus);
	}
	if(strDisable2 == false) {
		eval("form."+str2+".checked = "+checkedStatus);
	}
	if(strDisable3 == false) {
		eval("form."+str3+".checked = "+checkedStatus);

	}
	if(strDisable4 == false) {
		eval("form."+str4+".checked = "+checkedStatus);
	}
	
	getCheck(str1,0);
	getCheck(str2,0);
	getCheck(str3,0);
	getCheck(str4,0);
	return false;	
}


function getCheck(moduleName,mode) {
	if (mode == 1) {
		form = document.listForm;
		strCheck = eval("form."+moduleName+".checked");
		if(strCheck == true) {
			eval("form."+moduleName+".checked = false");
		}
		else {
			eval("form."+moduleName+".checked = true");
			
		}
		getCheck(moduleName,0);
	}
	else {
		spanName = moduleName+"Span";
		eval("document.getElementById(spanName).className = ''");
		moduleChecked = eval("document.listForm."+moduleName+".checked");
		if (moduleChecked == true) {
		   document.getElementById(spanName).className = "inputboxRedFilled";
		}
	}
}


function savePermissions() {

	form = document.listForm.name;
	var queryString = generateQueryString(form);

   url = '<?php echo HTTP_LIB_PATH;?>/RolePermission/ajaxSaveNewRolePermissions.php';
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
	require_once(TEMPLATES_PATH . "/RolePermissions/listRolePermissions.php");
	?>
	<SCRIPT LANGUAGE="JavaScript">
		moduleHeadJSON = '<?php echo $moduleHeadJSON; ?>';
		moduleJSON = eval('(' +moduleHeadJSON+ ')');
		moduleCheckJSON = '<?php echo $moduleCheckJSON; ?>';
		moduleCheckJSON = eval('(' +moduleCheckJSON+ ')');

		function selectChildren(parentModuleName) {
			childModuleArray = moduleJSON[parentModuleName];
			totalChildren = childModuleArray.length;
			for (i=0; i < totalChildren; i++ ) {
				thisChild = childModuleArray[i];
				if (moduleCheckJSON[parentModuleName][thisChild] == false) {
					moduleCheckJSON[parentModuleName][thisChild] = true;
				}
				else {
					moduleCheckJSON[parentModuleName][thisChild] = false;
				}
         		doAllChild(thisChild,moduleCheckJSON[parentModuleName][thisChild]);
			}
		}
	</SCRIPT>
    <?php
	require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>
<?php 
?>
