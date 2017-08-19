<?php
//-------------------------------------------------------
//  This File contains logic for company select
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
define('MODULE','CompanySelect');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Company Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>

<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
								new Array('srNo','#','width="2%"','',false), 
								new Array('companyName','Company Name','width="15%"','',true) , 
								new Array('financialYear','Financial Year','width="15%"','',false), 
								new Array('action1','Action','width="6%"','align="right"',false)
							);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Accounts/Company/ajaxInitSelectList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'Company';   
editFormName   = 'Company';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCompany';
divResultName  = 'results';
page=1; //default page
sortField = 'companyName';
sortOrderBy    = 'ASC';
actionUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Company/ajaxInitSelect.php';
// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   	displayWindow(dv,w,h);
    populateValues(id);   
}

var formSubmitted = false;


function setMode(str) {
	mode = str;
}

function validateAddForm(frm) {


	form = document.companyForm;
	if (form.companyId.value != '') {
		mode = 'Edit';
	}
    
	if(false == doFieldValidation(frm)) {
		return false;
	}



    //messageBox (act)
    if(mode=='Add') {
        addCompany();
        return false;
    }
    else if(mode=='Edit') {
        editCompany();  
        return false;
    }
}
function selectCompany(companyId) {
		var pars='companyId='+companyId;
		 pars += '&mode=Select';
       
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

function blankValues() {
	form = document.companyForm;
	form.companyId.value = '';
	form.companyName.value = '';
	form.address.value = '';
	form.email.value = '';
	form.phone.value = '';
	form.companyName.focus();
}
function editCompany() {
         
		 
		 var pars = generateQueryString('companyForm');
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
					 hiddenFloatingDiv('Company');
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



function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Accounts/Company/ajaxGetValues.php';
		 var pars = 'companyId='+id;
		 var form = document.companyForm; 

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
					form.companyId.value = id;
					form.companyName.value = j.companyName;
					form.address.value = j.address;
					form.email.value = j.email;
					form.phone.value = j.phone;
					form.fyearFrom.value = j.fyearFrom;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Accounts/Company/listCompanySelectContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>
<?php 
// $History: listCompanySelect.php $
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/10/09    Time: 6:47p
//Updated in $/LeapCC/Interface/Accounts
//removed access rights, placed accidently
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/10/09    Time: 4:08p
//Created in $/LeapCC/Interface/Accounts
//file added
//



?>
