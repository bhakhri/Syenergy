<?php
//-------------------------------------------------------
// Purpose: To generate the list of fee cycle from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (27.06.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FeeCycleMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/FeeCycle/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fee Cycle Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('cycleName','Name','width=15%','',true), new Array('cycleAbbr','Abbr.','width="15%"','',true), new Array('fromDate','From','width="15%"','align=center',true), new Array('toDate','To','width="15%"','align=center',true), new Array('action','Action','width="3%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeeCycle/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFeeCycle';   
editFormName   = 'EditFeeCycle';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFeeCycle';
divResultName  = 'results';
page=1; //default page
sortField = 'cycleName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (27.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//Author : Jaineesh
// Created on : (27.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("cycleName","<?php echo ENTER_FEECYCLE_NAME;?>"),new Array("cycleAbbr","<?php echo ENTER_FEECYCLE_ABBR;?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(act=='Add') {
        if(!dateDifference(eval("frm.fromDate.value"),eval("frm.toDate.value"),'-') ) {
                messageBox("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate.focus();");
                return false;
                break;
         } 
       }
       else if(act=='Edit') {
        if(!dateDifference(eval("frm.fromDate1.value"),eval("frm.toDate1.value"),'-') ) {
                messageBox("<?php echo DATE_VALIDATION;?>");
                eval("frm.fromDate1.focus();");
                return false;
                break;
         } 
       }  
        /*else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if((eval("frm."+(fieldsArray[i][0])+".value.length"))<3 && fieldsArray[i][0]=='stateName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("State Name can not be less than 3 characters");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
                        
            if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='roomCapacity' && fieldsArray[i][0] != 'roomRent' && fieldsArray[i][0]!='hostelName' && fieldsArray[i][0]!='roomName') {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("Enter only alphabetical characters (a-z) ");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        } */
     
    }
    if(act=='Add') {
        addFeeCycle();
        return false;
    }
    else if(act=='Edit') {
        editFeeCycle();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addFeeCycle() IS USED TO ADD NEW HOSTEL ROOM
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFeeCycle() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycle/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	cycleName: (document.addFeeCycle.cycleName.value), 
							cycleAbbr: (document.addFeeCycle.cycleAbbr.value), 
							fromDate: (document.addFeeCycle.fromDate.value), 
							toDate: (document.addFeeCycle.toDate.value)},
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
                             hiddenFloatingDiv('AddFeeCycle');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo CYCLE_ABBR_EXIST ?>'){
							document.addFeeCycle.cycleAbbr.focus();	
						}
						else {
							document.addFeeCycle.cycleName.focus();
						}
                     }
               
             },
			  onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEFeeCycle() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFeeCycle(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycle/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
               
                     hideWaitDialog(true);
                 //    messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                        else {
                         messageBox(trim(transport.responseText));
                     }
              
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
}

//-------------------------------------------------------
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues() {
   document.addFeeCycle.cycleName.value = '';
   document.addFeeCycle.cycleAbbr.value = '';
   document.addFeeCycle.fromDate.value = '';
   document.addFeeCycle.toDate.value = '';
   document.addFeeCycle.cycleName.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (27.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFeeCycle() {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycle/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: (document.editFeeCycle.feeCycleId.value), cycleName: (document.editFeeCycle.cycleName.value), cycleAbbr: (document.editFeeCycle.cycleAbbr.value), fromDate1: (document.editFeeCycle.fromDate1.value), toDate1: (document.editFeeCycle.toDate1.value)},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
               
                     hideWaitDialog(true);
                  //   messageBox(trim(transport.responseText));
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFeeCycle');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                        else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=='<?php echo CYCLE_ABBR_EXIST ?>'){
							document.editFeeCycle.cycleAbbr.focus();	
						}
						else {
							document.editFeeCycle.cycleName.focus();
						}
                     }
               
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/FeeCycle/ajaxGetValues.php';
          new Ajax.Request(url,
           {
             method:'post',
             parameters: {feeCycleId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
             onSuccess: function(transport){
              
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditFeeCycle');
                        messageBox('Cycle Name does not exist');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    }
                    j = eval('('+trim(transport.responseText)+')');  
                   document.editFeeCycle.cycleName.value = j.cycleName;
                   document.editFeeCycle.cycleAbbr.value = j.cycleAbbr;
                   document.editFeeCycle.fromDate1.value = j.fromDate;
                   document.editFeeCycle.toDate1.value = j.toDate;
                   document.editFeeCycle.feeCycleId.value = j.feeCycleId;
                   document.editFeeCycle.cycleName.focus();
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayFeeCycleReport.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   window.open(path,"DisplayFeeCycleReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    
    path='<?php echo UI_HTTP_PATH;?>/displayFeeCycleCSV.php?searchbox='+(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.location = path;
}
function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.addFeeCycle;
 }
 else{
     var form = document.editFeeCycle;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/FeeCycle/listFeeCycleContents.php");
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
// $History: listFeeCycle.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 10/21/09   Time: 10:38a
//Updated in $/LeapCC/Interface
//print & csv function updated
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/17/09    Time: 1:27p
//Updated in $/LeapCC/Interface
//Gurkeerat: resolved issue 927
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/13/09    Time: 4:55p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000932,0000544,0000550,0000549,0000949
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
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
//*****************  Version 10  *****************
//User: Parveen      Date: 11/06/08   Time: 12:49p
//Updated in $/Leap/Source/Interface
//Define Module, Access  Added
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/20/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//REPLACED VALIDATION MESSAGES WITH COMMON MESSAGES
//
//*****************  Version 8  *****************
//User: Arvind       Date: 8/07/08    Time: 3:28p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 7  *****************
//User: Arvind       Date: 8/01/08    Time: 11:27a
//Updated in $/Leap/Source/Interface
//added oncreate function 
//
//*****************  Version 6  *****************
//User: Arvind       Date: 7/29/08    Time: 1:28p
//Updated in $/Leap/Source/Interface
//modified date validatuion alert
//
//*****************  Version 5  *****************
//User: Arvind       Date: 7/24/08    Time: 7:35p
//Updated in $/Leap/Source/Interface
//modified the whole file.done many changes
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:32p
//Updated in $/Leap/Source/Interface
//change alert from message box
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:30p
//Updated in $/Leap/Source/Interface
//modified for calendar, todate not smaller than fromdate
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/05/08    Time: 4:26p
//Updated in $/Leap/Source/Interface
//modification for edit, delete, add
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/28/08    Time: 1:43p
//Created in $/Leap/Source/Interface
//give the list of fee cycle
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/27/08    Time: 12:28p
//Created in $/Leap/Source/Interface
//declaring ajax function during add, edit, delete passing through
//parameters & show the list
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
