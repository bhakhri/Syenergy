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
define('MODULE','PlacementFollowUpsMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Follow Ups Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
                                new Array('srNo','#','width="2%"','',false), 
                                new Array('companyCode','Company','width="15%"','',true) , 
                                new Array('contactedOn','Contacted On','width="10%"','align="center"',true), 
                                new Array('contactedVia','Contacted Via','width="10%"','',true) , 
                                new Array('contactedPerson','Contacted Person','width="15%"','',true) ,  
                                new Array('designation','Designation','width="10%"','',true), 
                                new Array('action','Action','width="5%"','align="center"',false)
                              );

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Placement/FollowUp/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddFollowUpDiv'; // div container name  
editFormName   = 'EditFollowUpDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteFollowUp';
divResultName  = 'results';
page=1; //default page
sortField = 'companyName';
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
            new Array("companyId","<?php echo SELECT_PLACEMENT_COMPANY_NAME; ?>"),
            new Array("contactedPerson","<?php echo ENTER_FOLLOWUP_CONTACT_PERSON; ?>"),
            new Array("designation","<?php echo ENTER_FOLLOWUP_PERSON_DESIGNATION; ?>")
           // new Array("comments","<?php echo ENTER_FOLLOWUP_COMMENTS; ?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
          if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='contactedPerson' ){ 
            messageBox("<?php echo ENTER_PFOLLOWUP_CONTACT_PERSON_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<3 && fieldsArray[i][0]=='designation' ){ 
            messageBox("<?php echo ENTER_FOLLOWUP_PERSON_DESIGNATION_LENGTH; ?>");
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
       
    
    if(act=='Add') {
        if(!dateDifference(document.getElementById('contactedOn1').value,serverDate,'-')){
                messageBox("Date of contact can not be greater than current date");
                document.getElementById('contactedOn1').focus();
                return false;
        }
        if(document.AddFollowUp.followUp[0].checked==true){
            if(trim(document.getElementById('followUpDate1').value)==''){
                messageBox("<?php echo ENTER_FOLLOWUP_DATE?>");
                document.getElementById('followUpDate1').focus();
                return false;
            }
            
            if(!dateDifference(serverDate,document.getElementById('followUpDate1').value,'-')){
                messageBox("Follow up date can not be smaller than current date");
                document.getElementById('followUpDate1').focus();
                return false;
            }
            if(document.AddFollowUp.followUpBy[0].checked==true){
               if(trim(document.AddFollowUp.followUpMethod.value)==''){
                   messageBox("Enter email id");
                   document.AddFollowUp.followUpMethod.focus();
                   return false;
               }
               if(!isEmail(trim(document.AddFollowUp.followUpMethod.value))){
                   messageBox("<?php echo ENTER_VALID_EMAIL;?>");
                   document.AddFollowUp.followUpMethod.focus();
                   return false;
               } 
            }
            else{
               if(trim(document.AddFollowUp.followUpMethod.value)==''){
                   messageBox("Enter mobile no.");
                   document.AddFollowUp.followUpMethod.focus();
                   return false;
               }
               if(!isNumeric(trim(document.AddFollowUp.followUpMethod.value))){
                   messageBox("<?php echo ENTER_NUMBER;?>");
                   document.AddFollowUp.followUpMethod.focus();
                   return false;
               }
               if(trim(document.AddFollowUp.followUpMethod.value).length<10){
                   messageBox("Mobile no. can not be less than 10 digits");
                   document.AddFollowUp.followUpMethod.focus();
                   return false;
               }
            }
        }
        addFollowUp();
        //return false;
    }
    else if(act=='Edit') {
        if(!dateDifference(document.getElementById('contactedOn2').value,serverDate,'-')){
                messageBox("Date of contact can not be greater than current date");
                document.getElementById('contactedOn2').focus();
                return false;
        }
        if(document.EditFollowUp.followUp[0].checked==true){
            if(trim(document.getElementById('followUpDate2').value)==''){
                messageBox("<?php echo ENTER_FOLLOWUP_DATE?>");
                document.getElementById('followUpDate2').focus();
                return false;
            }
            
            if(!dateDifference(serverDate,document.getElementById('followUpDate2').value,'-')){
                messageBox("Follow up date can not be smaller than current date");
                document.getElementById('followUpDate2').focus();
                return false;
            }
            
            if(document.EditFollowUp.followUpBy[0].checked==true){
               if(trim(document.EditFollowUp.followUpMethod.value)==''){
                   messageBox("Enter email id");
                   document.EditFollowUp.followUpMethod.focus();
                   return false;
               }
               if(!isEmail(trim(document.EditFollowUp.followUpMethod.value))){
                   messageBox("<?php echo ENTER_VALID_EMAIL;?>");
                   document.EditFollowUp.followUpMethod.focus();
                   return false;
               } 
            }
            else{
               if(trim(document.EditFollowUp.followUpMethod.value)==''){
                   messageBox("Enter mobile no.");
                   document.EditFollowUp.followUpMethod.focus();
                   return false;
               }
               if(!isNumeric(trim(document.EditFollowUp.followUpMethod.value))){
                   messageBox("<?php echo ENTER_NUMBER;?>");
                   document.EditFollowUp.followUpMethod.focus();
                   return false;
               }
               if(trim(document.EditFollowUp.followUpMethod.value).length<10){
                   messageBox("Mobile no. can not be less than 10 digits");
                   document.EditFollowUp.followUpMethod.focus();
                   return false;
               }
            }
        }
        editFollowUp();
        //return false;
    }
 }

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW INSTITUTE   
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addFollowUp() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/FollowUp/ajaxInitAdd.php';
         var contactedVia=0;
         if(document.AddFollowUp.contactedVia[0].checked==true){
             contactedVia=1;
         }
         else if(document.AddFollowUp.contactedVia[1].checked==true){
             contactedVia=2;
         }
         else if(document.AddFollowUp.contactedVia[2].checked==true){
             contactedVia=3;
         }
         else{
             contactedVia=4;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 companyId: (document.AddFollowUp.companyId.value), 
                 contactedOn: document.getElementById('contactedOn1').value,  
                 newCall: (document.AddFollowUp.newCall[0].checked==true?1:0), 
                 contactedVia: contactedVia,
                 contactedPerson: trim(document.AddFollowUp.contactedPerson.value), 
                 designation: trim(document.AddFollowUp.designation.value), 
                 comments: trim(document.AddFollowUp.comments.value),
                 followUp: (document.AddFollowUp.followUp[0].checked==true?1:0),
                 followUpDate : document.getElementById('followUpDate1').value,
                 followUpBy : document.AddFollowUp.followUpBy[0].checked==true?1:0,
                 followUpMethod : trim(document.AddFollowUp.followUpMethod.value)
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
                             hiddenFloatingDiv('AddFollowUpDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         }
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
function deleteFollowUp(id) {
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Placement/FollowUp/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 followUpId: id
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
   document.AddFollowUp.reset();
   document.getElementById('fTRId1').style.display='';
   document.AddFollowUp.followUpBy[0].checked=true;
   document.getElementById('fupD1').innerHTML='Email Id';
   document.AddFollowUp.followUpMethod.value='';
   document.AddFollowUp.companyId.focus();
}


//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A INSTITUTE
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function editFollowUp() {
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/FollowUp/ajaxInitEdit.php';
         var contactedVia=0;
         if(document.EditFollowUp.contactedVia[0].checked==true){
             contactedVia=1;
         }
         else if(document.EditFollowUp.contactedVia[1].checked==true){
             contactedVia=2;
         }
         else if(document.EditFollowUp.contactedVia[2].checked==true){
             contactedVia=3;
         }
         else{
             contactedVia=4;
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 followUpId: (document.EditFollowUp.followUpId.value), 
                 companyId: (document.EditFollowUp.companyId.value), 
                 contactedOn: document.getElementById('contactedOn2').value,  
                 newCall: (document.EditFollowUp.newCall[0].checked==true?1:0), 
                 contactedVia: contactedVia,
                 contactedPerson: trim(document.EditFollowUp.contactedPerson.value), 
                 designation: trim(document.EditFollowUp.designation.value), 
                 comments: trim(document.EditFollowUp.comments.value),
                 followUp: (document.EditFollowUp.followUp[0].checked==true?1:0),
                 followUpDate : document.getElementById('followUpDate2').value,
                 followUpBy : document.EditFollowUp.followUpBy[0].checked==true?1:0,
                 followUpMethod : trim(document.EditFollowUp.followUpMethod.value)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditFollowUpDiv');
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

//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EditInatitute" DIV
// Author : Dipanjan Bhattacharjee
// Created on : (14.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
function populateValues(id) {
         
         document.EditFollowUp.reset();
         
         var url = '<?php echo HTTP_LIB_PATH;?>/Placement/FollowUp/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 followUpId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                       hiddenFloatingDiv('EditFollowUpDiv');
                       messageBox("<?php echo FOLLOWUP_NOT_EXIST; ?>");
                       sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                   }
                   var j = trim(transport.responseText).evalJSON();
                                       
                   document.EditFollowUp.companyId.value=j.companyId;
                   document.EditFollowUp.contactedOn2.value=j.contactedOn;
                   
                   if(j.newCall==1){
                     document.EditFollowUp.newCall[0].checked=true;
                   }
                   else{
                     document.EditFollowUp.newCall[1].checked=true;  
                   }
                   
                   if(j.contactedVia==1){
                      document.EditFollowUp.contactedVia[0].checked=true; 
                   }
                   else if(j.contactedVia==2){
                      document.EditFollowUp.contactedVia[1].checked=true; 
                   }
                   else if(j.contactedVia==3){
                      document.EditFollowUp.contactedVia[2].checked=true; 
                   }
                   else{
                      document.EditFollowUp.contactedVia[3].checked=true;  
                   }
                   document.EditFollowUp.contactedPerson.value=j.contactedPerson;
                   document.EditFollowUp.designation.value=j.designation;
                   document.EditFollowUp.comments.value=j.comments;
                   if(j.followUp==1){
                       document.EditFollowUp.followUp[0].checked=true;
                       document.getElementById('followUpDate2').value=j.followUpDate;
                       document.getElementById('fTRId2').style.display='';
                       if(j.followUpBy==1){
                         document.EditFollowUp.followUpBy[0].checked=true;
                         document.getElementById('fupD2').innerHTML='Email Id';
                       }
                       else{
                         document.EditFollowUp.followUpBy[1].checked=true;
                         document.getElementById('fupD2').innerHTML='Mobile No.';
                       }
                       document.EditFollowUp.followUpMethod.value=j.followUpMethod;
                   }
                   else{
                      document.EditFollowUp.followUp[1].checked=true;
                      document.getElementById('followUpDate2').value='';
                      document.getElementById('fTRId2').style.display='none';
                   }
                   
                   document.EditFollowUp.followUpId.value=j.followUpId;
                   document.EditFollowUp.companyId.focus();
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


/* function to print university report*/
function printReport() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/Placement/followUpReportPrint.php?'+qstr;
    window.open(path,"FollowUpReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    window.location='<?php echo UI_HTTP_PATH;?>/Placement/followUpReportCSV.php?'+qstr;
}

function toggleFollowUp(mode,state){
    if(mode==1){
       if(state==1){
           document.getElementById('fTRId1').style.display='';
           document.AddFollowUp.followUpBy[0].checked=true;
           document.getElementById('fupD1').innerHTML='Email Id';
           document.AddFollowUp.followUpMethod.value='';
		   document.getElementById('f1').style.display='';
		   document.getElementById('f2').style.display='';
			document.getElementById('f3').style.display='';
	   }
       else{
           document.getElementById('fTRId1').style.display='none';
		   document.getElementById('f1').style.display='none';
			document.getElementById('f2').style.display='none';
			 document.getElementById('f3').style.display='none';   
	   }
    }
}
function toggleFollowUpEdit(mode,state){
    if(mode==1){
      if(state==1){
          document.getElementById('fTRId2').style.display='';
          document.EditFollowUp.followUpBy[0].checked=true;
          document.getElementById('fupD2').innerHTML='Email Id';
          document.EditFollowUp.followUpMethod.value=''; 
		   document.getElementById('f11').style.display='';
		    document.getElementById('f22').style.display='';
			document.getElementById('f33').style.display='';
       }
      else{
          document.getElementById('fTRId2').style.display='none';
		   document.getElementById('f11').style.display='none';
		    document.getElementById('f22').style.display='none';
			document.getElementById('f33').style.display='none';
      }
    }
}

function toggleEmailSms(mode,state){
    if(mode==1){
       if(state==1){
         document.getElementById('fupD1').innerHTML='Email Id';
       }
       else{
         document.getElementById('fupD1').innerHTML='Mobile No.';
         document.AddFollowUp.followUpMethod.value='';
       }
    }
   
}
function toggleEmailSmsEdit(mode,state){
	if(mode==1){
       if(state==1){
		 document.getElementById('fupD2').innerHTML='Email Id';  
       }
       else{
         document.getElementById('fupD2').innerHTML='Mobile No.';
         document.EditFollowUp.followUpMethod.value='';  
       } 
	}
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Placement/FollowUp/listFollowUpContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: listFollowUp.php $ 
?>