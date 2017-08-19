<?php
//-------------------------------------------------------
// Purpose: To generate the list of Subject Topic from the database, and have add/edit/delete, search 
// functionality 
//
// Author : Parveen Sharma
// Created on : 15.01.09
// Copyright 2009-2010: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectTopic');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();

//TO store Module name
$sessionHandler->setSessionVariable('Module',MODULE);
//require_once(BL_PATH . "/SubjectTopic/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Topic Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(
                     new Array('srNo','#','width="3%"','',false), 
                     //new Array('subjectCode','Subject Code','width="15%"','',true) , 
                     new Array('topic','Topic','width="45%"','',true), 
                     new Array('topicAbbr','Abbr.','width="25%"','',true) ,
                     new Array('action1','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitList.php';
searchFormName = 'totalMarksReportForm'; // name of the form which will be used for search
addFormName    = 'AddSubjectTopicDiv';   
editFormName   = 'EditSubjectTopicDiv';
winLayerWidth  = 480; //  add/edit form width
winLayerHeight = 300; // add/edit form height
deleteFunction = 'return deleteSubjectTopic';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy    = 'ASC';
queryString ='';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function editWindow(id,w,h) {
    var dv='EditSubjectTopicDiv';
        displayWindow(dv,w,h);
        populateValues(id);
} 

function showTopicDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateTopicValues(id);
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Parveen Sharma
// Created on : 16.01.09
// Copyright 2009-2010 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateTopicValues(id) {
	     var moduleName = ("<?php echo MODULE;?>");
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxGetValues.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {subjectTopicId: id,mod:moduleName},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divTopic');
                        messageBox("This subject topic record does not exists");
                        //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                          //return false;
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('topicInfo').innerHTML= j.topic;    
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}

function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function validateSearchForm() {
    queryString='';
    hideResults();
    if(document.getElementById("tSubjectId").value=='') {
       messageBox("<?php echo SELECT_SUBJECT;?>");       
       document.getElementById("tSubjectId").focus(); 
       return false;  
    }
	var moduleName = ("<?php echo MODULE;?>");
    queryString =  document.getElementById("tSubjectId").value;
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
    sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
    //sendReq(listURL,divResultName,'listForm','');
    return false;
}

function validateAddForm(frm, act) {
   
    var fieldsArray = new Array(new Array("studentSubject","Select Subject Code"),
                                new Array("subjectTopic","<?php echo ENTER_SUBJECT_TOPIC;?>"),
                                new Array("subjectAbbr","Enter Subject Topic Abbr.") );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            if(!isAlphaNumericCustom(eval("frm."+(fieldsArray[i][0])+".value"),'-+._ ') && fieldsArray[i][0]=='subjectAbbr' ) {
                //winAlert("Enter string",fieldsArray[i][0]);
                messageBox("<?php echo ENTER_ALPHABETS_NUMERIC_ABBR;?>");
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
            //else {
                //unsetAlertStyle(fieldsArray[i][0]);
            //}
        }
     
    }
    if(act=='Add') {
        addSubjectTopic();
        return false;
    }
    else if(act=='Edit') {
        editSubjectTopic();
        return false;
    }
}
function addSubjectTopic() {
	    var moduleName = ("<?php echo MODULE;?>");
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitAdd.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studentSubject: trim(document.addSubjectTopic.studentSubject.value), 
                          subjectTopic: trim(document.addSubjectTopic.subjectTopic.value), 
                          subjectAbbr: trim(document.addSubjectTopic.subjectAbbr.value),mod:moduleName},
             onCreate: function(){
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
                             hiddenFloatingDiv('AddSubjectTopicDiv');
                 sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
               
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function deleteSubjectTopic(id) {
	     var moduleName = ("<?php echo MODULE;?>");
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {   
        
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTopicId: id,mod:moduleName},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
						sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
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
function blankValues() {
   document.addSubjectTopic.studentSubject.value = '';
   document.addSubjectTopic.subjectTopic.value = '';
   document.addSubjectTopic.subjectAbbr.value='';
   document.addSubjectTopic.studentSubject.focus();
}
function editSubjectTopic() { 
	     var moduleName = ("<?php echo MODULE;?>");
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxInitEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
               parameters: { 
                   subjectTopicId: (document.editSubjectTopic.subjectTopicId.value),   
                   studentSubject: trim(document.editSubjectTopic.studentSubject.value), 
                   subjectTopic: trim(document.editSubjectTopic.subjectTopic.value), 
                   subjectAbbr: trim(document.editSubjectTopic.subjectAbbr.value),mod: moduleName}, 
             onCreate: function(){
                 showWaitDialog(true);
             },             
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditSubjectTopicDiv');
						 sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);
                         return false;
                         //location.reload();
                     }
                     else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function populateValues(id) {
	    var moduleName = ("<?php echo MODULE;?>");
         url = '<?php echo HTTP_LIB_PATH;?>/SubjectTopic/ajaxGetValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {subjectTopicId: id,mod: moduleName},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('EditSubjectTopicDiv');
                        messageBox("<?php echo SUBJECT_TOPIC_NOT_EXIST;?>");
            //sendReq(listURL,divResultName,'totalMarksReportForm','page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&mod='+moduleName);                             //return false;
                   }
                   j = eval('('+trim(transport.responseText)+')');
                   document.editSubjectTopic.subjectTopicId.value = j.subjectTopicId;                                      
                   document.editSubjectTopic.studentSubject.value = j.subjectId;
                   document.editSubjectTopic.subjectTopic.value = j.topic;
                   document.editSubjectTopic.subjectAbbr.value=j.topicAbbr;
                   document.editSubjectTopic.studentSubject.focus();
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
    var moduleName = ("<?php echo MODULE;?>");
	queryString =  document.getElementById("tSubjectId").value;
    //var chk=document.searchForm.searchbox.value;
	var qry = "&tSubjectId="+queryString+'&mod='+moduleName;
    path='<?php echo UI_HTTP_PATH;?>/listSubjectTopicPrint.php?sortOrderBy='+sortOrderBy+'&sortField='+sortField+qry;

    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

/* function to print all subject topic to csv*/
function printCSV() {
     var moduleName = ("<?php echo MODULE;?>");
	 queryString =  document.getElementById("tSubjectId").value;
	//var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField+"&tSubjectId="+queryString; 
    path='<?php echo UI_HTTP_PATH;?>/listSubjectTopicPrintCSV.php?'+qstr+'&mod='+moduleName;
    window.location = path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/SubjectTopic/listSubjectTopicContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>    
</body>
</html>
<?php
// $History: listSubjectTopic.php $
//
//*****************  Version 11  *****************
//User: Parveen      Date: 10/20/09   Time: 10:42a
//Updated in $/LeapCC/Interface
//printCSV function updated (search condition paramters added)
//
//*****************  Version 10  *****************
//User: Parveen      Date: 10/20/09   Time: 10:32a
//Updated in $/LeapCC/Interface
//extra space remove (add/edit function)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/06/09    Time: 5:26p
//Updated in $/LeapCC/Interface
//duplicate values & Dependency checks, formatting & conditions updated 
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/05/09    Time: 7:00p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000903, 0000800, 0000802, 0000801, 0000776, 0000775,
//0000776, 0000801, 0000778, 0000777, 0000896, 0000796, 0000720, 0000717,
//0000910, 0000443, 0000442, 0000399, 0000390, 0000373
//
//*****************  Version 7  *****************
//User: Parveen      Date: 6/01/09    Time: 3:22p
//Updated in $/LeapCC/Interface
//formatting & spelling correct
//
//*****************  Version 6  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 5  *****************
//User: Parveen      Date: 2/06/09    Time: 6:01p
//Updated in $/LeapCC/Interface
//issue fix
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/28/09    Time: 11:51a
//Updated in $/LeapCC/Interface
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/20/09    Time: 2:26p
//Updated in $/LeapCC/Interface
//print & csv function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/19/09    Time: 11:24a
//Updated in $/LeapCC/Interface
//bug fix
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/16/09    Time: 2:15p
//Created in $/LeapCC/Interface
//subject topic file added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/16/09    Time: 12:50p
//Updated in $/Leap/Source/Interface
//hyper link update topic list
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/15/09    Time: 6:13p
//Created in $/Leap/Source/Interface
//Subject topic file added
//

?>
