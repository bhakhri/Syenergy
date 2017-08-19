<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Offense ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Gurkeerat Sidhu
// Created on : (20.05.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ResourceCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Resource Category Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                        new Array('srNo','#','width="3%"','',false), 
                        new Array('resourceName','Category Name','width="90%"','',true),
                        new Array('action','Action','width="7%"','align="center"',false)
                       );

 recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ResourceCategory/ajaxInitResourceCategoryList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddResourceCategory';   
editFormName   = 'EditResourceCategory';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteResourceCategory';
divResultName  = 'results';
page=1; //default page
sortField = 'resourceName';
sortOrderBy    = 'ASC';
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Gurkeerat Sidhu 
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(new Array("resourceName","<?php echo ENTER_RESOURCECATEGORY_NAME ?>"));

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
        addResourceCategory();
        return false;
    }
    else if(act=='Edit') {
        editResourceCategory();
        return false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION addResourceCategory() IS USED TO ADD NEW Resource CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addResourceCategory() {
        
         url = '<?php echo HTTP_LIB_PATH;?>/ResourceCategory/ajaxInitResourceCategoryAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                resourceName:   trim(document.AddResourceCategory.resourceName.value)
                
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
                             hiddenFloatingDiv('AddResourceCategory');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                             return false;
                         }
                     }
                     
                     
             else if("<?php echo RESOURCE_CATEGORY_EXIST;?>" == trim(transport.responseText)){                      messageBox("<?php echo RESOURCE_CATEGORY_EXIST ;?>"); 
                        document.AddResourceCategory.resourceName.focus();
                      }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A PERIOD SLOT
//  id=resourceTypeId
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteResourceCategory(id) {
    
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/ResourceCategory/ajaxInitResourceCategoryDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {resourceTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "ADDPERIODSLOT" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
    document.AddResourceCategory.resourceName.value = '';
    document.AddResourceCategory.resourceName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Resource CATEGORY
//
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editResourceCategory() {
         url = '<?php echo HTTP_LIB_PATH;?>/ResourceCategory/ajaxInitResourceCategoryEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                    resourceTypeId: (document.EditResourceCategory.resourceTypeId.value),
                    resourceName: trim(document.EditResourceCategory.resourceName.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditResourceCategory');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                         return false;

                     }
                   else if("<?php echo RESOURCE_CATEGORY_EXIST;?>" == trim(transport.responseText)){
                        messageBox("<?php echo RESOURCE_CATEGORY_EXIST ;?>"); 
                        document.EditResourceCategory.resourceName.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITOFFENSEs" DIV
//
//Author : Gurkeerat Sidhu
// Created on : (20.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/ResourceCategory/ajaxResourceCategoryGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {resourceTypeId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditResourceCategory');
                        messageBox("<?php echo RESOURCE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);           
                   }
                   j = eval('('+trim(transport.responseText)+')');

                  
                   document.EditResourceCategory.resourceName.value    = j.resourceName;
                   document.EditResourceCategory.resourceTypeId.value= j.resourceTypeId;
                   document.EditResourceCategory.resourceName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayResourceCategoryReport.php?'+qstr;
    window.open(path,"DisplayResouceCategoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='displayResourceCategoryCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
        require_once(TEMPLATES_PATH . "/header.php");
        require_once(TEMPLATES_PATH . "/ResourceCategory/listResourceCategoryContents.php");
        require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
</script>
</body>
</html>

<?php 
// $History: listResourceCategory.php $
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 10/23/09   Time: 6:32p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001871,0001869,0001853,0001873,0001820,0001809,0001808,
//0001805,0001806, 0001876, 0001879, 0001878
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/22/09   Time: 5:28p
//Updated in $/LeapCC/Interface
//resolved issue #1819
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/11/09    Time: 6:07p
//Updated in $/Leap/Source/Interface
//Gurkeerat: Resolved issue 945,943,942,941
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//

?>
