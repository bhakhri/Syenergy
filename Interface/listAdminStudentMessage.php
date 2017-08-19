<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to students
//
// Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
?>
<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SendMessageToStudents');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Send Message to Students</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js");
echo UtilityManager::includeCSS2();
$queryString =  $_SERVER['QUERY_STRING'];
?>
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<script language="javascript">
var topPos = 0;
var leftPos = 0;
</script>


<script type="text/javascript">
/*window.onload = function () {
    var oTextbox = new AutoSuggestControl(document.getElementById("studentRoll"), new SuggestionProvider(), 'rollNumber');
   document.feeForm.studentRoll.focus();
}   */
</script>
<script language="javascript">
var SMSML=<?php echo SMS_MAX_LENGTH; ?>;
var SMSTD=<?php echo SMS_TEMPLATE_DISPLAY; ?>;
tinyMCE.init({
 gecko_spellcheck:true,
        mode : "textareas",
        theme : "advanced",
		editor_selector : "mceEditor",
		editor_deselector : "mceNoEditor",
        plugins : "paste",
        theme_advanced_buttons3_add : "pastetext,pasteword,selectall",
        paste_auto_cleanup_on_paste : true,
        paste_preprocess : function(pl, o) {
            // Content string containing the HTML from the clipboard
            //alert(o.content);
        },
        paste_postprocess : function(pl, o) {
            // Content DOM node containing the DOM structure of the clipboard
            //alert(o.node.innerHTML);
        },
        setup : function(ed) {
        ed.onKeyUp.add(function(ed, e) {
          smsCalculation("'"+removeHTMLTags(tinyMCE.get('elm1').getContent())+"'",SMSML,'sms_no');
         }
        );
      },

       // Theme options
       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "sub,sup,|,ltr,rtl",
 /* theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|formatselect,fontselect,fontsizeselect",
       //theme_advanced_buttons2 : "search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
       theme_advanced_buttons2 : "bullist,numlist,|,undo,redo,|,forecolor,backcolor",
       theme_advanced_buttons3 : "sub,sup,|,ltr,rtl",
   */ theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
});



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="3%"','',false),
 new Array('students','<input type=\"checkbox\" id=\"studentList\" name=\"studentList\" onclick=\"selectStudents();\">','width="3%"','align=\"center\"',false),
 new Array('studentName','Name','width="20%"','',true),
 new Array('rollNo','R. No','width="15%"','',true) ,
 new Array('degreeAbbr','Degree','width="8%"','',true) ,
 new Array('branchCode','Branch','width="8%"','',true) ,
 new Array('periodName','Study Period','width="8%"','',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminStudentMessageList.php';
//searchFormName = 'searchForm'; // name of the form which will be used for search
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (24.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){
  obj = document.listFrm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;
  }
}


//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all student checkboxes
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectStudents(){

    //state:checked/not checked
    var state=document.getElementById('studentList').checked;
    if(!chkObject('students')){
     document.listFrm.students.checked =state;
     return true;
    }
    formx = document.listFrm;
    var l=formx.students.length;
    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        formx.students[ i ].checked=state;
    }

}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any student checkboxes selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (8.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkStudents(){

    var fl=0;
    if(!chkObject('students')){
     if(document.listFrm.students.checked==true){
         fl=1;
     }
     return fl;
   }
    formx = document.listFrm;
    var l=formx.students.length;

    for(var i=2 ;i < l ; i++){   //started from 2 for two dummy fields.
        if(formx.students[ i ].checked==true){
            fl=1;
            break;
        }
    }

    return (fl);

}

//--------------------------------------------------------
// THIS FUNCTION IS USED FOR HELP BAR
//
//Author : Gagan Gill
//Created on :14 october 2010
//Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
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

//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
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
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
var fll=0;
function getData(){
    fll=0;
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
        fll=1;
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        if(j.totalRecords>0){
        document.getElementById('divButton').style.display='block';
        }
       else{
           document.getElementById('divButton').style.display='none';
       }
    }
   else if((document.getElementById('class').value != "") ){
        fll=1;
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
       if(j.totalRecords>0){
        document.getElementById('divButton').style.display='block';
       }
      else{
           document.getElementById('divButton').style.display='none';
       }

    }
   else{
       messageBox("<?php echo SELECT_STUDENT_SELECT_ALERT; ?>");
       document.getElementById('class').focus();
   }
   if(fll==1){
       //document.getElementById('totalRecordsId').innerHTML='<b>0</b>';
       document.getElementById('sendToAllChk').checked=false;
       //document.getElementById('totalRecordsId').innerHTML='<b>'+j.totalRecords+'</b>';
       allStudentId=j.studentInfo; //all studentIds
   }

}


//This function Validates Form
var myQueryString;
var allStudentId;

function validateAddForm() {


    myQueryString = '';
    form = document.allDetailsForm;

    if(!isEmpty(document.getElementById('birthYearF').value) || !isEmpty(document.getElementById('birthMonthF').value) || !isEmpty(document.getElementById('birthDateF').value)){

        if(isEmpty(document.getElementById('birthYearF').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthF').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateF').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearT').value) || !isEmpty(document.getElementById('birthMonthT').value) || !isEmpty(document.getElementById('birthDateT').value)){

        if(isEmpty(document.getElementById('birthYearT').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_YEAR; ?>");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthT').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_MONTH; ?>");
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateT').value)){

           messageBox("<?php echo SELECT_BIRTH_DAY_DATE; ?>");
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){

        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){

           messageBox("<?php echo RESTRICTION_BIRTH_DAY; ?>");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.getElementById('admissionYearF').value) || !isEmpty(document.getElementById('admissionMonthF').value) || !isEmpty(document.getElementById('admissionDateF').value)){

        if(isEmpty(document.getElementById('admissionYearF').value)){

           messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthF').value)){

           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateF').value)){

           messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearT').value) || !isEmpty(document.getElementById('admissionMonthT').value) || !isEmpty(document.getElementById('admissionDateT').value)){

        if(isEmpty(document.getElementById('admissionYearT').value)){

          messageBox("<?php echo SELECT_ADMISSION_DAY_YEAR ?>");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthT').value)){

           messageBox("<?php echo SELECT_ADMISSION_DAY_MONTH ?>");
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateT').value)){

          messageBox("<?php echo SELECT_ADMISSION_DAY_DATE ?>");
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearF').value) && !isEmpty(document.getElementById('admissionMonthF').value) && !isEmpty(document.getElementById('admissionDateF').value) && !isEmpty(document.getElementById('admissionYearT').value) && !isEmpty(document.getElementById('admissionMonthT').value) && !isEmpty(document.getElementById('admissionDateT').value)){

        doaFValue = document.getElementById('admissionYearF').value+"-"+document.getElementById('admissionMonthF').value+"-"+document.getElementById('admissionDateF').value

        doaTValue = document.getElementById('admissionYearT').value+"-"+document.getElementById('admissionMonthT').value+"-"+document.getElementById('admissionDateT').value

        if(dateCompare(doaFValue,doaTValue)==1){

           messageBox("<?php echo RESTRICTION_ADMISSION_DAY; ?>");
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
    }

    //roll no. + first name + last name
    myQueryString += '&rollNo='+form.rollNo.value;
    myQueryString += '&studentName='+form.studentName.value;


    //degree
    totalDegreeId = form.elements['degreeId[]'].length;
    selectedDegrees='';
    for(i=0;i<totalDegreeId;i++) {
        if (form.elements['degreeId[]'][i].selected == true) {
            if (selectedDegrees != '') {
                selectedDegrees += ',';
            }
            selectedDegrees += form.elements['degreeId[]'][i].value;
        }
    }

    myQueryString += '&degreeId='+selectedDegrees;

    //branch
    totalBranchId = form.elements['branchId[]'].length;
    selectedBranches='';
    for(i=0;i<totalBranchId;i++) {
        if (form.elements['branchId[]'][i].selected == true) {
            if (selectedBranches != '') {
                selectedBranches += ',';
            }
            selectedBranches += form.elements['branchId[]'][i].value;
        }
    }

    myQueryString += '&branchId='+selectedBranches;

    //periodicity
    totalPeriodicityId = form.elements['periodicityId[]'].length;
    selectedPeriodicity='';
    for(i=0;i<totalPeriodicityId;i++) {
        if (form.elements['periodicityId[]'][i].selected == true) {
            if (selectedPeriodicity != '') {
                selectedPeriodicity += ',';
            }
            selectedPeriodicity += form.elements['periodicityId[]'][i].value;
        }
    }

    myQueryString += '&periodicityId='+selectedPeriodicity;


    //course [subject]
    totalSubjectId = form.elements['courseId[]'].length;
    selectedSubjectId='';
    for(i=0;i<totalSubjectId;i++) {
        if (form.elements['courseId[]'][i].selected == true) {
            if (selectedSubjectId != '') {
                selectedSubjectId += ',';
            }
            selectedSubjectId += form.elements['courseId[]'][i].value;
        }
    }

    myQueryString += '&subjectId='+selectedSubjectId;

    //group
    totalGroupId = form.elements['groupId[]'].length;
    selectedGroupId='';
    for(i=0;i<totalGroupId;i++) {
        if (form.elements['groupId[]'][i].selected == true) {
            if (selectedGroupId != '') {
                selectedGroupId += ',';
            }
            selectedGroupId += form.elements['groupId[]'][i].value;
        }
    }

    myQueryString += '&groupId='+selectedGroupId;


    //from date of admission
    fromDateA = form.admissionMonthF.value+'-'+form.admissionDateF.value+'-'+form.admissionYearF.value;
    myQueryString += '&fromDateA='+fromDateA;


    //to date of admission
    toDateA = form.admissionMonthT.value+'-'+form.admissionDateT.value+'-'+form.admissionYearT.value;
    myQueryString += '&toDateA='+toDateA;

    //from date of birth
    fromDateD = form.birthMonthF.value+'-'+form.birthDateF.value+'-'+form.birthYearF.value;
    myQueryString += '&fromDateD='+fromDateD;

    //to date of birth
    toDateD = form.birthMonthT.value+'-'+form.birthDateT.value+'-'+form.birthYearT.value;
    myQueryString += '&toDateD='+toDateD;

    //gender + mgmt. category + quota
    myQueryString += '&gender='+form.gender.value;
    myQueryString += '&categoryId='+form.categoryId.value;
    myQueryString += '&quotaId='+form.quotaId.value;


    //hotsel
    totalHostelId = form.elements['hostelId[]'].length;
    selectedHostel='';
    for(i=0;i<totalHostelId;i++) {
        if (form.elements['hostelId[]'][i].selected == true) {
            if (selectedHostel != '') {
                selectedHostel += ',';
            }
            selectedHostel += form.elements['hostelId[]'][i].value;
        }
    }

    myQueryString += '&hostelId='+selectedHostel;

    //bus stop
    totalBusStopId = form.elements['busStopId[]'].length;
    selectedBusStop='';
    for(i=0;i<totalBusStopId;i++) {
        if (form.elements['busStopId[]'][i].selected == true) {
            if (selectedBusStop != '') {
                selectedBusStop += ',';
            }
            selectedBusStop += form.elements['busStopId[]'][i].value;
        }
    }

    myQueryString += '&busStopId='+selectedBusStop;


    //bus route
    totalBusRouteId = form.elements['busRouteId[]'].length;
    selectedBusRoute='';
    for(i=0;i<totalBusRouteId;i++) {
        if (form.elements['busRouteId[]'][i].selected == true) {
            if (selectedBusRoute != '') {
                selectedBusRoute += ',';
            }
            selectedBusRoute += form.elements['busRouteId[]'][i].value;
        }
    }
    myQueryString += '&busRouteId='+selectedBusRoute;


    //city
    totalCityId = form.elements['cityId[]'].length;
    selectedCity='';
    for(i=0;i<totalCityId;i++) {
        if (form.elements['cityId[]'][i].selected == true) {
            if (selectedCity != '') {
                selectedCity += ',';
            }
            selectedCity += form.elements['cityId[]'][i].value;
        }
    }
    myQueryString += '&cityId='+selectedCity;

    //state
    totalStateId = form.elements['stateId[]'].length;
    selectedState='';
    for(i=0;i<totalStateId;i++) {
        if (form.elements['stateId[]'][i].selected == true) {
            if (selectedState != '') {
                selectedState += ',';
            }
            selectedState += form.elements['stateId[]'][i].value;
        }
    }
    myQueryString += '&stateId='+selectedState;

    //country
    totalCountryId = form.elements['countryId[]'].length;
    selectedCountry='';
    for(i=0;i<totalCountryId;i++) {
        if (form.elements['countryId[]'][i].selected == true) {
            if (selectedCountry != '') {
                selectedCountry += ',';
            }
            selectedCountry += form.elements['countryId[]'][i].value;
        }
    }
    myQueryString += '&countryId='+selectedCountry;

   //univ.
    totalUniversityId = form.elements['universityId[]'].length;
    selectedUniversity='';
    for(i=0;i<totalUniversityId;i++) {
        if (form.elements['universityId[]'][i].selected == true) {
            if (selectedUniversity != '') {
                selectedUniversity += ',';
            }
            selectedUniversity += form.elements['universityId[]'][i].value;
        }
    }
    myQueryString += '&universityId='+selectedUniversity;

	//################################# FOR SHOWING ATTENDANCE FROM / TO ###################################################//
    if(!isEmpty(document.getElementById('attendanceFrom').value) && !isEmpty(document.getElementById('attendanceTo').value)){
        if (parseInt(document.getElementById('attendanceFrom').value) > parseInt(document.getElementById('attendanceTo').value)){

            messageBox("attendance from value cannot be greater than attendance to value");
            document.getElementById('attendanceFrom').focus();
            return false;
        }
    }
    if(!isEmpty(document.getElementById('attendanceFrom').value)){

        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.getElementById('attendanceFrom').value)){

            messageBox("Please enter correct attendance from value");
            document.getElementById('attendanceFrom').focus();
            return false;
        }
        else if (document.getElementById('attendanceFrom').value>100){

            messageBox("attendance from cannot be greater than 100");
            document.getElementById('attendanceFrom').focus();
            return false;
        }
    }
    myQueryString += '&attendanceFrom='+document.getElementById('attendanceFrom').value;
    if(!isEmpty(document.getElementById('attendanceTo').value)){

        reg = new RegExp("^([0-9]*\\.?[0-9]+|[0-9]+\\.?[0-9]*)([eE][+-]?[0-9]+)?$");
        if (!reg.test(document.getElementById('attendanceTo').value)){

            messageBox("Please enter correct attendance to value");
            document.getElementById('attendanceTo').focus();
            return false;
        }
        else if (document.getElementById('attendanceTo').value>100){

            messageBox("attendance to cannot be greater than 100");
            document.getElementById('attendanceTo').focus();
            return false;
        }
    }
    myQueryString += '&attendanceTo='+document.getElementById('attendanceTo').value;

////////////////////////////////////////////////////////////////////////////

    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
    myQueryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    showHide("hideAll");


    //document.getElementById('totalRecordsId').innerHTML='<b>0</b>';
    document.getElementById('sendToAllChk').checked=false;
    sendReq(listURL,divResultName,searchFormName, queryString,false);
    //document.getElementById('totalRecordsId').innerHTML='<b>'+j.totalRecords+'</b>';
    allStudentId=j.studentInfo; //all studentIds

    hide_div('showList',1);
    document.getElementById('divButton').style.display='block';

    return false;
   /*
     if((document.studentDetailsForm.students.length - 2) > 0){
       document.getElementById('divButton').style.display='block';
     }
     else{
       document.getElementById('divButton').style.display='none';
     }
     return false;
   */
}

function hideDetails() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}



//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
//Author : Dipanjan Bhattacharjee
// Created on : (21.07.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d');?>";
function validateForm() {

if((document.listFrm.students.length - 2) == 0){
   messageBox("<?php echo NO_DATA_SUBMIT; ?>");
   return false;
 }

/*
if(document.getElementById('emailCheck').checked && (trim(document.getElementById('msgSubject').value)=="") ){
      messageBox("Subject can not be empty for sending E-mail");
      document.getElementById('msgSubject').focus();
      return false;
 }
*/
if(!(document.getElementById('smsCheck').checked) && trim(document.getElementById('msgSubject').value)=="" ) {
      messageBox("<?php echo EMPTY_SUBJECT; ?>");
      document.getElementById('msgSubject').focus();
      return false;
 }
 else if(document.getElementById('emailCheck').checked  && trim(document.getElementById('msgSubject').value)==""  || (document.getElementById('dashBoardCheck').checked ) &&			trim(document.getElementById('msgSubject').value)=="" ) {
		messageBox("<?php echo EMPTY_SUBJECT; ?>");
		document.getElementById('msgSubject').focus();
		return false;
 }
else if(isEmpty(tinyMCE.get('elm1').getContent()))
    {
        messageBox("<?php echo EMPTY_MSG_BODY; ?>");
        try{
         tinyMCE.execInstanceCommand("elm1", "mceFocus");
        }
        catch(e){}
        return false;
    }
else if(!(document.getElementById('smsCheck').checked) && !(document.getElementById('emailCheck').checked) && !(document.getElementById('dashBoardCheck').checked)  ) {
       alert("<?php echo SELECT_MSG_MEDIUM ; ?>");
       document.getElementById('smsCheck').focus();
       return false;
    }

else if(document.getElementById('dashBoardCheck').checked && !dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){
      messageBox("'Visible To' Date can not be smaller than 'Visible From' Date");
      document.getElementById('startDate').focus();
      return false;
 }
else if(document.getElementById('dashBoardCheck').checked && !dateDifference(serverDate,document.getElementById('endDate').value,"-")){
      messageBox("'Visible To' Date can not be smaller than Current Date");
      document.getElementById('startDate').focus();
      return false;
 }

 //checkes whether any student/parent checkboxes selected or not[if "send to all" is not checked]
else if(!(checkStudents()) && !document.getElementById('sendToAllChk').checked){
     alert("<?php echo STUDENT_SELECT_ALERT; ?>");
     document.getElementById('studentList').focus();
     return false;
  }
else{
    if(!document.getElementById('sendToAllChk').checked){
      //sendMessage(); //sends the message
      initUpload(); //upload the attachment
      sendMessage(); //sends the message
      //return false;
    }
    else{
      sendMessageToAll(); //sends the message to All
      //return false;
    }
  }
}

//Used to upload message attachments
function initUpload() {
    showWaitDialog(true);
    document.getElementById('listFrm').onsubmit=function() {
        document.getElementById('listFrm').target = 'uploadTarget';
    }
    hideWaitDialog();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message  to ALL
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessageToAll() {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminSendStudentMessageAll.php';
         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;
         var student='';
         var l=allStudentId.length;
         for(var i=0;i<l;i++){
             if(student==''){
                 student=allStudentId[ i ].studentId;
             }
             else{
                 student=student+','+allStudentId[ i ].studentId;
             }
         }

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 msgBody: (tinyMCE.get('elm1').getContent()),
                 student: (student),
                 msgMedium: (msgMedium),
                 msgSubject:(trim(document.getElementById('msgSubject').value)),
                 visibleFrom:(document.getElementById('startDate').value),
                 visibleTo:(document.getElementById('endDate').value),
                 nos:(trim(document.getElementById('sms_no').value))
				},
             onCreate: function() {
                 //showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    var ret=trim(transport.responseText).split('!~!~!');
                    var eStr='';
                    var fl=0;
                    if("<?php echo SUCCESS;?>" == ret[0]) {
                       flag = true;
                       if(ret[1]!=''){
                         eStr ='\nSMS not sent to these students :\n'+ret[1];
                         fl=1;
                       }
                       else {
                          ret[1]=-1;
                       }
                       if(ret[2]!=''){
                         eStr +='\nEmail not sent to these students :\n'+ret[2];
                         fl=1;
                       }
                       else {
                          ret[2]=-1;
                       }
                       if(fl==1){
                         if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){
                           window.location = "<?php echo UI_HTTP_PATH ?>/detailsAdminMessageDocument.php?type=s&smsStudentIds="+ret[1]+"&emailStudentIds="+ret[2];
                         }
                       }
                       else {
                         messageBox("<?php echo MSG_SENT_OK; ?>"+eStr);
                       }
                    }
                    else {
                        messageBox(ret[0]);
                    }
                    resetForm(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


function fileUploadError(str){
   hideWaitDialog(true);
   var ret=trim(str).split('!~!~!');
   var eStr='';
   fl = 0;
   if("<?php echo MSG_SENT_OK;?>" == ret[0]) {
     flag = true;
     if(ret[1]!=''){
       eStr ='\nSMS not sent to these students :\n'+ret[1];
       fl = 1;
     }
     else {
        ret[1]=-1;
     }
    if(ret[2]!=''){
       eStr +='\nEmail not sent to these students :\n'+ret[2];
       fl = 1;
    }
    else {
        ret[2]=-1;
    }
    if(fl==1){
       if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){
         window.location = "<?php echo UI_HTTP_PATH ?>/detailsAdminMessageDocument.php?type=s&smsEmployeeIds="+ret[1]+"&emailEmployeeIds="+ret[2];
       }
    }
    else {
        messageBox("<?php echo MSG_SENT_OK; ?>"+eStr);
    }
  }
  else {
      messageBox(ret[0]);
  }
  resetForm();
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Dipanjan Bhattacharjee
// Created on : (21.7.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/ajaxAdminSendStudentMessage.php';


         //determines which student and parents are selected and their studentIds
         formx = document.listFrm;
         var student="";  //get studentIds when student checkboxes are selected

        if((document.listFrm.students.length - 2)<=1){
           student=(document.listFrm.students[2].checked ? document.listFrm.students[2].value : "0" );
         }
        else{
         var m=formx.students.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
            if(formx.students[ k ].checked==true){
                if(student==""){
                    student= formx.students[ k ].value;
                }
               else{
                    student+="," + formx.students[ k ].value;
               }
            }
         }
        }
         //determines message medium
         //var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()),
             student: (student),
             msgMedium: (msgMedium),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value))
				},
             onCreate: function() {
                // showWaitDialog(true);
             },
             onSuccess: function(transport){
						  hideWaitDialog(true);
                    var ret=trim(transport.responseText).split('!~!~!');
                    var eStr='';
                    var fl=0;
                    if("<?php echo SUCCESS;?>" == ret[0]) {
                       flag = true;
                       if(ret[1]!=''){
                         eStr ='\nSMS not sent to these students :\n'+ret[1];
                         fl=1;
                       }
                       else {
                          ret[1]=-1;
                       }
                       if(ret[2]!=''){
                         eStr +='\nEmail not sent to these students :\n'+ret[2];
                         fl=1;
                       }
                       else {
                          ret[2]=-1;
                       }
                       if(fl==1){
                         if(confirm("<?php echo MESSAGE_NOT_SEND; ?>")){
                           window.location = "<?php echo UI_HTTP_PATH ?>/detailsAdminMessageDocument.php?type=s&smsStudentIds="+ret[1]+"&emailStudentIds="+ret[2];
                         }
                       }
                       else {
                         messageBox("<?php echo MSG_SENT_OK; ?>"+eStr);
                       }
                    }
                    else {
                        messageBox(ret[0]);
                    }
                    resetForm(); //it is not called because there is paging
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}

//---------------------------------------------------------------------------------
//purspose:to show date options when msgmedium is dashboard
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function dateDivShow(){
	try {
		  if(document.getElementById('dashBoardCheck').checked){
			  document.getElementById('dateDiv').style.display='block';
			  document.getElementById('uploadFileDiv').style.display='block';
			  if(!document.getElementById('emailCheck').checked){
			   //document.getElementById('msgLogo').value="";
			  }
			  document.getElementById('startDate').focus();
		  }
		 else{
			 document.getElementById('dateDiv').style.display='none';
			 if(!document.getElementById('emailCheck').checked){
				document.getElementById('uploadFileDiv').style.display='none';
			 }
		 }
	}
	catch(e) {
	}
 /*
  if(document.getElementById('dashBoardCheck').checked){
      document.getElementById('dateDiv').style.display='block';
      document.getElementById('startDate').focus();
  }
 else{
     document.getElementById('dateDiv').style.display='none';
 }
 */
}


//---------------------------------------------------------------------------------
//purspose:to show subject options when msgmedium is email
//Author: Dipanjan Bhattacharjee
//Date: 21.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------
function subjectDivShow(){
	try{
  if(document.getElementById('emailCheck').checked){
	  document.getElementById('uploadFileDiv').style.display='block';
      if(!document.getElementById('dashBoardCheck').checked){
       //document.getElementById('msgLogo').value="";
      }
  }
 else{
     if(!document.getElementById('dashBoardCheck').checked){
        document.getElementById('uploadFileDiv').style.display='none';
     }
 }
	}
	catch(e) {
	}
 /*
  if(document.getElementById('emailCheck').checked){
      document.getElementById('subjectDiv').style.display='block';
      document.getElementById('msgSubject').focus();
  }
 else{
     document.getElementById('subjectDiv').style.display='none';
 }
 */
}


//Pupose:Delete rollNo from studentRollNo field upon changing class,subject or group
//Author: Dipanjan Bhattacharjee
//Date : 19.07.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function deleteRollNo(){
    document.getElementById('studentRollNo').value="";
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:Calculates  sms chars and no of smses
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function smsCalculation(value,limit,target){
	var temp1=value;
	
	var nos=1;    //no of sms limit://length of a sms
	if(SMSTD==0){
		tinyMCE.get('elm1').setContent(temp1);
	
	}
	if(tinyMCE.get('elm1').getContent()!=""){
		
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


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
	 hide_div('showList',2);
 //document.getElementById('class').selectedIndex=0;
 //tinyMCE.get('elm1').setContent("");
 //document.getElementById('sms_no').value=1;
  document.getElementById('divButton').style.display='none';
  document.getElementById('results').innerHTML="";
  //document.getElementById('msgLogo').value="";

 //document.getElementById('dashBoardCheck').checked=false;
 //document.getElementById('emailCheck').checked =false;
 //document.getElementById('smsCheck').checked=false;
 //document.getElementById('dateDiv').style.display='none';
 //document.getElementById('subjectDiv').style.display='none';
 //document.getElementById('smsDiv').style.display='none';
 //document.getElementById('msgSubject').value="";
 document.getElementById('msgSubject').focus();
 //document.getElementById('elm1').focus();
}

function getTextBox(val,smsTextMode){
   document.getElementById('spanTextBox').innerHTML = "";
   //alert(val);
   if(val!=0){
	   var ret = val.split("!~~!");
	   s=1;
       
	   str = "<table cellpadding='2px' cellspacing='2px' border='0px' width='12%'>";
       for(i=1;i<=ret[1];i++) {
         cellPadding="style='padding-left:12px'";    
         if(s==1) {
           str =str+"<tr>";
           cellPadding='';    
         }  
         
	     str =str+ "<td width='2%' "+cellPadding+" nowrap class='contenttab_internal_rows'><b>#Val"+i+"#&nbsp;</b></td>";
         str = str+"<td width='2%' nowrap class='contenttab_internal_rows'><b>&nbsp;:&nbsp;</b>&nbsp;</td>";
         str = str+"<td width='2%' nowrap class='contenttab_internal_rows'><input style='width:220px' type='text' name='smsText[]' id='smsText"+i+"' onkeyup='getUpdateSms(\"A\");return false;' class='inputbox'></td>";  
         if(s%2==0) {
	       str =str+"</tr>";
           s=1;
	     }
         else {
           s++; 
         }
	   }
       if(s!=0) {
         str =str+"</tr>";   
       }
	   str =str+"</table>";    
	   document.getElementById('spanTextBox').innerHTML +=str;    
       
	   if(smsTextMode=='A') {
	     tinyMCE.get('elm1').setContent(ret[2]);
         document.getElementById('txtSmsmsg').value = ret[2]; 
         smsCalculation("'"+removeHTMLTags(tinyMCE.get('elm1').getContent())+"'",SMSML,'sms_no');   
	   }
   }
   else {
     document.getElementById('txtSmsmsg').value = "";   
     tinyMCE.get('elm1').setContent("");   
   }
}

function getUpdateSms(smsTextMode) {
   val = document.getElementById('smsTemplate').value; 
   //alert(removeHTMLTags(tinyMCE.get('elm1').getContent()));
   smsText =''; 
   if(smsTextMode=='A') {
     ret = val.split("!~~!");     
     smsText = ret[2];  
     for(i=1;i<=ret[1];i++) { 
       if(trim(eval("document.getElementById('smsText"+i+"').value")) !='') {  
         smsText = smsText.replace("#col"+i+"#",trim(eval("document.getElementById('smsText"+i+"').value"))); 
       }
     }
     smsCalculation("'"+removeHTMLTags(tinyMCE.get('elm1').getContent())+"'",SMSML,'sms_no'); 
     tinyMCE.get('elm1').setContent(smsText);  
     document.getElementById('txtSmsmsg').value = smsText;
   }
}

//---------------------------------------------------------------------------------
//purspose:to show sms div  when msgmedium is sms
//Author: Dipanjan Bhattacharjee
//Date: 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.  
//
//---------------------------------------------------------------------------------
function smsDivShow() {
    
  if(document.getElementById('smsCheck').checked){
      document.getElementById('smsDiv').style.display='block';
      document.getElementById('smsTemplateDiv').style.display='block';
      document.getElementById('nameTinyMCE').style.display='none';
      document.getElementById('nameNotTinyMCE').style.display='';
      document.getElementById('smsTemplate').value="";
      document.getElementById('txtSmsmsg').value="";
      getTextBox(0);
  }
 else{
     document.getElementById('smsDiv').style.display='none';
     document.getElementById('smsTemplateDiv').style.display='none';
     document.getElementById('nameTinyMCE').style.display='';
     document.getElementById('nameNotTinyMCE').style.display='none';
     tinyMCE.get('elm1').setContent("");
     document.getElementById('txtSmsmsg').value=""; 
  }   
}

window.onload=function(){
   document.getElementById('msgSubject').focus();
   if("<?php echo $queryString?>"!=''){
      validateAddForm();
   }
   var roll = document.getElementById("rollNo");
   autoSuggest(roll);
}

</script>
</head>
<body>
	<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/listAdminStudentMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php
// $History: listAdminStudentMessage.php $
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 17/12/09   Time: 17:26
//Updated in $/LeapCC/Interface
//
//*****************  Version 19  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 11:44a
//Updated in $/LeapCC/Interface
//updated funtionality for 'send message to students' icon in footer
//
//*****************  Version 18  *****************
//User: Gurkeerat    Date: 10/28/09   Time: 6:42p
//Updated in $/LeapCC/Interface
//added script for autosuggest
//
//*****************  Version 17  *****************
//User: Dipanjan     Date: 13/10/09   Time: 13:46
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001774,00001775,00001776
//
//*****************  Version 16  *****************
//User: Dipanjan     Date: 3/09/09    Time: 17:47
//Updated in $/LeapCC/Interface
//Corrected problem of tiny mce and word file copy-paste
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 26/08/09   Time: 14:23
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--00001240
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 8/08/09    Time: 11:02a
//Updated in $/LeapCC/Interface
//Gurkeerat: updated access defines
//
//*****************  Version 13  *****************
//User: Rajeev       Date: 7/16/09    Time: 10:12a
//Updated in $/LeapCC/Interface
//Updated Editor with class="mceEditor" in send message modules
//
//*****************  Version 12  *****************
//User: Parveen      Date: 6/05/09    Time: 4:07p
//Updated in $/LeapCC/Interface
//validation message added
//
//*****************  Version 11  *****************
//User: Parveen      Date: 6/05/09    Time: 1:25p
//Updated in $/LeapCC/Interface
//sendMessageToAll function updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 6/04/09    Time: 7:17p
//Updated in $/LeapCC/Interface
//create document list (No messages send Information)
//
//*****************  Version 9  *****************
//User: Administrator Date: 1/06/09    Time: 17:32
//Updated in $/LeapCC/Interface
//Added the functionality : sms & email not sent to how many students
//
//*****************  Version 8  *****************
//User: Administrator Date: 30/05/09   Time: 12:41
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids--1111,1112,1114,1115,1116,1117,1118)
//
//*****************  Version 7  *****************
//User: Parveen      Date: 2/27/09    Time: 11:04a
//Updated in $/LeapCC/Interface
//made code to stop duplicacy
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 23/12/08   Time: 11:47
//Updated in $/LeapCC/Interface
//Added subject and group dropdown in student filter
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 12/15/08   Time: 5:41p
//Updated in $/LeapCC/Interface
//added define('MANAGEMENT_ACCESS',1) Parameter
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 12/12/08   Time: 16:08
//Updated in $/LeapCC/Interface
//Added "Visible From" and "Visible To" fields
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:44p
//Updated in $/LeapCC/Interface
//Added Common Student Filter
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 12/03/08   Time: 5:18p
//Updated in $/LeapCC/Interface
//Create Send Message to All
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/01/08    Time: 6:42p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/21/08    Time: 3:51p
//Updated in $/Leap/Source/Interface
//Added Standard Messages
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/18/08    Time: 11:21a
//Updated in $/Leap/Source/Interface
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 8/11/08    Time: 3:05p
//Created in $/Leap/Source/Interface
?>
