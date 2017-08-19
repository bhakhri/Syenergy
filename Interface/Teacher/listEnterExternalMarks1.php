<?php
//---------------------------------------------------------------------------
//  THIS FILE used for showing list of messages to parents and students
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','MannualExternalMarks');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Enter External Marks </title>
<style>
.redClass{
    background-color: #FF8888;
}
</style>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//to stop special formatting
specialFormatting=0;

var topPos = 0;
var leftPos = 0;
var previousMarks =0;

  var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false),
								 new Array('universityRollNo','Univ. Roll No.','width="15%"','',true),
                                 new Array('rollNo','Roll No.','width="10%"','',true),
                                 new Array('studentName','Name','width="25%"','',true) ,
                                 new Array('externalMarks','Marks','width="20%"','',false)
                                );

//recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
recordsPerPage =1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxEnterMarksList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//stores the max index of a test
gtestIndex=0;

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
function editWindow(id,dv,w,h) {
    document.frmComment.teacherComment.value="";
    document.frmComment.teacherComment.value=document.getElementById(id).value;
    displayWindow(dv,w,h);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO HIDE TEXT ON CLICK IF IT IS 0
// Created on : (4/15/2011)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hideText(id){
	if(document.getElementById(id).value == 0){
		document.getElementById(id).value='';
	}
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO ENTER IF IT IS 0 & to verify the data of text box
// Created on : (4/15/2011)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getSavedTextBoxData(id){
	if(document.getElementById(id).value == ''){
		document.getElementById(id).value=0;
	}
	if(parseInt(document.getElementById(id).value) > parseInt(document.getElementById('maxMarks').value)){
		document.getElementById(id).style.border="1px solid red";
	}
	else{
		document.getElementById(id).style.border="1px solid yellow";
	}
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO ENTER IF IT IS 0
// Created on : (4/15/2011)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getTextBoxData(id){
	if(document.getElementById(id).value == ''){
		document.getElementById(id).value=0;
	}
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    setGlobalEditFlag(0);
    if(mode==2){
     document.getElementById(id).style.display='none';
     document.getElementById('divButton1').style.display = 'none';
    }
    else{
        document.getElementById(id).style.display='block';
        document.getElementById('divButton1').style.display = '';
    }
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO reset form(top)
//
//Author : Dipanjan Bhattacharjee
// Created on : (0508.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function resetForm(){
 document.getElementById('imageField1').style.display='block';
 document.getElementById('class').selectedIndex=0;
 document.getElementById('subject').selectedIndex=0;
 document.getElementById('group').selectedIndex=0;
 document.getElementById('testType').selectedIndex=0;
 //document.getElementById('test').selectedIndex=0;
 //document.getElementById('test').options.length=2;
 document.getElementById('testType').options.length=1;
 document.getElementById('class').focus();
}



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var sclass="";var ssubject="";var sgroup="";
function fetchData(){
  sendReq(listURL,divResultName,searchFormName,'',false);
}
function getData(){
  
    document.getElementById('showList').style.display='none';         
    
    if(document.getElementById('class').value=='') {
       messageBox("Select Class");
       document.getElementById('class').focus();
       return false; 
    }
    
    if(document.getElementById('subject').value=='') {
       messageBox("Select Subject");
       document.getElementById('subject').focus();
       return false; 
    }
    
    if(document.getElementById('group').value=='') {
       messageBox("Select Group");
       document.getElementById('group').focus();
       return false; 
    }
    
    /*
    if(document.getElementById('testType').value=='') {
       messageBox("Select Test Type");
       document.getElementById('testType').focus();
       return false; 
    }
    */
    
    sendReq(listURL,divResultName,searchFormName,'',false);  
    document.getElementById('maxMarks').value=document.getElementById('max').value;   
    document.getElementById('showList').style.display=''; 
    document.getElementById('divButton1').style.display='';
}


//-----------------------------------------------------------------------------------
//Purpose:to make marks=0 and readonly when present is not checked
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function disableMarks(id){
    setGlobalEditFlag(1);
    var ele=document.getElementById("imarks"+id);
    if(document.getElementById("ipre"+id).checked){
      document.getElementById("imarks"+id).readOnly=false;
      //ele.parentNode.parentNode.className=ele.parentNode.parentNode.value;
      ele.parentNode.parentNode.className='';
    }
   else{
       document.getElementById("imarks"+id).readOnly=true;
       document.getElementById("imarks"+id).value=0;
       ele.parentNode.parentNode.className='redClass';
       ele.parentNode.parentNode.setAttribute('value','redClass');
   }
}

//-----------------------------------------------------------------------------------
//Purpose:to validate form inputs
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateForm(){

   
    if(document.getElementById('class').value==""){
          messageBox("Select Class");
          document.getElementById('class').focus();
          return false;
    }
    if(document.getElementById('subject').value==""){
          messageBox("Select Subject");
          document.getElementById('subject').focus();
          return false;
    }
    
    if(trim(document.getElementById('group').value)==""){
          messageBox("Select Group");
          document.getElementById('group').focus();
          return false;
    }
    
    /*
    if(trim(document.getElementById('testType').value)==""){
          messageBox("Select Test Type");
          document.getElementById('testType').focus();
          return false;
    }
    */
    
    if(trim(document.getElementById('maxMarks').value)==""){
          messageBox("<?php echo ENTER_MAX_MARK; ?>");
          document.getElementById('maxMarks').focus();
          return false;
    }
    if(!isNumeric(trim(document.getElementById('maxMarks').value))){
          messageBox("<?php echo ENTER_NUMERIC_VALUE; ?>");
          document.getElementById('maxMarks').focus();
          return false;
    }
    if(parseInt(trim(document.getElementById('maxMarks').value),10)<0){
          messageBox("<?php echo NEGATIVE_MARKS_NOT_ALLOWED; ?>");
          document.getElementById('maxMarks').focus();
          return false;
    }
    
    formx = document.searchForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
        if(obj[i].type.toUpperCase()=='HIDDEN' && obj[i].name.indexOf('stu[]')>-1) {
           // blank value check 
           id =obj[i].value;
           
           // Integer Value Checks updated
           if(trim(eval("document.getElementById('extMks"+id+"').value"))!='') {
               if(!isDecimal(trim(eval("document.getElementById('extMks"+id+"').value")))) {                          
                 messageBox ("Enter numeric value marks");
                 eval("document.getElementById('extMks"+id+"').className='inputboxRed'");   
                 eval("document.getElementById('extMks"+id+"').focus()");  
                 return false;
               }
               
               mval = trim(eval("document.getElementById('extMks"+id+"').value"));
               maxval = trim(eval("document.getElementById('maxMarks').value"));
               
               if(parseFloat(mval,10)>parseFloat(maxval,3)) {
                 messageBox ("External Marks value cannot be greater than Max Marks Value");
                 eval("document.getElementById('extMks"+id+"').className='inputboxRed'");   
                 eval("document.getElementById('extMks').focus()");  
                 return false; 
               }
           }
        }
    }
       
  
   var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxExternalMarks.php';   
   params = generateQueryString('searchForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     asynchronous:false,
     onCreate: function () {
        showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        messageBox(trim(transport.responseText));  
        if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
           blankValues();
           return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
  
    return false;

}






//-----------------------------------------------------------------------------------
//Purpose:to populate test_type dropdown upon selection of subject dropdown
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTestType(id) {

    if(trim(document.getElementById('class').value)==""){
      messageBox("<?php echo SELECT_CLASS; ?>");
      document.getElementById('subject').selectedIndex=0;
      document.getElementById('class').focus();
      return false;
    }

    document.searchForm.testType.options.length=0;
    var objOption = new Option("SELECT","");
    document.searchForm.testType.options.add(objOption);

    if(id==""){
        return false;
    }

     var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestType.php';
     new Ajax.Request(url,
   {
     method:'post',
     parameters: {subjectId: id,
                  classId:document.getElementById('class').value,
                  conductingAuthority : 1    //testType:1 means that it is internal(conductingAuthority:1)
                 },
      onCreate: function() {
                 showWaitDialog();
      },
      onSuccess: function(transport){
           hideWaitDialog();
            // alert(transport.responseText);
            j = eval('('+trim(transport.responseText)+')');
            var i=0;
            for(var i=0; i< j.length; i++){
             var objOption = new Option(j[i].testTypeAbbr,j[i].testTypeId);
             document.searchForm.testType.options.add(objOption);
            }
     },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}


//-----------------------------------------------------------------------------------
//Purpose:to populate test dropdown upon selection of test_type dropdown
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTest(id,mode) {



 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxGetTest.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {testTypeId: id,
                  subjectId:(document.getElementById('subject').value),
                  classId:(document.getElementById('class').value) ,
                  groupId:(document.getElementById('group').value)
                 },
     onCreate: function() {
                 showWaitDialog();
     },
     onSuccess: function(transport){
          hideWaitDialog();
            // alert(transport.responseText);
            j = eval('('+trim(transport.responseText)+')');
            var i=0;
            for(var i=0; i< j.length; i++){
             var objOption = new Option(j[i].testAbbr+"-"+j[i].testIndex,j[i].testId);
             document.searchForm.test.options.add(objOption);
            }
           if(mode==2){//if we want to show the lates created "test" as selected
            if(oldVal=="NT"){
              document.searchForm.test.selectedIndex=2;  //because "Select" and "New Test" will occupy first two indexes
            }
           else{
              document.searchForm.test.value=oldVal; //if editing of old "test" is done
            }
           }
    },
     onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}



//-----------------------------------------------------------------------------------
//Purpose:to populate test detail information upon selection of test dropdown
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTestDetails(id) {
 previousMarks=0;
//document.getElementById('testDesc').style.display='block';
hideTestDetails(true);

hide_div('showList',2);
if(id==""){
 //document.getElementById('testDesc').style.display='none';
 hideTestDetails(false);
 //first show list button
 document.getElementById('imageField1').style.display='block';
 return false;
}
//first show list button
document.getElementById('imageField1').style.display='none';

if(id=="NT"){ //for new Test
 var d=new Date(); //calculate current date
 var curDate=d.getFullYear()+"-"+((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1))+"-"+((d.getDate())<10?"0"+(d.getDate()):(d.getDate()));

 document.getElementById('testAbbr').value="";
 document.getElementById('maxMarks').value="";
 document.getElementById('testDate').value=curDate;
 document.getElementById('testTopic').value="";
 document.getElementById('comments').value="";
 //document.getElementById('testIndex').value=(document.getElementById('test').options.length-2)+1;

 //calls this function to set the max index of the new test to be created
 getMaxTestIndex(document.getElementById('testType').value);
 document.getElementById('testIndex').value=gtestIndex;
 document.getElementById('testAbbr').focus();
 return false;
}

 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestDetails.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {testId: id},
     onCreate: function() {
                 showWaitDialog();
     },
     onSuccess: function(transport){
           hideWaitDialog();
            // alert(transport.responseText);
            j = eval('('+trim(transport.responseText)+')');
            document.getElementById('testAbbr').value=j.testAbbr;
            document.getElementById('maxMarks').value=j.maxMarks;
			previousMarks = j.maxMarks;
            document.getElementById('testDate').value=j.testDate;
            document.getElementById('testTopic').value=j.testTopic;
            document.getElementById('testIndex').value=j.testIndex;
			document.getElementById('comments').value=j.comments;
            document.getElementById('testAbbr').focus();
     },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}
//-----------------------------------------------------------------------------------
//Purpose:to check whether the marks are new or old
//Date:4/18/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function checkMarks(id){

		marks = parseInt(document.getElementById('maxMarks').value);
		if(marks != '' && previousMarks !=''){
			if(false===confirm("Do you want to change marks")){
				document.getElementById('maxMarks').value = previousMarks;
			}
			else{
				validateMarks(marks);
				return false;
			}
		}
}
//-----------------------------------------------------------------------------------
//Purpose:to check whether the marks are new or old
//Date:4/18/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
/*
function checkData(id){
		marks = parseInt(document.getElementById('maxMarks').value);
		if(previousMarks != marks){
			if(marks != '' && previousMarks !='' && previousMarks !=0 ){
					validateMarks(marks);
					return false;
			}
		}
}
*/
//-----------------------------------------------------------------------------------
//Purpose:to validate the marks
//Date:4/18/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function validateMarks(marks){
	 $$('input[id*="imarks"]').each(function(a){
		 if(parseInt(a.value) > parseInt(marks)){
			a.style.border="1px solid red";
		 }
		 else{
			 a.style.border="1px solid yellow";
		 }
	});
}

//-----------------------------------------------------------------------------------
//Purpose:to get max test index upon selection of of test_type dropdown
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function getMaxTestIndex(id) {
if(id==""){
    return false;
}

 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetMaxTestId.php';
 new Ajax.Request(url,
   {
     method:'post',
     asynchronous :(false),
     parameters: {testTypeId: id,
                  subjectId:(document.getElementById('subject').value),
                  classId:(document.getElementById('class').value) ,
                  groupId:(document.getElementById('group').value)
                 },
     onCreate: function() {
     },
     onSuccess: function(transport){
            if(trim(transport.responseText)==0){
              gtestIndex=0;
              return false;
            }
            j = eval('('+trim(transport.responseText)+')');
            gtestIndex=parseInt(j.testIndex,10)+1;
    },
     onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}

//-----------------------------------------------------------------------------------
//Purpose:to delete the test details
//Author:Dipanjan Bhattacharjee
//Date:3.11.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function deleteData(id,index) {

if(document.getElementById('deleteTestIcon').style.display=='none'){
    return false;
}

 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
      return false;
  }
 else {
  var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxDeleteTestData.php';
  new Ajax.Request(url,
   {
     method:'post',
     asynchronous :(false),
     parameters: {
                   testId: id
                 },
     onCreate: function() {
     },
     onSuccess: function(transport){
            j = trim(transport.responseText);
            if(j==1){
                messageBox("<?php echo DELETE_MARKS_NOT_TEST; ?>");
            }
           else if(j==2){
                  messageBox("<?php echo DELETE_MARKS_AND_TEST; ?>");
                  document.searchForm.test.remove(index); //remove this test from test dropdown
            }
            else{
                messageBox(j);
                return false;
            }
        //refreshes dropdowns and lists
        document.getElementById('test').selectedIndex=0;
        //document.getElementById('testDesc').style.display='none';
        hideTestDetails(false);
        document.getElementById('deleteTestIcon').style.display='none';
        document.getElementById('showList').style.display='none';
        document.getElementById('divButton1').style.display = 'none';
        document.getElementById('imageField1').style.display='block';

    },
     onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
   });
 }
}

//-----------------------------------------------------------------------------------
//Purpose:To check for numeric entry in maxMarks textbox
//Author:Dipanjan Bhattacharekee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function checkNumber(value,id){
  s = value.toString();
  var fl=0;

  //value of max marks textbox(Above)
  var MM=parseInt(trim(document.getElementById('maxMarks').value),10);

	//GIVING INSTANT ALERT IF SOMETHING WRONG IS INPUTTED
  (parseInt(trim(value),10) > MM) ? (document.getElementById(id).className="inputboxRed") : document.getElementById(id).className="inputbox";

  /*
  for (var i = 0; i < s.length; i++){
    var c = s.charAt(i);
    if(!isDecimal(c))  {
     document.getElementById(id).value=document.getElementById(id).value.replace(c,"");
     fl=1;
   }
  }
  */
 if(fl==1){
   document.getElementById(id).focus();
   return false;
 }
  return true;
}

//-----------------------------------------------------------------------------------
//Purpose:to make present and marks enable/disable upon memofclass selection
//Author:Dipanjan Bhattacharekee
//Date:05.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function mocAction(id){
   setGlobalEditFlag(1);
   if(document.getElementById('mem'+id).checked){
     document.getElementById('ipre'+id).checked=true;
     document.getElementById('ipre'+id).disabled=false;
   }
  else{
      document.getElementById('ipre'+id).checked=false;
      document.getElementById('ipre'+id).disabled=true;
  }
   disableMarks(id);
}

//-----------------------------------------------------------------------------------
//Purpose:to set tabindex of submit and cancel(lower)
//Author:Dipanjan Bhattacharekee
//Date:05.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function marksAction(){
    document.getElementById('imageField2').tabIndex=document.getElementById('imarks'+(document.listFrm.imarks.length-1)).tabIndex+1;
    document.getElementById('imageField3').tabIndex=document.getElementById('imageField2').tabIndex+1;
}

//used to cleanup test and list divs
//Author: Dipanjan Bhattacharjee
function blankValues(value){
   document.getElementById('showList').style.display='none';
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxTestGroupPopulate.php';
    //document.searchForm.test.options.length=0;
   document.searchForm.group.options.length=0;
   var objOption = new Option("Select Group","");
   document.searchForm.group.options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }

   if(document.getElementById('class').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId : document.getElementById('subject').value,
                 classId   : document.getElementById('class').value
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
                    if(j.length==1){
                         document.searchForm.group.selectedIndex=1;
                    }

             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate test type drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (04.04.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function testTypePopulate(value) {
 
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxTestTypePopulate.php';
 
   document.searchForm.testType.options.length=0;
   var objOption = new Option("Select","");
   document.searchForm.testType.options.add(objOption);

   if(document.getElementById('class').value==""){
       return false;
   }
   if(document.getElementById('subject').value==""){
      return false;
   }
  

  new Ajax.Request(url,
  {
             method:'post',
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId:document.getElementById('class').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){
						 var objOption = new Option(j[c].testTypeName,j[c].testTypeId);
                         document.searchForm.testType.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/ExternalMarks/ajaxGetSubjects.php';

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
                       if(j[c].hasMarks==1) {
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                       }
                     }
                     if(j.length==1){
                         document.searchForm.subject.selectedIndex=1;
                         testTypePopulate(document.searchForm.subject.value); //populates testtype
                         groupPopulate(document.searchForm.subject.value);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

window.onload=function(){
 document.getElementById('class').focus();
 if(document.getElementById('class').options.length==2){
    document.getElementById('class').selectedIndex=1;
    populateSubjects(document.getElementById('class').value);
 }
}

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');
      return false;
    }
    //document.getElementById('divHelpInfo').innerHTML=title;
    document.getElementById('helpInfo').innerHTML= msg;
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);

    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}

function hideTestDetails(mode){
   if(mode){
     document.getElementById('testRowId1').style.display='';
     document.getElementById('testRowId2').style.display='';
	 document.getElementById('testRowId3').style.display='';
   }
   else{
     document.getElementById('testRowId1').style.display='none';
     document.getElementById('testRowId2').style.display='none';
	 document.getElementById('testRowId3').style.display='none';
   }
}

//this variable is used to detemine if anything has been modified or
//not after list is populated
var globalEditFlag=0;
function setGlobalEditFlag(value){
    globalEditFlag=value;
}

function getGlobalEditFlag(){
    return globalEditFlag;
}
//this function will check for unsaved data and alert user about it
function checkUnsavedData(e){
    if (getGlobalEditFlag()) {
          var evt = ( (!document.all) ? e : window.event);
          evt.returnValue = "<?php echo UNSAVED_DATA_ALERT; ?>";
          return false;
     }
}


//-------------------------------------
//THIS FUNCTION IS USED TO PRINT REPORT
//
//Author : Kavish Manjkhola
// Created on : 4/26/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------
function printReport() {
	pars = generateQueryString('searchForm');
	var path='<?php echo UI_HTTP_PATH;?>/Teacher/displayTestMarksReport.php?'+pars+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    try{
     var a=window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}
window.onbeforeunload=checkUnsavedData;
</script>

</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEnterExternalMarksContentsNew.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
