<?php
//-------------------------------------------------------
//  This File contains logic for groups
//
//
// Author :Ajinder Singh
// Created on : 10-aug-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Groups');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::ifCompanyNotSelected();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Groups Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH;?>/autosuggest.css" />
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/json.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/zxml.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>/autosuggest.js"></script>


<script type="text/javascript">
window.onload = function () {
	var oTextbox = new AutoSuggestControl(document.getElementById("parentGroup"), new SuggestionProvider(), 'companyGroups');
}
</script>

<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
								new Array('srNo','#','width="10%"','',false), 
								new Array('groupName','Group Name','width="30%"','',true) , 
								new Array('parentGroupName','Parent Group','width="30%"','',true), 
								new Array('action','Action','width="10%"','align="right"',false)
							);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Accounts/Groups/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'Groups';   
editFormName   = 'Groups';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGroup';
divResultName  = 'results';
page=1; //default page
sortField = 'groupName';
sortOrderBy    = 'ASC';
actionUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Groups/ajaxInitAction.php';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
	
   	displayWindow(dv,w,h);
    populateValues(id);   
}

var mode = '';

function setMode(str) {
	mode = str;
}

function validateAddForm(frm) {

	form = document.groupsForm;
	if (form.groupId.value != '') {
		mode = 'Edit';
	}

    //messageBox (act)
    var fieldsArray = new Array(new Array("groupName","<?php echo ENTER_GROUP_NAME;?>"), new Array("parentGroup","<?php echo ENTER_PARENT_GROUP;?>"));
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		/*
		else {
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
		}
		*/
    }
    if(mode=='Add') {
        addGroup();
        return false;
    }
    else if(mode=='Edit') {
        editGroup();  
        return false;
    }
}
function addGroup() {
         
		 var pars = generateQueryString('groupsForm');
		 pars += '&mode='+mode;

		 new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
					 }
					 else {
						 hiddenFloatingDiv('Groups');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
					 }
				 } 
				 else {
					messageBox(trim(transport.responseText)); 
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
	form = document.groupsForm;
	form.groupId.value = '';
	form.groupName.value = '';
	form.parentGroup.value = '';
	form.groupName.focus();
}
function editGroup() {
         
		 var pars = generateQueryString('groupsForm');
		 pars += '&mode=Edit';
       
         new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('Groups');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
					 //location.reload();
				 }
				 else {
					 messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteGroup(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         var pars = 'groupId='+id+'&mode=Delete';
         new Ajax.Request(actionUrl,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
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

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Accounts/Groups/ajaxGetValues.php';
		 var pars = 'groupId='+id+'&mode='+mode;
		 var form = document.groupsForm; 

         new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    
				   form.groupId.value = id;
                   form.groupName.value = j.groupName;
                   form.parentGroup.value = j.parentGroup;

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Accounts/Groups/listGroupsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>
<?php 
// $History: listGroups.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>
