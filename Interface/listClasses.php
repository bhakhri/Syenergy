<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Classes Form
//
//
// Author :Ajinder Singh
// Created on : 29-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateClass');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH . "/Classes/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Create Class </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false), new Array('degreeCode','Degree','width="15%"','',true), new Array('batchName','Batch','width="15%"','',true), new Array('branchName','Branch','width="15%"','',true), new Array('degreeDuration','Duration (Yrs.)','width="7%"','align="right"',true), new Array('Active','Active Classes','width="12%"','',true), new Array('studentCount','Student','width="12%"','align="right"',true),new Array('action','Action','width="2%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Classes/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddClasses';
editFormName   = 'EditClasses';
winLayerWidth  = 600; //  add/edit form width
winLayerHeight = 350; // add/edit form height
deleteFunction = 'return deleteClasses';
divResultName  = 'results';
page=1; //default page
sortField = 'degreeCode';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//This function Displays Div Window
function editWindow(id,dv,w,h) {

//	displayWindow(dv,w,h);
	displayFloatingDiv(dv,'',600,350,250,20)
	document.getElementById(dv).style.top='100px';
    populateValues(id);
}
var classDetailsFetched = false; //global variable for checking if the class details button has been clicked or not

var studyPeriods = '';

function validateAddForm(frm, act) {


    //messageBox (act)
    var fieldsArray = new Array(new Array("sessionId","<?php echo SELECT_SESSION;?>"), new Array("batchId","<?php echo SELECT_BATCH;?>"), new Array("universityId","<?php echo SELECT_UNIVERSITY;?>"), new Array("degreeId","<?php echo SELECT_DEGREE;?>"), new Array("branchId","<?php echo SELECT_BRANCH;?>"), new Array("degreeDurationId","<?php echo SELECT_DEGREE_DURATION;?>"), new Array("periodicityId","<?php echo SELECT_PERIODICITY;?>"));
    var len = fieldsArray.length;
	//check if fields are selected
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	//check if the class details (study period details) are fetched or not
	if (classDetailsFetched == false) {
            messageBox ('<?php echo GET_STUDY_PERIODS;?>');
			return false;
	}
	else { //class details have been fetched

		pars = generateQueryString(frm.name);
		studyPeriodsArray = '';
		studyPeriodsArray = studyPeriods.split(',');
		spLength = studyPeriodsArray.length;
		i = 0;
		myString = '';
		while(i < spLength) {
			myEle = eval("document.getElementById('sp_"+studyPeriodsArray[i]+"').value");
			myString += '&sp_'+studyPeriodsArray[i]+ '=' + myEle;
			i++;

		}
		pars += myString;
         url = '<?php echo HTTP_LIB_PATH;?>/Classes/doAllValidations.php';

		 pars += '&task='+act;

		 new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
				asynchronous:false,
		   OnCreate: function(){
			  showWaitDialog(true);
		   },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if(trim(transport.responseText)=="") {
					if(act=='Add') {
					   addClasses();
						return false;
					}
					else if(act=='Edit') {
						editClasses();
						return false;
					}
				 }
				 else {
					 messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
   }
}


function getClassDetails(formAction) {
	if (formAction == "edit") {
		frm = document.editClasses;
	}
	else {
		frm = document.addClasses;
	}
    var fieldsArray = new Array(new Array("degreeDurationId","<?php echo SELECT_DEGREE_DURATION;?>"), new Array("periodicityId","<?php echo SELECT_PERIODICITY;?>"));
    var len = fieldsArray.length;
	//check if fields are selected
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winmessageBox (fieldsArray[i][1],fieldsArray[i][0]);
            messageBox (fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }


	if (formAction == "edit") {
		classId = document.editClasses.classId.value;
		degreeDuration = document.editClasses.degreeDurationId.value;
		periodicity = document.editClasses.periodicityId.value;
		pars = 'degreeDuration='+degreeDuration+'&periodicity='+periodicity;
	}
	else {
		degreeDuration = document.addClasses.degreeDurationId.value;
		periodicity = document.addClasses.periodicityId.value;
		pars = 'degreeDuration='+degreeDuration+'&periodicity='+periodicity;
	}

	 url = '<?php echo HTTP_LIB_PATH;?>/Classes/getClassDetails.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: pars,
		asynchronous:false,
		   OnCreate: function(){
			  showWaitDialog(true);
		   },
		 onSuccess: function(transport){
				hideWaitDialog(true);

				j = eval('('+trim(transport.responseText)+')');
				var len = j.length;
				if (len == 0) {
					messageBox('Enter more Study Periods for this periodicity');
					return false;
				}
				else {
					studyPeriods = '';
					i = 0;
					rowCtr = 0;

					printText = "<table border='0' width='100%' align='center' rules='none' style='border:2px solid #6E6E6E;'>";
					printText += "<tr><td colspan='4'><U><B>Study Periods:</B></U></td></tr>";
					while(i < len) {
						if(rowCtr %4 == 0) {
							printText += "<tr>";
						}

						if (studyPeriods != '') {
							studyPeriods += ',';
						}
						studyPeriods += j[i]['studyPeriodId'];

						printText += "<td width='7%' class='contenttab_internal_rows'>"+j[i]['periodName']+"</td><td style='vertical-align:top;' class='contenttab_internal_rows' width='18%'>";
						printText += "&nbsp;<select size='1' class='selectfield' name='sp_"+j[i]['studyPeriodId']+"' id='sp_"+j[i]['studyPeriodId']+"' style='width:80px;'>";
						printText += "<option value='0'>select</option>";
						printText += "<option value='1'>Active</option>";
						printText += "<option value='2'>Future</option>";
						printText += "<option value='3'>Past</option>";
						printText += "<option value='4'>Unused</option>";
						printText += "</select>";
						printText += "</td>";
						rowCtr++;
						if(rowCtr %4 == 0) {
							printText += "</tr>";
						}
						i++;
					}
					while (i%4 != 0) {
						printText += '<td width="25%" colspan="2" class="contenttab_internal_rows"></td>';
						i++;
					}
					printText += "</table>";
				}

				if(formAction == "edit") {
					document.getElementById("classDetailsDivEdit").style.display = '';
					document.getElementById("classDetailsDivEdit").innerHTML = printText;
					i = 0;
					while(i < len) {
						eval("document.getElementById('sp_"+j[i]['studyPeriodId']+"').value="+j[i]['isActive']);
						i++;
					}
				}
				else {
					document.getElementById("classDetailsDivEdit").innerHTML = '';
					document.getElementById("classDetailsDiv").style.display = '';
					document.getElementById("classDetailsDiv").innerHTML = printText;
				}
				classDetailsFetched = true; //class details have been fetched
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function hideClassDetails(formAction) {
	classDetailsFetched = false; //class details have to be fetched again


	if (formAction == "edit") {
		document.getElementById("classDetailsDivEdit").style.display = "none";
		document.getElementById("classDetailsDivEdit").innerHTML = "";
	}
	else {
		document.getElementById("classDetailsDiv").style.display = "none";
		document.getElementById("classDetailsDiv").innerHTML = "";
	}
}


function addClasses() {

        url = '<?php echo HTTP_LIB_PATH;?>/Classes/ajaxInitAdd.php';
		frmName = document.addClasses.name;
		pars = generateQueryString(frmName);
		studyPeriodsArray = studyPeriods.split(',');
		spLength = studyPeriodsArray.length;
		i = 0;
		myString = '';
		while(i < spLength) {
			myEle = eval("document.getElementById('sp_"+studyPeriodsArray[i]+"').value");
			myString += '&sp_'+studyPeriodsArray[i]+ '=' + myEle;
			i++;
		}
		pars += myString;
		new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
					 asynchronous:false,
		   OnCreate: function(){
			  showWaitDialog(true);
		   },
             onSuccess: function(transport){
                     hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
					 }
					 else {
						 hiddenFloatingDiv('AddClasses');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
					 }
				 }
				 else {
					messageBox(trim(transport.responseText));
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addClasses.sessionId.value = '';
   document.addClasses.batchId.value = '';
   document.addClasses.universityId.value = '';
   document.addClasses.degreeId.value = '';
   document.addClasses.branchId.value = '';
   document.addClasses.degreeDurationId.value = '';
   document.addClasses.periodicityId.value = '';
   document.addClasses.description.value = '';
   document.getElementById("classDetailsDiv").style.display = '';
   document.getElementById("classDetailsDiv").innerHTML = '';

}
function editClasses() {
         url = '<?php echo HTTP_LIB_PATH;?>/Classes/ajaxInitEdit.php';
		frmName = document.editClasses.name;
		pars = generateQueryString(frmName);
		studyPeriodsArray = studyPeriods.split(',');
		spLength = studyPeriodsArray.length;
		i = 0;
		myString = '';
		while(i < spLength) {
			myEle = eval("document.getElementById('sp_"+studyPeriodsArray[i]+"').value");
			myString += '&sp_'+studyPeriodsArray[i]+ '=' + myEle;
			i++;
		}
		pars += myString;
		pars+='&classId='+document.editClasses.classId.value;
         new Ajax.Request(url,
           {
             method:'post',
             parameters: pars,
					 asynchronous:false,
			 OnCreate: function(){
			  showWaitDialog(true);
		   },
             onSuccess: function(transport){
                     hideWaitDialog(true);
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('EditClasses');
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     }
					 else {
						 messageBox(trim(transport.responseText));
					 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function deleteClasses(id) {
         if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
             return false;
         }
         else {

         url = '<?php echo HTTP_LIB_PATH;?>/Classes/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {classId: id},
					 asynchronous:false,
			   OnCreate: function(){
				  showWaitDialog(true);
			   },
             onSuccess: function(transport) {
				 hideWaitDialog(true);
				 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
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

function populateValues(id) {
	 url = '<?php echo HTTP_LIB_PATH;?>/Classes/ajaxGetValues.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 asynchronous:false,
		 parameters: {classId: id},
		   OnCreate: function(){
			  showWaitDialog(true);
		   },
		 onSuccess: function(transport) {
				hideWaitDialog(true);
				//alert(transport.responseText);
				j = eval('('+trim(transport.responseText)+')');

				degreeArray = j.degreeDuration.split(' ');

				document.editClasses.classId.value = id;
				document.editClasses.sessionId.value = j.sessionId;
				document.editClasses.batchId.value = j.batchId;
				document.editClasses.universityId.value = j.universityId;
				document.editClasses.degreeId.value = j.degreeId;
				document.editClasses.branchId.value = j.branchId;
				document.editClasses.degreeDurationId.value = degreeArray[0];
				document.editClasses.periodicityId.value = j.periodicityId;
				document.editClasses.description.value = j.classDescription;


				   classId = document.editClasses.classId.value;
				   pars = 'classId='+classId;
					//this code is redundant and also present in function getClassDetails().
					//this code is put here because, it was not working properly in edit case. and was getting called before needed.
					//please dont remove the code from here.

				 url = '<?php echo HTTP_LIB_PATH;?>/Classes/getClassDetails.php';
				 new Ajax.Request(url,
				   {
					 method:'post',
					 parameters: pars,
					 asynchronous:false,
					   OnCreate: function(){
						  showWaitDialog(true);
					   },
					 onSuccess: function(transport){
							hideWaitDialog(true);

							newj = eval('('+trim(transport.responseText)+')');
							var len = newj.length;
							if (len == 0) {
								messageBox("<?php echo ENTER_MORE_STUDY_PERIODS;?>");
								return false;
							}
							else {
								studyPeriods = '';
								i = 0;
								rowCtr = 0;

								printText = "<table border='0' width='100%' align='center' rules='none' style='border:2px solid #6E6E6E;'>";
								printText += "<tr><td colspan='4'><U><B>Study Periods:</B></U></td></tr>";
								while(i < len) {
									if(rowCtr %4 == 0) {
										printText += "<tr>";
									}

									if (studyPeriods != '') {
										studyPeriods += ',';
									}
									studyPeriods += newj[i]['studyPeriodId'];

									printText += "<td width='7%' class='contenttab_internal_rows'>"+newj[i]['periodName']+"</td><td width='18%'>";
									printText += "&nbsp;<select size='1' class='selectfield' name='sp_"+newj[i]['studyPeriodId']+"' id='sp_"+newj[i]['studyPeriodId']+"' style='width:80px;'>";
									printText += "<option value='0'>select</option>";
									printText += "<option value='1'>Active</option>";
									printText += "<option value='2'>Future</option>";
									printText += "<option value='3'>Past</option>";
									printText += "<option value='4'>Unused</option>";
									printText += "</select>";
									printText += "</td>";
									rowCtr++;
									if(rowCtr %4 == 0) {
										printText += "</tr>";
									}
									i++;
								}
								while (i%4 != 0) {
									printText += '<td width="25%" colspan="2" class="contenttab_internal_rows"></td>';
									i++;
								}
								printText += "</table>";
							}

							document.getElementById("classDetailsDiv").innerHTML = '';
							document.getElementById("classDetailsDivEdit").style.display = '';
							document.getElementById("classDetailsDivEdit").innerHTML = printText;
							classDetailsFetched = true;
							i = 0;
							while(i < len) {
								eval("document.getElementById('sp_"+newj[i]['studyPeriodId']+"').value='"+newj[i]['isActive']+"'");
								i++;
							}
					 },
					 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
				   });
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}

function printReport() {
   path='<?php echo UI_HTTP_PATH;?>/displayClassesReport.php?searchbox='+trim(document.searchForm.searchbox.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"DisplayClassesReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printCSV() {
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayClassesCSV.php?'+qstr;
	window.location = path;
}


</script>

</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Classes/listClassesContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>

</html>
<?php
// $History: listClasses.php $
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/05/09    Time: 1:16p
//Updated in $/LeapCC/Interface
//fixed bug no.0000903
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/03/09    Time: 5:41p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000602, 0000832, 0000831, 0000830
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/03/09    Time: 3:56p
//Updated in $/LeapCC/Interface
//fixed bug no.0000602
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 7/23/09    Time: 3:46p
//Updated in $/LeapCC/Interface
//done the changes to fix following bug no.s:
//1. 642
//2. 625
//3. 601
//4. 573
//5. 572
//6. 570
//7. 569
//8. 301
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 6/25/09    Time: 12:02p
//Updated in $/LeapCC/Interface
//fixed bugs nos.0000299, 000030, 000295
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 12/15/08   Time: 6:07p
//Updated in $/LeapCC/Interface
//added task for checking add/edit
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 8  *****************
//User: Parveen      Date: 11/10/08   Time: 12:10p
//Updated in $/Leap/Source/Interface
//add define access in module
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 8/26/08    Time: 2:29p
//Updated in $/Leap/Source/Interface
//done the common messaging
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 8/25/08    Time: 12:56p
//Updated in $/Leap/Source/Interface
//fixed:
//1. class initial sorting
//2. messageBox coming when editing class
//3. messageBox not coming while redundant data
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 8/14/08    Time: 6:45p
//Updated in $/Leap/Source/Interface
//file modified for fixing bugs reported by pushpender sir
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:55a
//Updated in $/Leap/Source/Interface
//fixed bugs found during self testing
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/09/08    Time: 10:47a
//Updated in $/Leap/Source/Interface
//updated the code of ajax request, and changed messages
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 8/08/08    Time: 6:36p
//Updated in $/Leap/Source/Interface
//done the form formatting
//
//*****************  Version 1  *****************
//User: Admin        Date: 8/05/08    Time: 3:33p
//Created in $/Leap/Source/Interface
//file added for class module
//

?>