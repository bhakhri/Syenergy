<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF USERS ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ManageUsers');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/ManageUser/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: User Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('userName','User Name','width="15%"','',true) , 
                               new Array('roleName','Role Name','width="15%"','',true) , 
                               new Array('roleUserName','Name','width="25%"','',true),
                               new Array('displayName','Display Name','width="25%"','',true),
                               new Array('userStatus','Active','width="10%"','align="center"',true),
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ManageUser/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddUser';   
editFormName   = 'EditUser';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteUser';
divResultName  = 'results';
page=1; //default page
sortField = 'userName';
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
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    document.EditUser.userName.disabled=true;
    //document.getElementById('EditUser').reset(); 
    displayWindow(dv,w,h);
    populateValues(id);   
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("userName","<?php echo ENTER_USER_NAME1; ?>"),
    new Array("userPwd","<?php echo ENTER_USER_PASSWORD1; ?>"),
    new Array("userPwd2","<?php echo ENTER_USER_PASSWORD12; ?>"));
//   new Array("displayName","<?php echo 'Enter valid name;' ?>"));
    var len = fieldsArray.length;
	
     if(!isAlphabetCharacters(frm.displayName.value)){
				 alert("<?php echo'Please enter valid name'; ?>");
				 return false;
  }
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
            if((eval('frm.'+(fieldsArray[i][0])+'.value.split(" ").length'))>1 && fieldsArray[i][0]=='userName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_USER_NAME_SPACE; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <3 && fieldsArray[i][0]=='userName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo USER_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length <6 && fieldsArray[i][0]=='userPwd' ) {
                alert("<?php echo USER_PASSWORD_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
            if(!trim_pwd_space(eval("frm."+(fieldsArray[i][0])+".value"))  && (fieldsArray[i][0]=='userPwd' || fieldsArray[i][0]=='userPwd2') ) {
                alert("<?php echo ENTER_PASSWORD_SPACE; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
			
          
            if(eval("frm.userPwd.value")!=eval("frm.userPwd2.value") ) {
                if(isEmpty(eval("frm.userPwd.value"))){
                alert("<?php echo ENTER_USER_PASSWORD1; ?>");
                eval("frm.userPwd.focus();");
                return false;
                break;  
                }
                if(isEmpty(eval("frm.userPwd2.value"))){
                alert("<?php echo ENTER_USER_PASSWORD12; ?>");
                eval("frm.userPwd2.focus();");
                return false;
                break;  
                }
                alert("<?php echo PASSWORD_NOT_MATCH; ?>");
                eval("frm.userPwd.focus();");
                return false;
                break;
            }   
                       
            /*if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && !(fieldsArray[i][0]=='userPwd' || fieldsArray[i][0]=='userPwd2')) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
    }
    
    
  /*  if+((frm.role.value=='1') && (frm.employeeId.value=='Select' || frm.employeeId.value=='')) {
       alert("<?php echo "Select Employee"; ?>");
       frm.employeeId.focus();
       return false;
    } */
    
			 
    if(act=='Add') {
        addManageUser();
        return false;
    }
    else if(act=='Edit') {
        editManageUser();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW USER
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addManageUser() {

		//alert(document.AddUser.roleId.length);
		//lc = eval("document.AddUser.roleId.length");
		var selected=0;
		var selectedDefault=0;
		var selectedRoleId='';
		formx = document.AddUser;

		for(var i=1;i<formx.length;i++){

			if(formx.elements[i].type=="checkbox"){
				
				if((formx.elements[i].checked) && (formx.elements[i].name=="roleId[]")){
					
					querySeprator ='';
					if(selectedRoleId!=''){

						querySeprator = ",";
					}
					selectedRoleId +=querySeprator+(formx.elements[i].value);
					selected++; 
				}
			}
			if(formx.elements[i].type=="radio"){
	
				if((formx.elements[i].checked) && (formx.elements[i].name=="defaultRole")){
					
					selectedDefault++; 
					defaultValue = formx.elements[i].value;
				}
			}
		}
		 
		if(selected==0){
			alert("<?php echo SELECT_ONE_ROLE_NAME?>");
			return false;
		}

		if(selectedDefault==0){

			alert("<?php echo SELECT_ONE_DEFAULT_ROLE_NAME?>");
			return false;
		}
		
        url = '<?php echo HTTP_LIB_PATH;?>/ManageUser/ajaxInitAdd.php';
        new Ajax.Request(url,
        {
             method:'post',
             parameters: {userName: (trim(document.AddUser.userName.value)),
             userPwd: (trim(document.AddUser.userPwd.value)), 
             selectedRoleId: (selectedRoleId),
			 defaultValue: (defaultValue),
             //employeeId: (document.AddUser.employeeId.value),
             displayName: (document.AddUser.displayName.value),
             isActive : (document.AddUser.isActive[0].checked==true?1:0)
             },
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
                         else if("<?php echo USER_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo USER_ALREADY_EXIST ;?>"); 
                         document.AddUser.userName.focus();
                        } 
                         else {
                             hiddenFloatingDiv('AddUser');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
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
//THIS FUNCTION IS USED TO DELETE A NEW USER
//  id=userId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteUser(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/ManageUser/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddManageUser" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
    
   //document.AddUser.employeeId.disabled=false; 
   //document.AddUser.displayName.disabled=false; 
   //document.EditUser.employeeId.value='Select';
   document.AddUser.userName.value = '';
   document.AddUser.userPwd.value = '';     
   document.AddUser.userPwd2.value = '';     
   document.AddUser.displayName.value = '';
   document.AddUser.isActive[0].checked=true; 
   document.AddUser.userName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT AN USER 
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editManageUser() {

		var selected=0;
		var selectedDefault=0;
		var selectedRoleId='';
		formx = document.EditUser;
		for(var i=1;i<formx.length;i++){

			if(formx.elements[i].type=="checkbox"){
				
				if((formx.elements[i].checked) && (formx.elements[i].name=="roleId[]")){
					
					querySeprator ='';
					if(selectedRoleId!=''){

						querySeprator = ",";
					}
					selectedRoleId +=querySeprator+(formx.elements[i].value);
					selected++; 
				}
			}
			if(formx.elements[i].type=="radio"){
	
				if((formx.elements[i].checked) && (formx.elements[i].name=="defaultRole")){
					
					selectedDefault++; 
					defaultValue = formx.elements[i].value;
				}
			}
		}
		if((formx.defaultRoleId.value!=3 && formx.defaultRoleId.value!=4)){
			if(selected==0){

				alert("<?php echo SELECT_ONE_ROLE_NAME?>");
				return false;
			}

			if(selectedDefault==0){

				alert("<?php echo SELECT_ONE_DEFAULT_ROLE_NAME?>");
				return false;
			}

			if(document.getElementById("roleId"+defaultValue).checked==false){
		      alert("<?php echo DEFAULT_ROLE_SELECTED?>");
			  return false;
		   }
	
		}
		else{
			defaultValue=formx.defaultRoleId.value;
		}
        url = '<?php echo HTTP_LIB_PATH;?>/ManageUser/ajaxInitEdit.php';
                     
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userId: (document.EditUser.userId.value),
              userName: (trim(document.EditUser.userName.value)) , 
              userPwd: (trim(document.EditUser.userPwd.value)), 
			  //roleId: (document.EditUser.role.value),
              defaultRoleId: (document.EditUser.defaultRoleId.value),
              displayName: (document.EditUser.displayName.value),
			  selectedRoleId: (selectedRoleId),
			  defaultValue: (defaultValue),
              isActive : (document.EditUser.isActive[0].checked==true?1:0)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
				       
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditUser');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo USER_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo USER_ALREADY_EXIST ;?>"); 
                         document.EditUser.userName.focus();
                    } 
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

function roleCheck(id)
{
	//alert("roleId"+id);
	//alert(document.getElementById('roleId1').checked);
    /*if(id>=2 && id<=4) {
      document.AddUser.employeeId.disabled=true;
      document.EditUser.displayName.disabled=true;
    }
    else  {
       document.EditUser.employeeId.disabled=false;
       document.EditUser.displayName.disabled=false;
    }*/
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditManageUser" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (1.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         
         var url = '<?php echo HTTP_LIB_PATH;?>/ManageUser/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditUser');
                        messageBox("<?php USER_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   j = eval('('+transport.responseText+')');
				   document.EditUser.reset(); 
                   
                   if(j.info[0].userStatus==1){
                       document.EditUser.isActive[0].checked=true;
                    }
                    else if(j.info[0].userStatus==0){
                       document.EditUser.isActive[1].checked=true;
                    }
                    
				   //alert(transport.responseText);
				   if(j.info[0].roleId==4){
					
						document.getElementById('showRole').style.display='none';
						document.getElementById('showDisplayName').style.display='none';
						document.getElementById('showOnlyRole').style.display='';
						document.getElementById('showOtherRole').innerHTML='Student';
						
				   }
				   else if(j.info[0].roleId==3){
					
						document.getElementById('showRole').style.display='none';
						document.getElementById('showDisplayName').style.display='none';
						document.getElementById('showOnlyRole').style.display='';
						document.getElementById('showOtherRole').innerHTML='Parent';
						
				   }
				   else{
				   
						document.getElementById('showRole').style.display='';
						document.getElementById('showOnlyRole').style.display='none';
						document.getElementById('showDisplayName').style.display='';
				   }
                   //document.EditUser.employeeId.value='Select';
                   document.EditUser.userName.value = j.info[0].userName;
                   //document.EditUser.userPwd.value = j.userPassword;
                   //document.EditUser.userPwd2.value = j.userPassword;
                   document.EditUser.userPwd.value = "1****1";
                   document.EditUser.userPwd2.value = "1****1";
                   //document.EditUser.role.value = j.info[0].roleId;
                   document.EditUser.userId.value = j.info[0].userId;
				
				   //document.EditUser.defaultRoleId.value = j.info[0].roleId;
                   document.EditUser.defaultRoleId.value = j.info[0].dafaultRole;
                   //document.EditUser.employeeId.value=j.info[0].employeeId;
                   document.EditUser.displayName.value=j.info[0].displayName;
				    
				   if(j.info[0].otherRole==''){

					   for(k=0;k<j.totalRecords;k++){
					   
							document.getElementById("roleId"+j.info[0].roleId).checked=true;
							document.getElementById("defaultRole"+j.info[0].roleId).checked=true;
					   }
				   }
				   else{
				   
						for(k=0;k<j.totalRecords;k++){
							document.getElementById("roleId"+j.info[k].otherRole).checked=true;
							/*
                            if(j.info[0].roleId==j.info[k].otherRole){
								document.getElementById("defaultRole"+j.info[k].roleId).checked=true;
							}
                            */
					   }
                       var len=document.EditUser.defaultRole.length;
                       for(var x=0;x<len;x++){
                           if(document.EditUser.defaultRole[x].value==j.info[0].dafaultRole){
                              document.EditUser.defaultRole[x].checked=true;
                              break;
                           }
                       }
				   }
				   
                   /* if(document.EditUser.role.value>=2 && document.EditUser.role.value<=4) {
                        document.EditUser.employeeId.disabled=true;
                        document.EditUser.displayName.disabled=true;
                    }
                    else  {
                       document.EditUser.employeeId.disabled=false;
                       document.EditUser.displayName.disabled=false;
                    }*/
                    if(j.info[0].userStatus==1){
                       document.EditUser.isActive[0].checked=true;
                    }
                    else if(j.info[0].userStatus==0){
                       document.EditUser.isActive[1].checked=true;
                    }
                   document.EditUser.userName.focus();
                   
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-----------------------------------------------------------------------------
//Purpose:Trim leading and trailing from password fields
//Author: Dipanjan Bhattacharjee
//Date:25.09.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------
function trim_pwd_space(value){
    var str=value.split(' ');
    if(str.length >1){
     return false
    }
   return true; 
}

/* function to print user report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/manageUserReportPrint.php?'+qstr;
    window.open(path,"ManageUserReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}
function printReportCSV() {
	 var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/manageUserReportCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/ManageUser/listManageUserContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

<?php 
// $History: listManageUser.php $ 
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 12/14/09   Time: 6:04p
//Updated in $/LeapCC/Interface
//updated code to add new field 'name' that shows name of user
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 21/09/09   Time: 16:43
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug id---00001577
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 28/07/09   Time: 17:53
//Updated in $/LeapCC/Interface
//Added "userStatus" field in manage user module and added the check in
//login page that if a user is in active then he/she can not login
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/01/09    Time: 11:17a
//Updated in $/LeapCC/Interface
//Updated manage user module in which multiple role can be selected to
//single user
//
//*****************  Version 6  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Interface
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/28/09    Time: 4:40p
//Updated in $/LeapCC/Interface
//New File Added in displayName
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/09/08   Time: 10:32a
//Updated in $/LeapCC/Interface
//condition update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 12/08/08   Time: 5:15p
//Updated in $/LeapCC/Interface
//employee Id code set
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/24/08   Time: 1:22p
//Updated in $/Leap/Source/Interface
//Corrected user password min. limit  from 3 to 6 characters
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:44p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 10/24/08   Time: 11:46a
//Updated in $/Leap/Source/Interface
//Added functionality for manage user report print
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/01/08   Time: 2:38p
//Updated in $/Leap/Source/Interface
//Corrected Problem of User Editing
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/25/08    Time: 4:34p
//Updated in $/Leap/Source/Interface
//Corrected javascript validations
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/24/08    Time: 6:51p
//Updated in $/Leap/Source/Interface
//Corrected javascript error:user name taking space in betwwen
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/21/08    Time: 1:37p
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
//User: Dipanjan     Date: 7/01/08    Time: 7:34p
//Updated in $/Leap/Source/Interface
//Created ManageUser Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/01/08    Time: 4:05p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>