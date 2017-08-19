<?php
//used for entering student's interl marks
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','TestMarks');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Test Marks </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

//to stop special formatting
specialFormatting=0;
var previousMarks =0;

var tableHeadArray = new Array(new Array('srNo','#','width="1%"','',false),
 new Array('studentName','Name','width="25%"','',true) ,
 new Array('rollNo','RollNo','width="10%"','',true),
 new Array('universityRollNo','Univ.RollNo','width="15%"','',true),
 new Array('regNo','Regd. No.','width="10%"','',true),
 new Array('isMemberOfClass','Class Member','width="12%"','align="center"',false),
 new Array('isPresent','Present','width="12%"','align="center"',false),
 new Array('marksScored','Marks','width="10%"','',false)
 //,new Array('stid','','width="0%"','',false) //used to have testMarksId~studentId form
 );

listURL = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxEnterMarksList.php';
//recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
recordsPerPage =1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

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
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('employeeId').value==''){
        messageBox("<?php echo SELECT_TEACHER; ?>");
        document.getElementById('employeeId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
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
        sclass=document.getElementById('classId').value;
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


//-----------------------------------------------------------------------------------
//Purpose:to check whether the marks are new or old
//Date:4/18/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function checkMarks(id){
		marks = document.getElementById('maxMarks').value;
		if(marks != '' && previousMarks !=''){
			//if(marks != previousMarks){
				if(false===confirm("Do you want to change marks")){
					document.getElementById('maxMarks').value = previousMarks;
				}
				else{
					validateMarks(document.getElementById('maxMarks').value);
					return false;
			//	}
			}
		}
}
//-----------------------------------------------------------------------------------
//Purpose:to check whether the marks are new or old
//Date:4/18/2011
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function checkData(id){
		marks = document.getElementById('maxMarks').value;
		if(marks != previousMarks){
			if(marks != '' && previousMarks !='' && previousMarks !=0 ){
				validateMarks(document.getElementById('maxMarks').value);
				return false;
			}
		}
}
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
	if(parseInt(document.getElementById(id).value) > parseInt(document.getElementById('maxMarks').value)){
		document.getElementById(id).style.border="1px solid red";
	}
	else{
		document.getElementById(id).style.border="1px solid yellow";
	}
}

function autoPopulateEmployee(timeTableLabelId){
    clearData(1);

    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTeachers.php';

    if(timeTableLabelId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: timeTableLabelId
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.employeeId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

var currentDate="<?php echo date('Y-m-d'); ?>";
function autoPopulateClass(employeeId){
    clearData(2);

    //var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetClass.php';
    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetAdjustedClass.php';

    if(employeeId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId: employeeId,
                 startDate        : currentDate,
                 endDate          : currentDate,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    j = eval('('+trim(transport.responseText)+')');

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.searchForm.classId.options.add(objOption);
                     }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


function populateSubjects(classId){
    clearData(3);

    //var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetSubjects.php';
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetAdjustedSubject.php';

    if(classId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 classId: classId,
                 employeeId :document.getElementById('employeeId').value,
                 startDate : currentDate,
                 endDate   : currentDate,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                     }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups(classId,subjectId) {
   clearData(4);

   //var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGroupPopulate.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxTestGroupPopulate.php';

   if(classId=="" || subjectId=="" || document.getElementById('employeeId').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId: subjectId,
                 classId  : classId,
                 employeeId :document.getElementById('employeeId').value,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var j = eval('('+trim(transport.responseText)+')');
                    var r=1;
                    var tname='';
                    for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function clearData(mode){
    document.getElementById('results').innerHTML='';
    if(mode==1){
        document.getElementById('employeeId').options.length=1;
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==2){
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
    else if(mode==3){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
    }
   else if(mode==4){
       document.getElementById('group').options.length=1;
   }

   blankValues(1);
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
var cdate="<?php echo date('Y-m-d'); ?>";
function validateForm(){

    if(document.listFrm.mem.length-2 ==0){ //2 for two dummy fields
     messageBox("<?php echo NO_DATA_SUBMIT; ?>");
     return false;
    }

    //if someone changes those dropdown before submitting this will negeta that selection
    document.getElementById('classId').value=sclass;
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
var clickFl=1;

function enterMarks() {

         if(clickFl==0){
             messageBox("Another request is in progress");
             return false;
         }

        var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxEnterMarks.php';

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
             classId:(document.getElementById('classId').value),
             subjectId:(document.getElementById('subject').value),
             groupId:(document.getElementById('group').value),
             employeeId:(document.getElementById('employeeId').value),
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function populateTestType(id) {

if(trim(document.getElementById('classId').value)==""){
  messageBox("<?php echo SELECT_CLASS; ?>");
  document.getElementById('subject').selectedIndex=0;
  document.getElementById('classId').focus();
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

 var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTestType.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {subjectId: id,
                  classId:document.getElementById('classId').value,
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
blankValues();
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

 var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTest.php';
 new Ajax.Request(url,
   {
     method:'post',
     parameters: {testTypeId: id,
                  subjectId:(document.getElementById('subject').value),
                  classId:(document.getElementById('classId').value) ,
                  groupId:(document.getElementById('group').value),
                  employeeId:document.getElementById('employeeId').value
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

var curDate="<?php echo date('Y-m-d');?>";
function populateTestDetails(id) {

    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('employeeId').value==''){
        messageBox("<?php echo SELECT_TEACHER; ?>");
        document.getElementById('employeeId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
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
    
    previousMarks =0;
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
//var d=new Date(); //calculate current date
//var curDate=d.getFullYear()+"-"+((d.getMonth()+1)<10?"0"+(d.getMonth()+1):(d.getMonth()+1))+"-"+((d.getDate())<10?"0"+(d.getDate()):(d.getDate()));
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
   
   previousMarks =0;
   var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTestDetails.php';
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

    var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetMaxTestId.php';
    new Ajax.Request(url,
    {
     method:'post',
     asynchronous :(false),
     parameters: {testTypeId: id,
                  subjectId:(document.getElementById('subject').value),
                  classId:(document.getElementById('classId').value) ,
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
     onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
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
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteTestData.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
function checkInput(id){
	if(document.getElementById(id).value==''){
		document.getElementById(id).value=0;
	}
}

//-----------------------------------------------------------------------------------
//Purpose:to make present and marks enable/disable upon memofclass selection
//Author:Dipanjan Bhattacharekee
//Date:05.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    document.getElementById('showList').style.display='none';
    document.getElementById('testDesc').style.display='none';
    document.getElementById('imageField1').style.display='block';
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate test type drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (04.04.09)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function testTypePopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxTestTypePopulate.php';
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
//THIS FUNCTION IS USED TO reset form(top)
//
//Author : Dipanjan Bhattacharjee
// Created on : (0508.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function resetForm(){
 document.getElementById('deleteTestIcon').style.display='none';
 document.getElementById('testDesc').style.display='none';
 document.getElementById('imageField1').style.display='block';
 document.getElementById('timeTableLabelId').selectedIndex=0;
 document.getElementById('employeeId').selectedIndex=0;
 document.getElementById('classId').selectedIndex=0;
 document.getElementById('subject').selectedIndex=0;
 document.getElementById('group').selectedIndex=0;
 document.getElementById('testType').selectedIndex=0;
 document.getElementById('test').selectedIndex=0;
 document.getElementById('test').options.length=2;
 document.getElementById('testType').options.length=1;
 document.getElementById('testDesc').style.display='none';
 document.getElementById('classId').focus();
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
	var path='<?php echo UI_HTTP_PATH;?>/displayTestMarksReport.php?'+pars+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    try{
     var a=window.open(path,"DegreeReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    }
    catch(e){
        
    }
}

window.onload=function(){
    document.getElementById('timeTableLabelId').focus();
    document.searchForm.reset();
    if(document.getElementById('timeTableLabelId').selectedIndex>0){
     autoPopulateEmployee(document.getElementById('timeTableLabelId').value);
    }
}
</script>

</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminTasks/listEnterAssignmentMarksContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
