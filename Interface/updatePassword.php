<?php 
//-------------------------------------------------------
//  This File contains Validation and ajax function used in transferredMarks report
//
//
// Author :Jaineesh
// Created on : 29-May-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','UpdatePasswordReport');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
UtilityManager::ifNotLoggedIn();
require_once(BL_PATH.'/HtmlFunctions.inc.php');
$htmlFunctions = HtmlFunctions::getInstance();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Generate Student Login </title>
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

var queryString = "";

var tableHeadArray = new Array(    
            new Array('srNo','#','width="2%"','width="2%"',false),
            new Array('checkAll','<input type="checkbox" name="checkbox2" value="checkbox" onClick="doAll()">','width="3%"','',false),
            new Array('regNo','Reg. No.','width="15%"','',true),
            new Array('universityRollNo','Univ. Roll No.','width="15%"','',true),
            new Array('rollNo','Roll No.','width="12%"','',true),
            new Array('studentName','Name','width="16%"','',true),
            new Array('studentEmail','Email','width="18%"','',true),
            new Array('userName','Old Username','width="18%"','',true));
            
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/UpdatePassword/initUpdatePasswordReport.php';
searchFormName = 'allDetailsForm'; // name of the form which will be used for search
//addFormName    = 'AddState';   
//editFormName   = 'EditState';
//winLayerWidth  = 315; //  add/edit form width
//winLayerHeight = 250; // add/edit form height
//deleteFunction = 'return deleteState';
divResultName  = 'resultsDiv';
page=1; //default page
sortField = 'regNo';
sortOrderBy = 'Asc';



function validateAddForm(frm) {

    insertForm();
    return false;
}

function getStudentList(){

    if(isEmpty(document.getElementById('degree').value) && isEmpty(document.getElementById('rollNo').value) && isEmpty(document.getElementById('studentName').value) && isEmpty(document.getElementById('groupId').value)) {
       messageBox("Please select atleast one search criteria");
       document.getElementById('degree').focus();   
       document.getElementById('nameRow').style.display='none';
       document.getElementById('resultRow').style.display='none';
       document.getElementById('nameRow2').style.display='none';
       return false;
   }
   else {   
       document.getElementById('nameRow').style.display='';
       document.getElementById('resultRow').style.display='';
       document.getElementById('nameRow2').style.display='';         
       
       sendReq(listURL,divResultName,'allDetailsForm','',true); 
   }
}

function insertForm() {
 
    if(document.allDetailsForm.changeUserName.checked == true)  {
      changeUserName=1;  
    }
    else {
      changeUserName=0;  
    }
    
    if (document.allDetailsForm.password[0].checked == true) {
        var onePassword = 1;
    }
    else if (document.allDetailsForm.password[1].checked == true) {
        var onePassword = 2;
        var pass1 = trim(document.allDetailsForm.newPassword.value);
        //if trim(document.allDetailsForm.newPassword.value == "") {
         if (pass1 == "") {
        alert('Enter common Password');
        return false;
        }
    }
    else if (document.allDetailsForm.password[2].checked == true) {
        var onePassword = 3;
    }
    var selected=0;
    var studentCheck="";
    form = document.allDetailsForm;
    for(var i=1;i<form.length;i++){

        if(form.elements[i].type=="checkbox"){

            if((form.elements[i].checked) && (form.elements[i].name=="chb[]")) {
             if(studentCheck=="") {
               studentCheck=form.elements[i].value; 
            }
            else {
                studentCheck = studentCheck + ',' +form.elements[i].value; 
            }
            selected++;
            }
        }
    } 
    if(selected==0){
        messageBox("Please select atleast one student");
        return false;
    }                                                                                                                           
    
    if (document.allDetailsForm.userNameFormat.value == 'Reg. No.') {
       userNameFormat = 1; 
    }
    else if (document.allDetailsForm.userNameFormat.value == 'Univ. Roll No.') {
        userNameFormat = 2;
    }
    else if (document.allDetailsForm.userNameFormat.value == 'Roll No.') {
        userNameFormat = 3;
    }
    else if (document.allDetailsForm.userNameFormat.value == 'Email') {
        userNameFormat = 4;
    }
    else if (document.allDetailsForm.userNameFormat.value == 'Student Name + Batch') {
        userNameFormat = 5;
    }
    
    classId = document.allDetailsForm.degree.value;  
    
     url = '<?php echo HTTP_LIB_PATH;?>/UpdatePassword/passwordAdd.php';
     var pars = generateQueryString('allDetailsForm');
     pars += '&onePassword='+onePassword+'&studentCheckIds='+studentCheck+'&userNameFormat='+userNameFormat+'&changeUserName='+changeUserName;
     pars += '&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'&classId='+classId;  

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
                    if(ret[1]!=''){
                         document.getElementById('result').value = ret[1];
                         document.allDetailsForm.target ="_blank";  
                         document.allDetailsForm.action ="<?php echo UI_HTTP_PATH ?>/studentListPrintCSV.php";
                         document.allDetailsForm.submit();
                         
                         document.allDetailsForm.target ="";  
                         document.allDetailsForm.action ="";
                         //window.location = "<?php echo UI_HTTP_PATH ?>/parentListPrintCSV.php?fIds="+ret[2]+"&mIds="+ret[3]+"&gIds="+ret[4]+'&checkValue='+onePassword+'&check='+newPassword+condition+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
                        //clearText();
                        sendReq(listURL,divResultName,'allDetailsForm','',true);
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

/*function printCSV() {
    var qstr="&sortOrderBy="+sortOrderBy+"&sortField="+sortField+"&chb="+chb;
    window.location="<?php echo UI_HTTP_PATH ?>/studentListPrintCSV.php?"+qstr; 
    alert("1");
    
}*/

function doAll(){
    
    formx = document.allDetailsForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){

            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
            
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            
            if(formx.elements[i].type=="checkbox") {

                formx.elements[i].checked=false;
            }
        }
    }
}


function hideResults() {
    document.getElementById("resultRow").style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
}

function clearText() {

  //  document.getElementById('saveDiv').style.display='none';
    document.getElementById('degree').value='';
    document.getElementById('rollNo').value='';
    document.getElementById('studentName').value='';
    document.getElementById('newPassword').value='';
    document.allDetailsForm.password[0].checked == true;
    document.getElementById('resultRow').style.display='none';
    document.getElementById('nameRow').style.display='none';
    document.getElementById('nameRow2').style.display='none';
    document.allDetailsForm.target ="";  
    document.allDetailsForm.action ="";
    //document.allDetailsForm.reset();
}

function getGroups() {
    form = document.allDetailsForm;
    var degree = form.degree.value;
   
    document.allDetailsForm.groupId.length = null;
    addOption(document.allDetailsForm.groupId, '', 'Select');
    
    if (degree == '') {
        return false;
    }
    var url = '<?php echo HTTP_LIB_PATH;?>/UpdatePassword/initClassGetGroups.php';
    var pars = 'class1='+degree;
    if (degree == '') {
        document.allDetailsForm.groupId.length = null;
        addOption(document.allDetailsForm.groupId, '', 'Select');
        return false;
    }
     new Ajax.Request(url,
       {
         method:'post',
         parameters: pars,
         asynchronous:false,
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
               hideWaitDialog(true);
                var j = eval('(' + transport.responseText + ')');
                len = j.length;
                document.allDetailsForm.groupId.length = null;
                addOption(document.allDetailsForm.groupId, '', 'Select');
                if(len > 0) {
                   document.allDetailsForm.groupId.length = null;
                   addOption(document.allDetailsForm.groupId, 'all', 'All');
                }
                for(i=0;i<len;i++) { 
                    addOption(document.allDetailsForm.groupId, j[i].groupId, j[i].groupName);
                }
                // now select the value
                //document.allDetailsForm.groupId.value = j[0].groupId;
           },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


window.onload=function(){
  var roll = document.getElementById("rollNo");
  autoSuggest(roll);
}
</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/UpdatePassword/updatePasswordReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php 

//$History: updatePassword.php $
//
//*****************  Version 8  *****************
//User: Gurkeerat    Date: 11/16/09   Time: 11:27a
//Updated in $/LeapCC/Interface
//Updated code to get username when clicked on 'Generate Login ' button
//
//*****************  Version 7  *****************
//User: Gurkeerat    Date: 11/13/09   Time: 5:57p
//Updated in $/LeapCC/Interface
//Updated code to add new field in 'Generate Student Login' 
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/12/09   Time: 12:31p
//Updated in $/LeapCC/Interface
//sorting order updated
//
//*****************  Version 5  *****************
//User: Gurkeerat    Date: 11/12/09   Time: 12:12p
//Updated in $/LeapCC/Interface
//updated code to resolve issues
//
//*****************  Version 4  *****************
//User: Gurkeerat    Date: 11/11/09   Time: 6:29p
//Updated in $/LeapCC/Interface
//resolved issues: 1967, 1968, 1969, 1971, 1972, 1980, 1981
//
//*****************  Version 3  *****************
//User: Gurkeerat    Date: 11/06/09   Time: 1:46p
//Updated in $/LeapCC/Interface
//Updated code to modify 'Generate student login'
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 6/01/09    Time: 3:26p
//Updated in $/LeapCC/Interface
//changes as per leap cc
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 6/01/09    Time: 1:08p
//Created in $/LeapCC/Interface
//copy from sc
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 5/30/09    Time: 6:20p
//Updated in $/Leap/Source/Interface
//optimization in code
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 5/30/09    Time: 4:01p
//Updated in $/Leap/Source/Interface
//put new link create users
//

?>
