<?php
//-------------------------------------------------------
//  This file shows list of all the candidates with thier status after performing Algorithem and have a funcationality of performing Algorithem 
//
// Author : Vimal Sharma
// Created on : (11.02.2009 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CandidateStatus');
define('ACCESS','view');
UtilityManager::ifAdmissionNotLoggedIn();
//require_once(BL_PATH . "/Admission/initCandidateStatusList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Candidate Status </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
//                    new Array('fatherGuardianName','Father/Guardian','width="15%"','',true) ,
//                    new Array('relationWithCandidate','Relation','width="15%"','',true) ,
var tableHeadArray = new Array(
new Array('srNo','#','width="3%"','',false), 
                    new Array('candidateName','Candidate Name','width=27%','',true) , 
                    new Array('fatherGuardianName','Father/Guardian','width="15%"','',false) , 
                    new Array('relationWithCandidate','Relation','width="15%"','',false) ,                    
                    new Array('formNo','Form No','width="10%"','',true), 
                    new Array('AIEEERollNo','AIEEE RollNo','width="15%"','',true) , 
                    new Array('AIEEERank','AIEEE Rank','width="15%"','',true) ,
                    new Array('dateOfBirth','DOB','width="15%"','',true) ,
                    new Array('programName','Program','width="10%"','',false), 
                    new Array('preference','Preference','width="10%"','',false),
                    new Array('candidateStatus','Status','width="10%"','',false),
                    new Array('actionStatus','Action','width="10%"','align="right"',false));
//, 
//                    new Array('action','Action','width="10%"','align="right"',false)                    
//var candidateGraduatePref = new Array();
//var candidatePostGraduatePref = new Array();

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Admission/ajaxInitCandidateStatusList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
//addFormName    = 'addForm';   
//editFormName   = 'editForm';
winLayerWidth  = 715; //  add/edit form width
winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteCandidate';
divResultName   = 'results';
page            =1; //default page
sortField       = ' acs.displayOrder, aaf.AIEEERank';
sortOrderBy    = ' ASC';
candidateId    = 0;
//graduatePrefTotal = <?php echo $graduatePrefTotal;?>;
//postGraduatePrefTotal = <?php echo $postGraduatePrefTotal;?>;

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
function editWindow(id,dv,w,h) {
    displayWindow(dv,w,h);
    populateValues(id);
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCandidate" DIV
//--------------------------------------------------------
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/ajaxGetCandidateStatusValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {candidateId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('editForm');
                        messageBox("<?php echo CANDIDATE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');          
                    candidateId                                     =  j.candidateInfo[0].candidateId; 
                    document.editForm.formNo.value                  = j.candidateInfo[0].formNo;
                    document.editForm.candidateName.value           = j.candidateInfo[0].candidateName;
                    document.editForm.fatherGuardianName.value      = j.candidateInfo[0].fatherGuardianName;
                    document.editForm.relationWithCandidate.value   = j.candidateInfo[0].relationWithCandidate;
                    document.editForm.candidateCategory.value       = j.candidateInfo[0].quotaId;
                    document.editForm.genderRadio.value             = j.candidateInfo[0].candidateGender;
                    document.editForm.dob.value                     = j.candidateInfo[0].dateOfBirth;
                    document.editForm.AIEEERollNo.value             = j.candidateInfo[0].AIEEERollNo;    
                    document.editForm.AIEEERank.value               = j.candidateInfo[0].AIEEERank;     
                    document.editForm.candidateMobile.value         = j.candidateInfo[0].candidateMobileNo;
                    document.editForm.fatherGuardianMobile.value    = j.candidateInfo[0].fatherGuardianMobileNo;
                    document.editForm.hostelFacility.value          = j.candidateInfo[0].hostelFacility; 
                    document.editForm.candidateEmail.value          = j.candidateInfo[0].candidateEmail; 
                    document.editForm.candidateStatusType.value     = j.candidateInfo[0].candidateStatus;
                    document.editForm.programAlloted.value          = j.candidateInfo[0].programId;
                   // alert(document.editForm.candidateStatusType.value +  document.editForm.programAlloted.value);
                   // document.editForm.programType.value             = j.candidateInfo[0].programType; 
                    objArray = j.prefInfo;
                  //  alert(j.candidateProgPref['preference']);
                    //return();
  /*                  
                    initArray();
                    if (document.editForm.programType.value == 'G') {
                        for(i=0; i< objArray.length; i++) {
                            candidateGraduatePref[objArray[i].preference] = objArray[i].programId;
                        }                                  
                    } else {
                        for(i=0; i< postGraduatePrefTotal; i++) {
                            //candidatePostGraduatePref[i] = objArray[i].programId; 
                            candidatePostGraduatePref[objArray[i].preference] = objArray[i].programId;
                        }    
                    }                    
    */            
 //                   showPreference(document.editForm.programType,'Edit'); 
                    document.editForm.formNo.focus();         
                },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//-------------------------------------------------------
//THIS FUNCTION IS USED TO REGENERATE Candidate Status List
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function regenerate() {

    blankDivs();
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/regenerateCandidateStatus.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters:{id:'1'} ,
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                        messageBox(trim(transport.responseText));  
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     } else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}
function  blankDivs() {   
    document.getElementById('results').innerHTML=''; 
    document.getElementById('nameRow').style.display='none';
    
    //document.getElementById('results').style.display='none';  
}

function  showDivs() {   
    document.getElementById('nameRow').style.display='';
}


//-------------------------------------------------------
//THIS FUNCTION IS USED TO DISPLAY ADD AND EDIT DIVS
//
//id:id of the div
//dv:name of the form
//w:width of the div
//h:height of the div
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
//function editWindow(id,dv,w,h) {
//    displayWindow(dv,w,h);
//    populateValues(id);
//}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO VALIDATE FORM INPUTS
//
//frm:form to be validated
//act:type of operations(Add/Edit)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

function validateAddForm(frm, act) {
/*    var fieldsArray = new Array(new Array("formNo","<?php echo ENTER_FROM_NUMBER?>"),new Array("candidateName","<?php echo ENTER_CANDIDATE_NAME?>"),new Array("fatherGuardianName","<?php echo ENTER_FATHER_GUARDIAN_NAME?>"),new Array("candidateCategory","<?php echo STUDENT_CATEGORY ?>"),new Array("AIEEERollNo","<?php echo ENTER_AIEEE_ROLLNO ?>"),new Array("AIEEERollNo","<?php echo ENTER_AIEEE_ROLLNO ?>"),new Array("AIEEERank","<?php echo ENTER_AIEEE_RANK ?>"),new Array("candidateEmail","<?php echo ENTER_CANDIDATE_EMAIL ?>"));
    var len = fieldsArray.length;
  //  var frm = document.addForm;
    for(i=0;i<len;i++) {

         if(isEmpty(document.getElementById(fieldsArray[i][0]).value) ) {
            messageBox(fieldsArray[i][1],fieldsArray[i][0]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
        else {
            unsetAlertStyle(fieldsArray[i][0]);
        }
    }      

    if(document.getElementById('candidateMobile').value){
        if(!isPhone(eval("frm."+('candidateMobile')+".value"))){  
            messageBox("<?php echo ENTER_VALID_MOBILE?>");
            eval("frm."+('candidateMobile')+".focus();");
            return false;
        }
    }
    if(document.getElementById('fatherGuardianMobile').value){

        if(!isPhone(eval("frm."+('fatherGuardianMobile')+".value"))){  

            messageBox("<?php echo ENTfER_VALID_MOBILE?>");
            eval("frm."+('fatherGuardianMobile')+".focus();");
            return false;
        }
    }

        if(!isEmail(document.getElementById("candidateEmail").value)) {
         messageBox("<?php echo ENTER_VALID_CANDIDATE_EMAIL ?>");
         eval("frm."+('candidateEmail')+".focus();");
         return false;
    }    

    //addApplicationForm();
    //return false;           */
    if(act=='Add') {
        addCandidate();
        return false;
    }
    else if(act=='Edit') {
        editCandidate();
        return false;
    }
}    

//-------------------------------------------------------
//THIS FUNCTION IS USED TO ADD A NEW Candidate
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
/*
function addCandidate() {
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/initAddApplicationForm.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: $('addForm').serialize(true),
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
                             blankValues();
                         }
                        else if("<?php echo CANDIDATE_ALREADY_EXIST;?>" == trim(transport.responseText)){
                         messageBox("<?php echo CANDIDATE_ALREADY_EXIST ;?>"); 
                         document.addForm.candidateName.focus();
                        }                                                      
                         else {
                             hiddenFloatingDiv('AddCandidate');
                             sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                             //location.reload();
                             return false;
                         }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }
             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}
*/


// This function is used to refresh values of form.

function blankValues()
{
/*    document.addForm.formNo.value = '';
    document.addForm.candidateName.value = '';
    document.addForm.fatherGuardianName.value = '';
    document.addForm.relationWithCandidate.value = 'F';
    document.addForm.candidateCategory.value = '';
    document.addForm.genderRadio.value = 'M';
    document.addForm.dob.value = '';    
    document.addForm.AIEEERollNo.value = '';    
    document.addForm.AIEEERank.value = '';     
    document.addForm.candidateMobile.value = '';
    document.addForm.fatherGuardianMobile.value = '';
    document.addForm.hostelFacility.value = '0'; 
    document.addForm.candidateEmail.value = ''; 
    document.addForm.programType.value = 'G';  
    showPreference(document.addForm.programType);
    document.addForm.formNo.focus();    
    */  
} 


//-------------------------------------------------------
//THIS FUNCTION IS USED TO EDIT A CANDIDATE
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
function editCandidate() {
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/ajaxCandidateStatusEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {candidateStatusType: (document.editForm.candidateStatusType.value), programAlloted: (document.editForm.programAlloted.value), candidateId:(candidateId)},
             onCreate: function(){
                 showWaitDialog();
             },
             onSuccess: function(transport){
                 hideWaitDialog();
                 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                     hiddenFloatingDiv('editForm');
                     messageBox(trim(transport.responseText)); 
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
function editCandidateold() {
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/ajaxCandidateStatusEdit.php';
         new Ajax.Request(url,
           {
             method:'post',
            // parameters: $('editForm').serialize(true), 
            parameters: {candidateStatusType: (document.editForm.candidateStatusType.value), programAlloted: (document.editForm.programAlloted.value), candidateId:(candidateId)},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         hiddenFloatingDiv('editForm');
                         messageBox("<?php echo SUCCESS ;?>");
                       //  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                         //location.reload();
                     } else {
                        messageBox(trim(transport.responseText));                         
                     }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") } 
           });
}

//-------------------------------------------------------
//THIS FUNCTION IS USED TO POPULATE "editCandidate" DIV
//--------------------------------------------------------
/*
function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Admission/ajaxGetCandidateValues.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {candidateId: id},
             onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                    if(trim(transport.responseText)==0) {
                        hiddenFloatingDiv('editForm');
                        messageBox("<?php echo CANDIDATE_NOT_EXIST; ?>");
                        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);                        
                        //return false;
                   }
                    j = eval('('+transport.responseText+')');          
                
                    document.editForm.formNo.value                  = j.candidateInfo[0].formNo;
                    document.editForm.candidateName.value           = j.candidateInfo[0].candidateName;
                    document.editForm.fatherGuardianName.value      = j.candidateInfo[0].fatherGuardianName;
                    document.editForm.relationWithCandidate.value   = j.candidateInfo[0].relationWithCandidate;
                    document.editForm.candidateCategory.value       = j.candidateInfo[0].quotaId;
                    document.editForm.genderRadio.value             = j.candidateInfo[0].candidateGender;
                    document.editForm.dob.value                     = j.candidateInfo[0].dateOfBirth;
                    document.editForm.AIEEERollNo.value             = j.candidateInfo[0].AIEEERollNo;    
                    document.editForm.AIEEERank.value               = j.candidateInfo[0].AIEEERank;     
                    document.editForm.candidateMobile.value         = j.candidateInfo[0].candidateMobileNo;
                    document.editForm.fatherGuardianMobile.value    = j.candidateInfo[0].fatherGuardianMobileNo;
                    document.editForm.hostelFacility.value          = j.candidateInfo[0].hostelFacility; 
                    document.editForm.candidateEmail.value          = j.candidateInfo[0].candidateEmail; 
                    document.editForm.programType.value             = j.candidateInfo[0].programType; 
                    objArray = j.prefInfo;
                  //  alert(j.candidateProgPref['preference']);
                    //return();
                    
                    initArray();
                    if (document.editForm.programType.value == 'G') {
                        for(i=0; i< objArray.length; i++) {
                            candidateGraduatePref[objArray[i].preference] = objArray[i].programId;
                        }                                  
                    } else {
                        for(i=0; i< postGraduatePrefTotal; i++) {
                            //candidatePostGraduatePref[i] = objArray[i].programId; 
                            candidatePostGraduatePref[objArray[i].preference] = objArray[i].programId;
                        }    
                    }                    
                    
/*                    if (document.addForm.programType.value == 'G') {
                        for(i=0; i< graduatePrefTotal; i++) {
                            candidateGraduatePref[i] = i+1;    
                        } 
                    } else {
                        for(i=0; i< postGraduatePrefTotal; i++) {
                            candidatePostGraduatePref[i] = i+1;    
                        }    
                    }
*/                    
/*                    showPreference(document.editForm.programType,'Edit'); 
                    document.editForm.formNo.focus();         
                },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
*/
/*
function initArray(){
    for(i=0; i<= graduatePrefTotal; i++) {
        candidateGraduatePref[i] = 0;    
    } 
    for(i=0; i<= postGraduatePrefTotal; i++) {
        candidatePostGraduatePref[i] = 0;    
    }        
}      */
//-------------------------------------------------------
//THIS FUNCTION IS USED TO SHOW PROGRAM PREFERENCE
//--------------------------------------------------------
/*
function showPreferenceEdit(obj,action) {
    if (obj.value == 'P') {
        document.getElementById('gpPreferenceEdit').style.display='none'; 
        document.getElementById('pgpPreferenceEdit').style.display='';
        if (action == "Edit") {
            for(i=0; i< postGraduatePrefTotal; i++) {
                document.getElementById("PG" + (i+1)).value = candidatePostGraduatePref[i+1];    
            }  
        }        
    } else {
        document.getElementById('pgpPreferenceEdit').style.display='none';   
        document.getElementById('gpPreferenceEdit').style.display=''; 

        if (action == 'Edit') {
            for(i=0; i< graduatePrefTotal; i++) {
               document.getElementById("G" + (i+1)).value = candidateGraduatePref[i+1];   
            }  
        }         
    } 
                   
}
 */
//-------------------------------------------------------
//THIS FUNCTION IS USED TO SHOW PROGRAM PREFERENCE
//--------------------------------------------------------
/*
function showPreference(obj,action) {
                       
       
    if (obj.value == 'P') {
        document.getElementById('gpPreference').style.display='none'; 
        document.getElementById('pgpPreference').style.display='';
        if (action == "Edit") {
            for(i=0; i< postGraduatePrefTotal; i++) {
                document.getElementById("PG" + (i+1)).value = candidatePostGraduatePref[i+1];    
            }  
        }        
    } else {
        document.getElementById('pgpPreference').style.display='none';   
        document.getElementById('gpPreference').style.display=''; 

        if (action == 'Edit') {
            for(i=0; i< graduatePrefTotal; i++) {
               document.getElementById("G" + (i+1)).value = candidateGraduatePref[i+1];   
            }  
        }         
    } 
                   
}
*/

/* function to export all student report*/
function exportCandidateCSV() {
    formName    = document.searchForm.name;
    queryString = generateQueryString(formName);
    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField
    document.getElementById('generateCandidateCSV').href='candidateStatusReportCSV.php?'+queryString;          

/*    queryString += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    qtr = "<?php echo $queryString?>";
    if(qtr!='')
        queryString = qtr;
    
    document.getElementById('generateCSV').href='scSearchStudentReportCSV.php?'+queryString;
    document.getElementById('generateCSV1').href='scSearchStudentReportCSV.php?'+queryString;
    */
}
</script>

</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/Admission/listCandidateStatusContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
    <script type="text/javascript" language="javascript">
     //calls sendReq to populate page
     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
