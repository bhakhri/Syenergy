<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF slabs
//
// Author : Dipanjan Bhattacharjee
// Created on : (11.08.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SlabsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Slabs/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Slabs Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false),
 new Array('deliveredFrom','From','width="28%"','',true) ,
 new Array('deliveredTo','To','width="28%"','',true), 
 new Array('marks','Marks','Marks','width="28%"','',false) ,
 new Array('action','Action','width="10%"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Slabs/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSlabs';   
editFormName   = 'EditSlabs';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSlabs';
divResultName  = 'results';
page=1; //default page
sortField = 'deliveredFrom';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("deliveredFrom","<?php echo ENTER_DELIVERED_FROM ;?>"),
    new Array("deliveredTo","<?php echo ENTER_DELIVERED_TO; ;?>"),
    new Array("marks","<?php echo ENTER_ATT_MARKS; ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(fieldsArray[i][0]=='deliveredFrom' && !isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("<?php echo ENTER_DELIVERED_FROM_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
           if(fieldsArray[i][0]=='deliveredTo' && !isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("<?php echo ENTER_DELIVERED_TO_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           if(fieldsArray[i][0]=='marks' && !isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("<?php echo ENTER_ATT_MARKS_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }                
            
        }
     
    }
    if(act=='Add') {
        addSlabs();
        return false;
    }
    else if(act=='Edit') {
        editSlabs();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW DEGREE
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addSlabs() {
         url = '<?php echo HTTP_LIB_PATH;?>/Slabs/ajaxInitAdd.php';
         if(parseInt(document.AddSlabs.deliveredFrom.value,10) >= parseInt(document.AddSlabs.deliveredTo.value,10)){
           messageBox("<?php echo ENTER_ATT_ALERT; ?>");
           document.AddSlabs.deliveredFrom.focus();
           return false;  
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {deliveredFrom: trim(document.AddSlabs.deliveredFrom.value), 
             deliveredTo: trim(document.AddSlabs.deliveredTo.value), 
             marks: trim(document.AddSlabs.marks.value)},
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
                        else if("<?php echo SLAB_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SLAB_ALREADY_EXIST ;?>"); 
                         document.AddSlabs.deliveredFrom.focus();
                        }  
                         else {
                             hiddenFloatingDiv('AddSlabs');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                        document.AddSlabs.deliveredFrom.focus();
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DEGREE
//  id=degreeId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteSlabs(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Slabs/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {slabId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddSlabs" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddSlabs.deliveredFrom.value = '';
   document.AddSlabs.deliveredTo.value = '';
   document.AddSlabs.marks.value = '';
   document.AddSlabs.deliveredFrom.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A slab
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editSlabs() {
         url = '<?php echo HTTP_LIB_PATH;?>/Slabs/ajaxInitEdit.php';
         if(parseInt(document.EditSlabs.deliveredFrom.value,10) >= parseInt(document.EditSlabs.deliveredTo.value,10)){
           messageBox("<?php echo ENTER_ATT_ALERT; ?>");
           document.EditSlabs.deliveredFrom.focus();
           return false;  
         }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {slabId: (document.EditSlabs.slabId.value),
             deliveredFrom: trim(document.EditSlabs.deliveredFrom.value), 
             deliveredTo: trim(document.EditSlabs.deliveredTo.value), 
             marks: trim(document.EditSlabs.marks.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditSlabs');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo SLAB_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo SLAB_ALREADY_EXIST ;?>"); 
                         document.EditSlabs.deliveredFrom.focus();
                    }  
                     else {
                        messageBox(trim(transport.responseText)); 
                        document.EditSlabs.deliveredFrom.focus();                        
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditSlabs" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Slabs/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {slabId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditSlabs');
                        messageBox("<?php echo SLAB_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.EditSlabs.deliveredFrom.value = j.deliveredFrom;
                   document.EditSlabs.deliveredTo.value = j.deliveredTo;
                   document.EditSlabs.marks.value = j.marks;
                   document.EditSlabs.slabId.value = j.slabId;
                   document.EditSlabs.deliveredFrom.focus();
                  

             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


/* function to print slabs report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/slabsReportPrint.php?'+qstr;
    window.open(path,"SlabsReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Slabs/listSlabsContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listSlabs.php $ 
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
//*****************  Version 5  *****************
//User: Dipanjan     Date: 11/06/08   Time: 10:39a
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 10/24/08   Time: 12:15p
//Updated in $/Leap/Source/Interface
//Added functionality for slabs report print
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Interface
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:02p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/12/08    Time: 11:44a
//Created in $/Leap/Source/Interface
?>