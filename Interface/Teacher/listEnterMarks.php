<?php
//---------------------------------------------------------------------------
//  THIS FILE used for showing list of messages to parents and students
//
// Author : Dipanjan Bhattacharjee
// Created on : (22.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
<title><?php echo SITE_NAME;?>: Teacher Comment </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="5%"','',false),
 new Array('studentName','Name','width="30%"','',false) , 
 new Array('rollNo','RollNo','width="15%"','',false), 
 new Array('universityRollNo','Universoty.RNo','width="15%"','',false), 
 new Array('marks','Marks','width="10%"','',false), 
 new Array('present','Present','width="10%"','',false), 
 new Array('memberOfClass','MOC','width="10%"','',false), 
 new Array('stid','','width="0%"','',false) //used to have testMarksId~studentId form
 );

//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
recordsPerPage = 100;
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
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var sclass="";var ssubject="";var sgroup="";
function getData(){
   if((document.getElementById('class').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){
        sclass=document.getElementById('class').value;
        ssubject=document.getElementById('subject').value;
        sgroup=document.getElementById('group').value;
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);     
    }
   else{
       messageBox("Select Class,Subject and Group to get Student List");
       document.getElementById('class').focus();
   } 
    
}


//-----------------------------------------------------------------------------------
//Purpose:to make marks=0 and readonly when present is not checked
//Author:Dipanjan Bhattacharjee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function validateForm(){
    
    if(document.listFrm.mem.length-2 ==0){ //2 for two dummy fields
     messageBox("No Data to Submit");   
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
          messageBox("Select a testtype");
          document.getElementById('testType').focus();
          return false;
    }
    if(document.getElementById('test').value==""){
          messageBox("Select a test");
          document.getElementById('test').focus();
          return false;
     } 
    if(trim(document.getElementById('testAbbr').value)==""){
          messageBox("Enter test abbriviation");
          document.getElementById('testAbbr').focus();
          return false;
    } 
    if(trim(document.getElementById('maxMarks').value)==""){
          messageBox("Enter maximum marks for test");
          document.getElementById('maxMarks').focus();
          return false;
    }  
    if(trim(document.getElementById('maxMarks').value)==""){
          messageBox("Enter maximum marks for test");
          document.getElementById('maxMarks').focus();
          return false;
    }
    if(!dateDifference(document.getElementById('testDate').value,cdate,"-")){
      messageBox("Test Date Can Not be Greater Than Current Date");   
      document.getElementById('testDate').focus();  
      return false;
    }  
    if(trim(document.getElementById('testTopic').value)==""){
          messageBox("Enter topic for test");
          document.getElementById('testTopic').focus();
          return false;
    }
    if(trim(document.getElementById('testIndex').value)==""){
          messageBox("Enter index for test");
          document.getElementById('testIndex').focus();
          return false;
    }
   var marks=document.listFrm.imarks.length;
   if(marks >0 ){
   for(var i=0; i < marks ; i++){
    if(trim(document.listFrm.imarks[ i ].value)==""){
        messageBox("Marks can not be empty");
        document.listFrm.imarks[ i ].focus();
        return false;
     }
    if(parseInt(trim(document.listFrm.imarks[ i ].value))>parseInt(trim(document.getElementById('maxMarks').value))){
        messageBox("Marks can not greater than maximum mark");
        document.listFrm.imarks[ i ].focus();
        return false;
     }          
   }
  }   
 else{
  if(trim(document.listFrm.imarks.value)==""){
        messageBox("Marks can not be empty");
        document.listFrm.imarks.focus();
        return false;
     }
    if(parseInt(trim(document.listFrm.imarks.value))>parseInt(trim(document.getElementById('maxMarks').value))){
        messageBox("Marks can not greater than maximum mark");
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function enterMarks() {
 
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxEnterMarks.php';
         
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
           memc=(document.listFrm.mem.checked ? "1" : "0" ); 
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
                         messageBox('<?php echo SUCCESS;?>');
                         //sendReq(listURL,divResultName,searchFormName,''); // no need to send request again
                         populateTest(document.getElementById('testType').value,2); //calls this function to populate with ewn values
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTestType(id) {

document.searchForm.testType.options.length=0;
var objOption = new Option("SELECT","");
document.searchForm.testType.options.add(objOption); 

//change "test" dropdown as well
document.searchForm.test.options.length=0;
var objOption = new Option("SELECT","");
document.searchForm.test.options.add(objOption);    
var objOption = new Option("New Test","NT");
document.searchForm.test.options.add(objOption);
document.searchForm.test.selectedIndex=0;
   
if(id==""){
    return false;
}
    
 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestType.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {subjectId: id},
     onCreate: function() {
                 showWaitDialog();
      },
     onSuccess: function(transport){
                     hideWaitDialog();
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
    
 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTest.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {testTypeId: id},
     onCreate: function() {
                 showWaitDialog();
      },
     onSuccess: function(transport){
            hideWaitDialog();
            j = eval('('+trim(transport.responseText)+')');
            var i=0;
            for(var i=0; i< j.length; i++){ 
             var objOption = new Option(j[i].testAbbr,j[i].testId);
             document.searchForm.test.options.add(objOption);    
            }
           if(mode==2){//if we want to show the lates created "test" as selected
             document.searchForm.test.selectedIndex=2;  //because "Select" and "New Test" will occupy first two indexes
           } 
           
    },
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }   
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
document.getElementById('testDesc').style.display='block';   
if(id==""){
 document.getElementById('testDesc').style.display='none';
 return false;
}
if(id=="NT"){ //for new Test
 var d=new Date(); //calculate current date
 var curDate=d.getFullYear()+"-"+((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1))+"-"+((d.getDate())<10?"0"+(d.getDate()):(d.getDate()));
 
 document.getElementById('testAbbr').value="";
 document.getElementById('maxMarks').value="";
 document.getElementById('testDate').value=curDate;
 document.getElementById('testTopic').value="";
 document.getElementById('testIndex').value="";
 document.getElementById('testAbbr').focus();
 return false;   
}
    
 url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetTestDetails.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {testId: id},
     onCreate: function() {
                 showWaitDialog();
      },
     onSuccess: function(transport){
            hideWaitDialog();
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
//Purpose:To check for numeric entry in maxMarks textbox
//Author:Dipanjan Bhattacharekee
//Date:23.07.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function checkNumber(value,id){
   
  s = value.toString();
  var fl=0;
  for (var i = 0; i < s.length; i++){
    var c = s.charAt(i); 
    if(!isInteger(c))  {
     document.getElementById(id).value=document.getElementById(id).value.replace(c,"");  
     fl=1;
   } 
  }
 if(fl==1){
   document.getElementById(id).focus();
   return false;  
 }  
  return true;
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEnterMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 
// $History: listEnterMarks.php $ 
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface/Teacher
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 8/09/08    Time: 1:39p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 7/31/08    Time: 6:38p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 7/31/08    Time: 3:04p
//Updated in $/Leap/Source/Interface/Teacher
//Added onCreate() function in ajax code
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 7/25/08    Time: 6:37p
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 7/25/08    Time: 11:52a
//Updated in $/Leap/Source/Interface/Teacher
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 7/24/08    Time: 7:57p
//Updated in $/Leap/Source/Interface/Teacher
//Changed header.php and footer.php paths to the original paths
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/24/08    Time: 11:58a
//Created in $/Leap/Source/Interface/Teacher
?>