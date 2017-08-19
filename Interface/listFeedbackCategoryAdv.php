<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF FEED BACK CATEGORIES(ADV)
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_CategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Feedback Category Master(Advanced) </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                      new Array('srNo','#','width="1%"','',false),
                      new Array('feedbackCategoryName','Category','width="15%"','',true) , 
                      new Array('parentCategoryName','Parent Category','width="15%"','',true), 
                      new Array('feedbackType','Relationship','width="10%"','',true),
                      new Array('subjectTypeName','Subject Type','width="10%"','',true),
                      //new Array('feedbackSurveyLabel','Label','width="15%"','',true),
                      new Array('printOrder','Print Order','width="8%"','align="right"',true),
                      new Array('actionString','Action','width="1%"','align="right"',false)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackCategoryList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddCategory';   
editFormName   = 'EditCategory';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'feedbackCategoryName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editWindow(id) {
    displayWindow('AddCategory',315,250);
    document.getElementById('divHeaderId1').innerHTML='&nbsp;Edit Feedback Category';
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {

    var fieldsArray = new Array(
        //new Array("labelId","<?php echo SELECT_ADV_LABEL_NAME;?>"),
        new Array("catName","<?php echo ENTER_ADV_CATEGORY_NAME;?>"),
        new Array("catRelation","<?php echo ENTER_ADV_CATEGORY_RELN;?>"),
        //new Array("catDesc","<?php echo ENTER_ADV_CATEGORY_DESC;?>"),
        new Array("printOrder","<?php echo ENTER_ADV_PRINT_ORDER;?>")
        
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
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<1 && fieldsArray[i][0]=='catName' ) {
                messageBox("<?php echo ADV_CATEGORY_NAME_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
          /* 
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<10 && fieldsArray[i][0]=='catDesc' ) {
                messageBox("<?php echo ADV_CATEGORY_DESC_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
          */ 
           if(!isNumeric(trim(eval("frm."+(fieldsArray[i][0])+".value"))) && fieldsArray[i][0]=='printOrder' ) {
                messageBox("<?php echo ADV_PRINT_ORDER_NUMERIC;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
           if(trim(eval("frm."+(fieldsArray[i][0])+".value"))==0 && fieldsArray[i][0]=='printOrder' ) {
                messageBox("<?php echo ADV_PRINT_ORDER_GREATER_THAN_ZERO;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
		   
           if(trim(eval("frm."+(fieldsArray[i][0])+".value"))>100 && fieldsArray[i][0]=='printOrder' ) {
                messageBox("Print Order cannot be greater than 100"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
     
    }
    if(document.getElementById('catRelation').value==4 && document.getElementById('subjectType').value==''){
        messageBox("<?php echo SELECT_ADV_CATEGORY_SUBJECT_TYPE;?>");
        document.getElementById('subjectType').focus();
        return false; 
    }
    
    /*if(document.AddCategory.parentCat.value!=''){
       if(trim(document.AddCategory.catDesc.value)==''){
         messageBox("<?php echo ENTER_ADV_CATEGORY_DESC;?>");
         document.AddCategory.catDesc.focus();
         return false;
       }*/
      /* if(trim(document.AddCategory.catDesc.value).length<10){
         messageBox("<?php echo ADV_CATEGORY_DESC_LENGTH;?>");
         document.AddCategory.catDesc.focus();
         return false;
       }
    }*/
    if(document.getElementById('catId').value == '') {
        addCategory();
        return false;
    }
    else if(document.getElementById('catId').value != '') {
        editCategory();
        return false;
    }
    
}

function toggleSubjectType(value){
    if(value==4){
       document.getElementById('subjectType').disabled=false;
       document.getElementById('stSpan').style.display='';
    }
    else{
       document.getElementById('subjectType').disabled=true; 
       document.getElementById('stSpan').style.display='none';
       if(document.getElementById('subjectType').options.length>0){
        document.getElementById('subjectType').selectedIndex=0;
       }       
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addCategory() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackCategoryOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 modeName    : 1,
                 //labelId     : document.AddCategory.labelId.value,
                 catName     : trim(document.AddCategory.catName.value),
                 parentCatId : document.AddCategory.parentCat.value,
                 catRel      : document.AddCategory.catRelation.value,
                 subjectType : document.AddCategory.subjectType.value,
                 catDesc     : trim(document.AddCategory.catDesc.value),
                 printOrder  : trim(document.AddCategory.printOrder.value),
                 catComments : document.AddCategory.catComments.checked==true?1:0
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
                             hiddenFloatingDiv('AddCategory');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else if("<?php echo ADV_CATEGORY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_CATEGORY_ALREADY_EXIST ;?>"); 
                         document.AddCategory.catName.focus();
                     }
                     else if("<?php echo SELECT_ADV_CATEGORY_SUBJECT_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SELECT_ADV_CATEGORY_SUBJECT_TYPE ;?>"); 
                         document.AddCategory.subjectType.focus();
                     }
                     else if("<?php echo ADV_TWO_LEVEL_HIERARCHY_FOUND;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_TWO_LEVEL_HIERARCHY_FOUND ;?>"); 
                         document.AddCategory.parentCat.focus();
                     }
                     else if("<?php echo ADV_INVALID_CATEGORY_RELN;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_INVALID_CATEGORY_RELN ;?>"); 
                         document.AddCategory.catRelation.focus();
                     }
                     else if("<?php echo ADV_SAME_PRINT_ORDER;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_SAME_PRINT_ORDER ;?>"); 
                         document.AddCategory.printOrder.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddCategory.catName.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteCategory(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackCategoryOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 modeName : 3,
                 catId    : id
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddCategory.reset();
   refreshParentCategory(0);
   document.AddCategory.catId.value='';
   document.AddCategory.subjectType.disabled=true;
   document.getElementById('divHeaderId1').innerHTML='&nbsp;Add Feedback Category';
   document.getElementById('stSpan').style.display='none';
   //document.AddCategory.labelId.focus();
   document.AddCategory.catName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editCategory() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxAdvFeedBackCategoryOperations.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  modeName    : 2,
                  catId       : document.AddCategory.catId.value,
                  //labelId     : document.AddCategory.labelId.value,
                  catName     : trim(document.AddCategory.catName.value),
                  parentCatId : document.AddCategory.parentCat.value,
                  catRel      : document.AddCategory.catRelation.value,
                  subjectType : document.AddCategory.subjectType.value,
                  catDesc     : trim(document.AddCategory.catDesc.value),
                  printOrder  : trim(document.AddCategory.printOrder.value),
                  catComments : document.AddCategory.catComments.checked==true?1:0
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddCategory');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo ADV_CATEGORY_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_CATEGORY_ALREADY_EXIST ;?>"); 
                         document.AddCategory.catName.focus();
                     }
                     else if("<?php echo SELECT_ADV_CATEGORY_SUBJECT_TYPE;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SELECT_ADV_CATEGORY_SUBJECT_TYPE ;?>"); 
                         document.AddCategory.subjectType.focus();
                     }
                     else if("<?php echo ADV_TWO_LEVEL_HIERARCHY_FOUND;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_TWO_LEVEL_HIERARCHY_FOUND ;?>"); 
                         document.AddCategory.parentCat.focus();
                     }
                     else if("<?php echo ADV_SELF_PARENT_HIERARCHY_FOUND;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_SELF_PARENT_HIERARCHY_FOUND ;?>"); 
                         document.AddCategory.parentCat.focus();
                     }
                     else if("<?php echo ADV_INVALID_CATEGORY_RELN;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_INVALID_CATEGORY_RELN ;?>"); 
                         document.AddCategory.catRelation.focus();
                     }
                     else if("<?php echo ADV_SAME_PRINT_ORDER;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ADV_SAME_PRINT_ORDER ;?>"); 
                         document.AddCategory.printOrder.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddCategory.catName.focus(); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "editCity" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         document.AddCategory.reset();
         document.AddCategory.subjectType.disabled=true; 
         document.getElementById('stSpan').style.display='none';
         document.AddCategory.catId.value='';
         url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedBackCategoryValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  catId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditCategory');
                        messageBox("<?php echo ADV_CATEGORY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.AddCategory.catId.value=j.feedbackCategoryId;
                   
                   //document.AddCategory.labelId.value = j.feedbackSurveyId;
                   
                   //fetching parent categories
                   //refreshParentCategory(j.feedbackSurveyId);
                   refreshParentCategory(0);
                   
                   document.AddCategory.catName.value = j.feedbackCategoryName;
                   if(j.parentFeedbackCategoryId != -1 ){ 
                    document.AddCategory.parentCat.value   = j.parentFeedbackCategoryId;
                   }
                   document.AddCategory.catRelation.value  = j.feedbackType;
                   if(j.subjectTypeId!=-1){
                    document.AddCategory.subjectType.value = j.subjectTypeId;
                    document.AddCategory.subjectType.disabled=false;
                    document.getElementById('stSpan').style.display='';
                   }
                   document.AddCategory.catDesc.value = j.description;
                   document.AddCategory.printOrder.value = j.printOrder;
                   if(j.hasFeedbackComments==1){
                     document.AddCategory.catComments.checked=true;  
                   }
                   else{
                     document.AddCategory.catComments.checked=false;  
                   }
                   //document.AddCategory.labelId.focus();
                   document.AddCategory.catName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "editCity" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (08.01.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function refreshParentCategory(val) {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetFeedBackParentCategoryValues.php';
         var ele=document.getElementById('parentCat');
         ele.options.length=1;
         /*
         if(val == ''){
             return false;
         }
         */
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                  //labelId : val
                  labelId : 0
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 var j = eval('('+transport.responseText+')');
                 var len=j.length;
                 for(var i=0;i<len;i++){
                     addOption(ele,j[i].feedbackCategoryId,j[i].feedbackCategoryName);
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


/* function to print Category report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/feedbackCategoryAdvReportPrint.php?'+qstr;
    window.open(path,"FeedbackCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='feedbackCategoryAdvReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listFeedBackCategoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listFeedbackCategoryAdv.php $ 
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 19/02/10   Time: 14:22
//Updated in $/LeapCC/Interface
//Done Bug fixing.
//Bug ids---
//0002910,0002909,0002907,
//0002906,0002904,0002908,
//0002905
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 18/02/10   Time: 12:59
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//0002900,0002899,0002898,0002897
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/02/10    Time: 19:38
//Updated in $/LeapCC/Interface
//Corrected code
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 25/01/10   Time: 16:29
//Updated in $/LeapCC/Interface
//Corrected table column names
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 25/01/10   Time: 15:52
//Updated in $/LeapCC/Interface
//Made UI related changes as instructed by sachin sir
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/01/10    Time: 18:29
//Updated in $/LeapCC/Interface
//Updated "Advanced Feedback Category" module as feedbackSurveyId is
//removed from table
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/01/10    Time: 16:47
//Created in $/LeapCC/Interface
//Created module "Advanced Feedback Category Module"
?>