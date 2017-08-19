<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF GROUP TYPE ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GroupTypeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/GroupType/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Group Type Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('groupTypeName','Group Type Name','width=20%','',true) , 
                               new Array('groupTypeCode','Abbr.','width="20%"','',true));

//new Array('action','Action','width="5%"','align="right"',false)

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/GroupType/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGroupType';   
editFormName   = 'EditGroupType';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGroupType';
divResultName  = 'results';
page=1; //default page
sortField = 'groupTypeName';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("groupTypeName","<?php echo ENTER_GROUPTYPE_NAME ?>"),new Array("groupTypeCode","<?php echo ENTER_GROUPTYPE_CODE ?> ") );

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
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='groupTypeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo GROUPTYPE_NAME_LENGTH ?>");
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
        addGroupType();
        return false;
    }
    else if(act=='Edit') {
        editGroupType();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addGroupType() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addGroupType() {
    url = '<?php echo HTTP_LIB_PATH;?>/GroupType/ajaxInitAdd.php';
    new Ajax.Request(url,
    {
        method:'post',
            parameters: {groupTypeName: (document.addGroupType.groupTypeName.value), groupTypeCode: (document.addGroupType.groupTypeCode.value)},
                
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
                                hiddenFloatingDiv('AddGroupType');
                                sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                            //location.reload();
                                 return false;
                           }
                       }
                        else {
                         messageBox(trim(transport.responseText)); 
                         if (trim(transport.responseText)=="<?php echo GROUPTYPE_NAME_EXIST ?>"){
							document.addGroupType.groupTypeName.value='';
							document.addGroupType.groupTypeName.focus();	
						}
						else {
							document.addGroupType.groupTypeCode.value='';
							document.addGroupType.groupTypeCode.focus();
						}

                            }
                },
    onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEGROUPTYPE() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function deleteGroupType(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/GroupType/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {groupTypeId: id},
             
              onCreate: function() { 
                  showWaitDialog(true);
              },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
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
   document.addGroupType.groupTypeName.value = '';
   document.addGroupType.groupTypeCode.value = '';
   document.addGroupType.groupTypeName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editGroupType() {
         url = '<?php echo HTTP_LIB_PATH;?>/GroupType/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {groupTypeId: (document.editGroupType.groupTypeId.value), groupTypeName: (document.editGroupType.groupTypeName.value), groupTypeCode: (document.editGroupType.groupTypeCode.value)},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditGroupType');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                     //location.reload();
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=="<?php echo GROUPTYPE_NAME_EXIST ?>"){
							document.editGroupType.groupTypeName.value='';
							document.editGroupType.groupTypeName.focus();	
						}
						else {
							document.editGroupType.groupTypeCode.value='';
							document.editGroupType.groupTypeCode.focus();
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
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/GroupType/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {groupTypeId: id},
                onCreate: function() {   
                  showWaitDialog(true);
                },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
                       hiddenFloatingDiv('EditGroupType');  
					   messageBox("<?php echo GROUPTYPE_NOT_EXIST;?>");
                      // exit();
                      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.editGroupType.groupTypeName.value = j.groupTypeName;
                   document.editGroupType.groupTypeCode.value = j.groupTypeCode;
                   document.editGroupType.groupTypeId.value = j.groupTypeId;
                   document.editGroupType.groupTypeName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/listGroupTypePrint.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"GroupTypeReportPrint","status=1,menubar=1,scrollbars=1, height=600, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    var qstr="searchbox="+(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listGroupTypeCSV.php?'+qstr;
    window.location = path;
}
</script>

</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/GroupType/listGroupTypeContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>

<SCRIPT LANGUAGE="JavaScript">
	
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
	
</SCRIPT>
</body>
</html>
<?php 
// $History: listGroupType.php $
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/28/09    Time: 11:07a
//Updated in $/LeapCC/Interface
//put sendReq function 
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
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:56a
//Updated in $/Leap/Source/Interface
//modification for edit & delete
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:14p
//Updated in $/Leap/Source/Interface
//modification in indentation
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/21/08    Time: 2:26p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/11/08    Time: 2:58p
//Updated in $/Leap/Source/Interface
//modified to check duplicate records
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:40p
//Updated in $/Leap/Source/Interface
//modified for edit or delete messages
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/01/08    Time: 3:07p
//Updated in $/Leap/Source/Interface
//modified for onCreate or onSuccess function
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/19/08    Time: 2:15p
//Updated in $/Leap/Source/Interface
//modified in duplicate record
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:04a
//Updated in $/Leap/Source/Interface
//fixed the bug.
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/16/08    Time: 8:19p
//Updated in $/Leap/Source/Interface
//bug fixed
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:39a
//Updated in $/Leap/Source/Interface
//replace alert with messagebox
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/30/08    Time: 10:56a
//Updated in $/Leap/Source/Interface
//modification through ajax functions
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/25/08    Time: 5:23p
//Updated in $/Leap/Source/Interface
//add the delete function
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:30p
//Updated in $/Leap/Source/Interface
?>