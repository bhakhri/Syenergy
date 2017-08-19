<?php
//used for showing student dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','HostelRegistration');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
//THIS FILE IS INCLUDED AFTER HEADER FILE AS SOME CALCULATIONS ARE DONE  AND SESSION VARIABLES ARE SET IN MENU FILE WHICH
//IS REQUIRED FOR FEEDBACK MODULES
//require_once(BL_PATH . "/Student/initDashboard.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Home </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js"); 
?>
<script src="<?php echo JS_PATH; ?>/gen_validatorv31.js" type="text/javascript"></script>
<script language="javascript">
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
//linksPerPage = <?php echo LINKS_PER_PAGE;?>;

var filePath = "<?php echo IMG_HTTP_PATH;?>";



function showHostelDetails(dv,w,h) {
     height='60%';
    width='80%';
	displayFloatingDiv(dv,'', w, h, width,height);
    
}

function showInstructionsDetails(){
	
document.getElementById("trhideApply").style.display =''; 	
     if(document.getElementById('registRoomTypeId').value=='') {
	 	messageBox("Select your first preference for Room Type");
	 	document.getElementById('registRoomTypeId').focus();
        return false; 
     }
     if(document.getElementById('registRoomTypeId1').value=='') {
	 	messageBox("Select your second preference for Room Type");
	 	document.getElementById('registRoomTypeId1').focus();
        return false; 
     }
     if(document.getElementById('registRoomTypeId2').value=='') {
	 	messageBox("Select your third preference for Room Type");
	 	document.getElementById('registRoomTypeId2').focus();
        return false; 
     }
     
	 document.getElementById("hostelInstructions").style.display ='none'; 
	 if(false===confirm("Are you sure you want to Apply for Hostel!!!")) {
          return false;
        } 	
	document.getElementById("trhideApply").style.display ='none'; 	
	 document.getElementById("hostelInstructions").style.display =''; 
	
}
function populateStudentHostelDetails() {   	
     
         url = '<?php echo HTTP_LIB_PATH;?>/Student/HostelRegistration/ajaxGetStudentDetails.php';          
         
		 new Ajax.Request(url,
           {
             method:'post',            
              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();  
                   j= trim(transport.responseText).evalJSON();
                   
                for($i=0;$i<j.length;$i++){
                	                	
                  document.getElementById("hostelName").innerHTML = j[0].hostelName;
                    document.getElementById("roomType").innerHTML = j[0].roomType;
                      document.getElementById("roomNo").innerHTML = j[0].roomName;
                  document.getElementById("checkOutDate").innerHTML= j[0].checkOutDate;
                  if(j[0].hostelDetails !='')
                   document.getElementById("hostelD1").style.display =''; 
                   document.getElementById("hostelD2").style.display =''; 
                   document.getElementById("hostelD3").style.display =''; 
                   document.getElementById("hostelD4").style.display ='';                   
                 }
                 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function registerHostelDetails(isCancel) { 	
		
    
     	if(document.getElementById("acceptHostel").checked == false) {  
                messageBox("Please accept all the terms to proceed further");
                document.getElementById("acceptHostel").focus();
                return false;  
           	} 
           	
	if(isCancel=='0'){		
		 if(false===confirm("Are you sure you want to apply for Hostel facility!!!")) {
          return false;
        } 
    }else{ 		
		 if(false===confirm("Are you sure you want to Cancel Registration!!!")) {
          return false;
        } 		
	}

	roomTypeId ="1~"+document.getElementById('registRoomTypeId').value+",2~"+document.getElementById('registRoomTypeId1').value+",3~"+document.getElementById('registRoomTypeId2').value;
      
         url = '<?php echo HTTP_LIB_PATH;?>/Student/HostelRegistration/ajaxAddHostelRegistrationDetails.php';           
         
		 new Ajax.Request(url,
           {
             method:'post',
              parameters: {isCancel :isCancel,              				
              				roomTypeId:roomTypeId
              				},
            
              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();	
			       messageBox(trim(transport.responseText));	        
				checkPreviousRegistration();
				document.getElementById("trhideApply").style.display ='none'; 	
	 			document.getElementById("hostelInstructions").style.display ='none'; 
	
				 return false;			 
				  
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function getNextHostel() {
   hiddenFloatingDiv('DivInstructions');
   showHostelDetails('HostelRegistration',400,550);
    populateStudentHostelDetails();
   return false;
}



function getRoomTypes(val,frm)
{
	
   var url = '<?php echo HTTP_LIB_PATH;?>/Student/HostelRegistration/ajaxGetRoomTypes.php';
   if(frm=="Add"){
            document.hostelRegForm.registRoomTypeId.options.length=0;
            var objOption = new Option("Select","");
            document.hostelRegForm.registRoomTypeId.options.add(objOption);
            document.hostelRegForm.registRoomTypeId1.options.add(objOption);
            document.hostelRegForm.registRoomTypeId2.options.add(objOption);        

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
                     document.getElementById('tblDetails').style.display ='';  
                     for(var c=0;c<j.length;c++){
                        var objOption = new Option(j[c].roomType,j[c].hostelRoomTypeId); 
                        var objOption1 = new Option(j[c].roomType,j[c].hostelRoomTypeId); 
                        var objOption2 = new Option(j[c].roomType,j[c].hostelRoomTypeId); 
                       
                             document.hostelRegForm.registRoomTypeId.options.add(objOption);  
                               document.hostelRegForm.registRoomTypeId1.options.add(objOption1);  
                                 document.hostelRegForm.registRoomTypeId2.options.add(objOption2);  
                     }
                      document.getElementById('tblDetails').style.display ='';  
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}


function checkPreviousRegistration(){
	
 url = '<?php echo HTTP_LIB_PATH;?>/Student/HostelRegistration/ajaxGetPreviousRegistration.php';          
         
		 new Ajax.Request(url,
           {
             method:'post',            
              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();  
                   j= trim(transport.responseText).evalJSON();
          if(j[0].roomTypeId !='' && j[0].registrationStatus=='0') {  
           roomTypeId = j[0].roomTypeId;
          
           ret = roomTypeId.split(",");
         
          		value=ret[0].split("~");
          		value1=ret[1].split("~");
          		value2=ret[2].split("~");	
          		roomType = value[1];
          		roomType1 = value1[1];
          		roomType2 = value2[1];
          		
          		document.getElementById('registRoomTypeId').value = roomType; 
          		document.getElementById('registRoomTypeId1').value = roomType1;
          		document.getElementById('registRoomTypeId2').value = roomType2;  
          		         			
          		document.getElementById('trhideApply').style.display ='none';  
          		document.getElementById('trDate1').style.display ='none';
          		document.getElementById('trDate2').style.display ='none';
          		document.getElementById('trDate3').style.display ='';
           		 document.getElementById('tdCancel').style.display ='';
           		 document.getElementById('tblDetails').style.display =''; 
           		
           } 
           if(j[0].registrationStatus!='0'){
           		document.getElementById('trhideApply').style.display ='none';  
          		document.getElementById('trDate1').style.display ='none';
          		document.getElementById('trDate2').style.display ='none';
          		document.getElementById('trDate3').style.display ='none';
           		 document.getElementById('tdCancel').style.display ='none';
           		 document.getElementById('tblDetails').style.display =''; 
           		 
           		 document.getElementById('trCancelRegister').style.display =''; 
           		 document.getElementById('trCancelRegisterComments').style.display =''; 
           		
           		 document.getElementById('applyPref1').style.display ='none';
           		 document.getElementById('applyPref2').style.display ='none';
           		 document.getElementById('applyPref3').style.display ='none';
           		  
           		if(j[0].registrationStatus=='2'){           	
           		document.getElementById('wardenStatus').innerHTML  = "Approved";
           		} 
           		if(j[0].registrationStatus=='3'){           	
           		document.getElementById('wardenStatus').innerHTML  = "Rejected";
           		} 
           		if(j[0].registrationStatus=='4'){           	
           		document.getElementById('wardenStatus').innerHTML  = "Pending";
           		} 
           	 	document.getElementById('wardenComments').innerHTML  = j[0].wardenComments; 
           	
           	}else{
           	
           	document.getElementById('trDate3').style.display ='none';
           	document.getElementById('trDate1').style.display ='';
          	document.getElementById('trDate2').style.display ='';
           	document.getElementById('trhideApply').style.display ='none';  
            document.getElementById('tdCancel').style.display ='';
            document.getElementById('tblDetails').style.display =''; 
           }  
            document.getElementById('tblDetails').style.display ='';     
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });	
	
	
	
	
}
</script>
<?php
    require_once(TEMPLATES_PATH . "/header.php");	
    require_once(TEMPLATES_PATH . "/Student/HostelRegistration/listHostelRegistrationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<script language="javascript">
window.onload=function(){
 populateStudentHostelDetails();
  getRoomTypes();
 checkPreviousRegistration();
 
 
};
</script>

</body>
</html>
