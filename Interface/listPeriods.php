<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF PERIODS ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PeriodsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Periods/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Periods Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), new Array('slotName','Slot','width=20%','',true), new Array('periodNumber','Period Number','width=15%','align=right',true), new Array('startTime','Start Time','width="15%"','align=center',true), new Array('endTime','End Time','width="15%"','align=center',true), new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Periods/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddPeriods';   
editFormName   = 'EditPeriods';
winLayerWidth  = 330; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deletePeriods';
divResultName  = 'results';
page=1; //default page
sortField = 'slotName';
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
// Created on : (13.6.2008)
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
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   
    var fieldsArray = new Array(	new Array("periodNumber","<?php echo ENTER_PERIOD_NUMBER ?>"),
									new Array("startTime","<?php echo ENTER_START_TIME ?>"),
									new Array("endTime","<?php echo ENTER_END_TIME ?>")
		);

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

		else  if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='periodNumber') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_INTEGER_PERIOD ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }
         
        /*else if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='periodNumber' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo PERIOD_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
			
           /*else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='periodNumber' && fieldsArray[i][0]!='institutes') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
           
            else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo SPECIAL_CHARACTERS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }*/
			
            
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
  
    if(act=='Add') {
			 
	 if(!isAlphaNumericdot(document.getElementById('startTime').value)) {
			messageBox("<?php echo ACCEPT_INTEGER ?>");
			document.getElementById('startTime').focus();
			return false;
			
	   }

	 if(!isAlphaNumericdot(document.getElementById('endTime').value)) {
			messageBox("<?php echo ACCEPT_INTEGER ?>");
			document.getElementById('endTime').focus();
			return false;
	   }
	
	if(document.getElementById('startTime').value != '') {
	 if (!isTime2(document.getElementById('startTime').value)) {
		messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
		document.getElementById('startTime').focus();
		return false;
	 }
	}

	if(document.getElementById('endTime').value != '') {
	 if (!isTime2(document.getElementById('endTime').value)) {
		messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
		document.getElementById('endTime').focus();
		return false;
	 }
	}
	if(document.getElementById('startTime').value != '') {
		if(document.getElementById('endTime').value == '' || document.getElementById('endTime').value == '00:00') {
			messageBox("<?php echo ENTER_END_TIME ?>");
			document.getElementById('endTime').focus();
			return false;
		}
	}
	
	if(document.getElementById('endTime').value != '' ) {
		if(document.getElementById('startTime').value == '' || document.getElementById('startTime').value == '00:00') {
			messageBox("<?php echo ENTER_START_TIME ?>");
			document.getElementById('startTime').focus();
			return false;
		}
	}

	if(document.getElementById('startTime').value != '') {
			if(document.getElementById('startAmPm').value == '') {	
			messageBox("<?php echo SELECT_AM_PM ?>");
			document.getElementById('startAmPm').focus();
			return false;
		}
	}
	if(document.getElementById('startTime').value == document.getElementById('endTime').value ) {
			if (document.getElementById('startAmPm').value == document.getElementById('endAmPm').value) {
			messageBox("<?php echo START_TIME_AND_END_TIME ?>");
			document.getElementById('startTime').focus();
			return false;
		}
	}

	if(document.getElementById('endTime').value != '') {
			if(document.getElementById('endAmPm').value == '') {	
			messageBox("<?php echo SELECT_AM_PM ?>");
			document.getElementById('endAmPm').focus();
			return false;
		}
	}	

	/*
	if(document.getElementById('startTime').value != '' && document.getElementById('endTime').value != '') {
	 if (document.getElementById('startTime').value >= document.getElementById('endTime').value) {
		messageBox("<?php echo Start_TIME_NOT_GREATER ?>");
		document.getElementById('endTime').focus();
		return false;
	 }
	}
	*/
        addPeriods();
        return false;
    }
    else if(act=='Edit') {
		
	  if(!isAlphaNumericdot(document.editPeriods.startTime.value)) {
			messageBox("<?php echo ACCEPT_INTEGER ?>");
			document.editPeriods.startTime.focus();
			return false;
	   }

	 if(!isAlphaNumericdot(document.editPeriods.endTime.value)) {
			messageBox("<?php echo ACCEPT_INTEGER ?>");
			document.editPeriods.endTime.focus();
			return false;
	   }

	 if(document.editPeriods.startTime.value != '') {
	 if (!isTime2(document.editPeriods.startTime.value)) {
		messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
		document.editPeriods.startTime.focus();
		return false;
	 }
	}

	if(document.editPeriods.endTime.value != '') {
	 if (!isTime2(document.editPeriods.endTime.value)) {
		messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
		document.editPeriods.endTime.focus();
		return false;
	 }
	}

	if(document.editPeriods.startTime.value != '') {
		if(document.editPeriods.endTime.value == ''|| document.editPeriods.endTime.value == '00:00') {
			messageBox("<?php echo ENTER_END_TIME ?>");
			document.editPeriods.endTime.focus();
			return false;
		}
	}

	if(document.editPeriods.endTime.value != '') {
		if(document.editPeriods.startTime.value == ''|| document.editPeriods.startTime.value == '00:00') {
			messageBox("<?php echo ENTER_START_TIME ?>");
			document.editPeriods.startTime.focus();
			return false;
		}
	}

	if(document.editPeriods.startTime.value != '') {
			if(document.editPeriods.startAmPm.value == '') {	
			messageBox("<?php echo SELECT_AM_PM ?>");
			document.editPeriods.startAmPm.focus();
			return false;
		}
	}

	if(document.editPeriods.endTime.value != '') {
			if(document.editPeriods.endAmPm.value == '') {	
			messageBox("<?php echo SELECT_AM_PM ?>");
			document.editPeriods.endAmPm.focus();
			return false;
		}
	}

	/*
	if(document.editPeriods.startTime.value != '' && document.editPeriods.endTime.value != '') {
	 if (document.editPeriods.startTime.value >= document.editPeriods.endTime.value) {
		messageBox("<?php echo Start_TIME_NOT_GREATER ?>");
		document.editPeriods.startTime.focus();
		return false;
   
      }
	}
	*/

	
		editPeriods();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addPeriods() IS USED TO ADD NEW Periods
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addPeriods() {
         url = '<?php echo HTTP_LIB_PATH; ?>/Periods/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodNumber: (document.addPeriods.periodNumber.value), startTime: (document.addPeriods.startTime.value), startAmPm: (document.addPeriods.startAmPm.value), endTime: (document.addPeriods.endTime.value), endAmPm: (document.addPeriods.endAmPm.value),
			  slotName: (document.addPeriods.slotName.value)},
             
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
                             hiddenFloatingDiv('AddPeriods');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                    }
                    else {
						messageBox(trim(transport.responseText));
					if ("<?php echo TIME_ALREADY_ALLOTED;?>" == trim(transport.responseText)) {
						document.addPeriods.startTime.focus();
						return false;
					 }
					 else {
						//messageBox(trim(transport.responseText));
						document.addPeriods.periodNumber.focus();
                    }
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deletePeriods(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/Periods/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodId: id},
             
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

function blankValues() {
   document.addPeriods.periodNumber.value = '';
   document.addPeriods.startTime.value = '';
   document.addPeriods.endTime.value = '';
   document.addPeriods.startAmPm.value = '';
   document.addPeriods.endAmPm.value = '';
   document.addPeriods.periodNumber.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editPeriods() {
         url = '<?php echo HTTP_LIB_PATH;?>/Periods/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodId: (document.editPeriods.periodId.value), periodNumber: (document.editPeriods.periodNumber.value), startTime: (document.editPeriods.startTime.value), startAmPm: (document.editPeriods.startAmPm.value), endTime: (document.editPeriods.endTime.value), endAmPm: (document.editPeriods.endAmPm.value), slotName: (document.editPeriods.slotName.value)},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               
             onSuccess: function(transport){
                     hideWaitDialog(true);

                    if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditPeriods');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                     return false;
                     //location.reload();
                     }
					 else {
						messageBox(trim(transport.responseText));
						
						if ("<?php echo TIME_ALREADY_ALLOTED;?>" == trim(transport.responseText)) {
						//messageBox(trim(transport.responseText));
						document.editPeriods.startTime.focus();
						return false;
						}
					 else if (trim(transport.responseText)=='<?php echo PERIOD_NUMBER_EXIST?>'){
							document.editPeriods.periodNumber.focus();	
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
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Periods/ajaxGetValues.php';
         
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {periodId: id},
             
               onCreate: function() {    
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0){
                        hiddenFloatingDiv('EditPeriods'); 
                        messageBox("<?php echo PERIOD_NOT_EXIST ?>");
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       //exit();
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    
                   document.editPeriods.slotName.value = j.periodSlotId;
				   document.editPeriods.periodNumber.value = j.periodNumber;
                   document.editPeriods.startTime.value = j.startTime;
				   if (j.startAmPm == "") {
					document.editPeriods.startAmPm.value = "";
				   }
                   document.editPeriods.startAmPm.value = j.startAmPm;
                   document.editPeriods.endTime.value = j.endTime;
				   if (j.endAmPm == "") {
					document.editPeriods.endAmPm.value = "";
				   }
                   document.editPeriods.endAmPm.value = j.endAmPm;
                   document.editPeriods.periodId.value = j.periodId; 
                   document.editPeriods.periodNumber.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayPeriodsReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayPeriodsReport","status=1,menubar=1,scrollbars=1, width=900, height=700");
}

function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayPeriodsCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Periods/listPeriodsContents.php");
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
// $History: listPeriods.php $
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 4/20/10    Time: 5:55p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003312, 0003311, 0003298, 0003299
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:33p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001936,0001938,0001939
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/30/09    Time: 6:46p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001611, 0001632, 0001612, 0001600, 0001599, 0001598,
//0001594
//
//*****************  Version 13  *****************
//User: Gurkeerat    Date: 9/24/09    Time: 5:42p
//Updated in $/LeapCC/Interface
//resolve issue 1596
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Interface
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/06/09    Time: 6:21p
//Updated in $/LeapCC/Interface
//modified in message
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 7/30/09    Time: 7:02p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/22/09    Time: 7:24p
//Updated in $/LeapCC/Interface
//changes to fix bugs
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/22/09    Time: 12:28p
//Updated in $/LeapCC/Interface
//fixed bug no.0000597 & put new message
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/16/09    Time: 11:07a
//Updated in $/LeapCC/Interface
//bug fixed no. 0000074 of mantis
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 5/19/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//show print during search
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/10/09    Time: 2:43p
//Updated in $/LeapCC/Interface
//modified to save only selected am, pm
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/08/09    Time: 2:46p
//Updated in $/LeapCC/Interface
//remove mandatory fields on start time & end time
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:36p
//Created in $/LeapCC/Interface
//get existing period files in leap
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 12/16/08   Time: 3:19p
//Updated in $/Leap/Source/Interface
//modified to get slot name
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:10p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 10/30/08   Time: 11:27a
//Updated in $/Leap/Source/Interface
//modified
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 10/25/08   Time: 5:43p
//Updated in $/Leap/Source/Interface
//add new field time table label Id
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:00p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:32a
//Updated in $/Leap/Source/Interface
//remove the delete message
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/29/08    Time: 11:07a
//Updated in $/Leap/Source/Interface
//modification in indentation
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/26/08    Time: 5:27p
//Updated in $/Leap/Source/Interface
//modified message
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 8/01/08    Time: 4:46p
//Updated in $/Leap/Source/Interface
//modified in OnCreate & OnSuccess functions
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 7/28/08    Time: 7:39p
//Updated in $/Leap/Source/Interface
//modified for institute id
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:37p
//Updated in $/Leap/Source/Interface
//change alert in message box
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:24p
//Updated in $/Leap/Source/Interface
//modified in message box
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/18/08    Time: 10:18a
//Updated in $/Leap/Source/Interface
//modification during duplicate record, text box should be empty and
//cursoe on text box
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 7/17/08    Time: 8:04p
//Updated in $/Leap/Source/Interface
//fixed the bug
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/12/08    Time: 12:07p
//Updated in $/Leap/Source/Interface
//concat AM & PM fields in database query
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:23p
//Updated in $/Leap/Source/Interface
//modified for special characters check
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/05/08    Time: 5:15p
//Updated in $/Leap/Source/Interface
//modified for add, edit & delete
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:19p
//Updated in $/Leap/Source/Interface
//modification with new ajax functions
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/25/08    Time: 4:15p
//Updated in $/Leap/Source/Interface
//modified in error occured during deletion
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:45p
//Updated in $/Leap/Source/Interface
//modified in add period
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/25/08    Time: 3:31p
//Updated in $/Leap/Source/Interface
//modified in validation function
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/08    Time: 12:57p
//Updated in $/Leap/Source/Interface
//modified delete with ajax
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:48p
//Updated in $/Leap/Source/Interface
?>