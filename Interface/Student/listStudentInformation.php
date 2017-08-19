<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentInfoDetail');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
include_once(BL_PATH ."/Student/initStudentInformation.php");
 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
$queryString =  $_SERVER['QUERY_STRING'];
function parseInput($input) {
    return ( (trim($input)!="" ? $input : 0 ) );
}

function parseOutput($data){
     
     return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
    
}
function createBlankTD($i,$str='<td  valign="middle" align="center" class="timtd">---</td>'){
    return ($i > 0 ? str_repeat($str,$i):str_repeat($str,0));
}
?>
<script language="javascript">
//recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
//linksPerPage = <?php echo LINKS_PER_PAGE;?>;

var filePath = "<?php echo IMG_HTTP_PATH;?>"

var tableHeadArray = new Array(new Array('srNo','#','width="4%"','valign="top"',false), 
new Array('subjectCode','Course','width="12%"','valign="top"',true) , 
new Array('description','Description','width="20%"','valign="top"',true), 
new Array('resourceName','Type','width="10%"','valign="top"',true), 
new Array('postedDate','Date','width="8%"','valign="top"',true),
new Array('resUrl','Link','width="8%"','valign="top"',false),
new Array('attFile','Attachment','width="8%"','valign="top" align="center"',false),
new Array('employeeName','Teacher Name','width="12%"','valign="top" align="left"',true)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCourseResourceList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
divResultName  = 'resultResource';
page=1; //default page
sortField = 'subjectCode';
sortOrderBy = 'ASC';

/*function totalFunction(value){

	getGroup(value);
	getTransferredMarks(value);
}*/

/****************************************************************/
//Overriding tabClick() function of tab-view.js

/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick() {
	
        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        
        //refresshes data for this tab
        totalFunction(document.getElementById('semesterDetail').value,tabNumber);
    }


//Global variables for classId countres for different tabs
var gcId=-1;
var tmdId=-1;
var offId=-1;
var rsId=-1;
var attId=-1; 
var grdId=-1;
var fineId=-1;
var pollId=-1;
var feeId=-1;

function totalFunction(Id,tabIndex) {
	var tabResourceIndex = "<?php echo $REQUEST_DATA['tabIndex']; ?>";
	if(tabResourceIndex != '') {
		tabIndex = tabResourceIndex;
	}

	if(tabIndex==2 && Id!=gcId) {
     //get the data of course based upon selected study period
     var groupData=getGroup(Id);
     gcId=Id;
     return;
    }
	
	if(tabIndex==4 && Id!=rsId) {
     //get the data of course based upon selected study period
	 //var resourceSubject=setSubjectCode(Id);
     var resourceData=getResource(Id);
     rsId=Id;
     return;
    }

	if(tabIndex==5 && Id!=tmdId) {
     //get the data of course based upon selected study period
     var transferredMarksData=getTransferredMarks(Id);
     tmdId=Id;
     return;
    }

	if(tabIndex==6 && Id!=offId) {
     //get the data of course based upon selected study period
     var getOffenceData=refreshOffenceData(Id);
     offId=Id;
     return;
    }
    
    if(tabIndex==9 && Id!=attId) {
      var getAttendanceRegisterData= refreshAttendanceRegisterData(Id); 
      attId=Id;  
      return;
    }
    
    if(tabIndex==10 && Id!=grdId) {
      var getAttendanceRegisterData= refreshGradeCardData(Id); 
      grdId=Id;  
      return;
    }
    
    if(tabIndex==11 && Id!=fineId) {
      var getStudentFineData= refreshStudentFineData(Id); 
      fineId=Id;
      return;
    }
    
    if(tabIndex==12 && Id!=pollId) {
     // var getStudentPollData= refreshStudentPollData(Id); 
      pollId=Id;
      return;
    }

     if(tabIndex==13 && Id!=feeId) {
      var getStudentFeesData= refreshStudentFeesData(Id); 
      feeId=Id;
      return;
    }
}


//this function fetches records corresponding to student fine detail
function refreshStudentFineData(Id){
		url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxinitStudentFine.php';
		var tbHeadArray =	new Array(
								new Array('srNo','#','width="5%"',false),
                                new Array('fineDate','Date','width="15%", align="center"',true), 
								new Array('fineCategoryName','Fine/Receipt No.<span class="redColorBig">* </span>','width="20%"',true),								
								new Array('reason','Reason','width="20%"',false),
								new Array('amount','Fine Amount','width="10%", align="right"',false),
								new Array('paidAmount','Paid Amount','width="10%", align="right"',false)
								//new Array('balance','Balance','width="10%", align="right"',true)
							);	
								 
//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
		listObj11 = new initPage(url,recordsPerPage,linksPerPage,1,'','fineDate','ASC','studentFineDiv','','',true,'listObj11',tbHeadArray,'','','&semesterDetail='+Id);
		sendRequest(url, listObj11, '')

}

      function refreshStudentFeesData(Id){
		url = '<?php echo HTTP_LIB_PATH;?>/Fee/PaymentHistory/ajaxFeeList.php';

		var tbHeadArray =new Array(new Array('srNos','#','width="2%"','',false),
                               new Array('receiptDate','Receipt Date','width="10%"','align="center"',true),  
                               new Array('receiptNo','Receipt No.','width="10%"','align="left"',true),
                               new Array('studentName','Name','width="10%"','',true) , 
                               new Array('rollNo','Roll No.','width="10%"','',true), 
                               new Array('className','Fee Class','width="10%"','',true),  
                               new Array('cycleName','Fee Cycle','width="9%"','',true),
                               new Array('feeTypeOf','Pay Fee Of','width="10%"','',true),   
                               new Array('receiveCash','Cash(Rs.)','width="10%"','align="right"',true), 
                               new Array('receiveDD','DD(Rs.)','width="10%"','align="right"',true), 
                               new Array('ddDetail', 'DD Detail','width="10%"','align="right"',true), 
                               new Array('onlinePayment', 'Online Payment','width="10%"','align="right"',true),
                               new Array('amount','Total Receipt','width="10%"','align="right"',true) 
					
					);	
								 
//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
listObj13 = new initPage(url,recordsPerPage,linksPerPage,1,'','receiptDate','ASC','feeDataDiv','','',true,'listObj13',tbHeadArray,'','','');
		sendRequest(url, listObj13, '')

}


function refreshStudentPollData(frm){
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxinitPoll.php';
     
    var fieldsArray = new Array(new Array("employeeId1","Select Adorable Teacher"),
                                new Array("employeeId2","Select Dedicated Teacher"),
                                new Array("employeeId3","Select Interactive Teacher"),
                                new Array("employeeId4","Select Ever-smiling Teacher"),
                                new Array("employeeId5","Select Charismatic Teacher (based on personality)")
                                );
    
    var len = fieldsArray.length;           
    for(i=0;i<len;i++) {
      eval("frm."+(fieldsArray[i][0])+".className='selectfield'");   
      if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
         messageBox (fieldsArray[i][1]);
         eval("frm."+(fieldsArray[i][0])+".className='inputboxRed'");
         eval("frm."+(fieldsArray[i][0])+".focus();");
         return false;
      }
    }                            
  
    new Ajax.Request(url,
    {
     method:'post',
     parameters: { employeeId1 : document.getElementById('employeeId1').value,
                   employeeId2 : document.getElementById('employeeId2').value,
                   employeeId3 : document.getElementById('employeeId3').value,
                   employeeId4 : document.getElementById('employeeId4').value,
                   employeeId5 : document.getElementById('employeeId5').value
                 },
     asynchronous:true, 
     onCreate: function() {
        showWaitDialog(true); 
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
       if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
         messageBox("Your response for the poll has been successfully saved");
         getCheckPoll();
         return false; 
       }
       else {
         messageBox(trim(transport.responseText));
       }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}


function getCheckPoll() {
   
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCheckPoll.php';
    
    document.getElementById('divPoll1').style.display='none';
    document.getElementById('divPoll2').style.display='none';
    
    new Ajax.Request(url,
    {
     method:'post',
     asynchronous:true, 
     onCreate: function() {
        showWaitDialog(true); 
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
       if(trim(transport.responseText)=='1') {
         document.getElementById('divPoll1').style.display='';
         document.getElementById('divPoll2').style.display='none';
       }
       else {
         document.getElementById('divPoll2').style.display='';
         document.getElementById('divPoll1').style.display='none';
       }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    }); 
}

//this function fetches records corresponding to student grades detail
function refreshGradeCardData(Id){

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
    listObj7 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectCode','ASC','finalGradesDiv','','',true,'listObj7',tableColumns,'','','&rClassId='+Id);
    sendRequest(url, listObj7, '')
}



function refreshAttendanceRegisterData(Id){
    
    url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxAttendanceRegisterList.php';
  
    document.getElementById('attendanceRegister').innerHTML='';
    
    new Ajax.Request(url,
    {
          method:'post',
          asynchronous:false,
          parameters: {classId: (Id)},
          onCreate: function() {
              showWaitDialog(true);
          },
          onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==false) {
                messageBox("<?php echo INCORRECT_FORMAT?>");  
             }
             else {
               document.getElementById('attendanceRegister').innerHTML=trim(transport.responseText);
             }
     },
     onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     });
}   



//// starts function for listing Subject According to Sem /////
function setSubjectCode(Id) {
	
    document.addForm.category.selectedIndex = 0;
    document.addForm.searchbox.value='';
	document.getElementById('subjects').options.length = 1;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCourseSubjectCodeList.php';
	//var pars = generateQueryString(frm2);
	var testVar = 0;
	//var StudentId =<?php //$sessionHandler->getSessionVariable('StudentId') ?>;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {classId: (Id)},
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			fetchedData = trim(transport.responseText);
			if(fetchedData != '<?php echo ERROR_OCCURED;?>') {
				var j= eval('('+fetchedData+')');
				var len=j.length;
				for(var i=0;i<len;i++){
				  addOption(document.getElementById('subjects'),j[i].subjectId,j[i].subjectCode);
				}
				
			}
			else {
				messageBox(fetchedData)
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
}

function resetDate(){
document.getElementById('postDate').value="";
}


///starts function for teacher code According to Sem/////
function setTeacherCode(Id) {

	document.getElementById('teachers').options.length = 1;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxTeacherCodeList.php';
	var testVar = 0;
	//var pars = generateQueryString(frm2);
	//var StudentId =<?php //$sessionHandler->getSessionVariable('StudentId') ?>;
	new Ajax.Request(url,
	{
		method:'post',
		parameters: {classId: (Id)},
		asynchronous:false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			fetchedData = trim(transport.responseText);
			if(fetchedData != '<?php echo ERROR_OCCURED;?>') {
				var j= eval('('+fetchedData+')');
				var len=j.length;
				//document.getElementById('teachers').addOption('select','');
				for(var i=0;i<len;i++){
				  addOption(document.getElementById('teachers'),j[i].employeeId,j[i].employeeName);
				}
				
			}
			else {
				messageBox(fetchedData);
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
	});
	
}
///ends code for teacher code /////////

function getResource(Id) {
url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxCourseResourceList.php';
var searchbox = document.getElementById('searchbox').value;
var subjectCode=document.getElementById('subjects').value;
var type=document.getElementById('category').value;
var postDate=document.getElementById('postDate').value;
var teacher=document.getElementById('teachers').value;
//alert(postDate);
//exit();

var tbHeadArray =	new Array(new Array('srNo','#','width="4%"','valign="top"',false), 
						new Array('subjectCode','Course','width="12%"',true),
						new Array('description','Description','width="20%"',true),
						new Array('resourceName','Type','width="10%"',true),
						new Array('postedDate','Date','width="8%", align="center"',true),
						new Array('resUrl','Link','width="8%"',false),
						new Array('attFile','Attachment','width="8%", align="center"',false),
						new Array('employeeName','Teacher Name','width="12%"',true));
		 
		 listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectCode','ASC','resultResource','','',true,'listObj3',tbHeadArray,'','','&semesterDetail='+Id+'&searchbox='+trim(searchbox)+'&subjectCode='+trim(subjectCode)+'&type='+trim(type)+'&teacher='+trim(teacher)+'&postDate='+trim(postDate));
		 sendRequest(url, listObj3, '')
}

function getGroup(Id) {

 
url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentGroupDetail.php';

		var tbHeadArray =	new Array(new Array('srNo','#','width="5%"',false), 
							new Array('periodName','For','width="12%"',false),
							new Array('groupName','Group','width="15%"',true), 
							new Array('groupTypeName','Group Type','width="15%"',true),
							new Array ('subjectCode','Subjet Code','width="15%"',true), 
							new Array('subjectName','Subject','width="40%"',true));
		 
		listObj1 = new initPage(url,recordsPerPage,linksPerPage,1,'','groupName','ASC','results','','',true,'listObj1',tbHeadArray,'','','&semesterDetail='+Id);
		sendRequest(url, listObj1, '')
}

function getTransferredMarks(Id) {
 
url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitStudentTransferredMarks.php';

	
			 var tbHeadArray =	new Array(new Array('srNo','#','width="3%"',false), 
								new Array('periodName','Study Period','width="12%"',true),
								new Array('subjectCode','Subject Code','width="40%"',true),
								new Array('attendance','Attendance','width="12%" align="right"',false),
								new Array('preCompre','Internal','width="12%" align="right"',false), 
								new Array('compre','External','width="10%" align="right"',false));
             
		  listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','periodName','ASC','transferredResult','','',true,'listObj2',tbHeadArray,'','','&semesterDetail='+Id);
		 sendRequest(url, listObj2, '')
}
      
function refreshOffenceData(Id){
	
  url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxOffenceStudent.php';
  var tableColumns = new Array(
                            new Array('srNo','#','width="2%" valign="middle"',false), 
							new Array('offenseName','Offense','width="15%" valign="middle"',true),
                            new Array('offenseDate','Date','width="10%" valign="middle"',true) , 
							new Array('periodName','Study Period','width="13%" valign="middle"',true) , 
							new Array('reportedBy','Reported By','width="15%" valign="middle"',true) , 
                            new Array('remarks','Remarks','width="50%" valign="middle" align="left"',true) 
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj9 = new initPage(url,recordsPerPage,linksPerPage,1,'','offenseName','ASC','offenceResultsDiv','','',true,'listObj9',tableColumns,'','','&semesterDetail='+Id);
 sendRequest(url, listObj9, '')
}

window.onload = function(){
    //totalFunction(document.getElementById('semesterDetail').value);
    document.addForm.reset();
    if("<?php echo $REQUEST_DATA['tabIndex']; ?>"!="") {
        if("<?php echo $REQUEST_DATA['tabIndex']; ?>"==4) {
          showTab('dhtmlgoodies_tabView1',4); 
          totalFunction(document.getElementById('semesterDetail').value,4);
        }    
    }
    
    /*START: function to populate correspondence states and countries based on student values*/
    autoPopulate("<?php echo $studentInformationArray[0]['corrCountryId']?>",'states','Add','correspondenceStates','correspondenceCity');
    autoPopulate("<?php echo $studentInformationArray[0]['corrStateId']?>",'city','Add','correspondenceStates','correspondenceCity');
     
    document.getElementById('correspondenceStates').value="<?php echo $studentInformationArray[0]['corrStateId']?>";
    document.getElementById('correspondenceCity').value="<?php echo $studentInformationArray[0]['corrCityId']?>";
    /*START: function to populate correspondence states and countries based on student values*/

    /*START: function to populate permanent states and countries based on student values*/
    autoPopulate("<?php echo $studentInformationArray[0]['permCountryId']?>",'states','Add','permanentStates','permanentCity');
    autoPopulate("<?php echo $studentInformationArray[0]['permStateId']?>",'city','Add','permanentStates','permanentCity');
    
    document.getElementById('permanentStates').value="<?php echo $studentInformationArray[0]['permStateId']?>";
    document.getElementById('permanentCity').value="<?php echo $studentInformationArray[0]['permCityId']?>";
    /*END: function to populate permanent states and countries based on student values*/    
    getCheckPoll();    
}

function initAdd() {
    if(trim(document.getElementById('studentPhoto').value)==''){
        messageBox("Select your image");
        document.getElementById('studentPhoto').focus();
        return false;
    }
    if(!checkAllowdExtensions(trim(document.getElementById('studentPhoto').value))){
     document.getElementById('studentPhoto').focus();
     document.getElementById('studentPhoto').value='';
     messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
     return false;
    } 
    document.getElementById('addForm').onsubmit=function() {
      document.getElementById('addForm').target = 'uploadTargetAdd';
    }
}


function checkAllowdExtensions(value){
  //get the extension of the file 
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="gif,jpg,jpeg,png,bmp";

  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;
  
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }

  if(fl){
   return true;
  }
 else{
  return false;
 }   
}

function photoUpload(src,mode){
    if(mode==2){
        messageBox(src);
        return false;
    }
    var d = new Date();
    var rndNo = d.getTime();
    document.getElementById('studentImageId1').setAttribute('src',src+'?'+rndNo);
    document.getElementById('studentImageId').setAttribute('src',src+'?'+rndNo);
    document.getElementById('studentPhoto').value='';
}



 

/*
function  download(str){
var address="<?php echo IMG_HTTP_PATH;?>/CourseResource/"+escape(str);
window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}
*/
function  download(id){
 var address="<?php echo HTTP_LIB_PATH;?>/forceDownload.php?fileId="+id+"&callingModule=ResourceDownload";
 //window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
 window.location=address;
}


//---------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "states/city" select box depending upon which country/state is selected
//
//Author : Rajeev Aggarwal
// Created on : (17.6.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------
//id:id 
//type:states/city
//target:taget dropdown box

function autoPopulate(val,type,frm,fieldSta,fieldCty)
{
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAll(frm) {

    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxStudentInfoEdit.php';
    
    //check for paymenet detais
    var elements=document.getElementById('tableDiv').getElementsByTagName('INPUT');
    var eleLen=elements.length;
    var dtPicsLen=(eleLen/5);
    for(var i=0;i<eleLen;i++){
        if(elements[i].name=='ddNo[]'){
            if(trim(elements[i].value)==''){
                messageBox("<?php echo ENTER_DD_NO;?>");
                elements[i].focus();
                return false;
            }
        }
        if(elements[i].name=='fromDate[]'){
          if(!dateDifference(elements[i].value,serverDate,'-')){///problem is here
               messageBox("<?php echo DD_DATE_RESTRICTION;?>");
               elements[i].focus();
               return false;
          }
        }
        if(elements[i].name=='ddAmt[]'){
            if(trim(elements[i].value)==''){
                messageBox("<?php echo ENTER_DD_AMT;?>");
                elements[i].focus();
                return false;
            }
            if(!isDecimal(trim(elements[i].value))){
                messageBox("<?php echo ENTER_DECIMAL_VALUE;?>");
                elements[i].focus();
                return false;
            }
        }
        if(elements[i].name=='ddBank[]'){
            if(trim(elements[i].value)==''){
                messageBox("<?php echo ENTER_DD_BANK_NAME;?>");
                elements[i].focus();
                return false;
            }
        }
    }

    new Ajax.Request(url,
    {
     method:'post',
     parameters: $('addForm').serialize(true),
     asynchronous:true, 
     onCreate: function() {
        showWaitDialog(true); 
     },
     onSuccess: function(transport){
       hideWaitDialog(true);
       if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
          messageBox(trim(transport.responseText));
       }
       else {
          messageBox(trim(transport.responseText));
       }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
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
    
var serverDate="<?php echo date('Y-m-d');?>"
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
      var cell6=document.createElement('td');
      
      cell1.setAttribute('align','left');
      cell1.name='srNo';
      cell2.setAttribute('align','left'); 
      cell3.setAttribute('align','left');
      cell4.setAttribute('align','left');
      cell5.setAttribute('align','left');
      cell6.setAttribute('align','center');
      
      
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
      var txt1=document.createElement('input');
      //var txt2=document.createElement('input');
      var txt3=document.createElement('input');
      var txt4=document.createElement('a');
      var txt5=document.createElement('input');
      
      txt1.setAttribute('id','ddNo'+parseInt(start+i,10));
      txt1.setAttribute('name','ddNo[]'); 
      txt1.setAttribute('type','text');
      txt1.className='inputbox'; 
      txt1.setAttribute('style','width:200px;'); 
      txt1.setAttribute('maxlength','100');
      
      txt3.setAttribute('id','ddAmt'+parseInt(start+i,10));
      txt3.setAttribute('name','ddAmt[]');
      txt3.setAttribute('type','text');
      txt3.className='inputbox';
      txt3.setAttribute('style','width:60px;');
      txt3.setAttribute('maxlength','8');
   
      txt4.setAttribute('id','rd');
      txt4.className='htmlElement';  
      txt4.setAttribute('title','Delete');       
      txt4.innerHTML='X';
      txt4.style.cursor='pointer';
      txt4.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
      
      txt5.setAttribute('id','ddBank'+parseInt(start+i,10));
      txt5.setAttribute('name','ddBank[]');
      txt5.setAttribute('type','text');
      txt5.className='inputbox';
      txt5.setAttribute('style','width:250px;');
      txt5.setAttribute('maxlength','100');
      
      
      cell1.appendChild(txt0);
      cell2.appendChild(txt1);
      //cell3.appendChild(txt2);
      cell3.innerHTML='<input type="text" id="fromDate'+parseInt(start+i,10)+'" name="fromDate[]" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
      cell3.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('fromDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
      cell4.appendChild(txt3);
      cell5.appendChild(txt5);
      cell6.appendChild(txt4);
      
             
      tr.appendChild(cell1);
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);
      tr.appendChild(cell6);
      
      
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


function checkAllowdExtensionsAll(value){
  //get the extension of the file 
  var val=value.substring(value.lastIndexOf('.')+1,value.length);
  var str="<?php echo implode(',',$allowedExtensionsArray);?>";
  var extArr=str.split(",");
  var fl=0;
  var ln=extArr.length;
  for(var i=0; i <ln; i++){
      if(val.toUpperCase()==extArr[i].toUpperCase()){
          fl=1;
          break;
      }
  }
  if(fl){
   return true;
  }
  else{
  return false;
  }   
}

function uploadAttachments() {
    var files= document.getElementById('uploadDocsContainerDiv').getElementsByTagName('INPUT');
    var fileCnt=files.length;
    var blankFlag=0;
    for(var i=0;i<fileCnt;i++){
        if(files[i].type=='file'){
            if(trim(files[i].value)!=''){
               blankFlag=1; 
               if(!checkAllowdExtensionsAll(trim(files[i].value))){
                files[i].focus();
                messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                return false;
               }
            }
        }
    }
    if(blankFlag==0){
        messageBox("Please select at least one file");
        return false;
    }
    document.getElementById('addForm').onsubmit=function() {
      document.getElementById('addForm').target = 'uploadTargetAdd';
    }
}

function documentUpload(src,mode){
    messageBox(src);
    var files= document.getElementById('uploadDocsContainerDiv').getElementsByTagName('INPUT');
    var fileCnt=files.length;
    if(src=='Files uploaded'){
       relaodDiv(); 
   }
}

function deleteAttachedDocumentFile(docId,studentId) {

    if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
       return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxDeleteAttachedDocumentFile.php';
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {docId: (docId)},
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var ret = trim(transport.responseText);
            if(ret=="<?php echo DELETE ?>"){
                relaodDiv();
            }
            
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function relaodDiv(){
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/getStudentAttachedDocuments.php';
    new Ajax.Request(url,
    {
        method:'post',
        parameters: {1: (1)},
        asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            var ret = trim(transport.responseText);
            document.getElementById('uploadDocsContainerDiv').innerHTML=ret;
            changeColor(currentThemeId);
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function  documentDownload(docId,studentId){
	var address="<?php echo HTTP_LIB_PATH;?>/forceDownload.php?fileId="+docId+"&callingModule=StudentInfoDetail&studentId="+studentId;
	window.location=address;
}

function studentFineReceipt() {
   
   url='<?php echo UI_HTTP_PATH;?>/Student/printStudentFine.php';
   //window.location = url;
   window.open(url,"StudentFineReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50"); 
}

function printout() {	
	 window.print();
	
	}
function printOnlineSlip(receiptId){  
    
   url='<?php echo HTTP_PATH;?>/Interface/Fee/printSlip.php?receiptId='+receiptId;  
   // window.open(url,"StudentOnlineSlip");
   window.open(url,"StudentOnlineSlip","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");     
 }  
</script>
<title><?php echo SITE_NAME;?>: Student Information </title>
<?php 
 

function trim_output($str,$maxlength,$mode=1,$rep='...'){
   $ret=($mode==2?chunk_split($str,30):$str);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}     

    
?> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/studentInformationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

