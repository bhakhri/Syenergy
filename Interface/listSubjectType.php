<?php 

//-------------------------------------------------------
//  This File contains Validation and ajax function used in Subject Type Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectTypesMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


//require_once(BL_PATH . "/SubjectType/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
 var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button                     

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
                               new Array('subjectTypeName','Subject Type','width="40%"','',true) , 
                               new Array('subjectTypeCode','Abbr.','width="30%"','',true),
                               new Array('universityName','University','width="30%"','',true), 
                               new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectType/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubjectType';   
editFormName   = 'EditSubjectType';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubjectType';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectTypeName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

//This function Displays Div Window

function editWindow(id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(id);   
}

//This function Validates Form 


function validateAddForm(frm, act) {
        
   
    var fieldsArray = new Array(new Array("subjectTypeName","<?php echo ENTER_SUBJECT_TYPE_NAME;?>"),
                                new Array("subjectTypeCode","<?php echo ENTER_SUBJECT_TYPE_CODE;?>"),
                                new Array("universityId","<?php echo SELECT_UNIVERSITY;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
/*        else {
            //unsetmessageBox Style(fieldsArray[i][0]);
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winmessageBox ("Enter string",fieldsArray[i][0]);
                messageBox ("<?php echo ENTER_ALPHABETS;?>");      
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetmessageBox Style(fieldsArray[i][0]);
            //}
        }
*/        
    }
    
    if(act=='Add') {
        addSubjectType();
        return false;
    }
    else if(act=='Edit') {
        editSubjectType();    
        return false;
    }
}

//This function adds form through ajax 


function addSubjectType() {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectType/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTypeName: trim(document.addSubjectType.subjectTypeName.value), 
                          subjectTypeCode: trim(document.addSubjectType.subjectTypeCode.value),
                          universityId: trim(document.addSubjectType.universityId.value)},
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
                             hiddenFloatingDiv('AddSubjectType');
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
// This function call a delete file which delete a record from the database

function deleteSubjectType(id) {  
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectType/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTypeId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
             
                     hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
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

function blankValues() {
   document.addSubjectType.subjectTypeCode.value = '';
   document.addSubjectType.subjectTypeName.value = '';
   document.addSubjectType.universityId.value = ''; 
   
   document.addSubjectType.subjectTypeName.focus();
}

//This function edit form through ajax 

function editSubjectType() {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectType/ajaxInitEdit.php';
           
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTypeId: trim(document.editSubjectType.subjectTypeId.value), 
                          subjectTypeName: trim(document.editSubjectType.subjectTypeName.value), 
                          subjectTypeCode: trim(document.editSubjectType.subjectTypeCode.value),universityId: (document.editSubjectType.universityId.value)},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
            
                     hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditSubjectType');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                      else {
                         messageBox(trim(transport.responseText));
                     }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function populates values in edit form through ajax 

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectType/ajaxGetValues.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTypeId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
             
                    hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');
                   
                   document.editSubjectType.subjectTypeCode.value = j.subjectTypeCode;
                   
                   document.editSubjectType.subjectTypeName.value = j.subjectTypeName;
                   document.editSubjectType.universityId.value = j.universityId;
                   
                   document.editSubjectType.subjectTypeId.value = j.subjectTypeId;
              
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print Subject Type report*/
function printReport() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/subjectTypeReportPrint.php?'+qstr;
    window.open(path,"SubjectTypeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/subjectTypeReportCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SubjectType/listSubjectTypeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
    </SCRIPT>
</body>
</html>

<?php 

//$History: listSubjectType.php $
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:14a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 8  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:21p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000800,0000802,0000801,0000776,0000775,0000776,0000801
//
//*****************  Version 6  *****************
//User: Parveen      Date: 6/12/09    Time: 5:57p
//Updated in $/LeapCC/Interface
//sorting list update university
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/12/09    Time: 4:25p
//Updated in $/LeapCC/Interface
//editSubjectType, addSubjectType function updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/01/09    Time: 12:56p
//Updated in $/LeapCC/Interface
//list formatting & required field validation added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Updated in $/LeapCC/Interface
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/20/08    Time: 12:38p
//Updated in $/Leap/Source/Interface
//Replaced validation messages by common messages
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/07/08    Time: 3:24p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/01/08    Time: 7:06p
//Updated in $/Leap/Source/Interface
//done enhancement
//
//*****************  Version 6  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/18/08    Time: 2:19p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 4  *****************
//User: Arvind       Date: 7/01/08    Time: 1:37p
//Updated in $/Leap/Source/Interface
//added a new files in dynamic table function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/30/08    Time: 4:22p
//Updated in $/Leap/Source/Interface
//1) Added a new javascript function which calls table listing through
//ajax and pagination function 
//2) Added a delete funciton which call ajax file to delete
//3) Modifies add and edit funnction.
//    Data saved successfullyand
//   DO you want to add more ?
//  messageBox  is displayed in one messageBox  box
//
//*****************  Version 2  *****************
//User: Arvind       Date: 6/26/08    Time: 5:16p
//Updated in $/Leap/Source/Interface
//added new delete function 
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:23p
//Created in $/Leap/Source/Interface
//first time checkin


?>
