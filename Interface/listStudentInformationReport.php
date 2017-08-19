<?php
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::ifNotLoggedIn();
 //include_once(BL_PATH ."/StudentInformation/initList.php"); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo SITE_NAME;?>: Student Information Report </title>
<style>
	BR.page { page-break-after: always }
</style>
<?php 
require_once(TEMPLATES_PATH .'/jsCssHeader.php'); 
?> 
<script language="javascript">
function openPage(url){
var w=window.open(url,"StudentInformation","scrollbars=1,status=1,menubar=1,resizable=1,width=800,height=700");
}
function checkForm()
{
var chk = document.studentInformation.radioStudent.length;

var chkRadioStudent;

for(var i=0;i<chk;i++)
{
   if(document.studentInformation.radioStudent[i].checked==true)
   {
   chkRadioStudent = document.studentInformation.radioStudent[i].value;
   } 
   
}

if (!chkRadioStudent)

{
    alert('Please select the generation report');
    return false;
}

if(document.studentInformation.degree.value=="select")
{
    alert('Please select the degree');
    document.studentInformation.degree.focus();     
    return false;
}
else if (document.studentInformation.batch.value=="select"){
    alert('Please select the batch');
    document.studentInformation.batch.focus();     
    return false;

}
else if (document.studentInformation.studyPeriod.value=="select"){
    alert('Please select the study period');
    document.studentInformation.studyPeriod.focus();     
    return false;

}

if(document.getElementById('rollNo').value != "")
{            
    var rollNo = document.getElementById('rollNo').value;
    switch(chkRadioStudent){
    case 'admitCard': openPage('listStudentAdmitCard.php?rollNo='+rollNo);break;
    case 'iCard': openPage('listStudentIdentityCard.php?task=IC&rollNo='+rollNo);break;
    case 'busPass': openPage('listStudentIdentityCard.php?task=BP&rollNo='+rollNo);break;
    case 'hostelCard': openPage('listStudentIdentityCard.php?task=HC&rollNo='+rollNo);break;
    case 'libraryCard': openPage('listStudentIdentityCard.php?task=LC&rollNo='+rollNo);break;
    case 'photoGallery': openPage('listStudentPhotoGallery.php?rollNo='+rollNo);break;
	}
}
else{
    switch(chkRadioStudent){
    case 'admitCard': openPage('listStudentAdmitCard.php');break;
    case 'iCard': openPage('listStudentIdentityCard.php?task=IC');break;
    case 'busPass': openPage('listStudentIdentityCard.php?task=BP');break;
	case 'hostelCard': openPage('listStudentIdentityCard.php?task=HC');break;
	case 'libraryCard': openPage('listStudentIdentityCard.php?task=LC');break;
    case 'photoGallery': openPage('listStudentPhotoGallery.php');break;
    }
}


}

</script>



</head>
<body>
    <?php 
    require_once(TEMPLATES_PATH . "/header.php");
    require_once(TEMPLATES_PATH . "/StudentReports/studentIdentificationReport.php");
    require_once(TEMPLATES_PATH . "/footer.php");
    ?>
</body>
</html>

<?php
  //$History: listStudentInformationReport.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 5:59p
//Created in $/LeapCC/Interface
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/18/08    Time: 7:52p
//Updated in $/Leap/Source/Interface
//show print in IE explorer also
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/18/08    Time: 5:24p
//Updated in $/Leap/Source/Interface
//modification in error message
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/16/08    Time: 4:44p
//Updated in $/Leap/Source/Interface
//modified for report student information
//
//*****************  Version 5  *****************
//User: Admin        Date: 8/05/08    Time: 6:33p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 8/05/08    Time: 3:56p
//Updated in $/Leap/Source/Interface
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 7/15/08    Time: 2:51p
//Updated in $/Leap/Source/Interface
//remove bread crum
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/10/08    Time: 5:15p
//Updated in $/Leap/Source/Interface
//modification for print report
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/10/08    Time: 10:53a
//Created in $/Leap/Source/Interface
//student report for admit card, buspass, hostel card, identity card,
//library card, photo gallery

?>
