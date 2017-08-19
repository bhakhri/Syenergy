<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF DESIGNATION ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','DesignationMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 include_once(BL_PATH ."/Designation/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Designation Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('designationName','Designation Name','width=30%','',true), new Array('designationCode','Abbr.','width="10%"','',true),new Array('employeeCount','Employees','width="30%"','align="right"',true),new Array('description','Description','width="50%"','',true), new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Designation/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDesignation';   
editFormName   = 'EditDesignation';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDesignation';
divResultName  = 'results';
page=1; //default page
sortField = 'designationName';
sortOrderBy    = 'ASC';

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
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("designationName","<?php echo ENTER_DESIGNATION_NAME ?>"),new Array("designationCode","<?php echo ENTER_DESIGNATION_CODE ?>") );

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
            //unsetAlertStyle(fieldsArray[i][0]);
             if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='designationName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo DESIGNATION_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
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
        addDesignation();
        return false;
    }
    else if(act=='Edit') {
        editDesignation();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addDesignation() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addDesignation() {
         url = '<?php echo HTTP_LIB_PATH;?>/Designation/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {designationName: (document.addDesignation.designationName.value), designationCode: (document.addDesignation.designationCode.value), description: (document.addDesignation.description.value)},
             
               OnCreate: function(){
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
                         hiddenFloatingDiv('AddDesignation');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
                         return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo DESIGNATION_NAME_EXIST ?>"){
							//document.addDesignation.designationName.value='';
							document.addDesignation.designationName.focus();	
						}
						else {
							//document.addDesignation.designationCode.value='';
							document.addDesignation.designationCode.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEPERIOD() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILED THROUGH ID
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteDesignation(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Designation/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {designationId: id},
             
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
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   document.addDesignation.designationName.value = '';
   document.addDesignation.designationCode.value = '';
   document.addDesignation.description.value = '';  
   
   document.addDesignation.designationName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION EDITDESIGNATION() IS USED TO populate edit the values & 
//save the values into the database by using designationId
//
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function editDesignation() {
         url = '<?php echo HTTP_LIB_PATH;?>/Designation/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {designationId: (document.editDesignation.designationId.value), designationName: (document.editDesignation.designationName.value), designationCode: (document.editDesignation.designationCode.value), description: (document.editDesignation.description.value)},
             
             onCreate: function() {
                  showWaitDialog(true);
             },
                 
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('EditDesignation');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                        return false;
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo DESIGNATION_NAME_EXIST ?>"){
							//document.editDesignation.designationName.value='';
							document.editDesignation.designationName.focus();	
						}
						else {
							//document.editDesignation.designationCode.value='';
							document.editDesignation.designationCode.focus();
						}
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Designation/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {designationId: id},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
                  
             onSuccess: function(transport){  
               
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
						hiddenFloatingDiv('EditDesignation'); 
                        messageBox("<?php echo DESIGNATION_NOT_EXIST;?>");
                        
                      // exit();
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.editDesignation.designationName.value = j.designationName;
                   document.editDesignation.designationCode.value = j.designationCode;
                   document.editDesignation.designationId.value = j.designationId;
                   document.editDesignation.description.value = j.description;  
                   
                   document.editDesignation.designationName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayDesignationReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayDesignationReport","status=1,menubar=1,scrollbars=1, width=900");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayDesignationCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Designation/listDesignationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>

</html>
<?php 
// $History: listDesignation.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Interface
//fixed bugs during self testing
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Interface
//put sendReq function 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/19/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//show print during search
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
//*****************  Version 19  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:34p
//Updated in $/Leap/Source/Interface
//define access values
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:49p
//Updated in $/Leap/Source/Interface
//embedded print option 
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:57a
//Updated in $/Leap/Source/Interface
//modification in code
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:42p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 8/20/08    Time: 2:32p
//Updated in $/Leap/Source/Interface
//modified for error message
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:41p
//Updated in $/Leap/Source/Interface
//modified for duplicate record check
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:30p
//Updated in $/Leap/Source/Interface
//modified in edit or delete messages
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/01/08    Time: 6:28p
//Updated in $/Leap/Source/Interface
//cursor show on designation name text box during textbox
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/01/08    Time: 11:52a
//Updated in $/Leap/Source/Interface
//modified for onSuccess function & transport 
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:22p
//Updated in $/Leap/Source/Interface
//change errormessage from echo
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:53p
//Updated in $/Leap/Source/Interface
//change alert in messagebox
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:10a
//Updated in $/Leap/Source/Interface
//fixed the bug.
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/01/08    Time: 12:42p
//Updated in $/Leap/Source/Interface
//modified in comments
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/30/08    Time: 10:10a
//Updated in $/Leap/Source/Interface
//Make the changes for ajax functions
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:15p
//Updated in $/Leap/Source/Interface
//modified in error occured during deletion
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:30p
//Updated in $/Leap/Source/Interface
//modified in validation function
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:21p
//Updated in $/Leap/Source/Interface
//put the delete function
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:00p
//Updated in $/Leap/Source/Interface
//modified with comments during checkin
?>