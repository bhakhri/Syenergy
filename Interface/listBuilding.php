<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Building ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BuildingMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Building/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Building Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
 new Array('buildingName','Building Name','width="50%"','',true) , 
 new Array('abbreviation','Abbr.','width="40%"','',true), 
 new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Building/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBuilding';   
editFormName   = 'EditBuilding';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBuilding';
divResultName  = 'results';
page=1; //default page
sortField = 'buildingName';
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
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("buildingName","<?php echo ENTER_BUILDING_NAME; ?>"),
    new Array("abbreviation","<?php echo ENTER_BUILDING_ABBR; ?>") );

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='buildingName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo BUILDING_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           /*             
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           */ 
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addBuilding();
        return false;
    }
    else if(act=='Edit') {
        editBuilding();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Building
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBuilding() {
         url = '<?php echo HTTP_LIB_PATH;?>/Building/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {buildingName: (document.AddBuilding.buildingName.value), 
             abbreviation: (document.AddBuilding.abbreviation.value)},
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
                             hiddenFloatingDiv('AddBuilding');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo BUILDING_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo BUILDING_ALREADY_EXIST ;?>"); 
                           document.AddBuilding.buildingName.focus();
                     }
                     else if("<?php echo BUILDING_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo BUILDING_ABBR_ALREADY_EXIST ;?>"); 
                           document.AddBuilding.abbreviation.focus();
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A Building
//  id=busRouteId
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBuilding(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Building/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {buildingId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddBuilding" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBuilding.buildingName.value = '';
   document.AddBuilding.abbreviation.value = '';
   document.AddBuilding.buildingName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Building
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBuilding() {
         url = '<?php echo HTTP_LIB_PATH;?>/Building/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {buildingId: (document.EditBuilding.buildingId.value),
             buildingName: (document.EditBuilding.buildingName.value), 
             abbreviation: (document.EditBuilding.abbreviation.value)},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBuilding');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                    else if("<?php echo BUILDING_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo BUILDING_ALREADY_EXIST ;?>"); 
                           document.EditBuilding.buildingName.focus();
                    }
                    else if("<?php echo BUILDING_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)){
                           messageBox("<?php echo BUILDING_ABBR_ALREADY_EXIST ;?>"); 
                           document.EditBuilding.abbreviation.focus();
                    } 
                     else {
                        messageBox(trim(transport.responseText));                         
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBuilding" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (10.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Building/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {buildingId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBuilding');
                        messageBox("<?php echo BUILDING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditBuilding.buildingName.value = j.buildingName;
                   document.EditBuilding.abbreviation.value = j.abbreviation;
                   document.EditBuilding.buildingId.value = j.buildingId;
                   document.EditBuilding.buildingName.focus();

             },
             onFailure:function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }    
           });
}

/* function to print nuilding report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/buildingReportPrint.php?'+qstr;
    window.open(path,"BuildingReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='buildingReportCSV.php?'+qstr;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Building/listBuildingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listBuilding.php $ 
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 4/08/09    Time: 16:01
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--
//0000861 to 0000877
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
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/05/08   Time: 5:57p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/24/08   Time: 10:38a
//Updated in $/Leap/Source/Interface
//Added functionality for building report print
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/20/08    Time: 6:19p
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
//User: Dipanjan     Date: 7/10/08    Time: 6:54p
//Updated in $/Leap/Source/Interface
//Created Building Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/10/08    Time: 5:27p
//Created in $/Leap/Source/Interface
//Initial checkin
?>