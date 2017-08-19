<?php
//-------------------------------------------------------
// Purpose: To generate student list for subject centric
// functionality
//
// Author : Rajeev Aggarwal
// Created on : (06.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EmployeeInformation');
define('ACCESS','view');
UtilityManager::ifTeacherNotLoggedIn();
require_once(BL_PATH . "/Teacher/TeacherActivity/initData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Employee Info</title>
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
var timeId1=-1;
var timeId2=-1;
var fiId=-1;
var globalFL=1;

var refreshTopicwiseQuery='';
/****************************************************************/
//Overriding tabClick() function of tab.js
//Dipanjan Bhattacharjee
//Date:14.02.2009
/****************************************************************/
var tabNumber=0;  //Determines the current tab index
function tabClick()
    {
        var idArray = this.id.split('_');
        showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
        tabNumber=(idArray[idArray.length-1].replace(/[^0-9]/gi,''));

        //refresshes data for this tab
        refreshEmployeeData("<?php echo $employeeArr[0]['employeeId']; ?>",tabNumber);
    }

//this function is uded to refresh tab data based uplon selection of study periods
function refreshEmployeeData(employeeId,tabIndex){

    if(tabIndex==1 && employeeId!=leId) {
      var topicwiseData=refreshLectureData(employeeId,document.getElementById("labelId").value);
      leId=employeeId;
      return;
    }

    if(tabIndex==2 && employeeId!=teId) {
        if(document.getElementById("timeTableLabelId").value!='') {
          populateClasses(document.getElementById("timeTableLabelId").value);
        }
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
	 if(tabIndex==7 && employeeId!=fiId) {
		//alert('sdffgedfgedfg');
        var mdpData=refreshMdpData(employeeId);
        fiId=employeeId;
        return;
    }
}

// Topics hide
function hideResults() {
 // document.getElementById("resultRowTopic").style.display='none';
  //document.getElementById('nameRowTopic').style.display='none';
  //document.getElementById('nameRow2Topic').style.display='none';
}
//-----------------------------------------------
//THIS FUNCTION IS FOR MDP
//-----------------------------------------------
 function refreshMdpData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitMdpList.php';
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
 listObj8 = new initPage(url,recordsPerPage,linksPerPage,1,'','mdpName','ASC','MdpResultDiv','MdpActionDiv','',true,'listObj8',tableColumns,'editWindow','deleteMdp','&searchbox='+value+'&employeeId='+employeeId);
 sendRequest(url, listObj8, '', true);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "MDP Details" DIV
//-------------------------------------------------------


function showmdpDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populateMdpValues(id);
}

function populateMdpValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxMdpGetValues1.php';
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
    path='<?php echo UI_HTTP_PATH;?>/Teacher/mdpPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj8.sortOrderBy+'&sortField='+listObj8.sortField+'&searchbox='+document.getElementById("searchboxMdp").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function mdpPrintReportCSV() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/mdpPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj8.sortOrderBy+'&sortField='+listObj8.sortField+'&searchbox='+document.getElementById("searchboxMdp").value+str;
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
 // Copyright : syenergy 2010-2011
 //----------------------------------------------
   function addMdp() {
     employeeId="<?php echo $employeeArr[0]['employeeId']; ?>";

     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitMdpAdd.php';

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
 // Copyright 2008-2009  syenergy Technologies Pvt. Ltd.
 //--------------------------------------------------------
function deleteMdp(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitMdpDelete.php';
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
 // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    //document.mdpDetail.mdpName.focus();
}

//-------------------------------------------------------
// THIS FUNCTION IS USED TO EDIT A MDP DOCUMENT
//
// Author : Gagan Gill
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
  function editMdp() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitMdpEdit.php';

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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function mdpPopulateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxMdpGetValues.php';
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
        url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEmployeeDetail.php';
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

//this function seminar
function refreshSeminarData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitSeminarList.php';
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


//this function publishing
function refreshBookJournalsData(employeeId){
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitPublishingList.php';
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

      url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxLectureDetails.php';
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

  refreshTopicwiseQuery='';

  var subjectId=document.getElementById('subject').value;
  var groupId=document.getElementById('group').value;
  var classId=document.getElementById('class').value;

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
  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxTopicwiseDetails.php';
  var tableColumns = new Array(
                        new Array('srNo','#','width="2%" align="left" valign="top"',false),
                        new Array('className','Class','width="15%" align="left" valign="top"',true),
                        new Array('groupName','Group','width="10%" align="left" valign="top"',true),
                        new Array('subjectName','Subject','width="15%" align="left" valign="top"',true),
                        new Array('subjectCode','Subject Code','width="15%" align="left" valign="top"',true),
                        new Array('topicAbbr','Topics Covered','width="22%" align="left" valign="top"',false),
                        new Array('pending','Pending Topics','width="22%" align="left" valign="top"',false)
                     );

 refreshTopicwiseQuery = '&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&subjectId='+subjectId+'&groupId='+groupId+'&classId='+classId;
 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj5 = new initPage(url,recordsPerPage,linksPerPage,1,'','className','ASC','resultsDivTopic','','',true,'listObj5',tableColumns,'','','&employeeId='+employeeId+'&timeTableLabelId='+labelId+'&subjectId='+subjectId+'&groupId='+groupId+'&classId='+classId);
 document.getElementById("resultTopic").style.display='';
 sendRequest(url, listObj5, '',true )
}


//this function Wrokshop Data
function refreshWorkshopData(employeeId) {

  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitWorkshopList.php';
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

  url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitConsultingList.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------


// Course Analytics    Start
function printReport() {

    var subjectId = document.getElementById("subjectId").value;
    var timeTableLabelId = document.getElementById("ttLabelId").value;
    var employeeId = document.getElementById("employeeId1").value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeName = document.getElementById('employeeName1').value;

    path='<?php echo UI_HTTP_PATH;?>/Teacher/courseAnalyticsPrint.php?employeeId='+employeeId+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&subjectId='+subjectId;
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function printReportCSV() {

    var subjectId = document.getElementById("subjectId").value;
    var timeTableLabelId = document.getElementById("ttLabelId").value;
    var employeeId = document.getElementById("employeeId1").value;
    var employeeCode = document.getElementById('employeeCode1').value;
    var employeeName = document.getElementById('employeeName1').value;

    path='<?php echo UI_HTTP_PATH;?>/Teacher/courseAnalyticsCSV.php?employeeId='+employeeId+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&subjectId='+subjectId;
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

    path='<?php echo UI_HTTP_PATH;?>/Teacher/lectureDeliveredPrint.php?employeeId='+employeeId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
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
    path='<?php echo UI_HTTP_PATH;?>/Teacher/lectureDeliveredCSV.php?employeeId='+employeeId+'&sortOrderBy='+listObj4.sortOrderBy+'&sortField='+listObj4.sortField+'&employeeName='+employeeName+'&timeTableLabelId='+timeTableLabelId+'&employeeCode='+employeeCode+'&lectureGroupTypeName='+lectureGroupTypeName+'&lectureGroupType='+lectureGroupType;
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location=path;
}
// Lecture Delivered End

// Course Topicwise End
  function topicwisePrintReport() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value+refreshTopicwiseQuery;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/topicwisePrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj5.sortOrderBy+'&sortField='+listObj5.sortField+str;
    //alert(path);
    window.open(path,"TopicwiseReport","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function topicwisePrintReportCSV() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value+refreshTopicwiseQuery;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/topicwisePrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj5.sortOrderBy+'&sortField='+listObj5.sortField+str;
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
   else {
      messageBox(trim(str));
   }
}


function initAdd(mode) {
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('PublishingDetail').target = 'fileUpload';
        document.getElementById('PublishingDetail').action= "<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/fileUpload.php"
        document.getElementById('PublishingDetail').submit();
    }
   else{
      document.getElementById('PublishingDetail').target = 'fileUpload';
      document.getElementById('PublishingDetail').action= "<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/fileUpload.php"
      document.getElementById('PublishingDetail').submit();
   }
}

// Publisher File Download
function  download(str){
	x = Math.random() * Math.random();
    var address="<?php echo IMG_HTTP_PATH;?>/Teacher/Publishing/"+escape(str)+'?x='+x;
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
    path='<?php echo UI_HTTP_PATH;?>/Teacher/publisherPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&searchbox='+document.getElementById("searchboxPublishing").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function publisherPrintReportCSV() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/publisherPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj3.sortOrderBy+'&sortField='+listObj3.sortField+'&searchbox='+document.getElementById("searchboxPublishing").value+str;
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function addPublishing() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitPublishingAdd.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deletePublishing(id) {
     if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
         return false;
     }
     else {
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitPublishingDelete.php';
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editPublishing() {
         globalFL=0;
         var url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitPublishingEdit.php';
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
function showPublisherDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populatePublisherValues(id);
}

function populatePublisherValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxPublishingGetValues1.php';
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
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxPublishingGetValues.php';
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
                        refreshBookJournalsData(employeeId);
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function getEmployee(employeeId) {
        url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEmployeeDetail.php';
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
function showSeminarDetails(id,dv,w,h) {
    //displayWindow('divMessage',600,600);
    displayFloatingDiv(dv,'', w, h, 500, 350)
    populateSeminarValues(id);
}

function populateSeminarValues(id) {
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSeminarGetValues1.php';
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
    path='<?php echo UI_HTTP_PATH;?>/Teacher/seminarPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField+'&searchbox='+document.getElementById("searchboxSeminar").value+str;
    //alert(path);
    window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
}

function seminarPrintReportCSV() {

    str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
    path='<?php echo UI_HTTP_PATH;?>/Teacher/seminarPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj2.sortOrderBy+'&sortField='+listObj2.sortField+'&searchbox='+document.getElementById("searchboxSeminar").value+str;
    //alert(path);
    //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    window.location = path;
}

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

function addSeminar() {

         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitSeminarAdd.php';
         empId = document.SeminarDetail.seminarEmployeeId.value;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                employeeId:          document.getElementById('employeeId1').value,
                seminarOrganisedBy :  trim(document.SeminarDetail.seminarOrganisedBy.value),
                seminarTopic :        trim(document.SeminarDetail.seminarTopic.value),
                startDate :           trim(document.SeminarDetail.startDate.value),
                endDate :             trim(document.SeminarDetail.endDate.value),
                seminarPlace :        trim(document.SeminarDetail.seminarPlace.value),
                seminarDescription :  trim(document.SeminarDetail.seminarDescription.value),
                participationId   :  trim(document.SeminarDetail.participationId.value),
                fee :  trim(document.SeminarDetail.seminarFee.value)
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
//THIS FUNCTION IS USED TO DELETE A DOCUMENT
//  id=documentId
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function deleteSeminar(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
             return false;
         }
         else {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitSeminarDelete.php';
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
//THIS FUNCTION IS USED TO CLEAN UP THE "TRAINING" DIV
//
//Author : Parveen Sharma
// Created on : (22.12.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//THIS FUNCTION IS USED TO EDIT A DOCUMENT
//
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editSeminar() {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitSeminarEdit.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                    seminarId:           trim(document.SeminarDetail.seminarId.value),
                    employeeId:          document.getElementById('employeeId1').value,
                    seminarOrganisedBy:  trim(document.SeminarDetail.seminarOrganisedBy.value),
                    seminarTopic:        trim(document.SeminarDetail.seminarTopic.value),
                    startDate:           trim(document.SeminarDetail.startDate.value),
                    endDate:             trim(document.SeminarDetail.endDate.value),
                    seminarPlace:        trim(document.SeminarDetail.seminarPlace.value),
                    seminarDescription:  trim(document.SeminarDetail.seminarDescription.value),
                    participationId   :  trim(document.SeminarDetail.participationId.value),
                    fee               :  trim(document.SeminarDetail.seminarFee.value)
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
//THIS FUNCTION IS USED TO POPULATE "EDITDOCUMENT" DIV
//
//Author : Parveen Sharma
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function seminarPopulateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxSeminarGetValues.php';
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
        url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEmployeeDetail.php';
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

    //-------------------------------------------------------
    //THIS FUNCTION IS USED TO POPULATE "Consulting Details" DIV
    function showConsultingDetails(id,dv,w,h) {
        //displayWindow('divMessage',600,600);
        displayFloatingDiv(dv,'', w, h, 500, 350)
        populateConsultingValues(id);
    }

    function populateConsultingValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxConsultingGetValues1.php';
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
        path='<?php echo UI_HTTP_PATH;?>/Teacher/consultingPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+'&searchbox='+document.getElementById("searchboxConsulting").value+str;
        //alert(path);
        window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    }

    function consultingPrintReportCSV() {

        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
        path='<?php echo UI_HTTP_PATH;?>/Teacher/consultingPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj6.sortOrderBy+'&sortField='+listObj6.sortField+'&searchbox='+document.getElementById("searchboxConsulting").value+str;
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

             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitConsultingAdd.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function deleteConsulting(id) {
             if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
                 return false;
             }
             else {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitConsultingDelete.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function editConsulting() {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitConsultingEdit.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function consultingPopulateValues(id) {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxConsultingGetValues.php';
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
            url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEmployeeDetail.php';
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
     url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxWorkshopGetValues1.php';
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
        path='<?php echo UI_HTTP_PATH;?>/Teacher/workshopPrint.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj7.sortOrderBy+'&sortField='+listObj7.sortField+'&searchbox='+document.getElementById("searchboxWorkShop").value+str;
        //alert(path);
        window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");
    }

    function workshopPrintReportCSV() {

        str = '&employeeName='+document.getElementById('employeeName1').value+'&employeeCode='+document.getElementById('employeeCode1').value;
        path='<?php echo UI_HTTP_PATH;?>/Teacher/workshopPrintCSV.php?employeeId='+document.getElementById('employeeId1').value+'&sortOrderBy='+listObj7.sortOrderBy+'&sortField='+listObj7.sortField+'&searchbox='+document.getElementById("searchboxWorkShop").value+str;
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

             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitWorkshopAdd.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function deleteWorkshop(id) {
             if(false===confirm("<?php echo DELETE_CONFIRM; ?>")) {
                 return false;
             }
             else {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitWorkshopDelete.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function editWorkshop() {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxInitWorkshopEdit.php';
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
    // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
    //
    //--------------------------------------------------------
    function workshopPopulateValues(id) {
             url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxWorkshopGetValues.php';
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
            url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetEmployeeDetail.php';
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
 //Function used to populate values in the Subject filter applied on topicwise detail comes out in teacher login
  //Author : Prashant
 // Created on : (19.05.2010)
 // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
                      if(j[c].hasAttendance==1) {
                        var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                        document.getElementById('subject').options.add(objOption);
                      }
                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//Function used to populate values in the class filter applied on topicwise detail comes out in teacher login
  //Author : Prashant
 // Created on : 20.05.2010)
 // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
function populateClasses(timeTableId){
    document.getElementById('subject').options.length=1;
    document.getElementById('group').options.length=1;
	document.getElementById('class').options.length=1;

    var url ='<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGetClasses.php';

    if(timeTableId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 timeTableLabelId: timeTableId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+trim(transport.responseText)+')');
					var len=j.length;
                    for(var c=0;c<len;c++){
                        var objOption = new Option(j[c].className,j[c].classId);
                        document.getElementById('class').options.add(objOption);

                    }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//Function used to populate values in the GROUP filter applied on topicwise detail comes out in teacher login
  //Author : Prashant
 // Created on : (19.05.2010)
 // Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
function groupPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/Teacher/TeacherActivity/ajaxGroupPopulate.php';
   document.getElementById('group').options.length=0;
   var objOption = new Option("Select Group","");
   document.getElementById('group').options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }
   if(document.getElementById('class').value==""){
       return false;
   }


 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: document.getElementById('subject').value,
                 classId  : document.getElementById('class').value
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
                         document.getElementById('group').options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}



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
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Teacher/TeacherActivity/listEmployeeInfoContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: employeeInfo.php $
//
//*****************  Version 32  *****************
//User: Parveen      Date: 11/23/09   Time: 2:39p
//Updated in $/LeapCC/Interface/Teacher
//alignment format updated topicwise report
//
//*****************  Version 31  *****************
//User: Parveen      Date: 11/23/09   Time: 2:34p
//Updated in $/LeapCC/Interface/Teacher
//topicswise report sorting order updated
//
//*****************  Version 30  *****************
//User: Parveen      Date: 11/23/09   Time: 2:13p
//Updated in $/LeapCC/Interface/Teacher
//topicswise report format updated (classname added)
//
//*****************  Version 29  *****************
//User: Parveen      Date: 11/04/09   Time: 12:44p
//Updated in $/LeapCC/Interface/Teacher
//lectureDetails function timeTableLabelId checks updated
//
//*****************  Version 28  *****************
//User: Parveen      Date: 10/23/09   Time: 5:47p
//Updated in $/LeapCC/Interface/Teacher
//report format update lecture report (groupTypeId base checks added)
//
//*****************  Version 27  *****************
//User: Parveen      Date: 10/23/09   Time: 3:56p
//Updated in $/LeapCC/Interface/Teacher
//lectureDelivered Report Format updated
//
//*****************  Version 26  *****************
//User: Parveen      Date: 10/08/09   Time: 3:13p
//Updated in $/LeapCC/Interface/Teacher
//edit seminar fee value show (0)
//
//*****************  Version 25  *****************
//User: Parveen      Date: 10/01/09   Time: 10:50a
//Updated in $/LeapCC/Interface/Teacher
//condition updated hasAttendance, hasMarks & formatting updated
//
//*****************  Version 24  *****************
//User: Parveen      Date: 9/25/09    Time: 5:25p
//Updated in $/LeapCC/Interface/Teacher
//alignment & format updated
//
//*****************  Version 23  *****************
//User: Parveen      Date: 9/25/09    Time: 12:38p
//Updated in $/LeapCC/Interface/Teacher
//blankValues function updated
//
//*****************  Version 22  *****************
//User: Parveen      Date: 9/25/09    Time: 10:24a
//Updated in $/LeapCC/Interface/Teacher
//employeeId check updated
//
//*****************  Version 21  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Interface/Teacher
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 20  *****************
//User: Parveen      Date: 9/16/09    Time: 5:53p
//Updated in $/LeapCC/Interface/Teacher
//search & conditions updated
//
//*****************  Version 19  *****************
//User: Parveen      Date: 9/11/09    Time: 3:55p
//Updated in $/LeapCC/Interface/Teacher
//issue fix 1519, 1518, 1517, 1473, 1442, 1451
//validiations & formatting updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 9/09/09    Time: 11:35a
//Updated in $/LeapCC/Interface/Teacher
//populateValues function update (publisher attachment link format
//updated)
//
//*****************  Version 17  *****************
//User: Parveen      Date: 9/04/09    Time: 11:16a
//Updated in $/LeapCC/Interface/Teacher
//publisher file attchment & publisher save message updated
//
//*****************  Version 16  *****************
//User: Parveen      Date: 9/01/09    Time: 12:56p
//Updated in $/LeapCC/Interface/Teacher
//scopeId checks updated & file format correct (workshopList)
//
//*****************  Version 15  *****************
//User: Parveen      Date: 9/01/09    Time: 11:22a
//Updated in $/LeapCC/Interface/Teacher
//publisher upload file code updated
//
//*****************  Version 14  *****************
//User: Parveen      Date: 8/28/09    Time: 4:50p
//Updated in $/LeapCC/Interface/Teacher
//1347 issue fix (sendreq sorting value updated)
//
//*****************  Version 13  *****************
//User: Parveen      Date: 8/20/09    Time: 7:17p
//Updated in $/LeapCC/Interface/Teacher
//issue fix 13, 15, 10, 4 1129, 1118, 1134, 555, 224, 1177, 1176, 1175
//formating conditions updated
//
//*****************  Version 12  *****************
//User: Parveen      Date: 8/19/09    Time: 6:55p
//Updated in $/LeapCC/Interface/Teacher
//formating & validation updated
//1132, 1130, 54, 1045, 1044, 500, 1042, 1043 issue resolve
//
//*****************  Version 11  *****************
//User: Parveen      Date: 8/12/09    Time: 4:55p
//Updated in $/LeapCC/Interface/Teacher
//default date setting
//
//*****************  Version 10  *****************
//User: Parveen      Date: 8/12/09    Time: 4:36p
//Updated in $/LeapCC/Interface/Teacher
//bug no. 400, 408, 405, 403 fix
//(formating condition format updated)
//
//*****************  Version 9  *****************
//User: Parveen      Date: 8/05/09    Time: 5:59p
//Updated in $/LeapCC/Interface/Teacher
//search condition updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 7/21/09    Time: 12:41p
//Updated in $/LeapCC/Interface/Teacher
//new enhancement added "attachmentAcceptationLetter" in Employee
//Publisher tab
//
//*****************  Version 7  *****************
//User: Parveen      Date: 7/17/09    Time: 5:35p
//Updated in $/LeapCC/Interface/Teacher
//all display tableArrays (Sorting & alignment order formatting updated)
//
//*****************  Version 6  *****************
//User: Parveen      Date: 7/17/09    Time: 2:41p
//Updated in $/LeapCC/Interface/Teacher
//role permission,alignment, new enhancements added
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/15/09    Time: 1:08p
//Updated in $/LeapCC/Interface/Teacher
//file system change, condition, formating & new enhancements added
//(Workshop)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/26/09    Time: 5:19p
//Updated in $/LeapCC/Interface/Teacher
//file right settings
//
//*****************  Version 3  *****************
//User: Parveen      Date: 6/26/09    Time: 5:11p
//Updated in $/LeapCC/Interface/Teacher
//function, condition, formatting updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 6/24/09    Time: 5:52p
//Updated in $/LeapCC/Interface/Teacher
//employeeName, employeeCode Update Print, CSV formats (Seminar,
//Publisher, Consulting, Lecture Details)
//
//*****************  Version 1  *****************
//User: Parveen      Date: 6/24/09    Time: 5:00p
//Created in $/LeapCC/Interface/Teacher
//initial checkin
//


?>
