<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax functions used in Branch Form
//
//
// Author :Arvind Singh Rawat
// Created on : 12-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BranchMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Branch/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Branch Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('branchName',' Name','width="30%"','',true), 
                               new Array('branchCode','Abbr.','width="30%"','',true), 
				new Array('miscReceiptPrefix','Misc. Receipt Preifix','width="20%"','',true),
                               new Array('studentCount','Student','width="20%"','align="right"',true), 
                               new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Branch/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBranch';   
editFormName   = 'EditBranch';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteBranch';
divResultName  = 'results';
page=1; //default page
sortField = 'branchName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 var flag = false;
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form

function validateAddForm(frm, act) {
          
       
    var fieldsArray = new Array(new Array("branchName","<?php echo ENTER_BRANCH_NAME;?>"),
                                new Array("branchCode","<?php echo ENTER_BRANCH_CODE;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
       if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
          //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
          messageBox(fieldsArray[i][1]);
          eval("frm."+(fieldsArray[i][0])+".focus();");
          return false;
          break;
       }
    /* else {
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),"") && fieldsArray[i][0]=='branchCode'  ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ACCEPT_ALPHABETS_NUMERIC_ABBR;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            else {
               unsetAlertStyle(fieldsArray[i][0]);
            }
        } */
    }
    
    if(act=='Add') {
        addBranch(); 
        return false;
    }
    else if(act=='Edit') {
        editBranch();     
        return false;
    }
}

//This function adds form through ajax 

function addBranch() {
         url = '<?php echo HTTP_LIB_PATH;?>/Branch/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {branchName: trim(document.addBranch.branchName.value), 
                          branchCode: trim(document.addBranch.branchCode.value),
			 miscReceiptPrefix: trim(document.addBranch.miscReceiptPrefix.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
               
                     hideWaitDialog(true);
                     if("<?php echo BRANCH_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                         messageBox("<?php echo BRANCH_ALREADY_EXIST;?>"); 
                         document.addBranch.branchName.focus();
                         return false;
                         //location.reload();
                     }
                     if("<?php echo ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                         messageBox("<?php echo ABBR_ALREADY_EXIST;?>");   
                         document.addBranch.branchCode.focus();
                         return false;
                         //location.reload();
                     }
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddBranch');
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
   document.addBranch.branchCode.value = '';
   document.addBranch.branchName.value = '';
   
   document.addBranch.branchName.focus();
}

//This function edit form through ajax 

function editBranch() {
         url = '<?php echo HTTP_LIB_PATH;?>/Branch/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {branchId: trim(document.editBranch.branchId.value), 
                          branchName: trim(document.editBranch.branchName.value), 
                          branchCode: trim(document.editBranch.branchCode.value),
			  miscReceiptPrefix: trim(document.editBranch.miscReceiptPrefix.value)
			},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                      if("<?php echo BRANCH_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                         messageBox("<?php echo BRANCH_ALREADY_EXIST;?>"); 
                         document.editBranch.branchName.focus();
                         return false;
                         //location.reload();
                     }
                     if("<?php echo ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)) {
                         messageBox("<?php echo ABBR_ALREADY_EXIST;?>");   
                         document.editBranch.branchCode.focus();
                         return false;
                         //location.reload();
                     }
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBranch');
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

function deleteBranch(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Branch/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {branchId: id},
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


//This function populates values in edit form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Branch/ajaxGetValues.php';   
         new Ajax.Request(url,
           {      
             method:'post',
             parameters: {branchId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                hideWaitDialog(true);
                j = eval('('+transport.responseText+')');
                document.editBranch.branchCode.value = j.branchCode;
                document.editBranch.branchName.value = j.branchName;
                document.editBranch.branchId.value = j.branchId;
                document.editBranch.miscReceiptPrefix.value = j.miscReceiptPrefix;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {  
    var path='<?php echo UI_HTTP_PATH;?>/listBranchPrint.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayBranchList","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var path='<?php echo UI_HTTP_PATH;?>/displayBranchCSV.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Branch/listBranchContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>
</body>
</html>
<?php 
//$History: listBranch.php $
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/21/09   Time: 10:34a
//Updated in $/LeapCC/Interface
//print and CSV sorting order updated (Bug No. 1817)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Interface
//search & conditions updated
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/02/09    Time: 2:56p
//Updated in $/LeapCC/Interface
//formatting & validations, conditions updated 
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/02/09    Time: 1:24p
//Updated in $/LeapCC/Interface
//condition comments (branchName all chars format allow)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/02/09    Time: 12:35p
//Updated in $/LeapCC/Interface
//condition & required parameter updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/27/09    Time: 3:29p
//Updated in $/LeapCC/Interface
//corrected caption name for print window
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/26/09    Time: 5:25p
//Updated in $/LeapCC/Interface
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/19/09    Time: 3:17p
//Updated in $/LeapCC/Interface
//print report sort order update
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
//*****************  Version 13  *****************
//User: Parveen      Date: 11/05/08   Time: 4:49p
//Updated in $/Leap/Source/Interface
//Define module-access - added
//
//*****************  Version 12  *****************
//User: Arvind       Date: 10/15/08   Time: 5:58p
//Updated in $/Leap/Source/Interface
//added print button
//
//*****************  Version 11  *****************
//User: Arvind       Date: 9/10/08    Time: 5:41p
//Updated in $/Leap/Source/Interface
//table width modified
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/19/08    Time: 6:50p
//Updated in $/Leap/Source/Interface
//used common error maessage for validations
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/07/08    Time: 3:15p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/18/08    Time: 2:13p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/18/08    Time: 11:35a
//Updated in $/Leap/Source/Interface
//added
// flag variable
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/30/08    Time: 3:59p
//Updated in $/Leap/Source/Interface
//added  a new delete function and a dynamic table function which will
//call table listing through ajax
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/13/08    Time: 12:52p
//Updated in $/Leap/Source/Interface
//comments modified
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/13/08    Time: 12:03p
//Updated in $/Leap/Source/Interface
//Make $history a comment
//
//*****************  Version 1  *****************
//User: Administrator Date: 6/12/08    Time: 8:17p
//Created in $/Leap/Source/Interface
//New Files Checkin

?>
