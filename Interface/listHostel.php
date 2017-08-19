<?php
//-------------------------------------------------------
// Purpose: To generate the list of hostel from the database, and have add/edit/delete, search 
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
define('MODULE','HostelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Hostel/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hostel Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), 
new Array('hostelName','Hostel Name','width=15%','',true) , 
new Array('hostelCode','Abbr.','width="10%"','',true),
new Array('hostelType','Type','width="10%"','',true),
new Array('floorTotal','No. of Floors','width="10%"','align="right"',true),
new Array('roomTotal','No. of Rooms','width="10%"','align="right"',true),
new Array('totalCapacity','Total Capacity','width="10%"','align="right"',true),
new Array('wardenName','Warden Name','width="10%"','align="right"',true),
new Array('wardenContactNo','Warden Contact No.','width="10%"','align="right"',true),  
new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Hostel/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddHostel';   
editFormName   = 'EditHostel';
winLayerWidth  = 320; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteHostel';
divResultName  = 'results';
page=1; //default page
sortField = 'hostelName';
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
    
   
    var fieldsArray = new Array(new Array("hostelName","<?php echo ENTER_HOSTEL_NAME ?>"),
                        new Array("hostelCode","<?php echo ENTER_HOSTEL_CODE ?>"),
                        new Array("roomTotal","<?php echo ENTER_TOTAL_ROOM ?>"),
                        new Array("hostelType","<?php echo ENTER_HOSTEL_TYPE ?>"),
                        new Array("floorTotal","<?php echo ENTER_TOTAL_FLOOR ?>"),
                        new Array("totalCapacity","<?php echo ENTER_TOTAL_CAPACITY ?>"),
                        new Array("wardenName","Enter Warden Name"),
                        new Array("wardenContactNo","Enter Warden Contact-No"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else if(fieldsArray[i][0]=="roomTotal"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_NUMBER ?>");
                //document.addHostel.roomTotal.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
         }
		 else if(fieldsArray[i][0]=="floorTotal"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_NUMBER ?>");
                //document.addHostel.roomTotal.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
         }

		 else if(fieldsArray[i][0]=="totalCapacity"){
                if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){ 
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_NUMBER ?>");
                //document.addHostel.roomTotal.value="";
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
         }

                        
      /* else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='roomTotal'&& fieldsArray[i][0]!='floorTotal'&& fieldsArray[i][0]!='totalCapacity' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
    }
    if(act=='Add') {
		if (document.addHostel.floorTotal.value > 100) {
			document.addHostel.floorTotal.focus();
			messageBox("<?php echo FLOOR_NOT_GREATER ?>");
			return false;
		}
        addHostel();
        return false;
    }
    else if(act=='Edit') {
		if (document.editHostel.floorTotal.value > 100) {
			document.editHostel.floorTotal.focus();
			messageBox("<?php echo FLOOR_NOT_GREATER ?>");
			return false;
		}
        editHostel();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addHostel() IS USED TO ADD NEW GROUP TYPE
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHostel() {
         url = '<?php echo HTTP_LIB_PATH;?>/Hostel/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelName: (document.addHostel.hostelName.value), 
             hostelCode: (document.addHostel.hostelCode.value),
             hostelType: (document.addHostel.hostelType.value),
             floorTotal: (document.addHostel.floorTotal.value),
             totalCapacity: (document.addHostel.totalCapacity.value), 
             roomTotal:  (document.addHostel.roomTotal.value),
             wardenName:  (document.addHostel.wardenName.value),
             wardenContactNo:  (document.addHostel.wardenContactNo.value)},
             
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
                             hiddenFloatingDiv('AddHostel');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                       //      location.reload();
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                        if (trim(transport.responseText)=='<?php echo HOSTEL_NAME_EXIST ?>'){
							//document.addHostel.hostelName.value='';
							document.addHostel.hostelName.focus();	
						}
						else {
                            
							//document.addHostel.hostelCode.value='';
							document.addHostel.hostelCode.focus();
						}
                        
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION DELETEHOSTEL() IS USED TO DELETE THE SPECIFIED RECORD 
//FROM  SPECIFIED FILE THROUGH ID
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteHostel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Hostel/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelId: id},
                          
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
//THIS FUNCTION blanValues() IS USED TO BLANK VALUES OF TEXT BOXES 
//
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function blankValues() {
   document.addHostel.hostelName.value = '';
   document.addHostel.hostelCode.value = '';
   document.addHostel.roomTotal.value = '';
   document.addHostel.hostelType.value = '';
   document.addHostel.floorTotal.value = '';
   document.addHostel.totalCapacity.value = '';
   document.addHostel.wardenName.value = '';
   document.addHostel.wardenContactNo.value = '';
   document.addHostel.hostelName.focus();
}

 //-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editHostel() {
         url = '<?php echo HTTP_LIB_PATH;?>/Hostel/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelId: (document.editHostel.hostelId.value), 
             hostelName: (document.editHostel.hostelName.value), 
             hostelCode: (document.editHostel.hostelCode.value),
             hostelType: (document.editHostel.hostelType.value),
             floorTotal: (document.editHostel.floorTotal.value),
             totalCapacity:(document.editHostel.totalCapacity.value),  
             roomTotal: (document.editHostel.roomTotal.value),
             wardenName: (document.editHostel.wardenName.value),
             wardenContactNo:(document.editHostel.wardenContactNo.value)},
             
             onCreate: function() {               
                  showWaitDialog(true);
             },
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditHostel');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                        // location.reload();
                     }
					 else {
                         messageBox(trim(transport.responseText));
						 if (trim(transport.responseText)=='<?php echo HOSTEL_NAME_EXIST ?>'){
							//document.editHostel.hostelName.value='';
							document.editHostel.hostelName.focus();	
						}
						else {
							//document.editHostel.hostelCode.value='';
							document.editHostel.hostelCode.focus();
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
         url = '<?php echo HTTP_LIB_PATH;?>/Hostel/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {hostelId: id},
             
               
              onCreate: function() {     
                  showWaitDialog(true);
              },
               onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditHostel');
                        messageBox("<?php echo HOSTEL_NOT_EXIST;?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                   
                   document.editHostel.hostelName.value = j.hostelName;
                   document.editHostel.hostelCode.value = j.hostelCode;
                   document.editHostel.roomTotal.value = j.roomTotal;
                   document.editHostel.floorTotal.value = j.floorTotal;
                   document.editHostel.hostelType.value = j.hostelType;
                   document.editHostel.totalCapacity.value = j.totalCapacity;
                   document.editHostel.wardenName.value = j.wardenName; 
                   document.editHostel.wardenContactNo.value = j.wardenContactNo; 
                   document.editHostel.hostelId.value = j.hostelId;
                   document.editHostel.hostelName.focus();
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	var path='<?php echo UI_HTTP_PATH;?>/displayHostelReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
    try{
     var a=window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHostelCSV.php?'+qstr;
	window.location = path;
}
</script>

</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Hostel/listHostelContents.php");
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
// $History: listHostel.php $
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Interface
//give print & export to excel facility
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/29/09    Time: 6:41p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000737, 0000736,0000734,0000735, 0000585, 0000584,
//0000583
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/27/09    Time: 11:17a
//Updated in $/LeapCC/Interface
//fixed bug no.0000621
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Interface
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/08/09    Time: 1:07p
//Updated in $/LeapCC/Interface
//fixed bugs Issues BuildCC# cc0001.doc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/05/09    Time: 11:08a
//Created in $/LeapCC/Interface
//file includes functions add, edit, delete & populate made by Jaineesh &
//modifications by Gurkeerat and added in VSS by Jaineesh
//
//*****************  Version 17  *****************
//User: Gurkeerat Sidhu     Date: 18/04/09   Time: 5:43p
//Updated in $/Leap/Source/Interface
//added new fields (floorTotal,hostelType,totalCapacity) 
//
//*****************  Version 16  *****************
//User: Ajinder      Date: 2/27/09    Time: 10:58a
//Updated in $/Leap/Source/Interface
//made code to stop duplicacy
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 1/16/09    Time: 1:32p
//Updated in $/Leap/Source/Interface
//modified left alignment
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/12/09    Time: 2:55p
//Updated in $/Leap/Source/Interface
//make changes for sending sendReq() function
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/10/09    Time: 4:14p
//Updated in $/Leap/Source/Interface
//use required fields & left labels
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 11/06/08   Time: 5:43p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/14/08   Time: 5:00p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 8/28/08    Time: 3:43p
//Updated in $/Leap/Source/Interface
//modification in indentation
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/21/08    Time: 3:56p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/19/08    Time: 3:13p
//Updated in $/Leap/Source/Interface
//change search button
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/11/08    Time: 5:04p
//Updated in $/Leap/Source/Interface
//modified to check duplicate records
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/07/08    Time: 3:52p
//Updated in $/Leap/Source/Interface
//modified in edit or delete messages
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 8/01/08    Time: 3:47p
//Updated in $/Leap/Source/Interface
//modified for OnCreate & OnSuccess functions
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:18p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:15p
//Updated in $/Leap/Source/Interface
//change alert in messagebox
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/30/08    Time: 1:58p
//Updated in $/Leap/Source/Interface
//modification with ajax function
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
