<?php
//used for showing student dashboard
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentDashboard');
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

var tabNumber=0;  //Determines the current tab index
function tabClick() {
    var idArray = this.id.split('_');
    showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
    tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
    //refresshes data for this tab
    totalFunction(tabNumber);
}  //Determines the current tab index


//Global variables for for different tabs
var rsrId=-1;
function totalFunction(tabIndex) {
    if(rsrId==-1) {
      url = '<?php echo HTTP_LIB_PATH;?>/Student/initDashboardStudent.php';
      document.getElementById("resources").innerHTML = '';
      rsrId=1;  
      new Ajax.Request(url,
      {
         method:'post',
         parameters:{resourceCheck: 1 },
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
            hideWaitDialog();
            if(trim(transport.responseText)!='') {
              document.getElementById("resources").innerHTML = trim(transport.responseText);
            }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       rsrId=1;  
    }
}


</script>
</head>
<body>
<?php
function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}


function trim_output2($str,$maxlength) {
	if (strlen($str) > $maxlength) {
		$str = substr($str, 0, $maxlength);
		$str .= '...';
	}
	return $str;
}
 ?>
<script language="javascript">
	function showEventDetails(id,dv,w,h) {

	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateEventValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAttendance" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateEventValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxEventGetValues.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {eventId: id},

              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){
			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerTitle").innerHTML = j.eventTitle;
				  document.getElementById("innerShortDescription").innerHTML = j.shortDescription;
				  document.getElementById("visibleFromDate").innerHTML = customParseDate(j.startDate,"-");
				  document.getElementById("visibleToDate").innerHTML = customParseDate(j.endDate,"-");
				  document.getElementById("longDescription").innerHTML = j.longDescription;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function showAdminDetails(id,dv,w,h) {

	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateAdminValues(id);
}

//This function populates values in View Deatil form through ajax

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divAdmin" DIV
//
//Author : Jaineesh
// Created on : (04.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

	function populateAdminValues(id) {
		 url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxAdminGetValues.php';

		 new Ajax.Request(url,
		   {
			 method:'post',
			 parameters: {messageId: id},

			  onCreate: function() {
				showWaitDialog();
			 },
			 onSuccess: function(transport){
				  hideWaitDialog();
				  //j= trim(transport.responseText).evalJSON();
				  j = eval('('+transport.responseText+')');
				  //alert(j.visibleFromDate);
				  // alert(html_entity_decode(j.message));
				  document.getElementById("innerAdmin").innerHTML = j.message;
				  document.getElementById("innerSubject").innerHTML = j.subject;
				  document.getElementById("visibleMessageFromDate").innerHTML = customParseDate(j.visibleFromDate,"-");
                  document.getElementById("visibleMessageToDate").innerHTML = customParseDate(j.visibleToDate,"-");
			 },
			 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
		   });
	}


function showTeacherDetails(id,dv,w,h) {

	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateTeacherValues(id);
}

	//This function populates values in View Deatil form through ajax

	function populateTeacherValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxTeacherGetValues.php';

		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {commentId: id},

              onCreate: function() {
			 	showWaitDialog();
			 },
			 onSuccess: function(transport){

			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
	              document.getElementById("innerTeacherNotice").innerHTML = j.comments;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function showNoticeDetails(id,dv,w,h) {

	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateNoticeValues(id);
   
}

function nextStudentDetails(){
   hiddenFloatingDiv('OnlineFeeInstruction');
   displayWindow('OnlineFeePayment',850,750);
   populateStudentFeeDetails();
   return false;
}


function populateStudentFeeDetails() {
        
   document.getElementById("divFeeAmountResult").innerHTML=''; 
   document.getElementById("spanPayment1").style.display = 'none'; 
   document.getElementById("spanPayment5").style.display = 'none'; 
   url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetStudentFeeDetails.php';  
   new Ajax.Request(url,
   {
     method:'post',      
     asynchronous:false,       
     onCreate: function() {
	   showWaitDialog();
	 },
	 onSuccess: function(transport){
        hideWaitDialog();
		//j= trim(transport.responseText).evalJSON();
        var value = trim(transport.responseText);
        var rval=value.split('!!~~!!~~!!');
		document.getElementById("divFeeAmountResult").innerHTML = rval[0];
        if(rval[1]=='1') {
          document.getElementById("spanPayment5").style.display = ''; 
          document.getElementById("spanPayment").style.display = '';  
          document.getElementById("spanPayment2").style.display = ''; 
          document.getElementById("spanPayment1").style.display = ''; 
        }
        else {
          document.getElementById("spanPayment5").style.display = ''; 
          document.getElementById("spanPayment1").style.display = ''; 
          document.getElementById("spanPayment").style.display = 'none'; 
          document.getElementById("spanPayment2").style.display = 'none'; 
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function getPaybleFee() {

    document.getElementById("onlineTotalFee").innerHTML = "0";        
    
    var formx = document.frmOnlineFeeForm;  
    var	totalFee = 0;
    for(var i=0;i<formx.length;i++){	
      if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chbCheckFee[]" && formx.elements[i].checked==true){
         var value = formx.elements[i].value; 
         var rval=value.split('~'); 
         totalFee += parseFloat(rval[2],2);  
      }
    }          		 
    document.getElementById("onlineTotalFee").innerHTML = totalFee;  
    return false;  	
}

function addOnlineFeeStudentDetails() {

     var formx = document.frmOnlineFeeForm;  
     id='';
     for(var i=0;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chbCheckFee[]" && formx.elements[i].checked==true) { 
          if(id !='') {
            id +=",";        
          }
          id += formx.elements[i].value;
       }
     }
     if(id=='') {
       messageBox("Please select fee class");
       return false;
     }
     
     if(trim(document.getElementById("onlineHolderName").value)=='') {
       messageBox("Please enter Account Holder Name");
       document.getElementById("onlineHolderName").focus();
       return false;  
     }
     
     if(trim(document.getElementById("onlineContactNo").value)=='') {
       messageBox("Please enter Contact No.");
       document.getElementById("onlineContactNo").focus();
       return false;  
     }
            
     url = '<?php echo HTTP_LIB_PATH;?>/Fee/OnlineFee/ajaxGetPaymentFee.php';   
     new Ajax.Request(url,
     {
          method:'post', 
          parameters:{id:id,
                      onlineHolderName: trim(document.getElementById("onlineHolderName").value) ,
                      onlineContactNo: trim(document.getElementById("onlineContactNo").value), 
                      onlineEmailId: trim(document.getElementById("onlineEmailId").value) 
                     },           
          onCreate: function() {
		    showWaitDialog();
	     },
	     onSuccess: function(transport){
            hideWaitDialog();
		    result = trim(transport.responseText);
            if(result.search("<?php echo TECHNICAL_PROBLEM; ?>")>0) {
              messageBox("<?php echo TECHNICAL_PROBLEM; ?>");
            }
            else {
              window.location = result;   
            }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function showHostelDetails(dv,w,h) {
     height='60%';
    width='80%';
	displayFloatingDiv(dv,'', w, h, width,height);
    
}

function populateStudentHostelDetails() {   	
     
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetStudentDetails.php';          
         
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
                	
                  document.getElementById("hostelDetails").innerHTML = j[0].hostelDetails;
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

function alertFeeInstructions(payFee,feeClassId){
    
    w = '950px' ;
    h = '750px' ;
    document.getElementById('alertPayFee').value=payFee;
    document.getElementById('alertFeeClassId').value=feeClassId;
    /*
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');   
      return false;
    }
    if(document.getElementById('helpChk').checked == false) {
       return false;
    }
    */
    displayFloatingDiv('AlertFeesInstruction', title, 300, 150, leftPos, topPos,1);
    
    leftPos = document.getElementById('AlertFeesInstruction').style.left;
    topPos = document.getElementById('AlertFeesInstruction').style.top;
    return false;
}

function registerHostelDetails(isCancel,classId) { 	
	if(isCancel=='0'){		
		 if(false===confirm("Are you sure you want to apply for Hostel facility!!!")) {
          return false;
        } 
    }else{ 		
		 if(false===confirm("Are you sure you want to Cancel registration for Hostel facility!!!")) {
          return false;
        } 		
	}
      
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxAddHostelRegistrationDetails.php';           
         
		 new Ajax.Request(url,
           {
             method:'post',
              parameters: {classId: classId,
              				isCancel :isCancel},
            
              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();	
			       messageBox(trim(transport.responseText));	        
				 javascript:hiddenFloatingDiv('HostelRegistration');
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

function populateNoticeValues(id) {
         //url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxNoticesGetValues.php';
         url = '<?php echo HTTP_LIB_PATH;?>/Index/ajaxGetNoticeDetails.php';  
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {noticeId: id},

              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();
				  document.getElementById("innerNotice").innerHTML = j.noticeSubject;
				  document.getElementById("innerDepartment").innerHTML = j.departmentName+' ('+j.abbr+')';
				  document.getElementById("visibleFromDate11").innerHTML = customParseDate(j.visibleFromDate,"-");
				  document.getElementById("visibleToDate11").innerHTML = customParseDate(j.visibleToDate,"-");
				  document.getElementById("innerText").innerHTML = j.noticeText;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//function to show Task Details /////

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {


    var fieldsArray = new Array(new Array("title","<?php echo ENTER_TITLE ?>"),
								new Array("shortDesc","<?php echo ENTER_SHORT_DESC?>"),
								new Array("daysPrior","<?php echo ENTER_DAYS_PRIOR?>"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }


	//var d=new Date();


       /* else if((eval("frm."+(fieldsArray[i][0])+".value.length"))<2 && fieldsArray[i][0]=='testTypeName' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo TESTTYPE_NAME_LENGTH ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }         */

        else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='title' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }

		else  if (!isAlphaNumeric(eval("frm."+(fieldsArray[i][0])+".value")) && fieldsArray[i][0]=='shortDesc' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_STRING ?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
                }

		else if (fieldsArray[i][0]=='daysPrior'){
			if (!isInteger(eval("frm."+(fieldsArray[i][0])+".value"))){
			//winAlert("Enter string",fieldsArray[i][0]);
			messageBox("<?php echo ENTER_NUMBER ?>");
			document.TaskDetail.daysPrior.value="";
			eval("frm."+(fieldsArray[i][0])+".focus();");
			return false;
			break;
			}
        }

		else if ((document.getElementById("dashboard").checked == false) && (document.getElementById("sms").checked == false)) {
			alert("One checkbox should be checked");
			return false;
		}

            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }

        editTask();
        return false;
    }
var ftd='';std='';jtd='';ctd='';
var globalTdId = '';
function showTaskDetails(tdid,id,dv,w,h) {
    jtd= document.getElementById('tasksTdId_'+tdid);
	ftd= document.getElementById('tasksTdId__'+tdid);
    std= document.getElementById('tasksTdId___'+tdid);
	ctd= document.getElementById('tasksTdId____'+tdid);
	globalTdId = tdid;
	height=screen.height/5;
	width=screen.width/4.5;
	displayFloatingDiv(dv,'', w, h, width,height);
    populateTaskValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTask() {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskDashboardEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
					taskId:				trim(document.getElementById('taskId').value),
					title:				trim(document.getElementById('title').value),
					shortDesc:			trim(document.getElementById('shortDesc').value),
					dueDate:			(document.getElementById('dueDate').value),

					//reminder:			reminder,
					dashboard:			(document.getElementById('dashboard').checked?1:0),
					sms:				(document.getElementById('sms').checked?2:0),
					daysPrior:			(document.getElementById('daysPrior').value),
					status:				(document.getElementById('status').value)
              },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
					 var ret=trim(transport.responseText).split('~');
                     if("<?php echo SUCCESS;?>" == ret[0]) {
                         hiddenFloatingDiv('ViewTasks');
						 //alert(document.getElementById('status').value);
						 //alert(Nan(document.getElementById('dueDate').value) - Nan(document.getElementById('daysPrior').value));

						 if(document.getElementById('status').value == 0) {

							var textAppend =trim(document.getElementById('title').value);
							if (trim(document.getElementById('title').value).length >= 25) {
								textAppend = trim(document.getElementById('title').value).substring(0,25)+'...';
							}

							jtd.style.color = "red";
							currentId = 'statusLink'+globalTdId;
							ftd.innerHTML="<a href=\"#\" name=\"bubble\" onclick=\"showTaskDetails("+globalTdId+",'"+document.getElementById('taskId').value+"','ViewTasks',350,250);return false;\"  title='"+trim(document.getElementById('shortDesc').value)+"' id='"+currentId+"' style=\"color:red\">"+textAppend+"</a>";
							std.innerHTML=ret[1];
							std.style.color="red";
							//ctd.innerHTML = '<img src="" alt="">';
							//alert(ctd.innerHTML);
							//ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/deactive.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+","+document.getElementById('taskId').value+","+document.getElementById('status').value+")>";
							//alert(ctd.innerHTML);
							ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/deactive.gif" border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+document.getElementById('taskId').value+','+document.getElementById('status').value+')">';
						 }
						 else {

							jtd.style.color = "black";
							var textAppend =trim(document.getElementById('title').value);
							if (trim(document.getElementById('title').value).length >= 25) {
								textAppend = trim(document.getElementById('title').value).substring(0,25)+'...';
							}
							currentId = 'statusLink'+globalTdId;
							ftd.innerHTML="<a href=\"#\" name=\"bubble\" onclick=\"showTaskDetails("+globalTdId+",'"+document.getElementById('taskId').value+"','ViewTasks',350,250);return false;\"  id='"+currentId+"' title='"+trim(document.getElementById('shortDesc').value)+"' style=\"color:black\">"+textAppend+"</a>";
							std.innerHTML=ret[1];
							std.style.color="black";
							ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/active.gif" border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+document.getElementById('taskId').value+','+document.getElementById('status').value+')">';

							//ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/active.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+","+document.getElementById('taskId').value+","+document.getElementById('status').value+")>";


						 }
						 jtd='';std='';ftd='';ctd='';

                     }
                   else {
                        messageBox(trim(transport.responseText));
                        if (trim(transport.responseText)=='<?php echo TITLE_ALREADY_EXIST; ?>'){
							document.getElementById('title').value="";
							document.getElementById('title').focus();
						}
                     }
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

function populateTaskValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxTaskGetValues.php';

		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {taskId: id},

              onCreate: function() {
			 	showWaitDialog();
			 },

			 onSuccess: function(transport){

			      hideWaitDialog();
		          j= trim(transport.responseText).evalJSON();

					document.getElementById('taskId').value			= j.taskId;

					document.getElementById('title').value			= j.title;

					document.getElementById('shortDesc').value		= j.shortDesc;
					document.getElementById('dueDate').value		= j.dueDate;
					if (j.reminderOptions == "1,0") {

					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=false;
				   }
				   if (j.reminderOptions == "0,2") {
					document.getElementById('dashboard').checked=false;
					document.getElementById('sms').checked=true;
				   }

				   if (j.reminderOptions == "1,2") {
					//document.getElementById('showDashboard').style.display = '';
					document.getElementById('dashboard').checked=true;
					document.getElementById('sms').checked=true;
				   }

				   document.getElementById('daysPrior').value			= j.daysPrior;
				   document.getElementById('status').value			= j.status;

                   document.getElementById('title').focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function changeStatus(tdid,id,status) {
	var alertStr='';
	if(status==1){
		alertStr='Do you want to pending the task?';
	}
	else{
		alertStr='Do you want to complete the task?';
	}
	if(false==confirm(alertStr)){
		return false;
	}
	var ftd='';std='';jtd='';ctd='';
	var globalTdId = '';

	jtd= document.getElementById('tasksTdId_'+tdid);
	ftd= document.getElementById('tasksTdId__'+tdid);
    std= document.getElementById('tasksTdId___'+tdid);
	ctd= document.getElementById('tasksTdId____'+tdid);
	globalTdId = tdid;

         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitTaskStatusChange.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
							taskId:	id,
							status: status
              },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();

                     if("<?php echo SUCCESS;?>" ==  trim(transport.responseText)) {
                         hiddenFloatingDiv('ViewTasks');


						 if(status == 0) {
							jtd.style.color = "black";
							ftd.style.color = "black";
							currentId = 'statusLink'+globalTdId;
							eval("document.getElementById('"+currentId+"').style.color = 'black'");
							std.style.color = "black";
							//ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/active.gif" border="0" alt="Completed" title="Completed" width="10" height="10" style="cursor:pointer" onclick="changeStatus('+globalTdId+','+id+','+1+')"/>';

							ctd.innerHTML = "<img src='<?php echo IMG_HTTP_PATH; ?>/active.gif' border=\"0\" alt=\"Completed\" title=\"Completed\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+",'"+id+"','"+1+"')\">";
						 }
						 else {
							jtd.style.color = "red";
							ftd.style.color = "red";
							std.style.color="red";
							currentId = 'statusLink'+globalTdId;
							eval("document.getElementById('"+currentId+"').style.color = 'red'");
							//ctd.innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/deactive.gif" border="0" alt="Pending" title="Pending" width="10" height="10" style="cursor:pointer" onclick="changeStatus("'+globalTdId+'","'+id+'","'+0+'")"/>';

							ctd.innerHTML="<img src='<?php echo IMG_HTTP_PATH; ?>/deactive.gif' border=\"0\" alt=\"Pending\" title=\"Pending\" width=\"10\" height=\"10\" style=\"cursor:pointer\" onclick=\"changeStatus("+globalTdId+",'"+id+"','"+0+"')\">";
						 }
						 jtd='';std='';ftd='';ctd='';

                         //getTask();
						 //emptySlotId();
						 //document.getElementById('taskId').value = '';
                         //return false;
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

/*
function  download(str){
 var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
 window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
*/

function  download(id){
 var address="<?php echo HTTP_LIB_PATH;?>/forceDownload.php?fileId="+id+"&callingModule=ResourceDownload";
 //window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
 window.location=address;
}
//For autosuggest
function changeDefaultTextOnClick()
{
    if(document.getElementById('menuLookup').value=="Menu Lookup..")
    {
        document.getElementById('menuLookup').value="";
        document.getElementById('menuLookup').className="text_class";
    }
}
function changeDefaultTextOnBlur()
{
    if(document.getElementById('menuLookup').value=="")
    {
        document.getElementById('menuLookup').className="fadeMenuText";
        document.getElementById('menuLookup').value="Menu Lookup..";
    }
}
//This script throws a ajax request to populate autosuggest menu
function getMenuLookup()
{
    document.getElementById('menuLookupContainer').style.display="none";
    if(document.getElementById('menuLookup').value.length>1)
    {
        url = '<?php echo HTTP_LIB_PATH;?>/menuLookup.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {txt: document.getElementById('menuLookup').value},
             onCreate: function() {

                // showWaitDialog(true);
             },
             onSuccess: function(transport){
                    // hideWaitDialog(true);
                    if((transport.responseText)!="") {
                        var display="<ul style='list-style:none'>";
                        var obj=transport.responseText.evalJSON()
                        if(obj)
                        {
                            var objSize=10;
                            if(obj.length<10)
                            {
                                objSize=obj.length;
                            }

                            for(var arrayIndex=0;arrayIndex<objSize;arrayIndex++)
                            {
                                display+="<li style='padding:3px'><a href='"+obj[arrayIndex]['link']+"'>"+obj[arrayIndex]['data']+"</a></li>";
                            }
                        }
                        display+="</ul>";
                        document.getElementById('menuLookupContainer').style.display="";
                        document.getElementById('menuLookupContainer').style.display="block";
                        document.getElementById('menuLookupContainer').innerHTML=display;
                        return false;
                    }
             },
             onFailure: function(){
                 //messageBox("<?php echo TECHNICAL_PROBLEM;?>")
             }
           });
     }
}

function printFeeReceipt(payFee,feeClassId){
	//hiddenFloatingDiv('DivInstructions');
    hiddenFloatingDiv('AlertFeesInstruction');
    <?php
      global $sessionHandler;
      $isPDF = $sessionHandler->getSessionVariable('FEE_PRINT_DETAIL_PDF');
    ?>
	if(isEmpty(payFee)){
	  payFee = '';
	}
    if("<?php echo $isPDF; ?>"=='1') {
      path="<?php echo UI_HTTP_PATH;?>/Fee/studentFeesPrint.php?fee="+payFee+"&feeClassId="+feeClassId;
      window.location = path;  
    }
    else {
	  window.open("<?php echo UI_HTTP_PATH;?>/Fee/studentFeesPrint.php?fee="+payFee+"&feeClassId="+feeClassId,"StuidentFeeReceiptPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    return false;
}

</script>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	//require_once(BL_PATH . "/Student/initDashboard.php");
    //require_once(TEMPLATES_PATH . "/Student/indexDashboardContents.php");
  
	require_once(BL_PATH . "/Student/initDashboardStudent.php");
    require_once(TEMPLATES_PATH . "/Student/indexDashboardStudentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<script language="javascript">
window.onload=function(){
  showTab('dhtmlgoodies_tabView1',0);      
  enableTooltips()
};
    //document.getElementById('div_Alerts').style.width="280px";
</script>

</body>
</html>
