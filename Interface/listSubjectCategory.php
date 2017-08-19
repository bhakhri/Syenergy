<?php
//-------------------------------------------------------
// Purpose: To generate the list of subject category from the database, and have add/edit/delete, search
// functionality
//
// Author : Parveen Sharma
// Created on : (05.07.2009 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectCategory');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Category Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
                               new Array('categoryName','Category Name','width=20%','',true),
                               new Array('abbr','Abbr.','width="20%"','',true),
                               new Array('subjectCount','No. of subjects','width="10%"','',true),
                               new Array('parentCategoryName','Parent Category','width="20%"','',true),
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGroup';
editFormName   = 'EditGroup';
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
deleteFunction = 'return deleteGroup';
divResultName  = 'results';
page=1; //default page
sortField = 'categoryName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Parveen sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    getGroupNameEdit();
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//-------------------------------------------------------

function validateAddForm(frm, act) {
    if(act=='Add') {
        if(isEmpty(document.addGroup.categoryName.value)) {
           messageBox("<?php echo ENTER_SUBJECT_CATEGORY; ?>");
           document.addGroup.categoryName.focus();
           return false;
        }
        if(!isAlphaNumericCustom(document.addGroup.categoryName.value," -/,.&()") ){
           messageBox("<?php echo ACCEPT_SUBJECT_CATEGORY; ?>");
           document.addGroup.categoryName.focus();
           return false;
        }
        if(isEmpty(document.addGroup.abbr.value)) {
           messageBox("<?php echo ENTER_SUBJECT_CATEGORY_ABBR; ?>");
           document.addGroup.abbr.focus();
           return false;
        }
        addGroup();
        return false;
    }
    else if(act=='Edit') {
        if(isEmpty(document.editGroup.categoryName.value)) {
           messageBox("<?php echo ENTER_SUBJECT_CATEGORY; ?>");
           document.editGroup.categoryName.focus();
           return false;
        }
        if(!isAlphaNumericCustom(document.editGroup.categoryName.value," -/,.&()") ){
           messageBox("<?php echo ACCEPT_SUBJECT_CATEGORY; ?>");
           document.editGroup.categoryName.focus();
           return false;
        }
        if(isEmpty(document.editGroup.abbr.value)) {
           messageBox("<?php echo ENTER_SUBJECT_CATEGORY_ABBR; ?>");
           document.editGroup.abbr.focus();
           return false;
        }
        if(document.editGroup.parentCategoryId.value==document.editGroup.subjectCategoryId.value) {
           messageBox("<?php echo PARENT_CATEGORY_ITSELF; ?>");
           document.editGroup.parentCategoryId.focus();
           return false;
        }
        editGroup();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addGroup() IS USED TO ADD NEW GROUP TYPE
//
//Author : Parveen sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


function addGroup() {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
						  categoryName: trim(document.addGroup.categoryName.value),
						  parentCategoryId: (document.addGroup.parentCategoryId.value),
                          abbr: trim(document.addGroup.abbr.value)
						 },

               onCreate: function() {
                  showWaitDialog(true);
               },

             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if("<?php echo ACCESS_DENIED;?>" == trim(transport.responseText)) {
						messageBox(trim(transport.responseText));	
						return false;
					}
					else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					  flag = true;

						//getGroupName();
                        if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")){

						   blankValues();
                         }
                         else {
                                hiddenFloatingDiv('AddGroup');
								sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                            //location.reload();
								return false;
                         }
                    }
                    else {
                        if (trim(transport.responseText)=="<?php echo ENTER_SUBJECT_CATEGORY ?>"){
                            messageBox("<?php echo ENTER_SUBJECT_CATEGORY ;?>");
                            document.addGroup.categoryName.focus();
							return false;
                        }
						if (trim(transport.responseText)=="<?php echo SUBJECT_CATEGORY_EXIST ?>"){
                            messageBox("<?php echo SUBJECT_CATEGORY_EXIST ;?>");
                            document.addGroup.categoryName.focus();
							return false;
                        }
                        if (trim(transport.responseText)=="<?php echo SUBJECT_CATEGORY_ABBR_EXIST ?>"){
                            messageBox("<?php echo SUBJECT_CATEGORY_ABBR_EXIST ;?>");
                            document.addGroup.abbr.focus();
                            return false;
                        }
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEGROUP() IS USED TO DELETE THE SPECIFIED RECORD
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Parveen Sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteGroup(id) {
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitDelete.php';
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {subjectCategoryId: id},
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
//THIS FUNCTION blankValues() IS USED TO BLANK VALUES OF TEXT BOXES
//
//Author : Parveen Sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues(){
    document.addGroup.categoryName.value="";
    document.addGroup.abbr.value="";
    document.addGroup.parentCategoryId.selectedIndex=0;
    document.addGroup.categoryName.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
//
//Author : Parveen Sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editGroup() {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitEdit.php';
       //  getGroupNameEdit(document.editGroup.groupId.value,document.editGroup.optionValue.value);
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {subjectCategoryId: (document.editGroup.subjectCategoryId.value),
						  categoryName: trim(document.editGroup.categoryName.value),
                          abbr: trim(document.editGroup.abbr.value),
                          parentCategoryId: (document.editGroup.parentCategoryId.value)

                         },
               onCreate: function() {
                  showWaitDialog(true);
               },
               onSuccess: function(transport){
                     hideWaitDialog(true);
					  if (trim(transport.responseText)=="<?php echo PARENT_CATEGORY_ITSELF; ?>"){
                            messageBox("<?php echo PARENT_CATEGORY_ITSELF; ?>");
							document.editGroup.parentGroup.focus();
							return false;
					  }
					  else if (trim(transport.responseText)=="<?php echo ENTER_SUBJECT_CATEGORY ?>"){
							messageBox("<?php echo ENTER_SUBJECT_CATEGORY; ?>");
							document.editGroup.categoryName.focus();
							return false;
					  }
                      else if (trim(transport.responseText)=="<?php echo SUBJECT_CATEGORY_EXIST ?>"){
                            messageBox("<?php echo SUBJECT_CATEGORY_EXIST; ?>");
                            document.editGroup.categoryName.focus();
                            return false;
                      }
                      else if (trim(transport.responseText)=="<?php echo SUBJECT_CATEGORY_ABBR_EXIST ?>"){
                            messageBox("<?php echo SUBJECT_CATEGORY_ABBR_EXIST ;?>");
                            document.editGroup.abbr.focus();
                            return false;
                      }
                      else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditGroup');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                        // location.reload();
                     }
                     else {
                         alert(trim(transport.responseText));
                         return false;
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values(subject Details)
//as seen in the subject category master list
//Author : Cheena garg
// Created on : (01.08.2011)
// Copyright 2011-2012 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function viewSubjectDetailsWindow(id) {
    
     dv = 'divSubjectDetails';
     w=810;
     h=400; 
     
      
     url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxGetValues.php';  
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {id: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            //hiddenFloatingDiv('resultInfo');
           //messageBox("<?php echo "Bank Branch List not present"; ?>");
           //return false;
         }
         subjectCategoryId = id;
         j = trim(transport.responseText);
         document.getElementById('resultInfo').innerHTML= j;    
         document.getElementById('categoryId').value=id;
         displayWindow(dv,w,h);
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values
 // during editing the record
//
//Author : Parveen Sharma
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {

         url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxGetValues.php';
         new Ajax.Request(url,
         {
             method:'post',
			 asynchronous:false,
             parameters: {subjectCategoryId: id},
             onCreate: function() {
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditGroup');
                        messageBox("<?php echo SUBJECT_CATEGORY_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }

                   var j = eval('('+transport.responseText+')');
                   document.editGroup.categoryName.value = j.categoryName;
                   document.editGroup.subjectCategoryId.value = j.subjectCategoryId;
                   document.editGroup.abbr.value = j.abbr;

                   getGroupNameEdit();
                   if(j.parentCategoryId!=0) {
                     document.editGroup.parentCategoryId.value = j.parentCategoryId;
                   }
                   else {
                     document.editGroup.parentCategoryId.selectedIndex=0;
                   }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getGroupName(){

     var url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitCategoryName.php';
     document.addGroup.parentCategoryId.length = null;
     addOption(document.addGroup.parentCategoryId, '', 'Select');

     new Ajax.Request(url,
     {
         method:'post',
         asynchronous:false,
         parameters: {},
          onCreate: function(transport){
             // showWaitDialog(true);
         },
         onSuccess: function(transport){
           // hideWaitDialog(true);
            var j = eval('('+ transport.responseText+')');
            len = j.length;
            document.addGroup.parentCategoryId.length = null;
            addOption(document.addGroup.parentCategoryId, '', 'Select');
            if(len>0) {
              for(i=0;i<len;i++) {
                addOption(document.addGroup.parentCategoryId, j[i].subjectCategoryId, j[i].categoryName);
              }
            }
       },
       onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
     });
}


function getGroupNameEdit(){
     var url = '<?php echo HTTP_LIB_PATH;?>/SubjectCategory/ajaxInitCategoryName.php';
     document.editGroup.parentCategoryId.length = null;
     addOption(document.editGroup.parentCategoryId, '', 'Select');

     new Ajax.Request(url,
     {
         method:'post',
         asynchronous:false,
         parameters: {},
          onCreate: function(transport){
             // showWaitDialog(true);
         },
         onSuccess: function(transport){
            //hideWaitDialog(true);
            var j = eval('('+ transport.responseText+')');
            len = j.length;
            document.editGroup.parentCategoryId.length = null;
            addOption(document.editGroup.parentCategoryId, '', 'Select');
            for(i=0;i<len;i++) {
              addOption(document.editGroup.parentCategoryId, j[i].subjectCategoryId, j[i].categoryName);
            }
       },
       onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
       });
}

function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/listSubjectCategoryPrint.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayBatchList","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listSubjectCategoryCSV.php?'+qstr;
    window.location = path;
}

function printReport1() {

    var qstr="categoryId="+document.getElementById('categoryId').value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;

    path='<?php echo UI_HTTP_PATH;?>/listSubjectCategoryPrint1.php?'+qstr;
    window.open(path,"DisplaySubjectCategoryList","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV1() {
    var qstr="categoryId="+document.getElementById('categoryId').value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listSubjectCategoryCSV1.php?'+qstr;
    window.location = path;
}


</script>

</head>
<body>
    <?php
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/SubjectCategory/listSubjectCategoryContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>
<?php
// $History: listSubjectCategory.php $
//
//*****************  Version 7  *****************
//User: Parveen      Date: 10/20/09   Time: 10:25a
//Updated in $/LeapCC/Interface
//1801 bug no. resolve (Category Name or Abbr. extra space remove)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Interface
//search & conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 8/21/09    Time: 5:40p
//Updated in $/LeapCC/Interface
//formatting & role permission added
//
//*****************  Version 4  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 7/11/09    Time: 3:45p
//Updated in $/LeapCC/Interface
//new field added (abbreviation)
//
//*****************  Version 2  *****************
//User: Parveen      Date: 7/08/09    Time: 11:42a
//Updated in $/LeapCC/Interface
//populateValues function updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/07/09    Time: 2:15p
//Created in $/LeapCC/Interface
//initial checkin
//

?>
