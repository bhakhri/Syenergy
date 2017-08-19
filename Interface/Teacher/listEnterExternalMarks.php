<?php
//---------------------------------------------------------------------------
//  THIS FILE used for showing list of messages to parents and students
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifTeacherNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: External Marks </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false),
 new Array('studentName','Name','width="25%"','',true) , 
 new Array('rollNo','RollNo','width="15%"','',true), 
 new Array('universityRollNo','Univ.RollNo','width="15%"','',true), 
 new Array('isMemberOfClass','ClassMember','width="12%"','align="center"',true), 
 new Array('isPresent','Present','width="12%"','align="center"',true), 
 new Array('marksScored','Marks','width="12%"','',true), 
 new Array('stid','','width="0%"','',false) //used to have testMarksId~studentId form
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
sortField = 'firstName';
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
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function hide_div(id,mode){
    
    if(mode==2){
     document.getElementById(id).style.display='none';
    }
    else{
        document.getElementById(id).style.display='block';
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
 //document.getElementById('class').selectedIndex=0;
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('testType').selectedIndex=0;
 //document.getElementById('test').selectedIndex=0;
 //document.getElementById('test').options.length=2;
 //document.getElementById('testType').options.length=1;
 //document.getElementById('testDesc').style.display='none';
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
function getData(){
   if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){
     if(document.getElementById('testType').value!="" && document.getElementById('test').value!=""){  
         
       if(trim(document.getElementById('testAbbr').value)!="" && trim(document.getElementById('maxMarks').value)!="" && trim(document.getElementById('testTopic').value)!=""){
        sclass=document.getElementById('class').value;
        ssubject=document.getElementById('subject').value;
        sgroup=document.getElementById('group').value;          
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1); 
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
    if(document.getElementById("ipre"+id).checked){
      document.getElementById("imarks"+id).readOnly=false;  
    }
   else{
       document.getElementById("imarks"+id).readOnly=true;
       document.getElementById("imarks"+id).value=0;
   } 
}

//-----------------------------------------------------------------------------------
//Purpose:to validate form inputs
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
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
    var d=new Date();
    var cdate=d.getFullYear()+"-"+(((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1)))+"-"+((d.getDate()<10?"0"+d.getDate():d.getDate()));
       
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
   if(!dateDifference(document.getElementById('testDate').value,cdate,"-")){
      messageBox("<?php echo TEST_DATE_VALIDATION; ?>");  
      document.getElementById('testDate').focus();  
      return false;
    }  
    if(trim(document.getElementById('testTopic').value)==""){
          messageBox("<?php echo ENTER_TEST_TOPIC; ?>");  
          document.getElementById('testTopic').focus();
          return false;
    }
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
     if(!isDecimal(trim(document.listFrm.imarks[ i ].value))){
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
    if(parseInt(trim(document.listFrm.imarks.value),10)>parseInt(trim(document.getElementById('maxMarks').value),10)){
        messageBox("<?php echo MARKS_VALIDATION; ?>");
        document.listFrm.imarks.focus();
        return false;
     }     
 } 
    
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
function enterMarks() {
 
        var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEnterMarks.php';
         
         var i=0;
         var studentId="";
         var testMarksId="";
         var mark="";    
         var present="";    
         var memc=""; 
         
         if((document.listFrm.mem.length-2) <= 1){     
           var arr=document.listFrm.stid.value.split("~");   
           studentId=arr[1];
           testMarksId=arr[0];
           mark=document.listFrm.imarks.value;  
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
             parameters: {studentIds: (studentId), 
             testMarksId: (testMarksId), 
             marks: (mark),
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
             groupId:(document.getElementById('group').value)
             },
            onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                      if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {  
                         flag = true;
                         populateTest(document.getElementById('testType').value,2); //calls this function to populate with ewn values
                          
                         //calls this function to set the max index of the new test to be created
                         getMaxTestIndex(document.getElementById('testType').value);
                          
                         messageBox("<?php echo ASSIGNMENT_MARKS_GIVEN; ?>");
                         hide_div('showList',2);
                         resetForm();
                     } 
                     else {
                        messageBox(trim(transport.responseText));

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
                  conductingAuthority : 2    //testType:1 means that it is external(conductingAuthority:2)
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
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
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
document.getElementById('testDesc').style.display='block';   
if(id=="" || id=="NT"){
    document.getElementById('deleteTestIcon').style.display='none';
}
else{
    document.getElementById('deleteTestIcon').style.display='block';
}
hide_div('showList',2); 
if(id==""){
 document.getElementById('testDesc').style.display='none';
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
            document.getElementById('testDate').value=j.testDate;
            document.getElementById('testTopic').value=j.testTopic;
            document.getElementById('testIndex').value=j.testIndex;
            document.getElementById('testAbbr').focus();
     },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
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
            j = eval('('+trim(transport.responseText)+')');
            gtestIndex=parseInt(j.testIndex,10)+1;
    },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
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
        //refreshes dropdowns and lists        
        document.getElementById('test').selectedIndex=0;    
        document.getElementById('testDesc').style.display='none';    
        document.getElementById('deleteTestIcon').style.display='none';    
        document.getElementById('showList').style.display='none';   
        document.getElementById('imageField1').style.display='block'; 
            
    },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
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
function checkNumber(value,id){
   
  s = value.toString();
  var fl=0;
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function mocAction(id){
   if(document.getElementById('mem'+id).checked){
     document.getElementById('ipre'+id).checked=true;  
   }
  else{
      document.getElementById('ipre'+id).checked=false;  
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
    
    document.getElementById('showList').style.display='none';
    document.getElementById('testDesc').style.display='none';
    document.getElementById('imageField1').style.display='block';    
    
    
}

window.onload=function(){
 document.getElementById('class').focus();   
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEnterExternalMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listEnterExternalMarks.php $ 
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