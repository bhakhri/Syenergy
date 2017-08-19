<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Student Registration Form
// Author :Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");

define('MODULE','COMMON');
define('ACCESS','view');

UtilityManager::ifStudentNotLoggedIn();  
UtilityManager::headerNoCache();
require_once(BL_PATH . "/RegistrationForm/Student/initStudentData.php");
require_once(MODEL_PATH . "/RegistrationForm/ScStudentRegistration.inc.php");


// Following Will Fetch the results form the database through scStudentRegistration.inc.php file
$studentRegistration = StudentRegistration::getInstance();
$cgpa=$studentRegistration->getCGPA($sessionHandler->getSessionVariable('StudentId'));
$studentDataArr[0]['cgpa']=UtilityManager::decimalRoundUp($cgpa[0]['cgpa']);
$mentor=$studentRegistration->getMentorName($sessionHandler->getSessionVariable('StudentId'),$sessionHandler->getSessionVariable('ClassId'));

if($mentor[0]['mentorUserName']==""){
      $studentDataArr[0]['mentor']="";
}
else{
 $studentDataArr[0]['mentor']=$mentor[0]['mentorUserName'];
}

$scholarType=$studentRegistration->getScholarType($sessionHandler->getSessionVariable('StudentId'),$sessionHandler->getSessionVariable('ClassId'));
$studentDataArr[0]['scholarType']=$scholarType[0]['dayScholar'];

$studentId = $sessionHandler->getSessionVariable('StudentId');
/* function to fetch student Previous Academic*/
$academicRecordArrayReg = $studentRegistration->getStudentAcademicList( " WHERE sa.studentId = '".$studentId."'",'previousClassId');
$academicRecordArrayReg = COUNT($academicRecordArrayReg);
for($i=0;$i<$academicRecordArrayReg;$i++){
  //echo "--".$academicRecordArray[$i]['previousClassId'];
  $rollArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousRollNo'];
  $sessionArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousSession'];
  $instituteArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousInstitute'];
  $boardArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousBoard'];
  $marksArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousMarks'];
  $educationArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousEducationStream'];
  $maxMarksArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousMaxMarks'];
  $perArrReg[$academicRecordArrayReg[$i]['previousClassId']] = $academicRecordArrayReg[$i]['previousPercentage'];
}   
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Registration Form</title>



<?php                                                  
  require_once(TEMPLATES_PATH .'/jsCssHeader.php');
  function parseOutput($data){
    return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
  }
?> 

<!-- This is used to display the bubble validations using Jquery Library -->
<link rel="stylesheet" href="<?php echo CSS_PATH;?>/validationEngine.jquery.css" type="text/css"/>
 <script src="<?php echo JS_PATH;?>/jquery.validationEngine-en.js" type="text/javascript" ></script>
 <script src="<?php echo JS_PATH;?>/jquery.validationEngine.js" type="text/javascript" ></script>
<script>
    jQuery(document).ready(function(){

        jQuery("#registrationForm").validationEngine('attach');
       });
    </script>

<script language="javascript">

recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Student/scAjaxCourseResourceList.php';
searchFormName = 'registrationForm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
divResultName  = 'subjectsDiv';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';
var flag = false;
check=0;

 var dtArray=new Array();  


 var tbHeadArray =	new Array(new Array('srNo','#','width="3%"',false),
								new Array ('subjectCode','Course Code','width="15%"',true),
								new Array('subjectName','Title','width="40%"',true),
								new Array('sectionName','Lecture Section','width="12%"',true),
								new Array('periodName','Study Period','width="12%"',false),
								
								new Array('sectionType','Type','width="7%"',true)							
								);

//displays the values in the table

function getSection(value) {

		url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Student/ajaxInitStudentGroupDetail.php';


		var tbHeadArray =	new Array(new Array('srNo','#','width="5%"',false), 
							new Array('periodName','For','width="12%"',false),
							new Array('groupName','Group','width="15%"',true), 
							new Array('groupTypeName','Group Type','width="15%"',true),
							new Array ('subjectCode','Subjet Code','width="15%"',true), 
							new Array('subjectName','Subject','width="40%"',true));
		
		listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','groupName','ASC','subjectsDiv','','',true,'listObj1',tbHeadArray,'','','&semesterDetail='+value);
		sendRequest(url, listObj1, '')

}


//checks the validation of the form

function validateLoginForm(){
 

        var fieldsArray = new Array(new Array("dateOfBirth","<?php echo 'Please select date of birth' ?>"),
                                    new Array("name","<?php echo 'Enter Student Name' ?>"),
                                    new Array("fatherName","<?php echo 'Enter Father Email' ?>"),
                                    new Array("universityRollNo","<?php echo 'Enter Identification No.' ?>"),
                                    new Array("studentEmail","<?php echo 'Enter Student Email' ?>"),
                                    new Array("parentsNumber","<?php echo 'Enter Latest Mobile No. of the Parent'?>"),
                                    new Array("studentNumber","<?php echo 'Enter Your Mobile No.'?>"),
                                    new Array("bloodGroup","<?php echo 'Select Your Blood Group'?>"),
                                    new Array("address","<?php echo 'Enter Your Latest Correspondence Address'?>"),
                                    new Array("mentor","<?php echo 'Enter Your Teacher Mentor'?>"),
                                    new Array("cityNative","<?php echo 'Enter Your Native City'?>"),
                                    new Array("stateNative","<?php echo 'Enter Your Native State'?>"),
                                    //new Array("landlineNo","<?php echo 'Enter Your Latest Landline No.'?>"),
                                    new Array("status","<?php echo 'Select Your Scholar Status'?>"));

       var len = fieldsArray.length;
       var frm = document.registrationForm;

    for(i=0;i<len;i++) {
		 if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
                        messageBox(fieldsArray[i][1],fieldsArray[i][0]);
			eval("frm."+(fieldsArray[i][0])+".focus();");
                        return false;
            		break;
        	}

		else if(fieldsArray[i][0]=="studentNumber"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value")) || (document.getElementById('studentNumber').value).length!=10) { //if not valid phone format
                 messageBox("Please enter a valid mobile number"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
             }
        }

		else if(fieldsArray[i][0]=="studentEmail"){
            if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) {//if not valid email format
                 messageBox("<?php echo STUDENT_VALID_EMAIL ?>");
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
              }
        }

		else if(fieldsArray[i][0]=="parentsNumber"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_PHONE ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
             }
        }
        /*
	    else if(fieldsArray[i][0]=="landlineNo"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_PHONE ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
             }
        }
        */
		else if(document.getElementById('parentEmail').value){
			if(!isEmail(eval("frm."+('parentEmail')+".value"))){  //if not valid email format
			messageBox("<?php echo 'Enter Valid Parent Email'?>");
			eval("frm."+('parentEmail')+".focus();");
			return false;
		}
	}
             
		
    }
                       
     if(trim(document.getElementById('landlineNo').value)!='') {
         if(!isPhone(trim(document.getElementById('landlineNo').value))) { //if not valid phone format
            messageBox("<?php echo VALID_PHONE ?>"); 
            document.getElementById('landlineNo').focus();
            return false;
         }
     }
    
   
              if(document.getElementById('status').value==1){		
		var travelArray = new Array(new Array("travel","<?php echo 'Select Your Travelling Status';  ?>"),
                                    new Array("routeNo","<?php echo 'Enter Route No.' ?>"),
                                    new Array("pickUp","<?php echo 'Enter Your Pick Up Point' ?>"),
                                    new Array("travellingPt","<?php echo 'Enter Travelling From (Mention Place)' ?>"),
                                    new Array("vehicleType","<?php echo 'Enter Your Vehicle Type' ?>"),
                                    new Array("vehicleRegistration","<?php echo 'Enter Vehicle Registration Number' ?>"),
                                    new Array("PgName","<?php echo 'Enter your PG owner name' ?>"),
                                    new Array("address1","<?php echo 'Enter your PG address' ?>"),
                                    new Array("pgContact1","<?php echo 'Enter your PG contact No.' ?>"));

                   if(document.getElementById('travel').value==1)
                  {
              	    for(i=0;i<=2;i++) {
		     	if(isEmpty(document.getElementById(travelArray[i][0]).value) ) {
                     	   messageBox(travelArray[i][1],travelArray[i][0]);
				eval("frm."+(travelArray[i][0])+".focus();");
                     	   return false;
            			break;
        		     }
	            }
	        }



	         if(document.getElementById('travel').value==2)
                  {
              	    for(i=3;i<=5;i++) {
		     	if(isEmpty(document.getElementById(travelArray[i][0]).value) ) {
                     	   messageBox(travelArray[i][1],travelArray[i][0]);
				eval("frm."+(travelArray[i][0])+".focus();");
                     	   return false;
            			break;
        		     }
	            }
	        }
	        
	           if(document.getElementById('travel').value==3)
                  {
              	    for(i=6;i<=8;i++) {
		     	if(isEmpty(document.getElementById(travelArray[i][0]).value) ) {
                     	   messageBox(travelArray[i][1],travelArray[i][0]);
				eval("frm."+(travelArray[i][0])+".focus();");
                     	   return false;
            			break;
        		     }
	            }
	        }
	   } 
                  



      	     if(document.getElementById('status').value==2){ 
                  
              	var hostelArray = new Array(
				new Array("hostelName","<?php echo 'Select Your Hostel' ?>"),
        			new Array("roomNo","<?php echo 'Enter Your Room Number' ?>"),
        			new Array("wardenContact","<?php echo 'Enter Warden Contact No' ?>")
		    );

   		 var hoslen = hostelArray.length;
      
                 for(i=0;i<hoslen;i++) {
		     if(isEmpty(document.getElementById(hostelArray[i][0]).value) ) {
                        messageBox(hostelArray[i][1],hostelArray[i][0]);
			eval("frm."+(hostelArray[i][0])+".focus();");
                        return false;
            		break;
        	     }
                    else if(hostelArray[i][0]=="roomNo" ) {
                     var roomNo=document.getElementById(hostelArray[i][0]).value;
                     var alphanum=/^[0-9a-bA-B]+$/;                 //Expression Will Check The AlphaNumric Value Of The RoomNo
                    if(roomNo.match(alphanum)==null){
                        alert("Room No Should Be AlphaNumeric Only");
            	         eval("frm."+(hostelArray[i][0])+".focus();");
              	        return false;
              	        break;  
                      }

                  }  

      	        }
		       
	    }
      	     if(document.getElementById('status').value==3){ 
                  
              	var pgArray = new Array(new Array("pgOwner","<?php echo 'Enter name of the owner of PG' ?>"),
        			                    new Array("pgAddress","<?php echo 'Enter address of PG' ?>"));

   		 var pglen = pgArray.length;
      
                 for(i=0;i<pglen;i++) {
		     if(isEmpty(document.getElementById(pgArray[i][0]).value) ) {
                        messageBox(pgArray[i][1],pgArray[i][0]);
			eval("frm."+(pgArray[i][0])+".focus();");
                        return false;
            		break;
        	     }
		else if(document.getElementById('pgContact').value){
			if(!isPhone(eval("frm."+('pgContact')+".value"))){  //if not valid phone Number
			messageBox("<?php echo 'Enter Valid PG Contact'?>");
			eval("frm."+('pgContact')+".focus();");
			return false;
			}
		}	  

      	   }
		       
       }
      	     if(document.getElementById('status').value==4){ 
                  
              	var traineeArray = new Array(
				new Array("companyName","<?php echo 'Enter name of the company' ?>"),
                new Array("companyCity","<?php echo 'Enter city' ?>"),
                new Array("companyHR","<?php echo 'Enter name of company HR' ?>"),
                new Array("companyEmailId","<?php echo 'Enter company Email Id' ?>"),
                new Array("companyContactNo","<?php echo 'Enter the contact no. of company' ?>"),
                new Array("companyProjectName","<?php echo 'Enter the project name' ?>"),
        		new Array("companyAddress","<?php echo 'Enter the address of company' ?>")
		    );

   		 var traineelen = traineeArray.length;
      
                 for(i=0;i<traineelen;i++) {
		     if(isEmpty(document.getElementById(traineeArray[i][0]).value) ) {
                        messageBox(traineeArray[i][1],traineeArray[i][0]);
			eval("frm."+(traineeArray[i][0])+".focus();");
                        return false;
            		break;
        	     }  

      	        }
	    }
        
       if(document.getElementById('chkAIEEE').value==1) {  
          if(trim(document.getElementById('aieeeRollNo').value)=='') { 
            document.getElementById('aieeeRollNo').focus();
            document.getElementById('aieeeRollNo').className = 'inputboxRed';
            messageBox("<?php echo 'Enter AIEEE Roll No.'?>");     
            return false; 
          } 
          if(trim(document.getElementById('aieeeRank').value)=='') { 
            document.getElementById('aieeeRank').focus();
            document.getElementById('aieeeRank').className = 'inputboxRed';
            messageBox("<?php echo 'Enter AIEEE Rank'?>");     
            return false; 
          } 
       }
        
       var len= document.getElementById('ttAcademicClass').options.length;
       var t=document.getElementById('ttAcademicClass');
       var acdCheck='';
       if(len>0) {
         for(ii=0;ii<len;ii++) { 
            kk = t.options[ii].value; 
            if(calculatePercentage(kk)==0) {
               return false;  
            } 
            
            acdCheck ='';
            if(ii==0) {
                  if(eval("trim(document.getElementById('session"+kk+"').value)")=='') {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Year of Passing'?>");     
                     return false;
                  }  
                  if(!isDecimal(trim(eval("document.getElementById('session"+kk+"').value")))) {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter numeric value for Year of Passing'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('board"+kk+"').value)")=='') {  
                     eval("document.getElementById('board"+kk+"').focus()");
                     eval("document.getElementById('board"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Name of Board/University'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('marks"+kk+"').value)")=='') {  
                     eval("document.getElementById('marks"+kk+"').focus()");
                     eval("document.getElementById('marks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Marks Obtained'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('maxMarks"+kk+"').value)")=='') {  
                     eval("document.getElementById('maxMarks"+kk+"').focus()");
                     eval("document.getElementById('maxMarks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Max. Marks'?>");     
                     return false;
                  }  
            }
            else {
              if(document.getElementById('chkAcademic_2').value==1 && ii==1)   {
                  if(eval("trim(document.getElementById('session"+kk+"').value)")=='') {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Year of Passing'?>");     
                     return false;
                  }  
                  if(!isDecimal(trim(eval("document.getElementById('session"+kk+"').value")))) {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter numeric value for Year of Passing'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('board"+kk+"').value)")=='') {  
                     eval("document.getElementById('board"+kk+"').focus()");
                     eval("document.getElementById('board"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Name of Board/University'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('marks"+kk+"').value)")=='') {  
                     eval("document.getElementById('marks"+kk+"').focus()");
                     eval("document.getElementById('marks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Marks Obtained'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('maxMarks"+kk+"').value)")=='') {  
                     eval("document.getElementById('maxMarks"+kk+"').focus()");
                     eval("document.getElementById('maxMarks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Max. Marks'?>");     
                     return false;
                  }   
              }
              if(document.getElementById('chkAcademic_3').value==1 && ii==2) {  
                  if(eval("trim(document.getElementById('session"+kk+"').value)")=='') {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Year of Passing'?>");     
                     return false;
                  }  
                  if(!isDecimal(trim(eval("document.getElementById('session"+kk+"').value")))) {  
                     eval("document.getElementById('session"+kk+"').focus()");
                     eval("document.getElementById('session"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter numeric value for Year of Passing'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('board"+kk+"').value)")=='') {  
                     eval("document.getElementById('board"+kk+"').focus()");
                     eval("document.getElementById('board"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Name of Board/University'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('marks"+kk+"').value)")=='') {  
                     eval("document.getElementById('marks"+kk+"').focus()");
                     eval("document.getElementById('marks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Marks Obtained'?>");     
                     return false;
                  }  
                  if(eval("trim(document.getElementById('maxMarks"+kk+"').value)")=='') {  
                     eval("document.getElementById('maxMarks"+kk+"').focus()");
                     eval("document.getElementById('maxMarks"+kk+"').className = 'inputboxRed'"); 
                     messageBox("<?php echo 'Enter Max. Marks'?>");     
                     return false;
                  }  
              }
            }
            
            if(ii==acdCheck) {
              
            }        
         }
       }
       


    if(document.registrationForm.undertaking.checked==false){
        messageBox("<?php echo 'Accept The Agreement First'?>");     
        document.registrationForm.undertaking.focus();
        return false;
    }

      addStudent();
      return false;
}


// Adding a student in the Database

function addStudent() {
   var allowIpCheck=document.getElementById('allowIpCheck').value;
   if(allowIpCheck=='0'){
   return false;
   }
   if(false===confirm("Have You Verified Your Data Because You have Got Only One Chance?")) {
             return false;
         } 
   else{
	 url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Student/ajaxAddRegistration.php';
	 new Ajax.Request(url,
	 {
		 method:'post',
		 parameters: $('registrationForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){		
		    hideWaitDialog(true);
            if("<?php echo SUCCESS;?>"==trim(transport.responseText)) {
               messageBox("Registration form submitted successfully");   
            }
            else {
               messageBox(trim(transport.responseText));
            }
            window.location = "<?php echo UI_HTTP_PATH;?>/Student/listStudentInformation.php";
            return false;
 		},
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });  
  }
 
}



function blankValues() {
	document.registrationForm.hostelName.value='';
	document.registrationForm.wardenName.value='';
	document.registrationForm.roomNo.value='';
	document.registrationForm.wardenContact.value='';
	document.registrationForm.routeNo.value='';
	document.registrationForm.travellingPt.value='';
	document.registrationForm.vehicleType.value='';
	document.registrationForm.vehicleRegistration.value='';
	document.registrationForm.pgOwner.value='';
	document.registrationForm.pgContact.value='';
	document.registrationForm.pgAddress.value='';
	document.registrationForm.companyName.value='';
	document.registrationForm.companyAddress.value='';
}




// Use to show the further options using selection

 
function getStatus() {
     
   document.getElementById('hostel1').style.display='none';
   document.getElementById('hostel2').style.display='none';
   document.getElementById('travel1').style.display='none';
   document.getElementById('travel2').style.display='none';
   document.getElementById('travel3').style.display='none';
   document.getElementById('travel0').style.display='none'; 
   document.getElementById('pg1').style.display='none'; 
   document.getElementById('pg2').style.display='none'; 
   document.getElementById('trainee1').style.display='none'; 
   document.getElementById('trainee2').style.display='none'; 
   document.getElementById('trainee3').style.display='none'; 
   document.getElementById('trainee4').style.display='none'; 

   if(document.getElementById('status').value==2) {
     blankValues();
     hideValues();
     document.registrationForm.pickUp.value='';
     document.registrationForm.travel.value='';
     document.getElementById('hostel1').style.display=''; 
     document.getElementById('hostel2').style.display=''; 
     
   	}
 
   else if(document.getElementById('status').value==1){
     blankValues();
     document.getElementById('travel').value='';
     document.getElementById('travel0').style.display='';
   }

  else if(document.getElementById('status').value==3){
	blankValues();
   	document.getElementById('pg1').style.display=''; 
   	document.getElementById('pg2').style.display=''; 
  }
  else if(document.getElementById('status').value==4){
	blankValues();
   	document.getElementById('trainee1').style.display=''; 
    document.getElementById('trainee2').style.display=''; 
    document.getElementById('trainee3').style.display=''; 
    document.getElementById('trainee4').style.display=''; 
  }
  else if(document.getElementById('status').value==5){
	blankValues();
  }
  return false;	 
}




function getTravel() {
   document.getElementById('travel1').style.display='none';
   document.getElementById('travel2').style.display='none';
   document.getElementById('travel3').style.display='none';
   document.getElementById('travel12').style.display='none';
   document.getElementById('travel13').style.display='none'; 
   if(document.getElementById('status').value==1 && document.getElementById('travel').value==1) {
     blankValues();
     document.getElementById('travel1').style.display='';
     hideValues();
   }
   if(document.getElementById('status').value==1 && document.getElementById('travel').value==2) {
     blankValues();
     document.getElementById('travel2').style.display='';
     document.getElementById('travel3').style.display=''; 
   }
      if(document.getElementById('status').value==1 && document.getElementById('travel').value==3) {
     blankValues();
     document.getElementById('travel12').style.display='';
     document.getElementById('travel13').style.display=''; 
   }
}

// This function gives fills warden name and contact number according to the selected hostel name
function getHostelDetails() {
    document.getElementById('wardenName').value='';
    document.getElementById('wardenContact').value='';
    value = document.getElementById('hostelName').value;
    if(value!='') {
      var rval=value.split('!~~!!~~!');
      document.getElementById('wardenName').value=rval[2];
      document.getElementById('wardenContact').value=rval[3];
    }
}

function checkDuplicate(value) {
    
    var ii= dtArray.length;
    var fl=1;
    for(var kk=0;kk<ii;kk++){
      if(dtArray[kk]==value){
        fl=0;
        break;
      }  
    }
    if(fl==1){
      dtArray.push(value);
    } 
    
    return fl;
}


// this function displays the pick up points of the selected route no
function getTravelDetails() {   

    dtArray.splice(0,dtArray.length); //empty the array  
    
    var len= document.getElementById('hiddenRouteStop').options.length;
    var t=document.getElementById('hiddenRouteStop');
    
    document.registrationForm.route1.length = null; 
    addOption(document.getElementById('route1'), '', 'Select');
    
    document.registrationForm.route1.style.display = 'none';
    
    routeNo =  document.getElementById('routeNo').value;
    
    if(routeNo!='') {
        document.registrationForm.route1.style.display = '';
        if(len>0) {
          for(k=1;k<len;k++) { 
            retId = (t.options[k].value).split('!~~!!~~!'); 
            routeName = retId[1];
            stopName = retId[2];
            if(routeName==routeNo) {
                if(checkDuplicate(stopName)!=0) {  
                  addOption(document.getElementById('route1'), stopName,  stopName);
                }
            }
          }
        } 
    }
    
}


// This function will hide the checkboxes and will make their default value=""
function hideValues() {
    document.registrationForm.route1.style.display = 'none'; 
}



window.onload=function() { 
   try {
     getRoute();  
     getSection(document.getElementById('currentClassId').value);
   }  catch(e){ }    
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
          return 0;
        }
        else{
          eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
        }
        if (!reg.test(document.getElementById('maxMarks'+cnt).value)){
          flag1=1;    
          alert("<?php echo ENTER_MAX_MARKS_TO_NUM; ?>");
          document.getElementById('maxMarks'+cnt).focus();
          eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
          return 0;
        }
        else{
          eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
        }

        if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){
            flag2=1;    
            alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
            //document.getElementById('marks'+cnt).value=0;
            document.getElementById('percentage'+cnt).value=0;
            document.getElementById('marks'+cnt).focus();
            eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
            return 0;
        }
        else{
          eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
        } 
        if(flag==0 && flag1==0 && flag2==0){
            document.getElementById('percentage'+cnt).value = ((document.getElementById('marks'+cnt).value/document.getElementById('maxMarks'+cnt).value)*100).toFixed(2);
        }
    }
    return 1;
}


function calculatePercentageWithoutMsg(cnt){

    flag=0;
    flag1=0;
    flag2=0;   
    document.getElementById('percentage'+cnt).value = '';
    if(document.getElementById('marks'+cnt).value!='' && document.getElementById('maxMarks'+cnt).value!=''){
        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.getElementById('marks'+cnt).value)){
          flag=1;    
          return 0;
        }
        if (!reg.test(document.getElementById('maxMarks'+cnt).value)){
          flag1=1;    
          return 0;
        }

        if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){
            flag2=1;    
            return 0;
        }
        if(flag==0 && flag1==0 && flag2==0){
          document.getElementById('percentage'+cnt).value = ((document.getElementById('marks'+cnt).value/document.getElementById('maxMarks'+cnt).value)*100).toFixed(2);
        }
    }
    return 1;
}

function getRoute() {
  
    dtArray.splice(0,dtArray.length); //empty the array  
    
    document.registrationForm.routeNo.length = null; 
    addOption(document.getElementById('routeNo'), '', 'Select');
    
    var len= document.getElementById('hiddenRouteStop').options.length;
    var t=document.getElementById('hiddenRouteStop');
    
    if(len>0) {
      for(k=1;k<len;k++) { 
         if(t.options[k].value != '') { 
            retId = (t.options[k].value).split('!~~!!~~!'); 
            routeName = retId[1];
            if(checkDuplicate(routeName)!=0) {  
              addOption(document.getElementById('routeNo'), routeName,  routeName);
            }
         }
      }
    }     
}

</script>

</head>


<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/RegistrationForm/scStudentRegistration.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

<SCRIPT LANGUAGE="JavaScript">
  //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT> 
</body>
</html>
