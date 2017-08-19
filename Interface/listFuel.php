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
define('MODULE','FuelMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Fuel/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Fuel Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('name','Staff','width="10%"','',true),
    new Array('busNo','Registration No.','width="10%"','',true),
    new Array('dated','Date','width="8%"','align="center"',true),
    new Array('lastMilege','Last Mileage','width="8%"','align="right"',true),
    new Array('currentMilege','Current Mileage','width="10%"','align="right"',true),
    new Array('litres','Litres','width="7%"','align="left"',true),
    new Array('amount','Amount','width="7%"','align="right"',true) , 
    new Array('action','Action','width="2%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFuel';   
editFormName   = 'EditFuel';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFuel';
divResultName  = 'results';
page=1; //default page
sortField = 'name';
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
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    //displayWindow(dv,w,h);
    populateValues(id,dv,w,h);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
      new Array("vehicleType","<?php echo SELECT_VEHICLE_TYPE; ?>"),
	  new Array("busId","<?php echo SELECT_BUS_NAME; ?>"),
      new Array("staffId","<?php echo ENTER_STAFF_NAME; ?>"),
      new Array("lastMilege","<?php echo ENTER_LAST_MILEGE; ?>"),
      new Array("currentMilege","<?php echo ENTER_CURRENT_MILEGE; ?>"),
      new Array("litres","<?php echo ENTER_LITRES; ?>"),
      new Array("amount","<?php echo ENTER_FUEL_COST; ?>")
      
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(fieldsArray[i][0]=="amount" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 ))  {
                messageBox("<?php echo FUEL_COST_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            } 
            if(fieldsArray[i][0]=="currentMilege" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 ))  {
                messageBox("<?php echo CURRENT_MILEAGE_COST_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(fieldsArray[i][0]=="litres" && (!isDecimal(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value") < 0 ))  {
                messageBox("<?php echo LITRES_NUM; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
         
        }
     
    }                 
    
    if(act=='Add') {
      if(!dateDifference(document.getElementById('dated1').value,serverDate,'-')){
         messageBox("<?php echo FUEL_DATE_VALIDATION; ?>");
         document.getElementById('dated1').focus();
         return false; 
      }
     if(document.AddFuel.lastMilege.value >= parseInt(trim(document.AddFuel.currentMilege.value),10)){
         messageBox("<?php echo CURRENT_FUEL_RESTRICTION; ?>");
         document.AddFuel.currentMilege.focus();
         return false; 
     }   
        addFuel();
        return false;
    }
    else if(act=='Edit') {
      if(!dateDifference(document.getElementById('dated2').value,serverDate,'-')){
         messageBox("<?php echo FUEL_DATE_VALIDATION; ?>");
         document.getElementById('dated2').focus();
         return false; 
      }
     if(document.EditFuel.lastMilege.value >= parseInt(trim(document.EditFuel.currentMilege.value),10)){
         messageBox("<?php echo CURRENT_FUEL_RESTRICTION; ?>");
         document.EditFuel.currentMilege.focus();
         return false; 
     }       
        editFuel();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A BUS
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFuel() {
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 busId:         (document.AddFuel.busId.value),
                 staffId:       (document.AddFuel.staffId.value),
                 dated:         (document.AddFuel.dated1.value),
                 litres:        trim(document.AddFuel.litres.value),
                 amount:        trim(document.AddFuel.amount.value),
                 lastMilege:    trim(document.AddFuel.lastMilege.value),
                 currentMilege: trim(document.AddFuel.currentMilege.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText).split('~!~!~');
                     if("<?php echo SUCCESS;?>" == ret[0]) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddFuel');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else if("<?php echo BACK_DATE_ENTRY_VALIDATION;?>" == ret[0]){
                       messageBox("<?php echo BACK_DATE_ENTRY_VALIDATION ;?>"+ret[1]+"<?php echo BACK_DATE_ENTRY_VALIDATION2;?>"+ret[2]+"<?php echo BACK_DATE_ENTRY_VALIDATION3 ?>"+ret[1]); 
                       document.AddFuel.busId.focus();
                     }
                     else if("<?php echo FUTURE_DATE_ENTRY_VALIDATION;?>" == ret[0]){
                       messageBox("<?php echo FUTURE_DATE_ENTRY_VALIDATION ;?>"+ret[1]+"<?php echo BACK_DATE_ENTRY_VALIDATION2;?>"+ret[2]+"<?php echo BACK_DATE_ENTRY_VALIDATION3 ?>"+ret[1]); 
                       document.EditFuel.busId.focus();
                     }  
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteFuel(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {fuelId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if(trim(transport.responseText)==-1) {
                         messageBox("You can not delete this record");
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addFuel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
	document.AddFuel.reset(); 
	document.getElementById('fuelSummaryDiv').innerHTML='';
	document.AddFuel.busId.length = null;
	addOption(document.AddFuel.busId, '', 'Select');
	document.AddFuel.vehicleType.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editFuel() {
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              fuelId:        (document.EditFuel.fuelId.value),
              busId:         (document.EditFuel.busId.value),
              staffId:       (document.EditFuel.staffId.value),
              dated:         (document.EditFuel.dated2.value),
              litres:        trim(document.EditFuel.litres.value),
              amount:        trim(document.EditFuel.amount.value),
              lastMilege:    trim(document.EditFuel.lastMilege.value),
              currentMilege: trim(document.EditFuel.currentMilege.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var ret=trim(transport.responseText).split('~!~!~');
                    if("<?php echo SUCCESS;?>" == ret[0]) {  
                         hiddenFloatingDiv('EditFuel');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                    else if("<?php echo BACK_DATE_ENTRY_VALIDATION;?>" == ret[0]){
                       messageBox("<?php echo BACK_DATE_ENTRY_VALIDATION ;?>"+ret[1]+"<?php echo BACK_DATE_ENTRY_VALIDATION2;?>"+ret[2]+"<?php echo BACK_DATE_ENTRY_VALIDATION3 ?>"+ret[1]); 
                       document.EditFuel.busId.focus();
                    }
                    else if("<?php echo FUTURE_DATE_ENTRY_VALIDATION;?>" == ret[0]){
                       messageBox("<?php echo FUTURE_DATE_ENTRY_VALIDATION ;?>"+ret[1]+"<?php echo BACK_DATE_ENTRY_VALIDATION2;?>"+ret[2]+"<?php echo BACK_DATE_ENTRY_VALIDATION3 ?>"+ret[1]); 
                       document.EditFuel.busId.focus();
                    }  
                    else {
                        messageBox(trim(transport.responseText));                         
                    }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditFuel" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)                                              
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id,dv,w,h) {
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {fuelId: id},
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
                    else if(trim(transport.responseText)==-1) {
                        messageBox("You can not edit this record");
                        return false;
                    }
                   else{
                       displayWindow(dv,w,h);
                       
                       j = eval('('+transport.responseText+')');
                       document.EditFuel.vehicleType.value = j.vehicleTypeId;
					   document.EditFuel.busId.value = j.busId;
                       document.EditFuel.staffId.value = j.staffId;
                       document.EditFuel.dated2.value = j.dated;
                       document.EditFuel.litres.value = j.litres;
                       document.EditFuel.amount.value = j.amount;
                       document.EditFuel.lastMilege.value = j.lastMilege;
                       document.EditFuel.currentMilege.value=j.currentMilege;
                   
                       document.EditFuel.fuelId.value =j.fuelId;
                   
                       document.EditFuel.busId.focus();
                   }
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "lastMilege" field
//Author : Dipanjan Bhattacharjee
// Created on : (06.04.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function getLastMilege(id,mode) {
         document.getElementById('fuelSummaryDiv').innerHTML='';
         if(id==''){
             return false;
         }
         if(mode==2){
             var fdate=document.getElementById('dated2').value;
         }
         else{
             var fdate=document.getElementById('dated1').value;
         }
         url = '<?php echo HTTP_LIB_PATH;?>/Fuel/ajaxGetLastMilege.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 busId: id,
                 fdate : fdate
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     var ret=trim(transport.responseText).split('~~~');
                     if(mode==2){
                        document.EditFuel.lastMilege.value=ret[0]; 
                     }
                    else{
                        document.AddFuel.lastMilege.value=ret[0];
                         if(ret.length>1){
                             document.getElementById('fuelSummaryDiv').innerHTML=ret[1];
                         }
                    } 
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (30.12.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getVehicleDetails() {
	form = document.AddFuel;
	var url = '<?php echo HTTP_LIB_PATH;?>/VehicleTyre/getVehicleNumbers.php';
	var pars = 'vehicleTypeId='+form.vehicleType.value;
	if (form.vehicleType.value=='') {
		form.busId.length = null;
		addOption(form.busId, '', 'Select');
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
				form.busId.length = null;
				addOption(form.busId, '', 'Select');
				return false;
			}
			len = j.length;
			/*if(len == 'undefined') {
				alert(1);
				form.vehicleNo.length = null;
				addOption(form.vehicleNo, '', 'Select');
			}*/
			form.busId.length = null;
			addOption(form.busId, '', 'Select');
			for(i=0;i<len;i++) {
				addOption(form.busId, j[i].busId, j[i].busNo);
			}
			// now select the value
			//form.blockName.value = j[0].blockId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

/* function to print fuel report report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/fuelReportPrint.php?'+qstr;
    window.open(path,"FuelReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='fuelReportCSV.php?'+qstr;
}


function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddFuel;
 }
 else{
     var form = document.EditFuel;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

window.onload=function(){
 document.getElementById('calImg1').onblur=getFuel1
 document.getElementById('calImg2').onblur=getFuel2
}

function getFuel1(){
  getLastMilege(document.AddFuel.busId.value,1);
}

function getFuel2(){
  getLastMilege(document.EditFuel.busId.value,2);
}

function getAmountCalculation() {
	if(document.AddFuel.rate.value != '') {
		document.AddFuel.amount.value = document.AddFuel.litres.value * document.AddFuel.rate.value;
	}
}

function getEditAmountCalculation() {
	if(document.EditFuel.rate.value != '') {
		document.EditFuel.amount.value = document.EditFuel.litres.value * document.EditFuel.rate.value;
	}
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Fuel/listFuelContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listFuel.php $ 
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 2/01/10    Time: 4:01p
//Updated in $/Leap/Source/Interface
//show select in bus no.
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 1/19/10    Time: 11:32a
//Updated in $/Leap/Source/Interface
//add vehicle type drop down to select vehicle no. according to vehicle
//type
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/24/09   Time: 7:05p
//Updated in $/Leap/Source/Interface
//fixed bug nos.0002354,0002353,0002351,0002352,0002350,0002347,0002348,0
//002355,0002349
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/18/09   Time: 6:28p
//Updated in $/Leap/Source/Interface
//changes in fuel as database changed
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 6/08/09    Time: 16:04
//Updated in $/Leap/Source/Interface
//Corrected validation code for fuel module when we add a fuel entry
//which  is between two existing dates.
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 5/08/09    Time: 17:27
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//bug ids--
//0000878 to 0000883
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 3/08/09    Time: 15:46
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//bug ids---
//0000817 to 0000821
//
//*****************  Version 3  *****************
//User: Administrator Date: 14/05/09   Time: 10:35
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//Bug Ids---1001 to 1005
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/05/09    Time: 15:39
//Updated in $/Leap/Source/Interface
//Updated fleet mgmt file in Leap 
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 22/04/09   Time: 19:14
//Updated in $/SnS/Interface
//Done bug fixing
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 20/04/09   Time: 10:37
//Updated in $/SnS/Interface
//Done bug fixing
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 18/04/09   Time: 10:28
//Updated in $/SnS/Interface
//Done bug fixing
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 10/04/09   Time: 10:29
//Updated in $/SnS/Interface
//Corrected spelling mistake
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:36
//Updated in $/SnS/Interface
//Enhanced fuel master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 6/04/09    Time: 13:04
//Updated in $/SnS/Interface
//Enhanced fuel master
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:50
//Updated in $/SnS/Interface
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 18:36
//Created in $/SnS/Interface
//Created Fuel Master
?>