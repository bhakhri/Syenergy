<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TyreRetreadingReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Tyre Retreading Report</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("swfobject.js");
?> 
<script language="javascript">

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function getData() {

		 var tyreNo = document.getElementById('tyreNo').value;
		 document.getElementById('nameRow').style.display = '';
		 document.getElementById('resultRow').style.display = '';
		 document.getElementById('showPrint').style.display = '';

         var url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxGetVehicleTyreRetreadingList.php';
         var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('busNo','Registration No.','width="10%" align="left"',true),
                        new Array('totalRun','KM Reading','width="5%" align="left"',true),
                        new Array('retreadingDate','Retreading Date','width="10%" align="left"',true),
                        new Array('reason','Reason','width="10%" align="left"',true),
						new Array('detail','Detail','width="10%" align="left"',false)
                       );

        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','busName','ASC','resultDiv','','',true,'listObj',tableColumns,'','','&tyreNo='+tyreNo);
        sendRequest(url, listObj, '');
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (26.11.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getTyreExistance() {
	document.getElementById('nameRow').style.display = 'none';
	document.getElementById('resultRow').style.display = 'none';
	document.getElementById('showPrint').style.display = 'none';
	if(document.getElementById('tyreNo').value == ''){
		messageBox("Enter Tyre No.");
		document.getElementById('tyreNo').focus();
		return false; 
	 }

	 url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxGetTyreValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {tyreNo: document.getElementById('tyreNo').value},
		 asynchronous:false,
		 
		   onCreate: function() {
			  showWaitDialog(true);
		   },
			  
		 onSuccess: function(transport){
		   
				hideWaitDialog(true);
				if(trim(transport.responseText)==0){
					messageBox("<?php echo VEHICLE_TYRE_NOT_EXIST;?>");
					document.getElementById('tyreNo').focus();
					return false;
				  // exit();
			   }
			   else {
					getData();
			   }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function editWindow(id,dv,w,h) {
   
    //displayWindow(dv,w,h);
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateReason(id);   
}

//This function populates values in View Deatil form through ajax 

function populateReason(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TyreRetreading/ajaxRetreadingGetValues.php';
		 
		 new Ajax.Request(url,
           {      
             method:'post',
             parameters: {retreadingId: id},
				 
              onCreate: function() {
			 	showWaitDialog();
			 },
				 
			 onSuccess: function(transport){
				 
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
				  document.getElementById("innerReason").innerHTML = '';
	              document.getElementById("innerReason").innerHTML = j.reason;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/* function to print insurance due report*/
function printReport() {
    if(document.getElementById('tyreNo').value == ''){
		messageBox("Enter Tyre No.");
		document.getElementById('tyreNo').focus();
		return false;
	 }
    
    queryString = '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
  //  form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/tyreRetreadingReportPrint.php?tyreNo='+document.getElementById('tyreNo').value+queryString;
    window.open(path,"TyreRetreadingReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to generate insurance due reportt in CSV format*/
function printCSV() {
    if(document.getElementById('busId').selectedIndex==-1){
            messageBox("Select atleast one bus");
            document.getElementById('busId').focus();
            return false; 
    }

    queryString=generateQueryString('allDetailsForm'); 
    queryString += '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;

    window.location='insuranceDueReportCSV.php?&'+queryString;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TyreRetreading/listTyreRetreadingReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php 
// $History: listTyreRetreadingReport.php $ 
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 2/03/10    Time: 10:14a
//Updated in $/Leap/Source/Interface
//put new report tyre retreading
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 2/02/10    Time: 5:17p
//Created in $/Leap/Source/Interface
//new file tyre retreading report
//
?>