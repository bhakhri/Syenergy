<?php
//-------------------------------------------------------
//  This File contains logic for company
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
define('MODULE','CompanyCreate');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//UtilityManager::ifCompanyNotSelected();
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
								new Array('address','Address','width="25%"','',true), 
								new Array('email','Email','width="8%"','',true), 
								new Array('phone','Phone','width="8%"','align="right"',true), 
								new Array('action','Action','width="6%"','align="right"',false)
							);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Accounts/Company/ajaxInitList.php';
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
actionUrl = '<?php echo HTTP_LIB_PATH;?>/Accounts/Company/ajaxInitAction.php';
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

function doFieldValidation(frm) {

    var fieldsArray = new Array(new Array("companyName","<?php echo ENTER_COMPANY_NAME;?>"), new Array("address","<?php echo ENTER_COMPANY_ADDRESS;?>"), new Array("email","<?php echo ENTER_EMAIL_ADDRESS;?>"), new Array("phone","<?php echo ENTER_PHONE_NUMBER;?>"), new Array("fyearFrom","<?php echo SELECT_FINANCIAL_START;?>"));
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
function addCompany() {
         
		 
		 var pars = generateQueryString('companyForm');
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
						 hiddenFloatingDiv('Company');
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

function deleteCompany(id) {  
         if(false===confirm("<?php echo COMPANY_DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         var pars = 'companyId='+id+'&mode=Delete';
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
	require_once(TEMPLATES_PATH . "/Accounts/Company/listCompanyContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>
<?php 
// $History: listCompany.php $
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
