<?php
//---------------------------------------------------------------------------
// THIS FILE used for student concession
// Author : Dipanjan Bhattacharjee
// Created on : (07.05.2010)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//----------------------------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','StudentConcession');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Concession</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(
 new Array('srNo','#','width="1%"','',false),
 new Array('studentName','Name','width="12%"','',true),
 new Array('rollNo','Roll No.','width="7%"','',true),
 new Array('universityRollNo','Univ.RollNo.','width="10%"','',true),

 new Array('totalFees','T.Fees','width="4%"','align="right"',false),
 new Array('totalFees1','Head Value','width="6%"','align="right" nowrap',false),
 new Array('totalConce','Concession','width="14%"','',true),
 new Array('concessionType','Type','width="10%"','',false),
 new Array('concessionValue','Value','width="5%"','',false),
 new Array('discValue','Discount','width="6%"','',false),
 new Array('reason','Reason','width="10%"','',false) ,
 new Array('printAction','Action','width="5%"','',false)
);

recordsPerPage = <?php echo RECORDS_PER_PAGE_ADMIN_MESSAGE ;?>;

linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/StudentConcession/ajaxStudentList.php';
//searchFormName = 'searchForm'; // name of the form which will be used for search
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

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

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




//This function Validates Form 
var myQueryString;
var allStudentId;
var queryString2='';
function validateAddForm(callingMode) {
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
    countDegree=0;
    for(i=0;i<totalDegreeId;i++) {
        if (form.elements['degreeId[]'][i].selected == true) {
            if (selectedDegrees != '') {
                selectedDegrees += ',';
                selectedDegreeText +=',';
            }
            countDegree++;
            selectedDegrees += form.elements['degreeId[]'][i].value;
            selectedDegreeText +=form.elements['degreeId[]'].options[form.elements['degreeId[]'].selectedIndex].text;
        }
    }
    if(countDegree==totalDegreeId || countDegree==0){
        selectedDegreeText ="ALL";
    }
    queryString2 ='&degsText='+selectedDegreeText

    myQueryString += '&degreeId='+selectedDegrees;

    //branch
    totalBranchId = form.elements['branchId[]'].length;
    selectedBranches='';
    countBranches=0;
    for(i=0;i<totalBranchId;i++) {
        if (form.elements['branchId[]'][i].selected == true) {
            if (selectedBranches != '') {
                selectedBranches += ',';
                selectedBranchesText += ',';
            }
            countBranches++;
            selectedBranches += form.elements['branchId[]'][i].value;
            selectedBranchesText +=form.elements['branchId[]'].options[form.elements['branchId[]'].selectedIndex].text;
        }
    }
    if(countBranches==totalBranchId || countBranches==0){
        selectedBranchesText ="ALL";
    }

    queryString2 += '&bransText='+selectedBranchesText;
    
    myQueryString += '&branchId='+selectedBranches;

    //periodicity
    totalPeriodicityId = form.elements['periodicityId[]'].length;
    selectedPeriodicity='';
    selectedPeriodicityText='';
    countPeriod=0;
    for(i=0;i<totalPeriodicityId;i++) {
        if (form.elements['periodicityId[]'][i].selected == true) {
            if (selectedPeriodicity != '') {
                selectedPeriodicity += ',';
                selectedPeriodicityText += ', ';
            }
            countPeriod++;
            selectedPeriodicity += form.elements['periodicityId[]'][i].value;
            selectedPeriodicityText +=form.elements['periodicityId[]'].options[form.elements['periodicityId[]'].selectedIndex].text;
        }
    }
    if(countPeriod==totalPeriodicityId || countPeriod==0){
        selectedPeriodicityText ="ALL";
    }

    queryString2 += '&periodsText='+selectedPeriodicityText;
    myQueryString += '&periodicityId='+selectedPeriodicity;
    
    
    //course [subject]
    totalSubjectId = form.elements['courseId[]'].length;
    selectedSubjectId='';
    selectedCourseText='';
    countPeriod=0;
    for(i=0;i<totalSubjectId;i++) {
        if (form.elements['courseId[]'][i].selected == true) {
            if (selectedSubjectId != '') {
                selectedSubjectId += ',';
                selectedCourseText += ', ';
            }
            countPeriod++;
            selectedSubjectId += form.elements['courseId[]'][i].value;
            selectedCourseText +=form.elements['courseId[]'].options[form.elements['courseId[]'].selectedIndex].text;
        }
    }
    if(countPeriod==totalSubjectId || countPeriod==0)
        selectedCourseText ="ALL";

    queryString2 += '&courseText='+selectedCourseText;
    myQueryString += '&subjectId='+selectedSubjectId;

    //group
    totalGroupId = form.elements['groupId[]'].length;
    selectedGroupId='';
    selectedGroupText='';
    countSection=0;
    for(i=0;i<totalGroupId;i++) {
        if (form.elements['groupId[]'][i].selected == true) {
            if (selectedGroupId != '') {
                selectedGroupId += ',';
                selectedGroupText +=',';
            }
            countSection++;
            selectedGroupId += form.elements['groupId[]'][i].value;
            selectedGroupText +=form.elements['groupId[]'].options[form.elements['groupId[]'].selectedIndex].text;
        }
    }
    
    if(countSection==totalGroupId || countSection==0)
        selectedGroupText ="ALL";

    queryString2 += '&grpsText='+selectedGroupText;
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
    selectedCityText='';
    countCity=0;
    for(i=0;i<totalCityId;i++) {
        if (form.elements['cityId[]'][i].selected == true) {
            if (selectedCity != '') {
                selectedCity += ',';
                selectedCityText += ', ';
            }
            countCity++;
            selectedCity += form.elements['cityId[]'][i].value;
            selectedCityText +=form.elements['cityId[]'].options[form.elements['cityId[]'].selectedIndex].text;
        }
    }
    if(countCity==totalCityId || countCity==0)
        selectedCityText ="ALL";

    queryString2 += '&citysText='+selectedCityText;
    myQueryString += '&cityId='+selectedCity;

    //state
    totalStateId = form.elements['stateId[]'].length;
    selectedState='';
    selectedStateText='';
    countState=0;
    for(i=0;i<totalStateId;i++) {
        if (form.elements['stateId[]'][i].selected == true) {
            if (selectedState != '') {
                selectedState += ',';
                selectedStateText += ', ';
            }
            countState++;
            selectedState += form.elements['stateId[]'][i].value;
            selectedStateText +=form.elements['stateId[]'].options[form.elements['stateId[]'].selectedIndex].text;
        }
    }
    if(countState==totalStateId || countState==0)
        selectedStateText ="ALL";

    queryString2 += '&statesText='+selectedStateText;
    myQueryString += '&stateId='+selectedState;

    //country
    totalCountryId = form.elements['countryId[]'].length;
    selectedCountry='';
    selectedCountryText='';
    countCountry=0;
    for(i=0;i<totalCountryId;i++) {
        if (form.elements['countryId[]'][i].selected == true) {
            if (selectedCountry != '') {
                selectedCountry += ',';
                selectedCountryText += ', ';
            }
            countCountry++;
            selectedCountry += form.elements['countryId[]'][i].value;
            selectedCountryText +=form.elements['countryId[]'].options[form.elements['countryId[]'].selectedIndex].text;
        }
    }
    if(countCountry==totalCountryId || countCountry==0)
        selectedCountryText ="ALL";

    queryString2 += '&cntsText='+selectedCountryText;
    myQueryString += '&countryId='+selectedCountry;

   //univ.
    totalUniversityId = form.elements['universityId[]'].length;
    selectedUniversity='';
    selectedUniversityText='';
    countUniversity=0;
    for(i=0;i<totalUniversityId;i++) {
        if (form.elements['universityId[]'][i].selected == true) {
            if (selectedUniversity != '') {
                selectedUniversity += ',';
                selectedUniversityText += ', ';
            }
            countUniversity++;
            selectedUniversity += form.elements['universityId[]'][i].value;
            selectedUniversityText +=form.elements['universityId[]'].options[form.elements['universityId[]'].selectedIndex].text;
        }
    }
    if(countUniversity==totalUniversityId || countUniversity==0)
        selectedUniversityText ="ALL";

    queryString2 += '&univsText='+selectedUniversityText;
    myQueryString += '&universityId='+selectedUniversity;
    var feeCycleName=''
    if(document.getElementById('feeCycle').value==''){
        messageBox("<?php echo SELECT_FEECYCLE;?>");
        document.getElementById('feeCycle').focus();
        return false;
    }
	if(document.getElementById('feeHead').value==''){
        messageBox("<?php echo SELECT_FEEHEAD;?>");
        document.getElementById('feeHead').focus();
        return false;
    }
    
    if(document.getElementById('leet').value==''){
        messageBox("<?php echo "Select Applicable To ";?>");
        document.getElementById('leet').focus();    
        return false;
    }
    feeCycleName =document.getElementById('feeCycle').options[document.getElementById('feeCycle').selectedIndex].text;

    queryString = 'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&fromDateA='+fromDateA+'&toDateA='+toDateA+'&fromDateD='+fromDateD+'&toDateD='+toDateD;
    myQueryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    if(callingMode==1){
     showHide("hideAll");
     sendReq(listURL,divResultName,searchFormName, queryString);
     hide_div('showList',1);
     document.getElementById('divButton').style.display='block';
     document.getElementById('printTrId').style.display='';
     document.getElementById('printTrId2').style.display='';
    }
   else if(callingMode==2){ //for print version
    var path='<?php echo UI_HTTP_PATH;?>/studentConcessionPrint.php?'+generateQueryString(searchFormName)+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+queryString2+'&feeCycleName='+feeCycleName;
    window.open(path,"StudentConcessionPrint","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
   }
   else if(callingMode==3){ //for csv version
     window.location='studentConcessionCSV.php?'+generateQueryString(searchFormName)+'&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
   }
   else{
       messageBox("Invalid value");
       return false;
   } 
}

function printReport(){
   validateAddForm(2);
}

function printStudentCSV(){
   validateAddForm(3);
}

function hideDetails() {
    /*
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    */
    document.getElementById('showList').style.display='none';
    document.getElementById('divButton').style.display='none';
    document.getElementById('printTrId').style.display='none';
    document.getElementById('printTrId2').style.display='none';
}

function checkMaxPossibleValue(ele,value,maxValue){
    var eleId=ele.id.split('concessionValue_')[1];
    var type=document.getElementById('concessionType_'+eleId).value;
    if(type==1){
      if(parseFloat(value)>100){
       ele.className='inputboxRed';
      }
      else{
		document.getElementById('discValue_'+eleId).value=(parseFloat(maxValue)-((parseFloat(maxValue)*parseFloat(value))/100)).toFixed(2);
       ele.className='inputbox'; 
     }
     return false;
    }
    if(parseFloat(value)>parseFloat(maxValue)){
       ele.className='inputboxRed';
    }
    else{
		document.getElementById('discValue_'+eleId).value=parseFloat(maxValue)-parseFloat(value);
       ele.className='inputbox'; 
    }
}


function checkMaxPossibleValueForSelect(ele,type){
    var eleId=ele.id.split('concessionType_')[1];
    var targetEle=document.getElementById('concessionValue_'+eleId);
    var value=targetEle.value;
    var maxValue=targetEle.alt;
    if(type==1){
      if(parseFloat(value)>100){
       targetEle.className='inputboxRed';
      }
      else{
       targetEle.className='inputbox'; 
     }
     return false;
    }
    if(parseFloat(value)>parseFloat(maxValue)){
       targetEle.className='inputboxRed';
    }
    else{
       targetEle.className='inputbox'; 
    }
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
    
       var c1 = document.getElementById('results').getElementsByTagName('INPUT');
  var len=c1.length;
  if(len==0){
      messageBox("<?php echo NO_DATA_SUBMIT;?>");
      return false;
  }
  if(document.getElementById('feeCycle').value==''){
     messageBox("<?php echo SELECT_FEECYCLE;?>");
     document.getElementById('feeCycle').focus();
     return false;
  } 
  
  var studentString='';
  for(var i=0;i<len;i++){
     if(c1[i].name=='concessionValue'){
      var eleId=c1[i].id;
      var scl=eleId.split('concessionValue_')[1];
      var type=document.getElementById('concessionType_'+scl).value;
      var reason=document.getElementById('reason_'+scl);
      var discValue=document.getElementById('discValue_'+scl);
      var value=c1[i].value;
      var maxValue=c1[i].alt;
      if(parseFloat(value)<0){
          messageBox("Please enter values greater than or equal to zero");
          c1[i].focus();
          return false;
      }
      if(!isDecimal(value)){
          messageBox("Please enter numeric value");
          c1[i].focus();
          return false;
      }
      if(type==1){
       if(parseFloat(value)>100){
        c1[i].className='inputboxRed';
        messageBox("<?php echo PERCENTAGE_WISE_MAX_VALUE_CHECK;?>");
        c1[i].focus();
        return false;
       }
       else{
        c1[i].className='inputbox'; 
       }
     }
     else{
       if(parseFloat(value)>parseFloat(maxValue)){
           c1[i].className='inputboxRed';
           messageBox("<?php echo TOTAL_FEES_WISE_MAX_VALUE_CHECK;?>");
           c1[i].focus();
           return false;
        }
        else{

           c1[i].className='inputbox'; 
        }
     }
     
     if(parseFloat(value)>0){
         if(trim(reason.value)==''){
             messageBox("<?php echo ENTER_CONCESSION_REASON;?>");
             reason.focus();
             return false;
         }
     }
     
     if(studentString!=''){
         studentString +='!@!^!@!';
     }
     studentString +=scl+'_'+type+'_'+value+'_'+trim(discValue.value)+'!_$^!@^@'+trim(reason.value);
     //alert(studentString);
     //return false;
   }
  }
   
   var url = '<?php echo HTTP_LIB_PATH;?>/StudentConcession/ajaxDoConcession.php';
   new Ajax.Request(url,
       {
         method:'post',
         parameters: {
             feeCycle : document.getElementById('feeCycle').value, 
             feeHead : document.getElementById('feeHead').value, 
             studentString : studentString
         },
         onCreate: function() {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText);
                 if(ret=="<?php echo SUCCESS?>"){
                    messageBox("<?php echo STUDENT_CONCESSION_GIVEN?>");
                    document.getElementById('feeCycle').value='';
                    document.getElementById('rollNo').focus();
                    resetForm(); 
                 }
                 else{
                     messageBox(ret);
                 }
                 
         },
         onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
       });
}


//----------------------------------------------------------------------------------------------------------------
//Pupose:To reset form after data submission
//Author: Dipanjan Bhattacharjee
//Date : 5.08.2008
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------------------
function resetForm(){
 //document.getElementById('class').selectedIndex=0;   
 //tinyMCE.get('elm1').setContent("");
 //document.getElementById('sms_no').value=1;
 document.getElementById('divButton').style.display='none';
 document.getElementById('results').innerHTML="";
 document.getElementById('printTrId').style.display='none';
 document.getElementById('printTrId2').style.display='none';
 //document.getElementById('results').style.height='0px';
// hide_div('showList',2);
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

window.onload=function(){
 }


function getStudentConcessionData(stuRoll,stuId,classId,feeCycleId,studyPeriodId) {

url = '<?php echo HTTP_LIB_PATH;?>/StudentConcession/ajaxStudentConcessionValues.php';
new Ajax.Request(url,
{
	method:'post',
	asynchronous: false,
	parameters: {feeCycleId: (feeCycleId), studentId: (stuId), rollNo: (stuRoll), classId: (classId), studyPeriodId: (studyPeriodId)},
	onCreate:function(transport){ showWaitDialog(true);},
	onSuccess: function(transport){
	hideWaitDialog(true);
	j= trim(transport.responseText).evalJSON();
	var tbHeadArray = new Array(new Array('srNo','#','width="3%"',''), new Array('headName','Head Name','width="83%"','') , new Array('feeHeadAmt','Amount','width="10%"',' align="right"'), new Array('concession','Concession','width="4%"',' align="right"'), new Array('reason','Reason','width="4%"',' align="left" nowrap'));
	 
	printResultsNoSorting('resultConcession', j.info, tbHeadArray);
	
	document.addBankBranch.totalValue.value  = j.totalConcession;
	document.addBankBranch.actualValue.value = j.totalFees;
	document.addBankBranch.classId.value	 = j.classId;
	document.addBankBranch.studentId.value   = j.studentId;
	document.addBankBranch.feeCycleId.value  = j.feeCycleId;
	if(j.studentinfo[0].universityRollNo==''){
	
		j.studentinfo[0].universityRollNo ='--';
	}
	document.getElementById('studentFull').innerHTML =j.studentinfo[0].firstName+' '+j.studentinfo[0].lastName;
	document.getElementById('studentClass').innerHTML=j.studentinfo[0].className;
	document.getElementById('studentUniv').innerHTML =j.studentinfo[0].universityRollNo;
	document.getElementById('studentRoll').innerHTML =j.studentinfo[0].rollNo;
	document.addBankBranch.paidValue.value  = (j.totalFees-j.totalConcession).toFixed(2);

	if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {

		 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
		 return false;
	   }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function editDetailReceipt(stuRoll,stuId,classId,feeCycleId,studyPeriodId) {

	 dv='EditStudentConcession';
     w=320;
     h=150;  
     getStudentConcessionData(stuRoll,stuId,classId,feeCycleId,studyPeriodId);
     displayWindow(dv,w,h);
}

function calculateTotal(){

	obj = document.addBankBranch.elements['chb[]'];
	obj1 = document.addBankBranch.elements['chb1[]'];
	len = obj.length;
	if(len>0 && obj!='undefined'){
		
		sumAmount = 0;
		totalFeesValue = 0;
		for(i=0;i<len;i++){
			
			reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
			if (!reg.test(obj[i].value)){

				alert("Please enter correct value");
				obj[i].value="0.00";
			}
			else{
				if(parseFloat(obj[i].value)>=0){
				
					if(parseFloat(obj[i].value)>parseFloat(obj1[i].value)){
					
						alert("Please enter correct value");
						obj[i].className='inputboxRed';
					}
					else{
					
						sumAmount = parseFloat(parseFloat(obj[i].value)+ parseFloat(sumAmount));
						obj[i].className='inputbox3';
					}
				}
				else{
					obj[i].value="0.00";
				}
			}
			totalFeesValue = parseFloat(parseFloat(obj1[i].value)+ parseFloat(totalFeesValue));
		}
	 }
	 else{

		if(obj.value>0 && obj!='undefined'){
			
			sumAmount = obj.value;
		}
	 }
	 document.addBankBranch.totalValue.value=(sumAmount).toFixed(2);
	 document.addBankBranch.paidValue.value=(totalFeesValue-sumAmount).toFixed(2);
}

function validateAddForm1(frm, act) {
    
	addConcession();
	return false;
}

function addConcession() {

	 url = '<?php echo HTTP_LIB_PATH;?>/StudentConcession/ajaxInitAdd.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: $('addBankBranch').serialize(true),
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			 hideWaitDialog(true);
			 
			 if("CONCESSION_SUCCESS" == trim(transport.responseText)) {                     
				 flag = true;
				 if(alert("Concession Given Successfully")) {
					 
				 }
				 else {
					 hiddenFloatingDiv('EditStudentConcession');
					 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
					 return false;
				 }
			 } 
			 else {
				messageBox(trim(transport.responseText));
				if (trim(transport.responseText)=='<?php echo BANK_NAME_ALREADY_EXIST ?>'){
					document.addBank.bankName.focus();
				}
				else {
					document.addBank.bankAbbr.focus();
				}
			 }
		 },
		 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	   });
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/StudentConcession/listStudentConcessionContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>

</body>
</html>

<?php                              
// $History: listAdminStudentMessage.php $ 
?>