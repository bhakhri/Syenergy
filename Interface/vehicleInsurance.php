<?php 

//-------------------------------------------------------------------------
// Purpose: This file shows a list of vehicle Insurance that are to be paid
// Author :Kavish Manjkhola
// Created on : 22-Oct-2008
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//-----------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','InsuranceVehicleAutopay');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Insurance (Autopay)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('busNo','Reg No.','width="10%"','',true),
                               new Array('lastInsuranceDate','Last Insurance Date','width="15%"','align="center"',true),
							   new Array('valueInsured','Value Insured(Rs.)','width="15%"','align="center"',true),
							   new Array('insurancePremium','Insurance Premium(Rs.)','width="17%"','align="center"',true),
							   new Array('insuranceDueDate','Insurance Due Date','width="15%"','align="center"',true),
							   new Array('insCalendar','Insurance Paid On','width="15%"','align="center"',true),
							   new Array('action1','Action','width="5%"','align="center"',false)
							   );
listURL='<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxVehicleInsuranceDueList.php';
divResultName = 'resultDiv';
fetchedData='';//global variable, used for passing (fetched and sorted) data to popup window
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'searchForm'; // name of the form which will be used for search
winLayerWidth  = 315;
winLayerHeight = 250;
page=1; //default page
sortField = 'busId';
sortOrderBy = 'ASC';



function getPayInfo(id,dv,w,h) {
	displayFloatingDiv(dv,'',w,h,450,100);
	document.getElementById(dv).style.center='60px';
	
	var paidOn = eval("document.getElementById('insCalendar"+id+"').value").split('-');
	var insurancePaidOn = paidOn[2]+'-'+paidOn[1]+'-'+paidOn[0];
	var insuranceDueDate = eval("document.getElementById('dueDate"+id+"').value");
	document.getElementById('txtPayId').value=id;
	document.getElementById('txtPaid').value=insurancePaidOn;
	document.getElementById('txtDue').value=insuranceDueDate;
	document.getElementById('trPayResults').innerHTML="<br>Insurance Paid On: "+" "+insurancePaidOn+'<br>Next Insurance Due Date:'+" "+insuranceDueDate+"<br><br>"; 
}

function autoPay() {
	var id = document.getElementById('txtPayId').value;
	var insurancePaidOn = document.getElementById('txtPaid').value
	var insuranceDueDate = document.getElementById('txtDue').value

	url = '<?php echo HTTP_LIB_PATH;?>/VehicleInsurance/ajaxVehicleInsuranceAutoPay.php';
	new Ajax.Request(url,
		{
			method:'post',
			parameters: {
				vehicleId : id,
				insurancePaidOn: insurancePaidOn,
				insuranceDueDate: insuranceDueDate
			},
			onCreate: function() {
			showWaitDialog(true);
			},
			onSuccess: function(transport){
				hideWaitDialog(true);
				if("<?php echo SUCCESS;?>"==trim(transport.responseText)) {
					hiddenFloatingDiv('divPayInsurance');
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


function hideResults() {
	document.getElementById("resultRow").style.display='none';
	document.getElementById('nameRow').style.display='none';
	document.getElementById('nameRow2').style.display='none';
}


</script>
</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/VehicleInsurance/listVehicleInsuranceLayout.php");
		require_once(TEMPLATES_PATH . "/footer.php");
	?>

</body>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
    changeColor(currentThemeId);
</script>
</html>