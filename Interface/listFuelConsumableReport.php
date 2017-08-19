<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (24.6.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','FuelConsumableReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//$flashPath = IMG_HTTP_PATH."/ampie.swf";
//require_once(BL_PATH . "/City/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fuel Consumable Report</title>
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function getData() {

         if(!dateDifference(document.getElementById('fromDate').value,serverDate,'-')){
             messageBox("From date can not be greater than current date");
             document.getElementById('fromDate').focus();
             return false;
         }
         if(!dateDifference(document.getElementById('toDate').value,serverDate,'-')){
             messageBox("To date can not be greater than current date");
             document.getElementById('toDate').focus();
             return false;
         }
         if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-')){
             messageBox("<?php echo DATE_VALIDATION; ?>");
             document.getElementById('toDate').focus();
             return false;
         }

		 if(document.getElementById('vehicleNo').selectedIndex==-1){
            messageBox("Select atleast one vehicle");
            document.getElementById('vehicleNo').focus();
            return false;
         }


         var busStr='';
         var len=document.getElementById('vehicleNo').options.length;
         var slen=0;
         for(var i=0;i<len;i++){
             if(document.getElementById('vehicleNo').options[i].selected==true){
              slen++;
               if(busStr!=''){
                 busStr +=',';
               }
               busStr +=document.getElementById('vehicleNo').options[i].value;
             }
         }
         document.getElementById('nameRow').style.display = '';
		 document.getElementById('resultRow').style.display = '';
		 document.getElementById('printRow').style.display = '';

         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxGetFuelConsumable.php';
         var tableColumns = new Array (
											new Array('srNo','#','width="1%" align="left"',false),
											new Array('busNo','Vehicle No.','width="10%" align="left"',false),
											new Array('totalKm','KM Run','width="8%" align="right"',false),
											new Array('fuelConsumed','Consumption (Ltrs.)','width="10%" align="right"',false),
											new Array('amountSpent','Amount Spent (Rs.)','width="10%" align="right"',false),
											new Array('fuelAvg','Average (Km/Ltr)','width="12%" align="right"',false)
									   );

        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','busNo','ASC','resultsDiv','','',true,'listObj',tableColumns,'','','&busId='+busStr+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value);
        sendRequest(url, listObj, '');
}


function getVehicleDetails() {
	form = document.allDetailsForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.vehicleNo.length = null;
		//addOption(form.vehicleNo, '', 'Select');
		document.getElementById('showTitle').style.display='none';
		document.getElementById('showData').style.display='none';
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
				form.vehicleNo.length = null;
				//addOption(form.vehicleNo, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.vehicleNo.length = null;
			for(i=0;i<len;i++) {
				addOption(form.vehicleNo, j[i].busId, j[i].busNo);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function clearList(){
	document.getElementById('nameRow').style.display = 'none';
	document.getElementById('resultRow').style.display = 'none';
	document.getElementById('printRow').style.display = 'none';
}


function printReport() {

	var busStr='';
	 var len=document.getElementById('vehicleNo').options.length;
	 var slen=0;
	 for(var i=0;i<len;i++){
		 if(document.getElementById('vehicleNo').options[i].selected==true){
		  slen++;
		   if(busStr!=''){
			 busStr +=',';
		   }
		   busStr +=document.getElementById('vehicleNo').options[i].value;
		 }
	 }

    queryString = '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
  //  form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/vehicleConsumableReport.php?busId='+busStr+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value;
    window.open(path,"VehicleInsuranceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to generate insurance due reportt in CSV format*/
function printCSV() {

	var busStr='';
	 var len=document.getElementById('vehicleNo').options.length;
	 var slen=0;
	 for(var i=0;i<len;i++){
		 if(document.getElementById('vehicleNo').options[i].selected==true){
		  slen++;
		   if(busStr!=''){
			 busStr +=',';
		   }
		   busStr +=document.getElementById('vehicleNo').options[i].value;
		 }
	 }

	path='<?php echo UI_HTTP_PATH;?>/vehicleConsumableReportCSV.php?busId='+busStr+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value;
    window.location=path;
}


window.onload=function(){
    //used to show graph on page loading
    //makeSelection("busId","All","allDetailsForm");
    //getData();
}
function showData(busId){

	var path='<?php echo UI_HTTP_PATH;?>/scFuelReportPrint.php?busId='+busId+'&fromDate='+document.getElementById('fromDate').value+'&toDate='+document.getElementById('toDate').value;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");

}
</script>

</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Fuel/listFuelConsumableReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History:  $
//
?>