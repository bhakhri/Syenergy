<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Vehicle');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Bus/initList.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Master </title>
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

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false), 
    new Array('busNo','Registration No.','width="15%"','align="left"',true), 
    new Array('vehicleType','Vehicle Type','width="10%"','align="left"',true), 
    new Array('lastInsuranceDate','Last Insured on ','width="10%", align="center"','align="center"',true), 
    new Array('insuranceDueDate','Insurance Due Date ','width="15%", align="center"','align="center"',true), 
    new Array('passengerTaxValidTill','Passenger Tax Valid Till ','width="15%", align="center"','align="center"',true), 
    new Array('roadTaxValidTill','Road Tax Valid Till ','width="15%", align="center"','align="center"',true),
    new Array('pollutionCheckValidTill','Pollution Check Valid Till ','width="15%", align="center"','align="center"',true),
    new Array('passingValidTill','Passing Valid Till','width="15%"','align="left"',true), 
    //new Array('isActive','Active','width="4%"','align="left"',true), 
    new Array('action','Action','width="2%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Vehicle/ajaxInitList.php';
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

function setMode(str) {
	document.getElementById('mode').value = str;
	if (str == 'add') {
		document.getElementById('busId').value = '';
		
		document.getElementById('warrantyTill').style.display = '';
		document.getElementById('batteryWarrantyTill').style.display = 'none';
		document.getElementById('modelNo').style.display = '';
		document.getElementById('manufacturingCompany').style.display = '';
		document.getElementById('detailTyres').style.display = '';
		document.getElementById('tyresInformation').style.display = 'none';
		document.getElementById('tyreInformationDiv').innerHTML = '';
		document.getElementById('vehicleRegnNoValidTill').style.display = '';
		document.getElementById('divRegnNoValidTill').style.display = 'none';
		document.getElementById('vehiclePassengerTaxValidTill').style.display = '';
		document.getElementById('divPassengerTaxValidTill').style.display = 'none';
		document.getElementById('vehiclePassingValidTill').style.display = '';
		document.getElementById('divPassingValidTill').style.display = 'none';
		document.getElementById('vehicleRoadTaxValidTill').style.display = '';
		document.getElementById('divRoadTaxValidTill').style.display = 'none';
		document.getElementById('vehiclePollutionTaxValidTill').style.display = '';
		document.getElementById('divPollutionTaxValidTill').style.display = 'none';
		document.getElementById('addInsuranceDate').style.display = '';
		document.getElementById('editInsuranceDate').style.display = 'none';

		form.insuringCompany.disabled		=		false;
		form.policyNo.readOnly				=		false;
		form.valueInsured.readOnly			=		false;
		form.insurancePremium.readOnly		=		false;
		form.ncb.readOnly					=		false;
		form.insurancePremium.readOnly		=		false;
		form.paymentMode.disabled			=		false;
		form.branchName.readOnly			=		false;
		form.agentName.readOnly				=		false;
		form.paymentDescription.readOnly	=		false;

	}
}


function deleteVehicle(id) {
	 if(false===confirm("<?php echo DISCARD_CONFIRM;?>")) {
		 return false;
	 }

	document.getElementById('mode').value = 'delete';
	 url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/saveVehicle.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: 'mode=delete&busId='+id,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
		/*	 if("<?php echo ACCESS_DENIED;?>"==trim(transport.responseText)) {
				  messageBox(trim(transport.responseText));
			 }  */
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

function checkAllowedExtensions(value){
  //get the extension of the file 
  
  var val=value.substring(value.lastIndexOf('.')+1,value.length);

  var extArr= 'gif,jpg,jpeg,png,bmp'.split(",");
  var fl=0;
  var ln=extArr.length;
  
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }

  if(fl){
	  //alert(1);
   return true;
  }
 else{
	 //alert(2);
  return false;
 }   
}

function doSubmitAction() {
	if(trim(document.getElementById('busPhoto').value) != ""){
		document.getElementById('maxFileUpload').value = 1;	

	if(!checkAllowedExtensions(trim(document.getElementById('busPhoto').value))) {
		messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
		return false;
	  }
		document.getElementById('vehicleForm').target = 'uploadTargetFrame';
		document.getElementById('vehicleForm').action = '<?php echo HTTP_LIB_PATH;?>/Vehicle/saveVehicle.php';
		formSubmissionTime = '<?php echo time();?>';
		document.getElementById('vehicleForm').submit();
	}

	document.getElementById('vehicleForm').target = 'uploadTargetFrame';
	document.getElementById('vehicleForm').action = '<?php echo HTTP_LIB_PATH;?>/Vehicle/saveVehicle.php';
	formSubmissionTime = '<?php echo time();?>';
	document.getElementById('vehicleForm').submit();

	
	
}

function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
	setMode('edit');
}

var serverDate="<?php echo date('Y-m-d'); ?>";
function validateAddForm(frm) {
	return false;
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

//this function is used to delete bus's image
function deleteVehicleImage(busId){
 if(busId==''){
     return false;
 }
 
 if(!confirm("Do you want to delete the image?")){
     return false;
 }
     var url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/deleteVehicleImage.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             busId:busId
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                 document.getElementById('busPhotoDiv').innerHTML='';
                 document.getElementById('busPhotoDeleteDiv').innerHTML='';
             }
             else {
                 messageBox(trim(transport.responseText));
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
    
}

function blankValues() {
	form = document.vehicleForm;
    showTab('dhtmlgoodies_tabView1',0);
    document.getElementById('divHeaderId1').innerHTML='&nbsp;Add Vehicle Details';
    document.getElementById('busPhotoDiv').innerHTML='';
    document.getElementById('busPhotoDeleteDiv').innerHTML='';
	document.getElementById('serviceDetailDiv').innerHTML = '';
    
    form.busName.focus();
	form.vehicleType.disabled =	false;
	form.batteryNo.readOnly	= false;
	form.batteryMake.readOnly =	false;
	form.tyreModelNo.readOnly =	false;
	form.tyreManufacturingCompany.readOnly = false;
	form.freeService.readOnly = false;
	document.getElementById('showButton').style.display = '';

	form.reset();
	form.uniqueId.value = '<?php echo $_SESSION["xId"];?>';
}


function populateValues(id) {
	//document.getElementById('tyreDetailDiv').innerHTML = 'Tyre details can not be updated from here. Please use tyre master module to update tyre details';
	//document.getElementById('serviceDetailDiv').innerHTML = 'Service details can not be updated from here. Please use service module to update service details';
	form = document.vehicleForm;
	form.vehicleType.disabled = true;
	form.reset();
	form.uniqueId.value = '<?php echo $_SESSION["xId"];?>';
	form.busId.value = id;
	url = '<?php echo HTTP_LIB_PATH;?>/Vehicle/getVehicleDetails.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous:false,
		 parameters: {
			 busId:id
		 },
		 onCreate: function() {
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
             document.getElementById('divHeaderId1').innerHTML='&nbsp;Edit Vehicle Details';
             showTab('dhtmlgoodies_tabView1',0);
             
			 res = eval('('+trim(transport.responseText)+')');

			 //alert(transport.responseText);
			 document.getElementById('showButton').style.display = 'none';
			 form.vehicleType.disabled			=		true;
			 form.batteryNo.readOnly			=		true;
			 form.batteryMake.readOnly			=		true;
			 form.vehicleType.value				=		res['vehicleDetails'][0]['vehicleTypeId'];
             /*var l=form.busId.length;
             for(var i=0;i<len;i++){
              form.busId[i].value               =       res['vehicleDetails'][0]['busId'];
             }
             */
             form.busId.value                   =       res['vehicleDetails'][0]['busId'];
			 form.busName.value					=		res['vehicleDetails'][0]['busName'];
			 form.busNo1.value					=		res['vehicleDetails'][0]['busNo1'];
			 form.busNo2.value					=		res['vehicleDetails'][0]['busNo2'];
			 form.busNo3.value					=		res['vehicleDetails'][0]['busNo3'];
			 form.busNo4.value					=		res['vehicleDetails'][0]['busNo4'];
			 form.busModel.value				=		res['vehicleDetails'][0]['modelNumber'];
			 form.purchaseDate.value			=		res['vehicleDetails'][0]['purchaseDate'];
			 form.seatingCapacity.value			=		res['vehicleDetails'][0]['seatingCapacity'];
			 form.fuelCapacity.value			=		res['vehicleDetails'][0]['fuelCapacity'];
			 form.manYear.value					=		res['vehicleDetails'][0]['yearOfManufacturing'];
			 form.engineNo.value				=		res['vehicleDetails'][0]['engineNo'];
			 form.chasisNo.value				=		res['vehicleDetails'][0]['chasisNo'];
			 form.bodyMaker.value				=		res['vehicleDetails'][0]['bodyMaker'];
			 form.chasisCost.value				=		res['vehicleDetails'][0]['chasisCost'];
			 form.chasisPurchaseDate.value		=		res['vehicleDetails'][0]['chasisPurchaseDate'];
			 form.bodyCost.value				=		res['vehicleDetails'][0]['bodyCost'];
			 form.putOnRoadDate.value			=		res['vehicleDetails'][0]['putOnRoad'];
			 busImage							=		res['vehicleDetails'][0]['busImage'];
             form.vehicleCategory.value         =       res['vehicleDetails'][0]['vechicleCategoryId']; 
             document.getElementById('busPhotoDiv').innerHTML='';
             document.getElementById('busPhotoDeleteDiv').innerHTML='';
			 d = new Date();
			 var rndNo = d.getTime();
			 if (busImage != '') {
				 document.getElementById('busPhotoDiv').innerHTML='<img src="'+imagePathURL+'/Bus/'+busImage+'?'+rndNo+'" style="width:100px;height:25px;border:2px solid grey" height="65px;" width="70px" onclick=download("'+busImage+'"); />';
                 document.getElementById('busPhotoDeleteDiv').innerHTML='<img style="border:0px solid #8EBCD7" src="'+imagePathURL+'/delete1.gif?'+rndNo+'" alt="Delete Image" title="Delete Image" onclick="deleteVehicleImage('+id+')" />';
			 }

			 document.getElementById('addInsuranceDate').style.display = 'none';
			 document.getElementById('editInsuranceDate').style.display = '';
			 document.getElementById('divInsuranceDate').innerHTML = '';
			 document.getElementById('divInsuranceDueDate').innerHTML = '';
			 document.getElementById('divInsuranceDate').innerHTML	=	res['insuranceDetails'][0]['lastInsuranceDate'];
			 document.getElementById('divInsuranceDueDate').innerHTML	=	res['insuranceDetails'][0]['insuranceDueDate'];
			 form.insuringCompany.disabled		=		true;
			 form.insuringCompany.value			=		res['insuranceDetails'][0]['insuringCompanyId'];
			 form.policyNo.readOnly				=		true;
			 form.policyNo.value				=		res['insuranceDetails'][0]['policyNo'];
			 form.valueInsured.readOnly			=		true;
			 form.valueInsured.value			=		res['insuranceDetails'][0]['valueInsured'];
			 form.insurancePremium.readOnly		=		true;
			 form.insurancePremium.value		=		res['insuranceDetails'][0]['insurancePremium'];
			 form.ncb.readOnly					=		true;
			 form.ncb.value						=		res['insuranceDetails'][0]['ncb'];
			 form.insurancePremium.readOnly		=		true;
			 form.insurancePremium.value		=		res['insuranceDetails'][0]['insurancePremium'];
			 form.paymentMode.disabled			=		true;
			 form.paymentMode.value				=		res['insuranceDetails'][0]['paymentMode'];
			 form.branchName.readOnly			=		true;
			 form.branchName.value				=		res['insuranceDetails'][0]['branchName'];
			 form.agentName.readOnly			=		true;
			 form.agentName.value				=		res['insuranceDetails'][0]['agentName'];
			 form.paymentDescription.readOnly	=		true;
			 form.paymentDescription.value		=		res['insuranceDetails'][0]['paymentDescription'];


			 //form.regnNoValidTill.readOnly = true;
			 document.getElementById('vehicleRegnNoValidTill').style.display = 'none';
			 document.getElementById('divRegnNoValidTill').style.display = '';
			 document.getElementById('regnNovalid').innerHTML = '';
			 document.getElementById('regnNovalid').innerHTML =	res['taxDetails'][0]['busNoValidTill'];

			 //form.regnNoValidTill.value			=		res['taxDetails'][0]['busNoValidTill'];
			 document.getElementById('vehiclePassengerTaxValidTill').style.display = 'none';
			 document.getElementById('divPassengerTaxValidTill').style.display = '';
			 document.getElementById('passengerTaxvalid').innerHTML = '';
			 document.getElementById('passengerTaxvalid').innerHTML =	res['taxDetails'][0]['passengerTaxValidTill'];
			 //form.passengerTaxValidTill.value	=		res['taxDetails'][0]['passengerTaxValidTill'];

			 document.getElementById('vehiclePassingValidTill').style.display = 'none';
			 document.getElementById('divPassingValidTill').style.display = '';
			 document.getElementById('passingValid').innerHTML = '';
			 document.getElementById('passingValid').innerHTML =	res['taxDetails'][0]['passingValidTill'];
			 //form.passingValidTill.value		=		res['taxDetails'][0]['passingValidTill'];
			 
			 document.getElementById('vehicleRoadTaxValidTill').style.display = 'none';
			 document.getElementById('divRoadTaxValidTill').style.display = '';
			 document.getElementById('roadTaxValid').innerHTML = '';
			 document.getElementById('roadTaxValid').innerHTML =	res['taxDetails'][0]['roadTaxValidTill'];
			 //form.roadTaxValidTill.value		=		res['taxDetails'][0]['roadTaxValidTill'];

			 document.getElementById('vehiclePollutionTaxValidTill').style.display = 'none';
			 document.getElementById('divPollutionTaxValidTill').style.display = '';
			 document.getElementById('pollutionTaxValid').innerHTML = '';
			 document.getElementById('pollutionTaxValid').innerHTML =	res['taxDetails'][0]['pollutionCheckValidTill'];
			 //form.pollutionTaxValidTill.value	=		res['taxDetails'][0]['pollutionCheckValidTill'];
			
			 form.tyreModelNo.readOnly			=		true;
			 form.tyreManufacturingCompany.readOnly			=		true;
			 //form.tyreModelNo.value				=		res['tyreDetails'][0]['modelNumber'];
			 //form.tyreManufacturingCompany.value	=		res['tyreDetails'][0]['manufacturer'];

			 document.getElementById('modelNo').style.display = 'none';
			 /*document.getElementById('tyreDivModelNo').style.display = '';
			 document.getElementById('divModelNo').innerHTML = '';
			 document.getElementById('divModelNo').innerHTML	=	res['modelDiv'];*/

			 document.getElementById('manufacturingCompany').style.display = 'none';
			 document.getElementById('detailTyres').style.display = 'none';
			 /*document.getElementById('tyreDivManufacturingCompany').style.display = '';
			 document.getElementById('tyreDivManufacturingCompany').innerHTML = '';
			 document.getElementById('tyreDivManufacturingCompany').innerHTML	=	res['tyreDetails'][0]['manufacturer'];*/
			 document.getElementById('tyresInformation').style.display = '';
			 document.getElementById('tyreInformationDiv').innerHTML = res['tyreDiv'];
			
			 if(res['serviceDetails'][0]['countRecords'] != 0) { 
				 form.freeService.readOnly = true;
				 form.freeService.value = res['serviceDetails'][0]['countRecords'];
				 document.getElementById('serviceDetailDiv').innerHTML = res['serviceDiv'];
			 }
			 else {
				 form.freeService.readOnly = true;
				form.freeService.value = res['serviceDetails'][0]['countRecords'];
				document.getElementById('serviceDetailDiv').innerHTML = '';
			 }


			 form.batteryNo.value				=		res['batteryDetails'][0]['batteryNo'];
			 form.batteryMake.value				=		res['batteryDetails'][0]['batteryMake'];
			 //form.warrantyDate.value			=		res['batteryDetails'][0]['warrantyDate'];
			 //alert(document.getElementById('batteryWarrantyDate').innerHTML);
			 document.getElementById('warrantyTill').style.display = 'none';
			 document.getElementById('batteryWarrantyTill').style.display = '';
			 document.getElementById('batteryWarrantyDate').innerHTML = '';
			 document.getElementById('batteryWarrantyDate').innerHTML	=	res['batteryDetails'][0]['warrantyDate'];
             form.busName.focus();
	
		 },
		 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
	   });

}

function  download(str){    
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/Bus/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

/* function to print bus stop report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/vehicleReportPrint.php?'+qstr;
    window.open(path,"VehicleReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='vehicleReportCSV.php?'+qstr;
}
/*function getData('uploadTargetFrame') {
var chatContents = document.getElementById("uploadTargetFrame");
alert(chatContents);
if(chatContents.contentWindow){
cWindow = chatContents.contentWindow;
}
if(cWindow.document) {
cDocument=cWindow.document;
}//alert(cDocument);
}

window.onload=function(){
  getData();
} */
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Vehicle/listVehicleContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listVehicle.php $ 
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 2/10/10    Time: 3:24p
//Updated in $/Leap/Source/Interface
//fixed bug no.0002800
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 1/25/10    Time: 11:14a
//Updated in $/Leap/Source/Interface
//Show latest vehicle insurance detail non-editable
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/21/10    Time: 5:25p
//Updated in $/Leap/Source/Interface
//solve problem to upload max size photo
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/13/10    Time: 4:00p
//Updated in $/Leap/Source/Interface
//fixed bug no. 0002533
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/13/10    Time: 11:00a
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 1/12/10    Time: 1:32p
//Updated in $/Leap/Source/Interface
//fixed bug in Fleet management
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 1/08/10    Time: 7:39p
//Updated in $/Leap/Source/Interface
//fixed bug in fleet management
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/05/10    Time: 2:03p
//Updated in $/Leap/Source/Interface
//fixed bug on fleet management
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/28/09   Time: 3:09p
//Updated in $/Leap/Source/Interface
//fixed bug no.0002379
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/26/09   Time: 10:15a
//Updated in $/Leap/Source/Interface
//bug fixed nos. 0002370, 0002369
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/24/09   Time: 7:05p
//Updated in $/Leap/Source/Interface
//fixed bug nos.0002354,0002353,0002351,0002352,0002350,0002347,0002348,0
//002355,0002349
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Interface
//fixed bug during self testing
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Interface
//put DL image in transport staff and changes in modules
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 12/10/09   Time: 5:54p
//Updated in $/Leap/Source/Interface
//files released for jaineesh to continue FLEET MANAGEMENT
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/12/09    Time: 10:14
//Updated in $/Leap/Source/Interface
//checked in files
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 12/07/09   Time: 12:42p
//Created in $/Leap/Source/Interface
//initial file check-in
//



?>