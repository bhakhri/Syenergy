<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseAuthorization');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Guest House Authorization</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('bookingNo','Booking No.','width="5%"','',true), 
                                new Array('guestName','Guest Name','width="10%"','',true),
                                new Array('hostelName','Guest House','width="8%"','',true),
                                new Array('roomName','Room','width="5%"','',true),
                                new Array('arrivalDate','Arr. Date','width="7%"','align="center"',true),
                                new Array('aTime','Arr. Time','width="7%"','align="center"',true),
                                new Array('departureDate','Dept. Date','width="8%"','align="center"',true),
                                new Array('dTime','Dept. Time','width="7%"','align="left"',true),
                                new Array('isAllocated','Allocated','width="7%"','',true),
                                new Array('actionString','Action','width="2%"','align="center"',false)
                               ); 

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxOccupantsList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'guestHouse';   
editFormName   = 'guestHouse';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGuestHouse';
divResultName  = 'results';
page=1; //default page
sortField = 'bookingNo';
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id) {
    populateValues(id,'GuestHouseDiv',315,250);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm() {
   if(document.guestHouse.allocate[0].checked==false && document.guestHouse.allocate[1].checked==false){
       messageBox("<?php echo SELECT_ALLOCATE_REJECT ?>");
       document.guestHouse.allocate[0].focus();
       return false;
   }
   if(document.guestHouse.allocate[1].checked==true){
       if(trim(document.guestHouse.reason.value)==''){
           messageBox("<?php echo ENTER_GUEST_HOUSE_REJECTION_REASON;?>");
           document.guestHouse.reason.focus();
           return false;
       }
       
       if(trim(document.guestHouse.reason.value).length<5){
           messageBox("<?php echo GUEST_HOUSE_REJECTION_REASON_LENGTH;?>");
           document.guestHouse.reason.focus();
           return false;
       }
   }
   else{
       if(document.getElementById('hostelId').value==''){
           messageBox("<?php echo SELECT_GUEST_HOUSE;?>");
           document.getElementById('hostelId').focus();
           return false;
       }
       if(document.getElementById('room').value==''){
           messageBox("<?php echo SELECT_ROOM;?>");
           document.getElementById('room').focus();
           return false;
       }
   }
   doGuestHouseAuthorization();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO DELETE A NEW CITY
// id=cityId
// Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteGuestHouseAuthorization(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/deleteGuestHouseAuthorization.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId: id
             },
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.guestHouse.reset();
   document.guestHouse.allocate[0].checked=false;
   document.guestHouse.allocate[1].checked=false;
   document.getElementById('hostelId').selectedIndex=0;
   document.getElementById('hostelId').disabled=true;
   document.getElementById('room').selectedIndex=0;
   document.getElementById('room').disabled=true;
   document.getElementById('room').options.length=1;
   document.getElementById('reason').value='';
   document.getElementById('reason').disabled=true;
   document.getElementById('vacantRoomDiv').innerHTML='';
   document.getElementById('vacantRoomDiv').overflow='none';
   document.getElementById('arrival').value='';
   document.getElementById('departure').value='';
   document.getElementById('alternateArrangementTrId').style.display='none';
   document.getElementById('alternateArrangement').innerHTML='';
}

function toggleData(value){
    document.getElementById('vacantRoomDiv').innerHTML='';
    document.getElementById('vacantRoomDiv').overflow='none';
    if(value==1){
        document.getElementById('hostelId').disabled=false;
        document.getElementById('hostelId').selectedIndex=0;
        document.getElementById('room').disabled=false;
        document.getElementById('room').selectedIndex=0;
        document.getElementById('reason').value='';
        document.getElementById('reason').disabled=true;
        document.getElementById('alternateArrangementTrId').style.display='none';
        getPossibleVacantRoomList();
    }
    else{
        document.getElementById('hostelId').selectedIndex=0;
        document.getElementById('hostelId').disabled=true;
        document.getElementById('room').selectedIndex=0;
        document.getElementById('room').disabled=true;
        document.getElementById('reason').value='';
        document.getElementById('reason').disabled=false;
        document.getElementById('vacantRoomDiv').innerHTML='';
        document.getElementById('alternateArrangementTrId').style.display='';
    }
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function doGuestHouseAuthorization() {
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/doGuestHouseAuthorization.php';
         var allocated=document.guestHouse.allocate[0].checked==true ? 1: 2;
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId    : (document.guestHouse.bookingId.value), 
                 allocated       : allocated,
                 hostelId        : document.getElementById('hostelId').value,
                 roomId          : document.getElementById('room').value,
                 reason          : trim(document.guestHouse.reason.value),
                 date1           : document.getElementById('arrival').value,
                 date2           : document.getElementById('departure').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('GuestHouseDiv');
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         blankValues();
         var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxGetGuestHouseAuthorizationValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 allocationId : id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)=="<?php echo BOOKING_NOT_EXIST; ?>") {
                        messageBox("<?php echo BOOKING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                    }
                   
                   var j = eval('('+trim(transport.responseText)+')');
                   
                   document.getElementById('bookingNoDiv').innerHTML = j.bookingNo;
                   document.getElementById('guestNameDiv').innerHTML     = j.guestName;
                   document.getElementById('arrivalDateDiv').innerHTML   = j.arrivalDate;
                   document.getElementById('departureDateDiv').innerHTML = j.departureDate;
                   document.getElementById('budgetHeadDiv').innerHTML    = j.headName;
                   document.getElementById('arrival').value=j.arrival;
                   document.getElementById('departure').value=j.departure;
                   if(j.isAllocated==1){
                    toggleData(1);
                    document.guestHouse.hostelId.value=j.hostelId
                    getRooms(j.hostelId);
                    document.guestHouse.room.value= j.hostelRoomId;
                    document.guestHouse.allocate[0].checked=true;
                   }
                   else if(j.isAllocated==2){
                      toggleData(2);
                      document.guestHouse.allocate[1].checked=true;
                      document.getElementById('reason').value=j.reason;
                      document.getElementById('alternateArrangementTrId').style.display='';
                      document.getElementById('alternateArrangement').innerHTML=j.alternativeArrangement;
                   }
                   else{
                    //getPossibleVacantRoomList();
                   }
                   document.getElementById('bookingId').value=j.allocationId;
                   
                   displayWindow(dv,w,h);
                   
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function getRooms(val){
   
   var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxGetRooms.php';
   document.guestHouse.room.options.length=1;
   if(val==''){
       return false;
   }
   
new Ajax.Request(url,
           {
             method:'post',
             asynchronous: false,
             parameters: {
                 hostelId : val 
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                   
                     hideWaitDialog(true);
                     var j = eval('('+trim(transport.responseText)+')');
                     var len=j.length;
                     if(len>0){
                     for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].roomName,j[c].hostelRoomId); 
                        document.guestHouse.room.options.add(objOption);   
                     }
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function selectHostelRoom(hostelId,roomId){
   getRooms(hostelId);
   document.getElementById('hostelId').value=hostelId;
   document.getElementById('room').value=roomId;
}


//this function is used to display possible vacant room list
function getPossibleVacantRoomList(){
   
   var date1=document.getElementById('arrival').value;
   var date2=document.getElementById('departure').value 
   
   var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxPossibleVacantRoomList.php';
   
   document.getElementById('vacantRoomDiv').overflow='auto';
   
   var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('hostelName','Hostel','width="40%" align="left"',true),
                        new Array('roomName','Room','width="40%" align="left"',true),
                        new Array('actionString','Select','width="10%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,1000,linksPerPage,1,'','hostelName','ASC','vacantRoomDiv','','',true,'listObj',tableColumns,'','','&fromDate='+date1+'&toDate='+date2);
 sendRequest(url, listObj, '',false)
}

function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/listGuestHouseAuthorizationPrint.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"GuestHouseReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/listGuestHouseAuthorizationCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/GuestHouse/guestHouseAuthorizationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: roomAllocation.php $ 
?>