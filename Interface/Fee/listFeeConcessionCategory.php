<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Country Form
//
//
// Author :Nishu Bindal
// Created on : 3-Feb-2012
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeConcessionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Country/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Concession Category Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script type="text/javascript">
 
 // ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('categoryName','Category Name','width="35%"','',true) , 
                               new Array('categoryOrder','Settlement Order','width="15%"','align="right" style="padding-right:10px;"',true), 
                               new Array('categoryDescription','Description','width="40%"','',true), 
                               new Array('action','Action','width="7%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeConcessionCategory/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeConcession';   
editFormName   = 'EditFeeConcession';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteFeeConcession';
divResultName  = 'results';
page=1; //default page
sortField = 'categoryName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
 
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button       
//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 


function validateAddForm(frm, act) {
        
   
    var fieldsArray = new Array(new Array("categoryName","<?php echo ENTER_CATEGORY_NAME;?>"),
                                new Array("categoryOrder","<?php echo ENTER_CATEGORY_ORDER;?>")
                            );
                                
    if(act=='Add') {
       document.addFeeConcessionForm.categoryName.value = trim(document.addFeeConcessionForm.categoryName.value);
       document.addFeeConcessionForm.categoryOrder.value = trim(document.addFeeConcessionForm.categoryOrder.value);
      
    }
    else {
       document.editFeeConcessionForm.categoryName.value = trim(document.editFeeConcessionForm.categoryName.value);
       document.editFeeConcessionForm.categoryOrder.value = trim(document.editFeeConcessionForm.categoryOrder.value);
      
    }                                

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {


		    if((eval("frm."+(fieldsArray[i][0])+".value.length"))>3 && fieldsArray[i][0]=='categoryOrder') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo CATEGORY_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
          if(eval("frm."+(fieldsArray[i][0])+".value")<=0)  {
                  messageBox ("<?php echo CATEGORY_ORDER_SHOULD_NOT_BE_ZERO_0R_NEGATIVE;?>");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }

 
			   if(eval("frm."+(fieldsArray[i][0])+".value")< 0 || eval("frm."+(fieldsArray[i][0])+".value")>100)  {
                  messageBox ("<?php echo CATEGORY_ORDER_VALUE;?>");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }


			   if(fieldsArray[i][0]=='categoryOrder' ) {
             if(!isNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                  //winmessageBox ("Enter string",fieldsArray[i][0]);
                  messageBox ("Enter the numeric value");
                  eval("frm."+(fieldsArray[i][0])+".focus();");
                  return false;
                  break;
               }
		    }
        }
    }
    if(act=='Add') {
        addCategory();
        return false;
    }
    else if(act=='Edit') {
        editCategory();    
        return false;
    }
}

//This function adds form through ajax 

                                                                 
function addCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeConcessionCategory/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {categoryName: (document.addFeeConcessionForm.categoryName.value), 
                          categoryOrder: (document.addFeeConcessionForm.categoryOrder.value),
                          categoryDescription: (document.addFeeConcessionForm.categoryDescription.value)
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
                             hiddenFloatingDiv('AddFeeConcession');
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
   document.addFeeConcessionForm.categoryName.value = '';
   document.addFeeConcessionForm.categoryOrder.value = '';
   document.addFeeConcessionForm.categoryName.focus();
}

//This function edit form through ajax                   

function editCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeConcessionCategory/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
              parameters: {categoryId: (document.editFeeConcessionForm.categoryId.value),
						   categoryName: (document.editFeeConcessionForm.categoryName.value), 
						   categoryOrder: (document.editFeeConcessionForm.categoryOrder.value),
                           categoryDescription: (document.editFeeConcessionForm.categoryDescription.value)
                          },
              onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
           			
                     hideWaitDialog(true);
                 
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hideWaitDialog(true);
                 	 messageBox(trim(transport.responseText));
                         hiddenFloatingDiv('EditFeeConcession');
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

//This function calls delete function through ajax

function deleteFeeConcession(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeeConcessionCategory/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {categoryId: id},
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
         url = '<?php echo HTTP_LIB_PATH;?>/Fee/FeeConcessionCategory/ajaxGetValue.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {categoryId: id},
            onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
          
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   
                   document.editFeeConcessionForm.categoryName.value = j.categoryName;                   
                   document.editFeeConcessionForm.categoryOrder.value = j.categoryOrder;
                   document.editFeeConcessionForm.categoryDescription.value = j.categoryDescription;
                   document.editFeeConcessionForm.categoryId.value = j.categoryId;
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listFeeConcessionPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"FeeConcessionReport","status=1,menubar=1,scrollbars=1, width=900");
}


function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listFeeConcessionCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fee/FeeConcessionCategory/listFeeConcessionCategoryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>


