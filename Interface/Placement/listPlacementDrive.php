<?php
//-------------------------------------------------------
// THIS FILE SHOWS A LIST OF INISTITUTES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PlacementDriveMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Placement Drive Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('placementDriveCode','Placement Drive Code','width="20%"','align="left"',true), 
                                new Array('companyCode','Company','width="15%"','align="left"',true), 
                                new Array('startDate','From','width="5%"','align="center"',true) , 
                                new Array('startTime','Time','width="5%"','align="center"',false) ,  
                                new Array('endDate','To','width="5%"','align="center"',true) , 
                                new Array('endTime','Time','width="5%"','align="center"',false) ,  
                                new Array('action','Action','width="3%"','align="center"',false)
                               );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddDriveDiv'; // div container name  
editFormName   = 'AddDriveDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteDrive';
divResultName  = 'results';
page=1; //default page
sortField = 'placementDriveCode';
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
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayFloatingDiv(dv,'', 650,375,200,100);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

var serverDate="<?php echo date('Y-m-d');?>"
function validateAddForm(frm, act) {
    
    var fieldsArray = new Array(
            new Array("driveCode","<?php echo ENTER_PLACEMENT_DRIVE_CODE; ?>"),
            new Array("companyId","<?php echo SELECT_PLACEMENT_COMPANY_NAME; ?>")
            //,new Array("visitingPerson","<?php echo ENTER_PLACEMENT_DRIVE_VISITING_PERSONS; ?>")
	      /*   new Array("cutOff1","<?php echo ENTER_MARKS_IN_10; ?>")
	     	  new Array("cutOff2","<?php echo ENTER_MARKS_IN_+2; ?>")
		      new Array("cutOff3","<?php echo ENTER_MARKS_IN_LAST_SEM; ?>")
              new Array("cutOff4","<?php echo ENTER_MARKS_IN_GRADUATION; ?>")            
*/
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
          if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='driveCode' ){ 
            messageBox("<?php echo ENTER_DRIVE_CODE_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
 
	
			for(var i=0;i<10;i++) {
				if(!isEmpty(document.getElementById('testSubject'+i).value)){
				//var k = i;
				//	if(!isEmpty(document.getElementById('testSubject'+k).value)) {
						for(var j=0;j<10;j++){
							if(!isEmpty(document.getElementById('testSubject'+j).value)) {
								if(j!=i){ 
									if((document.getElementById('testSubject'+i).value).toLowerCase() == (document.getElementById('testSubject'+j).value).toLowerCase()) {
										messageBox("Duplicate entry in test subject");
										document.getElementById('testSubject'+i).focus();
										return false;
									}
								}
							}
						}
				//	}
				}
			}
			
	}

    if(!dateDifference(serverDate,document.getElementById('startDate').value,'-')){
         messageBox("<?php echo DRIVE_START_DATE_RESTRICTION;?>");
         document.getElementById('startDate').focus();
         return false;
    }
    if(!isAlphaNumericdot(document.getElementById('startTime').value)) {
            messageBox("<?php echo ACCEPT_INTEGER ?>");
            document.getElementById('startTime').focus();
            return false;
    }
    if(!isTime2(document.getElementById('startTime').value)) {
        messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
        document.getElementById('startTime').focus();
        return false;
    }
	var startTime = document.getElementById('startTime').value;
	if(!isEmpty(document.getElementById('startTime').value)) {
		var s1= (document.getElementById('startTime').value).split(':');
		if(s1[0].length != 2){
			alert("Enter time in HH:MM format");
			return false;
		}
	}
	if(!isEmpty(document.getElementById('startTime').value)) {
		 if((s1[1] == " " || s1[1] >=60))  {
			 alert("Please enter accurate time in start minutes");
			 document.getElementById('startTime').focus();
			 return false;
		 }
		 if((s1[0] == " " || s1[0] >=12))  {
			 alert("Please enter accurate time in start Hours");
			 document.getElementById('endTime').focus();
			 return false;
		}
	}

    if(trim(document.getElementById('startTime').value)=='00:00') {
        messageBox("Invalid time");
        document.getElementById('startTime').focus();
        return false;
    }

    if(!dateDifference(serverDate,document.getElementById('endDate').value,'-')){
        messageBox("<?php echo DRIVE_END_DATE_RESTRICTION;?>");
        document.getElementById('endDate').focus();
        return false;
    }
    if(!isAlphaNumericdot(document.getElementById('endTime').value)) {
            messageBox("<?php echo ACCEPT_INTEGER ?>");
            document.getElementById('endTime').focus();
            return false;
    }
    if (!isTime2(document.getElementById('endTime').value)) {
        messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
        document.getElementById('endTime').focus();
        return false;
    }
	if(!isEmpty(document.getElementById('endTime').value)) {
		var s3= (document.getElementById('endTime').value).split(':');
		if(s3[0].length != 2){
			alert("Enter time in HH:MM format ");
		
			return false;
		}
	}
	if(!isEmpty(document.getElementById('endTime').value)) {
		var s2=(document.getElementById('endTime').value).split(':');
	
		 if((s2[1] == " " || s2[1] >=60))  {
			 alert("Please enter accurate minutes in end time");
			 document.getElementById('endTime').focus();
			 return false;
		 }
		 if((s2[0] == " " || s2[0] >=12))  {
			 alert("Please enter accurate time in end Hours");
			 document.getElementById('endTime').focus();
			 return false;
		}
	}

    if (trim(document.getElementById('endTime').value)=='00:00') {
        messageBox("Invalid time");
        document.getElementById('endTime').focus();
        return false;
    }
    
    if(!dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,'-')){
        messageBox("<?php echo DRIVE_START_END_DATE_RESTRICTION;?>");
        document.getElementById('endDate').focus();
        return false;
    }
    
    if(trim(document.getElementById('visitingPerson').value)==''){
        messageBox("<?php echo ENTER_PLACEMENT_DRIVE_VISITING_PERSONS;?>");
        document.getElementById('visitingPerson').focus();
        return false;
    }
	if(!isAlphabetCharacters(document.getElementById('visitingPerson').value)) {
		messageBox("Please enter valid Visitor Name");
		document.getElementById('visitingPerson').focus();
		return false;
	}
    if(trim(document.getElementById('venue').value)==''){
        messageBox("<?php echo ENTER_PLACEMENT_DRIVE_VENUE;?>");
        document.getElementById('venue').focus();
        return false;
    }
    
    if(document.AddDrive.eligibilityCriteria[0].checked==true){
       if(trim(document.AddDrive.cutOff1.value)==''){
           messageBox("<?php echo ENTER_CUT_OFF_MARKS;?>");
           document.AddDrive.cutOff1.focus();
           return false;
       }
       
       if(!isDecimal(trim(document.AddDrive.cutOff1.value))){
           messageBox("<?php echo ENTER_DECIMAL_VALUE;?>");
           document.AddDrive.cutOff1.focus();
           return false;
       }
       if((document.AddDrive.cutOff1.value) > 100){
           messageBox("<?php echo VALUE_NOT_MORE_THAN_100;?>");
           document.AddDrive.cutOff1.focus();
           return false;
       }
       
       if(trim(document.AddDrive.cutOff2.value)==''){
           messageBox("<?php echo ENTER_CUT_OFF_MARKS;?>");
           document.AddDrive.cutOff2.focus();
           return false;
       }
       
       if(!isDecimal(trim(document.AddDrive.cutOff2.value))){
           messageBox("<?php echo ENTER_DECIMAL_VALUE;?>");
           document.AddDrive.cutOff2.focus();
           return false;
       }
	    if((document.AddDrive.cutOff2.value) > 100){
           messageBox("<?php echo VALUE_NOT_MORE_THAN_100;?>");
           document.AddDrive.cutOff2.focus();
           return false;
       }
       
       if(trim(document.AddDrive.cutOff3.value)=='' && trim(document.AddDrive.cutOff4.value)==''){
           messageBox("<?php echo ENTER_CUT_OFF_MARKS_IN_EITHER_ONE;?>");
           document.AddDrive.cutOff3.focus();
           return false;
       }
       
       if(trim(document.AddDrive.cutOff3.value)!='' && trim(document.AddDrive.cutOff4.value)!=''){
           messageBox("<?php echo ENTER_CUT_OFF_MARKS_IN_EITHER_ONE;?>");
           document.AddDrive.cutOff3.focus();
           return false;
       }
       
       if(trim(document.AddDrive.cutOff3.value)!=''){ 
        if(!isDecimal(trim(document.AddDrive.cutOff3.value))){
           messageBox("<?php echo ENTER_DECIMAL_VALUE;?>");
           document.AddDrive.cutOff3.focus();
           return false;
        }
      }

	   if((document.AddDrive.cutOff3.value) > 100){
           messageBox("<?php echo VALUE_NOT_MORE_THAN_100;?>");
           document.AddDrive.cutOff3.focus();
           return false;
       }
      
      if(trim(document.AddDrive.cutOff4.value)!=''){ 
        if(!isDecimal(trim(document.AddDrive.cutOff4.value))){
           messageBox("<?php echo ENTER_DECIMAL_VALUE;?>");
           document.AddDrive.cutOff4.focus();
           return false;
        }
      }
	  if((document.AddDrive.cutOff4.value) > 100){
           messageBox("<?php echo VALUE_NOT_MORE_THAN_100;?>");
           document.AddDrive.cutOff4.focus();
           return false;
       }

        
    }
    
    if(document.AddDrive.isTest[0].checked==true){
        var isTestFlag=0;
        for(var x=0;x<10;x++){
			//alert(document.getElementById('testDuration'+x).value)

			/*		
			if(!isEmpty(document.getElementById('testDuration'+x).value)) {
				var st=(document.getElementById('testDuration'+x).value).split(':');
				//alert(st[0]+" " +st[1]);
				 if((st[0] == " " || st[0] >=12))  {
					 alert("Please enter accurate Hours in Test Duration");
					 document.getElementById('testDuration'+x).focus();
					 return false;
				 } 
				 if((st[1] == " " || st[1] >=60))  {
					 alert("Please enter accurate Minutes in Test Duration");
					 document.getElementById('testDuration'+x).focus();
					 return false;
				 } 
			}
			if (!isTime2(document.getElementById('testDuration'+x).value)) {
				messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
				document.getElementById('testDuration'+x).focus();
				return false;
			}
			if (trim(document.getElementById('testDuration'+x).value)=='00:00') {
				messageBox("Invalid time");
				document.getElementById('testDuration'+x).focus();
				return false;
			}*/

            if(trim(document.getElementById('testDuration'+x).value)!=''){
                isTestFlag=1;
                if(trim(document.getElementById('testSubject'+x).value)==''){
                    messageBox("<?php echo ENTER_TEST_SUBJECT;?>");
                    document.getElementById('testSubject'+x).focus();
                    return false;
                }
					if(!isEmpty(document.getElementById('testDuration'+x).value)) {
					var st=(document.getElementById('testDuration'+x).value).split(':');
					//alert(st[0]+" " +st[1]);
					 if((st[0] == " " || st[0] >=12))  {
						 alert("Please enter accurate Hours in Test Duration");
						 document.getElementById('testDuration'+x).focus();
						 return false;
					 } 
					 if((st[1] == " " || st[1] >=60))  {
						 alert("Please enter accurate Minutes in Test Duration");
						 document.getElementById('testDuration'+x).focus();
						 return false;
					 } 
				}
				if (!isTime2(document.getElementById('testDuration'+x).value)) {
					messageBox("<?php echo ENTER_SCHEDULE_TIME_NUM1 ?>");
					document.getElementById('testDuration'+x).focus();
					return false;
				}
				if (trim(document.getElementById('testDuration'+x).value)=='00:00') {
					messageBox("Invalid time");
					document.getElementById('testDuration'+x).focus();
					return false;
				}
            }
            
            if(trim(document.getElementById('testSubject'+x).value)!=''){
                isTestFlag=1;
                if(trim(document.getElementById('testDuration'+x).value)==''){
                    messageBox("<?php echo ENTER_TEST_DURATION;?>");
                    document.getElementById('testDuration'+x).focus();
                    return false;
                }

            }
           
        }
       if(isTestFlag==0){
           messageBox("<?php echo PLEASE_ENTER_ATLEAST_ONE_TEST_SUBJECT_DURATION;?>");
           document.getElementById('testSubject0').focus();
           return false;
       } 
    }
    
    if(document.AddDrive.groupDiscussion[0].checked==true){
        if(trim(document.AddDrive.discussionDuration.value)==''){
            messageBox("<?php echo ENTER_DISCUSSION_DURATION;?>");
            document.AddDrive.discussionDuration.focus();
            return false;
        }
    }
    
    if(document.AddDrive.individualInterview[0].checked==true){
        if(trim(document.AddDrive.interviewDuration.value)==''){
            messageBox("<?php echo ENTER_INTERVIEW_DURATION;?>");
            document.AddDrive.interviewDuration.focus();
            return false;
        }
    }
    
    if(document.AddDrive.hrInterview[0].checked==true){
        if(trim(document.AddDrive.hrInterviewDuration.value)==''){
            messageBox("<?php echo ENTER_INTERVIEW_DURATION;?>");
            document.AddDrive.hrInterviewDuration.focus();
            return false;
        }
    }
    
       
    if(document.AddDrive.placementDriveId.value==''){
        addDrive();
        return false;
    }
    if(document.AddDrive.placementDriveId.value!=''){
        editDrive();
        return false;
    }    

 }

//-------------------------------------------------------
// THIS FUNCTION IS USED TO ADD A NEW INSTITUTE   
// Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function addDrive() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxInitAdd.php';
         var subjectString='';
         if(document.AddDrive.isTest[0].checked==true){
          for(var x=0;x<10;x++){  
           if(trim(document.getElementById('testDuration'+x).value)!='' && trim(document.getElementById('testSubject'+x).value)){   
             if(subjectString!=''){
                 subjectString += '!@!@!';
             }
              subjectString += trim(document.getElementById('testDuration'+x).value) + '_!_@_!_@_!_' + trim(document.getElementById('testSubject'+x).value);
            }
           }
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 driveCode: trim(document.AddDrive.driveCode.value), 
                 companyId: (document.AddDrive.companyId.value), 
                 startDate: document.getElementById('startDate').value,  
                 startTime: document.getElementById('startTime').value,
                 startAmPm: document.getElementById('startAmPm').value,
                 endDate: document.getElementById('endDate').value,  
                 endTime: document.getElementById('endTime').value,
                 endAmPm: document.getElementById('endAmPm').value,
                 visitingPersons: trim(document.AddDrive.visitingPerson.value),
                 venue: trim(document.AddDrive.venue.value),
                 eligibilityCriteria : (document.AddDrive.eligibilityCriteria[0].checked==true?1:0),
                 cutOff1 : trim(document.getElementById('cutOff1').value),
                 cutOff2 : trim(document.getElementById('cutOff2').value),
                 cutOff3 : trim(document.getElementById('cutOff3').value),
                 cutOff4 : trim(document.getElementById('cutOff4').value),
                 isTest : (document.AddDrive.isTest[0].checked==true?1:0),
                 subjectString : subjectString,
                 individualInterview : (document.AddDrive.individualInterview[0].checked==true?1:0),
                 interviewDuration : trim(document.AddDrive.interviewDuration.value),
                 hrInterview : (document.AddDrive.hrInterview[0].checked==true?1:0),
                 hrInterviewDuration : trim(document.AddDrive.hrInterviewDuration.value),
                 groupDiscussion :  (document.AddDrive.groupDiscussion[0].checked==true?1:0),
                 discussionDuration : trim(document.AddDrive.discussionDuration.value)
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
                         else {
                             hiddenFloatingDiv('AddDriveDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
                     }
                    else if("<?php echo DUPLICATE_PLACEMENT_DRIVE_CODE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DUPLICATE_PLACEMENT_DRIVE_CODE;?>");
                        document.AddDrive.driveCode.focus();
                        return false;
                    }
                    else if("<?php echo PLACEMENT_DRIVE_TIME_CLASH;?>" == trim(transport.responseText)){
                        messageBox("<?php echo PLACEMENT_DRIVE_TIME_CLASH;?>");
                        document.getElementById('startDate').focus();
                        return false;
                    } 
                    else {
                        messageBox(trim(transport.responseText)); 
                    }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//--------------------------------------------------------   
//THIS FUNCTION IS USED TO DELETE AN INSTITUTE
//  id=universityId
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteDrive(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: id
             },
             onCreate: function(transport){
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
// THIS FUNCTION IS USED TO CLEAN UP THE "AddInatitute" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function blankValues() {
   document.AddDrive.reset();
   document.AddDrive.driveCode.focus();
   document.AddDrive.placementDriveId.value='';
   document.getElementById('divHeaderId').innerHTML='&nbsp;Add Placement Drive Details';
   cleanDiv();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A INSTITUTE
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editDrive() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxInitEdit.php';
         var subjectString='';
         if(document.AddDrive.isTest[0].checked==true){
          for(var x=0;x<10;x++){  
           if(trim(document.getElementById('testDuration'+x).value)!='' && trim(document.getElementById('testSubject'+x).value)){   
             if(subjectString!=''){
                 subjectString += '!@!@!';
             }
              subjectString += trim(document.getElementById('testDuration'+x).value) + '_!_@_!_@_!_' + trim(document.getElementById('testSubject'+x).value);
            }
           }
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: (document.AddDrive.placementDriveId.value),
                 driveCode: trim(document.AddDrive.driveCode.value),
                 companyId: (document.AddDrive.companyId.value), 
                 startDate: document.getElementById('startDate').value,  
                 startTime: document.getElementById('startTime').value,
                 startAmPm: document.getElementById('startAmPm').value,
                 endDate: document.getElementById('endDate').value,  
                 endTime: document.getElementById('endTime').value,
                 endAmPm: document.getElementById('endAmPm').value,
                 visitingPersons: trim(document.AddDrive.visitingPerson.value),
                 venue: trim(document.AddDrive.venue.value),
                 eligibilityCriteria : (document.AddDrive.eligibilityCriteria[0].checked==true?1:0),
                 cutOff1 : trim(document.getElementById('cutOff1').value),
                 cutOff2 : trim(document.getElementById('cutOff2').value),
                 cutOff3 : trim(document.getElementById('cutOff3').value),
                 cutOff4 : trim(document.getElementById('cutOff4').value),
                 isTest : (document.AddDrive.isTest[0].checked==true?1:0),
                 subjectString : subjectString,
                 individualInterview : (document.AddDrive.individualInterview[0].checked==true?1:0),
                 interviewDuration : trim(document.AddDrive.interviewDuration.value),
                 hrInterview : (document.AddDrive.hrInterview[0].checked==true?1:0),
                 hrInterviewDuration : trim(document.AddDrive.hrInterviewDuration.value),
                 groupDiscussion :  (document.AddDrive.groupDiscussion[0].checked==true?1:0),
                 discussionDuration : trim(document.AddDrive.discussionDuration.value)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddDriveDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else if("<?php echo DUPLICATE_PLACEMENT_DRIVE_CODE;?>" == trim(transport.responseText)){
                        messageBox("<?php echo DUPLICATE_PLACEMENT_DRIVE_CODE;?>");
                        document.AddDrive.driveCode.focus();
                        return false;
                     }
                     else if("<?php echo PLACEMENT_DRIVE_TIME_CLASH;?>" == trim(transport.responseText)){
                        messageBox("<?php echo PLACEMENT_DRIVE_TIME_CLASH;?>");
                        document.getElementById('startDate').focus();
                        return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     } 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EditInatitute" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         
         document.AddDrive.reset();
         cleanDiv();
         
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/Drive/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 placementDriveId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                       hiddenFloatingDiv('AddDriveDiv');
                       messageBox("<?php echo PLACEMENT_DRIVE_NOT_EXIST; ?>");
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                   document.getElementById('divHeaderId').innerHTML='&nbsp;Edit Placement Drive Details';
                   var j = trim(transport.responseText).evalJSON();
                   document.AddDrive.companyId.value=j.edit[0].companyId;
                   document.AddDrive.driveCode.value=j.edit[0].placementDriveCode;
                   
                   document.getElementById('startDate').value=j.edit[0].startDate;
                   document.getElementById('startTime').value=j.edit[0].startTime;
                   document.getElementById('startAmPm').value=j.edit[0].startTimeAmPm;
                   
                   document.getElementById('endDate').value=j.edit[0].endDate;
                   document.getElementById('endTime').value=j.edit[0].endTime;
                   document.getElementById('endAmPm').value=j.edit[0].endTimeAmPm;
                   
                   document.AddDrive.visitingPerson.value=j.edit[0].visitingPersons;
                   document.AddDrive.venue.value=j.edit[0].venue;
                   if(j.edit[0].eligibilityCriteria==1){
                     document.AddDrive.eligibilityCriteria[0].checked=true;
                     document.getElementById('cutOff1').value=j.criteria[0].cutOffMarks10th;
                     document.getElementById('cutOff2').value=j.criteria[0].cutOffMarks12th;
                     document.getElementById('cutOff3').value=j.criteria[0].cutOffMarksLastSem;
                     document.getElementById('cutOff4').value=j.criteria[0].cutOffMarksGraduation;
                     toggleEligibilityCriteria(true);
                   }
                   else{
                     document.AddDrive.eligibilityCriteria[1].checked=true;  
                     document.getElementById('cutOff1').value='';
                     document.getElementById('cutOff2').value='';
                     document.getElementById('cutOff3').value='';
                     document.getElementById('cutOff4').value='';
                   }
                   if(j.edit[0].isTest==1){
                     document.AddDrive.isTest[0].checked=true;
                     var len=j.tests.length;
                     toggleTest(true);
                     for(var y=0;y<len;y++){
                        document.getElementById('testDuration'+y).value=j.tests[y].testDuration;
                        document.getElementById('testSubject'+y).value=j.tests[y].testSubjects; 
                     }
                   }
                   else{
                       document.AddDrive.isTest[1].checked=true;
                       for(var x=0;x<10;x++){
                           document.getElementById('testDuration'+x).value='';
                           document.getElementById('testSubject'+x).value='';
                       }
                   }
                   if(j.edit[0].individualInterview==1){ 
                      toggleInterview(true);
                      document.AddDrive.individualInterview[0].checked=true; 
                      document.AddDrive.interviewDuration.value=j.edit[0].interviewDuration; 
                   }
                   else{
                      document.AddDrive.individualInterview[1].checked=true; 
                      document.AddDrive.interviewDuration.value=''; 
                   }
                   
                   if(j.edit[0].hrInterview==1){ 
                      toggleHRInterview(true);
                      document.AddDrive.hrInterview[0].checked=true; 
                      document.AddDrive.hrInterviewDuration.value=j.edit[0].hrInterviewDuration; 
                   }
                   else{
                      document.AddDrive.hrInterview[1].checked=true; 
                      document.AddDrive.hrInterviewDuration.value=''; 
                   }
                   
                   if(j.edit[0].groupDiscussion==1){
                      toggleGD(true); 
                      document.AddDrive.groupDiscussion[0].checked=true; 
                      document.AddDrive.discussionDuration.value=j.edit[0].discussionDuration; 
                   }
                   else{
                      document.AddDrive.groupDiscussion[1].checked=true; 
                      document.AddDrive.discussionDuration.value=''; 
                   }
                   
                   document.AddDrive.placementDriveId.value=j.edit[0].placementDriveId;
                   document.AddDrive.driveCode.focus();
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function toggleEligibilityCriteria(value){
    if(value==true){
        document.getElementById('cutOff1').disabled=false;
        document.getElementById('cutOff2').disabled=false;
        document.getElementById('cutOff3').disabled=false;
        document.getElementById('cutOff4').disabled=false;
    }
    else{
        document.getElementById('cutOff1').disabled=true;
        document.getElementById('cutOff2').disabled=true;
        document.getElementById('cutOff3').disabled=true;
        document.getElementById('cutOff4').disabled=true;
        
        document.getElementById('cutOff1').value='';
        document.getElementById('cutOff2').value='';
        document.getElementById('cutOff3').value='';
        document.getElementById('cutOff4').value='';
    }
}

function toggleTest(value){
    if(value==true){
      for(var x=0;x<10;x++){  
        document.getElementById('testDuration'+x).disabled=false;
        document.getElementById('testSubject'+x).disabled=false;
      }
    }
    else{
      for(var x=0;x<10;x++){  
        document.getElementById('testDuration'+x).disabled=true;
        document.getElementById('testSubject'+x).disabled=true;
        document.getElementById('testDuration'+x).value='';
        document.getElementById('testSubject'+x).value='';
      }
    }
}

function toggleInterview(value){
    if(value==true){
       document.getElementById('interviewDuration').disabled=false;
    }
    else{
       document.getElementById('interviewDuration').disabled=true;
       document.getElementById('interviewDuration').value='';
    }
}

function toggleHRInterview(value){
    if(value==true){
       document.getElementById('hrInterviewDuration').disabled=false;
    }
    else{
       document.getElementById('hrInterviewDuration').disabled=true;
       document.getElementById('hrInterviewDuration').value='';
    }
}

function toggleGD(value){
    if(value==true){
       document.getElementById('discussionDuration').disabled=false;
    }
    else{
       document.getElementById('discussionDuration').disabled=true;
       document.getElementById('discussionDuration').value='';
    }
}

function cleanDiv(){
    document.getElementById('cutOff1').disabled=true;
    document.getElementById('cutOff2').disabled=true;
    document.getElementById('cutOff3').disabled=true;
    document.getElementById('cutOff4').disabled=true;
    document.getElementById('cutOff1').value='';
    document.getElementById('cutOff2').value='';
    document.getElementById('cutOff3').value='';
    document.getElementById('cutOff4').value='';
    
    for(var x=0;x<10;x++){  
      document.getElementById('testDuration'+x).disabled=true;
      document.getElementById('testSubject'+x).disabled=true;
      document.getElementById('testDuration'+x).value='';
      document.getElementById('testSubject'+x).value='';
    }
    
    document.getElementById('interviewDuration').disabled=true;
    document.getElementById('interviewDuration').value='';
    
    document.getElementById('hrInterviewDuration').disabled=true;
    document.getElementById('hrInterviewDuration').value='';
    
    document.getElementById('discussionDuration').disabled=true;
    document.getElementById('discussionDuration').value='';
}


/* function to print university report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/Placement/placementDriveReportPrint.php?'+qstr;
    window.open(path,"DriveReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='<?php echo UI_HTTP_PATH;?>/Placement/PlacementDriveReportCSV.php?'+qstr;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Placement/Drive/listDriveContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listDrive.php $ 
?>