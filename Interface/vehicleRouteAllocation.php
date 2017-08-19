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
define('MODULE','VehicleRouteAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
global $sessionHandler;
$userRoleId = $sessionHandler->getSessionVariable('RoleId'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Vehicle Route Allocation </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                      new Array('srNo','#','width="1%"','',false),
                      new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"checkAll();\">','width="2%"','align=\"left\"',false), 
                      new Array('studentName','Name','width="7%"','',true) ,
                      new Array('rollNo','Roll No.','width="7%"','',true) , 
                      new Array('className','Class','width="12%"','',true) , 
                      new Array('routeName ','Route<BR>(Seat No.)','width="10%"','align="left"',true) ,
                      new Array('cityName','Stoppage','width="10%"','',true), 
                      new Array('validFromTo','Valid','width="8%" align="center"','align="center"',true) , 
                      new Array('routeCharges', 'Transport Charges','width="8%" align="center"','align="center"',false),
		      new Array('ledgerDebit','Ledger Debit ','width="10%" align="right"','align="right"',true),  
		      new Array('ledgerCredit','Ledger Credit ','width="10%" align="right"','align="right"',true),
		       new Array('totalAmount',  'Total Transport Fee','width="10%" align="center"','align="center"',false),   
                      new Array('paidAmount',  'Transport Fee Paid','width="10%" align="center"','align="center"',false), 
		       new Array('balance',  'Balance','width="10%" align="center"','align="center"',false), 
                      new Array('printReceipt',  'Bank<br>Challan','width="8%" align="center"','align="center"',false), 
                      new Array('action','Action','width="6%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxVehicleRouteAllocationList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'addVechileRouteAllocation';   
editFormName   = 'EditVehicleRouteAllocationDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteVehicleAllocation';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
valShow = '1';
  
ttVehicleCharges  ='';
ttBusRouteId = '';
ttBusStopId = '';
ttBusStopCityId = '';
ttClassId = '';
ttFeeCycleId = '';
ttActiveCycle = '';  

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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    ret = id.split("~");
    id= ret[0];   
    if(ret[1]=='1') {
      displayWindow('EditVehicleRouteAllocationDiv',w,h);
    }
    else {
      displayWindow('EditEmployeeVehicleRouteAllocationDiv',w,h);  
    }
     populateValues(id,ret[1]);   
}
function getShowSearch(val) {
   
   showStatus=''; 
   if(val=='') {
     showStatus='none';  
   } 
   
   for(i=1;i<=6;i++) {
     id = "searchDt"+i;  
     eval("document.getElementById('"+id+"').style.display=showStatus");
   }
   
}
 function getShowList() {
   
    page=1;
    /*
    if(document.getElementById('searchRouteName').value=='') { 
       messageBox("Select Route Name");
       document.getElementById('searchRouteName').focus();
       return false;  
    }
    */
   
    if(document.getElementById('searchDate').value!='') {
       if(document.getElementById('fromDate').value=='') {
          messageBox("Select From Date");
          document.getElementById('fromDate').focus();
          return false; 
       } 
       if(document.getElementById('toDate').value=='') {
          messageBox("Select To Date");
          document.getElementById('toDate').focus();
          return false; 
       } 
       if(!dateDifference(document.getElementById('fromDate').value,document.getElementById('toDate').value,'-') ) {
          messageBox ("From Date cannot greater than To Date");
          document.getElementById('fromDate').focus(); 
          return false;
       } 
    }
	document.getElementById('printRow').style.display='';
    document.getElementById('printRow2').style.display='none';
    document.getElementById('printRowNote').style.display='none';
   
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    return false;  
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
        new Array("rollRegNo","<?php echo ENTER_STUDENT_ROLL_OR_REG_NO;?>"),
        new Array("stopCity","Select Stop City"),
        new Array("stopName","Select Stop Name"),
        new Array("rootName","Select Root Name"),
	    new Array("seatNumber","Select Seat Number"),
        new Array("feeCycleId","Select Fee Cycle"),
        new Array("transportCharges","Enter Transport Charges")    
        );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }



    if(act=='Add') {
        if(frm.studentId.value==''){
            messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
            document.addVehicleRoomAllocation.rollRegNo.focus();
            return false;
        }
        
        transportCharges = parseFloat(trim(document.addVechileRouteAllocation.transportCharges.value),10);
        assignTransportCharges = parseFloat(trim(document.addVechileRouteAllocation.assignTransportCharges.value),10);
        
        if(transportCharges!=assignTransportCharges) {               
          if(trim(document.addVechileRouteAllocation.comments.value)=='') {
            messageBox("Please enter comments for changing the transport charges");
            document.addVechileRouteAllocation.comments.focus();
            return false;   
          }  
        }
        
        addRouteAllocation(frm);
        return false;
    }
    else if(act=='Edit') {
        if(document.EditVehicleRouteAllocation.studentId.value==''){
            messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
            document.EditVehicleRouteAllocation.rollRegNo.focus();
            return false;
        } 
        transportCharges = parseFloat(trim(document.EditVehicleRouteAllocation.transportCharges.value),10);
        if(document.EditVehicleRouteAllocation.editValidTo.value!=''){
            if(!dateDifference				(document.EditVehicleRouteAllocation.editValidFrom.value,document.EditVehicleRouteAllocation.editValidTo.value,'-')){
                messageBox("<?php echo CHECK_OUT_DATE_VALIDATION; ?>");
                document.EditVehicleRouteAllocation.editValidTo.focus();
                return false;
            }
        }
        
	ttCharges = parseFloat(transportCharges,10);
	if("<?php echo $userRoleId; ?>" != '23' ) {
          if(ttVehicleCharges != ttCharges) {
	    messageBox("Changes in the Transport Charges can only be done by ACCOUNTS DEPARTMENT' ");
	    return false;
	  }
	}
           
        editRouteAllocation();
        return false;
   }   
      
    }
    
  

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Nishu Bindal
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRouteAllocation(frm) {
        if(false===confirm("Are you sure you want to give transport facility!!!")) {
          return false;
        }
        
        val = frm.feeCycleId.value;
        ret = val.split("!~!");
	    feeCycleId = ret[0];
        
         url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxAddRouteAllocation.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {
                    studentId  : (document.addVechileRouteAllocation.studentId.value), 
                    stopCity     : (document.addVechileRouteAllocation.stopCity.value),
                    stopId   : (document.addVechileRouteAllocation.stopName.value),
                    classId    : (document.addVechileRouteAllocation.classId.value), 
                    validFrom      : (document.addVechileRouteAllocation.validFrom.value),
                    validTo    : (document.addVechileRouteAllocation.validTo.value),
                    rootId      : (document.addVechileRouteAllocation.rootName.value),
                    seatNumber     : (document.addVechileRouteAllocation.seatNumber.value),
                    feeCycleId : feeCycleId,
                    transportCharges : trim(document.addVechileRouteAllocation.transportCharges.value),
                    comments: trim(document.addVechileRouteAllocation.comments.value)     
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {busRouteStudentMappingId
                             blankValues(1);
                         }
                         else{
                            hiddenFloatingDiv('VechileRouteAllocation'); 
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteVehicleAllocation(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {
         	url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {
                 busRouteStudentMappingId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                    	messageBox(trim(transport.responseText));
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
//THIS FUNCTION IS USED TO CLEAN UP THE "Add Vehicle Route Allocation" DIV
//Author : Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues(isType) {
   
    if(isType=='1') {
       document.getElementById('showPrevious1').style.display='none';
       document.getElementById('studentName1').innerHTML  = '';  
       document.addVechileRouteAllocation.feeCycleId.length = null;    
       addOption(document.addVechileRouteAllocation.feeCycleId, '', 'Select'); 
       document.addVechileRouteAllocation.classId.length = null;    
       addOption(document.addVechileRouteAllocation.classId, '', 'Select');
       document.addVechileRouteAllocation.rollRegNo.focus();
       document.addVechileRouteAllocation.reset();
    }  
    else {
       document.addEmployeeVechileRouteAllocation.feeCycleId.length = null;    
       addOption(document.addEmployeeVechileRouteAllocation.feeCycleId, '', 'Select'); 
       document.addEmployeeVechileRouteAllocation.reset();
       document.getElementById('employeeName1').innerHTML      = '';
       document.getElementById('employeeDesignation1').innerHTML      = '';
       document.getElementById('showPrevious1').style.display='none';
       document.addEmployeeVechileRouteAllocation.employeeCode.focus();
    }
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRouteAllocation() {
   
         if(false===confirm("Are you sure you want to update route allocation")) {
          return false;
        }
        
        val = document.EditVehicleRouteAllocation.feeCycleId.value;
        ret = val.split("!~!");
        feeCycleId = ret[0];
        
        val = document.EditVehicleRouteAllocation.rootName.value;
        ret = val.split("!~!");
        roomId = ret[0];
        
      
         var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxEditVehicleRouteAllocation.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous :false,
             parameters: {
                 studentId       : (document.EditVehicleRouteAllocation.studentId.value), 
                 stopCity        : (document.EditVehicleRouteAllocation.stopCity.value), 
                 stopId        : (document.EditVehicleRouteAllocation.stopName.value),
                 classId       : (document.EditVehicleRouteAllocation.classId.value), 
                 rootId        : roomId,
				 validFrom       : (document.EditVehicleRouteAllocation.editValidFrom.value),
                 validTo          : (document.EditVehicleRouteAllocation.editValidTo.value),
			     seatNumber        : (document.EditVehicleRouteAllocation.seatNumber.value),
                 busRouteStudentMappingId: (document.EditVehicleRouteAllocation.busRouteStudentMappingId.value),
                 feeCycleId : feeCycleId,
                 transportCharges : trim(document.EditVehicleRouteAllocation.transportCharges.value),
                 comments : trim(document.EditVehicleRouteAllocation.comments.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditVehicleRouteAllocationDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else{
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 function populateValues(id, isAllocationId){
       ttVehicleCharges=''; 
       ttBusRouteId = '';
       ttBusStopId = '';
       ttBusStopCityId = '';
       ttClassId = '';
       ttFeeCycleId = '';
      
         var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxGetRouteAllocationValues.php';
         new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {
                 busRouteStudentMappingId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
		hideWaitDialog(true);

		if(trim(transport.responseText)=='Route Allocation Edit Restriction') {
		  messageBox(trim(transport.responseText));
		  return false; 	
		}	
		var j = eval('('+transport.responseText+')');
         if(j.isAllocation == '1') {
                    document.getElementById('studentName2').innerHTML     = '';
                    var ret='';
                    if(j.rollNo=="---"){
                       ret=j.regNo;
                    }
                    else{
                       ret=j.rollNo;
                    }
               
                  //  alert(j.busStopCityId);return false;
                    document.EditVehicleRouteAllocation.rollRegNo.value = ret;
                    //document.getElementById('studentName2').innerHTML = trim(j.studentName);                 
                   document.EditVehicleRouteAllocation.studentId.value = j.studentId;
                    document.EditVehicleRouteAllocation.stopCity.value = j.busStopCityId;
                    document.EditVehicleRouteAllocation.seatNumber.value = j.seatNumber;
                    document.EditVehicleRouteAllocation.editValidFrom.value= j.validFrom;
                    document.EditVehicleRouteAllocation.editValidTo.value = j.validTo;
                    document.EditVehicleRouteAllocation.busRouteStudentMappingId.value = id;
                    document.EditVehicleRouteAllocation.comments.value     = j.comments;   
                    document.EditVehicleRouteAllocation.transportCharges.value     = j.routeCharges;  
                    ttVehicleCharges = parseFloat(j.routeCharges,10); 	 

                    ttValidFrom = j.validFrom;
                    ttValidTo  = j.validTo;
                    ttBusStopCityId = j.busStopCityId;
                    ttBusStopId = j.busStopId;
                    ttClassId = j.classId; 
                    ttFeeCycleId = j.feeCycleId;
                    ttBusRouteId = j.busRouteId
                    
                    getStudentData(ret,'Edit');
                    document.getElementById('studentName2').innerHTML = trim(j.studentName); 
                    //alert(ttClassId+"   "+document.EditVehicleRouteAllocation.classId.length);
                    document.EditVehicleRouteAllocation.classId.value = ttClassId;   

                    getFeeCycle(ttClassId,'E');   
                    getStopName(ttBusStopCityId,'Edit',ttBusStopId); 
                    getRouteName(ttBusStopId,'Edit',ttBusRouteId);
                  //  document.EditVehicleRouteAllocation.editStopName.value = ttBusStopId;  
                //    document.EditVehicleRouteAllocation.editRootName.value = ttBusRouteId;  
                    
                 
                    var len=document.EditVehicleRouteAllocation.stopName.length;
                    for(var i=0; i<len; i++) {
                    ret = trim(document.EditVehicleRouteAllocation.stopName.options[i].value).split("!~!");
                    if(ret[0]==ttBusRouteId) {
                    document.EditVehicleRouteAllocation.stopName.options[i].selected = true;  
                    break;
                    }
                    }
                    var len=document.EditVehicleRouteAllocation.rootName.length;
                    for(var i=0; i<len; i++) {
                    ret = trim(document.EditVehicleRouteAllocation.rootName.options[i].value).split("!~!");
                    if(ret[0]==ttBusRouteId) {
                    document.EditVehicleRouteAllocation.rootName.options[i].selected = true;  
                    break;
                    }
                    }

                    var len=document.EditVehicleRouteAllocation.feeCycleId.length;
                    for(var i=0; i<len; i++) {
                    ret = trim(document.EditVehicleRouteAllocation.feeCycleId.options[i].value).split("!~!");
                    if(ret[0]==ttFeeCycleId) {
                    document.EditVehicleRouteAllocation.feeCycleId.options[i].selected = true;  
                    break;
                    }
                    }
                    document.EditVehicleRouteAllocation.editValidFrom.value= ttValidFrom;
                    document.EditVehicleRouteAllocation.editValidTo.value = ttValidTo;
         }
         
      },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });
}



function getStudentData(value,act){
	 document.getElementById('previousTransportFacilityDiv').innerHTML = '';  
    if(act=='Add'){
    	document.getElementById('showPrevious1').style.display='none';
        document.getElementById('studentName1').innerHTML     = '';
       // document.getElementById('studentClass1').innerHTML    = '';
        document.addVechileRouteAllocation.studentId.value='';
         
        document.addVechileRouteAllocation.classId.length = null;
        addOption(document.addVechileRouteAllocation.classId, '', 'Select');
    }
    else{
        document.getElementById('showPrevious2').style.display='none';        
        document.getElementById('studentName2').innerHTML     = '';
        document.EditVehicleRouteAllocation.studentId.value='';        
        document.EditVehicleRouteAllocation.classId.length = null;
        addOption(document.EditVehicleRouteAllocation.classId, '', 'Select');
    }
    if(trim(value)==''){
        return false;
    }

    var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxGetStudentClassData.php';
    new Ajax.Request(url,
           {
             method:'post',
              asynchronous :false,
             parameters: {
                 param: value
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)!=0){
                   var ret=trim(transport.responseText).split('!~!!~!');
                   if(act=='Add'){
                        j0 = eval(ret[0]);  
                        document.addVechileRouteAllocation.studentId.value=j0[0].studentId;
                        document.getElementById('studentName1').innerHTML=trim(j0[0].studentName);
                        document.addVechileRouteAllocation.classId.length = null; 
                        addOption(document.addVechileRouteAllocation.classId, '', 'Select');  
                                                   
                        j0 = eval(ret[1]);
                        for(i=0;i<j0.length;i++) { 
                            str = j0[i].className; 
                            addOption(document.addVechileRouteAllocation.classId, j0[i].classId, str);
                        }
                  }
                  else{ 
                       j0 = eval(ret[0]); 
                       document.EditVehicleRouteAllocation.studentId.value=j0[0].studentId;
                       document.getElementById('studentName2').innerHTML=trim(j0[0].studentName);
                       document.EditVehicleRouteAllocation.classId.length = null; 
                       addOption(document.EditVehicleRouteAllocation.classId, '', 'Select');        
                             
                       j0 = eval(ret[1]);
                       for(i=0;i<j0.length;i++) { 
                            addOption(document.EditVehicleRouteAllocation.classId, j0[i].classId, j0[i].className);
                       }                    
                  }
                  if(act=='Add') {
                        document.getElementById('showPrevious1').style.display='none';
                   }
                   else {
                        document.getElementById('showPrevious2').style.display='none';
                   }
                    document.getElementById('previousTransportFacilityDiv').innerHTML = '';
                    j0 = eval(ret[2]);
                    if(j0.length >0 ) {
                        tableData  = "<b><br>&nbsp;<u>"+j0[0].studentName+" ("+j0[0].rollNo+")</u></b><br><br>"; 
                        tableData += "<table width='100%' border='0' cellspacing='2' cellpadding='0'>";
                        tableData += "<tr class='rowheading'>";
                        tableData += "<td class='searchhead_text' width='5%'>#</td><td class='searchhead_text' width='20%'>Class</td>";
                        tableData += "<td class='searchhead_text' width='17%'>Stop City</td><td class='searchhead_text' width='18%'>Stop Name</td>";
                        tableData += "<td class='searchhead_text' width='10%' align='center'>Valid From</td><td align='center' class='searchhead_text' width='10%'>Valid To</td>";
                        tableData += "<td class='searchhead_text' width='10%' align='right'>Rent</td>";
                        tableData += "</tr>";            
                        for(i=0;i<j0.length;i++) { 
                          bg = bg =='trow0' ? 'trow1' : 'trow0';    
                          tableData += "<tr class='bg'>";            
                            tableData += "<td class='padding_top'>"+(i+1)+"</td><td class='padding_top'>"+j0[i].className+"</td>";
                            tableData += "<td class='padding_top'>"+j0[i].cityName+"</td><td class='padding_top'>"+j0[i].stopName+"</td>";
                            tableData += "<td class='padding_top' align='center'>"+j0[i].validFrom+"</td><td class='padding_top' align='center'>"+j0[i].validTo+"</td>";
                            tableData += "<td class='padding_top'  align='right'>"+j0[i].routeCharges+"</td>";
                          tableData += "</tr>";            
                        }
                        tableData += "</table>";            
                        document.getElementById('previousTransportFacilityDiv').innerHTML = tableData; 
                        if(act=='Add') {  
                          document.getElementById('showPrevious1').style.display='';
                        }
                        else {
                          document.getElementById('showPrevious2').style.display='';
                        }
                      }
                     return false;
                 }
                 else{
                     messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
           }); 
    
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Room Type
//Author : Nishu Bindal
// Created on : (6.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getStatus() {
  displayWindow('previousTransportFacility','550','550');
}


	function getStopName(cityId,act,selectedId){
		var objOption ='';
			 if(act == 'Add'){   
                form = document.addVechileRouteAllocation;
           		}    
			else{
			     form = document.EditVehicleRouteAllocation;
          		    
			}
        
		var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/getStopNames.php';
		new Ajax.Request(url,
		{
			method:'post',
			 asynchronous :false,
			parameters:{	cityId:cityId
				},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.stopName.length = null;
                
				form.rootName.length = null;
                	
				addOption(form.rootName, '', 'Select');
                
				if(j==0 || len == 0) {
				addOption(form.stopName, '', 'Select');
                   		 
					return false;
				}
				else{
				addOption(form.stopName, '', 'Select');
                  		 
				     for(i=0;i<len;i++) {
					 if(selectedId == j[i].busStopId){ 
   					     objOption = new Option(j[i].stopName,j[i].busStopId);
   					     objOption.setAttribute("selected", "selected");
				             form.stopName.options.add(objOption);                           	
						}
						else{
						addOption(form.stopName, j[i].busStopId, j[i].stopName);
                            	
						}
					}
				}
               
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
    
   function getStopNames(cityId,act,selectedId){
		var objOption ='';
			 if(act == 'Add'){   
                form = document.addEmployeeVechileRouteAllocation;
           		}    
			else{
			     form = document.EditEmployeeVehicleRouteAllocation;          		   
			}
        
		var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/getStopNames.php';
		new Ajax.Request(url,
		{
			method:'post',
			 asynchronous :false,
			parameters:{	cityId:cityId
				},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.stopName.length = null;                
				form.rootName.length = null;                	
				addOption(form.rootName, '', 'Select');
                
				if(j==0 || len == 0) {
				addOption(form.stopName, '', 'Select');
                   		 
					return false;
				}
				else{
				addOption(form.stopName, '', 'Select');
                  		 
				     for(i=0;i<len;i++) {
					 if(selectedId == j[i].busStopId){ 
   					     objOption = new Option(j[i].stopName,j[i].busStopId);
   					     objOption.setAttribute("selected", "selected");
				             form.stopName.options.add(objOption);                           	
						}
						else{
						addOption(form.stopName, j[i].busStopId, j[i].stopName);
                            	
						}
					}
				}
               
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	
//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET Room Name
//Author : Nishu Bindal
// Created on : (28.Feb.2012)
// Copyright 2012-2013 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	function getRouteName(stopId,act,selectedId){
		var objOption ='';
		 if(act == 'Add'){   
			form = document.addVechileRouteAllocation;
            	
		}
		else{
			form = document.EditVehicleRouteAllocation;
			
		}
        
        classId = form.classId.value;
        
        if(classId=='') {
          messageBox("Select Class");  
          form.classId.focus();  
          return false;  
        }
        
        
		var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/getRouteNames.php';
		new Ajax.Request(url,
		{
			method:'post',
			asynchronous :false,
			parameters:{ stopId:stopId,
                         classId: classId
                       },
			 onCreate: function(){
				 showWaitDialog(true);
			 },
			onSuccess: function(transport){ 
				hideWaitDialog(true);
        
				var j = eval('(' + transport.responseText + ')');
				len = j.length;
				form.rootName.length = null;
                
				if(j==0 || len == 0) {
					addOption(form.rootName, '', 'Select');              
					return false;
				}
				else{
					addOption(form.rootName, '', 'Select');
                 
					for(i=0;i<len;i++) {
                        str = j[i].routeName+' ('+j[i].transportCharges+')';
                        ids = j[i].busRouteId+'!~!'+j[i].transportCharges;
                        objOption = new Option(str,ids); 
						if(selectedId == j[i].busRouteId){ 
                          objOption.setAttribute("selected", "selected");
						}
						form.rootName.options.add(objOption);   
                     	}
				}
			
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		});
	}
	
var serverDate="<?php echo date('Y-m-d'); ?>";
//var advancedDate="<?php echo date("Y-m-d", mktime(0, 0, 0, date('m'), date('d')+30, date('Y'))); ?>";
var advancedDate="<?php echo date("Y-m-d"); ?>";
function prepareReportFilter(){
    var mode=document.form2.reportTypeRadio[0].checked==true?1:0;
    if(mode){
       document.getElementById('reportTrId').style.display='none';
       document.form2.hostelId.selectedIndex=0;
       document.form2.roomType.options.length=1;
       document.form2.roomType.selectedIndex=0;
       document.getElementById('results').innerHTML='';
       document.getElementById('searchFormTdId').style.display='';
       document.getElementById('searchbox').value='';
       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
    }
    else{
       document.getElementById('reportTrId').style.display='';
       document.getElementById('results').innerHTML='';
       document.form2.hostelId.selectedIndex=0;
       document.getElementById('searchFormTdId').style.display='none';
       document.getElementById('searchbox').value='';
       document.getElementById('toDate').value=advancedDate;
       getPossibleVacantRoomReport();  
    }
}


function selectStudents(){
    var c1 = document.getElementById('updateAllocationDiv').getElementsByTagName('INPUT');
    var len=c1.length;
    var state=document.getElementById('studentList').checked;
    for(var i=0;i<len;i++){
        if (c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='studentChk'){
            c1[i].checked=state;
        }
    }
}


window.onload=function(){
   valShow=1;           
   getShowDetail();
   //getShowSearch(5);   
   getSearchRouteName();
   //var roll = document.getElementById("rollRegNo");
   //autoSuggest(roll);
}



function getFeeCycle(id,mod) {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxGetFeeCycle.php';

   if(mod == 'A'){   
     form = document.addVechileRouteAllocation;
	
   }
   else{
     form = document.EditVehicleRouteAllocation;
      }
        
   form.feeCycleId.length = null;    
   addOption(form.feeCycleId, '', 'Select'); 

new Ajax.Request(url,
   {
     method:'post',
     asynchronous: false,
     parameters: {
         classId: id 
     },
     onCreate: function() {
         showWaitDialog(true); 
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         j = eval('('+transport.responseText+')'); 
         form.feeCycleId.length = null;    
         addOption(form.feeCycleId, '', 'Select'); 
         len = j.length;
         ttActiveCycle=''; 
         for(i=0;i<len;i++) {
           str = j[i].feeCycleId+"!~!"+j[i].fromDate+"!~!"+j[i].toDate+"!~!"+j[i].status;  
           addOption(form.feeCycleId, str, j[i].cycleName1);
           if(j[i].status==1) {
             ttActiveCycle = i+1;  
           }  
         }
         if(ttActiveCycle!='') {
           form.feeCycleId.selectedIndex=ttActiveCycle;  
           getFeeCycleDate(form.feeCycleId.value,mod,'Cycle'); 
         }  
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   }); 
    
}


function getFeeCycleDate(val,mod,type) {
   
   ret = val.split("!~!");   
   if(type=='Cycle') {
       if(mod=='A') {
         document.addVechileRouteAllocation.validFrom.value=ret[1];  
         document.addVechileRouteAllocation.validTo.value=ret[2];  

	
       } 
       else {
         document.EditVehicleRouteAllocation.editValidFrom.value=ret[1];  
         document.EditVehicleRouteAllocation.editValidTo.value=ret[2];  

       }
       return false;
   }
   
   if(type=='Rent') {
       if(mod=='A') {
         document.addVechileRouteAllocation.transportCharges.value=ret[1];  
         document.addVechileRouteAllocation.assignTransportCharges.value=ret[1];  
       } 
       else {
         document.EditVehicleRouteAllocation.transportCharges.value=ret[1];  
         document.EditVehicleRouteAllocation.assignTransportCharges.value=ret[1];  
       }
       return false;
   }
}

function printFeeReceipt(payFee,feeClassId, studentId, currentClassId){
    if(isEmpty(payFee)){
        payFee = '';
    }
    queryString = "fee="+payFee+"&feeClassId="+feeClassId+"&studentId="+studentId+"&currentClassId="+currentClassId;
    window.open("<?php echo UI_HTTP_PATH;?>/Fee/studentFeesPrint.php?"+queryString,"StuidentFeeReceiptPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

function getShowDetail() {
	
   document.getElementById("showhideSeats").style.display='';
   document.getElementById("lblMsg").innerHTML="Please Click to Hide Advance Search";
   document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-down.gif";  
   if(valShow==0) {
     document.getElementById("showhideSeats").style.display='none';
     document.getElementById("lblMsg").innerHTML="Please Click to Show Advance Search"; 
     document.getElementById("showInfo").src = "<?php echo IMG_HTTP_PATH;?>/arrow-up.gif"; 
     valShow=1;
   }
   else {
     valShow=0;  
   }
}



function printReport() {
    
    params = generateQueryString('searchForm');
    var qstr=params+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/vehicleRouteAllocationPrint.php?'+qstr; 
    window.open(path,"VehicleRouteAllocationReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    //var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    params = generateQueryString('searchForm');
    var qstr=params+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/vehicleRouteAllocationCSV.php?'+qstr;
    window.location = path;
}




function checkAll() {
    formx = document.searchForm;
    for(var i=1;i<formx.length;i++){
      if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
        if(formx.checkbox2.checked){  
          formx.elements[i].checked=true;
        }
        else {
          formx.elements[i].checked=false;  
        }
      }
    }
}


function generatePass() {
   
   str = ''; 
   formx = document.searchForm;
   for(var i=1;i<formx.length;i++){
      if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" && formx.elements[i].checked==true) {
         if(str!='') {
           str += ',';   
         } 
         str += formx.elements[i].value; 
      }
   }
    
   if(str==0)    {
     alert("Please select atleast one record!");
     return false;
   }
   
   qry = 'type=B&ids='+str+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   path='<?php echo UI_HTTP_PATH;?>/generatePass.php?'+qry;
   window.open(path,"BusPassPrint","status=1,menubar=1,scrollbars=1, width=900");
}

function resetForm() {
   document.getElementById('results').innerHTML='';   
   document.getElementById('printRow').style.display='none';
   document.getElementById('printRow2').style.display='';
   document.getElementById('printRowNote').style.display='';
}



function getShowCharges(isType) {
  
  document.getElementById('rdCharges').style.display='none';  
  if(isType=='1') {
    document.getElementById('rdCharges').style.display='';  
    document.addEmployeeVechileRouteAllocation.transportCharges.focus();  
  }  
}


function getSearchRouteName(){
   
    var form = document.searchForm;
    form.searchRouteName.length = null;       
    addOption(form.searchRouteName, '', 'All'); 
      
    var url = '<?php echo HTTP_LIB_PATH;?>/VehicleRouteAllocation/ajaxSearchRouteNames.php';
    new Ajax.Request(url,
    {
        method:'post',
        asynchronous :false,
        onCreate: function(){
          showWaitDialog(true);
        },
        onSuccess: function(transport){ 
            hideWaitDialog(true);
    
            var j = eval('(' + transport.responseText + ')');
            len = j.length;
            form.searchRouteName.length = null;
            addOption(form.searchRouteName, '', 'All');
            for(i=0;i<len;i++) {
              addOption(form.searchRouteName, j[i].busRouteId, j[i].routeName);    
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}
</script>
</head>
<body>
<?php 
	require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/VehicleRouteAllocation/vehicleRouteAllocationContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

