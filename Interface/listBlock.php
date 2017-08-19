<?php
//-------------------------------------------------------
// Purpose: To generate the list of blocks from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Dipanjan Bhatcharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BlockCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Block/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Block Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
 new Array('blockName','Block Name','width="50%"','',true) , 
 new Array('abbreviation','Abbr.','width="18%"','',true), 
 new Array('buildingName','Building','width="28%"','',true) ,
 new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Block/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBlock';   
editFormName   = 'EditBlock';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlock';
divResultName  = 'results';
page=1; //default page
sortField = 'blockName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function editWindow(id,dv,w,h) {
        displayWindow(dv,w,h);
        populateValues(id);
}

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("blockName","<?php echo ENTER_BLOCK_NAME; ?>"),
    new Array("abbreviation","<?php echo ENTER_BLOCK_ABBR; ?>"),
    new Array("building","<?php echo SELECT_BUILDING; ?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='blockName' ) {
                alert("<?php echo BLOCK_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
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
        addBlock();
        return false;
    }
    else if(act=='Edit') {
        editBlock();
        return false;
    }
}

//----------------------------------------------------------------------
//Author:Dipanjan Bhattacharjee
//Purpose:Add a Block
//Date:10.7.2008
//------------------------------------------------------------------------
function addBlock() {
         url = '<?php echo HTTP_LIB_PATH;?>/Block/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {blockName: (document.AddBlock.blockName.value), 
             abbreviation: (document.AddBlock.abbreviation.value), 
             buildingId: (document.AddBlock.building.value)},
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
                             hiddenFloatingDiv('AddBlock');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo BLOCK_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BLOCK_ALREADY_EXIST ;?>"); 
                       document.AddBlock.blockName.focus();
                     }
                     else if("<?php echo BLOCK_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BLOCK_ABBR_ALREADY_EXIST ;?>"); 
                       document.AddBlock.abbreviation.focus();
                     }    
                         
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//----------------------------------------------------------------------
//Author:Dipanjan Bhattacharjee
//Purpose:Delete a Block
//Date:10.7.2008
//------------------------------------------------------------------------

function deleteBlock(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Block/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {blockId: id},
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

//----------------------------------------------------------------------
//Author:Dipanjan Bhattacharjee
//Purpose:Cleans up "AddBlock" Div before adding
//Date:10.7.2008
//------------------------------------------------------------------------
function blankValues() {
   document.AddBlock.blockName.value = '';
   document.AddBlock.abbreviation.value = '';
   document.AddBlock.building.selectedIndex=0;
   document.AddBlock.blockName.focus();
}


//----------------------------------------------------------------------
//Author:Dipanjan Bhattacharjee
//Purpose:Edit a Block
//Date:10.7.2008
//------------------------------------------------------------------------
function editBlock() {  
         url = '<?php echo HTTP_LIB_PATH;?>/Block/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {blockId: (document.EditBlock.blockId.value), 
             blockName: (document.EditBlock.blockName.value), 
             abbreviation: (document.EditBlock.abbreviation.value), 
             buildingId: (document.EditBlock.building.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBlock');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo BLOCK_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BLOCK_ALREADY_EXIST ;?>"); 
                       document.EditBlock.blockName.focus();
                    }
                    else if("<?php echo BLOCK_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo BLOCK_ABBR_ALREADY_EXIST ;?>"); 
                       document.EditBlock.abbreviation.focus();
                    }
                    else {
                        messageBox(trim(transport.responseText));                         
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//----------------------------------------------------------------------
//Author:Dipanjan Bhattacharjee
//Purpose:Populates "EditBlock" before edit
//Date:10.7.2008
//------------------------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Block/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {blockId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBlock');
                        messageBox("<?php echo BLOCK_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   j = eval('('+trim(transport.responseText)+')');
                   document.EditBlock.blockName.value = j.blockName;
                   document.EditBlock.abbreviation.value = j.abbreviation;
                   document.EditBlock.building.value = j.buildingId;
                   document.EditBlock.blockName.focus();
                   document.EditBlock.blockId.value = j.blockId; 

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print block report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/blockReportPrint.php?'+qstr;
    window.open(path,"BlockReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='blockReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Block/listBlockContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php
// $History: listBlock.php $
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 9/04/09    Time: 11:30a
//Updated in $/LeapCC/Interface
//Gurkeerat: corrected title of page
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 6/08/09    Time: 11:27
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000919 to 0000922
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 5/08/09    Time: 12:39
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//0000887 to 0000895,
//0000906 to 0000909
//
//*****************  Version 5  *****************
//User: Administrator Date: 12/06/09   Time: 19:25
//Updated in $/LeapCC/Interface
//Corrected display issues which are detected during user documentation
//preparation
//
//*****************  Version 4  *****************
//User: Administrator Date: 4/06/09    Time: 11:26
//Updated in $/LeapCC/Interface
//Corrected bugs----
//bug ids--Leap bugs2.doc(10 to 15)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 22/12/08   Time: 12:02
//Updated in $/LeapCC/Interface
//Corrected Bugs
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:41p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:18a
//Updated in $/Leap/Source/Interface
//Added functionality for block report print
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/20/08    Time: 6:53p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/11/08    Time: 12:42p
//Updated in $/Leap/Source/Interface
//Created "Block" Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 7:04p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>
