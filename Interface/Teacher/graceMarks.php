<?php
//used for showing class wise grades
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GraceMarksEntry');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();

//if grace marks is not allowed
/*
if($sessionHandler->getSessionVariable('GRACE_MARKS_ALLOWED')!=1){
  redirectBrowser(UI_HTTP_PATH.'/Teacher/index.php?z=1');
}
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Grace Marks</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>

<script language="javascript"> 
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//to stop special formatting
specialFormatting=0;

var tableHeadArray = new Array(
new Array('srNo','S. No.','width="3%"','',false) ,
new Array('studentName','Name','width="12%"','align="left"',true) ,
new Array('rollNo','Roll No.','width="7%"','align="left" style="padding-left:5px;"',true),
new Array('universityRollNo','Univ. Roll No.','width="7%"','align="left" style="padding-left:5px;"',true),
new Array('marksScored','Marks Scored','width="7%"','align="right"',true),
new Array('graceMarks','Grace Marks','width="7%"','align="right"',false),
new Array('newMarks', 'Marks with Grace','width="9%"','align="right"',false),
new Array('maxMarks','Max. Marks','width="7%"','align="right"',false)
);

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGraceMarksList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getData(){
  
    if(document.getElementById('class').value == ""){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('class').focus();
        return false;
    }
   if(document.getElementById('subject').value == ""){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    }
   if(document.getElementById('group').value == ""){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
   }

   
   document.getElementById('graceMarksAll').value='';
   
   sendReq(listURL,divResultName,searchFormName,' ',false);
   if(j.totalRecords >0){
       document.getElementById('buttonRow').style.display='';
       document.getElementById('headingDivId').style.display='';
		 calculateClassAverage(); //function added to calculate class average.
   }
   else{
       document.getElementById('buttonRow').style.display='none';
       document.getElementById('headingDivId').style.display='none';
   }
   return false;  
}

function calculateClassAverage() {
	var eles=document.getElementsByTagName("INPUT");
   var len=eles.length;
	var marksScored = 0;
	var graceMarks = 0;
	var maxMarksScored = 0;
	var hiddenElements = 0;
   for(var i=0;i<len;i++){
    if (eles[i].type.toUpperCase()=='HIDDEN' || (eles[i].type.toUpperCase()=='TEXT' && eles[i].name != 'graceMarksAll')){
		 if (eles[i].name.indexOf('markScored') != -1) {
			if (eles[i].value != '') {
				marksScored += parseInt(eles[i].value);
			}
		 }
		 else if (eles[i].name.indexOf('graceMarks') != -1) {
			if (eles[i].value != '') {
				graceMarks  += parseInt(eles[i].value);
			}
		 }
		 else if (eles[i].name.indexOf('maxMarkScored') != -1) {
			if (eles[i].value != '') {
				maxMarksScored  += parseInt(eles[i].value);
			}
		 }
    }
   }
	var classAverageWithGrace = ((marksScored + graceMarks)*100) / maxMarksScored;
	classAverageWithGrace = Math.round(classAverageWithGrace*100)/100;
	document.getElementById("classAverageSpan").innerHTML = classAverageWithGrace;

	var classAverageWithoutGrace = ((marksScored)*100) / maxMarksScored;
	classAverageWithoutGrace = Math.round(classAverageWithoutGrace * 100)/100;
	document.getElementById("classAverageSpan3").innerHTML = classAverageWithoutGrace;
}
//-----------------------------------------------------------------------------------------------------------
//used to clear data
function clearData(){
    document.getElementById('results').innerHTML='';
    document.getElementById('buttonRow').style.display='none';
    document.getElementById('headingDivId').style.display='none';
    document.getElementById('graceMarksAll').value='';
}


//-----------------------------------------------------------------------------------------------------------
//used to populate grace marks textboxes
function setData(value){
  s = value.toString();
  var fl=0;
  for (var i = 0; i < s.length; i++){
    var c = s.charAt(i); 
    if(!isInteger(c))  {
     document.getElementById('graceMarksAll').value=document.getElementById('graceMarksAll').value.replace(c,"");  
     fl=1;
   } 
  }
  if(fl==1){
     document.getElementById('graceMarksAll').focus();
     return false;
  } 
  var lc=document.listFrm.student.length-2;
  if(lc >1){
    for(var i=0; i < lc; i++){
        document.listFrm.graceMarks[ i ].value=value;
        alertData(i); 
    }  
  }
 else if(lc==1){
     document.listFrm.graceMarks.value= value;
     alertData(0); 
 } 
  return true;
}

//-----------------------------------------------------------------------------------------------------------
//used to alert students
function alertData(i){
    
    val1="graceMarks"+i;      //grace marks
    val2="markScored"+i;      //marks scored
    val3="maxMarkScored"+i;   //max marks
    val4="newMarks"+i;   //max marks
 
    //GIVING INSTANT ALERT IF SOMETHING WRONG IS INPUTTED
    /*
      marks scored+grace marks <= max marks
    */                                 
    (parseInt(trim(document.getElementById(val1).value))+ parseInt(trim(document.getElementById(val2).value)) > parseInt(trim(document.getElementById(val3).value),10)) ? (document.getElementById(val1).className="inputboxRed") : document.getElementById(val1).className="inputbox";

    //check for numeric value
    s = document.getElementById(val1).value.toString();
    var fl=0;
    
    for (var i = 0; i < s.length; i++){
     var c = s.charAt(i); 
     if(!isInteger(c)){
      document.getElementById(val1).value=document.getElementById(val1).value.replace(c,""); 
      fl=1; 
    }
  }
  if(fl==1){
      document.getElementById(val1).focus(); 
      return false;
  }
  //to show updated "new marks"
  if(trim(document.getElementById(val1).value)!='' && trim(document.getElementById(val1).value)!=''){
   document.getElementById(val4).innerHTML=parseInt(trim(document.getElementById(val1).value),10)+parseInt(trim(document.getElementById(val2).value),10)
  }
  else{
      document.getElementById(val4).innerHTML=parseInt(trim(document.getElementById(val2).value),10);
  }
  calculateClassAverage(); //function added to calculate class average.
  return true;  
}

//-----------------------------------------------------------------------------------------------------------
//used to save data
function saveData(){
  
   if(document.getElementById('class').value == ""){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('class').focus();
        return false;
    }
   if(document.getElementById('subject').value == ""){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    }
   if(document.getElementById('group').value == ""){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
   }
   
   var lc=document.listFrm.student.length-2;
   if(lc >1){
    for(var i=0; i < lc; i++){
        if(trim(document.listFrm.graceMarks[ i ].value)==''){
            messageBox("Grace Marks cannot be empty");
            document.listFrm.graceMarks[ i ].focus();
            return false;
        }
        if(parseInt(document.listFrm.graceMarks[ i ].value,10)+parseInt(document.listFrm.markScored[ i ].value,10) >parseInt(document.listFrm.maxMarkScored[ i ].value,10)){
            alertData(i);
            messageBox("<?php echo GRACE_MARKS_VALIDATION; ?>");
            document.listFrm.graceMarks[ i ].focus();
            return false;
        }
    }  
   }
   else if(lc==1){
     
     if(trim(document.listFrm.graceMarks.value)==''){
            messageBox("Grace Marks cannot be empty");
            document.listFrm.graceMarks.focus();
            return false;
     }
        
     if(parseInt(document.listFrm.graceMarks.value,10)+parseInt(document.listFrm.markScored.value,10) >parseInt(document.listFrm.maxMarkScored.value,10)){
          alertData(0);
          messageBox("<?php echo GRACE_MARKS_VALIDATION; ?>");
          document.listFrm.graceMarks.focus();
          return false;
     }
   }
  
  giveGraceMarks();
  return false;     
}

//--------------------------------------------------------------------------------------
//used to save grace marks data

var doubleClickFl=0;
function giveGraceMarks(){
    
    if(doubleClickFl==1){
        messageBox("Another Request is in progress.");
        return false;
    }
    
    var studentIds='';graceMarks='';
    var lc=document.listFrm.student.length-2;
    if(lc >1){
    for(var i=0; i < lc; i++){
        if(studentIds!=''){
           studentIds +=',';   
           graceMarks +=','; 
        }
        studentIds += document.listFrm.students[i].value;
        graceMarks += document.listFrm.graceMarks[i].value !='' ? parseInt(document.listFrm.graceMarks[i].value,10) : 0;
     }  
   }
   else if(lc==1){
     studentIds =document.listFrm.students.value;
     graceMarks = document.listFrm.graceMarks.value !='' ? parseInt(document.listFrm.graceMarks.value,10) : 0;
   }
   

   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/giveGraceMarks.php';
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 studentIds : studentIds,
                 graceMarks : graceMarks,
                 classId    : document.getElementById('class').value,
                 subjectId  : document.getElementById('subject').value,
                 group    : document.getElementById('group').value,
                 rollNo     : trim(document.getElementById('studentRollNo').value)
             },
             onCreate: function(transport){
                  showWaitDialog(true);
                  doubleClickFl=1;
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    doubleClickFl=0;
                    if(trim(transport.responseText)=="<?php echo SUCCESS;?>"){
                        messageBox("<?php echo GRACE_MARKS_GIVEN; ?>");
                    }
                    else{
                      messageBox(trim(transport.responseText));  
                    }
                    clearData();
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
        }); 
}

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO clear rollno when clas,subject or group is changed
//
//Author : Dipanjan Bhattacharjee
// Created on : (07.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function deleteRollNo(){
  // document.searchForm.studentRollNo.value=""; 
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh 
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   
   clearData();
   
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption); 
   
   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId: document.getElementById('class').value
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
                         document.searchForm.group.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}

//to populate subjects based on choosen class
function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
    
    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTransferredSubjectPopulate.php';
    
    if(classId==''){
      return false;
    }
    
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')'); 

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           }); 
}


//used to cleanup page after refresh
window.onload=function(){
    document.searchForm.reset();
    document.getElementById('class').focus();
    var roll = document.getElementById("studentRollNo");
    autoSuggest(roll);
}
</script>

</head>
<body>
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/graceMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");    
?>
</body>
</html>
