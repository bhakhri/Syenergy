<?php
	//-------------------------------------------------------
	// Purpose: To  add student 
	// functionality 
	//
	// Author : Rajeev Aggarwal
	// Created on : (05.07.2008 )
	// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
	//
	//--------------------------------------------------------
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	define('MODULE','Admit');
	define('ACCESS','view');
	UtilityManager::ifNotLoggedIn();
	require_once(BL_PATH . "/Student/getRegistrationNumber.php");
	global $sessionHandler;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Admit Student Master</title>

<?php 
	require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
    //echo UtilityManager::includeCSS("css.css");
    echo UtilityManager::includeCSS('winjs/default.css');
    echo UtilityManager::includeCSS('winjs/alphacube.css');    
    echo UtilityManager::includeJS("winjs/prototype.js"); 
    echo UtilityManager::includeJS("winjs/window.js"); 
    echo UtilityManager::includeJS("functions.js");  

	global $sessionHandler;
	$defaultCountryId = $sessionHandler->getSessionVariable('DEFAULT_COUNTRY');
	$defaultStateId = $sessionHandler->getSessionVariable('DEFAULT_STATE');
	$defaultCityId = $sessionHandler->getSessionVariable('DEFAULT_CITY');
    $admitStudentRequiredField = $sessionHandler->getSessionVariable('ADMIT_STUDENT_REQUIRED_FIELD'); 
    echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>

<script language="javascript">

var globalFL=1;  

function test(e){
        var evt = e || window.event;
        if(evt.keyCode==13){
            return false;
        }
        alert(1);
}

// Student Filter
function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);
      var extArr = new Array('gif','jpg','jpeg','png','bmp');
      var fl=0;
      var ln=extArr.length;
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}

 function getShowStudyPeriod(){

  // document.getElementById('migStudy').display='none';
   if(document.addForm.isMigration.checked==true){
	
     document.getElementById('divMigStudy').style.display = '';
   }else{
	 document.getElementById('divMigStudy').style.display = 'none';
  }

 }

function validateLoginForm() {

    if("<?php echo $admitStudentRequiredField; ?>"=="0") {
       var fieldsArray = new Array(
           new Array("studentInstitute","<?php echo STUDENT_INSTITUTE?>"),
           new Array("degree","Select Class"),
           new Array("collegeRegNo","<?php echo STUDENT_COLLEGE_REG_NO?>"),
           new Array("studentName","<?php echo STUDENT_FIRST_NAME?>")
       );           
    }
    else {
       var fieldsArray = new Array(
           new Array("studentInstitute","<?php echo STUDENT_INSTITUTE?>"),
           //new Array("degree","<?php echo STUDENT_DEGREE?>"),
           new Array("degree","Select Class"),
           new Array("entranceExam","<?php echo STUDENT_ENTRANCE?>"),
           new Array("studentCategory","<?php echo STUDENT_CATEGORY ?>"),
           new Array("collegeRegNo","<?php echo STUDENT_COLLEGE_REG_NO?>"),
           new Array("studentName","<?php echo STUDENT_FIRST_NAME?>"),
           new Array("country","<?php echo STUDENT_COUNTRY?>"),
           //new Array("feeReceiptNo","Enter student fee receipt no"),
           new Array("studentDomicile","<?php echo STUDENT_DOMICILE?>")
       );  
    }
    
    document.getElementById('collegeRegNo').value = trim(document.getElementById('collegeRegNo').value);
    document.getElementById('studentClassRole').value = trim(document.getElementById('studentClassRole').value);
    document.getElementById('studentUniversityRole').value = trim(document.getElementById('studentUniversityRole').value);

    if(trim(document.getElementById('collegeRegNo').value)!='') {
       if(!isAlphaNumericCustom(trim(document.getElementById('collegeRegNo').value),'0-9,a-z,&-_.\/+,{}[]()')) {
          messageBox("<?php echo STUDENT_REG_NO; ?>");
          document.getElementById('collegeRegNo').focus();
          return false; 	
       }
    }
    if(trim(document.getElementById('studentClassRole').value)!='') {
       if(!isAlphaNumericCustom(trim(document.getElementById('studentClassRole').value),'0-9,a-z,&-_./+,{}[]()')) {
          messageBox("<?php echo STUDENT_ROLL; ?>");
          document.getElementById('studentClassRole').focus();
          return false; 	
       }
    }
    if(trim(document.getElementById('studentUniversityRole').value)!='') {
       if(!isAlphaNumericCustom(trim(document.getElementById('studentUniversityRole').value),'0-9,a-z,&-_.\/+,{}[]()')) {
          messageBox("<?php echo STUDENT_UNI_ROLL_NO; ?>");
          document.getElementById('studentUniversityRole').focus();
          return false; 	
       }
    }

    var len = fieldsArray.length;
	var frm = document.addForm;
    var rollNo  = document.getElementById('studentClassRole').value;
     var leng = rollNo.length;
	  if(!isEmpty(document.getElementById('studentClassRole').value))
	{
		if(leng >30)
		{
			alert("Class Roll no can't exceed 30 letters");
			frm.studentClassRole.focus();
			return false;
		}
	}
    if(trim(frm.studentPhoto.value)!='') {
      if(!checkFileExtensionsUpload(frm.studentPhoto.value)){
         messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
         frm.studentPhoto.focus();  
         return false;
      }
    }

    var chk=0;
    for(i=0;i<len;i++) {
        chk=0;
        if(document.getElementById('admitOptionalField').value==1) {  
           if(fieldsArray[i][0]=='entranceExam' || fieldsArray[i][0]=='studentDomicile') {
             chk=1;    
           }
        } 
        
        if(chk==0) {
            if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
                messageBox(fieldsArray[i][1],fieldsArray[i][0]);
			    eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
		    else if(fieldsArray[i][0]=="studentNo"){
			    
                if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format

                     messageBox("<?php echo VALID_PHONE ?>"); 
                     eval("frm."+(fieldsArray[i][0])+".focus();");
                     return false;
                     break;  
                  }
            }
		    /*else if(fieldsArray[i][0]=="studentEmail"){

                if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) {//if not valid email format
			    
                  
                     messageBox("<?php echo STUDENT_VALID_EMAIL ?>");
                     eval("frm."+(fieldsArray[i][0])+".focus();");
                     return false;
                     break;  
                  }
			      else {
					    unsetAlertStyle(fieldsArray[i][0]);
				    }
            }*/
            else {
                unsetAlertStyle(fieldsArray[i][0]);
            }
        }
    }
    
	if(document.getElementById('studentEmail').value){
		if(!isEmail(eval("frm."+('studentEmail')+".value"))){  //if not valid email format
			messageBox("<?php echo STUDENT_VALID_EMAIL?>");
			eval("frm."+('studentEmail')+".focus();");
			return false;
		}
	}
	if(document.getElementById('alternateEmail').value) {
		if(!isEmail(eval("frm."+('alternateEmail')+".value"))){  //if not valid email format
			messageBox("<?php echo STUDENT_VALID_EMAIL?>");
			eval("frm."+('alternateEmail')+".focus();");
			return false;
		}
	}
   /* if(document.getElementById('collegeRegNo').value){
                if (!isAlphaNumericCustom(eval("frm."+('collegeRegNo')+".value.split(" ").length"))>1 && collegeRegNo=='regNo'){ 
                messageBox("<?php echo DONT_USE_SPACE ?>");
                eval("frm."+('collegeRegNo')+".focus();");
                return false;
           
           }
      }
*/
   
   
    if(document.getElementById('admitOptionalField').value==1) {
        if(document.getElementById('workEmail').value) {
            if(!isEmail(eval("frm."+('workEmail')+".value"))){  //if not valid email format
                messageBox("<?php echo STUDENT_VALID_EMAIL?>");
                eval("frm."+('workEmail')+".focus();");
                return false;
            }
        }
    }
    

	if(!isPhone(eval("frm."+('studentNo')+".value"))){  //if not valid email format
		messageBox("<?php echo STUDENT_VALID_CONTACT_NO?>");
		eval("frm."+('studentNo')+".focus();");
		return false;
	}

    
    if(document.getElementById('admitOptionalField').value!=1) { 
	    if(document.getElementById('yearsInHostel').value) {
		    if(!isInteger(eval("frm."+('yearsInHostel')+".value"))){  //if not valid email format
			    messageBox("<?php echo STUDENT_VALID_YEARS?>");
			    eval("frm."+('yearsInHostel')+".focus();");
			    return false;
		    }
	    }
    }

	if(document.getElementById('loanAmount').value) {
		if(!isInteger(eval("frm."+('loanAmount')+".value"))){  //if not valid email format
			messageBox("<?php echo STUDENT_VALID_LOAN_AMOUNT?>");
			eval("frm."+('loanAmount')+".focus();");
			return false;
		}
	}

	if(document.getElementById('studentMobile').value){

		if(!isPhone(eval("frm."+('studentMobile')+".value"))){  //if not valid email format

			messageBox("<?php echo STUDENT_VALID_MOBILE?>");
			eval("frm."+('studentMobile')+".focus();");
			return false;
		}
	}

	if((document.getElementById("admissionMonth").value !='') && (document.getElementById("admissionDate").value !='') && (document.getElementById("admissionYear").value!='')){
		
		BirthYear = document.getElementById("admissionMonth").value + "-" + document.getElementById("admissionDate").value + "-" + document.getElementById("admissionYear").value;
		if (!isDate1(BirthYear)) {
				document.getElementById("admissionYear").focus();
				return false;
			}

		birthDate = document.getElementById("admissionYear").value+'-'+document.getElementById("admissionMonth").value+'-'+document.getElementById("admissionDate").value;
		currentDate = "<?php echo date('Y-m-d')?>";
		if(dateCompare(birthDate,currentDate)==1){

			 messageBox("<?php echo STUDENT_VALID_DATEOFADMISSION?>");
			 document.addForm.admissionYear.focus();
			 return false;
		}
	}

	if((document.getElementById("studentMonth").value !='') && (document.getElementById("studentDate").value !='') && (document.getElementById("studentYear").value!='')){
		
		BirthYear = document.getElementById("studentMonth").value + "-" + document.getElementById("studentDate").value + "-" + document.getElementById("studentYear").value;
		if (!isDate1(BirthYear)) {
				document.getElementById("studentYear").focus();
				return false;
			}

		birthDate = document.getElementById("studentYear").value+'-'+document.getElementById("studentMonth").value+'-'+document.getElementById("studentDate").value;
		currentDate = "<?php echo date('Y-m-d')?>";
		if(dateCompare(birthDate,currentDate)==1){

			 messageBox("<?php echo STUDENT_VALID_DATEOFBIRTH?>");
			 document.addForm.studentYear.focus();
			 return false;
		}
	}


	flag=0; 
	flag1=0; 
	flag2=0; 
	for(cnt=1;cnt<=document.addForm.countRecord.value;cnt++){
	
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if(document.getElementById('marks'+cnt).value){

			if (!reg.test(document.getElementById('marks'+cnt).value)){

			   eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			   flag++;
			}
			else{
				
				eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
			}
		}
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if(document.getElementById('maxMarks'+cnt).value){

			if (!reg.test(document.getElementById('maxMarks'+cnt).value)){

			   eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
			   flag1++;
			}
			else{
			
				eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
			}
		}
		if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){

			flag2=1;	
		    alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
		    document.getElementById('marks'+cnt).focus();
			eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			  
		}
		else{
		
		  eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		} 
	}
	if(flag>0){
	
		 alert("<?php echo ENTER_MARKS_TO_NUM; ?>");
		 return false;
	}
	else if(flag1>0){
	
		 alert("<?php echo ENTER_MAX_MARKS_TO_NUM; ?>");
		 return false;
	}
	else if(flag2>0){
	
		 alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
		 return false;
	} 
  
    
	if(document.getElementById('admitOptionalField').value!=1) { 
     
	    if(document.getElementById('fatherEmail').value){

		    if(!isEmail(eval("frm."+('fatherEmail')+".value"))){  //if not valid email format

			    messageBox("<?php echo FATHER_VALID_EMAIL?>");
			    eval("frm."+('fatherEmail')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('fatherMobile').value){

		    if(!isPhone(eval("frm."+('fatherMobile')+".value"))){  //if not valid email format

			    messageBox("<?php echo FATHER_VALID_MOBILE?>");
			    eval("frm."+('fatherMobile')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('fatherContact').value){

		    if(!isPhone(eval("frm."+('fatherContact')+".value"))){  //if not valid email format

			    messageBox("<?php echo FATHER_VALID_CONTACT?>");
			    eval("frm."+('fatherContact')+".focus();");
			    return false;
		    }
	    }

	    if(document.getElementById('motherEmail').value){

		    if(!isEmail(eval("frm."+('motherEmail')+".value"))){  //if not valid email format

			    messageBox("<?php echo MOTHER_VALID_EMAIL?>");
			    eval("frm."+('motherEmail')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('motherMobile').value){

		    if(!isPhone(eval("frm."+('motherMobile')+".value"))){  //if not valid email format

			    messageBox("<?php echo MOTHER_VALID_MOBILE?>");
			    eval("frm."+('motherMobile')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('motherContact').value){

		    if(!isPhone(eval("frm."+('motherContact')+".value"))){  //if not valid email format

			    messageBox("<?php echo MOTHER_VALID_CONTACT?>");
			    eval("frm."+('motherContact')+".focus();");
			    return false;
		    }
	    }

	    if(document.getElementById('guardianEmail').value){

		    if(!isEmail(eval("frm."+('guardianEmail')+".value"))){ //if not valid email format

			    messageBox("<?php echo GUARDIAN_VALID_EMAIL?>");
			    eval("frm."+('guardianEmail')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('guardianMobile').value){

		    if(!isPhone(eval("frm."+('guardianMobile')+".value"))){  //if not valid email format

			    messageBox("Please enter a valid guardian mobile number");
			    eval("frm."+('guardianMobile')+".focus();");
			    return false;
		    }
	    }
	    if(document.getElementById('guardianContact').value){

		    if(!isPhone(eval("frm."+('guardianContact')+".value"))){  //if not valid email format

			    messageBox("<?php echo GUARDIAN_VALID_CONTACT?>");
			    eval("frm."+('guardianContact')+".focus();");
			    return false;
		    }
	    }
    }

	if(document.getElementById('correspondecePincode').value){

		 if(!isAlphaNumeric(eval("frm.correspondecePincode.value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo CORRESPONDENCE_PINCODE?>");
                eval("frm.correspondecePincode.focus();");
                return false;
        }
	}


	if(document.getElementById('correspondecePhone').value){

		if(!isPhone(eval("frm."+('correspondecePhone')+".value"))){  //if not valid email format

			messageBox("<?php echo CORRESPONDENCE_VALID_CONTACT?>");
			eval("frm."+('correspondecePhone')+".focus();");
			return false;
		}
	}


	if(document.getElementById('permanentPincode').value){

		 if(!isAlphaNumeric(eval("frm.permanentPincode.value"))) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo PERMANENT_PINCODE?>");
                eval("frm.permanentPincode.focus();");
                return false;
            }
	}

	if(document.getElementById('permanentPhone').value){

		if(!isPhone(eval("frm."+('permanentPhone')+".value"))){  //if not valid email format

			messageBox("<?php echo PERMANENT_VALID_CONTACT?>");
			eval("frm."+('permanentPhone')+".focus();");
			return false;
		}
	}
	   if(document.addForm.isMigration.checked==true){
		if(document.getElementById('migratedStudyPeriod').value==0){
		  messageBox("Select Migrated Study Period");
			eval("frm."+('migratedStudyPeriod')+".focus();");
			return false;
		}
	  }
	addStudent();
	return false;
}

function resetLoginForm(){

	document.addForm.reset();
    blankValues();
	return false;
}

function calculatePercentage(cnt){

	flag=0;
	flag1=0;
	flag2=0;
	if(document.getElementById('marks'+cnt).value!='' && document.getElementById('maxMarks'+cnt).value!=''){
		
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('marks'+cnt).value)){
		
		  flag=1;	
		  alert("<?php echo ENTER_MARKS_TO_NUM; ?>"); 
		  document.getElementById('marks'+cnt).focus();
		  eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
		  return false;
		}
		else{
		
		  eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		}
		
		if (!reg.test(document.getElementById('maxMarks'+cnt).value)){

		  flag1=1;	
		  alert("<?php echo ENTER_MAX_MARKS_TO_NUM; ?>");
		  document.getElementById('maxMarks'+cnt).focus();
		  eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
		  return false;
		}
		else{
		
		  eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
		}

		if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){

			flag2=1;	
		    alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
			document.getElementById('marks'+cnt).focus();
			document.getElementById('marks'+cnt).value=0;
		   
			eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			  
		}
		else{
		
		  eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		} 
		if(flag==0 && flag1==0 && flag2==0){
		
			document.getElementById('percentage'+cnt).value = ((document.getElementById('marks'+cnt).value/document.getElementById('maxMarks'+cnt).value)*100).toFixed(2);
		}
	}
}


function addStudent() {
 
     if(globalFL==0){
        //messageBox("Another request is in progress.");
        return false;
     }  
	
     if(false===confirm("Do you want to save student details ? ")) {
        return false;
     }
    
     document.addForm.hiddenFile.value = document.addForm.studentPhoto.value;
    
	 var url = '<?php echo HTTP_LIB_PATH;?>/Student/initAdd.php';

	 new Ajax.Request(url,
	 {
		 method:'post',
		 parameters: $('addForm').serialize(true),
		 onCreate: function() {
			 //showWaitDialog(true); 
		 },
		 onSuccess: function(transport){
             
             var arr = trim(transport.responseText).split('~');    
             if("<?php echo SUCCESS;?>" == arr[0]) {  
               initAdd();  
             }
             else {
                 str = trim(transport.responseText);
                 if(str=="<?php echo QUARANTINE_REGISTRATION_ALREADY_EXISTS?>"){
                    messageBox(trim(str)); 
                    document.getElementById('collegeRegNo').className = 'inputboxRed'; 
                    document.addForm.collegeRegNo.focus();
                    return false;
                 }
                 else if(str=="Student roll number already exists"){
                    messageBox(trim(str)); 
                    document.getElementById('studentClassRole').className = 'inputboxRed'; 
                    document.addForm.studentClassRole.focus();
                    return false;
                 }
                 else if(str=="Student roll number already exists in deleted Records"){
                    messageBox(trim(str)); 
                    document.getElementById('studentClassRole').className = 'inputboxRed'; 
                    document.addForm.studentClassRole.focus();
                    return false;
                 }
                 else if(str=="College reg no. already exists"){
                    messageBox(trim(str)); 
                    document.getElementById('collegeRegNo').className = 'inputboxRed'; 
                    document.addForm.collegeRegNo.focus();
                    return false;
                 }
                 else if(str=="College reg no. already exists in deleted records"){
                    messageBox(trim(str)); 
                    document.getElementById('collegeRegNo').className = 'inputboxRed'; 
                    document.addForm.collegeRegNo.focus();
                    return false;
                 }
                 else if(str=="University no. already exists"){
                    messageBox(trim(str)); 
                    document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
                    document.addForm.studentUniversityRole.focus();
                    return false;
                 }
                 else if(str=="University no. already exists in deleted records"){
                    messageBox(trim(str)); 
                    document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
                    document.addForm.studentUniversityRole.focus();
                    return false;
                 }
                 else if(str=="Fee receipt no. already exists"){
                    messageBox(trim(str)); 
                    document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
                    document.addForm.feeReceiptNo.focus();
                    return false;
                 }
                 else if(str=="Fee receipt no. already exists in deleted records"){
                    messageBox(trim(str)); 
                    document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
                    document.addForm.feeReceiptNo.focus();
                    return false;
                 }
                 else if(str=="Student email already exists"){
                    messageBox(trim(str)); 
                    document.getElementById('studentEmail').className = 'inputboxRed'; 
                    document.addForm.studentEmail.focus();
                    return false;
                 }
                 else if(str=="Student email already exists in deleted Records"){
                    messageBox(trim(str)); 
                    document.getElementById('studentEmail').className = 'inputboxRed'; 
                    document.addForm.studentEmail.focus();
                    return false;
                 }
                 else if(str=="<?php echo QUARANTINE_ROLLNO_ALREADY_EXISTS?>"){
                    messageBox(trim(str)); 
                    document.getElementById('studentClassRole').className = 'inputboxRed'; 
                    document.addForm.studentClassRole.focus();
                    return false;
                 }
                 else if(str=="<?php echo QUARANTINE_UNIV_ALREADY_EXISTS?>"){
                    messageBox(trim(str)); 
                    document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
                    document.addForm.studentUniversityRole.focus();
                    return false;
                 }
                 else if(str=="<?php echo REGISTRATION_ALREADY_EXISTS?>"){
                    messageBox(trim(str)); 
                    document.getElementById('collegeRegNo').className = 'inputboxRed'; 
                    document.addForm.collegeRegNo.focus();
                    return false;
                 }
                 else if(str=="<?php echo EMAIL_ALREADY_EXISTS?>"){
                    messageBox(trim(str)); 
                    document.getElementById('studentEmail').className = 'inputboxRed';
                    document.addForm.studentEmail.focus();
                    return false;
                 }

                 else if(str=="<?php echo ALTERNATE_EMAIL_ALREADY_EXISTS?>"){
                 
                    messageBox(trim(str)); 
                    document.getElementById('alternateEmail').className = 'inputboxRed';
                    document.addForm.alternateEmail.focus();
                    return false;
                 }
                 else if(str=="<?php echo FEE_ALREADY_EXISTS?>"){
                 
                     
                    messageBox(trim(str)); 
                    document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
                    document.addForm.feeReceiptNo.focus();
                    return false;
                 }
                 else{
                    messageBox(trim(str)); 
                 }
             }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}


function initAdd() {
    //document.getElementById('addNotice').target = 'uploadTargetAdd';   
    showWaitDialog(true);
    document.getElementById('addForm').target = 'uploadTargetAdd';
    document.getElementById('addForm').action= "<?php echo HTTP_LIB_PATH;?>/Student/fileUploadStudent.php"
    document.getElementById('addForm').submit();
}

function fileUploadError(str,mode){
		
     hideWaitDialog(true);
     //globalFL=1;
     flag = true;
     //arr = transport.responseText.replace(/^\s+|\s+$/g,'').split('~');
     var arr = trim(str).split('~');    
     if("<?php echo SUCCESS;?>" == arr[0]) {  
         flag = true;
         if(confirm("<?php echo SUCCESS;?>\n\n <?php echo ADD_MORE; ?>")) {
             document.getElementById('addForm').reset(); 
             blankValues();
             document.getElementById('collegeRegNo').value = arr[1];
         }
         else {
             //window.location='admitStudent.php';
             document.getElementById('addForm').reset(); 
             return false;
         }
     } 
     else {
         if(str=="<?php echo QUARANTINE_REGISTRATION_ALREADY_EXISTS?>"){
            messageBox(trim(str)); 
            document.getElementById('collegeRegNo').className = 'inputboxRed'; 
            document.addForm.collegeRegNo.focus();
            return false;
         }

        else if(str=="Student roll number already exists"){

            messageBox(trim(str)); 
            document.getElementById('studentClassRole').className = 'inputboxRed'; 
            document.addForm.studentClassRole.focus();
            return false;
         }
         else if(str=="Student roll number already exists in deleted Records"){
            messageBox(trim(str)); 
            document.getElementById('studentClassRole').className = 'inputboxRed'; 
            document.addForm.studentClassRole.focus();
            return false;
         }
        else if(str=="College reg no. already exists"){
            messageBox(trim(str)); 
            document.getElementById('collegeRegNo').className = 'inputboxRed'; 
            document.addForm.collegeRegNo.focus();
            return false;
         }
         else if(str=="College reg no. already exists in deleted records"){
            messageBox(trim(str)); 
            document.getElementById('collegeRegNo').className = 'inputboxRed'; 
            document.addForm.collegeRegNo.focus();
            return false;
         }
         else if(str=="University no. already exists"){
            messageBox(trim(str)); 
            document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
            document.addForm.studentUniversityRole.focus();
            return false;
         }
         else if(str=="University no. already exists in deleted records"){
            messageBox(trim(str)); 
            document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
            document.addForm.studentUniversityRole.focus();
            return false;
         }
         else if(str=="Fee receipt no. already exists"){
            messageBox(trim(str)); 
            document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
            document.addForm.feeReceiptNo.focus();
            return false;
         }
         else if(str=="Fee receipt no. already exists in deleted records"){
            messageBox(trim(str)); 
            document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
            document.addForm.feeReceiptNo.focus();
            return false;
         }
         else if(str=="Student email already exists"){
            messageBox(trim(str)); 
            document.getElementById('studentEmail').className = 'inputboxRed'; 
            document.addForm.studentEmail.focus();
            return false;
         }
         else if(str=="Student email already exists in deleted Records"){
            messageBox(trim(str)); 
            document.getElementById('studentEmail').className = 'inputboxRed'; 
            document.addForm.studentEmail.focus();
            return false;
         }
         else if(str=="<?php echo QUARANTINE_ROLLNO_ALREADY_EXISTS?>"){
            messageBox(trim(str)); 
            document.getElementById('studentClassRole').className = 'inputboxRed'; 
            document.addForm.studentClassRole.focus();
            return false;
         }
         else if(str=="<?php echo QUARANTINE_UNIV_ALREADY_EXISTS?>"){
            messageBox(trim(str)); 
            document.getElementById('studentUniversityRole').className = 'inputboxRed'; 
            document.addForm.studentUniversityRole.focus();
            return false;
         }
         else if(str=="<?php echo REGISTRATION_ALREADY_EXISTS?>"){
            messageBox(trim(str)); 
            document.getElementById('collegeRegNo').className = 'inputboxRed'; 
            document.addForm.collegeRegNo.focus();
            return false;
         }
         else if(str=="<?php echo EMAIL_ALREADY_EXISTS?>"){
         
            messageBox(trim(str)); 
            document.getElementById('studentEmail').className = 'inputboxRed';
            document.addForm.studentEmail.focus();
            return false;
         }

         else if(str=="<?php echo ALTERNATE_EMAIL_ALREADY_EXISTS?>"){
            messageBox(trim(str)); 
            document.getElementById('alternateEmail').className = 'inputboxRed';
            document.addForm.alternateEmail.focus();
	    return false; 
         }
         else if(str=="<?php echo FEE_ALREADY_EXISTS?>"){
           messageBox(trim(str)); 
            document.getElementById('feeReceiptNo').className = 'inputboxRed'; 
            document.addForm.feeReceiptNo.focus();
            return false; 
         }
         else{
            messageBox(trim(str)); 
         }
     }
     document.getElementById('studentInstitute').focus();
}


function getInstituteClass(instituteId){

	if(instituteId!=''){
	 
	form = document.addForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/initGetInstituteClass.php';
	var pars = 'instituteId='+instituteId;
	 
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
				 
				document.addForm.degree.length = null;

				addOption(document.addForm.degree, '', 'Select');
				if (len > 0) {
					//addOption(document.testWiseMarksReportForm.groupId, 'all', 'All');
				}
				for(i=0;i<len;i++) { 
					addOption(document.addForm.degree, j[i].classId, j[i].className);
				}
				 
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	}
	else{
	
		document.addForm.degree.length = null;
		addOption(document.addForm.degree, '', 'Select');
	}
} 

function blankValues()
{
	document.addForm.entranceExam.value = '';
	document.addForm.studentRank.value = '';
	document.addForm.studentCategory.value = '';
	document.addForm.studentName.value = '';
	document.addForm.studentLName.value = '';
	document.addForm.studentYear.value = '';
	document.addForm.studentMonth.value = '';
	document.addForm.studentDate.value = '';
	document.addForm.studentEmail.value = '';
	document.addForm.studentNo.value = '';
	document.addForm.studentMobile.value = '';
	document.addForm.fatherName.value = '';
	document.addForm.motherName.value = '';
	document.addForm.everStayedInHostel.value = 0;
	document.addForm.yearsInHostel.disabled = true;
	document.addForm.educationYes.value = 0;
	document.addForm.bankName.disabled = true;
	document.addForm.loanAmount.disabled = true;
	document.addForm.completedGraduationYes.value = 1;
	document.addForm.writtenFinalExamYes.checked = false;
	document.addForm.writtenFinalExamYes.disabled = true;
	document.addForm.writtenFinalExamNo.checked = false;
	document.addForm.writtenFinalExamNo.disabled = true;
	document.addForm.coachingManager.disabled = true;
	document.addForm.address.disabled = true;
	document.addForm.department.disabled = true;
	document.addForm.organization.disabled = true;
	document.addForm.place.disabled = true;

	document.getElementById('rollNo3').disabled=false;
	document.getElementById('session3').disabled=false;
	document.getElementById('institute3').disabled=false;
	document.getElementById('board3').disabled=false;
	document.getElementById('educationStream3').disabled=false;
	document.getElementById('maxMarks3').disabled=false;
	document.getElementById('marks3').disabled=false;
	document.getElementById('percentage3').disabled=false;

	document.getElementById('rollNo4').disabled=false;
	document.getElementById('session4').disabled=false;
	document.getElementById('institute4').disabled=false;
	document.getElementById('board4').disabled=false;
	document.getElementById('educationStream4').disabled=false;
	document.getElementById('maxMarks4').disabled=false;
	document.getElementById('marks4').disabled=false;
	document.getElementById('percentage4').disabled=false;

    document.addForm.entranceExam.focus();
    
    document.addForm.studentInstitute.value = '<?php echo $sessionHandler->getSessionVariable('InstituteId'); ?>';
    document.addForm.entranceExam.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_EXAM'); ?>';
    document.addForm.studentCategory.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CATEGORY'); ?>';
    document.addForm.studentDate.value = '01';
    document.addForm.studentMonth.value = '04';
    document.addForm.studentYear.value = '1992';
    document.addForm.country.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_NATIONALITY'); ?>';
    document.addForm.studentDomicile.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_DOMICILE'); ?>';
    document.addForm.fatherCountry.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
    document.addForm.fatherStates.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>';
    document.addForm.fatherCity.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
    document.addForm.motherCountry.value ='<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
    document.addForm.motherStates.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>'; 
    document.addForm.motherCity.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
    document.addForm.guardianCountry.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
    document.addForm.guardianStates.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>'; 
    document.addForm.guardianCity.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
    document.addForm.correspondenceCountry.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
    document.addForm.correspondenceStates.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>'; 
    document.addForm.correspondenceCity.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
    document.addForm.permanentCountry.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
    document.addForm.permanentStates.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>'; 
    document.addForm.permanentCity.value = '<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
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
 asynchronous:false,
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
	 },
	 onFailure: function(){ alert('Something went wrong...') }
   }); 
}


function copyText(){

	if(document.addForm.sameText.checked==true){
		 
		document.addForm.permanentAddress1.value    = document.addForm.correspondeceAddress1.value;
		document.addForm.permanentAddress1.disabled = true;

		document.addForm.permanentAddress2.value    = document.addForm.correspondeceAddress2.value;
		document.addForm.permanentAddress2.disabled = true;

		document.addForm.permanentPincode.value     = document.addForm.correspondecePincode.value;  
		document.addForm.permanentPincode.disabled  = true;

		document.addForm.permanentPhone.value       = document.addForm.correspondecePhone.value;
		document.addForm.permanentPhone.disabled    = true;

		for(i=document.addForm.correspondenceCountry.options.length-1;i>=0;i--){

			if(document.addForm.correspondenceCountry.options[i].selected)
				document.addForm.permanentCountry.options[i].selected=true;
		}
		var abc = (document.addForm.correspondenceStates.options[document.addForm.correspondenceStates.selectedIndex].text);

		document.addForm.permanentStates.options.length=0;
		var objOption = new Option(abc,"1");
        document.addForm.permanentStates.options.add(objOption); 

		var abc1 = (document.addForm.correspondenceCity.options[document.addForm.correspondenceCity.selectedIndex].text);
		document.addForm.permanentCity.options.length=0;
		var objOption = new Option(abc1,"1");
        document.addForm.permanentCity.options.add(objOption); 

		document.addForm.permanentCountry.disabled=true;
		document.addForm.permanentStates.disabled=true;
		document.addForm.permanentCity.disabled=true;
		
	}
	else{

		document.addForm.permanentAddress1.disabled=false;
		document.addForm.permanentAddress2.disabled=false;
		document.addForm.permanentPincode.disabled=false;

		document.addForm.permanentCountry.disabled=false;
		document.addForm.permanentStates.disabled=false;
		document.addForm.permanentCity.disabled=false;
		document.addForm.permanentPhone.disabled=false;

		document.addForm.permanentCountry.options[0].selected=true;

		document.addForm.permanentStates.options.length=0;
		var objOption = new Option("Select","");
        document.addForm.permanentStates.options.add(objOption); 

		document.addForm.permanentCity.options.length=0;
		var objOption = new Option("Select","");
        document.addForm.permanentCity.options.add(objOption); 
	}
}
window.onload=function(){
    document.addForm.reset();
    
	getInstituteClass("<?php echo $sessionHandler->getSessionVariable('InstituteId')?>");
	//$defaultInstituteId = ;
	/*START: function to populate father states and countries based on session values*/
    
    if("<?php $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD') ?>" != 1 ) {
	    autoPopulate('<?php echo $defaultCountryId?>','states','Add','fatherStates','fatherCity');
	    autoPopulate('<?php echo $defaultStateId?>','city','Add','fatherStates','fatherCity');
	    
	    document.getElementById('fatherCountry').value="<?php echo $defaultCountryId?>";
	    document.getElementById('fatherStates').value="<?php echo $defaultStateId?>";
	    document.getElementById('fatherCity').value="<?php echo $defaultCityId?>";
	    /*END: function to populate father states and countries based on session values*/

	    /*START: function to populate mother states and countries based on session values*/
	    autoPopulate('<?php echo $defaultCountryId?>','states','Add','motherStates','motherCity');
	    autoPopulate('<?php echo $defaultStateId?>','city','Add','motherStates','motherCity');
	    
	    document.getElementById('motherCountry').value="<?php echo $defaultCountryId?>";
	    document.getElementById('motherStates').value="<?php echo $defaultStateId?>";
	    document.getElementById('motherCity').value="<?php echo $defaultCityId?>";
	    /*END: function to populate mother states and countries based on session values*/

	    /*START: function to populate guardian states and countries based on session values*/
	    autoPopulate('<?php echo $defaultCountryId?>','states','Add','guardianStates','guardianCity');
	    autoPopulate('<?php echo $defaultStateId?>','city','Add','guardianStates','guardianCity');
	    
	    document.getElementById('guardianCountry').value="<?php echo $defaultCountryId?>";
	    document.getElementById('guardianStates').value="<?php echo $defaultStateId?>";
	    document.getElementById('guardianCity').value="<?php echo $defaultCityId?>";
	    /*END: function to populate guardian states and countries based on session values*/
    }

	/*START: function to populate correspondence states and countries based on session values*/
	autoPopulate('<?php echo $defaultCountryId?>','states','Add','correspondenceStates','correspondenceCity');
	autoPopulate('<?php echo $defaultStateId?>','city','Add','correspondenceStates','correspondenceCity');
	
	document.getElementById('correspondenceCountry').value="<?php echo $defaultCountryId?>";
	document.getElementById('correspondenceStates').value="<?php echo $defaultStateId?>";
	document.getElementById('correspondenceCity').value="<?php echo $defaultCityId?>";
	/*END: function to populate correspondence states and countries based on session values*/

	/*START: function to populate permanent states and countries based on session values*/
	autoPopulate('<?php echo $defaultCountryId?>','states','Add','permanentStates','permanentCity');
	autoPopulate('<?php echo $defaultStateId?>','city','Add','permanentStates','permanentCity');
	
	document.getElementById('permanentCountry').value="<?php echo $defaultCountryId?>";
	document.getElementById('permanentStates').value="<?php echo $defaultStateId?>";
	document.getElementById('permanentCity').value="<?php echo $defaultCityId?>";
	/*END: function to populate permanent states and countries based on session values*/
    
    document.getElementById('studentCategory').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_CATEGORY'); ?>';
    
    
    if("<?php $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD') ?>" != 1 ) {  
        /*START: function to populate permanent states and countries based on session values*/
        autoPopulate('<?php echo $defaultCountryId?>','states','Add','presentStates','presentCity');
        autoPopulate('<?php echo $defaultStateId?>','city','Add','presentStates','presentCity');
        
        document.getElementById('presentCountry').value="<?php echo $defaultCountryId?>";
        document.getElementById('presentStates').value="<?php echo $defaultStateId?>";
        document.getElementById('presentCity').value="<?php echo $defaultCityId?>";
        
        /*START: function to populate permanent states and countries based on session values*/
        autoPopulate('<?php echo $defaultCountryId?>','states','Add','spouseStates','spouseCity');
        autoPopulate('<?php echo $defaultStateId?>','city','Add','spouseStates','spouseCity');
        
        document.getElementById('spouseCountry').value="<?php echo $defaultCountryId?>";
        document.getElementById('spouseStates').value="<?php echo $defaultStateId?>";
        document.getElementById('spouseCity').value="<?php echo $defaultCityId?>";
    }
    else {
       var roll = document.getElementById("studentEntranceRole");
       autoSuggest(roll);
    }
}

function makeMotherFieldsToggle(state){

	 
    if(state){
        document.getElementById('motherCountry').value='';
        document.getElementById('motherStates').value='';
        document.getElementById('motherCity').value='';
        //document.getElementById('motherName').value='';
        document.getElementById('motherOccupation').value='';
        document.getElementById('motherEmail').value='';
        document.getElementById('motherMobile').value='';
        document.getElementById('motherAddress1').value='';
        document.getElementById('motherAddress2').value='';
        document.getElementById('motherContact').value='';
        document.getElementById('dipanjan2').focus();
		
    }
    else{

		document.getElementById('motherName').focus();
       document.getElementById('motherCountry').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
       document.getElementById('motherStates').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>';
       document.getElementById('motherCity').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
	   
    }
   document.getElementById('motherCountry').disabled=state;
   document.getElementById('motherStates').disabled=state;
   document.getElementById('motherCity').disabled=state;
  // document.getElementById('motherName').disabled=state;
   document.getElementById('motherOccupation').disabled=state;
   document.getElementById('motherEmail').disabled=state;
   document.getElementById('motherMobile').disabled=state;
   document.getElementById('motherAddress1').disabled=state;
   document.getElementById('motherAddress2').disabled=state;
   document.getElementById('motherContact').disabled=state;   
}


function makeGuardianFieldsToggle(state){
    if(state){
        document.getElementById('guardianCountry').value='';
        document.getElementById('guardianStates').value='';
        document.getElementById('guardianCity').value='';
        document.getElementById('guardianName').value='';
        document.getElementById('guardianOccupation').value='';
        document.getElementById('guardianEmail').value='';
        document.getElementById('guardianMobile').value='';
        document.getElementById('guardianAddress1').value='';
        document.getElementById('guardianAddress2').value='';
        document.getElementById('guardianContact').value='';
		document.getElementById('correspondeceAddress1').focus();
		
    }
    else{
       document.getElementById('guardianCountry').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_COUNTRY'); ?>';
       document.getElementById('guardianStates').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_STATE'); ?>';
       document.getElementById('guardianCity').value='<?php echo $sessionHandler->getSessionVariable('DEFAULT_CITY'); ?>';
    }
   document.getElementById('guardianCountry').disabled=state;
   document.getElementById('guardianStates').disabled=state;
   document.getElementById('guardianCity').disabled=state;
   document.getElementById('guardianName').disabled=state;
   document.getElementById('guardianOccupation').disabled=state;
   document.getElementById('guardianEmail').disabled=state;
   document.getElementById('guardianMobile').disabled=state;
   document.getElementById('guardianAddress1').disabled=state;
   document.getElementById('guardianAddress2').disabled=state;
   document.getElementById('guardianContact').disabled=state;   
}
function currentValue(valueSelected){

	//alert(valueSelected);
}

function changeCase(enterText){

	//alert(enterText);
	if(document.addForm.formCase[0].checked)
		document.getElementById(enterText).value=document.getElementById(enterText).value.toUpperCase();

	if(document.addForm.formCase[1].checked)
		document.getElementById(enterText).value=document.getElementById(enterText).value.toLowerCase();

	 
	//this.charAt(0).toUpperCase() + this.substring(1,this.length).toLowerCase();

}
function copyContactNo(value){
    document.getElementById('fatherContact').value=value;
    
    if(!document.getElementById('motherContact').disabled){
        document.getElementById('motherContact').value=value;
    }
    
    if(!document.getElementById('guardianContact').disabled){
        document.getElementById('guardianContact').value=value;
    }
    
}

function getCheckedGraduation() {
		document.getElementById('rollNo3').value = '';
		document.getElementById('rollNo3').disabled=false;
		document.getElementById('session3').value = '';
		document.getElementById('session3').disabled=false;
		document.getElementById('institute3').value = '';
		document.getElementById('institute3').disabled=false;
		document.getElementById('board3').value = '';
		document.getElementById('board3').disabled=false;
		document.getElementById('educationStream3').value = '';
		document.getElementById('educationStream3').disabled=false;
		document.getElementById('maxMarks3').value = '';
		document.getElementById('maxMarks3').disabled=false;
		document.getElementById('marks3').value = '';
		document.getElementById('marks3').disabled=false;
		document.getElementById('percentage3').value = '';
		document.getElementById('percentage3').disabled=false;

		document.getElementById('rollNo4').value = '';
		document.getElementById('rollNo4').disabled=false;
		document.getElementById('session4').value = '';
		document.getElementById('session4').disabled=false;
		document.getElementById('institute4').value = '';
		document.getElementById('institute4').disabled=false;
		document.getElementById('board4').value = '';
		document.getElementById('board4').disabled=false;
		document.getElementById('educationStream4').value = '';
		document.getElementById('educationStream4').disabled=false;
		document.getElementById('maxMarks4').value = '';
		document.getElementById('maxMarks4').disabled=false;
		document.getElementById('marks4').value = '';
		document.getElementById('marks4').disabled=false;
		document.getElementById('percentage4').value = '';
		document.getElementById('percentage4').disabled=false;

		document.getElementById('writtenFinalExamYes').disabled=true;
		document.getElementById('writtenFinalExamNo').disabled=true;
		document.getElementById('writtenFinalExamYes').checked = false;
		document.getElementById('writtenFinalExamNo').checked =false;
}

function getUnCheckedGraduation() {
		document.getElementById('rollNo3').value = '';
		document.getElementById('rollNo3').disabled=true;
		document.getElementById('session3').value = '';
		document.getElementById('session3').disabled=true;
		document.getElementById('institute3').value = '';
		document.getElementById('institute3').disabled=true;
		document.getElementById('board3').value = '';
		document.getElementById('board3').disabled=true;
		document.getElementById('educationStream3').value = '';
		document.getElementById('educationStream3').disabled=true;
		document.getElementById('maxMarks3').value = '';
		document.getElementById('maxMarks3').disabled=true;
		document.getElementById('marks3').value = '';
		document.getElementById('marks3').disabled=true;
		document.getElementById('percentage3').value = '';
		document.getElementById('percentage3').disabled=true;

		document.getElementById('rollNo4').value = '';
		document.getElementById('rollNo4').disabled=true;
		document.getElementById('session4').value = '';
		document.getElementById('session4').disabled=true;
		document.getElementById('institute4').value = '';
		document.getElementById('institute4').disabled=true;
		document.getElementById('board4').value = '';
		document.getElementById('board4').disabled=true;
		document.getElementById('educationStream4').value = '';
		document.getElementById('educationStream4').disabled=true;
		document.getElementById('maxMarks4').value = '';
		document.getElementById('maxMarks4').disabled=true;
		document.getElementById('marks4').value = '';
		document.getElementById('marks4').disabled=true;
		document.getElementById('percentage4').value = '';
		document.getElementById('percentage4').disabled=true;

		document.getElementById('writtenFinalExamYes').disabled=false;
		document.getElementById('writtenFinalExamNo').disabled=false;
		document.getElementById('writtenFinalExamNo').checked = true;

	//document.getElementById('writtenFinalExam').disabled=false;
}

function getEducationYes() {
	document.getElementById('bankName').value = '';
	document.getElementById('bankName').disabled = true;
	document.getElementById('loanAmount').value = '';
	document.getElementById('loanAmount').disabled = true;

}

function getEducationNo() {
	document.getElementById('bankName').disabled = false;
	document.getElementById('loanAmount').disabled = false;
}

function getHostelFacility() {
	document.getElementById('yearsInHostel').disabled = false;
}

function getHostelFacilityNo() {
	document.getElementById('yearsInHostel').value = '';
	document.getElementById('yearsInHostel').disabled = true;
}

function getAilmentYes() {
	document.getElementById('natureAilment').disabled = false;
	document.getElementById('familyAilment').disabled = false;
	document.getElementById('otherAilment').disabled = false;
}

function getAilmentNo() {
	document.getElementById('natureAilment').value = '';
	document.getElementById('natureAilment').disabled = true;
	var obj1 = document.getElementById('familyAilment');
	var obj = document.getElementById('familyAilment').options.length;
	for (var h=0; h < obj; h++) {
		obj1[h].selected = false;
	}
	document.getElementById('familyAilment').disabled = true;
	document.getElementById('otherAilment').value = '';
	document.getElementById('otherAilment').disabled = true;
}

function changeCoaching() {
	if(document.getElementById('coachingCenter').value != '') {
		document.getElementById('coachingManager').disabled = false;
		document.getElementById('address').disabled = false;
	}
	else {
		document.getElementById('coachingManager').value = '';
		document.getElementById('coachingManager').disabled = true;
		document.getElementById('address').value = '';
		document.getElementById('address').disabled = true;
	}
}

function getWorkExperienceYes() {
	document.getElementById('department').disabled = false;
	document.getElementById('organization').disabled = false;
	document.getElementById('place').disabled = false;
}

function getWorkExperienceNo() {
	document.getElementById('department').value = '';
	document.getElementById('department').disabled = true;
	document.getElementById('organization').value = '';
	document.getElementById('organization').disabled = true;
	document.getElementById('place').value = '';
	document.getElementById('place').disabled = true;
}


function copyFatherText(){

	 
	if((document.addForm.sameFatherText.checked==true)){
		 
		document.addForm.motherOccupation.value    = document.addForm.fatherOccupation.value;
		//document.addForm.motherOccupation.disabled = true;

		document.addForm.motherEmail.value    = document.addForm.fatherEmail.value;
		//document.addForm.motherEmail.disabled = true;

		document.addForm.motherMobile.value     = document.addForm.fatherMobile.value;  
		//document.addForm.motherMobile.disabled  = true;

		document.addForm.motherAddress1.value       = document.addForm.fatherAddress1.value;
		//document.addForm.motherAddress1.disabled    = true;

		document.addForm.motherAddress2.value       = document.addForm.fatherAddress2.value;
		//document.addForm.motherAddress2.disabled    = true;

		document.addForm.motherContact.value       = document.addForm.fatherContact.value;
		//document.addForm.motherContact.disabled    = true;

		document.addForm.motherPincode.value       = document.addForm.fatherPincode.value;
		//document.addForm.motherPincode.disabled    = true;

		for(i=document.addForm.fatherCountry.options.length-1;i>=0;i--){

			if(document.addForm.fatherCountry.options[i].selected)
				document.addForm.motherCountry.options[i].selected=true;
		}
		var abc = (document.addForm.fatherStates.options[document.addForm.fatherStates.selectedIndex].text);

		document.addForm.motherStates.options.length=0;
		var objOption = new Option(abc,"1");
        document.addForm.motherStates.options.add(objOption); 

		var abc1 = (document.addForm.fatherCity.options[document.addForm.fatherCity.selectedIndex].text);
		document.addForm.motherCity.options.length=0;
		var objOption = new Option(abc1,"1");
        document.addForm.motherCity.options.add(objOption); 

		//document.addForm.motherCountry.disabled=true;
		//document.addForm.motherStates.disabled=true;
		//document.addForm.motherCity.disabled=true;
		
	}
	else{

		document.addForm.motherOccupation.disabled=false;
		document.addForm.motherEmail.disabled=false;
		document.addForm.motherMobile.disabled=false;

		document.addForm.motherAddress1.disabled=false;
		document.addForm.motherAddress2.disabled=false;
		document.addForm.motherContact.disabled=false;
		document.addForm.motherCountry.disabled=false;
		document.addForm.motherStates.disabled=false;
		document.addForm.motherCity.disabled=false;
		document.addForm.motherPincode.disabled=false;
	}
}

function copyGuardianText(){

	 
	if((document.addForm.sameFatherText1.checked==true)){
		 
		document.addForm.sameMotherText.checked=false;

		document.addForm.guardianOccupation.value    = document.addForm.fatherOccupation.value;
		//document.addForm.guardianOccupation.disabled = true;

		document.addForm.guardianEmail.value    = document.addForm.fatherEmail.value;
		//document.addForm.guardianEmail.disabled = true;

		document.addForm.guardianMobile.value     = document.addForm.fatherMobile.value;  
		//document.addForm.guardianMobile.disabled  = true;

		document.addForm.guardianAddress1.value       = document.addForm.fatherAddress1.value;
		//document.addForm.guardianAddress1.disabled    = true;

		document.addForm.guardianAddress2.value       = document.addForm.fatherAddress2.value;
		//document.addForm.guardianAddress2.disabled    = true;

		document.addForm.guardianContact.value       = document.addForm.fatherContact.value;
		//document.addForm.guardianContact.disabled    = true;

		document.addForm.guardianPincode.value       = document.addForm.fatherPincode.value;
		//document.addForm.guardianPincode.disabled    = true;

		for(i=document.addForm.fatherCountry.options.length-1;i>=0;i--){

			if(document.addForm.fatherCountry.options[i].selected)
				document.addForm.guardianCountry.options[i].selected=true;
		}
		var abc = (document.addForm.fatherStates.options[document.addForm.fatherStates.selectedIndex].text);

		document.addForm.guardianStates.options.length=0;
		var objOption = new Option(abc,"1");
        document.addForm.guardianStates.options.add(objOption); 

		var abc1 = (document.addForm.fatherCity.options[document.addForm.fatherCity.selectedIndex].text);
		document.addForm.guardianCity.options.length=0;
		var objOption = new Option(abc1,"1");
        document.addForm.guardianCity.options.add(objOption); 

		//document.addForm.guardianCountry.disabled=true;
		//document.addForm.guardianStates.disabled=true;
		//document.addForm.guardianCity.disabled=true;
		
	}
	else{

		document.addForm.guardianOccupation.disabled=false;
		document.addForm.guardianEmail.disabled=false;
		document.addForm.guardianMobile.disabled=false;

		document.addForm.guardianAddress1.disabled=false;
		document.addForm.guardianAddress2.disabled=false;
		document.addForm.guardianContact.disabled=false;
		document.addForm.guardianCountry.disabled=false;
		document.addForm.guardianStates.disabled=false;
		document.addForm.guardianCity.disabled=false;
		document.addForm.guardianPincode.disabled=false;
 
	}
}
function copyGuardianMotherText(){

	 
	if((document.addForm.sameMotherText.checked==true)){
		
		document.addForm.sameFatherText1.checked=false; 
		document.addForm.guardianOccupation.value    = document.addForm.motherOccupation.value;
		document.addForm.guardianOccupation.disabled = true;

		document.addForm.guardianEmail.value    = document.addForm.motherEmail.value;
		document.addForm.guardianEmail.disabled = true;

		document.addForm.guardianMobile.value     = document.addForm.motherMobile.value;  
		document.addForm.guardianMobile.disabled  = true;

		document.addForm.guardianAddress1.value       = document.addForm.motherAddress1.value;
		document.addForm.guardianAddress1.disabled    = true;

		document.addForm.guardianAddress2.value       = document.addForm.motherAddress2.value;
		document.addForm.guardianAddress2.disabled    = true;

		document.addForm.guardianContact.value       = document.addForm.motherContact.value;
		document.addForm.guardianContact.disabled    = true;

		document.addForm.guardianPincode.value       = document.addForm.motherPincode.value;
		document.addForm.guardianPincode.disabled    = true;

		for(i=document.addForm.motherCountry.options.length-1;i>=0;i--){

			if(document.addForm.motherCountry.options[i].selected)
				document.addForm.guardianCountry.options[i].selected=true;
		}
		var abc = (document.addForm.motherStates.options[document.addForm.motherStates.selectedIndex].text);

		document.addForm.guardianStates.options.length=0;
		var objOption = new Option(abc,"1");
        document.addForm.guardianStates.options.add(objOption); 

		var abc1 = (document.addForm.motherCity.options[document.addForm.motherCity.selectedIndex].text);
		document.addForm.guardianCity.options.length=0;
		var objOption = new Option(abc1,"1");
        document.addForm.guardianCity.options.add(objOption); 

		document.addForm.guardianCountry.disabled=true;
		document.addForm.guardianStates.disabled=true;
		document.addForm.guardianCity.disabled=true;
		
	}
	else{

		document.addForm.guardianOccupation.disabled=false;
		document.addForm.guardianEmail.disabled=false;
		document.addForm.guardianMobile.disabled=false;

		document.addForm.guardianAddress1.disabled=false;
		document.addForm.guardianAddress2.disabled=false;
		document.addForm.guardianContact.disabled=false;
		document.addForm.guardianCountry.disabled=false;
		document.addForm.guardianStates.disabled=false;
		document.addForm.guardianCity.disabled=false;
		document.addForm.guardianPincode.disabled=false;
	}
}
</script>
</head>
<body>
    <?php 
		require_once(TEMPLATES_PATH . "/header.php");
		require_once(TEMPLATES_PATH . "/Student/addStudentContents.php");
		require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: admitStudent.php $
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 10-03-27   Time: 10:42a
//Updated in $/LeapCC/Interface
//resolved bug no 0002941
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 10-02-23   Time: 3:46p
//Updated in $/LeapCC/Interface
//updated admit student with config setting for registration number
//
//*****************  Version 23  *****************
//User: Gurkeerat    Date: 2/09/10    Time: 11:33a
//Updated in $/LeapCC/Interface
//set the default value for category
//
//*****************  Version 22  *****************
//User: Gurkeerat    Date: 4/02/10    Time: 18:57
//Updated in $/LeapCC/Interface
//resolved issues 0002650,0002620,0002098,0001602,0002788,0002785
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 10-01-28   Time: 1:46p
//Updated in $/LeapCC/Interface
//earlier it was showing all classes. not it will show classes based on
//academic head previleges 
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 09-11-11   Time: 3:50p
//Updated in $/LeapCC/Interface
//added validations on domicile and nationality
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 09-11-06   Time: 3:00p
//Updated in $/LeapCC/Interface
//Bug Fix 0001181, 0001944,0001601
//
//*****************  Version 18  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 8/07/09    Time: 4:31p
//Updated in $/LeapCC/Interface
//Gurkeerat: Updated access defines
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 7/30/09    Time: 1:25p
//Updated in $/LeapCC/Interface
//1) 0000758: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message. 
//2) 0000757: Admit (Admin) > Focus should be move back to appropriate
//field text box after validation message. 
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Interface
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:48p
//Updated in $/LeapCC/Interface
//Updated with transactions in admit student
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:20p
//Updated in $/LeapCC/Interface
//Updated with Onkeyup event to onblur event in previous academic
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 6/24/09    Time: 5:32p
//Updated in $/LeapCC/Interface
//Updated with default institute and degree
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 6/22/09    Time: 4:27p
//Updated in $/LeapCC/Interface
//Made Enhancements:
//1) Changing Case as per requirements
//2) N/A for mother name to be removed
//
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Interface
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Interface
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 5/27/09    Time: 6:59p
//Updated in $/LeapCC/Interface
//added reference name,blood group, fee receipt no,institute wise search
//for class student previous academic in admit student
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 4/08/09    Time: 6:14p
//Updated in $/LeapCC/Interface
//fixed bugs
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//Updated with Required field, centralized message, left align
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 1/10/09    Time: 11:05a
//Updated in $/LeapCC/Interface
//removed Date of Birth, Email, Contact Number, Nationality, Domicile
//and Titles  validations
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 1/06/09    Time: 12:40p
//Updated in $/LeapCC/Interface
//removed last name validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/23/08   Time: 2:16p
//Updated in $/LeapCC/Interface
//Updated with single class selection dropdown and REQUIRED_FIELD
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 9/08/08    Time: 4:51p
//Updated in $/Leap/Source/Interface
//updated with duplicate student email address
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/27/08    Time: 3:25p
//Updated in $/Leap/Source/Interface
//updated formatting
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/25/08    Time: 5:31p
//Updated in $/Leap/Source/Interface
//updated date of birth validations
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/08/08    Time: 11:42a
//Updated in $/Leap/Source/Interface
//updated all the validations mentioned by QA
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/07/08    Time: 2:27p
//Updated in $/Leap/Source/Interface
//changed validation message
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:30p
//Updated in $/Leap/Source/Interface
//remove all the demo issues
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/29/08    Time: 1:43p
//Updated in $/Leap/Source/Interface
//made minor modifications
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/18/08    Time: 7:05p
//Updated in $/Leap/Source/Interface
//replaced winalert with msgbox function
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/17/08    Time: 11:58a
//Updated in $/Leap/Source/Interface
//updated date of birth check
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/12/08    Time: 2:27p
//Updated in $/Leap/Source/Interface
//updated the javascript issue for email
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/10/08    Time: 5:53p
//Updated in $/Leap/Source/Interface
//made the student admit module ajax based
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/09/08    Time: 3:13p
//Updated in $/Leap/Source/Interface
//updated admint student with domicile, mgmt category and management
//reference fields
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/07/08    Time: 4:14p
//Created in $/Leap/Source/Interface
//intial checkin
?>
