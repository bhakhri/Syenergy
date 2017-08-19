<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Student Registration Form
//
//
// Author :Parveen Sharma
// Created on : 12-June-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CourseRegistrationForm');
define('ACCESS','view');
UtilityManager::ifStudentNotLoggedIn();
include_once(BL_PATH ."/Student/initStudentInformation.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>: Course Registration Form</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');

function parseOutput($data){
  return ( (trim($data)!="" ? $data : NOT_APPLICABLE_STRING ) );  
}
?> 
<script language="javascript">
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'reppearStatus';
sortOrderBy    = 'ASC';
queryString = "";
notShowDelete = "";

var dtArray=new Array();      
var resourceElectiveAddCnt= new Array("0","0","0");
var resourceCareerAddCnt  = new Array("0","0","0");  
var bgclassElective = new Array("","","");
var bgclassCareer = new Array("","","");
                              
// check browser
var isMozilla = (document.all) ? 0 : 1;


// ========= Career OR Elective Course START ===========  

        function addElectiveOneRow(cnt,mode,courseType) {
            //document.getElementById('experienceResults').style.display = '';
            //document.getElementById('trExperience').style.display = '';
            if(courseType=='E') { 
                if(cnt=='')
                cnt=1;
                
                if(isMozilla){
                  if(document.getElementById('anyidBodyElective'+mode).childNodes.length <= 3){
                    resourceElectiveAddCnt[mode]=0; 
                  }       
                }
                else{
                  if(document.getElementById('anyidBodyElective'+mode).childNodes.length <= 1){
                    resourceElectiveAddCnt[mode]=0; 
                  }       
                }                      
                resourceElectiveAddCnt[mode]++;  
                id="electiveId"+mode;
                createElectiveRows(resourceElectiveAddCnt[mode],cnt,mode,eval("document.getElementById('"+id+"').innerHTML"),courseType);
            }
            else if(courseType=='C') { 
                if(cnt=='')
                cnt=1;
                
                if(isMozilla){
                  if(document.getElementById('anyidBodyCareer'+mode).childNodes.length <= 3){
                    resourceCareerAddCnt[mode]=0; 
                  }       
                }
                else{
                  if(document.getElementById('anyidBodyCareer'+mode).childNodes.length <= 1){
                    resourceCareerAddCnt[mode]=0; 
                  }       
                }                                  
                resourceCareerAddCnt[mode]++;      
                id="careerId"+mode;
                createElectiveRows(resourceCareerAddCnt[mode],cnt,mode,eval("document.getElementById('"+id+"').innerHTML"),courseType);
            }
        }

        function createElectiveRows(start,rowCnt,mode,subjectData,courseType){
                
              if(courseType=='E') {
                courseValue='Elective';
              }
              else if(courseType=='C') {
                courseValue='Career';
              }
                 
                 
              id1='anyid'+courseValue+mode;
              id2='anyidBody'+courseValue+mode;   
              
              var tbl=document.getElementById(id1);
              var tbody = document.getElementById(id2);
                     
              for(var i=0;i<rowCnt;i++) {
                  
                  var tr=document.createElement('tr');
                  
                  id1='row'+courseValue+mode+'_'+parseInt(start+i,10); 
                  tr.setAttribute('id',id1);
                  
                  var cell1=document.createElement('td');  
                  var cell2=document.createElement('td');
                  var cell3=document.createElement('td'); 
                  var cell4=document.createElement('td'); 
                 
                  cell1.setAttribute('align','left');  
                  if(courseType=='E') { 
                    cell1.name='srNoE1'+mode;
                  }
                  else if(courseType=='C') { 
                    cell1.name='srNoC1'+mode;
                  }
                  cell2.setAttribute('align','left');     
                  cell3.setAttribute('align','left'); 
                  cell4.setAttribute('align','center'); 
                   
                  if(start==0){
                    var txt0=document.createTextNode(start+i+1);
                  }
                  else{
                    var txt0=document.createTextNode(start+i);
                  }
                 // var txt0=document.createTextNode(i+1);
                  
                  var txt1=document.createElement('select');
                  
                  var txt2=document.createElement('input');
                  var txt3=document.createElement('a');
                  
                  if(courseType=='E') {  
                    id1 = "electiveCourseId"+mode;
                    id2 = "electiveCourseId"+mode+'[]';
                  }
                  else if(courseType=='C') {  
                    id1 = "careerCourseId"+mode;
                    id2 = "careerCourseId"+mode+'[]';  
                  }
                  txt1.setAttribute('id',id1+parseInt(start+i,10));
                  txt1.setAttribute('style','width:270px');
                  txt1.setAttribute('name',id2); 
                  txt1.className='htmlElement';
                  
                  if(courseType=='E') { 
                    txt1.setAttribute('onchange',"getCredits(this,'E',"+mode+")");  
                    id1 = "electiveCreditId"+mode;
                    id2 = "electiveCreditId"+mode+'[]';
                  }
                  else if(courseType=='C') { 
                    txt1.setAttribute('onchange',"getCredits(this,'C',"+mode+")");  
                    id1 = "careerCreditId"+mode;
                    id2 = "careerCreditId"+mode+'[]';  
                  }
                  
                  txt2.setAttribute('id',id1+parseInt(start+i,10));
                  txt2.setAttribute('name',id2);
                  txt2.setAttribute('maxlength','"3"');      
                  txt2.className='inputbox1';
                  txt2.setAttribute('size','"5"');
                  txt2.setAttribute('type','text');

                  showId = document.getElementById('showId').value; 
                  
                  txt3.setAttribute('id','rd');
                  txt3.className='inputbox1';  
                  
                  if(showId==0) {
                    txt3.innerHTML='<?php echo NOT_APPLICABLE_STRING; ?>';       
                  }
                  else if(notShowDelete=="") {
                    txt3.setAttribute('title','Delete');       
                    txt3.innerHTML='X';
                    txt3.style.cursor='pointer';
                    txt3.setAttribute('href','javascript:deleteElectiveRow("'+parseInt(start+i,10)+'~0",'+mode+',"'+courseType+'")');  //for ie and ff    
                  }
                  else {
                    //txt3.style.cursor='pointer'; 
                    txt3.innerHTML='<?php echo NOT_APPLICABLE_STRING; ?>';      
                  }
                  
                  cell1.appendChild(txt0);      
                  cell2.appendChild(txt1);
                  cell3.appendChild(txt2);
                  cell4.appendChild(txt3);
                          
                  tr.appendChild(cell1);                
                  tr.appendChild(cell2);
                  tr.appendChild(cell3);
                  tr.appendChild(cell4);

             
                  if(courseType=='E') { 
                    bgclassElective[mode]=(bgclassElective[mode]=='row0'? 'row1' : 'row0');
                    tr.className=bgclassElective[mode];
                  }
                  else if(courseType=='C') { 
                    bgclassCareer[mode]=(bgclassCareer[mode]=='row0'? 'row1' : 'row0');
                    tr.className=bgclassCareer[mode];
                  }
                  
                  tbody.appendChild(tr); 
                  
                  // add Subject List
                  if(courseType=='E') {  
                    id = "electiveId"+mode;
                  }
                  else if(courseType=='C') {  
                    id = "careerId"+mode;  
                  }
                  var len= eval("document.getElementById('"+id+"').options.length");
                  var t=eval("document.getElementById('"+id+"')");
                  // add option Select initially
                  if(len>0) {
                    if(courseType=='E') {  
                      id1 = "electiveCourseId"+mode;
                    }
                    else if(courseType=='C') {  
                      id1 = "careerCourseId"+mode;  
                    }  
                    var tt=id1+parseInt(start+i,10) ; 
                    //alert(eval("document.getElementById(tt).length"));
                    for(k=0;k<len;k++) { 
                      addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
                     }
                  } 
                  
             } 
             tbl.appendChild(tbody);    
         }
         
         //for deleting a row from the table 
         function deleteElectiveRow(value,mode,courseType){
             
            try {
              var rval=value.split('~');
              if(courseType=='E') {  
                  var tbody = document.getElementById('anyidBodyElective'+mode);
                  id="rowElective"+mode+"_"+rval[0];
                  var tr=eval("document.getElementById('"+id+"')");
                  tbody.removeChild(tr);
                  
                  rowElectiveCalculate(mode,courseType);
                  
                  if(isMozilla){
                      if((tbody.childNodes.length-3)==0) {
                        resourceElectiveAddCnt[mode]=0;
                      }
                  }
                  else{
                      if((tbody.childNodes.length-1)==0){
                        resourceElectiveAddCnt[mode]=0;
                      }
                  }
              }
              else if(courseType=='C') {  
                  var tbody = document.getElementById('anyidBodyCareer'+mode);
                  id="rowCareer"+mode+"_"+rval[0];
                  var tr=eval("document.getElementById('"+id+"')");
                  tbody.removeChild(tr);
                  rowElectiveCalculate(mode,courseType); 
                  if(isMozilla){
                      if((tbody.childNodes.length-3)==0){
                        resourceCareerAddCnt[mode]=0;
                      }
                  }
                  else{
                      if((tbody.childNodes.length-1)==0){
                        resourceCareerAddCnt[mode]=0;
                      }
                  }
              }
            }
            catch (e) {
               alert(e); 
            }
         }

         function rowElectiveCalculate(mode,courseType) {
              
              var bgclass='';
             
              if(courseType=='E') {
                  var a=document.getElementsByTagName('td');
                  var l=a.length;
                  var j=1;
                  for(var i=0;i<l;i++){     
                    if(a[i].name=='srNoE1'+mode) {
                      bgclass=(bgclass=='row0'? 'row1' : 'row0');
                      bgclassElective[mode]=bgclass;
                      a[i].parentNode.className=bgclass;
                      a[i].innerHTML=j;
                      j++;
                    }
                  }
              }
              else if(courseType=='C') {  
                  var a=document.getElementsByTagName('td');
                  var l=a.length;
                  var j=1;
                  for(var i=0;i<l;i++){     
                    if(a[i].name=='srNoC1'+mode){
                      bgclass=(bgclass=='row0'? 'row1' : 'row0');
                      bgclassCareer[mode]=bgclass;
                      a[i].parentNode.className=bgclass;
                      a[i].innerHTML=j;
                      j++;
                    }
                  }
                  resourceCareerAddCnt[mode]=j-1; 
              }
        }
// ========= Career OR Elective Course START ===========  

function getCredits(credit,mode,termId) {
   var id = credit.id;
   var c = credit.value;
   var rval=c.split('~'); 
   
   
   if(mode=='C') {    
      var ids = id.split('careerCourseId');
      var v = "careerCreditId"+ids[1];
      var v1 =  "careerCourseId"+ids[1];
      
      subjectId = eval("document.getElementById('"+v1+"').value");
      if (typeof rval[1] === "undefined") {
        eval("document.getElementById('"+v+"').value=0");
      }
      else {
        eval("document.getElementById('"+v+"').value=4");
      }
   }
   else if(mode=='E') {    
     var ids = id.split('electiveCourseId');
     var v = "electiveCreditId"+ids[1];
     var v1 = "electiveCourseId"+ids[1];
     
     subjectId = eval("document.getElementById('"+v1+"').value");
     if (typeof rval[1] === "undefined") {
       eval("document.getElementById('"+v+"').value=0");  
     }
     else {
       eval("document.getElementById('"+v+"').value=4");
     }
   }
   
   url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxSubjectCredits.php';
   new Ajax.Request(url,
   {
         method:'post',
         parameters: {id: termId,
                      subjectId : subjectId,
                      currentClassId: document.getElementById('currentClassId').value
                     },
         asynchronous:false,                     
         onSuccess: function(transport){
           if(transport.readyState==0 || transport.readyState==1 || transport.readyState==2 || transport.readyState==3 ) {
              showWaitDialog(true);
           }
           else {
               hideWaitDialog(true);
               j = eval('('+transport.responseText+')');
               
               if(mode=='C') {
                 ids = id.split('careerCourseId');
                 v = "careerCreditId"+ids[1];
                 v1 =  "careerCourseId"+ids[1];
                 if (typeof j.credits === "undefined") {
                   eval("document.getElementById('"+v+"').value=''");  
                 }
                 else {
                   eval("document.getElementById('"+v+"').value=j.credits"); 
                 }
               }
               else if(mode=='E') {
                 ids = id.split('electiveCourseId');
                 v = "electiveCreditId"+ids[1];
                 if (typeof j.credits === "undefined") {
                   eval("document.getElementById('"+v+"').value=''");  
                 }
                 else {
                   eval("document.getElementById('"+v+"').value=j.credits"); 
                 }
               }
           }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
   });
   
}

function checkDuplicateSubject(value) {
    var i= dtArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(dtArray[k]==value){
        fl=0;
        break;
      }  
    }
    if(fl==1){
      dtArray.push(value);
    } 
    return fl;
}

function validateAddForm(frm,confirmId) {
  
    if(confirmId=='Y') {
       msg = confirm("<?php echo "Are you sure confirm registration"; ?>");
       if(msg == false) {
          return false;      
       }
    }
    
    dtArray.splice(0,dtArray.length); //empty the array   
    
    var formx = document.allDetailsForm; 
    formx.cgpa.value = trim(formx.cgpa.value);
    formx.majorConcentration.value = trim(formx.majorConcentration.value);
        
    if(formx.cgpa.value != "") {
        /*if(formx.cgpa.value == "") {
           messageBox ("Enter Cumulative Grade Point Average(CGPA)");
           formx.cgpa.focus();
           return false;             
        } */
       
        // Integer Value Checks updated
        if(!isDecimal(formx.cgpa.value)) {
           messageBox ("Enter numeric value for Cumulative Grade Point Average(CGPA)"); 
           formx.cgpa.focus();
           return false;
        }
        
        if(parseFloat(formx.cgpa.value,2) < 0 || parseFloat(formx.cgpa.value,2) >10 ) {
          messageBox ("Enter Cumulative Grade Point Average(CGPA) between 0 to 10"); 
          formx.cgpa.focus();
          return false;
        }  
    }
 
 /* if(formx.majorConcentration.value == "") {
      messageBox ("Enter Major Concentration");
      formx.majorConcentration.focus();
      return false;             
    } */

    for(ii=0;ii<3;ii++) {
      rowElectiveCalculate((ii+1),'C');
      rowElectiveCalculate((ii+1),'E');
    }          
    
    var objSelect=formx.getElementsByTagName('SELECT');  
    var totalSelect=objSelect.length;
    var find=0; 
    for(i=0;i<3;i++) {
        for(var j=0;j<totalSelect;j++) {
           idSelect = "careerCourseId"+(i+1)+"[]";
           if(objSelect[j].type.toUpperCase()=='SELECT-ONE' && eval("objSelect[j].name.indexOf('"+idSelect+"')") >-1) {
               find=1;
               break;
           }
           idSelect = "electiveCourseId"+(i+1)+"[]";
           if(objSelect[j].type.toUpperCase()=='SELECT-ONE' && eval("objSelect[j].name.indexOf('"+idSelect+"')") >-1) {
              find=1;
              break;
           }
        }
        if(find==1) {
          break;  
        }
    }
    
    if(find==0) {
          if(document.getElementById('editId').value==0) {
             messageBox ("Please fill registration form"); 
             return false;
          }  
          //msg = confirm('All values for this attendance set will be deleted. Are you sure?')
          confirmId = 'N';
          msg = confirm("<?php echo REGISTRATION_CANCEL; ?>");
          if(msg == false) {
            populateStudentRegistrationValue();
            for(ii=0;ii<3;ii++) {
              rowElectiveCalculate((ii+1),'C');
              rowElectiveCalculate((ii+1),'E');
            }    
            return false;
          }
          addStudentRegistration(confirmId); 
          return false;
    }
    else {
       a=courseCheck(totalSelect,'careerCourseId','careerCreditId','Career');
       if(a==false) {
         return false;  
       }
       b=courseCheck(totalSelect,'electiveCourseId','electiveCreditId','Elective');
       if(b==false) {
         return false;  
       }
    }  // if condition end          
    
    if(a==true && b==true) {
      addStudentRegistration(confirmId); 
    }
    return false;
}


function courseCheck(tot,courseId,creditId,msg) {
    
       var formx = document.allDetailsForm; 
       // Select Career Course  
       for(i=0;i<tot;i++) {   
          var obj=formx.getElementsByTagName('INPUT');
          var total=obj.length;
          var objSelect=formx.getElementsByTagName('SELECT');  
          var totalSelect=objSelect.length;
          var x=0;
          var y=0;
          for(var j=0;j<totalSelect;j++) {
              idSelect = courseId+(i+1)+"[]";
              if(objSelect[j].type.toUpperCase()=='SELECT-ONE' && eval("objSelect[j].name.indexOf('"+idSelect+"')") >-1) {
                 // blank value check 
                 if(trim(objSelect[j].value) == "") {
                   messageBox ("Select "+msg+" Courses");
                   objSelect[j].focus();
                   return false;             
                 }
                 if(!checkDuplicateSubject(objSelect[j].value)){
                    msg1 = "<?php echo " Course already selected"; ?>"; 
                    messageBox(msg1);
                    objSelect[j].focus();
                    return false;
                 } 
              }
          }
          for(var j=0;j<total;j++) {
              id = creditId+(i+1)+"[]";
              if(obj[j].type.toUpperCase()=='TEXT' && eval("obj[j].name.indexOf('"+id+"')") >-1) {
                   obj[j].value = trim(obj[j].value);
                   if(trim(obj[j].value) == "") {
                     messageBox ("Enter "+msg.toLowerCase()+" course credits");
                     obj[j].focus();
                     return false;             
                   }
                   
                   // Integer Value Checks updated
                   if(!isDecimal(trim(obj[j].value))) {
                     messageBox ("Enter the numeric value for "+msg.toLowerCase()+" course credits from (0 to 10)");
                     obj[j].focus();
                     return false;
                   }
                   
                   // Ranges Checks 
                   if(parseFloat(obj[j].value,2) < 0 || parseFloat(obj[j].value,2) >10 ) {
                     messageBox ("Enter the numeric value for "+msg.toLowerCase()+" course credits from (0 to 10)");
                     obj[j].focus();
                     return false;
                   }
                }
            }
         }
         return true; 
}

function addStudentRegistration(confirmId) {
   
   url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxInitAddRegistration.php';
   
   document.getElementById('confirmId').value = confirmId;
   
   params = generateQueryString('allDetailsForm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        if("<?php echo REGISTRATION_SUBMITTED;?>" == trim(transport.responseText)) {  
            messageBox(trim(transport.responseText));  
            //document.getElementById('editId').value=1;
            //populateStudentRegistrationValue();
            location.reload();
        }
        else if("<?php echo REGISTRATION_DELETE;?>" == trim(transport.responseText)) {  
            document.getElementById('cgpa').value=''; 
            document.getElementById('majorConcentration').value='';
            messageBox(trim(transport.responseText));
            //document.getElementById('editId').value=0;
            //populateStudentRegistrationValue();
            location.reload();
        }
        else if("<?php echo REGISTRATION_UPDATED;?>" == trim(transport.responseText)) {  
            messageBox(trim(transport.responseText));
            //document.getElementById('editId').value=1;                   
            //populateStudentRegistrationValue();
            location.reload();
        }
        else {
            messageBox(trim(transport.responseText));
            return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}

function populateStudentRegistrationValue() {
    
	try 
	{
    var url = '<?php echo HTTP_LIB_PATH;?>/Student/ajaxGetRegistrationValue.php';   
    
    document.getElementById("nameRowId").style.display='';   
    
    studentId=0;
    if(document.getElementById('studentId') == null) {  
      studentId =0;  
    }
    else {
      studentId = document.getElementById('studentId').value;
    }
    
    showId = document.getElementById('showId').value; 
    
    notShowDelete="";
    
    new Ajax.Request(url,
    {
         method:'post',
         parameters: { studentId: studentId },
         asynchronous:false,     
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                var jj = eval('(' + transport.responseText + ')');         
                len = jj.length;
                if(len>0) {
                   if(jj[0]['confirmId']=='Y') {
                     document.getElementById("nameRowId").style.display='none';   
                     document.getElementById('regDate1').innerHTML = customParseDate(jj[0]['regDate'],'-');
                     document.getElementById('confirmId').value = jj[0]['confirmId'];
                     notShowDelete="1";
                   }
                   document.getElementById('cgpa').value = jj[0]['cgpa'];
                   document.getElementById('majorConcentration').value = jj[0]['majorConcentration']; 
                   totalClass = document.getElementById('totalClass').value;
                   
                   if(notShowDelete=="1") {
                     document.getElementById('cgpa').disabled=true;  
                     document.getElementById('majorConcentration').disabled=true;  
                     document.getElementById('totalClass').disabled=true;  
                   }
                   
                   if(showId==0) {
                     document.getElementById('cgpa').disabled=true;     
                     document.getElementById('majorConcentration').disabled=true;   
                   }
                   
                   document.getElementById('editId').value=1; 
                   for(xx=0;xx<parseInt(totalClass);xx++) {
                      cnt1=1; 
                      tId='tClassId'+(xx+1);   
                      tclassId=eval("document.getElementById('"+tId+"').value"); 
                      for(yy=0;yy<len;yy++) {
                        if(tclassId==jj[yy]['classId'] && jj[yy]['subjectType']=='Career') {
                           addElectiveOneRow(1,(xx+1),'C'); 
                           courseId='careerCourseId'+(xx+1)+cnt1;
                           credits = 'careerCreditId'+(xx+1)+cnt1;
                           eval("document.getElementById('"+courseId+"').value = jj[yy]['subjectId']");
                           eval("document.getElementById('"+credits+"').value = jj[yy]['credits']");
                           if(notShowDelete=="1") { 
                             eval("document.getElementById('"+courseId+"').disabled=true;");   
                             eval("document.getElementById('"+credits+"').disabled=true;");   
                           }
                           
                           if(showId==0) {
                             eval("document.getElementById('"+courseId+"').disabled=true;");   
                             eval("document.getElementById('"+credits+"').disabled=true;");     
                           }
                           cnt1++;
                        }
                      } 
                      rowElectiveCalculate((i+1),'C');
                      cnt1=1; 
                      for(yy=0;yy<len;yy++) {    
                        if(tclassId==jj[yy]['classId'] && jj[yy]['subjectType']=='Elective') {
                           addElectiveOneRow(1,(xx+1),'E'); 
                           courseId='electiveCourseId'+(xx+1)+cnt1;
                           credits = 'electiveCreditId'+(xx+1)+cnt1;
                           eval("document.getElementById('"+courseId+"').value = jj[yy]['subjectId']");
                           eval("document.getElementById('"+credits+"').value = jj[yy]['credits']");
                           if(notShowDelete=="1") { 
                             eval("document.getElementById('"+courseId+"').disabled=true;");   
                             eval("document.getElementById('"+credits+"').disabled=true;");   
                           }
                           
                           if(showId==0) {  
                             eval("document.getElementById('"+courseId+"').disabled=true;");   
                             eval("document.getElementById('"+credits+"').disabled=true;");   
                           }
                           cnt1++;
                        }
                      }
                      rowElectiveCalculate((i+1),'E'); 
                   }
               }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
	}catch(e) { };

}

window.onload=function(){  
   populateStudentRegistrationValue(); 
}

function printReport() {
    path='<?php echo UI_HTTP_PATH;?>/Student/courseRegistrationReport.php';
    window.open(path,"CourseRegistraionReportPrint","status=1,menubar=1,scrollbars=1, width=900");
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Student/listCourseRegistrationContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
//$History: studentInternalReappearForm.php $
//
//*****************  Version 12  *****************
//User: Parveen      Date: 1/28/10    Time: 5:40p
//Updated in $/LeapCC/Interface/Student
//validation & format update (button & radio button updated)
//
//*****************  Version 11  *****************
//User: Parveen      Date: 1/19/10    Time: 6:27p
//Updated in $/LeapCC/Interface/Student
//function & validation message and format updated
//
//*****************  Version 10  *****************
//User: Parveen      Date: 1/15/10    Time: 5:35p
//Updated in $/LeapCC/Interface/Student
//format and validation updated
//
//*****************  Version 9  *****************
//User: Parveen      Date: 1/15/10    Time: 12:32p
//Updated in $/LeapCC/Interface/Student
//validation & sorting format updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 1/15/10    Time: 10:03a
//Updated in $/LeapCC/Interface/Student
//format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 1/14/10    Time: 5:37p
//Updated in $/LeapCC/Interface/Student
//page title updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 1/14/10    Time: 2:43p
//Updated in $/LeapCC/Interface/Student
//validation format update
//
//*****************  Version 5  *****************
//User: Parveen      Date: 1/14/10    Time: 2:15p
//Updated in $/LeapCC/Interface/Student
//checks updated
//
//*****************  Version 4  *****************
//User: Parveen      Date: 1/13/10    Time: 2:12p
//Updated in $/LeapCC/Interface/Student
//subjectId base checks updated
//
//*****************  Version 3  *****************
//User: Parveen      Date: 1/12/10    Time: 5:26p
//Updated in $/LeapCC/Interface/Student
//validation message updated
//
//*****************  Version 2  *****************
//User: Parveen      Date: 1/09/10    Time: 5:22p
//Updated in $/LeapCC/Interface/Student
//look & feel updated
//
//*****************  Version 1  *****************
//User: Parveen      Date: 1/09/10    Time: 1:04p
//Created in $/LeapCC/Interface/Student
//initial checkin
//

?>
