<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (12.6.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PreAdmissionMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Pre Admission Master</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/PreAdmission/ajaxInitList.php';
searchFormName = 'searchExtraForm'; // name of the form which will be used for search
addFormName    = 'AddExtraClass';   
editFormName   = 'EditExtraClass';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteStudent';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'admissionStatus';
sortOrderBy    = 'ASC';
queryString1=''; 

roleId = "<?php echo $roleId; ?>";

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      
//This function Displays Div Window
// ajax search results ---end ///
//This function Displays Div Window


function refereshData() {
    hideDetails(); 
    queryString1="";
    url = '<?php echo HTTP_LIB_PATH;?>/PreAdmission/ajaxInitList.php';  
    var tableColumns2 =new Array(
                                new Array('srNo','#','width="2%" align="left"',false), 
                                new Array('admissionStatus','Admission Status','width="8%" align="left"',true),
                                new Array('admissionNumber','Admission No.','width="12% align="left"',true), 
                                new Array('studentName','Student Name','width="10%" align="left"',true), 
                                new Array('campAbbr','Camp','width="8%" align="left"',true),
                                new Array('courseAbbr','Course','width="10%" align="left"',true), 
                                new Array('action1','Action','width="5%" align="center"',false)
                               );
   
        
    //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
    listObj3 = new initPage(url,recordsPerPage,linksPerPage,1,'',sortField,sortOrderBy,divResultName,'','',true,'listObj3',tableColumns2,'','','&'+queryString1);
    sendRequest(url, listObj3, '',false);
}

function blankValues() {
 
    formx = document.addForm;
    var obj=formx.getElementsByTagName('INPUT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='TEXT') {   
        // blank value check 
        obj[i].value='';   
      }
      if(obj[i].type.toUpperCase()=='RADIO') {   
        // blank value check 
        obj[i].checked=false;  
      }
      if(obj[i].type.toUpperCase()=='CHECKBOX') {   
        // blank value check 
        obj[i].checked=false;  
      }
    }
    
    var obj=formx.getElementsByTagName('SELECT');
    var total=obj.length;
    for(var i=0;i<total;i++) {
      if(obj[i].type.toUpperCase()=='SELECT-ONE') {   
        // blank value check 
        obj[i].selectedIndex=0;  
      }  
    }
    document.getElementById('status1').checked=true;
    getAdmissionNo();    
    getPreference();  
    return false;
}

function hideDetails() {
   document.getElementById('divShowSearch').style.display='none';
   document.getElementById('divShowList').style.display='';
   document.getElementById('resultsDiv').innerHTML='';
   document.getElementById('nameRow2').style.display='';  
   document.getElementById('divShowAddEdit').style.display='none';  
   
}

function editWindow(id,str) {
   blankValues(); 
   if(str=='Add') {
     document.getElementById('studentId').value='';
   }  
   else {
     document.getElementById('studentId').value=id;  
   }
   document.getElementById('divShowSearch').style.display='none';
   document.getElementById('divShowList').style.display='none';
   document.getElementById('resultsDiv').innerHTML='';
   document.getElementById('nameRow2').style.display='none';  
   document.getElementById('divShowAddEdit').style.display='';      
   if(str!='Add') {
      populateValues(id); 
   }
   return false;
}

function populateValues(id) {

     url = '<?php echo HTTP_LIB_PATH;?>/PreAdmission/ajaxGetValues.php';
     
     //document.addForm.studentId.selectedIndex=0;
     new Ajax.Request(url,
       {
         method:'post',
         parameters: {studentId: id},
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
               hideWaitDialog(true);
               j = eval('('+transport.responseText+')');
                
                document.addForm.studentId.value = j.studentinfo[0].studentId;
                
                if(j.studentinfo[0].admissionStatus=='Enquiry') {
                  document.addForm.status[0].checked=true; 
                }
                else if(j.studentinfo[0].admissionStatus=='Admission') { 
                  document.addForm.status[1].checked=true;
                }
                else if(j.studentinfo[0].admissionStatus=='Registered') {
                  document.addForm.status[2].checked=true;
                }
                else if(j.studentinfo[0].admissionStatus=='Walk In') {
                  document.addForm.status[3].checked=true;   
                }
                else {
                  document.addForm.gender[0].checked=false;  
                }
                
                document.addForm.camp.value = j.studentinfo[0].campId+"~"+j.studentinfo[0].campAbbr;
                
                document.addForm.courses.value = j.studentinfo[0].courseId;
                document.addForm.school.value = j.studentinfo[0].schoolId;
                document.addForm.srNumber.value = j.studentinfo[0].srNumber;
                document.addForm.studentName.value = j.studentinfo[0].studentName;
                
                getAdmissionNo();
                
                if(j.studentinfo[0].dateofBirth=='0000-00-00') {
                  document.addForm.dateofBirth.value = '';  
                }
                else {
                  document.addForm.dateofBirth.value = j.studentinfo[0].dateofBirth;
                }
                if(j.studentinfo[0].gender=='Male') {
                  document.addForm.gender[0].checked=true;
                }
                else if(j.studentinfo[0].gender=='Female') {
                  document.addForm.gender[1].checked=true;   
                }
                else {
                  document.addForm.gender[0].checked=false;  
                  document.addForm.gender[1].checked=false;    
                }
                
                country1=j.studentinfo[0].corr_country;
                country2=j.studentinfo[0].perm_country;
                country3=j.studentinfo[0].father_off_country;
                country4=j.studentinfo[0].mother_off_country;
                country5=j.studentinfo[0].guardian_off_country;
                
                state1=j.studentinfo[0].corr_state;
                state2=j.studentinfo[0].perm_state;
                state3=j.studentinfo[0].father_off_state;
                state4=j.studentinfo[0].mother_off_state;
                state5=j.studentinfo[0].guardian_off_state;
                
                city1=j.studentinfo[0].corr_city;
                city2=j.studentinfo[0].perm_city; 
                city3=j.studentinfo[0].father_off_city; 
                city4=j.studentinfo[0].mother_off_city; 
                city5=j.studentinfo[0].guardian_off_city; 
               
                
                document.addForm.blood_group.value = j.studentinfo[0].bloodGroup;
                document.addForm.religion.value = j.studentinfo[0].religionId;
                document.addForm.category.value = j.studentinfo[0].categoryId;
                document.addForm.stateDomicile.value = j.studentinfo[0].domicileId;
                
                
                document.addForm.identiMark.value = j.studentinfo[0].identificationMark;
                
                document.addForm.correspondeceAddress1.value = j.studentinfo[0].corr_address1;
                document.addForm.correspondeceAddress2.value = j.studentinfo[0].corr_address2;
                document.addForm.correspondecePincode.value = j.studentinfo[0].corr_pincode;
                document.addForm.correspondecePhone.value = j.studentinfo[0].corr_contactno;
              
                document.addForm.permanentAddress1.value = j.studentinfo[0].perm_address1;
                document.addForm.permanentAddress2.value = j.studentinfo[0].perm_address2;
                document.addForm.permanentPincode.value = j.studentinfo[0].perm_pincode;
                document.addForm.permanentPhone.value = j.studentinfo[0].perm_contactno;    
                
                document.addForm.hostel_acc.value = j.studentinfo[0].hostelFacility;
                
                document.addForm.mobileNumber.value = j.studentinfo[0].mobileNumber;  
                document.addForm.fatherName.value = j.studentinfo[0].fatherName;
                document.addForm.fatherQualification.value = j.studentinfo[0].fatherQual;
                document.addForm.fatherProfession.value = j.studentinfo[0].fatherProf;
                document.addForm.fatherDesignation.value = j.studentinfo[0].fatherDesig;
                document.addForm.fatherMobile.value = j.studentinfo[0].father_mobile;
                document.addForm.fatherAddress.value = j.studentinfo[0].father_off_address;
                
                document.addForm.fatherPincode.value = j.studentinfo[0].father_off_pincode;
                document.addForm.fatherContact.value = j.studentinfo[0].father_contactNo;
                document.addForm.fatherEmail.value = j.studentinfo[0].father_email;
                
               
                document.addForm.motherName.value = j.studentinfo[0].motherName;
                document.addForm.motherQualification.value = j.studentinfo[0].motherQual;
                document.addForm.motherProfession.value = j.studentinfo[0].motherProf;
                document.addForm.motherDesignation.value = j.studentinfo[0].fatherDesig;
                document.addForm.motherMobile.value = j.studentinfo[0].mother_landlineNo;
                document.addForm.motherAddress.value = j.studentinfo[0].mother_off_address;
                
                document.addForm.motherPincode.value = j.studentinfo[0].mother_off_pincode;
                document.addForm.motherContact.value = j.studentinfo[0].mother_contactNo;
                document.addForm.motherEmail.value = j.studentinfo[0].mother_email;
                
                
                document.addForm.guardianName.value = j.studentinfo[0].guardianName;
                document.addForm.guardianQualification.value = j.studentinfo[0].guardianQual;
                document.addForm.guardianProfession.value = j.studentinfo[0].guardianProf;
                document.addForm.guardianDesignation.value = j.studentinfo[0].guardianDesig;
                document.addForm.guardianMobile.value = j.studentinfo[0].guardian_landlineNo;
                document.addForm.guardianAddress.value = j.studentinfo[0].guardian_off_address;
                
                document.addForm.guardianPincode.value = j.studentinfo[0].guardian_off_pincode;
                document.addForm.guardianContact.value = j.studentinfo[0].guardian_contactNo;
                document.addForm.guardianEmail.value = j.studentinfo[0].guardian_email;
                
                document.addForm.annualIncome.value = j.studentinfo[0].annualIncome;
                
                document.addForm.siblingName.value = j.studentinfo[0].siblingName;
                document.addForm.siblingYear.value = j.studentinfo[0].siblingYear;
                document.addForm.siblingCourse.value = j.studentinfo[0].siblingCourse;
                document.addForm.siblingRollno.value = j.studentinfo[0].siblingRollno;
                
                
                getPreference();    
                for(i=0;i<j.preferenceinfo.length;i++) {
                  id = j.preferenceinfo[i].preferanceCourseId;
                  str = j.preferenceinfo[i].sortId;
                  eval("document.getElementById('coursePreference"+id+"').value=str"); 
                }
                
                // $examArray = array("1"=>"10th","2"=>"10+2","3"=>"Graduation","4"=>"Diploma","5"=>"Any Other");  
                // previousBoard, previousYear,  previousMarks, previousMaxMarks, subjects, previousPercentage
                for(i=0;i<j.academicinfo.length;i++) {
                   if(j.academicinfo[i].classId=="10th") {
                     setValue(1,i)  
                   }
                   else if(j.academicinfo[i].classId=="10+2") {
                     setValue(2,i)  
                   }
                   else if(j.academicinfo[i].classId=="Graduation") {
                     setValue(3,i)  
                   }
                   else if(j.academicinfo[i].classId=="Diploma") {
                     setValue(4,i)  
                   }
                   else if(j.academicinfo[i].classId=="Any Other") {
                     setValue(5,i)  
                   }
                }
                
                for(i=0;i<j.examinfo.length;i++) {
                  id = j.examinfo[i].testId;
                  eval("document.getElementById('examType"+id+"').checked=true"); 
                }
                
                
                if(country1!=0) {
                  document.addForm.correspondenceCountry.value = country1;  
                  autoPopulate(country1,'states','Add','correspondenceStates','correspondenceCity');
                }
                if(country2!=0) {
                  document.addForm.permanentCountry.value = country2;   
                  autoPopulate(country2,'states','Add','permanentStates','permanentCity');
                }
                if(country3!=0) {
                  document.addForm.fatherCountry.value = country3;  
                  autoPopulate(country3,'states','Add','fatherStates','fatherCity');
                }
                if(country4!=0) {
                  document.addForm.motherCountry.value = country4;  
                  autoPopulate(country4,'states','Add','motherStates','motherCity');
                }
                if(country5!=0) {
                  document.addForm.guardianCountry.value = country5;  
                  autoPopulate(country5,'states','Add','guardianStates','guardianCity');
                }
                
                
                if(state1!=0) { 
                  document.addForm.correspondenceStates.value = state1;
                  autoPopulate(state1,'city','Add','correspondenceStates','correspondenceCity');
                }
                if(state2!=0) {  
                  document.addForm.permanentStates.value = state2;  
                  autoPopulate(state2,'city','Add','permanentStates','permanentCity');
                }
                if(state3!=0) {  
                  document.addForm.fatherStates.value = state3;  
                  autoPopulate(state3,'city','Add','fatherStates','fatherCity');
                }
                if(state4!=0) {  
                  document.addForm.motherStates.value = state4;  
                  autoPopulate(state4,'city','Add','motherStates','motherCity');
                }
                if(state5!=0) {  
                  document.addForm.guardianStates.value = state5;
                  autoPopulate(state5,'city','Add','guardianStates','guardianCity');
                }
               
                if(city1!=0) { 
                  document.addForm.correspondenceCity.value = city1;
		   
                }
                if(city2!=0) { 
                  document.addForm.permanentCity.value = city2;
                }
                if(city3!=0) { 
                  document.addForm.fatherCity.value = city3;
                }
                if(city4!=0) { 
                  document.addForm.motherCity.value = city4;
                }
                if(city5!=0) { 
                  document.addForm.guardianCity.value = city5;
                }
           }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function setValue(id,i) {
   eval("document.getElementById('board"+id+"').value=j.academicinfo[i].previousBoard");
   eval("document.getElementById('year"+id+"').value=j.academicinfo[i].previousYear");
   eval("document.getElementById('marksObtained"+id+"').value=j.academicinfo[i].previousMarks");
   eval("document.getElementById('maxMarks"+id+"').value=j.academicinfo[i].previousMaxMarks");
   eval("document.getElementById('subject"+id+"').value=j.academicinfo[i].subjects");
   eval("document.getElementById('percentage"+id+"').value=j.academicinfo[i].previousPercentage");
   return false;
}



function deleteStudent(id) {  
        
     if(false===confirm("Do you want to delete this record?")) {
         return false;
     }
     else {   
         url = '<?php echo HTTP_LIB_PATH;?>/PreAdmission/ajaxInitDelete.php';
         new Ajax.Request(url,
         { method:'post',
           parameters: {studentId: id},
           onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
                 hideWaitDialog(true);
              //   messageBox(trim(transport.responseText));
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     refereshData();   
                     return false;
                 }
                  else {
                     messageBox(trim(transport.responseText));
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
     }    
}

function getAdmissionNo() {
  
  admNo='';
  status='E';  
  if(document.addForm.status[0].checked==true) {
    status='E';
  }
  else if(document.addForm.status[1].checked==true) {
    status='A';
  }
  else if(document.addForm.status[2].checked==true) {
    status='R';
  }
  else if(document.addForm.status[3].checked==true) {
    status='W';
  }
  admNo=status+'12';
  camp=document.addForm.camp.value;
  if(camp=='') {
    return false;  
  }
  var rval=camp.split('~'); 
  nn = trim(document.addForm.srNumber.value);
  str="";
  if(nn.length==1) {
    str="000"+nn;      
  }
  else if(nn.length==2) {
    str="00"+nn;      
  }
  else if(nn.length==3) {
    str="0"+nn;      
  }
  else {
    str=nn;      
  }
  
  admNo=admNo+rval[1]+str;    
  document.addForm.admissionNumber.value = admNo;
}

function validateLoginForm(frm) {

      
    var fieldsArray = new Array(new Array("camp","Select Admin Camp"),
                                new Array("srNumber","Enter Serial Number"),
                                new Array("studentName","Enter Student Name"));

    var len = fieldsArray.length;
    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            alert(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
    
    addPreAdmission();
    
    return false;
}

 
function getPreference() {
   
   if(document.getElementById('courses').value=='') {
     document.getElementById('showPreference1').style.display='none'; 
     document.getElementById('showPreference2').style.display='none'; 
     document.getElementById('showPreference3').innerHTML=''; 
     return false;
  }

  
   var url ='<?php echo HTTP_LIB_PATH;?>/PreAdmission/ajaxGetPreference.php';
   new Ajax.Request(url,
   {
         method:'post',
         asynchronous:false,
         parameters: { courseId: document.getElementById('courses').value},
         onCreate: function(transport){
            showWaitDialog();
         },
         onSuccess: function(transport){
            hideWaitDialog();
            if(trim(transport.responseText)==0){
              document.getElementById('showPreference1').style.display='none'; 
              document.getElementById('showPreference2').style.display='none'; 
              document.getElementById('showPreference3').innerHTML=''; 
              return false;
            }
            document.getElementById('showPreference1').style.display=''; 
            document.getElementById('showPreference2').style.display=''; 
            document.getElementById('showPreference3').innerHTML=trim(transport.responseText); 
         },
         onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
   }); 
}
 
function addPreAdmission() {
     /*
     var fieldsArray = new Array(new Array("cityName","<?php echo ENTER_CITY_NAME;?>"),
                                 new Array("cityCode","<?php echo ENTER_CITY_CODE;?>"),
                                 new Array("states","<?php echo SELECT_STATE_NAME;?>") );
    
     */
     url = '<?php echo HTTP_LIB_PATH;?>/PreAdmission/initAdd.php';
     formx = document.addForm;
     new Ajax.Request(url,
     {
         method:'post',
         parameters: $('addForm').serialize(true),
         onCreate: function() {
             showWaitDialog(true); 
         },
         onSuccess: function(transport) {
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
                 hideWaitDialog(true);
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
                     flag = true;                              
                     if(document.getElementById('studentId').value!='') {
                        document.getElementById('studentId').value='';
                        refereshData();
                        return false;
                     }
                     blankValues(); 
                     document.getElementById('studentId').value='';
                     if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                        location.reload();
                     }
                     else {
                         refereshData();
                         //location.reload();
                         return false;
                     }
                 } 
                 else {
                    messageBox(trim(transport.responseText)); 
                 }
           }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });      
}

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

            document.addForm.city.options.length=0;
            var objOption = new Option("Select","");          
            document.addForm.city.options.add(objOption);
        } 
   }
   
new Ajax.Request(url,
{
 method:'post',
 asynchronous:false,
 parameters: {type: type,id: val},
 onCreate: function() {
     showWaitDialog(true); 
 },
     onSuccess: function(transport){

         hideWaitDialog(true);
         j = eval('('+transport.responseText+')'); 
        // alert(transport.responseText);
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
                     document.addForm.states.options.add(objOption); 
                 }
                else{
                     var objOption = new Option(j[c].cityName,j[c].cityId);
                     document.addForm.city.options.add(objOption); 
                } 
              }
          }
     },
     onFailure: function(){ alert('Something went wrong...') }
   }); 
}


window.onload=function() {  
   
    autoPopulate('<?php echo $defaultCountryId?>','states','Add','fatherStates','fatherCity');
    autoPopulate('<?php echo $defaultStateId?>','city','Add','fatherStates','fatherCity');
    
    document.getElementById('fatherCountry').value="<?php echo $defaultCountryId?>";
    document.getElementById('fatherStates').value="<?php echo $defaultStateId?>";
    document.getElementById('fatherCity').value="<?php echo $defaultCityId?>";
    /*END: function to populate father states and countries based on session values*/

    /*START: function to populate mother states and countries based on session values*/
    autoPopulate('<?php echo $defaultCountryId?>','states','Add','motherStates','motherCity');
    autoPopulate('<?php echo $defaultStateId?>','city','Add','motherStates','motherCity');
    
    document.getElementById('motherCountry').value="<?php echo $defaultCountryId?>";
    document.getElementById('motherStates').value="<?php echo $defaultStateId?>";
    document.getElementById('motherCity').value="<?php echo $defaultCityId?>";
    /*END: function to populate mother states and countries based on session values*/

    /*START: function to populate guardian states and countries based on session values*/
    autoPopulate('<?php echo $defaultCountryId?>','states','Add','guardianStates','guardianCity');
    autoPopulate('<?php echo $defaultStateId?>','city','Add','guardianStates','guardianCity');
    
    document.getElementById('guardianCountry').value="<?php echo $defaultCountryId?>";
    document.getElementById('guardianStates').value="<?php echo $defaultStateId?>";
    document.getElementById('guardianCity').value="<?php echo $defaultCityId?>";
    /*END: function to populate guardian states and countries based on session values*/
   
    /*START: function to populate correspondence states and countries based on session values*/
    autoPopulate('<?php echo $defaultCountryId?>','states','Add','correspondenceStates','correspondenceCity');
    autoPopulate('<?php echo $defaultStateId?>','city','Add','correspondenceStates','correspondenceCity');
    
    document.getElementById('correspondenceCountry').value="<?php echo $defaultCountryId?>";
    document.getElementById('correspondenceStates').value="<?php echo $defaultStateId?>";
    document.getElementById('correspondenceCity').value="<?php echo $defaultCityId?>";
    /*END: function to populate correspondence states and countries based on session values*/

    /*START: function to populate permanent states and countries based on session values*/
    autoPopulate('<?php echo $defaultCountryId?>','states','Add','permanentStates','permanentCity');
    autoPopulate('<?php echo $defaultStateId?>','city','Add','permanentStates','permanentCity');
    
    document.getElementById('permanentCountry').value="<?php echo $defaultCountryId?>";
    document.getElementById('permanentStates').value="<?php echo $defaultStateId?>";
    document.getElementById('permanentCity').value="<?php echo $defaultCityId?>";
    /*END: function to populate permanent states and countries based on session values*/
    
    refereshData();
    return false;
}


function copyText(){

    if(document.addForm.sameText.checked==true){
         
        document.addForm.permanentAddress1.value    = document.addForm.correspondeceAddress1.value;
        document.addForm.permanentAddress1.disabled = true;

        document.addForm.permanentAddress2.value    = document.addForm.correspondeceAddress2.value;
        document.addForm.permanentAddress2.disabled = true;

        document.addForm.permanentPincode.value     = document.addForm.correspondecePincode.value;  
        document.addForm.permanentPincode.disabled  = true;

        document.addForm.permanentPhone.value       = document.addForm.correspondecePhone.value;
        document.addForm.permanentPhone.disabled    = true;

        for(i=document.addForm.correspondenceCountry.options.length-1;i>=0;i--){

            if(document.addForm.correspondenceCountry.options[i].selected)
                document.addForm.permanentCountry.options[i].selected=true;
        }
        var abc = (document.addForm.correspondenceStates.options[document.addForm.correspondenceStates.selectedIndex].text);

        document.addForm.permanentStates.options.length=0;
        var objOption = new Option(abc,"1");
        document.addForm.permanentStates.options.add(objOption); 

        var abc1 = (document.addForm.correspondenceCity.options[document.addForm.correspondenceCity.selectedIndex].text);
        document.addForm.permanentCity.options.length=0;
        var objOption = new Option(abc1,"1");
        document.addForm.permanentCity.options.add(objOption); 

        document.addForm.permanentCountry.disabled=true;
        document.addForm.permanentStates.disabled=true;
        document.addForm.permanentCity.disabled=true;
        
    }
    else{

        document.addForm.permanentAddress1.disabled=false;
        document.addForm.permanentAddress2.disabled=false;
        document.addForm.permanentPincode.disabled=false;

        document.addForm.permanentCountry.disabled=false;
        document.addForm.permanentStates.disabled=false;
        document.addForm.permanentCity.disabled=false;
        document.addForm.permanentPhone.disabled=false;

        document.addForm.permanentCountry.options[0].selected=true;

        document.addForm.permanentStates.options.length=0;
        var objOption = new Option("Select","");
        document.addForm.permanentStates.options.add(objOption); 

        document.addForm.permanentCity.options.length=0;
        var objOption = new Option("Select","");
        document.addForm.permanentCity.options.add(objOption); 
    }
}

function copyFatherText(){

     
    if((document.addForm.sameFatherText.checked==true)){
         
        document.addForm.motherQualification.value    = document.addForm.fatherQualification.value;
        //document.addForm.motherOccupation.disabled = true;

        document.addForm.motherEmail.value    = document.addForm.fatherEmail.value;
        //document.addForm.motherEmail.disabled = true;

        document.addForm.motherMobile.value     = document.addForm.fatherMobile.value;  
        //document.addForm.motherMobile.disabled  = true;

        document.addForm.motherAddress.value       = document.addForm.fatherAddress.value;
        //document.addForm.motherAddress1.disabled    = true;

        document.addForm.motherProfession.value       = document.addForm.fatherProfession.value;
        //document.addForm.motherAddress2.disabled    = true;

        document.addForm.motherContact.value       = document.addForm.fatherContact.value;
        //document.addForm.motherContact.disabled    = true;

        document.addForm.motherPincode.value       = document.addForm.fatherPincode.value;
        //document.addForm.motherPincode.disabled    = true;

        for(i=document.addForm.fatherCountry.options.length-1;i>=0;i--){

            if(document.addForm.fatherCountry.options[i].selected)
                document.addForm.motherCountry.options[i].selected=true;
        }
        var abc = (document.addForm.fatherStates.options[document.addForm.fatherStates.selectedIndex].text);

        document.addForm.motherStates.options.length=0;
        var objOption = new Option(abc,"1");
        document.addForm.motherStates.options.add(objOption); 

        var abc1 = (document.addForm.fatherCity.options[document.addForm.fatherCity.selectedIndex].text);
        document.addForm.motherCity.options.length=0;
        var objOption = new Option(abc1,"1");
        document.addForm.motherCity.options.add(objOption); 

        //document.addForm.motherCountry.disabled=true;
        //document.addForm.motherStates.disabled=true;
        //document.addForm.motherCity.disabled=true;
        
    }
    else{

        document.addForm.motherQualification.disabled=false;
        document.addForm.motherEmail.disabled=false;
        document.addForm.motherMobile.disabled=false;

        document.addForm.motherAddress.disabled=false;
        document.addForm.motherProfession.disabled=false;
        document.addForm.motherDesignation.disabled=false; 
        document.addForm.motherContact.disabled=false;
        document.addForm.motherCountry.disabled=false;
        document.addForm.motherStates.disabled=false;
        document.addForm.motherCity.disabled=false;
        document.addForm.motherPincode.disabled=false;
    }
}

function copyGuardianText(){

     
    if((document.addForm.sameFatherText1.checked==true)){
         
        document.addForm.sameMotherText.checked=false;

        document.addForm.guardianOccupation.value    = document.addForm.fatherOccupation.value;
        //document.addForm.guardianOccupation.disabled = true;

        document.addForm.guardianEmail.value    = document.addForm.fatherEmail.value;
        //document.addForm.guardianEmail.disabled = true;

        document.addForm.guardianMobile.value     = document.addForm.fatherMobile.value;  
        //document.addForm.guardianMobile.disabled  = true;

        document.addForm.guardianAddress1.value       = document.addForm.fatherAddress1.value;
        //document.addForm.guardianAddress1.disabled    = true;

        document.addForm.guardianAddress2.value       = document.addForm.fatherAddress2.value;
        //document.addForm.guardianAddress2.disabled    = true;

        document.addForm.guardianContact.value       = document.addForm.fatherContact.value;
        //document.addForm.guardianContact.disabled    = true;

        document.addForm.guardianPincode.value       = document.addForm.fatherPincode.value;
        //document.addForm.guardianPincode.disabled    = true;

        for(i=document.addForm.fatherCountry.options.length-1;i>=0;i--){

            if(document.addForm.fatherCountry.options[i].selected)
                document.addForm.guardianCountry.options[i].selected=true;
        }
        var abc = (document.addForm.fatherStates.options[document.addForm.fatherStates.selectedIndex].text);

        document.addForm.guardianStates.options.length=0;
        var objOption = new Option(abc,"1");
        document.addForm.guardianStates.options.add(objOption); 

        var abc1 = (document.addForm.fatherCity.options[document.addForm.fatherCity.selectedIndex].text);
        document.addForm.guardianCity.options.length=0;
        var objOption = new Option(abc1,"1");
        document.addForm.guardianCity.options.add(objOption); 

        //document.addForm.guardianCountry.disabled=true;
        //document.addForm.guardianStates.disabled=true;
        //document.addForm.guardianCity.disabled=true;
        
    }
    else{

        document.addForm.guardianOccupation.disabled=false;
        document.addForm.guardianEmail.disabled=false;
        document.addForm.guardianMobile.disabled=false;

        document.addForm.guardianAddress1.disabled=false;
        document.addForm.guardianAddress2.disabled=false;
        document.addForm.guardianContact.disabled=false;
        document.addForm.guardianCountry.disabled=false;
        document.addForm.guardianStates.disabled=false;
        document.addForm.guardianCity.disabled=false;
        document.addForm.guardianPincode.disabled=false;
 
    }
}
function copyGuardianMotherText(){

     
    if((document.addForm.sameMotherText.checked==true)){
        
        document.addForm.sameFatherText1.checked=false; 
        document.addForm.guardianOccupation.value    = document.addForm.motherOccupation.value;
        document.addForm.guardianOccupation.disabled = true;

        document.addForm.guardianEmail.value    = document.addForm.motherEmail.value;
        document.addForm.guardianEmail.disabled = true;

        document.addForm.guardianMobile.value     = document.addForm.motherMobile.value;  
        document.addForm.guardianMobile.disabled  = true;

        document.addForm.guardianAddress1.value       = document.addForm.motherAddress1.value;
        document.addForm.guardianAddress1.disabled    = true;

        document.addForm.guardianAddress2.value       = document.addForm.motherAddress2.value;
        document.addForm.guardianAddress2.disabled    = true;

        document.addForm.guardianContact.value       = document.addForm.motherContact.value;
        document.addForm.guardianContact.disabled    = true;

        document.addForm.guardianPincode.value       = document.addForm.motherPincode.value;
        document.addForm.guardianPincode.disabled    = true;

        for(i=document.addForm.motherCountry.options.length-1;i>=0;i--){

            if(document.addForm.motherCountry.options[i].selected)
                document.addForm.guardianCountry.options[i].selected=true;
        }
        var abc = (document.addForm.motherStates.options[document.addForm.motherStates.selectedIndex].text);

        document.addForm.guardianStates.options.length=0;
        var objOption = new Option(abc,"1");
        document.addForm.guardianStates.options.add(objOption); 

        var abc1 = (document.addForm.motherCity.options[document.addForm.motherCity.selectedIndex].text);
        document.addForm.guardianCity.options.length=0;
        var objOption = new Option(abc1,"1");
        document.addForm.guardianCity.options.add(objOption); 

        document.addForm.guardianCountry.disabled=true;
        document.addForm.guardianStates.disabled=true;
        document.addForm.guardianCity.disabled=true;
        
    }
    else{
        document.addForm.guardianOccupation.disabled=false;
        document.addForm.guardianEmail.disabled=false;
        document.addForm.guardianMobile.disabled=false;

        document.addForm.guardianAddress1.disabled=false;
        document.addForm.guardianAddress2.disabled=false;
        document.addForm.guardianContact.disabled=false;
        document.addForm.guardianCountry.disabled=false;
        document.addForm.guardianStates.disabled=false;
        document.addForm.guardianCity.disabled=false;
        document.addForm.guardianPincode.disabled=false;
    }
}
</script>
</head>
<body>
	<?php 
      require_once(TEMPLATES_PATH . "/header.php");
      require_once(TEMPLATES_PATH . "/PreAdmission/listPreAdmissionContents.php");
      require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<SCRIPT LANGUAGE="JavaScript">
    <!--
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
    //-->
</SCRIPT>       
</body>
</html>

