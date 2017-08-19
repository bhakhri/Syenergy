<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF EMPLOYEE ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Jaineesh
// Created on : (13.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','view');
$queryString =  $_SERVER['QUERY_STRING'];
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Employee/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
global $sessionHandler;
$instituteId = $sessionHandler->getSessionVariable('InstituteId');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var instituteId = "<?php echo $sessionHandler->getSessionVariable('InstituteId') ?>";

var tableHeadArray = new Array(	new Array('srNo','#','width="1%"','',false),
								new Array('employeeName','Name','width=15%','',true),
								new Array('employeeCode','Emp.Code','width="10%"','',true),
								new Array('isTeaching','Teaching','width="8%"','',true),
								new Array('departmentAbbr','Deptt.','width="5%"','',true),
								new Array('contactNumber','Contact No.','width="8%"','align=left',true),
								new Array('mobileNumber','Mobile No.','width="8%"','align=left',true),
								new Array('emailAddress','Email','width="12%"','align=left',true),
                                new Array('guestFacultyDisplay','Guest Faculty','width="17%"','align=left',true),
								new Array('active','Active','width="2%"','align="center"',false),
								new Array('action1','Action','width="15%" align="center"','align="right"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitList.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h,guestFaculty,left,top) {
	if(typeof left === 'undefined') {
		left = 150;
		top = 40;
	}
 //   alert(guestFaculty);
    displayFloatingDiv(dv,'',w,h,left,top);
    if (guestFaculty == 0) {
    populateValues(id);
    }
    else if (guestFaculty == 1) {
    populateGuestValues(id);
    }
}


//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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

     document.editGuestEmployee.marriageYear1.value="";
    document.editGuestEmployee.marriageYear1.disabled=true;
    document.editGuestEmployee.marriageMonth1.value="";
    document.editGuestEmployee.marriageMonth1.disabled=true;
    document.editGuestEmployee.marriageDate1.value="";
    document.editGuestEmployee.marriageDate1.disabled=true;
    document.editGuestEmployee.editSpouseName.value='';
    document.editGuestEmployee.editSpouseName.disabled=true;


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

    document.editGuestEmployee.marriageYear1.value="";
    document.editGuestEmployee.marriageYear1.disabled=false;
    document.editGuestEmployee.marriageMonth1.value="";
    document.editGuestEmployee.marriageMonth1.disabled=false;
    document.editGuestEmployee.marriageDate1.value="";
    document.editGuestEmployee.marriageDate1.disabled=false;
    document.editGuestEmployee.editSpouseName.value='';
    document.editGuestEmployee.editSpouseName.disabled=false;
}

var curDate="<?php echo date('Y-m-d'); ?>";

function validateAddForm(frm, act) {


	var fieldsArray = new Array(
									new Array("title","<?php echo ENTER_TITLE ?>"),
									new Array("employeeName","<?php echo ENTER_EMPLOYEE_NAME ?>"),
									new Array("employeeCode", "<?php echo ENTER_EMPLOYEE_CODE ?>"),
									new Array("employeeAbbreviation", "<?php echo ENTER_EMPLOYEE_ABBR ?>"),
									new Array ("designation", "<?php echo CHOOSE_EMPLOYEE_DESIGNATION ?> "),
									new Array ("branch", "<?php echo CHOOSE_EMPLOYEE_BRANCH ?>")
								);

    var len = fieldsArray.length;

    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="spouseName" && fieldsArray[i][0]!="address2" ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }

        else if(fieldsArray[i][0]=="address1" || fieldsArray[i][0]=="address2"){
         //no check
       }
        else {
            if(!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0] != "employeeName" && fieldsArray[i][0] != "employeeCode" && fieldsArray[i][0] != "address1" && fieldsArray[i][0] != "address2" && fieldsArray[i][0] != "userPassword" && fieldsArray[i][0] != "qualification" && fieldsArray[i][0] != "employeeAbbreviation") {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING_NUMERIC ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }

            else if(fieldsArray[i][0]=="pin"){
             if(!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))) {
					messageBox("<?php echo ENTER_NUMBER ?>");
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;

				}
            }

            else if(!isAlphabetCharacters(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!='title' && fieldsArray[i][0]!='qualification' && fieldsArray[i][0]!='userPassword' && fieldsArray[i][0]!='employeeCode' && fieldsArray[i][0]!='employeeAbbreviation' && fieldsArray[i][0]!='roleName' && fieldsArray[i][0]!='designation' && fieldsArray[i][0]!='branch' && fieldsArray[i][0]!='country' && fieldsArray[i][0]!='states' && fieldsArray[i][0]!='city' && fieldsArray[i][0]!='employeeYear' && fieldsArray[i][0]!='employeeMonth' && fieldsArray[i][0]!='employeeDate' && fieldsArray[i][0]!='joiningYear' && fieldsArray[i][0]!='joiningMonth' && fieldsArray[i][0]!='joiningDate' && fieldsArray[i][0]!='teachingininstitutes' && fieldsArray[i][0]!='userName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);

                messageBox("<?php echo ENTER_ALPHABETS ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }

        }
	}

		if (act=="Add"){


			if (document.getElementById("employeeMonth").value != '' ||  document.getElementById("employeeDate").value != '' || document.getElementById("employeeYear").value !='') {
				BirthDate = document.getElementById("employeeYear").value + "-" + document.getElementById("employeeMonth").value + "-" + document.getElementById("employeeDate").value;
			if (!dateDifference(BirthDate,curDate,'-')) {
				messageBox("<?php echo BIRTH_NOT_CURDATE ?>");
				document.getElementById("employeeDate").focus();
				return false;
				}
			}

			if (document.getElementById("employeeMonth").value != '' ||  document.getElementById("employeeDate").value != '' || document.getElementById("employeeYear").value !='') {
				BirthYear = document.getElementById("employeeMonth").value + "-" + document.getElementById("employeeDate").value + "-" + document.getElementById("employeeYear").value;
			if (!isDate1(BirthYear)) {
				document.getElementById("employeeDate").focus();
				return false;
				}
			}

			if(document.addEmployee.isMarried[1].checked==false){

				if (document.getElementById("marriageYear").value!='' || document.getElementById("marriageMonth").value!='' || document.getElementById("marriageDate").value){
				MarriageYear = document.getElementById("marriageMonth").value + "-" + document.getElementById("marriageDate").value + "-" + document.getElementById("marriageYear").value;
				if (!isDate1(MarriageYear)) {
					document.getElementById("marriageDate").focus();
					return false;
				}
			}
			}

			if (document.getElementById("marriageYear").value != '' ||  document.getElementById("marriageMonth").value != '' || document.getElementById("marriageDate").value !='') {
				MarriageYear = document.getElementById("marriageYear").value + "-" + document.getElementById("marriageMonth").value + "-" + document.getElementById("marriageDate").value;
			if (!dateDifference(MarriageYear,curDate,'-')) {
				messageBox("<?php echo MARRIAGE_NOT_CURDATE ?>");
				document.getElementById("marriageDate").focus();
				return false;
				}
			}

			if (document.getElementById("marriageYear").value!='' || document.getElementById("marriageMonth").value!='' || document.getElementById("marriageDate").value){
				MarriageYear = document.getElementById("marriageYear").value + "-" + document.getElementById("marriageMonth").value + "-" + document.getElementById("marriageDate").value;
				BirthYear = document.getElementById("employeeYear").value + "-" + document.getElementById("employeeMonth").value + "-" + document.getElementById("employeeDate").value;

			if (!dateDifference(BirthYear,MarriageYear,"-")) {
				messageBox("<?php echo COMPARISON_YEAR ?>");
				document.getElementById("marriageYear").focus();
				return false;
			}
			}
			if (document.getElementById("joiningMonth").value != '' ||  document.getElementById("joiningDate").value != '' || document.getElementById("joiningYear").value !='') {

				JoiningYear = document.getElementById("joiningYear").value + "-" + document.getElementById("joiningMonth").value + "-" + document.getElementById("joiningDate").value;
			if (!dateDifference(JoiningYear,curDate,'-')) {
				messageBox("<?php echo JOINING_NOT_CURDATE ?>");
				document.getElementById("joiningDate").focus();
				return false;
				}
			}

			if (document.getElementById("joiningMonth").value != '' ||  document.getElementById("joiningDate").value != '' || document.getElementById("joiningYear").value !='') {
				JoiningYear = document.getElementById("joiningMonth").value + "-" + document.getElementById("joiningDate").value + "-" + document.getElementById("joiningYear").value;
				if (!isDate1(JoiningYear)) {
					document.getElementById("joiningYear").focus();
					return false;
				}
			}

			if (document.getElementById("joiningYear").value!='' || document.getElementById("joiningMonth").value!='' || document.getElementById("joiningDate").value){
				JoiningYear = document.getElementById("joiningYear").value + "-" + document.getElementById("joiningMonth").value + "-" + document.getElementById("joiningDate").value;

				BirthYear = document.getElementById("employeeYear").value + "-" + document.getElementById("employeeMonth").value + "-" + document.getElementById("employeeDate").value;

				if (!dateDifference(BirthYear,JoiningYear,"-")) {
					messageBox("<?php echo COMPARISON_JOINING_YEAR ?>");
					document.getElementById("joiningYear").focus();
					return false;
				}
			}

			if (document.getElementById("leavingYear").value != '' ||  document.getElementById("leavingMonth").value != '' || document.getElementById("leavingDate").value !='') {
				LeavingYear = document.getElementById("leavingYear").value + "-" + document.getElementById("leavingMonth").value + "-" + document.getElementById("leavingDate").value;
			if (!dateDifference(LeavingYear,curDate,'-')) {
				messageBox("<?php echo LEAVING_NOT_CURDATE ?>");
				document.getElementById("leavingDate").focus();
				return false;
				}
			}

			if (document.getElementById("leavingYear").value!='' || document.getElementById("leavingMonth").value!='' || document.getElementById("leavingDate").value!=''){
				LeavingYear = document.getElementById("leavingMonth").value + "-" + document.getElementById("leavingDate").value + "-" + document.getElementById("leavingYear").value;
				if (!isDate1(LeavingYear)){
					document.getElementById("leavingDate").focus();
					return false;
				}
			}

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

			  if(!isAlphabetCharacters(document.addEmployee.lastName.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.addEmployee.lastName.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.addEmployee.middleName.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.addEmployee.middleName.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.addEmployee.religion.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.addEmployee.religion.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("fatherName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("fatherName").focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("motherName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("motherName").focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("spouseName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("spouseName").focus();
                 return false;
              }

			if (document.getElementById("leavingYear").value!='' || document.getElementById("leavingMonth").value!='' || document.getElementById("leavingDate").value){
				LeavingYear = document.getElementById("leavingYear").value + "-" + document.getElementById("leavingMonth").value + "-" + document.getElementById("leavingDate").value;
				BirthYear = document.getElementById("employeeYear").value + "-" + document.getElementById("employeeMonth").value + "-" + document.getElementById("employeeDate").value;
			if (!dateDifference(BirthYear,LeavingYear,"-")) {
				messageBox("<?php echo COMPARISON_LEAVING_YEAR ?>");
				document.getElementById("leavingYear").focus();
				return false;
			  }
			}

			if (document.getElementById("leavingYear").value!='' || document.getElementById("leavingMonth").value!='' || document.getElementById("leavingDate").value){
				LeavingYear = document.getElementById("leavingYear").value + "-" + document.getElementById("leavingMonth").value + "-" + document.getElementById("leavingDate").value;

				JoiningYear = document.getElementById("joiningYear").value + "-" + document.getElementById("joiningMonth").value + "-" + document.getElementById("joiningDate").value;
			if (!dateDifference(JoiningYear,LeavingYear,"-")) {
				messageBox("<?php echo COMPARISON_LEAVING_JOINING_YEAR ?>");
				document.getElementById("leavingYear").focus();
				return false;
			  }
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

			if (document.getElementById("employeeMonth1").value != '' ||  document.getElementById("employeeDate1").value != '' || document.getElementById("employeeYear1").value !='') {
				BirthDate = document.getElementById("employeeYear1").value + "-" + document.getElementById("employeeMonth1").value + "-" + document.getElementById("employeeDate1").value;

			if (!dateDifference(BirthDate,curDate,'-')) {
				messageBox("<?php echo BIRTH_NOT_CURDATE ?>");
				document.getElementById("employeeDate1").focus();
				return false;
				}
			}

			if (document.getElementById("employeeMonth1").value != '' ||  document.getElementById("employeeDate1").value != '' || document.getElementById("employeeYear1").value !='') {
			BirthYear = document.getElementById("employeeMonth1").value + "-" + document.getElementById("employeeDate1").value + "-" + document.getElementById("employeeYear1").value;
			if (!isDate1(BirthYear)) {
				document.getElementById("employeeYear1").focus();
				return false;
			 }
		    }
			if(document.editEmployee.isMarried[0].checked==true){
				if (document.getElementById("marriageYear1").value!='' || document.getElementById("marriageMonth1").value!='' || document.getElementById("marriageDate1").value){
				MarriageYear = document.getElementById("marriageMonth1").value + "-" + document.getElementById("marriageDate1").value + "-" + document.getElementById("marriageYear1").value;
				if (!isDate1(MarriageYear)) {
					document.getElementById("marriageDate1").focus();
					return false;
					}
				}
			}

			if (document.getElementById("marriageYear1").value != '' ||  document.getElementById("marriageMonth1").value != '' || document.getElementById("marriageDate1").value !='') {
				MarriageYear = document.getElementById("marriageYear1").value + "-" + document.getElementById("marriageMonth1").value + "-" + document.getElementById("marriageDate1").value;
			if (!dateDifference(MarriageYear,curDate,'-')) {
				messageBox("<?php echo MARRIAGE_NOT_CURDATE ?>");
				document.getElementById("marriageDate1").focus();
				return false;
				}
			}

			if (document.getElementById("marriageYear1").value!='' || document.getElementById("marriageMonth1").value!='' || document.getElementById("marriageDate1").value){
				MarriageYear1 = document.getElementById("marriageYear1").value + "-" + document.getElementById("marriageMonth1").value + "-" + document.getElementById("marriageDate1").value;
				BirthYear1 = document.getElementById("employeeYear1").value + "-" + document.getElementById("employeeMonth1").value + "-" + document.getElementById("employeeDate1").value;

				if (!dateDifference(BirthYear1,MarriageYear1,"-")) {
				messageBox("<?php echo COMPARISON_YEAR ?>");
				document.getElementById("marriageYear1").focus();
				return false;
			   }
			}

			if (document.getElementById("joiningMonth1").value != '' ||  document.getElementById("joiningDate1").value != '' || document.getElementById("joiningYear1").value !='') {
				JoiningYear = document.getElementById("joiningYear1").value + "-" + document.getElementById("joiningMonth1").value + "-" + document.getElementById("joiningDate1").value;
			if (!dateDifference(JoiningYear,curDate,'-')) {
				messageBox("<?php echo JOINING_NOT_CURDATE ?>");
				document.getElementById("joiningDate1").focus();
				return false;
				}
			}
// new function start
if(document.addEmployee.roleName.value!='')
{
 messageBox("enter user name and password");
document.getElementById("userName").focus();
return false;

}

//new function end

			if (document.getElementById("joiningMonth1").value != '' ||  document.getElementById("joiningDate1").value != '' || document.getElementById("joiningYear1").value !='') {
				JoiningYear = document.getElementById("joiningMonth1").value + "-" + document.getElementById("joiningDate1").value + "-" + document.getElementById("joiningYear1").value;
				if (!isDate1(JoiningYear)) {
					document.getElementById("joiningYear1").focus();
					return false;
				}
			}


			if (document.getElementById("joiningYear1").value!='' || document.getElementById("joiningMonth1").value!='' || document.getElementById("joiningDate1").value!='') {

				BirthYear1 = document.getElementById("employeeYear1").value + "-" + document.getElementById("employeeMonth1").value + "-" + document.getElementById("employeeDate1").value;



				JoiningYear1 = document.getElementById("joiningYear1").value + "-" + document.getElementById("joiningMonth1").value + "-" + document.getElementById("joiningDate1").value;



				if (!dateDifference(BirthYear1,JoiningYear1,"-")) {
					messageBox("<?php echo COMPARISON_JOINING_YEAR ?>");
					document.getElementById("joiningYear1").focus();
					return false;
				}
			}

			if (document.getElementById("leavingYear1").value != '' ||  document.getElementById("leavingMonth1").value != '' || document.getElementById("leavingDate1").value !='') {
				LeavingYear = document.getElementById("leavingYear1").value + "-" + document.getElementById("leavingMonth1").value + "-" + document.getElementById("leavingDate1").value;
			if (!dateDifference(LeavingYear,curDate,'-')) {
				messageBox("<?php echo LEAVING_NOT_CURDATE ?>");
				document.getElementById("leavingDate1").focus();
				return false;
				}
			}

			if (document.getElementById("leavingYear1").value!='' || document.getElementById("leavingMonth1").value!='' || document.getElementById("leavingDate1").value!=''){
				LeavingYear = document.getElementById("leavingMonth1").value + "-" + document.getElementById("leavingDate1").value + "-" + document.getElementById("leavingYear1").value;
				if (!isDate1(LeavingYear)){
					document.getElementById("leavingDate1").focus();
					return false;
				}
			}

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

			  if(!isAlphabetCharacters(document.editEmployee.lastName.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.editEmployee.lastName.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.editEmployee.middleName.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.editEmployee.middleName.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.editEmployee.religion.value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.editEmployee.religion.focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("editFatherName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("editFatherName").focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("editMotherName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("editMotherName").focus();
                 return false;
              }

			  if(!isAlphabetCharacters(document.getElementById("editSpouseName").value)) {
                 messageBox("<?php echo ENTER_ALPHABETS ?>");
                 document.getElementById("editSpouseName").focus();
                 return false;
              }

			  if (document.getElementById("leavingYear1").value!='' || document.getElementById("leavingMonth1").value!='' || document.getElementById("leavingDate1").value){
				LeavingYear1 = document.getElementById("leavingYear1").value + "-" + document.getElementById("leavingMonth1").value + "-" + document.getElementById("leavingDate1").value;
				BirthYear1 = document.getElementById("employeeYear1").value + "-" + document.getElementById("employeeMonth1").value + "-" + document.getElementById("employeeDate1").value;
			if (!dateDifference(BirthYear1,LeavingYear1,"-")) {
				messageBox("<?php echo COMPARISON_LEAVING_YEAR ?>");
				document.getElementById("leavingYear1").focus();
				return false;
			  }
			}

			if (document.getElementById("leavingYear1").value!='' || document.getElementById("leavingMonth1").value!='' || document.getElementById("leavingDate1").value){
				LeavingYear1 = document.getElementById("leavingYear1").value + "-" + document.getElementById("leavingMonth1").value + "-" + document.getElementById("leavingDate1").value;

				JoiningYear1 = document.getElementById("joiningYear1").value + "-" + document.getElementById("joiningMonth1").value + "-" + document.getElementById("joiningDate1").value;
			if (!dateDifference(JoiningYear1,LeavingYear1,"-")) {
				messageBox("<?php echo COMPARISON_LEAVING_JOINING_YEAR ?>");
				document.getElementById("leavingYear1").focus();
				return false;
			  }
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
        //return false;
    }
    else if(act=='Edit') {


        editEmployee();
        //return false;
    }
}


//validate form for guest employee

function validateAddGuestForm(frm, act) {
    var fieldsArray = new Array(    new Array("userName","<?php echo ENTER_USER_NAME ?>"),
                                    new Array("userPassword","<?php echo ENTER_USER_PASSWORD ?>"),
                                    new Array("roleName","<?php echo ENTER_USER_ROLE ?>"),
                                    new Array("title","<?php echo ENTER_TITLE ?>"),
                                    new Array("employeeName","<?php echo ENTER_EMPLOYEE_NAME ?>"),
                                    new Array("employeeCode", "<?php echo ENTER_EMPLOYEE_CODE ?>"),
                                    new Array("employeeAbbreviation", "<?php echo ENTER_EMPLOYEE_ABBR ?>"),
                                    new Array ("designation", "<?php echo CHOOSE_EMPLOYEE_DESIGNATION ?> "),
                                    new Array ("branch", "<?php echo CHOOSE_EMPLOYEE_BRANCH ?>")
                                    );

    var len = fieldsArray.length;

    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]!="address2" ) {
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
                    if(document.addGuestEmployee.userName.value != '') {
                if(document.addGuestEmployee.userPassword.value == '') {
                    messageBox("<?php echo ENTER_USER_PASSWORD ?>");
                    document.addGuestEmployee.userPassword.focus();
                    return false;
                }
           if(document.addGuestEmployee.roleName.value == '') {
                    messageBox("<?php echo ENTER_USER_ROLE ?>");
                    document.addGuestEmployee.roleName.focus();
                    return false;
                }
            }
                  if(document.addGuestEmployee.userName.value == '') {
                if(document.addGuestEmployee.userPassword.value != '') {
                    messageBox("<?php echo ENTER_USER_NAME_SELECT_PASSWORD ?>");
                       document.addGuestEmployee.userName.focus();
                    return false;
                }
                if(document.addGuestEmployee.roleName.value != '') {
                    messageBox("<?php echo ENTER_USER_NAME_SELECT_ROLE ?>");
              document.addGuestEmployee.userName.focus();
                    return false;
                }
            }
            if(document.addGuestEmployee.title.value == 1) {
                if(document.addGuestEmployee.gender[0].checked == false){
                    messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
                    document.addGuestEmployee.title.focus();
                    return false;
                }
            }

            if(document.addGuestEmployee.title.value == 2 || document.addGuestEmployee.title.value == 3 || document.addGuestEmployee.title.value == 5) {
                if(document.addGuestEmployee.gender[1].checked == false){
                    messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
                    document.addGuestEmployee.title.focus();
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


            if(document.editGuestEmployee.userName.value != '') {
                if(document.editGuestEmployee.userPassword.value == '') {
                    messageBox("<?php echo ENTER_USER_PASSWORD ?>");
                    document.editGuestEmployee.userPassword.focus();
                    return false;
                }
                if(document.editGuestEmployee.roleName.value == '') {
                    messageBox("<?php echo ENTER_USER_ROLE ?>");
                    document.editGuestEmployee.roleName.focus();
                    return false;
                }
            }

            if(document.editGuestEmployee.userName.value == '') {
                if(document.editGuestEmployee.userPassword.value != '') {
                    messageBox("<?php echo ENTER_USER_NAME_SELECT_PASSWORD ?>");
                    document.editGuestEmployee.userName.focus();
                    return false;
                }
                if(document.editGuestEmployee.roleName.value != '') {
                    messageBox("<?php echo ENTER_USER_NAME_SELECT_ROLE ?>");
                    document.editGuestEmployee.userName.focus();
                    return false;
                }
            }


            if(document.editGuestEmployee.title.value == 1) {
                if(document.editGuestEmployee.gender[0].checked == false){
                    messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
                    document.editGuestEmployee.title.focus();
                    return false;
                }
            }

            if(document.editGuestEmployee.title.value == 2 || document.editGuestEmployee.title.value == 3 || document.editGuestEmployee.title.value == 5) {
                if(document.editGuestEmployee.gender[1].checked == false){
                    messageBox("<?php echo MISMATCH_TITLE_GENDER ?>");
                    document.editGuestEmployee.title.focus();
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
       addGuestEmployee();
       return false;
    }
    else if(act=='Edit') {
        editGuestEmployee();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION addEmployee IS USED TO ADD NEW EMPLOYEE
//
//Author : Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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


         url = '<?php echo HTTP_LIB_PATH; ?>/Employee/ajaxInitAdd.php';

         var isTeaching=0;
         var gender=0;
         var isMarried=0;
	 var receiveSMS=0;
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
	 if(document.addEmployee.receiveSMS[0].checked){
             receiveSMS=document.addEmployee.receiveSMS[0].value;
         }
	else{
             receiveSMS=document.addEmployee.receiveSMS[1].value;
         }
         if(document.addEmployee.isMarried[0].checked){
             isMarried=document.addEmployee.isMarried[0].value;
         }    
		
	else if (document.addEmployee.isMarried[1].checked){
			 isMarried=document.addEmployee.isMarried[1].value;
         }


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
	     receiveSMS:receiveSMS,
             teachingininstitutes : selectedInstitute,
			 defaultInstitute : defaultValue,
             designation:(document.addEmployee.designation.value),
             gender: gender,
             branch:(document.addEmployee.branch.value),
			 department:(document.addEmployee.department.value),
			 panNo:(document.addEmployee.panNo.value),
			 religion:(document.addEmployee.religion.value),
			 caste:(document.addEmployee.caste.value),
			 pfNo:(document.addEmployee.pfNo.value),
			 bankName:(document.addEmployee.bankName.value),
			 accountNo:(document.addEmployee.accountNo.value),
			 branchName:(document.addEmployee.branchName.value),
             country: (document.addEmployee.country.value),
             states: (document.addEmployee.states.value),
             city: (document.addEmployee.city.value),
            // qualification: (document.addEmployee.qualification.value),
             isMarried: isMarried,
             spouseName: (document.addEmployee.spouseName.value),
             fatherName: (document.addEmployee.fatherName.value),
             motherName: (document.addEmployee.motherName.value),
             contactNumber: (document.addEmployee.contactNumber.value),
             mobileNumber: (document.addEmployee.mobileNumber.value),
             email: (document.addEmployee.email.value),
             address1: (document.addEmployee.address1.value),
             address2: (document.addEmployee.address2.value),
             pin: (document.addEmployee.pin.value),
             employeeYear: (document.addEmployee.employeeYear.value),
             employeeMonth: (document.addEmployee.employeeMonth.value),
             employeeDate: (document.addEmployee.employeeDate.value),
             marriageYear: (document.addEmployee.marriageYear.value),
             marriageMonth: (document.addEmployee.marriageMonth.value),
             marriageDate: (document.addEmployee.marriageDate.value),
             joiningYear: (document.addEmployee.joiningYear.value),
             joiningMonth: (document.addEmployee.joiningMonth.value),
             joiningDate: (document.addEmployee.joiningDate.value),
             leavingYear: (document.addEmployee.leavingYear.value),
             leavingMonth: (document.addEmployee.leavingMonth.value),
             leavingDate: (document.addEmployee.leavingDate.value),
			 isActive: (document.addEmployee.isactive.value),
			 hiddenFile: (document.addEmployee.employeePhoto.value),
			 hiddenThumbImage: (document.addEmployee.thumbImage.value),
             esiNumber: (document.addEmployee.esiNumber.value),  //Added by abhiraj for payroll module
			 bloodGroup: (document.addEmployee.bloodGroup.value),
			 remarks: (document.addEmployee.remarks.value)  
				 }, //Added by Sachin for remarks field

             onCreate: function() {
                  //showWaitDialog(true);
              },

             onSuccess: function(transport){
                  //hideWaitDialog(true);
				  if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
					  initAdd();
				  }
				  else {
					messageBox(trim(transport.responseText));
					if ("<?php echo EMPLOYEE_ALREADY_EXIST;?>" == trim(transport.responseText)) {
						document.addEmployee.employeeCode.focus();
					}
					if ("<?php echo EMPLOYEE_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)) {
						document.addEmployee.employeeAbbreviation.focus();
					}
					if ("<?php echo EMPLOYEE_PAN_NO_ALREADY_EXIST;?>" == trim(transport.responseText)) {
						document.addEmployee.panNo.focus();
					}
					if ("<?php echo DUPLICATE_USER;?>" == trim(transport.responseText)) {
						document.addEmployee.userName.focus();
					}

				  }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 
 // function to add new guest employee data
 function addGuestEmployee() {

         var selected=0;
         var selectedDefault=0;
         var selectedInstitute='';
         form = document.addGuestEmployee;

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
         if(document.addGuestEmployee.isTeaching[0].checked){
             isTeaching=document.addGuestEmployee.isTeaching[0].value;
         }
         else{
             isTeaching=document.addGuestEmployee.isTeaching[1].value;
         }
         if(document.addGuestEmployee.gender[0].checked){
             gender=document.addGuestEmployee.gender[0].value;
         }
         else{
             gender=document.addGuestEmployee.gender[1].value;
         }

         url = '<?php echo HTTP_LIB_PATH; ?>/Employee/ajaxInitShortEmployeeAdd.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userName: (document.addGuestEmployee.userName.value),
             userPassword: (document.addGuestEmployee.userPassword.value),
             roleName: (document.addGuestEmployee.roleName.value),
             title: (document.addGuestEmployee.title.value),
             lastName: (document.addGuestEmployee.lastName.value),
             employeeName: (document.addGuestEmployee.employeeName.value),
             middleName: (document.addGuestEmployee.middleName.value),
             employeeCode: (document.addGuestEmployee.employeeCode.value),
             employeeAbbreviation: (document.addGuestEmployee.employeeAbbreviation.value),
             isTeaching : isTeaching,
             teachingininstitutes : selectedInstitute,
             defaultInstitute : defaultValue,
             designation:(document.addGuestEmployee.designation.value),
             gender: gender,
             branch:(document.addGuestEmployee.branch.value),
             department:(document.addGuestEmployee.department.value),
             contactNumber: (document.addGuestEmployee.contactNumber.value),
             mobileNumber: (document.addGuestEmployee.mobileNumber.value),
             email: (document.addGuestEmployee.email.value),
             address1: (document.addGuestEmployee.address1.value),
             address2: (document.addGuestEmployee.address2.value)
                 },

             onCreate: function() {
                  showWaitDialog(true);
              },

             onSuccess: function(transport){
                  hideWaitDialog(true);

                  if(trim(transport.responseText)=="<?php echo DUPLICATE_USER ?>"){
                      messageBox(trim(transport.responseText));
                      document.addGuestEmployee.userName.focus();
                  }
                  else if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
                       flag = true;
                       if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                            //blankValues();
                            blankGuestValues();
                       }
                       else {
                            hiddenFloatingDiv('AddGuestEmployee');
                            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                            //location.reload();
                            return false;
                        }
                     }
                     else{
                            messageBox(trim(transport.responseText));
                            if (trim(transport.responseText)=='<?php echo ADMIN_CANNOT_CREATE ?>'){
                            //document.addHostel.hostelName.value='';
                                document.addGuestEmployee.roleName.focus();
                            }
                            else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ALREADY_EXIST ?>') {
                                document.addGuestEmployee.employeeCode.focus();
                            }
                            else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ABBR_ALREADY_EXIST ?>') {
                                document.addGuestEmployee.employeeAbbreviation.focus();
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE Employee Photo
//Author : Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteEmployeeImage(id) {
         if(false===confirm("Do you want to delete this image?")) {
             return false;
         }
         else {

         var url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxDeleteEmployeeImage.php';
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
//THIS FUNCTION IS USED TO DELETE Employee Photo
//Author : Jaineesh
// Created on : (20.08.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDLImage(id) {
         if(false===confirm("Do you want to delete this image?")) {
             return false;
         }
         else {

         var url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxDeleteEmployeeThumbImage.php';
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
                         document.getElementById('thumbImageDisplayDiv').innerHTML='';
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

function blankValues() {
  var obj=document.getElementById('scroll1').getElementsByTagName('INPUT');
  var obj1 = obj.length;
	for(var h=0 ; h < obj1 ;h++){
		 if(obj[h].type.toUpperCase() == 'CHECKBOX' && obj[h].value != instituteId) {
				obj[h].checked=false;
			 }
	  }

   document.addEmployee.userName.disabled = false;
   document.addEmployee.userName.value = '';
   document.addEmployee.userPassword.value='';
   document.addEmployee.roleName.value='';
   document.addEmployee.title.value='';
   document.addEmployee.lastName.value='';
   document.addEmployee.employeeName.value='';
   document.addEmployee.middleName.value='';
   document.addEmployee.employeeCode.value='';
   document.addEmployee.employeeAbbreviation.value='';
   document.addEmployee.isTeaching[0].checked = true;
   document.addEmployee.designation.value='';
   document.addEmployee.gender[0].checked = true;
   document.addEmployee.branch.value='';
   document.addEmployee.department.value='';
   document.addEmployee.panNo.value='';
   document.addEmployee.religion.value='';
   document.addEmployee.caste.value='';
   document.addEmployee.pfNo.value='';
   document.addEmployee.bankName.value='';
   document.addEmployee.accountNo.value='';
   document.addEmployee.branchName.value='';
   document.addEmployee.country.value='';
   document.addEmployee.states.value='';
   document.addEmployee.states.length=0;
   addOption(document.addEmployee.states,'','Select');
   document.addEmployee.city.value='';
   document.addEmployee.city.length=0;
   addOption(document.addEmployee.city,'','Select');
  // document.addEmployee.qualification.value='';
   document.addEmployee.isMarried[1].checked=true;
   document.getElementById("marriageYear").disabled=true;
   document.getElementById("marriageMonth").disabled=true;
   document.getElementById("marriageDate").disabled=true;
   document.getElementById("spouseName").disabled=true;
   document.addEmployee.spouseName.value='';
   document.addEmployee.fatherName.value='';
   document.addEmployee.motherName.value='';
   document.addEmployee.contactNumber.value='';
   document.addEmployee.mobileNumber.value='';
   document.addEmployee.email.value='';
   document.addEmployee.address1.value='';
   document.addEmployee.address2.value='';
   document.addEmployee.pin.value='';
   document.addEmployee.employeeYear.value='';
   document.addEmployee.employeeMonth.value='';
   document.addEmployee.employeeDate.value='';
   document.addEmployee.marriageYear.value='';
   document.addEmployee.marriageMonth.value='';
   document.addEmployee.marriageDate.value='';
   document.addEmployee.joiningYear.value='';
   document.addEmployee.joiningMonth.value='';
   document.addEmployee.joiningDate.value='';
   document.addEmployee.leavingYear.value=''
   document.addEmployee.leavingMonth.value=''
   document.addEmployee.leavingDate.value=''
   //document.addEmployee.teachingininstitutes.value=''
   document.addEmployee.employeePhoto.value='';
   //Added by abhiraj for payroll module
   document.addEmployee.esiNumber.value='';
   document.addEmployee.bloodGroup.value='';
   document.addEmployee.userName.focus();
   //Added by abhiraj for payroll module Ends
}
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

 function blankGuestValues() {

   // for guest employee
   getLastestEmployeeCode('Add');
   getLastestUserCode('Add');
   document.addGuestEmployee.userName.disabled = false;
   document.addGuestEmployee.lastName.value='';
   document.addGuestEmployee.employeeName.value='';
   document.addGuestEmployee.middleName.value='';
   document.addGuestEmployee.employeeAbbreviation.value='';
   document.addGuestEmployee.isTeaching[0].checked = true;
   document.addGuestEmployee.designation.value='';
   document.addGuestEmployee.gender[0].checked = true;
   document.addGuestEmployee.branch.value='';
   document.addGuestEmployee.contactNumber.value='';
   document.addGuestEmployee.mobileNumber.value='';
   document.addGuestEmployee.email.value='';
   document.addGuestEmployee.address1.value='';
   document.addGuestEmployee.address2.value='';
   document.addGuestEmployee.userName.focus();
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO UPDATE THE EXISTING RECOORD
//
//Author : Jaineesh
// Created on : (13.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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

		if(eval("document.getElementById('teachingininstitutes1_"+defaultValue+"').checked==false")){
			alert("<?php echo DEFAULT_INSTITUTE_SELECTED?>");
			return false;
		}



         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitEdit.php';
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
	 if(document.editEmployee.receiveSMS[0].checked){
             receiveSMS=document.editEmployee.receiveSMS[0].value;
         }
	 else{
             receiveSMS=document.editEmployee.receiveSMS[1].value;
         }
         if(document.editEmployee.isMarried[0].checked){
             isMarried=document.editEmployee.isMarried[0].value;
         }

         if (document.editEmployee.isMarried[1].checked){
             isMarried=document.editEmployee.isMarried[1].value;
         }

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
			 panNo:(document.editEmployee.panNo.value),
			 religion:(document.editEmployee.religion.value),
			 caste:(document.editEmployee.caste.value),
			 pfNo:(document.editEmployee.pfNo.value),
			 bankName:(document.editEmployee.bankName.value),
			 accountNo:(document.editEmployee.accountNo.value),
			 branchName:(document.editEmployee.branchName.value),
             country: (document.editEmployee.country.value),
             states: (document.editEmployee.states.value),
             city: (document.editEmployee.city.value),
             //qualification: (document.editEmployee.qualification.value),
             isMarried: isMarried,
	     receiveSMS:receiveSMS,
             spouseName: (document.editEmployee.editSpouseName.value),
             fatherName: (document.editEmployee.editFatherName.value),
             motherName: (document.editEmployee.editMotherName.value),
             contactNumber: (document.editEmployee.editContactNumber.value),
             mobileNumber: (document.editEmployee.editMobileNumber.value),
             email: (document.editEmployee.emailEdit.value),
             address1: (document.editEmployee.address1.value),
             address2: (document.editEmployee.address2.value),
             pin: (document.editEmployee.pin.value),
             employeeYear: (document.editEmployee.employeeYear1.value),
             employeeMonth: (document.editEmployee.employeeMonth1.value),
             employeeDate: (document.editEmployee.employeeDate1.value),
             marriageYear: (document.editEmployee.marriageYear1.value),
             marriageMonth: (document.editEmployee.marriageMonth1.value),
             marriageDate: (document.editEmployee.marriageDate1.value),
             joiningYear: (document.editEmployee.joiningYear1.value),
             joiningMonth: (document.editEmployee.joiningMonth1.value),
             joiningDate: (document.editEmployee.joiningDate1.value),
             leavingYear: (document.editEmployee.leavingYear1.value),
             leavingMonth: (document.editEmployee.leavingMonth1.value),
             leavingDate: (document.editEmployee.leavingDate1.value),
			 isActive: (document.editEmployee.isactive.value),
             employeeId: (document.editEmployee.employeeId.value),
             userId: (document.editEmployee.userId.value),
			 hiddenFile: (document.editEmployee.employeePhoto.value),
			 hiddenThumbImage: (document.editEmployee.thumbImage.value),
             esiNumber: (document.editEmployee.esiNumber.value),    //added by abhiraj payroll module
			 bloodGroup: (document.editEmployee.bloodGroup.value),
			 remarks: (document.editEmployee.remarks.value)  
				 }, //Added by Sachin for remarks field

				onCreate: function() {
                  //showWaitDialog(true);
               },

                onSuccess: function(transport){
					//initEdit();
                   //hideWaitDialog(true);
                   if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
					    initEdit();
                   }
				   else {
                        messageBox(trim(transport.responseText));
						if ("<?php echo EMPLOYEE_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.editEmployee.employeeCode.focus();
						}
						if ("<?php echo EMPLOYEE_ABBR_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.editEmployee.employeeAbbreviation.focus();
						}
						if ("<?php echo EMPLOYEE_PAN_NO_ALREADY_EXIST;?>" == trim(transport.responseText)) {
							document.editEmployee.panNo.focus();
						}
						if ("<?php echo DUPLICATE_USER;?>" == trim(transport.responseText)) {
							document.editEmployee.userName.focus();
						}
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//edit for guest employee
function editGuestEmployee() {

         pars = generateQueryString('editGuestEmployee');
		// alert(pars);
		 var selected=0;
         var selectedDefault=0;
         var selectedInstitute='';
         form = document.editGuestEmployee;
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

		selectedInstuteArray = selectedInstitute.split(',');
		defaultInstituteSelected = false;
		for (b=0; b < selectedInstuteArray.length; b++) {
			if (selectedInstuteArray[b] == defaultValue) {
				defaultInstituteSelected = true;
				break;
			}
		}
		if (defaultInstituteSelected == false) {
			alert("<?php echo DEFAULT_INSTITUTE_SELECTED?>");
			return false;
		}

        var isTeaching=0;
         var gender=0;
         var isMarried=0;
         if(document.editGuestEmployee.isTeaching[0].checked){
             isTeaching=document.editGuestEmployee.isTeaching[0].value;
         }
         else{
             isTeaching=document.editGuestEmployee.isTeaching[1].value;
         }
         if(document.editGuestEmployee.gender[0].checked){
             gender=document.editGuestEmployee.gender[0].value;
         }
         else{
             gender=document.editGuestEmployee.gender[1].value;
            
         }


         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitShortEmployeeEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userName: (document.editGuestEmployee.userName.value),
             userPassword: (document.editGuestEmployee.userPassword.value),
             roleName: (document.editGuestEmployee.roleName.value),
             title: (document.editGuestEmployee.title.value),
             lastName: (document.editGuestEmployee.lastName.value),
             employeeName: (document.editGuestEmployee.employeeName.value),
             middleName: (document.editGuestEmployee.middleName.value),
             employeeCode:(document.editGuestEmployee.employeeCode.value),
             employeeAbbreviation: (document.editGuestEmployee.employeeAbbreviation.value),
             isTeaching: isTeaching,
             teachingininstitutes : selectedInstitute,
             defaultInstitute : defaultValue,
             designation:(document.editGuestEmployee.designation.value),
             gender: gender,
             branch:(document.editGuestEmployee.branch.value),
             department:(document.editGuestEmployee.department.value),
             contactNumber: (document.editGuestEmployee.editContactNumber.value),
             mobileNumber: (document.editGuestEmployee.editMobileNumber.value),
             email: (document.editGuestEmployee.emailEdit.value),
             address1: (document.editGuestEmployee.address1.value),
             address2: (document.editGuestEmployee.address2.value),
             employeeId: (document.editGuestEmployee.employeeId.value),
             userId: (document.editGuestEmployee.userId.value)
                 },

                onCreate: function() {
                  showWaitDialog(true);
               },

                onSuccess: function(transport){
                   hideWaitDialog(true);
                   if("<?php echo SUCCESS;?>" == trim(transport.responseText )) {
                        hiddenFloatingDiv('EditGuestEmployeeDiv');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                   }
                   else {
                        messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo ADMIN_CANNOT_CREATE ?>'){
                        //document.addHostel.hostelName.value='';
                            document.editGuestEmployee.roleName.focus();
                        }
                        else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ALREADY_EXIST ?>') {
                            document.editGuestEmployee.employeeCode.focus();
                        }
                        else if(trim(transport.responseText)=='<?php echo EMPLOYEE_ABBR_ALREADY_EXIST ?>') {
                            document.editGuestEmployee.employeeAbbreviation.focus();
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) { 

	form = document.editEmployee;
         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxGetValues.php';
		 document.getElementById('imageDisplayDiv').innerHTML='';
		 document.getElementById('txtEmpImage').innerHTML='';
		 document.getElementById('thumbImageDisplayDiv').innerHTML = '';
		 document.getElementById('txtEmpThumbImage').innerHTML = '';
         document.editEmployee.reset();

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
					document.editEmployee.remarks.value=j.edit[0].remarks;    //Added by Sachin to accommodate remarks field
                   if(j.edit[0].isTeaching == "1"){
                   document.editEmployee.isTeaching[0].checked=true;
                   }
                   else if(j.edit[0].isTeaching == "0"){
                   document.editEmployee.isTeaching[1].checked=true;
                   }
			
		   if(j.edit[0].receiveSMS == "1"){
                   document.editEmployee.receiveSMS[0].checked=true;
                   }
                   else if(j.edit[0].receiveSMS == "0"){
                   document.editEmployee.receiveSMS[1].checked=true;
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
				   document.editEmployee.panNo.value=j.edit[0].panNo;
				   document.editEmployee.religion.value=j.edit[0].religion;
				   document.editEmployee.caste.value=j.edit[0].caste;
				   document.editEmployee.pfNo.value=j.edit[0].providentFundNo;
				   document.editEmployee.bankName.value=j.edit[0].bankName;
				   document.editEmployee.accountNo.value=j.edit[0].accountNo;
				   document.editEmployee.branchName.value=j.edit[0].branchName;

				   if(j.edit[0].countryId == '') {
					 document.editEmployee.country.value = '';
				   }
				   else {
					document.editEmployee.country.value = j.edit[0].countryId;
				   }

				   if(j.edit[0].stateId == '') {
					document.editEmployee.states.value = '';
				   }
				   else {
					   //alert(j[0].states.length);
					len = j.state.length;
					document.editEmployee.states.length = null;
                   // add option Select initially
                   addOption(document.editEmployee.states, '', 'Select');
                   for(i=0;i<len;i++) {
                     addOption(document.editEmployee.states, j.state[i].stateId, j.state[i].stateName);
                   }
					document.editEmployee.states.value = j.edit[0].stateId;
				   }

				   if(j.edit[0].cityId == '') {
					document.editEmployee.city.value = '';
				   }
				   else {
					len = j.city.length;
                   document.editEmployee.city.length = null;
                   // add option Select initially
                   addOption(document.editEmployee.city, '', 'Select');
                   for(i=0;i<len;i++) {
                     addOption(document.editEmployee.city, j.city[i].cityId, j.city[i].cityName);
                   }
					document.editEmployee.city.value = j.edit[0].cityId;
				   }
                   //document.editEmployee.qualification.value=j[0]['qualification'];
		   if(j.edit[0].isActive=='1'){
		  document.editEmployee.isactive.value='1';
		     }
		 else{
		   document.editEmployee.isactive.value='0';
		}
   
                   if(j.edit[0].isMarried == "1"){

                       document.editEmployee.isMarried[0].checked=true;
					   document.editEmployee.marriageYear1.value="";
						document.editEmployee.marriageYear1.disabled=false;
						document.editEmployee.marriageMonth1.value="";
						document.editEmployee.marriageMonth1.disabled=false;
						document.editEmployee.marriageDate1.value="";
						document.editEmployee.marriageDate1.disabled=false;
						document.editEmployee.editSpouseName.value="";
						document.editEmployee.editSpouseName.disabled=false;
                   }
                   else if(j.edit[0].isMarried == "0"){

                       document.editEmployee.isMarried[1].checked=true;
					   document.editEmployee.marriageYear1.value="";
						document.editEmployee.marriageYear1.disabled=true;
						document.editEmployee.marriageMonth1.value="";
						document.editEmployee.marriageMonth1.disabled=true;
						document.editEmployee.marriageDate1.value="";
						document.editEmployee.marriageDate1.disabled=true;
						document.editEmployee.editSpouseName.value="";
						document.editEmployee.editSpouseName.disabled=true;
                   }
                   document.editEmployee.editSpouseName.value=j.edit[0].spouseName;
                   document.editEmployee.editFatherName.value=j.edit[0].fatherName;
                   document.editEmployee.editMotherName.value=j.edit[0].motherName;
                   document.editEmployee.editContactNumber.value=j.edit[0].contactNumber;
                   document.editEmployee.editMobileNumber.value=j.edit[0].mobileNumber;
                   document.editEmployee.emailEdit.value=j.edit[0].emailAddress;
                   document.editEmployee.address1.value=j.edit[0].address1;
                   document.editEmployee.address2.value=j.edit[0].address2;
                   document.editEmployee.pin.value=j.edit[0].pinCode;
                   document.editEmployee.employeeYear1.value='';
				   document.editEmployee.employeeYear1.value=j.edit[0].employeeYear; 


				   if (j.edit[0].employeeYear=="0000") {
						document.editEmployee.employeeYear1.value="";
				   }
                   document.editEmployee.employeeMonth1.value='';
				   document.editEmployee.employeeMonth1.value=j.edit[0].employeeMonth;
				   if (j.edit[0].employeeMonth =="00") {
						document.editEmployee.employeeMonth1.value="";
				   }
				   document.editEmployee.employeeDate1.value='';

				   if (j.edit[0].employeeDate<10){
					document.editEmployee.employeeDate1.value="0"+parseInt(j.edit[0].employeeDate,10);
				   }
				   else {
					document.editEmployee.employeeDate1.value=parseInt(j.edit[0].employeeDate);
				   }

				   if (j.edit[0].employeeDate == "00") {
						document.editEmployee.employeeDate1.value="";
				   }

                   document.editEmployee.marriageYear1.value='';

                   document.editEmployee.marriageYear1.value = j.edit[0].marriageYear;

				   if (j.edit[0].marriageYear == "0000") {
					document.editEmployee.marriageYear1.value="";
				   }

				   document.editEmployee.marriageMonth1.value="";
                   document.editEmployee.marriageMonth1.value=j.edit[0].marriageMonth;

				   if (j.edit[0].marriageMonth=="00") {
					document.editEmployee.marriageMonth1.value="";
				   }

				   document.editEmployee.marriageDate1.value="";
				   if (j.edit[0].marriageDate<10) {
					document.editEmployee.marriageDate1.value="0"+parseInt(j.edit[0].marriageDate,10);
				   }
				   else {
					document.editEmployee.marriageDate1.value=parseInt(j.edit[0].marriageDate);
				   }


				   if (j.edit[0].marriageDate =="00") {
					document.editEmployee.marriageDate1.value="";
				   }
				   document.editEmployee.joiningYear1.value='';
                   document.editEmployee.joiningYear1.value=j.edit[0].joiningYear;

				   if (j.edit[0].joiningYear == "0000") {
					document.editEmployee.joiningYear1.value="";
				   }
				   document.editEmployee.joiningMonth1.value='';
				   document.editEmployee.joiningMonth1.value=j.edit[0].joiningMonth;
				   if (j.edit[0].joiningMonth == "00") {
					document.editEmployee.joiningMonth1.value="";
				   }
				   document.editEmployee.joiningDate1.value='';


				   if (j.edit[0].joiningDate<10){
					document.editEmployee.joiningDate1.value='0'+parseInt(j.edit[0].joiningDate,10);
				   }
				   else {
					document.editEmployee.joiningDate1.value=parseInt(j.edit[0].joiningDate);
				   }

				   if (j.edit[0].joiningDate == "00") {
					document.editEmployee.joiningDate1.value="";
				   }


				   document.editEmployee.leavingYear1.value='';
                   document.editEmployee.leavingYear1.value=j.edit[0].leavingYear;
				   if (j.edit[0].leavingYear == "0000") {
					document.editEmployee.leavingYear1.value="";
				   }
				   document.editEmployee.leavingMonth1.value='';
                   document.editEmployee.leavingMonth1.value=j.edit[0].leavingMonth;
				   if (j.edit[0].leavingMonth == "00") {
					document.editEmployee.leavingMonth1.value="";
				   }

				   document.editEmployee.leavingDate1.value='';
				   if (j.edit[0].leavingDate<10) {
					document.editEmployee.leavingDate1.value="0"+parseInt(j.edit[0].leavingDate,10);
				   }
				   else {
					document.editEmployee.leavingDate1.value=parseInt(j.edit[0].leavingDate);
				   }
				   if (j.edit[0].leavingDate=="00") {
					document.editEmployee.leavingDate1.value="";
				   }

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

				   if(trim(j.edit[0].employeeImage) == '-1') {
					document.getElementById('imageDisplayDiv').innerHTML = '';
					document.getElementById('txtEmpImage').innerHTML = '';
				   }


				   if(trim(j.edit[0].employeeImage)!= '-1') {
                     var d = new Date();
                     rndNo = d.getTime();
                     document.getElementById('imageDisplayDiv').innerHTML='<img src="'+imagePathURL+'/Employee/'+j.edit[0].employeeImage+'?'+rndNo+'"  alt="pic " style="width:30px;height:25px;border:2px solid grey" onclick=download("'+j.edit[0].employeeImage+'");>';
                     //if(mode==1){
                         document.getElementById('imageDisplayDiv').innerHTML +='<a onclick="deleteEmployeeImage('+j.edit[0].employeeId+')"><img src="'+imagePathURL+'/delete.gif" style="margin-bottom-4px" alt="Delete" title="Delete Image" ></a>';
                     //}
					 document.getElementById('txtEmpImage').innerHTML = 'Employee Image';
                   }
				   if(trim(j.edit[0].thumbImage) == '-1') {
					document.getElementById('thumbImageDisplayDiv').innerHTML = '';
					document.getElementById('txtEmpThumbImage').innerHTML = '';
				   }

				   if(trim(j.edit[0].thumbImage)!= '-1') {
                     var d = new Date();
                     rndNo = d.getTime();
                     document.getElementById('thumbImageDisplayDiv').innerHTML='<img src="'+imagePathURL+'/Employee/ThumbImage/'+j.edit[0].thumbImage+'?'+rndNo+'" style="width:30px;height:25px;border:2px solid grey" onclick=downloadDL("'+j.edit[0].thumbImage+'");>';
                     //if(mode==1){
                         document.getElementById('thumbImageDisplayDiv').innerHTML +='<a onclick="deleteDLImage('+j.edit[0].employeeId+')"><img src="'+imagePathURL+'/delete.gif" style="margin-bottom-4px" alt="Delete" title="Delete Image" ></a>';
                     //}
					 document.getElementById('txtEmpThumbImage').innerHTML = 'Employee Thumb Image';
                   }
				   //document.editEmployee.isactive.value = j.edit[0].isActive; 
				   if(j.edit[0].userId == 0 ) {
					document.editEmployee.userName.focus();
				   }
				   else {
					document.editEmployee.employeeName.focus();
				   }
                   if(trim(j.edit[0].esiNumber)=="") {
                       document.editEmployee.esiNumber.value='';
                   }
                   else {
                      document.editEmployee.esiNumber.value=j.edit[0].esiNumber;
                   }
					if(trim(j.edit[0].bloodGroup) == 0 || trim(j.edit[0].bloodGroup) == '') {
						document.editEmployee.bloodGroup.value = '';
					}
					else {
						document.editEmployee.bloodGroup.value = j.edit[0].bloodGroup;
					}

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
 //for guest employee

 function populateGuestValues(id) {


    form = document.editGuestEmployee;
         url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxGetShortEmployeeValues.php';
         document.editGuestEmployee.reset();

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
                    hiddenFloatingDiv('EditGuestEmployeeDiv');
                    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                  }
                    j = eval('('+trim(transport.responseText)+')');
                    if(j.edit[0].userId != 0) {
                        document.editGuestEmployee.userName.disabled = true;
                        document.editGuestEmployee.userName.value = j.edit[0].userName;
                        document.editGuestEmployee.userPassword.value="********";
                        document.editGuestEmployee.roleName.value = j.edit[0].roleId;
                    }
                    else {
                        document.editGuestEmployee.userName.disabled = false;
                        document.editGuestEmployee.userPassword.value='';
                        document.editGuestEmployee.roleName.value = '';
                    }

                    if(trim(j.edit[0].title) == '' || trim(j.edit[0].title) == 0) {
                        document.editGuestEmployee.title.value = '';
                    }
                    else {
                        document.editGuestEmployee.title.value=j.edit[0].title;
                    }
                    document.editGuestEmployee.lastName.value=j.edit[0].lastName;
                    document.editGuestEmployee.employeeName.value=j.edit[0].employeeName;
                    document.editGuestEmployee.middleName.value=j.edit[0].middleName;
                    document.editGuestEmployee.employeeCode.value=j.edit[0].employeeCode;
                    document.editGuestEmployee.employeeAbbreviation.value=j.edit[0].employeeAbbreviation;

                   if(j.edit[0].isTeaching == "1"){
                   document.editGuestEmployee.isTeaching[0].checked=true;
                   }
                   else if(j.edit[0].isTeaching == "0"){
                   document.editGuestEmployee.isTeaching[1].checked=true;
                   }

                   document.editGuestEmployee.designation.value=j.edit[0].designationId;
                   if(j.edit[0].gender == "M"){
                   document.editGuestEmployee.gender[0].checked=true;
                    }
                   else if(j.edit[0].gender == "F"){
                   document.editGuestEmployee.gender[1].checked=true;
                    }

                   document.editGuestEmployee.branch.value=j.edit[0].branchId;

                   if(j.edit[0].departmentId == '') {
                        document.editGuestEmployee.department.value = '';
                   }
                   else {
                       document.editGuestEmployee.department.value=j.edit[0].departmentId;
                   }
                   document.editGuestEmployee.editContactNumber.value=j.edit[0].contactNumber;
                   document.editGuestEmployee.editMobileNumber.value=j.edit[0].mobileNumber;
                   document.editGuestEmployee.emailEdit.value=j.edit[0].emailAddress;
                   document.editGuestEmployee.address1.value=j.edit[0].address1;
                   document.editGuestEmployee.address2.value=j.edit[0].address2;
                   document.editGuestEmployee.employeeId.value=j.edit[0].employeeId;

                   document.editGuestEmployee.userId.value=j.edit[0].userId;
	           if(j.edit[0].isActive == '1') {
                         document.editGuestEmployee.isactive.value=1;
                   }
                   else{
                      document.editGuestEmployee.isactive.value=0;
                     }


                   var obj=document.getElementById('scroll2').getElementsByTagName('INPUT');
                   var len1=obj.length;
                    for(var n=0 ; n < len1 ;n++){
                         if(obj[n].type.toUpperCase() == 'CHECKBOX') {
                              obj[n].checked=false;
                          }
                    }

				   var c=0;
				   for(var i=1;i<form.length;i++){
					  if(form.elements[i].type=="checkbox"){
						if(form.elements[i].value==j.edit[c].instituteId) {
						  form.elements[i].checked=true;
						  c++;
						}
					  }
					}

                   if(j.edit[0].userId == 0 ) {
                    document.editGuestEmployee.userName.focus();
                   }
                   else {
                    document.editGuestEmployee.employeeName.focus();
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//-------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Jaineesh
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------
//id:id
//type:states/city
//target:taget dropdown box

function autoPopulate(val,type,frm)
{
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   if(frm=="Add"){
       if(type=="states"){
            document.addEmployee.states.options.length=0;
            var objOption = new Option("Select","");
            document.addEmployee.states.options.add(objOption);

            var objOption = new Option("Select","");
            document.addEmployee.city.options.length=0;
            document.addEmployee.city.options.add(objOption);
       }
      else {
			document.addEmployee.city.options.length=0;
			var objOption = new Option("Select","");
			document.addEmployee.city.options.add(objOption);
      }
   }
   else{
        if(type=="states"){
            document.editEmployee.states.options.length=0;
            var objOption = new Option("Select","");
            document.editEmployee.states.options.add(objOption);

            document.editEmployee.city.options.length=0;
            var objOption = new Option("Select","");
            document.editEmployee.city.options.add(objOption);
       }
      else {
           document.editEmployee.city.options.length=0;
           var objOption = new Option("Select","");
           document.editEmployee.city.options.add(objOption);
      }
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {type: type,id: val},

             onCreate: function() {
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                  hideWaitDialog(true);
                    j = eval('('+transport.responseText+')');

					for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                             if(type=="states"){
                                var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.addEmployee.states.options.add(objOption);
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.addEmployee.city.options.add(objOption);
                            }
                          }
                      else{
                            if(type=="states"){
                                 var objOption = new Option(j[c].stateName,j[c].stateId);
                                 document.editEmployee.states.options.add(objOption);
                             }
                            else{
                                 var objOption = new Option(j[c].cityName,j[c].cityId);
                                 document.editEmployee.city.options.add(objOption);
                            }
                          }
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function initAdd() {

	document.getElementById('addEmployeeForm').target = 'uploadTargetAdd';
	document.getElementById('addEmployeeForm').action = '<?php echo HTTP_LIB_PATH;?>/Employee/fileUpload.php';
    document.getElementById('addEmployeeForm').submit();
}

function initEdit() {
	//showWaitDialog(true);
    document.getElementById('editEmployeeForm').target = 'uploadTargetEdit';
	document.getElementById('editEmployeeForm').action = '<?php echo HTTP_LIB_PATH;?>/Employee/fileUpload.php';
    document.getElementById('editEmployeeForm').submit();
}

function  download(str){
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/Employee/"+str;
  window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function  downloadDL(str){
  str = escape(str);
  var address="<?php echo IMG_HTTP_PATH;?>/Employee/ThumbImage/"+str;
  window.open(address,"ThumbImage","status=1,resizable=1,width=800,height=600")
}


function printReport() {
	path='<?php echo UI_HTTP_PATH;?>/displayEmployeeReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayroomReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayEmployeeCSV.php?'+qstr;
	window.location = path;
}

function fileUploadError(str,mode) {
	//alert(str);
   hideWaitDialog(true);
   globalFL=1;

   if("<?php echo DUPLICATE_USER;?>" == trim(str)) {
       messageBox(trim(str));
	   //document.addEmployee.userName.value='';
	   document.addEmployee.userName.focus();
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
	   //alert(str);
	   /*if("<?php echo 'Employee code already exists';?>" == trim(str)) {
       messageBox(trim(str));
	   document.addEmployee.employeeCode.focus();
	   return false;
   }*/
	   if("<?php echo SUCCESS;?>" != trim(str)) {
		 alert(str);
		 document.addEmployee.employeeCode.focus();
		return false;
	   }

      if("<?php echo SUCCESS;?>" == trim(str)) {
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
   /*else {
		messageBox(trim(transport.responseText));
		document.addEmployee.employeeCode.focus();
	 }*/
   }
   else if(mode==2){
	   if("<?php echo SUCCESS;?>" != trim(str)) {
		 alert(str);
		 document.editEmployee.employeeCode.focus();
		return false;
	   }
	//alert(str);
	   /*if("<?php echo DUPLICATE_USER;?>" == trim(str)) {
       messageBox(trim(str));
	   document.editEmployee.employeeCode.focus();
	   return false;
   }*/

      if("<?php echo SUCCESS;?>" == trim(str)) {
			hiddenFloatingDiv('EditEmployeeDiv');
			sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
			return false;
	   }
	   /*else {
			messageBox(trim(transport.responseText));
			document.editEmployee.employeeCode.focus();
	   }*/
   }

   else{
      messageBox(trim(str));
   }
}

function printTimeTableEmployee(employeeId,labelId,timeTableType) {
	path='<?php echo UI_HTTP_PATH;?>/teacherTimeTableReportPrint.php?labelId='+labelId+'&teacherId='+employeeId+'&timeTableType='+timeTableType+'&typeFormat=t';
    window.open(path,"DisplayTeacherTimeTable","status=1,menubar=1,scrollbars=1, width=900");
}

//populate list
window.onload=function(){
   // alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
	   //if("<?php echo $listEmployee?>" != 1) {
			sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
	   //}
   }
   else {
		sendReq(listURL,divResultName,searchFormName,'',false);
   }
}

</script>
</head>
<body>
	<?php
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Employee/listEmployeeContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listEmployee.php $
//
//*****************  Version 42  *****************
//User: Jaineesh     Date: 4/15/10    Time: 6:56p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003247, 0003250, 0003174
//
//*****************  Version 41  *****************
//User: Jaineesh     Date: 4/06/10    Time: 7:27p
//Updated in $/LeapCC/Interface
//issue resolved No. 0003219
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 4/06/10    Time: 2:31p
//Updated in $/LeapCC/Interface
//make the size of image small during populate of images
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 4/01/10    Time: 5:20p
//Updated in $/LeapCC/Interface
//fixed error
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 37  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Interface
//changes for gap analysis in employee master
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 2/20/10    Time: 6:46p
//Updated in $/LeapCC/Interface
//change in code to show values in add
//
//*****************  Version 35  *****************
//User: Jaineesh     Date: 2/17/10    Time: 12:35p
//Updated in $/LeapCC/Interface
//provide the facility to change institute of an employee
//
//*****************  Version 34  *****************
//User: Gurkeerat    Date: 1/22/10    Time: 1:05p
//Updated in $/LeapCC/Interface
//updated default value for radio button
//
//*****************  Version 33  *****************
//User: Jaineesh     Date: 12/26/09   Time: 6:30p
//Updated in $/LeapCC/Interface
//fixed bug no.0002326
//
//*****************  Version 32  *****************
//User: Jaineesh     Date: 12/18/09   Time: 4:07p
//Updated in $/LeapCC/Interface
//show selected default institute of employee
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 11/05/09   Time: 5:33p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001936,0001938,0001939
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 10/22/09   Time: 4:31p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001854, 0001827, 0001828, 0001829, 0001830, 0001831,
//0001832, 0001834, 0001836, 0001837, 0001838, 0001839, 0001840, 0001841,
//0001842, 0001843, 0001845, 0001842, 0001833, 0001844, 0001835, 0001826,
//0001849
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 10/03/09   Time: 6:08p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001681, 0001680, 0001679, 0001678, 0001677, 0001676,
//0001675, 0001666, 0001665, 0001664, 0001631, 0001614, 0001682, 0001610
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 10/03/09   Time: 12:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.0001664, 0001665, 0001666
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 9/22/09    Time: 6:43p
//Updated in $/LeapCC/Interface
//change breadcrumb & put department in employee
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 9/18/09    Time: 7:16p
//Updated in $/LeapCC/Interface
//fixed bug during self testing
//
//*****************  Version 25  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Interface
//issue fix 1519, 1518, 1517, 1473, 1442, 1451
//validiations & formatting updated
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 9/01/09    Time: 2:08p
//Updated in $/LeapCC/Interface
//Modification in code while saving & edit record in IE browser.
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 8/31/09    Time: 7:33p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0001366, 0001358, 0001305, 0001304, 0001282
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 09-08-24   Time: 1:05p
//Updated in $/LeapCC/Interface
//Updated with Institute Wise Checks including ACCESS rights DEFINE
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/18/09    Time: 7:37p
//Updated in $/LeapCC/Interface
//Remove administrator role from role type so that no new administrator
//can be made and syenergy will be administrator and Applied time
//validation so that start time can not be greater than end time.
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/10/09    Time: 10:18a
//Updated in $/LeapCC/Interface
//give print & export to excel facility
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 7/23/09    Time: 10:53a
//Updated in $/LeapCC/Interface
//check date of leaving with date of birth & put new message
//
//*****************  Version 18  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Interface
//role permission,alignment, new enhancements added
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 7/13/09    Time: 12:51p
//Updated in $/LeapCC/Interface
//modification in validations
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 7/09/09    Time: 3:22p
//Updated in $/LeapCC/Interface
//fixed bug no.0000358
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/08/09    Time: 10:46a
//Updated in $/LeapCC/Interface
//fixed bug no.0000358
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 6/30/09    Time: 2:20p
//Updated in $/LeapCC/Interface
//make select all & none teach in institutes and some correction
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 6/30/09    Time: 12:01p
//Updated in $/LeapCC/Interface
//Make the correction in employee code should be unique
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 6/26/09    Time: 6:33p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000179,0000178,0000173,0000172,0000174,0000171,
//0000170, 0000169,0000168,0000167,0000140,0000139,0000138,0000137,
//0000135,0000134,0000136,0000133,0000132,0000131,0000130,
//0000129,0000128,0000127,0000126,0000125
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 6/25/09    Time: 6:18p
//Updated in $/LeapCC/Interface
//fixed bug no.0000202,0000177,0000176,0000175
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 6/24/09    Time: 3:03p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000258,0000260,0000265,0000270,0000255
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//validation, formatting, themes base css templates changes
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 5/27/09    Time: 7:34p
//Updated in $/LeapCC/Interface
//fixed bugs & enhancement No.1071,1072,1073,1074,1075,1076,1077,1079
//issues of Issues [25-May-09]Build# cc0006.doc
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 5/19/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//show print during search
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/13/09    Time: 12:48p
//Updated in $/LeapCC/Interface
//show new field in list emp. abbr.
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/19/08   Time: 3:30p
//Updated in $/LeapCC/Interface
//modified for employee can teach in
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:22p
//Updated in $/LeapCC/Interface
//modification for required fields
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 11/19/08   Time: 5:30p
//Updated in $/Leap/Source/Interface
//add new field status (active or deactive)
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 11/19/08   Time: 11:22a
//Updated in $/Leap/Source/Interface
//show message
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 11/06/08   Time: 12:43p
//Updated in $/Leap/Source/Interface
//define access module
//
//*****************  Version 37  *****************
//User: Jaineesh     Date: 11/05/08   Time: 3:20p
//Updated in $/Leap/Source/Interface
//modification in the length of fields
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 11/04/08   Time: 11:55a
//Updated in $/Leap/Source/Interface
//modified the code for date functions
//
//*****************  Version 35  *****************
//User: Jaineesh     Date: 10/23/08   Time: 10:32a
//Updated in $/Leap/Source/Interface
//modified in message
//
//*****************  Version 34  *****************
//User: Jaineesh     Date: 10/13/08   Time: 3:49p
//Updated in $/Leap/Source/Interface
//embedded print option
//
//*****************  Version 33  *****************
//User: Jaineesh     Date: 9/29/08    Time: 3:37p
//Updated in $/Leap/Source/Interface
//modified in query
//
//*****************  Version 32  *****************
//User: Jaineesh     Date: 9/29/08    Time: 2:10p
//Updated in $/Leap/Source/Interface
//modified for user name
//
//*****************  Version 31  *****************
//User: Jaineesh     Date: 9/25/08    Time: 6:08p
//Updated in $/Leap/Source/Interface
//fixed bug
//
//*****************  Version 30  *****************
//User: Jaineesh     Date: 9/25/08    Time: 4:39p
//Updated in $/Leap/Source/Interface
//fixed bug
//
//*****************  Version 29  *****************
//User: Jaineesh     Date: 8/29/08    Time: 6:10p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 28  *****************
//User: Jaineesh     Date: 8/28/08    Time: 12:06p
//Updated in $/Leap/Source/Interface
//modified in indentation
//
//*****************  Version 27  *****************
//User: Jaineesh     Date: 8/27/08    Time: 12:42p
//Updated in $/Leap/Source/Interface
//modified in date comparison function
//
//*****************  Version 26  *****************
//User: Jaineesh     Date: 8/25/08    Time: 12:56p
//Updated in $/Leap/Source/Interface
//modified in edit window function for edit floating div
//
//*****************  Version 25  *****************
//User: Jaineesh     Date: 8/23/08    Time: 12:46p
//Updated in $/Leap/Source/Interface
//modified in validation for dates
//
//*****************  Version 24  *****************
//User: Jaineesh     Date: 8/20/08    Time: 6:32p
//Updated in $/Leap/Source/Interface
//modified in messages
//
//*****************  Version 23  *****************
//User: Jaineesh     Date: 8/11/08    Time: 7:37p
//Updated in $/Leap/Source/Interface
//modified for checking dates
//
//*****************  Version 22  *****************
//User: Jaineesh     Date: 8/09/08    Time: 6:21p
//Updated in $/Leap/Source/Interface
//modified in Employee - bug removed
//
//*****************  Version 21  *****************
//User: Jaineesh     Date: 8/08/08    Time: 5:15p
//Updated in $/Leap/Source/Interface
//modification for dates
//
//*****************  Version 20  *****************
//User: Jaineesh     Date: 8/07/08    Time: 8:06p
//Updated in $/Leap/Source/Interface
//modified for delete & edit messages
//
//*****************  Version 19  *****************
//User: Jaineesh     Date: 8/01/08    Time: 2:27p
//Updated in $/Leap/Source/Interface
//modified in screen height & width
//
//*****************  Version 18  *****************
//User: Jaineesh     Date: 7/29/08    Time: 7:36p
//Updated in $/Leap/Source/Interface
//check for user name entry
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:37p
//Updated in $/Leap/Source/Interface
//modified in cursor position for edit
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 7/19/08    Time: 5:43p
//Updated in $/Leap/Source/Interface
//get foucs on employee code text field if fould duplicate record
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 7/18/08    Time: 4:03p
//Updated in $/Leap/Source/Interface
//change alert into messagebox
//
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 7/17/08    Time: 10:31a
//Updated in $/Leap/Source/Interface
//double pop up is deleted on save record
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 7/16/08    Time: 4:41p
//Updated in $/Leap/Source/Interface
//modification in validation or check for insertion data
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 7/15/08    Time: 1:44p
//Updated in $/Leap/Source/Interface
//modified with 4 new date fields
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 7/12/08    Time: 3:25p
//Updated in $/Leap/Source/Interface
//modified in template & functionality
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 7/12/08    Time: 2:27p
//Updated in $/Leap/Source/Interface
//modification in employee in templates & functions
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 7/10/08    Time: 3:09p
//Updated in $/Leap/Source/Interface
//modified in edit and validation
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 7/04/08    Time: 12:27p
//Updated in $/Leap/Source/Interface
//modified in coding
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 7/04/08    Time: 11:11a
//Updated in $/Leap/Source/Interface
//modified for role name
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 7/02/08    Time: 11:05a
//Updated in $/Leap/Source/Interface
//modified in comments
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/01/08    Time: 9:25a
//Updated in $/Leap/Source/Interface
//modification with ajax functions using for add, edit, delete & populate
//the values
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/24/08    Time: 7:24p
//Updated in $/Leap/Source/Interface
//modification & check the flow
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/19/08    Time: 3:53p
//Created in $/Leap/Source/Interface
//Employee List during checkin
?>
