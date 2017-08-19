<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF Training ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRoomTypeDetail');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Discipline/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Hostel Room Type Detail Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
sortField		= 'hostelName';
sortOrderBy		= 'ASC';
winLayerWidth  = 340; //  add/edit form width
winLayerHeight = 250; // add/edit form height

// ajax search results ---end ///

function getHostelRoomTypeDetailData(){
  url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomTypeDetail/ajaxInitHostelRoomTypeDetailList.php';
  //var value=document.getElementById('searchbox').value;
  var value=trim(document.searchForm.searchbox.value);
  
 var tableColumns = new Array(
                        new Array('srNo','#','width="4%" align="left"',false), 
                        new Array('hostelName','Hostel Name','width="12%" align="left"',true),
						new Array('roomType','Hostel Room Type','width="10%" align="left"',true),
						new Array('Capacity','Capacity','width="8%" align="right"',true),
						new Array('noOfBeds','No. of Beds','width="7%" align="right"',true),
						new Array('attachedBath','Attached Bathroom','width="10%" align="left"',true),
						new Array('airConditioned','Air Conditioned','width="13%" align="left"',true),
						new Array('internetFacility','Internet Facility','width="10%" align="left"',true),
						new Array('noOfFans','No. of Fans','width="7%" align="right"',true),
						new Array('noOfLights','No. of Lights','width="7%" align="right"',true),
                        			new Array('action','Action','width="6%" align="right"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','hostelName','ASC','HostelRoomTypeDetailResultDiv','HostelRoomTypeDetailDiv','',true,'listObj',tableColumns,'editWindow','deleteHostelRoomTypeDetail','&searchbox='+trim(value));
 sendRequest(url, listObj, '')

}
// ajax search results ---end ///

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Hostel Room Type Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);
}
//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
    
   var fieldsArray = new Array(	new Array("hostelName","<?php echo SELECT_HOSTEL; ?>"),
								//new Array("roomType","<?php echo CHOOSE_ROOM_TYPE; ?>"),
								new Array("capacity","<?php echo ENTER_CAPACITY; ?>"),
								new Array("noBeds","<?php echo ENTER_BEDS; ?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

         
       else if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='roomType' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo CHOOSE_ROOM_TYPE ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
	   else if(fieldsArray[i][0]=="capacity"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_NUMBER ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            }

		else if(fieldsArray[i][0]=="noBeds"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
                messageBox("<?php echo ENTER_NUMBER ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            }

		else if(!isInteger(document.getElementById('noOfFans').value)) {
              messageBox("<?php echo ENTER_NUMBER ?>");
			  document.getElementById('noOfFans').focus();
			 return false
            }

		else if(!isInteger(document.getElementById('noOfLights').value)) {
              messageBox("<?php echo ENTER_NUMBER ?>");
			  document.getElementById('noOfLights').focus();
			 return false
            }
		else if (document.getElementById('roomType').value == "") {
			messageBox("<?php echo CHOOSE_ROOM_TYPE ?>");
			document.getElementById('roomType').focus();
			return false;
			break;
		   }
         }
    if(document.getElementById('roomTypeInfoId').value=='') {
        //alert('add slot');
		addHostelRoomTypeDetail();
        return false;
    }
    else{
		//alert('edit slot');
        editHostelRoomTypeDetail();
        return false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW Hostel room type
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addHostelRoomTypeDetail() {
		
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomTypeDetail/ajaxInitHostelRoomTypeDetailAdd.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
				hostelName:			trim(document.HostelRoomTypeDetail.hostelName.value),
				roomType:			trim(document.HostelRoomTypeDetail.roomType.value),
				Capacity:			trim(document.HostelRoomTypeDetail.capacity.value),
				noOfBeds:			trim(document.HostelRoomTypeDetail.noBeds.value),
				attachBathroom:		trim(document.HostelRoomTypeDetail.attachBathroom.value),
				airConditioned:		trim(document.HostelRoomTypeDetail.airConditioned.value),
				internetFacility:	trim(document.HostelRoomTypeDetail.internetFacility.value),
				noOfFans:			trim(document.HostelRoomTypeDetail.noOfFans.value),
				noOfLights:			trim(document.HostelRoomTypeDetail.noOfLights.value),
				internetFacility:	trim(document.HostelRoomTypeDetail.internetFacility.value)

             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('HostelRoomTypeDetailDiv');
                             getHostelRoomTypeDetailData();
                             return false;
                         }
                     }
					else {
						messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_EXIST; ?>'){
							document.HostelRoomTypeDetail.roomType.focus();
						}
						else if (trim(transport.responseText)=='<?php echo CAPACITY_NOT_GREATER; ?>'){
							document.HostelRoomTypeDetail.capacity.focus();
						}
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteHostelRoomTypeDetail(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomTypeDetail/ajaxInitHostelRoomTypeDetailDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomTypeInfoId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         getHostelRoomTypeDetailData(); 
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
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
	document.getElementById('divHeaderId').innerHTML='&nbsp; Add Hostel Room Type Detail';
	document.HostelRoomTypeDetail.hostelName.value = '';
	document.HostelRoomTypeDetail.roomType.value = '';
	document.HostelRoomTypeDetail.capacity.value = '';
	document.HostelRoomTypeDetail.noBeds.value = '';
	document.HostelRoomTypeDetail.attachBathroom.value = 1;
	document.HostelRoomTypeDetail.airConditioned.value = 1;
	document.HostelRoomTypeDetail.internetFacility.value = 1;
	document.HostelRoomTypeDetail.noOfFans.value = "";
	document.HostelRoomTypeDetail.noOfLights.value = "";
	document.HostelRoomTypeDetail.roomTypeInfoId.value = '';
	document.HostelRoomTypeDetail.hostelName.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editHostelRoomTypeDetail() {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomTypeDetail/ajaxInitHostelRoomTypeDetailEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
					roomTypeInfoId	:	(document.HostelRoomTypeDetail.roomTypeInfoId.value),
					hostelName:			trim(document.HostelRoomTypeDetail.hostelName.value),
					roomType:			trim(document.HostelRoomTypeDetail.roomType.value),
					Capacity:			trim(document.HostelRoomTypeDetail.capacity.value),
					noOfBeds:			trim(document.HostelRoomTypeDetail.noBeds.value),
					attachBathroom:		trim(document.HostelRoomTypeDetail.attachBathroom.value),
					airConditioned:		trim(document.HostelRoomTypeDetail.airConditioned.value),
					internetFacility:	trim(document.HostelRoomTypeDetail.internetFacility.value),
					noOfFans:			trim(document.HostelRoomTypeDetail.noOfFans.value),
					noOfLights:			trim(document.HostelRoomTypeDetail.noOfLights.value)
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     	 messageBox(transport.responseText);
                         hiddenFloatingDiv('HostelRoomTypeDetailDiv');
                         getHostelRoomTypeDetailData();
						 //emptySlotId();
                         return false;
                     }
                   else {
						messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo HOSTELROOM_TYPE_EXIST; ?>'){
							document.HostelRoomTypeDetail.roomType.focus();
						}
						else if (trim(transport.responseText)=='<?php echo CAPACITY_NOT_GREATER; ?>'){
							document.HostelRoomTypeDetail.capacity.focus();
						}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//Author : Jaineesh
// Created on : (21.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/HostelRoomTypeDetail/ajaxHostelRoomTypeDetailGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {roomTypeInfoId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('HostelRoomTypeDiv');
                        messageBox("<?php echo HOSTEL_ROOM_TYPE_DETAIL_NOT_EXIST; ?>");
                        getHostelRoomTypeDetailData();           
                   }

                   j = eval('('+trim(transport.responseText)+')');
				   
                   document.HostelRoomTypeDetail.hostelName.value			= j.hostelId;
				   document.HostelRoomTypeDetail.roomType.value				= j.hostelRoomTypeId;
				   document.HostelRoomTypeDetail.capacity.value				= j.Capacity;
				   document.HostelRoomTypeDetail.noBeds.value				= j.noOfBeds;
				   document.HostelRoomTypeDetail.attachBathroom.value		= j.attachedBath;
				   document.HostelRoomTypeDetail.airConditioned.value		= j.airConditioned;
				   document.HostelRoomTypeDetail.internetFacility.value		= j.internetFacility;
				   document.HostelRoomTypeDetail.noOfFans.value				= j.noOfFans;
				   document.HostelRoomTypeDetail.noOfLights.value			= j.noOfLights;
				   document.HostelRoomTypeDetail.noOfFans.value				= j.noOfFans;
				   document.HostelRoomTypeDetail.roomTypeInfoId.value		= j.roomTypeInfoId;
                   document.HostelRoomTypeDetail.hostelName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomTypeDetailReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayHostelRoomTypeDetailReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayHostelRoomTypeDetailCSV.php?'+qstr;
	window.location = path;
}


window.onload=function(){
        //loads the data
        getHostelRoomTypeDetailData();    
}

</script>

</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/HostelRoomTypeDetail/listHostelRoomTypeDetailContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
	
</body>
</html>
<?php 
// $History: listHostelRoomTypeDetail.php $ 
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/26/09    Time: 10:22a
//Updated in $/LeapCC/Interface
//fixed bug nos.0001235, 0001233, 0001230, 0001234 and put time table in
//reports
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/24/09    Time: 10:39a
//Updated in $/LeapCC/Interface
//fixed bugs
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Interface
//give print & export to excel facility
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/20/09    Time: 5:46p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000622,0000623,0000624,0000611
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 6/08/09    Time: 6:58p
//Updated in $/LeapCC/Interface
//Fixed bug Nos.1303,1304,1305,1306,1307,1308,1310,1311,1312,1313,1314,13
//15,1316,1317 of Issues [05-June-09].doc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 5/12/09    Time: 5:18p
//Updated in $/LeapCC/Interface
//fixed bugs Issues Build cc0001.doc
//(Nos.991,992,993,994,995,996,997,998,999,1000)
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:45p
//Updated in $/LeapCC/Interface
//put new message for hostel room type detail and message in add or edit
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/23/09    Time: 12:12p
//Updated in $/LeapCC/Interface
//modified in heading of hostel room type detail 
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/23/09    Time: 11:53a
//Created in $/LeapCC/Interface
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 4/22/09    Time: 11:47a
//Created in $/LeapCC/Interface
//
?>
