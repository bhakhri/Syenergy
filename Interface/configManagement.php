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
define('MODULE','ConfigMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
//echo "<pre>";
//print_r($_SESSION);
require_once(BL_PATH . "/Configs/scInitData.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Config Settings</title>
<script language="javascript">
var filePath = "<?php echo IMG_HTTP_PATH;?>"
</script>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
//require_once(CSS_PATH .'/tab-view.css');
echo UtilityManager::includeCSS("tab-view.css");
echo UtilityManager::includeJS("tab-view.js");
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js");
global $sessionHandler;
$defaultCountryId = $sessionHandler->getSessionVariable('DEFAULT_COUNTRY');
$defaultStateId = $sessionHandler->getSessionVariable('DEFAULT_STATE');
$defaultCityId = $sessionHandler->getSessionVariable('DEFAULT_CITY');
?>
<script language="javascript">
var topPos = 0;
var leftPos = 0;
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
		editor_selector : "mceEditor",
		editor_deselector : "mceNoEditor",
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation("'"+removeHTMLTags(tinyMCE.get('reminderConfig_6').getContent())+"'",SMSML,'sms_no');
         }
        );
      },

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
});
tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
		editor_selector : "mceEditor1",
		editor_deselector : "mceNoEditor1",
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation1("'"+removeHTMLTags(tinyMCE.get('reminderConfig_13').getContent())+"'",SMSML,'sms_no1');
         }
        );
      },

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",

    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
});



//for help in performance

function showHelpDetails(title,msg) {
    if(msg=='') {
      hiddenFloatingDiv('divHelpInfo');
      return false;
    }
	 if(document.getElementById('helpChk').checked == false) {
		 return false;
	 }
    //document.getElementById('divHelpInfo').innerHTML=title;
    document.getElementById('helpInfo').innerHTML= msg;
    displayFloatingDiv('divHelpInfo', title, 300, 150, leftPos, topPos,1);

    leftPos = document.getElementById('divHelpInfo').style.left;
    topPos = document.getElementById('divHelpInfo').style.top;
    return false;
}


function photoUpload(studentSrc,employeeSrc,bussPassSrc,icardSrc,empIcardSrc,empSignSrc,printLogoSrc){

    d = new Date();
    rndNo = d.getTime();
	if(studentSrc!=-1)
		document.getElementById('studentImageId').setAttribute('src',studentSrc+'?'+rndNo);

	if(employeeSrc!=-1)
	   document.getElementById('employeeImageId').setAttribute('src',employeeSrc+'?'+rndNo);

	if(bussPassSrc!=-1)
	   document.getElementById('bussPassImageId').setAttribute('src',bussPassSrc+'?'+rndNo);

	if(icardSrc!=-1)
	   document.getElementById('icardImageId').setAttribute('src',icardSrc+'?'+rndNo);

	if(empIcardSrc!=-1)
	   document.getElementById('employeeiCardImageId').setAttribute('src',empIcardSrc+'?'+rndNo);

	if(empSignSrc!=-1)
	   document.getElementById('employeeiCardSignatureImageId').setAttribute('src',empSignSrc+'?'+rndNo);

	if(printLogoSrc!=-1)
	   document.getElementById('printReportLogoImageId').setAttribute('src',printLogoSrc+'?'+rndNo);


}
function validateAddForm(frm) {


	if(trim(document.getElementById('recordPerPage').value)>100){

		messageBox("<?php echo VALID_RECORD_PER_PAGE?>");
		document.getElementById('recordPerPage').focus();
		return false;
	}

	if(trim(document.getElementById('linkPerPage').value)>25){

		messageBox("<?php echo VALID_LINK_PER_PAGE?>");
		document.getElementById('linkPerPage').focus();
		return false;
	}

	if(trim(document.getElementById('recordPerPageTeacher').value)>100){

		messageBox("<?php echo VALID_RECORD_PER_PAGE_TEACHER?>");
		document.getElementById('recordPerPageTeacher').focus();
		return false;
	}

	if(trim(document.getElementById('recordPerPageAdmin').value)>100){

		messageBox("<?php echo VALID_RECORD_PER_PAGE_ADMIN?>");
		document.getElementById('recordPerPageAdmin').focus();
		return false;
	}

	if(trim(document.getElementById('recordPerPageMessage').value)>100){

		messageBox("<?php echo VALID_RECORD_PER_PAGE_MESSAGE?>");
		document.getElementById('recordPerPageMessage').focus();
		return false;
	}
    
    if(trim(document.getElementById('logoutHomeLink').value)!='') {
       if(!checkDomainWithoutMsg(trim(document.getElementById('logoutHomeLink').value))) { //if not valid website format
         if(!isUrl(trim(document.getElementById('logoutHomeLink').value))) { //if not valid website format
           showTab('dhtmlgoodies_tabView1',0);  
           alert("Please enter a valid redirect page link"); 
           document.getElementById('logoutHomeLink').focus();
           return false;
         }
      }
    }

	var message = document.getElementById('msg5').value;
	var mandatoryString1 = "(studentName)";
	var mandatoryString2 = "(subjectCode)";
	var mandatoryString3 = "(date)";
	var matchPos1 = message.search(mandatoryString1);
	var matchPos2 = message.search(mandatoryString2);
	var matchPos3 = message.search(mandatoryString3);
	if (matchPos1 == -1 || matchPos2 == -1 || matchPos3 == -1) {
		alert("[Please do not edit (studentName), (subjectCode) and (date) text from the message]");
		return false;
	}

	url = '<?php echo HTTP_LIB_PATH;?>/Configs/ajaxInitSave.php';
	var pars = generateQueryString("addConfig");
	new Ajax.Request(url,
		{
			method:'post',
			parameters: pars,
			asynchronous:false,
			onCreate: function(){
			showWaitDialog();
		},
			onSuccess: function(transport) {
				hideWaitDialog();
				messageBox(trim(transport.responseText));
			},
			onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
			});
	initAdd();
			//return false;

}
function initAdd() {

    document.getElementById('addConfig').onsubmit=function() {

        document.getElementById('addConfig').target = 'uploadTargetAdd';
    }

}

//id:id
//type:states/city
//target:taget dropdown box
function autoPopulate(val,type,frm,fieldSta,fieldCty){
   url = '<?php echo HTTP_LIB_PATH;?>/Populate/ajaxAutoPopulate.php';
   var fieldState = document.getElementById(fieldSta);
   var fieldCity = document.getElementById(fieldCty);
   if(frm=="Add"){

       if(type=="states"){

            fieldState.options.length=0;
            var objOption = new Option("Select","");
            fieldState.options.add(objOption);

            var objOption = new Option("Select","");
            fieldCity.options.length=0;
            fieldCity.options.add(objOption);
       }
	   else if(type=="hostel"){

			fieldState.options.length=0;
			var objOption = new Option("Select","");
			fieldState.options.add(objOption);
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
	 showWaitDialog();
 },
	 onSuccess: function(transport){

		 hideWaitDialog();
		 j = eval('('+transport.responseText+')');
		 for(var c=0;c<j.length;c++){
		 	 if(frm=="Add"){
				 if(type=="states"){
					 var objOption = new Option(j[c].stateName,j[c].stateId);
					 fieldState.options.add(objOption);
				 }
				else if(type=="hostel"){
					 var objOption = new Option(j[c].roomName,j[c].hostelRoomId);
					 fieldState.options.add(objOption);
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
window.onload=function(){

	autoPopulate('<?php echo $defaultCountryId?>','states','Add','fatherStates','fatherCity');
	autoPopulate('<?php echo $defaultStateId?>','city','Add','fatherStates','fatherCity');

	document.getElementById('fatherStates').value="<?php echo $defaultStateId?>";
	document.getElementById('fatherCity').value="<?php echo $defaultCityId?>";
}

function showOtherOption(optionValue,selectValue){

	if(selectValue=='student'){

		if(optionValue=='1'){

			document.getElementById('showTypeStudent').style.display='';
			document.getElementById('showTypeStudent1').style.display='';
			document.getElementById('smsDivStudent').style.display='';
			document.getElementById('emailDivStudent').style.display='';

		}
		else{

			document.getElementById('showTypeStudent').style.display='none';
			document.getElementById('showTypeStudent1').style.display='none';
			document.getElementById('smsDivStudent').style.display='none';
			document.getElementById('emailDivStudent').style.display='none';
		}
	}
	if(selectValue=='employee'){

		if(optionValue=='1'){

			document.getElementById('showTypeEmployee').style.display='';
			document.getElementById('showTypeEmployee1').style.display='';
			document.getElementById('smsDivEmployee').style.display='';
			document.getElementById('emailDivEmployee').style.display='';

		}
		else{

			document.getElementById('showTypeEmployee').style.display='none';
			document.getElementById('showTypeEmployee1').style.display='none';
			document.getElementById('smsDivEmployee').style.display='none';
			document.getElementById('emailDivEmployee').style.display='none';
		}
	}
}

function smsDivShow(){

	if(document.getElementById('reminderConfig_3').checked){

		document.getElementById('smsDivStudent').style.display='';
	}
	else{

		document.getElementById('smsDivStudent').style.display='none';
	}
}

function emailDivShow(){

	if(document.getElementById('reminderConfig_4').checked){

		document.getElementById('emailDivStudent').style.display='';
	}
	else{

		document.getElementById('emailDivStudent').style.display='none';
	}
}
function smsDivShow1(){

	if(document.getElementById('reminderConfig_10').checked){

		document.getElementById('smsDivEmployee').style.display='';
	}
	else{

		document.getElementById('smsDivEmployee').style.display='none';
	}
}

function emailDivShow1(){

	if(document.getElementById('reminderConfig_11').checked){

		document.getElementById('emailDivEmployee').style.display='';
	}
	else{

		document.getElementById('emailDivEmployee').style.display='none';
	}
}
function smsCalculation(value,limit,target){

	var temp1=value;
	var nos=1;    //no of sms limit://length of a sms
	if(tinyMCE.get('reminderConfig_6').getContent()!=""){

		document.getElementById('sms_char').value=(parseInt(temp1.length)+1-3);
	}
	else{

		document.getElementById('sms_char').value=0;
	}
	while(temp1.length > (limit+2)){

		temp1=temp1.substr(limit);
		nos=nos+1;
	}
	document.getElementById(target).value=nos;
}
function smsCalculation1(value,limit,target){

	var temp1=value;
	var nos=1;    //no of sms limit://length of a sms
	if(tinyMCE.get('reminderConfig_13').getContent()!=""){

		document.getElementById('sms_char1').value=(parseInt(temp1.length)+1-3);
	}
	else{

		document.getElementById('sms_char1').value=0;
	}
	while(temp1.length > (limit+2)){

		temp1=temp1.substr(limit);
		nos=nos+1;
	}
	document.getElementById(target).value=nos;
}
function disableEnable(){

	if(document.getElementById('config_tt1').value=="1"){

		document.getElementById('config_tt2').disabled=false;
		document.getElementById('config_tt3').disabled=false;
	}
	if(document.getElementById('config_tt1').value=="0"){

		document.getElementById('config_tt2').value=0;
		document.getElementById('config_tt3').value=0;
		document.getElementById('config_tt2').disabled=true;
		document.getElementById('config_tt3').disabled=true;
	}
}


function enableDisable(id){
	if(id=="1"){
		document.getElementById('msg3').disabled=false;
		document.getElementById('msg4').disabled=false;
		document.getElementById('msg5').disabled=true;
	}
	if(id=="0"){
		document.getElementById('msg3').checked=true;
		document.getElementById('msg3').disabled=true;
		document.getElementById('msg4').disabled=true;
		document.getElementById('msg5').disabled=true;
	}
}

function customMessage(id) {
	if(id == "1"){
		document.getElementById('msg5').disabled=true;
	}
	if(id == "0"){
		document.getElementById('msg5').disabled=false;
	}
}

function isUrl(s) {
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}

</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Configs/listConfigContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: configManagement.php $
//
//*****************  Version 10  *****************
//User: Rajeev       Date: 09-12-24   Time: 3:27p
//Updated in $/LeapCC/Interface
//config we can change the print report image if print report image is
//not available.
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 09-11-06   Time: 12:09p
//Updated in $/LeapCC/Interface
//Updated with Paging Parameter in config management
//
//*****************  Version 8  *****************
//User: Rajeev       Date: 09-09-03   Time: 4:18p
//Updated in $/LeapCC/Interface
//Added Time table conflict parameters
//
//*****************  Version 7  *****************
//User: Rajeev       Date: 09-08-18   Time: 12:45p
//Updated in $/LeapCC/Interface
//added  	"It is used to freeze attendance older than X days" Parameter
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 7/14/09    Time: 5:45p
//Updated in $/LeapCC/Interface
//added define variable for Role Permission
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 7/14/09    Time: 10:30a
//Updated in $/LeapCC/Interface
//Updated with bus pass and i card parameters
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 7/13/09    Time: 6:49p
//Updated in $/LeapCC/Interface
//added reminder and other Bus Pass config parameter
?>