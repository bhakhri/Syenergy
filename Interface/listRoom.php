<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoomsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//include_once(BL_PATH ."/Room/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Room Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','',false), 
								new Array('roomName','Room Name','width=10%','align=left',true) , 
								new Array('roomAbbreviation','Abbr.','width="10%"','align=left',true), 
								new Array('roomType','Room Type','width="10%"','align=left',true),
								new Array('buildingName','Building','width="15%"','align=left',true), 
								new Array('blockName','Block','width="12%"','align=left',true), 
								new Array('capacity','Capacity','width="7%"','align=right',true), 
								new Array('examCapacity','Examroom Capacity','width="7%"','align=right',true), 
								new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRoom';   
editFormName   = 'EditRoom';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRoom';
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
// Created on : (26.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(	new Array("roomName","<?php echo ENTER_ROOM_NAME ?>"),
									new Array("roomAbbreviation","<?php echo ENTER_ROOM_ABBR ?>"),
									new Array("roomType","<?php echo ENTER_ROOM_TYPE ?>"),
									new Array("building","<?php echo CHOOSE_BUILDING ?>"),	
									new Array("blockName","<?php echo CHOOSE_BLOCK_NAME ?>"),
									new Array("capacity","<?php echo ENTER_ROOM_CAPACITY ?>"), 
									new Array("examCapacity","<?php echo ENTER_EXAM_CAPACITY ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="examCapacity" ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="capacity"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))
             {
                messageBox("<?php echo ENTER_NUMBER ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                eval("frm."+(fieldsArray[i][0])+".value='';");
                return false;
                break;
                
             }
            }
		else if(fieldsArray[i][0]=="examCapacity"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value")))
             {
                messageBox("<?php echo ENTER_NUMBER ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                eval("frm."+(fieldsArray[i][0])+".value='';");
                return false;
                break;
                
             }
            }
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_OFFENSE_ALPHABETS_NUMERIC ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    
    if(act=='Add') {
   //  if("<?php echo $sessionHandler->getSessionVariable('RoleId');?>"=="1"){
        if(document.addRoom.instituteId.value==""){
            messageBox("<?php echo SELECT_INSTITUTE; ?>");
            document.addRoom.instituteId.focus();
            return false;
        } 
   //  }   
        addRoom();
        return false;
    }
    else if(act=='Edit') {
    // if("<?php echo $sessionHandler->getSessionVariable('RoleId');?>"=="1"){
        if(document.editRoom.instituteId.value==""){
            messageBox("<?php echo SELECT_INSTITUTE; ?>");
            document.editRoom.instituteId.focus();
            return false;
        } 
     //}
        editRoom();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addRoom() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRoom() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxInitAdd.php';
      //   if("<?php echo $sessionHandler->getSessionVariable('RoleId');?>"=="1"){
            var l=document.addRoom.instituteId.length;
            var insId="";
            for(var i=0 ; i < l ;i++){
             if(document.addRoom.instituteId.options[ i ].selected && document.addRoom.instituteId.options[ i ].value!=''){
                 if(insId!=""){
                     insId +=',';
                 }
                 insId +=document.addRoom.instituteId.options[ i ].value;
             }
         }
        // }
       //  else{
      //       var insId=document.getElementById('hiddenInstituteId').value;
      //   }
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 roomName: trim(document.addRoom.roomName.value), 
                 roomAbbreviation: trim(document.addRoom.roomAbbreviation.value), 
                 roomType: (document.addRoom.roomType.value),
                 blockName: (document.addRoom.blockName.value), 
                 capacity: trim(document.addRoom.capacity.value),
			     examCapacity: trim(document.addRoom.examCapacity.value),
                 instituteId:insId
             },
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                                         
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                        if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")){
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddRoom');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       //      location.reload();
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo ROOM_NAME_EXIST ?>"){
							//document.addRoom.roomName.value='';
							document.addRoom.roomName.focus();	
						}
						else {
							//document.addRoom.roomAbbreviation.value='';
							document.addRoom.roomAbbreviation.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEGROUP() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteRoom(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomId: id},
            
             onCreate: function() {  
                  showWaitDialog(true);
             },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else{
                        messageBox(trim(transport.responseText));
                    } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
}
//-------------------------------------------------------
//THIS FUNCTION blankValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (2.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
function blankValues() {
   /*document.addRoom.roomName.value = '';
   document.addRoom.roomAbbreviation.value = '';
   document.addRoom.roomType.value = '';
   document.addRoom.blockName.value = '';
   document.addRoom.capacity.value = '';
   document.addRoom.examCapacity.value = '';
   */
   document.addRoom.reset();
   document.addRoom.roomName.focus();
   if (document.addRoom.building.value=='') {
		document.addRoom.blockName.length = null;
		addOption(document.addRoom.blockName, '', 'Select');
		return false;
	}
   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRoom() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxInitEdit.php';
      //   if("<?php echo $sessionHandler->getSessionVariable('RoleId');?>"=="1"){
            var l=document.editRoom.instituteId.length;
            var insId="";
            for(var i=0 ; i < l ;i++){
             if(document.editRoom.instituteId.options[ i ].selected && document.editRoom.instituteId.options[ i ].value!=''){
                 if(insId!=""){
                     insId +=',';
                 }
                 insId +=document.editRoom.instituteId.options[ i ].value;
             }
         }
      //   }
      //   else{
     //        var insId=document.getElementById('hiddenInstituteId').value;
      //   }
         
         new Ajax.Request(url,
           {
             method:'post',
					parameters: {
                        roomId: (document.editRoom.roomId.value), 
					    roomName: trim(document.editRoom.roomName.value), 
					    roomAbbreviation: trim(document.editRoom.roomAbbreviation.value), 
					    roomType: (document.editRoom.roomType.value),
					    blockName: (document.editRoom.blockName.value), 
					    capacity: trim(document.editRoom.capacity.value),
					    examCapacity: trim(document.editRoom.examCapacity.value),
                        instituteId:insId
                    },
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditRoom');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                        // location.reload();
                     }
					 else {
						messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=="<?php echo ROOM_NAME_EXIST?>"){
							//document.editRoom.roomName.value='';
							document.editRoom.roomName.focus();	
						}
						else if(trim(transport.responseText)=="<?php echo ROOM_ALREADY_EXIST?>") {
							//document.editRoom.roomAbbreviation.value='';
							document.editRoom.roomAbbreviation.focus();
						}
                        else{
                          document.editRoom.instituteId.focus();  
                        }
				   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/*  Author : Jaineesh
    created on : 17th June'08
    This function populate the values into the text boxes by using designationId.
    
 */
function populateValues(id) {
    
        var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetValues.php';
        document.editRoom.reset(); 
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomId: id},
                onCreate: function() {   
                  showWaitDialog(true);
                },
               
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditRoom');
                        messageBox("<?php echo ROOM_NOT_EXIST ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);         
                    }
                   var ret=trim(transport.responseText).split('~!~!~');
                   var j = eval('('+ret[0]+')');
                   
                   document.editRoom.roomName.value = j.roomName;
                   document.editRoom.roomAbbreviation.value = j.roomAbbreviation;
                   document.editRoom.roomType.value = j.roomTypeId;
				   document.editRoom.building.value = j.buildingId;
                   document.editRoom.blockName.value = j.blockId;
				  // alert(j.blockId);
                   document.editRoom.capacity.value= j.capacity;
				   document.editRoom.examCapacity.value = j.examCapacity;
                   document.editRoom.roomId.value = j.roomId;
                   
                   
               //    if("<?php echo $sessionHandler->getSessionVariable('RoleId'); ?>"=="1"){
                       if(ret.length>1){
                           var insId=document.editRoom.instituteId;
                           var c1=insId.length;
                           var k = eval('('+ret[1]+')');
                           var c2=k.length;
                           for(var m=0;m<c1;m++){
                               for(var n=0;n<c2;n++){
                                   if(insId.options[m].value==k[n]['instituteId']){
                                      insId.options[m].selected=true; 
                                   }
                               }
                           }
                       }
                 //  }
                   getEditBlock(j.blockId);
                   document.editRoom.roomName.focus();
				   
                                      
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getBlock() {
	form = document.addRoom;
	var url = '<?php echo HTTP_LIB_PATH;?>/Room/getBlock.php';
	var pars = 'buildingId='+form.building.value;

	if (form.building.value=='') {
		form.blockName.length = null;
		addOption(form.blockName, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			if(j==0) {
				form.blockName.length = null;
				addOption(form.blockName, '', 'Select');
				return false;
			}
			len = j.length;
			form.blockName.length = null;
			addOption(form.blockName, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.blockName, j[i].blockId, j[i].blockName);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function getEditBlock(myBlockId) {
	form = document.editRoom;
	var url = '<?php echo HTTP_LIB_PATH;?>/Room/getBlock.php';
	var pars = 'buildingId='+form.building.value;

	if (form.building.value=='') {
		form.blockName.length = null;
		addOption(form.blockName, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			if(j==0) {
				form.blockName.length = null;
				addOption(form.blockName, '', 'Select');
				return false;
			}
			len = j.length;
			form.blockName.length = null;
			addOption(form.blockName, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.blockName, j[i].blockId, j[i].blockName);
			}
			//document.editRoom.blockName.value = myBlockId;
			// now select the value
			if (myBlockId != '') {
				form.blockName.value = myBlockId;
			}
			if(myBlockId == undefined) {
				form.blockName.value = '';
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayRoomReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayroomReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayRoomReportCSV.php?'+qstr;
	window.location = path;
}

</script>

</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Room/listRoomContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
//-->
</SCRIPT>
</html>

<?php
  //$History: listRoom.php $
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 11/03/09   Time: 4:40p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001899, 0001898, 0001891,0001889
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 14  *****************
//User: Gurkeerat    Date: 9/29/09    Time: 1:06p
//Updated in $/LeapCC/Interface
//resolved issue 1609
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 9/03/09    Time: 7:33p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001440, 0001433, 0001432, 0001423, 0001239, 0001406,
//0001405, 0001404
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/17/09    Time: 7:34p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001093, 0001086, 0000672, 0001087
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 17/08/09   Time: 14:17
//Updated in $/LeapCC/Interface
//Added the check : If a room is used in time table then it cannot be
//deleted and cannot be de-allocated from the institute with which it is
//associated in time table
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 14/08/09   Time: 16:43
//Updated in $/LeapCC/Interface
//Done enhancement in "Room" module---added room and institute mapping so
//that one room can be shared across institutes
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/12/09    Time: 7:27p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0000969, 0000965, 0000962, 0000963, 0000980, 0000950
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/13/09    Time: 10:20a
//Updated in $/LeapCC/Interface
//change the heading room report to room report print
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/02/09    Time: 2:03p
//Updated in $/LeapCC/Interface
//increase the width of layer during edit
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/26/09    Time: 6:10p
//Updated in $/LeapCC/Interface
//fixed bugs No.5,6 bugs-report.doc dated 26.05.09
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
//User: Jaineesh     Date: 11/06/08   Time: 5:06p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:49p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 9/30/08    Time: 3:09p
//Updated in $/Leap/Source/Interface
//modified for exam capacity
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 9/26/08    Time: 3:05p
//Updated in $/Leap/Source/Interface
//new field exam capacity added 
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 9/26/08    Time: 11:32a
//Updated in $/Leap/Source/Interface
//remove the delete message
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 9/25/08    Time: 3:29p
//Updated in $/Leap/Source/Interface
//fixed bug
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 8/28/08    Time: 4:35p
//Updated in $/Leap/Source/Interface
//modification in indentation
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:25p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 8/21/08    Time: 1:01p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/11/08    Time: 12:41p
//Updated in $/Leap/Source/Interface
//modified for duplicate record check
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/09/08    Time: 6:59p
//Updated in $/Leap/Source/Interface
//modification in room - bug fixed
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/01/08    Time: 4:52p
//Updated in $/Leap/Source/Interface
//modified for OnSuccess & OnCreate functions
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 7/24/08    Time: 1:09p
//Updated in $/Leap/Source/Interface
//modified in action width size
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/18/08    Time: 3:31p
//Updated in $/Leap/Source/Interface
//modified for select option in ie
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/18/08    Time: 1:23p
//Updated in $/Leap/Source/Interface
//modified in sql query
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/12/08    Time: 11:39a
//Updated in $/Leap/Source/Interface
//modified in block with blockname through blockid
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/03/08    Time: 8:32p
//Updated in $/Leap/Source/Interface
//modified in table fields
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/02/08    Time: 8:24p
//Updated in $/Leap/Source/Interface
//add all the ajax based functions for add, edit, delete or populate the
//values
?>