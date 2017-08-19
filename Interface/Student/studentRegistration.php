<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Student Registration Form
//
//
// Author :Ankur Aggarwal
// Created on : 25-July-2011
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
include_once(BL_PATH ."/Student/initStudentInformation.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Course Registration Form</title>
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

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCourseResourceList.php';
searchFormName = 'registrationForm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
divResultName  = 'subjectsDiv';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';
var flag = false;






		 

//displays the values in the table

function getSection(value) {


		url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentGroupDetail.php';


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
 

        var fieldsArray = new Array(
	new Array("name","<?php echo 'Enter Student Name' ?>"),
        new Array("fatherName","<?php echo 'Enter Father Email' ?>"),
        new Array("rollNo","<?php echo 'Enter Identification No' ?>"),
        new Array("studentEmail","<?php echo 'Enter Student Email' ?>"),
        new Array("parentsNumber","<?php echo 'Enter Parent Contact No'?>"),
	new Array("studentNumber","<?php echo 'Enter Your Mobile Number'?>"),
	new Array("bloodGroup","<?php echo 'Select Your Blood Group'?>"),
	new Array("landlineNo","<?php echo 'Enter Your LandLine Number'?>"),
	new Array("status","<?php echo 'Select Your Scholar Type'?>"),

        new Array("address","<?php echo 'Enter Your Address'?>")
    );
      



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
                 messageBox("<?php echo VALID_PHONE ?>"); 
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
	 else if(fieldsArray[i][0]=="landlineNo"){
            if(!isPhone(eval("frm."+(fieldsArray[i][0])+".value"))) { //if not valid phone format
                 messageBox("<?php echo VALID_PHONE ?>"); 
                 eval("frm."+(fieldsArray[i][0])+".focus();");
                 return false;
                 break;  
             }
        }
		else if(document.getElementById('parentEmail').value){
			if(!isEmail(eval("frm."+('parentEmail')+".value"))){  //if not valid email format
			messageBox("<?php echo 'Enter Valid Parent Email'?>");
			eval("frm."+('parentEmail')+".focus();");
			return false;
		}
	}
             
		
    }

              if(document.getElementById('status').value==1){	

		     	if(isEmpty(document.getElementById('travel').value)) {
                     	   messageBox("<?php echo 'Select Your Travelling Status'?>");
				eval("frm."+('travel')+".focus();");
                     	   return false;
        		     }
	            
                	
		var travelArray = new Array(
				new Array("routeNo","<?php echo 'Enter Route No' ?>"),
        			new Array("pickUp","<?php echo 'Enter Your Pick Up Point' ?>"),
        			new Array("travellingPt","<?php echo 'Enter Travelling Point' ?>"),
        			new Array("vehicleType","<?php echo 'Enter Your Vehicle Type' ?>"),
        			new Array("vehicleRegistration","<?php echo 'Enter Vehicle Registration No' ?>")
		    );

                   if(document.getElementById('travel').value==1)
                  {
              	    for(i=0;i<2;i++) {
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
              	    for(i=2;i<5;i++) {
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
        			new Array("wardenName","<?php echo 'Enter Your Warden Name' ?>"),
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
            	         eval("frm."+(hostelArray[i][0])+".focus(periodName);");
              	        return false;
              	        break;  
                      }

                  }
		 else if(hostelArray[i][0]=="wardenContact"){
          	  if(!isPhone(eval("frm."+(hostelArray[i][0])+".value"))) { //if not valid phone format
            	     messageBox("<?php echo VALID_PHONE ?>"); 
              	   eval("frm."+(hostelArray[i][0])+".focus();");
              	   return false;
              	   break;  
             }
        }  

      	        }
		       
	    }

          
              if(document.registrationForm.undertaking.checked==false){
		       messageBox("<?php echo 'Accept The Agreement First'?>");     
			return false;
		}

              
              

     addStudent();
      return false;
}


// Adding a student in the Database

function addStudent() {
   if(false===confirm("Have You Verified Your Data Because You have Got Only One Chance?")) {
             return false;
         } 
   else{
	 url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxAddStudentRegistration.php';
	 new Ajax.Request(url,
	 {
		 method:'post',
		 parameters: $('registrationForm').serialize(true),
		 onCreate: function() {
			 showWaitDialog(true); 
		 },
		 onSuccess: function(transport){		
		 hideWaitDialog(true);
		 messageBox(trim(transport.responseText));   
		window.location = "<?php echo UI_HTTP_PATH;?>/Student/index.php";
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
	document.registrationForm.pickUp.value='';
	document.registrationForm.travellingPt.value='';
	document.registrationForm.vehicleType.value='';
	document.registrationForm.vehicleRegistration.value='';
}




// Use to show the further options using selection

 
function getStatus() {
     
   document.getElementById('hostel1').style.display='none';
   document.getElementById('hostel2').style.display='none';
   document.getElementById('travel1').style.display='none';
   document.getElementById('travel2').style.display='none';
   document.getElementById('travel3').style.display='none';
   document.getElementById('travel0').style.display='none'; 

   if(document.getElementById('status').value==2) {
     blankValues();
     document.registrationForm.travel.value='';
     document.getElementById('hostel1').style.display=''; 
     document.getElementById('hostel2').style.display=''; 
     
   	}
 
   else if(document.getElementById('status').value==1){
     blankValues();
     document.getElementById('travel').value='';
     document.getElementById('travel0').style.display='';
   }
  

}




function getTravel() {
   

   document.getElementById('travel1').style.display='none';
   document.getElementById('travel2').style.display='none';
   document.getElementById('travel3').style.display='none'; 
   if(document.getElementById('status').value==1 && document.getElementById('travel').value==1) {
     blankValues();
     document.getElementById('travel1').style.display='';
   }
   if(document.getElementById('status').value==1 && document.getElementById('travel').value==2) {
     blankValues();
     document.getElementById('travel2').style.display='';
     document.getElementById('travel3').style.display=''; 
   }
}

window.onload=function() { 
getSection(document.getElementById('currentClassId').value);
 }

</script>
</head>

<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentRegistrationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>
</body>
</html>
