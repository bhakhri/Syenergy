<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SeatAllocationReport');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Seat Allocation Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
 // Section    teacher    day    period    room                               
var tableHeadArray = new Array(
                         new Array('srNo',           '#','width="2%"','',false),
                         new Array('branchName',     'Course Name','width="20%"','align="left"',true),
                         new Array('quotaName',      'Seat Type','width="25%"','align="left"',true),
                         new Array('totalSeats',     'Total Seats','width="10%"','align="right"',true), 
                         new Array('allotedSeats',   'Allotted Seats','width="12%"','align="right"',true),
                         new Array('reportedSeats',  'Reported Seats','width="14%"','align="right"',true),
                         new Array('vacantSeats',    'Vacant Seats','width="12%"','align="right"',true)
                     );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Quota/ajaxDisplaySeatAllocationReport.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'branchName';
sortOrderBy    = 'ASC';
queryString = '';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function validateAddForm(frm) {
 
    page=1;
    showReport(page);    
    return false;
}

function showReport(page) {      

     var url='<?php echo HTTP_LIB_PATH;?>/Quota/ajaxSeatAllocationReport.php';
     pars = generateQueryString('allDetailsForm');
     
     allocationDate = document.getElementById('allocationDate').value;
     classId = getCommaSeprated("classId");   
     quotaId = getCommaSeprated("quotaId");   
     roundId = getCommaSeprated("roundId"); 
     
     var alotSeat = 0;
     var rptSeat = 0;
     var vcnSeat = 0;
     
     if(document.allDetailsForm.alotSeat.checked) {
       alotSeat=1;
     }
      
     if(document.allDetailsForm.rptSeat.checked) { 
       rptSeat=1;
     }
     
      if(document.allDetailsForm.vcnSeat.checked) { 
        vcnSeat=1;
     } 
          
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters:{allocationDate: allocationDate,
                     classId: classId,
                     quotaId : quotaId,
                     roundId: roundId,  
                     alotSeat: alotSeat,
                     rptSeat: rptSeat,
                     vcnSeat:  vcnSeat,
                     sortOrderBy: sortOrderBy,
                     sortField : sortField,
                     page: page
                    },
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = ret[0];
                var j1 = ret[1];
                
                if(j1=='') {
                  totalRecords = 0;
                }
                else {
                  totalRecords = j1; 
                }
                document.getElementById("nameRow").style.display='';
                document.getElementById("nameRow2").style.display='';
                document.getElementById("resultRow").style.display='';
                document.getElementById("pageRow").style.display='';    
                document.getElementById('resultsDiv').innerHTML=j0;
                //document.getElementById('resultsDiv').innerHTML=trim(transport.responseText);
                
                pagingData='';
                document.getElementById("pagingDiv").innerHTML = pagingData;
                document.getElementById("pagingDiv1").innerHTML = pagingData;
                
                totalPages = totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>");
                completePages = parseInt(totalRecords / parseInt("<?php echo RECORDS_PER_PAGE; ?>"));
                if (totalPages > completePages) {
                    completePages++;
                }
                if (totalRecords > 0) {
                    pagingData = pagination2(page, totalRecords, parseInt("<?php echo RECORDS_PER_PAGE; ?>"), parseInt("<?php echo LINKS_PER_PAGE; ?>"));
                    document.getElementById("pagingDiv").innerHTML = pagingData;
                    document.getElementById("pagingDiv1").innerHTML = "<b>Total Records&nbsp;:&nbsp;</b>"+totalRecords; 
                }
             }
          },
          onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
        });

}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.getElementById('resultsDiv').innerHTML='';
    document.getElementById("pagingDiv").innerHTML = '';
    document.getElementById("pagingDiv1").innerHTML = '';
    document.getElementById("pageRow").style.display='none';
}


function printReport() {
    var alotSeat = 0;
    var rptSeat = 0;
    var vcnSeat = 0;
     
    if(document.allDetailsForm.alotSeat.checked) {
       alotSeat=1;
    }
      
    if(document.allDetailsForm.rptSeat.checked) { 
       rptSeat=1;
    }
     
    if(document.allDetailsForm.vcnSeat.checked) { 
        vcnSeat=1;
    } 
    var str = "&alotSeat="+alotSeat+"&rptSeat="+rptSeat+"&vcnSeat="+vcnSeat;    
    path='<?php echo UI_HTTP_PATH;?>/seatAllocationReportPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=900, height=500, top=100,left=50");
}

function printReportCSV() {
    
    var alotSeat = 0;
    var rptSeat = 0;
    var vcnSeat = 0;
     
    if(document.allDetailsForm.alotSeat.checked) {
       alotSeat=1;
    }
      
    if(document.allDetailsForm.rptSeat.checked) { 
       rptSeat=1;
    }
     
    if(document.allDetailsForm.vcnSeat.checked) { 
        vcnSeat=1;
    } 
    var str = "&alotSeat="+alotSeat+"&rptSeat="+rptSeat+"&vcnSeat="+vcnSeat;
    path='<?php echo UI_HTTP_PATH;?>/seatAllocationReportCSV.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+str;
    window.location=path;
}

 
</script>
</head>
<body>
<?php   
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Quota/listSeatAllocationContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
// $History: smsDetailReport.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/02/09   Time: 3:40p
//Updated in $/LeapCC/Interface
//meta tag format updated (utf-8)
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/24/09   Time: 4:47p
//Updated in $/LeapCC/Interface
//sorting format updated (message detials)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 8/21/09    Time: 3:58p
//Updated in $/LeapCC/Interface
//role permission & removePHPJS  function updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/20/09    Time: 4:14p
//Updated in $/LeapCC/Interface
//new enhancement added Action button perform Berif Description added 
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/12/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//file name changes (lowercase)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/19/09    Time: 2:36p
//Updated in $/LeapCC/Interface
//code update search for & condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 3/31/09    Time: 5:46p
//Updated in $/LeapCC/Interface
//formatting settings
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
//*****************  Version 8  *****************
//User: Parveen      Date: 11/28/08   Time: 5:36p
//Updated in $/Leap/Source/Interface
//list and report formatting
//
//*****************  Version 5  *****************
//User: Parveen      Date: 11/28/08   Time: 12:21p
//Updated in $/Leap/Source/Interface
//list formatting
//
//*****************  Version 4  *****************
//User: Parveen      Date: 11/28/08   Time: 10:45a
//Updated in $/Leap/Source/Interface
//changed lists view format
//
//*****************  Version 3  *****************
//User: Parveen      Date: 11/27/08   Time: 5:22p
//Updated in $/Leap/Source/Interface
//add fields messages
//
//*****************  Version 2  *****************
//User: Parveen      Date: 11/26/08   Time: 5:06p
//Updated in $/Leap/Source/Interface
//sms details report added
//


?>