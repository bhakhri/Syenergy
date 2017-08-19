<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
<title><?php echo SITE_NAME;?>: Item Category Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

winLayerWidth  = 340; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getItemCategoryData(){         
  url = '<?php echo HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryList.php';
  var value = document.searchBox1.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="4%" align="left"',false), 
                        new Array('categoryName','Item Category','width="40%" align="left"',true),
                        new Array('abbr','Abbreviation','width="40%" align="left"',true),
                        new Array('action','Action','width="10%" align="right"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','categoryName','ASC','ItemCategoryResultDiv','ItemCategoryActionDiv','',true,'listObj',tableColumns,'editWindow','deleteItemCategory','&searchbox='+trim(value));
 sendRequest(url, listObj, '')

}
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("categoryName","<?php echo ENTER_CATEGORY ?>"),
    new Array("abbr","<?php echo ENTER_ABBR ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if((fieldsArray[i][0]=='abbr') && !isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value")),"-/") ) {
                alert("<?php echo ENTER_ALPHABETS_NUMERIC2; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
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

function emptySlotId() {
	document.getElementById('offenseId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addItemCategory() IS USED TO ADD NEW Item CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addItemCategory() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                categoryName:   trim(document.ItemCategoryDetail.categoryName.value),
                abbr:           trim(document.ItemCategoryDetail.abbr.value)
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
                             getItemCategoryData();
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo ITEM_CATEGORY_EXIST; ?>'){
							document.ItemCategoryDetail.categoryName.value="";
							document.ItemCategoryDetail.categoryName.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=itemCategoryId
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteItemCategory(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryDelete.php';
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
                         getItemCategoryData(); 
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Item Category';
	document.ItemCategoryDetail.categoryName.value = '';
    document.ItemCategoryDetail.abbr.value = '';
	document.getElementById('itemCategoryId').value='';
	document.ItemCategoryDetail.categoryName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEST TYPE CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editItemCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/ItemCategory/ajaxInitItemCategoryEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					itemCategoryId: (document.ItemCategoryDetail.itemCategoryId.value),
					categoryName:   trim(document.ItemCategoryDetail.categoryName.value),
                    abbr:           trim(document.ItemCategoryDetail.abbr.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('ItemCategoryActionDiv');
                         getItemCategoryData();
						 return false;
                       }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo ITEM_CATEGORY_EXIST; ?>'){
							document.ItemCategoryDetail.categoryName.value="";
							document.ItemCategoryDetail.categoryName.focus();
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/ItemCategory/ajaxItemCategoryGetValues.php';
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
                        getItemCategoryData();           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.ItemCategoryDetail.categoryName.value	= j.categoryName;
                   document.ItemCategoryDetail.abbr.value   = j.abbr;
                   document.ItemCategoryDetail.itemCategoryId.value		= j.itemCategoryId;
                   document.ItemCategoryDetail.categoryName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
        //loads the data
        document.searchBox1.reset();
        getItemCategoryData();    
}

/*function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayPeriodsReport.php';
    window.open(path,"DisplayPeriodsReport","status=1,menubar=1,scrollbars=1, width=900, height=700");
}*/

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/ItemCategory/listItemCategoryContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
