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
define('MODULE','RoomAllocation');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
global $sessionHandler;  
$hostelSecurityAmount = $sessionHandler->getSessionVariable('HOSTEL_SECURITY_AMOUNT'); 
$userRoleId = $sessionHandler->getSessionVariable('RoleId'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Room Allocation Master </title>
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

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),  
				new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"checkAll();\">','width="2%"','align=\"left\"',false),    
                               new Array('studentName','Name ','width="10%"','',true), 
                               new Array('rollNo','Roll No.','width="10%"','',true), 
                               new Array('className','Class','width="15%"','',true), 
                               new Array('hostelName','Hostel', 'width="15%"','',true),
                               new Array('hostelCharges', 'Charges','width="8%" align="right"','align="right"',true), 
                               new Array('securityAmount','Security ','width="10%" align="right"','align="right"',true),  
			       new Array('ledgerDebit','Ledger Debit ','width="10%" align="right"','align="right"',true),  
				new Array('ledgerCredit','Ledger Credit ','width="10%" align="right"','align="right"',true),  
                               new Array('totalAmount',  'Total Hostel Fee','width="10%" align="center"','align="center"',false), 
			      new Array('paidAmount',  'Hostel Fee Paid','width="10%" align="center"','align="center"',false), 
                               new Array('balance',  'Balance','width="10%" align="center"','align="center"',false), 
                               //new Array('transportAmount',  'Transport Fee Paid','width="10%" align="center"','align="center"',false), 
                               //new Array('acdemicAmount',  'Academic Fee Paid','width="10%" align="center"','align="center"',false), 
                               new Array('printReceipt',  'Bank Challan','width="10%" align="center"','align="center"',false), 
                               new Array('action','Action','width="7%"','align="center"',false));


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxRoomAllocationList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddRoomAllocation';   
editFormName   = 'EditRoomAllocation';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteRoomAllocation';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';

ttHostelCharges  ='';
ttHostelSecurityCharges  ='';
ttHostelSecurity ='';
ttHostelRoomTypeId = '';
ttHostelRoomId = '';
ttHostelId = '';
ttClassId = '';
ttFeeCycleId = '';
ttActiveCycle = '';
// ajax search results ---end ///
valShow = '0';
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function getShowList() {
   
    page=1;
   
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
    populateValues(id,dv,w,h);   
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
        new Array("classId","<?php echo SELECT_CLASS;?>"),
        new Array("hostel","<?php echo SELECT_HOSTEL;?>"),
        new Array("roomType","Select Room type"),
        new Array("room","<?php echo SELECT_ROOM;?>"),
        new Array("feeCycleId","Select Fee Cycle"),
        new Array("hostelCharges","Enter Hostel Charges")
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
        if(document.AddRoomAllocation.studentId.value==''){
           messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
           document.AddRoomAllocation.rollRegNo.focus();
           return false;
        }
        
        if(document.AddRoomAllocation.pChkOut1.value==''){
           messageBox("Enter expected date of checkout");
           document.AddRoomAllocation.pChkOut1.focus();
           return false;
        }
        
        if(!dateDifference(document.AddRoomAllocation.chkIn1.value,document.AddRoomAllocation.pChkOut1.value,'-')){
           messageBox("Expected date of checkout cannot be less than checkin date");
           document.AddRoomAllocation.pChkOut1.focus();
           return false;
        }
       
        if(!isDecimal(trim(document.AddRoomAllocation.hostelCharges.value))){
          messageBox("Hostel Charges Should be Numeric");
          document.AddRoomAllocation.hostelCharges.focus();
          return false;
        }
       
        if(document.AddRoomAllocation.securityMode.value=='0') {
           if(trim(document.AddRoomAllocation.securityAmount.value) == '') {
              messageBox("Enter Security Amount");
              document.AddRoomAllocation.securityAmount.focus();
              return false;
           }     
           if(!isDecimal(trim(document.AddRoomAllocation.securityAmount.value))){
             messageBox("Security Amount Should be Numeric");
             document.AddRoomAllocation.securityAmount.focus();
             return false;
           }
        }
        
        hostelCharges = parseFloat(trim(document.AddRoomAllocation.hostelCharges.value),10);
        assignHostelCharges = parseFloat(trim(document.AddRoomAllocation.assignHostelCharges.value),10);
        
        if(hostelCharges!=assignHostelCharges) {               
          if(trim(document.AddRoomAllocation.comments.value)=='') {
            messageBox("Please enter comments for changing the hostel charges");
            document.AddRoomAllocation.comments.focus();
            return false;   
          }  
        }
        addRoomAllocation();
        return false;
    }
    else if(act=='Edit') {
        if(document.EditRoomAllocation.studentId.value==''){
            messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
            document.EditRoomAllocation.rollRegNo.focus();
            return false;
        }
        if(document.EditRoomAllocation.chkOut2.value!=''){
            if(!dateDifference(document.EditRoomAllocation.chkIn2.value,document.EditRoomAllocation.chkOut2.value,'-')){
                messageBox("<?php echo CHECK_OUT_DATE_VALIDATION; ?>");
                document.EditRoomAllocation.chkOut2.focus();
                return false;
            }
        }
        
        if(!isDecimal(trim(document.EditRoomAllocation.hostelCharges.value))){
          messageBox("Hostel Charges Should be Numeric");
          document.EditRoomAllocation.hostelCharges.focus();
          return false;
        }
       
        if(document.EditRoomAllocation.securityMode.value=='0') {
           if(trim(document.EditRoomAllocation.securityAmount.value) == '') {
              messageBox("Enter Security Amount");
              document.EditRoomAllocation.securityAmount.focus();
              return false;
           }     
           if(!isDecimal(trim(document.EditRoomAllocation.securityAmount.value))){
             messageBox("Security Amount Should be Numeric");
             document.EditRoomAllocation.securityAmount.focus();
             return false;
           }
        }
        
        hostelCharges = parseFloat(trim(document.EditRoomAllocation.hostelCharges.value),10);
        assignHostelCharges = parseFloat(trim(document.EditRoomAllocation.assignHostelCharges.value),10);
        
        if(hostelCharges!=assignHostelCharges) {               
          if(trim(document.EditRoomAllocation.comments.value)=='') {
            messageBox("Please enter comments for changing the hostel charges");
            document.EditRoomAllocation.comments.focus();
            return false;   
          }  
        }

	    if("<?php echo $userRoleId; ?>" != '23' ) {
          if(ttHostelCharges != hostelCharges ) {
	        messageBox("Changes in the Hostel Charges and Security Amount can only be done by ACCOUNTS DEPARTMENT");
	        return false;
	      }
          
          if(ttHostelSecurityCharges != trim(document.EditRoomAllocation.securityAmount.value) ) {
            messageBox("Changes in the Hostel Charges and Security Amount can only be done by ACCOUNTS DEPARTMENT");
            return false;
          }
          
          status =0;   
          if(document.EditRoomAllocation.securityStatus.checked) {      
            status = 1;
          }
          
          if(ttHostelSecurity!=status){
             messageBox("Changes in the Security Status can only be done by ACCOUNTS DEPARTMENT")  ;
             return false;
          }
	    }
        editRoomAllocation();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addRoomAllocation() {
	 
        if(false===confirm("Are you sure you want to allocate the room")) {
          return false;
        }
        
        val = document.AddRoomAllocation.feeCycleId.value;
        ret = val.split("!~!");
	    feeCycleId = ret[0];
        
        val = document.AddRoomAllocation.room.value;
        ret = val.split("!~!");
        roomId = ret[0];
        
        
        if(document.AddRoomAllocation.securityMode.value=='1') {
           status=0; 
           securityAmount=0;
        }
        else {
           securityAmount = trim(document.AddRoomAllocation.securityAmount.value); 
           status = '';
           if(document.AddRoomAllocation.securityStatus.checked) {      
             status = 1;
           }
           else {
             status =0;
           }
        }
    
         url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxAddRoomAllocation.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 studentId  : (document.AddRoomAllocation.studentId.value), 
                 classId    : (document.AddRoomAllocation.classId.value), 
                 roomId     : roomId, 
                 hostelId   : (document.AddRoomAllocation.hostel.value),
                 chkIn      : (document.AddRoomAllocation.chkIn1.value),
                 pChkOut    : (document.AddRoomAllocation.pChkOut1.value),
                 securityAmount : securityAmount,
                 hostelCharges : trim(document.AddRoomAllocation.hostelCharges.value),
                 feeCycleId : feeCycleId,
                 securityStatus : status,
                 comments:   trim(document.AddRoomAllocation.comments.value)      
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     flag = true;
                     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                         blankValues();
                     }
                     else{
                        hiddenFloatingDiv('AddRoomAllocation'); 
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     }
                 }   
                 else if("<?php echo HOSTEL_STUDENT_ALREADY_EXIST;?>" == trim(transport.responseText)){
                     messageBox("<?php echo HOSTEL_STUDENT_ALREADY_EXIST ;?>"); 
                     document.AddRoomAllocation.rollRegNo.focus();
                 }
                 else if("<?php echo ROOM_CAPACITY_VALIDATION;?>" == trim(transport.responseText)){
                     messageBox("<?php echo ROOM_CAPACITY_VALIDATION ;?>"); 
                     document.AddRoomAllocation.room.focus();
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
function deleteRoomAllocation(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxDeleteRoomAllocation.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 hostelStudentId: id
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   
   document.AddRoomAllocation.securityAmount.value = "<?php echo $hostelSecurityAmount; ?>";
   document.AddRoomAllocation.reset();
   document.getElementById('studentName1').innerHTML = '';
   document.getElementById('roomTypeFacility1').innerHTML= '';
   document.AddRoomAllocation.hostelCharges.value='';
   document.getElementById('showPrevious1').style.display='none';
   document.AddRoomAllocation.comments.value = '';
   document.AddRoomAllocation.securityMode.value='1';
   document.getElementById('lblSecurity1').style.display='none';
   document.getElementById('lblSecurity2').style.display='none';      
   document.AddRoomAllocation.rollRegNo.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editRoomAllocation() {

	
	    
        if(false===confirm("Are you sure you want to update room allocation")) {
          return false;
        }
        
        val = document.EditRoomAllocation.feeCycleId.value;
        ret = val.split("!~!");
        feeCycleId = ret[0];
        
        val = document.EditRoomAllocation.room.value;
        ret = val.split("!~!");
        roomId = ret[0];
        
       
        if(document.EditRoomAllocation.securityMode.value=='1') {
           status=0; 
           securityAmount=0;
        }
        else {
           securityAmount = trim(document.EditRoomAllocation.securityAmount.value); 
           status = '';
           if(document.EditRoomAllocation.securityStatus.checked) {      
             status = 1;
            
           }
           else {
             status =0;
               
           }
        }
        
        var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxEditRoomAllocation.php';
        new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 hostelStudentId : (document.EditRoomAllocation.hostelStudentId.value), 
                 studentId       : (document.EditRoomAllocation.studentId.value), 
                 classId         : (document.EditRoomAllocation.classId.value), 
                 roomId          : roomId, 
                 hostelId        : (document.EditRoomAllocation.hostel.value),
                 chkIn           : (document.EditRoomAllocation.chkIn2.value),
                 chkOut          : (document.EditRoomAllocation.chkOut2.value),
                 //pChkOut         : (document.EditRoomAllocation.pChkOut2.value),
                 securityAmount  : securityAmount,
                 hostelCharges  : trim(document.EditRoomAllocation.hostelCharges.value),
                 feeCycleId : feeCycleId,
                 securityStatus  : status,
                 comments:   trim(document.EditRoomAllocation.comments.value)      
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
			
		
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditRoomAllocation');
                          messageBox(trim(transport.responseText));
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo ROOM_CAPACITY_VALIDATION;?>" == trim(transport.responseText)){
                         messageBox("<?php echo ROOM_CAPACITY_VALIDATION ;?>"); 
                         document.EditRoomAllocation.room.focus();
                     }
				
                     else {
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
function populateValues(id,dv,w,h) {
       
       ttHostelCharges  ='';
       ttHostelSecurityCharges  ='';
        ttHostelSecurity ='';
       ttHostelRoomTypeId = '';
       ttHostelRoomId = '';
       ttHostelId = '';
       ttClassId = '';
       ttFeeCycleId = '';
                   
         var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetRoomAllocationValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 hostelStudentId: id
             },
             asynchronous:false,  
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.EditRoomAllocation.reset();
                    document.getElementById('studentName2').innerHTML     = '';
                    document.getElementById('roomTypeFacility2').innerHTML='';
                    if(trim(transport.responseText)=="<?php echo INVALID_ROOM_ALLOCATION; ?>") {
                        messageBox("<?php echo INVALID_ROOM_ALLOCATION; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                    }
                    else if(trim(transport.responseText)=="<?php echo ROOM_ALLOCATION_EDIT_RESTRICTION; ?>"){
                       messageBox("<?php echo ROOM_ALLOCATION_EDIT_RESTRICTION; ?>");
                       return false;
                    }
                   displayWindow(dv,w,h);
                   var j = eval('('+transport.responseText+')');
                   var ret='';
                   if(j.regNo=='---'){
                       ret=j.rollNo;
                   }
                   else{
                       ret=j.regNo;
                   }


                   document.EditRoomAllocation.rollRegNo.value           = ret;
                   document.getElementById('studentName2').innerHTML     = trim(j.studentName);
                   document.EditRoomAllocation.hostel.value              = j.hostelId;
                   document.EditRoomAllocation.chkIn2.value       = j.dateOfCheckIn;
                   document.EditRoomAllocation.chkOut2.value          = j.dateOfCheckOut;
                   document.EditRoomAllocation.studentId.value       = j.studentId;
                   document.EditRoomAllocation.hostelStudentId.value     = j.hostelStudentId;
                   document.EditRoomAllocation.securityAmount.value     = j.securityAmount;
                   document.EditRoomAllocation.hostelCharges.value     = j.hostelCharges;
                   document.EditRoomAllocation.comments.value     = j.comments;
                   document.EditRoomAllocation.assignHostelCharges.value  = j.hostelCharges;      
                   
                   ttDateOfCheckIn = j.dateOfCheckIn;
                   ttDateOfCheckOut = j.dateOfCheckOut;
                   
                   ttHostelCharges  = j.hostelCharges;
                   ttHostelSecurityCharges  = j.securityAmount;
                
                   ttHostelRoomTypeId = j.hostelRoomTypeId;
                   ttHostelRoomId = j.hostelRoomId;
                   ttHostelId = j.hostelId;
                   ttClassId = j.classId; 
                   ttFeeCycleId = j.feeCycleId;
                   
                  ttHostelSecurity=1;
                   document.EditRoomAllocation.securityStatus.checked=true;  
                   if(j.securityStatus=='0') {
                     document.EditRoomAllocation.securityStatus.checked=false; 
                     ttHostelSecurity=0;
                   }
                   getFeeCycle(ttClassId,'E');
                   
                   getStudentData(ret,'Edit');
                   document.EditRoomAllocation.classId.value = ttClassId;
                   
                   getRoomTypes(ttHostelId,'Edit');
                   document.EditRoomAllocation.roomType.value = ttHostelRoomTypeId;
                   
                   getRooms(ttHostelRoomTypeId,'Edit');
                   var len=document.EditRoomAllocation.room.length;
                   for(var i=0; i<len; i++) {
                      ret = trim(document.EditRoomAllocation.room.options[i].value).split("!~!");
                      if(ret[0]==ttHostelRoomId) {
                        document.EditRoomAllocation.room.options[i].selected = true;  
                        break;

                      }
                   }
                   document.EditRoomAllocation.chkIn2.value  = ttDateOfCheckIn;
                   document.EditRoomAllocation.chkOut2.value = ttDateOfCheckOut;
                  
                   document.EditRoomAllocation.hostel.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getStudentData(value,act){
    
    document.getElementById('previousHostelFacilityDiv').innerHTML = '';  
    if(act=='Add'){
        document.getElementById('showPrevious1').style.display='none';
     
        document.getElementById('studentName1').innerHTML     = '';
        document.AddRoomAllocation.studentId.value='';
        
        document.AddRoomAllocation.classId.length = null;
        addOption(document.AddRoomAllocation.classId, '', 'Select');
    }
    else{
        document.getElementById('showPrevious2').style.display='none';
        
        document.getElementById('studentName2').innerHTML     = '';
        document.EditRoomAllocation.studentId.value='';
        
        document.EditRoomAllocation.classId.length = null;
        addOption(document.EditRoomAllocation.classId, '', 'Select');
    }
    
    if(trim(value)==''){
        return false;
    }
    
    //var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetStudentData.php';
    var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetStudentClassData.php';
    new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,  
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
                   if(act=='Add') {
                      j0 = eval(ret[0]);  
                      document.AddRoomAllocation.studentId.value=j0[0].studentId;
                      document.getElementById('studentName1').innerHTML=trim(j0[0].studentName);
                      
                      document.AddRoomAllocation.classId.length = null; 
                      addOption(document.AddRoomAllocation.classId, '', 'Select');        
                         
                      j0 = eval(ret[1]);
                      for(i=0;i<j0.length;i++) { 
                        str = j0[i].className; 
                        addOption(document.AddRoomAllocation.classId, j0[i].classId, str);
                      }
                      
                      document.AddRoomAllocation.securityMode.value='1';
                      document.getElementById('lblSecurity1').style.display='none';
                      document.getElementById('lblSecurity2').style.display='none';
                      if(act=='Add'){  
                        document.AddRoomAllocation.securityAmount.value = "<?php echo $hostelSecurityAmount; ?>";
                      }
                      if(trim(ret[3])=='0') {
                        document.getElementById('lblSecurity1').style.display='';
                        document.getElementById('lblSecurity2').style.display='';
                        document.AddRoomAllocation.securityMode.value='0'; 
                      }
                   }
                   else {
                       j0 = eval(ret[0]); 
                       document.EditRoomAllocation.studentId.value=j0[0].studentId;
                       document.getElementById('studentName2').innerHTML=trim(j0[0].studentName);
                             
                       document.EditRoomAllocation.classId.length = null; 
                       addOption(document.EditRoomAllocation.classId, '', 'Select');        
                             
                       j0 = eval(ret[1]);
                       for(i=0;i<j0.length;i++) { 
                         addOption(document.EditRoomAllocation.classId, j0[i].classId, j0[i].className);
                       }
                   }
                   if(act=='Add') {  
                     document.getElementById('showPrevious1').style.display='none';
                   }
                   else {
                     document.getElementById('showPrevious2').style.display='none';
                   }
                      document.getElementById('previousHostelFacilityDiv').innerHTML = '';
                      j0 = eval(ret[2]);
                      if(j0.length >0 ) {
                        tableData  = "<b><br>&nbsp;<u>"+j0[0].studentName+" ("+j0[0].rollNo+")</u></b><br><br>"; 
                        tableData += "<table width='100%' border='0' cellspacing='2' cellpadding='0'>";
                        tableData += "<tr class='rowheading'>";
                        tableData += "<td class='searchhead_text' width='5%'>#</td><td class='searchhead_text' width='20%'>Class</td>";
                        tableData += "<td class='searchhead_text' width='17%'>Hostel</td><td class='searchhead_text' width='18%'>Room</td>";
                        tableData += "<td class='searchhead_text' width='10%' align='center'>Check In</td><td align='center' class='searchhead_text' width='10%'>Check Out</td>";
                        tableData += "<td class='searchhead_text' width='10%' align='right'>Rent</td><td  align='right' class='searchhead_text' width='10%'>Security<br>Amount</td>";
                        tableData += "</tr>";            
                        for(i=0;i<j0.length;i++) { 
                          bg = bg =='trow0' ? 'trow1' : 'trow0';    
                          tableData += "<tr class='bg'>";            
                            tableData += "<td class='padding_top'>"+(i+1)+"</td><td class='padding_top'>"+j0[i].className+"</td>";
                            tableData += "<td class='padding_top'>"+j0[i].hostelCode+"</td><td class='padding_top'>"+j0[i].roomName+"</td>";
                            tableData += "<td class='padding_top' align='center'>"+j0[i].checkInDate+"</td><td class='padding_top' align='center'>"+j0[i].checkOutDate+"</td>";
                            tableData += "<td class='padding_top'  align='right'>"+j0[i].hostelCharges+"</td><td  align='right' class='padding_top'>"+j0[i].securityAmount+"</td>";
                          tableData += "</tr>";            
                        }
                        tableData += "</table>";            
                        document.getElementById('previousHostelFacilityDiv').innerHTML = tableData; 
                        if(act=='Add') {  
                          document.getElementById('showPrevious1').style.display='';
                        }
                        else {
                          document.getElementById('showPrevious2').style.display='';
                        }
                      }
                 }
                 else{
                     messageBox("<?php echo STUDENT_NOT_EXISTS; ?>");
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>")}
           }); 
    
}

function getStatus() {
  displayWindow('previousHostelFacility','550','550');
}


function getRoomTypes(val,frm)
{
   var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetRoomTypes.php';
   if(frm=="Add"){
            document.AddRoomAllocation.roomType.options.length=0;
            var objOption = new Option("Select","");
            document.AddRoomAllocation.roomType.options.add(objOption); 
            document.AddRoomAllocation.room.options.length=0;
            var objOption = new Option("Select","");

            document.AddRoomAllocation.room.options.add(objOption); 
   }
   else{    
            
            document.EditRoomAllocation.roomType.options.length=0;
            var objOption = new Option("Select","");
            document.EditRoomAllocation.roomType.options.add(objOption); 
            document.EditRoomAllocation.room.options.length=0;
            var objOption = new Option("Select","");          
            document.EditRoomAllocation.room.options.add(objOption);
   }
   
   if(val==''){
       return false;
   }
   
new Ajax.Request(url,
           {
             method:'post',
             asynchronous: false,
             parameters: {
                 hostelId: val
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                   
                     hideWaitDialog(true);
                     j = eval('('+transport.responseText+')'); 
                     for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].roomType,j[c].hostelRoomTypeId); 
                         if(frm=="Add"){
                             document.AddRoomAllocation.roomType.options.add(objOption);   
                          }
                         else{
                             document.EditRoomAllocation.roomType.options.add(objOption);
                          }
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function getRooms(val,frm)
{
   var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetRooms.php';
   if(frm=="Add"){
     document.getElementById('roomTypeFacility1').innerHTML='';
     document.AddRoomAllocation.room.options.length=0;
     var objOption = new Option("Select","");
     document.AddRoomAllocation.room.options.add(objOption);
     var hostelId=document.AddRoomAllocation.hostel.value;
     if(document.AddRoomAllocation.classId.value=='') {
        return false; 
     }
     classId = document.AddRoomAllocation.classId.value;
   }
   else{    
     document.getElementById('roomTypeFacility2').innerHTML='';
     document.EditRoomAllocation.room.options.length=0;
     var objOption = new Option("Select","");          
     document.EditRoomAllocation.room.options.add(objOption);
     var hostelId=document.EditRoomAllocation.hostel.value;
     if(document.EditRoomAllocation.classId.value=='') {
        return false; 
     }
     classId = document.EditRoomAllocation.classId.value;
   }
   
   if(val==''){
       return false;
   }
   
new Ajax.Request(url,
           {
             method:'post',
             asynchronous: false,
             parameters: {
                 hostelRoomTypeId: val,
                 hostelId : hostelId ,
                 classId: classId
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if(trim(transport.responseText)=='Hostel Fee not defined') {
                    messageBox("Hostel Fee not defined"); 
                    return false; 
                 }
                 
                 var ret=trim(transport.responseText).split('!~!~!');
                 if(ret.length>0 && ret[0]!=''){
                 var j = eval('('+ret[0]+')');
                 for(var c=0;c<j.length;c++){
                    str = j[c].hostelRoomId+"!~!"+ j[c].roomRent; 
                    var objOption = new Option(j[c].roomName,str); 
                    if(frm=="Add"){
                      document.AddRoomAllocation.room.options.add(objOption);   
                    }
                    else{
                      document.EditRoomAllocation.room.options.add(objOption);
                    }
                  }
                  document.EditRoomAllocation.room.value  = ttHostelRoomId;
               }
               if(ret.length>1 && ret[1]!=''){
                   var jj = eval('('+ret[1]+')');
                   var str='&nbsp;<b>Attached Bath: '+jj.attachedBath+', Air Conditioned: '+jj.airConditioned+', Internet Facility: '+jj.internetFacility+'</b>';
                   if(frm=="Add"){
                     document.getElementById('roomTypeFacility1').innerHTML=str;
                   }
                   else{
                     document.getElementById('roomTypeFacility2').innerHTML=str;
                   }
               }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function getRoomTypesForHostel(val)
{
   var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetRoomTypes.php';
   document.form2.roomType.options.length=1;
   
   if(val=='-1' || val==''){
       return false;
   }
   
new Ajax.Request(url,
           {
             method:'post',
             asynchronous: false,
             parameters: {
                 hostelId: val
             },
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                   
                     hideWaitDialog(true);
                     j = eval('('+transport.responseText+')'); 
                     for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].roomType,j[c].hostelRoomTypeId); 
                        document.form2.roomType.options.add(objOption);   
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function getPossibleVacantRoomReport(){
  
  var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxPossibleVacantRoomList.php';
  if(!dateDifference(serverDate,document.getElementById('toDate').value,'-')){
      messageBox("Date can not be less than current date");
      document.getElementById('toDate').focus();
      return false;
  }
  
  var hostelId=document.form2.hostelId.value;
  var roomTypeId=document.form2.roomType.value;
  var toDate=document.getElementById('toDate').value;
  
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false),
                        new Array('hostelName','Hostel','width="15%" align="left"',true),
                        new Array('roomName','Room','width="10%" align="left"',true),
                        new Array('roomType','Room Type','width="10%" align="left"',true),
                        new Array('roomCapacity','Capacity','width="8%" align="right"',true),
                        new Array('occupied','Occupied','width="8%" align="right"',true),
                        new Array('vacant','Free','width="8%" align="right"',true),
                        new Array('actionString','Action','width="5%" align="right"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','hostelName','ASC','results','','',true,'listObj',tableColumns,'','','&hostelId='+hostelId+'&roomType='+roomTypeId+'&toDate='+toDate);
 sendRequest(url, listObj, '');
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

function popUpdateAllocationDiv(hostelId,roomId) {
    if(!dateDifference(serverDate,document.getElementById('toDate').value,'-')){
      messageBox("Date can not be less than current date");
      document.getElementById('toDate').focus();
      return false;
  }
  
  var toDate=document.getElementById('toDate').value;
  
    var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxPossibleVacantRoomOccupants.php';
    var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false),
                        new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%" align=\"left\"',false), 
                        new Array('studentName','Student','width="20%" align="left"',true),
                        new Array('rollNo','Roll No.','width="10%" align="left"',true),
                        new Array('className','Class','width="25%" align="left"',true),
                        new Array('dateOfCheckIn','Check in','width="15%" align="center"',true)
                       );
  
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','students','ASC','updateAllocationDiv','','',true,'listObj2',tableColumns,'','','&hostelId='+hostelId+'&roomId='+roomId+'&toDate='+toDate);
 sendRequest(url, listObj2, '',false);
 displayWindow('UpdateRoomAllocation',315,250);
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

function validateOccupantForm(){
   
   var c1 = document.getElementById('updateAllocationDiv').getElementsByTagName('INPUT');
   var len=c1.length;
   var fl=0;
   var studentString='';
   for(var i=0;i<len;i++){
        if (c1[i].type.toUpperCase()=='CHECKBOX' && c1[i].name=='studentChk'){
            if(c1[i].checked==true){
                fl=1;;
                if(studentString!=''){
                    studentString +='!';
                }
                studentString +=c1[i].value;
            }
        }
   }
   
   if(!fl){
       messageBox("Please select at least one checkbox");
       return false;
   }
   
   
  var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxCheckoutOccupants.php';
  new Ajax.Request(url,
         {
             method:'post',
             parameters: {
                 studentString : studentString
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                        hiddenFloatingDiv('UpdateRoomAllocation');
                        getPossibleVacantRoomReport();
                     }   
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

window.onload=function(){
   valShow=1;           
   getShowDetail();
   getRoomData();
   //getShowSearch(5);   
   //var roll = document.getElementById("rollRegNo");
   //autoSuggest(roll);
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

function getFeeCycle(id,mod) {
   
   var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetFeeCycle.php';

   if(mod=='A') {
     form = document.AddRoomAllocation;  
   }
   else {
     form = document.EditRoomAllocation;  
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
         
         ttActiveCycle='';
         len = j.length;
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
         document.AddRoomAllocation.chkIn1.value=ret[1];  
         document.AddRoomAllocation.pChkOut1.value=ret[2];  
       } 
       else {
         document.EditRoomAllocation.chkIn2.value=ret[1];  
         document.EditRoomAllocation.chkOut2.value=ret[2];  
       }
       return false;
   }
   
   if(type=='Rent') {
       if(mod=='A') {
         document.AddRoomAllocation.hostelCharges.value=ret[1];  
         document.AddRoomAllocation.assignHostelCharges.value=ret[1];  
       } 
       else {
         document.EditRoomAllocation.hostelCharges.value=ret[1];  
         document.EditRoomAllocation.assignHostelCharges.value=ret[1];  
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
    path='<?php echo UI_HTTP_PATH;?>/roomAllocationPrint.php?'+qstr;  
    window.open(path,"RoomAllocationReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    params = generateQueryString('searchForm');
    var qstr=params+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    path='<?php echo UI_HTTP_PATH;?>/roomAllocationCSV.php?'+qstr;
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
    
   qry = 'type=H&ids='+str+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   path='<?php echo UI_HTTP_PATH;?>/generatePass.php?'+qry;
   window.open(path,"BusPassPrint","status=1,menubar=1,scrollbars=1, width=900");
}

function resetForm() {
   document.getElementById('results').innerHTML='';   
   document.getElementById('printRow').style.display='none';
   document.getElementById('printRow2').style.display='';
   document.getElementById('printRowNote').style.display='';
}
function getRoomData(){
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Room/ajaxGetRoomList.php';  
    
    form = document.searchForm;
   
    document.searchForm.searchRoom.length = null; 
    addOption(document.searchForm.searchRoom, '', 'All');
    
    pars = 'hostelId='+document.searchForm.searchHostel.value;
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;

                document.searchForm.searchRoom.length = null;
                addOption(document.searchForm.searchRoom, '', 'All'); 
                for(i=0;i<len;i++) { 
                  addOption(document.searchForm.searchRoom, j[i].hostelRoomId, j[i].hostelRoomName);
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
	require_once(TEMPLATES_PATH . "/Room/roomAllocationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: roomAllocation.php $ 
//
//*****************  Version 2  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 31/08/09   Time: 13:33
//Created in $/LeapCC/Interface
//Added files for "Room Allocation Master"
//
//*****************  Version 2  *****************
//User: Administrator Date: 14/07/09   Time: 11:59
//Updated in $/Leap/Source/Interface
//Added "Room Type" and "Room Type Facilities" in "Room Allocation
//Master"
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 23/04/09   Time: 17:57
//Created in $/Leap/Source/Interface
//Created "Room Allocation Master"
?>
