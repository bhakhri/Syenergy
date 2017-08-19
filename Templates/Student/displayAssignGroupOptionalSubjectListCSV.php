 <?php
//This file is used as printing version for display Assign Group Optional Subject List.
//
// Author :Hitesh Gupta
// Created on : 19-08-2014
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

?>

<?php
	global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','AssignGroupAdvanced');
    define('ACCESS','view');
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();
	require_once(MODEL_PATH . "/StudentManager.inc.php");
	$studentManager = StudentManager::getInstance();
	require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();
	$degree = $REQUEST_DATA['degree'];
	$sortBy = $REQUEST_DATA['sortBy'];
	


	if($degree=='') {
		$degree='0';  
	}

	$sortBy = $REQUEST_DATA['sortBy'];
	if ($sortBy == 'alphabetic') {
		$sortBy = 'studentName';
	}
	$resultArray = array();
	$cnt = 0;
	$hasParentSubjectIds = 0;
	$noParentSubjectIds = 0;
	$childSubjectIds = 0;

	//Array To find the Major/Minor Subjects with Child Subjects to a class
	$hasParentSubjectArray = $studentManager->hasParentSubjectDetails($degree);
	$hasParentSubjectIds = UtilityManager::makeCSList($hasParentSubjectArray,'subjectId');
	if($hasParentSubjectIds == '') {
		$hasParentSubjectIds = 0;
	}
	//Array To find the Major/Minor Subjects without Child Subjects to a class
	$noParentSubjectArray = $studentManager->noParentSubjectDetails($degree);
	$noParentSubjectIds = UtilityManager::makeCSList($noParentSubjectArray,'subjectId');
		$institute= $reportManager->getInstituteName();
		$date=date('d-M-y');
		$time=date('h:i:s A');
		$CSVDataHeader = ",,,,,$institute,\n,,,,,,,,,,,,,Date:,$date \n,,,,,,,,,,,,,Time:, $time \n ,,,,,Group Optional Subject Group\n\n";
		
	if(count($hasParentSubjectArray)==0 && count($noParentSubjectArray)==0) {
		$resultArray = Array('totalGroups' => 0);
	}
	else {
		//Array to find the corresponding Child Subject of Parent(major/minor) Subject
		$getChildSubjectsArray = $studentManager->getChildClassesDetails($degree,$hasParentSubjectIds);
		$subjectArray = array();
		//Formation of multidimensional array of child Subject Code,Child SubjectId, Parent SubjectId under Parent Subject Code,
		foreach ($hasParentSubjectArray as $record) {
			$subjectId = $record['subjectId'];
			$subjectArray[$subjectId] = array();
		}
		foreach($getChildSubjectsArray as $records) {
			$subjectArray[$records['parentOfSubjectId']][] = array('childSubjectId'=>$records['childSubjectId'], 'childSubjectCode'=>$records['childSubjectCode'], 'parentSubjectCode'=>$records['parentSubjectCode']);
		}


		$childSubjectIds = UtilityManager::makeCSList($getChildSubjectsArray,'childSubjectId');
		if ($childSubjectIds == '') {
			$childSubjectIds = 0;
		}
		if ($noParentSubjectIds != '') {
			$childSubjectIds .= ',';
			$childSubjectIds .= $noParentSubjectIds;
		}




		//Array to find the Groups of the optinal subjects in a particular class
		$classGroupDetailsArray = $studentManager->getClassGroupDetails($degree, $childSubjectIds);
		$cnt = count($classGroupDetailsArray);
		$groupArray = array();
		foreach($getChildSubjectsArray as $records) {
			$groupArray[$records['childSubjectId']] = array();
		}
		foreach($classGroupDetailsArray as $records) {
			$groupArray[$records['childsubjectId']][] = array('groupId' => $records['groupId'], 'groupName' => $records['groupShort']);
		}



		//Array to find the student details of the class
		$studentDetailsArray = $studentManager->getStudentDetails($degree,$sortBy);
		$studentIds = UtilityManager::makeCSList($studentDetailsArray,'studentId');

		if($studentIds=='') {
		$studentIds='0';
		}
		//Array to group alloted to student
		$studentGroupDetailsArray = $studentManager->studentGroupDetails($degree,$studentIds);
		$studentGroupArray = array();
		foreach($studentGroupDetailsArray as $records) {
			$studentGroupArray[$records['studentId']][] = array('groupId'=>$records['groupId'],'parentOfSubjectId'=>$records['parentOfSubjectId'], 'subjectId'=>$records['subjectId']);
		}


		//Array to find the group count
		$groupCountDetailsArray = $studentManager->getGroupCount($degree);
		
		


		//Final Array to be send to Interface file
		$totalGroups=$cnt;
						$talign="align='center'";
					    $spacePerGroup = 75/$totalGroups;
					    $CSVDataHeader .= '#,Roll No.,U.Roll No.,Student Name,';
					    $parentSubjectCtr = 0;
					    $totalParentSubjects = count($hasParentSubjectArray);
						
					    while ($parentSubjectCtr < $totalParentSubjects) {
						    $parentSubjectCode = $hasParentSubjectArray[$parentSubjectCtr]['subjectCode'];
						    $parentSubjectId = $hasParentSubjectArray[$parentSubjectCtr]['subjectId'];
						    $totalChildSubjects = count($subjectArray[$parentSubjectId]);
                            if($totalChildSubjects>0) {
						        $childSubjectCtr = 0;
						        $totalChildGroups = 0;
                                while($childSubjectCtr < $totalChildSubjects) {
							        $childSubjectId = $subjectArray[$parentSubjectId][$childSubjectCtr]['childSubjectId'];
							        $totalChildGroups += count($groupArray[$childSubjectId]);
							        $childSubjectCtr++;
						        }
						        $colBlank ='';
						        if($totalChildGroups!='' && $totalChildGroups >0) {
									for($i=1;$i<=$totalChildGroups;$i++){
										$colBlank .= ",";
									}
						        }
						        if($totalChildGroups == 0) {
									for($i=1;$i<=$totalChildSubjects;$i++){
										$colBlank .= ",";
									}
								}
								// echo $totalChildGroups ;die;
									$CSVDataHeader .=$parentSubjectCode.''.$colBlank;
                            }
							
						    $parentSubjectCtr++;
					    }
                      
                        
					    
                        $parentSubjectCtr = 0;
					    $totalChildGroups = 0;
					    $totalParentSubjects = count($noParentSubjectArray);
					    while ($parentSubjectCtr < $totalParentSubjects) {
						    $parentSubjectCode = $noParentSubjectArray[$parentSubjectCtr]['subjectCode'];
							
						    $childSubjectId = $noParentSubjectArray[$parentSubjectCtr]['subjectId'];
                            $totalChildGroups=0; 
                            if (gettype($groupArray[$childSubjectId]) === "undefined") {
                              $totalChildGroups=0;  
                            }
                            else {
                              $totalChildGroups = count($groupArray[$childSubjectId]);  
                            }                            
                            $colBlank ='';
						        if($totalChildGroups!='' && $totalChildGroups >0) {
									for($i=1;$i<=$totalChildGroups;$i++){
										$colBlank .= ",";
									}
						        }
						        $CSVDataHeader .=$parentSubjectCode.''.$colBlank;
						    $parentSubjectCtr++;
					    }
					    $CSVDataHeader .= " \n,,,,";
                      
                        
                       
					    $parentSubjectCtr = 0;
					    $totalParentSubjects = count($hasParentSubjectArray);
					    while ($parentSubjectCtr < $totalParentSubjects) {
						    $parentSubjectId = $hasParentSubjectArray[$parentSubjectCtr]['subjectId'];
                            $totalChildSubjects = count($subjectArray[$parentSubjectId]);
						    $childSubjectCtr = 0;
                            while($childSubjectCtr < $totalChildSubjects) {
							    $childSubjectCode = $subjectArray[$parentSubjectId][$childSubjectCtr]['childSubjectCode'];
							    $childSubjectId = $subjectArray[$parentSubjectId][$childSubjectCtr]['childSubjectId'];
							    //totalAllottedGroups = j['groupDetails'][childSubjectId].length;
                                $totalAllottedGroups=0;
                                if (gettype($groupArray[$childSubjectId]) === "undefined") {
                                  $totalAllottedGroups=0;  
                                  $tableData = $tableData .'<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" '.$colspan.' '.$talign.' >&nbsp;</td>';
                                }
                                else {
                                  $totalAllottedGroups = count($groupArray[$childSubjectId]);
                                } 
								
								$colBlank ='';
						        if($totalAllottedGroups!='' && $totalAllottedGroups >0) {
									
									for($i=1;$i<=$totalAllottedGroups;$i++){
										$colBlank .= ",";
									}
						        }
							   
                                $CSVDataHeader .= $childSubjectCode.''.$colBlank;
							    $childSubjectCtr++;
						    }
                            
						    $parentSubjectCtr++;
					    }
					    $CSVDataHeader .= " \n,,,,";
                        
                    
                       
					    
                       
					    $parentSubjectCtr = 0;
					    $totalParentSubjects = count($hasParentSubjectArray);
					    while ($parentSubjectCtr < $totalParentSubjects) {
						    $parentSubjectId = $hasParentSubjectArray[$parentSubjectCtr]['subjectId'];
						    $totalChildSubjects = count($subjectArray[$parentSubjectId]);
						    $childSubjectCtr = 0;
						    while($childSubjectCtr < $totalChildSubjects) {
							    $childSubjectId = $subjectArray[$parentSubjectId][$childSubjectCtr]['childSubjectId'];
							    $totalGroups = count($groupArray[$childSubjectId]);
							    $groupCtr = 0;
							    while($groupCtr < $totalGroups) {
								    $groupName = $groupArray[$childSubjectId][$groupCtr]['groupName'];
								    $groupId =  $groupArray[$childSubjectId][$groupCtr]['groupId'];
								     $CSVDataHeader .=$groupName.",";
								    $groupCtr++;
							    }
							    $childSubjectCtr++;
						    }
						    $parentSubjectCtr++;
					    }
                        
                        $parentSubjectCtr = 0;
					    $totalChildGroups = 0;
					    $totalParentSubjects = count($noParentSubjectArray);
					    while ($parentSubjectCtr < $totalParentSubjects) {
						    $parentSubjectCode = $noParentSubjectArray[$parentSubjectCtr]['subjectCode'];
						    $childSubjectId = $noParentSubjectArray[$parentSubjectCtr]['subjectId'];
                            $totalChildGroups=0;   
						    if (gettype($groupArray[$childSubjectId]) === "undefined") {
                              $totalChildGroups=0;  
                               $CSVDataHeader .="  ,";   
                            }
                            else {
                              $totalChildGroups = count($groupArray[$childSubjectId]);
                            } 
                            $groupCtr = 0;
						    while($groupCtr < $totalChildGroups) {
							    $groupName = $groupArray[$childSubjectId][$groupCtr]['groupName'];
							    $groupId =  $groupArray[$childSubjectId][$groupCtr]['groupId'];
							     $CSVDataHeader .=$groupName.",";
							    $groupCtr++;
						    }
						    $parentSubjectCtr++;
					    }
					   $CSVDataHeader .= " \n";
						$CSVHeader = $CSVDataHeader;
		
					
//===========================================================================================================================

						
						$totalStudentCount = count($studentDetailsArray);
					    $studentCtr = 0;
						$reportDataFind='0';
						
					    while($studentCtr < $totalStudentCount) {
						    $recordCounter = $studentCtr+1;
														
                            $studentId = $studentDetailsArray[$studentCtr]['studentId'];
                            
						   
						    $CSVDataHeader .= $recordCounter.',';
						    $CSVDataHeader .= $studentDetailsArray[$studentCtr]['rollNo'].',';
						    $CSVDataHeader .= $studentDetailsArray[$studentCtr]['universityRollNo'].',';
						    $CSVDataHeader .= $studentDetailsArray[$studentCtr]['studentName'].',';
						    $parentSubjectCtr = 0;
						    $totalParentSubjects = count($hasParentSubjectArray);
                            while ($parentSubjectCtr < $totalParentSubjects) {
							    $parentSubjectId = $hasParentSubjectArray[$parentSubjectCtr]['subjectId'];
                                $totalChildSubjects = count($subjectArray[$parentSubjectId]);
							    $childSubjectCtr = 0;
                                if($totalChildSubjects==0) {
                                  //tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">&nbsp;</td>';  
                                }
                                else {
							        while($childSubjectCtr < $totalChildSubjects) {
								        $childSubjectId = $subjectArray[$parentSubjectId][$childSubjectCtr]['childSubjectId'];
								        //totalGroups = j['groupDetails'][childSubjectId].length;
                                        $totalGroups=0;       
                                        if (gettype($groupArray[$childSubjectId]) == "undefined") {
                                          $totalGroups=0;  
                                          //tableData += '<td width="'+spacePerGroup+'" class="searchhead_text">22&nbsp;</td>';  
                                        }
                                        else {
                                          $totalGroups = count($groupArray[$childSubjectId]);
                                        } 
								        $groupCtr = 0;
								        while($groupCtr < $totalGroups) {
									        $groupId = $groupArray[$childSubjectId][$groupCtr]['groupId'];
									        $groupName = $groupArray[$childSubjectId][$groupCtr]['groupName'];
									        $studentId = $studentDetailsArray[$studentCtr]['studentId'];
											$checked = '---';
									       
									        if ($studentGroupArray[$studentId]) {
										        $perStudentGroups = count($studentGroupArray[$studentId]);
										        $perGroupCtr = 0;
										        while($perGroupCtr < $perStudentGroups) {
											        $studentGroup = $studentGroupArray[$studentId][$perGroupCtr]['groupId'];
											        $groupParentSubject = $studentGroupArray[$studentId][$perGroupCtr]['parentOfSubjectId'];
                                                    
											        if($groupId == $studentGroup && $groupParentSubject == $parentSubjectId) {
                                                        
												        $checked = 'YES';
												        
											        }
											        $perGroupCtr++;
										        }
									        }
									        $CSVDataHeader .= $checked.',';
									        $groupCtr++;
								        }
								        $childSubjectCtr++;
							        }  
                                }
							    $parentSubjectCtr++;
						    }
						    $parentSubjectCtr = 0;
						    $totalChildGroups = 0;
						    $totalParentSubjects = count($noParentSubjectArray);

						    while ($parentSubjectCtr < $totalParentSubjects) {
							    $parentSubjectCode = $noParentSubjectArray[$parentSubjectCtr]['subjectCode'];
							    $childSubjectId = $noParentSubjectArray[$parentSubjectCtr]['subjectId'];
							    //totalChildGroups = j['groupDetails'][childSubjectId].length;
                                
                                $totalChildGroups=0;
                                if (gettype($groupArray[$childSubjectId]) == "undefined") {
                                  $totalChildGroups=0;  
                                  $tableData .= '<td '.$reportManager->getReportDataStyle().' width="'.spacePerGroup.'">&nbsp;</td>';   
                                }
                                else {
                                  $totalChildGroups = count($groupArray[$childSubjectId]);
                                } 
                                
                          
                                $groupCtr = 0;
							    while($groupCtr < $totalChildGroups) {
								    $groupId =  $groupArray[$childSubjectId][$groupCtr]['groupId'];
								    $studentId = $studentDetailsArray[$studentCtr]['studentId'];
								   
								    $checked = '---';
								    
                                    if ($studentGroupArray[$studentId]) {
									    $perStudentGroups = count($studentGroupArray[$studentId]);
									    $perGroupCtr = 0;
									    while($perGroupCtr < $perStudentGroups) {
										    $studentGroup = $studentGroupArray[$studentId][$perGroupCtr]['groupId'];
										    $groupParentSubjectId = $studentGroupArray[$studentId][$perGroupCtr]['subjectId'];
										    if($groupId == $studentGroup && $groupParentSubjectId == $childSubjectId) {
											    $checked = 'YES';
											   
										    }
										    $perGroupCtr++;
									    }
								    }
								   $CSVDataHeader .= $checked.',';
								    $groupCtr++;
							    }
							    $parentSubjectCtr++;
						    }
						    $CSVDataHeader .= " \n";
							$reportDataFind++;    
								
						    $studentCtr++;
					    }
				    

		$csvData=$CSVDataHeader;
	}
	ob_end_clean();
	header("Cache-Control: public, must-revalidate");
		// We'll be outputting a PDF
	header('Content-type: application/octet-stream');
	header("Content-Length: " .strlen($csvData) );
	// It will be called downloaded.pdf
	header('Content-Disposition: attachment;  filename="'.'AssignGroupOptionalSubjectList.csv'.'"');
	// The PDF source is in original.pdf
	header("Content-Transfer-Encoding: binary/n");
	echo $csvData;
	die;
//$History : $
?>