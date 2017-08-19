<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used for group change
//
//
// Author :Ajinder Singh
// Created on : 07-Mar-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdateStudentGroups');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: List Students </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
echo UtilityManager::includeCSS2();
?>
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>

<script type="text/javascript">
tabsShown  = false;
function validateAddForm(frm) {

	form = document.assignGroup;
    var fieldsArray = new Array(new Array("rollNo","<?php echo ENTER_ROLLNO;?>"));

	var len = fieldsArray.length;

	for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) || eval("frm."+(fieldsArray[i][0])+".value")==0) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	showGroupAssignment();
}

function changeClass(str,mode) {
    if(mode==2){
	 studentGroupId = "chkop_"+str;
    }
    else{
     studentGroupId = "chk_"+str;
    }
	tdId = "td_"+str;
	form = document.assignGroup;
	if(eval("form."+studentGroupId+".checked") == true) {
		document.getElementById(tdId).className = 'highlightPermission';
	}
	else {
		document.getElementById(tdId).className = '';
	}
}
function showGroupAssignment() {
	frm = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/showStudentGroupAssignment.php';
	var pars = generateQueryString(frm);
	form = document.assignGroup;

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			fetchedData = trim(transport.responseText);
			if (fetchedData.indexOf('"') == -1) {
				messageBox(fetchedData);
				return false;
			}
			j = eval('('+ transport.responseText + ')');
			tableData = '';
			tableData += "<table border='1' width='100%' rules='all'>";
			tableData += "<tr><td>Theory Groups</td><td>Tutorial Groups</td><td>Practical Groups</td></tr>";
			tableData += "<tr>";
			tableData += "<td valign='top'><table border='0' width='100%' rules='none'>";
			i = 0;
			totalThGroups = j['ThGroups'].length;
			while(i < totalThGroups) {
				if (i%3==0) {
					tableData += "<tr>";
				}
				tableData += "<td id='td_"+j['ThGroups'][i]['groupId']+"'><input onClick = changeClass('"+j['ThGroups'][i]['groupId']+"') type='checkbox' name='chk_"+j['ThGroups'][i]['groupId']+"' value='1'>"+j['ThGroups'][i]['groupShort']+"</td>";
				i++;
				if (i%3==0) {
					tableData += "</tr>";
				}
			}
			if (i%3 != 0) {
				tableData += "</tr>";
			}
			tableData += "</table></td>";

			tableData += "<td valign='top'><table border='0' width='100%' rules='none'>";
			i = 0;
			totalTutGroups = j['TutGroups'].length;
			while(i < totalTutGroups) {
				if (i%3==0) {
					tableData += "<tr>";
				}
				tableData += "<td id='td_"+j['TutGroups'][i]['groupId']+"'><input onClick = changeClass('"+j['TutGroups'][i]['groupId']+"') type='checkbox' name='chk_"+j['TutGroups'][i]['groupId']+"' value='1'>"+j['TutGroups'][i]['groupShort']+"</td>";
				i++;
				if (i%3==0) {
					tableData += "</tr>";
				}
			}
			tableData += "</table></td>";

			tableData += "<td valign='top'><table border='0' width='100%' rules='none'>";
			i = 0;
			totalPrGroups = j['PrGroups'].length;
			while(i < totalPrGroups) {
				if (i%3==0) {
					tableData += "<tr>";
				}
				tableData += "<td id='td_"+j['PrGroups'][i]['groupId']+"'><input onClick = changeClass('"+j['PrGroups'][i]['groupId']+"') type='checkbox' name='chk_"+j['PrGroups'][i]['groupId']+"' value='1'>"+j['PrGroups'][i]['groupShort']+"</td>";
				i++;
				if (i%3==0) {
					tableData += "</tr>";
				}
			}
			tableData += "</table></td>";
			tableData += "</tr></table>";

			document.getElementById("resultRow").style.display='';
			document.getElementById("resultsDiv").innerHTML=tableData;
            if(j['studentOptionalGroupInfo']!=-1){
              document.getElementById("resultRow1").style.display='';
              document.getElementById("nameRow22").style.display='';
              document.getElementById("resultsDiv1").innerHTML =trim(j['studentOptionalGroupInfo']);
            }
            else{
              document.getElementById("resultRow1").style.display='none';
              document.getElementById("nameRow22").style.display='none';
              document.getElementById("resultsDiv1").innerHTML ='';
            }
			document.getElementById("nameRow").style.display='';
			document.getElementById("nameRow2").style.display='';

			i = 0;
			totalStuGroups = j['studentGroups'].length;
			while(i < totalStuGroups) {
				studentGroupId = "chk_"+j['studentGroups'][i]['groupId'];
				tdId = "td_"+j['studentGroups'][i]['groupId'];
				document.getElementById(tdId).className = 'highlightPermission';
				form = document.assignGroup;
				eval("form."+studentGroupId+".checked=true");
				i++;
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}

function showTabs() {
	frm = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/makeGroupTabs.php';
	var pars = generateQueryString(frm)+'&themePath='+globalCurrentThemePath;

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);

			document.getElementById("innerDiv").innerHTML = '';
			res = trim(transport.responseText);
            if(res=="<?php echo INVALID_ROLL_NO?>" || res=="<?php echo THEORY_GROUP_SELECTION_COUNT_DOES_NOT_MATCH?>" || res=="<?php echo TUTORIAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH?>" || res=="<?php echo PRACTICAL_GROUP_SELECTION_COUNT_DOES_NOT_MATCH?>" ){
                messageBox(res);
                return false;
            }

			document.getElementById("innerDiv").innerHTML = res;
			//document.getElementById("innerDiv").innerHTML = table;
			//displayFloatingDiv('showDetailsDiv','',800,600);
            cleanActiveTabIndex();
            jsCodeStartIndex = res.indexOf("<!--");
            jsCodeEndIndex = res.indexOf("-->");
            tabsShown1 = res.substring(jsCodeStartIndex+4,jsCodeEndIndex);
            //alert(tabsShown1);
            eval(tabsShown1);
            tabsShown = true;
            displayFloatingDiv('showDetailsDiv','',800,600);
            document.getElementById('divHeaderId').innerHTML='&nbsp;Update compulsory groups for : '+trim(document.getElementById('rollNo').value);
			/*
            if (tabsShown == true) {
				cleanActiveTabIndex();
				eval(tabsShown1);
			}
			else {
				jsCodeStartIndex = res.indexOf("<!--");
				jsCodeEndIndex = res.indexOf("-->");
				tabsShown1 = res.substring(jsCodeStartIndex+4,jsCodeEndIndex);
				//alert(tabsShown1);
				eval(tabsShown1);
				tabsShown = true;
			}
            */
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


function showTabs2() {
    frm = document.assignGroup.name;
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/makeGroupTabsForOptionalGroups.php';
    var pars = generateQueryString(frm)+'&themePath='+globalCurrentThemePath;

    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
        asynchronous: false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);

            document.getElementById("innerDiv").innerHTML = '';
            res = trim(transport.responseText);
            if(res=="<?php echo INVALID_ROLL_NO?>" || res=="<?php echo NO_OPTIONAL_GROUP_DATA_FOUND?>" || res=="<?php echo INVALID_OPTIONAL_GROUP_COUNT?>"){
                messageBox(res);
                return false;
            }
            document.getElementById("innerDiv").innerHTML = res;
            //document.getElementById("innerDiv").innerHTML = table;
            //displayFloatingDiv('showDetailsDiv','',800,600);
            /*
            if (tabsShown == true) {
                cleanActiveTabIndex();
                eval(tabsShown1);
            }
            else {
                jsCodeStartIndex = res.indexOf("<!--");
                jsCodeEndIndex = res.indexOf("-->");
                tabsShown1 = res.substring(jsCodeStartIndex+4,jsCodeEndIndex);
                //alert(tabsShown1);
                eval(tabsShown1);
                tabsShown = true;
            }
            */
            cleanActiveTabIndex();
            jsCodeStartIndex = res.indexOf("<!--");
            jsCodeEndIndex = res.indexOf("-->");
            tabsShown1 = res.substring(jsCodeStartIndex+4,jsCodeEndIndex);
            //alert(tabsShown1);
            eval(tabsShown1);
            tabsShown = true;
            displayFloatingDiv('showDetailsDiv','',800,600);
            document.getElementById('divHeaderId').innerHTML='&nbsp;Update optional groups for : '+trim(document.getElementById('rollNo').value);
        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

function saveData() {
	frm = document.assignGroup.name;
	var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitUpdateStudentGroup.php';
	var pars = generateQueryString(frm);

	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		asynchronous: false,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			res = trim(transport.responseText);
			messageBox(res);
			if (res == "<?php echo SUCCESS; ?>") {
				hiddenFloatingDiv('showDetailsDiv');
			}
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
}


function saveData2() {
    frm = document.assignGroup.name;
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitUpdateStudentOptionalGroup.php';
    var pars = generateQueryString(frm);

    new Ajax.Request(url,
    {
        method:'post',
        parameters: pars,
        asynchronous: false,
         onCreate: function(){
             showWaitDialog(true);
         },
        onSuccess: function(transport){
            hideWaitDialog(true);
            res = trim(transport.responseText);
            messageBox(res);
            hiddenFloatingDiv('showDetailsDiv');

        },
        onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}

window.onload=function(){
 //document.feeForm.studentRoll.focus();
 var roll = document.getElementById("rollNo");
 autoSuggest(roll);
}
</script>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/updateStudentGroup.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>
<?php
//$History: listUpdateStudentGroup.php $
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 9/21/09    Time: 11:26a
//Updated in $/LeapCC/Interface
//file changed to correct attendance problem.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 8/24/09    Time: 11:30a
//Updated in $/LeapCC/Interface
//fixed bug no.1204
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:41p
//Updated in $/LeapCC/Interface
//changed file name
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 3/07/09    Time: 4:32p
//Created in $/LeapCC/Interface
//file added for group change.
//





?>