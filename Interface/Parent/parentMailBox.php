<?php
//-------------------------------------------------------
// Purpose: To generate Parent list for subject centric
// functionality 
//
// Author : Parveen Sharma
// Created on : (04.02.2008 )
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ParentMailBox');
define('ACCESS','view');
UtilityManager::ifParentNotLoggedIn();     
//require_once(BL_PATH . "/Parent/scInitData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Mail Box</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?> 
<?php
function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

// ajax search results ---start///

var globalFL=1;

winLayerWidth  = 680; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//this function is uded to refresh tab data based uplon selection of study periods
function refreshMessageData(){
    
    //get the data of Parent grade card based upon selected study period
    var inboxData=refreshInboxData();

    //get the data of offence based upon selected study period
    var sentItemData=refreshSentItemData();
    
}
//this function fetches records corresponding to Parent grades detail
function refreshInboxData(){
    
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitParentMessageList.php';
  //var value=document.getElementById('searchbox').value;
            
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('messageSubject','Subject','width="12%" align="left"',true),                
                        new Array('employeeName','From','width="10%" align="left"',true),
                        new Array('messageDate','Date','width="10%" align="center"',true),
                        new Array('message','Message','width="37%" align="left"',true),
                        new Array('attachmentFile','Attachment','width="10%" align="center"',false),
                        new Array('action1','Action','width="7%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage,linksPerPage,1,'','messageDate','DESC','LeaveTypeResultDiv','ParentTeacherActionDiv','',true,'listObj',tableColumns,'editWindow','deleteParentTeacher','');
 sendRequest(url, listObj, '')
}

//this function fetches records corresponding to Parent offence
function refreshSentItemData(){
        
  url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitSentItemList.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('messageSubject','Subject','width="17%" align="left"',true),                
                        new Array('employeeName','To','width="10%" align="left"',true),
                        new Array('messageDate','Date','width="10%" align="center"',true),
                        new Array('message','Message','width="32%" align="left"',true),
                        new Array('attachmentFile','Attachment','width="8%" align="center"',false),
                        new Array('action1','Action','width="7%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj9 = new initPage(url,recordsPerPage,linksPerPage,1,'','messageDate','DESC','sentItemResultsDiv','','',true,'listObj9',tableColumns,'','','');
 sendRequest(url, listObj9, '')
} 

window.onload=function(){
  refreshMessageData();  
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Parveen Sharma
// Created on : (04.02.2009)
// Copyright 2009-2010 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    if(globalFL==0){
        //messageBox("Another request is in progress.");
        return false;
    }
    var fieldsArray = new Array(new Array("messageSubject","<?php echo ENTER_MESSAGE_SUBJECT; ?>"),
                                new Array("messageText","<?php echo ENTER_MESSAGE_TEXT; ?>"));
    var len = fieldsArray.length;
    
    if(document.getElementById('messageId').value=='') {
       if(document.getElementById('teacherId').value=='') {
         messageBox("<?php echo SELECT_TEACHER; ?>"); 
         return false; 
       }
    }
    
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
     
    if(document.getElementById('messageId').value=='') {
      addParentTeacher(); 
    }
    else{
      editParentTeacher();
    }
}
function initAdd(mode) {
    //document.getElementById('addNotice').target = 'uploadTargetAdd';   
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('ParentTeacher').target = 'fileUpload';
        document.getElementById('ParentTeacher').action= "<?php echo HTTP_LIB_PATH;?>/Parent/messageFileUpload.php"
        document.getElementById('ParentTeacher').submit();
        // document.getElementById('ParentTeacher').onsubmit=function() {         
        //   document.getElementById('ParentTeacher').target = 'fileUpload';   
        // }
    }
   else{
      document.getElementById('ParentTeacher').target = 'fileUpload';
      document.getElementById('ParentTeacher').action= "<?php echo HTTP_LIB_PATH;?>/Parent/messageFileUpload.php"
      document.getElementById('ParentTeacher').submit(); 
      // document.getElementById('ParentTeacher').onsubmit=function() {         
      //   document.getElementById('ParentTeacher').target = 'fileUpload';   
      // }
   } 
}

function showSentData(id,dv) {
    document.getElementById('divHeaderId3').innerHTML='&nbsp; Sent Message Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateSentData(id);   
}

function showInboxData(id,dv) {
    document.getElementById('divHeaderId4').innerHTML='&nbsp; Message Detail';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateInboxData(id);   
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW FINANCIAL YEAR
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addParentTeacher() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitParentTeacherAdd.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             roleId: trim(document.ParentTeacher.roleId.value), 
             teacherId: trim(document.ParentTeacher.teacherId.value),
             messageSubject: trim(document.ParentTeacher.messageSubject.value),      
             messageText: trim(document.ParentTeacher.messageText.value),
             hiddenFile:document.ParentTeacher.noticeAttachment.value  
         },
         onCreate: function() {
             //showWaitDialog(true);
         },
         onSuccess: function(transport){
            initAdd(1);    
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

function blankValues() {
   document.getElementById('divHeaderId1').innerHTML='&nbsp; Send Message';
   document.ParentTeacher.reset();
  // document.ParentTeacher.teacherId.value = '';
   document.ParentTeacher.messageSubject.value = '';
   document.ParentTeacher.messageText.value = '';
   document.ParentTeacher.messageId.value = '';
   document.getElementById('editLogoPlace').style.display = 'none';
   document.getElementById('replyMessage').style.display = 'none';
   document.getElementById('replyMessage_1').style.display = 'none';
   document.getElementById('replyMessage_2').style.display = 'none';
   //document.getElementById('replyMessage1').style.display = 'block';
   document.getElementById('replyMessage1').style.display = '';
   document.getElementById('replyMessage4').style.display = '';

   document.ParentTeacher.teacherId.disabled = false;
   document.ParentTeacher.messageSubject.disabled = false;
   document.ParentTeacher.messageText.disabled = false;
   document.ParentTeacher.attachmentFile.Readonly = false;
   
   //document.getElementById('rowStatus').style.display = 'block';
   document.getElementById('editLogoPlace1').style.display = 'inline';
   
   document.ParentTeacher.messageSubject.focus();
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A yearLabel
//  id=yearId
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteParentTeacher(id) {

     if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
         return false;
     }
     else {   
    
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitParentTeacherDelete.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {messageId: id},
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
                 hideWaitDialog();
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     refreshMessageData(); 
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
//THIS FUNCTION IS USED TO DISPLAY EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv) {
    document.getElementById('divHeaderId1').innerHTML="&nbsp; Reply To Teacher's Message";
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues(id);   
}

/*function editWindow1(id,dv) {
    document.getElementById('divHeaderId').innerHTML='&nbsp; Edit Parent Teacher Message';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    populateValues1(id);   
}
*/
 

function  download(str){    
     
    var address="<?php echo IMG_HTTP_PATH;?>/StudentMessage/"+str;
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A grade label
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editParentTeacher() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxInitParentTeacherEdit.php';
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: { 
            //teacherId: trim(document.ParentTeacher.teacherId.value),
            roleId: trim(document.ParentTeacher.troleId.value), 
            messageSubject: trim(document.ParentTeacher.messageSubject.value),      
            messageText: trim(document.ParentTeacher.messageText.value),
            messageId: trim(document.ParentTeacher.messageId.value),
            senderId: trim(document.ParentTeacher.senderId.value),
            receiverId: trim(document.ParentTeacher.receiverId.value),
            hiddenFile:document.ParentTeacher.noticeAttachment.value      
         },
         onCreate: function() {
             //showWaitDialog();
         },
         onSuccess: function(transport){
            initAdd(2);
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
       });
}




//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {

     blankValues(); 
     
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGetParentTeacherValues1.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {messageId: id},
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
               hideWaitDialog();
                if(trim(transport.responseText)==0) {
                    hiddenFloatingDiv('ParentTeacherActionDiv');
                    messageBox("<?php echo "Message details not found";  ?>");
                    getParentTeacherData();           
               }
               j = eval('('+trim(transport.responseText)+')');
              // alert(transport.responseText);
               document.getElementById('replyMessage').style.display = '';
               document.getElementById('replyMessage_1').style.display = '';
               document.getElementById('replyMessage_2').style.display = '';
               document.getElementById('replyMessage1').style.display = 'none';
               document.getElementById('replyMessage4').style.display = 'none';
              
               document.getElementById('senderName').innerHTML = trim(j.employeeName)+" ("+trim(j.roleName)+")";
               document.getElementById('senderDate').innerHTML = trim(j.messageDate);
               //document.getElementById('senderSubject').innerHTML = trim(j.messageSubject);
               //document.getElementById('senderText').innerHTML = trim(j.message);
               document.getElementById('receiverId').value = trim(j.receiverId);
               document.getElementById('senderId').value = trim(j.senderId);
               document.getElementById('messageId').value = trim(j.messageId);
               document.getElementById('troleId').value = trim(j.roleId); 

               
               document.ParentTeacher.messageSubject.value = "Re:"+j.messageSubject;    
               document.ParentTeacher.messageText.value = "\n\n------Reply Text-----\n\n"+j.message;                
              // alert(j.attachmentFile);    
               if(j.attachmentFile){
                     document.getElementById('editLogoPlace').style.display = 'block';
                     document.getElementById('editLogoPlace').innerHTML = 
                "<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
               }
               else{

                     document.getElementById('editLogoPlace').innerHTML = '--';
               }
         },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues1(id) {

     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGetParentTeacherValues.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {messageId: id},
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
            hideWaitDialog();
            if(trim(transport.responseText)==0) {
              hiddenFloatingDiv('ParentTeacherActionDiv');
              messageBox("<?php echo "Message details not found"; ?>");
              getParentTeacherData();           
           }
           j = eval('('+trim(transport.responseText)+')');
          //alert(transport.responseText);
           //return false;
           document.getElementById('receiverId1').value = trim(j.receiverId);
           document.getElementById('senderId1').value = trim(j.senderId);
           document.getElementById('messageId1').value = trim(j.messageId);
           document.getElementById('senderName').innerHTML = trim(j.employeeName);
           document.getElementById('senderDate').innerHTML = trim(j.messageDate);
           document.ParentTeacher1.messageSubject.value = "Re:"+j.messageSubject;    
           document.ParentTeacher1.messageText.value = "\n\n------Reply Text-----\n\n"+j.message;
           if(j.attachmentFile){
             //alert(j.attachmentFile);
             document.getElementById('editLogoPlace2').style.display = 'block';
             document.getElementById('editLogoPlace2').innerHTML = 
            "<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
           }
           else{
             document.getElementById('editLogoPlace2').innerHTML = '--';
           }
         },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateSentData(id) {
    
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGetParentTeacherValues.php';
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {messageId: id},
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
                 hideWaitDialog();
                 if(trim(transport.responseText)==0) {
                    hiddenFloatingDiv('LeaveTypeActionDiv');
                    messageBox("<?php echo "Message details not found"; ?>");
                    getParentTeacherData();           
               }

               j = eval('('+trim(transport.responseText)+')');

               //alert(transport.responseText);
               //document.getElementById('replyMessage').style.display = 'block';
               document.getElementById('senderNameEmployee').innerHTML = trim(j.employeeName);
               document.getElementById('senderDateEmployee').innerHTML = trim(j.messageDate);
               document.getElementById('senderSubjectEmployee').innerHTML = trim(j.messageSubject);
               document.getElementById('senderMessageEmployee').innerHTML = trim(j.message);
               if(j.attachmentFile){
                  document.getElementById('editLogoPlaceEmployee').style.display = 'block';
                  document.getElementById('editLogoPlaceEmployee').innerHTML = 
                "<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
               }
               else{
                 //document.getElementById('editLogoPlaceEmployee').style.display = 'none';
                  document.getElementById('editLogoPlaceEmployee').innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>";
               }
         },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditLeaveType" DIV
//
//Author : Rajeev Aggarwal
// Created on : (25.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateInboxData(id) {
     
     url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGetParentTeacherValues1.php';
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {messageId: id},
         onCreate: function() {
             showWaitDialog();
         },
         onSuccess: function(transport){
                 hideWaitDialog();
                 if(trim(transport.responseText)==0) {
                    hiddenFloatingDiv('InboxDataActionDiv');
                    messageBox("<?php echo "Message details not found"; ?>");
                    getParentTeacherData();           
               }
               j = eval('('+trim(transport.responseText)+')');
               document.getElementById('divHeaderId1').innerHTML='&nbsp; Reply to Teacher';
               document.getElementById('senderName1').innerHTML = trim(j.employeeName);
               document.getElementById('senderDate1').innerHTML = trim(j.messageDate);
               document.getElementById('senderText1').innerHTML = trim(j.messageSubject);
               document.getElementById('senderSubject1').innerHTML = trim(j.message);
               document.getElementById('editLogoPlace22').innerHTML = "<?php echo NOT_APPLICABLE_STRING; ?>";
               if(j.attachmentFile){
                  document.getElementById('editLogoPlace22').style.display = 'block';
                  document.getElementById('editLogoPlace22').innerHTML="<a href='<?php echo IMG_HTTP_PATH?>/StudentMessage/"+j.attachmentFile+"' target='_blank'><img src='<?php echo IMG_HTTP_PATH?>/download.gif'></a>";
               }
         },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
       refreshMessageData();
}

function fileUploadError(str,mode){
   hideWaitDialog(true);
   //globalFL=1;
  
   if("<?php echo ACCESS_DENIED;?>" == trim(str)) {
      messageBox(trim(str));
   }
   else 
   if("<?php echo MSG_SENT_OK;?>" != trim(str)) {
      messageBox(trim(str));
   }
   else
   if(mode==1){
      if("<?php echo MSG_SENT_OK;?>" == trim(str)) {
         flag = true;
         messageBox(trim(str));  
         hiddenFloatingDiv('ParentTeacherActionDiv');
         refreshMessageData();
         return false;
      }  
   }
   else if(mode==2){
      if("<?php echo MSG_SENT_OK;?>" == trim(str)) {
         hiddenFloatingDiv('ParentTeacherActionDiv1');
         refreshMessageData();        
         return false;
      }
   }
   else{
      messageBox(trim(str));  
   }
   
}

function getTeacher() {

    var url = '<?php echo HTTP_LIB_PATH;?>/Parent/ajaxGetRoleTeacher.php';    
    
    document.ParentTeacher.teacherId.length = null; 
    addOption(document.ParentTeacher.teacherId, '', 'Select');
    
    if(document.ParentTeacher.roleId.value=='') {
      return false;  
    }
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { roleId: document.ParentTeacher.roleId.value },  
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.ParentTeacher.teacherId.length = null;                  
                addOption(document.ParentTeacher.teacherId, '', 'Select');    
                for(i=0;i<len;i++) { 
                 addOption(document.ParentTeacher.teacherId, j[i].userId, j[i].employeeName);
                }
                
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Parent/parentTeacherMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

