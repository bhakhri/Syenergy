<?php
//----------------------------------------------------------------------------------------------------------
// THIS FILE SHOWS A LIST OF FINE CATEGORIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FineCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fine Category Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                         new Array('srNo','#','width="4%"','',false),
                         new Array('fineCategoryName','Category Name','"width=30%"','',true) ,
                         new Array('fineCategoryAbbr','Category Abbr.','width="30%"','',true) ,
                         new Array('fineType','Type(Fine/Activity)','width="30%"','',true) ,
                         new Array('action','Action','width="6%"','align="center"',false)
                         );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFineCategory';
editFormName   = 'EditFineCategory';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFineCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'fineCategoryName';
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
// Created on : (13.6.2008)
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
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {


    var fieldsArray = new Array(
        new Array("categoryName","<?php echo ENTER_FINE_CATEGORY_NAME; ?>"),
        new Array("categoryAbbr","<?php echo ENTER_FINE_CATEGORY_ABBR; ?>")
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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='categoryName' ) {
                messageBox("<?php echo FINE_CATEGORY_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='categoryAbbr' ) {
                messageBox("<?php echo FINE_CATEGORY_ABBR_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }

    }
    if(act=='Add') {
        addFineCategory();
        return false;
    }
    else if(act=='Edit') {
        editFineCategory();
        return false;
    }
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW Fine Category
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function addFineCategory() {
		fineType ='';
		if(document.getElementById('fineType1').checked==true){
			fineType='Fine';
		}
		else{
			fineType='Activity';
		}
		
		 var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                   categoryName: (trim(document.AddFineCategory.categoryName.value)),
                   categoryAbbr: (trim(document.AddFineCategory.categoryAbbr.value)),
                   fineType: fineType
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
                             hiddenFloatingDiv('AddFineCategory');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else if("<?php echo FINE_CATEGORY_NAME_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_CATEGORY_NAME_ALREADY_EXIST ;?>");
                         document.AddFineCategory.categoryName.focus();
                     }
                     else if("<?php echo FINE_CATEGORY_ABBR_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_CATEGORY_ABBR_ALREADY_EXIST ;?>");
                         document.AddFineCategory.categoryAbbr.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A FINE CATEGORY
// id=fineCategoryId
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteFineCategory(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {

        var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 fineCategoryId: id
             },
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



//--------------------------------------------------------------------
// THIS FUNCTION IS USED TO CLEAN UP THE "AddFineCategory" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------
function blankValues() {
   document.AddFineCategory.reset();
   document.AddFineCategory.categoryName.focus();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A Fine Category
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editFineCategory() {
		fineType ='';
		if(document.getElementById('editfineType1').checked==true){
			fineType='Fine';
		}
		else{
			fineType='Activity';
		}

         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxInitEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 fineCategoryId: (document.EditFineCategory.fineCategoryId.value),
                 categoryName: (trim(document.EditFineCategory.categoryName.value)),
                 categoryAbbr: (trim(document.EditFineCategory.categoryAbbr.value)),
                 fineType: fineType
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFineCategory');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo FINE_CATEGORY_NAME_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_CATEGORY_NAME_ALREADY_EXIST ;?>");
                         document.EditFineCategory.categoryName.focus();
                     }
                     else if("<?php echo FINE_CATEGORY_ABBR_ALREADY_EXIST; ?>" == trim(transport.responseText)){
                         messageBox("<?php echo FINE_CATEGORY_ABBR_ALREADY_EXIST ;?>");
                         document.EditFineCategory.categoryAbbr.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EditFineCategory" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (02.07.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/Fine/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 fineCategoryId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFineCategory');
                        messageBox("<?php echo FINE_CATEGORY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }

                   var j = eval('('+trim(transport.responseText)+')');
                   document.EditFineCategory.categoryName.value   = j.fineCategoryName;
                   document.EditFineCategory.categoryAbbr.value   = j.fineCategoryAbbr;
                   document.EditFineCategory.fineCategoryId.value = j.fineCategoryId;
                   if(j.fineType=='Fine'){
                   		document.getElementById('editfineType1').checked=true;
                   }
                   else if(j.fineType=='Activity'){
                   		document.getElementById('editfineType2').checked=true;
                   }                   
                   document.EditFineCategory.categoryName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print fine category report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/fineCategoryReportPrint.php?'+qstr;
    window.open(path,"FineCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='fineCategoryReportCSV.php?'+qstr;
}

window.onload=function(){
    document.searchForm.reset();
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fine/listFineCategoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>