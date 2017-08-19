<?php
//-------------------------------------------------------
// THIS FILE IS USED TO upload student Detail
//
// Author : Jaineesh
// Created on : (14.11.2009)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//                      
//--------------------------------------------------------

	set_time_limit(0);
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	require_once(BL_PATH . "/FileUploadManager.inc.php");
	require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
	define('MODULE','UploadTopicDetail');
	define('ACCESS','add');
	UtilityManager::ifNotLoggedIn(true);
	global $sessionHandler;
	
    $fileObj = FileUploadManager::getInstance('topicDetailUploadFile');
	$filename = $fileObj->tmp;
	
	
	if ($filename == '') {
		echo ('<script type="text/javascript">alert("Please Select File");</script>');
		die;
	}
	
	if ($fileObj->fileExtension != 'xls') {
		
		echo ('<script type="text/javascript">alert("Please Select Excel File");</script>');
		die;
	}
	
	require_once(MODEL_PATH . "/TopicManager.inc.php");
	$topicManager = TopicManager::getInstance();

	require_once(BL_PATH . "/reader.php");
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read($filename);
    
	$m=0;
	$sheetNameArray = array();
	while(isset($data->boundsheets[$m]['name'])) {
		$sheetNameArray[] =  $data->boundsheets[$m]['name'];
		$m++;
	}

    $str = '';
	$totalRecordCounter = 0;
	
	$inconsistenciesArray = array();
	$successArray = array();
	$insertQueryArray = array();
	
	
	
	if(SystemDatabaseManager::getInstance()->startTransaction()) 
	{
	  //echo hii;
		foreach($sheetNameArray as $sheetIndex=>$value) 
		{ 
			 $subId = $topicManager->getSubjectId($value);
			 print_r($subId);
			 $subjectId =$subId[0]['subjectId'];
			 $topList=$topicManager->getTopicList($subjectId);
		     print_r($topList);
			                    
			for ($i = 1; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) 
			{
				if ($data->sheets[$sheetIndex]['cells'][1][1] != "[Sr.No.]") 
				{
					$inconsistenciesArray[] = "Data has not entered in given format";
					continue;
				}
			}
			$topicArray = array();
			$topicAbbrArray = array();
			$subjectIdArray = array();
						for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {

			
				 $srNo = $data->sheets[$sheetIndex]['cells'][$i][1];
				 $topic = strtolower($data->sheets[$sheetIndex]['cells'][$i][2]);
				 $topicAbbr= strtolower($data->sheets[$sheetIndex]['cells'][$i][3]);
				 $subjectId =$subId[0]['subjectId'];
				  $topicArray[] = $topic;
				  $topicAbbrArray[] = $topicAbbr;
				
							if($topic != '') {
					if(count($topicArray)!=count(array_unique($topicArray))){
						$inconsistenciesArray[] = "Duplicate Topic '$topic' for selected subject '$value' at Sr. No.'$srNo'";
						continue;
					}
				}
				if($topic != '') {
					if(count($topList)!=count(array_unique($topList))){
						$inconsistenciesArray[] = "Duplicate Topic '$topic' for selected subject '$value' at Sr. No.'$srNo'";
						continue;
					}
				}
				
				
				
				if($topicAbbr != '') {
					if(count($topicAbbrArray)!=count(array_unique($topicAbbrArray))){
						$inconsistenciesArray[] = "Duplicate Topic Abbr '$topicAbbr' for selected subject '$value' at Sr. No.'$srNo'";
						continue;
					}
				}

				
			}
			
	
			
			for ($i = 2; $i <= $data->sheets[$sheetIndex]['numRows']; $i++) {
				
				
				$subjectId = $subId[0]['subjectId'];
				$topic = $data->sheets[$sheetIndex]['cells'][$i][2];
				$topicAbbr = $data->sheets[$sheetIndex]['cells'][$i][3];
				
				
				$totalTopics++;

				if($getClassId != '') {
					$conditions = "WHERE cl.classId = ".$getClassId;
					$classArray = $topicManager->getClassInfo($conditions);
					$className = $classArray[0]['className'];
				}
				
				if (empty($topic)) {
					$inconsistenciesArray[] = "Please mention topic at Sr. No.'$srNo'";
					continue;
				}

				if (empty($topicAbbr)) {
					$inconsistenciesArray[] = "Please mention topicAbbr for Sr. No.'$srNo'";
					continue;
				}
								
						
				
				
					if($subjectId == '') 
					{
		
					  $inconsistenciesArray[] = "Subject code does not exist";
					  continue;
					}
					else if ($subjectId != '')
					{
						//echo 'r u there';
						/*$conditions = "WHERE subjectCode = '".$subjectId."'";
						$subjectIdArray = $topicManager->getTopicSubjectId();
						$topicSubjectId = $subjectIdArray[0]['subjectId'];
						if(topicSubjectId==''){*/
						  //add topic
						  $return = $topicManager->addTopicInfoInTransaction($subjectId,$topic,$topicAbbr);
						 

					}		
				}

				
			}
			
		}
	


if (count($inconsistenciesArray) == 0)
{

	
if(SystemDatabaseManager::getInstance()->startTransaction())

 {
		$successArray[] = "Data saved successfully for $totalTopics topics ";
		$csvData = '';
		$i = 1;
		foreach($successArray as $key=>$record) {
			$csvData .= "$i. $record\r\n";
			$i++;
		}
		$csvData = trim($csvData);
		$fileName = "Upload Topic Information.txt";
		ob_end_clean();
		header("Cache-Control: public, must-revalidate");
		header("Pragma: hack"); // WTF? oh well, it works...
		header("Content-Type: application/octet-stream");
		header("Content-Length: " .strlen($csvData));
		header('Content-Disposition: attachment; filename="'.$fileName.'"');
		header("Content-Transfer-Encoding: text\n");
		echo $csvData;
		die;
	}
}
else {
	$csvData = '';
	$i = 1;
	foreach($inconsistenciesArray as $key=>$record) {
		$csvData .= "$i $record\r\n";
		$i++;
	}
	$csvData = trim($csvData);
	$fileName = "Inconsistencies_Student_Information.txt";
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
	header("Pragma: hack"); // WTF? oh well, it works...
	header("Content-Type: application/octet-stream");
	header("Content-Length: " .strlen($csvData));
	header('Content-Disposition: attachment; filename="'.$fileName.'"');
	header("Content-Transfer-Encoding: text\n");
	echo $csvData;
	die;
}

//$History: fileUpload.php $
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 2/06/10    Time: 2:06p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modification in code for student detail upload
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 1/22/10    Time: 1:42p
//Updated in $/LeapCC/Library/StudentDetailUpload
//remove the comments
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 1/19/10    Time: 4:51p
//Updated in $/LeapCC/Library/StudentDetailUpload
//comment one line
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 12/29/09   Time: 1:06p
//Updated in $/LeapCC/Library/StudentDetailUpload
//remove check for domicile
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 12/28/09   Time: 4:38p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field university roll no.
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 12/19/09   Time: 12:35p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in message
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 12/08/09   Time: 5:09p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put 14 new fields during student uploading and modification in checks
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 12/01/09   Time: 3:32p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put addslashes during corrAddress, PermAddress
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 12/01/09   Time: 1:35p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field registration No. during uploading student detail
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:44p
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in query to check state, city, country for NULL
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:36p
//Updated in $/LeapCC/Library/StudentDetailUpload
//check NULL for state, country, city
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 12/01/09   Time: 12:28p
//Updated in $/LeapCC/Library/StudentDetailUpload
//put new field date of admission 
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 12/01/09   Time: 11:43a
//Updated in $/LeapCC/Library/StudentDetailUpload
//modified in student upload format
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 11/19/09   Time: 10:53a
//Updated in $/LeapCC/Library/StudentDetailUpload
//put check if user can upload file in given format
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/18/09   Time: 6:40p
//Created in $/LeapCC/Library/StudentDetailUpload
//new ajax file for student detail upload
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 11/04/09   Time: 11:48a
//Created in $/LeapCC/Library/StudentRollNoUpload
//new files for student roll no. uploading
//
?>