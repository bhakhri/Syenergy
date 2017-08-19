<?php
//---------------------------------------------------------------------------
//  THIS FILE used for showing list of messages to parents and students
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EnterAssignmentMarksMaster');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Marks </title>
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

  var tableHeadArray = new Array(
									new Array('srNo','#','width="5%"','',false),
									new Array('studentName','Name','width="25%"','',true) ,
									new Array('rollNo','RollNo','width="10%"','',true),
									new Array('universityRollNo','Univ.RollNo','width="15%"','',true),
									new Array('regNo','Regd. No.','width="10%"','',true),
									new Array('isMemberOfClass','Class Member','width="12%"','align="center"',false),
									new Array('isPresent','Present','width="12%"','align="center"',false),
									new Array('marksScored','Marks','width="10%"','',false)
                                 //,new Array('stid','','width="0%"','',false) //used to have testMarksId~studentId form
                                );

//recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
recordsPerPage =1000;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEnterMarksList.php';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function resetForm(){
 document.getElementById('imageField1').style.display='block';
 document.getElementById('deleteTestIcon').style.display='none';
 document.getElementById('deleteTestIcon1').style.display='none';
 document.getElementById('testRowId1').style.display='none';
 document.getElementById('testRowId2').style.display='none';
 document.getElementById('testRowId3').style.display='none';
 document.getElementById('class').selectedIndex=0;
 document.getElementById('subject').selectedIndex=0;
 document.getElementById('group').selectedIndex=0;
 document.getElementById('testType').selectedIndex=0;
 document.getElementById('test').selectedIndex=0;
 document.getElementById('test').options.length=2;
 document.getElementById('testType').options.length=1;
 document.getElementById('testDesc').style.display='none';
 document.getElementById('class').focus();
}



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var sclass="";var ssubject="";var sgroup="";
function fetchData(){
  sendReq(listURL,divResultName,searchFormName,'',false);
}
function getData(){
   if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){
     if(document.getElementById('testType').value!="" && document.getElementById('test').value!=""){

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
       //if(trim(document.getElementById('testAbbr').value)!="" && trim(document.getElementById('maxMarks').value)!="" && trim(document.getElementById('testTopic').value)!=""){
       if(trim(document.getElementById('testAbbr').value)!="" && trim(document.getElementById('maxMarks').value)!="" ){
        sclass=document.getElementById('class').value;
        ssubject=document.getElementById('subject').value;
        sgroup=document.getElementById('group').value;

        setGlobalEditFlag(0);
        //window.setTimeout(fetchData, 1);
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        var divEle=document.getElementById('results').getElementsByTagName('INPUT');
        var chkLength=divEle.length;
        for(var k=0;k<chkLength;k++){
            if(divEle[k].type.toUpperCase()=='CHECKBOX' && divEle[k].name=='ipre' && !divEle[k].checked){
                divEle[k].parentNode.parentNode.className='redClass';
                divEle[k].parentNode.parentNode.setAttribute('value','redClass');
            }
        }
      }
     else{
         messageBox("<?php echo ENTER_TEST_INFO; ?>");
         document.getElementById('testAbbr').focus();
     }
    }
     else{
         messageBox("<?php echo SELECT_TESTTYPE_TEST; ?>");
         document.getElementById('test').focus();
    }

   }
   else{
        messageBox("<?php echo MARKS_SELECT_STUDENT_LIST; ?>");
        document.getElementById('class').focus();
   }

}



//-----------------------------------------------------------------------------------
//Purpose:to make marks=0 and readonly when present is not checked
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var cdate="<?php echo date('Y-m-d'); ?>";
function validateForm(){

    if(document.listFrm.mem.length-2 ==0){ //2 for two dummy fields
     messageBox("<?php echo NO_DATA_SUBMIT; ?>");
     return false;
    }

    //if someone changes those dropdown before submitting this will negeta that selection
    document.getElementById('class').value=sclass;
    document.getElementById('subject').value=ssubject;
    document.getElementById('group').value=sgroup;

    //calculate current date
   // var d=new Date();
    //var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));

    if(document.getElementById('testType').value==""){
          messageBox("<?php echo SELECT_TESTTYPE; ?>");
          document.getElementById('testType').focus();
          return false;
    }
    if(document.getElementById('test').value==""){
          messageBox("<?php echo SELECT_TEST; ?>");
          document.getElementById('test').focus();
          return false;
     }
    if(trim(document.getElementById('testAbbr').value)==""){
          messageBox("<?php echo ENTER_TEST_ABBR; ?>");
          document.getElementById('testAbbr').focus();
          return false;
    }
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
   if(!dateDifference(document.getElementById('testDate').value,cdate,"-")){
      messageBox("<?php echo TEST_DATE_VALIDATION; ?>");
      document.getElementById('testDate').focus();
      return false;
    }
    /*
    if(trim(document.getElementById('testTopic').value)==""){
          messageBox("<?php echo ENTER_TEST_TOPIC; ?>");
          document.getElementById('testTopic').focus();
          return false;
    }
    */
    if(trim(document.getElementById('testIndex').value)==""){
          messageBox("<?php echo ENTER_TEST_INDEX; ?>");
          document.getElementById('testIndex').focus();
          return false;
    }
   var marks=document.listFrm.imarks.length;
   if(marks >0 ){
   for(var i=0; i < marks ; i++){
    if(trim(document.listFrm.imarks[ i ].value)==""){
        messageBox("<?php echo EMPTY_MARKS; ?>");
        document.listFrm.imarks[ i ].focus();
        return false;
     }
     if(!isDecimal(trim(document.listFrm.imarks[ i ].value)) || trim(document.listFrm.imarks[ i ].value) < 0 ){
        messageBox("<?php echo INVALID_MARKS; ?>");
        document.listFrm.imarks[ i ].focus();
        return false;
     }
    if(parseInt(trim(document.listFrm.imarks[ i ].value),10)>parseInt(trim(document.getElementById('maxMarks').value),10)){
        messageBox("<?php echo MARKS_VALIDATION; ?>");
        document.listFrm.imarks[ i ].focus();
        return false;
     }
   }
  }
 else{
  if(trim(document.listFrm.imarks.value)==""){
        messageBox("<?php echo EMPTY_MARKS; ?>");
        document.listFrm.imarks.focus();
        return false;
  }
  if(!isDecimal(trim(document.listFrm.imarks.value)) || trim(document.listFrm.imarks.value) < 0 ){
        messageBox("<?php echo INVALID_MARKS; ?>");
        document.listFrm.imarks.focus();
        return false;
     }
    if(parseInt(trim(document.listFrm.imarks.value),10)>parseInt(trim(document.getElementById('maxMarks').value),10)){
        messageBox("<?php echo MARKS_VALIDATION; ?>");
        document.listFrm.imarks.focus();
        return false;
     }
 }

    setGlobalEditFlag(0);
    enterMarks(); //calls the function for entering marks
    return false;

}


//--------------------------------------------------------------------------------------
//Purpose:For entering marks
//Author:Dipanjan Bhattachaarjee
//Date : 23.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var clickFl=1;

function enterMarks() {

         if(clickFl==0){
             messageBox("Another request is in progress");
             return false;
         }

        var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEnterMarks.php';

         var i=0;
         var studentId="";
         var testMarksId="";
         var mark="";
	 var hiddenMark="";
         var present="";
         var memc="";

         if((document.listFrm.mem.length-2) <= 1){
           var arr=document.listFrm.stid.value.split("~");
           studentId=arr[1];
           testMarksId=arr[0];
           mark=document.listFrm.imarks.value;
	   hiddenMark=document.listFrm.hiddenMarks.value;
           present=(document.listFrm.ipre.checked ? "1" : "0" );
           memc=(document.listFrm.mem[2].checked ? "1" : "0" );
         }
        else{
         //detecting studentId and testMarksId(previous records)
         var studenttest=document.listFrm.stid.length; //hidden field
          for(i=0; i <studenttest ; i++){
             var arr=document.listFrm.stid[ i ].value.split("~");
             if(studentId==""){
                 studentId=arr[1]; //studentId
             }
            else{
                studentId=studentId+","+arr[1]; //studentId
            }
           if(testMarksId==""){
                 testMarksId=arr[0]; //testMarksId
             }
            else{
                testMarksId=testMarksId + "," + arr[0]; //testMarksId
            }
         }

         //detecting marks list
         var marks=document.listFrm.imarks.length;
         for(i=0; i < marks ; i++){
             if(mark==""){
                 mark=document.listFrm.imarks[ i ].value;
             }
            else{
                 mark=mark + "," + document.listFrm.imarks[ i ].value;
            }
         }

	//detecting hidden marks list
         var marks=document.listFrm.hiddenMarks.length;
         for(i=0; i < marks ; i++){
             if(hiddenMark==""){
                 hiddenMark=document.listFrm.hiddenMarks[ i ].value;
             }
            else{
                 hiddenMark=hiddenMark + "," + document.listFrm.hiddenMarks[ i ].value;
            }
         }

         //detecting present list
         var pre=document.listFrm.ipre.length;
         for(i=0; i < pre ; i++){
             if(present==""){
                 present=(document.listFrm.ipre[ i ].checked ? "1" : "0" );
             }
            else{
                 present=present + "," + ( document.listFrm.ipre[ i ].checked ? "1" : "0" );
            }
         }

         //detecting memberofclass list
         var member=document.listFrm.mem.length;
         for(i=2; i < member ; i++){ //subtracting 2 for two dummy fields
             if(memc==""){
                 memc=(document.listFrm.mem[ i ].checked ? "1" : "0" );
             }
            else{
                 memc=memc + "," + ( document.listFrm.mem[ i ].checked ? "1" : "0" );
            }
         }
      }
        //alert("studentId="+studentId+"\n"+"testMarksId="+testMarksId+"\n"+"marks="+mark+"\n"+"present="+present+"\n"+"memc="+memc);

         new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {studentIds: (studentId),
             testMarksId: (testMarksId),
             marks: (mark),
	     hiddenMarks:(hiddenMark),
             present: (present),
             memofclass: (memc),
             testTypeId:(document.getElementById('testType').value),
             testId:(document.getElementById('test').value),
             testAbbr:(trim(document.getElementById('testAbbr').value)),
             maxMarks:(trim(document.getElementById('maxMarks').value)),
             testDate:(trim(document.getElementById('testDate').value)),
             testTopic:(trim(document.getElementById('testTopic').value)),
             testIndex:(trim(document.getElementById('testIndex').value)),
             classId:(document.getElementById('class').value),
             subjectId:(document.getElementById('subject').value),
             groupId:(document.getElementById('group').value),
			 comments:(document.getElementById('comments').value)
             },
            onCreate: function() {
                 clickFl=0;

                 showWaitDialog();
             },
             onSuccess: function(transport){
                    clickFl=1;
                     hideWaitDialog();

                     var ret=trim(transport.responseText).split('~!~');

                      if("<?php echo SUCCESS;?>" == ret[0]) {

                         flag = true;
                         populateTest(document.getElementById('testType').value,2); //calls this function to populate with ewn values

                         //calls this function to set the max index of the new test to be created
                         getMaxTestIndex(document.getElementById('testType').value);

                         if(ret.length>1){
                            messageBox("<?php echo ASSIGNMENT_MARKS_GIVEN; ?>\n<?php echo STUDENTS_WITH_ZERO_MARKS; ?>"+ret[1]);
                         }
                         else{
                           messageBox("<?php echo ASSIGNMENT_MARKS_GIVEN; ?>");
                         }

                         hide_div('showList',2);
                         resetForm();
                     }
                    else if("<?php echo DOUBLE_CLICKS; ?>" == ret[0]){
                        messageBox("<?php echo ASSIGNMENT_MARKS_GIVEN; ?>");
                    }
                     else {
                        messageBox(ret[0]);

                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}



//-----------------------------------------------------------------------------------
//Purpose:to populate test_type dropdown upon selection of subject dropdown
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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

//change "test" dropdown as well
document.searchForm.test.options.length=0;
var objOption = new Option("SELECT","");
document.searchForm.test.options.add(objOption);
var objOption = new Option("Create New Test","NT");
document.searchForm.test.options.add(objOption);
document.searchForm.test.selectedIndex=0;

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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTest(id,mode) {

if(document.getElementById('subject').value=='' || document.getElementById('group').value=='' || document.getElementById('class').value=='' || id==0 || id==''){
    return false;
}
var oldVal=document.getElementById('test').value;

document.searchForm.test.options.length=0;
var objOption = new Option("SELECT","");
document.searchForm.test.options.add(objOption);
var objOption = new Option("Create New Test","NT");
document.searchForm.test.options.add(objOption);
if(mode==1){ //if we want to show "SELECT" as selectd
 document.searchForm.test.selectedIndex=0;
}


if(id==""){
    return false;
}

 var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTest.php';
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTestDetails(id) {
    
    if(document.getElementById('class').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('class').focus();
        return false;
    }
    if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    }
    if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
    }
    
 previousMarks=0;
//document.getElementById('testDesc').style.display='block';
hideTestDetails(true);
if(id=="" || id=="NT"){
    document.getElementById('deleteTestIcon').style.display='none';
    document.getElementById('deleteTestIcon1').style.display='none';
}
else{
    document.getElementById('deleteTestIcon').style.display='block';
    document.getElementById('deleteTestIcon1').style.display='block';
}
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function checkData(id){
		marks = parseInt(document.getElementById('maxMarks').value);
		if(previousMarks != marks){
			if(marks != '' && previousMarks !='' && previousMarks !=0 ){
					validateMarks(marks);
					return false;
			}
		}
}
//-----------------------------------------------------------------------------------
//Purpose:to validate the marks
//Date:4/18/2011
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
            gtestIndex=parseInt(j[0].testIndex,10)+1;
    },
     onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
   });
}

//-----------------------------------------------------------------------------------
//Purpose:to delete the test details
//Author:Dipanjan Bhattacharjee
//Date:3.11.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function checkNumber(value,id,e,srNoUp,srNoDown){
  
  var ev = e||window.event;
   thisKeyCode = ev.keyCode;	
   if (thisKeyCode == '40') {
   	 txt = "imarks"+srNoDown;
   	 if(eval("document.getElementById('"+txt+"').value")=='0'){
   	 	eval("document.getElementById('"+txt+"').value=''");
   	 }
   	 eval("document.getElementById('"+txt+"').focus()");
   	 return false;
   }
   else if (thisKeyCode == '38') {
   	 txt = "imarks"+srNoUp;
   	 if(eval("document.getElementById('"+txt+"').value")=='0'){
   	 	eval("document.getElementById('"+txt+"').value=''");
   	 }
   	 eval("document.getElementById('"+txt+"').focus()");
   	 return false;
   }
   
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
 alert(f1);
 if(fl==1) {
   document.getElementById(id).focus();
   return false;
 }
  return true;
}

//-----------------------------------------------------------------------------------
//Purpose:to make present and marks enable/disable upon memofclass selection
//Author:Dipanjan Bhattacharekee
//Date:05.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function marksAction(){
    document.getElementById('imageField2').tabIndex=document.getElementById('imarks'+(document.listFrm.imarks.length-1)).tabIndex+1;
    document.getElementById('imageField3').tabIndex=document.getElementById('imageField2').tabIndex+1;
}

//used to cleanup test and list divs
//Author: Dipanjan Bhattacharjee
function blankValues(value){
    if(value==1){
      document.searchForm.test.options.length=0;
      var objOption = new Option("SELECT","");
      document.searchForm.test.options.add(objOption);
      var objOption = new Option("Create New Test","NT");
      document.searchForm.test.options.add(objOption);
      document.searchForm.test.selectedIndex=0;
      document.searchForm.testType.selectedIndex=0;
    }
	document.getElementById('deleteTestIcon').style.display='none';
	document.getElementById('deleteTestIcon1').style.display='none';
    document.getElementById('showList').style.display='none';
    document.getElementById('divButton1').style.display = 'none';
    //document.getElementById('testDesc').style.display='none';
    hideTestDetails(false);
    document.getElementById('imageField1').style.display='block';


}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (12.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTestGroupPopulate.php';
    document.searchForm.test.options.length=0;
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function testTypePopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTestTypePopulate.php';
   document.searchForm.testType.options.length=0;
   var objOption = new Option("Select","");
   document.searchForm.testType.options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: document.getElementById('subject').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){

						 var objOption = new Option(j[c].testTypeName,j[c].testTypeCategoryId);
                         document.searchForm.testType.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function populateSubjects(classId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetSubjects.php';

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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//------------------------------------------------------
function printReport() {
	pars = generateQueryString('searchForm');
	var path='<?php echo UI_HTTP_PATH;?>/Teacher/displayTestMarksReport.php?'+pars+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    try{
		hideUrlData(path,true);
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
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEnterAssignmentMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
// $History: listEnterAssignmentMarks.php $
//
//*****************  Version 26  *****************
//User: Dipanjan     Date: 13/04/10   Time: 17:03
//Updated in $/LeapCC/Interface/Teacher
//Done llrit enhancements
//
//*****************  Version 25  *****************
//User: Dipanjan     Date: 28/01/10   Time: 11:31
//Updated in $/LeapCC/Interface/Teacher
//Added "Univ. Roll No." column in student list display
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 17/12/09   Time: 15:47
//Updated in $/LeapCC/Interface/Teacher
//Added the code for "Freezed" class
//
//*****************  Version 23  *****************
//User: Dipanjan     Date: 14/12/09   Time: 12:52
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//Bug ids---
//0002259,0002258,0002257,0002256,0002255,0002252,0002251,
//0002250,0002254
//
//*****************  Version 22  *****************
//User: Dipanjan     Date: 3/12/09    Time: 11:02
//Updated in $/LeapCC/Interface/Teacher
//Made UI related changes :  Added alert for unsaved data
//
//*****************  Version 21  *****************
//User: Dipanjan     Date: 1/12/09    Time: 17:09
//Updated in $/LeapCC/Interface/Teacher
//Made UI changes in test marks module in teacher module
//
//*****************  Version 20  *****************
//User: Parveen      Date: 11/02/09   Time: 3:29p
//Updated in $/LeapCC/Interface/Teacher
//Help div function added (showHelpDetails)
//
//*****************  Version 19  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Interface/Teacher
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 18  *****************
//User: Dipanjan     Date: 8/09/09    Time: 15:38
//Updated in $/LeapCC/Interface/Teacher
//Done bug fixing.
//bug id---00001467
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 7/09/09    Time: 16:58
//Updated in $/LeapCC/Interface/Teacher
//Fixed Query Error :
//
//1.SELECT testId,testAbbr,testIndex FROM test WHERE testTypeCategoryId=1
//AND subjectId=18 AND classId=87 AND groupId= AND sessionId=1 AND
//instituteId=1 ORDER BY testId DESC
//
//2.SELECT IF(MAX(testIndex) IS NULL ,0,MAX(testIndex)) AS testIndex FROM
//test WHERE testTypeCategoryId=1 AND subjectId=18 AND classId=87 AND
//groupId= AND sessionId=1 AND instituteId=1
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 31/08/09   Time: 14:18
//Updated in $/LeapCC/Interface/Teacher
//corrected javascript code
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 31/07/09   Time: 11:29
//Updated in $/LeapCC/Interface/Teacher
//Woked on client issues.
//Issues taken care of ---4,5,7,10
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 29/07/09   Time: 17:23
//Updated in $/LeapCC/Interface/Teacher
//Done the enhancement: subjects are populated corresponding to the class
//selected
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 17/07/09   Time: 15:09
//Updated in $/LeapCC/Interface/Teacher
//Added Role Permission Variables
//
//*****************  Version 11  *****************
//User: Administrator Date: 29/05/09   Time: 17:58
//Updated in $/LeapCC/Interface/Teacher
//Added the functionality : teacher cannot enter -ve marks
//
//*****************  Version 10  *****************
//User: Administrator Date: 25/05/09   Time: 15:16
//Updated in $/LeapCC/Interface/Teacher
//Added the functionality "No of students with zero marks : X"
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 9/04/09    Time: 10:48
//Updated in $/LeapCC/Interface/Teacher
//Modified "Double Clicks" preventing javascript alerts in marks module
//in SNS,Leap and LeapCC
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/04/09    Time: 16:09
//Updated in $/LeapCC/Interface/Teacher
//Added class check during group populate
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 4/06/09    Time: 1:05p
//Updated in $/LeapCC/Interface/Teacher
//to get new ajax file
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 4/04/09    Time: 3:28p
//Updated in $/LeapCC/Interface/Teacher
//show test type as per theory or practical subject
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 3/24/09    Time: 6:30p
//Updated in $/LeapCC/Interface/Teacher
//modified as per sorting university roll no. Leet & isLett
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/18/09    Time: 10:37a
//Updated in $/LeapCC/Interface/Teacher
//modified to show group by selecting subject
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 3/16/09    Time: 6:24p
//Updated in $/LeapCC/Interface/Teacher
//modified for test type & put test type category
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/09/08   Time: 3:09p
//Updated in $/LeapCC/Interface/Teacher
//Corrected Marks modules
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 10/24/08   Time: 4:27p
//Updated in $/Leap/Source/Interface/Teacher
//Corrected javascript problem of "selecting new test" or "selecting old
//test" afer entering marks
//
//*****************  Version 13  *****************
//User: Dipanjan     Date: 9/04/08    Time: 6:03p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 12  *****************
//User: Dipanjan     Date: 9/01/08    Time: 5:36p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 11  *****************
//User: Dipanjan     Date: 8/30/08    Time: 11:26a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 8/25/08    Time: 11:21a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 8/22/08    Time: 3:27p
//Updated in $/Leap/Source/Interface/Teacher
//Added Standard Messages
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 8/14/08    Time: 1:32p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/09/08    Time: 1:39p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 8/05/08    Time: 7:59p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/31/08    Time: 6:38p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:04p
//Updated in $/Leap/Source/Interface/Teacher
//Added onCreate() function in ajax code
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/29/08    Time: 7:45p
//Updated in $/Leap/Source/Interface/Teacher
//Corrected Javascript Code
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/25/08    Time: 6:37p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/25/08    Time: 2:56p
//Created in $/Leap/Source/Interface/Teacher
?>
