<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in RoleWiseReport Form
//
//
// Author :Jaineesh
// Created on : 28.05.09
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','RoleWiseList');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);
UtilityManager::ifNotLoggedIn(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Role Wise User Report </title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(	new Array('srNo','#','width="5%" align=left','align=left',false), 
								new Array('userName','User Name','width=40%  align=left',' align=left',true), new Array('name','Name','width="40%"  align=left',' align=left',true),
								new Array('loginDetail','Login Detail','width="10%"  align=center',' align=center',false)); 

 //This function Validates Form 
var listURL='<?php echo HTTP_LIB_PATH;?>/StudentReports/initRoleWiseUserReport.php';
var divResultName = 'resultsDiv';
var fetchedData;//global variable, used for passing (fetched and sorted) data to popup window


recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

searchFormName = 'roleWiseUserForm'; // name of the form which will be used for search
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
page=1; //default page
sortField = 'userName';
sortOrderBy  = 'ASC';
 //This function Validates Form 


function validateAddForm(frm) {
	form = document.marksNotEnteredForm;
    var fieldsArray = new Array(new Array("roleId","<?php echo SELECT_ROLE;?>"));

			var len = fieldsArray.length;
			for(i=0;i<len;i++) {
				if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
					messageBox(fieldsArray[i][1]);
					eval("frm."+(fieldsArray[i][0])+".focus();");
					return false;
					break;
				}
			}
	document.getElementById("nameRow").style.display='';
	document.getElementById("nameRow2").style.display='';
	document.getElementById("resultRow").style.display='';
	sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);

}

function showUserRoleDetail(id,dv,w,h) {
	//displayWindow('divMessage',600,600);
	document.getElementById("roleView").value=3;
	document.getElementById("userRoleInfo").innerHTML = "";
	displayFloatingDiv(dv,'', w, h, 900, 400)
	document.UserRoleForm.userId.value = id;
	populateUserThreeLoginValues(document.UserRoleForm.userId.value);
}

function selectOption() {
	if(document.UserRoleForm.roleView.value == 3) {
		populateUserThreeLoginValues(document.UserRoleForm.userId.value);
	}
	if (document.UserRoleForm.roleView.value == 6) {
		populateUserThreeLoginValues(document.UserRoleForm.userId.value);
	}
	else if (document.UserRoleForm.roleView.value == 9) {
		populateUserThreeLoginValues(document.UserRoleForm.userId.value);
	}
	else if (document.UserRoleForm.roleView.value == 1) {
		populateUserThreeLoginValues(document.UserRoleForm.userId.value);
	}

}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Jaineesh
// Created on : 16.01.09
// Copyright 2009-2010 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateUserThreeLoginValues(id) {
	
         url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/populateThreeLogin.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {	userId: id,
							roleView: document.UserRoleForm.roleView.value },
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    /*if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divThoughts');
                        messageBox("<?php echo NOT_EXIST; ?>");
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('userRoleInfo').innerHTML= j.dateTimeIn;    */

					if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
						showThreeBarChartResults();
					}
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}

function showThreeBarChartResults() {
	
	//document.getElementById("resultRow").style.display='';
	//form = document.marksNotEnteredForm;
	
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline","1090", "360", "8", "#FFFFFF");
	 so.addVariable("path", "./");
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>140</y><rotate>true</rotate><text>Login Detail ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size><frequency>8</frequency></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/loginDetailBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("userRoleInfo");
	  
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "divTopic" DIV
//
//Author : Jaineesh
// Created on : 16.01.09
// Copyright 2009-2010 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function populateUserRoleValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/populateUserLogin.php';     
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {userId: id},
             onCreate: function() {
                 showWaitDialog(true);
         },
         onSuccess: function(transport){
                     hideWaitDialog(true);
                    /*if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('divThoughts');
                        messageBox("<?php echo NOT_EXIST; ?>");
                   }
                    j = eval('('+trim(transport.responseText)+')');
                    document.getElementById('userRoleInfo').innerHTML= j.dateTimeIn;    */

					if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
						showBarChartResults();
					}
          },
          onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
        });
}
function changeStatus(){
   document.getElementById("nameRow").style.display='none';
   document.getElementById("nameRow2").style.display='none';
   document.getElementById("resultRow").style.display='none';
   document.getElementById('resultsDiv').innerHTML="";
}

function showBarChartResults() {
	
	//document.getElementById("resultRow").style.display='';
	//form = document.marksNotEnteredForm;
	var so = new SWFObject("<?php echo IMG_HTTP_PATH; ?>/amcolumn.swf", "amline","390", "260", "8", "#FFFFFF");
	 so.addVariable("path", "./");  
	  so.addVariable("chart_id", "amcolumn"); // if you have more then one chart in one page, set different chart_id for each chart	
	  x = Math.random() * Math.random();
	  so.addVariable("additional_chart_settings", "<settings><labels><label id='1'><x>1</x><y>140</y><rotate>true</rotate><text>Login Detail ---></text><text_size>10</text_size></label></labels><values><category><enabled></enabled><frequency>1</frequency><rotate>true</rotate><text_size>10</text_size></category></values><plot_area><margins><bottom>60</bottom></margins></plot_area></settings>");
	  so.addParam("wmode", "transparent");
	  so.addVariable("settings_file", encodeURIComponent("../Templates/Xml/barChartSetting.xml"));
	  so.addVariable("data_file", encodeURIComponent("../Templates/Xml/loginDetailBarData.xml?t="+x));
	  so.addVariable("preloader_color", "#999999");
	  so.write("allLoginInfo");
}

function showData(id){

	path='<?php echo UI_HTTP_PATH;?>/userPrint.php?dateSelected='+id;
	window.open(path,"printReport","status=1,menubar=1,scrollbars=1, width=750, height=500, top=100,left=50");
}

function printReport() {
	form = document.roleWiseUserForm;
	path='<?php echo UI_HTTP_PATH;?>/displayRoleWiseUserReport.php?id='+form.roleId.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
	window.open(path,"DisplyRoleWiseUserReport","status=1,menubar=1,scrollbars=1, width=900");
}


</script>

<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js"); 

?> 
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/listRoleWiseUserReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: roleWiseUserReport.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:10p
//Updated in $/LeapCC/Interface
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/05/09   Time: 6:23p
//Updated in $/LeapCC/Interface
//fixed bug nos.0002204, 0002202, 0002201, 0002203, 0002198, 0002197,
//0002185, 0002187, 0002200, 0002199, 0002183, 0002160, 0002156, 0002157,
//0002166, 0002165, 0002164, 0002163, 0002162, 0002161, 0002176, 0002181,
//0002180, 0002179, 0002178, 0002159, 0002158
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/04/09    Time: 7:07p
//Updated in $/LeapCC/Interface
//fixed bug nos.0000854, 0000853,0000860,0000859,0000858,0000857,0000856,
//0000824,0000822,0000811,0000823,0000809 0000810,
//0000808,0000807,0000806,0000805, 1395
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 6/10/09    Time: 3:34p
//Updated in $/LeapCC/Interface
//bugs fixed nos. 1370 to 1380 of Issues [08-June-09].doc
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/28/09    Time: 6:12p
//Updated in $/LeapCC/Interface
//modification in files to run role wise graphs & report in leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 5/28/09    Time: 4:31p
//Created in $/LeapCC/Interface
//copy from sc
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 4/15/09    Time: 3:58p
//Updated in $/Leap/Source/Interface
//modified in showing the graph
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 4/14/09    Time: 6:16p
//Updated in $/Leap/Source/Interface
//modified in feedback label & role wise graph
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 4/10/09    Time: 6:49p
//Updated in $/Leap/Source/Interface
//modified the files to show graphs quartely wise
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 1/22/09    Time: 10:07a
//Created in $/Leap/Source/Interface
//new file to show role wise user report
//

?>
