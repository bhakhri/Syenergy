<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Config Form
//
//
// Author :Ajinder Singh
// Created on : 08-Sep-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ConfigMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Configs/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Config Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="10%"','',false), new Array('labelName','Label','width="40%"','',false), new Array('value','Value','width="40%"','',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Config/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddConfig';   
editFormName   = 'EditConfig';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteConfig';
divResultName  = 'results';
page=1; //default page
sortField = 'param';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
	displayWindow(dv,w,h);
    populateValues(id);   
}

function validateAddForm(frm) {
	url = '<?php echo HTTP_LIB_PATH;?>/Configs/ajaxInitSave.php';
	form = document.forms[0].name;
	var pars = generateQueryString(form);
	new Ajax.Request(url, 
		{
			method:'post',
			parameters: pars,
			asynchronous:false,
			onCreate: function(){
			showWaitDialog(true);
		},
			onSuccess: function(transport) {
				hideWaitDialog(true);
				messageBox(trim(transport.responseText)); 
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
			});
			return false;
}

function blankValues() {
   document.addConfig.param.value = '';
   document.addConfig.label.value = '';
   document.addConfig.val.value = '';
   document.addConfig.param.focus();
}
function editConfig() {
         url = '<?php echo HTTP_LIB_PATH;?>/Config/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {param: (document.editConfig.param.value), label: (document.editConfig.label.value), val: (document.editConfig.val.value), configId:(document.editConfig.configId.value)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditConfig');
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

function deleteConfig(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Config/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {configId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/Config/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {configId: id},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editConfig.configId.value = id;
                   document.editConfig.param.value = j.param;
                   document.editConfig.label.value = j.labelName;
                   document.editConfig.val.value = j.value;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Configs/listConfigsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listConfigs.php $
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
//*****************  Version 3  *****************
//User: Parveen      Date: 11/10/08   Time: 11:21a
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/08/08   Time: 4:52p
//Updated in $/Leap/Source/Interface
//fixed bug
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 9/08/08    Time: 7:18p
//Created in $/Leap/Source/Interface
//file added for configs master
//

?>