<?php
 ini_set("post_max_size", "10M");
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Notice Form
//
//
// Author :Arvind Singh Rawat
// Created on : 5-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','EventMaster');
define('ACCESS','view');
define('MANAGEMENT_ACCESS',1);
global $sessionHandler;
$roleId = $sessionHandler->getSessionVariable('RoleId');
if($roleId == 5){
  UtilityManager::ifManagementNotLoggedIn();
}
else{
  UtilityManager::ifNotLoggedIn();
}
//require_once(BL_PATH . "/Event/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Greeting Master </title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');
echo UtilityManager::includeJS("tiny_mce/tiny_mce.js");
?>
<script language="javascript">



// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag
var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
                               new Array('eventPhoto','Photo','width="10%"','align="center"',false),
                               new Array('eventWishDate','Greeting Date','width="15%"','align="center"',true),
                               new Array('comments','Comments','width="45%"','',true),
                               new Array('abbr','Abbreviation','width="15%"','align="left"',true), 
                               new Array('checkAll','Visible ','width="12%" align="center"','align="center"',false),
                               new Array('action','Action','width="5%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddEventDiv';
editFormName   = 'EditEventDiv';
winLayerWidth  = 600; //  add/edit form width
winLayerHeight = 400; // add/edit form height
deleteFunction = 'return deleteEvent';
divResultName  = 'results';
page=1; //default page
sortField = 'eventWishDate';
sortOrderBy    = 'DESC';
var globalCheck='';
var sendSms = "<?php echo $sessionHandler->getSessionVariable('SMS_ALERT_FOR_NOTICE_UPLOAD') ?>";
var topPos = 0;
var leftPos = 0;
var globalFL=1;

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
//This function Displays Div Window

function editWindow(id,dv,w,h) {
   // displayWindow(dv,w,h);

	displayFloatingDiv(dv,'', w, h, screen.width/4.8, screen.height/10);
    populateValues(id);
 /*  document.getElementById('roleId2').style.display='';
     makeDDHide('roleId2','d222','d333');
     document.getElementById('d111').style.zIndex=parseInt(document.getElementById('editEventDiv').style.zIndex,10)+20;
     document.getElementById('d222').style.zIndex=parseInt(document.getElementById('editEventDiv').style.zIndex,10)+10;
     document.getElementById('d333').style.zIndex=parseInt(document.getElementById('editEventDiv').style.zIndex,10)+10;
     document.getElementById('d111').style.height='150px';
  */
}




function validateAddForm(frm, act) {

    if(globalFL==0){
        //messageBox("Another request is in progress.");
        return false;
    }

    var currentDate = "<?php echo date('Y-m-d'); ?>";
    if(act=='Add') {
        if(!dateDifference(currentDate,document.getElementById('eventWishDate').value,'-') ) {
           messageBox ("Greeting Date cannot be less than Current Date"); 
           document.getElementById('eventWishDate').focus();  
           return false;
        } 
        
        if(trim(document.addEvent.elm11.value) =='') {
          messageBox("Enter Comments");
          document.addEvent.elm11.focus();
          return false;
        }
        if(trim(document.addEvent.eventAbbrevation.value) =='') {    
          messageBox("Enter Abbreviation");
          document.addEvent.eventAbbrevation.focus();
          return false;
        }
        if(document.addEvent.roleId.value=='') {
          messageBox("Select Greeting Visible To");
          document.addEvent.roleId.focus();
          return false;
        }
        if(trim(document.addEvent.eventPicture.value)==""){    
          messageBox("Select Photo");
          document.addEvent.eventPicture.focus();
          return false;  
        }
        if(document.addEvent.eventPicture.value!=""){
           if(!checkFileExtensionsUpload(trim(document.addEvent.eventPicture.value))) {
             document.addEvent.eventPicture.focus();
             messageBox("This image extension is not allowed");
             return false;
           }
        }
        addEvent();
    }
    else if(act=='Edit') {
        if(document.getElementById('eventWishDate1').value!=document.getElementById('editEventDate').value) {
          if(!dateDifference(currentDate,document.getElementById('eventWishDate1').value,'-') ) {
            messageBox ("Greeting Date cannot be less than Current Date"); 
            document.getElementById('eventWishDate1').focus();  
            return false;
          } 
        }
        
		if(trim(document.editEvent.elm12.value) =='') {
          messageBox("Enter Comments");
          document.editEvent.elm12.focus();
          return false;
        }
        if(trim(document.editEvent.eventAbbrevation.value) =='') {
          messageBox("Enter Abbreviation");
          document.editEvent.eventAbbrevation.focus();
          return false;
        }
        if(document.editEvent.roleId.value=='') {
          messageBox("Select Greeting Visible To");
          document.editEvent.roleId.focus();
          return false;
        }
        
        if(document.getElementById('editLogoPlace').style.display=='none') {
            if(trim(document.editEvent.eventPicture.value)==""){    
              messageBox("Select Photo");
              document.editEvent.eventPicture.focus();
              return false;  
            }
            if(document.editEvent.eventPicture.value!=""){
               if(!checkFileExtensionsUpload(trim(document.editEvent.eventPicture.value))) {
                 document.editEvent.eventPicture.focus();
                 messageBox("This image extension is not allowed");
                 return false;
               }
            } 
        }
        else {
           if(document.editEvent.eventPicture.value!=""){
               if(!checkFileExtensionsUpload(trim(document.editEvent.eventPicture.value))) {
                 document.editEvent.eventPicture.focus();
                 messageBox("This image extension is not allowed");
                 return false;
               }
           }  
        }
        editEvent();
       //return false;
    }
}

function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg');

      var fl=0;
      var ln=extArr.length;
      
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}



//This function adds form through ajax
function addEvent() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxInitAdd.php';
	 
     new Ajax.Request(url,
     {
         method:'post',
         parameters: $('#addEvent').serialize(true),
         onCreate: function() {
			// showWaitDialog(true);
		 },
		 onSuccess: function(transport){
            if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
              initAdd(1);
            }
            else {
               globalFL=1; 
               messageBox(trim(transport.responseText));
            }
		 },
		  onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
      });
}

function blankValues() {
   document.addEvent.reset();
   
   document.addEvent.eventAbbrevation.value = '';
   document.addEvent.elm11.value = '';
   
   var l=document.addEvent.roleId.length;
   for(var i=0; i<l; i++){
     document.addEvent.roleId.options[ i ].selected=false;
   }
   document.addEvent.eventPicture.value = '';
   document.addEvent.elm11.focus();
}

//This function edit form through ajax
function editEvent() {
     globalFL=0;
     var url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxInitEdit.php';
    
     new Ajax.Request(url,
     {
         method:'post',
         parameters: $('#editEvent').serialize(true),  
         onCreate: function() {
		   //showWaitDialog(true);
		 },
		 onSuccess: function(transport){
			// showWaitDialog(true);
            if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
              initAdd(2);
            }
            else {
              globalFL=1;  
              messageBox(trim(transport.responseText));
            }
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
     });

}


//This function calls delete function through ajax 

function deleteEvent(id) {

         if(false===confirm("Do you want to delete this Event?")) {
             return false;
         }
         
         url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxInitDelete.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {userWishEventId: id},
             onCreate: function() {
			 	showWaitDialog(true);
			 },
			 onSuccess: function(transport){

                     hideWaitDialog(true);
                   //  messageBox(trim(transport.responseText));
                     if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                         return false;
                     }
                      else {
                         messageBox(trim(transport.responseText));
                     }

             },
             onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
           });
         
}


//This function populates values in edit form through ajax

function populateValues(id) {
 
    var url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxGetValues.php';
    document.editEvent.eventPicture.value = '';
    new Ajax.Request(url,
    {
         method:'post',
         parameters: {userWishEventId: id},
        onCreate: function() {
			showWaitDialog(true);
		 },
		 onSuccess: function(transport){
               hideWaitDialog(true);
               var j = eval('('+trim(transport.responseText)+')'); 
               
               
               document.editEvent.userWishEventId.value=j[0].userWishEventId;
               document.editEvent.eventAbbrevation.value = j[0].abbr;
               document.editEvent.elm12.value = j[0].comments;
               document.editEvent.visibility[1].checked=true;
               
               if(j[0].isStatus==1) {
                  document.editEvent.visibility[0].checked=true;
               }
               document.editEvent.eventWishDate1.value = j[0].eventWishDate;
               document.editEvent.editEventDate.value = j[0].eventWishDate;
               var len=document.editEvent.roleId.length;
               for(var n =0 ; n <len;n++){       
                  find='0'; 
                  for(var i=0 ; i < j.length ;i++) {    
                    if(document.editEvent.roleId.options[n].value==j[i].roleId){
                      find='1';
                      break;
                    }
                  }
                  if(find=='1') {
                    document.editEvent.roleId.options[n].selected=true;  
                  }
                  else {
                    document.editEvent.roleId.options[n].selected=false;  
                  }
               }
               document.editEvent.downloadFileName.value=j[0].eventPhoto;
               document.getElementById('editLogoPlace').style.display = 'none';
               imageLogoPath='';  
               if(j[0].eventPhoto=='' || j[0].eventPhoto==null){
                 document.getElementById('editLogoPlace').style.display = 'none';
               }
               else{
                 document.getElementById('editLogoPlace').style.display = '';
               }
               document.editEvent.eventPicture.value = ''; 
               document.editEvent.elm12.focus();
         },
         onFailure: function(){ messageBox ("<?php echo TECHNICAL_PROBLEM;?>") }
       });
}

function initAdd(mode) {
    //document.getElementById('addEvent').target = 'uploadTargetAdd';
    showWaitDialog(true);
    if(mode==1){
        document.getElementById('addEvent').target = 'uploadTargetAdd';
        document.getElementById('addEvent').action= "<?php echo HTTP_LIB_PATH;?>/Event/fileUpload.php";
        document.getElementById('addEvent').submit();
    }
   else{
      document.getElementById('editEvent').target = 'uploadTargetEdit';
      document.getElementById('editEvent').action= "<?php echo HTTP_LIB_PATH;?>/Event/fileUpload.php"
      document.getElementById('editEvent').submit();
   }
}
function fileUploadError(str,mode){
   hideWaitDialog(true);
   globalFL=1;
   if("<?php echo SUCCESS;?>" != trim(str)) {
       messageBox(trim(str));
   }
   if(mode==1){
      if("<?php echo SUCCESS;?>" == trim(str)) {
         flag = true;
         if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
            blankValues();
            return false;
         }
         else {
            hiddenFloatingDiv('AddEventDiv');
            sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
            return false;
         }
         hiddenFloatingDiv('AddEventDiv');
         sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
         return false;
      }
   }
   else if(mode==2){
      if("<?php echo SUCCESS;?>" == trim(str)) {
          hiddenFloatingDiv('EditEventDiv');
          sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
          return false;
      }
   }
   else{
      messageBox(trim(str));
   }
}

function  download1(){

   var address="<?php echo IMG_HTTP_PATH;?>/Event/"+document.getElementById('downloadFileName').value+"?ggg="+<?php echo rand(0,1000); ?>;
   //   window.location = address;
   window.open(address,"GreetingAttachment","status=1,resizable=1,width=500,height=500")
   return false;
}

function  download(str){
    var address="<?php echo IMG_HTTP_PATH;?>/Event/"+str+"?gggg="+<?php echo rand(0,1000); ?>;
	//alert(address);
    window.open(address,"GreetingAttachment","status=1,resizable=1,width=500,height=500")
}


function sendKeys(mode,eleName, e) {
     var ev = e||window.event;
     thisKeyCode = ev.keyCode;
     if (thisKeyCode == '13') {
        if(mode==1){
          var form = document.addEvent;
        }
        else{
          var form = document.editEvent;
        }
        eval('form.'+eleName+'.focus()');
        return false;
     }
}
function printReport() {

    path='<?php echo UI_HTTP_PATH;?>/listEventPrint.php?searchbox='+document.searchForm.searchbox.value+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    window.open(path,"GreetingReport","status=1,menubar=1,scrollbars=1, width=900");
}

/* function to output data to a CSV*/
function printReportCSV() {

    var qstr="searchbox="+document.searchForm.searchbox.value;
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/listEventCSV.php?'+qstr;
    window.location = path;
}


function deatach(){
    if(false===confirm("Do you want to delete this file?")) {
       return false;
    }
    else {
       var url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxDeleteUploadedFile.php';
          new Ajax.Request(url,
          {
           method:'post',
           parameters: {
              userWishEventId: document.editEvent.userWishEventId.value
           },
          onCreate: function() {
              showWaitDialog(true);
           },
         onSuccess: function(transport){
             hideWaitDialog(true);
             if("<?php echo DELETE;?>"==trim(transport.responseText)) {
			    document.getElementById('editLogoPlace').style.display = 'none';
                //sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
             }
             else {
                messageBox(trim(transport.responseText));
             }
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
      });
     }
}
function sendCheck(id,str){
   var i;
   var value1;
   var formx = document.myForm;
   var len=document.myForm.length;
   if(globalCheck=='2') {
      return false;
   } 
  

   if(str=='s' && globalCheck=='') {
    for(i=0;i<len;i++){
      if(id == document.myForm.elements[i].id && formx.elements[i].name=="chb[]") {
         if(document.myForm.elements[i].checked ){
           value1 =1; 
           eval(" document.getElementById('lbl"+id+"').innerHTML = 'Yes' ") ;
           
	 }
         else{
           value1=0;  
           eval("document.getElementById('lbl"+id+"').innerHTML = 'No' ") ;
           
         }
         break;
       }
     }
   }
   else if(globalCheck=='1' && str=='a') {
      if(document.myForm.checkbox2.checked){
	  value1 =1;  
       }
       else {
	   value1=0;
       }
   }
  globalCheck='';
    var url = '<?php echo HTTP_LIB_PATH;?>/Event/ajaxInitEditCheck.php';
              new Ajax.Request(url,
              {
               method:'post',
               parameters: {
                  userWishEventId:id,
                  isStatus:value1
               },
              onCreate: function() {
                  showWaitDialog(true);
               },
             onSuccess: function(transport){
                hideWaitDialog(true);
                if(value1==1){
                       document.getElementById(id).checked=true;
                     }
                else{
                       document.getElementById(id).checked=false;
                    }
                       return false;
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
          });
}

function doAll(){

  var idArray='0';
  var formx = document.myForm;
  if(globalCheck=='2') {
    return false;
  }   
 
  if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]" ){
               formx.elements[i].checked=true;
               var id1=formx.elements[i].id;
               eval("document.getElementById('lbl"+id1+"').innerHTML = 'Yes' ");
		idArray +=","+formx.elements[i].id;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
                var id1=formx.elements[i].id;
                eval("document.getElementById('lbl"+id1+"').innerHTML = 'No' ");
		idArray +=","+formx.elements[i].id;
            }
        }
   }
   globalCheck=1;
   sendCheck(idArray,'a');
   return false;
}
</script>
</head>
<body>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/Event/listEventContents.php"); 
    require_once(TEMPLATES_PATH . "/footer.php");
?>
<SCRIPT LANGUAGE="JavaScript">
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</SCRIPT>
</body>
</html>

<?php
function trim_output($str,$maxlength='250',$rep='...'){
  $ret=chunk_split($str,60);

   if(strlen($ret) > $maxlength){
      $ret=substr($ret,0,$maxlength).$rep;
   }
  return $ret;
}
?>
