<?php

 //-------------------------------------------------------
//  This File contains code for still footer
//
//
// Author :Rahul Nagpal
// Created on : 03-Nov-2009
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>
<!------------------------ CSS FOR STATIC FOOTER----------------------------->
<style type="text/css">
	#containfooter{
    position:fixed;
    bottom:0px;
	width:1000px;
	}
	#fbutton{
	border:none;
	}
	#fbutton img{
	position:relative;
	border:none;
	}
	#footermenu ul{
	margin: 0; 
	padding: 0; 
	list-style: none; 
	}
	#footermenu li {
	float: left;  
	padding:0px 10px 0px 1px; 
	position:relative;
	top:1px;
	}
	#footerInuptField { 
	position:relative;
	top:1px;  
	border:none;
	height:17px;
	}
	#ftooltip{
	display:none; 
	padding:2px 3px;
	margin-left:0px; 
	}
	#searchicon{
	position:relative;
	top:4px;
	}
</style>

<script type="text/javascript">
/* FOR INTERNET EXPLORER FOR MAKING THE FOOTER STATIC*/
	var browser=navigator.appName;
	 if(browser=='Microsoft Internet Explorer'){
	document.write("<style type='text/css'>* html #containfooter{position:absolute;top:expression((0-(containfooter.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');} </style>");	
		 }
	
	/* Functions for showing the tooltip for footer icons*/
	
	function showftooltip(txt,no){
		if(browser=='Microsoft Internet Explorer'){
		document.getElementById('ftooltip').style.position='absolute';
		document.getElementById('ftooltip').style.top=-26+'px';
		document.getElementById('ftooltip').style.left=-parseInt(20*parseInt(no+no))+40+'px';
		}
		else{
		document.getElementById('ftooltip').style.position='relative';
		document.getElementById('ftooltip').style.top=-38+'px';
		document.getElementById('ftooltip').style.left=-parseInt(20*parseInt(no+no))-1+'px';
		//document.getElementById('footerSugguestion').style.top=-33+'px';
		//document.getElementById('footerSugguestion').style.left=-8+'px';
		}
		document.getElementById('ftooltip').style.display = 'inline';
		
		document.getElementById('ftooltip').style.color='BLACK';
		txt = "<table border='0' bgcolor='#ffffff' style='border:1px solid #000;'><tr><td nowrap='nowrap'>"+txt+"</td></tr></table>";
		document.getElementById('ftooltip').innerHTML = txt;
	}

	function hideftooltip(){
		document.getElementById('ftooltip').innerHTML = '';
		document.getElementById('ftooltip').style.display='none';
		//document.getElementById('footerSugguestion').style.top=5+'px';
		//document.getElementById('footerSugguestion').style.left=0+'px';
	}

	function sendKeys2(eleName, e){
	
	var ev = e||window.event;
	thisKeyCode = ev.keyCode;	
	if (thisKeyCode == '13') {
		if (eleName == 'footerButton') {
			doMove();
		}
		else if(eleName == 'searchicon'){
			searchSubmit();
		}       
        else if(eleName == 'listAdminStudentMessage'){
            searchSubmit2();
        }   
        else if(eleName == 'listStudentMessage'){
            searchSubmit3();
        } 
		else if(eleName =='searchCategory'){
			setFSearchCategory();
			changeColor(currentThemeId);
		}
		else{
			window.location=eleName;
		}
		return false;
	}
}

var footerFocusFlag=false;

function setFooterFocusFlag(elem)
{
	if(elem=='footerInuptField')
	{
		clearDefault();
	}
	footerFocusFlag=true;
}
function resetFooterFocusFlag(elem)
{
	footerFocusFlag=false;
}

dafaultVal='Search Student (RollNo./Name)';  // variable for the default value in the input text field
</script>

<?php
$teacherFooter = array
  (
  "Daily Attendance"=>array
				(
					'id'=>'listDailyAttendance',
					'link'=>'listDailyAttendance.php',
					'image'=>'dailyAttendance.gif'
				),
  "Enter Marks"=>array
				(
					'id'=>'listEnterAssignmentMarks',
					'link'=>'listEnterAssignmentMarks.php',
					'image'=>'enterMarks.gif'
				),
  "Send Message to Students"=>array
				(
					'id'=>'listStudentMessage',
					'link'=>'listStudentMessage.php',
					'image'=>'sendMessage.gif'
				),
	"Upload Resource"=>array
				(
					'id'=>'listCourseResource',
					'link'=>'listCourseResource.php',
					'image'=>'uploadresource.gif'
				),
	"Display Notices"=>array
				(
					'id'=>'listInstituteNotice',
					'link'=>'listInstituteNotice.php',
					'image'=>'notices.gif'
				),
	"Display Events"=>array
				(
					'id'=>'listInstituteEvent',
					'link'=>'listInstituteEvent.php',
					'image'=>'events.gif'
				),
    "Subject wise performance report"=>array
                (
                    'id'=>'listSubjectWisePerformance',
                    'link'=>'listSubjectWisePerformance.php',
                    'image'=>'StudentDemograhpics.gif'
                )
  );
  
$adminFooter = array
  (
   "Bulk Attendance"=>array
				(
					'id'=>'listBulkAttendance',
					'link'=>'listBulkAttendance.php',
					'image'=>'dailyAttendance.gif'
				),
   "Test Marks"=>array
				(
					'id'=>'listEnterAssignmentMarks',
					'link'=>'listEnterAssignmentMarks.php',
					'image'=>'enterMarks.gif'
				),
 "Manage Notices"=>array
				(
					'id'=>'listNotice',
					'link'=>'listNotice.php',
					'image'=>'manageNotices.gif'
				),
  "Display Teacher Load"=>array
				(
					'id'=>'displayLoadTeacherTimeTable',
					'link'=>'displayLoadTeacherTimeTable.php',
					'image'=>'teacherLoad.gif'
				),
  "Manage Class TimeTable"=>array
				(
					'id'=>'createTimeTableAdvanced',
					'link'=>'createTimeTableAdvanced.php',
					'image'=>'manageTimeTable.gif'
				),
 
  "Send Message to Students"=>array
				(
					'id'=>'listAdminStudentMessage',
					'link'=>'listAdminStudentMessage.php',
					'image'=>'sendMessage.gif'
				),
  "Send Message to Employees"=>array
				(
					'id'=>'listAdminEmployeeMessage',
					'link'=>'listAdminEmployeeMessage.php',
					'image'=>'sendmessageEmployee.gif'
				),
 "Percentagewise Attendance Report"=>array
				(
					'id'=>'studentPercentageWiseReport',
					'link'=>'studentPercentageWiseReport.php',
					'image'=>'attendanceReport.gif'
				),
  "Student Demographics"=>array
				(
					'id'=>'studentDemographics',
					'link'=>'studentDemographics.php',
					'image'=>'StudentDemograhpics.gif'
				),
  "Fee Payment History"=>array
				(
					'id'=>'feeCollection',
					'link'=>'paymentHistory.php',
					'image'=>'feePayment.gif'
				)            
  );
$studentFooter = array(
  "Display Attendence"=>array
				(
					'id'=>'listAttendance',
					'link'=>'listAttendance.php',
					'image'=>'dailyAttendance.gif'
				),
  "Test Marks"=>array
				(
					'id'=>'listStudentMarks',
					'link'=>'listStudentMarks.php',
					'image'=>'enterMarks.gif'
				)
  );

require_once(BL_PATH . "/UtilityManager.inc.php");
if($sessionHandler->getSessionVariable('RoleId')==1) {
?>
<script type='text/javascript'>
	/* this script for setting the search category i.e. search for employee or student in rollId=1 */
	searchCatFlag='Employee';
	
	function setFSearchCategory(){
		scat=document.getElementById('searchCategory');
		if(searchCatFlag=='Employee'){//for employee
			scat.src='".IMG_HTTP_PATH ."/employee.gif';
			document.getElementById('footerInuptField').value='Search Employee (Name/Code)';
			searchCatFlag='Student';
			dafaultVal='Search Employee (Name/Code)';
			return;
		}
		if(searchCatFlag=='Student'){//for student
			scat.src='".IMG_HTTP_PATH."/student.gif';
			document.getElementById('footerInuptField').value='Search Student (RollNo./Name)';
			searchCatFlag='Employee';
			dafaultVal='Search Student (RollNo./Name)';
			return;
		}
	}
	function genrateURL(){
	if(document.getElementById('footerInuptField').value!='')
	{
		if(searchCatFlag!='Student')
		{
			var regExp = /[0-9]/;
			var searchfor=document.getElementById('footerInuptField').value;
			if(searchfor.search(regExp)>=0){
			document.getElementById('footerInuptField').name='rollNo';
			}
			else{
			document.getElementById('footerInuptField').name='studentName';
			}
			document.footersearch.action='searchStudent.php';
			document.footersearch.submit();
		}
		else
		{
			document.getElementById('footerInuptField').name='searchbox';
			document.footersearch.action='listEmployee.php';
			document.footersearch.submit();
		}
	 }
	 else
	 {
		  return false;
	 }
	}
    function genrateURL2(){
    if(document.getElementById('footerInuptField').value!='')
    {
        if(searchCatFlag!='Student')
        {
            var regExp = /[0-9]/;
            var searchfor=document.getElementById('footerInuptField').value;
            if(searchfor.search(regExp)>=0){
            document.getElementById('footerInuptField').name='rollNo';
            }
            else{
            document.getElementById('footerInuptField').name='studentName';
            }
            document.footersearch.action='listAdminStudentMessage.php';
            document.footersearch.submit();
        }
        else
        {
            alert("Kindly click on search category icon to select search for student") ;
        }
     }
     else
     {
          return false;
     }
    }
	function searchSubmit(){
		searchFor=document.getElementById('footerInuptField').value;
		if(searchFor!='Search Student (RollNo./Name)' && searchFor!='Search Employee (Name/Code)' )
		{
			genrateURL();
		}
		else{
		messageBox('Please enter search data');
		clearDefault();
		document.getElementById('footerInuptField').focus();
		}
	}
    function searchSubmit2(){
        searchFor=document.getElementById('footerInuptField').value;
        if(searchFor!='Search Student (RollNo./Name)' && searchFor!='Search Employee (Name/Code)' )
        {
            genrateURL2();
        }
        else{
        window.location='listAdminStudentMessage.php';
        }
    }
	function clearDefault(){
	text=document.getElementById('footerInuptField');
	text.value='';
	}
	function setDefault(){
	var text=document.getElementById('footerInuptField'); 
	if(text.value==''){
	text.value=dafaultVal;
	}
}</script>
<?php
}
else if($sessionHandler->getSessionVariable('RoleId')==2) {
?>
<script type='text/javascript'>
 function genrateURL(){
	 if(document.getElementById('footerInuptField').value!=''){
		var regExp = /[0-9]/;
		var searchfor=document.getElementById('footerInuptField').value;
		if(searchfor.search(regExp)>=0){
		document.getElementById('footerInuptField').name='studentRollNo';
		}
		else{
		document.getElementById('footerInuptField').name='studentNameFilter';
		}
		document.footersearch.action='searchStudent.php';
		document.footersearch.submit();
	 }
	 else{
		 return false;
     }
 }
 function genrateURL3(){
     if(document.getElementById('footerInuptField').value!=''){
        var regExp = /[0-9]/;
        var searchfor=document.getElementById('footerInuptField').value;
        /*if(searchfor.search(regExp)>=0){
        document.getElementById('footerInuptField').name='studentRollNo';
        }
        else{
        document.getElementById('footerInuptField').name='studentNameFilter';
        }  */
        document.getElementById('footerInuptField').name='studentRollNo';
        document.footersearch.action='listStudentMessage.php';
        document.footersearch.submit();
     }
     else{
         return false;
     }
 }
	function searchSubmit(){
		searchFor=document.getElementById('footerInuptField').value;
		if(searchFor!='Search Student (RollNo./Name)'){
		genrateURL();
		}
		else{
		messageBox('Please enter search data');
		clearDefault();
		document.getElementById('footerInuptField').focus();
		}
	}
    function searchSubmit3(){
        
        searchFor=document.getElementById('footerInuptField').value;
        if(searchFor!='Search Student (RollNo./Name)'){
        genrateURL3();
        }
        else{
        window.location='listStudentMessage.php';
        }
    }
	function clearDefault(){
		text=document.getElementById('footerInuptField');
		text.value='';
	}
	function setDefault(){
		var text=document.getElementById('footerInuptField'); 
		if(text.value==''){
		text.value=dafaultVal;
		}
	}
</script>
<?php
}
?>

<!------------------ HTML FOR CONTAINING THE FOOTER--------------------------->

<div id="containfooter"> 	
	<div id="contents" style="float:left;" ><div id='footerleft' style="float:left;" ><input type="image" src="<?php echo IMG_HTTP_PATH;?>/footer_leftbar.gif"></div> <div id='footerright' style="float:right;"><input type="image" src="<?php echo IMG_HTTP_PATH;?>/footer_rightbar.gif"></div><div id="footermenu"><ul><?php 
	if($sessionHandler->getSessionVariable('RoleId')==1){
        
		$n=sizeof($adminFooter);
		echo("<li ><input type='image' id='searchCategory' src='".IMG_HTTP_PATH ."/student.gif' onclick='setFSearchCategory();changeColor(currentThemeId);' onmouseover='showftooltip(\"Search Category\",$n+7)' onmouseout='hideftooltip()' onkeypress=\"return sendKeys2('searchCategory',event);\"   onFocus=\"setFooterFocusFlag('searchCategory')\" onBlur=\"resetFooterFocusFlag('searchCategory')\" tabIndex='1' ></li><li><form name='footersearch' method=\"GET\" onSubmit=\"return genrateURL()\"><input type='text' value='Search Student (RollNo./Name)' style='color:#878999;' onclick='clearDefault()' id='footerInuptField' onBlur=\"javascript: setDefault(); resetFooterFocusFlag('footerInuptField');\" onFocus=\"setFooterFocusFlag('footerInuptField')\"  size='29' tabIndex='1' ></form><input type='image' src='".IMG_HTTP_PATH ."/footersearch.gif' id='searchicon' onclick='searchSubmit()' onkeypress=\"return sendKeys2('searchicon',event);\" onFocus=\"setFooterFocusFlag('searchicon')\" onBlur=\"resetFooterFocusFlag('searchicon')\" tabIndex='1' ></li>");
        $i=0;
       foreach($adminFooter as $value=>$link){
        if($i==5){
         echo("<li><input type='image' src='" . IMG_HTTP_PATH . "/sendMessage.gif' id='listAdminStudentMessage' onmouseover='showftooltip(\"Send Message to Students\",$n)'  onmouseout='hideftooltip()' onClick='searchSubmit2()' onkeypress=\"return sendKeys2('listAdminStudentMessage',event);\"  onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1' ></li>");    
        }
        else{   
		echo("<li><input type='image' src='" . IMG_HTTP_PATH . "/".$link['image']."'"." onmouseover='showftooltip(\"$value\",$n)'  onmouseout='hideftooltip()' onClick='javascript:window.location=\"".$link['link']."\"' onkeypress=\"return sendKeys2('".$link['link']."',event);\"  onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1' ></li>");
        }
		$n--;
        $i++;
		}
	}
	else if($sessionHandler->getSessionVariable('RoleId')==2) {			
					echo("<li >
							<form name='footersearch' method=\"GET\" onSubmit=\"return genrateURL()\"> 
								<input type='text' size='28' value='Search Student (RollNo./Name)' style='color:#878999;' onclick='clearDefault()' id='footerInuptField' onBlur=\"javscript: setDefault(); resetFooterFocusFlag('footerInuptField');\" onFocus=\"setFooterFocusFlag('footerInuptField')\" tabIndex='1' >
							</form><input type='image' src='" . IMG_HTTP_PATH . "/footersearch.gif' id='searchicon' onclick='searchSubmit()' onkeypress=\"return sendKeys2('searchicon',event);\"  onFocus=\"setFooterFocusFlag('searchicon')\" onBlur=\"resetFooterFocusFlag('searchicon')\" tabIndex='1' >
						</li>");
					$n=sizeof($teacherFooter);
                      $i=0;
					foreach($teacherFooter as $value=>$link) {
                          if($i==2){
         echo("<li><input type='image' src='" . IMG_HTTP_PATH . "/sendMessage.gif' id='listStudentMessage' onmouseover='showftooltip(\"Send Message to Students\",$n)'  onmouseout='hideftooltip()' onClick='searchSubmit3()' onkeypress=\"return sendKeys2('listStudentMessage',event);\"  onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1' ></li>");    
        }
		        else{ 
		        echo("<li><input type='image' src='".IMG_HTTP_PATH."/".$link['image']."'"." onmouseover='showftooltip(\"$value\",$n)'  onmouseout='hideftooltip()' onClick='javascript:window.location=\"".$link['link']."\"' onkeypress=\"return sendKeys2('".$link['link']."',event);\"  onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1'> 
		        </li>");
                }
	        $n--;
            $i++;
	        }
        }
        else if($sessionHandler->getSessionVariable('RoleId')==4) {
	        $n=sizeof($studentFooter);
	        foreach($studentFooter as $value=>$link) {
		        echo("<li><input type='image' src='".IMG_HTTP_PATH . "/".$link['image']."'"." onmouseover='showftooltip(\"$value\",$n)'  onmouseout='hideftooltip()' onClick='javascript:window.location=\"".$link['link']."\"' onkeypress=\"return sendKeys2('".$link['link']."',event);\" onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1' > 
		        </li>");
	        $n--;
	        }
        }
        
        $value="Subject Information";
        echo "<li><input type='image' src='".IMG_HTTP_PATH."/subject_info.gif' onmouseover='showftooltip(\"$value\",$n)'  onmouseout='hideftooltip()' onClick=\"showSubjectDetails('subjectBox',350,350);blankValues1();return false;\" onFocus=\"setFooterFocusFlag('icon')\" onBlur=\"resetFooterFocusFlag('icon')\" tabIndex='1'>";
	?>
<span id='ftooltip' style='font-size:12px;font-family:Arial;'></span>
<script type="text/javascript">
	if(browser=='Microsoft Internet Explorer'){
	  //document.write("<li><div id='footerSugguestion' style=\"float:right;position:relative;top:5px;left:12px;\" ><a href=\"\" onClick=\"showSuggesstionDetails('suggestionBox',350,350);blankValues1();return false;\" class=\"text\" style=\"color:#FFFFFF;\" tabIndex='1' >Suggest a feature</a></div><li>");
      document.write("<li><div id='footerSugguestion' style=\"float:right;position:relative;top:0px;left:12px;\" ><a href=\"\" onClick=\"showSuggesstionDetails('suggestionBox',350,350);blankValues1();return false;\" class=\"text\" style=\"color:#FFFFFF;\" tabIndex='1' ><img src='<?php echo IMG_HTTP_PATH;?>/suggest.gif' style='height:26px;' ></a></div><li>");
	}
</script></ul>
</div>
</div>
<div id="fbutton" style="float:right"><img id="footerButtonImage" src="<?php echo(IMG_HTTP_PATH);?>/cp_linkup.gif" onkeypress="return sendKeys2('footerButton',event);" onclick="doMove()" tabIndex='2' onFocus="setFooterFocusFlag('fbutton')" onBlur="resetFooterFocusFlag('fbutton')" ></div>
<script type="text/javascript">
if(browser!='Microsoft Internet Explorer'){
  //document.write("<div id='footerSugguestion' style=\"float:right;position:relative;top:5px;left:-5px;'\" ><a href=\"\" onClick=\"showSuggesstionDetails('suggestionBox',350,350);blankValues1();return false;\" class=\"text\" style=\"color:#FFFFFF;\" tabIndex='1' >Suggest a feature</a></div>");
  document.write("<div id='footerSugguestion' style=\"float:right;position:relative;top:1px;left:-5px;'\" ><a href=\"\" onClick=\"showSuggesstionDetails('suggestionBox',350,350);blankValues1();return false;\" class=\"text\" style=\"color:#FFFFFF;\" tabIndex='1' ><img src='<?php echo IMG_HTTP_PATH;?>/suggest.gif' style='height:26px;' ></a></div>");
  document.getElementById('footerInuptField').style.top=-2+'px';
}</script>
</div>
<script type="text/javascript"> 
/* script for the animation effect on the footer */
var footerFlag=false;
var footer=null;
var footerButton=null;
var Did=null;
var down=false;
var status="<?php echo($sessionHandler->getSessionVariable('staticfooter'));?>";
function doMoveDown() {		
	footer.style.height = parseInt(footer.style.height)-1+'px';
	if(parseInt(document.getElementById('footerSugguestion').style.top)<34){
	document.getElementById('footerSugguestion').style.top=parseInt(document.getElementById('footerSugguestion').style.top)+1+'px';
	}
	Did=setTimeout('doMoveDown()',10);// call doMove in 10msec
	if(parseInt(footer.style.height)<=0)
	{
		clearTimeout(Did);
		document.getElementById('contents').style.display='none';
		document.getElementById('footerSugguestion').style.display='none';
		down=true;
		Did=null;
		setFooterStatus('off');
		footerFlag=false;
	}
}
function doMoveUp() {	
	document.getElementById('contents').style.display='';
	document.getElementById('footerSugguestion').style.display='';
	footer.style.height = parseInt(footer.style.height)+1+'px';
	document.getElementById('footerSugguestion').style.top=parseInt(document.getElementById('footerSugguestion').style.top)-1+'px';
	Did=setTimeout('doMoveUp()',10);// call doMove in 10msec
	if(parseInt(footer.style.height)>=29)
	{
		clearTimeout(Did);
		down=false;
		setFooterStatus('on');
		footerFlag=false;
		document.getElementById('footerSugguestion').style.top=5+'px';		
	}
}
function doMove(){
	if(!footerFlag){
		footerFlag=true;
		if(!down)
		{		
			document.getElementById('footerButtonImage').src='<?php echo(IMG_HTTP_PATH);?>/cp_linkup.gif';
			doMoveDown();
		}
		else
		{
			document.getElementById('footerButtonImage').src='<?php echo(IMG_HTTP_PATH);?>/cp_linkdown.gif';
			doMoveUp();
		}
	}
}
function initFooter() {
	footer =  document.getElementById('contents'); 
	if(status=='on'){
		document.getElementById('footerButtonImage').src='<?php echo(IMG_HTTP_PATH);?>/cp_linkdown.gif';
		footer.style.height = '29px';
		down=false;
	}
	else{
	document.getElementById('footerButtonImage').src='<?php echo(IMG_HTTP_PATH);?>/cp_linkup.gif';
	footer.style.height = '0px';
	document.getElementById('contents').style.display='none';
	document.getElementById('footerSugguestion').style.display='none';
		document.getElementById('footerSugguestion').style.top=34+'px';	

	down=true;
	}
	
}
initFooter();
/* For saving  the status of the footer */
function setFooterStatus(stat) {
 url = '<?php echo HTTP_LIB_PATH;?>/setFooterStatus.php';
 new Ajax.Request(url,
   {
	 method:'post',
	 parameters: {status: stat
	 },
	 onSuccess: function(transport){
		 hideWaitDialog(true);
		 if("<?php echo SUCCESS;?>" == trim(transport.responseText)) {                     
		 } 
		 else {
			 alert('TECHNICAL_PROBLEM');
		 }
	 },
	 onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM;?>") }
   });
}
function width() {
	var myWidth = 0, myHeight = 0;
	if(typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		myWidth = window.innerWidth;
		myHeight = window.innerHeight;
	  } else if( document.documentElement && ( document.documentElement.clientWidth ||	document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		myWidth = document.documentElement.clientWidth;
		myHeight = document.documentElement.clientHeight;
	  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		myWidth = document.body.clientWidth;
		myHeight = document.body.clientHeight;
	  }
	  return myWidth;
}
var containfooterId=document.getElementById('containfooter');
var winWidth=width();
if(navigator.appName=='Microsoft Internet Explorer'){
containfooterId.style.left = parseInt((winWidth -1005)/2)+2.5+'px';
}
else{

containfooterId.style.left = parseInt((winWidth -1005)/2)-4+'px';
}
</script>
<?php
//$History: dynamicFooter.php $
//
//*****************  Version 21  *****************
//User: Parveen      Date: 4/15/10    Time: 5:23p
//Updated in $/LeapCC/Templates
//subjectInformation new functionality added
//
//*****************  Version 20  *****************
//User: Dipanjan     Date: 30/03/10   Time: 17:41
//Updated in $/LeapCC/Templates
//Modified UI of "Suggest A Feature" pop-up div
//
//*****************  Version 19  *****************
//User: Gurkeerat    Date: 12/15/09   Time: 11:44a
//Updated in $/LeapCC/Templates
//updated funtionality for 'send message to students' icon in footer
//
//*****************  Version 18  *****************
//User: Rahul.nagpal Date: 11/16/09   Time: 1:28p
//Updated in $/LeapCC/Templates
//alignment issue resolved
//
//*****************  Version 15  *****************
//User: Rahul.nagpal Date: 11/14/09   Time: 12:55p
//Updated in $/LeapCC/Templates
//#0001978 resolved.
//
//*****************  Version 14  *****************
//User: Rahul.nagpal Date: 11/13/09   Time: 2:35p
//Updated in $/LeapCC/Templates
//issues #002005,#002007 resolved
//
//*****************  Version 13  *****************
//User: Rahul.nagpal Date: 11/12/09   Time: 5:22p
//Updated in $/LeapCC/Templates
//Fee Payment History link modified.
//
//*****************  Version 12  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 2:20p
//Updated in $/LeapCC/Templates
//issues resolved #1949,#1977.
//
//*****************  Version 11  *****************
//User: Rahul.nagpal Date: 11/09/09   Time: 2:58p
//Updated in $/LeapCC/Templates
//
//*****************  Version 10  *****************
//User: Rahul.nagpal Date: 11/09/09   Time: 2:57p
//Updated in $/LeapCC/Templates
//tab order issue has been resolved
//
//*****************  Version 9  *****************
//User: Rahul.nagpal Date: 11/09/09   Time: 12:21p
//
//*****************  Version 8  *****************
//User: Rahul.nagpal Date: 11/09/09   Time: 12:09p
//Updated in $/LeapCC/Templates
//search icon issue resolved
//
//*****************  Version 7  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 5:34p
//Updated in $/LeapCC/Templates
//
//*****************  Version 6  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 1:21p
//Updated in $/LeapCC/Templates
//
//*****************  Version 5  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 12:33p
//Updated in $/LeapCC/Templates
//
//*****************  Version 4  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 11:40a
//Updated in $/LeapCC/Templates
//
//*****************  Version 3  *****************
//User: Rahul.nagpal Date: 11/06/09   Time: 11:36a
//Updated in $/LeapCC/Templates
//Added Bar Contents for Admin
//
//*****************  Version 2  *****************
//User: Rahul.nagpal Date: 11/04/09   Time: 5:06p
//Updated in $/LeapCC/Templates
//function name init changed to initFooter
//
//*****************  Version 1  *****************
//User: Rahul.nagpal Date: 11/03/09   Time: 3:59p
//Created in $/LeapCC/Templates
//contains the code for still footer
//
?>


