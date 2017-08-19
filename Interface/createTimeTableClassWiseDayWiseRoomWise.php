<?php
//-------------------------------------------------------
// Purpose: To generate time table functionality
// Author : Ajinder Singh
// Created on : (30.09.2009 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','CreateTimeTableClassWiseDayWiseRoomWise');
define('ACCESS','edit');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Manage Class,Day and Room Wise (Weekly) Time Table</title>
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
   if (str == 'conflicts') {
	   url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxInitAddAdvanced.php';
   }
   else {
	   url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/ajaxInitSaveAdvanced.php';
   }
   form = document.timeTableForm;
   pars = generateQueryString('timeTableForm');


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
					  form.studentClass.focus();
				 }
			 }
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

		form = document.timeTableForm;
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
          //var cell5=document.createElement('td');
          var cell6=document.createElement('td');
          var cell13=document.createElement('td');

          cell1.setAttribute('align','right');
          cell2.setAttribute('align','left');
          cell2.setAttribute('style','padding-left:3px');
          cell3.setAttribute('align','left');
          cell3.setAttribute('style','padding-left:3px');
          cell4.setAttribute('align','left');
          cell4.setAttribute('style','padding-left:3px');
          /*
		  cell5.setAttribute('align','left');
          cell5.setAttribute('style','padding-left:3px');
		  */
          cell6.setAttribute('align','left');
          cell6.setAttribute('style','padding-left:3px');

          if(start==0){
            var txt0=document.createTextNode(start+i+1);
          }
          else{
            var txt0=document.createTextNode(start+i);
          }
          var txt1=document.createElement('select');
          var txt2=document.createElement('select');
          var txt3=document.createElement('select');
          //var txt4=document.createElement('select');
          var txt5=document.createElement('input');
          var txt12=document.createElement('a');

          txt1.setAttribute('id','subjectId'+parseInt(start+i,10));
          txt1.setAttribute('name','subjectId[]');
          txt1.className='htmlElement';
		  thisCtr = parseInt(start+i,10);
          txt1.setAttribute('onBlur','getGroups('+thisCtr+')');

          txt2.setAttribute('id','groupId'+parseInt(start+i,10));
          txt2.setAttribute('name','groupId[]');
          txt2.className='htmlElement';

          txt3.setAttribute('id','teacherId'+parseInt(start+i,10));
          txt3.setAttribute('name','teacherId[]');
          txt3.className='htmlElement';

          /*
		  txt4.setAttribute('id','roomId'+parseInt(start+i,10));
          txt4.setAttribute('name','roomId[]');
          txt4.className='htmlElement';
		  */

          if (form.day.value == 1) {
			  txt5.setAttribute('id','mon'+parseInt(start+i,10));
			  txt5.setAttribute('name','mon[]');
          }
          else if (form.day.value == 2) {
			  txt5.setAttribute('id','tue'+parseInt(start+i,10));
			  txt5.setAttribute('name','tue[]');
          }
          else if (form.day.value == 3) {
			  txt5.setAttribute('id','wed'+parseInt(start+i,10));
			  txt5.setAttribute('name','wed[]');
          }
          else if (form.day.value == 4) {
			  txt5.setAttribute('id','thu'+parseInt(start+i,10));
			  txt5.setAttribute('name','thu[]');
          }
          else if (form.day.value == 5) {
			  txt5.setAttribute('id','fri'+parseInt(start+i,10));
			  txt5.setAttribute('name','fri[]');
          }
          else if (form.day.value == 6) {
			  txt5.setAttribute('id','sat'+parseInt(start+i,10));
			  txt5.setAttribute('name','sat[]');
          }
          else if (form.day.value == 7) {
			  txt5.setAttribute('id','sun'+parseInt(start+i,10));
			  txt5.setAttribute('name','sun[]');
          }

          txt5.className='htmlElement';
          txt5.setAttribute('size','"15"');
          txt5.setAttribute('type','text');

          //hiddenIds.innerHTML=optionData;


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
          //cell5.appendChild(txt4);
          cell6.appendChild(txt5);
          cell13.appendChild(txt12);

          tr.appendChild(cell1);
          tr.appendChild(cell2);
          tr.appendChild(cell3);
          tr.appendChild(cell4);
          //tr.appendChild(cell5);
          tr.appendChild(cell6);
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
				  document.getElementById(tt).options[k].style.color = document.getElementById('teacherHidden').options[k].style.color;
             }
          }

		  /*
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
		  */

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

/*
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
		optionalSubjectId = subjectsGroups['groups'][i]['optionalSubjectId'];

		if (optionalSubjectId != '' && optionalSubjectId != 'null' && optionalSubjectId != null) {
			if ((selectedSubjectTypeCode == groupTypeCode) && (selectedSubjectOptional == groupOptional) && (selectedSubjectId == optionalSubjectId)) {
				addOption(document.getElementById('groupId'+ctr), subjectsGroups['groups'][i]['groupId'], subjectsGroups['groups'][i]['groupShort']);
			}
		}
		else {
			if ((selectedSubjectTypeCode == groupTypeCode) && (selectedSubjectOptional == groupOptional)) {
				addOption(document.getElementById('groupId'+ctr), subjectsGroups['groups'][i]['groupId'], subjectsGroups['groups'][i]['groupShort']);
			}
		}
	}
}
*/

function getGroups(ctr) {

	form = document.timeTableForm;
	//var subjectId='subjectId'+parseInt(ctr);
	selectedSubject = document.getElementById('subjectId'+ctr).selectedIndex;
	if (selectedSubject == 0) {
		return false;
	}
	selectedSubjectId = document.getElementById('subjectId'+ctr).value;

	selectedSubjectTypeCode = subjectsGroups['subjects'][selectedSubject-1]['subjectTypeCode'];
	selectedSubjectOptional = subjectsGroups['subjects'][selectedSubject-1]['optional'];
	totalGroups = subjectsGroups['groups'].length;
	document.getElementById('groupId'+ctr).length = null;
	for(i=0; i<totalGroups; i++) {
		groupTypeCode = subjectsGroups['groups'][i]['groupTypeCode'];
		groupOptional = subjectsGroups['groups'][i]['isOptional'];
		optionalSubjectId = subjectsGroups['groups'][i]['optionalSubjectId'];

		if (optionalSubjectId != '' && optionalSubjectId != 'null' && optionalSubjectId != null) {
			if ((selectedSubjectTypeCode == groupTypeCode) && (selectedSubjectOptional == groupOptional) && (selectedSubjectId == optionalSubjectId)) {
				addOption(document.getElementById('groupId'+ctr), subjectsGroups['groups'][i]['groupId'], subjectsGroups['groups'][i]['groupShort']);
			}
		}
		else {
			if ((selectedSubjectTypeCode == groupTypeCode) && (selectedSubjectOptional == groupOptional)) {
				addOption(document.getElementById('groupId'+ctr), subjectsGroups['groups'][i]['groupId'], subjectsGroups['groups'][i]['groupShort']);
			}
		}
	}
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
	//var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetClasses.php';

        var url = '<?php echo HTTP_LIB_PATH;?>/StudentReports/initGetPrivilegesClasses.php';
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

    url = '<?php echo HTTP_LIB_PATH;?>/TimeTable/getClassTimeTableAdvanced.php';
    if(form.studentClass.value=="") {
       return false;
    }
	pars = 'classId='+form.studentClass.value+'&labelId='+form.timeTableLabelId.value+'&day='+form.day.value+'&roomId='+form.roomId.value+'&periodSlotId='+form.periodSlotId.value;
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

                var ttResponse = trim(transport.responseText).evalJSON();
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

					groupIdName = 'groupId'+parseInt(currentRow,10);
					document.getElementById(groupIdName).value = ttResponse[currentRow]['groupId'];


					monIdName = 'mon'+parseInt(currentRow,10);
					if (document.getElementById(monIdName)) {
						fieldValue = ttResponse[currentRow]['monday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(monIdName).value = fieldValue;
					}

					tueIdName = 'tue'+parseInt(currentRow,10);
					if (document.getElementById(tueIdName)) {
						fieldValue = ttResponse[currentRow]['tuesday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(tueIdName).value = fieldValue;
					}


					wedIdName = 'wed'+parseInt(currentRow,10);
					if (document.getElementById(wedIdName)) {
						fieldValue = ttResponse[currentRow]['wednesday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(wedIdName).value = fieldValue;
					}


					thuIdName = 'thu'+parseInt(currentRow,10);
					if (document.getElementById(thuIdName)) {
						fieldValue = ttResponse[currentRow]['thursday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(thuIdName).value = fieldValue;
					}

					friIdName = 'fri'+parseInt(currentRow,10);
					if (document.getElementById(friIdName)) {
						fieldValue = ttResponse[currentRow]['friday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(friIdName).value = fieldValue;
					}

					satIdName = 'sat'+parseInt(currentRow,10);
					if (document.getElementById(satIdName)) {
						fieldValue = ttResponse[currentRow]['saturday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(satIdName).value = fieldValue;
					}

					sunIdName = 'sun'+parseInt(currentRow,10);
					if (document.getElementById(sunIdName)) {
						fieldValue = ttResponse[currentRow]['sunday'];
						if (fieldValue === null) {
							fieldValue = '';
						}
						document.getElementById(sunIdName).value = fieldValue;
					}
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
	cleanUpTable();
     	hideAddRow();
     	getTimeTablePeriodSlotPopulate();
	document.timeTableForm.periodSlotId.focus();
}


</script>
</head>
<body>
    <?php
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/TimeTable/timetableClassWiseDayWiseRoomWiseContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: createTimeTableClassWiseDayWiseRoomWise.php $
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 4/17/10    Time: 4:29p
//Updated in $/LeapCC/Interface
//done changes as per FCNS No. 1601
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 11/02/09   Time: 12:01p
//Updated in $/LeapCC/Interface
//removed unwanted code.
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 10/12/09   Time: 10:35a
//Updated in $/LeapCC/Interface
//done changes to fix bugs:
//0001740, 0001738, 0001737, 0001736, 0001735, 0001728
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 10/06/09   Time: 11:09a
//Updated in $/LeapCC/Interface
//applied changes for multi-slot time table.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 10/03/09   Time: 4:05p
//Updated in $/LeapCC/Interface
//done changes for
//1. fetching groups based on subjects
//2. showing mba subjects.
//
//


?>
