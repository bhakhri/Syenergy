<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF institute events for teacher
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CourseResourceMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
//require_once(BL_PATH . "/Teacher/TeacherActivity/initCourseResourceList.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Upload Course Resources </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="1%"','',false), 
new Array('subject','Subject','width="10%"','',true) , 
new Array('description','Description','width="15%"','',true), 
new Array('resourceName','Type','width="10%"','',true), 
new Array('postedDate','Date','width="8%"','align="center"',true),
new Array('resourceLink','Link','width="8%"','',false),
new Array('attachmentLink','Attachment','width="4%"','align="center"',false),
new Array('downloadCount','Count','width="4%"','align="right"',true),
new Array('actionString','Action','width="3%"','align="center"',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxCourseResourceList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddResourceDiv';   
editFormName   = 'EditResourceDiv';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteResource';
divResultName  = 'results';
page=1; //default page
sortField = 'subject';
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
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow('EditResourceDiv',winLayerWidth,winLayerHeight);
    populateValues(id);   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("subject","<?php echo SELECT_SUBJECT;?>"),
    new Array("category","<?php echo SELECT_CATEGORY;?>"),
    new Array("description","<?php echo ENTER_DESCRIPTION;?>") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            //unsetAlertStyle(fieldsArray[i][0]);
            if(trim(eval("frm."+(fieldsArray[i][0])+".value")).length<5 && fieldsArray[i][0]=='description' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo DESCRIPTION_LENGTH;?>"); 
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }            
        }
    }
    if(act=='Add') {
         if(trim(document.AddResource.resourceUrl.value)=="" && trim(document.AddResource.resourceFile.value)==""){     
         messageBox("<?php echo ATLEAST_ONE_RESOURCE; ?>");
         document.AddResource.resourceUrl.focus();
         return false;
        }  
		  if(trim(document.AddResource.group.value)=="" && trim(document.AddResource.group.value)==""){     
         messageBox("Select group");
         document.AddResource.group.focus();
         return false;
        }  
		
       if(trim(document.AddResource.resourceUrl.value)!=""){
           if(!isValidateUrl(trim(document.AddResource.resourceUrl.value))){
             document.AddResource.resourceUrl.focus();  
             messageBox("<?php echo INCORRECT_URL; ?>");
             return false;
           }  
         }    
        if(trim(document.AddResource.resourceFile.value)!=""){
           if(!checkAllowdExtensions(trim(document.AddResource.resourceFile.value))){
             document.AddResource.resourceFile.focus();  
             messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
             return false;
         } 
        }    
        
        initAdd();
        addResource();
        //return false;
    }
   else if(act=='Edit') {
       if(trim(document.EditResource.resourceUrl.value)=="" && trim(document.EditResource.resourceFile.value)=="" && document.getElementById('uploadIconLabel').innerHTML==''){     
         messageBox("<?php echo ATLEAST_ONE_RESOURCE; ?>");
         document.EditResource.resourceUrl.focus();
         return false;
        }   
	  if(trim(document.EditResource.group.value)=="" && trim(document.EditResource.group.value)==""){     
         messageBox("Select group");
         document.EditResource.group.focus();
         return false;
        }  
       if(trim(document.EditResource.resourceUrl.value)!=""){
           if(!isValidateUrl(trim(document.EditResource.resourceUrl.value))){
             document.EditResource.resourceUrl.focus();  
             messageBox("<?php echo INCORRECT_URL; ?>");
             return false;
           }  
         }    
      if(trim(document.EditResource.resourceFile.value)!=""){ 
         if(!checkAllowdExtensions(trim(document.EditResource.resourceFile.value))){
             document.EditResource.resourceFile.focus();
             messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
             return false;
         } 
       }  
        
        initEdit();
        editResource();
        //return false;
    }
}

//--------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check a files extension before it is uploaded
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function checkAllowdExtensions(value){
  //get the extension of the file 
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="<?php echo implode(",",$allowedExtensionsArray );?>";

  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;
  
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }

  if(fl){
   return true;
  }
 else{
  return false;
 }   
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Resource
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addResource() {
	
	 if(document.getElementById('group').value!='') {
				groupId = getCommaSepratedResource("group","AddResource");
		}
		else {
          len= document.getElementById('group').options.length;
          t=document.getElementById('group');
			if(len>0) {
				for(k=0;k<len;k++) { 
					if(groupId=='') 
						groupId = t.options[k].value;
					else
						groupId = groupId + ', '+t.options[k].value;
					}
			}
      }
//	  alert(groupId);
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxAddCourseResource.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              subjectId: (document.AddResource.subject.value), 
			  groupId: groupId,
              resourceType: (document.AddResource.category.value), 
              resourceUrl: (document.AddResource.resourceUrl.value),
              description: (document.AddResource.description.value),
              hiddenFile : document.AddResource.resourceFile.value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                     } 
                     /*
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                         else {
                             hiddenFloatingDiv('AddResourceDiv');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                     */
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A Resource
//id=courseResourceId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteResource(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDeleteCourseResource.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {courseResourceId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog(); 
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
function getGroups(formName) {

	frm = eval("document."+formName);
	var subjectId = frm.subject.value;
	frm.group.length = null;

	if (subjectId == '') {
  	  return false;
	}

	var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetResourceGroup.php';
	var pars = 'subjectId='+subjectId;
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
				for(i=0;i<len;i++) { 
					addOption(frm.group, j[i].groupId, j[i].groupName);
				}
				// now select the value
				//document.testWiseMarksReportForm.groupId.value = j[0].groupId;
		   },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });

	 //  hideResults();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.AddResource.reset(); 
   document.AddResource.subject.value = '';
   document.AddResource.category.value = '';
   document.AddResource.resourceUrl.value = '';
   document.AddResource.description.value = '';
   document.AddResource.resourceFile.value = '';
   document.EditResource.courseResourceId.value='';
   
   document.AddResource.subject.focus();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A Resource
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editResource() {
	 if(document.EditResource.group.value!='') {
				groupIds = getCommaSepratedResource("group","EditResource");
		}
		else {
          len= document.EditResource.group.options.length;
          t=document.EditResource.group;
			if(len>0) {
				for(k=0;k<len;k++) { 
					if(groupIds=='') 
						groupIds = t.options[k].value;
					else
						groupIds = groupIds + ', '+t.options[k].value;
					}
			}
      }
	//  alert(groupIds+"hii");  
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEditCourseResource.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
              courseResourceId: (document.EditResource.courseResourceId.value), 
              subjectId: (document.EditResource.subject.value), 
			  groupId: groupIds,
              resourceType: (document.EditResource.category.value), 
              resourceUrl: (document.EditResource.resourceUrl.value),
              description: (document.EditResource.description.value),
              hiddenFile : document.EditResource.resourceFile.value 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     /*
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditResourceDiv');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
                    */ 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           }); 
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EditResourceDiv" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (04.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetCourseResourceDetails.php';
         document.EditResource.courseResourceId.value='';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {courseResourceId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditResourceDiv');
                        messageBox("<?php echo RESOURCE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                        //return false;
                   }
	                j = eval('('+transport.responseText+')');
                 
                   document.EditResource.resourceFile.value='';
                   document.EditResource.subject.value = j.subjectId;
                   document.EditResource.category.value = j.resourceTypeId;
                   document.EditResource.resourceUrl.value = (j.resourceUrl==-1 ? '' : j.resourceUrl);
                   document.EditResource.description.value = j.description;
                   document.EditResource.courseResourceId.value = j.courseResourceId;
                   getGroups('EditResource');
                   document.getElementById('uploadIconLabel').innerHTML='';
                   document.getElementById('uploadIconLabel2').innerHTML='';
                   if(j.attachmentFile !=-1){
                    document.getElementById('uploadIconLabel').innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" name="'+j.attachmentFile+'" onclick="download(this.name);" title="Download File" />';
                    document.getElementById('uploadIconLabel2').innerHTML='<img src="<?php echo IMG_HTTP_PATH; ?>/delete.gif"  onclick="deleteUploadedFile('+j.courseResourceId+');" title="Delete Uploaded File" />';
                   }
			
				//   alert("testing "+j['groupId']);
				  var groupArray = j['groupId'].split(",");
				  var totalSelectedGroups = groupArray.length;
				  var ctr = 0;
				  var len = document.EditResource.group.options.length;
				  var form = document.EditResource;

					groupCtr = 0;
					while (groupCtr < len) {
						form.group.options[groupCtr].selected=false;
						groupCtr++;
					}
					
				
					while (ctr < totalSelectedGroups) {
						groupCtr = 0;
						thisSelectedGroup = groupArray[ctr];
						while (groupCtr < len) {
							thisFormGroupValue = form.group.options[groupCtr].value;
							if (thisSelectedGroup == thisFormGroupValue) {
								form.group.options[groupCtr].selected=true;
							}
							groupCtr++;
						}
						ctr++;
					} 

					
				  /*
				  for(var i=0; i<len; i++) {
					  document.EditResource.groups.options[i].selected=false;
					  if(n <  totalGroups) {
						if(group[n]==document.EditResource.groups.options[i].value){
						  document.EditResource.groups.options[i].selected=true;
						  n++;
						}
					  }
					}
                   document.EditResource.subject.focus();
				   */

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function deleteUploadedFile(id) {
         if(false===confirm("Do you want to delete this file?")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDeleteUploadedFile.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  courseResourceId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true); 
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                        /* 
                         messageBox("File Deleted");
                         document.getElementById('uploadIconLabel').innerHTML='';
                         document.getElementById('uploadIconLabel2').innerHTML='';
                        */
                        hiddenFloatingDiv('EditResourceDiv');
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
                     }
                     else {
                         messageBox(trim(transport.responseText));
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }    
           
}

//used to upload file
function initAdd() {
    document.getElementById('AddResource').onsubmit=function() {
        document.getElementById('AddResource').target = 'uploadTargetAdd';
    }
}

//used to upload file
function initEdit() {
    document.getElementById('EditResource').onsubmit=function() {
        document.getElementById('EditResource').target = 'uploadTargetEdit';
    }
}

function  download(str){
 var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
 window.location=address;
 //window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function fileUploadError(str,mode){
   hideWaitDialog(true);    
   
   if(document.EditResource.courseResourceId.value==''){
      if("<?php echo SUCCESS;?>" == trim(str)) {
          if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                 blankValues();
             }
             else {
                 hiddenFloatingDiv('AddResourceDiv');
                 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                 return false;
             }
         }
       else{
           messageBox(str);
           try{ 
            document.AddResource.subject.focus();
           }
           catch(e){}
           return false;
       }
    }
   else if(document.EditResource.courseResourceId.value!=''){
       if("<?php echo SUCCESS;?>" == trim(str)) {
             hiddenFloatingDiv('EditResourceDiv');
             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
             return false;
       }
      else{
           messageBox(str);
           try{ 
            document.EditResource.subject.focus();
           }
           catch(e){}
           return false;
      }
       
   }
   else{
        messageBox(str);
        try{
          hiddenFloatingDiv('AddResourceDiv');
        }
        catch(e){
        }
        try{
            hiddenFloatingDiv('EditResourceDiv');
        }
        catch(e){
        }
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
        return false;
   } 
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listCourseResourceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
//---------------------------------------------------------------------------------------------------------------  
//purpose: to trim a string and output str.. etc
//Author:Dipanjan Bhattcharjee
//Date:2.09.2008
//$str=input string,$maxlenght:no of characters to show,$rep:what to display in place of truncated string
//$mode=1 : no split after 30 chars,mode=2:split after 30 characters
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------  
function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
// $History: listCourseResource.php $ 
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 12/02/10   Time: 13:01
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//0002830
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 4/02/10    Time: 12:55
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//0002528,0002303,0002193,0001928,
//0001922,0001863,0001763,0001238,
//0001229,0001894,0002143
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 13/11/09   Time: 11:44
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001890,00001892
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 10/27/09   Time: 4:48p
//Updated in $/LeapCC/Interface/Teacher
//resolved issue 0001893
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 22/10/09   Time: 17:27
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//0001556
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 12/09/09   Time: 17:36
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//00001502,00001496
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:54
//Updated in $/LeapCC/Interface/Teacher
//Corrected look and feel of teacher module logins
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 4/03/09    Time: 13:51
//Updated in $/LeapCC/Interface/Teacher
//Fixes Bugs
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 12/04/08   Time: 11:19a
//Created in $/LeapCC/Interface/Teacher
//Created "Upload Resource" Module
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/28/08   Time: 3:52p
//Updated in $/Leap/Source/Interface/Teacher
//Corrected url validation error
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/24/08   Time: 10:56a
//Updated in $/Leap/Source/Interface/Teacher
//Added javascript check  for URL validation
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/06/08   Time: 11:46a
//Updated in $/Leap/Source/Interface/Teacher
//Corrected bug of donwload files having spaces in name
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:43p
//Created in $/Leap/Source/Interface/Teacher
//Created CourseResource Module
?>