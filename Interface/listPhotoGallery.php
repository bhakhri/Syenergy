<?php
//-------------------------------------------------------
//  THIS FILE SHOWS A LIST OF CITIES ALONG WITH ADD,EDIT,DELETE,SEARCH AND PAGING OPTIONS
//
// Author : Jaineesh
// Created on : (26 July 10)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','PhotoGallery');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Events Photo Gallery </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">

var tableHeadArray = new Array(
    new Array('srNo',        '#',               'width="2%"','',false),
    new Array('visibleFrom', 'Visible From',    'width="12%"','align="center"',true),
    new Array('visibleTo',   'Visible To',      'width="12%"','align="center"',true),
    new Array('eventName',   'Event Name',      'width="20%"','',true),
    new Array('eventDescription','Event Description','width="30%"','',true),
    new Array('roleVisibleTo','Role Visible To', 'width="20%"','align="left"',true),
    new Array('photo','Photo','width="12%"','align="center"',false),
    new Array('checkAll',    'Download&nbsp;<input type=\"checkbox\" id=\"checkbox2\" name=\"checkbox2\" onclick=\"doAll();\">','width="10%" align="center"','align="center"',false),
    new Array('action',  'Action',              'width="10%"','align="center"',false)
);
recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/EventPhotoGallery/ajaxInitList.php'; 
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddPhoto';   
editFormName   = 'AddPhoto';
winLayerWidth  = 360; //  add/edit form widthreOrderLevel

winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deletePhoto';
divResultName  = 'results';

page=1; //default page
sortField   = 'dateOfEntry';
sortOrderBy = 'DESC';
// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button
var globalFL='';
var photoId='';
var deletePhotoGalleryId='0';

function blankValues() {
   cleanUpTable();  
   document.getElementById('mainPhotoGalleryId').value = '';  
   document.getElementById('eventDescription').value = '';   
   document.addPhoto.eventName.value = '';
   makeSelection("roleId","None","addPhoto");
   document.addPhoto.eventName.focus();
}


//for deleting a row from the table 
   function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
      reCalculate();
      
      if(isMozilla){
          if((tbody1.childNodes.length-2)==0){
              resourceAddCnt=0;
          }
      }
      else{photoGalleryId
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
      if(rval[1]!='0') {
        deleteOneByOnePhoto(rval[1]);  
      }
    } 
    
    var resourceAddCnt=0;
  // check browser
     var isMozilla = (document.all) ? 0 : 1;
//to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records photoGalleryIdwere retrieved but not posted bcos user marked them deleted
        document.getElementById('deleteFlag').value=true;
              
        if(cnt=='')
       cnt=1;  
         if(isMozilla){
             if(document.getElementById('anyidBody').childNodes.length <= 3){
                resourceAddCnt=0; 
             }       
        }
        else{
             if(document.getElementById('anyidBody').childNodes.length <= 1){
               resourceAddCnt=0;  
             }       
        } 
        resourceAddCnt++; 
        
        createRows(resourceAddCnt,cnt);
    }

 
 var bgclass='';
    
//function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt) {
  
     // alert(start+'  '+rowCnt);
     var tbl=document.getElementById('anyid');
     var tbody = document.getElementById('anyidBody');
     
     for(var i=0;i<rowCnt;i++){
          var tr=document.createElement('tr');
          tr.setAttribute('id','row'+parseInt(start+i,10));
          
          var cell1=document.createElement('td');
          var cell2=document.createElement('td'); 
          var cell3=document.createElement('td');
          var cell4=document.createElement('td');
          
          cell1.setAttribute('align','left');
          cell1.name='srNo';
          cell2.setAttribute('align','left'); 
          cell3.setAttribute('align','left');
          cell4.setAttribute('align','center');
          
          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
          
          var txt1=document.createElement('input');
          var txt2=document.createElement('input');
          var txt3=document.createElement('a');
          var txt4=document.createElement('input');
          var txt5=document.createElement('label');
          var txt6=document.createElement('input'); 
          
          
         txt1.setAttribute('id','uploadPhoto'+parseInt(start+i,10));
         txt1.setAttribute('name','uploadPhoto[]');
         txt1.className='inputbox';
         txt1.setAttribute('type','file');
         txt1.setAttribute('style','width:250px;');
          
          txt2.setAttribute('id','comments'+parseInt(start+i,10));
          txt2.setAttribute('name','comments[]');
          txt2.setAttribute('type','text');
          txt2.className="inputBox";
          txt2.setAttribute('style','width:375px;')

          txt3.setAttribute('id','rd');
          txt3.className='htmlElement';  
          txt3.setAttribute('title','Delete');       
          txt3.innerHTML='X';
          txt3.style.cursor='pointer';  
          txt3.setAttribute('style','width:10px;'); 
          if(deletePhotoGalleryId=='') {
            deletePhotoGalleryId=0;  
          }
          txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~'+deletePhotoGalleryId+'")');  //for ie and ff
          
          txt4.setAttribute('id','hiddenId'+parseInt(start+i,10));
          txt4.setAttribute('name','hiddenId[]');
          txt4.setAttribute('value',parseInt(start+i,10));
          txt4.setAttribute('type','hidden');
          txt4.className="inputBox";
          txt4.setAttribute('style','width:375px;')
          
          txt6.setAttribute('id','photoHiddenId'+parseInt(start+i,10));
          txt6.setAttribute('name','photoHiddenId[]');
          txt6.setAttribute('value',deletePhotoGalleryId);
          txt6.setAttribute('type','hidden');
          txt6.className="inputBox";
          txt6.setAttribute('style','width:375px;')
          
          txt5.setAttribute('id','lblPhoto'+parseInt(start+i,10));
          txt5.setAttribute('name','lblPhoto[]');
          txt5.className="inputBox";
          txt5.setAttribute('style','width:375px;')
           
          cell1.appendChild(txt0); 
          cell2.appendChild(txt1);             
          cell2.appendChild(txt5);
          
          cell3.appendChild(txt2);
          cell3.appendChild(txt4);   
          cell3.appendChild(txt6);  
          
          cell4.appendChild(txt3);
          
                 
          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
          
          bgclass=(bgclass=='row0'? 'row1' : 'row0');
          tr.className=bgclass;
          tbody.appendChild(tr); 
      } // End For Loop (Row Cnt)
      tbl.appendChild(tbody); 
      reCalculate();  
}  

//to clean up table rows
function cleanUpTable(){
   var tbody = document.getElementById('anyidBody');
   for(var k=0;k<=resourceAddCnt;k++){
      try{
        tbody.removeChild(document.getElementById('row'+k));
      }
      catch(e){
         //alert(k);  // to take care of deletion problem
      }
   }  
}
    
    
//to recalculate Serial no.
function reCalculate(){
  var a =document.getElementById('tableDiv').getElementsByTagName("td");
  var l=a.length;
  var j=1;
  for(var i=0;i<l;i++){     
    if(a[i].name=='srNo'){
    bgclass=(bgclass=='row0'? 'row1' : 'row0');
    a[i].parentNode.className=bgclass;
      a[i].innerHTML=j;
      j++;
    }
  }
  //resourceAddCnt=j-1;
}      


var typeArray=new Array();
function checkDuplicateValue(value){
    var i= typeArray.length;
    var fl=1;
    for(var k=0;k<i;k++){
      if(typeArray[k]==value){
          fl=0;
          break;
      }  
    }
   if(fl==1){
       typeArray.push(value);
   } 
   return fl;
}

function checkFileExtensionsUpload(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg','png','bmp');

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
//function to upload images 
function uploadImages() {
  
        if(globalFL=='0'){
           messageBox("Another request is in progress.");
           return false;
        } 

        if(trim(document.addPhoto.eventName.value) == ""){
           messageBox("<?php echo "Please enter event name";?>");
           document.addPhoto.eventName.className="inputboxRed";
           document.addPhoto.eventName.focus();
           return false;
        }

        if(trim(document.addPhoto.mainPhotoGalleryId.value) == ""){   
            // validation rows added 
            var selected = 0; 
            var formx = document.addPhoto;
            for(var i=0;i<formx.length;i++) {
                if(formx.elements[i].name=="uploadPhoto[]") {
                   selected++;
                   break;
                }
            }
            if(selected==0) {
              messageBox("<?php echo "Please create a row and browse atleast one file."; ?>");   
              return false;
            }
            
            var len = (formx.length);
            // validation photo upload
            selected = 0;   //  status photo upload 
            var photo
            for(var i=0;i<len;i++){ 
                if(formx.elements[i].name=="hiddenId[]" && trim(formx.elements[i].value)!=""){
                    id = formx.elements[i].value;
                    photo= eval("document.getElementById('uploadPhoto"+id+"').value"); 
                    if(photo!='') {
                      if(!checkFileExtensionsUpload(photo)) {
                         messageBox("<?php echo INCORRECT_FILE_EXTENSION; ?>");
                         eval("document.getElementById('uploadPhoto"+id+"').className='inputboxRed'");
                         eval("document.getElementById('uploadPhoto"+id+"').focus()");
                         return false;
                      }
                    }
                    if(photo == "") { 
                       messageBox("Please browse photo");      
                       eval("document.getElementById('uploadPhoto"+id+"').className='inputboxRed'");
                       eval("document.getElementById('uploadPhoto"+id+"').focus()");
                       return false;
                    }
                    selected = 1;
                 }
             } 
         }
         
         if(selected==1 || trim(document.addPhoto.mainPhotoGalleryId.value) != "") {
            document.getElementById('addPhoto').target = 'uploadTargetAdd';
            document.getElementById('addPhoto').action= "<?php echo HTTP_LIB_PATH;?>/EventPhotoGallery/fileUpload.php";
            document.getElementById('addPhoto').submit(); 
            return true;
         }
         else {
            messageBox("<?php echo "Please browse atleast one file."; ?>");  
         }
         
         return false;
}


  function fileUploadError(str){
   hideWaitDialog(true);

   if("<?php echo NOT_WRITEABLE_FOLDER;?>" == trim(str)) {
      messageBox("<?php echo NOT_WRITEABLE_FOLDER; ?>");       
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
      return false;
   }
   else
   if("<?php echo SUCCESS;?>" == trim(str)) {
      hiddenFloatingDiv('AddPhoto');   
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
      return false;
   }
   else {
      //Invalid file extension or maximum upload size exceeds
      messageBox("Some files were not uploaded as they exceeded maximum upload size limit of "+<?php echo ceil(MAXIMUM_FILE_SIZE/1024); ?>+"kb");
      //showDetails(trim(str),'divInformation',300,250)
      hiddenFloatingDiv('AddPhoto');   
      sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
      return false;
    }
    hiddenFloatingDiv('AddPhoto');   
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField); 
    return false;
}


//function to check all the checkboxes

function doAll(){

    formx = document.listForm;
    if(formx.checkbox2.checked){
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=true;
            }
        }
    }
    else{
        for(var i=1;i<formx.length;i++){
            if(formx.elements[i].type=="checkbox" && formx.elements[i].name=="chb[]"){
                formx.elements[i].checked=false;
            }
        }
    }
}

//function used to delete photos 
function deleteOneByOnePhoto(id) {
     
     if(id=='0' || id=='') {
       return false;  
     }
    
     var url = '<?php echo HTTP_LIB_PATH;?>/EventPhotoGallery/ajaxDeletePhoto.php';
     new Ajax.Request(url,
       {
         method:'post',
         asynchronous:false, 
         parameters: {
             photoGalleryId: id,
             photoType: 'OneByOne'
         },
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


function deletePhoto(id) {
     
    if(false===confirm("Do you want to delete this record?")) {
      return false;
    }
    
    var url = '<?php echo HTTP_LIB_PATH;?>/EventPhotoGallery/ajaxDeletePhoto.php';
    new Ajax.Request(url,
    {
         method:'post',
         asynchronous:false, 
         parameters: {
             photoGalleryId: id,
             photoType: 'all'
         },
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

// edit window 

    function editWindow(id,dv,w,h) {
       populateValues(id);
       displayWindow(dv,w,h);   
   }
   
//function to populate values on clicking edit button
function populateValues(id) {
    
         deletePhotoGalleryId=0;
         document.getElementById('mainPhotoGalleryId').value = '';
       
         var url = '<?php echo HTTP_LIB_PATH;?>/EventPhotoGallery/ajaxGetEventPhotos.php';
         cleanUpTable();

         new Ajax.Request(url,
         {
             method:'post',
             parameters: {photoGalleryId: id},
             asynchronous:false,
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
              hideWaitDialog(true);
              
              var j = eval('('+trim(transport.responseText)+')');
		      len=j.length;
             
              if(len>0) {
                  for(i=0;i<len;i++) {
                    deletePhotoGalleryId=j[i]['photoGalleryDetailId'];
                    document.getElementById('mainPhotoGalleryId').value = j[i]['photoGalleryId'];
                    addOneRow(1);
                    varFirst=i+1;
                    id = "comments"+varFirst;
                    eval("document.getElementById(id).value = j[i]['comments']");
                    
                    id = "lblPhoto"+varFirst;
                    imgSrc= "<?php echo IMG_HTTP_PATH; ?>/download.gif";  
                    img = "<img src='"+imgSrc+"' name='"+j[i]['photoName']+"' onclick='download(this.name);return false;' alt='Download' title='Download' />";
                    eval("document.getElementById(id).innerHTML = img");    
                    if(i==0) {
                      document.getElementById('visibleFrom').value =  j[i]['visibleFrom'];
                      document.getElementById('visibleTo').value =  j[i]['visibleTo'];
                      document.getElementById('eventName').value =  j[i]['eventName'];
                      document.getElementById('eventDescription').value =  j[i]['eventDescription'];
                      var roles=j[i]['roleVisibleTo'].split(',');
                      var rolelen=document.addPhoto.roleId.length;
                      for(var n =0 ; n <rolelen; n++){
                         for(var k=0 ; k < roles.length ;k++){
                            if(document.addPhoto.roleId.options[n].value==roles[k]){
                              document.addPhoto.roleId.options[n].selected=true;
                            }  // If 
                         } // For Loop
                       } // For Loop
                     }
                document.getElementById('note').innerHTML='<span><span style="font-weight:bold;font-size:10px;color:red;">*Note : If you delete all photos one by one then the event will be automatically deleted</span>';
               document.getElementById('divHeaderId').innerHTML="Edit Event PhotoGraphs";
                     deletePhotoGalleryId=0;
                  }
               } 

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
//function to download photos 
 function  download(str){
    var address="<?php echo IMG_HTTP_PATH;?>/EventPhotoGallery/"+str;
    window.open(address,"Attachment","status=1,resizable=1,location=1,width=450,height=450,top=150,left=400");
  
}

function getPhotoGalleryList(id,dv,w,h) {
    displayWindow(dv,w,h);
    displayPhotos(id);   
    return false;
}

function displayPhotos(id) {

    url = '<?php echo HTTP_LIB_PATH;?>/PhotoGalleryReport/ajaxGetValues.php';
    new Ajax.Request(url,
    {
      method:'post',
      parameters: { photoGalleryId:id,
                    roleId: 'all'
		    },
      asynchronous:false,
      onCreate: function() {
        showWaitDialog(true);
      },
      onSuccess: function(transport){
         hideWaitDialog(true);
         if(trim(transport.responseText)==false) {
           messageBox("<?php echo INCORRECT_FORMAT?>");  
         }
         else {
           document.getElementById('photoResultsDiv').innerHTML=trim(transport.responseText);
         }
       },
       onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
     }); 
     
}


</script>   
</head>
<body>
	<?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/EventPhotoGallery/listPhotoGalleryContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<script>
    //This function will populate list
    sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
</script>
<?php 
// $History: $ 
//
?>
