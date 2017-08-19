<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Range Level Form
//
//
// Author :Ajinder Singh
// Created on : 20-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RangeLevelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/RangeLevel/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Range Level Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false), new Array('rangeFrom','From Range','width="30%"','',true) , new Array('rangeTo','To Range','width="30%"','',true), new Array('rangeLabel','Range Label','width="30%"','',true), new Array('action','Action','width="5%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RangeLevel/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRange';   
editFormName   = 'EditRange';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRange';
divResultName  = 'results';
page=1; //default page
sortField = 'rangeFrom';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
	displayWindow(dv,w,h);
    populateValues(id);   
}

function validateAddForm(frm, act) {
    
    //messageBox (act)
    var fieldsArray = new Array(new Array("rangeFrom","Enter Range From"), new Array("rangeTo","Enter Range To"));
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

	rangeFrom = document.getElementById("rangeFrom").value;
	rangeTo = document.getElementById("rangeTo").value;

	if(false == isInteger(rangeFrom)) {
		messageBox("Enter Valid numeric value in range from");
		document.getElementById("rangeFrom").focus();
		return false;
	}
	if(false == isInteger(rangeTo)) {
		messageBox("Enter Valid numeric value in range to");
		document.getElementById("rangeTo").focus();
		return false;
	}

	if (parseInt(rangeFrom) > parseInt(rangeTo)) {
		messageBox("From range can not be larger than To range");
		return false;
	}

	if (parseInt(rangeFrom) > 100) {
		messageBox("From range can not be larger 100%");
		return false;
	}
	if (parseInt(rangeTo) > 100) {
		messageBox("To range can not be larger 100%");
		return false;
	}

    if(act=='Add') {
        addRange();
        return false;
    }
    else if(act=='Edit') {
        editRange();  
        return false;
    }
}
function addRange() {
         url = '<?php echo HTTP_LIB_PATH;?>/RangeLevel/ajaxInitAdd.php';

		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {rangeFrom: (document.addRange.rangeFrom.value), rangeTo: (document.addRange.rangeTo.value), rangeLabel: (document.addRange.rangeLabel.value)},
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
						 hiddenFloatingDiv('AddRange');
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
   document.addRange.rangeFrom.value = '';
   document.addRange.rangeTo.value = '';
   document.addRange.rangeLabel.value = '';
   document.addRange.rangeFrom.focus();
}
function editRange() {
         url = '<?php echo HTTP_LIB_PATH;?>/RangeLevel/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {rangeId: (document.editRange.rangeId.value), rangeFrom: (document.editRange.rangeFrom.value), rangeTo: (document.editRange.rangeTo.value), rangeLabel:(document.editRange.rangeLabel.value)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditRange');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
					 //location.reload();
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteRange(id) {  
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/RangeLevel/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {rangeId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/RangeLevel/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {rangeId: id},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editRange.rangeId.value = id;
                   document.editRange.rangeFrom.value = j.rangeFrom;
                   document.editRange.rangeTo.value = j.rangeTo;
                   document.editRange.rangeLabel.value = j.rangeLabel;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/RangeLevel/listRangeLevelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listRangeLevel.php $
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/27/09    Time: 4:26p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1279,1277,1280,1278
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
//*****************  Version 2  *****************
//User: Parveen      Date: 11/10/08   Time: 11:54a
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 8/20/08    Time: 3:02p
//Created in $/Leap/Source/Interface
//file added for range level masters

?>