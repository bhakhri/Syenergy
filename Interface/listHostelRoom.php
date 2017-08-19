<?php
//-------------------------------------------------------
// Purpose: To generate the list of states from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Jaineesh
// Created on : (26.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomCourse');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/HostelRoom/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hostel Room Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = 	new Array(new Array('srNo','#','width="3%"','',false), 
			new Array('roomName','Room Name','width=15%','',true),
			new Array('hostelName','Hostel Name','width="15%"','',true),
			new Array('roomCapacity','Room Capacity','width="15%"','align=right',true), 
			new Array('roomType','Room Type','width="15%"','',true), 
			new Array('roomFloor','Room Floor','width="15%"','',true),
			new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHostelRoom';   
editFormName   = 'EditHostelRoom';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHostelRoom';
divResultName  = 'results';
page=1; //default page
sortField = 'roomName';
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("roomName","<?php echo ENTER_HOSTELROOM_NAME ?>"),
								new Array("hostelName","<?php echo ENTER_HOSTEL_NAME ?>"),
								new Array("hostelroomtype","<?php echo ENTER_HOSTEL_ROOM_TYPE_NAME ?>"),
								new Array("roomCapacity","<?php echo ENTER_HOSTELROOM_CAPACITY ?>"),
								new Array("roomFloor","Enter Room Floor"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if (fieldsArray[i][0]=="roomCapacity"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_NUMBER ?>");
               // document.addHostelRoom.roomCapacity.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
     
		
		else if (fieldsArray[i][0]=="roomName"){
                if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS_HOSTEL_ROOM_NUMERIC ?>");
               // document.addHostelRoom.roomRent.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
		}
		
		else if (fieldsArray[i][0]=="roomFloor"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_NUMBER ?>");
               // document.addHostelRoom.roomCapacity.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
		  
            
        
            /*if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='roomCapacity' && fieldsArray[i][0] != 'roomRent' && fieldsArray[i][0]!='roomName') {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/

            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     

    if(act=='Add') {
        addHostelRoom();
        return false;
    }
    else if(act=='Edit') {
        editHostelRoom();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addHostelRoom() IS USED TO ADD NEW HOSTEL ROOM
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHostelRoom() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	hostelId: (document.addHostelRoom.hostelName.value), 
							roomName: (document.addHostelRoom.roomName.value), 
							roomCapacity: (document.addHostelRoom.roomCapacity.value),
							hostelroomtype: (document.addHostelRoom.hostelroomtype.value),
							roomFloor: (document.addHostelRoom.roomFloor.value)},
             
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
                             hiddenFloatingDiv('AddHostelRoom');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //    location.reload();
                             return false;
                         }
                     }
                     else {
				 messageBox(trim(transport.responseText)); 
				if (trim(transport.responseText)=="<?php echo ROOM_NOT_GREATER ?>"){
					document.addHostelRoom.roomName.focus();
				}
				if (trim(transport.responseText)=="<?php echo CAPACITY_NOT_GREATER ?>"){
					document.addHostelRoom.roomCapacity.focus();
				}
				else {
				        document.addHostelRoom.roomName.value='';
				        document.addHostelRoom.roomName.focus();
				}
                        
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEHOSTELROOM() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteHostelRoom(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelRoomId: id},
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues() {
   //document.addHostelRoom.hostelName.value = '';
   document.addHostelRoom.roomName.value = '';
   document.addHostelRoom.roomCapacity.value = '';
   document.addHostelRoom.hostelName.value = '';
   document.addHostelRoom.hostelroomtype.value = '';
   document.addHostelRoom.roomFloor.value = '';
   document.addHostelRoom.roomName.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editHostelRoom() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           { 
             method:'post',
             parameters: {hostelRoomId: (document.editHostelRoom.hostelRoomId.value), 
             	          hostelId: trim(document.editHostelRoom.hostelName.value), 
             	          roomName: trim(document.editHostelRoom.roomName.value), 
             	          roomCapacity: (document.editHostelRoom.roomCapacity.value),
             	          hostelroomtype: (document.editHostelRoom.hostelroomtype.value),
             	          roomFloor: (document.editHostelRoom.roomFloor.value)},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     	 messageBox(transport.responseText);
                         hiddenFloatingDiv('EditHostelRoom');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
			 else {
         			messageBox(trim(transport.responseText));
				 if (trim(transport.responseText)=="<?php echo ROOM_NOT_GREATER ?>"){
					document.editHostelRoom.roomName.focus();
				}
				if (trim(transport.responseText)=="<?php echo CAPACITY_NOT_GREATER ?>"){
					document.editHostelRoom.roomCapacity.focus();
				}
				else {
				 document.editHostelRoom.roomName.focus();
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelRoomId: id},
                            
               onCreate: function() {    
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditHostelRoom');
                        messageBox("<?php echo HOSTELROOM_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                    }
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.editHostelRoom.roomName.value = j.roomName;
                   document.editHostelRoom.hostelName.value = j.hostelId;
                   document.editHostelRoom.hostelRoomId.value = j.hostelRoomId;
                   getRoomType(j.hostelId,'edit',j.hostelRoomTypeId);
                    document.editHostelRoom.roomCapacity.value = j.roomCapacity;
                    document.editHostelRoom.roomFloor.value = j.roomFloor;
                   document.editHostelRoom.roomName.focus();
                   
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO Get hostel room type capacity & rent
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getRoomDetail() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxGetRoomTypeCapacity.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	hostelRoomTypeId: document.addHostelRoom.hostelroomtype.value,
				hostelId: document.addHostelRoom.hostelName.value
			},  
               onCreate: function() {    
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //messageBox("<?php echo HOSTEL_ROOM_TYPE_NO_VALUE;?>");
						//document.getElementById('hostelroomtype').value = '';
						document.addHostelRoom.roomCapacity.value = '';
						return false;
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                    }
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.addHostelRoom.roomCapacity.value = j.capacity;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO Get hostel room type capacity & rent
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getRoomEditDetail() {

         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/ajaxGetRoomTypeCapacity.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {	hostelRoomTypeId: document.editHostelRoom.hostelroomtype.value,
							hostelId: document.editHostelRoom.hostelName.value},
                            
               onCreate: function() {    
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        messageBox("<?php echo HOSTEL_ROOM_TYPE_NO_VALUE;?>");
						document.editHostelRoom.hostelroomtype.value = '';
						document.editHostelRoom.roomCapacity.value = '';
						return false;
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                    }
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.editHostelRoom.roomCapacity.value = j.capacity;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO Get hostel room type capacity & rent
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function changeRoomType() {
	//alert(1);
         //if(document.addHostelRoom.hostelName.value == '') {
			 document.addHostelRoom.hostelroomtype.value = '';
			 document.addHostelRoom.roomCapacity.value = '';
		 //}
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO Get Room Types
//Author : Nishu Bindal
// Created on : (29.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	function getRoomType(hostelId,act,selectedId){
		 if(act == 'add'){   
			form = document.addHostelRoom; 
			 form.roomCapacity.value = '';
		}
		else{
			form = document.editHostelRoom;
			 form.roomCapacity.value = '';
		}
		var url = '<?php echo HTTP_LIB_PATH;?>/HostelRoom/getHostelRoomType.php';
		new Ajax.Request(url,
		{
			method:'post',
			parameters:{	hostelId:hostelId
				},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.hostelroomtype.length = null;
				if(j==0 || len == 0) {
					addOption(form.hostelroomtype, '', 'Select');
					return false;
				}
				else{
					addOption(form.hostelroomtype, '', 'Select'); 
					for(i=0;i<len;i++) {
						if(selectedId == j[i].hostelRoomTypeId){ 
   							 var objOption = new Option(j[i].roomType,j[i].hostelRoomTypeId,'selected=selected');
							form.hostelroomtype.options.add(objOption);
						}
						else{
							addOption(form.hostelroomtype, j[i].hostelRoomTypeId, j[i].roomType);
						}
					}
				}
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO Get hostel room type capacity & rent during edit
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function changeEditRoomType() {
	//alert(1);
         //if(document.addHostelRoom.hostelName.value == '') {
			 document.editHostelRoom.hostelroomtype.value = '';
			 document.editHostelRoom.roomCapacity.value = '';
		 //}
}



function printReport() {
	var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
	path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomReport.php?'+qstr;
    window.open(path,"DisplayHostelRoomReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/HostelRoom/listHostelRoomContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	<SCRIPT LANGUAGE="JavaScript">

		sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 

	</SCRIPT>
</body>
</html>
<?php
// $History: listHostelRoom.php $
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/26/09   Time: 4:20p
//Updated in $/LeapCC/Interface
//done changes to save, edit & show hostel type according to hostel name
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/21/09    Time: 7:28p
//Updated in $/LeapCC/Interface
//fixed bugs during self testing
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Interface
//give print & export to excel facility
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:47p
//Created in $/LeapCC/Interface
//put some conditions on hostel room & capacity
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 7/22/09    Time: 7:24p
//Updated in $/Leap/Source/Interface
//changes to fix bugs
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 7/16/09    Time: 3:46p
//Updated in $/Leap/Source/Interface
//fire event on hostel room type at edit
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 7/16/09    Time: 1:22p
//Updated in $/Leap/Source/Interface
//Put new messages for hostel room type 
//Get capacity & rent by selecting room type
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 7/11/09    Time: 4:22p
//Updated in $/Leap/Source/Interface
//fixed bug no.0000091
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 6/09/09    Time: 10:29a
//Updated in $/Leap/Source/Interface
//fixed bug nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313 to 1317
//bugs of cc fixed on sc also
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 6/05/09    Time: 1:07p
//Updated in $/Leap/Source/Interface
//add new field hostel room type
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 6/01/09    Time: 12:03p
//Updated in $/Leap/Source/Interface
//put link of hostel room
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 3/19/09    Time: 10:51a
//Updated in $/Leap/Source/Interface
//fixed bug to give room name according to hostel name
//
//*****************  Version 14  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/16/09    Time: 1:32p
//Updated in $/Leap/Source/Interface
//modified left alignment
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Interface
//make changes for sending sendReq() function
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 1/10/09    Time: 4:24p
//Updated in $/Leap/Source/Interface
//use required fields and left labels
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:46p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:00p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/25/08    Time: 6:02p
//Updated in $/Leap/Source/Interface
//fixed bug
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:10p
//Updated in $/Leap/Source/Interface
//modified in indentation
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/22/08    Time: 11:05a
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:57p
//Updated in $/Leap/Source/Interface
//modification in edit or delete messages
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/01/08    Time: 4:26p
//Updated in $/Leap/Source/Interface
//modified for OnCreate & OnSuccess functions
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:19p
//Updated in $/Leap/Source/Interface
//change alert in message box
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/30/08    Time: 2:52p
//Updated in $/Leap/Source/Interface
//modification with ajax functions
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
