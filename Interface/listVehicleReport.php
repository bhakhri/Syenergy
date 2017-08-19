<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (25.01.2010 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Bus/initList.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Report </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>";
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
?>
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
/*
var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('busNo','Registration No.','width="15%"','align="left"',true),
    new Array('vehicleType','Vehicle Type','width="15%"','align="left"',true),
    new Array('lastInsuranceDate','First Insured on ','width="10%", align="center"','align="center"',true),
    new Array('insuringCompanyName','First Insurance Company','width="20%"','align="left"',true),
    //new Array('isActive','Active','width="4%"','align="left"',true),
    new Array('action','Action','width="2%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddBus';
editFormName   = 'VehicleDiv';
winLayerWidth  = 720; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicle';
divResultName  = 'results';
page=1; //default page
sortField = 'busNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var mode = '';
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

*/
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Vehicle Detail
//
//Author : Jaineesh
// Created on : (28.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function doSubmitAction(id,dv,w,h) {
	showTab('dhtmlgoodies_tabView1',1);
	if(document.getElementById('vehicleType').value == '') {
		alert ('Select Vehicle Type');
		document.getElementById('vehicleType').focus();
		return false;
	}
	if(document.getElementById('vehicleNo').value == '') {
		alert ('Select Registration No.');
		document.getElementById('vehicleNo').focus();
		return false;
	}
	document.getElementById('results').style.display = 'block';
	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('results').style.display = 'block';
			 document.getElementById('results').innerHTML = trim(transport.responseText);
			 //document.getElementById('divTyreDetail').innerHTML = trim(transport.responseText);
		 	 initTabs('dhtmlgoodies_tabView1',Array('Vehicle Info','Insurance Info','Fuel Info','Accident Info','Service Summary Info','Service Detail Info','Tyre History'),0,990,370,Array(false,false,false,false,false,false,false));
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
	getVehicleTyre();
	getInsuranceDetail();

}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Vehicle Tyre
//
//Author : Jaineesh
// Created on : (28.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleTyre() {
	document.getElementById('results').style.display = 'block';
	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleTyreList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('results').style.display = 'block';
			 document.getElementById('divTyreDetail').innerHTML = trim(transport.responseText);
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function clearText() {
	showTab('dhtmlgoodies_tabView1',1);
	document.getElementById('results').style.display = 'none';
}

function clearFuelText() {
	document.getElementById('divFuelDetail').style.display = 'none';
}

function clearAccidentText() {
	document.getElementById('divAccidentDetail').style.display = 'none';
}

function clearServiceText() {
	document.getElementById('divServiceDetail').style.display = 'none';
}

function clearDetailsService(){
	document.getElementById('divAllServiceDetail').innerHTML='';
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Insurance Detail
//
//Author : Jaineesh
// Created on : (15.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getInsuranceDetail() {
	document.getElementById('results').style.display = 'block';
	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleInsuranceList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('results').style.display = 'block';
			 document.getElementById('divInsuranceDetail').innerHTML = trim(transport.responseText);

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Fuel Detail
//
//Author : Jaineesh
// Created on : (27.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getFuelDetail() {
	document.getElementById('divFuelDetail').style.display = '';
	/*if(document.getElementById('fuelStaffId').value == '') {
		alert('Select Staff Name');
		document.getElementById('fuelStaffId').focus();
		return false;
	}*/

	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleFuelList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('divFuelDetail').innerHTML = trim(transport.responseText);

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Vehicle Accident Detail
//
//Author : Jaineesh
// Created on : (27.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getAccidentDetail() {
	document.getElementById('divAccidentDetail').style.display = '';
	if(document.getElementById('accidentStaffId').value == '') {
		alert('Select Staff Name');
		document.getElementById('accidentStaffId').focus();
		return false;
	}

	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleAccidentList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('divAccidentDetail').innerHTML = trim(transport.responseText);

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Vehicle Accident Detail
//
//Author : Jaineesh
// Created on : (27.01.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getServiceDetail() {
	document.getElementById('divServiceDetail').style.display = '';
	if(document.getElementById('busService').value == '') {
		alert('Select Service Type');
		document.getElementById('busService').focus();
		return false;
	}

	pars = generateQueryString('listForm');

	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleServiceList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('divServiceDetail').innerHTML = trim(transport.responseText);

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
function getAllServiceDetail() {
	document.getElementById('divServiceDetail').style.display = '';
	if(document.getElementById('busService1').value == '') {
		alert('Select Service Type');
		document.getElementById('busService1').focus();
		return false;
	}

	pars = generateQueryString('listForm');
	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitVehicleDetailServiceList.php';
	 new Ajax.Request(url,
	   {
		 asynchronous:false,
		 method:'post',
		 parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 document.getElementById('divAllServiceDetail').innerHTML = trim(transport.responseText);

		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
function getVehicleTyres() {
	url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/getVehicleTyres.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {
			 vehicleType:trim(document.vehicleForm.vehicleType.value)
		 },
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 res = eval('('+transport.responseText+')');
			 mainTyres = res[0]['mainTyres'];
			 spareTyres = res[0]['spareTyres'];
			 x = 1;
			 tyreDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
			 while (x <= mainTyres) {
				 tyreDiv += '<tr><td width="20%">&nbsp;Number of Tyre '+x+'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" maxlength = "30" style="width:300px" name="mainTyre_'+x+'" /></td></tr>';
				 x++;
			 }
			 x = 1;
			 while (x <= spareTyres) {
				 tyreDiv += '<tr><td width="20%">&nbsp;Number of Spare Tyre '+x+'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox1" type="text" maxlength = "30"  style="width:300px" name="spareTyre_'+x+'" /></td></tr>';
				 x++;
			 }
			 tyreDiv += '</table>';
			 document.getElementById('tyreDetailDiv').innerHTML = tyreDiv;


		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE SERVICE DIV
//
//Author : Nishu Bindal
// Created on : (31.03.2011)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getServiceDetails(id) {
		url = '<?php echo HTTP_LIB_PATH;?>/VehicleServiceRepair/ajaxGetDetailServiceValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {serviceRepairId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EditFuel');
                        messageBox("<?php echo FUEL_RECORD_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                    }
                   else{

                       j = eval('('+transport.responseText+')');
					   //alert(transport.responseText);
                       document.vehicleServiceRepairDetails.vehicleType.value = j['vehicleServiceDetails'][0]['vehicleTypeId'];
					   document.vehicleServiceRepairDetails.busNo.value = j['vehicleServiceDetails'][0]['busId'];
                       document.vehicleServiceRepairDetails.busService.value = j['vehicleServiceDetails'][0]['serviceType'];
					   if(j['vehicleServiceDetails'][0]['serviceType'] == 1) {
							document.getElementById('getEditServiceNo').style.display = '';
							document.getElementById('divServiceNo').innerHTML = '';
							document.getElementById('divServiceNo').innerHTML = j['vehicleServiceDetails'][0]['serviceNo'];
					   }
					   else {
							document.getElementById('getEditServiceNo').style.display = 'none';
					   }
                       document.getElementById('serviceDate1').innerHTML = j['vehicleServiceDetails'][0]['serviceDate'];
                       document.getElementById('readingEntry').innerHTML = j['vehicleServiceDetails'][0]['kmReading'];
                       document.getElementById('billNo').innerHTML = j['vehicleServiceDetails'][0]['billNo'];
                       document.getElementById('servicedAt').innerHTML = j['vehicleServiceDetails'][0]['servicedAt'];
                       document.vehicleServiceRepairDetails.serviceRepairId.value =j['vehicleServiceDetails'][0]['serviceRepairId'];
					   document.getElementById('serviceDetail1').innerHTML = j['repairServiceDiv'];
					   document.getElementById('vehicleServiceRepairDetail').innerHTML = j['vehicleServiceRepairDetail'];


                       document.vehicleServiceRepairDetails.busNo.focus();
                   }
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getServiceInfo(value) {
	/*x = 1;
	if (value > 0 ) {
		serviceDiv = '<table border="0" width="100%" class="contenttab_internal_rows">';
	while (x <= value) {
		 serviceDiv += '<tr><td width="27%">Free service no. '+x+'</td><td>&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox" type="text" maxlength = "50" name="serviceDate_'+x+'" /></td>';
		 serviceDiv += '<td width="27%">&nbsp;&nbsp;<b>:</b>&nbsp;<input class="inputbox" type="text" maxlength = "50" name="kmRun_'+x+'" /></td></tr>';
		 x++;
	 }
		serviceDiv += '</table>';
	}

	document.getElementById('serviceDetailDiv').innerHTML = serviceDiv;*/
	if (value == '') {
		messageBox("<?php echo ENTER_FREE_SERVICE_NO ?>");
		document.getElementById('freeService').focus();
		return false;
	}
	if (value != '') {
		if(!isInteger(value)) {
			messageBox("<?php echo ENTER_NUMBER ?>");
			document.getElementById('freeService').focus();
			return false;
		}
	}
	url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/getVehicleService.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {
			 serviceValue:trim(value)
		 },
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 //alert(transport.responseText);
			 //res = eval('('+transport.responseText+')');
			 document.getElementById('serviceDetailDiv').innerHTML = trim(transport.responseText);
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
//
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleDetails() {
	//document.getElementById('showTitle').style.display = 'none';
	//document.getElementById('showData').style.display = 'none';
	form = document.listForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.vehicleNo.length = null;
		addOption(form.vehicleNo, '', 'Select');
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
				addOption(form.vehicleNo, '', 'Select');
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

function  download(str){
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/Bus/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

/* function to print bus stop report*/
function printServiceReport() {
    var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleServiceReportPrint.php?'+qstr;
    window.open(path,"VehicleServiceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printAccidentReport() {
    var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleAccidentReportPrint.php?'+qstr;
    window.open(path,"VehicleAccidentReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printFuelReport() {
   var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleFuelReportPrint.php?'+qstr;
    window.open(path,"VehicleFuelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printVehicleReport() {
   var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleDetailReportPrint.php?'+qstr;
    window.open(path,"VehicleDetailReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printInsuranceReport() {
   var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleInsuranceReportPrint.php?'+qstr;
    window.open(path,"VehicleInsuranceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function printTyreReport() {
   var qstr=generateQueryString('listForm');
    path='<?php echo UI_HTTP_PATH;?>/vehicleTyreReportPrint.php?'+qstr;
    window.open(path,"VehicleTyreReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='busReportCSV.php?'+qstr;
}
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Vehicle/listVehicleReportContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
// $History: listVehicleReport.php $
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/29/10    Time: 4:53p
//Created in $/Leap/Source/Interface
//new file for vehicle report
//
//
?>