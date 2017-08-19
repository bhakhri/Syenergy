<?php
//-------------------------------------------------------
// Purpose: To generate assign time table label to class from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (30.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InvIssueItems');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Issue Non-Consumable Items</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(	new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
								new Array('srNo','#','width="3%"','',false), 
								new Array('subItemCode','Sub Item Name','width="20%"','',true),
								new Array('status','Status','width="20%"','',true),
								new Array('deptName','Current Store','width="20%"','',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InvIssueItems/scInvIssueItemsList.php';
searchFormName = 'listForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'subItemCode';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
       displayWindow(dv,w,h);
       populateValues(id);
}

function getItems(){

	if(isEmpty(document.getElementById('store').value)){
	   messageBox("<?php echo SELECT_DEPTT_STORE;?>");
	   document.getElementById('store').focus();
	   return false;
	}
	else if (isEmpty(document.getElementById('itemCategory').value)) {
		messageBox("<?php echo SELECT_ITEM_CATEGORY;?>");
		document.getElementById('itemCategory').focus();
		return false;
	}
	else if (isEmpty(document.getElementById('itemName').value)) {
		messageBox("<?php echo SELECT_ITEM_TYPE;?>");
		document.getElementById('itemName').focus();
		return false;
	}
	else {
		document.getElementById('saveDiv1').style.display='';
		document.getElementById('showTitle').style.display='';
		document.getElementById('showData').style.display='';
		document.getElementById('showIssuedItem').style.display='';
		sendReq(listURL,divResultName,'listForm','',false);
	    getDeptt(document.getElementById('store').value);
	}
		 
}
var date = "<?php echo date('Y-m-d'); ?>";
function clearText(){
    document.getElementById('store').value='';
	document.getElementById('itemCategory').value='';
	document.getElementById('itemName').value='';
	document.getElementById('issuedItemStatus').value='';
	document.getElementById('issuedTo').value='';
	document.getElementById('issuedDate').value = date; 
	document.getElementById('showTitle').style.display='none';
	document.getElementById('showData').style.display='none';
	document.getElementById('saveDiv1').style.display='none';
	document.getElementById('results').innerHTML="";
	document.getElementById('issTo').innerHTML = '';
	document.getElementById('issDate').innerHTML = '';
}

function clearData(){
	document.getElementById('itemCategory').value='';
	document.getElementById('itemName').value='';
	document.getElementById('issuedItemStatus').value='';
	document.getElementById('issuedTo').value='';
	document.getElementById('issuedDate').value = date; 
	document.getElementById('showTitle').style.display='none';
	document.getElementById('showData').style.display='none';
	document.getElementById('saveDiv1').style.display='none';
	document.getElementById('results').innerHTML="";
	document.getElementById('issTo').innerHTML = '';
	document.getElementById('issDate').innerHTML = '';
}

function clearItemData(){
	document.getElementById('itemName').value='';
	document.getElementById('issuedItemStatus').value='';
	document.getElementById('issuedTo').value='';
	document.getElementById('issuedDate').value = date; 
	document.getElementById('showTitle').style.display='none';
	document.getElementById('showData').style.display='none';
	document.getElementById('saveDiv1').style.display='none';
	document.getElementById('results').innerHTML="";
	document.getElementById('issTo').innerHTML = '';
	document.getElementById('issDate').innerHTML = '';
}

function clearData1(){
	document.getElementById('issuedItemStatus').value='';
	document.getElementById('issuedTo').value='';
	document.getElementById('issuedDate').value = date; 
	document.getElementById('showTitle').style.display='none';
	document.getElementById('showData').style.display='none';
	document.getElementById('saveDiv1').style.display='none';
	document.getElementById('results').innerHTML="";
	document.getElementById('issTo').innerHTML = '';
	document.getElementById('issDate').innerHTML = '';
}



function getAction() {
	if(document.getElementById('issuedItemStatus').value == 2) {
		document.getElementById('issTo').innerHTML = 'Issue To :'
		document.getElementById('issDate').innerHTML = 'Issue Date :'
	}
	if(document.getElementById('issuedItemStatus').value == 3) {
		document.getElementById('issTo').innerHTML = 'Transfer To :'
		document.getElementById('issDate').innerHTML = 'Transfer Date :'
	}
	if(document.getElementById('issuedItemStatus').value == 4) {
		document.getElementById('issTo').innerHTML = 'Return To :'
		document.getElementById('issDate').innerHTML = 'Return Date :'
	}
	if(document.getElementById('issuedItemStatus').value == '') {
		document.getElementById('issTo').innerHTML = ''
		document.getElementById('issDate').innerHTML = ''
	}
}

function insertForm() {
 
	 url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InvIssueItems/scInitIssuedItemsAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('listForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){

			 hideWaitDialog(true);
			 if("<?php echo ISSUE_SUCCESSFULLY;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 clearText();
					 return false;
			 }
			 else if("<?php echo TRANSFERRED_SUCCESSFULLY;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 clearText();
					 return false;
			 }
			 else if("<?php echo RETURNED_SUCCESSFULLY;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 clearText();
					 return false;
			 }
			 else if("<?php echo SELECT_ISSUED_STATUS;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo SELECT_ISSUED_TO;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo CANT_TRANSFER_ITEMS;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo CANT_RETURN_ITEMS;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo CANT_ISSUED_ITEMS;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo SELECT_TRANSFER_TO;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 else if("<?php echo SELECT_RETURN_TO;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }

			 else if("<?php echo DATE_NOT_LESS;?>" == trim(transport.responseText)) {  
					 messageBox(trim(transport.responseText));
					 return false;
			 }
			 
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

 

function validateAddForm(frm, act) {
    
	var selected=0;
	var selected1=0;
	//var midsemValueString = [];
	//var finalExamString = [];
	formx = document.listForm;
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].disabled){

			 {selected1++;}
		} 
	}
	if(selected1>0){

		alert("<?php echo SELECT_ITEM?>");
		return false;
	}
	for(var i=1;i<formx.length;i++){

		if(formx.elements[i].type=="checkbox"){

			if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]"))
			{selected++;}
		
		}
	}
	if(selected==0){

		alert("<?php echo SELECT_ATLEAST_ONE_ITEM?>");
		return false;
	}

	/*if(formx.reason.value=="") {
		alert("<?php echo ENTER_REASON?>");

		return false;
	}*/

	if(!dateDifference(document.getElementById('issuedDate').value,date,"-")){
	   messageBox("<?php echo DISCIPLINE_DATE_VALIDATION;?>"); 
	   document.getElementById('issuedDate').focus(); 
	   return false;
   }    

    insertForm();
	return false;
}

 
function doAll(){

	formx = document.listForm;
	if(formx.checkbox2.checked){
		for(var i=1;i<formx.length;i++){

			if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
			
				formx.elements[i].checked=true;
			}
		}
	}
	else{
		for(var i=1;i<formx.length;i++){
			
			if(formx.elements[i].type=="checkbox"){
			
				formx.elements[i].checked=false;
			}
		}
	}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getItemName() {
	form = document.listForm;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InvIssueItems/getItemName.php';
	var pars = 'itemCategoryId='+form.itemCategory.value;
	if (form.itemCategory.value=='') {
		form.itemName.length = null;
		addOption(form.itemName, '', 'Select');
		return false;
	}
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
			
			if(j==0) {
				form.itemName.length = null;
				addOption(form.itemName, '', 'Select');
				return false;
			}
			len = j.length;
			form.itemName.length = null;
			addOption(form.itemName, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.itemName, j[i].itemId, j[i].itemName);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getDeptt(value) {
	form = document.listForm;
	var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InvIssueItems/getDepttName.php';
	var pars = 'invDepttId='+value;
	if (form.store.value=='') {
		form.issuedTo.length = null;
		addOption(form.issuedTo, '', 'Select');
		return false;
	}
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
			
			if(j==0) {
				form.issuedTo.length = null;
				addOption(form.issuedTo, '', 'Select');
				return false;
			}
			len = j.length;
			form.issuedTo.length = null;
			addOption(form.issuedTo, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.issuedTo, j[i].invDepttId, j[i].invDepttAbbr);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(INVENTORY_TEMPLATES_PATH . "/InvIssueItems/invIssueItemsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: invIssueItems.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/24/10    Time: 10:05a
//Created in $/Leap/Source/Interface/INVENTORY
//new files for inventory issue items
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:20a
//Updated in $/Leap/Source/Interface
//put the check if class does exist in student cgpa then class cannot be
//freezed.
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/02/09    Time: 11:49a
//Created in $/Leap/Source/Interface
//new file for frozen class
//
?>