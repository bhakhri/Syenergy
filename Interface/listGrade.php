<?php
//-------------------------------------------------------
//  This File contains Validation and ajax function used in Grade Form
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','GradeMaster');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
//require_once(BL_PATH . "/Grade/initList.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Grade Master </title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="2%"','',false), 
                               new Array('gradeSetName','Grade Set Name','width="20%"','',true),
                               new Array('gradeLabel','Grade Name','width="15%"','',true), 
                               new Array('gradePoints','Grade Points','width="15%"','align="right"',true), 
                               new Array('failGrade','Fail Grade','width="15%"','align="left"',true), 
                               new Array('gradeStatus','Grade Status','width="25%"','align="left"',true), 
                               new Array('action','Action','width="8%"','align="center"',false));

recordsPerPage = <?php echo RECORDS_PER_PAGE;?>;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;
listURL = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxInitList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
addFormName    = 'AddGrade';   
editFormName   = 'EditGrade';
winLayerWidth  = 350; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteGrade';
divResultName  = 'results';
page=1; //default page
sortField = 'gradeSetName';
sortOrderBy    = 'ASC';

// ajax search results ---end ///
var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button      

//This function Displays Div Window
function editWindow(id,dv,w,h) {
   
	displayWindow(dv,w,h);
	populateValues(id);   
}

function validateAddForm(frm, act) {

    if(act=='Add') {
	    form  = document.addGrade;
    }
    else if(act=='Edit') {
        form  = document.editGrade;
    }    
    
     if(trim(form.gradeSetId.value) == '') {
         messageBox ("<?php echo SELECT_GRADESET; ?>");
         form.gradeSetId.focus();
         return false;
    }
    
	if(trim(form.gradeLabel.value) == '') {
			messageBox ("<?php echo ENTER_GRADE;?>");
			form.gradeLabel.focus();
			return false;
	}
    
    if(trim(form.gradePoints.value) == '') {
            messageBox ("<?php echo ENTER_GRADE_POINTS ;?>");
            form.gradePoints.focus();
            return false;
    }
    
	if (false == isAlphaNumericCustom(form.gradeLabel.value,'+-')) {
		messageBox ("<?php echo ACCEPT_CHARACTERS;?>");
		form.gradeLabel.focus();
		return false;
	}
    
    if (false == isDecimal(form.gradePoints.value)) {
        messageBox ("<?php echo GRADE_RANGE_POINTS; ?>");
        form.gradePoints.focus();
        return false;
    }


    if (parseFloat(form.gradePoints.value) < 0 || parseFloat(form.gradePoints.value)>127) {
        messageBox ("<?php echo GRADE_RANGE_POINTS; ?>");
        form.gradePoints.focus();
        return false;
    }
    
   

  	if(act=='Add') {
		addGrade();
		return false;
	}
	else if(act=='Edit') {
		editGrade();  
		return false;
	}
}
function addGrade() {
         url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxInitAdd.php';
		 new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeLabel:  (document.addGrade.gradeLabel.value),
                          gradePoints: (document.addGrade.gradePoints.value),
                          gradeSetId: (document.addGrade.gradeSetId.value)
                         },
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
					 flag = true;
					 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
						 blankValues();
					 }
					 else {
						 hiddenFloatingDiv('AddGrade');
						 sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
						 //location.reload();
						 return false;
					 }
				 } 
				 else {
					messageBox(trim(transport.responseText)); 
				 }
				 },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}
function blankValues() {
   document.addGrade.gradeLabel.value = '';
   document.addGrade.gradePoints.value = '';
   if(document.addGrade.gradeSetId.length>0) {
     document.addGrade.gradeSetId.selectedIndex=1;  
   }
   else {
     document.addGrade.gradeSetId.selectedIndex=0;  
   }
   document.addGrade.gradeLabel.focus();
}

function editGrade() {
         url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxInitEdit.php';
       
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeLabel: (document.editGrade.gradeLabel.value), 
                          gradePoints: (document.editGrade.gradePoints.value),
                          gradeSetId: (document.editGrade.gradeSetId.value),
                          failGrade:  trim(document.editGrade.failGrade.value),
                          gradeStatus:  trim(document.editGrade.gradeStatus.value),
                          gradeId:(document.editGrade.gradeId.value)},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
					 hiddenFloatingDiv('EditGrade');
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

function deleteGrade(id) {  
	 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
		 return false;
	 }
	 else {
	 url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxInitDelete.php';
	 new Ajax.Request(url,
	   {
		 method:'post',
		 parameters: {gradeId: id},
		 onCreate: function(){
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

function populateValues(id) {
         url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxGetValues1.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {gradeId: id},
			 onCreate: function(){
				 showWaitDialog(true);
			 },
             onSuccess: function(transport){
                   hideWaitDialog(true);
                   var j = eval('('+trim(transport.responseText)+')');  
                   document.editGrade.gradeId.value = j[0].gradeId;
                   document.editGrade.gradeLabel.value = j[0].gradeLabel;
                   document.editGrade.gradePoints.value = j[0].gradePoints;
                   document.editGrade.failGrade.value = j[0].failGrade;
                   document.editGrade.gradeStatus.value = j[0].gradeStatus;
                   
                   if(j[0].gradeSetId=='' || j[0].gradeSetId=='0') {
                     document.editGrade.gradeSetId.selectedIndex=0;    
                   }
                   else {
                     document.editGrade.gradeSetId.value = j[0].gradeSetId;
                   }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}

function printReport() {
	
	//sortField = listObj.sortField;
	//sortOrderBy = listObj.sortOrderBy;

	var path='<?php echo UI_HTTP_PATH;?>/displayGradeReport.php?searchbox='+trim(document.searchForm.searchbox_h.value)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField;
    //window.open(path,"DisplayHostelReport","status=1,menubar=1,scrollbars=1, width=900");
	 var a=window.open(path,"Grade","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
   
}

/* function to output data to a CSV*/

function printReportCSV() {
//	sortField = listObj.sortField;
//	sortOrderBy = listObj.sortOrderBy;

    var qstr="searchbox="+trim(document.searchForm.searchbox_h.value);
    qstr=qstr+"&sortOrderBy="+sortOrderBy+"&sortField="+sortField;
    path='<?php echo UI_HTTP_PATH;?>/displayGradeCSV.php?'+qstr;
	window.location = path;
}

//-------------------------------------------------------
// ADD GRADE DESCRIPTION
// Author :Aditi Miglani
// Created on : (19.08.2011 )
// Copyright 2011-2012: Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------

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
      else{
          if((tbody1.childNodes.length-1)==0){
              resourceAddCnt=0;
          }
      }
    } 
    
     var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;

//to add one row at the end of the list
function addOneRow(cnt) {
	//set value true to check that the records were retrieved but not posted because user marked them deleted
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
    
//function createRows(start,rowCnt)
function createRows(start,rowCnt){

      var tbl=document.getElementById('anyid');
      var tbody = document.getElementById('anyidBody');
     
                          
      for(var i=0;i<rowCnt;i++){
      	var tr=document.createElement('tr');
      	tr.setAttribute('id','row'+parseInt(start+i,10));
      
      	var cell1=document.createElement('td');
      	var cell2=document.createElement('td'); 
      	var cell3=document.createElement('td');
      	var cell4=document.createElement('td');
        var cell5=document.createElement('td');
        var cell6=document.createElement('td');
      	
      	cell1.setAttribute('align','left');
      	cell1.name='srNo';
      	cell2.setAttribute('align','left'); 
      	cell3.setAttribute('align','left');
      	cell4.setAttribute('align','center');
        
        cell5.setAttribute('align','left');
        cell6.setAttribute('align','left');
      
      
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
        
        var txt5=document.createElement('input');     
        var txt6=document.createElement('input');     

        
      	txt1.setAttribute('id','gradeLabel'+parseInt(start+i,10));
    	txt1.setAttribute('name','gradeLabel[]');
        txt1.setAttribute('class','inputbox');      
      	txt1.setAttribute('type','text');
        txt1.setAttribute('style','width:80px;'); 
        
      	txt2.setAttribute('id','gradePoints'+parseInt(start+i,10));
      	txt2.setAttribute('name','gradePoints[]');
      	txt2.setAttribute('type','text');      
        txt2.setAttribute('class','inputbox');      
      	txt2.setAttribute('maxlength','5');
      	txt2.setAttribute('style','width:80px;');
        
        
        txt5.setAttribute('id','failGrade'+parseInt(start+i,10));
        txt5.setAttribute('name','failGrade[]');
        txt5.setAttribute('type','text');      
        txt5.setAttribute('class','inputbox');      
        txt5.setAttribute('maxlength','20');
        txt5.setAttribute('style','width:120px;');
          
        txt6.setAttribute('id','gradeStatus'+parseInt(start+i,10));
        txt6.setAttribute('name','gradeStatus[]');
        txt6.setAttribute('class','inputbox');      
        txt6.setAttribute('type','text');      
        txt6.setAttribute('maxlength','30');
        txt6.setAttribute('style','width:120px;');
        
      
      	txt3.setAttribute('id','rd');
      	txt3.className='htmlElement';  
      	txt3.setAttribute('title','Delete');   
	    if(itemDelete!=''){    
      	       txt3.innerHTML="<?php echo NOT_APPLICABLE_STRING; ?>";	
	    }
	    else{
	       txt3.innerHTML='X';
      	       txt3.style.cursor='pointer';
       	       txt3.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');
	    }

	    txt4.setAttribute('id','hiddenGradeIdBox'+parseInt(start+i,10));
      	txt4.setAttribute('name','hiddenGradeIdBox[]');
      	txt4.setAttribute('type','hidden');     
      
      	cell1.appendChild(txt0);
      	cell2.appendChild(txt1);
      	cell3.appendChild(txt2);
        cell4.appendChild(txt3);
        cell4.appendChild(txt4);
        
        cell5.appendChild(txt5);
        cell6.appendChild(txt6);
        
             
      	tr.appendChild(cell1);
      	tr.appendChild(cell2);
      	tr.appendChild(cell3);
        tr.appendChild(cell5);
        tr.appendChild(cell6);
        tr.appendChild(cell4);
      
      	bgclass=(bgclass=='row0'? 'row1' : 'row0');
      	tr.className=bgclass;
      
      	tbody.appendChild(tr); 

	    var  id='hiddenGradeIdBox'+parseInt(start+i,10) ;
     	    eval("document.getElementById(id).value= -1");
        } 
        itemDelete='';
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
}   

//This function populates the values in the respective textboxes

var itemDelete='';

function populateGradeDescription(id) {
   cleanUpTable(); 
   var url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxGetValues.php';  
   
   new Ajax.Request(url,
   {
         method:'post',
         asynchronous:false,  
             parameters: {gradeId: id},
         onCreate: function(){
             showWaitDialog(true);
         },
         onSuccess: function(transport){
            hideWaitDialog(true);
            var j = eval('('+trim(transport.responseText)+')');   
            len=j.length;
            if(len>0) {
              for(i=0;i<len;i++) {
		itemDelete='';
		if(j[i]['gradeId1']!= ''){
		   itemDelete='1';
		}                
		addOneRow(1);
                 
                varFirst=i+1;
                id = "gradeLabel"+varFirst;
                eval("document.getElementById(id).value = j[i]['gradeLabel']"); 
                   
                id = "gradePoints"+varFirst;
                eval("document.getElementById(id).value = j[i]['gradePoints']");
                
		        id = "hiddenGradeIdBox"+varFirst;
                eval("document.getElementById(id).value = j[i]['gradeId']");
                
                id = "failGrade"+varFirst;
                eval("document.getElementById(id).value = j[i]['failGrade']");
                
                id = "gradeStatus"+varFirst;
                eval("document.getElementById(id).value = j[i]['gradeStatus']");
              }
              reCalculate();     
           } 
       },
       onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
    });
}   

//This function applies validations
var typeArray=new Array();

function validateDescription()
{
        typeArray.splice(0,typeArray.length);
	
	//check for unselected grade set.
	var ele=document.getElementById('tableDiv').getElementsByTagName("SELECT");
        if(document.addGradeDescription.gradeSetId.value==''){
             messageBox("<?php echo "Select Grade Set";?>");
             document.addGradeDescription.gradeSetId.className='inputBoxRed';
             document.addGradeDescription.gradeSetId.focus();
             return false;
         }
	
	var ele=document.getElementById('tableDiv').getElementsByTagName("INPUT");
        var len=ele.length;
      
         for(var i=0;i<len;i++){
	//check for empty grade.
             if(ele[i].name=='gradeLabel[]' && ele[i].value==''){
                     messageBox("<?php echo "Enter Grade";?>");
                     ele[i].className='inputboxRed';
                     ele[i].focus();
                     return false;
              }

	//check for empty grade points.
              else if(ele[i].name=='gradePoints[]' && ele[i].value==''){
                     messageBox("<?php echo "Enter Grade Points";?>");
                     ele[i].className='inputboxRed';
                     ele[i].focus();
                     return false;
              }

	//check whether grade value is between 0 and 127
	      if(ele[i].name=='gradePoints[]' && (parseFloat(trim(ele[i].value))<0 || parseFloat(trim(ele[i].value))>127) ){
                messageBox("<?php echo "Enter Grade Points value between 0 and 127";?>");
                ele[i].className='inputboxRed';
                ele[i].clear();
                ele[i].focus();
                return false;
	      }

	//check whether the grade point value is Float
	     else if(ele[i].name=='gradePoints[]' && ele[i].value != (parseFloat(trim(ele[i].value)))){
            messageBox("<?php echo "Enter Grade Points value between 0 and 127";?>");
            ele[i].className='inputboxRed';
            ele[i].clear();
            ele[i].focus();
            return false;
	      }

	//check for duplicate grade name
	      if(ele[i].name=='gradeLabel[]') {
                   var str = trim(ele[i].value.toUpperCase())+"_"+ele[i].name;
                   if(checkDuplicateValue(str)==0) {
                    	messageBox("<?php echo "Duplicate Grade Name";?>");
                    	ele[i].focus();
                    	return false;
                   }
              }

         }
	addGradeDescription();
}


//funciton to check duplicate values
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

//function to add grade in the database
function addGradeDescription() {
	url = '<?php echo HTTP_LIB_PATH;?>/Grade/ajaxInitAddDescription.php';

        param = generateQueryString('addGradeDescription');
	  new Ajax.Request(url,
           {
    	     method:'post',
             asynchronous:false,
             parameters: param, 
            
             onCreate: function() {
                 showWaitDialog(true); 
             },
             onSuccess: function(transport){
		 hideWaitDialog(true);
		 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
		    messageBox(trim(transport.responseText));
		    hiddenFloatingDiv('AddGradeDescription');
		    return false;
		 }
		 else{
		    messageBox(trim(transport.responseText));
		    return false;
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
	require_once(TEMPLATES_PATH . "/Grade/listGradeContents.php");
	require_once(TEMPLATES_PATH . "/footer.php");
?>

<SCRIPT LANGUAGE="JavaScript">
   
        sendReq(listURL,divResultName,searchFormName,'page='+page+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
   
</SCRIPT>    
</body>
</html>
