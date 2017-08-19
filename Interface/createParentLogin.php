<?php
//-------------------------------------------------------
// Purpose: To generate student list functionality of Create Parent Login
//
// Author : Parveen Sharma
// Created on : (23.07.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateParentLogin');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
$showTitle = "none";
$showData  = "none";
$showPrint = "none";
//require_once(BL_PATH . "/ScStudent/scInitList.php");

require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();

$queryString =  $_SERVER['QUERY_STRING'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Generate Parent Login </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php');

echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
echo UtilityManager::javaScriptFile2();
?>
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="3%"','align=\"left\"',false), 
                               new Array('firstName','Name','width="14%"','',true), 
                               new Array('rollNo','Roll No','width="10%"','',true), 
                               new Array('className','Class','width="23%"','',true),
                               new Array('fatherName',"<input type=\"checkbox\" id=\"fcheckbox\" name=\"fcheckbox\" >Father's Name",'width="16%"','',true),
                               new Array('motherName',"<input type=\"checkbox\" id=\"mcheckbox\" name=\"mcheckbox\" >Mother's Name",'width="15%"','',true),
                               new Array('guardianName',"<input type=\"checkbox\" id=\"gcheckbox\" name=\"gcheckbox\" >Guardian's Name",'width="17%"','',true));

<?php
  global $sessionHandler;
  $perPage = $sessionHandler->getSessionVariable('RECORDS_PER_PAGE_GENERATE_LOGIN');
  if($perPage=='') {
    $perPage = RECORDS_PER_PAGE;         
  }
?>                               
recordsPerPage = "<?php echo $perPage; ?>";
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/CreateParentLogin/ajaxInitParentList.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'results';
page=1; //default page
sortField = 'firstName';
sortOrderBy    = 'ASC';
var queryString='';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

function validateAddForm() {

          /* START: search filter */

    queryString = '';
    form = document.allDetailsForm;
    
    if(!isEmpty(document.getElementById('birthYearF').value) || !isEmpty(document.getElementById('birthMonthF').value) || !isEmpty(document.getElementById('birthDateF').value)){
        
        if(isEmpty(document.getElementById('birthYearF').value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthF').value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsForm.birthMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateF').value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsForm.birthDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearT').value) || !isEmpty(document.getElementById('birthMonthT').value) || !isEmpty(document.getElementById('birthDateT').value)){
        
        if(isEmpty(document.getElementById('birthYearT').value)){
           
           messageBox("Please select date of birth year");
           document.allDetailsForm.birthYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthMonthT').value)){
           
           messageBox("Please select date of birth month");
           document.allDetailsForm.birthMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('birthDateT').value)){
           
           messageBox("Please select date of birth date");
           document.allDetailsForm.birthDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('birthYearF').value) && !isEmpty(document.getElementById('birthMonthF').value) && !isEmpty(document.getElementById('birthDateF').value) && !isEmpty(document.getElementById('birthYearT').value) && !isEmpty(document.getElementById('birthMonthT').value) && !isEmpty(document.getElementById('birthDateT').value)){
    
        dobFValue = document.getElementById('birthYearF').value+"-"+document.getElementById('birthMonthF').value+"-"+document.getElementById('birthDateF').value

        dobTValue = document.getElementById('birthYearT').value+"-"+document.getElementById('birthMonthT').value+"-"+document.getElementById('birthDateT').value

        if(dateCompare(dobFValue,dobTValue)==1){
           
           messageBox("From Date of birth cannot be greater than To Date of birth");
           document.allDetailsForm.birthYearF.focus();
           return false;
        }
    }

    /* admission date*/
    if(!isEmpty(document.getElementById('admissionYearF').value) || !isEmpty(document.getElementById('admissionMonthF').value) || !isEmpty(document.getElementById('admissionDateF').value)){
        
        if(isEmpty(document.getElementById('admissionYearF').value)){
           
           messageBox("Please select date of admission year");
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthF').value)){
           
           messageBox("Please select date of admission month");
           document.allDetailsForm.admissionMonthF.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateF').value)){
           
           messageBox("Please select date of admission date");
           document.allDetailsForm.admissionDateF.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearT').value) || !isEmpty(document.getElementById('admissionMonthT').value) || !isEmpty(document.getElementById('admissionDateT').value)){
        
        if(isEmpty(document.getElementById('admissionYearT').value)){
           
           messageBox("Please select date of admission year");
           document.allDetailsForm.admissionYearT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionMonthT').value)){
           
           messageBox("Please select date of admission month");
           document.allDetailsForm.admissionMonthT.focus();
           return false;
        }
        if(isEmpty(document.getElementById('admissionDateT').value)){
           
           messageBox("Please select date of admission date");
           document.allDetailsForm.admissionDateT.focus();
           return false;
        }
    }

    if(!isEmpty(document.getElementById('admissionYearF').value) && !isEmpty(document.getElementById('admissionMonthF').value) && !isEmpty(document.getElementById('admissionDateF').value) && !isEmpty(document.getElementById('admissionYearT').value) && !isEmpty(document.getElementById('admissionMonthT').value) && !isEmpty(document.getElementById('admissionDateT').value)){
    
        doaFValue = document.getElementById('admissionYearF').value+"-"+document.getElementById('admissionMonthF').value+"-"+document.getElementById('admissionDateF').value

        doaTValue = document.getElementById('admissionYearT').value+"-"+document.getElementById('admissionMonthT').value+"-"+document.getElementById('admissionDateT').value

        if(dateCompare(doaFValue,doaTValue)==1){
           
           messageBox("From Date of admission cannot be greater than To Date of admission");
           document.allDetailsForm.admissionYearF.focus();
           return false;
        }
    }
    //roll no. + first name + last name
    queryString += 'rollNo='+form.rollNo.value;
    queryString += '&studentName='+form.studentName.value;
    queryString += '&gender='+form.gender.value;
    queryString += '&quotaId='+form.quotaId.value;
    quotaText ='';
    var quotaText = document.getElementById('quotaId');
    quotaTextValue = quotaText.options[quotaText.selectedIndex].text;
    queryString += '&quotaText='+quotaTextValue;
    document.allDetailsForm.quotaText.value = quotaTextValue;

    queryString += '&bloodGroup='+form.bloodGroup.value;
    bloodGroupText ='';
    var bloodGroupText = document.getElementById('bloodGroup');
    bloodGroupTextValue = bloodGroupText.options[bloodGroupText.selectedIndex].text;
    queryString += '&bloodGroupText='+bloodGroupTextValue;
    document.allDetailsForm.bloodGroupText.value = bloodGroupTextValue;

    //from date of birth
    //fromDateD = form.birthMonthF.value+'-'+form.birthDateF.value+'-'+form.birthYearF.value;
    queryString += '&birthMonthF='+form.birthMonthF.value+'&birthDateF='+form.birthDateF.value+'&birthYearF='+form.birthYearF.value;

    //to date of birth
    //toDateD = form.birthMonthT.value+'-'+form.birthDateT.value+'-'+form.birthYearT.value;
    queryString += '&birthMonthT='+form.birthMonthT.value+'&birthDateT='+form.birthDateT.value+'&birthYearT='+form.birthYearT.value;

    
    //start degree
    totalDegreeId = form.elements['degreeId[]'].length;
    var name = document.getElementById('degreeId');
    selectedDegrees='';
    selectedDegreeText='';
    countDegree=0;
    for(i=0;i<totalDegreeId;i++) {
        if (form.elements['degreeId[]'][i].selected == true) {
            if (selectedDegrees != '') {
                selectedDegrees += ',';
                selectedDegreeText += ', ';
                
            }
            countDegree++;
            selectedDegrees += form.elements['degreeId[]'][i].value;
            selectedDegreeText += eval("name.options["+i+"].text");
        }
    }
    if(countDegree==totalDegreeId || countDegree==0)
        selectedDegreeText ="ALL";
    
    queryString += '&degs='+selectedDegrees+'&degsText='+selectedDegreeText;
    document.allDetailsForm.degs.value = selectedDegrees;
    document.allDetailsForm.degsText.value = selectedDegreeText;
    //end degree

    //start branch
    totalBranchId = form.elements['branchId[]'].length;
    var branchText = document.getElementById('branchId');

    selectedBranches='';
    selectedBranchesText='';
    countBranches=0;
    for(i=0;i<totalBranchId;i++) {
        if (form.elements['branchId[]'][i].selected == true) {
            if (selectedBranches != '') {
                selectedBranches += ',';
                selectedBranchesText += ', ';
            }
            countBranches++;
            selectedBranches += form.elements['branchId[]'][i].value;
            selectedBranchesText += eval("branchText.options["+i+"].text");
        }
    }
    if(countBranches==totalBranchId || countBranches==0)
        selectedBranchesText ="ALL";

    queryString += '&brans='+selectedBranches+'&bransText='+selectedBranchesText;
    document.allDetailsForm.brans.value = selectedBranches;
    document.allDetailsForm.branText.value = selectedBranchesText;
    //end branch
    
    //start periodicity
    totalPeriodicityId = form.elements['periodicityId[]'].length;
    var periodText = document.getElementById('periodicityId');

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
            selectedPeriodicityText += eval("periodText.options["+i+"].text");
        }
    }
    if(countPeriod==totalPeriodicityId || countPeriod==0)
        selectedPeriodicityText ="ALL";

    queryString += '&periods='+selectedPeriodicity+'&periodsText='+selectedPeriodicityText;
    document.allDetailsForm.periods.value = selectedPeriodicity;
    document.allDetailsForm.periodsText.value = selectedPeriodicityText;
    //end periodicity

    //start Course
    totalCourseId = form.elements['courseId[]'].length;
    var courseText = document.getElementById('courseId');

    selectedCourse='';
    selectedCourseText='';
    countPeriod=0;
    for(i=0;i<totalCourseId;i++) {
        if (form.elements['courseId[]'][i].selected == true) {
            if (selectedCourse != '') {
                selectedCourse += ',';
                selectedCourseText += ', ';
            }
            countPeriod++;
            selectedCourse += form.elements['courseId[]'][i].value;
            selectedCourseText += eval("courseText.options["+i+"].text");
        }
    }
    if(countPeriod==totalCourseId || countPeriod==0)
        selectedCourseText ="ALL";

    queryString += '&course='+selectedCourse+'&courseText='+selectedCourseText;

     
    document.allDetailsForm.subjectId.value = selectedCourse;
    document.allDetailsForm.courseText.value = selectedCourseText;
     
    //end Course

     
    //start group.
    totalGroupId = form.elements['groupId[]'].length;
    var groupText = document.getElementById('groupId');

    selectedGroup='';
    selectedGroupText='';
    countSection=0;

    for(i=0;i<totalGroupId;i++) {
        if (form.elements['groupId[]'][i].selected == true) {
            if (selectedGroup != '') {
                selectedGroup += ',';
                selectedGroupText += ', ';
            }
            countSection++;
            selectedGroup += form.elements['groupId[]'][i].value;
            selectedGroupText += eval("groupText.options["+i+"].text");
        }
    }
    if(countSection==totalGroupId || countSection==0)
        selectedGroupText ="ALL";

    queryString += '&grps='+selectedGroup+'&grpsText='+selectedGroupText;
    document.allDetailsForm.grps.value = selectedGroup;
    document.allDetailsForm.grpsText.value = selectedGroupText;
    //end group.
//alert(queryString);
    //start univ.
    totalUniversityId = form.elements['universityId[]'].length;
    var univText = document.getElementById('universityId');

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
            selectedUniversityText += eval("univText.options["+i+"].text");
        }
    }
    if(countUniversity==totalUniversityId || countUniversity==0)
        selectedUniversityText ="ALL";

    queryString += '&univs='+selectedUniversity+'&univsText='+selectedUniversityText;
    document.allDetailsForm.univs.value = selectedUniversity;
    document.allDetailsForm.univsText.value = selectedUniversityText;

    //end univ.

    //alert(queryString);

    //start city
    totalCityId = form.elements['cityId[]'].length;
    var cityText = document.getElementById('cityId');

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
            selectedCityText += eval("cityText.options["+i+"].text");
        }
    }
    if(countCity==totalCityId || countCity==0)
        selectedCityText ="ALL";

    queryString += '&citys='+selectedCity+'&citysText='+selectedCityText;
    document.allDetailsForm.citys.value = selectedCity;
    document.allDetailsForm.citysText.value = selectedCityText;

    //start state
    totalStateId = form.elements['stateId[]'].length;
    var stateText = document.getElementById('stateId');

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
            selectedStateText += eval("stateText.options["+i+"].text");
        }
    }
    if(countState==totalStateId || countState==0)
        selectedStateText ="ALL";

    queryString += '&states='+selectedState+'&statesText='+selectedStateText;
    document.allDetailsForm.states.value = selectedState;
    document.allDetailsForm.statesText.value = selectedStateText;
    //end state

    //start country
    totalCountryId = form.elements['countryId[]'].length;
    var countryText = document.getElementById('countryId');

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
            selectedCountryText += eval("countryText.options["+i+"].text");
        }
    }
    if(countCountry==totalCountryId || countCountry==0)
        selectedCountryText ="ALL";

    queryString += '&cnts='+selectedCountry+'&cntsText='+selectedCountryText;
    document.allDetailsForm.cnts.value = selectedCountry;
    document.allDetailsForm.cntsText.value = selectedCountryText;
    //end country

    //management category
    //var categoryText = document.getElementById('categoryId');
    //categoryTextValue = categoryText.options[categoryText.selectedIndex].text;

    queryString += '&categoryId='+form.categoryId.value;
     
    //from date of admission
    //fromDateA = form.admissionMonthF.value+'-'+form.admissionDateF.value+'-'+form.admissionYearF.value;
    queryString += '&admissionMonthF='+form.admissionMonthF.value+'&admissionDateF='+form.admissionDateF.value+'&admissionYearF='+form.admissionYearF.value;
    
    //to date of admission
    //toDateA = form.admissionMonthT.value+'-'+form.admissionDateT.value+'-'+form.admissionYearT.value;
    queryString += '&admissionMonthT='+form.admissionMonthT.value+'&admissionDateT='+form.admissionDateT.value+'&admissionYearT='+form.admissionYearT.value;

    //start hotsel
    totalHostelId = form.elements['hostelId[]'].length;
    var hostelText = document.getElementById('hostelId');

    selectedHostel='';
    selectedHostelText='';
    countHostel=0;

    for(i=0;i<totalHostelId;i++) {
        if (form.elements['hostelId[]'][i].selected == true) {
            if (selectedHostel != '') {
                selectedHostel += ',';
                selectedHostelText += ', ';
            }
            countHostel++;
            selectedHostel += form.elements['hostelId[]'][i].value;
            selectedHostelText += eval("hostelText.options["+i+"].text");
        }
    }
    if(countHostel==totalHostelId || countHostel==0)
        selectedHostelText ="ALL";

    queryString += '&hostels='+selectedHostel+'&hostelsText='+selectedHostelText;
    document.allDetailsForm.hostels.value = selectedHostel;
    document.allDetailsForm.hostelsText.value = selectedHostelText;
    //end hotsel

    //Start bus stop
    totalBusStopId = form.elements['busStopId[]'].length;
    var busstopText = document.getElementById('busStopId');

    selectedBusStop='';
    selectedBusStopText='';
    countBusstop=0;

    for(i=0;i<totalBusStopId;i++) {
        if (form.elements['busStopId[]'][i].selected == true) {
            if (selectedBusStop != '') {
                selectedBusStop += ',';
                selectedBusStopText += ', ';
            }
            countBusstop++;
            selectedBusStop += form.elements['busStopId[]'][i].value;
            selectedBusStopText += eval("busstopText.options["+i+"].text");
        }
    }
    if(countBusstop==totalBusStopId || countBusstop==0)
        selectedBusStopText ="ALL";

    queryString += '&buss='+selectedBusStop+'&bussText='+selectedBusStopText;
    document.allDetailsForm.buss.value = selectedBusStop;
    document.allDetailsForm.bussText.value = selectedBusStopText;
    //End bus stop

    //start bus route
    totalBusRouteId = form.elements['busRouteId[]'].length;
    var busrouteText = document.getElementById('busRouteId');

    selectedBusRoute='';
    selectedBusRouteText='';
    countBusroute=0;

 
    for(i=0;i<totalBusRouteId;i++) {
        if (form.elements['busRouteId[]'][i].selected == true) {
            if (selectedBusRoute != '') {
                selectedBusRoute += ',';
                selectedBusRouteText += ', ';
            }
            countBusroute++;
            selectedBusRoute += form.elements['busRouteId[]'][i].value;
            selectedBusRouteText += eval("busrouteText.options["+i+"].text");
        }
    }
    if(countBusroute==totalBusRouteId || countBusroute==0)
        selectedBusRouteText ="ALL";

    queryString += '&routs='+selectedBusRoute+'&routsText='+selectedBusRouteText;
    document.allDetailsForm.routs.value = selectedBusRoute;
    document.allDetailsForm.routsText.value = selectedBusRouteText;
    //end bus route
    

    queryString += '&page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;

    //alert(queryString);
    //return false;
    showHide("hideAll");
    
    page=1;  

    hideDetails(); 
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField,false);
   
    document.getElementById("nameRow").style.display='';
    document.getElementById("nameRow2").style.display='';
    document.getElementById("resultRow").style.display='';
}

function doAll(){
    formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
       for(var i=1;i<formx.length;i++){
         if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
           formx.elements[i].checked=true;
         }
       }
    }
    else{
      for(var i=1;i<formx.length;i++){
        if(formx.elements[i].type=="checkbox"){
          formx.elements[i].checked=false;
        }
      }
    }
}

function hideDetails() {
   document.getElementById("resultRow").style.display='none';
   document.getElementById('nameRow').style.display='none';
   document.getElementById('nameRow2').style.display='none';
}

function passwordReport() {

     newPassword = "";
     if (document.allDetailsForm.password[0].checked == true) {
        var onePassword = 1;
     }
     else if (document.allDetailsForm.password[1].checked == true) {
        var onePassword = 2;
        if(trim(document.allDetailsForm.newPassword.value) == "") {
            messageBox('Enter common password');
            document.allDetailsForm.newPassword.focus();
            return false;
        }
        var newPassword=trim(document.allDetailsForm.newPassword.value);
     }
     
     if (document.allDetailsForm.overwrite.checked == true) {
      var  overwrite = 1;
     }
     else if (document.allDetailsForm.overwrite.checked == false) {
        var overwrite = 0;
     }
     
     if(isEmpty(document.getElementById('authorizedName').value)) {
       messageBox("Enter authorized person");
       document.getElementById('authorizedName').focus();
       return false;
     }     
    
     if(isEmpty(document.getElementById('designation').value)) {
       messageBox("Enter designation");
       document.getElementById('designation').focus();
       return false;
     }

     
     var selected = 0;
     var studentCheck="";
     
     formx = document.allDetailsForm;
     for(var i=1;i<formx.length;i++){
       if(formx.elements[i].type=="checkbox"){
          if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
            if(studentCheck=="") {
               studentCheck=formx.elements[i].value; 
            }
            else {
                studentCheck = studentCheck + ',' +formx.elements[i].value; 
            }
            selected++;
          }
       }
     }
     
     if(selected==0){
       messageBox("Please select atleast one student");
       return false;
     }
     
     //document.allDetailsForm.fcheckbox.value=0;
     //document.allDetailsForm.mcheckbox.value=0;
     //document.allDetailsForm.gcheckbox.value=0;
     
     var condition = "";                       
     selected=0;
     if(formx.fcheckbox.checked==true) {
       selected=1;  
       document.allDetailsForm.fcheckbox.value = 1;
       condition = condition + "&fcheckbox=1";
     }
     else {
       condition = condition + "&fcheckbox=0";  
     } 
     
     if(formx.mcheckbox.checked==true) {
       selected=1;  
       condition = condition + "&mcheckbox=1";
       document.allDetailsForm.mcheckbox.value = 1;
     }
     else {
       condition = condition + "&mcheckbox=0";
     }
     
     if(formx.gcheckbox.checked==true) {
       selected=1;  
       condition = condition + "&gcheckbox=1";
       document.allDetailsForm.gcheckbox.value = 1;
     }
     else {
       condition = condition + "&gcheckbox=0";
     }
     
     if(selected==0){
       messageBox("Please select one option father/mother/guardian");
       return false;
     }
     
     if(false===confirm("Do you want to generate parent logins?")) {
       return false;
    }
    else {   
         url = '<?php echo HTTP_LIB_PATH;?>/CreateParentLogin/ajaxCreatePassword.php';

         //pars = generateQueryString('allDetailsForm');
         pars = '&onePassword='+onePassword+'&studentCheckIds='+studentCheck+condition+'&overwrite='+overwrite+'&newPassword='+newPassword;
         
         new Ajax.Request(url,
         {
             method:'post',
             asynchronous:false,
             parameters: pars,
             
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText).split('!~!~!');
                 if("<?php echo SUCCESS;?>" == ret[0]) {                     
                   flag = true;
                   if(ret[2]!='' || ret[3]!='' || ret[4]!=''){
                     document.getElementById('studentNotIds').value=ret[1];   
                     document.getElementById('fIds').value=ret[2];
                     document.getElementById('mIds').value=ret[3];
                     document.getElementById('gIds').value=ret[4];
                     document.getElementById('checkValue').value=onePassword;
                     document.getElementById('check1').value=newPassword;
                     document.getElementById('sortOrderBy1').value=sortOrderBy;
                     document.getElementById('sortField1').value=sortField;
                     document.allDetailsForm.target ="_blank";  
                     document.allDetailsForm.action ="<?php echo UI_HTTP_PATH ?>/parentListPrint.php";
                     
                     //document.allDetailsForm.action ="<?php echo UI_HTTP_PATH ?>/parentListPrint.php";
                     document.allDetailsForm.submit();
                     //path="<?php echo UI_HTTP_PATH ?>/parentListPrint.php?fIds="+ret[2]+"&mIds="+ret[3]+"&gIds="+ret[4]+'&checkValue='+onePassword+'&check='+newPassword+condition+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
                     //path="<?php echo UI_HTTP_PATH ?>/parentListPrint.php";
                     //window.open(path,"Report","status=1,menubar=1,scrollbars=1, width=800, height=510, top=150,left=150");  
                     //window.location = "<?php echo UI_HTTP_PATH ?>/parentListPrint.php?fIds="+ret[2]+"&mIds="+ret[3]+"&gIds="+ret[4]+'&checkValue='+onePassword+'&check='+newPassword+condition+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
                   }
                   else {
                     messageBox("No login created"); 
                   }
                 }
                 else {
                   messageBox(ret[0]);    
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
         });
    }
}

    
function passwordReportCSV() {

     newPassword = "";
     if (document.allDetailsForm.password[0].checked == true) {
        var onePassword = 1;
     }
     else if (document.allDetailsForm.password[1].checked == true) {
        var onePassword = 2;
        if(trim(document.allDetailsForm.newPassword.value) == "") {
            messageBox('Enter common password');
            document.allDetailsForm.newPassword.focus();
            return false;
        }
        newPassword=trim(document.allDetailsForm.newPassword.value);
     }
     
     if (document.allDetailsForm.overwrite.checked == true) {
      var  overwrite = 1;
     }
     else if (document.allDetailsForm.overwrite.checked == false) {
        var overwrite = 0;
     }
     
     var selected = 0;
     var studentCheck="";
     
     formx = document.allDetailsForm;
     for(var i=1;i<formx.length;i++){
       if(formx.elements[i].type=="checkbox"){
          if((formx.elements[i].checked) && (formx.elements[i].name=="chb[]")){
            if(studentCheck=="") {
               studentCheck=formx.elements[i].value; 
            }
            else {
                studentCheck = studentCheck + ',' +formx.elements[i].value; 
            }
            selected++;
          }
       }
     }
     
     if(selected==0){
       messageBox("Please select atleast one student");
       return false;
     }
     
     document.allDetailsForm.fcheckbox.value=0;
     document.allDetailsForm.mcheckbox.value=0;
     document.allDetailsForm.gcheckbox.value=0;
     
     var condition = "";                       
     selected=0;
     if(formx.fcheckbox.checked==true) {
       selected=1;  
       document.allDetailsForm.fcheckbox.value = 1;
       condition = condition + "&fcheckbox=1";
     }
     else {
       condition = condition + "&fcheckbox=0";  
     } 
     
     if(formx.mcheckbox.checked==true) {
       selected=1;  
       condition = condition + "&mcheckbox=1";
       document.allDetailsForm.mcheckbox.value = 1;
     }
     else {
       condition = condition + "&mcheckbox=0";
     }
     
     if(formx.gcheckbox.checked==true) {
       selected=1;  
       condition = condition + "&gcheckbox=1";
       document.allDetailsForm.gcheckbox.value = 1;
     }
     else {
       condition = condition + "&gcheckbox=0";
     }
     
     if(selected==0){
       messageBox("Please select one option father/mother/guardian");
       return false;
     }
 
    if(false===confirm("Do you want to generate parent logins?")) {
       return false;
    }
    else {   
         url = '<?php echo HTTP_LIB_PATH;?>/CreateParentLogin/ajaxCreatePassword.php';

         //var pars = generateQueryString('allDetailsForm');
         pars = '&onePassword='+onePassword+'&studentCheckIds='+studentCheck+'&overwrite='+overwrite+'&newPassword='+newPassword+condition;
         
         new Ajax.Request(url,
         {
             method:'post',
             asynchronous:false,
             parameters: pars,
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
                 hideWaitDialog(true);
                 var ret=trim(transport.responseText).split('!~!~!');
                 if("<?php echo SUCCESS;?>" == ret[0]) {                     
                   flag = true;
                   if(ret[2]!='' || ret[3]!='' || ret[4]!=''){
                     document.getElementById('studentNotIds').value=ret[1];   
                     document.getElementById('fIds').value=ret[2];
                     document.getElementById('mIds').value=ret[3];
                     document.getElementById('gIds').value=ret[4];
                     document.getElementById('checkValue').value=onePassword;
                     document.getElementById('check1').value=newPassword;
                     document.getElementById('sortOrderBy1').value=sortOrderBy;
                     document.getElementById('sortField1').value=sortField;
                     
                     document.allDetailsForm.target ="_blank";  
                     document.allDetailsForm.action ="<?php echo UI_HTTP_PATH ?>/parentListPrintCSV.php";
                     
                     document.allDetailsForm.submit();
                     //window.location = "<?php echo UI_HTTP_PATH ?>/parentListPrintCSV.php?fIds="+ret[2]+"&mIds="+ret[3]+"&gIds="+ret[4]+'&checkValue='+onePassword+'&check='+newPassword+condition+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
                   }
                   else {
                     messageBox("No login created"); 
                   }
                 }
                 else {
                   messageBox(ret[0]);    
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
         });
    }
}

//populate list
window.onload=function(){
//alert("<?php echo $queryString?>");
   if("<?php echo $queryString?>"!=''){
       sendReq(listURL,divResultName,searchFormName,"<?php echo $queryString?>");
       document.getElementById("nameRow").style.display='';
       document.getElementById("nameRow2").style.display='';
       document.getElementById("resultRow").style.display='';
   }
   var roll = document.getElementById("rollNo");
   roll.focus();
 autoSuggest(roll);
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/CreateParentLogin/createParentLoginContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: createParentLogin.php $
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/29/10    Time: 11:46a
//Updated in $/LeapCC/Interface
//records to be displayed per page in config base format updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/25/10    Time: 3:16p
//Updated in $/LeapCC/Interface
//validation format update (father/Mother/guardian userId '0' check
//updated)
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 10/29/09   Time: 6:13p
//Updated in $/LeapCC/Interface
//added code for autosuggest functionality
//
//*****************  Version 3  *****************
//User: Parveen      Date: 9/21/09    Time: 1:15p
//Updated in $/LeapCC/Interface
//Resolved the sorting, conditions, alignment issues updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 9/18/09    Time: 10:53a
//Updated in $/LeapCC/Interface
//sorting & validations updated & CSV file created
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/28/09    Time: 4:11p
//Created in $/LeapCC/Interface
//initial checkin
//

?> 