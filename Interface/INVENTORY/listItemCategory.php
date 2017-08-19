<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ItemCategoryMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Item Type </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
	new Array('srNo','#','width="4%"','align="left"',false), 
    new Array('categoryName','Category Name','width="30%"','align="left"',true),
    new Array('categoryCode','Category Code','width="30%"','align="left"',true),
	new Array('categoryType','Category Type','width="30%"','align="left"',true),
	new Array('action','Action','width="10%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'ItemCategoryActionDiv';   
editFormName   = 'ItemCategoryActionDiv';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteItemCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'categoryName';
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
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Item Category';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("categoryName","<?php echo ENTER_CATEGORY ?>"),
    new Array("categoryCode","<?php echo ENTER_CATEGORY_CODE ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        /*else if((fieldsArray[i][0]=='abbr') && !isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value")),"-/") ) {
                alert("<?php echo ENTER_ALPHABETS_NUMERIC2; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } */
     }
  
    if(document.getElementById('itemCategoryId').value=='') {
        addItemCategory();
        return false;
    }
    else{
		editItemCategory();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addItemCategory() IS USED TO ADD NEW Item CATEGORY
//
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addItemCategory() {
		
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryAdd.php';

         var catType=2;
		 if(document.ItemCategoryDetail.categoryType[0].checked==true ) {
           catType=1;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                categoryName:   trim(document.ItemCategoryDetail.categoryName.value),
                categoryCode:   trim(document.ItemCategoryDetail.categoryCode.value),
				categoryType:	catType
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('ItemCategoryActionDiv');
                             //getItemCategoryData();
							 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo CATEGORY_NAME_EXIST; ?>'){
							document.ItemCategoryDetail.categoryName.focus();
						}
						if (trim(transport.responseText)=='<?php echo CATEGORY_CODE_EXIST; ?>'){
							document.ItemCategoryDetail.categoryCode.focus();
						}

                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A ITEM CATEGORY
//  id=categoryId
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteItemCategory(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {itemCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         //getItemCategoryData();
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
//THIS FUNCTION IS USED TO Provide help window data

//Author : Abhay Kant
// Created on : (28.07.2011)
// Copyright 2010-2011 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var topPos = 0;
var leftPos = 0;
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    
    if(document.getElementById('helpChk').checked == false) {
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title; 
    document.getElementById('helpInfo').innerHTML= msg;   
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}


//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Item Category';
	document.ItemCategoryDetail.categoryName.value = '';
    document.ItemCategoryDetail.categoryCode.value = '';
	document.getElementById('itemCategoryId').value='';
	document.ItemCategoryDetail.categoryName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A ITEM CATEGORY
//
//Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editItemCategory() {
         var url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryEdit.php';
         
		 var catType=2;
		 if(document.ItemCategoryDetail.categoryType[0].checked==true ) {
           catType=1;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					itemCategoryId:		(document.ItemCategoryDetail.itemCategoryId.value),
					categoryName:		trim(document.ItemCategoryDetail.categoryName.value),
                    categoryCode:		trim(document.ItemCategoryDetail.categoryCode.value),
					categoryType:		catType
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('ItemCategoryActionDiv');
                         //getItemCategoryData();
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 return false;
                       }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo CATEGORY_NAME_EXIST; ?>'){
							document.ItemCategoryDetail.categoryName.focus();
						}
						if (trim(transport.responseText)=='<?php echo CATEGORY_CODE_EXIST; ?>'){
							document.ItemCategoryDetail.categoryCode.focus();
						}
                        
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITOFFENSEs" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/ItemCategory/ajaxItemCategoryGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {itemCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('ItemCategoryActionDiv');
                        messageBox("<?php echo CATEGORY_NOT_EXIST; ?>");
                        //getItemCategoryData();           
						sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                   j = eval('('+trim(transport.responseText)+')');
                  
                   document.ItemCategoryDetail.categoryName.value	= j.categoryName;
                   document.ItemCategoryDetail.categoryCode.value   = j.categoryCode;
                   document.ItemCategoryDetail.itemCategoryId.value		= j.itemCategoryId;
				   document.ItemCategoryDetail.categoryType[0].checked=true;
				   if(j.categoryType==2) {
					 document.ItemCategoryDetail.categoryType[1].checked=true;
				   }

                   document.ItemCategoryDetail.categoryName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
    document.searchForm.reset();
}

function printItemCategoryReport() {
	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayItemCategoryReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"itemCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayItemCategoryReportCSV.php?'+qstr;
	window.location = path;
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddItem;
 }
 else{
     var form = document.EditItem;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(INVENTORY_TEMPLATES_PATH . "/ItemCategory/listItemCategoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script>
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: $ 
//
?>
