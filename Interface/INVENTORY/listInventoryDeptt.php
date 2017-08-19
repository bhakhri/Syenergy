<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (08.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InventoryDeptartment');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Deptt./Store Master </title>
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

function getInventoryDepartmentData(){         
  url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InventoryDepartment/ajaxInitInventoryDepttList.php';
  var value = document.searchBox1.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('invDepttName','Deptt./Store Name','width="15%" align="left"',true),
                        new Array('invDepttAbbr','Abbreviation','width="15%" align="left"',true),
						new Array('departmentType','Type','width="15%" align="left"',true),
						new Array('employeeName','Incharge','width="15%" align="left"',true),
                        new Array('action','Action','width="5%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','invDepttName','ASC','InventoryDepartmentResultDiv','InventoryDepartmentDiv','',true,'listObj',tableColumns,'editWindow','deleteInventoryDepartment','&searchbox='+trim(value));
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
//Author : Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Inventory Department';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
       
    var fieldsArray = new Array(	new Array("inventoryDeptName","<?php echo ENTER_INVENTORY_DEPT_NAME ?>"),
									new Array("abbr","<?php echo ENTER_ABBR ?>"),
									new Array("employeeName","<?php echo SELECT_EMPLYEE_NAME ?>"),
									new Array("fromDate","<?php echo ENTER_FROM_DATE ?>"),
									new Array("departmentType","<?php echo SELECT_DEPT_TYPE ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }
  
    if(document.getElementById('invDepttId').value=='') {
		if(document.InventoryDepartmentDetail.toDate.value != '') {
			if(!dateDifference(document.InventoryDepartmentDetail.fromDate.value,document.InventoryDepartmentDetail.toDate.value,'-')) {
				messageBox ("<?php echo DATE_VALIDATION;?>");
					document.InventoryDepartmentDetail.toDate.focus();
					return false;
			}
		}
        addInventoryDepartment();
        return false;
    }
    else{
		if(document.InventoryDepartmentDetail.toDate.value != '') {
			if(!dateDifference(document.InventoryDepartmentDetail.fromDate.value,document.InventoryDepartmentDetail.toDate.value,'-')) {
				messageBox ("<?php echo DATE_VALIDATION;?>");
					document.InventoryDepartmentDetail.toDate.focus();
					return false;
			}
		}
		editInventoryDepartment();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addItemCategory() IS USED TO ADD NEW INVENTORY DEPARTMENT
//
//Author : Jaineesh
// Created on : (23.02.2010)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addInventoryDepartment() {
	
		
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InventoryDepartment/ajaxInitInventoryDepartmentAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                inventoryDeptName:   trim(document.InventoryDepartmentDetail.inventoryDeptName.value),
                abbr:           trim(document.InventoryDepartmentDetail.abbr.value),
				departmentType: trim(document.InventoryDepartmentDetail.departmentType.value),
				employee: trim(document.InventoryDepartmentDetail.employeeName.value),
				fromDate: trim(document.InventoryDepartmentDetail.fromDate.value),
				toDate: trim(document.InventoryDepartmentDetail.toDate.value),
				description: trim(document.InventoryDepartmentDetail.description.value)
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
                             hiddenFloatingDiv('InventoryDepartmentDiv');
                             getInventoryDepartmentData();
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo INVENTORY_DEPT_NAME_EXIST; ?>'){
							document.InventoryDepartmentDetail.inventoryDeptName.focus();
						}
						else if (trim(transport.responseText)=='<?php echo INVENTORY_DEPT_ABBR_EXIST; ?>'){
							document.InventoryDepartmentDetail.abbr.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=itemCategoryId
//Author : Jaineesh
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteInventoryDepartment(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InventoryDepartment/ajaxInitInventoryDepartmentDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {invDepttId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getInventoryDepartmentData(); 
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Inventory Department';
	document.InventoryDepartmentDetail.inventoryDeptName.value = '';
    document.InventoryDepartmentDetail.abbr.value = '';
	document.getElementById('invDepttId').value='';
	document.InventoryDepartmentDetail.departmentType.value = '';
	document.InventoryDepartmentDetail.employeeName.value = '';
	document.InventoryDepartmentDetail.fromDate.value = '';
	document.InventoryDepartmentDetail.toDate.value = '';
	document.InventoryDepartmentDetail.description.value = '';
	document.InventoryDepartmentDetail.inventoryDeptName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEST TYPE CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (08.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editInventoryDepartment() {
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InventoryDepartment/ajaxInitInventoryDepartmentEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					invDepttId:			(document.InventoryDepartmentDetail.invDepttId.value),
					inventoryDeptName:  trim(document.InventoryDepartmentDetail.inventoryDeptName.value),
					abbr:				trim(document.InventoryDepartmentDetail.abbr.value),
					departmentType:		trim(document.InventoryDepartmentDetail.departmentType.value),
					employee: trim(document.InventoryDepartmentDetail.employeeName.value),
					fromDate: trim(document.InventoryDepartmentDetail.fromDate.value),
					toDate: trim(document.InventoryDepartmentDetail.toDate.value),
					description: trim(document.InventoryDepartmentDetail.description.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('InventoryDepartmentDiv');
                         getInventoryDepartmentData();
						 return false;
                       }
                   else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo INVENTORY_DEPT_NAME_EXIST; ?>'){
							document.InventoryDepartmentDetail.inventoryDeptName.focus();
						}
						else if (trim(transport.responseText)=='<?php echo INVENTORY_DEPT_ABBR_EXIST; ?>'){
							document.InventoryDepartmentDetail.abbr.focus();
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
         url = '<?php echo INVENTORY_HTTP_LIB_PATH;?>/InventoryDepartment/ajaxInventoryDepartmentGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {invDepttId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('InventoryDepartmentDiv');
                        messageBox("<?php echo INVENTORY_DEPTT_NOT_EXIST; ?>");
                        getInventoryDepartmentData();           
                   }
                   j = eval('('+trim(transport.responseText)+')');
                   document.InventoryDepartmentDetail.inventoryDeptName.value	= j.invDepttName;
                   document.InventoryDepartmentDetail.abbr.value   = j.invDepttAbbr;
				   document.InventoryDepartmentDetail.departmentType.value   = j.depttType;
				   document.InventoryDepartmentDetail.employeeName.value   = j.inchargeId;
                   document.InventoryDepartmentDetail.fromDate.value		= j.fromDate;
				   if (j.toDate == '0000-00-00') {
					document.InventoryDepartmentDetail.toDate.value		= '';
				   }
				   else {
					document.InventoryDepartmentDetail.toDate.value		= j.toDate;
				   }

				   document.InventoryDepartmentDetail.description.value		= j.description;
				   document.InventoryDepartmentDetail.invDepttId.value		= j.invDepttId;
                   document.InventoryDepartmentDetail.inventoryDeptName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


window.onload=function(){
        //loads the data
        //document.searchBox1.reset();
        getInventoryDepartmentData();    
}

function printReport() {
	
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

	var path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayInventoryDepttReport.php?searchbox='+trim(document.searchBox1.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"InventoryDepttReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {

	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

    var qstr="searchbox="+trim(document.searchBox1.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo INVENTORY_UI_HTTP_PATH;?>/displayInventoryDepttReportCSV.php?'+qstr;
	window.location = path;
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.InventoryDepartmentDetail;
 }
 /*else{
     var form = document.EditHostelVisitor;
 }*/
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(INVENTORY_TEMPLATES_PATH . "/InventoryDepartment/listInventoryDepartmentContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>