<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ADVFB_TeacherMapping');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Teacher Mapping </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('labelName','Time Table','width="15%"','align="left"',true) , 
                               new Array('feedbackSurveyLabel','Survey','width="15%"','align="left"',true) ,
                               new Array('className','Class','width="25%"','align="left"',true) , 
                               //new Array('groupName','Group','width="15%"','align="left"',true), 
                               //new Array('subjectCode','Subject','width="15%"','align="left"',true) , 
                               //new Array('mappedEmployees','Mapped Employees','width="18%"','align="right"',true) , 
                               //new Array('employeeName','Employee','width="18%"','align="left"',true) , 
                               new Array('actionString','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxTeacherMappingList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddTeacher';   
editFormName   = 'EditTeacher';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteTeacher';
divResultName  = 'results';
page=1; //default page
sortField = 'labelName';
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
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id) {
    displayWindow('AddTeacher',320,200);
    populateValues(id);   
}

function detailWindow(id) {
    displayWindow('TeacherMappingDetail',500,410);
    detailPopulateValues(id);   
}


function detailPopulateValues(id) {
     
     document.getElementById('divTeacherMappingDetail').innerHTML='';    
     document.getElementById('divTeacherMappingDetailMsg').innerHTML='';    
     
     var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxTeacherMappingDetailList.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: { id: id },
         onCreate: function() {
           showWaitDialog(true);
         },
         onSuccess: function(transport){
           hideWaitDialog(true);
           
           var ret=trim(transport.responseText).split('~~');   
           
           document.getElementById('divTeacherMappingDetailMsg').innerHTML=ret[0];  
           document.getElementById('divTeacherMappingDetail').innerHTML=ret[1];
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(
      new Array("timeTableLabelId","<?php echo SELECT_TIME_TABLE;?>"),
      new Array("surveyId","<?php echo SELECT_SURVEY;?>"),
      new Array("classId","<?php echo SELECT_CLASS;?>"),
      new Array("groupId","<?php echo SELECT_GROUP;?>"),
      new Array("subjectId","<?php echo SELECT_SUBJECT;?>"),
      new Array("employeeId","<?php echo SELECT_EMPLOYEE;?>")
    );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    if(document.AddTeacher.mappingId.value=='') {
        addTeacher();
        return false;
    }
    else if(document.AddTeacher.mappingId.value!='') {
        editTeacher();
        return false;
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addTeacher() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxTeacherMappingOperations.php';
         
         var ele=document.AddTeacher.employeeId;
         var len=ele.options.length;
         var empIds='';
         for(var i=0;i<len;i++){
             if(ele.options[i].selected==true){
                 if(empIds!=''){
                     empIds +=',';
                 }
                 empIds +=ele.options[i].value;
             }
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId : document.AddTeacher.timeTableLabelId.value,
                 surveyId         : (document.AddTeacher.surveyId.value),
                 classId          : (document.AddTeacher.classId.value),
                 groupId          : (document.AddTeacher.groupId.value), 
                 subjectId        : (document.AddTeacher.subjectId.value),
                 employeeIds      : (empIds),
                 modeName         : 1 
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
                             hiddenFloatingDiv('AddTeacher');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.AddTeacher.classId.focus();
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>"); }
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A NEW CITY
//  id=cityId
//Author : Dipanjan Bhattacharjee
// Created on : (25.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteTeacher(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else { 
        
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxTeacherMappingOperations.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                  mappingId : id,
                  modeName  : 3
             },
             onCreate: function() {
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
//THIS FUNCTION IS USED TO CLEAN UP THE "addCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function blankValues() {
   document.getElementById('divHeaderId1').innerHTML='&nbsp;Add Teacher Mapping';
   document.AddTeacher.reset();
   document.AddTeacher.surveyId.options.length=1;
   document.AddTeacher.classId.options.length=1;
   document.AddTeacher.groupId.options.length=1;
   document.AddTeacher.subjectId.options.length=1;
   resetEmps();
   document.AddTeacher.mappingId.value='';
   document.AddTeacher.timeTableLabelId.focus();
}

function resetEmps(){
    var ele=document.AddTeacher.employeeId;
    var len=ele.options.length;
    var empIds='';
    for(var i=0;i<len;i++){
      ele.options[i].selected=false;
    }
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CITY
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editTeacher() {
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxTeacherMappingOperations.php';
         
         var ele=document.AddTeacher.employeeId;
         var len=ele.options.length;
         var empIds='';
         for(var i=0;i<len;i++){
             if(ele.options[i].selected==true){
                 if(empIds!=''){
                     empIds +=',';
                 }
                 empIds +=ele.options[i].value;
             }
         }
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 mappingId        : document.AddTeacher.mappingId.value,
                 timeTableLabelId : document.AddTeacher.timeTableLabelId.value,
                 surveyId         : (document.AddTeacher.surveyId.value),
                 classId          : (document.AddTeacher.classId.value),
                 groupId          : (document.AddTeacher.groupId.value), 
                 subjectId        : (document.AddTeacher.subjectId.value),
                 employeeIds      : (empIds),
                 modeName         : 2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('AddTeacher');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                     else {
                        messageBox(trim(transport.responseText));
                        document.EditTeacher.classId.focus();                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCity" DIV
//
//Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateValues(id) {
            document.AddTeacher.reset();
            document.AddTeacher.classId.options.length=1;
            document.AddTeacher.groupId.options.length=1;
            document.AddTeacher.subjectId.options.length=1;
            document.getElementById('surveyId').options.length=1;
            
            resetEmps();
            document.getElementById('divHeaderId1').innerHTML='&nbsp;Edit Teacher Mapping';
         
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetMappedTeachers.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 mappingId: id
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditTeacher');
                        messageBox("<?php echo TEACHER_MAPPING_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   document.AddTeacher.mappingId.value=j[0].teacherMappingId;
                    
                   document.AddTeacher.timeTableLabelId.value=j[0].timeTableLabelId;
                   
                   fetchMappedSurveyLabels(j[0].timeTableLabelId);
                   document.getElementById('surveyId').value=j[0].feedbackSurveyId;
                                      
                   fetchClass(j[0].timeTableLabelId);
                   document.AddTeacher.classId.value=j[0].classId;
                   
                   fetchGroup(j[0].timeTableLabelId,j[0].classId);
                   document.AddTeacher.groupId.value=j[0].groupId;
                   
                   fetchSubject(j[0].timeTableLabelId,j[0].classId);
                   document.AddTeacher.subjectId.value=j[0].subjectId;
                   
                   var cnt=j.length;
                   var ele=document.AddTeacher.employeeId;
                   var len=ele.options.length;
                   for(var i=0;i<cnt;i++){
                       for(var k=0;k<len;k++){
                           if(j[i].employeeId==ele.options[k].value){
                              ele.options[k].selected=true; 
                           }
                       }
                   }
                  document.AddTeacher.timeTableLabelId.focus(); 
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function fetchClass(value) {
         
         var classId=document.AddTeacher.classId;
         classId.options.length=1;
         document.AddTeacher.groupId.options.length=1;
         document.AddTeacher.subjectId.options.length=1;
         //document.AddTeacher.employeeId.options.length=0;
         resetEmps();
         if(value==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetTimeTableClass.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        return false;
                    }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                      addOption(classId,j[i].classId,j[i].className);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function fetchGroup(value1,value2) {
         
         var groupId=document.AddTeacher.groupId;
         groupId.options.length=1;
         //document.AddTeacher.subjectId.options.length=1;
         //document.AddTeacher.employeeId.options.length=0;
         resetEmps();
         if(value1==''  || value2==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetTimeTableGroups.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: value1,
                 classId : value2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        return false;
                    }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                      addOption(groupId,j[i].groupId,j[i].groupName);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function fetchSubject(value1,value2) {
         var subjectId=document.AddTeacher.subjectId;
         subjectId.options.length=1;
         //document.AddTeacher.employeeId.options.length=0;
         resetEmps();
         if(value1==''  || value2==''){
             return false;
         }
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetTimeTableSubjects.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: value1,
                 classId : value2
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        return false;
                    }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   for(var i=0;i<len;i++){
                      addOption(subjectId,j[i].subjectId,j[i].subjectCode);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

//teachers will be fetched from time table during addition[ONLY]
function fetchTimeTableTeachers() {
         if(document.AddTeacher.mappingId.value!=""){
         }
         if(document.getElementById('timeTableLabelId').value=="" || document.getElementById('classId').value=="" || document.getElementById('groupId').value=="" || document.getElementById('subjectId').value==""){
             return false;
         }
         resetEmps();
         
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetTimeTableTeachers.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: document.getElementById('timeTableLabelId').value,
                 classId : document.getElementById('classId').value,
                 groupId : document.getElementById('groupId').value,
                 subjectId : document.getElementById('subjectId').value
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        return false;
                    }
                   var j = eval('('+trim(transport.responseText)+')');
                   var cnt=j.length;
                   var ele=document.AddTeacher.employeeId;
                   var len=ele.options.length;
                   for(var i=0;i<cnt;i++){
                       for(var k=0;k<len;k++){
                           if(j[i].employeeId==ele.options[k].value){
                              ele.options[k].selected=true; 
                           }
                       }
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


function fetchMappedSurveyLabels(val) {
         var surveyId=document.getElementById('surveyId');
         surveyId.options.length=1;
         if(val==''){
             return false;
         } 
         var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetMappedSurveyLabels.php';
         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: val
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   if(trim(transport.responseText)==0) {
                        return false;
                   }
                   var j = eval('('+trim(transport.responseText)+')');
                   var len=j.length;
                   
                   for(var i=0;i<len;i++){
                       addOption(surveyId,j[i].feedbackSurveyId,j[i].feedbackSurveyLabel);
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

 function blankValuesNew() {
   
   var frm = document.AddMappingTeacher;
   frm.surveyId.length = null; 
   addOption(frm.surveyId, '', 'Select');
  
   frm.classId.length = null; 
   addOption(frm.classId, '', 'Select');

}


function getSurveyClass() {
 
   var frm = document.AddMappingTeacher;
   
   frm.surveyId.length = null; 
   addOption(frm.surveyId, '', 'Select');
  
   frm.classId.length = null; 
   addOption(frm.classId, '', 'Select');
   
   var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/ajaxGetSurveyClasses.php';
   
   pars = 'timeTableLabelId='+frm.timeTableLabelId.value;
    
   new Ajax.Request(url,
   {
        method:'post',
        asynchronous:false,
        parameters: pars,
        onCreate: function(){
             showWaitDialog(true);
        },
        onSuccess: function(transport){
            hideWaitDialog(true);
            
            var ret=trim(transport.responseText).split('!~!~!');  
            if(ret.length > 0 ) {
                var j = eval('(' + ret[0] + ')');
                len = j.length;
               
                frm.surveyId.length = null;
                if(len>0) {
                  for(i=0;i<len;i++) { 
                    addOption(frm.surveyId, j[i].feedbackSurveyId, j[i].feedbackSurveyLabel);
                  }
                }
                else {
                  addOption(frm.surveyId, '', 'Select');       
                }
            
                var j = eval('(' + ret[1] + ')');
                len = j.length;
                frm.classId.length = null;
                for(i=0;i<len;i++) {        
                   addOption(frm.classId, j[i].classId, j[i].className);
                }
            }
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function addTimeTableClasses(frm) {
    
     var url = '<?php echo HTTP_LIB_PATH;?>/FeedbackAdvanced/addTimeTableClassMapping.php';
         
     var fieldsArray = new Array(
          new Array("timeTableLabelId","<?php echo SELECT_TIME_TABLE;?>"),
          new Array("surveyId","<?php echo SELECT_SURVEY;?>"),
          new Array("classId","<?php echo SELECT_CLASS;?>")
     );

     var len = fieldsArray.length;
     for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
     }
     
     var ele=document.AddMappingTeacher.classId;
     var len=ele.options.length;
     var classIds='';
     for(var i=0;i<len;i++){
         if(ele.options[i].selected==true){
             if(classIds!=''){
                 classIds +=',';
             }
             classIds +=ele.options[i].value;
         }
     }
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             timeTableLabelId : document.AddMappingTeacher.timeTableLabelId.value,
             surveyId         : document.AddMappingTeacher.surveyId.value,
             classId          : classIds
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText).split('!~~!~~!');  
                 
                 msg = '';
                 if(ret[1]!='') {
                    msg = "These classes are already mapped:\n";
                    msg += ret[1];  
                 }
                 if(ret[0]!='') { 
                    if(msg=='') {
                      messageBox(ret[0]);      
                    }
                    flag = true;
                    hiddenFloatingDiv('AddMappingTeacher');
                    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    //location.reload();
                    return false;
                 }
                 else if("<?php echo FAILURE;?>" == trim(transport.responseText)) {  
                     messageBox(trim(transport.responseText));      
                     document.AddMappingTeacher.classId.focus();
                 }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>"); }
       });
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/FeedbackAdvanced/listTeacherMappingContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
