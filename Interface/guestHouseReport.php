<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GuestHouseReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Guest House Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('bookingNo','Booking No.','width="5%"','',true), 
                                new Array('guestName','Guest Name','width="10%"','',true),
                                new Array('hostelName','Guest House','width="10%"','',true),
                                new Array('roomName','Room','width="5%"','',true),
                                new Array('arrivalDate','Arr. Date','width="7%"','align="center"',true),
                                new Array('arrivalTime','Time','width="5%"','align="center"',false),
                                new Array('departureDate','Dept. Date','width="8%"','align="center"',true),
                                new Array('departureTime','Time','width="5%"','align="center"',false),
                                new Array('isAllocated','Status','width="6%"','',true),
                                new Array('userName','Req. By','width="7%"','',true),
                                new Array('headName','Head','width="5%"','',true)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxGuestHouseReport.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'guestHouse';   
editFormName   = 'guestHouse';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGuestHouse';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'bookingNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function fetchReport() {
     var fromDate=document.getElementById('fromDate');
     var toDate=document.getElementById('toDate');
     
     if(!dateDifference(fromDate.value,toDate.value,'-')){
         messageBox("To date can not be smaller than from date");
         fromDate.focus();
         return false;
     }
     
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
     vanishData(2);
}

function getRooms(val){
   vanishData(1);
   var url = '<?php echo HTTP_LIB_PATH;?>/GuestHouse/ajaxGetRooms.php';
   document.searchForm.roomId.options.length=1;
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
                        document.searchForm.roomId.options.add(objOption);   
                     }
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function vanishData(mode){
  if(mode==1){  
   document.getElementById('results').style.display='none';
   document.getElementById('printTrId').style.display='none';
  }
  else{
   document.getElementById('results').style.display='';
   document.getElementById('printTrId').style.display='';   
  }
}

function printReport() {
    var path='<?php echo UI_HTTP_PATH;?>/guestHouseReportPrint.php?'+generateQueryString(searchFormName)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"GuestHouseReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    var qstr=generateQueryString(searchFormName);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    var path='<?php echo UI_HTTP_PATH;?>/guestHouseReportCSV.php?'+qstr;
    window.location = path;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/GuestHouse/guestHouseReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>