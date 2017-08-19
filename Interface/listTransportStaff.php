<?php
//--------------------------------------------------------------------------------------------------
//  THIS FILE SHOWS A LIST OF BUSSTOP ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TransportStaffMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/TransportStuff/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Transport Staff Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
    new Array('srNo','#','width="2%"','',false),
    new Array('name','Staff','width="10%"','',true),
    new Array('staffCode','Code','width="8%"','',true),
    new Array('joiningDate','Joining Date','width="8%"','align="center"',true),
    new Array('staffType','Type','width="8%"','align="left"',true),
    new Array('verificationDone','Verification Done','width="7%"','align="center"',true), 
    new Array('dlNo','License','width="8%"','',true) , 
    //new Array('dlIssuingAuthority','Authority','width="10%"','align="left"',true), 
    new Array('dlExpiryDate','Expiry Date','width="8%"','align="center"',true), 
	new Array('medicalExaminationDate','Med. Exam. Date','width="8%"','align="center"',true), 
    new Array('action','Action','width="2%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTransportStaff';   
editFormName   = 'EditTransportStaff';
winLayerWidth  = 360; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTransportStaff';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {
   
	  var fieldsArray = new Array(
      new Array("staffName","<?php echo ENTER_STAFF_NAME; ?>"),
      new Array("staffCode","<?php echo ENTER_STAFF_CODE; ?>"),
      new Array("dlNo","<?php echo ENTER_DRIVING_LICENSE; ?>"),
      new Array("dlAuthority","<?php echo ENTER_DRIVING_LICENSE_AUTHORITY; ?>"),
      new Array("staffType","<?php echo SELECT_STAFF_TYPE; ?>")
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
            if(!isAlphabetCharacters(trim(eval("frm."+(fieldsArray[i][0])+".value"))) && fieldsArray[i][0]=='staffName' ){
                messageBox("<?php echo ENTER_ALPHABETS; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='staffName' ) {
                messageBox("<?php echo STAFF_NAME_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<2 && fieldsArray[i][0]=='staffCode' ) {
                messageBox("<?php echo STAFF_CODE_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<4 && fieldsArray[i][0]=='dlNo' ) {
                messageBox("<?php echo DL_LENGTH; ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
         
        }
     
    }
    
    if(act=='Add') {
      if(!dateDifference(document.getElementById('join1').value,serverDate,'-')){
         messageBox("<?php echo JOINING_DATE_VALIDATION; ?>");
         document.getElementById('join1').focus();
         return false; 
      }

	  if(!dateDifference(document.getElementById('issueDate').value,serverDate,'-')){
         messageBox("<?php echo ISSUE_DATE_VALIDATION; ?>");
         document.getElementById('issueDate').focus();
         return false; 
      }
	  if(!dateDifference(document.getElementById('dob').value,serverDate,'-')){
         messageBox("<?php echo DATE_OF_BIRTH_VALIDATION; ?>");
         document.getElementById('dob').focus();
         return false; 
      }

  if(document.AddTransportStaff.leav1.value !='') { 
	  if(!dateDifference(document.getElementById('join1').value,document.AddTransportStaff.leav1.value,'-')){
             messageBox("<?php echo LEAVING_DATE_VALIDATION1; ?>");
             document.AddTransportStaff.leav1.focus();
             return false; 
      }
      if(!dateDifference(document.AddTransportStaff.leav1.value,serverDate,'-')){
         messageBox("<?php echo LEAVING_DATE_VALIDATION2; ?>");
         document.AddTransportStaff.leav1.focus();
         return false; 
      }
}
	  if(!dateDifference(document.getElementById('issueDate').value,document.getElementById('dlExp1').value,'-')){
         messageBox("<?php echo EXPIRY_DATE_VALIDATION; ?>");
         document.AddTransportStaff.dlExp1.focus();
         return false; 
      }
	 if(!dateDifference(document.getElementById('dob').value,document.getElementById('join1').value,'-')){
         messageBox("<?php echo BIRTH_DATE_VALIDATION; ?>");
         document.AddTransportStaff.join1.focus();
         return false; 
      }
	
     
	 /*if(document.AddTransportStaff.inService[1].checked){
        if(document.AddTransportStaff.leav1.value==''){
          messageBox("Select date of leaving");
          document.AddTransportStaff.leav1.focus();
          return false;
        }    
         
	  
     }
     else{
          document.AddTransportStaff.leav1.value='';
     }*/
        AddTransportStaff();
        return false;
    }
    else if(act=='Edit') {
      if(!dateDifference(document.getElementById('join2').value,serverDate,'-')){
         messageBox("<?php echo JOINING_DATE_VALIDATION; ?>");
         document.getElementById('join2').focus();
         return false; 
      }
	  if(!dateDifference(document.getElementById('dob1').value,serverDate,'-')) {
         messageBox("<?php echo DATE_OF_BIRTH_VALIDATION; ?>");
         document.getElementById('dob1').focus();
         return false; 
      }
	  if(!dateDifference(document.getElementById('issueDate1').value,serverDate,'-')){
         messageBox("<?php echo ISSUE_DATE_VALIDATION; ?>");
         document.getElementById('issueDate1').focus();
         return false; 
      }
  if(document.EditTransportStaff.leav2.value !='') { 
	  if(!dateDifference(document.getElementById('join2').value,document.EditTransportStaff.leav2.value,'-')){
         messageBox("<?php echo LEAVING_DATE_VALIDATION1; ?>");
         document.EditTransportStaff.leav2.focus();
         return false; 
      }
      if(!dateDifference(document.EditTransportStaff.leav2.value,serverDate,'-')){
         messageBox("<?php echo LEAVING_DATE_VALIDATION2; ?>");
         document.EditTransportStaff.leav2.focus();
         return false; 
      }
}
	  if(!dateDifference(document.getElementById('issueDate1').value,document.getElementById('dlExp2').value,'-')){
         messageBox("<?php echo EXPIRY_DATE_VALIDATION; ?>");
         document.EditTransportStaff.dlExp2.focus();
         return false; 
      }
	  if(!dateDifference(document.getElementById('dob1').value,document.getElementById('join2').value,'-')){
         messageBox("<?php echo BIRTH_DATE_VALIDATION; ?>");
         document.EditTransportStaff.join2.focus();
         return false; 
      }

     
	 /*if(document.EditTransportStaff.inService[1].checked){
      if(document.EditTransportStaff.leav2.value==''){
          messageBox("Select date of leaving");
          document.EditTransportStaff.leav2.focus();
          return false;
      }   
      
     }
     else{
          document.EditTransportStaff.leav2.value='';
     } */    
        EditTransportStaff();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A BUS
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function AddTransportStaff() {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 staffName:			trim(document.AddTransportStaff.staffName.value),
				 address:			trim(document.AddTransportStaff.address.value),
				 dob:				trim(document.AddTransportStaff.dob.value),
                 staffCode:			trim(document.AddTransportStaff.staffCode.value),
                 dlNo:				trim(document.AddTransportStaff.dlNo.value),
				 issueDate:			trim(document.AddTransportStaff.issueDate.value),
                 dlAuthority:		trim(document.AddTransportStaff.dlAuthority.value),
                 dlExp:				(document.AddTransportStaff.dlExp1.value),
                 join:				(document.AddTransportStaff.join1.value),
				 bloodGroup:        (document.AddTransportStaff.bloodGroup.value),
                 staffType:			(document.AddTransportStaff.staffType.value),
                 //inService:			(document.AddTransportStaff.inService[0].checked ? 1 : 0 ),
				 verificationDone:  (document.AddTransportStaff.verificationDone[0].checked ? 1 : 0 ),
                 leavingDate:		(document.AddTransportStaff.leav1.value),
				 medExamDate:		(document.AddTransportStaff.medExaminationDate.value),
				 hiddenFile:		(document.AddTransportStaff.staffPhoto.value),
				 hiddenDLFile:		(document.AddTransportStaff.drivingLicencePhoto.value)
             },
             onCreate: function() {
                 //showWaitDialog(true);
             },
             onSuccess: function(transport) {
                     //hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
						initAdd();
                     }
                     else {
						messageBox(trim(transport.responseText)); 
						 if("<?php echo STAFF_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
						   document.AddTransportStaff.staffCode.focus();
						 }
						 else {
						   document.AddTransportStaff.dlNo.focus();
						 }
					 }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=staffId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTransportStaff(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {staffId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
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
           
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A BUSSTOP
//  id=busStopId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDLImage(id) {
         if(false===confirm("Do you want to delete this image?")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxDeleteDLImage.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {staffId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         document.getElementById('imageDLDisplayDiv').innerHTML='';
                         messageBox("Image Deleted");
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
//THIS FUNCTION IS USED TO DELETE Employee Photo
//Author : Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteStaffImage(id) {
         if(false===confirm("Do you want to delete this image?")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxDeleteStaffImage.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {staffId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         document.getElementById('imageDisplayDiv').innerHTML='';
                         messageBox("Image Deleted");
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
//THIS FUNCTION IS USED TO CLEAN UP THE "AddTransportStaff" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddTransportStaff.reset();
   //document.AddTransportStaff.bloodGroup.value='';
   document.AddTransportStaff.staffName.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A BUSSTOP
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function EditTransportStaff() {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxInitEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             staffId:			(document.EditTransportStaff.staffId.value),
             staffName:			trim(document.EditTransportStaff.staffName.value),
			 address:			trim(document.EditTransportStaff.address.value),
			 dob:				trim(document.EditTransportStaff.dob1.value),
			 staffCode:			trim(document.EditTransportStaff.staffCode.value),
			 dlNo:				trim(document.EditTransportStaff.dlNo.value),
			 issueDate:			trim(document.EditTransportStaff.issueDate1.value),
			 dlAuthority:		trim(document.EditTransportStaff.dlAuthority.value),
			 dlExp:				(document.EditTransportStaff.dlExp2.value),
			 join:				(document.EditTransportStaff.join2.value),
			 bloodGroup:		(document.EditTransportStaff.bloodGroup.value),
			 staffType:			(document.EditTransportStaff.staffType.value),
			 //inService:		(document.EditTransportStaff.inService[0].checked ? 1 : 0 ),
			 verificationDone:	(document.EditTransportStaff.verificationEditDone[0].checked ? 1 : 0 ),
			 leavingDate:		(document.EditTransportStaff.leav2.value),
			 medExamDate:		(document.EditTransportStaff.medExaminationDate1.value),
			 hiddenFile:		(document.EditTransportStaff.staffPhoto.value),
			 hiddenDLFile:		(document.EditTransportStaff.drivingLicencePhoto.value)
             },
             onCreate: function() {
                 //showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         /*hiddenFloatingDiv('EditTransportStaff');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;*/
						  initEdit();
                     }
                   else {
						messageBox(trim(transport.responseText));
						if("<?php echo STAFF_CODE_ALREADY_EXIST;?>" == trim(transport.responseText)){
						   document.EditTransportStaff.staffCode.focus();
						 }
						 else {
							document.EditTransportStaff.dlNo.focus();
						 }
				   }
                     
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditTransportStaff" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {staffId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTransportStaff');
                        messageBox("<?php echo STUFF_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                   }
                   document.EditTransportStaff.reset();
                   
                   j = eval('('+transport.responseText+')');
                   //alert(transport.responseText);
                   document.EditTransportStaff.staffName.value = j.name;
				   document.EditTransportStaff.address.value = j.permanantAddress;
				   if(j.dob != -1) {
						document.EditTransportStaff.dob1.value = j.dob;
				   }
				   else {
						document.EditTransportStaff.dob1.value = '';
				   }
                   document.EditTransportStaff.staffCode.value = j.staffCode;
				   document.EditTransportStaff.join2.value = j.joiningDate;
				   document.EditTransportStaff.bloodGroup.value = j.bloodGroup;
				   document.EditTransportStaff.staffType.value=j.staffType;
				  
				   if(j.leavingDate != -1){
                     document.EditTransportStaff.leav2.value=j.leavingDate;  
                   }
                   else{
                       document.EditTransportStaff.leav2.value='';
                   }

				   if(j.medicalExaminationDate != -1){
                     document.EditTransportStaff.medExaminationDate1.value=j.medicalExaminationDate;  
                   }
                   else{
                       document.EditTransportStaff.medExaminationDate1.value='';
                   }
					
				   if(j.verificationDone == 1) {
					document.EditTransportStaff.verificationEditDone[0].checked=true;
                   }
                   else{
                    document.EditTransportStaff.verificationEditDone[1].checked=true;
                   }
				   
                   document.EditTransportStaff.dlNo.value = j.dlNo;
				   document.EditTransportStaff.issueDate1.value = j.dlIssuingDate;
				   document.EditTransportStaff.dlAuthority.value = j.dlIssuingAuthority;
				   document.EditTransportStaff.dlExp2.value = j.dlExpiryDate;

				   if(trim(j.photo) == '-1') {
					document.getElementById('imageDisplayDiv').innerHTML = '';
				   }
					
				   if(trim(j.photo)!= '-1') {
                     var d = new Date();
                     rndNo = d.getTime();
                     document.getElementById('imageDisplayDiv').innerHTML='<img src="'+imagePathURL+'/TransportStaff/'+j.photo+'?'+rndNo+'" style="width:100px;height:25px;border:2px solid grey" onclick=download("'+j.photo+'");>';
                     //if(mode==1){
                         document.getElementById('imageDisplayDiv').innerHTML +='<a onclick="deleteStaffImage('+j.staffId+')"><img src="'+imagePathURL+'/delete1.gif" style="margin-bottom-4px" alt="Delete" title="Delete Image" ></a>'; 
                     //}
                   }

				   if(trim(j.dlPhoto) == '-1') {
					document.getElementById('imageDLDisplayDiv').innerHTML = '';
				   }
					
				   if(trim(j.dlPhoto)!= '-1') {
                     var d = new Date();
                     rndNo = d.getTime();
                     document.getElementById('imageDLDisplayDiv').innerHTML='<img src="'+imagePathURL+'/TransportStaff/DLImage/'+j.dlPhoto+'?'+rndNo+'" style="width:100px;height:25px;border:2px solid grey" onclick=downloadDL("'+j.dlPhoto+'");>';
                     //if(mode==1){
                         document.getElementById('imageDLDisplayDiv').innerHTML +='<a onclick="deleteDLImage('+j.staffId+')"><img src="'+imagePathURL+'/delete1.gif" style="margin-bottom-4px" alt="Delete" title="Delete Image" ></a>'; 
                     //}
                   }
                   
                   document.EditTransportStaff.staffId.value =j.staffId;
                   
                   document.EditTransportStaff.staffName.focus();
             },
              onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//This function is used to control leaving date field based on selection of "In Service" field
function dateAdjst(mode,val){
    if(mode==1){ //During Add
      if(val==2){
          document.AddTransportStaff.leav1.value=serverDate;
      }
      else{
          document.AddTransportStaff.leav1.value='';
      }
    }
    else if(mode==2){ //During Edit
      if(val==2){
          document.EditTransportStaff.leav2.value=serverDate;
      }
      else{
          document.EditTransportStaff.leav2.value='';
      }
    }
}

function initAdd() {
	document.getElementById('AddTransportStaffForm').target = 'uploadTargetAdd';
	document.getElementById('AddTransportStaffForm').action = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/fileUpload.php';
    document.getElementById('AddTransportStaffForm').submit();
}

function initEdit() {
	//showWaitDialog(true);
    document.getElementById('EditTransportStaffForm').target = 'uploadTargetEdit';
	document.getElementById('EditTransportStaffForm').action = '<?php echo HTTP_LIB_PATH;?>/TransportStaff/fileUpload.php';
    document.getElementById('EditTransportStaffForm').submit();
}

/* function to print bus stop report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/transportStaffReportPrint.php?'+qstr;
    window.open(path,"TransportStaffReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='transportStaffReportCSV.php?'+qstr;
}

function  download(str){    
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/TransportStaff/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

 function  downloadDL(str){    
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/TransportStaff/DLImage/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function fileUploadError(str,mode) {
	//alert(str);
   hideWaitDialog(true);
   globalFL=1;
   
   if("<?php echo DUPLICATE_USER;?>" == trim(str)) {
       messageBox(trim(str));
	   //document.addEmployee.userName.value='';
	   document.AddTransportStaff.employeeCode.focus();
	   return false;
   }

   if("<?php echo UPLOAD_IMAGE;?>" == trim(str)) {
	   //alert(str);
        messageBox(trim(str));
		return false;
   }

   if("<?php echo FILE_NOT_UPLOAD;?>" == trim(str)) {
       messageBox(trim(str));
	   return false;
   }


   if(mode==1){

      if("<?php echo SUCCESS;?>" == trim(str)) {
		flag = true;
	   if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
			blankValues();
	   }
	   else {
			hiddenFloatingDiv('AddTransportStaff');
			sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
			//location.reload();
			return false;
		}
   }
   /*else {
		messageBox(trim(transport.responseText)); 
		document.addEmployee.employeeCode.focus(); 
	 }*/
   }
   else if(mode==2){
	   if("<?php echo SUCCESS;?>" != trim(str)) {
		 alert(str);
		 document.EditTransportStaff.staffCode.focus();
		return false;
	   }
	
      if("<?php echo SUCCESS;?>" == trim(str)) {
			hiddenFloatingDiv('EditTransportStaff');
			sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			return false;
	   }
   }
   else{
      messageBox(trim(str));  
   }
}

function sendKeys(mode,eleName, e) {
 var ev = e||window.event;
 thisKeyCode = ev.keyCode;
 if (thisKeyCode == '13') {
 if(mode==1){    
  var form = document.AddTransportStaff;
 }
 else{
     var form = document.EditTransportStaff;
 }
  eval('form.'+eleName+'.focus()');
  return false;
 }
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TransportStaff/listTransportStaffContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>

<?php 
// $History: listTransportStaff.php $ 
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 1/21/10    Time: 4:07p
//Updated in $/Leap/Source/Interface
//Add new field medical examination date
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:33p
//Updated in $/Leap/Source/Interface
//fixed bug nos. 0002370,0002369,0002365,0002363,0002362,0002361,0002368,
//0002366,0002360,0002359,0002372,0002358,0002357
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/24/09   Time: 7:05p
//Updated in $/Leap/Source/Interface
//fixed bug nos.0002354,0002353,0002351,0002352,0002350,0002347,0002348,0
//002355,0002349
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/22/09   Time: 6:08p
//Updated in $/Leap/Source/Interface
//fixed bug during self testing
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/17/09   Time: 3:41p
//Updated in $/Leap/Source/Interface
//put DL image in transport staff and changes in modules
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/10/09   Time: 4:15p
//Updated in $/Leap/Source/Interface
//add new fields and upload image
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/09/09   Time: 6:08p
//Updated in $/Leap/Source/Interface
//change in menu item from bus master to fleet management and doing
//changes in transport staff
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 4/08/09    Time: 10:30
//Updated in $/Leap/Source/Interface
//done bug fixing.
//bug ids---
//0000844,0000845,0000847,0000850,000843
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 17/06/09   Time: 11:15
//Updated in $/Leap/Source/Interface
//Done bug fixing.
//bug ids---0000063,0000082,0000083,0000085,0000087,0000090,0000092,
//0000095
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/09   Time: 15:54
//Updated in $/Leap/Source/Interface
//Done bug fixing ------Issues [08-May-09] Build
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/05/09    Time: 17:57
//Updated in $/Leap/Source/Interface
//Fixed bugs in bus & transport staff master as reported by vimal sir
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 14/04/09   Time: 10:58
//Updated in $/SnS/Interface
//Done bug fixing
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 6/04/09    Time: 14:12
//Updated in $/SnS/Interface
//Enhanced Transport Staff Master
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 4/04/09    Time: 19:27
//Updated in $/SnS/Interface
//Done enhancement for transport staff master
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:08a
//Updated in $/SnS/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 25/02/09   Time: 11:44
//Updated in $/SnS/Interface
//Modified look and feel
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 10/02/09   Time: 16:46
//Created in $/SnS/Interface
//Created module Transport Stuff Master
?>
