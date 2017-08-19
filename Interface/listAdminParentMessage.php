<?php
//---------------------------------------------------------------------------
//  THIS FILE used for sending message(sms/email/dashboard) to students
//
// Author : Jaineesh
// Created on : (20.01.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MANAGEMENT_ACCESS',1);
define('MODULE','SendMessageToParents');
define('ACCESS','add');
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
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?> 
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
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left"
    //theme_advanced_statusbar_location : "bottom",
    //theme_advanced_resizing : false
});



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
  new Array('srNo','#','width="3%"','',false),
 new Array('studentName','Student','width="15%"','align="left" style="padding-left:5px"',true) ,
 new Array('rollNo','R. No','width="8%"','',true) ,
 new Array('universityRollNo','Univ. R. No.','width="8%"','',true),
 new Array('fatherName','<input type=\"checkbox\" id=\"fatherList\" name=\"fatherList\" onclick=\"selectFathers();\">Father','width="10%"','align=\"left\"',true), 
 new Array('motherName','<input type=\"checkbox\" id=\"motherList\" name=\"motherList\" onclick=\"selectMothers();\">Mother','width="10%"','align=\"left\"',true), 
 new Array('guardianName','<input type=\"checkbox\" id=\"guardianList\" name=\"guardianList\" onclick=\"selectGuardians();\">Guardian','width="10%"','align=\"left\"',true)
 );

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/scAjaxAdminParentMessageList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
/*
addFormName    = 'AddCity';   
editFormName   = 'EditCity';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteCity';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'studentName';
sortOrderBy = 'ASC';
var topPos = 0;
var leftPos = 0;
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button



//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether a control is object or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function chkObject(id){
  obj = document.allDetailsForm.elements[id];
  if(obj.length > 0) {
      return true;
  }
  else{
    return false;;    
  }
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all fathers checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectFathers(){
    
    //state:checked/not checked
    var state=document.getElementById('fatherList').checked;
    if(!chkObject('fathers')){
     if(!document.allDetailsForm.fathers.disabled){       
     document.allDetailsForm.fathers.checked =state;
     } 
     return true;  
    }
    formx = document.allDetailsForm; 
    
    var l=formx.fathers.length;
    
    for(var i=0 ;i < l ; i++){
      if(!formx.fathers[ i ].disabled){       
        formx.fathers[ i ].checked=state;
      }  
    }
    
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all mothers checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectMothers(){
    
    //state:checked/not checked
    var state=document.getElementById('motherList').checked;
    if(!chkObject('mothers')){
    if(!document.allDetailsForm.mothers.disabled){     
     document.allDetailsForm.mothers.checked =state;
    } 
     return true;  
    }
    formx = document.allDetailsForm; 
    
    var l=formx.mothers.length;
    
    for(var i=0 ;i < l ; i++){
      if(!formx.mothers[ i ].disabled){   
        formx.mothers[ i ].checked=state;
      }  
    }
    
}

//---------------------------------------------------------------------------
//THIS FUNCTION IS USED TO select/deselect all guardians checkboxes
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------
function  selectGuardians(){
    
    //state:checked/not checked
    var state=document.getElementById('guardianList').checked;
    if(!chkObject('guardians')){
     if(!document.allDetailsForm.guardians.disabled){   
      document.allDetailsForm.guardians.checked =state;
     } 
     return true;  
    }
    formx = document.allDetailsForm; 
    
    var l=formx.guardians.length;
    
    for(var i=0 ;i < l ; i++){
      if(!formx.guardians[ i ].disabled){  
        formx.guardians[ i ].checked=state;
      }  
    }
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any father checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkFathers(){
    
    var fl=0; 
    if(!chkObject('fathers')){
     if(document.allDetailsForm.fathers.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.allDetailsForm; 
    var l=formx.fathers.length;
    for(var i=0 ;i < l ; i++){ 
        if(formx.fathers[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any mother checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkMothers(){
    
    var fl=0; 
    if(!chkObject('mothers')){
     if(document.allDetailsForm.mothers.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.allDetailsForm; 
    var l=formx.mothers.length;
    for(var i=0 ;i < l ; i++){ 
        if(formx.mothers[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}


//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any guardian checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkGuardians(){
    
    var fl=0; 
    if(!chkObject('guardians')){
     if(document.allDetailsForm.guardians.checked==true){
         fl=1;
     }
     return fl;
   }

    formx = document.allDetailsForm; 
    var l=formx.guardians.length;
    for(var i=0 ;i < l ; i++){ 
        if(formx.guardians[ i ].checked==true){
            fl=1;
            break;
        }
    }
    
    return (fl);
    
}

//-----------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether any father/mother/guardian checkboxes selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------
function checkParents(){
  if(checkFathers() || checkMothers() || checkGuardians()){
      return 1;
  }
  else{
      return 0;
  }   
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO hide "showList" div
//
//Author : Jaineesh
// Created on : (20.01.2009)
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



//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------

function getData(){
    if(trim(document.getElementById('studentRollNo').value)!="")
    {
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        if((document.allDetailsForm.students.length - 2) > 0){
         document.getElementById('divButton').style.display='block';
        }
       else{
           document.getElementById('divButton').style.display='none';
       }   
    }
   else if((document.getElementById('class').value != "") ){
        sendReq(listURL,divResultName,searchFormName,'',false);
        hide_div('showList',1);
        if((document.allDetailsForm.students.length - 2) > 0){
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
    if((document.allDetailsForm.fathers.length - 2) > 0){
       document.getElementById('divButton').style.display='block';
     }
    else{
           document.getElementById('divButton').style.display='none';
     }
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
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
var serverDate="<?php echo date('Y-m-d');?>";

function validateForm() {

if((document.allDetailsForm.fathers.length - 2) == 0){
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
if(trim(document.getElementById('msgSubject').value)==""){  
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
else if(!(checkParents())){  //checkes whether any parent checkboxes selected or not
     alert("<?php echo SELECT_PARENT_MSG; ?>");
     document.getElementById('fatherList').focus();
     return false;
  } 
else{
     //sendMessage(); //sends the message to All
     initUpload(); //upload the attachment
     sendMessage(); //sends the message
     //return false; 
  }  
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
  
//Used to upload message attachments
function initUpload() {
    showWaitDialog(true);
    document.getElementById('allDetailsForm').onsubmit=function() {
      document.getElementById('allDetailsForm').target = 'uploadTarget';
    }
    hideWaitDialog(true);
}
  

//-------------------------------------------------------
//THIS FUNCTION IS USED TO send message
//
//Author : Jaineesh
// Created on : (20.01.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function sendMessage() {
         url = '<?php echo HTTP_LIB_PATH;?>/AdminMessage/scAjaxAdminSendParentMessage.php';
         
         
         //determines which student and parents are selected and their studentIds
         formx = document.allDetailsForm; 
         var father="";  //get studentIds when student checkboxes are selected
         var mother="";  //get studentIds when student checkboxes are selected
         var guardian="";  //get studentIds when student checkboxes are selected

         if((document.allDetailsForm.fathers.length - 2)<=1){
           father=(document.allDetailsForm.fathers[2].checked ? document.allDetailsForm.fathers[2].value : "0" );   
           mother=(document.allDetailsForm.mothers.checked ? document.allDetailsForm.mothers.value : "0" );   
           guardian=(document.allDetailsForm.guardians.checked ? document.allDetailsForm.guardians.value : "0" );   
         }
        else{
         var m=formx.fathers.length;
         for(var k=2 ; k < m ; k++){ //started from 2 for two dummy fields.
            if(formx.fathers[ k ].checked==true){
                if(father==""){
                    father= formx.fathers[ k ].value;
                }
               else{
                    father+="," + formx.fathers[ k ].value; 
               } 
            }
         }
         
         for(k=0 ; k < m-2 ; k++){ //started from 2 for two dummy fields.
            if(formx.mothers[ k ].checked==true){
                if(mother==""){
                    mother= formx.mothers[ k ].value;
                }
               else{
                    mother+="," + formx.mothers[ k ].value; 
               } 
            }
         }
         
         for(k=0 ; k < m-2 ; k++){ //started from 2 for two dummy fields.
            if(formx.guardians[ k ].checked==true){
                if(guardian==""){
                    guardian= formx.guardians[ k ].value;
                }
               else{
                    guardian+="," + formx.guardians[ k ].value; 
               } 
            }
         }
         
        }  //determines message medium
        
        //alert("F="+father);
        //alert("M="+mother);
        //alert("G="+guardian);

         var msgMedium=((document.getElementById('smsCheck').checked) ? document.getElementById('smsCheck').value: 0)+","+((document.getElementById('emailCheck').checked) ? document.getElementById('emailCheck').value: 0)+","+((document.getElementById('dashBoardCheck').checked) ? document.getElementById('dashBoardCheck').value: 0) ;

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {msgBody: (tinyMCE.get('elm1').getContent()), 
             father: (father),
             mother: (mother),
             guardian: (guardian),
             msgMedium: (msgMedium),
             msgSubject:(trim(document.getElementById('msgSubject').value)),
             visibleFrom:(document.getElementById('startDate').value),
             visibleTo:(document.getElementById('endDate').value),
             nos:(trim(document.getElementById('sms_no').value)),
             hiddenFile:document.getElementById('msgLogo').value     
             },
             onCreate: function() {
                //showWaitDialog(true);    
             },
            /* onSuccess: function(transport){
                    // hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                    flag = true;
                    //messageBox("<?php echo MSG_SENT_OK; ?>");    
                 } 
                 else {
                   //messageBox(trim(transport.responseText)); 
                 }    
             },*/
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.  
//
//---------------------------------------------------------------------------------
function dateDivShow(){
    
      if(document.getElementById('dashBoardCheck').checked){
          document.getElementById('dateDiv').style.display='block';
          document.getElementById('uploadFileDiv').style.display='block';
          if(!document.getElementById('emailCheck').checked){  
           document.getElementById('msgLogo').value="";
          }
          document.getElementById('startDate').focus();
      }
     else{
         document.getElementById('dateDiv').style.display='none';
         if(!document.getElementById('emailCheck').checked){ 
            document.getElementById('uploadFileDiv').style.display='none'; 
         }
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
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.  
//
//---------------------------------------------------------------------------------
function subjectDivShow(){
      if(document.getElementById('emailCheck').checked){
          document.getElementById('uploadFileDiv').style.display='block';
          if(!document.getElementById('dashBoardCheck').checked){  
           document.getElementById('msgLogo').value="";
          }
      }
     else{
         if(!document.getElementById('dashBoardCheck').checked){ 
            document.getElementById('uploadFileDiv').style.display='none'; 
         }
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



//----------------------------------------------------------------------------------------------------------------
//Pupose:Calculates  sms chars and no of smses
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
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
//for help module
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

//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Jaineesh
//Date : 20.01.2009
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
     document.getElementById('dashBoardCheck').checked=false;
     document.getElementById('emailCheck').checked =false;
     document.getElementById('smsCheck').checked=false;
  //   document.getElementById('msgSubject').value='';
//tinyMCE.set('elm1').value="";
     //document.getElementById('elm1').innerHTML='';
     //document.allDetailsForm.textarea.value='';
     document.getElementById('divButton').style.display='none';
     document.getElementById('results').innerHTML="";
     document.getElementById('msgLogo').value="";    
     hide_div('showList',2); 
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
 var roll = document.getElementById("rollNo");
 autoSuggest(roll); 
}

</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminMessage/scListAdminParentMessageContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAdminParentMessage.php $ 
//
//*****************  Version 10  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 10:50a
//Updated in $/LeapCC/Interface
//added script for auto suggest
//
//*****************  Version 9  *****************
//User: Dipanjan     Date: 13/10/09   Time: 13:46
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001774,00001775,00001776
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 3/09/09    Time: 17:47
//Updated in $/LeapCC/Interface
//Corrected problem of tiny mce and word file copy-paste
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 26/08/09   Time: 14:23
//Updated in $/LeapCC/Interface
//Done bug fixing.
//bug ids--00001240
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 24/08/09   Time: 11:14
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids---
//00001201,00001207,00001208,00001216
//
//*****************  Version 5  *****************
//User: Parveen      Date: 6/05/09    Time: 4:07p
//Updated in $/LeapCC/Interface
//validation message added 
//
//*****************  Version 4  *****************
//User: Parveen      Date: 6/04/09    Time: 7:17p
//Updated in $/LeapCC/Interface
//create document list (No messages send Information)
//
//*****************  Version 3  *****************
//User: Administrator Date: 30/05/09   Time: 12:40
//Updated in $/LeapCC/Interface
//Done bug fixing.
//Bug ids--1111,1112,1114,1115,1116,1117,1118)
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 5/26/09    Time: 5:44p
//Updated in $/LeapCC/Interface
//Updated with Management access so that it can be accessed from
//management login
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 26/05/09   Time: 13:28
//Created in $/LeapCC/Interface
//Created module  "Send Message for Parents"
?>
