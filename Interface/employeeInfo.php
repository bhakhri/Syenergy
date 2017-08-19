<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Employee/initData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Detail</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 

require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//require_once(CSS_PATH .'/tab-view.css'); 
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");  

//pareses input and returns "-" if the input is blank
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


//Global variables for employeeId for different tabs
var leId=-1;
var teId=-1;
var seId=-1;
var peId=-1;
var ceId=-1;
var weId=-1;
var qlId=-1;
var expId=-1;
var fiId=-1;
var timeId1=-1;
var timeId2=-1;
var globalFL=1;   

/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick() {
        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        //tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1]);
		
        
        //refreshes data for this tab
        refreshEmployeeData("<?php echo $employeeArr[0]['employeeId'];?>","<?php echo $employeeArr[0]['isTeaching'];?>",tabNumber);
    }

//this function is uded to refresh tab data based uplon selection of study periods
function refreshEmployeeData(employeeId,isTeaching,tabIndex){
  if(isTeaching != 'No') {
    if(tabIndex==1 && employeeId!=leId) {
      var topicwiseData=refreshLectureData(employeeId,document.getElementById("labelId").value);   
      leId=employeeId;
      return;  
    }
    
    if(tabIndex==2 && employeeId!=teId) {
        var topicwiseData=refreshTopicwise(employeeId,document.getElementById("timeTableLabelId").value);   
        teId=employeeId;
        return;
    }
    
    //get the data of Seminar 
    if(tabIndex==3 && employeeId!=seId) {
        var seminarData=refreshSeminarData(employeeId);
        seId=employeeId;
        return;
    }
    
    //get the data of Book/Journals/Papers
    if(tabIndex==4 && employeeId!=peId) {
        var booksJournalsData=refreshBookJournalsData(employeeId);
        peId=employeeId;
        return;
    }
    
    //get the data of consulting
    if(tabIndex==5 && employeeId!=ceId) {
        var consultingData=refreshConsultingData(employeeId);
        ceId=employeeId;
        return;
    }
    
    //get the data of workshop
    if(tabIndex==6 && employeeId!=weId) {
        var consultingData=refreshWorkshopData(employeeId);
        weId=employeeId;
        return;
    }

	//get the data of qualification
    if(tabIndex==7 && employeeId!=qlId) {
        var consultingData=refreshQualificationData(employeeId);
        qlId=employeeId;
        return;
    }
   //get consulting 
	if(tabIndex==8 && employeeId!=expId) {
        var consultingData=refreshExperienceData(employeeId);
        expId=employeeId;
        return;
    }
    // get financial
    if(tabIndex==9 && employeeId!=fiId) {
        var financialData=refreshFinancialData(employeeId);
        fiId=employeeId;
        return;
    }

    // get MDP
    if(tabIndex==10 && employeeId!=fiId) {
		//alert('sdffgedfgedfg');
        var mdpData=refreshMdpData(employeeId);
        fiId=employeeId;
        return;
    }
	
  }
  else {
    //get the data of Seminar 
    if(tabIndex==1 && employeeId!=seId) {
        var seminarData=refreshSeminarData(employeeId);
        seId=employeeId;
        return;
    }
    
    //get the data of Book/Journals/Papers
    if(tabIndex==2 && employeeId!=peId) {
        var booksJournalsData=refreshBookJournalsData(employeeId);
        peId=employeeId;
        return;
    }
    
    //get the data of consulting
    if(tabIndex==3 && employeeId!=ceId) {
        var consultingData=refreshConsultingData(employeeId);
        ceId=employeeId;
        return;
    }
    
    //get the data of workshop
    if(tabIndex==4 && employeeId!=weId) {
        var consultingData=refreshWorkshopData(employeeId);
        weId=employeeId;
        return;
    }

	//get the data of qualification
    if(tabIndex==5 && employeeId!=qlId) {
        var consultingData=refreshQualificationData(employeeId);
        qlId=employeeId;
        return;
    }
   //get experience
	if(tabIndex==6 && employeeId!=expId) {
        var consultingData=refreshExperienceData(employeeId);
        expId=employeeId;
        return;
    }
    // get finanacial
    if(tabIndex==7 && employeeId!=fiId) {
        var financialData=refreshFinancialData(employeeId);
        fiId=employeeId;
        return;
    }
	
  }
}

  // Topics hide
  function hideResults() {
  // document.getElementById("resultRowTopic").style.display='none';
  //document.getElementById('nameRowTopic').style.display='none';
  //document.getElementById('nameRow2Topic').style.display='none';  
}



//div---this function Mdp
//---------------------------------------------------------------------------------------------------------------------------------------------------


  function refreshMdpData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitMdpList.php';
  var value = document.getElementById("searchboxMdp").value;
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="center"',false), 
                        new Array('mdpName','Mdp Name','width="10%" align="left" valign="middle"',true),
                        new Array('startDate','Start Date','width="10%" align="left" valign="middle"',true),
                        new Array('endDate','End Date','width="10%" align="left" valign="middle"',true),
                        new Array('mdp','Mdp','width="7%" align="left" valign="middle"',true),
                        new Array('sessionsAttended','Session','width="9%" align="right" valign="middle"',true),
                        new Array('hoursAttended','Hours','width="7%" align="right"',true),
	                    new Array('venue','Venue','width="7%" align="left"',true),
	                    new Array('mdpType','MDP Type','width="8%" align="left"',true),
	                    new Array('description','Description','width="12%" align="left"',true),
	                    new Array('action1','Action','width="7%" align="center"',false)
                     );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','mdpName','ASC','MdpResultDiv','MdpActionDiv','',true,'listObj4',tableColumns,'editWindow','deleteMdp','&searchbox='+value+'&employeeId='+employeeId);
 sendRequest(url, listObj4, '', true);
}

//----------------------------------------------------------------------------------------------------------------------------------------------------
//this function seminar
function refreshSeminarData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitSeminarList.php';
  var value = document.getElementById("searchboxSeminar").value;
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('organisedBy','Organised By','width="17%" align="left" valign="top"',true),
                        new Array('topic','Topic','width="17%" align="left" valign="top"',true),
                        new Array('startDate','Start Date','width="10%" align="center" valign="top"',true),
                        new Array('endDate','End Date','width="10%" align="center" valign="top"',true),
                        new Array('seminarPlace','Seminar Place','width="15%" align="left" valign="top"',true),
                        new Array('participationId','Participation','width="12%" align="center" valign="top"',true),
                        new Array('fee','Fee','width="8%" align="right" valign="top"',true),
                        new Array('action1','Action','width="12%" align="center"',false)
                     );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj2 = new initPage(url,recordsPerPage,linksPerPage,1,'','organisedBy','ASC','SeminarResultDiv','SeminarActionDiv','',true,'listObj2',tableColumns,'editWindow','deleteSeminar','&searchbox='+value+'&employeeId='+employeeId);
 sendRequest(url, listObj2, '', true)
}

/*
function validateMdpAddForm(formName,act) {
	var pars = generateQueryString('mdpDetail');
	alert(pars);
	return false;
}
*/

//this function publishing  
function refreshBookJournalsData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitPublishingList.php';
  var value = document.getElementById("searchboxPublishing").value;
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('type','Type','width="12%" align="left"',true),
                        new Array('publicationName','Publication Name','width="15%" align="left"',true),
                        new Array('publishOn','Publish On','width="10%" align="center"',true),
                        new Array('publishedBy','Published By','width="17%" align="left"',true),
                        new Array('description','Description','width="17%" align="left"',true),
                        new Array('attachmentFile','Attachment','width="8%" align="center"',false),
                        new Array('attachmentAcceptationLetter','Accp. Let.','width="8%" align="center"',false),
                        new Array('action1','Action','width="8%" align="center"',false)
                       );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'','type','ASC','PublishingResultDiv','PublishingActionDiv','',true,'listObj3',tableColumns,'editWindow','deletePublishing','&searchbox='+value+'&employeeId='+employeeId);
 sendRequest(url, listObj3, '',true )
}
                                                     
//this function fetches Lecture Data 
function refreshLectureData(employeeId,labelId){
   
      if(document.getElementById("labelId").value=="") {
         if(timeId1!=-1) {
           messageBox("<?php echo SELECT_TIME_TABLE ?>"); 
         }
         timeId1=1;
         document.getElementById("labelId").focus();
         return false;
      }   
      timeId1=1;
      
      url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxLectureDetails.php';
      document.getElementById("resultLecture").style.display='none'; 
      var tableColumns = new Array(
                            new Array('srNo','#','width="2%" align="left"',false), 
                            new Array('subjectName','Subject Name','width="20%" align="left"',true),
                            new Array('subjectCode','Subject Code','width="10%" align="left"',true)
                         );
        
      var lectureGroupType='';
      
      var cnt = document.addForm.lectureGroupType.length;
      if(cnt>0) {
         for(var i=0;i<cnt;i++){
            tableColumns.push(new Array('s'+document.addForm.lectureGroupType.options[i].value,document.addForm.lectureGroupType.options[i].text,'width="5%" align="right"',true));
            if(lectureGroupType=='') {
              lectureGroupType = document.addForm.lectureGroupType.options[i].value;
            }
            else {
              lectureGroupType = lectureGroupType + ', '+document.addForm.lectureGroupType.options[i].value;
            }
         }          
         tableColumns.push(new Array('total','Total','width="5%" align="right"',true)); 
      }         
      
      document.getElementById("nameRow").style.display='';
      document.getElementById("nameRow2").style.display='';
      document.getElementById("resultRow").style.display='';

      //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
      listObj4 = new initPage(url,recordsPerPage,linksPerPage,1,'','subjectName','ASC','lecturerResultDiv','','',true,'listObj4',tableColumns,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&groupType='+lectureGroupType);
      sendRequest(url, listObj4, '',true )
      document.getElementById("resultLecture").style.display='';    
} 

function hideLectureResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
} 
 
//this function fetches Course Topicwise
function refreshTopicwise(employeeId,labelId) {
  
   if(document.getElementById("timeTableLabelId").value=="") {
     if(timeId2!=-1) {  
       messageBox("<?php echo SELECT_TIME_TABLE ?>"); 
     }
     timeId2=1; 
     document.getElementById("timeTableLabelId").focus();
     return false;
  }   
  timeId2=1; 
  
  document.getElementById("resultTopic").style.display='none';
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxTopicwiseDetails.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left" valign="top"',false), 
                        new Array('className','Class','width="15%" align="left" valign="top"',true), 
                        new Array('groupName','Group','width="10%" align="left" valign="top"',true), 
                        new Array('subjectName','Subject','width="15%" align="left" valign="top"',true),
                        new Array('subjectCode','Subject Code','width="15%" align="left" valign="top"',true), 
                        new Array('topicAbbr','Topics Covered','width="22%" align="left" valign="top"',false), 
                        new Array('pending','Pending','width="22%" align="left" valign="top"',false) 
                     );
 
 
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj5 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','resultsDivTopic','','',true,'listObj5',tableColumns,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId);
 document.getElementById("resultTopic").style.display='';
 sendRequest(url, listObj5, '',true )   
}


//this function Wrokshop Data
function refreshWorkshopData(employeeId) {
 
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitWorkshopList.php';
  var value = document.getElementById("searchboxWorkShop").value;
  var tableColumns = new Array(new Array('srNo','#','width="2%" align="left"',false), 
                               new Array('topic','Topic','width="15%" align="left"',true),
                               new Array('startDate','Start Date','width="8%" align="center"',true),
                               new Array('endDate','End Date','width="8%" align="center"',true),
                               new Array('sponsoredDetail','Sponsored','width="15%" align="left"',true),
                               new Array('audience','Audience','width="15%" align="left"',true),
                               new Array('location','Location','width="15%" align="left"',true),
                               new Array('action1','Action','width="8%" align="center"',false)
                              );

  //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
  listObj7 = new initPage(url,recordsPerPage,linksPerPage,1,'','topic','ASC','WorkShopResultDiv','WorkShopActionDiv','',true,'listObj7',tableColumns,'editWindow','deleteWorkshop','&searchbox='+value+'&employeeId='+employeeId);
  sendRequest(url, listObj7, '', true)
}
// Workshop End
 



//this function Consulting Data
function refreshConsultingData(employeeId) {
 
  url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitConsultingList.php';
  var value = document.getElementById("searchboxConsulting").value;
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left"',false), 
                        new Array('projectName','Project Name','width="10%" align="left"',true),
                        new Array('sponsorName','Sponsor','width="10%" align="left"',true),
                        new Array('startDate','Start Date','width="8%" align="center"',true),
                        new Array('endDate','End Date','width="8%" align="center"',true),
                        new Array('amountFunding','Amount Funding','width="10%" align="right"',true),
                        new Array('remarks','Remarks','width="10%" align="left"',true),
                        new Array('action1','Action','width="5%" align="center"',false)
                     );

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj6 = new initPage(url,recordsPerPage,linksPerPage,1,'','projectName','ASC','ConsultingResultDiv','ConsultingActionDiv','',true,'listObj6',tableColumns,'editWindow','deleteConsulting','&searchbox='+value+'&employeeId='+employeeId);
 sendRequest(url, listObj6, '', true)
}


 //this function Consulting Data
function refreshQualificationData(employeeId) {
	showEmployeeInfo();
}
function refreshFinancialData(employeeId,labelId) {
	
     var url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitFinancialInfo.php';
     new Ajax.Request(url,
           {
             method:'post',
             parameters: {employeeId: employeeId},
             onCreate: function(){
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if(trim(transport.responseText)!="") {
                     //location.reload();
                     var j = eval('('+trim(transport.responseText)+')');
                     document.getElementById('pfNumber').innerHTML=j.providentFundNo; 
                     document.getElementById('esiNumber').innerHTML=j.esiNumber; 
                     document.getElementById('pan').innerHTML=j.panNo; 
					 document.getElementById('bankName').innerHTML=j.bankName; 
					 document.getElementById('accountNo').innerHTML=j.accountNo; 
					 document.getElementById('branchName').innerHTML=j.branchName; 
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
  }
  
function refreshExperienceData(employeeId) {
	showEmployeeExperienceInfo();
	//document.getElementById('experienceResults').style.display = '';
	//document.getElementById('trExperience').style.display = '';
	//document.getElementById('experienceRow').style.display = '';
}

var bgclass='';
var resourceAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table 
function deleteRow(value) {
		//alert(value);
      var temp=resourceAddCnt;  
      try{  
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody_add');
	  var tr=document.getElementById('row'+rval[0]);
      
      if(isMozilla){
          if((tbody1.childNodes.length-4)==0){
              resourceAddCnt=0;
              if(!checkRowExisting()){
                  resourceAddCnt=temp;
                  return false;
              }
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
              if(!checkRowExisting()){
                  resourceAddCnt=temp;
                  return false;
              }
          }
      }
      
      tbody1.removeChild(tr);
	  
	  reCalculate();

      if(isMozilla){
		  //alert(tbody1.childNodes.length);
          if((tbody1.childNodes.length-3)==0){
			  resourceAddCnt=0;
			  document.getElementById('trQualification').style.display = 'none';
			  //checkRowExisting();
          }
      }

      else {
		  //alert(tbody1.childNodes.length);
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
			  document.getElementById('trQualification').style.display = 'none';
			  //checkRowExisting();
          }
       }
      }
      catch(e){
      }
}


function reCalculate(){
  var a=document.getElementsByTagName('td');
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
  resourceAddCnt=j-1;
}


//to add one row at the end of the list
    function addOneRow(cnt,mode) {
	
		document.getElementById('results').style.display = '';
		document.getElementById('trQualification').style.display = '';
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        //document.getElementById('deleteFlag').value=true;
        //alert(document.getElementById('itemCategory').value);
        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody_'+mode).childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody_'+mode).childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        }
		
        resourceAddCnt++; 
        createRows(resourceAddCnt,cnt,mode);
    }


	function createRows(start,rowCnt,mode){
           // alert(start+'  '+rowCnt);
         var tbl=document.getElementById('anyid_'+mode);
         var tbody = document.getElementById('anyidBody_'+mode);
         
         for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
		  cell1.setAttribute('align','right');
		  cell1.name='srNo';
          var cell2=document.createElement('td'); 
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td');
		  var cell5=document.createElement('td');
		  var cell6=document.createElement('td');
          
          cell1.setAttribute('align','left');
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
          var txt2=document.createElement('input');
		  var txt3=document.createElement('input');
		  var txt4=document.createElement('input');
          var txt5=document.createElement('a');
          
          txt1.setAttribute('id','ugDegree'+parseInt(start+i,10));
          txt1.setAttribute('name','ugDegree[]'); 
          txt1.className='inputbox1';
		  txt1.setAttribute('size',"32");
		  txt1.setAttribute('maxLength',"40");
		  txt1.setAttribute('type','text');
                    
          txt2.setAttribute('id','pgDegree'+parseInt(start+i,10));
          txt2.setAttribute('name','pgDegree[]');
          txt2.className='inputbox1';
          txt2.setAttribute('size',"32");
		  txt2.setAttribute('maxLength',"40");
          txt2.setAttribute('type','text');

		  txt3.setAttribute('id','highestQual'+parseInt(start+i,10));
          txt3.setAttribute('name','highestQual[]');
          txt3.className='inputbox1';
          txt3.setAttribute('size',"32");
		  txt3.setAttribute('maxLength',"40");
          txt3.setAttribute('type','text');

		  txt4.setAttribute('id','otherQual'+parseInt(start+i,10));
          txt4.setAttribute('name','otherQual[]');
          txt4.className='inputbox1';
          txt4.setAttribute('size',"32");
		  txt4.setAttribute('maxLength',"40");
          txt4.setAttribute('type','text');

          //hiddenIds.innerHTML=optionData;         
          txt5.setAttribute('id','rd');
          txt5.className='htmlElement';  
          txt5.setAttribute('title','Delete');       
          
          txt5.innerHTML='X';
          txt5.style.cursor='pointer';
          
		  if(mode == 'add') {
			//txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt5.onclick = new Function("deleteRow('" + parseInt(start+i,10)+'~0' + "')");
		  }
		  else if (mode == 'edit') {
			txt3.setAttribute('href','javascript:deleteEditRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
		  }
          
          cell1.appendChild(txt0);
          //cell1.appendChild(hiddenId);
          cell2.appendChild(txt1);
          cell3.appendChild(txt2);
          cell4.appendChild(txt3);
		  cell5.appendChild(txt4);
		  cell6.appendChild(txt5);
                 
          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
		  tr.appendChild(cell5);
		  tr.appendChild(cell6);
          
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;
          
          tbody.appendChild(tr); 
      
          // add option Teacher   
      }
      tbl.appendChild(tbody);   
   }

function validateAddQualificationForm(frm) {
    if(resourceAddCnt==0) {
		msg = confirm('All Qualifications of an employee will be deleted. Are you sure?')
        if(msg == false) {
			return false;
		}
    }
	
	if (resourceAddCnt != 0 ) {
		form = document.qualificationFrm;
		//total = form.elements['ugDegree[]'].length;
		var total=form.elements.length;
		var ele=document.qualificationFrm.elements;
		for(i=0;i<total;i++) {
           if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name.indexOf("ugDegree")>-1) {
			   if(ele[i].value != '') {
				if(isInteger(ele[i].value)){
					messageBox("<?php echo ENTER_ALPHABETS_SPECIAL_CHARACTERS ?>");
					ele[i].className = 'inputboxRed';
					ele[i].focus();
					return false;
				}
				else {
					ele[i].className = 'inputbox1';
				}
			  }
		   }

		   if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name.indexOf("pgDegree")>-1) {
			   if(ele[i].value != '') {
				if(isInteger(ele[i].value)){
					messageBox("<?php echo ENTER_ALPHABETS_SPECIAL_CHARACTERS ?>");
					ele[i].className = 'inputboxRed';
					ele[i].focus();
					return false;
				}
				else {
					ele[i].className = 'inputbox1';
				}
			  }
		   }

		   if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name.indexOf("highestQual")>-1) {
			   if(ele[i].value != '') {
				if(isInteger(ele[i].value)){
					messageBox("<?php echo ENTER_ALPHABETS_SPECIAL_CHARACTERS ?>");
					ele[i].className = 'inputboxRed';
					ele[i].focus();
					return false;
				}
				else {
					ele[i].className = 'inputbox1';
				}
			  }
		   }

		   if(ele[i].type.toUpperCase()=='TEXT' && ele[i].name.indexOf("otherQual")>-1) {
			   if(ele[i].value != '') {
				if(isInteger(ele[i].value)){
					messageBox("<?php echo ENTER_ALPHABETS_SPECIAL_CHARACTERS ?>");
					ele[i].className = 'inputboxRed';
					ele[i].focus();
					return false;
				}
				else {
					ele[i].className = 'inputbox1';
				}
			  }
		   }
        }
        //return;
		
    }
	addEmployeeQualification();
	    return false;
}

function addEmployeeQualification() {
	employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";

   url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitQualificationAdd.php';

   params = generateQueryString('qualificationFrm')+"&employeeId="+employeeId;
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
			messageBox(trim(transport.responseText)); 
            //location.reload();
            return false;
        }
		else {
			messageBox(trim(transport.responseText));
		}
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function checkRowExisting() {
	
	if(resourceAddCnt == 0 ) {
		if(false===confirm("Do you want to delete all the rows?")) {
			 //showEmployeeInfo();
			 return false;
			
		}
		else {
		employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";

	   url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitCheckQualification.php';

	   params = generateQueryString('qualificationFrm')+"&employeeId="+employeeId;
	   new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: params ,
		 onCreate: function () {
				 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			hideWaitDialog(true);    
			if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
				return false;
			}
			else {
				messageBox(trim(transport.responseText));
			}
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
       return true;
		}
	}
}


function showEmployeeInfo(){
	
  employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";
   url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxEmployeeQualificationGetValues.php';
   
  // resourceAddCnt=0; 
   //cleanUpTable();   
   
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {
             employeeId : employeeId
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                //cleanUpTable();                              
               // alert(transport.responseText);
			   //document.getElementById('trQualification').style.display = '';
                j = eval('('+trim(transport.responseText)+')');

				var len=j.employeeQualificationArr.length;

                if(len > 0) {
					//document.getElementById('trQualification').style.display = 'none';	
                    addOneRow(len,'add');
                    resourceAddCnt=len;
                    for(i=0;i<len;i++) {
                        varFirst = i+1;
                        ugDegree = 'ugDegree'+varFirst;
                        pgDegree = 'pgDegree'+varFirst;
						highestQual = 'highestQual'+varFirst;
						otherQual = 'otherQual'+varFirst;
						
                        document.getElementById(ugDegree).value = j['employeeQualificationArr'][i]['UGDegree'];
                        document.getElementById(pgDegree).value = j['employeeQualificationArr'][i]['PGDegree'];
						document.getElementById(highestQual).value = j['employeeQualificationArr'][i]['highestQualification'];
                        document.getElementById(otherQual).value = j['employeeQualificationArr'][i]['otherQualification'];
                   }

               }
			   
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
 
}

function reCalculate1(mode){
  var a=document.getElementsByTagName('td');
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo1'){
    if(mode==1){
     bgclass=(bgclass=='row0'? 'row1' : 'row0');
     a[i].parentNode.className=bgclass;
    }
      a[i].innerHTML=j;
      j++;
    }
  }
 // resourceExpAddCnt=j-1;
}

var bgclass='';
var resourceExpAddCnt=0;
// check browser
var isMozilla = (document.all) ? 0 : 1;

//for deleting a row from the table 
    function deleteExpRow(value){
		//alert(value);
    var temp=resourceExpAddCnt;    
	try {
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody1_add');
      if(isMozilla){
          if((tbody1.childNodes.length-4)==0){
              resourceExpAddCnt=0;
              if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }
          }
      }
      else{
          if((tbody1.childNodes.length-2)==0){
              resourceExpAddCnt=0;
              if(!checkExperienceRowExisting()){
                  resourceExpAddCnt=temp;
                  return false;
              }
          }
      }
      var tr=document.getElementById('row_exp'+rval[0]);
      tbody1.removeChild(tr);
	  
	  reCalculate1(1);
      
	  if(isMozilla){
          if((tbody1.childNodes.length-3)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }
      else{
		  //alert(tbody1.childNodes.length);
          if((tbody1.childNodes.length-1)==0){
              resourceExpAddCnt=0;
			  document.getElementById('trExperience').style.display = 'none';
			  //checkExperienceRowExisting();
          }
      }
	}
	catch (e) {
	}
    }


//to add one row at the end of the list
    function addExperienceOneRow(cnt,mode) {
	
		document.getElementById('experienceResults').style.display = '';
		document.getElementById('trExperience').style.display = '';
		//document.getElementById('experienceRow').style.display = '';
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
        //document.getElementById('deleteFlag').value=true;
        //alert(document.getElementById('itemCategory').value);
        if(cnt=='')
        cnt=1;
        if(isMozilla){
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 3){
                resourceExpAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody1_'+mode).childNodes.length <= 1){
               resourceExpAddCnt=0;  
             }       
        }
		
        resourceExpAddCnt++; 
        createExpRows(resourceExpAddCnt,cnt,mode);
    }


	function createExpRows(start,rowCnt,mode){
           // alert(start+'  '+rowCnt);
		 var serverDate = "<?php echo date('Y-m-d') ?>";
         var tbl=document.getElementById('anyid1_'+mode);
         var tbody = document.getElementById('anyidBody1_'+mode);
         
         for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row_exp'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
		  cell1.setAttribute('align','right');
		  cell1.name='srNo1';
          var cell2=document.createElement('td'); 
          var cell3=document.createElement('td'); 
          var cell4=document.createElement('td');
		  var cell5=document.createElement('td');
		  var cell6=document.createElement('td');
		  var cell7=document.createElement('td');
		  var cell8=document.createElement('td');
          
          cell1.setAttribute('align','left');
          cell2.setAttribute('align','left');
          cell3.setAttribute('align','left');
		  cell4.setAttribute('align','left');
		  cell5.setAttribute('align','left');
		  cell6.setAttribute('align','left');
		  cell7.setAttribute('align','left');
          cell8.setAttribute('align','center');

          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
          
          var txt3=document.createElement('input');
          var txt4=document.createElement('input');
		  var txt5=document.createElement('select');
		  var txt6=document.createElement('select');
          var txt7=document.createElement('a');
          
          txt3.setAttribute('id','organisation'+parseInt(start+i,10));
          txt3.setAttribute('name','organisation[]'); 
          txt3.className='inputbox1';
		  txt3.setAttribute('size',"20");
		  txt3.setAttribute('maxLength','"40"');
		  txt3.setAttribute('type','text');

          txt4.setAttribute('id','designation'+parseInt(start+i,10));
          txt4.setAttribute('name','designation[]');
          txt4.className='inputbox1';
          txt4.setAttribute('size','"20"');
		  txt4.setAttribute('maxLength',"40");
          txt4.setAttribute('type','text');

		  txt5.setAttribute('id','experience'+parseInt(start+i,10));
          txt5.setAttribute('name','experience[]');
          txt5.className='inputbox1';
          /*txt3.setAttribute('size','"20"');
		  txt3.setAttribute('maxlength','"40"');
          txt3.setAttribute('type','text');*/

		  txt6.setAttribute('id','expCertificate'+parseInt(start+i,10));
          txt6.setAttribute('name','expCertificate[]');
          txt6.className='inputbox1';
          /*txt4.setAttribute('size','"20"');
		  txt4.setAttribute('maxlength','"40"');
          txt4.setAttribute('type','text');*/

          //hiddenIds.innerHTML=optionData;         
          txt7.setAttribute('id','rd');
          txt7.className='htmlElement';  
          txt7.setAttribute('title','Delete');       
          
          txt7.innerHTML='X';
          txt7.style.cursor='pointer';
          
		  if(mode == 'add') {
			//txt7.setAttribute('onclick','javascript:deleteExpRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
            txt7.onclick = new Function("deleteExpRow('" + parseInt(start+i,10)+'~0' + "')");
		  }
		  /*else if (mode == 'edit') {
			txt3.setAttribute('href','javascript:deleteEditRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff
		  }*/
          
          cell1.appendChild(txt0);
          //cell1.appendChild(hiddenId);

		  cell2.innerHTML='<input type="text" id="fromDate'+parseInt(start+i,10)+'" name="fromDate'+parseInt(start+i,10)+'" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
		  cell2.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('fromDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
          cell3.innerHTML='<input type="text" id="toDate'+parseInt(start+i,10)+'" name="toDate'+parseInt(start+i,10)+'" class="inputBox" readonly="true" value="'+serverDate+'" size="8" />';
		  cell3.innerHTML +="<input type=\"image\" id=\"calImg\" name=\"calImg\" title=\"Select Date\" src=\""+imagePathURL+"/calendar.gif\"  onClick=\"return showCalendar('toDate"+parseInt(start+i,10)+"','%Y-%m-%d', '24', true);\">";
          cell4.appendChild(txt3);
		  cell5.appendChild(txt4);
		  cell6.appendChild(txt5);
		  cell7.appendChild(txt6);
		  cell8.appendChild(txt7);
                 
          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
		  tr.appendChild(cell5);
		  tr.appendChild(cell6);
		  tr.appendChild(cell7);
		  tr.appendChild(cell8);
          
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;
          
          tbody.appendChild(tr); 
      
          // add option Teacher   
		  if(mode == 'add') {
			  var len= document.getElementById('experienceData').options.length;
			  var t=document.getElementById('experienceData');
			  // add option Select initially
			  if(len>0) {
				var tt='experience'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }
			  }

			  var len= document.getElementById('experienceCertificate').options.length;
			  var t=document.getElementById('experienceCertificate');
			  // add option Select initially
			  if(len>0) {
				var tt='expCertificate'+parseInt(start+i,10) ; 
				//alert(eval("document.getElementById(tt).length"));
				for(k=0;k<len;k++) { 
				  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
				 }

			  }
		  }
      }
      tbl.appendChild(tbody); 
      reCalculate1(0);  
   }


  function validateAddExperienceForm(frm) {
	var dtStr='';
	var toStr='';
	var serverDate = "<?php echo date('Y-m-d') ?>";
    if(resourceExpAddCnt==0) {
		msg = confirm('All experiences of an employee will be deleted. Are you sure?')
        if(msg == false) {
			return false;
		}
    }

	if (resourceExpAddCnt != 0 ) {
		form = document.experienceFrm;
		//totalExp = document.getElementById['organisation[]'].length;
		for(i=0;i<resourceExpAddCnt;i++) {
			
			try {
			    //*****to throw exception,this dummy line is added.*****
				 var v=document.getElementById('fromDate'+(i+1)).value;
			    //*****to throw exception,this dummy line is added.*****

				if(dtStr!=''){
                  dtStr +=',';
                }

				if(toStr!=''){
		           toStr +=',';
                }
				dtStr += document.getElementById('fromDate'+(i+1)).value;
				toStr += document.getElementById('toDate'+(i+1)).value;

				if(!dateDifference(document.getElementById('fromDate'+(i+1)).value,serverDate,'-')) {
					messageBox("<?php echo FROM_NOT_GREATER_CURDATE ?>");
					document.getElementById('fromDate'+(i+1)).className = 'inputboxRed';
					document.getElementById('fromDate'+(i+1)).focus();
					return false;
				}
				else {
					document.getElementById('fromDate'+(i+1)).className = 'inputBox';
				}

				if(!dateDifference(document.getElementById('toDate'+(i+1)).value,serverDate,'-')) {
					messageBox("<?php echo TO_NOT_GREATER_CURDATE ?>");
					document.getElementById('toDate'+(i+1)).className = 'inputboxRed';
					document.getElementById('toDate'+(i+1)).focus();
					return false;
				}
				else {
					document.getElementById('toDate'+(i+1)).className = 'inputBox';
				}

				if(!dateDifference(document.getElementById('fromDate'+(i+1)).value,document.getElementById('toDate'+(i+1)).value,'-')) {
					messageBox("<?php echo FROM_TO_NOT_GREATER_CURDATE ?>");
					document.getElementById('fromDate'+(i+1)).className = 'inputboxRed';
					document.getElementById('fromDate'+(i+1)).focus();
					return false;
				}
				else {
					document.getElementById('toDate'+(i+1)).className = 'inputBox';
				}
			}
			catch(e){
				
			}
			
		}
	}
    addEmployeeExperience(dtStr,toStr);
    return false;
}

function addEmployeeExperience(dtStr,toStr) {
	
	employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";

   url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitExperienceAdd.php';

   params = generateQueryString('experienceFrm')+"&employeeId="+employeeId+"&fromDateString="+dtStr+"&toDateString="+toStr;
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
			messageBox(trim(transport.responseText)); 
            return false;
        }
		else {
			messageBox(trim(transport.responseText));
		}
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function showEmployeeExperienceInfo(){

	employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";
   
   url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxEmployeeExperienceGetValues.php';
   
  // resourceAddCnt=0; 
   //cleanUpTable();   
   
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {
             employeeId : employeeId
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                //cleanUpTable();                              
               // alert(transport.responseText);
			   document.getElementById('experienceResults').style.display = '';
                j = eval('('+trim(transport.responseText)+')');
               var len = j.employeeExperienceArr.length;
				
				                 
                if(len > 0) {
					//document.getElementById('trQualification').style.display = 'none';	
                    addExperienceOneRow(len,'add');
                    resourceExpAddCnt=len;
                    for(i=0;i<len;i++) {

                        varFirst = i+1;
                        fromDate = 'fromDate'+varFirst;
                        toDate = 'toDate'+varFirst;
						organisation = 'organisation'+varFirst;
						designation = 'designation'+varFirst;
						experience = 'experience'+varFirst;
						expCertificate = 'expCertificate'+varFirst;
						
                        document.getElementById(fromDate).value = j['employeeExperienceArr'][i]['fromDate'];
                        document.getElementById(toDate).value = j['employeeExperienceArr'][i]['toDate'];
						document.getElementById(organisation).value = j['employeeExperienceArr'][i]['organisation'];
                        document.getElementById(designation).value = j['employeeExperienceArr'][i]['designation'];
						document.getElementById(experience).value = j['employeeExperienceArr'][i]['experience'];
						document.getElementById(expCertificate).value = j['employeeExperienceArr'][i]['expCertificateAvailable'];
                   }

               }
			   
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
 
}

function checkExperienceRowExisting() {

	if(resourceExpAddCnt == 0 ) {
		if(false===confirm("Do you want to delete all the rows?")) {
			//showEmployeeExperienceInfo();
			//location.reload();
			 return false;
		}
		else {
		
            employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";
	        url = '<?php echo HTTP_LIB_PATH;?>/Employee/ajaxInitCheckExperience.php';
	        params = generateQueryString('qualificationFrm')+"&employeeId="+employeeId;
	           new Ajax.Request(url,
	            {
		          method:'post',
		          parameters: params ,
		          onCreate: function () {
				         showWaitDialog(true);
		          },
		          onSuccess: function(transport){
			         hideWaitDialog(true);    
			         if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
				        return false;
			         }
			         else {
				        messageBox(trim(transport.responseText));
			         }
		          },
		          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	            }
              );
          return true;    
		}
	}
}
 
winLayerWidth  = 340; //  add/edit form width
winLayerHeight = 250; // add/edit form height

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
//Author : Parveen Sharma
// Created on : (05.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------


// Course Analytics    Start
function printReport() {

    var subjectId = document.getElementById("subjectId").value;
    var timeTableLabelId = document.getElementById("ttLabelId").value;
    var employeeId = document.getElementById("employeeId1").value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeName = document.getElementById('employeeName1').value;   
    
    path='<?php echo UI_HTTP_PATH;?>/courseAnalyticsPrint.php?employeeId='+employeeId+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&subjectId='+subjectId;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function printReportCSV() {
    
    var subjectId = document.getElementById("subjectId").value;
    var timeTableLabelId = document.getElementById("ttLabelId").value;
    var employeeId = document.getElementById("employeeId1").value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeName = document.getElementById('employeeName1').value;   
    
    path='<?php echo UI_HTTP_PATH;?>/courseAnalyticsCSV.php?employeeId='+employeeId+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&subjectId='+subjectId;
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location=path;
} 
// Course Analytics    End


// Lecture Delivered    Start
function lecturePrintReport() {

    var timeTableLabelId = document.getElementById('labelId').value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeId = document.getElementById('employeeId1').value;   
    var employeeName = document.getElementById('employeeName1').value;   

    lectureGroupTypeName='';
    lectureGroupType='';
    
    var cnt = document.addForm.lectureGroupType.length;
    if(cnt>0) {
         for(var i=0;i<cnt;i++){
            if(lectureGroupTypeName=='') {
              lectureGroupType  = document.addForm.lectureGroupType.options[i].value;
              lectureGroupTypeName = document.addForm.lectureGroupType.options[i].text;
            }
            else {
              lectureGroupType  = lectureGroupType + ','+document.addForm.lectureGroupType.options[i].value;
              lectureGroupTypeName = lectureGroupTypeName + ','+document.addForm.lectureGroupType.options[i].text;
            }
         }          
    }         
 
    path='<?php echo UI_HTTP_PATH;?>/lectureDeliveredPrint.php?employeeId='+employeeId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function lecturePrintReportCSV() {
    
    var timeTableLabelId = document.getElementById('labelId').value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeId = document.getElementById('employeeId1').value;   
    var employeeName = document.getElementById('employeeName1').value;   
    
    lectureGroupTypeName='';
    lectureGroupType='';
    
    var cnt = document.addForm.lectureGroupType.length;
    if(cnt>0) {
         for(var i=0;i<cnt;i++){
            if(lectureGroupTypeName=='') {
              lectureGroupType  = document.addForm.lectureGroupType.options[i].value;
              lectureGroupTypeName = document.addForm.lectureGroupType.options[i].text;
            }
            else {
              lectureGroupType  = lectureGroupType + ','+document.addForm.lectureGroupType.options[i].value;
              lectureGroupTypeName = lectureGroupTypeName + ','+document.addForm.lectureGroupType.options[i].text;
            }
         }          
    }  
    
    path='<?php echo UI_HTTP_PATH;?>/lectureDeliveredCSV.php?employeeId='+employeeId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location=path;
}

 // Lecture Delivered    End

// Course Topicwise End
  function topicwisePrintReport() {
                                                                                                                                          
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value;  
    path='<?php echo UI_HTTP_PATH;?>/topicwisePrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj5.sortOrderBy+'&sortField='+listObj5.sortField+str;
    //alert(path);
    window.open(path,"TopicwiseReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function topicwisePrintReportCSV() {
    
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value+'&timeTableLabelId='+document.getElementById('timeTableLabelId').value;    
    path='<?php echo UI_HTTP_PATH;?>/topicwisePrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj5.sortOrderBy+'&sortField='+listObj5.sortField+str;
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location = path;
}

// Course Topicwise End
 
 
// Publisher Start

function fileUploadError(str,mode){
   hideWaitDialog(true);
   //globalFL=1;
   if("<?php echo SUCCESS;?>" != trim(str)) {
      if(mode!=0) {
        messageBox(trim(str));
      }
   }
   if(mode==1){
      if("<?php echo SUCCESS;?>" == trim(str)) {
         flag = true;
         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
            blankValues();
         }
         else {
            hiddenFloatingDiv('PublishingActionDiv');
            refreshBookJournalsData(document.getElementById('employeeId1').value); 
            return false;
         }
      }  
   }
   else if(mode==2){
      if("<?php echo SUCCESS;?>" == trim(str)) {
          hiddenFloatingDiv('PublishingActionDiv');
          refreshBookJournalsData(document.getElementById('employeeId1').value);        
          return false;
      }
   }
   else{
      messageBox(trim(str));  
   }
}


function initAdd(mode) {
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('PublishingDetail').target = 'fileUpload';
        document.getElementById('PublishingDetail').action= "<?php echo HTTP_LIB_PATH;?>/EmployeeReports/fileUpload.php"
        document.getElementById('PublishingDetail').submit();
    }
   else{
      document.getElementById('PublishingDetail').target = 'fileUpload';
      document.getElementById('PublishingDetail').action= "<?php echo HTTP_LIB_PATH;?>/EmployeeReports/fileUpload.php"
      document.getElementById('PublishingDetail').submit(); 
   } 
}

// Publisher File Download
function  download(str){  
    var address="<?php echo IMG_HTTP_PATH;?>/Teacher/Publishing/"+escape(str)+"?x="+(Math.random()*150);
    //window.location=address;
    window.open(address,"Attachment","status=1,resizable=1,width=800,height=600")
}

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button 

function editWindow(id,dv,w,h) {
    document.getElementById('divHeaderId2').innerHTML='&nbsp; Edit Publishing';
    //displayWindow(dv,winLayerWidth,winLayerHeight);
    displayWindow(dv,360,100);
    populateValues(id);   
}


function publisherPrintReport() {
    
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/publisherPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&searchbox='+document.getElementById("searchboxPublishing").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function publisherPrintReportCSV() {
    
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/publisherPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&searchbox='+document.getElementById("searchboxPublishing").value+str;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location=path;
}

//-------------------------------------------------------
//THIS FUNCTION validateAddForm() IS USED TO VALIDATE THE FORM
//
//frm : returns the name of the form
//act : action value of the button (add/edit)
//Author : Parveen Sharma
// Created on : (05.03.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function validateAddForm(frm, act) {   
     
    if(globalFL==0){
      //messageBox("Another request is in progress.");
      return false;
    }
    
    var fieldsArray = new Array(new Array("type","<?php echo ENTER_TYPE_NAME ?>"), 
                                new Array("scopeId","<?php echo SELECT_SCOPE ?>"), 
                                new Array("publishOn","<?php echo ENTER_PUBLISHER_DATE ?>"),
                                new Array("publishedBy","<?php echo ENTER_PUBLISHER_NAME?>"),
                                new Array("description","<?php echo ENTER_DESCRIPTION ?>")
                               );

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    
    if(trim(document.getElementById('publisherAttachment').value)!=""){
        if(!checkFileExtensions(trim(document.getElementById('publisherAttachment').value))){
           document.getElementById('publisherAttachment').focus();  
           messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
           return false;
        } 
    }  
    
    
    
    if(trim(document.getElementById('publisherAccpLet').value)!=""){
        if(!checkFileExtensions(trim(document.getElementById('publisherAccpLet').value))){
            document.getElementById('publisherAccpLet').focus();  
            messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
            return false;
         } 
    }  
  
  
    if(document.getElementById('publishId').value=='') {
       addPublishing();
    }
    else{
       editPublishing();
    }
}                                                                      

//-------------------------------------------------------
//THIS FUNCTION addDocument() IS USED TO ADD NEW TRAINING
//
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addPublishing() {
     globalFL=0;   
     var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitPublishingAdd.php';
     empId = document.PublishingDetail.employeeId.value;
     
     new Ajax.Request(url,
       {
         method:'post',
         asynchronous:false,
         parameters: {
            employeeId:   document.getElementById('employeeId1').value,
            type:         trim(document.PublishingDetail.type.value),
            scopeId:      trim(document.PublishingDetail.scopeId.value),
            publishOn:    trim(document.PublishingDetail.publishOn.value),
            publishedBy:  trim(document.PublishingDetail.publishedBy.value),
            description:  trim(document.PublishingDetail.description.value),
            hiddenFile1:  document.getElementById('publisherAttachment').value,
            hiddenFile2:  document.getElementById('publisherAccpLet').value      
        },
         onCreate: function() {
             //showWaitDialog(true);
         },
         onSuccess: function(transport){
            initAdd(1);    
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deletePublishing(id) {
     if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
         return false;
     }
     else {   
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitPublishingDelete.php';
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {publishId: id},
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     refreshBookJournalsData(document.getElementById('employeeId1').value);
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

//----------------------------------------------------------------------
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Parveen Sharma
// Created on : (22.12.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

function blankValues() {
    document.PublishingDetail.reset();
    
    document.getElementById('divHeaderId2').innerHTML='&nbsp; Add Publishing';
    document.PublishingDetail.type.value = '';
    //document.PublishingDetail.publishOn.value = '';
    document.PublishingDetail.publishedBy.value = '';
    document.PublishingDetail.description.value = '';
    document.getElementById("scopeId").selectedIndex=0;
    document.getElementById('publishId').value='';
    document.getElementById('attachmentLink').innerHTML='';
    document.getElementById('accptLink').innerHTML='';
    document.getElementById('attachmentLink').style.display = 'none';       
    document.getElementById('accptLink').style.display = 'none';   
    //document.getElementById("employeeName").innerHTML = "";
    //document.getElementById("employeeCode").innerHTML = "";
    document.PublishingDetail.type.focus();
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editPublishing() {
         globalFL=0; 
         var url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitPublishingEdit.php';
         var empId = trim(document.PublishingDetail.employeeId.value); 
         new Ajax.Request(url,
         {
             method:'post',
             asynchronous:false,
             parameters:{ publishId:    trim(document.PublishingDetail.publishId.value),
                          employeeId:   document.getElementById('employeeId1').value,
                          type:         trim(document.PublishingDetail.type.value),
                          scopeId:      trim(document.PublishingDetail.scopeId.value),
                          publishOn:    trim(document.PublishingDetail.publishOn.value),
                          publishedBy:  trim(document.PublishingDetail.publishedBy.value),
                          description:  trim(document.PublishingDetail.description.value),
                          hiddenFile1:  document.getElementById('publisherAttachment').value,
                          hiddenFile2:  document.getElementById('publisherAccpLet').value      
                        },
         onCreate: function() {
             //showWaitDialog(true);
         },
         onSuccess: function(transport){
            initAdd(2);    
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Publisher Details" DIV
//---------------------------------------------------------
function showPublisherDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populatePublisherValues(id);
}

function populatePublisherValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxPublishingGetValues1.php';     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {publishId: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            hiddenFloatingDiv('divPublisherInfo');
            messageBox("<?php echo PUBLISHING_NOT_EXIST; ?>");
         }
         j = trim(transport.responseText);
         document.getElementById('publisherInfo').innerHTML= j;    
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxPublishingGetValues.php';               
         blankValues();
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {publishId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                  hideWaitDialog(true);
                  if(trim(transport.responseText)==0) {
                     hiddenFloatingDiv('PublishingActionDiv');
                     messageBox("<?php echo PUBLISHING_NOT_EXIST; ?>");
                     refreshBookJournalsData(document.getElementById('employeeId1').value);
                  }
                  j = eval('('+trim(transport.responseText)+')');
                  document.getElementById('divHeaderId2').innerHTML='&nbsp; Edit Publishing';
                  document.getElementById("employeeName").innerHTML  = j.employeeName;
                  document.getElementById("employeeCode").innerHTML  = j.employeeCode;
                  document.PublishingDetail.type.value               = j.type;
                  document.PublishingDetail.publishOn.value          = j.publishOn;
                  document.PublishingDetail.scopeId.value            = j.scopeId;  
                  document.PublishingDetail.publishedBy.value        = j.publishedBy;
                  document.PublishingDetail.description.value        = j.description ;
                  document.PublishingDetail.publishId.value          = j.publishId;
                  document.PublishingDetail.employeeId.value         = j.employeeId; 
                  
                  if(j.attachmentFile!='') { 
                     //imageLogoPath = '<img name="logo" src="<?php echo IMG_HTTP_PATH;?>/Institutes/'+j.edit[0].instituteLogo+'?'+rndNo+'" border="0" width="70" height="70" title="Close"/>';
                     var imageLogoPath = '<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" name="imageDownload" onclick=download("'+j.attachmentFile+'"); title="Download File" />';
                     document.getElementById('attachmentLink').style.display = 'block';
                     document.getElementById('attachmentLink').innerHTML = imageLogoPath;
                   }
                   else {
                      document.getElementById('attachmentLink').style.display =  'none';
                   }
                   
                   if(j.attachmentAcceptationLetter!='') { 
                     var  imageLogoPath = '<img src="<?php echo IMG_HTTP_PATH; ?>/download.gif" name="imageDownload1" onClick=download("'+j.attachmentAcceptationLetter+'"); title="Download File" />';
                     document.getElementById('accptLink').style.display = 'block';   
                     document.getElementById('accptLink').innerHTML = imageLogoPath;
                   }
                   else {
                     document.getElementById('accptLink').style.display = 'none';
                   }                  
                   document.PublishingDetail.type.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });

}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO GET THE VALUES
//
//Author : Parveen Sharma
// Created on : (25.11.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getEmployee(employeeId) {
        url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployeeDetail.php';
        blankValues();
        
        new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId:  employeeId 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EmployeeLeaveDetailActionDiv');
                        messageBox("<?php echo EMPLOYEE_NOT_EXIST ?>");
                        blankValues();
                        return false;
                   }
                   j = eval('('+transport.responseText+')'); 
                   document.getElementById("employeeId").value = j.employeeId;
                   document.getElementById("employeeName").innerHTML = j.employeeName;
                   document.getElementById("employeeCode").innerHTML = j.employeeCode;
             },
            onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}           
// Publisher End


// Seminar Start           


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Seminar Details" DIV
//-------------------------------------------------------
function showSeminarDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populateSeminarValues(id);
}

function populateSeminarValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxSeminarGetValues1.php';     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {seminarId: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            hiddenFloatingDiv('divSeminarInfo');
            messageBox("<?php echo SEMINAR_NOT_EXIST; ?>");
         }
         j = trim(transport.responseText);
         document.getElementById('seminarInfo').innerHTML= j;    
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}

function seminarEditWindow(id,dv,w,h) {
    document.getElementById('divHeaderId1').innerHTML='&nbsp; Edit Seminar';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    seminarPopulateValues(id);   
}

function seminarPrintReport() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/seminarPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField+'&searchbox='+document.getElementById("searchboxSeminar").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function seminarPrintReportCSV() {
    
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/seminarPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField+'&searchbox='+document.getElementById("searchboxSeminar").value+str;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location = path;
}

// MDP Start           
//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "MDP Details" DIV
//-------------------------------------------------------


function showmdpDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populateMdpValues(id);
}

function populateMdpValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxMdpGetValues1.php';     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {mdpId: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            hiddenFloatingDiv('divMdpInfo');
          //  messageBox("<?php echo MDP_NOT_EXIST; ?>");
         }
         j = trim(transport.responseText);
         document.getElementById('mdpInfo').innerHTML= j;    
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}

function mdpEditWindow(id,dv,w,h) {
    document.getElementById('divHeaderId5').innerHTML='&nbsp; Edit Mdp';
    displayWindow(dv,winLayerWidth,winLayerHeight);
    mdpPopulateValues(id);   
}
function mdpPrintReport() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/mdpPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&searchbox='+document.getElementById("searchboxMdp").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function mdpPrintReportCSV() {
    
    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
    path='<?php echo UI_HTTP_PATH;?>/mdpPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&searchbox='+document.getElementById("searchboxMdp").value+str;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location = path;
}


 //----------------------------------------------------
 //This is for adding Mdp
 //----------------------------------------------------
 function validateMdpAddForm(frm, act) {
    var fieldsArray = new Array(new Array("mdpName","<?php echo ENTER_MDP_NAME ?>"), 
			new Array("mdpstartDate","<?php echo SELECT_MDP_START_DATE ?>"),
			new Array("mdpendDate","<?php echo SELECT_MDP_END_DATE ?>"),
			new Array("mdpSelectId","<?php echo SELECT_MDP ?>"),
			new Array("mdpSessionAttended","<?php echo ENTER_MDP_SESSION_ATTENDED ?>"),
			new Array("mdpHours","<?php echo ENTER_MDP_HOURS ?>"),
			new Array("mdpVenue","<?php echo ENTER_MDP_VENUE ?>"),
			new Array("mdpDescription","<?php echo ENTER_DESCRIPTION ?>"));

	var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	
	if(!dateDifference(eval("frm.mdpstartDate.value"),eval("frm.mdpendDate.value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("frm.mdpstartDate.focus();");
        return false;
    }
	  
    if(trim(eval("frm.mdpSessionAttended.value"))!='' && !isInteger(trim(eval("frm.mdpSessionAttended.value")))) {
        messageBox ("<?php echo ENTER_VALID_VALUE_FOR_SESSIONS_ATTENDED;?>");
        eval("frm.mdpSessionAttended.focus();");
        return false;  
    }
	
    if(trim(eval("frm.mdpHours.value"))!='' && !isInteger(trim(eval("frm.mdpHours.value")))) {
        messageBox ("<?php echo ENTER_VALID_VALUE_FOR_HOURS;?>");
        eval("frm.mdpHours.focus();");
        return false;  
    }
	 
    if(document.getElementById('mdpId').value=='') {
        addMdp();
		
        return false;
    }
    else {
        editMdp();
        return false;
    }
}
 
 //----------------------------------------------
 // THIS FUNCTION IS FOR ADDING OF MDP
 // Author :Gagan Gill
 // Dated  :13-Dec-2010
 //----------------------------------------------
   function addMdp() {
	  
     employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";
	  
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitMdpAdd.php';
	  
        empId = document.mdpDetail.mdpEmployeeId.value;
	 
		
		var pars = generateQueryString('mdpDetail');
		     
		pars += '&employeeId='+employeeId;
       
         new Ajax.Request(url,
           {
             method:'post',
			
             parameters: pars,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
	 
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
	                
                     flag = true;
                     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 mdpBlankValues();
                     }
                     else {
	                    hiddenFloatingDiv('MdpActionDiv');
                        refreshMdpData(document.getElementById('employeeId1').value);
                        return false;
                    }
                }
                else {
                    messageBox(trim(transport.responseText));  
                }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
		   //mdpBlankValues();
}    

 //-------------------------------------------------------
 // THIS FUNCTION IS USED TO DELETE A MDP DOCUMENT
 // id = documentId
 // Author : Gagan Gill
 // Created on : (28.02.2009)
 // Copyright 2008-2009  Chalkpad Technologies Pvt. Ltd.
 //--------------------------------------------------------
function deleteMdp(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitMdpDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mdpId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         refreshMdpData(document.getElementById('employeeId1').value);
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
 //----------------------------------------------------------------------
 // THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV FOR MDP
 // Author : Gagan Gill
 // Created on : (22.12.2008)
 // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
 //---------------------------------------------------------------------

  function mdpBlankValues() {
	  //alert(str);
    document.mdpDetail.reset(); 
    document.getElementById('divHeaderId5').innerHTML='&nbsp; Add Mdp';
  //document.getElementById("mdpEmployeeName").innerHTML        = "";
  //document.getElementById("mdpEmployeeName").innerHTML        = "";
  //document.getElementById("mdpEmployeeCode").innerHTML        = "";
    document.mdpDetail.mdpName.value                            = '';
  //document.mdpDetail.mdpstartDate.value                       = '';
  //document.mdpDetail.mdpendDate.value                         = '';                   
    document.mdpDetail.mdpType.value                            = '';
    document.mdpDetail.mdpHours.value                           = '';
 	document.mdpDetail.mdpVenue.value                           = '';
	document.mdpDetail.mdpSessionAttended.value                 = '';
	document.mdpDetail.mdpDescription.value                     = '';
    document.getElementById("mdpSelectId").selectedIndex=0;
    document.getElementById('mdpId').value                      = '';
  //  document.mdpDetail.mdpName.focus();
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A MDP DOCUMENT
//
// Author : Gagan Gill
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  function editMdp() {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitMdpEdit.php';
        
         new Ajax.Request(url,
           {
             method:'post',
             parameters:generateQueryString('mdpDetail'),
                  
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('MdpActionDiv');
                     refreshMdpData(document.getElementById('employeeId1').value);
                     return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));  
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
// THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
// Author : Gagan Gill
// Created on : (28.02.2009)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function mdpPopulateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxMdpGetValues.php';
         mdpBlankValues();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {mdpId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   /*if(trim(transport.responseText)==0) {
                     hiddenFloatingDiv('SeminarActionDiv');
                     messageBox("<?php echo PUBLISHING_NOT_EXIST; ?>");
                     refreshSeminarData(employeeId);
                   } */
                   j = eval('('+trim(transport.responseText)+')');
				   
                   document.getElementById('divHeaderId5').innerHTML='&nbsp; Edit Mdp';     
                   
				   /*document.getElementById("mdpEmployeeName").innerHTML  = j.employeeName;
                   document.getElementById("mdpEmployeeCode").innerHTML  = j.employeeCode;
				   */
                   document.mdpDetail.mdpName.value                         = j.mdpName;
                   document.mdpDetail.mdpstartDate.value                    = j.startDate;
                   document.mdpDetail.mdpendDate.value                      = j.endDate;
                   document.mdpDetail.mdpSelectId.value                     = j.mdp ;
                   document.mdpDetail.mdpSessionAttended.value              = j.sessionsAttended;
                   document.mdpDetail.mdpHours.value                        = j.hoursAttended;
				   //document.mdpDetail.employeeId.value				    = j.employeeId; 
                   document.mdpDetail.mdpVenue.value                        = j.venue; 
				   mdpTypeArray = j.mdpType.split(',');
				   
				   for(i=0; i<mdpTypeArray.length; i++) {
						selectedValue = mdpTypeArray[i];
						loopCnt = document.mdpDetail.elements['mdpType'].length;
						for (t = 0; t < loopCnt; t++) {
							if (document.mdpDetail.elements['mdpType'][t].value == selectedValue) {
								document.mdpDetail.elements['mdpType'][t].checked = true;
							}
						}
				   }


                   //document.mdpDetail.TypeId.value                       = j.mdpTypeId; 
                   document.mdpDetail.mdpDescription.value                  = j.description;
                    document.mdpDetail.mdpId.value=j.mdpId;
                    
                   document.mdpDetail.mdpName.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
      }
//---------------------------------------
// FUNCTION FOR GETTING MDP EMPLOYEE
//---------------------------------------
  function getMdpEmployee(employeeId) {
        url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployeeDetail.php';
        mdpBlankValues();
        
        new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId:  employeeId 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EmployeeLeaveDetailActionDiv');
                        messageBox("<?php echo EMPLOYEE_NOT_EXIST ?>");
                        mdpBlankValues();
                        return false;
                   }
                   j = eval('('+transport.responseText+')'); 
                   document.getElementById("mdpEmployeeId").value = j.employeeId;
                   document.getElementById("mdpEmployeeName").innerHTML = j.employeeName;
                   document.getElementById("mdpEmployeeCode").innerHTML = j.employeeCode;
             },
            onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
} 

//----------------------------------------------------
// this is for Validation of seminars
//----------------------------------------------------
function validateSeminarAddForm(frm, act) {   
   
    var fieldsArray = new Array(new Array("seminarOrganisedBy","<?php echo ENTER_SEMINAR_ORGANISEDBY ?>"), 
                                new Array("seminarTopic","<?php echo ENTER_SEMINAR_TOPIC?>"),
                                new Array("seminarDescription","<?php echo ENTER_SEMINAR_DESCRIPTION ?>"),
                                new Array("startDate","<?php echo ENTER_SEMINAR_START_DATE ?>"),
                                new Array("endDate","<?php echo ENTER_SEMINAR_END_DATE ?>"),
                                new Array("seminarPlace","<?php echo ENTER_SEMINAR_PLACE ?>"),
                                new Array("participationId","<?php echo SELECT_PARTICIPATION ?>"));
                                
    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    
    if(!dateDifference(eval("frm.startDate.value"),eval("frm.endDate.value"),'-') ) {
        messageBox ("<?php echo DATE_CONDITION;?>");
        eval("frm.startDate.focus();");
        return false;
    }
    
    if(trim(eval("frm.seminarFee.value"))!='' && !isInteger(trim(eval("frm.seminarFee.value")))) {
        messageBox ("<?php echo INVALID_SEMINAR_FEE;?>");
        eval("frm.seminarFee.focus();");
        return false;  
    }

    if(document.getElementById('seminarId').value=='') {
        addSeminar();
        return false;
    }
    else{
        editSeminar();
        return false;
    }
}
//-------------------------------------------------
//  THIS FUNCTION IS FOR ADDING A SEMINAR
//-------------------------------------------------
function addSeminar() {
        
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitSeminarAdd.php';
         empId = document.SeminarDetail.seminarEmployeeId.value;
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                employeeId           :  document.getElementById('employeeId1').value,
                seminarOrganisedBy   :  trim(document.SeminarDetail.seminarOrganisedBy.value),
                seminarTopic         :  trim(document.SeminarDetail.seminarTopic.value),
                startDate            :  trim(document.SeminarDetail.startDate.value),
                endDate              :  trim(document.SeminarDetail.endDate.value),
                seminarPlace         :  trim(document.SeminarDetail.seminarPlace.value),
                seminarDescription   :  trim(document.SeminarDetail.seminarDescription.value),
                participationId      :  trim(document.SeminarDetail.participationId.value),
                fee                  :  trim(document.SeminarDetail.seminarFee.value)
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     flag = true;
                     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                         seminarBlankValues();
                     }
                     else {
                         hiddenFloatingDiv('SeminarActionDiv');
                         refreshSeminarData(document.getElementById('employeeId1').value);
                         return false;
                    }
                }
                else {
                    messageBox(trim(transport.responseText));  
                }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//  Author : Parveen Sharma
//  Created on : (28.02.2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
   function deleteSeminar(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {   
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitSeminarDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {seminarId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         refreshSeminarData(document.getElementById('employeeId1').value);
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

//----------------------------------------------------------------------
//  THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//  Author : Parveen Sharma
//  Created on : (22.12.2008)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------

 function seminarBlankValues() {
    document.SeminarDetail.reset(); 
    document.getElementById('divHeaderId1').innerHTML='&nbsp; Add Seminar';
    //document.getElementById("seminarEmployeeName").innerHTML = "";
    //document.getElementById("seminarEmployeeName").innerHTML = "";
    //document.getElementById("seminarEmployeeCode").innerHTML = "";
    document.SeminarDetail.seminarOrganisedBy.value    = '';
    document.SeminarDetail.seminarTopic.value          = '';
    document.SeminarDetail.seminarDescription.value    = '';
    //document.SeminarDetail.startDate.value             = '';
    // document.SeminarDetail.endDate.value               = '';
    document.SeminarDetail.seminarPlace.value          = '';
    document.SeminarDetail.seminarFee.value            = '';
    document.getElementById("participationId").selectedIndex=0;
    document.getElementById('seminarId').value='';
    document.SeminarDetail.seminarOrganisedBy.focus();
}

//-------------------------------------------------------
//  THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//  Author : Parveen Sharma
//  Created on : (28.02.2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editSeminar() {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitSeminarEdit.php';
          
         new Ajax.Request(url,
           {
             method:'post',
             parameters: { 
                    seminarId            :  trim(document.SeminarDetail.seminarId.value),
                    employeeId           :  document.getElementById('employeeId1').value,
                    seminarOrganisedBy   :  trim(document.SeminarDetail.seminarOrganisedBy.value),
                    seminarTopic         :  trim(document.SeminarDetail.seminarTopic.value),
                    startDate            :  trim(document.SeminarDetail.startDate.value),
                    endDate              :  trim(document.SeminarDetail.endDate.value),
                    seminarPlace         :  trim(document.SeminarDetail.seminarPlace.value),
                    seminarDescription   :  trim(document.SeminarDetail.seminarDescription.value),
                    participationId      :  trim(document.SeminarDetail.participationId.value),
                    fee                  :  trim(document.SeminarDetail.seminarFee.value)
              },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('SeminarActionDiv');
                     refreshSeminarData(document.getElementById('employeeId1').value);
                     return false;
                 }
                 else {
                    messageBox(trim(transport.responseText));  
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
           });
}
//-------------------------------------------------------
//  THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//  Author : Parveen Sharma
//  Created on : (28.02.2009)
//  Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function seminarPopulateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxSeminarGetValues.php';
         seminarBlankValues();
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {seminarId: id},
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   /*if(trim(transport.responseText)==0) {
                     hiddenFloatingDiv('SeminarActionDiv');
                     messageBox("<?php echo PUBLISHING_NOT_EXIST; ?>");
                     refreshSeminarData(employeeId);
                   } */
                   j = eval('('+trim(transport.responseText)+')');
                   document.getElementById('divHeaderId1').innerHTML='&nbsp; Edit Seminar';     
                   document.getElementById("seminarEmployeeName").innerHTML  = j.employeeName;
                   document.getElementById("seminarEmployeeCode").innerHTML  = j.employeeCode;
                   document.SeminarDetail.seminarOrganisedBy.value    = j.organisedBy;
                   document.SeminarDetail.seminarTopic.value          = j.topic;
                   document.SeminarDetail.seminarDescription.value    = j.description;
                   document.SeminarDetail.startDate.value             = j.startDate ;
                   document.SeminarDetail.endDate.value               = j.endDate;
                   document.SeminarDetail.seminarPlace.value          = j.seminarPlace;
                   document.SeminarDetail.seminarEmployeeId.value     = j.employeeId; 
                   document.SeminarDetail.seminarId.value             = j.seminarId; 
                   document.SeminarDetail.participationId.value       = j.participationId;
                   //if(j.fee!=0) 
                     document.SeminarDetail.seminarFee.value            = j.fee;
                   document.SeminarDetail.seminarOrganisedBy.focus();
             },
            onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function getSeminarEmployee(employeeId) {
        url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployeeDetail.php';
        seminarBlankValues();
        
        new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId:  employeeId 
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if(trim(transport.responseText)==0) {
                        //hiddenFloatingDiv('EmployeeLeaveDetailActionDiv');
                        messageBox("<?php echo EMPLOYEE_NOT_EXIST ?>");
                        seminarBlankValues();
                        return false;
                   }
                   j = eval('('+transport.responseText+')'); 
                   document.getElementById("seminarEmployeeId").value = j.employeeId;
                   document.getElementById("seminarEmployeeName").innerHTML = j.employeeName;
                   document.getElementById("seminarEmployeeCode").innerHTML = j.employeeCode;
             },
            onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
} 
// Seminar End           

// Consulting       Start

    //-------------------------------------------------------------
    //THIS FUNCTION IS USED TO POPULATE "Consulting Details" DIV
	//-------------------------------------------------------------
    function showConsultingDetails(id,dv,w,h) {
        //displayWindow('divMessage',600,600);
        displayFloatingDiv(dv,'', w, h, 500, 350)
        populateConsultingValues(id);
    }

    function populateConsultingValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxConsultingGetValues1.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {consultId: id},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if(trim(transport.responseText)==0) {
                hiddenFloatingDiv('divConsultingInfo');
                messageBox("<?php echo COUNSULTING_NOT_EXIST; ?>");
             }
             j = trim(transport.responseText);
             document.getElementById('consultingInfo').innerHTML= j;    
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });   
    }


    function consultingEditWindow(id,dv,w,h) {
        document.getElementById('divHeaderId3').innerHTML='&nbsp; Edit Consulting';
        displayWindow(dv,winLayerWidth,winLayerHeight);
        consultingPopulateValues(id);   
    }

    function consultingPrintReport() {
        
        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
        path='<?php echo UI_HTTP_PATH;?>/consultingPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+'&searchbox='+document.getElementById("searchboxConsulting").value+str;
        //alert(path);
        window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    }

    function consultingPrintReportCSV() {
        
        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
        path='<?php echo UI_HTTP_PATH;?>/consultingPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+'&searchbox='+document.getElementById("searchboxConsulting").value+str;
        //alert(path);
        //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
        window.location=path;
    }

    function validateConsultingAddForm(frm, act) {   
       
     var fieldsArray = new Array(new Array("consultingProjectName","<?php echo ENTER_COUNSULTING_PROJECTNAME ?>"), 
                                    new Array("consultingSponsor","<?php echo ENTER_COUNSULTING_SPONSOR?>"),
                                    new Array("cStartDate","<?php echo ENTER_COUNSULTING_START_DATE ?>"),
                                    new Array("cEndDate","<?php echo ENTER_COUNSULTING_END_DATE ?>"),
                                    new Array("consultingAmountFunding","<?php echo ENTER_COUNSULTING_AMOUNT ?>"),
                                    new Array("consultingRemarks","<?php echo ENTER_COUNSULTING_REMARKS ?>"));
                                    
        var len = fieldsArray.length;
        for(i=0;i<len;i++) {
            if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) && eval("frm."+(fieldsArray[i][0])+".name")!='consultingAmountFunding') {
                //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
                messageBox(fieldsArray[i][1]);
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
        
        if(!dateDifference(eval("frm.cStartDate.value"),eval("frm.cEndDate.value"),'-') ) {
            messageBox ("<?php echo DATE_CONDITION;?>");
            eval("frm.cStartDate.focus();");
            return false;
        }
        
        if(eval("frm.consultingAmountFunding.value")!='' && !isInteger(trim(eval("frm.consultingAmountFunding.value")))) {
            messageBox ("<?php echo INVALID_COUNSULTING_AMOUNT;?>");
            eval("frm.consultingAmountFunding.focus();");
            return false;  
        }
        

        if(document.getElementById('consultId').value=='') {
            addConsulting();
            return false;
        }
        else{
            editConsulting();
            return false;
        }
    }

    function addConsulting() {
            
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitConsultingAdd.php';
             empId = document.ConsultingDetail.consultingEmployeeId.value;
             
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {
                    employeeId:    document.getElementById('employeeId1').value,
                    projectName:   trim(document.ConsultingDetail.consultingProjectName.value),
                    sponsorName:   trim(document.ConsultingDetail.consultingSponsor.value),
                    startDate:     trim(document.ConsultingDetail.cStartDate.value),
                    endDate:       trim(document.ConsultingDetail.cEndDate.value),
                    amountFunding: trim(document.ConsultingDetail.consultingAmountFunding.value),
                    remarks:       trim(document.ConsultingDetail.consultingRemarks.value)
                 },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                         if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                             flag = true;
                             if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                                 consultingBlankValues();
                             }
                             else {
                                 hiddenFloatingDiv('ConsultingActionDiv');
                                 refreshConsultingData(document.getElementById('employeeId1').value);
                                 return false;
                            }
                        }
                        else {
                           messageBox(trim(transport.responseText));  
                        }
                 },
                 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
               });
    }

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO DELETE A DOCUMENT
    //  id=documentId
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function deleteConsulting(id) {
             if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
                 return false;
             }
             else {   
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitConsultingDelete.php';
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {consultId: id},
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                         if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                             refreshConsultingData(document.getElementById('employeeId1').value);
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

    //----------------------------------------------------------------------
    //THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
    //
    //Author : Parveen Sharma
    // Created on : (22.12.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------

    function consultingBlankValues() {
        document.getElementById('divHeaderId3').innerHTML='&nbsp; Add Consulting';
        document.ConsultingDetail.consultingProjectName.value='';
        document.ConsultingDetail.consultingSponsor.value='';
        //document.ConsultingDetail.cStartDate.value='';
       // document.ConsultingDetail.cEndDate.value='';
        document.ConsultingDetail.consultingAmountFunding.value='';
        document.ConsultingDetail.consultingRemarks.value='';
        document.getElementById('consultId').value='';
        document.ConsultingDetail.consultingProjectName.focus();
    }

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO EDIT A DOCUMENT
    //
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function editConsulting() {
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitConsultingEdit.php'; 
             new Ajax.Request(url,
             {
                 method:'post',
                 parameters: { 
                     employeeId:    document.getElementById('employeeId1').value,
                     projectName:   trim(document.ConsultingDetail.consultingProjectName.value),
                     sponsorName:   trim(document.ConsultingDetail.consultingSponsor.value),
                     startDate:     trim(document.ConsultingDetail.cStartDate.value),
                     endDate:       trim(document.ConsultingDetail.cEndDate.value),
                     amountFunding: trim(document.ConsultingDetail.consultingAmountFunding.value),
                     remarks:       trim(document.ConsultingDetail.consultingRemarks.value),
                     consultId:     trim(document.ConsultingDetail.consultId.value)
                  },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                       hiddenFloatingDiv('ConsultingActionDiv');
                       refreshConsultingData(document.getElementById('employeeId1').value);
                       return false;
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
               });
    }
    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
    //
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function consultingPopulateValues(id) {
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxConsultingGetValues.php';
             consultingBlankValues();
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {consultId: id},
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                       hideWaitDialog(true);
                       /*if(trim(transport.responseText)==0) {
                         hiddenFloatingDiv('SeminarActionDiv');
                         messageBox("<?php echo PUBLISHING_NOT_EXIST; ?>");
                         refreshSeminarData(employeeId);
                       } */
                       j = eval('('+trim(transport.responseText)+')');
                       document.getElementById('divHeaderId3').innerHTML='&nbsp; Edit Consulting';     
                       document.getElementById("consultingEmployeeName").innerHTML  = j.employeeName;
                       document.getElementById("consultingEmployeeCode").innerHTML  = j.employeeCode;
                       document.ConsultingDetail.consultingEmployeeId.value = j.employeeId;
                       document.ConsultingDetail.consultingProjectName.value=j.projectName;
                       document.ConsultingDetail.consultingSponsor.value=j.sponsorName;
                       document.ConsultingDetail.cStartDate.value=j.startDate;
                       document.ConsultingDetail.cEndDate.value=j.endDate;
                       document.ConsultingDetail.consultingAmountFunding.value=j.amountFunding;
                       document.ConsultingDetail.consultingRemarks.value=j.remarks;
                       document.ConsultingDetail.consultId.value=j.consultId;
                       document.ConsultingDetail.consultingProjectName.focus();
                 },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
    }

    function getConsultingEmployee(employeeId) {
            url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployeeDetail.php';
            consultingBlankValues();
            
            new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {
                     employeeId:  employeeId 
                 },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                         if(trim(transport.responseText)==0) {
                            //hiddenFloatingDiv('EmployeeLeaveDetailActionDiv');
                            messageBox("<?php echo EMPLOYEE_NOT_EXIST ?>");
                            consultingBlankValues();
                            return false;
                       }
                       j = eval('('+transport.responseText+')'); 
                       document.getElementById("consultingEmployeeId").value = j.employeeId;
                       document.getElementById("consultingEmployeeName").innerHTML = j.employeeName;
                       document.getElementById("consultingEmployeeCode").innerHTML = j.employeeCode;
                 },
                onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
               });
    } 
// Consulting       End


// Workshop       Start


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "Workshop Details" DIV
function showWorkshopDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populateWorkshopValues(id);
}

function populateWorkshopValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxWorkshopGetValues1.php';     
     new Ajax.Request(url,
     {
         method:'post',
         parameters: {workshopId: id},
         onCreate: function() {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==0) {
            hiddenFloatingDiv('divWorkshopInfo');
            messageBox("<?php echo WORKSHOP_NOT_EXIST; ?>");
         }
         j = trim(transport.responseText);
         document.getElementById('workshopInfo').innerHTML= j;    
      },
      onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });   
}


    function workshopEditWindow(id,dv,w,h) {
        document.getElementById('divHeaderId4').innerHTML='&nbsp; Edit Workshop';
        displayWindow(dv,w,h);
        workshopPopulateValues(id);   
    }

    function workshopPrintReport() {
        
        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
        path='<?php echo UI_HTTP_PATH;?>/workshopPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj7.sortOrderBy+'&sortField='+listObj7.sortField+'&searchbox='+document.getElementById("searchboxWorkShop").value+str;
        //alert(path);
        window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    }

    function workshopPrintReportCSV() {
        
        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;    
        path='<?php echo UI_HTTP_PATH;?>/workshopPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj7.sortOrderBy+'&sortField='+listObj7.sortField+'&searchbox='+document.getElementById("searchboxWorkShop").value+str;
        //alert(path);
        //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
        window.location=path;
    }

    function validateWorkshopAddForm(frm, act) {   
       
     var fieldsArray = new Array(new Array("workshopTopic","<?php echo ENTER_WORKSHOP_TOPIC ?>"), 
                                 new Array("workshopStartDate","<?php echo ENTER_WORKSHOP_START_DATE?>"),
                                 new Array("workshopEndDate","<?php echo ENTER_WORKSHOP_END_DATE ?>"),
                                 new Array("workshopSponsored","<?php echo ENTER_WORKSHOP_SPONSORED ?>"),
                                 new Array("workshopLocation","<?php echo ENTER_WORKSHOP_LOCATION ?>"),
                                 new Array("workshopOtherSpeakers","<?php echo ENTER_WORKSHOP_OTHERSPEAKERS ?>"),
                                 new Array("workshopAudience","<?php echo ENTER_WORKSHOP_AUDIENCE ?>"),
                                 new Array("workshopAttendees","<?php echo ENTER_WORKSHOP_ATTENDEES ?>"));
                                    
        var len = fieldsArray.length;
        for(i=0;i<len;i++) {
            if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value"))) {
                //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
                messageBox(fieldsArray[i][1]);
                eval("frm."+(fieldsArray[i][0])+".focus();");
                return false;
                break;
            }
        }
        
        if(eval("frm.workshopSponsored.value")=='Y') {
          if(isEmpty(eval("frm.workshopSponsoredDetail.value"))) {
            messageBox ("<?php echo ENTER_WORKSHOP_SPONSOREDDETAIL;?>");
            eval("frm.workshopSponsoredDetail.focus();");
            return false;
          }
        }
        else {
          document.getElementById('workshopSponsoredDetail').value= '';
        }
        
        if(!dateDifference(eval("frm.workshopStartDate.value"),eval("frm.workshopEndDate.value"),'-') ) {
            messageBox ("<?php echo DATE_CONDITION;?>");
            eval("frm.workshopStartDate.focus();");
            return false;
        }
        
        if(eval("frm.workshopAttendees.value")!='' && !isInteger(trim(eval("frm.workshopAttendees.value")))) {
            messageBox ("<?php echo ACCEPT_WORKSHOP_INTEGER;?>");
            eval("frm.workshopAttendees.focus();");
            return false;  
        }
        
        if(document.getElementById('workshopId').value=='') {
            addWorkshop();
            return false;
        }
        else{
            editWorkshop();
            return false;                    
        }
    }

    function addWorkshop() {
            
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitWorkshopAdd.php';
             empId = document.WorkshopDetail.workshopEmployeeId.value;
             
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {
                     employeeId:    document.getElementById('employeeId1').value,
                     topic           : trim(document.WorkshopDetail.workshopTopic.value),
                     sponsored       : trim(document.WorkshopDetail.workshopSponsored.value),
                     startDate       : trim(document.WorkshopDetail.workshopStartDate.value),
                     endDate         : trim(document.WorkshopDetail.workshopEndDate.value),
                     sponsoredDetail : trim(document.WorkshopDetail.workshopSponsoredDetail.value),
                     location        : trim(document.WorkshopDetail.workshopLocation.value),
                     otherSpeakers   : trim(document.WorkshopDetail.workshopOtherSpeakers.value),
                     audience        : trim(document.WorkshopDetail.workshopAudience.value),
                     attendees       : trim(document.WorkshopDetail.workshopAttendees.value)
                 },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                         if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                             flag = true;
                             if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                                 workshopBlankValues();
                             }
                             else {
                                 hiddenFloatingDiv('WorkShopActionDiv');
                                 refreshWorkshopData(document.getElementById('employeeId1').value);
                                 return false;
                            }
                        }
                        else {
                          messageBox(trim(transport.responseText)); 
                        }
                 },
                 onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
               });
    }

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO DELETE A DOCUMENT
    //  id=documentId
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function deleteWorkshop(id) {
             if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
                 return false;
             }
             else {   
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitWorkshopDelete.php';
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {workshopId: id},
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                         hideWaitDialog(true);
                         if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                             refreshWorkshopData(document.getElementById('employeeId1').value);
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

    //----------------------------------------------------------------------
    //THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
    //
    //Author : Parveen Sharma
    // Created on : (22.12.2008)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //---------------------------------------------------------------------

    function workshopBlankValues() {
        
        document.getElementById('divWorkShopSponsored').style.display= 'none';
        document.WorkshopDetail.reset();        
        
        document.getElementById('divHeaderId4').innerHTML='&nbsp; Add Workshop';
        document.WorkshopDetail.workshopTopic.value='';
        //document.WorkshopDetail.workshopStartDate.value='';
        //document.WorkshopDetail.workshopEndDate.value='';
        document.getElementById("workshopSponsored").selectedIndex=0;
        document.WorkshopDetail.workshopSponsoredDetail.value='';
        document.WorkshopDetail.workshopLocation.value='';
        document.WorkshopDetail.workshopOtherSpeakers.value='';
        document.getElementById('workshopId').value='';
        document.WorkshopDetail.workshopAudience.value='';
        document.WorkshopDetail.workshopAttendees.value='';
        document.WorkshopDetail.workshopTopic.focus();
    }

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO EDIT A DOCUMENT
    //
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function editWorkshop() {
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxInitWorkshopEdit.php'; 
             new Ajax.Request(url,
             {
                 method:'post',
                 parameters: { 
                     employeeId:    document.getElementById('employeeId1').value,
                     topic           : trim(document.WorkshopDetail.workshopTopic.value),
                     sponsored       : trim(document.WorkshopDetail.workshopSponsored.value),
                     startDate       : trim(document.WorkshopDetail.workshopStartDate.value),
                     endDate         : trim(document.WorkshopDetail.workshopEndDate.value),
                     sponsoredDetail : trim(document.WorkshopDetail.workshopSponsoredDetail.value),
                     location        : trim(document.WorkshopDetail.workshopLocation.value),
                     otherSpeakers   : trim(document.WorkshopDetail.workshopOtherSpeakers.value),
                     workshopId      : trim(document.getElementById('workshopId').value),
                     audience        : trim(document.WorkshopDetail.workshopAudience.value),
                     attendees       : trim(document.WorkshopDetail.workshopAttendees.value)
                  },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                       hiddenFloatingDiv('WorkShopActionDiv');
                       refreshWorkshopData(document.getElementById('employeeId1').value); 
                       return false;
                     }
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
                 },
                 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }  
               });
    }
    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
    //
    //Author : Parveen Sharma
    // Created on : (28.02.2009)
    // Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function workshopPopulateValues(id) {
             url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxWorkshopGetValues.php';
             workshopBlankValues();
             new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {workshopId: id},
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                       hideWaitDialog(true);
                       j = eval('('+trim(transport.responseText)+')');
                       document.getElementById('divHeaderId4').innerHTML='&nbsp; Edit Workshop';     
                       document.getElementById("workshopEmployeeName").innerHTML  = j.employeeName;
                       document.getElementById("workshopEmployeeCode").innerHTML  = j.employeeCode;
                       
                       document.WorkshopDetail.workshopEmployeeId.value=j.employeeId;
                       document.WorkshopDetail.workshopTopic.value=j.topic;
                       document.WorkshopDetail.workshopStartDate.value=j.startDate;
                       document.WorkshopDetail.workshopEndDate.value=j.endDate;
                       document.WorkshopDetail.workshopSponsored.value=j.sponsored;
                       if(j.sponsored=='Y') {
                         document.getElementById('divWorkShopSponsored').style.display= ''
                       }
                       else {
                         document.getElementById('divWorkShopSponsored').style.display= 'none'
                       }
                       document.WorkshopDetail.workshopSponsoredDetail.value=j.sponsoredDetail;
                       document.WorkshopDetail.workshopLocation.value=j.location;
                       document.WorkshopDetail.workshopOtherSpeakers.value=j.otherSpeakers;
                       document.getElementById('workshopId').value=j.workshopId;
                       document.WorkshopDetail.workshopAudience.value=j.audience;
                       document.WorkshopDetail.workshopAttendees.value=j.attendees;
                       document.WorkshopDetail.workshopTopic.focus();
                 },
                onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
               });
    }

    function getWorkshopEmployee(employeeId) {
            url = '<?php echo HTTP_LIB_PATH;?>/EmployeeReports/ajaxGetEmployeeDetail.php';
            workshopBlankValues();
            new Ajax.Request(url,
               {
                 method:'post',
                 parameters: {
                     employeeId:  employeeId 
                 },
                 onCreate: function() {
                     showWaitDialog(true);
                 },
                 onSuccess: function(transport){
                       hideWaitDialog(true);
                       if(trim(transport.responseText)==0) {
                            //hiddenFloatingDiv('EmployeeLeaveDetailActionDiv');
                            messageBox("<?php echo EMPLOYEE_NOT_EXIST ?>");
                            workshopBlankValues();
                            return false;
                       }
                       j = eval('('+transport.responseText+')'); 
                       document.getElementById("workshopEmployeeId").value = j.employeeId;
                       document.getElementById("workshopEmployeeName").innerHTML = j.employeeName;
                       document.getElementById("workshopEmployeeCode").innerHTML = j.employeeCode;
                 },
                onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
               });
    } 
// Workshop       End

 function listPage(path){
   window.location=path;
 }
 
 function sendKeys(eleName, e,formname) {
    var ev = e||window.event;
    thisKeyCode = ev.keyCode;
    if (thisKeyCode == '13') {
    {    
       var form = document.forms[formname.name];
       eval('form.'+eleName+'.focus()');
       return false;
    }
 }
}

function getMDPValue(str) {
/*
    document.getElementById("mdp1").innerHTML = 'Sessions Attended';
    document.getElementById("mdp2").innerHTML = 'No.of Hours';
	if(str==1) {
	   document.getElementById("mdp1").innerHTML = 'Sessions Taken';
	   document.getElementById("mdp2").innerHTML = 'No.of Hours';
	}
*/
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Employee/listEmployeeInfoContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: employeeInfo.php $
//
//*****************  Version 43  *****************
//User: Jaineesh     Date: 4/20/10    Time: 5:55p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003312, 0003311, 0003298, 0003299
//
//*****************  Version 42  *****************
//User: Jaineesh     Date: 4/15/10    Time: 12:53p
//Updated in $/LeapCC/Interface
//fixed bug no.0003247
//
//*****************  Version 41  *****************
//User: Jaineesh     Date: 4/13/10    Time: 4:46p
//Updated in $/LeapCC/Interface
//fixed bug no.3245
//
//*****************  Version 40  *****************
//User: Jaineesh     Date: 4/09/10    Time: 2:41p
//Updated in $/LeapCC/Interface
//fixed bug no. 0003244
//
//*****************  Version 39  *****************
//User: Jaineesh     Date: 4/09/10    Time: 11:50a
//Updated in $/LeapCC/Interface
//fixed bug no. 0003246
//
//*****************  Version 38  *****************
//User: Jaineesh     Date: 3/31/10    Time: 7:21p
//Updated in $/LeapCC/Interface
//fixed bug nos. 0003176, 0003164, 0003165, 0003166, 0003167, 0003168,
//0003169, 0003170, 0003171, 0003172, 0003173, 0003175
//
//*****************  Version 37  *****************
//User: Jaineesh     Date: 3/29/10    Time: 5:18p
//Updated in $/LeapCC/Interface
//modification in files according to add new fileds, show exp. &
//qualification
//
//*****************  Version 36  *****************
//User: Jaineesh     Date: 3/29/10    Time: 3:29p
//Updated in $/LeapCC/Interface
//changes for gap analysis in employee master
//
//*****************  Version 35  *****************
//User: Parveen      Date: 11/23/09   Time: 2:39p
//Updated in $/LeapCC/Interface
//alignment format updated topicwise report
//
//*****************  Version 34  *****************
//User: Parveen      Date: 11/23/09   Time: 2:34p
//Updated in $/LeapCC/Interface
//topicswise report sorting order updated
//
//*****************  Version 33  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Interface
//topicswise report format updated (classname added)
//
//*****************  Version 32  *****************
//User: Parveen      Date: 11/04/09   Time: 12:44p
//Updated in $/LeapCC/Interface
//lectureDetails function timeTableLabelId checks updated
//
//*****************  Version 31  *****************
//User: Parveen      Date: 10/23/09   Time: 5:47p
//Updated in $/LeapCC/Interface
//report format update lecture report (groupTypeId base checks added)
//
//*****************  Version 30  *****************
//User: Parveen      Date: 10/23/09   Time: 3:56p
//Updated in $/LeapCC/Interface
//lectureDelivered Report Format updated
//
//*****************  Version 29  *****************
//User: Parveen      Date: 10/08/09   Time: 3:13p
//Updated in $/LeapCC/Interface
//edit seminar fee value show (0)
//
//*****************  Version 28  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Interface
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 27  *****************
//User: Parveen      Date: 9/25/09    Time: 5:25p
//Updated in $/LeapCC/Interface
//alignment & format updated
//
//*****************  Version 26  *****************
//User: Parveen      Date: 9/25/09    Time: 12:39p
//Updated in $/LeapCC/Interface
//blankValues function updated
//
//*****************  Version 25  *****************
//User: Parveen      Date: 9/25/09    Time: 10:24a
//Updated in $/LeapCC/Interface
//employeeId checks updated
//
//*****************  Version 24  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Interface
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Interface
//search & conditions updated
//
//*****************  Version 22  *****************
//User: Gurkeerat    Date: 9/11/09    Time: 6:56p
//Updated in $/LeapCC/Interface
//resolved issue 1520
//
//*****************  Version 21  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Interface
//issue fix 1519, 1518, 1517, 1473, 1442, 1451 
//validiations & formatting updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 9/09/09    Time: 11:35a
//Updated in $/LeapCC/Interface
//populateValues function update (publisher attachment link format
//updated)
//
//*****************  Version 19  *****************
//User: Parveen      Date: 9/04/09    Time: 11:16a
//Updated in $/LeapCC/Interface
//publisher file attchment & publisher save message updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Interface
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 17  *****************
//User: Parveen      Date: 8/31/09    Time: 3:15p
//Updated in $/LeapCC/Interface
//file upload coding updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 8/28/09    Time: 4:50p
//Updated in $/LeapCC/Interface
//1347 issue fix (sendreq sorting value updated)
//
//*****************  Version 15  *****************
//User: Rajeev       Date: 09-08-25   Time: 4:52p
//Updated in $/LeapCC/Interface
//Added Access rights DEFINE
//
//*****************  Version 14  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/LeapCC/Interface
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 13  *****************
//User: Parveen      Date: 8/12/09    Time: 4:55p
//Updated in $/LeapCC/Interface
//default date setting 
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/12/09    Time: 4:36p
//Updated in $/LeapCC/Interface
//bug no. 400, 408, 405, 403 fix
//(formating condition format updated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 8/12/09    Time: 3:25p
//Updated in $/LeapCC/Interface
//alignment & formatting updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Interface
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab 
//
//*****************  Version 9  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Interface
//role permission,alignment, new enhancements added 
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/16/09    Time: 5:15p
//Updated in $/LeapCC/Interface
//new enhancements added (publisher, workshop, seminar, consulting) Div
//base show berif information browse
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Interface
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/07/09    Time: 9:48a
//Updated in $/LeapCC/Interface
//alignment, formatting, conditions updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/26/09    Time: 5:11p
//Updated in $/LeapCC/Interface
//function, condition, formatting updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/24/09    Time: 6:02p
//Updated in $/LeapCC/Interface
//print report and CSV functions (employeeName, employeeCode added)
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/24/09    Time: 3:00p
//Updated in $/LeapCC/Interface
//formatting, conditions, validations updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/17/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//validation, formatting, themes base css templates changes
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/16/09    Time: 2:15p
//Created in $/LeapCC/Interface
//inital checkin
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/10/09    Time: 6:00p
//Updated in $/Leap/Source/Interface
//validation, conditions update 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/10/09    Time: 5:33p
//Updated in $/Leap/Source/Interface
//backInfo button added
//
//*****************  Version 3  *****************
//User: Parveen      Date: 5/20/09    Time: 11:59a
//Updated in $/Leap/Source/Interface
//Course Analytics Report Print & CSV function added
//
//*****************  Version 2  *****************
//User: Parveen      Date: 5/18/09    Time: 3:13p
//Updated in $/Leap/Source/Interface
//formatting settings
//
//*****************  Version 1  *****************
//User: Parveen      Date: 5/18/09    Time: 1:10p
//Created in $/Leap/Source/Interface
//file added
//

?>
