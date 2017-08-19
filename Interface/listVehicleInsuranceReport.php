<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (09.06.2010)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','VehicleInsuranceReport');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Insurance Report</title>
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
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";
function getData() {

		if(document.getElementById('vehicleType').value == '') {
			messageBox("<?php echo SELECT_VEHICLE_TYPE ?>");
			return false;
		}
		else {
			vehicleType = document.getElementById('vehicleType').value;
		}
		if(document.getElementById('vehicleNo').value == '') {
			messageBox("<?php echo SELECT_VEHICLE_No ?>");
			return false;
		}
		else {
			vehicleNo = document.getElementById('vehicleNo').value;
		}
		document.getElementById('nameRow').style.display = '';
		document.getElementById('resultRow').style.display = '';
		document.getElementById('printRow').style.display = '';

         var url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxVehicleInsuranceHistory.php';
         var tableColumns = new Array(
                        new Array('srNo','#','width="1%" align="left"',false), 
                        new Array('insuringCompanyName','Insurance Company','width="10%" align="left"',true),
						new Array('isActive','In service','width="8%" align="left"',true),
                        new Array('policyNo','Policy No.','width="10%" align="left"',true),
                        new Array('lastInsuranceDate','Insurance From','width="12%" align="center"',true),
						new Array('insuranceDueDate','Insurance To','width="12%" align="center"',true),
						new Array('valueInsured','Sum Insured','width="10%" align="right"',true),
						new Array('insurancePremium','Premium','width="10%" align="right"',true),
						new Array('ncb','NCB','width="10%" align="right"',true)
                       );

        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','busName','ASC','resultsDiv','','',true,'listObj',tableColumns,'','','&vehicleNo='+vehicleNo+'&vehicleType='+vehicleType);
        sendRequest(url, listObj, '');
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

function clearText() {
	document.getElementById('nameRow').style.display = 'none';
	document.getElementById('resultRow').style.display = 'none';
	document.getElementById('printRow').style.display = 'none';
}
/* function to print insurance due report*/
function printReport() {
    
    queryString = '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
  //  form = document.allDetailsForm;
    path='<?php echo UI_HTTP_PATH;?>/vehicleInsurancePrintReport.php?vehicleNo='+document.getElementById('vehicleNo').value+'&vehicleType='+document.getElementById('vehicleType').value+queryString;
    window.open(path,"VehicleInsuranceReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to generate insurance due reportt in CSV format*/
function printCSV() {
    
	//queryString=generateQueryString('allDetailsForm'); 
    queryString = '&sortOrderBy='+listObj.sortOrderBy+'&sortField='+listObj.sortField;
	path='<?php echo UI_HTTP_PATH;?>/vehicleInsuranceReportCSV.php?vehicleNo='+document.getElementById('vehicleNo').value+'&vehicleType='+document.getElementById('vehicleType').value+queryString;
    window.location=path;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/VehicleInsurance/listVehicleInsuranceReportContents.php");
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