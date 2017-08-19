<?php
//-------------------------------------------------------
// Purpose: To generate the list of states from the database, and have add/edit/delete, search 
// functionality 
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentProgramFee');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Program Fee Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                 new Array('srNo','#','width="2%"','',false), 
                                 new Array('programFeeName','Program Fee','width="96%"','',true),
                                 new Array('action','Action','width="4%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ProgramFees/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddProgramFee';   
editFormName   = 'EditProgramFee';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteProgramFee';
divResultName  = 'results';
page=1; //default page
sortField = 'programFeeName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("programFeeName","<?php echo ENTER_PROGRAM_FEE_NAME;?>")
                               );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(act=='Add') {
        addProgramFee();
        return false;
    }
    else if(act=='Edit') {
        editProgramFee();
        return false;
    }
}
function addProgramFee() {
         var url = '<?php echo HTTP_LIB_PATH;?>/ProgramFees/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  programFeeName: (document.addProgramFee.programFeeName.value)
             },
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
                             hiddenFloatingDiv('AddProgramFee');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
						messageBox(trim(transport.responseText));
                        document.addProgramFee.programFeeName.focus();
                     }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function deleteProgramFee(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         var url = '<?php echo HTTP_LIB_PATH;?>/ProgramFees/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {programFeeId: id},
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
function blankValues() {
   document.addProgramFee.programFeeName.value = '';
   document.addProgramFee.programFeeName.focus();
}
function editProgramFee() {  
         var url = '<?php echo HTTP_LIB_PATH;?>/ProgramFees/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 programFeeId: (document.editProgramFee.programFeeId.value), 
                 programFeeName: (document.editProgramFee.programFeeName.value)
             },
             onCreate: function(){
                 showWaitDialog(true);
             },             
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditProgramFee');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.editProgramFee.programFeeName.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/ProgramFees/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {programFeeId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditProgramFee');
                        messageBox("<?php echo PROGRAM_FEE_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.editProgramFee.programFeeName.value = j.programFeeName;
                   document.editProgramFee.programFeeId.value = j.programFeeId;
                   document.editProgramFee.programFeeName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/ProgramFees/listProgramFeeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
sendReq(listURL,divResultName,searchFormName,'');
</script>