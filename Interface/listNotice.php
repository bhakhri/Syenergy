<?php
 ini_set("post_max_size", "10M");
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Notice Form
//
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AddNotices');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
global $sessionHandler;
//$classId= $sessionHandler->getSessionVariable('ClassId');
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
	UtilityManager::ifManagementNotLoggedIn();
}
else{
	UtilityManager::ifNotLoggedIn();
}
//require_once(BL_PATH . "/Notice/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Manage Notices </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js");
?>
<script language="javascript">

//for addition
 tinyMCE.init({
        gecko_spellcheck:true,
        mode : "textareas",
        theme : "advanced",
        editor_selector : "tiny1",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
        },
        paste_postprocess : function(pl, o) {
        },


       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "bullist,numlist,|,undo,redo,|,forecolor,backcolor",
       theme_advanced_buttons3 : "sub,sup,|,ltr,rtl",
       force_br_newlines : true,
       forced_root_block : '',

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
 });


 //for editing
  tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        editor_selector : "tiny2",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
        },
        paste_postprocess : function(pl, o) {
        },


       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       //theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
       theme_advanced_buttons2 : "bullist,numlist,|,undo,redo,|,forecolor,backcolor",
       theme_advanced_buttons3 : "sub,sup,|,ltr,rtl",
       force_br_newlines : true,
       forced_root_block : '',

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
 });

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
                               new Array('visibleFromDate','Visible From','width="12%"','align="center"',true),
                               new Array('visibleToDate','Visible To','width="12%"','align="center"',true),
                               new Array('noticeSubject','Subject','width="20%"','',true),
                               new Array('departmentName','Issuing Dept.','width="12%"','',true),
                            // new Array('roleName','Sent To Role(s)','width="17%"','',true),
                               new Array('visibleMode','Mode','width="8%" align="center"',' align="center"',true),
                               new Array('noticePublishTo','Publish To','width="12%" align="center"',' align="center"',true),
                               new Array('noticeAttachment','Attachment','width="8%"','align="center"',false),
                               new Array('viewDetail','Notice Detail','width="12%" align="center"','align="center"',false),
                               new Array('action','Edit/Delete','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddNoticeDiv';
editFormName   = 'EditNoticeDiv';
winLayerWidth  = 600; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = 'return deleteNotice';
divResultName  = 'results';
page=1; //default page
sortField = 'visibleFromDate';
sortOrderBy    = 'DESC, n.noticeId DESC';
var sendSms = "<?php echo $sessionHandler->getSessionVariable('SMS_ALERT_FOR_NOTICE_UPLOAD') ?>";
var topPos = 0;
var leftPos = 0;
var globalFL=1;

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//This function Displays Div Window

function editWindow(id,dv,w,h) {
   // displayWindow(dv,w,h);

	displayFloatingDiv(dv,'', w, h, screen.width/4.8, screen.height/10);
    populateValues(id);
 /*  document.getElementById('roleId2').style.display='';
     makeDDHide('roleId2','d222','d333');
     document.getElementById('d111').style.zIndex=parseInt(document.getElementById('EditNoticeDiv').style.zIndex,10)+20;
     document.getElementById('d222').style.zIndex=parseInt(document.getElementById('EditNoticeDiv').style.zIndex,10)+10;
     document.getElementById('d333').style.zIndex=parseInt(document.getElementById('EditNoticeDiv').style.zIndex,10)+10;
     document.getElementById('d111').style.height='150px';
  */
}




function validateAddForm(frm, act) {

    if(globalFL==0){
        //messageBox("Another request is in progress.");
        return false;
    }

  /*  var fieldsArray = new Array( new Array("noticeSubject","<?php echo ENTER_NOTICE_SUBJECT;?>"));
                      new Array("noticeText","<?php echo ENTER_NOTICE_TEXT;?>"),
                                new Array("universityId","<?php echo SELECT_UNIVERSITY;?> "),
                                new Array("degreeId","<?php echo SELECT_DEGREE;?>"),
                               // new Array("branchId","<?php echo SELECT_BRANCH;?>"),
                                new Array("departmentId","<?php echo SELECT_DEPARTMENT; ?>"),
                                new Array("roleId","<?php echo SELECT_ROLE; ?>"));

    var len = fieldsArray.length;
   for(i=0;i<len;i++) {
       if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    else if(fieldsArray[i][0]=="roleId" && eval("frm."+(fieldsArray[i][0])+".value")=="" )  {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }   */
		 if(act=='Add') {
			 if(document.addNotice.noticeSubject.value =='') {
				 messageBox("Enter Notice Subject");
				document.addNotice.noticeSubject.focus();
				return false;
			 }
			 try{
        if(trim(tinyMCE.get('elm11').getContent())==''){
            messageBox("<?php echo "Enter Notice Text";?>");
            try{
              tinyMCE.execInstanceCommand("elm11", "mceFocus");
            }catch(e){}
            return false;
        }
       }
       catch(e){
       }
	    if(!dateDifference(eval("frm.visibleFromDate.value"),eval("frm.visibleToDate.value"),'-') ) {
                messageBox ("<?php echo VISIBLE_DATE_VALIDATION;?>");
                eval("frm.visibleFromDate.focus();");
                return false;
         }
	   if(document.addNotice.departmentId.value =='') {
		   messageBox("Select Department");
			document.addNotice.departmentId.focus();
			return false;
	   }
       
       if(document.addNotice.noticePublishedTo[0].checked==true) {
         if(document.addNotice.roleId.value == '') {
            messageBox("Select Role");
            document.addNotice.roleId.focus();
            return false;
	     }
       }
       else {
         if(document.addNotice.noticeInstituteId.value == '') {
            messageBox("Select Institute");
            document.addNotice.noticeInstituteId.focus();
            return false;
         } 
       }
	   
/*       if(document.addNotice.classId.value =='' && document.addNotice.roleId.value ) {
		   messageBox("Select Role or Class");
			document.addNotice.roleId.focus();
			return false;
	   }
		
*/	 
	 if(trim(document.addNotice.noticeAttachment.value)!=""){
            if(!checkFileExtensions(trim(document.addNotice.noticeAttachment.value))) {
                document.addNotice.noticeAttachment.focus();
                messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                return false;
             }
	 }
	 addNotice();

    }
    else if(act=='Edit') {
		 if(document.editNotice.noticeSubject.value =='') {
			messageBox("Enter Notice Subject");
			document.editNotice.noticeSubject.focus();
			return false;
		}


			try{
        if(trim(tinyMCE.get('elm12').getContent())==''){
            messageBox("<?php echo "Enter Notice Text"; ?>");
            try{
              tinyMCE.execInstanceCommand("elm12", "mceFocus");
            }catch(e){}
            return false;
        }
       }
       catch(e){
       }

	    if(!dateDifference(eval("frm.visibleFromDate1.value"),eval("frm.visibleToDate1.value"),'-') ) {
                 messageBox ("<?php echo VISIBLE_DATE_VALIDATION;?>");
                eval("frm.visibleFromDate1.focus();");
                return false;
        }

	    if(document.editNotice.departmentId.value =='') {
		   messageBox("Enter Department");
			document.editNotice.departmentId.focus();
			return false;
	   }

	   if(document.editNotice.noticePublishedTo[0].checked==true) {
         if(document.editNotice.roleId.value == '') {
            messageBox("Select Role");
            document.addNotice.roleId.focus();
            return false;
         }
       }
       else {
         if(document.editNotice.noticeInstituteId.value == '') {
            messageBox("Select Institute");
            document.editNotice.noticeInstituteId.focus();
            return false;
         } 
       }
	
/*	    if(document.editNotice.classId.value =='' && document.editNotice.roleId.value ) {
           messageBox("Select Role or Class");
            document.editNotice.roleId.focus();
            return false;
       }
        
*/	   
	   if(trim(document.editNotice.noticeAttachment.value)!=""){
            if(!checkFileExtensions(trim(document.editNotice.noticeAttachment.value))){
                document.editNotice.noticeAttachment.focus();
                messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                return false;
             }
       }
	   //closeTargetDiv('d111','containerDiv2');
       editNotice();
       //return false;
    }
}

function getBranch(){
	var u = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitBranch.php';
	   new Ajax.Request(u,
     {
         method:'post',
         parameters:{ degreeId: (document.addNotice.degreeId.value)
                      //noticeText: (document.addNotice.noticeText.value),
                    },
         onCreate: function() {
			 showWaitDialog(true);
		 },
			onSuccess: function(transport){
			 hideWaitDialog(true);

				var j = eval('(' + transport.responseText + ')');
				var len = j.length;
				document.addNotice.branchId.length = null;
				addOption(document.addNotice.branchId, '', 'ALL');
				/*
				if (len > 0) {
					addOption(document.studentAttendanceForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) {
					  addOption(document.addNotice.branchId, j[i].branchId, j[i].branchCode);
				}
				// now select the value
				document.addNotice.branchId.value = j[0].branchId;

				},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
      });
}
//To get Branch data for edit division
function getBranchData(){
	var u = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitBranch.php';
	   new Ajax.Request(u,
     {
         method:'post',
         parameters:{ degreeId: (document.editNotice.degreeId.value)
                      //noticeText: (document.addNotice.noticeText.value),
                    },
         onCreate: function() {
			 showWaitDialog(true);
		 },
			onSuccess: function(transport){
			 hideWaitDialog(true);

				var j = eval('(' + transport.responseText + ')');
				var len = j.length;
				document.editNotice.branchId.length = null;
				addOption(document.editNotice.branchId, '', 'ALL');
				/*
				if (len > 0) {
					addOption(document.studentAttendanceForm.subjectId, 'all', 'All');
				}
				*/
				for(i=0;i<len;i++) {
					  addOption(document.editNotice.branchId, j[i].branchId, j[i].branchCode);
				}
				// now select the value
				document.editNotice.branchId.value = j[0].branchId;
				},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }



      });
}
//This function adds form through ajax
function addNotice() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitAdd.php';
 	
    
     var l=document.addNotice.classId.length;
     var selClasses="";
     for(var i=0 ; i < l ;i++){
         if(document.addNotice.classId.options[ i ].selected){
             if(selClasses==""){
                 selClasses=document.addNotice.classId.options[ i ].value;
             }
            else{
                 selClasses+="~"+document.addNotice.classId.options[ i ].value;
            }
         }
     }


	 var l=document.addNotice.roleId.length;
     var selRoles="";
     for(var i=0 ; i < l ;i++){
         if(document.addNotice.roleId.options[ i ].selected){
             if(selRoles==""){
                 selRoles=document.addNotice.roleId.options[ i ].value;
             }
            else{
                 selRoles+="~"+document.addNotice.roleId.options[ i ].value;
            }
         }
     }
     
     var l=document.addNotice.noticeInstituteId.length;
     var selInstitute="";
     for(var i=0 ; i < l ;i++){
         if(document.addNotice.noticeInstituteId.options[ i ].selected){
             if(selInstitute==""){
                 selInstitute=document.addNotice.noticeInstituteId.options[ i ].value;
             }
            else{
                 selInstitute+="~"+document.addNotice.noticeInstituteId.options[ i ].value;
            }
         }
     }
     
     noticePublishTo='2';
     if(document.addNotice.noticePublishedTo[0].checked==true) { 
       noticePublishTo='1';
     }
     
     if(noticePublishTo=='1') {
       selInstitute="";     
     }
     else {
       selRoles="";     
       selClasses="";
       document.addNotice.universityId.selectedIndex=0;
       document.addNotice.degreeId.selectedIndex=0;
       document.addNotice.branchId.selectedIndex=0;
     }
     
	 var form = document.addNotice;
	 var parameter='noticeSubject='+escape(form.noticeSubject.value)+'&noticeText='+escape(trim(tinyMCE.get('elm11').getContent()));
     parameter=parameter+'&visibleFromDate='+document.addNotice.visibleFromDate.value+'&visibleToDate='+document.addNotice.visibleToDate.value;
     parameter=parameter+'&universityId='+document.addNotice.universityId.value+'&roleId='+selRoles+'&degreeId='+document.addNotice.degreeId.value;
     parameter=parameter+'&branchId='+document.addNotice.branchId.value+'&departmentId='+document.addNotice.departmentId.value;
     parameter=parameter+'&hiddenFile='+document.addNotice.noticeAttachment.value+'&noticeClassId='+selClasses;  
     parameter=parameter+'&visibleMode='+document.addNotice.visibleMode.value+'&noticeInstitute='+selInstitute;
     parameter=parameter+'&noticePublishTo='+noticePublishTo;
     
	 if(sendSms == 1) {
		id = document.addNotice.smsStatus.checked?1:0;
		parameter =parameter+ '&sms='+document.addNotice.smsText.value+'&smsStatus='+id;
	 }
     //selRoles="~"+selRoles+"~";
     //alert(selRoles);
     new Ajax.Request(url,
     {
         method:'post',
         parameters:parameter,
         onCreate: function() {
			// showWaitDialog(true);
		 },
		 onSuccess: function(transport){
            initAdd(1);
		 },
		  onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
      });
}

function blankValues() {
   document.addNotice.noticePublishedTo1.checked = true;
   document.addNotice.noticePublishedTo2.checked = false;
   getNoticePublish(1,'A'); 
   document.addNotice.reset();
   document.addNotice.noticeSubject.value = '';
   //document.addNotice.noticeText.value = '';
   tinyMCE.get('elm11').setContent("");
   if(sendSms == 1) {
	 document.addNotice.smsText.disabled=true;
   }
   document.getElementById('roleId').style.display='';
   //document.addNotice.visibleFromDate.value = '';
   //document.addNotice.visibleToDate.value = '';
   /*for(i=0;i< document.addNotice.roleId.length;i++)
   {
   	document.addNotice.roleId[ i ].checked = false ;
   }               */
   var l=document.addNotice.roleId.length;
   for(var i=0 ; i < l ;i++){
   //if(document.addNotice.roleId.options[ i ].selected){
         document.addNotice.roleId.options[ i ].selected=false;
     //}
   }
   document.addNotice.universityId.selectedIndex=0;
   document.addNotice.degreeId.selectedIndex=0;
   document.addNotice.branchId.selectedIndex=0;
   document.addNotice.noticeAttachment.value = '';
   document.addNotice.departmentId.value='';
   document.addNotice.noticeSubject.focus();

   /*
     makeDDHide('roleId1','d22','d33');
     document.getElementById('d11').style.zIndex=parseInt(document.getElementById('AddNoticeDiv').style.zIndex,10)+20;
     document.getElementById('d22').style.zIndex=parseInt(document.getElementById('AddNoticeDiv').style.zIndex,10)+10;
     document.getElementById('d33').style.zIndex=parseInt(document.getElementById('AddNoticeDiv').style.zIndex,10)+10;
     document.getElementById('d11').style.height='150px';
   */
}

//This function edit form through ajax

function editNotice() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitEdit.php';

     var l=document.editNotice.classId.length;
     var selClasses="";
     for(var i=0 ; i < l ;i++){
         if(document.editNotice.classId.options[ i ].selected){
             if(selClasses==""){
                 selClasses=document.editNotice.classId.options[ i ].value;
             }
            else{
                 selClasses+="~"+document.editNotice.classId.options[ i ].value;
            }
         }
     }


     var l=document.editNotice.roleId.length;
     var selRoles="";
     for(var i=0 ; i < l ;i++){
         if(document.editNotice.roleId.options[ i ].selected){
             if(selRoles==""){
                 selRoles=document.editNotice.roleId.options[ i ].value;
             }
            else{
                 selRoles+="~"+document.editNotice.roleId.options[ i ].value;
            }
         }
     }
     
     var l=document.editNotice.noticeInstituteId.length;
     var selInstitute="";
     for(var i=0 ; i < l ;i++){
         if(document.editNotice.noticeInstituteId.options[ i ].selected){
             if(selInstitute==""){
                 selInstitute=document.editNotice.noticeInstituteId.options[ i ].value;
             }
            else{
                 selInstitute+="~"+document.editNotice.noticeInstituteId.options[ i ].value;
            }
         }
     }
     
     noticePublishTo='2';
     if(document.editNotice.noticePublishedTo[0].checked==true) { 
       noticePublishTo='1';
     }
     
     if(noticePublishTo=='1') {
       selInstitute="";     
     }
     else {
       selRoles="";     
       selClasses="";
       document.editNotice.universityId.selectedIndex=0;
       document.editNotice.degreeId.selectedIndex=0;
       document.editNotice.branchId.selectedIndex=0;
     }
     
     
     var form = document.editNotice;
     
     var parameter='noticeSubject='+escape(form.noticeSubject.value)+'&noticeText='+escape(trim(tinyMCE.get('elm12').getContent()));
     parameter=parameter+'&visibleFromDate='+document.editNotice.visibleFromDate1.value+'&visibleToDate='+document.editNotice.visibleToDate1.value;
     parameter=parameter+'&universityId='+document.editNotice.universityId.value+'&roleId='+selRoles+'&degreeId='+document.editNotice.degreeId.value;
     parameter=parameter+'&branchId='+document.editNotice.branchId.value+'&departmentId='+document.editNotice.departmentId.value;
     parameter=parameter+'&hiddenFile='+document.editNotice.noticeAttachment.value+'&noticeClassId='+selClasses;  
     parameter=parameter+'&visibleMode='+document.editNotice.visibleMode.value+'&noticeInstitute='+selInstitute;
     parameter=parameter+'&noticePublishTo='+noticePublishTo+'&noticeId='+form.noticeId.value;

     if(sendSms == 1) {
		id = document.editNotice.editSmsStatus.checked?1:0;
		parameter = parameter+'&smsText='+document.editNotice.editSms.value+'&smsStatus='+id;
	 }
     new Ajax.Request(url,
     {
         method:'post',
         parameters: parameter,
         onCreate: function() {
			showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			// showWaitDialog(true);
            initAdd(2);
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
     });

}
// this function is used to clear the text box of add notice
function clearTextBox(){
	if(document.addNotice.smsText.value == "Enter Your SMS Text Here"){
		document.addNotice.smsText.value='';
	}
	document.addNotice.smsText.style.color="black";
}
// this function is used to clear the text box of edit notice
function clearEditTextBox(){
	if(document.editNotice.editSms.value == "Enter Your SMS Text Here"){
		document.editNotice.editSms.value='';
	}
	document.editNotice.editSms.style.color="black";
}
// this function is used to enbale/disable send sms  text box of add notice
function enableAddTextBox(){
	var formAdd =document.addNotice;
	if(formAdd.smsStatus.checked ==true){
		formAdd.smsText.disabled=false;
		formAdd.smsText.value='<?php echo "Enter Your SMS Text Here";?>';
		formAdd.smsText.style.color="gray";
	}
	else{
		formAdd.smsText.value='';
		formAdd.smsText.disabled=true;
	}
}
// this function is used to enbale/disable send sms  text box of edit notice
function enableEditTextBox(){
	var formEdit =document.editNotice;
	if(formEdit.editSmsStatus.checked== true){
		formEdit.editSms.disabled=false;
		formEdit.editSms.value='<?php echo "Enter Your SMS Text Here";?>';
		formEdit.editSms.style.color="gray";
	}
	else{
		formEdit.editSms.value='';
		formEdit.editSms.disabled=true;
	}
}

//This function calls delete function through ajax

function deleteNotice(id) {

        noticeStatus = '';
         url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitGetNoticeState.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {noticeId: id},
             asynchronous:false,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){

                     hideWaitDialog(true);
                     sirticeStatus = trim(transport.responseText);
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
          if (noticeStatus == 'expired') {
                messageBox("<?php echo OLD_NOTICE;?>");
                return false;
           }
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {noticeId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                      else {
                         messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
         }
}


//This function populates values in edit form through ajax

function populateValues(id) {

    var url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxGetValues.php';
    document.editNotice.reset();

    document.editNotice.noticeAttachment.value = '';
    document.editNotice.universityId.selectedIndex=0;
    document.editNotice.degreeId.selectedIndex=0;
    document.editNotice.branchId.selectedIndex=0;

    var l=document.editNotice.roleId.length;
    for(var i=0 ; i < l ;i++){
        document.editNotice.roleId.options[ i ].selected=false;
    }
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {noticeId: id},
         onCreate: function() {
			showWaitDialog(true);
		 	},
	 onSuccess: function(transport){
                	hideWaitDialog(true);
                	j = eval('('+transport.responseText+')');

                    getAllClass('U','Edit');
                    
                    document.getElementById('editLogoPlace').style.display = 'none';
               		imageLogoPath='';
                    document.editNotice.noticeId.value=j.noticeId;
                    document.editNotice.noticeSubject.value = j.noticeSubject;
                    //document.editNotice.noticeText.value =j.noticeText ;
                    tinyMCE.get('elm12').setContent(j.noticeText);
                    document.editNotice.visibleFromDate1.value =j.visibleFromDate ;
                    document.editNotice.visibleToDate1.value = j.visibleToDate;
                    document.editNotice.departmentId.value=j.departmentId;
                    
                    document.editNotice.visibleMode.value=j.visibleMode;
                    
                    if(sendSms == 1) {
                      document.editNotice.editSms.value=j.smsText;
                      if(j.sendSms == 1){
	                    document.editNotice.editSmsStatus.checked = true;
	                    document.editNotice.editSms.disabled=false;
	                  }
                      else{	
                         document.editNotice.editSmsStatus.checked = false;
	                  }
                    }        
                    
                    if(j.noticePublishTo==1) {
                      document.editNotice.noticePublishedTo1.checked = true;
                      document.editNotice.noticePublishedTo2.checked = false;
                      getNoticePublish(1,'E'); 
                    }
                    else {
                      document.editNotice.noticePublishedTo2.checked = true;
                      document.editNotice.noticePublishedTo1.checked = false;  
                      getNoticePublish(2,'E'); 
                    }
			        
                    if(j.noticePublishTo==1) {
                        var roles=j.roleId.split('~');
               		    var len=document.editNotice.roleId.length;
               		    for(var n =0 ; n <len  ;n++){
                  		    for(var i=0 ; i < roles.length ;i++){
                    		    if(document.editNotice.roleId.options[n].value==roles[i]){
                      		      document.editNotice.roleId.options[n].selected=true;
                    		    }
                 		    }
               		    }
               		    if(j.universityId!=null) {
                          getAllClass('U','Edit');  
                 	      document.editNotice.universityId.value = j.universityId;
               		    }
               		    if(j.degreeId!=null) {
                          getAllClass('D','Edit');  
                 	      document.editNotice.degreeId.value =j.degreeId ;
               		    }
               		    if(j.branchId!=null) {
                          getAllClass('B','Edit');
                 	      document.editNotice.branchId.value =j.branchId ;
               		    }
                        
                        var roles=j.noticeClassId.split('~');
                        var len=document.editNotice.classId.length;
                           for(var n =0 ; n <len  ;n++){
                              for(var i=0 ; i < roles.length ;i++){
                                if(document.editNotice.classId.options[n].value==roles[i]){
                                    document.editNotice.classId.options[n].selected=true;
                                }
                             }
                        }
                    }
                    else {
                        var roles=j.noticeInstituteId.split('~');
                        var len=document.editNotice.noticeInstituteId.length;
                           for(var n =0 ; n <len  ;n++){
                              for(var i=0 ; i < roles.length ;i++){
                                if(document.editNotice.noticeInstituteId.options[n].value==roles[i]){
                                    document.editNotice.noticeInstituteId.options[n].selected=true;
                                }
                             }
                        }
                    }
                    
               		document.editNotice.noticeAttachment.value = '';
               		//totalSelected('roleId2','d333');
               		document.getElementById('downloadFileName').value = '';
               		// File Attachment
			        if(j.noticeAttachment=='' || j.noticeAttachment==null){
                	    //document.getElementById('editLogoPlace').innerHTML = '';
                 	    document.getElementById('editLogoPlace').style.display = 'none';
               		}
               		else{
                  	    //document.getElementById('editLogoPlace').innerHTML = j.noticeAttachment;
                  	    document.getElementById('downloadFileName').value = j.noticeAttachment;
                  	    document.getElementById('editLogoPlace').style.display = '';
               		}

			document.getElementById('uploadIconLabel').innerHTML='';
			if(j.noticeAttachment !=-1){
				document.getElementById('uploadIconLabel').innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/delete.gif"  onclick="deatach('+j.noticeId+');" class="imgLinkRemove1" alt="Delete Uploaded File" title="Delete Uploaded File"';
				}
			document.editNotice.noticeSubject.focus();
         		},
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>"); }

       });
}

function initAdd(mode) {
    //document.getElementById('addNotice').target = 'uploadTargetAdd';
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('addNotice').target = 'uploadTargetAdd';
        document.getElementById('addNotice').action= "<?php echo HTTP_LIB_PATH;?>/Notice/fileUpload.php"
        document.getElementById('addNotice').submit();
    }
   else{
      document.getElementById('editNotice').target = 'uploadTargetEdit';
      document.getElementById('editNotice').action= "<?php echo HTTP_LIB_PATH;?>/Notice/fileUpload.php"
      document.getElementById('editNotice').submit();
   }
}
function fileUploadError(str,mode){
   hideWaitDialog(true);
   //globalFL=1;
   if("<?php echo SUCCESS;?>" != trim(str)) {
       messageBox(trim(str));
   }
   if(mode==1){
      if("<?php echo SUCCESS;?>" == trim(str)) {
         flag = true;
         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
            blankValues();
         }
         else {
            hiddenFloatingDiv('AddNoticeDiv');
            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
            return false;
         }
      }
   }
   else if(mode==2){
      if("<?php echo SUCCESS;?>" == trim(str)) {
          hiddenFloatingDiv('EditNoticeDiv');
          sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
          return false;
      }
   }
   else{
      messageBox(trim(str));
   }
}

function  download1(){

   var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+document.getElementById('downloadFileName').value;
//   window.location = address;
  window.open(address,"Attachment","status=1,resizable=1,scrollbars=1,width=800,height=600")
   return false;
}

function  download(str){
    var address="<?php echo IMG_HTTP_PATH;?>/Notice/"+str;
	//alert(address);
    window.open(address,"Attachment","status=1,resizable=1,scrollbars=1,width=800,height=600")
}


function sendKeys(mode,eleName, e) {
     var ev = e||window.event;
     thisKeyCode = ev.keyCode;
     if (thisKeyCode == '13') {
        if(mode==1){
          var form = document.addNotice;
        }
        else{
          var form = document.editNotice;
        }
        eval('form.'+eleName+'.focus()');
        return false;
     }
}
//for help module
function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');
      return false;
    }
     if(document.getElementById('helpChk').checked == false) {
         return false;
     }
    //document.getElementById('divHelpInfo').innerHTML=title;
    document.getElementById('helpInfo').innerHTML= msg;
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);

    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}
/*
var initialTextForMultiDropDowns='Click to select multiple items';
var selectTextForMultiDropDowns='item';
//used to close popuped div [over riding function defined in js file]
function hiddenFloatingDiv(divId)
{
    document.getElementById(divId).style.visibility='hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    over=false;
    DivID = "";
    if(document.getElementById('containfooter'))
    {
        document.getElementById('containfooter').style.display='';
    }
 try{
  document.getElementById('d11').style.display='none';
  document.getElementById('d22').style.display='none';
  document.getElementById('roleId1').style.display='none';
 }
 catch(e){}
 try{
  document.getElementById('d111').style.display='none';
  document.getElementById('d222').style.display='none';
  document.getElementById('roleId2').style.display='none';
 }
 catch(e){}
}
*/

function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/listNoticePrint.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"StateReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {

    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listNoticeCSV.php?'+qstr;
    window.location = path;
}


function deatach(id){
if(false===confirm("Do you want to delete this file?")) {
              return false;
           }
          else {

           var url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxDeleteUploadedFile.php';
              new Ajax.Request(url,
              {
               method:'post',
               parameters: {
                  noticeId: id
               },
              onCreate: function() {
                  showWaitDialog(true);
               },
             onSuccess: function(transport){
                       hideWaitDialog(true);
                       if("<?php echo DELETE;?>"==trim(transport.responseText)) {

                        // messageBox("File Deleted");
                        // document.getElementById('uploadIconLabel').innerHTML='';
                         //document.getElementById('uploadIconLabel2').innerHTML='';
						document.getElementById('editLogoPlace').style.display = 'none';
                       // hiddenFloatingDiv('EditNoticeDiv');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
          });
         }
//alert(document.editNotice.noticeId.value);
}


function getAllClass(str,frm){
  
    var url ='<?php echo HTTP_LIB_PATH;?>/Notice/ajaxPopulateValues.php';
  
    var strAll=''; 
    if(frm=='Add') {
      form = document.addNotice;    
    }
    else { 
      form = document.editNotice;  
    }
    
    if(str=='U') {
      form.degreeId.length = null;
      addOption(form.degreeId, 'NULL', 'All');
      strAll='D';
    }
   
    if(str=='D' || strAll=='D') {
      form.branchId.length = null;
      addOption(form.branchId, 'NULL', 'All');
      strAll='B';
    }
    
    if(str=='B' || strAll=='B') {
      form.classId.length = null;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 universityId : form.universityId.value,
                 branchId  : form.branchId.value,  
                 degreeId  : form.degreeId.value,
                 val: str
             },
             onCreate: function(transport){
                 showWaitDialog();
             },   
             onSuccess: function(transport){
                hideWaitDialog();
                var ret=trim(transport.responseText).split('!~~!');
                var j0 = eval(ret[0]);
                var j1 = eval(ret[1]);
                var j2= eval(ret[2]);
                var j3 = eval(ret[3]);
                
                if(str=='U') {
                  for(i=0;i<j1.length;i++) { 
                    addOption(form.degreeId, j1[i].degreeId, j1[i].degreeCode);
                  }
                  str='D';
                }
                
                if(str=='D') {
                  for(i=0;i<j2.length;i++) { 
                    addOption(form.branchId, j2[i].branchId, j2[i].branchCode);
                  }
                  str='B';
                }
                
                if(str=='B') {
                  for(i=0;i<j3.length;i++) { 
                    addOption(form.classId, j3[i].classId, j3[i].className);
                  }
                }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function getNoticePublish(val,str) {
   
   if(str=='A') {
      if(val=='1') {
        document.getElementById('showRoleInstituteAdd').style.display='none'; 
        document.getElementById('showRoleNoticeAdd').style.display='';
      } 
      else {
        document.getElementById('showRoleInstituteAdd').style.display=''; 
        document.getElementById('showRoleNoticeAdd').style.display='none';
      }
   } 
   else {
      if(val=='1') {
        document.getElementById('showRoleInstituteEdit').style.display='none'; 
        document.getElementById('showRoleNoticeEdit').style.display='';
      } 
      else {
        document.getElementById('showRoleInstituteEdit').style.display=''; 
        document.getElementById('showRoleNoticeEdit').style.display='none';
      }
   }
}


function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 600, 600)
    populateMessageValues(id);
}


function populateMessageValues(id) {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Notice/ajaxNoticeDetail.php';    
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {noticeId: id},
         onCreate: function() {
            showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            j = eval('('+transport.responseText+')');
            document.getElementById('viewNoticeSubject').innerHTML = j.noticeSubject;  
            document.getElementById('viewNoticeDepartment').innerHTML = j.departmentName;
            document.getElementById('viewVisibleFromDate').innerHTML = j.visibleFromDate; 
            document.getElementById('viewVisibleToDate').innerHTML = j.visibleToDate;
            document.getElementById('viewNoticeText').innerHTML = j.noticeText;
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>"); }
       }); 
} 

</script>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Notice/listNoticeContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
		getBranch();
		getBranchData();
    //-->
</SCRIPT>
</body>
</html>
