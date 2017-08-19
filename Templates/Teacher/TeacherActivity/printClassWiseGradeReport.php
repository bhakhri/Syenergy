 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();  

    require_once(MODEL_PATH . "/Teacher/TeacherManager.inc.php");
	$teacherManager = TeacherManager::getInstance();
	
	/// Search filter /////  
    //------------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------
//Purpose: To parse the user submitted value in a space seperated string
//Author:Dipanjan Bhattacharjee
//Date:19.09.2008
//-----------------------------------------------------------------------------------
function parseName($value){
    $name=explode(' ',$value);
    $genName="";
    $len= count($name);
    if($len > 0){
      for($i=0;$i<$len;$i++){
          if(trim($name[$i])!=""){
              if($genName!=""){
                  $genName =$genName ." ".$name[$i];
              }
             else{
                 $genName =$name[$i];
             } 
          }
      }
    }
  if($genName!=""){
      $genName=" OR CONCAT(TRIM(s.firstName),' ',TRIM(s.lastName)) LIKE '".add_slashes($genName)."%'";
  }
  
  return $genName;
}


//creates the search condition
$conditions=" AND c.classId=".$REQUEST_DATA['classId']." AND su.subjectId=".$REQUEST_DATA['subjectId']." AND g.groupId=".$REQUEST_DATA['groupId'];
if(trim($REQUEST_DATA['studentRollNo'])!=''){
  $conditions .=' AND ( s.rollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" OR s.universityRollNo LIKE "'.add_slashes(trim($REQUEST_DATA['studentRollNo'])).'%" ) ';
}

if(trim($REQUEST_DATA['studentName'])!=""){
	$parsedName=parseName(trim($REQUEST_DATA['studentName']));    //parse the name for compatibality
	$conditions .=" AND (
							TRIM(s.firstName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%' 
							OR 
							TRIM(s.lastName) LIKE '".add_slashes(trim($REQUEST_DATA['studentName']))."%'
							$parsedName
						)"; 
}


$sortField=' studentName';
$sortBy =' ASC ';

if(trim($REQUEST_DATA['sortField'])==1){
    $sortField=' LENGTH(s.rollNo)+0,s.rollNo';
}
if(trim($REQUEST_DATA['sortField'])==2){
    $sortField=' LENGTH(s.universityRollNo)+0,s.universityRollNo';
}
if(trim($REQUEST_DATA['sortField'])==3){
    $sortField=' studentName';
}

if(trim($REQUEST_DATA['sortBy'])==1){
    $sortBy=' ASC';
}
if(trim($REQUEST_DATA['sortBy'])==0){
    $sortBy=' DESC';
}

$orderBy=$sortField.'  '.$sortBy;

$teacherGradeArray = $teacherManager->getClassWiseGradeList($conditions,' ',$orderBy);

$ustudentIds=UtilityManager::makeCSList($teacherGradeArray,'studentId');
    
    $ustudentIds=(array_unique(explode(',',$ustudentIds)));
    $totalRecords = count($ustudentIds);
    $totalPages = floor($totalRecords / 30);
    $balanceRecords = round($totalRecords % 30);
    if ($balanceRecords > 0) {
         $totalPages++;
    }
    $pageCounter = 1;

$gradeStr="";
$searchCrieria=" Subject : ".$REQUEST_DATA['subjectName']." Class : ".$REQUEST_DATA['className']."  Group :".$REQUEST_DATA['groupName'];
$searchCrieria .="<br/>Name :".$REQUEST_DATA['studentName']."   Roll No. :".$REQUEST_DATA['studentRollNo'];

$recordCount = count($teacherGradeArray);

$reportHeader='
<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
    <tr>
        <td align="left" colspan="1" width="25%" class="">'.$reportManager->showHeader().'</td>
        <th align="center" colspan="1" width="50%" '.$reportManager->getReportTitleStyle().'>
        '.$reportManager->getInstituteName().'</th>
        <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" '.$reportManager->getDateTimeStyle().'>'.date("d-M-y").'</td>
                </tr>
                <tr>
                    <td valign="" colspan="1" '.$reportManager->getDateTimeStyle().' align="right">Time :&nbsp;</td><td valign="" colspan="1" '. $reportManager->getDateTimeStyle().'>'.date("h:i:s A").'</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><th colspan="3" '.$reportManager->getReportHeadingStyle().' align="center">Display Marks Report</th></tr>

    <tr><th colspan="3" '.$reportManager->getReportInformationStyle().'>For '. $searchCrieria.'</th></tr>

    </table> <br>';
    
$reportFooter='<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
    <tr>
        <td align="left" colspan="8" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
    </tr>
    </table>';


$gradeStr1='<table border="1" cellspacing="0" width="90%" class="reportTableBorder"  align="center">
   <tr>
    <td width="3%" valign="middle" align="left" style="padding-left:3px" class = "headingFont"><b>Sr.No.</b>
    <td valign="middle" align="left" width="15%"'.$reportManager->getReportDataStyle().'><b>Name</b></td>
    <td valign="middle" align="left" width="12%"'.$reportManager->getReportDataStyle().'><b>Roll No.</b></td>
    <td valign="middle" align="left" width="12%"'.$reportManager->getReportDataStyle().'><b>Univ Roll. No.</b></td>
    <td valign="middle" align="left" width="12%"'.$reportManager->getReportDataStyle().'><b>Subject</b></td>
    <td valign="middle" align="left" width="8%"'.$reportManager->getReportDataStyle().'><b>Exam</b></td>
    <td valign="middle" align="left" width="12%"'.$reportManager->getReportDataStyle().'><b>TestType</b></td>
	<td valign="middle" align="left" width="12%"'.$reportManager->getReportDataStyle().'><b>Test</b></td>
	<td valign="middle" align="right" width="12%"'.$reportManager->getReportDataStyle().'><b>T.Mark</b></td>
    <td valign="middle" align="right" width="10%" style="padding-right:3px"'.$reportManager->getReportDataStyle().'><b>Obtained</b></td>
    </tr>';

	$gradeStr=$reportHeader.$gradeStr1;

if($recordCount >0 && is_array($teacherGradeArray) ) {
$studentName ="" ; $sN="";
$studentId ="" ;
$subjectName =""; $subN="";
$examType    ="";  $eT="";
$testTypeName="";  $tTN="";
$sRollNo='';
$sUnivRollNo='';
//$rollNo = "";
$j=0;
$k=0;
$fl=0;
for($i=0; $i<$recordCount; $i++ ) {
		$bg = $bg =='row0' ? 'row1' : 'row0';
		
		if($studentId==$teacherGradeArray[$i]['studentId']){
		   $sN="";
		   $sRollNo='';
		   $sUnivRollNo='';
		   $j="";$k++; 
		}
	   else{
		   $j=$i-$k+1;
		   $studentId=$teacherGradeArray[$i]['studentId'];
		   $studentName=$teacherGradeArray[$i]['studentName'];
		  /* 
		   if(trim($teacherGradeArray[$i]['rollNo'])!=""){
			$sN=$studentName.'<br />['.trim($teacherGradeArray[$i]['rollNo']).']';
		   }
		   else{
			   $sN=$studentName.'<br />[---]'; 
		   }
		   */
		   $sN=$studentName; 
		   $sRollNo=$teacherGradeArray[$i]['rollNo'];
		   $sUnivRollNo=$teacherGradeArray[$i]['universityRollNo'];
		   
		   $subjectName="";
		   $examType="";
		   $testTypeName="";
		 //if($i!=0)  //if it is not the first record
			//$gradeStr .='<tr><td colspan="10" ><hr></td></tr>'; //create a <hr> row as new student name found

		} 
	   if($subjectName==$teacherGradeArray[$i]['subjectName']){
		   $subN="";   
		}
	   else{
		   $subjectName=$teacherGradeArray[$i]['subjectName'];
		   //$subN=$subjectName." ( ".$teacherGradeArray[$i]['subjectCode']." )";
		   $subN=$teacherGradeArray[$i]['subjectCode'];
		   $examType="";
		   $testTypeName="";   
		}
	   if($examType==$teacherGradeArray[$i]['examType']){
		   $eT="";     
		}
	   else{
		   $examType=$teacherGradeArray[$i]['examType'];
		   $eT=$examType;
		   $testTypeName="";     
		}
	   if($testTypeName==$teacherGradeArray[$i]['testTypeName']){
		   $tTN="";     
		}
	   else{
		   $testTypeName=$teacherGradeArray[$i]['testTypeName'];
		   $tTN=$testTypeName;   
	   }      
		  
	 $gradeStr .= '<tr >
		<td valign="top" '.$reportManager->getReportDataStyle().' >'.$j.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$sN.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$sRollNo.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$sUnivRollNo.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$subN.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$eT.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$tTN.'</td>
		<td '.$reportManager->getReportDataStyle().'>'.$teacherGradeArray[$i]['testName'].'</td>
		<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$teacherGradeArray[$i]['totalMarks'].'</td> 
		<td valign="top" '.$reportManager->getReportDataStyle().' align="right">'.$teacherGradeArray[$i]['obtainedMarks'].'</td> 
		</tr>';    
	 
		 if($j % 30==0 and $j!=''){
		   $fl=1; 
		   $gradeStr .= '</table><br class="page" />';
		   $gradeStr .='
					   <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
					   <tr>
					   <td align="left" colspan="7" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
					   <td align="right" colspan="1" '.$reportManager->getFooterStyle().'>Page '.$pageCounter.' / '.$totalPages.'</td>
					   </tr>
					  </table>';
		  $gradeStr .= $reportHeader.$gradeStr1;
		  $pageCounter++;
		 }   
	 }
  }
  else {
   $gradeStr .= '<tr><td colspan="10" align="center" '.$reportManager->getReportDataStyle().'>'.NO_DATA_FOUND.'</td></tr></table>'.$reportFooter;
  }

  if($fl==1){
    $gradeStr .= '</table><br class="page" />';
    $gradeStr .='
               <table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
               <tr>
               <td align="left" colspan="7" '.$reportManager->getFooterStyle().'>'.$reportManager->showFooter().'</td>
               <td align="right" colspan="1" '.$reportManager->getFooterStyle().'>Page '.$pageCounter.' / '.$totalPages.'</td>
               </tr>
              </table>';
   echo $gradeStr;                              
 }
 else{
  echo $gradeStr;
 }
//$History : $
?>
