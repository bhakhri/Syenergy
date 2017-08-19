<?php
//-------------------------------------------------------
// Purpose: To generate time table functionality
// Author : Ajinder Singh
// Created on : (11-Nov-2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
//require_once(BL_PATH . "/TimeTable/initList.php");
define('MODULE','ExtraClassesTimeTable');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Create Time Table</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
subjectsGroups = '';

function validatetTimetableForm(str) {
 
	var fieldsArray = new Array(new Array("periodSlotId","Please select period slot"),
                                new Array("timeTableLabelId","Please select time table"),
                                new Array("studentClass","Please select class"));

    var len = fieldsArray.length;
	var frm = document.timeTableForm;

    for(i=0;i<len;i++) {
        if(isEmpty(eval("frm."+(fieldsArray[i][0])+".value")) ) {
            //winAlert(fieldsArray[i][1],fieldsArray[i][0]);
            messageBox(fieldsArray[i][1]);
            eval("frm."+(fieldsArray[i][0])+".focus();");
            return false;
            break;
        }
    }
	 if (str == 'validate') {
		 getSubjectsGroups();
		 getCurrentTimeTable();
	 }
	 else {
		addTimeTable(str);
	 }
	 
	 return false;
}

function addTimeTable(str) {
   form = document.timeTableForm;
   pars = generateQueryString('timeTableForm');
   if (str == 'conflicts') {
	   pars += '&do=checkConflicts';
   }
   else {
	   pars += '&do=saveExtraClasses';
   }

   url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxInitAddExtraClasses.php';
  
   new Ajax.Request(url,
   {
	 method:'post',
	 parameters: pars,
     onCreate: function () {
         showWaitDialog(true);
     },
	 onSuccess: function(transport){
			
			hideWaitDialog(true);
			 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
				 
				 flag = true;
				 if(confirm("<?php echo SUCCESS;?> \n\n <?php echo ADD_MORE; ?>")) {
					 //document.getElementById('timeTableForm').reset(); 
				 }
				 else {
					  document.getElementById('timeTableForm').reset();
					  cleanUpTable();
					  hideAddRow();
					  form.studentClass.focus();
				 }
			 }
			 /*
			 else if (trim(transport.responseText) == "<?php echo EXTRA_CLASSES_TIMETABLE_DATE_CANNOT_BE_PAST; ?>") {
					messageBox(trim(transport.responseText));
					return;
			 }
			 */
			 else {
				//messageBox(trim(transport.responseText));
				res = trim(transport.responseText);
				displayWindow('Conflicts',500,250);
				document.getElementById('conflictMessage').innerHTML = res;
				//document.getElementById('addForm').reset(); 
			 }
	   },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


	
	
	var resourceAddCnt=0;
    // check browser
     var isMozilla = (document.all) ? 0 : 1;

    function addDetailRows(value){
         var tbl=document.getElementById('anyid');
         var tbody = document.getElementById('anyidBody');
         //var tblB    = document.createElement("tbody");
         if(!isInteger(value)){
            return false;
         }
         
         if(resourceAddCnt>0){     //if user reenter no of rows
          //if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
               cleanUpTable();
          //}
          //else{
            //  return false;
          //}
        } 
        resourceAddCnt=parseInt(value); 
        createRows(0,resourceAddCnt,0);
    }


    //for deleting a row from the table 
    function deleteRow(value){
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
     
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


    //to add one row at the end of the list
    function addOneRow(cnt) {
        //set value true to check that the records were retrieved but not posted bcos user marked them deleted
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
        //createRows(resourceAddCnt,cnt,eval("document.getElementById('teacher').innerHTML"),eval("document.getElementById('studentGroup').innerHTML"),eval("document.getElementById('room').innerHTML"));
        createRows(resourceAddCnt,cnt);
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

    var bgclass='';

    //create dynamic rows 
    
    //function createRows(start,rowCnt,optionData,sectionData,roomData){
function createRows(start,rowCnt){
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
	  var cell5=document.createElement('td');
	  var cell6=document.createElement('td'); 
	  /*
	  var cell7=document.createElement('td'); 
	  var cell8=document.createElement('td');
	  var cell9=document.createElement('td');
	  var cell10=document.createElement('td'); 
	  var cell11=document.createElement('td'); 
	  var cell12=document.createElement('td'); 
	  */
	  var cell13=document.createElement('td'); 
	  
	  cell1.setAttribute('align','left');      
	  cell2.setAttribute('align','left'); 
	  cell3.setAttribute('align','left'); 
	  cell4.setAttribute('align','left'); 
	  cell5.setAttribute('align','right');      
	  cell6.setAttribute('align','left'); 
	  /*
	  cell7.setAttribute('align','left'); 
	  cell8.setAttribute('align','left'); 
	  cell9.setAttribute('align','left'); 
	  cell10.setAttribute('align','left'); 
	  cell11.setAttribute('align','left'); 
	  cell12.setAttribute('align','left'); 
	  */
	  
	  if(start==0){
		var txt0=document.createTextNode(start+i+1);
	  }
	  else{
		var txt0=document.createTextNode(start+i);
	  }
	  var txt1=document.createElement('select');
	  var txt2=document.createElement('select');
	  var txt3=document.createElement('select');
	  var txt4=document.createElement('select');
	  var txt5=document.createElement('input');
	  /*
	  var txt6=document.createElement('input');
	  var txt7=document.createElement('input');
	  var txt8=document.createElement('input');
	  var txt9=document.createElement('input');
	  var txt10=document.createElement('input');
	  var txt11=document.createElement('input');
	  */
	  var txt12=document.createElement('a');
	  /*var hiddenId=document.createElement('input');
	  
	  hiddenId.setAttribute('id','hiddenId'+parseInt(start+i,10));
	  hiddenId.setAttribute('name','hiddenId[]');
	  hiddenId.setAttribute('type','hidden'); 
	  hiddenId.className='htmlElement';  */                           
	/*          
	  txt1.setAttribute('id','teacherId'+parseInt(start+i,10));
	  txt1.setAttribute('name','teacherId[]'); 
	  txt1.className='htmlElement';
				
	  txt2.setAttribute('id','subjectId'+parseInt(start+i,10));
	  txt2.setAttribute('name','subjectId[]');
	  txt2.className='htmlElement';
   
	  txt3.setAttribute('id','groupId'+parseInt(start+i,10));
	  txt3.setAttribute('name','groupId[]');
	  txt3.className='htmlElement';
		*/

	  txt1.setAttribute('id','subjectId'+parseInt(start+i,10));
	  txt1.setAttribute('name','subjectId[]');
	  txt1.className='htmlElement';
	  txt1.setAttribute('style','width:180px;');
	  thisCtr = parseInt(start+i,10);
	  txt1.onblur = new Function("getGroups('"+thisCtr+"')");

   
	  txt2.setAttribute('id','groupId'+parseInt(start+i,10));
	  txt2.setAttribute('name','groupId[]');
	  txt2.setAttribute('style','width:180px;');
	  txt2.className='htmlElement';

	  txt3.setAttribute('id','teacherId'+parseInt(start+i,10));
	  txt3.setAttribute('name','teacherId[]'); 
	  txt3.className='htmlElement';
	  txt3.setAttribute('style','width:180px;');
   


	  txt4.setAttribute('id','roomId'+parseInt(start+i,10));
	  txt4.setAttribute('name','roomId[]');
	  txt4.className='htmlElement';
	  txt4.setAttribute('style','width:180px;');
   
	  txt5.setAttribute('id','period'+parseInt(start+i,10));
	  txt5.setAttribute('name','period[]');
	  txt5.className='htmlElement';
	  txt5.setAttribute('type','text');
	  txt5.setAttribute('width','180');
	  txt5.setAttribute('size','20');

	  //hiddenIds.innerHTML=optionData;         
	  
	  
	  /*
	  txt6.setAttribute('id','tue'+parseInt(start+i,10));
	  txt6.setAttribute('name','tue[]'); 
	  txt6.className='htmlElement';  
	  txt6.setAttribute('width','60');
	  txt6.setAttribute('size','8');
	  txt6.setAttribute('type','text');
	  
	  txt7.setAttribute('id','wed'+parseInt(start+i,10));
	  txt7.setAttribute('name','wed[]'); 
	  txt7.className='htmlElement';  
	  txt7.setAttribute('width','60');
	  txt7.setAttribute('size','8');
	  txt7.setAttribute('type','text');
	  
	  txt8.setAttribute('id','thu'+parseInt(start+i,10));
	  txt8.setAttribute('name','thu[]'); 
	  txt8.className='htmlElement';  
	  txt8.setAttribute('width','60');
	  txt8.setAttribute('size','8');
	  txt8.setAttribute('type','text');
	  
	  txt9.setAttribute('id','fri'+parseInt(start+i,10));
	  txt9.setAttribute('name','fri[]'); 
	  txt9.className='htmlElement';  
	  txt9.setAttribute('width','60');
	  txt9.setAttribute('size','8');
	  txt9.setAttribute('type','text');
	  
	  txt10.setAttribute('id','sat'+parseInt(start+i,10));
	  txt10.setAttribute('name','sat[]'); 
	  txt10.className='htmlElement';  
	  txt10.setAttribute('width','60');
	  txt10.setAttribute('size','8');
	  txt10.setAttribute('type','text');
	  
	  txt11.setAttribute('id','sun'+parseInt(start+i,10));
	  txt11.setAttribute('name','sun[]'); 
	  txt11.className='htmlElement';  
	  txt11.setAttribute('width','60');
	  txt11.setAttribute('size','8');
	  txt11.setAttribute('type','text');
	  */
	  
	  txt12.setAttribute('id','rd');
	  txt12.className='htmlElement';  
	  txt12.setAttribute('title','Delete');       
	  
	  txt12.innerHTML='X';
	  txt12.style.cursor='pointer';
	  

	  txt12.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
	  
	  cell1.appendChild(txt0);
	  //cell1.appendChild(hiddenId);
	  cell2.appendChild(txt1);
	  cell3.appendChild(txt2);
	  cell4.appendChild(txt3);
	  cell5.appendChild(txt4);
	  cell6.appendChild(txt5);
	  /*
	  cell7.appendChild(txt6);
	  cell8.appendChild(txt7);
	  cell9.appendChild(txt8);
	  cell10.appendChild(txt9);
	  cell11.appendChild(txt10);
	  cell12.appendChild(txt11);
	  */
	  cell13.appendChild(txt12);
			 
	  tr.appendChild(cell1);
	  tr.appendChild(cell2);
	  tr.appendChild(cell3);
	  tr.appendChild(cell4);
	  tr.appendChild(cell5);
	  tr.appendChild(cell6);
	  /*
	  tr.appendChild(cell7);
	  tr.appendChild(cell8);
	  tr.appendChild(cell9);
	  tr.appendChild(cell10);
	  tr.appendChild(cell11);
	  tr.appendChild(cell12); 
	  */
	  tr.appendChild(cell13); 
	  
	  bgclass=(bgclass=='row0'? 'row1' : 'row0');
	  tr.className=bgclass;
	  
	  tbody.appendChild(tr); 
	  var len= document.getElementById('teacherHidden').options.length;
	  var t=document.getElementById('teacherHidden');
	  if(len>0) {
		var tt='teacherId'+parseInt(start+i,10) ;
		eval('form.'+tt+'.length = null');
		//alert(eval("document.getElementById(tt).length"));
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }

	  var len= document.getElementById('roomHidden').options.length;
	  var t=document.getElementById('roomHidden');
	  if(len>0) {
		var tt='roomId'+parseInt(start+i,10) ; 
		eval('form.'+tt+'.length = null');
		//alert(eval("document.getElementById(tt).length"));
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }

	  var len= document.getElementById('subjectHidden').options.length;
	  var t=document.getElementById('subjectHidden');
	  if(len>0) {
		var tt='subjectId'+parseInt(start+i,10) ; 
		eval('form.'+tt+'.length = null');
		//alert(eval("document.getElementById(tt).length"));
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }
	  /*
	  var tt='groupId'+parseInt(start+i,10);
	  addOption(document.getElementById(tt), '',  'Select       ');
	  */

	  
	  var len= document.getElementById('groupHidden').options.length;
	  var t=document.getElementById('groupHidden');
	  if(len>0) {
		var tt='groupId'+parseInt(start+i,10) ; 
		eval('form.'+tt+'.length = null');
		//alert(eval("document.getElementById(tt).length"));
		for(k=0;k<len;k++) { 
		  addOption(document.getElementById(tt), t.options[k].value,  t.options[k].text);
		 }
	  }
	  
  } 
  tbl.appendChild(tbody);   
}


function getGroups(ctr) {
	
	form = document.timeTableForm;
	//var subjectId='subjectId'+parseInt(ctr); 
	selectedSubject = document.getElementById('subjectId'+ctr).selectedIndex;
	if (selectedSubject == 0) {
		return false;
	}
	selectedSubjectTypeCode = subjectsGroups['subjects'][selectedSubject-1]['subjectTypeCode'];
	selectedSubjectOptional = subjectsGroups['subjects'][selectedSubject-1]['optional'];
	totalGroups = subjectsGroups['groups'].length;
	document.getElementById('groupId'+ctr).length = null;
	for(i=0; i<totalGroups; i++) { 
		groupTypeCode = subjectsGroups['groups'][i]['groupTypeCode'];
		groupOptional = subjectsGroups['groups'][i]['isOptional'];
		if ((selectedSubjectTypeCode == groupTypeCode) && (selectedSubjectOptional == groupOptional)) {
			addOption(document.getElementById('groupId'+ctr), subjectsGroups['groups'][i]['groupId'], subjectsGroups['groups'][i]['groupShort']);
		}
	}
}

function showAdjustments() {
	form = document.timeTableForm;
    if(form.studentClass.value=="") {
       messageBox("Please select class");
	   return false;
    }
	pars = 'classId='+form.studentClass.value+'&timeTableLabelId='+form.timeTableLabelId.value;
	url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getClassAdjustments.php';

    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous: false,
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
			hideWaitDialog(true);
			res = trim(transport.responseText);
			displayWindow('Adjustments',500,250);
			document.getElementById('adjustmentMessage').innerHTML = res;
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 

	return false;
}

function getSubjectsGroups() {
	form = document.timeTableForm;

    url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getClassSubjectsGroups.php';
    if(form.studentClass.value=="") {
       return false;
    }
	pars = 'classId='+form.studentClass.value;
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
         asynchronous: false,
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                j = trim(transport.responseText).evalJSON();
				subjectsGroups = j;
				len = j['subjects'].length;
                form.subjectHidden.length = null;
				addOption(form.subjectHidden, '', 'Select');
				
                for(i=0;i<len;i++) { 
					addOption(form.subjectHidden, j['subjects'][i]['subjectId'], j['subjects'][i]['subjectCode']);
				}
				

				len = j['groups'].length;
                form.groupHidden.length = null;
				addOption(form.groupHidden, '', 'Select');
				
                for(i=0;i<len;i++) { 
					addOption(form.groupHidden, j['groups'][i]['groupId'], j['groups'][i]['groupShort']);
				}

         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       }); 

}

function getTimeTableClasses() {
	form = document.timeTableForm;
	var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';
	var pars = 'labelId='+form.timeTableLabelId.value;

	if (form.timeTableLabelId.value=='') {
		form.studentClass.length = null;
		addOption(form.studentClass, '', 'Select');
		return false;
	}
	
	new Ajax.Request(url,
	{
		method:'post',
		parameters: pars,
		 onCreate: function(){
			 showWaitDialog(true);
		 },
		onSuccess: function(transport){
			hideWaitDialog(true);
			var j = eval('(' + transport.responseText + ')');
			len = j.length;
			form.studentClass.length = null;
			for(i=0;i<len;i++) {
				addOption(form.studentClass, j[i].classId, j[i].className);
			}
			// now select the value
			form.studentClass.value = j[0].classId;
		},
		onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
	});
	
}



function getCurrentTimeTable() {
	
	form = document.timeTableForm;

    url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getExtraClassesTimeTable.php';
    if(form.studentClass.value=="") {
       return false;
    }
	pars = 'classId='+form.studentClass.value+'&labelId='+form.timeTableLabelId.value+'&periodSlotId='+form.periodSlotId.value+'&date='+form.toDate.value;
    new Ajax.Request(url,
    {
         method:'post',
         parameters: pars,
		 asynchronous:false,
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
				hideWaitDialog(true);
                var ttResponse = eval('('+transport.responseText+')');
				var currentRow = 0;
				totalRows = ttResponse.length;
				addDetailRows(totalRows);
				
				while (currentRow < totalRows) {
					//form.elements['teacherId[]'][currentRow].value = ttResponse[currentRow]['employeeId'];
					teacherIdName = 'teacherId'+parseInt(currentRow,10);
					document.getElementById(teacherIdName).value = ttResponse[currentRow]['employeeId'];
					subjectIdName = 'subjectId'+parseInt(currentRow,10);
					document.getElementById(subjectIdName).value = ttResponse[currentRow]['subjectId'];
					//form.elements['subjectId[]'][currentRow].value = ttResponse[currentRow]['subjectId'];

					getGroups(currentRow);
					groupIdName = 'groupId'+parseInt(currentRow,10);
					document.getElementById(groupIdName).value = ttResponse[currentRow]['groupId'];

					roomIdName = 'roomId'+parseInt(currentRow,10);
					document.getElementById(roomIdName).value = ttResponse[currentRow]['roomId'];

					fieldValue = ttResponse[currentRow]['periods'];
					if (fieldValue === null) {
						fieldValue = '';
					}
					periodIdName = 'period'+parseInt(currentRow,10);
					document.getElementById(periodIdName).value = fieldValue;
					currentRow++;
				}
				
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
	   document.getElementById('addRowDiv').style.display='';
}


function hideAddRow() {
	document.getElementById('addRowDiv').style.display='none';
}


window.onload = function() {
	document.timeTableForm.periodSlotId.focus();
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/extraClassesTimetableContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: extraClassesTimeTable.php $
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 11/11/09   Time: 11:55a
//Created in $/LeapCC/Interface
//file added for extra classes
//


 
?>