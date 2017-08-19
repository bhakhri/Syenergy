 <?php
//This file is used as printing version for designations.
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
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
	 $studentManager = StudentManager::getInstance();

	$degree = $REQUEST_DATA['degree'];
// echo "hello";die;
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
					    $tableData = '<table border="1" cellpadding="1" cellspacing="1" width="800px" class="reportTableBorder" >';
						
					    

					    $tableData = $tableData .'<tr ><td rowspan="3" '.$reportManager->getReportDataStyle().' width="2%" >#</td><td '.$reportManager->getReportDataStyle().' rowspan="3" width="8%" >Roll No.</td><td '.$reportManager->getReportDataStyle().' width="8%" rowspan="3" >U.Roll No.</td><td '.$reportManager->getReportDataStyle().' width="15%" rowspan="3" >Student Name</td>';
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
						        $colspan ='';
						        if($totalChildGroups!='' && $totalChildGroups >=2) {
						          $colspan = " colspan='".$totalChildGroups."'";
								  
						        }
						        if($totalChildGroups == 0) {
							        $colspan = " colspan='".$totalChildSubjects."'";
						        }
								$tableData .='<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" '.$colspan.' '.$talign.' >'.$parentSubjectCode.'</td>';
                            
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
                            $colspan ='';
						    if($totalChildGroups!='' && $totalChildGroups >=2) {
							    $colspan = " colspan='".$totalChildGroups."'";
						    }
						    $tableData .= '<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" '.$colspan.' '.$talign.' rowspan ="2" >'.$parentSubjectCode.'</td>';
						    $parentSubjectCtr++;
							
					    }
					    $tableData .= '</tr>';
                      
                        
                        $tableData .= '<tr >';
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
								
							    $colspan ='';
							    if($totalAllottedGroups!='' && $totalAllottedGroups >=2) {
								    $colspan = " colspan='".$totalAllottedGroups."'";
							    }
                                $tableData = $tableData .'<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" '.$colspan.' '.$talign.' >'.$childSubjectCode.'</td>';
							    $childSubjectCtr++;
								
						    }
                            
						    $parentSubjectCtr++;
					    }
					    $tableData = $tableData . '</tr>';
                        // var_dump($totalChildGroups);die;
                    
                       
					    
                        $tableData = $tableData . '<tr>';
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
								    $tableData .= '<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" >'.$groupName.'</td>';
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
                              $tableData .= '<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'">&nbsp;</td>';   
                            }
                            else {
                              $totalChildGroups = count($groupArray[$childSubjectId]);
                            } 
                            $groupCtr = 0;
						    while($groupCtr < $totalChildGroups) {
							    $groupName = $groupArray[$childSubjectId][$groupCtr]['groupName'];
							    $groupId =  $groupArray[$childSubjectId][$groupCtr]['groupId'];
							    $tableData .= '<td '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'" >'.$groupName.'</td>';
							    $groupCtr++;
						    }
						    $parentSubjectCtr++;
					    }
					    $tableData .= '</tr>';
						$tableHeader = $tableData;
						//========================================
						
						$totalStudentCount = count($studentDetailsArray);
					    $studentCtr = 0;
						$reportDataFind='0';
						//count no of pages==============
						$pageRecordLimit ='30';
    
						$pageCounter = '1';
						$totalPages = '1';
    
						$cntPage = $totalStudentCount/$pageRecordLimit;
    
						if(intval($cntPage)==$cntPage) {
							$totalPages = intval($cntPage);  
						}
						else {
							$totalPages = intval($cntPage)+1;
						}
    
						if($totalPages=='') {
								$totalPages='1';  
						}
					    while($studentCtr < $totalStudentCount) {
						    $recordCounter = $studentCtr+1;
							if($tableData==""){
								$tableData=$tableHeader;
								
							}
							
                            $studentId = $studentDetailsArray[$studentCtr]['studentId'];
                            
						    $tableData .= '<tr>';
						    $tableData .= '<td '.$reportManager->getReportDataStyle().'>'.$recordCounter.'</td>';
						    $tableData .= '<td '.$reportManager->getReportDataStyle().'>'.$studentDetailsArray[$studentCtr]['rollNo'].'</td>';
						    $tableData .= '<td '.$reportManager->getReportDataStyle().'>'.$studentDetailsArray[$studentCtr]['universityRollNo'].'</td>';
						    $tableData .= '<td '.$reportManager->getReportDataStyle().'>'.$studentDetailsArray[$studentCtr]['studentName'].'</td>';
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
									        $tableData .= '<td '.$reportManager->getReportDataStyle().'  width="'.$spacePerGroup.'""> '.$checked.'</td>';
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
								   $tableData .= '<td  '.$reportManager->getReportDataStyle().' width="'.$spacePerGroup.'""> '.$checked.'</td>';
								    $groupCtr++;
							    }
							    $parentSubjectCtr++;
						    }
						    $tableData .= '</tr>';
							$reportDataFind++;    
							if($reportDataFind==30) {
								$tableData .= "</table>";  
								echo reportGenerate($tableData);    
								$reportDataFind='0';
								$tableData = '';
							}
							
							
						    $studentCtr++;
					    }
				    }
					
					if($tableData != '') {
						$tableData .= "</table>"; 
						echo reportGenerate($tableData);  
					}
					die;

   // $reportManager->setReportWidth(800);
	// $reportManager->setReportHeading('Assign Group Optional Subject List ');
	// $reportManager->setReportInformation("Search By : $search");


    // $reportManager->setRecordsPerPage(40);
    // $reportManager->setReportData($reportTableHead, $valueArray);
    // $reportManager->showReport();

function reportGenerate($value) {
        
        global $pageCounter;
        global $totalPages;
        global $reportManager;
        global $searchHeading;
        
        if($totalPages=='') {
          $totalPages='1';  
        }
        
        $reportManager->setReportWidth(800);
        $reportManager->setReportHeading('Group Optional SubjectList');
        $reportManager->setReportInformation("Search By : $search"); 
		// $reportManager->setRecordsPerPage(40);
      ?>
        <div>
            <table border="0" cellspacing="0" cellpadding="0" width="95%" align="center">
            <tr>
            <td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
            <th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>>
                <?php echo $reportManager->getInstituteName(); ?>
            </th>
            <td align="right" colspan="1" width="25%" class="">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right" width="50%">Date :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("d-M-y");?></td>
                </tr>
                <tr>
                    <td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?> align="right">Time :&nbsp;</td><td valign="" colspan="1" <?php echo $reportManager->getDateTimeStyle();?>><?php echo date("h:i:s A");?></td>
                </tr>
            </table>
            </td>
            </tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><?php echo $reportManager->reportHeading; ?></th></tr>
            <tr><th colspan="3" <?php echo $reportManager->getReportInformationStyle(); ?>  align="center"><?php echo $reportManager->getReportInformation(); ?></th></tr>
            </table> <br>
            <table border='0' cellspacing='0' width="800px" class="reportTableBorder"  align="center">
            <tr>
            <td valign="top">
                <?php echo $value; ?>        
            </td>
            </tr> 
            </table> 
            <table border='0' cellspacing='0' cellpadding='0' width="800px" align="center">
                <tr>
                    <td valign='' align="left"  <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter(); ?></td>
                <?php
                 if($totalPages!='1') {
                ?>    
                    <td valign='' align="right" <?php echo $reportManager->getFooterStyle();?>>Page <?php echo $pageCounter; ?> / <?php echo $totalPages; ?></td>
                <?php
                 }
                ?>    
                </tr>
            </table>
            <br class='page'> 
        </div>    
<?php        
        $pageCounter++;
    }
//$History : $
//

?>
