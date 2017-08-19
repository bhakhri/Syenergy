<?php
//-------------------------------------------------------
// Purpose: To generate student list
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.07.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','COMMON');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Student/initData.php");
global $sessionHandler;
$optionalField = $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Detail</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  
?> 
<?php

function parseOutput($data){
  return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
}

function createBlankTD($i,$str='<td valign="middle" align="center"  class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
winLayerWidth  = 300; //  add/edit form width
winLayerHeight = 200; // add/edit form height
page=1; //default page


function showMessageDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMessageValues(id);
}

//this function shows duty leave details when we click on them
function showDutyLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateDutyLeaveValues(id);
}

//this function shows medical leave details when we click on them
function showMedicalLeaveDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 400, 200)
    populateMedicalLeaveValues(id);
}



function editWindow(previousClassId,id,dv,w,h) { 
    displayWindow(dv,w,h);
    populateValues(previousClassId);  
}

function populateValues(previousClassId) {

   cleanUpTable();
   var studentId="<?php echo $REQUEST_DATA["id"]; ?>";
   var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetSubjectValue.php';  

   var len=document.AcademicForm.acdClass.length;
   for(var n =0 ; n <len  ;n++){
     if(document.AcademicForm.acdClass.options[n].value==previousClassId){
       document.AcademicForm.acdClass.options[n].selected=true;
     }
   }	

   new Ajax.Request(url,
   {
         method:'post',
         asynchronous:false,  
         parameters:{studentId: studentId,
                     previousClassId:document.AcademicForm.acdClass.value
                    },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            j = eval('('+trim(transport.responseText)+')');   
            len=j.length;
            if(len>0) {
              for(i=0;i<len;i++) {
                addOneRow(1);
                varFirst=i+1;
                id = "subjectName"+varFirst;
                eval("document.getElementById(id).value=j[i]['subjectName']");    
                id = "sub_marks"+varFirst;
		eval("document.getElementById(id).value=j[i]['marks']"); 
		id = "sub_maxMarks"+varFirst;
		eval("document.getElementById(id).value=j[i]['maxMarks']");
              }
              reCalculate();     
           } 
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}



function validateAddForm(frm, act) {
    if(act=='Add') {
        addSubjectDetail();
        return false;
    }

}



//for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      
      if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
    } 

var resourceAddCnt=0;
 // check browser
     var isMozilla = (document.all) ? 0 : 1;
//to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
              
        if(cnt=='')
       cnt=1;  
         if(isMozilla){
             if(document.getElementById('anyidBody').childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        } 
        resourceAddCnt++; 
        createRows(resourceAddCnt,cnt);
	

    }
 var bgclass='';

//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){
       // alert(start+'  '+rowCnt); 
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
    
                         
     for(var i=0;i<rowCnt;i++){
	 var tr=document.createElement('tr');
      tr.setAttribute('id','row'+parseInt(start+i,10));
      
      var cell1=document.createElement('td');
      var cell2=document.createElement('td'); 
      var cell3=document.createElement('td');
	var cell4=document.createElement('td');
      var cell5=document.createElement('td');
      
      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
	cell4.setAttribute('align','left');
      cell5.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      
      var txt1=document.createElement('input');
      txt1.className="inputbox";
      //txt1.style.width="120px";
      txt1.setAttribute('id','subjectName'+parseInt(start+i,10));
      txt1.setAttribute('name','SubjectNameValue[]');

      var txt2=document.createElement('input');
 var txt3=document.createElement('input');
	
     
      
      txt2.setAttribute('id','sub_marks'+parseInt(start+i,10));
      txt2.setAttribute('name','MarksObtainedValue[]');
      txt2.setAttribute('type','text');
      txt2.className='inputbox';
      txt2.setAttribute('style','width:80px;');
      txt2.setAttribute('maxlength','5');
      
      txt3.setAttribute('id','sub_maxMarks'+parseInt(start+i,10));
      txt3.setAttribute('name','MaxMarksValue[]');
      txt3.setAttribute('type','text');
      txt3.className='inputbox';
      txt3.setAttribute('style','width:80px;');
      txt3.setAttribute('maxlength','5');


       var txt4=document.createElement('a');


      txt4.setAttribute('id','rd');
      txt4.className='htmlElement';  
      txt4.setAttribute('title','Delete');       
      txt4.innerHTML='X';
      txt4.style.cursor='pointer';
      txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
      
      
      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);
      
             
      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);
      
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;
      
     
      tbody.appendChild(tr); 
}
  tbl.appendChild(tbody); 
  reCalculate();  
}  

//to clean up table rows
    function cleanUpTable(){
       var tbody = document.getElementById('anyidBody');
       for(var k=0;k<=resourceAddCnt;k++){
             try{
              tbody.removeChild(document.getElementById('row'+k));
             }
             catch(e){
                 //alert(k);  // to take care of deletion problem
             }
          }  
    }
    
    
//to recalculate Serial no.
function reCalculate(){

  var a =document.getElementById('tableDiv').getElementsByTagName("td");
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      a[i].innerHTML=j;
      j++;
    }
  }
  //resourceAddCnt=j-1;
}     






function addSubjectDetail() {

	var subjectName='';
	var marks='';
	var maxMarks='';
	var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");

	var len=ele.length;
	for(var i=0;i<len;i++){
	   if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name=='SubjectNameValue[]') {
	      if(ele[i].value==''){
		messageBox("<?php echo ENTER_SUBJECT_NAME;?>");
		ele[i].focus();
		return false;
    	      }
	      if(ele[i].type!=''){
		if(subjectName !=''){
		   subjectName+=',';
		}
		subjectName +=ele[i].value;
	       }
            }
 	    if( ele[i].name=='MarksObtainedValue[]'){
		if(ele[i].value==''){
		   messageBox("<?php echo ENTER_MARKS_OBTAINED;?>");
		   ele[i].focus();
		   return false;
		}
		if(ele[i].type!='') {
		  if(marks  !=''){
		    marks+=',';
		  }
		  marks +=ele[i].value;
		}
		if(!isDecimal(trim(ele[i].value))) {
		messageBox("Please enter numeric values");
		ele[i].focus();
		return false;
		}
		
		}
		if( ele[i].name=='MaxMarksValue[]'){
		if(ele[i].value==''){
		messageBox("<?php echo ENTER_MAX_MARKS;?>");
		ele[i].focus();
		return false;

		}
		if(ele[i].type!='')
		{
		if(maxMarks!=''){
		maxMarks+=',';
				}
		maxMarks+=ele[i].value;
		}
		if(!isDecimal(trim(ele[i].value))) {
		messageBox("Please enter numeric values");
		ele[i].focus();
		return false;
		}
		
		/*if(marks>maxMarks)
  		{  
             	 messageBox("Marks obtained cannot be greater than maximum marks");
		ele[i].focus();
		return false;
		}*/
}

		
	}

 var dr=document.getElementById('tableDiv').getElementsByTagName("tr");
var rowCountTotal=dr.length-1;  
   if(rowCountTotal<=0){
		
		
		return false;
   }

//var  SubjectNameString=document.getElementById('subjectName');
//alert(SubjectNameString);
//var  MarksObtainedValueString=document.getElementById('marks');
//var MaxMarksValueString=document.getElementById('maxMarks');

var studentId="<?php echo $REQUEST_DATA["id"]; ?>";
    cleanUpTable();

         var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetSubjectDetails.php';
new Ajax.Request(url,
           {
      
             method:'post',
             parameters:{ studentId:studentId,
			  SubjectNameString: subjectName, 
			  MarksObtainedValueString:marks ,
			  MaxMarksValueString:maxMarks,
			  rowCountTotal:rowCountTotal,
			  previousClassId:document.AcademicForm.acdClass.value
             		},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                            
                         }
                         else {
                             hiddenFloatingDiv('divAcademic');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divNotice" DIV
//
//Author : Parveen Sharma
// Created on : (27.11.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateMessageValues(id) {

    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetMessageValue.php';   

    new Ajax.Request(url,
    {      
	method:'post',
	parameters: {messageId: id},
	onCreate: function() {
	 showWaitDialog();
	},
	onSuccess: function(transport){
	hideWaitDialog();
  	  j = eval('('+transport.responseText+')');
 	  document.getElementById('message').innerHTML= j.message;  
	},
	onFailure: function(){ alert('Something went wrong...') }
     });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "div_dutyLeave" DIV
//
//Author : Aditi Miglani
// Created on : (04.11.2011)
// Copyright 2011-2012 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateDutyLeaveValues(id) {
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetDutyLeaveValue.php';   
    document.getElementById('div_dutyLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText); 
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_dutyLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}


function populateMedicalLeaveValues(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetMedicalLeaveValue.php';   
    
    document.getElementById('div_medicalLeave').innerHTML ='';
    new Ajax.Request(url,
    {      
		method:'post',
		parameters: {id: id },
		onCreate: function() {
			showWaitDialog();
		},
		onSuccess: function(transport){
		    hideWaitDialog(true); 
		    var ret=trim(transport.responseText);  
		    var retArray=ret.split('!~!');  
		    document.getElementById('div_medicalLeave').innerHTML = trim(retArray[0]);
		             //displayWindow('divMessage',200,200); 
		},
	onFailure: function(){ alert('Something went wrong...') }
   });
}


function photoUpload(src){
    d = new Date();
    rndNo = d.getTime();
    document.getElementById('studentImageId').setAttribute('src',src+'?'+rndNo);
    //alert(document.getElementById('studentImageId').src);
}
/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick(){
        var idArray = this.id.split('_');
       
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
   
        //refreshes data for this tab
        refreshStudentData("<?php echo $_REQUEST['id']; ?>",document.getElementById('studyPeriod').value,tabNumber);
    }


//Global variables for classId countres for different tabs
var gcId=-1;
var tcId=-1;
var mcId=-1;
var atcId=-1;
var ffcId=-1;
var rcId=-1;
var frcId=-1;
var mrcId=-1;
//var cgId=-1;
//var gcaId=-1;
var ocId=-1;
var grdcId=-1;
var mentorId=-1;

//this function is uded to refresh tab data based uplon selection of study periods
function refreshStudentData(studentId,classId,tabIndex){
  
	//get the data of course based upon selected study period
	if(tabIndex==3 && classId!=gcId){
      //  alert("group");
		var groupData=refreshGroupData(studentId,classId);
		gcId=classId;
		return;
    }
    
    
	//get the data of time table based upon selected study period
	if(tabIndex==5 && classId!=tcId){

		var timeTableData=refreshTimeTableData(studentId,classId);
		tcId=classId;
		return;
    }
    

	//get the data of grade based upon selected study period
	if(tabIndex==6 && classId!=mcId){

		var gradeData=refreshGradeData(studentId,classId);
		mcId=classId;
		return;
    }
    
     //get the data of attendance based upon selected study period
	if(tabIndex==7 && classId!=atcId){

		 var attendanceData=refreshAttendanceData(studentId,classId);
		atcId=classId;
		return;
    }
   
   

    //get the data of fees based upon selected study period
	if(tabIndex==8 && classId!=ffcId){
		
		var timeTableData=refreshFeesResultData(studentId,classId);
		ffcId=classId;
		return;
    }
    
    
    //get the data of resource based upon selected study period
	if(tabIndex==9 && classId!=rcId){

		var resourceData=refreshResourceData(studentId,classId);
		rcId=classId;
		return;
    }
    

   //get the data of final result based upon selected study period
   if(tabIndex==10 && classId!=frcId){

		var timeTableData=refreshFinalResultData(studentId,classId);
		frcId=classId;
		return;
    }
    

   //get the data of offence based upon selected study period
   if(tabIndex==11 && classId!=ocId){
	var timeTableData=refreshOffenceData(studentId,classId);
	ocId=classId;
	return;
    }
  
    if(tabIndex==13 && classId!=mrcId){
       refreshMessageMedium2(studentId);
       mrcId=classId;
       return;
    }
    
    if(tabIndex==14 && classId!=grdcId){
       refreshGradeCardData(studentId,classId);
       grdcId=classId;
       return;
    }
     if(tabIndex==15 && classId!=mentorId){
      refreshMentorComments(studentId);  
      mentorId=classId;
      return;
    }
  
}


function refreshMentorComments(studentId){

  url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/Student/scAjaxMentorComments.php';

  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false),
                        new Array('commentDate','Date','width="12%" align="left"',true),
                        new Array('periodName','Study Period','width="10%" align="left"',true),
                        new Array('employeeName','Employee Name','width="15%" align="left"',true),
                        new Array('employeeCode','Employee Code','width="15%" align="left"',true),
                        new Array('comments','Comments','width="46%" align="left"',true)
                      );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj15 = new initPage(url,recordsPerPage,linksPerPage,1,'','commentDate','DESC','mentorCommentsDiv','','',true,'listObj15',tableColumns,'','','&studentId='+studentId);
 sendRequest(url, listObj15, '')
}

//this function fetches records corresponding to student grades detail
function refreshGradeCardData(studentId,classId){

     url = '<?php echo HTTP_LIB_PATH;?>/Student/scAjaxStudentGradesInfo.php';
     var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false),
                            new Array('subjectCode','Subject Code','width="8%" valign="middle"',false),
                            new Array('subjectName','Subject Name','width="40%" valign="middle"',false) ,
                            new Array('credits','Credits','width="12%" valign="middle" align="right"',false),
                            new Array('gradeLabel','Grade','width="11%" valign="middle" align="right"',false),
                            new Array('periodName','Study Period','width="10%" valign="middle" align="right"',false)
                       );
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj7 = new initPage(url,recordsPerPage,linksPerPage,1,'','periodName','ASC','finalGradesDiv','','',true,'listObj7',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
    sendRequest(url, listObj7, '')
}


function refreshGroupData(studentId,classId){
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGroup.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
			new Array('studyPeriod','Study Period','width="14%" align="left"',true),
                        new Array('groupName','Group Name','width="22%" align="left"',true),
                        new Array('groupTypeName','Group Type','width="12%" align="left"',true), 
                        new Array('groupTypeCode','Group Type Code','width="22%" align="left"',true)
						
                       );
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','studyPeriod','ASC','courseResultDiv','','',true,'listObj1',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj1, '',true )
 
}
 

//refresh message data on click 
function refreshMessageMedium2(studentId){  
         var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetMessageDetail.php';
    sortOrderBy ="ASC";
    sortField ="dated";
   var tableColumns   = new Array(new Array('srNo','#','width="2%"',false),
                               new Array('dated','Message Date','width="11%"  align="center"',true),
                               new Array('userName','Sent By','width="12%"  align="left"',true),  
                               new Array('messageType', 'Message Medium', 'width="15%" align="left"',true),
                               new Array('message','The Message','width="50%"  align="left"',true),
                               new Array('action1','Message Detail','width="8%"  align="center"',false));
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj14 = new initPage(url,recordsPerPage,linksPerPage,1,'','messageFrm','ASC','messageCorrespondenceResultDiv','','',true,'listObj14',tableColumns,'','','&studentId='+studentId+'&ClassId='+document.getElementById('studyPeriod').value+'&messageMedium='+document.getElementById('MessageMedium1').value+'&roleType='+document.getElementById('roleType').value+'&messagebox1='+document.getElementById('messagebox1').value);
   sendRequest(url,listObj14,'');


if(listObj14.totalRecords!=0)
	document.getElementById('showMessageSearch').style.display='';
 else
	document.getElementById('showMessageSearch').style.display='none';
 

} 


//this function fetches records corresponding to student grades/marks
function refreshGradeData(studentId,classId){

  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentMarks.php';
  var tableColumns1 = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('subjectName','Subject','width="15%" align="left"',true),
                        new Array('testTypeName','Type','width="12%" align="left"',true), 
                        new Array('testDate','Date','width="8%" align="center"',true),
                        new Array('employeeName','Teacher','width="16%" align="left"',true),
						new Array('studyPeriod','Study Period','width="12%" align="left"',true),
                        new Array('testName','Test Name','width="10%" align="left"',true),
                        new Array('totalMarks','M.M.','width="6%" align="right"',true),
                        new Array('obtainedMarks','Scored','width="8%" align="right"',true),
                        new Array('colorCode','Color','width="6%" align="left"',true)
                       );
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName','ASC','gradeResultDiv','','',true,'listObj2',tableColumns1,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj2, '',true )
 document.getElementById('saveDiv1').style.display='';
 
}

//this variable is used to determine whether group wise or 
//consolidated attendance view is required
//Modified By : Dipanjan Bhattacharjee
//Date: 06.10.2009
var attendanceConsolidatedView=1;
var viewType=0;
//this function fetches records corresponding to student attendance
function refreshAttendanceData(studentId,classId){
        var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php';  
        //if consolidated view is not required
        if(attendanceConsolidatedView==1){ 
            var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="20%" align="left"',true),
                                new Array('periodName','Study Period','width="14% align="left"',true), 
                                new Array('groupName','Group','width="8%" align="left"',true),
                                new Array('employeeName','Teacher','width="15%" align="left"',true),
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',false),
								new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
        }
        else{
            var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="12% align="left"',true), 
                                new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',false),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',false),
								new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',false),
                                new Array('delivered','Delivered','width="10%" align="right"',false),
                                new Array('per','%age','width="10%" align="right"',false)
                               );
        }
        
        //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
        listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj3',tableColumns2,'','','&studentId='+studentId+'&rClassId='+classId+'&startDate2='+document.addForm.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
        sendRequest(url, listObj3, '',true );
        document.getElementById('printDiv2').style.display='';
}

function toggleAttendanceDataFormat(studentId,classId){
 var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentAttendanceList.php';
 //if consolidated view is not required
        if(viewType==1){ 
            var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="14% align="left"',true), 
                                new Array('groupName','Group','width="8%" align="left"',true),
                                new Array('employeeName','Teacher','width="15%" align="left"',true),
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',true),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',true),
                                new Array('delivered','Delivered','width="10%" align="right"',true),
                                new Array('per','%age','width="10%" align="right"',true)
                               );
        }
        else{
            var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('subjectName1','Subject','width="25%" align="left"',true),
                                new Array('periodName','Study Period','width="12% align="left"',true), 
                                new Array('employeeName','Teacher','width="15%" align="left"',true), 
                                new Array('fromDate','From','width="8%" align="center"',true),
                                new Array('toDate','To','width="8%" align="center"',true),
                                new Array('attended','Attended','width="10%" align="right"',true),
                                new Array('leaveTaken','Duty Leaves','width="10%" align="right"',true),
								new Array('medicalLeaveTaken','Medical Leaves','width="10%" align="right"',true),
                                new Array('delivered','Delivered','width="10%" align="right"',true),
                                new Array('per','%age','width="10%" align="right"',true)
                               );
        }
        
        attendanceConsolidatedView=viewType;
        if(viewType==1){
            viewType=0;
            
            document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/consolidated.gif" />';
            document.getElementById('consolidatedDiv').title='Consolidated View';    
        }
        else{
            viewType=1;
            //document.getElementById('consolidatedDiv').innerHTML='Detailed View';
            document.getElementById('consolidatedDiv').innerHTML='<input type="image" name="imageField" src="'+globalCurrentThemePath+'/detailed.gif" />';
            document.getElementById('consolidatedDiv').title='Detailed View';
        }

        listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName1','ASC','attendanceResultDiv','','',true,'listObj3',tableColumns2,'','','&studentId='+studentId+'&rClassId='+classId+'&startDate2='+document.addForm.startDate2.value+'&consolidatedView='+attendanceConsolidatedView);
        sendRequest(url, listObj3, '',true );
        document.getElementById('printDiv2').style.display='';
} 

//this function fetches records corresponding to student fees detail
function refreshFeesResultData(studentId,classId){
	
   url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentFees.php';
 
   var tableColumns   = new Array(new Array('srNos',             '#',                    'width="2%"',false),
                               new Array('receiptDate',         'Receipt Date',         'width="15%"  align="center"',true),  
                               new Array('receiptNo',           'Receipt',              'width="10%" align="left"',true),
                               new Array('className',           'Fee Class',            'width="15%" align="left"',true),  
                               new Array('cycleName',           'Fee Cycle',            'width="12%"  align="left"',true),  
                               new Array('installmentCount',    'Installment',          'width="12%" align="left"',true), 
                               new Array('discountedFeePayable','Payable<br>(Rs.)',     'width="10%" align="right"',false), 
                               new Array('amountPaid',          'Paid<br>(Rs.)',        'width="8%"  align="right"',false), 
                               new Array('previousDues',        'Outstanding<br>(Rs.)', 'width="12%" align="right"',false),
                               new Array('instStatus',          'Instrument',           'width="12%" align="left"',false), 
                               new Array('retStatus',           'Status',               'width="12%" align="left"',false));  
  
   //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
   listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','receiptDate','ASC','feesResultsDiv','','',true,'listObj6',tableColumns,'','','&studentId='+studentId+'&classId='+classId);
   sendRequest(url, listObj6, '')
} 

//this function fetches records corresponding to student attendance
function refreshResourceData(studentId,classId){
	
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentResource.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
                            new Array('subject','Subject Code','width="12%" valign="middle"',true) , 
                            new Array('description','Description','width="15%" valign="middle"',true), 
                            new Array('resourceName','Type','width="8%" valign="middle"',true), 
                            new Array('postedDate','Date','width="8%"  align="center" valign="middle"',true),
                            new Array('resourceLink','Link','width="12%" align="left" valign="middle"',false),
                            new Array('attachmentLink','Attachment','width="7%" valign="middle" align="center"',false),
                            new Array('employeeName','Creator','width="10%" align="left" valign="middle"',true)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','subject','ASC','resourceResultsDiv','','',true,'listObj4',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId+'&searchbox='+document.getElementById('searchbox').value);
 sendRequest(url, listObj4, '')

 if(listObj4.totalRecords!=0)
	document.getElementById('showResourceSearch').style.display='';
 else
	document.getElementById('showResourceSearch').style.display='none';
 

} 

//this function fetches records corresponding to student final exam
function refreshFinalResultData(studentId,classId){
	
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentResult.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
			    new Array('periodName','Study Period','width="10%" valign="middle"',true),
                            new Array('subjectCode','Course','width="40%" valign="middle"',true) , 
			    new Array('attendance','Attendance','width="12%" valign="middle" align="right"',true),                           
			    new Array('preComprehensive','Internal','width="12%" valign="middle" align="right"',true), 
			   new Array('Comprehensive','External','width="12%" valign="middle" align="right"',true)
                            
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj5 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectCode','ASC','finalResultsDiv','','',true,'listObj5',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj5, '')
 
 if(listObj5.totalRecords!=0)
	document.getElementById('printDiv3').style.display='';
 else
	document.getElementById('printDiv3').style.display='none';
 
} 
//this function fetches records corresponding to student offence
function refreshOffenceData(studentId,classId){
	
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentOffence.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
							new Array('offenseName','Offense','width="15%" valign="middle"',true),
                            new Array('offenseDate','Date','width="10%" valign="middle"',true) , 
							new Array('periodName','Study Period','width="13%" valign="middle"',true) , 
							new Array('reportedBy','Reported By','width="15%" valign="middle"',true) , 
                            new Array('remarks','Remarks','width="50%" valign="middle" align="left"',true) 
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array

 listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','offenceResultsDiv','','',true,'listObj6',tableColumns,'','','&studentId='+studentId+'&rClassId='+classId);
 sendRequest(url, listObj6, '')
} 

//this function fetches records corresponding to student attendance
function refreshTimeTableData(studentId,classId){

  currentClassId = "<?php echo $studentDataArr[0]['classId']?>";	 
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentTimeTable.php';
  new Ajax.Request(url,
   {
     method:'post',
     parameters: {
         currentClassId: (currentClassId),studentId: (studentId),classId: (classId)
         },
     onCreate: function() {
         showWaitDialog(true);
     },
     onSuccess: function(transport){
             hideWaitDialog(true);
             document.getElementById('timeTableResultDiv').innerHTML=trim(transport.responseText);
      },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
   
} 


function getAttendance(studentId,startDate,endDate) {

  
   var attendanceData=refreshAttendanceData(studentId,document.getElementById('studyPeriod').value);  
}
 
 function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg');

      var fl=0;
      var ln=extArr.length;
      
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}


function validateAll(frm){
    
    var isActive=0;
    if("<?php echo $sessionHandler->getSessionVariable('PERSONAL_INFO') ?>"==1)  {
       if(!validateStudentDetailsForm(frm)){
         showTab('dhtmlgoodies_tabView1',0); 
         return false;
       }
       isActive=1;
    }
  
    if("<?php echo $sessionHandler->getSessionVariable('PARENTS_INFO') ?>"==1)  {            
         if(!validateParentDetailsForm(frm)){
            showTab('dhtmlgoodies_tabView1',1); 
            return false; 
         }
    }
    
    if("<?php echo $sessionHandler->getSessionVariable('ADMINISTRATIVE') ?>"==1)  {            
         if(!validateAdditionalDetailsForm(frm)){
            showTab('dhtmlgoodies_tabView1',4); 
            return false; 
         }
    }
    
     
    if("<?php echo $sessionHandler->getSessionVariable('MISC_INFO') ?>"==1)  {            
        if(!validateMiscellaneousForm(frm)){
           showTab('dhtmlgoodies_tabView1',12); 
           return false; 
        }
    }
    if(isActive==1) {
       if(document.addForm.isActive.checked==false) {
          msg = confirm("<?php echo UPDATE_STUDENT_STATUS; ?>");
          if(msg == false) {
             return false;      
          }           
       }   
    }    
    initAdd();
    editStudentProfile();
}

function validateStudentDetailsForm(frm) {

            var fieldsArray = new Array(new Array("studentName","<?php echo STUDENT_FIRST?>"));
            
             /* new Array("studentNo","Enter Contact No."));
		new Array("country","<?php echo STUDENT_NATIONALITY?>"),
		new Array("studentCategory","<?php echo STUDENT_DETAIL_CATEGORY?>")); */

           var len = fieldsArray.length;
	   var frm = document.addForm;
	
      for(i=0;i<len;i++) {
		 
        if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
        
			messageBox(fieldsArray[i][1],fieldsArray[i][0]);
            return false;
            break;
        }
		else if(fieldsArray[i][0]=="studentEmail"){
        
			if(!isEmail(eval("frm."+(fieldsArray[i][0])+".value"))) //if not valid email format
            {
                alert("<?php echo STUDENT_DETAIL_VALID_EMAIL?>");
				showTab('dhtmlgoodies_tabView1',0);
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;  
            }
			else{
				unsetAlertStyle(fieldsArray[i][0]);
			}
        }
        else{
            
			unsetAlertStyle(fieldsArray[i][0]);
        } 
    }
	if(document.getElementById('studentReg').value =='') {
		messageBox("Enter College Reg No.");
		document.getElementById("studentReg").focus();
		return false;
	}
    
    if("<?php echo $sessionHandler->getSessionVariable('INSTITUTE_ADMIT_STUDENT_OPTIONAL_FIELD') ?>" ==0){    
       if(document.getElementById('entranceExam').value =='') {
          messageBox("Select Exam");
          document.getElementById("entranceExam").focus();
          return false;
       }
    }
 
    document.getElementById('studentReg').value = trim(document.getElementById('studentReg').value);
    document.getElementById('studentRoll').value = trim(document.getElementById('studentRoll').value);
    document.getElementById('studentUniversityNo').value = trim(document.getElementById('studentUniversityNo').value);
    document.getElementById('studentUniversityRegNo').value = trim(document.getElementById('studentUniversityRegNo').value);
    
    if(trim(document.getElementById('studentReg').value)!='') {
   if(!isAlphaNumericCustom(trim(document.getElementById('studentReg').value),'a-z,0-9,&-_./+,{}[]()')) {
      messageBox("<?php echo STUDENT_REGISTRATION_NO; ?>");
      document.getElementById('studentReg').focus();
      return false; 	
         }
      }
   if(trim(document.getElementById('studentRoll').value)!='') {
   if(!isAlphaNumericCustom(trim(document.getElementById('studentRoll').value),'a-z,0-9,&-_./+,{}[]()')) {
      messageBox("<?php echo STUDENT_ROLL_NO; ?>");
      document.getElementById('studentRoll').focus();
      return false; 	
         }
      }
   if(trim(document.getElementById('studentUniversityNo').value)!='') {
   if(!isAlphaNumericCustom(trim(document.getElementById('studentUniversityNo').value),'a-z,0-9,&-_./+,{}[]()')) {
      messageBox("<?php echo STUDENT_UNI_ROLL_NO; ?>");
      document.getElementById('studentUniversityNo').focus();
      return false; 	
         }
      }
   if(trim(document.getElementById('studentUniversityRegNo').value)!='') {
   if(!isAlphaNumericCustom(trim(document.getElementById('studentUniversityRegNo').value),'a-z,0-9,&-_./+,{}[]()')) {
      messageBox("<?php echo STUDENT_UNIVERSITY_REGISTRATION_NO; ?>");
      document.getElementById('studentUniversityRegNo').focus();
      return false; 	
         }
      }
   
    if(document.getElementById('admitOptionalField').value ==1){
		if(document.getElementById('studentDomicile').value =='') {
			messageBox("Select Domicile");
			document.getElementById("studentDomicile").focus();
			return false;
		}
       
		if(document.getElementById('studentCategory').value =='') {
			messageBox("Select Category");
			document.getElementById("studentCategory").focus();
			return false;
		}
		if(document.getElementById('country').value =='') {
			messageBox("Select Nationality");
			document.getElementById("country").focus();
			return false;
		}

	}
    
	if(document.getElementById('alternateEmail').value){
		if(!isEmail(eval("frm."+('alternateEmail')+".value"))){  //if not valid email format

			messageBox("<?php echo STUDENT_DETAIL_VALID_EMAIL?>");
			showTab('dhtmlgoodies_tabView1',0);
			eval("frm."+('alternateEmail')+".focus();");
			return false;
		}
	}
	
    if(!isPhone(eval("frm."+('studentNo')+".value"))){  //if not valid email format
		messageBox("<?php echo STUDENT_VALID_CONTACT_NO?>");
		showTab('dhtmlgoodies_tabView1',0);
		eval("frm."+('studentNo')+".focus();");
		return false;
	}
	if(document.getElementById('studentMobile').value){

		if(!isPhone(eval("frm."+('studentMobile')+".value"))){  //if not valid email format

			messageBox("<?php echo STUDENT_VALID_MOBILE?>");
			showTab('dhtmlgoodies_tabView1',0);
			eval("frm."+('studentMobile')+".focus();");
			return false;
		}
	}
	if((document.getElementById("studentMonth").value !='') && (document.getElementById("studentDate").value !='') && (document.getElementById("studentYear").value!='')){
		BirthYear = document.getElementById("studentMonth").value + "-" + document.getElementById("studentDate").value + "-" + document.getElementById("studentYear").value;
		if (!isDate1(BirthYear)) {

				showTab('dhtmlgoodies_tabView1',0);
				document.getElementById("studentYear").focus();
				return false;
			}

		birthDate = document.getElementById("studentYear").value+'-'+document.getElementById("studentMonth").value+'-'+document.getElementById("studentDate").value;
		currentDate = "<?php echo date('Y-m-d')?>";
		if(dateCompare(birthDate,currentDate)==1){
			messageBox("<?php echo STUDENT_VALID_DATEOFBIRTH?>");
			showTab('dhtmlgoodies_tabView1',0);
			document.addForm.studentYear.focus();
			return false;
		}
		 
	}

	if(document.getElementById("studentUser").value!='')
	{
		username = document.getElementById("studentUser").value;
		if(!validUsername(username))
		{
			messageBox("<?php echo SD_STUDENT_VALID_USER?>","studentUser");
			showTab('dhtmlgoodies_tabView1',0);
			document.addForm.studentUser.focus();
			return false;
		}
	}
	if(isEmpty(document.getElementById("studentPassword").value) && (document.getElementById("studentUser").value!=''))
	{
		messageBox("<?php echo SD_STUDENT_PASSWORD?>","studentPassword");
		showTab('dhtmlgoodies_tabView1',0);
		document.addForm.studentPassword.focus();
		return false;
	}
	else
	{
		if(isEmpty(document.getElementById("studentUser").value) && (document.getElementById("studentPassword").value!=''))
		{
			messageBox("<?php echo SD_STUDENT_USER?>","studentUser");
			showTab('dhtmlgoodies_tabView1',0);
			document.addForm.studentUser.focus();
			return false;
		}
		else
		{
			if((document.getElementById("studentPassword").value=='1****1')){

				stuPwd = document.getElementById("studentPassword").value;
				if(stuPwd.length<6)
				{
					messageBox("<?php echo SD_STUDENT_MAX_PASSWORD?>","studentPassword");
					showTab('dhtmlgoodies_tabView1',0);
					document.addForm.studentPassword.focus();
					return false;
				}
			}
		}
	}
    
    
    if((document.getElementById("studentAdmissionMonth").value !='') && (document.getElementById("studentAdmissionDate").value !='') && (document.getElementById("studentAdmissionYear").value!='')){
        AdmYear = document.getElementById("studentAdmissionMonth").value + "-" + document.getElementById("studentAdmissionDate").value + "-" + document.getElementById("studentAdmissionYear").value;
        if (!isDate1(AdmYear)) {
                showTab('dhtmlgoodies_tabView1',0);
                document.getElementById("studentAdmissionYear").focus();
                return false;
            }

        admDate = document.getElementById("studentAdmissionYear").value+'-'+document.getElementById("studentAdmissionMonth").value+'-'+document.getElementById("studentAdmissionDate").value;
        currentDate = "<?php echo date('Y-m-d')?>";
        if(!dateDifference(admDate,currentDate,'-')){
            messageBox("Date of admission can not be greater than current date");
            showTab('dhtmlgoodies_tabView1',0);
            document.addForm.studentAdmissionYear.focus();
            return false;
        }
    }

    if(trim(document.getElementById("studentPhoto").value)!='') {
       if(!checkFileExtensionsUpload(trim(document.getElementById("studentPhoto").value))){
         messageBox("Only gif jpg and jpeg formats are allowed");
         document.getElementById("studentPhoto").focus();  
         return false;
       }
    }
	//if(document.getElementById("studentAdmissionMonth").value !='') 

		

	return true;
}

function validUsername(username)
{
	var error = "";
	var illegalChars = /\W\/-/; // allow letters, numbers, and underscores
	if (illegalChars.test(username)) 
	{
		return false;
	} 
	else 
	{
		return true;
	}

}
	function validateParentDetailsForm(frm) {


//,new Array("fatherName","<?php echo STUDENT_FATHER?>"),new Array(f"motherName","<?php echo STUDENT_MOTHER?>")
	if(trim(document.getElementById("fatherName").value)=='')
	{
		alert("<?php echo STUDENT_FATHER?>");
		showTab('dhtmlgoodies_tabView1',1);
		document.addForm.fatherName.focus();
		return false;
	}

	/*if(trim(document.getElementById("motherName").value)=='')
	{
		alert("<?php echo STUDENT_MOTHER?>");
		showTab('dhtmlgoodies_tabView1',1);
		document.addForm.motherName.focus();
		return false;
	}
*/
	if(document.getElementById("fatherEmail").value!='')
	{
		fatherEmail = document.getElementById("fatherEmail").value;
		if(!isEmail(fatherEmail))
		{
			messageBox("<?php echo SD_FATHER_VALID_EMAIL?>","fatherEmail");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.fatherEmail.focus();
			return false;
		}
		 
	}
	if(document.getElementById("motherEmail").value!='')
	{
		motherEmail = document.getElementById("motherEmail").value;
		if(!isEmail(motherEmail))
		{
			messageBox("<?php echo SD_MOTHER_VALID_EMAIL?>","motherEmail");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.motherEmail.focus();
			return false;
		}
		 
	}
	if(document.getElementById("guardianEmail").value!='')
	{
		guardianEmail = document.getElementById("guardianEmail").value;
		if(!isEmail(guardianEmail))
		{
			messageBox("<?php echo SD_GUARDIAN_VALID_EMAIL?>","guardianEmail");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.guardianEmail.focus();
			return false;
		}
		 
	}
	

	if(document.getElementById("fatherUserName").value!='')
	{
		username = document.getElementById("fatherUserName").value;
		if(!validUsername(username))
		{
			messageBox("<?php echo SD_FATHER_VALID_USER?>","fatherUserName");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.fatherUserName.focus();
			return false;
		}
		else
		{
			if(isEmpty(document.getElementById("fatherPassword").value))
			{
				messageBox("<?php echo SD_FATHER_PASSWORD?>","fatherPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.fatherPassword.focus();
				return false;
			}
			else
			{
				faPwd = document.getElementById("fatherPassword").value;
				if(faPwd.length<6)
				{
					messageBox("<?php echo SD_FATHER_MAX_PASSWORD?>","fatherPassword");
					showTab('dhtmlgoodies_tabView1',1);
					document.addForm.fatherPassword.focus();
					return false;
				}
			}
		}
	}
	
	if(isEmpty(document.getElementById("fatherUserName").value) && (document.getElementById("fatherPassword").value!=''))
	{
		messageBox("<?php echo SD_FATHER_USER?>","fatherUserName");
		showTab('dhtmlgoodies_tabView1',1);
		document.addForm.fatherUserName.focus();
		return false;
	}
	else
	{
		if(document.getElementById("fatherPassword").value!='')
		{
			faPwd = document.getElementById("fatherPassword").value;
			if(faPwd.length<6)
			{
				messageBox("<?php echo SD_FATHER_MAX_PASSWORD?>","fatherPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.fatherPassword.focus();
				return false;
			}
		}
	}
	 
		 

	if(document.getElementById("motherUserName").value!='')
	{
		username = document.getElementById("motherUserName").value;
		if(!validUsername(username))
		{
			messageBox("<?php echo SD_MOTHER_VALID_USER?>","motherUserName");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.motherUserName.focus();
			return false;
		}
		else
		{
			if(isEmpty(document.getElementById("motherPassword").value))
			{
				messageBox("<?php echo SD_MOTHER_PASSWORD?>","motherPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.motherPassword.focus();
				return false;
			}
			else
			{
				moPwd = document.getElementById("motherPassword").value;
				if(moPwd.length<6)
				{
					messageBox("<?php echo SD_MOTHER_MAX_PASSWORD?>","motherPassword");
					showTab('dhtmlgoodies_tabView1',1);
					document.addForm.motherPassword.focus();
					return false;
				}
			}
		}
	}

	 
	if(isEmpty(document.getElementById("motherUserName").value) && (document.getElementById("motherPassword").value!=''))
	{
		messageBox("<?php echo SD_MOTHER_USER?>","motherUserName");
		showTab('dhtmlgoodies_tabView1',1);
		document.addForm.motherUserName.focus();
		return false;
	}
	else
	{
		if(document.getElementById("motherPassword").value!='')
		{
			moPwd = document.getElementById("motherPassword").value;
			if(moPwd.length<6)
			{
				messageBox("<?php echo SD_MOTHER_MAX_PASSWORD?>","motherPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.motherPassword.focus();
				return false;
			}
		}
	}
	 	 
			 
	if(document.getElementById("guardianUserName").value!='')
	{
		username = document.getElementById("guardianUserName").value;
		if(!validUsername(username))
		{
			messageBox("<?php echo SD_GUARDIAN_VALID_USER?>","guardianUserName");
			showTab('dhtmlgoodies_tabView1',1);
			document.addForm.guardianUserName.focus();
			return false;
		}
		else
		{
			if(isEmpty(document.getElementById("guardianPassword").value))
			{
				messageBox("<?php echo SD_GUARDIAN_PASSWORD?>","guardianPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.guardianPassword.focus();
				return false;
			}
			else
			{
				gaPwd = document.getElementById("guardianPassword").value;
				if(gaPwd.length<6)
				{
					messageBox("<?php echo SD_GUARDIAN_MAX_PASSWORD?>","guardianPassword");
					showTab('dhtmlgoodies_tabView1',1);
					document.addForm.guardianPassword.focus();
					return false;
				}
			}
		}
	}
	 
	if(isEmpty(document.getElementById("guardianUserName").value) && (document.getElementById("guardianPassword").value!=''))
	{
		messageBox("<?php echo SD_GUARDIAN_USER?>","guardianUserName");
		showTab('dhtmlgoodies_tabView1',1);
		document.addForm.guardianUserName.focus();
		return false;
	}
	else
	{
		if(document.getElementById("guardianPassword").value!='')
		{
			gaPwd = document.getElementById("guardianPassword").value;
			if(gaPwd.length<6)
			{
				messageBox("<?php echo SD_GUARDIAN_MAX_PASSWORD?>","guardianPassword");
				showTab('dhtmlgoodies_tabView1',1);
				document.addForm.guardianPassword.focus();
				return false;
			}
		}
	}
	return true;

	}

	function validateAdditionalDetailsForm(frm) {

	  // try {
            if (typeof document.addForm.countRecord === "undefined") {
              return true;
            }
            
            flag=0; 
	        flag1=0; 
	        flag2=0; 
	        for(cnt=1;cnt<=document.addForm.countRecord.value;cnt++){
	            
		        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		        if(document.getElementById('marks'+cnt).value){

			        if (!reg.test(document.getElementById('marks'+cnt).value)){

			           
			           
			           eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			          
			           showTab('dhtmlgoodies_tabView1',4);
			           //eval("document.getElementById('marks"+cnt+"').focus()");
			         
			           flag++;
			        }
			        else{
				        
				        eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
			        }
		        }
		        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		        if(document.getElementById('maxMarks'+cnt).value){

			        if (!reg.test(document.getElementById('maxMarks'+cnt).value)){

			           eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
			           showTab('dhtmlgoodies_tabView1',4);
			           eval("document.getElementById('maxMarks"+cnt+"').focus()");
			           flag1++;
			        }
			        else{
			        
				        eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
			        }
		        }
		        if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){

			        flag2=1;	
		            alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
			        showTab('dhtmlgoodies_tabView1',4);
			        eval("document.getElementById('maxMarks"+cnt+"').focus()");
		            //document.getElementById('marks'+cnt).focus();
			        eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			        eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
			          
		        }
		        else{
		        
		          eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		          eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
		        } 
	        }
             
	        if(flag>0){
	        
		         alert("<?php echo ENTER_MARKS_TO_NUM; ?>");
		         
		         showTab('dhtmlgoodies_tabView1',4); 
		         return false;
	        }
	        else if(flag1>0){
	        
		         alert("<?php echo ENTER_MAX_MARKS_TO_NUM; ?>");
		         showTab('dhtmlgoodies_tabView1',4);
		         return false;
	        }
         <?php  
         if($optionalField == 0){             ?>	      
	        if(document.getElementById('completedGraduation').value == '') {
		        alert("<?php echo SELECT_COMPLETED_GRADUATION; ?>");
		        showTab('dhtmlgoodies_tabView1',4);
		        document.getElementById('completedGraduation').focus();
		        return false;
	        }       
             

	         if(document.getElementById('everStayedInHostel').value == '') {
		        alert("<?php echo SELECT_EVER_STAYED_HOSTEL; ?>");
		        showTab('dhtmlgoodies_tabView1',4);
		        document.getElementById('everStayedInHostel').focus();
		        return false;
	        }

	        if(document.getElementById('yearsInHostel').value) {
		        if(!isInteger(eval("frm."+('yearsInHostel')+".value"))){  //if not valid email format
			        messageBox("<?php echo STUDENT_VALID_YEARS?>");
			        showTab('dhtmlgoodies_tabView1',4);
			        eval("frm."+('yearsInHostel')+".focus();");
			        return false;
		        }
	        }           
            <?php  } ?>
         
	        return true; 
       //}  catch(e){ }
}

function validateMiscellaneousForm(frm) {
	if(document.getElementById('education').value == '') {
		alert("<?php echo SELECT_EDUCATION; ?>");
		showTab('dhtmlgoodies_tabView1',12);
		document.getElementById('education').focus();
		return false;
	}

	if(document.getElementById('medicalAttention').value == '') {
		alert("<?php echo SELECT_MEDICAL_ATTENTION; ?>");
		showTab('dhtmlgoodies_tabView1',12);
		document.getElementById('medicalAttention').focus();
		return false;
	}

	if(document.getElementById('loanAmount').value) {
		if(!isInteger(eval("frm."+('loanAmount')+".value"))){  //if not valid email format
			messageBox("<?php echo STUDENT_VALID_LOAN_AMOUNT?>");
			showTab('dhtmlgoodies_tabView1',12);
			eval("frm."+('loanAmount')+".focus();");
			return false;
		}
	}

	if(document.getElementById('workExperience').value == '') {
		alert("<?php echo SELECT_WORK_EXPERIENCE; ?>");
		showTab('dhtmlgoodies_tabView1',12);
		document.getElementById('workExperience').focus();
		return false;
	}

	return true;
}

function  download(str){    
var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

function initAdd() {
    document.getElementById('addForm').onsubmit=function() {
        document.getElementById('addForm').target = 'uploadTargetAdd';
    }
} 

function addSibling() {

 if(document.addForm.siblingRoll.value==''){
	
	alert("Please enter roll no.");
	document.addForm.siblingRoll.focus();
	return false;
 }
 url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitSibling.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studentName: (document.addForm.studentName.value),studentLName: (document.addForm.studentLName.value),studentId: (document.addForm.studentId.value), siblingRoll: (document.addForm.siblingRoll.value)},
			 onCreate:function(transport){ showWaitDialog(true);},
             onSuccess: function(transport){
              
			 hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				j= trim(transport.responseText).evalJSON();
				var tbHeadArray = new Array(new Array('srNo','#','width="5%"',''), new Array('fullName','Student Name','width="20%"','') , new Array('dateOfBirth','Date Of Birth','width="8%"',''));
				
  				printResultsNoSorting('results1', j.info, tbHeadArray);
				 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
				 return false;
				 //location.reload();
                     
               }
			   else
					alert(trim(transport.responseText));
				
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getMedicalAttention() {
	if(document.getElementById('medicalAttention').value == 0) {
		document.getElementById('natureAilment').value = '';
		document.getElementById('natureAilment').disabled = true;
		var obj1 = document.getElementById('familyAilment'); 
		var obj = document.getElementById('familyAilment').options.length;
		for (var h=0; h < obj; h++) {
			obj1[h].selected = false;
		}
		document.getElementById('familyAilment').disabled = true;
		document.getElementById('otherAilment').value = '';
		document.getElementById('otherAilment').disabled = true;
	}
	else {
		document.getElementById('natureAilment').disabled = false;
		document.getElementById('familyAilment').disabled = false;
		document.getElementById('otherAilment').disabled = false;
	}
}

function getHostelStayed() {
	if(document.getElementById('everStayedInHostel').value == 0) {
		document.getElementById('yearsInHostel').value = '';
		document.getElementById('yearsInHostel').disabled = true;
	}
	else {
		document.getElementById('yearsInHostel').disabled = false;
	}
}

function getEducation() {
	if(document.getElementById('education').value == 0) {
		document.getElementById('bankName').value = '';
		document.getElementById('loanAmount').value = '';
		document.getElementById('bankName').disabled = true;
		document.getElementById('loanAmount').disabled = true;
	}
	else {
		document.getElementById('bankName').disabled = false;
		document.getElementById('loanAmount').disabled = false;
	}
}

function getCompletedGraduation() {

	if(document.getElementById('completedGraduation').value == 1) {
		document.getElementById('writtenFinalExam').value = '';
		document.getElementById('writtenFinalExam').disabled = true;
		document.getElementById('resultDue').value = '';

		document.getElementById('rollNo3').value = '';
		document.getElementById('rollNo3').disabled=false;
		document.getElementById('session3').value = '';
		document.getElementById('session3').disabled=false;
		document.getElementById('institute3').value = '';
		document.getElementById('institute3').disabled=false;
		document.getElementById('board3').value = '';
		document.getElementById('board3').disabled=false;
		document.getElementById('educationStream3').value = '';
		document.getElementById('educationStream3').disabled=false;
		document.getElementById('maxMarks3').value = '';
		document.getElementById('maxMarks3').disabled=false;
		document.getElementById('marks3').value = '';
		document.getElementById('marks3').disabled=false;
		document.getElementById('percentage3').value = '';
		document.getElementById('percentage3').disabled=false;

		document.getElementById('rollNo4').value = '';
		document.getElementById('rollNo4').disabled=false;
		document.getElementById('session4').value = '';
		document.getElementById('session4').disabled=false;
		document.getElementById('institute4').value = '';
		document.getElementById('institute4').disabled=false;
		document.getElementById('board4').value = '';
		document.getElementById('board4').disabled=false;
		document.getElementById('educationStream4').value = '';
		document.getElementById('educationStream4').disabled=false;
		document.getElementById('maxMarks4').value = '';
		document.getElementById('maxMarks4').disabled=false;
		document.getElementById('marks4').value = '';
		document.getElementById('marks4').disabled=false;
		document.getElementById('percentage4').value = '';
		document.getElementById('percentage4').disabled=false;
	}
	else {
		document.getElementById('writtenFinalExam').value = 0;
		document.getElementById('writtenFinalExam').disabled = false;

		document.getElementById('rollNo3').value = '';
		document.getElementById('rollNo3').disabled=true;
		document.getElementById('session3').value = '';
		document.getElementById('session3').disabled=true;
		document.getElementById('institute3').value = '';
		document.getElementById('institute3').disabled=true;
		document.getElementById('board3').value = '';
		document.getElementById('board3').disabled=true;
		document.getElementById('educationStream3').value = '';
		document.getElementById('educationStream3').disabled=true;
		document.getElementById('maxMarks3').value = '';
		document.getElementById('maxMarks3').disabled=true;
		document.getElementById('marks3').value = '';
		document.getElementById('marks3').disabled=true;
		document.getElementById('percentage3').value = '';
		document.getElementById('percentage3').disabled=true;

		document.getElementById('rollNo4').value = '';
		document.getElementById('rollNo4').disabled=true;
		document.getElementById('session4').value = '';
		document.getElementById('session4').disabled=true;
		document.getElementById('institute4').value = '';
		document.getElementById('institute4').disabled=true;
		document.getElementById('board4').value = '';
		document.getElementById('board4').disabled=true;
		document.getElementById('educationStream4').value = '';
		document.getElementById('educationStream4').disabled=true;
		document.getElementById('maxMarks4').value = '';
		document.getElementById('maxMarks4').disabled=true;
		document.getElementById('marks4').value = '';
		document.getElementById('marks4').disabled=true;
		document.getElementById('percentage4').value = '';
		document.getElementById('percentage4').disabled=true;
	}

}

function getCoaching() {
	if(document.getElementById('coachingCenter').value == '') {
		document.getElementById('coachingManager').value = '';
		document.getElementById('coachingManager').disabled = true;
		document.getElementById('address').value = '';
		document.getElementById('address').disabled = true;
	}
	else {
		document.getElementById('coachingManager').disabled = false;
		document.getElementById('address').disabled = false;
	}
}

function getWorkExperience() {
	if(document.getElementById('workExperience').value == 1) {
		document.getElementById('department').disabled = false;
		document.getElementById('organization').disabled = false;
		document.getElementById('place').disabled = false;
	}
	else {
		document.getElementById('department').value = ''; 
		document.getElementById('department').disabled = true;
		document.getElementById('organization').value = ''; 
		document.getElementById('organization').disabled = true;
		document.getElementById('place').value = ''; 
		document.getElementById('place').disabled = true;
	}
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A STUDENT PROFILE
//
//Author : Rajeev Aggarwal
// Created on : (12.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editStudentProfile() {
         url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitEdit.php';
         formx = document.addForm;
         new Ajax.Request(url,
           {
             method:'post',
             parameters: $('#addForm').serialize(true),
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport) {
				hideWaitDialog(true);
                msg = trim(transport.responseText);  
                ret = msg.split("~");
                if(ret.length > 1 ) {
                   if("<?php echo SUCCESS;?>" == trim(ret[0])) {
                     messageBox(trim(ret[0]));
                   }
                   else {
                     messageBox(trim(ret[0]));   
                   }
                }
                else{
                   messageBox(trim(msg));
                }
                if(msg == "College reg no. already exists") {
                    document.getElementById('studentReg').className = 'inputboxRed'; 
                    document.addForm.studentReg.focus();
                    return false;
                }
                else if(msg == "University no. already exists") {
                     document.getElementById('studentUniversityNo').className = 'inputboxRed'; 
                     document.addForm.studentUniversityNo.focus();
                     return false;
                }
                else if(msg == "University reg no. already exists") {
                    document.getElementById('studentUniversityRegNo').className = 'inputboxRed'; 
                    document.addForm.studentUniversityRegNo.focus();
                    return false;
                }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function calculatePercentage(cnt){

	flag=0;
	flag1=0;
	flag2=0;
	if(document.getElementById('marks'+cnt).value!='' && document.getElementById('maxMarks'+cnt).value!=''){
		
		reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
		if (!reg.test(document.getElementById('marks'+cnt).value)){
		
		  flag=1;	
		  alert("<?php echo ENTER_MARKS_TO_NUM; ?>"); 
		  showTab('dhtmlgoodies_tabView1',4); 
		  //alert('22222');
		  document.getElementById('marks'+cnt).focus();
		  eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
		  return false;
		}
		else{
		
		  eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		}
		
		if (!reg.test(document.getElementById('maxMarks'+cnt).value)){

		  flag1=1;	
		  alert("<?php echo ENTER_MAX_MARKS_TO_NUM; ?>");
		  showTab('dhtmlgoodies_tabView1',4); 
		  document.getElementById('maxMarks'+cnt).focus();
		  eval("document.getElementById('maxMarks"+cnt+"').className = 'inputboxRed'");
		  return false;
		}
		else{
		
		  eval("document.getElementById('maxMarks"+cnt+"').className = 'inputbox1'");
		}

		if(parseFloat(document.getElementById('marks'+cnt).value)>parseFloat(document.getElementById('maxMarks'+cnt).value)){

			flag2=1;	
		    alert("<?php echo ENTER_MAX_MARKS_GREATER_MARKS; ?>");
			//document.getElementById('marks'+cnt).value=0;
			document.getElementById('percentage'+cnt).value=0;
			showTab('dhtmlgoodies_tabView1',4); 
		    document.getElementById('marks'+cnt).focus();
			eval("document.getElementById('marks"+cnt+"').className = 'inputboxRed'");
			  
		}
		else{
		
		  eval("document.getElementById('marks"+cnt+"').className = 'inputbox1'");
		} 
		if(flag==0 && flag1==0 && flag2==0){
		
			document.getElementById('percentage'+cnt).value = ((document.getElementById('marks'+cnt).value/document.getElementById('maxMarks'+cnt).value)*100).toFixed(2);
			
			 
		}
	}
}
//---------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Rajeev Aggarwal
// Created on : (17.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------
//id:id 
//type:states/city
//target:taget dropdown box

function autoPopulate(val,type,frm,fieldSta,fieldCty) {
    
   try { 
	    //alert(val+"--"+type+"--"+frm+"--"+fieldSta+"--"+fieldCty);
       url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
       var fieldState = document.getElementById(fieldSta);
       var fieldCity = document.getElementById(fieldCty);
       // alert(fieldState);
       if(frm=="Add"){
		    
           if(type=="states"){
		      
                fieldState.options.length=0;
                var objOption = new Option("Select","");
                fieldState.options.add(objOption); 
              
                var objOption = new Option("Select","");
                fieldCity.options.length=0;
                fieldCity.options.add(objOption); 
           }
	        
          else{
		      
                fieldCity.options.length=0;
                var objOption = new Option("Select","");
                fieldCity.options.add(objOption);
		      
          } 
       }
       else{                        //for edit
            if(type=="states"){
                document.addForm.correspondenceStates.options.length=0;
                var objOption = new Option("Select","");            
                 document.addForm.correspondenceStates.add(objOption); 
           }

          else{
                document.EditInstitute.city.options.length=0;
                var objOption = new Option("Select","");          
                document.EditInstitute.city.options.add(objOption);
          } 
       }
       
       new Ajax.Request(url,
               {
                 method:'post',
			     asynchronous:false,
                 parameters: {type: type,id: val},
                 onCreate: function() {
                     showWaitDialog(true); 
                 },
                 onSuccess: function(transport){
				       
                         hideWaitDialog(true);
                         j = eval('('+transport.responseText+')'); 
					    //alert(transport.responseText);
                         for(var c=0;c<j.length;c++){
                             if(frm=="Add"){
                                 if(type=="states"){
                                     var objOption = new Option(j[c].stateName,j[c].stateId);
                                     fieldState.options.add(objOption); 
                                 }
							    else if(type=="hostel"){
                                     var objOption = new Option(j[c].roomName,j[c].hostelRoomId);
                                     fieldCity.options.add(objOption); 
                                } 
							    else if(type=="busRoute"){
                                     var objOption = new Option(j[c].stopName,j[c].busStopId);
                                     fieldCity.options.add(objOption); 
                                } 
                                else{
                                     var objOption = new Option(j[c].cityName,j[c].cityId);
                                     fieldCity.options.add(objOption); 
                                } 
                              }
                          else{
                                if(type=="states"){
                                     var objOption = new Option(j[c].stateName,j[c].stateId);
                                     document.EditInstitute.states.options.add(objOption); 
                                 }
                                else{
                                     var objOption = new Option(j[c].cityName,j[c].cityId);
                                     document.EditInstitute.city.options.add(objOption); 
                                } 
                              }
                         }
                         
                       
                 },
                 onFailure: function(){ alert('Something went wrong...') }
               }); 
   }
   catch(e){ }
}

 
function copyText()
{
	if(document.addForm.sameText.checked==true)
	{
		 
		document.addForm.permanentAddress1.value    = document.addForm.correspondeceAddress1.value;
		document.addForm.permanentAddress1.disabled = true;

		document.addForm.permanentAddress2.value    = document.addForm.correspondeceAddress2.value;
		document.addForm.permanentAddress2.disabled = true;

		document.addForm.permanentPincode.value     = document.addForm.correspondecePincode.value;  
		document.addForm.permanentPincode.disabled  = true;

		document.addForm.permanentPhone.value       = document.addForm.correspondecePhone.value;
		document.addForm.permanentPhone.disabled    = true;

		for(i=document.addForm.correspondenceCountry.options.length-1;i>=0;i--)
		{
			if(document.addForm.correspondenceCountry.options[i].selected)
				document.addForm.permanentCountry.options[i].selected=true;
		}
		var abc = (document.addForm.correspondenceStates.options[document.addForm.correspondenceStates.selectedIndex].text);

		document.addForm.permanentStates.options.length=0;
		var objOption = new Option(abc,"1");
        document.addForm.permanentStates.options.add(objOption); 
		//document.addForm.permanentStates.options[0].selected=true;

		var abc1 = (document.addForm.correspondenceCity.options[document.addForm.correspondenceCity.selectedIndex].text);
		//alert(abc1);
		document.addForm.permanentCity.options.length=0;
		var objOption = new Option(abc1,"1");
        document.addForm.permanentCity.options.add(objOption); 
		//document.addForm.permanentCity.options[0].selected=true;

		document.addForm.permanentCountry.disabled=true;
		document.addForm.permanentStates.disabled=true;
		document.addForm.permanentCity.disabled=true;
		
	}
	else
	{
		document.addForm.permanentAddress1.disabled=false;
		document.addForm.permanentAddress2.disabled=false;
		document.addForm.permanentPincode.disabled=false;
		//document.getElementById('permCountry').innerHTML=document.addForm.correspondenceCountry.options[document.addForm.correspondenceCountry.selectedIndex].text;

		document.addForm.permanentCountry.disabled=false;
		document.addForm.permanentStates.disabled=false;
		document.addForm.permanentCity.disabled=false;
		document.addForm.permanentPhone.disabled=false;

		document.addForm.permanentCountry.options[0].selected=true;

		document.addForm.permanentStates.options.length=0;
		var objOption = new Option("SELECT","");
        document.addForm.permanentStates.options.add(objOption); 

		document.addForm.permanentCity.options.length=0;
		var objOption = new Option("SELECT","");
        document.addForm.permanentCity.options.add(objOption); 

	}
}





function printReport(className) {
	
	//alert(className);
	form = document.addForm;
	path='<?php echo UI_HTTP_PATH;?>/scStudentTimeTableReportPrint.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&className='+className;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=500, top=150,left=150");
}

function printMarksReport(className) {

	form = document.addForm;

	var name = document.getElementById('studyPeriod');
	path='<?php echo UI_HTTP_PATH;?>/studentMarksReportPrint.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&classId='+document.getElementById('studyPeriod').value+'&className='+name.options[name.selectedIndex].text+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
}

function csvMarksReport() {
	form = document.addForm;
	var name = document.getElementById('studyPeriod');
	path='<?php echo UI_HTTP_PATH;?>/studentMarksReportPrintCSV.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&classId='+document.getElementById('studyPeriod').value+'&className='+name.options[name.selectedIndex].text+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField;
	window.location = path;
}

function printAttendanceReport() {
	form = document.addForm;
	var name = document.getElementById('studyPeriod');
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrint.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&startDate2='+form.startDate2.value+'&classId='+document.getElementById('studyPeriod').value+'&className='+name.options[name.selectedIndex].text+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&consolidatedView='+attendanceConsolidatedView;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
}

function csvAttendanceReport() {
	form = document.addForm;
	var name = document.getElementById('studyPeriod');
	path='<?php echo UI_HTTP_PATH;?>/studentAttendanceReportPrintCSV.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&startDate2='+form.startDate2.value+'&classId='+document.getElementById('studyPeriod').value+'&className='+name.options[name.selectedIndex].text+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&consolidatedView='+attendanceConsolidatedView;
	window.location = path;
}
function csvResourceReport() {
	var form = document.addForm;
	var path='<?php echo UI_HTTP_PATH;?>/studentResourceReportPrintCSV.php?studentId='+form.studentId.value+'&classId='+document.getElementById('studyPeriod').value+'&sortField='+listObj4.sortField+'&sortOrderBy='+listObj4.sortOrderBy+'&searchbox='+document.getElementById('searchbox').value;
	window.location = path;
}

function printFinalResultReport(){

	form = document.addForm;

	var name = document.getElementById('studyPeriod');
	path='<?php echo UI_HTTP_PATH;?>/studentFinalResultReportPrint.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&classId='+document.getElementById('studyPeriod').value+'&className='+name.options[name.selectedIndex].text+'&sortOrderBy='+listObj5.sortOrderBy+'&sortField='+listObj5.sortField;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
}
function printFeesReport(className) {
	form = document.addForm;
	path='<?php echo UI_HTTP_PATH;?>/scStudentFeesReportPrint.php?studentId='+form.studentId.value+'&studentName='+form.studentName.value+'&studentLName='+form.studentLName.value+'&className='+className;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
}
 
 function printResourceReport(className) {
	form = document.addForm;
	path='<?php echo UI_HTTP_PATH;?>/studentResourceReportPrint.php?studentId='+form.studentId.value+'&classId='+document.getElementById('studyPeriod').value+'&sortField='+listObj4.sortField+'&sortOrderBy='+listObj4.sortOrderBy+'&searchbox='+document.getElementById('searchbox').value;
	window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=400, top=150,left=150");
}


function listPage(path){

	window.location=path;
}
window.onload=function(){
getShowStudyPeriod();
     if("<?php echo $sessionHandler->getSessionVariable('PERSONAL_INFO') ?>"==1)  {  
	    /*START: function to populate correspondence states and countries based on student values*/
	    autoPopulate("<?php echo $studentDataArr[0]['corrCountryId']?>",'states','Add','correspondenceStates','correspondenceCity');
	    autoPopulate("<?php echo $studentDataArr[0]['corrStateId']?>",'city','Add','correspondenceStates','correspondenceCity');
	     
	    document.getElementById('correspondenceStates').value="<?php echo $studentDataArr[0]['corrStateId']?>";
	    document.getElementById('correspondenceCity').value="<?php echo $studentDataArr[0]['corrCityId']?>";
	    /*START: function to populate correspondence states and countries based on student values*/

	    /*START: function to populate permanent states and countries based on student values*/
	    autoPopulate("<?php echo $studentDataArr[0]['permCountryId']?>",'states','Add','permanentStates','permanentCity');
	    autoPopulate("<?php echo $studentDataArr[0]['permStateId']?>",'city','Add','permanentStates','permanentCity');
	    
	    document.getElementById('permanentStates').value="<?php echo $studentDataArr[0]['permStateId']?>";
	    document.getElementById('permanentCity').value="<?php echo $studentDataArr[0]['permCityId']?>";
	    /*END: function to populate permanent states and countries based on student values*/	
     }

     if("<?php echo $sessionHandler->getSessionVariable('PARENTS_INFO') ?>"==1)  {            
	    /*START: function to populate father states and countries based on student values*/
	    autoPopulate("<?php echo $studentDataArr[0]['fatherCountryId']?>",'states','Add','fatherStates','fatherCity');
	    autoPopulate("<?php echo $studentDataArr[0]['fatherStateId']?>",'city','Add','fatherStates','fatherCity');
	    
	    document.getElementById('fatherStates').value="<?php echo $studentDataArr[0]['fatherStateId']?>";
	    document.getElementById('fatherCity').value="<?php echo $studentDataArr[0]['fatherCityId']?>";
	    /*END: function to populate father states and countries based on student values*/

	    /*START: function to populate mother states and countries based on student values*/
	    autoPopulate("<?php echo $studentDataArr[0]['motherCountryId']?>",'states','Add','motherStates','motherCity');
	    autoPopulate("<?php echo $studentDataArr[0]['motherStateId']?>",'city','Add','motherStates','motherCity');
	    
	    document.getElementById('motherStates').value="<?php echo $studentDataArr[0]['motherStateId']?>";
	    document.getElementById('motherCity').value="<?php echo $studentDataArr[0]['motherCityId']?>";
	    /*END: function to populate mother states and countries based on student values*/

	    /*START: function to populate guardian states and countries based on student values*/
	    autoPopulate("<?php echo $studentDataArr[0]['guardianCountryId']?>",'states','Add','guardianStates','guardianCity');
	    autoPopulate("<?php echo $studentDataArr[0]['guardianStateId']?>",'city','Add','guardianStates','guardianCity');
	    
	    document.getElementById('guardianStates').value="<?php echo $studentDataArr[0]['guardianStateId']?>";
	    document.getElementById('guardianCity').value="<?php echo $studentDataArr[0]['guardianCityId']?>";
	    /*END: function to populate guardian states and countries based on student values*/
     }
     
     showTab('dhtmlgoodies_tabView1',document.getElementById('showTabView').value); 
     return false;
}

 function getShowStudyPeriod(){

  // document.getElementById('migStudy').display='none';
   if(document.addForm.isMigration.checked==true){
	
     document.getElementById('divMigStudy').style.display = '';
   }else{
	 document.getElementById('divMigStudy').style.display = 'none';
  }

 }
</script>
</head>
<body>
<SCRIPT LANGUAGE="JavaScript">
	showWaitDialog(true);
</SCRIPT>
	
<?php 
    require_once(TEMPLATES_PATH . "/header.php");
   //this will fetch the Latest Registration Details from the student_registration table
  
   if($roleId==2){
     require_once(TEMPLATES_PATH . "/RegistrationForm/ScStudent/scMenteesDetailContents.php");  
   }
   else{
      require_once(TEMPLATES_PATH . "/Student/studentDetailContents.php");
   }
   require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
	hideWaitDialog(true);
</SCRIPT>
</body>
</html>
<?php

function trim_output($str,$maxlength='250',$rep='...'){
   $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep; 
   }
  return $ret;  
}
 
// $History: studentDetail.php $

//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 2/04/10    Time: 11:07a
//Updated in $/LeapCC/Interface
//changes in code to show final result tab in find student & parent 
//
//*****************  Version 38  *****************
//User: Parveen      Date: 1/28/10    Time: 3:14p
//Updated in $/LeapCC/Interface
//parseOutput function added
// 
//
//*****************  Version 37  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/Interface
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 11/24/09   Time: 10:25a
//Updated in $/LeapCC/Interface
//show leaves taken field in attendance of student
//
//*****************  Version 35  *****************
//User: Rajeev       Date: 09-10-24   Time: 4:28p
//Updated in $/LeapCC/Interface
//fixed bug no 0001821,0001880,0001816,0001852,0001851,0001637,0001329,00
//01244,0001855
//
//*****************  Version 33  *****************
//User: Rajeev       Date: 09-10-12   Time: 11:53a
//Updated in $/LeapCC/Interface
//Updated with Access right parameters
//
//*****************  Version 32  *****************
//User: Dipanjan     Date: 6/10/09    Time: 17:00
//Updated in $/LeapCC/Interface
//Added Detailed(group wise) and Consolidated view(irrespective of groups
//of a subject) of attendance in admin section
//
//*****************  Version 31  *****************
//User: Rajeev       Date: 09-09-18   Time: 3:33p
//Updated in $/LeapCC/Interface
//updated username with / and - permission in username
//
//*****************  Version 30  *****************
//User: Rajeev       Date: 7/29/09    Time: 10:21a
//Updated in $/LeapCC/Interface
//Updated with quartine student registration number increment
//
//*****************  Version 29  *****************
//User: Rajeev       Date: 7/22/09    Time: 3:27p
//Updated in $/LeapCC/Interface
//Updated student previous academic validation
//
//*****************  Version 28  *****************
//User: Rajeev       Date: 7/20/09    Time: 4:02p
//Updated in $/LeapCC/Interface
//Fixed bugs and enhancements 0000616-0000620
//
//*****************  Version 27  *****************
//User: Rajeev       Date: 7/16/09    Time: 10:53a
//Updated in $/LeapCC/Interface
//Updated student detail module formatting when session is changed from
//top
//
//*****************  Version 26  *****************
//User: Rajeev       Date: 7/13/09    Time: 12:25p
//Updated in $/LeapCC/Interface
//Removed Last name validation check as per Sachin sir dated 13thjuly
//
//*****************  Version 25  *****************
//User: Rajeev       Date: 7/11/09    Time: 11:01a
//Updated in $/LeapCC/Interface
//made enhancement to exchange max marks and marks obtained field and
//validations
//
//*****************  Version 24  *****************
//User: Rajeev       Date: 6/23/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//updated with back button image
//
//*****************  Version 23  *****************
//User: Rajeev       Date: 6/18/09    Time: 12:19p
//Updated in $/LeapCC/Interface
//Fixed:  	 0000058: Find Student - Admin > Sorting should be on �Group
//Name� Field by Default. 
//
//*****************  Version 22  *****************
//User: Rajeev       Date: 6/18/09    Time: 12:08p

//Updated in $/LeapCC/Interface
//Fixed 0000057: Find Student - Admin > �per(%)� should be �Per.(%)� on
//�Attendance Info� Tab 
//
//*****************  Version 21  *****************
//User: Rajeev       Date: 6/15/09    Time: 7:22p
//Updated in $/LeapCC/Interface
//Enhanced "Admin Student" module as mailed by Puspender Sir.
//
//*****************  Version 20  *****************
//User: Rajeev       Date: 6/11/09    Time: 6:41p
//Updated in $/LeapCC/Interface
//Updated student roll number validation
//
//*****************  Version 19  *****************
//User: Rajeev       Date: 6/11/09    Time: 5:10p
//Updated in $/LeapCC/Interface
//Made Group detail left aligned
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 6/02/09    Time: 11:39a
//Updated in $/LeapCC/Interface
//Fixed bugs  1104-1110  and enhanced with student previous academics
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 5/29/09    Time: 12:58p
//Updated in $/LeapCC/Interface
//Updated student password validation
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 5/28/09    Time: 3:30p
//Updated in $/LeapCC/Interface
//Added blood group, reference name, sports activity, student previous
//academic, in print report as well as find student tab
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 4/09/09    Time: 3:18p
//Updated in $/LeapCC/Interface
//added print reports
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 4/08/09    Time: 6:14p
//Updated in $/LeapCC/Interface
//fixed bugs
//
//*****************  Version 13  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 2/24/09    Time: 6:16p
//Updated in $/LeapCC/Interface
//Updated student tab so that only tab based file is called
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 1/12/09    Time: 5:30p
//Updated in $/LeapCC/Interface
//Updated with Required field, centralized message, left align
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 1/10/09    Time: 2:32p
//Updated in $/LeapCC/Interface
//Removed "ajax.js" include
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 1/07/09    Time: 2:05p
//Updated in $/LeapCC/Interface
//added attendance file
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 1/05/09    Time: 5:06p
//Updated in $/LeapCC/Interface
//changed case of student
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 1/05/09    Time: 11:40a
//Updated in $/LeapCC/Interface
//added reported by in offense tab
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 12/22/08   Time: 5:52p
//Updated in $/LeapCC/Interface
//added Offense tab
//
//*****************  Version 4  *****************
//User: Parveen      Date: 12/10/08   Time: 3:28p
//Updated in $/LeapCC/Interface
//condition update
//
//*****************  Version 3  *****************
//User: Parveen      Date: 12/10/08   Time: 3:12p
//Updated in $/LeapCC/Interface
//code review 
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/10/08   Time: 10:19a
//Updated in $/LeapCC/Interface
//modified as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 18  *****************
//User: Rajeev       Date: 9/09/08    Time: 1:53p
//Updated in $/Leap/Source/Interface
//removed validation from rank and exam controls
//
//*****************  Version 17  *****************
//User: Rajeev       Date: 9/01/08    Time: 12:08p
//Updated in $/Leap/Source/Interface
//updated with default list of attendance details under attendance tab as
//said by Sachin sir
//
//*****************  Version 16  *****************
//User: Rajeev       Date: 8/22/08    Time: 5:48p
//Updated in $/Leap/Source/Interface
//updated print reports
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 8/21/08    Time: 2:03p
//Updated in $/Leap/Source/Interface
//updated formatting and print reports
//
//*****************  Version 14  *****************
//User: Rajeev       Date: 8/16/08    Time: 2:54p
//Updated in $/Leap/Source/Interface
//updated file for print report
//

//*****************  Version 13  *****************
//User: Rajeev       Date: 8/16/08    Time: 12:17p
//Updated in $/Leap/Source/Interface
//added  filePath to be used in tab images
//
//*****************  Version 12  *****************
//User: Rajeev       Date: 8/14/08    Time: 7:29p
//Updated in $/Leap/Source/Interface

//updated print functions
//
//*****************  Version 11  *****************
//User: Rajeev       Date: 8/13/08    Time: 12:45p
//Updated in $/Leap/Source/Interface
//updated the ajax method
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 8/05/08    Time: 6:30p
//Updated in $/Leap/Source/Interface
//remove all the demo issues
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 8/01/08    Time: 4:03p
//Updated in $/Leap/Source/Interface
//updated attendance function
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 7/29/08    Time: 3:54p
//Updated in $/Leap/Source/Interface
//updated image upload function
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 7/24/08    Time: 6:37p
//Updated in $/Leap/Source/Interface
//worked on single parent user login
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/17/08    Time: 3:00p
//Updated in $/Leap/Source/Interface
//updated with user validations
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/16/08    Time: 1:41p
//Updated in $/Leap/Source/Interface
//updated student profile with student marks 
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/15/08    Time: 11:18a
//Updated in $/Leap/Source/Interface
//added attendance module
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:24p
//Updated in $/Leap/Source/Interface
//updated validations
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 7/12/08    Time: 5:18p
//Updated in $/Leap/Source/Interface
//made ajax based


//
//*****************  Version 1  *****************
//User: Rajeev       Date: 7/11/08    Time: 6:43p
//Created in $/Leap/Source/Interface
//intial checkin
?>
