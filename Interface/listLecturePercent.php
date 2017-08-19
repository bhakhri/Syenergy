<?php
//-------------------------------------------------------
// Purpose: To generate Attendance Marks Percent functionality
// Author : Jaineesh
// Created on : 30-03-09
// Copyright 2009-2010: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','LecturePercent');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title><?php echo SITE_NAME;?>: Lecture Percent</title>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 

<script type="text/javascript" language="javascript">

function hideValue() {
   
    document.getElementById('trLecture').style.display='none';
    document.getElementById('results').style.display='none';
    document.getElementById('results11').style.display='none';
    cleanUpTable();   
}


// Check integerValue
function valInteger(en) {
   if (false == isInteger(en.value)) {
        messageBox ("Enter interger value ");
        en.value="";
        en.focus();
        return false;
    }
    if (isInteger(en.value)<0) {
        messageBox ("Enter the value between 0 to 100");
        en.value="";
        en.focus();
        return false;
    }
}

function valDecimal(en) {
   if (false == isDecimal(en.value)) {
        messageBox ("Enter decmial value ");
        en.value="";
        en.focus();
        return false;
    }
    if (isDecimal(en.value)<0 || isDecimal(en.value)>100) {
        messageBox ("Marks Scored value between 0 to 100. ");
        en.value="";
        en.focus();
        return false;
    }
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
      if(confirm('Previous Data Will Be Erased.\n Are You Sure ?')){
           cleanUpTable();
      }
      else{
          return false;
      }
    } 

     resourceAddCnt=parseInt(value); 
     createRows(0,resourceAddCnt);
    }


    //for deleting a row from the table 
    function deleteRow(value){
	  resourceAddCnt--;
      var rval=value.split('~');
      var tbody1 = document.getElementById('anyidBody');
      
      var tr=document.getElementById('row'+rval[0]);
      tbody1.removeChild(tr);
     
		if(isMozilla == 1){
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
    function addOneRow(cnt)
    {
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
	 if (resourceAddCnt == 0) {
		 document.getElementById('trLecture').style.display='none';
	 }
	 else {
		 document.getElementById('trLecture').style.display='';
	 }
	 
     createRows(resourceAddCnt,cnt);
    }

    //to clean up table rows
    function cleanUpTable(){
		document.getElementById('trLecture').style.display='none';
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
     
      cell1.setAttribute('align','center');  
      cell2.setAttribute('align','center');     
      cell3.setAttribute('align','center'); 
      cell4.setAttribute('align','center'); 
      cell5.setAttribute('align','center'); 
	  cell6.setAttribute('align','center'); 
       
      if(start==0){
        var txt0=document.createTextNode(start+i+1);
      }
      else{
        var txt0=document.createTextNode(start+i);
      }
     // var txt0=document.createTextNode(i+1);
      
      var txt1=document.createElement('input');
      var txt2=document.createElement('input');
      var txt3=document.createElement('input');
	  var txt4=document.createElement('input');
      var txt5=document.createElement('a');
      
      
      txt1.setAttribute('id','lectureDelivered'+parseInt(start+i,10));
      txt1.setAttribute('name','lectureDelivered[]');
      //txt1.setAttribute('onBlur','valInteger(this)');
      txt1.className='inputbox1';
      txt1.setAttribute('size','"5"');
      txt1.setAttribute('maxlength','"3"');
      //txt4.onBlur='isIntegerComma(this)';
      txt1.setAttribute('type','text');
      
      txt2.setAttribute('id','lectureAttendedFrom'+parseInt(start+i,10));
      txt2.setAttribute('name','lectureAttendedFrom[]');
      //txt2.setAttribute('onBlur','valInteger(this)');
      txt2.setAttribute('maxlength','"3"');      
      txt2.className='inputbox1';
      txt2.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt2.setAttribute('type','text');

	  txt3.setAttribute('id','lectureAttendedTo'+parseInt(start+i,10));
      txt3.setAttribute('name','lectureAttendedTo[]');
      //txt3.setAttribute('onBlur','valInteger(this)');
      txt3.setAttribute('maxlength','"3"');      
      txt3.className='inputbox1';
      txt3.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt3.setAttribute('type','text');
      
      txt4.setAttribute('id','marksScored'+parseInt(start+i,10));
      txt4.setAttribute('name','marksScored[]');
      //txt4.setAttribute('onBlur','valDecimal(this)');   
      txt4.setAttribute('maxlength','"6"');
      txt4.className='inputbox1';
      txt4.setAttribute('size','"5"');
      //txt4.onBlur='isIntegerComma(this)';
      txt4.setAttribute('type','text');
       
      txt5.setAttribute('id','rd');
      txt5.className='inputbox1';  
      txt5.setAttribute('title','Delete');       
      txt5.innerHTML='X';
      txt5.style.cursor='pointer';
      txt5.setAttribute('href','javascript:deleteRow("'+parseInt(start+i,10)+'~0")');  //for ie and ff    
      
      
      cell1.appendChild(txt0);      
      cell2.appendChild(txt1);
      cell3.appendChild(txt2);
      cell4.appendChild(txt3);
      cell5.appendChild(txt4);
	  cell6.appendChild(txt5);
              
      tr.appendChild(cell1);                
      tr.appendChild(cell2);
      tr.appendChild(cell3);
      tr.appendChild(cell4);
      tr.appendChild(cell5);
	  tr.appendChild(cell6);
 
      bgclass=(bgclass=='row0'? 'row1' : 'row0');
      tr.className=bgclass;
      
      tbody.appendChild(tr); 
     } 
     tbl.appendChild(tbody);   
}


function validateAddForm(frm) {

    /*
    if(trim(document.getElementById('subjectTypeId').value)==""){
        messageBox("<?php echo SELECT_SUBJECT_TYPE; ?>");
        document.getElementById('subjectTypeId').focus();
        return false;
    }       
   */ 
   
    if(trim(document.getElementById('attendanceSetId').value)==""){
      messageBox("<?php echo SELECT_ATTENDANCE_SET; ?>");
      document.getElementById('attendanceSetId').focus();
      return false;
    } 
   
    if(resourceAddCnt==0) {
		msg = confirm('All values for this subject type will be deleted. Are you sure?')
        if(msg == false) {
			return false;
		}
    }
/* else {
            form = document.lecturePercentFrm;
            total = form.elements['lectureDelivered[]'].length;
            for(i=0;i<total;i++) {
                // Empty Box Check
                if(trim(form.elements['lectureDelivered[]'][i].value) == "") {
                   messageBox ("Lecture delivered field cannot be left blank");
                   form.elements['lectureDelivered[]'][i].focus();
                   return false;             
                }
                if(trim(form.elements['lectureAttendedFrom[]'][i].value) == "") {
                   messageBox ("Lecture Attended From field cannot be left blank");
                   form.elements['lectureAttendedFrom[]'][i].focus();
                   return false;             
                }
                if(trim(form.elements['lectureAttendedTo[]'][i].value) == "") {
                   messageBox ("Lecture Attended To field cannot be left blank");
                   form.elements['lectureAttendedTo[]'][i].focus();
                   return false;             
                }
                if(trim(form.elements['marksScored[]'][i].value) == "") {
                   messageBox ("Marks Scored field cannot be left blank");
                   form.elements['marksScored[]'][i].focus();
                   return false;             
                }
                
                // Integer & Decimal Value Checks updated
                if(!isInteger(trim(form.elements['lectureDelivered[]'][i].value))) {
                    messageBox ("Enter interger value for lecture delivered");
                    form.elements['lectureDelivered[]'][i].focus();
                    return false;
                }
                if(!isInteger(trim(form.elements['lectureAttendedFrom[]'][i].value))) {
                    messageBox ("Enter interger value for lecture attended from (0 to 100)");
                    form.elements['lectureAttendedFrom[]'][i].focus();
                    return false;
                }
                if(!isInteger(trim(form.elements['lectureAttendedTo[]'][i].value))) {
                    messageBox ("Enter interger value for lecture attended to (0 to 100)");
                    form.elements['lectureAttendedTo[]'][i].focus();
                    return false;
                }
                if(!isDecimal(trim(form.elements['marksScored[]'][i].value))) {   
                    messageBox ("Enter decimal value for marks scored (0 to 100)");
                    form.elements['marksScored[]'][i].focus();
                    return false;             
                }
                
                // Ranges Checks 
                if(form.elements['lectureAttendedFrom[]'][i].value < 0 || form.elements['lectureAttendedFrom[]'][i].value >100 ) {
                    messageBox ("Enter interger value for lecture attended from (0 to 100)");
                    form.elements['lectureAttendedFrom[]'][i].focus();
                    return false;
                }
                if(form.elements['lectureAttendedTo[]'][i].value < 0 || form.elements['lectureAttendedTo[]'][i].value >100 ) {
                    messageBox ("Enter interger value for lecture attended to (0 to 100)");
                    form.elements['lectureAttendedTo[]'][i].focus();
                    return false;
                }
                if(form.elements['marksScored[]'][i].value < 0 || form.elements['marksScored[]'][i].value >100 ) { 
                    messageBox ("Enter decimal value for marks scored (0 to 100)");   
                    form.elements['marksScored[]'][i].focus();
                    return false;             
                }
                
                if(form.elements['lectureAttendedFrom[]'][i].value > form.elements['lectureAttendedTo[]'][i].value) {
                    messageBox("Lecture attended from cannot be more than lecture attended to.");   
                    form.elements['lectureAttendedFrom[]'][i].focus();
                    return false;
                }
            }
            
            // Wrong Data Validation Checks
            for(i=0;i<total;i++) { 
              ifrom = form.elements['lectureAttendedFrom[]'][i].value;
              ito   = form.elements['lectureAttendedTo[]'][i].value;
              for(k= i+1; k < total; k++) {  
                jfrom = form.elements['lectureAttendedFrom[]'][k].value;
                jto   = form.elements['lectureAttendedTo[]'][k].value;    
                for(j=ifrom; j<ito; j++) {  
                   if(j == jfrom || j == jto) {
                      messageBox("Wrong data input.");    
                      return false;
                   }
                }     
              }
            }
    }    
*/

    addLecturePercent();
    return false;
}

function resetValues() {
	
    document.getElementById('lecturePercentFrm').reset();
	location.reload(true);

}


function showLecturePercent(){

/*   
	if(trim(document.getElementById('labelId').value)==""){
			messageBox("<?php echo SELECT_TIME_TABLE_LABEL; ?>");
			document.getElementById('labelId').focus();
			return false;
		}

	if(trim(document.getElementById('degree').value)==""){
		messageBox("<?php echo SELECT_DEGREE; ?>");
		document.getElementById('degree').focus();
		return false;
	}


	if(trim(document.getElementById('subjectTypeId').value)==""){
			messageBox("<?php echo SELECT_SUBJECT_TYPE; ?>");
			document.getElementById('subjectTypeId').focus();
			return false;
		}
*/
    if(trim(document.getElementById('attendanceSetId').value)==""){
      messageBox("<?php echo SELECT_ATTENDANCE_SET; ?>");
      document.getElementById('attendanceSetId').focus();
      return false;
    } 

   url = '<?php echo HTTP_LIB_PATH;?>/LecturePercent/ajaxLecturePercentGetValues.php';
   document.getElementById('results').style.display='';
   document.getElementById('results11').style.display='';  
   document.getElementById('trLecture').style.display='none';
   
   
  // resourceAddCnt=0; 
   cleanUpTable();   
   
   //if(document.lecturePercentFrm.subjectTypeId.value!='' ) {
   if(document.lecturePercentFrm.attendanceSetId.value!='' ) {
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {
             //subjectTypeId: document.getElementById('subjectTypeId').value,
			 //timeTableLabelId: document.getElementById('labelId').value,
			 //degreeId: document.getElementById('degree').value
             attendanceSetId : document.getElementById('attendanceSetId').value
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                //cleanUpTable();                              
               // alert(transport.responseText);
			   
                j = eval('('+trim(transport.responseText)+')');
				//alert(transport.responseText);
								
                len=j.lecturePercentArr.length;
				                 
                if(len>0) {
                    addOneRow(len);
                    resourceAddCnt=len;
					document.getElementById('results').style.display='';
                    document.getElementById('results11').style.display='';  
					document.getElementById('trLecture').style.display='';
                    for(i=0;i<len;i++) {
                        varFirst = i+1;
                        lectureDelivered = 'lectureDelivered'+varFirst;
                        lectureAttendedFrom = 'lectureAttendedFrom'+varFirst;
						lectureAttendedTo = 'lectureAttendedTo'+varFirst;
                        marksScored = 'marksScored'+varFirst;
                        eval("document.getElementById(lectureDelivered).value = j['lecturePercentArr'][i]['lectureDelivered']");
                        eval("document.getElementById(lectureAttendedFrom).value = j['lecturePercentArr'][i]['lectureAttendedFrom']");
						eval("document.getElementById(lectureAttendedTo).value = j['lecturePercentArr'][i]['lectureAttendedTo']");
                        eval("document.getElementById(marksScored).value = j['lecturePercentArr'][i]['marksScored']");
						
                   }
               }
			   
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
   }
}

function addLecturePercent() {
   url = '<?php echo HTTP_LIB_PATH;?>/LecturePercent/ajaxInitLecturePercent.php';
   
   document.getElementById('trLecture').style.display='none';
   
/* if (document.lecturePercentFrm.subjectTypeId.value != '') {
	   document.getElementById('trLecture').style.display='';
   }
*/   
   if (document.lecturePercentFrm.attendanceSetId.value != '') {
       document.getElementById('trLecture').style.display='';
   }
   params = generateQueryString('lecturePercentFrm');
   new Ajax.Request(url,
   {
     method:'post',
     parameters: params ,
     onCreate: function () {
             showWaitDialog(true);
     },
     onSuccess: function(transport){
        hideWaitDialog(true);    
        messageBox(trim(transport.responseText)); 
        if(trim("<?php echo SUCCESS;?>") == trim(transport.responseText) || trim("<?php echo SLAB_DELETE_SUCCESSFULLY;?>") == trim(transport.responseText)) {  
            //location.reload(); 
			cleanUpTable();
			resetValues();
			/*
            document.getElementById('subjectTypeId').value="";
			document.getElementById('degree').value = '';
			document.getElementById('labelId').focus();
            */
            document.getElementById('attendanceSetId').selectedIndex=0;  
            document.getElementById('attendanceSetId').focus();
			document.getElementById('results').style.display='none';
            document.getElementById('results11').style.display='none';  
			document.getElementById('trLecture').style.display='none';
            return false;
        }
     },
     onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}


function getClassDegree(){
   url = '<?php echo HTTP_LIB_PATH;?>/LecturePercent/ajaxAttendanceDegreeValues.php';
   document.getElementById('trLecture').style.display='none';
   document.getElementById('degree').length = 1;
  // resourceAddCnt=0; 
   cleanUpTable();   
   
   if(document.lecturePercentFrm.labelId.value!='' ) {
      new Ajax.Request(url,
      {
         method:'post',
         parameters: {
			 timeTableLabelId: document.getElementById('labelId').value
         },
         onCreate: function () {
             showWaitDialog(true);
         },
         onSuccess: function(transport){
                hideWaitDialog(true);
                //cleanUpTable();                              
               // alert(transport.responseText);
				var j = eval('('+trim(transport.responseText)+')');
				len=j.length;
								 
				if(len>0) {
					for(i=0;i<len;i++) {
						addOption(document.lecturePercentFrm.degree, j[i].degreeId, j[i].degreeCode);
				   }
			   }
			   
         },
         onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
       });
   }
}

window.onload=function() {
	//document.getElementById('labelId').focus();
    document.getElementById('attendanceSetId').focus();
}
</script>
</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/LecturePercent/listLecturePercentContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>
<?php
// $History: listLecturePercent.php $
//
//*****************  Version 10  *****************
//User: Parveen      Date: 12/29/09   Time: 6:52p
//Updated in $/LeapCC/Interface
//attendance Set Id base code updated
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 11/20/09   Time: 3:41p
//Updated in $/LeapCC/Interface
//Fixed error in IE browser, attendance marks slabs
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 11/20/09   Time: 1:56p
//Updated in $/LeapCC/Interface
//modification in code to show exact values
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:53a
//Updated in $/LeapCC/Interface
//modification in code if select different degree to show attendance
//marks slabs
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 11/20/09   Time: 10:34a
//Updated in $/LeapCC/Interface
//add new field degree in lecture percent and fixed bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 11/18/09   Time: 3:33p
//Updated in $/LeapCC/Interface
//Add Time Table Label dropdown and change in interface of attendance
//marks slabs. Now user can add the marks between the range for Lecture
//attended. 
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 3/31/09    Time: 11:42a
//Updated in $/LeapCC/Interface
//modified in messaging
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 3/31/09    Time: 11:19a
//Updated in $/LeapCC/Interface
//modified code to make it working even better
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 3/31/09    Time: 10:21a
//Updated in $/LeapCC/Interface
//modified to check some validations
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 3/30/09    Time: 1:42p
//Created in $/LeapCC/Interface
//new file to show lecture attended & delievered
//
//

?>