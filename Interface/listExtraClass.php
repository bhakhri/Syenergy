<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Subject Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','ExtraClassAttendance');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Extra Class Conducted by Faculty </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
global $sessionHandler;  
 $roleId = $sessionHandler->getSessionVariable('RoleId'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxExtraClassAttendanceList.php';
searchFormName = 'searchExtraForm'; // name of the form which will be used for search
addFormName    = 'AddExtraClass';   
editFormName   = 'EditExtraClass';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteExtraClass';
divResultName  = 'results';
page=1; //default page
sortField = 'fromDate';
sortOrderBy    = 'ASC';
queryString1=''; 

roleId = "<?php echo $roleId; ?>";
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

function refereshData() {
    
    url = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxExtraClassAttendanceList.php';
    var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('fromDate','Date','width="8%" align="center"',true),
                                new Array('className','Class','width="12% align="left"',true), 
                                new Array('subjectCode','Subject','width="10%" align="left"',true), 
                                new Array('groupName','Group','width="8%" align="left"',true),
                                new Array('periodTime','Period','width="10%" align="left"',true),
                                new Array('employeeName','Employee','width="10%" align="left"',true),
                                new Array('substituteEmployee','Substitute For','width="12%" align="left"',true),
                                new Array('comments','Comments','width="15%" align="left"',true),
                                new Array('action1','Action','width="5%" align="center"',false)
                               );
   
        
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'',sortField,sortOrderBy,divResultName,'','',true,'listObj3',tableColumns2,'','','&'+queryString1);
    sendRequest(url, listObj3, '',true );
}

//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

function validateShowList() {
  
   queryString1 =""; 
   if(document.getElementById('searchDateFilter').value!='') {
     if(document.getElementById('searchFromDate').value=='' || document.getElementById('searchToDate').value=='') {
        if(document.getElementById('searchFromDate').value=='') {
           messageBox ("Please Select From Date"); 
           document.getElementById('searchFromDate').focus(); 
           return false;
        }  
        if(document.getElementById('searchToDate').value=='') {
           messageBox ("Please Select To Date"); 
           document.getElementById('searchToDate').focus();
           return false; 
        }  
      }  
        if(!dateDifference(document.getElementById('searchFromDate').value,document.getElementById('searchToDate').value,'-') ) {
           messageBox ("From Date cannot be greater than To Date"); 
           document.getElementById('searchFromDate').focus();  
           return false;
        } 
   }
    
   if(roleId !=2 ) {  
      if(document.searchExtraForm.timeTableLabelId.value=='') {
        messageBox ("Please Select Time Table"); 
        document.searchExtraForm.timeTableLabelId.focus();
        return false;   
      }
   }
   queryString1 = generateQueryString('searchExtraForm');         
   page=1;  
   refereshData();      
   return false;
}

//this function validates form
function validateAddForm(frm, act) {
    
    if(act=='Add') {   
      datechk = "forDate";  
    }
    else {
      datechk = "forDate1";  
    }
    
    if(roleId !=2 ) {       
        var fieldsArray = new Array(new Array("timeTableLabelId","<?php echo SELECT_TIME_TABLE;?>"),
                                    new Array("substituteEmployeeId","Select Substitute For"),
	 	                            new Array("employeeId","Select Employee Name"),
				                    new Array(datechk,"<?php echo SELECT_DATE;?>"),
                                    new Array("classId","<?php echo SELECT_CLASS;?>"),
				                    new Array("subjectId","<?php echo SELECT_SUBJECTS;?>"),
				                    new Array("groupId","<?php echo SELECT_GROUP;?>"),
				                    new Array("periodId","<?php echo SELECT_PERIOD;?>"));
    }
    else {
      var fieldsArray = new Array(new Array(datechk,"<?php echo SELECT_DATE;?>"),
                                  new Array("classId","<?php echo SELECT_CLASS;?>"),
                                  new Array("subjectId","<?php echo SELECT_SUBJECT;?>"),
                                  new Array("groupId","<?php echo SELECT_GROUP;?>"),
                                  new Array("periodId","<?php echo SELECT_PERIOD;?>"));  
    }
    var len = fieldsArray.length;
    
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }

    if(act=='Add') {
        addExtraClass();
        return false;
    }
    else if(act=='Edit') {
        editExtraClass();
        return false;
    }
}
    
function addExtraClass() {
    
     url = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxInitAdd.php';
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: $('#searchForm').serialize(true),  
         onSuccess: function(transport) {
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
                 hideWaitDialog(true);
                 
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     flag = true;
                     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                         blankValues();
                     }
                     else {
                         hiddenFloatingDiv('AddExtraClass');
                         refereshData();
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });
}

function blankValues() {
   
   try { 
       form = document.searchForm;  
       
       form.forDate.value = "<?php echo date('Y-m-d'); ?>";
       
       form.timeTableLabelId.value = document.getElementById('hiddenActiveTimeTableId').value; 
       
       //form.employeeId.length = null;
       //addOption(form.employeeId, '', 'Select');
       form.employeeId.selectedIndex=0;   
       
       form.substituteEmployeeId.length = null;
       addOption(form.substituteEmployeeId, '', 'Select');
       
       form.classId.length = null;
       addOption(form.classId, '', 'Select');
       
       form.subjectId.length = null;
       addOption(form.subjectId, '', 'Select');
       
       form.groupId.length = null;
       addOption(form.groupId, '', 'Select');
       
       form.periodId.selectedIndex=0;   
       
       form.commentTxt.value='';
       
       autoPopulateEmployee(form.timeTableLabelId.value,'add');
       //autoPopulateEmployee(this.value,'add');
       
       getEmployeeName();
       
       form.employeeId.focus();
       
       
   } catch(e){  }
   
}


function editExtraClass() {

     url = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxInitEdit.php';
     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: $('#editExtraClass').serialize(true),  
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
              hideWaitDialog(true);
                // messageBox(trim(transport.responseText));
              if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                 hiddenFloatingDiv('EditExtraClass');
                 refereshData();
                 return false;
                     //location.reload();
              }
              else {
                messageBox(trim(transport.responseText));
              }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
     });
}

function deleteExtraClass(id) {  
        
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else {   
         url = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxInitDelete.php';
         new Ajax.Request(url,
         { method:'post',
           parameters: {extraAttendanceId: id},
           onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
                 hideWaitDialog(true);
              //   messageBox(trim(transport.responseText));
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     refereshData();   
                     return false;
                 }
                  else {
                     messageBox(trim(transport.responseText));
                 }
           
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
     }    
}

function populateValues(id) {

     url = '<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxGetValues.php';
     
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {extraAttendanceId: id},
         asynchronous:false,
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
               hideWaitDialog(true);
               jjEdit = eval('('+transport.responseText+')');
               
               document.editExtraClass.extraId.value = jjEdit.extraAttendanceId;
               document.editExtraClass.timeTableLabelId.value = jjEdit.timeTableLabelId;
               document.editExtraClass.forDate1.value = jjEdit.fromDate;   
               
               autoPopulateEmployee(1,'edit');  
               document.editExtraClass.substituteEmployeeId.value = jjEdit.substituteEmployeeId;
               
               document.editExtraClass.employeeId.value = jjEdit.employeeId;
               
               getClassData('edit');
               document.editExtraClass.classId.value = jjEdit.classId;
               
               populateSubjects(1,'edit');
               document.editExtraClass.subjectId.value = jjEdit.subjectId;
               
               groupPopulate('edit');
               document.editExtraClass.groupId.value = jjEdit.groupId;
               
               document.editExtraClass.periodId.value = jjEdit.periodId;
               
               document.editExtraClass.commentTxt.value = jjEdit.comments;
           }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function autoPopulateEmployee(timeTableLabelId, frm){
    
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxPopulateValues.php';

    if(frm=='add') {
      form = document.searchForm;    
    }
    else {
      form = document.editExtraClass;      
    }
     
    form.substituteEmployeeId.length = null;
    addOption(form.substituteEmployeeId, '', 'Select');
   
    form.classId.length = null;
    addOption(form.classId, '', 'Select');
   
    form.subjectId.length = null;
    addOption(form.subjectId, '', 'Select');
   
    form.groupId.length = null;
    addOption(form.groupId, '', 'Select');
   
    
    if(roleId!=2){
        if(frm=='add') {
          timeTableLabelId = document.searchForm.timeTableLabelId.value;
        }
        else {
          timeTableLabelId = document.editExtraClass.timeTableLabelId.value;
        }
        if(timeTableLabelId==''){
         return false;
        }
    }
    
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             timeTableLabelId: timeTableLabelId,
             val : 'E',
             viewType : frm
         },
         asynchronous:false,
         onCreate: function(transport){
              showWaitDialog();
         },
         onSuccess: function(transport){
                hideWaitDialog();
                j = eval('('+transport.responseText+')');
                for(var c=0;c<j.length;c++){                                    
                  
                  if(frm=='add') {        
                    var objOption = new Option(j[c].employeeName,j[c].employeeId);
                    document.searchForm.substituteEmployeeId.options.add(objOption);
                  }
                  else if(frm=='edit') {   
                    var objOption = new Option(j[c].employeeName,j[c].employeeId);
                    document.editExtraClass.substituteEmployeeId.options.add(objOption);
                  }
                  else {    
                    var objOption = new Option(j[c].employeeName,j[c].employeeId);
                    document.searchExtraForm.substituteEmployeeId.options.add(objOption);
                  }
               }
         },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
       });
}


function getClassData(frm){
  
      var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxPopulateValues.php';
      
      if(frm=='add') {
        form = document.searchForm;    
        startDate = document.getElementById('forDate').value;
        endDate   = document.getElementById('forDate').value;
      }
      else if(frm=='edit') {
        form = document.editExtraClass;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
      }
      else {
        form = document.searchExtraForm;      
        startDate = document.getElementById('forDate2').value;
        endDate   = document.getElementById('forDate2').value;
      }
            
      form.classId.length = null;
      addOption(form.classId, '', 'Select');
       
      form.subjectId.length = null;
      addOption(form.subjectId, '', 'Select');
       
      form.groupId.length = null;
      addOption(form.groupId, '', 'Select');
       
      
      if(roleId!=2){
        if(frm=='add') {
            timeTableLabelId = document.searchForm.timeTableLabelId.value;
            substituteEmployeeId   = document.searchForm.substituteEmployeeId.value;   
        }
        else if(frm=='edit') {
          timeTableLabelId = document.editExtraClass.timeTableLabelId.value;
          substituteEmployeeId = document.editExtraClass.substituteEmployeeId.value;   
        }
        else  {
          timeTableLabelId = document.searchExtraForm.timeTableLabelId.value;
          substituteEmployeeId   =  document.searchExtraForm.substituteEmployeeId.value;           
          
        }
      }
     
      if(startDate=='') {
         return false; 
      }
     
      new Ajax.Request(url,
      {
             method:'post',
             asynchronous:false,
             parameters: {
                 startDate : startDate,
                 endDate   : endDate,
                 timeTableLabelId : timeTableLabelId,
                 substituteEmployeeId : substituteEmployeeId,
                 viewType : frm,
                 val : 'C',
                 moduleName : "<?php echo MODULE; ?>"
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')');
                    for(var c=0;c<j.length;c++){
                       if(frm=='add') { 
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.searchForm.classId.options.add(objOption);  
                       }
                       else if(frm=='edit') {   
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.editExtraClass.classId.options.add(objOption);  
                       }
                       else {
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.searchExtraForm.classId.options.add(objOption);
                      }
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}



function populateSubjects(classId,frm){
  
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxPopulateValues.php';
  
    var timeTableLabelId='';
    var employeeId='';
      if(frm=='add') {
        form = document.searchForm;    
        startDate = document.getElementById('forDate').value;
        endDate   = document.getElementById('forDate').value;
      }
      else if(frm=='edit') { 
        form = document.editExtraClass;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
      }
      else  { 
        form = document.searchExtraForm;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
      }
    
    
    if(roleId!=2){
      if(frm=='add') {
        timeTableLabelId = document.searchForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchForm.substituteEmployeeId.value;
      }
      else if(frm=='edit') { 
        timeTableLabelId = document.editExtraClass.timeTableLabelId.value;
        substituteEmployeeId   = document.editExtraClass.substituteEmployeeId.value;
      }
      else { 
        timeTableLabelId = document.searchExtraForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchExtraForm.substituteEmployeeId.value;
      }
      
    }
    
    
    
    if(frm=='add') {
      form = document.searchForm;    
    }
    else if(frm=='edit') {  
      form = document.editExtraClass;      
    }
    else {
       form = document.searchExtraForm;        
    }
    
    classId = form.classId.value;    
     
    form.groupId.length = null;
    addOption(form.groupId, '', 'Select');
   
    
    if(classId==''){
       return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId             : classId,
                 startDate           : startDate,
                 endDate             : endDate,
                 attendanceType      : 2,
                 timeTableLabelId    : timeTableLabelId,
                 substituteEmployeeId  : substituteEmployeeId,
                 val : 'S',
                 viewType : frm,
                 moduleName : "<?php echo MODULE; ?>"
             },

             onCreate: function(transport){
                  showWaitDialog();
             },   
             onSuccess: function(transport){
                hideWaitDialog();
                j = eval('('+transport.responseText+')');

                for(var c=0;c<j.length;c++){
                    var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                    if(frm=='add') {
                      document.searchForm.subjectId.options.add(objOption);
                    }
                    else if(frm=='edit') {   
                      document.editExtraClass.subjectId.options.add(objOption); 
                    }
                    else  {   
                      document.searchExtraForm.subjectId.options.add(objOption); 
                    }
                }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function getPeriodNames(frm) {
    
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxPopulateValues.php';
    var timeTableLabelId='';
    var employeeId='';
    if(frm=='add') {
        form = document.searchForm;    
        startDate = document.getElementById('forDate').value;
        endDate   = document.getElementById('forDate').value;
    }
    else if(frm=='edit') { 
        form = document.editExtraClass;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
    }
    else  { 
        form = document.searchExtraForm;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
    }
    
    if(roleId!=2){
      if(frm=='add') {
        timeTableLabelId = document.searchForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchForm.substituteEmployeeId.value;
      }
      else if(frm=='edit') { 
        timeTableLabelId = document.editExtraClass.timeTableLabelId.value;
        substituteEmployeeId   = document.editExtraClass.substituteEmployeeId.value;
      }
      else { 
        timeTableLabelId = document.searchExtraForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchExtraForm.substituteEmployeeId.value;
      }
    }
    
    classId = form.classId.value;    
    
    subjectId = form.subjectId.value;  
    
    groupId = form.groupId.value;   
     
   
    
       new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                forDate:startDate,
                classId:classId,
                subjectId:subjectId,
                groupId:groupId,
                startDate : startDate,
                endDate   : endDate,
                viewType : frm,
                val : 'P',
                timeTableLabelId : timeTableLabelId,
                substituteEmployeeId : substituteEmployeeId,
                moduleName : "<?php echo MODULE; ?>"
             },
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+trim(transport.responseText)+')');
                    var l=j.length;
                    if(l >0){
                      var ttPeriodSlotId = ''; 
                      for(var i=0; i < l ; i++){
                        var bg = bg =='trow0' ? 'trow1' : 'trow0';    
                        var periodSlotId = j[i].periodSlotId;  
                        var showPeriod = "<span class='"+bg+"'>"+j[i].periodNumber+' ( '+j[i].periodTime+' )'+"</span>";
                        
                        var objOption = new Option(showPeriod,j[i].periodId);
                        
                        if(frm=='add') {
                          document.searchForm.periodId.options.add(objOption);
                        }
                        else if(frm=='edit') {  
                          document.editExtraClass.periodId.options.add(objOption); 
                        }
                        else  {  
                           document.searchExtraForm.periodId.options.add(objOption);  
                        }
                      }
                    }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }
function groupPopulate(frm) {
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxPopulateValues.php';
    var timeTableLabelId='';
    var employeeId='';
    if(frm=='add') {
        form = document.searchForm;    
        startDate = document.getElementById('forDate').value;
        endDate   = document.getElementById('forDate').value;
    }
    else if(frm=='edit') { 
        form = document.editExtraClass;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
    }
    else  { 
        form = document.searchExtraForm;      
        startDate = document.getElementById('forDate1').value;
        endDate   = document.getElementById('forDate1').value;
    }
    
    if(roleId!=2){
      if(frm=='add') {
        timeTableLabelId = document.searchForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchForm.substituteEmployeeId.value;
      }
      else if(frm=='edit') { 
        timeTableLabelId = document.editExtraClass.timeTableLabelId.value;
        substituteEmployeeId   = document.editExtraClass.substituteEmployeeId.value;
      }
      else { 
        timeTableLabelId = document.searchExtraForm.timeTableLabelId.value;
        substituteEmployeeId   = document.searchExtraForm.substituteEmployeeId.value;
      }
    }
    
    
    
      
    classId = form.classId.value;    
    
    subjectId = form.subjectId.value;  
    
    
    form.groupId.length = null;
    addOption(form.groupId, '', 'Select');
   
     new Ajax.Request(url,
     {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: subjectId,
                 classId  : classId,
                 startDate : startDate,
                 endDate   : endDate,
                 timeTableLabelId : timeTableLabelId,
                 substituteEmployeeId : substituteEmployeeId,
                 val : 'G', 
                 viewType : frm,
                 moduleName : "<?php echo MODULE; ?>"
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

					 var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
						 var objOption = new Option(j[c].groupName,j[c].groupId);
                         if(frm=='add') {
                           document.searchForm.groupId.options.add(objOption);
                         }
                         else if(frm=='edit') {  
                           document.editExtraClass.groupId.options.add(objOption);  
                         }
                         else  {  
                           document.searchExtraForm.groupId.options.add(objOption);  
                         }
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
        });
}

/* function to print Subject report*/
function printReport() {
    
    var qstr = "sortOrderBy="+sortOrderBy+"&sortField="+sortField+'&'+queryString1;
    path='<?php echo UI_HTTP_PATH;?>/listExtraClassPrint.php?'+qstr;
    window.open(path,"ExtraClassReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
  
    //var qstr="searchbox="+document.searchForm.searchbox.value;
    var qstr = "sortOrderBy="+sortOrderBy+"&sortField="+sortField+'&'+queryString1; 
    path='<?php echo UI_HTTP_PATH;?>/listExtraClassCSV.php?'+qstr;
    window.location = path;  
}

function getCopyEmployee(frm) {
    
   if(frm=='add') {
     form = document.searchForm;    
   }
   else if(frm=='edit') { 
     form = document.editExtraClass;      
   }
   else  { 
     form = document.searchExtraForm;      
   }
   
   form.substituteEmployeeId.length = null; 
   addOption(form.substituteEmployeeId, '', '');  
   
  
   var len= form.employeeId.options.length;
   var t=form.employeeId;
   
   if(len>0) {
      for(k=1;k<len;k++) { 
         if(t.options[k].value!=form.employeeId.value) {       
           addOption(form.substituteEmployeeId, t.options[k].value,  t.options[k].text);
         }
      } 
   }
}

function getDateClear(str) {
    
   if(str=='') {
      document.getElementById('searchFromDate').value = '';  
      document.getElementById('searchToDate').value = ''; 
      document.getElementById('lblDt1').style.display='none';
      document.getElementById('lblDt2').style.display='none';
      document.getElementById('lblDt3').style.display='none';
      document.getElementById('lblDt4').style.display='none';
   } 
   else {
      document.getElementById('searchFromDate').value = '';  
      document.getElementById('searchToDate').value = '';   
      document.getElementById('lblDt1').style.display='';
      document.getElementById('lblDt2').style.display='';
      document.getElementById('lblDt3').style.display='';
      document.getElementById('lblDt4').style.display='';
   }      
}

function getDateLeftClear(str) {
    
   if(str=='1') {
      document.getElementById('searchLeftFromDate').value = '';  
      document.getElementById('searchLeftToDate').value = ''; 
      document.getElementById('lblLeftStaff').style.display='none';
   } 
   else {
      document.getElementById('searchLeftFromDate').value = '';  
      document.getElementById('searchLeftToDate').value = ''; 
      document.getElementById('lblLeftStaff').style.display='';
   }
}

// Function Mentioned 
window.onload=function() {   
   getSearchValue('E'); 
   //getSearchValue('all');  
   document.getElementById('hiddenActiveTimeTableId').value =  document.searchExtraForm.timeTableLabelId.value; 
   queryString1 = generateQueryString('searchExtraForm');
   refereshData();
}

function getSearchValue(str) {
   
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxExtraSearchValues.php';     
    
    
    param = generateQueryString('searchExtraForm')+"&valType="+str;   
    new Ajax.Request(url,
    {
      method:'post',
      asynchronous:false,
      parameters: param, 
      onCreate: function() {
          showWaitDialog(true);
      },
      onSuccess: function(transport){
        hideWaitDialog(true);
         
        var ret=trim(transport.responseText).split('~~');
            
        var j0 = eval(ret[0]);
        var j1 = eval(ret[1]);
        var j2= eval(ret[2]);
        var j3 = eval(ret[3]);
        var j4= eval(ret[4]);
        
         
        // add option Select initially
        if(roleId!=2){  
            if(str=='all' || str=='E') { 
              document.searchExtraForm.substituteEmployeeId.length = null; 
              if(j0.length>0) {
                addOption(document.searchExtraForm.substituteEmployeeId,'', 'All');      
              }
              else {
                addOption(document.searchExtraForm.substituteEmployeeId, '', 'Select');        
              }
              for(i=0;i<j0.length;i++) { 
                addOption(document.searchExtraForm.substituteEmployeeId, j0[i].employeeId, j0[i].employeeName);
              }
              str='all';
            }
        }
        
        if(str=='all' || str=='C') { 
          document.searchExtraForm.classId.length = null;    
          if(j1.length>0) {
            addOption(document.searchExtraForm.classId,'', 'All');      
          }
          else {
            addOption(document.searchExtraForm.classId, '', 'Select');        
          }
          for(i=0;i<j1.length;i++) { 
            addOption(document.searchExtraForm.classId, j1[i].classId, j1[i].className);
          }
          str='all';
        }
        
        if(str=='all' || str=='S') { 
          document.searchExtraForm.subjectId.length = null;    
          if(j2.length>0) {
            addOption(document.searchExtraForm.subjectId,'', 'All');      
          }
          else {
            addOption(document.searchExtraForm.subjectId, '', 'Select');        
          }
          for(i=0;i<j2.length;i++) { 
            addOption(document.searchExtraForm.subjectId, j2[i].subjectId, j2[i].subjectCode);
          }
          str='all';
        }
        
        if(str=='all' || str=='G') { 
          document.searchExtraForm.groupId.length = null;    
          if(j3.length>0) {
            addOption(document.searchExtraForm.groupId, '', 'All');      
          }
          else {
            addOption(document.searchExtraForm.groupId, '', 'Select');        
          }
          for(i=0;i<j3.length;i++) { 
             addOption(document.searchExtraForm.groupId, j3[i].groupId, j3[i].groupName);
          }
          str='all';
        }
       
    },
    onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });   
}


function getEmployeeName() {
    
    var url ='<?php echo HTTP_LIB_PATH;?>/ExtraClassesAttendance/ajaxGetEmployeeName.php';

    form = document.searchForm; 
    
    form.employeeId.length = null;
    addOption(form.employeeId, '', 'Select');
   
    showEmployee='';
    if(document.searchForm.showEmployeeName[0].checked==true) {
      showEmployee='1';
    }
    else if(document.searchForm.showEmployeeName[1].checked==true) {
      showEmployee='2';
    }
     
    new Ajax.Request(url,
    {
     method:'post',
     parameters: { showEmployee: showEmployee },
     asynchronous:false,
     onCreate: function(transport){
          showWaitDialog();
     },
     onSuccess: function(transport){
        hideWaitDialog();
        j = eval('('+transport.responseText+')');
        
        form.employeeId.length = null;
        addOption(form.employeeId, '', 'Select');
        for(var c=0;c<j.length;c++){                                    
          addOption(document.searchForm.employeeId, j[c].employeeId, j[c].employeeName1);  
       }
     },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}

</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/listExtraClassContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>        
</body>
</html>
