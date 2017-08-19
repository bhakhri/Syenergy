<?php
//-----------------------------------------------------------------------------
//  To generate Studnet Counseling functionality      
//
// Author :Abhay Kant
// Created on : 22.6.2011
// Copyright 2010-2011: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','AllowIp');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
UtilityManager::headerNoCache();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Allow IP for Registration </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
//echo UtilityManager::includeCSS2(); 
?> 
<script language="JavaScript">
var libraryPath = '<?php echo HTTP_LIB_PATH;?>';
</script>
<?php
//echo UtilityManager::javaScriptFile2();
?> 
<script language="javascript">
var tableHeadArray = new Array(new Array('srNo','#','width="1%"','',false),
                               new Array('checkAll','<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="4%" align=\"center\" ','align=\"center\"  valign="middle"',false),
                               new Array('allowIPNo','Allowed Ip No.','width="88%" ','align="left"',true),                       
                               new Array('action1','Action','width="12%"','align="center"',false));
	
//This function Validates Form 
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL='<?php echo HTTP_LIB_PATH;?>/RegistrationForm/AllowIp/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddSubject';   
editFormName   = 'EditSubject';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlockStudent';
divResultName  = 'results';
page=1; //default page
sortField = 'allowIPNo';
sortOrderBy = 'ASC';
 
queryString='';
queryString1 = '';

var dtArray=new Array();   

function doAll() {

   try {
     var formx = document.allDetailsForm;
     if(document.getElementById('checkbox2').value=='on'){
        for(var i=0;i<document.getElementsByName('chb[]').length;i++){
         document.getElementsByName('chb[]')[i].checked=true;
        }
     }
     if(document.getElementById('checkbox2').checked==false){
          for(var i=0;i<document.getElementsByName('chb[]').length;i++){   
           document.getElementsByName('chb[]')[i].checked=false;
        }
     }
   } catch (e) {}
   
}


function validateAddForm() {
   
   dtArray.splice(0,dtArray.length); //empty the array  
   
   var queryString = '';
   var queryString1 = '';

   var mail_check= 0; 
   var AllowIp  = trim(document.getElementById('allowIp').value);
 
    if(trim(document.getElementById('allowIp').value)=='') {
       messageBox("Enter Allow IP Nos");
       document.getElementById('allowIp').focus();
       document.getElementById('allowIp').className='inputboxRed';
       return false;
    }
    
    var ret=AllowIp.split(',');   
    
    ipNos = '';
    
    
    for(i=0;i<ret.length;i++) { 
      var id = ret[i];
      var chkIP = ret[i].split('.');  
      if(chkIP.length!=4) {
        messageBox("Invalid Format of IP address");
        document.getElementById('allowIp').focus();
        document.getElementById('allowIp').className='inputboxRed';
        return false;
      }
      
      if(trim(chkIP[0])=='' || trim(chkIP[1])=='' || trim(chkIP[2])=='' || trim(chkIP[3])=='') {
         messageBox("Invalid Format of IP address");
         document.getElementById('allowIp').focus();
         document.getElementById('allowIp').className='inputboxRed';
         return false;
      }
      
      if( !isNumeric(trim(chkIP[0])) || !isNumeric(trim(chkIP[1])) || !isNumeric(trim(chkIP[2])) ) {
         messageBox("Invalid Format of IP address");
         document.getElementById('allowIp').focus();
         document.getElementById('allowIp').className='inputboxRed';
         return false;
      }
      
      
      if(!isNumeric(trim(chkIP[3]))) {
          var contIP = chkIP[3].split('~');
          if(contIP.length>2) {  
            messageBox("Invalid Format of IP address");  
            document.getElementById('allowIp').focus();
            document.getElementById('allowIp').className='inputboxRed';
            return false;  
          }
          if(contIP.length==2) {
             var st=trim(contIP[0]);
             var ed=trim(contIP[1]);
             var str = trim(chkIP[0])+"."+trim(chkIP[1])+"."+trim(chkIP[2]); 
             if(!isNumeric(trim(st)) || !isNumeric(trim(ed))) {
                messageBox("Invalid Format of IP address");  
                document.getElementById('allowIp').focus();
                document.getElementById('allowIp').className='inputboxRed';
                return false; 
             }
             
             if(parseInt(st) > parseInt(ed) ) {
                messageBox("Invalid Range Format of IP address");  
                document.getElementById('allowIp').focus();
                document.getElementById('allowIp').className='inputboxRed';
                return false;  
             }
             
             if(st!='' && ed!='' ) { 
                for(var k=st;k<=ed;k++) {
                   if(checkDuplicate(str+"."+k)==0) {                                                                
                      messageBox("Already Define of IP address");  
                      document.getElementById('allowIp').focus();
                      document.getElementById('allowIp').className='inputboxRed';
                      return false;
                   }
                   if(ipNos=='') {
                     ipNos = str+"."+k;
                   }
                   else {
                     ipNos =ipNos+","+(str+"."+k);
                   }
                }
             }
          }
          else if(contIP.length==1) {
            if(ipNos=='') {
              ipNos = id;
            }
            else {
              ipNos =ipNos+","+id;
            }  
            if(checkDuplicate(id)==0) {                                                                
              messageBox("Duplicate IP addresses should not be accepted");
              document.getElementById('allowIp').focus();
              document.getElementById('allowIp').className='inputboxRed';
              return false;
            }
         }
      }
      else {
          if(ipNos=='') {
            ipNos = id;
          }
          else {
            ipNos =ipNos+","+id;
          }  
          if(checkDuplicate(id)==0) {                                                                
             messageBox("Duplicate IP addresses should not be accepted");
             document.getElementById('allowIp').focus();
             document.getElementById('allowIp').className='inputboxRed';
             return false;
          }
      }
   }
    
   addIp(ipNos);   
   return false;
}


function checkDuplicate(value) {
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


function addIp(ipNos) {
    
     var mail_check= 0; 
     //var allowIp  = trim(document.getElementById('allowIp').value);
     var url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/AllowIp/ajaxAddAllowIp.php';
	 new Ajax.Request(url,
     {
         method:'post',
         parameters:{allowIp: ipNos},
         onCreate: function() {
		   showWaitDialog(true);
		 },
		 onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
               messageBox("<?php echo SUCCESS;?>");
               document.getElementById('allowIp').value='';
               document.getElementById('allowIp').className='inputbox1'; 
               sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
               document.getElementById('allowIp').focus();    
             } 
             else {
               messageBox(trim(transport.responseText)); 
               document.getElementById('allowIp').className='inputboxRed'; 
             }
         },
		  onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}


function deleteSubject(id) {
    
    url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/AllowIp/ajaxInitDelete.php';
        
    if(false===confirm("Do you want to delete this record?")) {
       return false;
    }
    else {   
         new Ajax.Request(url,
         {
             method:'post',
             parameters: {deleteIp: id},
             onCreate: function() {
			    showWaitDialog(true);
		     },
		     onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                 }
                 else {
                     messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
    }    
}


//Function for delteing multiple ips
function unBlock(){
    
    url = '<?php echo HTTP_LIB_PATH;?>/RegistrationForm/AllowIp/ajaxInitDelete.php';  

    if(false===confirm("Do you want to delete selected IPs?")) {
          return false;
    }
    else {
        var unBlockId="";
	    var counter=document.getElementsByName('chb[]').length;
        
        blockId='0';
 	    for(var i=0;i<counter;i++){
 	       if(document.getElementsByName('chb[]')[i].checked==true) {
    	     blockId=blockId+","+document.getElementsByName('chb[]')[i].value;
 	       }
        }
        
	    new Ajax.Request(url,
        {
             method:'post',
             parameters: {deleteIp: blockId},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){
                 hideWaitDialog(true);
                 if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                     return false;
                 }
                 else {
                     messageBox(trim(transport.responseText));
                 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         }   
}


function printReport() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    
    var path='<?php echo UI_HTTP_PATH;?>/RegistrationForm/listAllowedIpPrint.php?'+qstr;
    window.open(path,"CountryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

/* function to output data to a CSV*/
function printReportCSV() {
    
    var qstr="searchbox="+trim(document.searchForm.searchbox.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/RegistrationForm/listAllowedIpReportCSV.php?'+qstr;
    window.location = path;
}
</script>

</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/RegistrationForm/AllowIp/listAllowIpContent.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
<script language="javascript">
  sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
</body>
</html>

