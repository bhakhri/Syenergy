<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality
//
// Author : Dipanjan Bhattacharjee
// Created on : (28.05.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//done changes
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AddStudentEnquiry');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
//hello
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Enquiry</title>
<?php
    require_once(TEMPLATES_PATH .'/jsCssHeader.php');
    global $sessionHandler;
    $defaultCountryId = $sessionHandler->getSessionVariable('DEFAULT_COUNTRY');
    $defaultStateId = $sessionHandler->getSessionVariable('DEFAULT_STATE');
    $defaultCityId = $sessionHandler->getSessionVariable('DEFAULT_CITY');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
    new Array('srNo','#','width="1%"','',false),
    new Array('enquiryDate','Enq. Date','width="9%"','align="center"',true) ,
    new Array('studentName','Name','width="14%"','',true) ,
    //new Array('fatherName',"Father",'width="12%"','',true) ,
    new Array('compExamBy','Comp.<br>Exam. By','width="9%"','',true),
    new Array('compExamRollNo','Roll No.','width="9%"','',true),
    new Array('compExamRank','Rank','width="9%"','',true),
    //new Array('corrCityId','City','width="10%"','',true),
    //new Array('studentEmail','Email','width="10%"','',true),
    new Array('contact','Contact Info.','width="16%"','',true),
    new Array('className1','Degree','width="10%"','',true),
    new Array('candidateStatus1','Status','width="8%" align="center"','align="center"',true),
    new Array('counselingDate_start','Counseling Date','width="8%" align="center"',' align="center"',true),
    //new Array('displayName','Counselor','width="10%"','',true),
    new Array('act','Action','width="7%"','align="center"',false)
    );


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxInitList.php';
searchFormName = 'studentSearchForm'; // name of the form which will be used for search
addFormName    = 'AddStudentEnquiry';
editFormName   = 'EditStudentEnquiry';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteStudentEnquiry';
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    //document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function editWindow(id) {
    document.getElementById('divHeaderId').innerHTML='&nbsp;Edit Enquiry Details';
    document.getElementById('divCity').style.display='none';
    displayWindow('AddStudentEnquiry','850','450');
    populateValues(id);
}
function getData() {

   document.getElementById("nameRow2").style.display='';
   document.getElementById("resultRow").style.display='';
   sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   //sendReq(listURL,divResultName,'listForm','');
   return false;
}
var serverDate="<?php echo date('Y-m-d');?>";
function validateAddForm(frm,act) {
    if(!dateDifference(document.addForm.enquiryDate.value,serverDate,'-'))  {
        messageBox("Enquiry date cannot be greater than current date");
        document.addForm.enquiryDate.focus();
        return false;
    }
	var fieldValue = document.getElementById('studentNo').value;
var l = fieldValue.length;
if(!isPhone(document.getElementById('studentNo').value)){
	alert("<?php echo'Invalid phone no'; ?>");
}

//if(counterText(document.getElementById('studentNo').value,5,12){
	//alert("hh");
//}
	if(trim(document.getElementById('studentNo').value)=='') {
      document.getElementById('studentNo').value = trim(document.getElementById('studentMobile').value);
    }

    var fieldsArray = new Array(
                new Array("degree","<?php echo SELECT_DEGREE;?>",1),
                new Array("counselor","Select counselor",1),
                new Array("studentFName","<?php echo ENTER_STUDENT_FIRST_NAME;?>",1),
                new Array("studentLName","<?php echo "Enter last name";?>",0),
                new Array("studentEmail","<?php echo "Enter student email";?>",1),
                new Array("studentNo","<?php echo ENTER_STUDENT_CONTACT_NO;?>",1),
                new Array("studentMobile","<?php echo ENTER_STUDENT_MOBILE_NO;?>",0),
                new Array("studentNationality","<?php echo STUDENT_NATIONALITY;?>",0),
                new Array("fatherName","<?php echo ENTER_FATHER_NAME;?>",0),
                new Array("motherName","<?php echo ENTER_MOTHER_NAME;?>",0),
                new Array("correspondeceAddress1","<?php echo ENTER_STUDENT_ADDRESS1;?>",0),
                new Array("correspondenceCountry","<?php echo SELECT_COUNTRY;?>",0),
                new Array("correspondenceStates","<?php echo SELECT_STATE;?>",0),
                new Array("correspondenceCity","<?php echo SELECT_CITY;?>",0),
                new Array("correspondecePincode","<?php echo ENTER_PIN_CODE;?>",0)
               );
// new Array("visitPurpose","<?php echo "Enter purpose of visit";?>",0),
// new Array("visitorName","<?php echo "Enter the name of visitor";?>",0)

    var len = fieldsArray.length;

    if(eval("frm.enquiryDate.value")=='0000-00-00') {
        messageBox("<?php echo SELECT_ENQUIRY_DATE;?>");
        eval("frm.enquiryDate.focus()");
        return false;
    }
	if(!isEmpty(document.getElementById("visitorName").value)){
		if(!isAlphaCharCustom(document.getElementById("visitorName").value)) {
	
			messageBox("Enter alphabets in name of the person whom visitor intends to meet");
			document.getElementById("visitorName").focus();
			return false;
		}
	}
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][2]==1) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
           if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='studentFName' ) {
                messageBox("<?php echo STUDENT_FIRST_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
          /*if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='fatherName' ) {
                messageBox("<?php echo FATHER_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          } */
          if(trim(eval("frm."+(fieldsArray[i][0])+".value"))!='' && fieldsArray[i][0]=='motherName' ) {
             if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='motherName' ) {
                messageBox("<?php echo MOTHER_NAME_LENGTH;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
             }
          }
          if(!isAlphaCharCustom(eval("frm."+(fieldsArray[i][0])+".value"),' .') && (fieldsArray[i][0]=='studentFName' || fieldsArray[i][0]=='fatherName')) {
                messageBox("Enter only alphabets");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
         if(!isAlphaCharCustom(eval("frm."+(fieldsArray[i][0])+".value"),' .') &&  trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && (fieldsArray[i][0]=='studentLName' || fieldsArray[i][0]=='motherName'))  {
                messageBox("Enter only alphabets");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
          }
         if(!isEmail(trim(eval("frm."+(fieldsArray[i][0])+".value"))) &&  trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && fieldsArray[i][0]=='studentEmail' ) {
                messageBox("<?php echo ENTER_VALID_EMAIL;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
         }
         if(!isNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value")),'-') &&  trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && fieldsArray[i][0]=='studentNo' ) {
                messageBox("Allowed characters are (0-9) and (-) in contact no.");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
         }
         if(!isNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value")),'-') &&  trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && fieldsArray[i][0]=='studentMobile' ) {
                messageBox("Allowed characters are (0-9) and (-) in mobile no.");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
         }
         if(!isAlphaNumericCustom(trim(eval("frm."+(fieldsArray[i][0])+".value"))," ") &&  trim(eval("frm."+(fieldsArray[i][0])+".value")).length>0 && fieldsArray[i][0]=='correspondecePincode' ) {
                messageBox("Allowed characters are a-z A-Z 0-9");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
           }
        }
    }
	 if(!isAlphaNumericCustom(trim(eval("document.addForm.city.value")))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                alert("Special characters are not allowed in Other(city)");
                document.addForm.city.focus();
                return false;
                
            }
   /* if(document.addForm.correspondenceCity.value=='' && trim(document.addForm.cityNameExtra.value)==''){
        messageBox("Select a city Or enter its name");
        document.addForm.correspondenceCity.focus();
        return false;
    }*/
    if(document.addForm.correspondenceCity.value =='' && trim(document.addForm.correspondenceCity.value)==''){
        messageBox("Either select a city Or enter its name");
        document.addForm.correspondenceCity.focus();
        return false;
    }
  /*  if(document.addForm.correspondenceCity.value=='' && trim(document.addForm.city.value).length > 0 ){
       if(trim(document.addForm.city.value).length<3){
           messageBox("<?php echo CITY_NAME_LENGTH;?>");
           document.addForm.city.focus();
           return false;
       }
    } */

    var dob=document.getElementById('studentMonth').value+'-'+document.getElementById('studentDate').value+'-'+document.getElementById('studentYear').value;
    if(!isDate1(dob)){
        document.getElementById('studentYear').focus();
        return false;
    }

    var dob2=document.getElementById('studentYear').value+'-'+document.getElementById('studentMonth').value+'-'+document.getElementById('studentDate').value;
    if(!dateDifference(dob2,serverDate,'-')){
        messageBox("<?php echo STUDENT_BIRTHDAY_VALIDATION; ?>");
        document.getElementById('studentYear').focus();
        return false;
    }


/*  selected = '';
    formx = document.addForm;
    for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
            if((formx.elements[i].checked) && (formx.elements[i].name=="visitSource")){
                selected++;
                break;
            }
        }
    }
    if(selected==0 && trim(formx.paperName.value) == '') {
       alert("Please select the source of visitor came to know about the college");
       return false;
    }
 */

    if(document.addForm.studentId.value=='') {
        addStudentEnquiry();
        return false;
    }
    else if(document.addForm.studentId.value!='') {
        editStudentEnquiry();
        return false;
    }
}

function blankValues() {

    /*START: function to populate father states and countries based on session values*/
    autoPopulate(<?php echo $defaultCountryId; ?>,'states','Add','correspondenceStates','correspondenceCity');

    //autoPopulate(<?php echo $defaultStateId; ?>,'city','Add','correspondenceStates','correspondenceCity');



    document.getElementById('divHeaderId').innerHTML='&nbsp;Add Enquiry Details';
    document.getElementById('divCity').style.display='none';
    document.addForm.reset();


    document.getElementById('candidateStatus1').value="";
    //document.addForm.correspondenceCountry.length = 1;
    document.addForm.correspondenceStates.length = 1;
    document.addForm.correspondenceCity.length = 1;

    document.addForm.studentId.value='';
    document.addForm.degree.focus();

    document.getElementById('admissionRow1').style.display='';
    document.getElementById('admissionRow2').style.display='none';
}

/* function to print all student report*/
function printStudentCSV() {

	var queryString = generateQueryString('studentSearchForm');
    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    var path='<?php echo UI_HTTP_PATH;?>/listStudentEnquiryPrintCSV.php?listStudent=1&'+queryString;
    window.location=path;
}

/* function to print student profile report*/
function printStudentReport() {

    var queryString = generateQueryString('studentSearchForm');
    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    path='<?php echo UI_HTTP_PATH;?>/listStudentEnquiryPrint.php?listStudent=1&'+queryString;
    window.open(path,"StudentEnquiryDetailReportCSV","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO Student Detail A student enquiry
//id=studentId
//Author : Parveen Sharma
//Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function printStudentEnquiry(id) {

    path='<?php echo UI_HTTP_PATH;?>/studentEnquiryPrint.php?studentId='+id;
    window.open(path,"StudentEnquiryDetailReport","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
}

//---------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Rajeev Aggarwal
// Created on : (17.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------
//id:id
//type:states/city
//target:taget dropdown box
function autoPopulate(val,type,frm,fieldSta,fieldCty){

   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   var fieldState = document.getElementById(fieldSta);
   var fieldCity = document.getElementById(fieldCty);
   if(frm=="Add"){

       if(type=="states"){

            fieldState.options.length=0;
            var objOption = new Option("Select","");
            fieldState.options.add(objOption);

            var objOption = new Option("Select","");
            fieldCity.options.length=0;
            fieldCity.options.add(objOption);
       }
       else if(type=="hostel"){

            fieldState.options.length=0;
            var objOption = new Option("Select","");
            fieldState.options.add(objOption);
       }
      else{

            fieldCity.options.length=0;
            var objOption = new Option("Select","");
            fieldCity.options.add(objOption);
      }
   }
   else{                        //for edit
        if(type=="states"){

            document.addForm.correspondenceStates.options.length=0;
            var objOption = new Option("Select","");
            document.addForm.correspondenceStates.add(objOption);
        }
        else{

            document.EditInstitute.city.options.length=0;
            var objOption = new Option("Select","");
            document.EditInstitute.city.options.add(objOption);
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
        // alert(transport.responseText);
         for(var c=0;c<j.length;c++){
              if(frm=="Add"){
                 if(type=="states"){
                     var objOption = new Option(j[c].stateName,j[c].stateId);
                     fieldState.options.add(objOption);

                 }
                else if(type=="hostel"){
                     var objOption = new Option(j[c].roomName,j[c].hostelRoomId);
                     fieldState.options.add(objOption);
                 }
                else{
                     var objOption = new Option(j[c].cityName,j[c].cityId);
                     fieldCity.options.add(objOption);
                }
          }
          else{
                if(type=="states"){
                     var objOption = new Option(j[c].stateName,j[c].stateId);
                     document.EditInstitute.states.options.add(objOption);
                 }
                else{
                     var objOption = new Option(j[c].cityName,j[c].cityId);
                     document.EditInstitute.city.options.add(objOption);
                }
              }
          }
			 /*

          if(frm=='Add' && type=='states') {
            fieldState.value=<?php echo $defaultStateId; ?>;
          }
			 */
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A student enquiry
//id=studentId
//Author : Dipanjan Bhattacharjee
//Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function deleteStudentEnquiry(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studentId: id},
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
//THIS FUNCTION IS USED TO POPULATE "AddStudentEnquiry" DIV
//Author : Dipanjan Bhattacharjee
// Created on : (29.05.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         var url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxGetValues.php';
         document.addForm.reset();
         document.addForm.studentId.value='';

         document.getElementById('candidateStatus1').value="";
         document.getElementById('candidateStatus2').value="";

         formx = document.addForm;
         for(var i=1;i<formx.length;i++) {
            if(formx.elements[i].type=="checkbox") {
              formx.elements[i].checked=false;
            }
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studentId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('AddStudentEnquiry');
                        messageBox("<?php echo STUDENT_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                   var ret=trim(transport.responseText).split('~!~!~!~');

                   var j = eval('('+ret[0]+')');
                   document.addForm.studentId.value=j.studentId;
                   document.addForm.degree.value=j.classId;
                   document.addForm.enquiryDate.value=j.enquiryDate;
                   document.addForm.counselor.value=j.addedByUserId;

                   document.addForm.entranceExam.value=j.compExamBy;
                   document.addForm.studentRank.value=j.compExamRank;
                   document.addForm.entranceExamRollNo.value=j.compExamRollNo;
                   document.addForm.formNo.value=j.applicationNo;

                   if(j.candidateStatus==2) {
                      document.getElementById('admissionRow1').style.display='none';
                      document.getElementById('admissionRow2').style.display='';
                      document.getElementById('candidateStatus1').value="Admission";
                      document.getElementById('candidateStatus2').value=2;
                   }
                   else if(j.candidateStatus==5) {
                      document.getElementById('admissionRow1').style.display='none';
                      document.getElementById('admissionRow2').style.display='';
                      document.getElementById('candidateStatus1').value="Counseling";
                      document.getElementById('candidateStatus2').value=5;
                   }
                   else {
                     document.getElementById('admissionRow1').style.display='';
                     document.getElementById('admissionRow2').style.display='none';
                     document.getElementById('candidateStatus1').value="";
                     document.getElementById('candidateStatus2').value="";
                     document.addForm.candidateStatus.value=j.candidateStatus;

                   }

                   document.addForm.studentCategory.value=j.quotaId;
                   document.addForm.studentFName.value=j.firstName;
                   document.addForm.studentLName.value=j.lastName;

                   var dob=j.dateOfBirth.split('-');
                   document.getElementById('studentYear').value=dob[0];
                   document.getElementById('studentMonth').value=dob[1];
                   document.getElementById('studentDate').value=dob[2];

                   if(j.studentGender=='M'){
                    document.addForm.genderRadio[0].checked=true;
                   }
                   else if(j.studentGender=='F'){
                       document.addForm.genderRadio[1].checked=true;
                   }

                   document.addForm.studentEmail.value=j.studentEmail;

                   document.addForm.studentNo.value=j.studentPhone;
                   document.addForm.studentMobile.value=j.studentMobileNo;
                   document.addForm.studentNationality.value=j.nationalityId;
                   document.addForm.studentDomicile.value=j.domicileId;
                   document.addForm.fatherName.value=j.fatherName;
                   document.addForm.motherName.value=j.motherName;
                   document.addForm.correspondeceAddress1.value=j.corrAddress1;
                   document.addForm.correspondeceAddress2.value=j.corrAddress2;
                   document.addForm.correspondecePincode.value=j.corrPinCode;
                   document.addForm.studentRemarks.value=j.studentRemarks;

                   document.addForm.correspondenceStates.options.length=1;
                   document.addForm.correspondenceCity.options.length=1;

                   document.addForm.correspondenceCountry.value=j.corrCountryId;

                   if(ret.length>1){
                     var k=eval('('+ret[1]+')');
                     if(k!=null) {
                         var kl=k.length;
                         for(var kk=0;kk<kl;kk++){
                           var objOption = new Option(k[kk].stateName,k[kk].stateId);
                           document.addForm.correspondenceStates.options.add(objOption);
                         }
                     }
                   }

                   if(ret.length>2){
                      var k=eval('('+ret[2]+')');
                      if(k!=null) {
                         var kl=k.length;
                         for(var kk=0;kk<kl;kk++){
                           var objOption = new Option(k[kk].cityName,k[kk].cityId);
                           document.addForm.correspondenceCity.options.add(objOption);
                         }
                      }
                   }

                   document.addForm.correspondenceStates.value =j.corrStateId;
                   document.addForm.correspondenceCity.value   =j.corrCityId;

                   var j = eval('('+ret[0]+')');
                   document.addForm.visitPurpose.value = j.visitPurpose;
                   document.addForm.visitorName.value = j.visitorName;
                   document.addForm.paperName.value = j.paperName;
                   if(j.visitSource!='') {
                      var ret1=trim(j.visitSource).split('~');
                      if(ret1!=null) {
                        for(i=0;i<ret1.length;i++) {
                           aa = 'visitSource'+ret1[i];
                           eval("document.getElementById(aa).checked=true");
                        }
                      }
                   }
                   document.addForm.degree.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//for updating student enquiry
function editStudentEnquiry() {

         visitSource = '';
         formx = document.addForm;
         for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
              if((formx.elements[i].checked) && (formx.elements[i].name=="visitSource")){
                visitSource = visitSource+formx.elements[i].value+"~";
              }
            }
         }

         if(document.getElementById('candidateStatus1').value=='') {
           candidateStatus = document.addForm.candidateStatus.value;
         }
         else {
           candidateStatus = document.getElementById('candidateStatus2').value;
         }

         var url = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxInitAdd.php';
         var dob2=document.getElementById('studentYear').value+'-'+document.getElementById('studentMonth').value+'-'+document.getElementById('studentDate').value;
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                          candidateStatus:   candidateStatus,
                          applicationNo:     trim(document.addForm.formNo.value),
                          studentId :            document.addForm.studentId.value,
                          enquiryDate:           (document.addForm.enquiryDate.value),
                          degree:                (document.addForm.degree.value),
                          entranceExam:          (document.addForm.entranceExam.value),
                          compExamRollNo:        trim(document.addForm.entranceExamRollNo.value),
                          studentRank:           trim(document.addForm.studentRank.value),
                          studentCategory:       (document.addForm.studentCategory.value),
                          studentFName:          trim(document.addForm.studentFName.value),
                          studentLName:          trim(document.addForm.studentLName.value),
                          studentDob:            (dob2),
                          studentGender:         (document.addForm.genderRadio[0].checked ? 'M' : 'F'),
                          studentEmail:          trim(document.addForm.studentEmail.value),
                          studentNo:             trim(document.addForm.studentNo.value),
                          studentMobile:         trim(document.addForm.studentMobile.value),
                          studentNationality:    (document.addForm.studentNationality.value),
                          studentDomicile:       (document.addForm.studentDomicile.value),
                          fatherName:            trim(document.addForm.fatherName.value),
                          motherName:            trim(document.addForm.motherName.value),
                          correspondeceAddress1: trim(document.addForm.correspondeceAddress1.value),
                          correspondeceAddress2: trim(document.addForm.correspondeceAddress2.value),
                          correspondecePincode:  trim(document.addForm.correspondecePincode.value),
                          correspondenceCountry: (document.addForm.correspondenceCountry.value),
                          correspondenceStates:  (document.addForm.correspondenceStates.value),
                          correspondenceCity:    (document.addForm.correspondenceCity.value),
                          studentRemarks:        trim(document.addForm.studentRemarks.value),
                          city :        trim(document.addForm.city.value),
                          userId:                document.addForm.counselor.value,
                          visitPurpose:          trim(document.addForm.visitPurpose.value),
                          visitorName:           trim(document.addForm.visitorName.value),
                          paperName:             trim(document.addForm.paperName.value),
                          visitSource:           trim(visitSource)

             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo COMP_EXAM_ROLLNO_EXIST?>"== trim(transport.responseText)) {
                        messageBox("<?php echo COMP_EXAM_ROLLNO_EXIST; ?>");
                        document.addForm.entranceExamRollNo.focus();
                        return false;
                     }
                     else if("<?php echo APPLICATION_NO_EXIST?>"== trim(transport.responseText)) {
                        messageBox("<?php echo APPLICATION_NO_EXIST; ?>");
                        document.addForm.formNo.focus();
                        return false;
                     }
                     else if("<?php echo CITY_NAME_ALREADY_EXIST; ?>"==trim(transport.responseText)){
                        messageBox("<?php echo CITY_NAME_ALREADY_EXIST; ?>");
                        document.addForm.city.focus();
                        return false;
                     }
                     else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                        hiddenFloatingDiv('AddStudentEnquiry');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}
//for adding new student enquiry
function addStudentEnquiry() {

         visitSource = '';
         formx = document.addForm;
         for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox"){
              if((formx.elements[i].checked) && (formx.elements[i].name=="visitSource")){
                visitSource = visitSource+formx.elements[i].value+"~";
              }
            }
         }

         var url   = '<?php echo HTTP_LIB_PATH;?>/StudentEnquiry/ajaxInitAdd.php';
         var dob2  = document.getElementById('studentYear').value+'-'+document.getElementById('studentMonth').value+'-'+document.getElementById('studentDate').value;
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                          candidateStatus:   document.addForm.candidateStatus.value,
                          applicationNo:     trim(document.addForm.formNo.value),
                          degree:                (document.addForm.degree.value),
                          enquiryDate:           (document.addForm.enquiryDate.value),
                          entranceExam:          (document.addForm.entranceExam.value),
                          studentRank:           trim(document.addForm.studentRank.value),
                          compExamRollNo:        trim(document.addForm.entranceExamRollNo.value),
                          studentCategory:       (document.addForm.studentCategory.value),
                          studentFName:          trim(document.addForm.studentFName.value),
                          studentLName:          trim(document.addForm.studentLName.value),
                          studentDob:            (dob2),
                          studentGender:         (document.addForm.genderRadio[0].checked ? 'M' : 'F'),
                          studentEmail:          trim(document.addForm.studentEmail.value),
                          studentNo:             trim(document.addForm.studentNo.value),
                          studentMobile:         trim(document.addForm.studentMobile.value),
                          studentNationality:    (document.addForm.studentNationality.value),
                          studentDomicile:       (document.addForm.studentDomicile.value),
                          fatherName:            trim(document.addForm.fatherName.value),
                          motherName:            trim(document.addForm.motherName.value),
                          correspondeceAddress1: trim(document.addForm.correspondeceAddress1.value),
                          correspondeceAddress2: trim(document.addForm.correspondeceAddress2.value),
                          correspondecePincode:  trim(document.addForm.correspondecePincode.value),
                          correspondenceCountry: (document.addForm.correspondenceCountry.value),
                          correspondenceStates:  (document.addForm.correspondenceStates.value),
                          correspondenceCity:    (document.addForm.correspondenceCity.value),
                          studentRemarks:        trim(document.addForm.studentRemarks.value),
                          city :                 trim(document.addForm.city.value),
                          userId:                document.addForm.counselor.value,
                          visitPurpose:          trim(document.addForm.visitPurpose.value),
                          visitorName:           trim(document.addForm.visitorName.value),
                          paperName:             trim(document.addForm.paperName.value),
                          visitSource:           trim(visitSource)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo COMP_EXAM_ROLLNO_EXIST?>"== trim(transport.responseText)) {
                        messageBox("<?php echo COMP_EXAM_ROLLNO_EXIST; ?>");
                        document.addForm.entranceExamRollNo.focus();
                        return false;
                     }
                     else if("<?php echo APPLICATION_NO_EXIST?>"== trim(transport.responseText)) {
                        messageBox("<?php echo APPLICATION_NO_EXIST; ?>");
                        document.addForm.formNo.focus();
                        return false;
                     }
                     else if("<?php echo CITY_NAME_ALREADY_EXIST; ?>"==trim(transport.responseText)){
                          messageBox("<?php echo CITY_NAME_ALREADY_EXIST; ?>");
                          document.addForm.city.focus();
                          return false;
                     }
                     else if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddStudentEnquiry');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function hiddenFloatingDiv(divId)
 {
    document.getElementById('divCity').style.display='';
    //document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
    //document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);

    DivID = "";
	if(document.getElementById('containfooter'))
	{
		document.getElementById('containfooter').style.display='';
	}
}


var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='item';

window.onload=function(){
    document.studentSearchForm.reset();

    //call multi selected dds function
    //makeDDHide('cityId1','d2','d3');
    //makeDDHide('stateId','d22','d33');
    //makeDDHide('countryId','d222','d333');
}


</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentEnquiry/listStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script>
//sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php
// $History: addStudentEnquiry.php $
//
//*****************  Version 31  *****************
//User: Parveen      Date: 4/13/10    Time: 4:36p
//Updated in $/LeapCC/Interface
//query and validation format updated
//
//*****************  Version 30  *****************
//User: Parveen      Date: 3/24/10    Time: 4:23p
//Updated in $/LeapCC/Interface
//validation check updated
//
//*****************  Version 29  *****************
//User: Parveen      Date: 3/24/10    Time: 3:53p
//Updated in $/LeapCC/Interface
//condition format updated
//
//*****************  Version 28  *****************
//User: Parveen      Date: 3/23/10    Time: 6:34p
//Updated in $/LeapCC/Interface
//query & condition format updated
//
//*****************  Version 27  *****************
//User: Parveen      Date: 3/18/10    Time: 12:45p
//Updated in $/LeapCC/Interface
//validation & condition updated
//
//*****************  Version 26  *****************
//User: Parveen      Date: 3/05/10    Time: 4:58p
//Updated in $/LeapCC/Interface
//validation & condition format updated
//
//*****************  Version 25  *****************
//User: Parveen      Date: 3/05/10    Time: 1:08p
//Updated in $/LeapCC/Interface
//comp. exam roll no. validation check added
//
//*****************  Version 24  *****************
//User: Parveen      Date: 3/03/10    Time: 5:44p
//Updated in $/LeapCC/Interface
//format & validation udpated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 3/03/10    Time: 11:36a
//Updated in $/LeapCC/Interface
//visitor details added
//
//*****************  Version 22  *****************
//User: Parveen      Date: 2/20/10    Time: 12:43p
//Updated in $/LeapCC/Interface
//validation and format updated
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 4/01/10    Time: 19:01
//Updated in $/LeapCC/Interface
//Made UI changes
//
//*****************  Version 20  *****************
//User: Rahul.nagpal Date: 11/17/09   Time: 2:31p
//Updated in $/LeapCC/Interface
//
//*****************  Version 19  *****************
//User: Rahul.nagpal Date: 11/16/09   Time: 1:31p
//Updated in $/LeapCC/Interface
//added code for setting  dynamic footer display  in the javscript
//function hiddenFloatingDiv
//
//*****************  Version 18  *****************
//User: Administrator Date: 3/06/09    Time: 17:39
//Updated in $/LeapCC/Interface
//corrected lalebls
//
//*****************  Version 17  *****************
//User: Administrator Date: 3/06/09    Time: 17:22
//Updated in $/LeapCC/Interface
//Done these modifications :
//
//1. My Time Table in Teacher: Add a link in the cell of Period/Day in My
//Time Table of teacher module, that takes the teacher to Daily
//Attendance interface and sets the value in Class, Subject,  and group
//DDMs from the time table. however, teacher will need to select Date and
//Period manually.
//
//2. Student Info in Teacher: Please add just "And/Or" between Name and
//Roll No search text boxes.
//
//3. Department wise Employee Selection in send messages links in teacher
//
//*****************  Version 16  *****************
//User: Parveen      Date: 6/02/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//validation modify & formatting update
//
//*****************  Version 15  *****************
//User: Parveen      Date: 6/02/09    Time: 3:54p
//Updated in $/LeapCC/Interface
//spellling correct
//
//*****************  Version 14  *****************
//User: Parveen      Date: 6/02/09    Time: 3:37p
//Updated in $/LeapCC/Interface
//spelling change (edit query detail)
//
//*****************  Version 13  *****************
//User: Administrator Date: 1/06/09    Time: 17:18
//Updated in $/LeapCC/Interface
//Updated student enquiry module
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/01/09    Time: 11:45a
//Updated in $/LeapCC/Interface
//validation, message checks update
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/01/09    Time: 11:10a
//Updated in $/LeapCC/Interface
//blankValue function update
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/01/09    Time: 10:51a
//Updated in $/LeapCC/Interface
//validation update student Mobile No
//
//*****************  Version 9  *****************
//User: Administrator Date: 30/05/09   Time: 18:33
//Updated in $/LeapCC/Interface
//corrected bugs
//
//*****************  Version 6  *****************
//User: Administrator Date: 30/05/09   Time: 16:19
//Updated in $/LeapCC/Interface
//allowed a-z0-9 in pincode
//
//*****************  Version 4  *****************
//User: Parveen      Date: 5/30/09    Time: 2:50p
//Updated in $/LeapCC/Interface
//enquiryDate, contatctNo added in show details
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/30/09    Time: 2:40p
//Updated in $/LeapCC/Interface
//enquiryDate added validation update
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/30/09    Time: 11:27a
//Updated in $/LeapCC/Interface
//studentprintReport, StudentCSV, studentDetails function added
//
//*****************  Version 1  *****************
//User: Administrator Date: 29/05/09   Time: 16:51
//Created in $/LeapCC/Interface
//Created "Student Enquiry" module
?>