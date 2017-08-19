<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Role ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Role/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>:Role  Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('roleName','Role Name','width="20%"','',true),
    new Array('userList','Users(Total)','width="7%"','align="center"',false),
    new Array('permissionList','View Permissions','width="12%"','align="center"',false),
    new Array('editPermissionList','Edit Permissions','width="8%"','align="center"',false),
    new Array('copyPermissionList','Copy Permissions','width="8%"','align="center"',false),
    new Array('action','Action','width="2%"','align="center"',false)
    );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRole';
editFormName   = 'EditRole';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRole';
divResultName  = 'results';
page=1; //default page
sortField = 'roleName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    if(id <=5 ){
             messageBox("You Do Not Have Permission to Edit This Role");
             return false;
     }
    displayWindow(dv,w,h);
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {


    var fieldsArray = new Array(new Array("roleName","<?php echo ENTER_ROLE_NAME; ?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='roleName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ROLE_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }

    }
    if(act=='Add') {
        addRole();
        return false;
    }
    else if(act=='Edit') {
        editRole();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Role
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRole() {
         url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roleName: (document.AddRole.roleName.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                        else if("<?php echo ROLE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ROLE_ALREADY_EXIST ;?>");
                         document.AddRole.roleName.focus();
                        }
                         else {
                             hiddenFloatingDiv('AddRole');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A Role
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteRole(id) {

         if(id <=5 ){
             messageBox("<?php echo ROLE_DELETE_PERMISSION; ?>");
             return false;
         }
         if(false==confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roleId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });

         }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddRole" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddRole.roleName.value = '';
   document.AddRole.roleName.focus();
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Role
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRole() {

         url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roleId: (document.EditRole.roleId.value),
              roleName: (document.EditRole.roleName.value)
              },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditRole');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo ROLE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ROLE_ALREADY_EXIST ;?>");
                         document.EditRole.roleName.focus();
                        }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditRole" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roleId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditRole');
                        messageBox("<?php echo ROLE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');

                   document.EditRole.roleName.value = j.roleName;
                   document.EditRole.roleId.value = j.roleId;
                   document.EditRole.roleName.focus();
             },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function viewUsers(roleId,roleName) {
        if(roleId==''){
            return false
        }
        document.getElementById('userDetailsContentsDiv').innerHTML='';
        document.getElementById('roleId1').value=roleId;
        document.getElementById('roleName1').value=roleName;

        var url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxGetRoleUserList.php';
        var recordsPerPage2='<?php echo RECORDS_PER_PAGE; ?>';
        if( roleId !=3 && roleId !=4){ //for employees/admins
         var userTableHeadArray =new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('userName','UserName','width="8%" align="left"',true),
                                new Array('displayName','Display Name','width="10%" align="left"',true),
                                new Array('employeeName','Name','width="10%" align="left"',true),
                                new Array('isTeaching','Teaching','width="3%" align="left"',true),
                                new Array('designationName','Designation','width="10%" align="left"',true),
                                new Array('dateOfJoining','DOJ','width="7%" align="center"',true),
                                new Array('dateOfBirth','DOB','width="7%" align="center"',true)
                             );
       }
      else if(roleId==3){
        var userTableHeadArray =new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('userName','UserName','width="8%" align="left"',true),
                                new Array('displayName','Display Name','width="10%" align="left"',true),
                                new Array('parentName','Name','width="60%" align="left"',true),
                                new Array('parent','Type','width="20%" align="left"',true)
                             );
      }
      else if(roleId==4){
        var userTableHeadArray =new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('userName','UserName','width="8%" align="left"',true),
                                new Array('displayName','Display Name','width="10%" align="left"',true),
                                new Array('studentName','Name','width="20%" align="left"',true),
                                new Array('dateOfBirth','DOB','width="5%" align="center"',true),
                                new Array('dateOfAdmission','DOA','width="5%" align="center"',false)
                             );
      }
      else{
          messageBox("Invalid Role");
          return false;
      }

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage2,linksPerPage,1,'','userName','ASC','userDetailsContentsDiv','','',true,'listObj',userTableHeadArray,'','','&roleId='+roleId);
 sendRequest(url, listObj, ' ',false);
 document.getElementById('divHeaderId3').innerHTML='List of users for role : '+roleName;
 displayWindow('UserDetailDiv',670,220);
}

function copyPermissions(roleId,roleName) {
	  if(roleId==''){
			return false
	  }
	  displayWindow('CopyPermissionDiv',670,420);
	  document.copyPermissionForm.roleId.value = roleId;
}


function copyRolePermission() {
	var pars = generateQueryString('copyPermissionForm');
	url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxInitCopyPermissions.php';
	new Ajax.Request(url,
	  {
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			  showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			  hideWaitDialog(true);
			  if("<?php echo SUCCESS;?>"==trim(transport.responseText)) {
					messageBox("<?php echo SUCCESS;?>");
					return false;
			  }
			  else {
					messageBox(trim(transport.responseText));
			  }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	  });
}

function viewPermissions(roleId,roleName) {
        if(roleId==''){
            return false
        }
        if(roleId==1){
            messageBox("Administrator has all the permissions");
            return false;
        }
        document.getElementById('roleId2').value=roleId;
        document.getElementById('roleName2').value=roleName;
        document.getElementById('userPermissionsContentsDiv').innerHTML='';

        var url = '<?php echo HTTP_LIB_PATH;?>/Role/ajaxGetRolePermissionList.php';
        var recordsPerPage2='<?php echo RECORDS_PER_PAGE; ?>';
        if( roleId !=1){ //for employees/admins
         var userTableHeadArray =new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('moduleName','Module','width="12%" align="left"',true),
                                new Array('viewPermission','View','width="5%" align="left"',true),
                                new Array('addPermission','Add','width="5%" align="left"',true),
                                new Array('editPermission','Edit','width="5%" align="left"',true),
                                new Array('deletePermission','Delete','width="5%" align="left"',true)
                             );
        }
        else{
          messageBox("Administrator has all the permissions");
          return false;
        }

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage2,linksPerPage,1,'','moduleName','ASC','userPermissionsContentsDiv','','',true,'listObj2',userTableHeadArray,'','','&roleId='+roleId);
 sendRequest(url, listObj2, ' ',false);
 document.getElementById('divHeaderId4').innerHTML='Permissions for role : '+roleName;
 displayWindow('UserPermissionDiv',570,320);
}


/* function to print role report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/roleReportPrint.php?'+qstr;
    window.open(path,"RoleReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}
function printReportCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/roleReportCSV.php?'+qstr;
    window.location=path;
}

function printUserReport(){
  var roleId=document.getElementById('roleId1').value;
  var roleName=document.getElementById('roleName1').value.split('(')[0];
  var qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField+'&roleId='+roleId+'&roleName='+escape(roleName);
  var path='<?php echo UI_HTTP_PATH;?>/userReportPrint.php?'+qstr;
  window.open(path,"UserReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function exportUserReport(){
  var roleId=document.getElementById('roleId1').value;
  var qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField+'&roleId='+roleId;
  var path='<?php echo UI_HTTP_PATH;?>/userReportCSV.php?'+qstr;
  window.location=path;
}

function printPermissionReport(){
  var roleId=document.getElementById('roleId2').value;
  var roleName=document.getElementById('roleName2').value.split('(')[0];
  var qstr=qstr+"&sortOrderBy="+listObj2.sortOrderBy+"&sortField="+listObj2.sortField+'&roleId='+roleId+'&roleName='+escape(roleName);
  var path='<?php echo UI_HTTP_PATH;?>/rolePermissionReportPrint.php?'+qstr;
  window.open(path,"RolePermissionReportPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function exportPermissionReport(){
  var roleId=document.getElementById('roleId2').value;
  var qstr=qstr+"&sortOrderBy="+listObj2.sortOrderBy+"&sortField="+listObj2.sortField+'&roleId='+roleId;
  var path='<?php echo UI_HTTP_PATH;?>/rolePermissionReportCSV.php?'+qstr;
  window.location=path;
}

</script>

</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Role/listRoleContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php
// $History: listRole.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 16/12/09   Time: 12:51
//Updated in $/LeapCC/Interface
//Corrected DIV width
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 15/12/09   Time: 18:46
//Updated in $/LeapCC/Interface
//Made UI changes in Role Master module
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/10/09    Time: 16:41
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001726,
//00001714,
//00001713
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 10/07/09   Time: 17:29
//Updated in $/LeapCC/Interface
//Corrected "Edit File Path"
//
//*****************  Version 3  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Interface
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:32a
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/05/08   Time: 1:51p
//Updated in $/Leap/Source/Interface
//role condition update
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:32a
//Updated in $/Leap/Source/Interface
//Added functionality for role report print
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 12:28p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:22p
//Updated in $/Leap/Source/Interface
//Created Role(Role Master) Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 2:58p
//Created in $/Leap/Source/Interface
//Initial checkin
?>