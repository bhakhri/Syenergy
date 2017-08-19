<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BudgetHeads');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/BudgetHeads/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Budget Heads Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('headName','Head Name','width="35%"','',true) , 
                               new Array('headAmount','Head Amount','width="10%"','align="right"',true), 
                               new Array('headTypeId','Head Type','width="30%"','',true) , 
                               new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BudgetHeads/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBudgetHeads';   
editFormName   = 'EditBudgetHeads';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBudgetHeads';
divResultName  = 'results';
page=1; //default page
sortField = 'headName';
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                    new Array("headName","<?php echo ENTER_BUDGET_HEAD_NAME;?>"),
                    new Array("headAmount","<?php echo ENTER_BUDGET_HEAD_AMT;?>"),
                    new Array("headTypeId","<?php echo SELECT_BUDGET_HEAD_TYPES;?>") 
        );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='headName' ) {
                messageBox("<?php echo BUDGET_HEAD_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
           if(!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='headAmount' ) {
                messageBox("<?php echo ENTER_DECIMAL_VALUE;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
           
           if((eval("frm."+(fieldsArray[i][0])+".value"))<0 && fieldsArray[i][0]=='headAmount' ) {
                messageBox("<?php echo ENTER_POSITIVE_VALUE;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
            
        }
     
    }
    if(act=='Add') {
        addBudgetHeads();
        return false;
    }
    else if(act=='Edit') {
        editBudgetHeads();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBudgetHeads() {
         var url = '<?php echo HTTP_LIB_PATH;?>/BudgetHeads/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 headName: (document.AddBudgetHeads.headName.value), 
                 headAmount: (document.AddBudgetHeads.headAmount.value), 
                 headTypeId: (document.AddBudgetHeads.headTypeId.value)
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
                         else {
                             hiddenFloatingDiv('AddBudgetHeads');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo BUDGET_HEAD_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo BUDGET_HEAD_ALREADY_EXIST ;?>"); 
                         document.AddBudgetHeads.headName.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddBudgetHeads.headName.focus(); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBudgetHeads(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/BudgetHeads/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {budgetHeadId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addBudgetHeads" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBudgetHeads.reset();
   document.AddBudgetHeads.headName.value = '';
   document.AddBudgetHeads.headAmount.value = '';
   document.AddBudgetHeads.headTypeId.value = '';
   document.AddBudgetHeads.headName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBudgetHeads() {
         var url = '<?php echo HTTP_LIB_PATH;?>/BudgetHeads/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 budgetHeadId: (document.EditBudgetHeads.budgetHeadId.value), 
                 headName: (document.EditBudgetHeads.headName.value), 
                 headAmount: (document.EditBudgetHeads.headAmount.value), 
                 headTypeId: (document.EditBudgetHeads.headTypeId.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBudgetHeads');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo BUDGET_HEAD_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo BUDGET_HEAD_ALREADY_EXIST ;?>"); 
                         document.EditBudgetHeads.headName.focus();
                     } 
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditBudgetHeads.headName.focus();                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editBudgetHeads" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         document.EditBudgetHeads.reset();
         var url = '<?php echo HTTP_LIB_PATH;?>/BudgetHeads/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {budgetHeadId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBudgetHeads');
                        messageBox("<?php echo BUDGET_HEAD_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   var j = eval('('+transport.responseText+')');
                   
                   document.EditBudgetHeads.headName.value = j.headName;
                   document.EditBudgetHeads.headAmount.value = j.headAmount;
                   document.EditBudgetHeads.headTypeId.value = j.headTypeId;
                   document.EditBudgetHeads.budgetHeadId.value = j.budgetHeadId;
                   document.EditBudgetHeads.headName.focus();

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listBudgetHeadsPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"BudgetHeadsReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listBudgetHeadsCSV.php?'+qstr;
    window.location = path;
}
</script>
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BudgetHeads/listBudgetHeadsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listBudgetHeads.php $ 
?>