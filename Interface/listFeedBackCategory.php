<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF FeedBackCategory ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeedBackCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeedBack/initFeedBackCategoryList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Category Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="2%"','',false), 
 new Array('feedbackCategoryName','Category Name','width="95%"','',true) , 
 new Array('action','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackCategoryList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeedBackCategory';   
editFormName   = 'EditFeedBackCategory';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeedBackCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackCategoryName';
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
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
                                new Array("categoryName","<?php echo ENTER_CATEGORY_NAME; ?>")
                               );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='categoryName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo CATEGORY_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addFeedBackCategory();
        return false;
    }
    else if(act=='Edit') {
        editFeedBackCategory();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FeedBackCategory
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeedBackCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackCategoryAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 categoryName: (document.AddFeedBackCategory.categoryName.value)
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
                      else if("<?php echo CATEGORY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo CATEGORY_ALREADY_EXIST ;?>"); 
                        document.AddFeedBackCategory.categoryName.focus();
                      }  
                         else {
                             hiddenFloatingDiv('AddFeedBackCategory');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A FeedBackCategory
//  id=FeedBackCategoryId
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeedBackCategory(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackCategoryDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedBackCategoryId: id},
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

//------------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "AddFeedBackCategory" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------
function blankValues() {
   document.AddFeedBackCategory.categoryName.value = '';
   document.AddFeedBackCategory.categoryName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A FeedBackCategory
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeedBackCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxInitFeedBackCategoryEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 feedBackCategoryId: (document.EditFeedBackCategory.feedBackCategoryId.value),
                 categoryName: (document.EditFeedBackCategory.categoryName.value) 
              },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeedBackCategory');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo CATEGORY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo CATEGORY_ALREADY_EXIST ;?>"); 
                        document.EditFeedBackCategory.routeCode.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFeedBackCategory" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeedBack/ajaxGetFeedBackCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feedBackCategoryId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFeedBackCategory');
                        messageBox("<?php echo CATEGORY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                    }
                    else if(trim(transport.responseText)=="<?php echo DEPENDENCY_CONSTRAINT_EDIT;?>"){
                        messageBox("<?php echo DEPENDENCY_CONSTRAINT_EDIT; ?>");
                        return false;
                    }
                    
                    displayWindow(dv,w,h);
                    
                    j = eval('('+transport.responseText+')');
                    
                    document.EditFeedBackCategory.categoryName.value = j.feedbackCategoryName;
                    document.EditFeedBackCategory.feedBackCategoryId.value = j.feedbackCategoryId;
                    document.EditFeedBackCategory.categoryName.focus();
            },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print FeedBack Label report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/feedBackCategoryReportPrint.php?'+qstr;
    window.open(path,"FeedBackCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedBackCategoryReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedBack/listFeedBackCategoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listFeedBackCategory.php $ 
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 25/07/09   Time: 13:12
//Updated in $/LeapCC/Interface
//Done Bug Fixing.
//Bug ids---0000680 to 0000688,0000690 to 0000696
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 24/06/09   Time: 12:49
//Updated in $/LeapCC/Interface
//Bug fixing.
//bug ids---
//00000256,00000257,00000259,00000261,00000263,00000264.
//00000266,00000269,00000262
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 23/06/09   Time: 14:46
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids----
//00000187,00000191,00000198,00000199,00000203,00000204,
//00000205,00000207,0000209,00000211
//
//*****************  Version 5  *****************
//User: Administrator Date: 13/06/09   Time: 18:59
//Updated in $/LeapCC/Interface
//Corredted issues which are detected during user documentation
//preparation
//
//*****************  Version 4  *****************
//User: Administrator Date: 11/06/09   Time: 11:15
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000011,0000012,0000016,0000018,0000020,0000006,0000017,0000009,0000019
//
//*****************  Version 3  *****************
//User: Administrator Date: 21/05/09   Time: 11:14
//Updated in $/LeapCC/Interface
//Copied "Feedback Master Modules" from Leap to LeapCC
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
//User: Dipanjan     Date: 11/21/08   Time: 12:10p
//Updated in $/Leap/Source/Interface
//Corrected problem corresponding to Issues [20-11-08] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/15/08   Time: 3:10p
//Updated in $/Leap/Source/Interface
//Corrected javascript alert message
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/15/08   Time: 1:40p
//Created in $/Leap/Source/Interface
//Created FeedBack Masters
?>