<?php
//used for taking bulk attendance
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
define('MODULE','BulkAttendance');
define('ACCESS','view');
UtilityManager::ifNotLoggedIn();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Bulk Attendance</title>
<?php
require_once(TEMPLATES_PATH .'/jsCssHeader.php');?>

<script language="javascript">

//to stop special formatting
specialFormatting=0;

//array for scroller
var pausecontent=new Array();

//---------------------------------------------------------------------------------
//Init function for scroll
//
//Author : Dipanjan Bhattacharjee
// Created on : (25.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------
function scroll_init(msg){
 return;
 var gMsg="";
 if(msg.length >0){
    gMsg=msg;
  }
  pausecontent=gMsg.split("+~+");
   //calls actual function to show the scroller
   //new pausescroller(pausecontent, "pscroller2", "someclass", 4000);
   arrNews=pausecontent;
   initTicker(pausecontent.length, 50, 4000, "");
}


// ajax search results ---start///
// table one index will contain key(table field name), cell label, cell width, align, sorting flag

var tableHeadArray = new Array(new Array('srNo','#','width="3%"','',false),
new Array('studentName','Name','width="15%"','',true) ,
new Array('rollNo','Roll No.','width="8%"','',true),
new Array('universityRollNo','Univ. Roll No.','width="10%"','',true),
new Array('memberOfClass','MOC','width="5%"','align="center"',false),
new Array('oldDelivered','Last','width="5%"','align="right"',false),
new Array('delivered','Delivered','width="6%"','',false) ,
new Array('oldAttended','Last','width="5%"','align="right"',false),
new Array('attended','Attended','width="6%"','',false) ,
new Array('percentage','Percentage','width="8%"','align="center"',false)

//,new Array('attendance','','width="0%"','',false)      //for having (attandanceId~studentId) format
);

//recordsPerPage = <?php echo RECORDS_PER_PAGE_TEACHER;?>;
recordsPerPage = 1000;
linksPerPage = <?php echo LINKS_PER_PAGE;?>;

listURL = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxInitBulkAttendanceList.php';
searchFormName = 'searchForm'; // name of the form which will be used for search
/*
addFormName    = 'AddBlock';
editFormName   = 'EditBlock';
winLayerWidth  = 315; //  add/edit form width
winLayerHeight = 250; // add/edit form height
deleteFunction = 'return deleteBlock';
*/
divResultName  = 'results';
page=1; //default page
sortField = 'universityRollNo';
sortOrderBy    = 'ASC';

// ajax search results ---end ///

var flag = false; // for whether the record has been added or not, if flag is true then refresh your page when user clicks on cancel button

//---------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO check whether all the drop-downs are selected or not
//
//Author : Dipanjan Bhattacharjee
// Created on : (14.7.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------



//this will be used in giveBulkAttendance function.If user changes values of dropdowns before
//hitting submit button then wrong values will go to database.To prevent that we will use these
//variables for temporary storage.
var sclass=0;
var ssub=0;
var sgroup=0;
var sfdate='';
var stdate='';
var slect=0;

var cdate="<?php echo date('Y-m-d'); ?>";
function getData(){
    if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
    }
    if(document.getElementById('employeeId').value==''){
        messageBox("<?php echo SELECT_TEACHER; ?>");
        document.getElementById('employeeId').focus();
        return false;
    }
    if(document.getElementById('classId').value==''){
        messageBox("<?php echo SELECT_CLASS; ?>");
        document.getElementById('classId').focus();
        return false;
    }
   if(document.getElementById('subject').value==''){
        messageBox("<?php echo SELECT_SUBJECT; ?>");
        document.getElementById('subject').focus();
        return false;
    }
   if(document.getElementById('group').value==''){
        messageBox("<?php echo SELECT_GROUP; ?>");
        document.getElementById('group').focus();
        return false;
    }


      if(trim(document.getElementById('startDate').value)==""){
        messageBox("<?php echo EMPTY_FROM_DATE; ?>");
        document.getElementById('startDate').focus();
        return false;
      }
      if(trim(document.getElementById('endDate').value)==""){
       messageBox("<?php echo EMPTY_TO_DATE; ?>");
        document.getElementById('endDate').focus();
        return false;
      }

     /*
	  if(trim(document.getElementById('startDate').value)!=""){
			fromDate = document.getElementById('startDate').value.split('-');
			toDate = document.getElementById('endDate').value.split('-');
			if (fromDate[1] != toDate[1]) {
				alert("The month of From Date & To Date should be same");
				document.getElementById('endDate').focus();
				return false;
			}

			if (fromDate[0] != toDate[0]) {
				alert("The Year of From Date & To Date should be same");
				document.getElementById('endDate').focus();
				return false;
			}

		}
      */

      if((document.getElementById('classId').value != "") && (document.getElementById('subject').value != "") && (document.getElementById('group').value != "") ){

         if(!dateDifference(document.getElementById('endDate').value,cdate,"-")){

           messageBox("<?php echo DATE_VALIDATION2; ?>");
           document.getElementById('endDate').focus();
         }
         else if(dateDifference(document.getElementById('startDate').value,document.getElementById('endDate').value,"-")){

             document.getElementById('topicsId').selectedIndex=-1;
             document.getElementById('commentTxt').value='';

              checkDailyAttendance();  //checking overlap between daily and bulk attendace.If no overlap then call sendReq
         }
         else{
          messageBox("<?php echo DATE_VALIDATION; ?>");
           document.getElementById('startDate').focus();
         }

       }
      else{
           messageBox("<?php echo BULK_SELECT_STUDENT_LIST; ?>");
           document.getElementById('class').focus();
      }

}


 //--------------------------------------------------------------------------------------
//Purpose:To Calcultae percentage of lecture delivered and attended
//Author: Dipanjan Bhattacharjee
//i: textbox number ; e:event for val1(e=1) or val2(e=2)
//Date:16.07.2008
//
//percentage check will be performed during form submit(percentage must be <=100 and not NaN)
//--------------------------------------------------------------------------------------
function changePercentage(i,e){

    val1="ldel"+i;  //lecture delivered
    val2="latt"+i;  //lecture attended
    val3="lcep"+i;  //lecture percentage
    val4="old_ldel"+i;  //lecture delivered(old)
    val5="old_att"+i;  //lecture attended(old)

	//value of lecture delivered textbox(Above)
    var LD=parseInt(trim(document.getElementById('lectureDelivered').value),10);

    //GIVING INSTANT ALERT IF SOMETHING WRONG IS INPUTTED

    /*
     If lectured delivered greater than main lectured delivered OR lectured delivered less than old lectured delivered
     If lectured attended greater than lectured delivered OR lectured attended less than old lectured attended
    */
    (parseInt(trim(document.getElementById(val1).value),10) > LD || parseInt(trim(document.getElementById(val1).value),10) < parseInt(trim(document.getElementById(val4).value),10) ) ? (document.getElementById(val1).className="inputboxRed") : document.getElementById(val1).className="inputbox";
    (parseInt(trim(document.getElementById(val2).value)) > parseInt(trim(document.getElementById(val1).value),10) || parseInt(trim(document.getElementById(val2).value)) < parseInt(trim(document.getElementById(val5).value),10)) ? (document.getElementById(val2).className="inputboxRed") : document.getElementById(val2).className="inputbox";


    //Current (LD-LA) MUST BE > =Old(LD-LA)
    if(parseInt(trim(document.getElementById(val1).value)-trim(document.getElementById(val2).value),10) < parseInt(trim(document.getElementById(val4).value)-trim(document.getElementById(val5).value),10)){
       document.getElementById(val2).className="inputboxRed";
    }


    //check for numeric value
    s = document.getElementById(val1).value.toString();
    var fl=0;

    for (var i = 0; i < s.length; i++){
     var c = s.charAt(i);
     if(!isInteger(c)){
      document.getElementById(val1).value=document.getElementById(val1).value.replace(c,"");
      fl=1;
    }
  }
  if(fl==1){
      document.getElementById(val1).focus();
      return false;
  }


 //check for numeric value
    s = document.getElementById(val2).value.toString();
    fl=0;
    for (var i = 0; i < s.length; i++){
     var c = s.charAt(i);
     if(!isInteger(c)){
      document.getElementById(val2).value=document.getElementById(val2).value.replace(c,"");
      fl=1;
    }
  }
  if(fl==1){
      document.getElementById(val2).focus();
      return false;
  }

   //check for "0" value
    if(parseInt(document.getElementById(val1).value,10)==0 || parseInt(document.getElementById(val2).value,10)==0 ){
          document.getElementById(val3).value="0.00%";
          return true;
    }

    //if all above conditions fail
    if(trim(document.getElementById(val1).value)!="" && trim(document.getElementById(val2).value)!=""){
     document.getElementById(val3).value=(((parseInt(document.getElementById(val2).value,10)/parseInt(document.getElementById(val1).value,10))*100)).toFixed(2)+"%";
     fl=1;
     return true;
    }
   else{
      document.getElementById(val3).value="0.00%";
      return true;
   }
 }

//--------------------------------------------------------------------------------------
//Purpose:To check lectureDelivered textbox input
//Author: Dipanjan Bhattacharjee
//value:value of  lectureDelivered textbox
//Date:16.07.2008
//--------------------------------------------------------------------------------------
var pastLectureDelivered=0;
function checkLectureDelivered(value){
  s = value.toString();
  if(trim(s)==''){
      return false;
  }
  var fl=0;
  for (var i = 0; i < s.length; i++){
    var c = s.charAt(i);
    if(!isInteger(c))  {
     //document.getElementById('lectureDelivered').value=document.getElementById('lectureDelivered').value.substring(0,(document.getElementById('lectureDelivered').value.length-1));
     document.getElementById('lectureDelivered').value=document.getElementById('lectureDelivered').value.replace(c,"");
     fl=1;
   }
  }
  if(fl==1){
     document.getElementById('lectureDelivered').focus();
     return false;
  }
  var lectureDeliverdByUser=parseInt(document.getElementById('lectureDelivered').value,10);
  var lc=document.listFrm.lcep.length-2;

  if(lectureDeliverdByUser < maxLastLectureDelivered){
       messageBox("Lecture Delivered cannot be less than "+maxLastLectureDelivered);
       document.getElementById('lectureDelivered').focus();
       return false;
  }
  if(lc >1){
var flag=0;
    for(var i=0; i < lc; i++){
       if(!document.listFrm.ldel[ i ].disabled){
		flag=1;
        var ld_old=parseInt(document.getElementById("old_ldel"+i).value,10);
        var la_old=parseInt(document.getElementById("old_att"+i).value,10);
        //document.listFrm.ldel[ i ].value=document.getElementById('lectureDelivered').value;
        //document.listFrm.latt[ i ].value=parseInt((document.getElementById('lectureDelivered').value-ld_old+la_old),10);
        if(operationMode==1){
           document.listFrm.ldel[ i ].value=(lectureDeliverdByUser-maxLastLectureDelivered)+ld_old;
           document.listFrm.latt[ i ].value=(lectureDeliverdByUser-maxLastLectureDelivered)+la_old;
        }
        else if(operationMode==2){
           document.listFrm.ldel[ i ].value=(lectureDeliverdByUser-pastLectureDelivered)+parseInt(document.listFrm.ldel[ i ].value,10);
           document.listFrm.latt[ i ].value=(lectureDeliverdByUser-pastLectureDelivered)+parseInt(document.listFrm.latt[ i ].value,10);
        }
       }
       changePercentage(i,1,0);
    }
	if(flag == 0){
		messageBox("<?php echo SELECT_ATLEAST_ONE_CHECK_BOX;?>");
	}
  }
 else if(lc==1){
    if(!document.listFrm.ldel.disabled){
     var ld_old=parseInt(document.getElementById("old_ldel0").value,10);
     var la_old=parseInt(document.getElementById("old_att0").value,10);

     document.listFrm.ldel.value= document.getElementById('lectureDelivered').value;
     document.listFrm.latt.value= parseInt((document.getElementById('lectureDelivered').value-ld_old+la_old),10);
    }
 }
  pastLectureDelivered=parseInt(document.getElementById('lectureDelivered').value,10);
  return true;
}



//--------------------------------------------------------------------------------------
//Purpose : To validate form input
//Author :Dipanjan Bhattacharjee
//Date : 156.07.2008
//--------------------------------------------------------------------------------------
function validateForm(frm){
    //number of percentage checkboxes
    var l=(document.listFrm.lcep.length-2); //subtracting 2 for two dummy fields
    if(l == 0){
        messageBox("<?php echo NO_DATA_SUBMIT; ?>");
        return false;
    }
    if(trim(document.getElementById('lectureDelivered').value)==""){
      if(document.listFrm.latt.length >1){   //as may be we are editing old records
        if(document.listFrm.ldel[ 0 ].value==""){
            messageBox("<?php echo EMPTY_LECTURE_DELIVERED; ?>");
            document.getElementById('lectureDelivered').focus();
            return false;
        }
      }
     else{
        if(document.listFrm.ldel.value==""){
           messageBox("<?php echo EMPTY_LECTURE_DELIVERED; ?>");
           document.getElementById('lectureDelivered').focus();
           return false;
        }
      }
    }

	if(document.getElementById('topicsId').value==""){
        messageBox("<?php echo SELECT_TOPICS_TAUGHT; ?>");
        document.getElementById('topicsId').focus();
        return false;
    }
 /*
   if(trim(document.getElementById('commentTxt').value)==""){
        messageBox("<?php echo ENTER_YOUR_COMMENTS; ?>");
        document.getElementById('commentTxt').focus();
        return false;
    }
 */
    var ld=parseInt(document.getElementById('lectureDelivered').value,10);
    var per;
    if(l==1){ //if only one row is there
      per=document.getElementById("lcep0").value.split("%") ;
      var ldd=parseInt(document.getElementById("ldel0").value,10);

      var ld_old=parseInt(document.getElementById("old_ldel0").value,10);
      var la_old=parseInt(document.getElementById("old_att0").value,10);

      if(trim(document.getElementById("ldel0").value)==""){
         messageBox("<?php echo EMPTY_LECTURE_DELIVERED; ?>");
         document.getElementById('ldel0').focus();
         return false;
      }
      if(parseInt(ldd,10) > ld){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED_MAX; ?>");
          document.getElementById("ldel0").focus();
          return false;
      }
      if(parseInt(ldd,10) == 0 && !document.getElementById("ldel0").disabled){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED_CLASS_MEMBER; ?>");
          document.getElementById("ldel0").focus();
          return false;
      }
      if(parseInt(per[0]) > 100 || per[0]=="NaN" || parseInt(document.getElementById("latt0").value,10) > ldd){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED; ?>");
          document.getElementById("latt0").focus();
          return false;
      }
     if(trim(document.getElementById("latt0").value)==""){
          messageBox("<?php echo EMPTY_LECTURE_ATTENDED; ?>");
          document.getElementById("latt0").focus();
          return false;;
     }

    if(parseInt(document.getElementById("ldel0").value,10) < ld_old){ //if lectured delivered less than already delivered
          messageBox("<?php echo OLD_LECTURE_DELIVERED_RESTRICTION; ?>");
          document.getElementById("ldel0").focus();
          return false;;
     }
    if(parseInt(document.getElementById("latt0").value,10) < la_old){ //if lectured attended less than already attended
          messageBox("<?php echo OLD_LECTURE_ATTENDED_RESTRICTION; ?>");
          document.getElementById("latt0").focus();
          return false;;
     }
    //Current (LD-LA) MUST BE > =Old(LD-LA)
    if(parseInt(trim(document.getElementById("ldel0").value)-trim(document.getElementById("latt0").value),10) < parseInt(ld_old-la_old,10)){
        //messageBox("<?php echo LECTURE_SUBSTRACTION_ERROR; ?>");
        messageBox("Lecture attended can not be more than "+((parseInt(trim(document.getElementById("ldel0").value))-ld_old)+la_old)+" for this time interval");
        document.getElementById("latt0").focus();
        return false;
    }

    }
   else{         //if there are more than one row
     for(var i=0;i<l;i++){
      per=document.getElementById("lcep"+i).value.split("%") ;
      var ldd=parseInt(document.getElementById("ldel"+i).value,10);

      var ld_old=parseInt(document.getElementById("old_ldel"+i).value,10);
      var la_old=parseInt(document.getElementById("old_att"+i).value,10);

      if(trim(document.getElementById("ldel"+i).value)==""){
         messageBox("<?php echo EMPTY_LECTURE_DELIVERED; ?>");
         document.getElementById('ldel'+i).focus();
         return false;
      }
      if(parseInt(ldd,10) > ld){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED_MAX; ?>");
          document.getElementById("ldel"+i).focus();
          return false;
      }
      if(parseInt(ldd,10) == 0 && !document.getElementById("ldel"+i).disabled){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED_CLASS_MEMBER; ?>");
          document.getElementById("ldel"+i).focus();
          return false;
      }
      if(parseInt(per[0]) > 100 || per[0]=="NaN" || parseInt(document.getElementById("latt"+i).value,10) > ldd){
          messageBox("<?php echo CHECK_ATTENDED_DELIVERED; ?>");
          document.getElementById("latt"+i).focus();
          return false;
      }
     if(trim(document.getElementById("latt"+i).value)==""){
          messageBox("<?php echo EMPTY_LECTURE_ATTENDED; ?>");
          document.getElementById("latt"+i).focus();
          return false;;
     }

    if(parseInt(document.getElementById("ldel"+i).value,10) < ld_old){ //if lectured delivered less than already delivered
          messageBox("<?php echo OLD_LECTURE_DELIVERED_RESTRICTION; ?>");
          document.getElementById("ldel"+i).focus();
          return false;;
     }
    if(parseInt(document.getElementById("latt"+i).value,10) < la_old){ //if lectured attended less than already attended
          messageBox("<?php echo OLD_LECTURE_ATTENDED_RESTRICTION; ?>");
          document.getElementById("latt"+i).focus();
          return false;;
    }

    //Current (LD-LA) MUST BE > =Old(LD-LA)
    if(parseInt(trim(document.getElementById("ldel"+i).value)-trim(document.getElementById("latt"+i).value),10) < parseInt(ld_old-la_old,10)){
        //messageBox("<?php echo LECTURE_SUBSTRACTION_ERROR; ?>");
        //messageBox("Lecture attended can not be more than "+(ld_old+la_old)+" for this time interval");
        messageBox("Lecture attended can not be more than "+((parseInt(trim(document.getElementById("ldel"+i).value))-ld_old)+la_old)+" for this time interval");
        document.getElementById("latt"+i).focus();
        return false;
    }

    }
   }
    giveBulkAttendance();
    return false;
}

//--------------------------------------------------------------------------------------
//Purpose:For Bulk Attendance
//Author:Dipanjan Bhattachaarjee
//Date : 16.07.2008
//--------------------------------------------------------------------------------------
function giveBulkAttendance() {

         url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxBulkAttendance.php';

         document.getElementById('classId').value=sclass ; document.getElementById('subject').value=ssub;
         document.getElementById('group').value=sgroup ; //document.getElementById('lectureDelivered').value=slec;
         document.getElementById('startDate').value=sfdate ; document.getElementById('endDate').value=stdate;
         //checkLectureDelivered(slec);

         var i=0;

         var studentId="";
         var attendanceId="";
         var del="";
         var memc="";

         if((document.listFrm.lcep.length-2) <= 1){
           var arr=document.listFrm.attendance.value.split("~");
           studentId=arr[1] ;
           attendanceId=arr[0];
           del=document.listFrm.ldel.value;
           att=document.listFrm.latt.value;
           memc=(document.listFrm.mem.checked ? "1" : "0" );
         }
        else{
         //detecting studentId and attendanceId(previous records)
         var studentatt=document.listFrm.attendance; //hidden field
         for(i=0; i <studentatt.length ; i++){
             var arr=document.listFrm.attendance[ i ].value.split("~");
             if(studentId==""){
                 studentId=arr[1]; //studentId
             }
            else{
                studentId=studentId+","+arr[1]; //studentId
            }
           if(attendanceId==""){
                 attendanceId=arr[0]; //attendanceId
             }
            else{
                attendanceId=attendanceId + "," + arr[0]; //attendanceId
            }
         }

         //alert(studentId);
         //alert(attendanceId);


         //detecting delivered list
         var delivered=document.listFrm.ldel;
         for(i=0; i < delivered.length ; i++){
             if(del==""){
                 del=document.listFrm.ldel[ i ].value;
             }
            else{
                 del=del + "," + document.listFrm.ldel[ i ].value;
            }
         }
         //alert(del);
         //detecting attended list
         var attended=document.listFrm.latt;
         var att="";
         for(i=0; i < attended.length ; i++){
             if(att==""){
                 att=document.listFrm.latt[ i ].value;
             }
            else{
                 att=att + "," + document.listFrm.latt[ i ].value;
            }
         }
         //alert(att);
         //detecting memberofclass list
         var member=document.listFrm.mem;
         for(i=0; i < member.length ; i++){
             if(memc==""){
                 memc=(document.listFrm.mem[ i ].checked ? "1" : "0" );
             }
            else{
                 memc=memc + "," + ( document.listFrm.mem[ i ].checked ? "1" : "0" );
            }
         }
        //alert(memc);
        }

		//this is used to seperate an array with ~
		form = document.searchForm;

		 totaltopics = form.elements['topicsId[]'].length;
			var name = document.getElementById('topicsId');
			selectedTopic='';
			countTopic=0;
			for(i=0;i<totaltopics;i++) {
				if (form.elements['topicsId[]'][i].selected == true) {
					if (selectedTopic != '') {
						selectedTopic += '~';
					}
					countTopic++;
					selectedTopic += form.elements['topicsId[]'][i].value;
				}
			}
			selectedTopic ='~'+selectedTopic+'~';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {studentIds: (studentId),
             attendanceIds: (attendanceId),
             delivered: (del),
             attended: (att),
             memofclass: (memc),
             fromDate:(document.getElementById('startDate').value),
             toDate:(document.getElementById('endDate').value),
             classId:(document.getElementById('classId').value),
             groupId:(document.getElementById('group').value),
             subjectId:(document.getElementById('subject').value),
			 subjectTopicId : selectedTopic,
			 taught:(document.getElementById('taught').value),
             comments:trim(document.getElementById('commentTxt').value),
             employeeId:(document.getElementById('employeeId').value),
             sortField :(sortField),
             sortOrderBy :(sortOrderBy)
             },
            onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                      if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {
                         flag = true;
                         messageBox('<?php echo BULK_ATTENDANCE_TAKEN;?>');
                         //sendReq(listURL,divResultName,searchFormName,'');
						 topicPopulate(document.getElementById('subject').value);
                         resetForm();
                     }
                     else {
                        messageBox(trim(transport.responseText));
                     }
            },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
           });
}


//--------------------------------------------------------------------------------------
//Purpose:Check For Daily Attendance Overlap with Bulk Attendance
//Author:Dipanjan Bhattachaarjee
//Date : 16.07.2008
//--------------------------------------------------------------------------------------

function checkDailyAttendance() {
         //document.getElementById('lectureDelivered').value="";
		 document.getElementById('results').innerHTML="";
         document.getElementById('divButton').style.display='none'

         url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxInitDailyAttendanceCheck.php';

         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
             startDate:(document.getElementById('startDate').value),
             endDate:(document.getElementById('endDate').value),
             classId :(document.getElementById('classId').value),
             subjectId:(document.getElementById('subject').value),
             groupId:(document.getElementById('group').value)
             },
            onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText) != 0){ //if overlaping occurs
                      j = eval('('+trim(transport.responseText)+')');
                      messageBox("<?php echo BULK_ATTENDANCE_RESTRICTION_FROM; ?> ( "+j[0].fromDate+" ) <?php echo BULK_ATTENDANCE_RESTRICTION_TO; ?> ( "+j[j.length-1].fromDate+" )");
                    }
                   else{
                      //if no overlap between daily and bulk attendace occurs
                      sclass=trim(document.getElementById('classId').value) ; ssub=trim(document.getElementById('subject').value);
                      sgroup=trim(document.getElementById('group').value) ; slec=trim(document.getElementById('lectureDelivered').value);
                      sfdate=document.getElementById('startDate').value; stdate=document.getElementById('endDate').value ;
                      document.getElementById('lectureDelivered').value="";

                      //checking for conflicting bulk attendance
                      checkBulkAttendanceConflict();
					  //document.getElementById('results').style.display='block';
					  //document.getElementById('divButton').style.display='block';


					  if (attendanceConflict == 0 ) {
					  	return false;
                      }
                      if(attendanceConflict > 0){
							sendReq(listURL,divResultName,searchFormName,'',false);
							if(trim(j.newAttendance) == true){
								document.getElementById('attendanceStatus').innerHTML="<?php echo 'NEW ATTENDANCE' ?>";
							}
							else{
								document.getElementById('attendanceStatus').innerHTML="<?php echo 'OLD ATTENDANCE' ?>";
							}
							document.getElementById('attendanceStatus').style.fontSize='1';
							if(document.listFrm.lcep.length >2){   //as may be we are editing old records
								 document.getElementById('lectureDelivered').value=maxLectureDelivered();//getting max of lecture delivered
							   //document.getElementById('lectureDelivered').value=document.listFrm.ldel[ 0 ].value
							}
							else{
							   if(document.listFrm.ldel){
								   document.getElementById('lectureDelivered').value=document.listFrm.ldel.value
							   }
							   else{
								   document.getElementById('lectureDelivered').value='';
							   }
							 }
							 document.getElementById('taught').value=j.topicTaughtId;
							 document.getElementById('results').style.display='block'
							 document.getElementById('divButton').style.display='block'

							 attendanceConflict=0;// reset the flag;
                      }

					  if (j.topicSubjectId == null || j.topicSubjectId == "" ) {
						  document.getElementById('taught').value=j.topicTaughtId;
						  //document.getElementById('topicsId').value=j.topicsId;
						  //document.getElementById('defaultAttCode').value="";
						  //document.getElementById('commentTxt').value=j.topicsComments;
						  document.getElementById('results').style.display='block';
						  document.getElementById('divButton').style.display='block';
						  return false;
					  }
					  else {
						  arr = j.topicSubjectId.split('~');
						  arrLen = arr.length;
						  topicLen =document.getElementById('topicsId').length;

						  if(topicLen) {
							  for(i=0;i<topicLen;i++) {
								for(m=0;m<arrLen;m++) {
								  if(document.getElementById('topicsId')[i].value == arr[m] ) {
										document.getElementById('topicsId').options[i].selected=true;
								  }
								}
							  }
						  }
					  }

						  document.getElementById('taught').value=j.topicTaughtId;
						  //document.getElementById('topicsId').value=j.topicsId;
						  document.getElementById('commentTxt').value=j.topicsComments;
						  document.getElementById('results').style.display='block';
						  document.getElementById('divButton').style.display='block';
                   }
              },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }


//--------------------------------------------------------------------------------------
//Puppose:To get the max lecture delivered
//Author:Dipanjan Bhttacharjee
//--------------------------------------------------------------------------------------
var maxLastLectureDelivered=0;
var operationMode=0;
function maxLectureDelivered(){
  var ret=0;
  var ret2=0;
  var i=0;
  maxLastLectureDelivered=0;
  var l=document.listFrm.ldel.length;
  for(var i=0; i< l;i++){
      if(parseInt(trim(document.listFrm.ldel[ i ].value),10) > ret){
          ret=parseInt(trim(document.listFrm.ldel[ i ].value),10);
      }
      if(parseInt(trim(document.listFrm.old_ldel[ i ].value),10) > ret2){
          ret2=parseInt(trim(document.listFrm.old_ldel[ i ].value),10);
      }
  }
  if(ret==0){
    operationMode=1;//for add
  }
  else{
      operationMode=2;//for edit
  }
  maxLastLectureDelivered=ret2;
  pastLectureDelivered=ret;
  return ret;
}


//--------------------------------------------------------------------------------------
//Purpose:Check For Daily Attendance Overlap with Bulk Attendance
//Author:Dipanjan Bhattachaarjee
//Date : 16.07.2008
//--------------------------------------------------------------------------------------
var attendanceConflict=0; //bulk attendance conflict flag;
function checkBulkAttendanceConflict() {

         url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxInitBulkAttendanceConflictCheck.php';
         //resetForm();

         new Ajax.Request(url,
           {
             method:'post',
             asynchronous: ( false ),
             parameters: {
             fromDate:(document.getElementById('startDate').value),
             toDate:(document.getElementById('endDate').value),
             classId :(document.getElementById('classId').value),
             subjectId:(document.getElementById('subject').value),
             groupId:(document.getElementById('group').value)
             },
            onCreate: function() {
                 showWaitDialog();
             },
             onSuccess: function(transport){
                     hideWaitDialog();
                     if(trim(transport.responseText)=="0"){
                         attendanceConflict=1; //no conflict
                     }
                     else {
                         if (trim(transport.responseText)=="<?php echo ATTENDANCE_EXISTS_SEPARATELY;?>") {
                             messageBox("<?php echo ATTENDANCE_EXISTS_SEPARATELY;?>");
                         }
                         else {

                             var j = eval('('+trim(transport.responseText)+')');
                             if(j.length>1){
                                  var cnt1=j.length;
                                  var sstr=''
                                  for(var i=0;i<cnt1;i++){
                                     if(sstr!=''){
                                         sstr +=',\n';
                                     }
                                     sstr +='( ' + j[i].fromDate +' ) To ( '+j[i].toDate +' )';
                                  }
                                  messageBox("<?php echo BULK_ATTENDANCE_RESTRICTION_EXISTING_SC; ?>" +sstr);
                                  attendanceConflict=0; //conflicting bulk attendnace  occurs
                             }
                             else if(dateEqual(j[0].fromDate,document.getElementById('startDate').value,'-') && dateEqual(j[0].toDate,document.getElementById('endDate').value,'-')){
                                 attendanceConflict=1; //no conflict
                             }
                             else{
                               messageBox("<?php echo BULK_ATTENDANCE_RESTRICTION_EXISTING_SC; ?> ( "+j[0].fromDate+" ) <?php echo BULK_ATTENDANCE_RESTRICTION_TO; ?> ( "+j[0].toDate+" )");
                               attendanceConflict=0; //conflicting bulk attendnace  occurs
                             }

                         }
                     }
              },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>"); }
           });
 }


//--------------------------------------------------------------------------------------
//Purpose:to make lecture attended o and readonly if memofclass is unchecked
//Author:Dipanjan Bhattachaarjee
//Date : 5.08.2008
//--------------------------------------------------------------------------------------
 function mocAction(id){
  if(document.listFrm.latt.length >1){
   document.getElementById('imageField2').tabIndex=document.getElementById('mem'+(document.listFrm.latt.length-1)).tabIndex+1;

   if(document.getElementById('mem'+id).checked){
     document.getElementById('latt'+id).disabled=false;
     document.getElementById('ldel'+id).disabled=false;
   }
  else{
      var ld_old=parseInt(document.getElementById("old_ldel"+id).value,10);
      var la_old=parseInt(document.getElementById("old_att"+id).value,10);
      document.getElementById('latt'+id).value=la_old;
      document.getElementById('ldel'+id).value=ld_old;
      changePercentage(id,2);
      document.getElementById('latt'+id).disabled=true;
      document.getElementById('ldel'+id).disabled=true;
  }
 }
else{
    if(document.getElementById('mem'+id).checked){
     document.getElementById('latt0').disabled=false;
     document.getElementById('ldel0').disabled=false;
   }
  else{
      var ld_old=parseInt(document.getElementById("old_ldel0").value,10);
      var la_old=parseInt(document.getElementById("old_att0").value,10);
      document.getElementById('latt0').value=la_old;
      document.getElementById('ldel0').value=ld_old;
      changePercentage(id,2);
      document.getElementById('latt0').disabled=true;
      document.getElementById('ldel0').disabled=true;
  }
 }
}

//--------------------------------------------------------------------------------------
//Purpose:to set the taborder
//Author:Dipanjan Bhattachaarjee
//Date : 5.08.2008
//--------------------------------------------------------------------------------------
function attAction(){
 if(document.listFrm.latt.length >1){
  document.getElementById('imageField2').tabIndex=document.getElementById('mem'+(document.listFrm.latt.length-1)).tabIndex+1;
 }
}

//--------------------------------------------------------------------------------------
//Purpose:to reset form fields
//Author:Dipanjan Bhattachaarjee
//Date : 5.08.2008
//--------------------------------------------------------------------------------------
function resetForm(){
 //document.getElementById('class').selectedIndex=0;
 //document.getElementById('subject').selectedIndex=0;
 //document.getElementById('group').selectedIndex=0;
 //document.getElementById('startDate').value="";
 //document.getElementById('endDate').value="";
 document.getElementById('lectureDelivered').value="";
 document.getElementById('results').style.display='none'
 document.getElementById('divButton').style.display='none'
 document.getElementById('classId').focus();
 document.getElementById('attendanceStatus').innerHTML='';
}

//--------------------------------------------------------------------------------------
//Purpose:To set the scroller according to the subject selected
//Author:Dipanjan Bhattacharjee
//Date:03.09.2008
//--------------------------------------------------------------------------------------
function setScroller(val){
    return;
    if(val!=0){
      if(arrNews.length > 0){
       document.getElementById('ticker').innerHTML=document.getElementById('hi_'+val).value;
       Stop(); //calls function to stop
      }
    }
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function topicPopulate(value) {
   url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAutoPopulateTopic.php';
   document.searchForm.topicsId.options.length=0;
  // var objOption = new Option("Select","");
  // document.searchForm.topicsId.options.add(objOption);

   if(document.getElementById('subject').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 subjectId  : document.getElementById('subject').value,
                 employeeId : document.getElementById('employeeId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
				    hideWaitDialog();
                    if(trim(transport.responseText)==0){
                      messageBox("<?php echo EMPTY_TOPICS_TAUGHT; ?>");
                    }
                    j = eval('('+transport.responseText+')');

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){

                         //var topic=j[c].topicAbbr;
                         //var objOption = new Option(topic,j[c].subjectTopicId+j[c].topicsTaughtId);
						 var objOption = new Option(j[c].topic,j[c].subjectTopicId);
                         document.searchForm.topicsId.options.add(objOption);
					 }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function autoPopulateEmployee(timeTableLabelId){
    clearData(1);
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetTeachers.php';

    if(timeTableLabelId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 timeTableLabelId: timeTableLabelId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].employeeName,j[c].employeeId);
                         document.searchForm.employeeId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

/*
function autoPopulateClass(employeeId){
    clearData(2);
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetClass.php';

    if(employeeId==''){
      return false;
    }

     new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 employeeId: employeeId
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].className,j[c].classId);
                         document.searchForm.classId.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}
*/

function populateSubjects(classId){
    clearData(3);
    //var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetSubjects.php';
    var url ='<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetAdjustedSubject.php';

    if(classId==''){
      return false;
    }
    if(document.getElementById('employeeId').value==''){
        return false;
    }
     new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 classId: classId,
                 employeeId :document.getElementById('employeeId').value,
                 startDate : document.getElementById('startDate').value,
                 endDate   : document.getElementById('endDate').value,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     for(var c=0;c<j.length;c++){
                       if(j[c].hasAttendance==1) {
                         var objOption = new Option(j[c].subjectCode,j[c].subjectId);
                         document.searchForm.subject.options.add(objOption);
                       }
                     }
                     if(j.length==1){
                         document.searchForm.subject.selectedIndex=1;
                         populateGroups(classId,document.searchForm.subject.value);
                         topicPopulate(document.searchForm.subject.value);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

//--------------------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO populate topic drop-down upon selection of  subject(Teacher Module)
//Author : Jaineesh
// Created on : (12.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------------------------------------------------------------
function populateGroups(classId,subjectId) {
   clearData(4);

   //var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGroupPopulate.php';
   var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetAdjustedGroup.php';

   if(classId=="" || subjectId=="" || document.getElementById('employeeId').value==""){
       return false;
   }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 subjectId: subjectId,
                 classId  : classId,
                 employeeId :document.getElementById('employeeId').value,
                 startDate : document.getElementById('startDate').value,
                 endDate   : document.getElementById('endDate').value,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    j = eval('('+transport.responseText+')');

                     var r=1;
                     var tname='';

                     for(var c=0;c<j.length;c++){
                         var objOption = new Option(j[c].groupName,j[c].groupId);
                         document.searchForm.group.options.add(objOption);
                     }
             },
             onFailure: function(){ alert('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

function clearData(mode){
	 document.getElementById('attendanceStatus').innerHTML='';
     document.getElementById('results').innerHTML='';
     document.getElementById('lectureDelivered').value="";
     document.getElementById('commentTxt').value="";
     document.getElementById('results').style.display='none';
     document.getElementById('divButton').style.display='none';
     document.getElementById('topicsId').selectedIndex=-1;

    if(mode==1){
        document.getElementById('employeeId').options.length=1;
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
    }
    else if(mode==2){
        document.getElementById('classId').options.length=1;
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
    }
    else if(mode==3){
        document.getElementById('subject').options.length=1;
        document.getElementById('group').options.length=1;
        document.getElementById('topicsId').options.length=0;
    }
   else if(mode==4){
       document.getElementById('group').options.length=1;
   }

 //  blankValues(1);
}


//this function fetches class data based upon user selected dates
function getClassData(){
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxGetAdjustedClass.php';
  var classEle=document.getElementById('classId');
  //classEle.options.length=1;
  clearData(2);

  if(document.getElementById('employeeId').value==''){
        return false;
  }

 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 employeeId       : document.getElementById('employeeId').value,
                 startDate        : document.getElementById('startDate').value,
                 endDate          : document.getElementById('endDate').value,
                 timeTableLabelId : document.getElementById('timeTableLabelId').value
             },
             onCreate: function(transport){
                  showWaitDialog();
             },
             onSuccess: function(transport){
                    hideWaitDialog();
                    var j = eval('('+transport.responseText+')');
                    for(var c=0;c<j.length;c++){
                       var objOption = new Option(j[c].className,j[c].classId);
                       classEle.options.add(objOption);
                    }
             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}

var selectedDate1="<?php echo date('Y-m-d')?>";
var selectedDate2="<?php echo date('Y-m-d')?>";
function refreshDropDowns(){
    if(selectedDate1!=document.getElementById('startDate').value || selectedDate2!=document.getElementById('endDate').value){
       selectedDate1=trim(document.getElementById('startDate').value);
       selectedDate2=trim(document.getElementById('endDate').value);
       if(selectedDate1=='' || selectedDate2==''){
           return false;
       }
       getClassData();
       document.getElementById('subject').options.length=1;
       document.getElementById('group').options.length=1;
       document.getElementById('lectureDelivered').value='';
       document.getElementById('commentTxt').value='';
       document.getElementById('topicsId').options.length=0;
       document.getElementById('results').style.display = 'none';
       document.getElementById('divButton').style.display = 'none';
       //document.getElementById('divButton1').style.display = 'none';
    }
}


var includeDateRange=0;
function fetchAttendanceHistory(range){
    includeDateRange=range;
    getAttendanceHistory();
}

//to get the attendance history list
function getAttendanceHistory(){

  if(document.getElementById('timeTableLabelId').value==''){
        messageBox("<?php echo SELECT_TIME_TABLE; ?>");
        document.getElementById('timeTableLabelId').focus();
        return false;
  }
  if(document.getElementById('employeeId').value==''){
       messageBox("<?php echo SELECT_TEACHER; ?>");
       document.getElementById('employeeId').focus();
       return false;
  }

  var employeeId=document.getElementById('employeeId').value;
  var employeeName=document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
  var timeTableLabelId=document.getElementById('timeTableLabelId').value;

  hideDropDowns(0); //hides drop downs

  document.getElementById('historyResults').innerHTML='';
  //var recordsPerPage2=<?php echo RECORDS_PER_PAGE;?>;
  var recordsPerPage2=100;
  var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxAttendanceHistoryList.php';
  /*
  var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('groupShort','Group','width="6%" align="left"',true),
                                new Array('subjectCode','Subject','width="8%" align="left"',true),
                                new Array('className','Class','width="12%" align="left"',true),
                                new Array('periodNumber','Period','width="5%" align="left"',true),
                                new Array('fromDate','From','width="5%" align="center"',true) ,
                                new Array('toDate','To','width="5%" align="center"',true),
                                new Array('attendanceType','Att. Type','width="6%" align="left"',true),
                                new Array('lectureDelivered','Lectures','width="4%" align="left"',true)
                             );
 */
 var tableHeadArray = new Array(
                                new Array('srNo','#','width="1%"','',false),
                                new Array('employeeName','Teacher','width="8%" align="left"',true),
                                new Array('subjectCode','Subject','width="5%" align="left"',true),
                                new Array('groupShort','Group','width="3%" align="left"',true),
                                new Array('className','Class','width="10%" align="left"',true),
                                new Array('periodNumber','Period','width="4%" align="left"',true),
                                new Array('fromDate','From','width="5%" align="center"',true) ,
                                new Array('toDate','To','width="5%" align="center"',true),
                                //new Array('attendanceType','Att. Type','width="6%" align="left"',true),
                                new Array('attendanceType','Type','width="4%" align="left"',true),
                                //new Array('lectureDelivered','Lec. taken','width="7%" align="right"',true),
                                new Array('lectureDelivered','Lec.','width="4%" align="right"',true),
                                new Array('topic','Topics','width="12%" align="left"',true),
                                new Array('actionString','Action','width="1%" align="center"',false)
                             );
 //var includeDateRange=document.getElementById('includeDateRange').checked ? 1 : 0 ;

 //url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array
 listObj = new initPage(url,recordsPerPage2,linksPerPage,1,'','employeeName','ASC','historyResults','','',true,'listObj',tableHeadArray,'','','&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&fromDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('endDate').value+'&attType=1'+'&employeeId='+employeeId+'&timeTableLabelId='+timeTableLabelId+'&includeDateRange='+includeDateRange);
 sendRequest(url, listObj, ' ',false);

 var dateString='';
 if(includeDateRange){
     dateString=' between '+ customParseDate(document.getElementById('startDate').value,'-') +' and '+customParseDate(document.getElementById('endDate').value,'-');
 }
 document.getElementById('divHeaderId4').innerHTML='Attendance history of '+employeeName+dateString;

 displayWindow('AttendanceHistoryDiv','850','320');
}


function editAttendance(classId,subjectId,groupId,dateValues){
    dateVal=dateValues.split('~!~');
    document.getElementById('startDate').value=dateVal[0];
    document.getElementById('endDate').value=dateVal[1];
    refreshDropDowns();
    document.getElementById('classId').value=classId;
    populateSubjects(classId);
    document.getElementById('subject').value=subjectId;
    document.getElementById('subject').value=subjectId;
    if(document.getElementById('subject').options.length>2){
     topicPopulate(subjectId);
    }

    populateGroups(classId,subjectId);
    document.getElementById('group').value=groupId;
    getData();
    hiddenFloatingDiv('AttendanceHistoryDiv');
}


//this function is used to delete attendance records
function deleteAttendanceData(attendanceId){
 if(attendanceId==''){
     messageBox("Invalid selection");
     return false;
 }
 if(false===confirm("<?php echo DELETE_CONFIRM;?>")) {
    return false;
 }

 hiddenFloatingDiv('AttendanceHistoryDiv');

 var url = '<?php echo HTTP_LIB_PATH;?>/AdminTasks/ajaxDeleteAttendanceData.php';
 new Ajax.Request(url,
           {
             method:'post',
             asynchronous:false,
             parameters: {
                 attendanceId : attendanceId,
                 employeeId   : document.getElementById('employeeId').value,
                 moduleName   : "<?php echo MODULE;?>"
             },
             onCreate: function(transport){
                  showWaitDialog(true);
             },
             onSuccess: function(transport){
                    hideWaitDialog(true);
                    if("<?php echo DELETE;?>"==trim(transport.responseText)) {
                     resetForm();
                     messageBox("Selected attendance record deleted");
                    }
                    else{
                        messageBox(trim(transport.responseText));
                    }

             },
             onFailure: function(){ messageBox('<?php echo TECHNICAL_PROBLEM;?>') }
           });
}


//this function is used to print attendance history report
function printReport(){
    //var includeDateRange=document.getElementById('includeDateRange').checked ? 1 : 0 ;
    var employeeId=document.getElementById('employeeId').value;
    var employeeName=document.getElementById('employeeId').options[document.getElementById('employeeId').selectedIndex].text;
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;
    var timeTableLabelName=document.getElementById('timeTableLabelId').options[document.getElementById('timeTableLabelId').selectedIndex].text;

    var className='';
    var subjectName='';
    var groupName='';
    if(document.getElementById('classId').selectedIndex>0){
     className=document.getElementById('classId').options[document.getElementById('classId').selectedIndex].text;
    }
    if(document.getElementById('subject').selectedIndex>0){
     subjectName=document.getElementById('subject').options[document.getElementById('subject').selectedIndex].text;
    }
    if(document.getElementById('group').selectedIndex>0){
     groupName=document.getElementById('group').options[document.getElementById('group').selectedIndex].text;
    }

    var qstr='&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&fromDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('endDate').value+'&attType=1'+'&includeDateRange='+includeDateRange+'&className='+className+'&subjectName='+subjectName+'&groupName='+groupName+'&employeeId='+employeeId+'&timeTableLabelId='+timeTableLabelId+'&employeeName='+employeeName+'&timeTableLabelName='+timeTableLabelName;
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    var path='<?php echo UI_HTTP_PATH;?>/attendanceHistoryPrint.php?'+qstr;
    window.open(path,"AttendanceHistoryReport","status=1,menubar=1,scrollbars=1, width=900, height=700, top=100,left=50");
}

//this function is used to make CSV version of attendance history report
function printCSV(){
    var employeeId=document.getElementById('employeeId').value;
    var timeTableLabelId=document.getElementById('timeTableLabelId').value;
    //var includeDateRange=document.getElementById('includeDateRange').checked ? 1 : 0 ;

    var qstr='&classId='+document.getElementById('classId').value+'&subjectId='+document.getElementById('subject').value+'&groupId='+document.getElementById('group').value+'&fromDate='+document.getElementById('startDate').value+'&toDate='+document.getElementById('endDate').value+'&attType=1'+'&includeDateRange='+includeDateRange+'&employeeId='+employeeId+'&timeTableLabelId='+timeTableLabelId;
    qstr=qstr+"&sortOrderBy="+listObj.sortOrderBy+"&sortField="+listObj.sortField;
    path='<?php echo UI_HTTP_PATH;?>/attendanceHistoryCSV.php?'+qstr;
    window.location=path;
}


function hiddenFloatingDiv(divId)
{
    hideDropDowns(1);
    //document.getElementById(divId).innerHTML = originalDivHTML;
    document.getElementById(divId).style.visibility='hidden';
    //document.getElementById('dimmer').style.visibility = 'hidden';
    document.getElementById('modalPage').style.display = "none";
    makeMenuDisable('qm0',false);
    over=false;

    DivID = "";
}

function hideDropDowns(mode){
    //show/hide in search filter
    var frmObj1=document.forms['searchForm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){
            frmObj1[i].style.display='none';
          }
          else{
              frmObj1[i].style.display='';
          }
        }
    }


    //show/hide in result divs
    var frmObj1=document.forms['listFrm'].elements;
    var objLength=frmObj1.length;
    for(var i=0;i<objLength;i++){
        if(frmObj1[i].type=='select-multiple' || frmObj1[i].type=='select-one'){
          if(mode==0){
            frmObj1[i].style.display='none';
          }
          else{
              frmObj1[i].style.display='';
          }
        }
    }
}


window.onload=function(){
 document.getElementById('timeTableLabelId').focus();
 document.searchForm.reset();
 document.getElementById('calImg1').onblur=refreshDropDowns;
 document.getElementById('calImg2').onblur=refreshDropDowns;
 if(document.getElementById('timeTableLabelId').selectedIndex>0){
   autoPopulateEmployee(document.getElementById('timeTableLabelId').value);
 }
}

</script>

</head>
<body>
<?php
//Purpose:Returns the lecture delivered box's value
//Author:Dipanjan Bhattacharjee
//Date:16.07.2008
function lectureDelivered($val){
 return $val;
}

?>
<?php
    require_once(TEMPLATES_PATH . "/header.php");
	require_once(TEMPLATES_PATH . "/AdminTasks/listBulkAttendanceContents.php");
    require_once(TEMPLATES_PATH . "/footer.php");
?>
</body>
</html>