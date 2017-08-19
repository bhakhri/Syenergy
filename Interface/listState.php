<?php
//-------------------------------------------------------
// Purpose: To generate the list of states from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Pushpender Kumar Chauhan
// Created on : (11.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StateMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/States/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: States Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="10%"','',false), 
                               new Array('stateName','State Name','width="30%"','',true) , 
                               new Array('stateCode','State Code','width="20%"','',true), 
                               new Array('countryName','Country Name','width="30%"','',true) ,
                               new Array('action','Action','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/States/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddState';   
editFormName   = 'EditState';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'stateName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("stateName","<?php echo ENTER_STATE_NAME;?>"),new Array("stateCode","<?php echo ENTER_STATE_CODE;?>"),new Array("countries","<?php echo SELECT_COUNTRY;?>") );

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='stateName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo STATE_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            //if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='countries' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                //messageBox("<?php echo ENTER_ALPHABETS_NUMERIC;?>");
               // eval("frm."+(fieldsArray[i][0])+".focus();");
               // return false;
               // break;
            //}
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addState();
        return false;
    }
    else if(act=='Edit') {
        editState();
        return false;
    }
}
function addState() {
         url = '<?php echo HTTP_LIB_PATH;?>/States/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stateName: (document.addState.stateName.value), stateCode: (document.addState.stateCode.value), countries: (document.addState.countries.value)},
             onCreate: function(){
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
                             hiddenFloatingDiv('AddState');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo STATE_ALREADY_EXIST ?>'){
							document.addState.stateCode.focus();	
						}
						else {
							document.addState.stateName.focus();
						}
                     }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function deleteState(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/States/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stateId: id},
             onCreate: function(){
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
function blankValues() {
   document.addState.stateCode.value = '';
   document.addState.stateName.value = '';
   document.addState.countries.value = '';
   document.addState.stateName.focus();
}
function editState() {  
         url = '<?php echo HTTP_LIB_PATH;?>/States/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stateId: (document.editState.stateId.value), stateName: (document.editState.stateName.value), stateCode: (document.editState.stateCode.value), countries: (document.editState.countries.value)},
             onCreate: function(){
                 showWaitDialog(true);
             },             
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditState');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo STATE_ALREADY_EXIST ?>'){
							document.editState.stateCode.focus();	
						}
						else {
							document.editState.stateName.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/States/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stateId: id},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditState');
                        messageBox("<?php echo STATE_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   j = eval('('+trim(transport.responseText)+')');
                   document.editState.stateCode.value = j.stateCode;
                   document.editState.stateName.value = j.stateName;
                   document.editState.countries.value = j.countryId;
                   document.editState.stateId.value = j.stateId;
                   document.editState.stateName.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    
    path='<?php echo UI_HTTP_PATH;?>/listStatePrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"StateReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listStateCSV.php?'+qstr;
    window.location = path;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/States/listStateContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
sendReq(listURL,divResultName,searchFormName,'');
</script>
<?php
// $History: listState.php $
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/19/10    Time: 3:33p
//Updated in $/LeapCC/Interface
//added print & excel format 
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/08/09   Time: 6:12p
//Updated in $/LeapCC/Interface
//resolved issue 1607
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:04p
//Updated in $/LeapCC/Interface
//resolved issue 1608
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/27/09    Time: 3:51p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1286,1285
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/10/09    Time: 5:36p
//Created in $/LeapCC/Interface
//copy from sc with modifications
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 6/03/09    Time: 4:12p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 1198 to 1206 of bug4.doc
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 6/03/09    Time: 2:43p
//Updated in $/Leap/Source/Interface
//fixed bug nos.1213,1219,1220,1221
//
//*****************  Version 12  *****************
//User: Pushpender   Date: 5/21/09    Time: 4:46p
//Updated in $/Leap/Source/Interface
//introduced Grouping Feature, now the request goes to server onLoad of
//html body
//
//*****************  Version 11  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 10  *****************
//User: Pushpender   Date: 8/06/08    Time: 2:04p
//Updated in $/Leap/Source/Interface
//Replaced centralised messages variables and removed 'Data saved or
//deleted successfully' kind of messages as per discussion in demo
//
//*****************  Version 9  *****************
//User: Pushpender   Date: 6/30/08    Time: 7:32p
//Updated in $/Leap/Source/Interface
//made changes to set focus on state name while editing
//
//*****************  Version 8  *****************
//User: Pushpender   Date: 6/30/08    Time: 12:46p
//Updated in $/Leap/Source/Interface
//assign width to stateName, country columns
//
//*****************  Version 7  *****************
//User: Pushpender   Date: 6/27/08    Time: 4:08p
//Updated in $/Leap/Source/Interface
//replaced alert function with messageBox function, placed sendReq
//function in actions such add/edit/delete, also fixed QA bugs,and "data
//saved successfully, add more?" changed
//
//*****************  Version 6  *****************
//User: Pushpender   Date: 6/26/08    Time: 8:04p
//Updated in $/Leap/Source/Interface
//added ajax search results functionality
//
//*****************  Version 5  *****************
//User: Pushpender   Date: 6/18/08    Time: 7:59p
//Updated in $/Leap/Source/Interface
//delete code js function added and put some validations on state name
//
//*****************  Version 4  *****************
//User: Pushpender   Date: 6/14/08    Time: 1:28p
//Updated in $/Leap/Source/Interface
//fixed defects produced in QA testing
//
//*****************  Version 3  *****************
//User: Pushpender   Date: 6/13/08    Time: 4:44p
//Updated in $/Leap/Source/Interface
//added Comments Header,  ADD_MORE variable, trim function
?>
