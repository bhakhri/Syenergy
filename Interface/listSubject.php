<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Subject Form
//
//
// Author :Arvind Singh Rawat
// Created on : 14-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','Subject');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();


//require_once(BL_PATH . "/Subject/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
                               new Array('subjectName','Subject Name ','width="25%"','',true), 
                               new Array('subjectCode','Subject Code','width="15%"','',true), 
                               new Array('subjectAbbreviation','Abbr.','width="10%"','',true), 
                               new Array('subjectTypeName','Subject Type', 'width="12%"','',true), 
                               new Array('categoryName','Subject Category','width="17%"','',true), 
                               new Array('alternateSubjectName','alternate Subject Name','width="17%"','',true),
                               new Array('alternateSubjectCode','alternate Subject Code','width="17%"','',true),
                               new Array('hasAttendance','Attendance','width="10%" align="center"','align="center"',true), 
                               new Array('hasMarks',     'Marks','width="10%" align="center"','align="center"',true), 
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteSubject';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectName';
sortOrderBy    = 'ASC';

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);   
}

//this function validates form
function validateAddForm(frm, act) {
    
   
    var fieldsArray = new Array(new Array("subjectName","<?php echo ENTER_SUBJECT_NAME;?>"),
								new Array("subjectCode","<?php echo ENTER_SUBJECT_CODE;?>"),
								new Array("subjectTypeId","<?php echo SELECT_SUBJECT_TYPE;?>"),
                                new Array("subjectCategoryId","<?php echo SUBJECT_CATEGORY_NAME;?>"));

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
        addSubject();
        return false;
    }
    else if(act=='Edit') {
        editSubject();
        return false;
    }
}
function addSubject() {
    
         url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectName: trim(document.addSubject.subjectName.value), 
                          subjectCode: trim(document.addSubject.subjectCode.value), 
                          subjectAbbreviation: trim(document.addSubject.subjectAbbreviation.value),
                          subjectTypeId: trim(document.addSubject.subjectTypeId.value),
                          subjectCategoryId: trim(document.addSubject.subjectCategoryId.value),
                          alternateSubjectName: (document.addSubject.alternateSubjectName.value),
                          alternateSubjectCode: (document.addSubject.alternateSubjectCode.value),
                          hasAttendance: document.addSubject.hasAttendance.value,
                          hasMarks:document.addSubject.hasMarks.value
                         },
             onSuccess: function(transport){
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
                             hiddenFloatingDiv('AddSubject');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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
   document.addSubject.subjectCode.value = '';
   document.addSubject.subjectName.value = '';
   document.addSubject.subjectAbbreviation.value = '';
   document.addSubject.subjectTypeId.value = '';
   document.addSubject.subjectCategoryId.selectedIndex=0;
   document.addSubject.alternateSubjectName.value = '';
   document.addSubject.alternateSubjectCode.value = '';  
   document.addSubject.hasAttendance.selectedIndex=0;    
   document.addSubject.hasMarks.selectedIndex=0;    
   document.addSubject.subjectName.focus();
}
function editSubject() {

         url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectId: trim(document.editSubject.subjectId.value), 
                          subjectName: trim(document.editSubject.subjectName.value), 
                          subjectCode: trim(document.editSubject.subjectCode.value), 
                          subjectAbbreviation: trim(document.editSubject.subjectAbbreviation.value),
                          subjectTypeId: trim(document.editSubject.subjectTypeId.value),
                          subjectCategoryId: trim(document.editSubject.subjectCategoryId.value),
                          alternateSubjectName: (document.editSubject.alternateSubjectName.value),
                          alternateSubjectCode: (document.editSubject.alternateSubjectCode.value),
                          hasAttendance: document.editSubject.hasAttendance.value,
                          hasMarks:document.editSubject.hasMarks.value
                        },
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
               else {
                  hideWaitDialog(true);
                    // messageBox(trim(transport.responseText));
                  if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('EditSubject');
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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

function deleteSubject(id) {  
         if(false===confirm("Do you want to delete this record?")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectId: id},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                  showWaitDialog(true);
               }
                else {
                     hideWaitDialog(true);
                  //   messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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

     url = '<?php echo HTTP_LIB_PATH;?>/Subject/ajaxGetValues.php';
     document.editSubject.subjectCategoryId.selectedIndex=0;      
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {subjectId: id},
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
               hideWaitDialog(true);
               j = eval('('+transport.responseText+')');
               document.editSubject.subjectCode.value = j.subjectCode;
               document.editSubject.subjectName.value = j.subjectName;
               document.editSubject.subjectAbbreviation.value = j.subjectAbbreviation;
               document.editSubject.subjectId.value = j.subjectId;
               document.editSubject.subjectTypeId.value = j.subjectTypeId;
                document.editSubject.alternateSubjectName.value = j.alternateSubjectName;
                   document.editSubject.alternateSubjectCode.value = j.alternateSubjectCode;
               if(j.subjectCategoryId!=0) {
                 document.editSubject.subjectCategoryId.value = j.subjectCategoryId;
               }
               else {
                 document.editSubject.subjectCategoryId.selectedIndex=0;
               }
               document.editSubject.hasAttendance.value=j.hasAttendance;    
               document.editSubject.hasMarks.value=j.hasMarks;      
           }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

//----------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "universityId/subjectId" select box depending upon which university/subject is selected
//
//Author : Arvind Singh Rawat
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------
//id:id 
//type:universityId/subjectId
//target:taget dropdown box

function autoPopulate(val,type,frm) {       
   
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php'; 
   if(frm=="Add"){
       if(type=="subjectTypeId"){
            document.addSubject.subjectTypeId.options.length=0;
       }
   }
   else{
        if(type=="subjectTypeId"){
            document.editSubject.subjectTypeId.options.length=0;
       }
   }  
      
 new Ajax.Request(url,
           {
             method:'post',
             parameters: {type: type,id: val},
             onSuccess: function(transport){
               if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
                   
                  showWaitDialog(true);
               }
               else {
                    hideWaitDialog(true);
                     j = eval('('+transport.responseText+')'); 
                    
                     for(var c=0;c<j.length;c++){
                         if(frm=="Add"){
                            var objOption = new Option("Select","");
                            document.addSubject.subjectTypeId.options.add(objOption);  
                             if(type=="subjectTypeId"){
                                 objOption = new Option(j[c].subjectTypeName,j[c].subjectTypeId);
                                 document.addSubject.subjectTypeId.options.add(objOption); 
                             }
                           
                          }
                      else{
                            if(type=="subjectTypeId"){
                                var objOption = new Option("Select","");
                                document.addSubject.subjectTypeId.options.add(objOption);  
                                
                                objOption = new Option(j[c].subjectTypeName,j[c].subjectTypeId);
                                document.editSubject.subjectTypeId.options.add(objOption); 
                             }
                           
                          }
                     }
                     
                  }
             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           }); 
}

function getMarkAttendanceShow(val,act) {
   if(act=='Add') {
     if(val==5) {  
       document.addSubject.hasAttendance.selectedIndex =1;    
       document.addSubject.hasMarks.selectedIndex=1;    
     }
     else {
       document.addSubject.hasAttendance.selectedIndex =0;    
       document.addSubject.hasMarks.selectedIndex=0;    
     }
   }
   else
   if(act=='Edit') {
     if(val==5) {  
       document.editSubject.hasAttendance.selectedIndex =1;    
       document.editSubject.hasMarks.selectedIndex=1;    
     }
     else {
       document.editSubject.hasAttendance.selectedIndex =0;    
       document.editSubject.hasMarks.selectedIndex=0;    
     }
   }
}
/* function to print Subject report*/
function printReport() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/subjectReportPrint.php?'+qstr;
    window.open(path,"SubjectReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}


/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    
    path='<?php echo UI_HTTP_PATH;?>/subjectReportCSV.php?'+qstr;
    window.location = path;  
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Subject/listSubjectContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>        
</body>
</html>
<?php 
//$History: listSubject.php $
//
//*****************  Version 16  *****************
//User: Parveen      Date: 9/24/09    Time: 10:57a
//Updated in $/LeapCC/Interface
//alignment & condition format updated
//
//*****************  Version 15  *****************
//User: Parveen      Date: 8/11/09    Time: 2:30p
//Updated in $/LeapCC/Interface
// issue fix 1012, 1011
//validation updated
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/08/09    Time: 2:37p
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:14a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 11  *****************
//User: Parveen      Date: 7/31/09    Time: 2:48p
//Updated in $/LeapCC/Interface
//validation remove (special char condition remove)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/20/09    Time: 2:26p
//Updated in $/LeapCC/Interface
//populate function updated (subjectCategoryId checks added)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/20/09    Time: 1:55p
//Updated in $/LeapCC/Interface
//new enhancement categoryId (link subject_category table) new field
//added 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//validation, formatting, themes base css templates changes
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 6/04/09    Time: 5:57p
//Updated in $/LeapCC/Interface
//take some special characters in adding subject code
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 6/04/09    Time: 4:40p
//Updated in $/LeapCC/Interface
//allowed special characters & subject abbr. blank
//
//*****************  Version 5  *****************
//User: Parveen      Date: 5/07/09    Time: 2:40p
//Updated in $/LeapCC/Interface
//issue fix subject code space allow, sorting format setting
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/27/09    Time: 4:23p
//Updated in $/LeapCC/Interface
//subject code condition update
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 11/12/08   Time: 16:58
//Updated in $/LeapCC/Interface
//Added "Print" and "Export to excell" in subject and subjectType modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 12  *****************
//User: Arvind       Date: 8/19/08    Time: 7:08p
//Updated in $/Leap/Source/Interface
//used common error message for validations
//
//*****************  Version 11  *****************
//User: Arvind       Date: 8/11/08    Time: 3:21p
//Updated in $/Leap/Source/Interface
//modified the addsubject()
//
//*****************  Version 10  *****************
//User: Arvind       Date: 8/09/08    Time: 6:34p
//Updated in $/Leap/Source/Interface
//modified the populatevalues
//
//*****************  Version 9  *****************
//User: Arvind       Date: 8/07/08    Time: 3:24p
//Updated in $/Leap/Source/Interface
//modified the display message
//
//*****************  Version 8  *****************
//User: Arvind       Date: 7/18/08    Time: 2:21p
//Updated in $/Leap/Source/Interface
//added messageBox in place of alert 
//
//*****************  Version 7  *****************
//User: Arvind       Date: 7/11/08    Time: 3:12p
//Updated in $/Leap/Source/Interface
//modified the table widths in dynamci table array
//
//*****************  Version 6  *****************
//User: Arvind       Date: 6/30/08    Time: 5:58p
//Updated in $/Leap/Source/Interface
//added focus on the first field in th blankvalues()  function
//
//*****************  Version 5  *****************
//User: Arvind       Date: 6/30/08    Time: 4:22p
//Updated in $/Leap/Source/Interface
//1) Added a new javascript function which calls table listing through
//ajax and pagination function 
//2) Added a delete funciton which call ajax file to delete
//3) Modifies add and edit funnction.
//    Data saved successfullyand
//   DO you want to add more ?
//  messageBox  is displayed in one messageBox  box
//
//*****************  Version 4  *****************
//User: Arvind       Date: 6/17/08    Time: 2:59p
//Updated in $/Leap/Source/Interface
//modified added new ajax function
//
//*****************  Version 3  *****************
//User: Arvind       Date: 6/16/08    Time: 11:33a
//Updated in $/Leap/Source/Interface
//modification
//
//*****************  Version 1  *****************
//User: Arvind       Date: 6/14/08    Time: 6:23p
//Created in $/Leap/Source/Interface
//first time checkin
?>