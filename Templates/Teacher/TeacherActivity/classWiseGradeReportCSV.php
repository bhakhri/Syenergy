 <?php 
//This file is used as printing version for designations.
//
// Author :Jaineesh
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	
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


//used to parse csv data
	function parseCSVComments($comments) {
		 $comments = str_replace('"', '""', $comments);
		 $comments = str_ireplace('<br/>', "\n", $comments);
		 if(eregi(",", $comments) or eregi("\n", $comments)) {
		   return '"'.$comments.'"'; 
		 } 
		 else {
		 return $comments; 
		 }
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

/*$gradeStr="";
$searchCrieria=" Subject : ".$REQUEST_DATA['subjectName']." Class : ".$REQUEST_DATA['className']."  Group :".$REQUEST_DATA['groupName'];
$searchCrieria .="<br/>Name :".$REQUEST_DATA['studentName']."   Roll No. :".$REQUEST_DATA['studentRollNo'];*/

$recordCount = count($teacherGradeArray);

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
$valueArray = array();

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
		
		  $valueArray[] = array_merge(array('srNo' => ($j),'studentName'=>$sN,'rollNo'=>$sRollNo,'universityRollNo'=>$sUnivRollNo,'subject'=>$subN,'exam'=>$eT,'testType'=>$tTN,'test'=>$teacherGradeArray[$i]['testName'],'totalMarks'=>$teacherGradeArray[$i]['totalMarks'],'obtained'=>$teacherGradeArray[$i]['obtainedMarks']));
		
	 }
  }
	$csvData = '';
	$csvData .= "Sr.No., Name, Roll No., Univ. Roll No., Subject, Exam, Test Type, Test, T.Marks, Obtained \n";
	foreach($valueArray as $record) {
        $csvData .= $record['srNo'].',  '.parseCSVComments($record['studentName']).',  '.parseCSVComments($record['rollNo']).',  '.parseCSVComments($record['universityRollNo']).',  '.parseCSVComments($record['subject']).',  '.parseCSVComments($record['exam']).',  '.parseCSVComments($record['testType']).',  '.parseCSVComments($record['test']).', '.parseCSVComments($record['totalMarks']).', '.parseCSVComments($record['obtained']);
		$csvData .= "\n";
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="classMarksSummary.csv"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary\n");
	echo $csvData;
	die;
	
	//$History : $
?>
