<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (28.04.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DisciplineCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Discipline Category Master </title>
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

function getDisciplineCategoryData(){         
  url = '<?php echo HTTP_LIB_PATH;?>/HostelDisciplineCat/ajaxInitDisciplineCategoryList.php';
  var value = document.searchForm.searchbox.value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="3%" align="left"',false), 
                        new Array('categoryName','Category Name','width="93%" align="left"',true),
                        new Array('action','Action','width="4%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','categoryName','ASC','DisciplineCategoryResultDiv','DisciplineCategoryActionDiv','',true,'listObj',tableColumns,'editWindow','deleteDisciplineCategory','&searchbox='+trim(value));
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
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Discipline Category';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Gurkeerat Sidhu 
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("categoryName","<?php echo ENTER_DISCIPLINECATEGORY_NAME ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }
  
    if(document.getElementById('disciplineCategoryId').value=='') {
        //alert('add slot');
		addDisciplineCategory();
        return false;
    }
    else{
		//alert('edit slot');
        editDisciplineCategory();
        return false;
    }
}

function emptySlotId() {
	document.getElementById('offenseId').value='';
}

//-------------------------------------------------------
//THIS FUNCTION addTestTypeCategory() IS USED TO ADD NEW DISCIPLINE CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDisciplineCategory() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/HostelDisciplineCat/ajaxInitDisciplineCategoryAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                categoryName:   trim(document.DisciplineCategoryDetail.categoryName.value)
                
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
                             hiddenFloatingDiv('DisciplineCategoryActionDiv');
                             getDisciplineCategoryData();
                             return false;
                         }
                     }
					 
					 else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo DISCIPLINE_CATEGORY_EXIST; ?>'){
							document.DisciplineCategoryDetail.categoryName.value="";
							document.DisciplineCategoryDetail.categoryName.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=disciplineCatId
//Author : Gurkeerat Sidhu
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDisciplineCategory(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HostelDisciplineCat/ajaxInitDisciplineCategoryDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {disciplineCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getDisciplineCategoryData(); 
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
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Discipline Category';
	document.DisciplineCategoryDetail.categoryName.value = '';
    document.getElementById('disciplineCategoryId').value='';
	document.DisciplineCategoryDetail.categoryName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A TEST TYPE CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editDisciplineCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelDisciplineCat/ajaxInitDisciplineCategoryEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					disciplineCategoryId: (document.DisciplineCategoryDetail.disciplineCategoryId.value),
					categoryName: trim(document.DisciplineCategoryDetail.categoryName.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('DisciplineCategoryActionDiv');
                         getDisciplineCategoryData();
                         return false;

                     }
                   else {
                        messageBox(trim(transport.responseText)); 
                            if (trim(transport.responseText)=='<?php echo DISCIPLINE_CATEGORY_EXIST; ?>'){
							document.DisciplineCategoryDetail.categoryName.value="";
							document.DisciplineCategoryDetail.categoryName.focus();
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
// Created on : (28.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelDisciplineCat/ajaxDisciplineCategoryGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {disciplineCategoryId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('DisciplineCategoryActionDiv');
                        messageBox("<?php echo DISCIPLINE_NOT_EXIST; ?>");
                        getDisciplineCategoryData();           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.DisciplineCategoryDetail.categoryName.value	= j.categoryName;
                   document.DisciplineCategoryDetail.disciplineCategoryId.value= j.disciplineCategoryId;
                   document.DisciplineCategoryDetail.categoryName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;

	path='<?php echo UI_HTTP_PATH;?>/displayHostelDisciplineCatReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayDesignationTempReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"DisplayDisciplineCateReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

function printCSV() {
	sortField = listObj.sortField;
	sortOrderBy = listObj.sortOrderBy;
    path='<?php echo UI_HTTP_PATH;?>/displayHostelDisciplineCatCSV.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}


window.onload=function(){
        //loads the data
        getDisciplineCategoryData();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/HostelDisciplineCat/listDisciplineCategoryContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
