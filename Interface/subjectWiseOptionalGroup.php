<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF INISTITUTES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Dipanjan Bhattacharjee
// Created on : (13.6.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','SubjectWiseOptionalGroup');
define('ACCESS','view');
define("MANAGEMENT_ACCESS",1);   
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Institute/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Subject Wise Optional Group  </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxSubjectWiseOptionalGroup.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddInstituteDiv'; // div container name  
editFormName   = 'EditInstituteDiv'; // div container name
winLayerWidth  = 650; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteInstitute';
divResultName  = 'results';
page=1; //default page
sortField = 'subjectName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

var queryString='';


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

function getValue(val) {
   if(queryString!='') {
     queryString +='&';
   } 
   nm = escape(val);
   queryString += "chb[]="+nm;
}


function addOptionalGroups() {
    
    var url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxAddSubjectWiseOptionalGroup.php';
    queryString='';
    
    msg = confirm('Are you sure to create a optional groups?')
    if(msg == false) {
      return false;
    }
    
    //params = generateQueryString('allDetailsForm');   
    
    formx = document.allDetailsForm;   
    for(var j=1;j<formx.length;j++) {      
      if(formx.elements[j].checked==true && formx.elements[j].name=="chb[]" && formx.elements[j].type=="checkbox") {
         str = formx.elements[j].value+'~Y';
         getValue(str);
      }
    }
    
    
    for(var j=1;j<formx.length;j++) {
      if(formx.elements[j].type=="checkbox" && formx.elements[j].name=="chb1[]" ) { 
        for(var i=1;i<formx.length;i++){   
          if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]") {
             if(formx.elements[i].value==formx.elements[j].value && formx.elements[j].checked==true && formx.elements[i].checked==false) {
                 str = formx.elements[i].value+'~N';
                 getValue(str);
                 break;
             }
          }
        }
      }
    } 

    
    new Ajax.Request(url,
    {
       method:'post',
       parameters: queryString , 
       asynchronous:true,
       onCreate: function() {
          showWaitDialog(true);
       },
       onSuccess: function(transport){
          hideWaitDialog(true);
          if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText)) {
             messageBox(trim(transport.responseText)); 
             showResult();
          }
          else {
            messageBox(trim(transport.responseText)); 
          }
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}    

function showResult() {

    var url = '<?php echo HTTP_LIB_PATH;?>/Group/ajaxSubjectWiseOptionalGroup.php';       
    
    var labelId=document.allDetailsForm.labelId.value; 
    if(labelId=='') {
      labelId=0;  
    }
    
    new Ajax.Request(url,
    {
       method:'post',
       parameters: {labelId: labelId },
       asynchronous:true,
        onCreate: function(){
             showWaitDialog(true);
       },
       onSuccess: function(transport){
          hideWaitDialog(true);
          var j=trim(transport.responseText);
          document.getElementById('results').innerHTML=j
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
    });
}


window.onload=function() {   
   showResult();
}
/* function to print institute report*/
function printReport() {
  
    path='<?php echo UI_HTTP_PATH;?>/subjectWiseOptionalGroupPrint.php';
    window.open(path,"SubjectWiseOptionalGroup","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
    
}

/* function to output data to a CSV*/
function printCSV() {
   
    path='<?php echo UI_HTTP_PATH;?>/subjectWiseOptionalGroupCSV.php';   
    window.location=path;
}

</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Group/listSubjectWiseOptionalGroupContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
