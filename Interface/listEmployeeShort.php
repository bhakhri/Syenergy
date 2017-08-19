<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF EMPLOYEE ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (14.07.10 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ShortEmployeeMaster');
define('ACCESS','view');
$queryString =  $_SERVER['QUERY_STRING'];    
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Employee/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Master (Guest Faculty)</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var instituteId = "<?php echo $sessionHandler->getSessionVariable('InstituteId') ?>";

var tableHeadArray = new Array(	new Array('srNo','#','width="3%"','',false), 
								new Array('employeeName','Name','width=15%','',true), 
								new Array('employeeCode','Emp. Code','width="10%"','',true), 
								//new Array('employeeAbbreviation','Abbr.','width="8%"','',true),
								new Array('isTeaching','Teaching','width="9%"','',true), 
								new Array('departmentAbbr','Deptt.','width="8%"','',true), 
								new Array('contactNumber','Contact No.','width="12%"','align=left',true),
								new Array('mobileNumber','Mobile No.','width="10%"','align=left',true),
								new Array('emailAddress','Email','width="18%"','align=left',true), 
								//new Array('active','Status','width="4%"','align="center"',false), 
								new Array('action','Action','width="10%" align="center"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitShortEmployeeList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEmployee';
editFormName   = 'EditEmployeeDiv';
winLayerWidth  = 850; //  add/edit form width
winLayerHeight = 595; // add/edit form height
deleteFunction = 'return deleteEmployee';
divResultName  = 'results';
page=1; //default page
sortField = 'employeeName';
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
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h,left,top) {
	if(typeof left === 'undefined') {
		left = 150;
		top = 40;
	}
    displayFloatingDiv(dv,'',w,h,left,top);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function checkRadio(){
	document.getElementById("marriageYear").value="";
	document.getElementById("marriageYear").disabled=true;
	document.getElementById("marriageMonth").value="";
	document.getElementById("marriageMonth").disabled=true;
	document.getElementById("marriageDate").value="";
	document.getElementById("marriageDate").disabled=true;
	document.getElementById("spouseName").disabled=true;
}	
function checkStatus(){
	document.getElementById("marriageYear").disabled=false;
	document.getElementById("marriageMonth").disabled=false;
	document.getElementById("marriageDate").disabled=false;
	document.getElementById("spouseName").disabled=false;
}

function checkRadio1(){
	document.editEmployee.marriageYear1.value="";
	document.editEmployee.marriageYear1.disabled=true;
	document.editEmployee.marriageMonth1.value="";
	document.editEmployee.marriageMonth1.disabled=true;
	document.editEmployee.marriageDate1.value="";
	document.editEmployee.marriageDate1.disabled=true;
	document.editEmployee.editSpouseName.value='';
	document.editEmployee.editSpouseName.disabled=true;
}	
function checkStatus1(){
	document.editEmployee.marriageYear1.value="";
	document.editEmployee.marriageYear1.disabled=false;
	document.editEmployee.marriageMonth1.value="";
	document.editEmployee.marriageMonth1.disabled=false;
	document.editEmployee.marriageDate1.value="";
	document.editEmployee.marriageDate1.disabled=false;
	document.editEmployee.editSpouseName.value='';
	document.editEmployee.editSpouseName.disabled=false;
}

var curDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {
	var fieldsArray = new Array(	new Array("userName","<?php echo ENTER_USER_NAME ?>"),
									new Array("userPassword","<?php echo ENTER_USER_PASSWORD ?>"), 
									new Array("roleName","<?php echo ENTER_USER_ROLE ?>"),
									new Array("title","<?php echo ENTER_TITLE ?>"),
									new Array("employeeName","<?php echo ENTER_EMPLOYEE_NAME ?>"),
									new Array("employeeCode", "<?php echo ENTER_EMPLOYEE_CODE ?>"),
									new Array("employeeAbbreviation", "<?php echo ENTER_EMPLOYEE_ABBR ?>"), 
									new Array ("designation", "<?php echo CHOOSE_EMPLOYEE_DESIGNATION ?> "),
									new Array ("branch", "<?php echo CHOOSE_EMPLOYEE_BRANCH ?>")
									/*new Array ("country", "<?php echo SELECT_COUNTRY ?>"),
									new Array ("states", "<?php echo SELECT_STATE ?>"),
									new Array ("city", "<?php echo SELECT_CITY ?>"),
									new Array("qualification","<?php echo ENTER_EMPLOYEE_QUALIFICATION ?>"),
									new Array("spouseName","<?php echo ENTER_SPOUSE_NAME ?>"),
									new Array("fatherName","<?php echo ENTER_FATHER_NAME ?>"),
									new Array("motherName","<?php echo ENTER_MOTHER_NAME ?>"), 
									new Array("contactNumber","<?php echo ENTER_CONTACT_NUMBER ?>"), 
									new Array("mobileNumber", "<?php echo ENTER_MOBILE_NUMBER ?>"), 
									new Array("email","<?php echo ENTER_EMAIL ?>"), 
									new Array("address1","<?php echo ENTER_EMPLOYEE_ADDRESS1 ?> "), 
									new Array("address2", "<?php echo ENTER_EMPLOYEE_ADDRESS2 ?>"),
									new Array("pin","<?php echo ENTER_PIN ?>"),*/
									//new Array("teachingininstitutes","<?php echo SELECT_TEACHINGINSTITUTE ?>")
									);
    
    var len = fieldsArray.length;
   
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="address2" ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
		
        else if(fieldsArray[i][0]=="address1" || fieldsArray[i][0]=="address2"){
         //no check
       }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            /*
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0] != "employeeName" && fieldsArray[i][0] != "employeeCode" && fieldsArray[i][0] != "address1" && fieldsArray[i][0] != "address2" && fieldsArray[i][0] != "userPassword" && fieldsArray[i][0] != "employeeAbbreviation") {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING_NUMERIC ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            
            else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='title' && fieldsArray[i][0]!='userPassword' && fieldsArray[i][0]!='employeeCode' && fieldsArray[i][0]!='employeeAbbreviation' && fieldsArray[i][0]!='roleName' && fieldsArray[i][0]!='designation' && fieldsArray[i][0]!='branch' && fieldsArray[i][0]!='teachingininstitutes' && fieldsArray[i][0]!='userName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
				
                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }*/
              
        }
	}
	
		if (act=="Add"){

			if(!isEmail(document.getElementById("email").value) && trim(document.getElementById("email").value) != "") {
            //if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid email format
                 messageBox("<?php echo CHOOSE_EMAIL ?>");
			   document.getElementById("email").focus();
                 return false;
              }

			  if(!isPhone(document.getElementById("contactNumber").value)) {
                 messageBox("<?php echo VALID_PHONE ?>");
                 document.getElementById("contactNumber").focus();
                 return false;
              }

			 if(!isPhone(document.getElementById("mobileNumber").value)) {
                 messageBox("<?php echo VALID_PHONE ?>");
                 document.getElementById("mobileNumber").focus();
                 return false;
              }

			if(document.addEmployee.userName.value != '') {
				if(document.addEmployee.userPassword.value == '') {
					messageBox("<?php echo ENTER_USER_PASSWORD ?>");
					document.addEmployee.userPassword.focus();
					return false;
				}
				if(document.addEmployee.roleName.value == '') {
					messageBox("<?php echo ENTER_USER_ROLE ?>");
					document.addEmployee.roleName.focus();
					return false;
				}
			}


			if(document.addEmployee.userName.value == '') {
				if(document.addEmployee.userPassword.value != '') {
					messageBox("<?php echo ENTER_USER_NAME_SELECT_PASSWORD ?>");
					document.addEmployee.userName.focus();
					return false;
				}
				if(document.addEmployee.roleName.value != '') {
					messageBox("<?php echo ENTER_USER_NAME_SELECT_ROLE ?>");
					document.addEmployee.userName.focus();
					return false;
				}
			}
			if(document.addEmployee.title.value == 1) {
				if(document.addEmployee.gender[0].checked == false){
					messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
					document.addEmployee.title.focus();
					return false;
				}
			}

			if(document.addEmployee.title.value == 2 || document.addEmployee.title.value == 3 || document.addEmployee.title.value == 5) {
				if(document.addEmployee.gender[1].checked == false){
					messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
					document.addEmployee.title.focus();
					return false;
				}
			}
		}
		
		else if (act=="Edit") {
			
			if(!isEmail(document.getElementById("emailEdit").value)  && trim(document.getElementById("emailEdit").value) != "") {
            //if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid email format
                 messageBox("<?php echo CHOOSE_EMAIL ?>");
			   document.getElementById("emailEdit").focus();
                 return false;
                
              }

			 if(!isPhone(document.getElementById("editContactNumber").value)) {
                 messageBox("<?php echo VALID_PHONE ?>");
                 document.getElementById("editContactNumber").focus();
                 return false;
              }

			  if(!isPhone(document.getElementById("editMobileNumber").value)) {
                 messageBox("<?php echo VALID_PHONE ?>");
                 document.getElementById("editMobileNumber").focus();
                 return false;
              }


			if(document.editEmployee.userName.value != '') {
				if(document.editEmployee.userPassword.value == '') {
					messageBox("<?php echo ENTER_USER_PASSWORD ?>");
					document.editEmployee.userPassword.focus();
					return false;
				}
				if(document.editEmployee.roleName.value == '') {
					messageBox("<?php echo ENTER_USER_ROLE ?>");
					document.editEmployee.roleName.focus();
					return false;
				}
			}

			if(document.editEmployee.userName.value == '') {
				if(document.editEmployee.userPassword.value != '') {
					messageBox("<?php echo ENTER_USER_NAME_SELECT_PASSWORD ?>");
					document.editEmployee.userName.focus();
					return false;
				}
				if(document.editEmployee.roleName.value != '') {
					messageBox("<?php echo ENTER_USER_NAME_SELECT_ROLE ?>");
					document.editEmployee.userName.focus();
					return false;
				}
			}


			if(document.editEmployee.title.value == 1) {
				if(document.editEmployee.gender[0].checked == false){
					messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
					document.editEmployee.title.focus();
					return false;
				}
			}

			if(document.editEmployee.title.value == 2 || document.editEmployee.title.value == 3 || document.editEmployee.title.value == 5) {
				if(document.editEmployee.gender[1].checked == false){
					messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
					document.editEmployee.title.focus();
					return false;
				}
			}

		
		}

	function validUsername(username){
        var error = "";
        var illegalChars = /\W_/; // allow letters, numbers, and underscores
        if (illegalChars.test(username)) {
            return false;
        } 
        else {
            return true;
        }
    }
    
    if (document.getElementById("userName").value!=''){
        username=document.getElementById("userName").value;
        if (!validUsername(username)){
            messageBox("Invalid user name","userName");
            document.getElementById("userName").focus();
            return false;
        }
    }
      
	   if(act=='Add') {
       addEmployee();
       return false;
    }
    else if(act=='Edit') {
        editEmployee();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addEmployee IS USED TO ADD NEW EMPLOYEE
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addEmployee() {
	
		 var selected=0;
		 var selectedDefault=0;
		 var selectedInstitute='';
		 form = document.addEmployee;
		 
		 for(var i=1;i<form.length;i++){

			if(form.elements[i].type=="checkbox"){
				
				if((form.elements[i].checked) && (form.elements[i].name=="teachingininstitutes[]")){
					querySeprator ='';
					if(selectedInstitute!=''){
						querySeprator = ",";
					}
					selectedInstitute +=querySeprator+(form.elements[i].value);
					selected++; 
				}
			}
			if(form.elements[i].type=="radio"){
	
				if((form.elements[i].checked) && (form.elements[i].name=="defaultInstitute")){
			
					selectedDefault++; 
					defaultValue = form.elements[i].value;
				}
			}
		}
			
		if(selected==0){
			alert("<?php echo SELECT_ONE_INSTITUTE?>");
			return false;
		}

		if(selectedDefault==0){

			alert("<?php echo SELECT_DEFAULT_INSTITUTE?>");
			return false;
		}
	
		if(document.getElementById("teachingininstitutes"+defaultValue).checked==false){
			alert("<?php echo DEFAULT_INSTITUTE_SELECTED?>");
			return false;
		}
		
		var isTeaching=0;
         var gender=0;
         //var isMarried=0;
         if(document.addEmployee.isTeaching[0].checked){
             isTeaching=document.addEmployee.isTeaching[0].value;
         }
         else{  
             isTeaching=document.addEmployee.isTeaching[1].value;
         }
         if(document.addEmployee.gender[0].checked){
             gender=document.addEmployee.gender[0].value;
         }
         else{  
             gender=document.addEmployee.gender[1].value;
         }

         url = '<?php echo HTTP_LIB_PATH; ?>/Employee/ajaxInitShortEmployeeAdd.php';
		
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {userName: (document.addEmployee.userName.value),
             userPassword: (document.addEmployee.userPassword.value),
             roleName: (document.addEmployee.roleName.value),
			 title: (document.addEmployee.title.value),
			 lastName: (document.addEmployee.lastName.value),
             employeeName: (document.addEmployee.employeeName.value),
			 middleName: (document.addEmployee.middleName.value),
             employeeCode: (document.addEmployee.employeeCode.value),
             employeeAbbreviation: (document.addEmployee.employeeAbbreviation.value),
             isTeaching : isTeaching,
             teachingininstitutes : selectedInstitute,
			 defaultInstitute : defaultValue,
             designation:(document.addEmployee.designation.value),
             gender: gender,
             branch:(document.addEmployee.branch.value),
			 department:(document.addEmployee.department.value),
             contactNumber: (document.addEmployee.contactNumber.value), 
             mobileNumber: (document.addEmployee.mobileNumber.value), 
             email: (document.addEmployee.email.value), 
             address1: (document.addEmployee.address1.value), 
             address2: (document.addEmployee.address2.value)
				 },

             onCreate: function() {
                  showWaitDialog(true);
              },
               
             onSuccess: function(transport){
                  hideWaitDialog(true);
				     
                  if(trim(transport.responseText)=="<?php echo DUPLICATE_USER ?>"){
					  messageBox(trim(transport.responseText)); 
                      document.addEmployee.userName.focus();
                  }
                  else if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
                       flag = true;
                       if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                            blankValues();
                       }
                       else {
                            hiddenFloatingDiv('AddEmployee');
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                            //location.reload();
                            return false;
                        }
                     }
                     else{
                            messageBox(trim(transport.responseText));
							if (trim(transport.responseText)=='<?php echo ADMIN_CANNOT_CREATE ?>'){
							//document.addHostel.hostelName.value='';
								document.addEmployee.roleName.focus();
						    }
							else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ALREADY_EXIST ?>') {
								document.addEmployee.employeeCode.focus();
							}
							else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ABBR_ALREADY_EXIST ?>') {
								document.addEmployee.employeeAbbreviation.focus();
							}
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteEmployee(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitDelete.php';
         new Ajax.Request(url,
          {
             method:'post',
             parameters: {employeeId: id},
             
               onCreate: function() {
                  showWaitDialog(true);
               },
               
               onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         //location.reload();
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

function blankValues() {
  var obj=document.getElementById('scroll1').getElementsByTagName('INPUT');
  var obj1 = obj.length;
	for(var h=0 ; h < obj1 ;h++){
		 if(obj[h].type.toUpperCase() == 'CHECKBOX' && obj[h].value != instituteId) {
				obj[h].checked=false;	
			 }
	  }
   getLastestEmployeeCode('Add');
   getLastestUserCode('Add');
   document.addEmployee.userName.disabled = false;
   //document.addEmployee.title.value='';
   document.addEmployee.lastName.value='';
   document.addEmployee.employeeName.value='';
   document.addEmployee.middleName.value='';
   document.addEmployee.employeeAbbreviation.value='';
   document.addEmployee.isTeaching[0].checked = true;
   document.addEmployee.designation.value='';
   document.addEmployee.gender[0].checked = true;
   document.addEmployee.branch.value='';
   //document.addEmployee.department.value='';
   document.addEmployee.contactNumber.value='';
   document.addEmployee.mobileNumber.value='';
   document.addEmployee.email.value='';
   document.addEmployee.address1.value='';
   document.addEmployee.address2.value='';
   document.addEmployee.userName.focus();
   //Added by abhiraj for payroll module Ends
}

//used to get lastet item code
function  getLastestEmployeeCode(mode){
    
     var url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxGetNewEmployeeCode.php';
     document.addEmployee.employeeCode.value='';
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous :false,
             parameters: {
                 '1': 1
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.addEmployee.employeeCode.value=trim(transport.responseText);
					//document.addEmployee.userName.value=trim(transport.responseText);
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//used to get lastet user code
function  getLastestUserCode(mode){
    
     var url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxGetNewUserCode.php';
     document.addEmployee.userName.value='';
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous :false,
             parameters: {
                 '1': 1
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    document.addEmployee.userName.value=trim(transport.responseText);
					//document.addEmployee.userName.value=trim(transport.responseText);
                    
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
// 
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editEmployee() {
		 var selected=0;
		 var selectedDefault=0;
		 var selectedInstitute='';
		 form = document.editEmployee;
		 for(var i=1;i<form.length;i++){

			if(form.elements[i].type=="checkbox"){
				
				if((form.elements[i].checked) && (form.elements[i].name=="teachingininstitutes1[]")){

					querySeprator ='';
					if(selectedInstitute!=''){
						querySeprator = ",";
					}
					selectedInstitute +=querySeprator+(form.elements[i].value);
					selected++; 
				}
			}
			
			if(form.elements[i].type=="radio"){
				if((form.elements[i].checked) && (form.elements[i].name=="defaultInstitute")){
					selectedDefault++; 
					defaultValue = form.elements[i].value;
				}
			}
		}

		if(selected==0){

			alert("<?php echo SELECT_ONE_INSTITUTE?>");
			return false;
		}

		if(selectedDefault==0){

			alert("<?php echo SELECT_DEFAULT_INSTITUTE?>");
			return false;
		}

		if(document.getElementById("teachingininstitutes1_"+defaultValue).checked==false){
			alert("<?php echo DEFAULT_INSTITUTE_SELECTED?>");
			return false;
		}

		var isTeaching=0;
         var gender=0;
         var isMarried=0;
         if(document.editEmployee.isTeaching[0].checked){
             isTeaching=document.editEmployee.isTeaching[0].value;
         }
         else{  
             isTeaching=document.editEmployee.isTeaching[1].value;
         }
         if(document.editEmployee.gender[0].checked){
             gender=document.editEmployee.gender[0].value;
         }
         else{  
             gender=document.editEmployee.gender[1].value;
         }


         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitShortEmployeeEdit.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userName: (document.editEmployee.userName.value), 
             userPassword: (document.editEmployee.userPassword.value),
             roleName: (document.editEmployee.roleName.value),
			 title: (document.editEmployee.title.value),
			 lastName: (document.editEmployee.lastName.value),
             employeeName: (document.editEmployee.employeeName.value),
			 middleName: (document.editEmployee.middleName.value),
             employeeCode:(document.editEmployee.employeeCode.value), 
             employeeAbbreviation: (document.editEmployee.employeeAbbreviation.value), 
             isTeaching: isTeaching,
			 teachingininstitutes : selectedInstitute,
			 defaultInstitute : defaultValue,
             designation:(document.editEmployee.designation.value),
             gender: gender, 
             branch:(document.editEmployee.branch.value),
			 department:(document.editEmployee.department.value),
             contactNumber: (document.editEmployee.editContactNumber.value),
             mobileNumber: (document.editEmployee.editMobileNumber.value), 
             email: (document.editEmployee.emailEdit.value),
             address1: (document.editEmployee.address1.value), 
             address2: (document.editEmployee.address2.value),
             employeeId: (document.editEmployee.employeeId.value),
             userId: (document.editEmployee.userId.value)
				 }, 

				onCreate: function() {
                  showWaitDialog(true);
               },
               
                onSuccess: function(transport){
                   hideWaitDialog(true);
                   if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
						hiddenFloatingDiv('EditEmployeeDiv');
						sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						return false;
                   }
				   else {
                        messageBox(trim(transport.responseText));
						if (trim(transport.responseText)=='<?php echo ADMIN_CANNOT_CREATE ?>'){
						//document.addHostel.hostelName.value='';
							document.editEmployee.roleName.focus();
						}
						else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ALREADY_EXIST ?>') {
							document.editEmployee.employeeCode.focus();
						}
						else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ABBR_ALREADY_EXIST ?>') {
							document.editEmployee.employeeAbbreviation.focus();
						}
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO populate the values 
 // during editing the record
// 
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
	form = document.editEmployee;
         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxGetShortEmployeeValues.php';
         document.editEmployee.reset();
		 //alert(id);
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {employeeId: id},
               
			 onCreate: function() {
                showWaitDialog(true);
             },
               
             onSuccess: function(transport){
                hideWaitDialog(true);
                  if(trim(transport.responseText)==0) {
					messageBox("<?php echo EMPLOYEE_NOT_EXIST;?>");
                    hiddenFloatingDiv('EditEmployeeDiv');
                    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                  }
					j = eval('('+trim(transport.responseText)+')');
					//alert(transport.responseText);
					if(j.edit[0].userId != 0) {
						document.editEmployee.userName.disabled = true;
						document.editEmployee.userName.value = j.edit[0].userName;
						document.editEmployee.userPassword.value="********";
						document.editEmployee.roleName.value = j.edit[0].roleId;
					}
					else {
						document.editEmployee.userName.disabled = false;
						document.editEmployee.userPassword.value='';
						document.editEmployee.roleName.value = '';
					}
					
					if(trim(j.edit[0].title) == '' || trim(j.edit[0].title) == 0) {
						document.editEmployee.title.value = '';
					}
					else {
						document.editEmployee.title.value=j.edit[0].title;
					}
					document.editEmployee.lastName.value=j.edit[0].lastName;
					document.editEmployee.employeeName.value=j.edit[0].employeeName;
					document.editEmployee.middleName.value=j.edit[0].middleName;
					document.editEmployee.employeeCode.value=j.edit[0].employeeCode;
					document.editEmployee.employeeAbbreviation.value=j.edit[0].employeeAbbreviation;

                   if(j.edit[0].isTeaching == "1"){
                   document.editEmployee.isTeaching[0].checked=true;
                   }
                   else if(j.edit[0].isTeaching == "0"){
                   document.editEmployee.isTeaching[1].checked=true; 
                   }

				   document.editEmployee.designation.value=j.edit[0].designationId;
                   if(j.edit[0].gender == "M"){
                   document.editEmployee.gender[0].checked=true;
					}
				   else if(j.edit[0].gender == "F"){
				   document.editEmployee.gender[1].checked=true; 
                    }
					
                   document.editEmployee.branch.value=j.edit[0].branchId;

				   if(j.edit[0].departmentId == '') {
						document.editEmployee.department.value = '';
				   }
				   else {
					   document.editEmployee.department.value=j.edit[0].departmentId;
				   }
                   document.editEmployee.editContactNumber.value=j.edit[0].contactNumber;
                   document.editEmployee.editMobileNumber.value=j.edit[0].mobileNumber;
                   document.editEmployee.emailEdit.value=j.edit[0].emailAddress;
                   document.editEmployee.address1.value=j.edit[0].address1;
                   document.editEmployee.address2.value=j.edit[0].address2;
				   document.editEmployee.employeeId.value=j.edit[0].employeeId;
				   
                   document.editEmployee.userId.value=j.edit[0].userId;

				   
				   var obj=document.getElementById('scroll2').getElementsByTagName('INPUT');
				   var len1=obj.length;
                    for(var n=0 ; n < len1 ;n++){ 
						 if(obj[n].type.toUpperCase() == 'CHECKBOX') {
 							 obj[n].checked=false;
						  }
				    }

				   len1=j.edit.length;	
				   var len2 = obj.length;
                   for(var i=0 ; i < len1 ;i++){
                     for(var n=0 ; n < len2 ;n++) {
						 if(obj[n].type.toUpperCase() == 'CHECKBOX' && obj[n].value==j.edit[i].instituteId) {
 							 obj[n].checked=true;
						  }
					   }
				   }

				   if(j.edit[0].userId == 0 ) {
					document.editEmployee.userName.focus();
				   }
				   else {
					document.editEmployee.employeeName.focus();
				   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayEmployeeShortReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayShortEmployeeReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayEmployeeShortCSV.php?'+qstr;
	window.location = path;
}


function printTimeTableEmployee(employeeId,labelId,timeTableType) {
	path='<?php echo UI_HTTP_PATH;?>/teacherTimeTableReportPrint.php?labelId='+labelId+'&teacherId='+employeeId+'&timeTableType='+timeTableType+'&typeFormat=t';
    window.open(path,"DisplayTeacherTimeTable","status=1,menubar=1,scrollbars=1, width=900");
}

//populate list
/*window.onload=function(){
   // alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
	   //if("<?php echo $listEmployee?>" != 1) {
			sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
	   //}
   }
   else {
	sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
   }
}*/

</script>
</head>
<body>
	<?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Employee/listShortEmployeeContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>
