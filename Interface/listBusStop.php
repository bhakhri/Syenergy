<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BusStopCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/BusStop/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Stop Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
new Array('routeCode','Vehicle Route','width="200"','',true),
new Array('studentCount','No. of students','width="150"','align="right"',true) ,   
new Array('stopName','Vehicle Stop','width="250"','',true) ,
new Array('stopAbbr','Abbr','width="150"','',true), 
new Array('scheduleTime','Time','width="150"','align="center"',true), 
new Array('transportCharges','Transport Charges','width="150"','align="right"',true),
new Array('action','Action','width="4%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBusStop';   
editFormName   = 'EditBusStop';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBusStop';
divResultName  = 'results';
page=1; //default page
sortField = 'routeCode';
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
// Created on : (26.6.2008)
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("stopName","<?php echo ENTER_STOP_NAME ?>"),
    new Array("stopAbbr","<?php echo ENTER_STOP_ABBR ?>"),
    new Array("routeCode","<?php echo SELECT_BUS_ROUTE_CODE ?>"),
    new Array("scheduleTime","Enter schedule time"),
    new Array("transportCharges","<?php echo ENTER_TRANSPORT_CHARGES ?>")
    );

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
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='stopName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo STOP_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=='scheduleTime' && !isTime(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("Invalid schedule time");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
            if((fieldsArray[i][0]!='scheduleTime' &&  fieldsArray[i][0]!='transportCharges' ) && !isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("<?php echo ENTER_ALPHABETS_NUMERIC; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=='transportCharges' && !isDecimal(eval("frm."+(fieldsArray[i][0])+".value"))) {
                alert("Enter correct value for transport charges");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=='transportCharges' && parseFloat(eval("frm."+(fieldsArray[i][0])+".value"))<=0 ) {
                alert("Transport charges must be greater than zero");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }

			if(fieldsArray[i][0]=='transportCharges')
				if (parseFloat(eval("frm."+(fieldsArray[i][0])+".value")) > 100000 ) {
                alert("Transport charges cannot greater than 100000");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
     
    }
    
    if(act=='Add') {
        addBusStop();
        return false;
    }
    else if(act=='Edit') {
        editBusStop();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addBusStop() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {stopName: (document.AddBusStop.stopName.value), 
             stopAbbr: (document.AddBusStop.stopAbbr.value), 
             routeCode: (document.AddBusStop.routeCode.value), 
             scheduleTime: (document.AddBusStop.scheduleTime.value), 
             transportCharges: (document.AddBusStop.transportCharges.value)
             },
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
                             hiddenFloatingDiv('AddBusStop');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else if("<?php echo STOP_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STOP_ALREADY_EXIST ;?>"); 
                       document.AddBusStop.stopName.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteBusStop(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopId: id},
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addBusStop" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddBusStop.stopName.value = '';
   document.AddBusStop.stopAbbr.value = '';
   document.AddBusStop.routeCode.selectedIndex=0; 
   document.AddBusStop.scheduleTime.value=''; 
   document.AddBusStop.transportCharges.value= '';
   document.AddBusStop.stopName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editBusStop() {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopId: (document.EditBusStop.busStopId.value),
              stopName: (document.EditBusStop.stopName.value), 
              stopAbbr: (document.EditBusStop.stopAbbr.value), 
              routeCode: (document.EditBusStop.routeCode.value), 
              scheduleTime: (document.EditBusStop.scheduleTime.value), 
              transportCharges: (document.EditBusStop.transportCharges.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditBusStop');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                   else if("<?php echo STOP_ALREADY_EXIST;?>" == trim(transport.responseText)){
                       messageBox("<?php echo STOP_ALREADY_EXIST ;?>"); 
                       document.EditBusStop.stopName.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText));                         
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusStop" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {busStopId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditBusStop');
                        messageBox("<?php echo BUSSTOP_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.EditBusStop.stopName.value = j.stopName;
                   document.EditBusStop.stopAbbr.value = j.stopAbbr;
                   document.EditBusStop.transportCharges.value = j.transportCharges;
                   document.EditBusStop.routeCode.value = j.busRouteId;
                   document.EditBusStop.scheduleTime.value = j.scheduleTime;
                   document.EditBusStop.busStopId.value = j.busStopId;
                   document.EditBusStop.stopName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusStop" DIV
//
//Author : Jaineesh
// Created on : (2.4.09)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function autoCharges(id) {
	     url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxGetCharges.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {routeCode: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('AddBusStop');
                        //messageBox("<?php echo CITY_NOT_EXIST; ?>");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.AddBusStop.transportCharges.value = j.routeCharges;
				   //document.AddBusStop.stopName.focus();
                   
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditBusStop" DIV
//
//Author : Jaineesh
// Created on : (2.4.09)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function autoEditCharges(id) {
	     url = '<?php echo HTTP_LIB_PATH;?>/BusStop/ajaxGetCharges.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {routeCode: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AddBusStop');
                        messageBox("<?php echo CITY_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');
                   
                   document.AddBusStop.transportCharges.value = j.routeCharges;
				   //document.AddBusStop.stopName.focus();
                   
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print bus stop report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busStopReportPrint.php?'+qstr;
    window.open(path,"BusStopReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/busStopReportCSV.php?'+qstr;
	window.location = path;
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/BusStop/listBusStopContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listBusStop.php $ 
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/14/09    Time: 6:36p
//Updated in $/LeapCC/Interface
//put route charges and check box 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/27/09    Time: 2:02p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 1299
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 27/08/09   Time: 11:34
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids---
//00001283,00001294,00001297
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 11/07/09   Time: 16:41
//Updated in $/LeapCC/Interface
//Make combination of  busstop name and route code unique
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 30/06/09   Time: 17:45
//Updated in $/LeapCC/Interface
//Corrected look and feel of masters which are detected during user
//documentation preparation
//
//*****************  Version 5  *****************
//User: Administrator Date: 4/06/09    Time: 13:05
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--Issues[03-june-09].doc(1 to 11)
//
//*****************  Version 4  *****************
//User: Administrator Date: 2/06/09    Time: 11:34
//Updated in $/LeapCC/Interface
//Done bug fixing.
//BugIds : 1167 to 1176,1185
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
//*****************  Version 11  *****************
//User: Dipanjan     Date: 11/05/08   Time: 6:17p
//Updated in $/Leap/Source/Interface
//Added access rules
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 10/23/08   Time: 6:45p
//Updated in $/Leap/Source/Interface
//Added functionality for bus stop report print
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/24/08    Time: 10:21a
//Updated in $/Leap/Source/Interface
//Added functionilty for busRouteId in bus stop master
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/20/08    Time: 3:14p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/08/08    Time: 3:06p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:03p
//Updated in $/Leap/Source/Interface
//Added onCreate() function in ajax code
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/05/08    Time: 1:00p
//Updated in $/Leap/Source/Interface
//Modifies" instituId"  insertion so that it comes from session variable
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/28/08    Time: 4:33p
//Updated in $/Leap/Source/Interface
//Added AjaxListing Functionality
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 6/26/08    Time: 7:06p
//Updated in $/Leap/Source/Interface
//Modifying Page Title
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/26/08    Time: 5:30p
//Updated in $/Leap/Source/Interface
//Created BusStop Module
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/26/08    Time: 4:01p
//Created in $/Leap/Source/Interface
//Initial Checkin
?>