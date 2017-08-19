<?php
//-------------------------------------------------------
// THIS FILE IS USED FOR DB OPERATION FOR "city" TABLE
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//--------------------------------------------------------
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');

class AppraisalDataManager {
	private static $instance = null;
	
//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR CREATING AN OBJECT OF "AppraisalDataManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------      
	private function __construct() {
	}

//-------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING AN INSTANCE OF "AppraisalDataManager" CLASS
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008)
// Copyright 2008-2009 - Chalkpad Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------       
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

	public function getAppraisalProofText($conditions='') {
		
			$query="SELECT * FROM employee_appraisal_tab $conditions";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			}  
			
			   public function getAppraisalFormData($employeeId,$conditions='') {
				   global $sessionHandler;
				   $sessionId=$sessionHandler->getSessionVariable('SessionId');
				   if($employeeId==''){
					   return EMPLOYEE_INFO_MISSING;
					   }
					   
								$query = "SELECT 
								am.appraisalId,
								am.appraisalProof,
								am.appraisalText,
								am.appraisalWeightage,
								at.appraisalTabId,
								at.appraisalTabName,
								at.appraisalProofText,
								atl.appraisalTitleId,
								atl.appraisalTitle,
								ap.appraisalProofName,
								ap.appraisalProofId,
								ap.editableBySelf,
								ad.selfEvaluation,
								ad.hodEvaluation,
								IF(am.appraisalProof=1,IF(ap.editableBySelf=1,1,0),1) AS disabledQuestions
								FROM 
								employee_appraisal_tab `at`,
								employee_appraisal_title atl,
								employee_appraisal_master am
								LEFT JOIN employee_appraisal_proof ap ON ap.appraisalProofId=am.appraisalProofId $conditions2
								LEFT JOIN employee_appraisal_data ad ON ( ad.appraisalId=am.appraisalId AND ad.employeeId=$employeeId AND 
								ad.sessionId=$sessionId )
								WHERE
								at.appraisalTabId=am.appraisalTabId
								AND atl.appraisalTitleId=am.appraisalTitleId
								AND am.isActive=1
								$conditions
								ORDER BY at.appraisalTabName,atl.appraisalTitle,atl.appraisalTitleId  
								";
								return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
								}
								

public function getAppraisalLeaveData($employeeId,$conditions='') {
	
			global $sessionHandler;
			$sessionId=$sessionHandler->getSessionVariable('SessionId');
			if($employeeId==''){
				return EMPLOYEE_INFO_MISSING;
				}
				
						$query = "SELECT 
						*
						FROM 
						employee_appraisal_leaves_availed 
						WHERE
						employeeId=$employeeId
						AND sessionId=$sessionId
						$conditions
						";
						return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
						}
						

public function getAppraisalReviewerData($employeeId,$conditions='') {
	
			global $sessionHandler;
			$sessionId=$sessionHandler->getSessionVariable('SessionId');
			if($employeeId==''){
				return EMPLOYEE_INFO_MISSING;
				}
				
						$query = "SELECT 
						*
						FROM 
						employee_appraisal_reviewer 
						WHERE
						employeeId=$employeeId
						AND sessionId=$sessionId
						$conditions
						";
						return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
						}   
						
						public function deleteApprisalLeaveData($employeeId){
							global $sessionHandler;
							$sessionId=$sessionHandler->getSessionVariable('SessionId');
							$query="DELETE FROM employee_appraisal_leaves_availed WHERE employeeId=$employeeId AND sessionId=$sessionId";
							return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
							}

public function insertAppraisalLeaveData($employeeId,$cl,$el,$pl,$sl,$lwp,$lwpC,$overAll){
	global $sessionHandler;
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
	$query="INSERT INTO
	employee_appraisal_leaves_availed 
	(employeeId,sessionId,casual_leave,earned_leave,mp_leave,study_leave,without_pay,lwp_times,self_appraisal)
	VALUES ($employeeId,$sessionId,$cl,$el,$pl,$sl,$lwp,$lwpC,'$overAll')";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

// COMPATIBILITY
public function getEmployee($employeeCode) {

		$query = "SELECT 
						employeeId
					FROM
						employee
					WHERE
						employeeCode = '$employeeCode' ";
	return SystemDatabaseManager::getInstance()->executeQuery($query);
}

public function	insertAppraisalCompatibilityData($employeeToBeAppraised,$scoreGained,$dutiesWeekend,$extSupreintendent,$copyChecked,
	$dutiesExternal,$dutiesInternal) {
	
	global $sessionHandler;
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
	$query="INSERT INTO
						employee_appraisal_compatibility 
						(employeeId,sessionId,scoreGained,dutiesWeekend,extSupreintendent,copyChecked,	dutiesExternal,dutiesInternal)
				VALUES 
						($employeeToBeAppraised,$sessionId,$scoreGained,$dutiesWeekend,$extSupreintendent,$copyChecked,$dutiesExternal,$dutiesInternal)";
    return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function	updateAppraisalCompatibilityData($employeeToBeAppraised,$scoreGained,$dutiesWeekend,$extSupreintendent,$copyChecked,
	$dutiesExternal,$dutiesInternal) {
	
	global $sessionHandler;
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$query="UPDATE
						employee_appraisal_compatibility 
				SET
						employeeId='$employeeToBeAppraised',
						sessionId='$sessionId',
						scoreGained='$scoreGained',
						dutiesWeekend='$dutiesWeekend',
						extSupreintendent='$extSupreintendent',
						copyChecked='$copyChecked',
						dutiesExternal='$dutiesExternal',
						dutiesInternal='$dutiesInternal'
				WHERE
						employeeId=$employeeToBeAppraised
				AND		sessionId=$sessionId";

       return  SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function getCompatibilityData($employeeToBeAppraised,$sessionId){  
	global $sessionHandler;
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$query="SELECT 
					COUNT(*) AS cnt 
				FROM 
						employee_appraisal_compatibility 
				WHERE
						employeeId=$employeeToBeAppraised
				AND
						sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getCompatibilityList($employeeToBeAppraised,$sessionId){
	global $sessionHandler;
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$query="SELECT
						* 
				FROM
						employee_appraisal_compatibility
				WHERE
						employeeId=$employeeToBeAppraised
				AND		sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function deleteSelfAppraisalData($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_data WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertSelfAppraisalData($insertString){
		$query="INSERT INTO 
						employee_appraisal_data
						(employeeId,appraisalId,selfEvaluation,hodEvaluation,sessionId)
				VALUES
						$insertString";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteEmployeeReviewerData($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_reviewer WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertEmployeeReviewerDateFromHOD($insertString){
	$query="INSERT INTO 
	employee_appraisal_reviewer
	(
	reviewedByUserId,employeeId,sessionId,
	initiative,responsibility,punctuality,committment,
	loyality,develop,oral,written_com,teamwork,leadership,
	relation,matuarity,temper,rel_stud,grandtotal,self_total
	)
	VALUES $insertString";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertSelfAppraisalDataFromHOD($insertString){
	$query="INSERT INTO 
	employee_appraisal_data
	(employeeId,appraisalId,selfEvaluation,hodEvaluation,sessionId,superiorEmployeeId)
	VALUES $insertString";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


public function getProofFormType($proofId,$conditions='') {
	
			$query = "SELECT 
			*
			FROM 
			employee_appraisal_proof 
			WHERE
			appraisalProofId=$proofId
			$conditions
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			}

public function getEmployeeInfo($employeeId,$conditions='') {
	
			$query = "SELECT 
			*
			FROM 
			employee 
			WHERE
			employeeId=$employeeId
			$conditions
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			}   

public function gerWeightageData($appraisalId,$conditions='') {
	
			$query = "SELECT 
			appraisalId,appraisalWeightage
			FROM 
			employee_appraisal_master 
			WHERE
			appraisalId=$appraisalId
			$conditions
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			}

public function checkMainAppraisal($appraisalId,$employeeId,$sessionId,$conditions='') {
	
			$query = "SELECT 
			COUNT(*) AS cnt
			FROM 
			employee_appraisal_data 
			WHERE
			appraisalId=$appraisalId
			AND employeeId=$employeeId
			AND sessionId=$sessionId
			$conditions
			";
			return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			}

public function insertMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$hodEvaluation,$sessionId,$superiorEmployeeId=0) {
	
			$query = "INSERT INTO 
			employee_appraisal_data (employeeId,appraisalId,selfEvaluation,hodEvaluation,sessionId,superiorEmployeeId)
			VALUES($employeeId,$appraisalId,$selfEvaluation,$hodEvaluation,$sessionId,$superiorEmployeeId)
			";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
			}

public function updateMainAppraisal($employeeId,$appraisalId,$selfEvaluation,$sessionId) {
	
			$query = "UPDATE 
			employee_appraisal_data 
			SET
			selfEvaluation=$selfEvaluation
			WHERE
			employeeId=$employeeId
			AND appraisalId=$appraisalId
			AND sessionId=$sessionId
			";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
			}

public function updateMainAppraisalHOD($employeeId,$appraisalId,$hodEvaluation,$sessionId,$superiorEmployeeId) {
	
			$query = "UPDATE 
			employee_appraisal_data 
			SET
			hodEvaluation=$hodEvaluation
			WHERE
			employeeId=$employeeId
			AND appraisalId=$appraisalId
			AND sessionId=$sessionId
			AND superiorEmployeeId=$superiorEmployeeId
			";
			return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
			}
			
public function gerProofData($proofId,$employeeId,$sessionId,$conditions='') {
			$tableArray=array(
				'2'=>'employee_appraisal_proof_data1',
				'3'=>'employee_appraisal_proof_data3',
				'4'=>'employee_appraisal_proof_data2',
				'5'=>'employee_appraisal_proof_data4',
				'6'=>'employee_appraisal_proof_data5',
				'7'=>'employee_appraisal_proof_data6',
				'8'=>'employee_appraisal_proof_data7',
				'9'=>'employee_appraisal_proof_data8',
				'10'=>'employee_appraisal_proof_data9',
				'11'=>'employee_appraisal_proof_data10',
				'12'=>'employee_appraisal_proof_data11',
				'13'=>'employee_appraisal_proof_data12',
				'15'=>'employee_appraisal_proof_data13',
				'16'=>'employee_appraisal_proof_data14',
				'17'=>'employee_appraisal_proof_data15',
				'14'=>'employee_appraisal_proof_data16',
				'1'=>'employee_appraisal_proof_data17',
				'18'=>'employee_appraisal_proof_data18',
				'19'=>'employee_appraisal_proof_data19',
				'20'=>'employee_appraisal_proof_data20',
				'21'=>'employee_appraisal_proof_data21',
				'22'=>'employee_appraisal_proof_data22',
				'23'=>'employee_appraisal_proof_data23',
				'24'=>'employee_appraisal_proof_data24',
				'25'=>'employee_appraisal_proof_data25',
				'26'=>'employee_appraisal_proof_data26',
				'27'=>'employee_appraisal_proof_data27',
				'28'=>'employee_appraisal_proof_data28',
				'29'=>'employee_appraisal_proof_data29',
				'30'=>'employee_appraisal_proof_data30',
				'31'=>'employee_appraisal_proof_data31',
				'32'=>'employee_appraisal_proof_data32',
				'33'=>'employee_appraisal_proof_data33',
				'34'=>'employee_appraisal_proof_data34'
			);

		$query ="SELECT 
						*
				FROM 
						".$tableArray[$proofId]." 
				WHERE
					employeeId=$employeeId
					AND sessionId=$sessionId
					$conditions
				";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
		}
		
	

public function deleteProofData1($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data1 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData1($employeeId,$sessionId,$cert_process,$devoted,$supervision,$certification,$assistance,$superValue){
	$query="INSERT INTO 
	employee_appraisal_proof_data1
	(employeeId,sessionId,cert_process,devoted,supervision,certification,assistance,super)
	VALUES ($employeeId,$sessionId,'$cert_process','$devoted','$supervision','$certification','$assistance','$superValue')";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function deleteProofData3($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data3 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData3($employeeId,$sessionId,$central,$facilities){
	$query="INSERT INTO 
	employee_appraisal_proof_data3
	(employeeId,sessionId,central,facilities)
	VALUES ($employeeId,$sessionId,'$central','$facilities')";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


public function deleteProofData4($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data2 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData4($employeeId,$sessionId,$insertString){
	$query="INSERT INTO 
	employee_appraisal_proof_data2
	(
	employeeId,sessionId,
	act1,test_input1,test_input2,
	act2,test_input3,test_input4,
	act3,test_input5,test_input6,
	act4,test_input7,test_input8,
	act5,test_input9,test_input10,
	act6,test_input11,test_input12,
	act7,test_input13,test_input14,
	act8,test_input15,test_input16,
	act9,test_input17,test_input18,
	act10,test_input19,test_input20,
	act1_marks,act2_marks,act3_marks
	)
	VALUES ($employeeId,$sessionId,".$insertString.")";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function deleteProofData5($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data4 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData5($employeeId,$sessionId,$insertString){
	$query="INSERT INTO 
	employee_appraisal_proof_data4
	(
	employeeId,sessionId,
	act1,test_input1,test_input2,
	act2,test_input3,test_input4,
	act3,test_input5,test_input6,
	act4,test_input7,test_input8,
	act5,test_input9,test_input10,
	act6,test_input11,test_input12,
	act7,test_input13,test_input14,
	act1_marks,act2_marks
	)
	VALUES ($employeeId,$sessionId,".$insertString.")";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}
	

public function deleteProofData6($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data5 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData6($employeeId,$sessionId,$insertString){
	$query="INSERT INTO 
	employee_appraisal_proof_data5
	(
	employeeId,sessionId,
	act1,test_input1,test_input2,
	act2,test_input3,test_input4,
	act3,test_input5,test_input6,
	act4,test_input7,test_input8,
	act5,test_input9,test_input10,
	act6,test_input11,test_input12,
	act7,test_input13,test_input14,
	act8,test_input15,test_input16,
	act9,test_input17,test_input18,
	act10,test_input19,test_input20,
	act11,test_input21,test_input22,
	act12,test_input23,test_input24,
	act13,test_input25,test_input26,
	act14,test_input27,test_input28,
	act1_marks,act2_marks,act3_marks
	)
			VALUES ($employeeId,$sessionId,".$insertString.")";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData7($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data6 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData7($employeeId,$sessionId,$newlab,$labname,$act1_marks,$major_equip,$major_equip2,$act2_marks,$maint_equip,$act3_marks,$testing_meas,$testing_meas2,$act4_marks){
	$query="INSERT INTO 
					employee_appraisal_proof_data6
					(
					employeeId,sessionId,newlab,labname,act1_marks,
					major_equip,major_equip2,act2_marks,
					maint_equip,act3_marks,
					testing_meas,testing_meas2,act4_marks
					)
					VALUES 
					$employeeId,$sessionId,'$newlab','$labname','$act1_marks','$major_equip','$major_equip2','$act2_marks','$maint_equip','$act3_marks','$test
					ng_meas','$testing_meas2','$act4_marks')";
					return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
					}

public function deleteProofData8($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data7 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData8($employeeId,$sessionId,$qty_lab,$new_manual,$act1_marks,$existing_manual,$act2_marks){
	$query="INSERT INTO 
	employee_appraisal_proof_data7
	(employeeId,sessionId,qty_lab,new_manual,act1_marks,existing_manual,act2_marks)
	VALUES ($employeeId,$sessionId,'$qty_lab','$new_manual','$act1_marks','$existing_manual','$act2_marks')";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}


public function deleteProofData9($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data8 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
	}

public function insertProofData9($employeeId,$sessionId,$act1_marks,$strength_indus,$location_indus,$indus_datefrom,$indus_dateto,$act2_marks,$strength_indus2,$location_indus2,$indus_datefrom2,$indus_dateto2,$reportSubmitted,$act3_marks,$act4_marks,$strength_trips,$location_trips,$trips_datefrom,$trips_dateto,$act5_marks,$strength_trips2,$location_trips2,$trips_datefrom2,$trips_dateto2){
	$query="INSERT INTO 
						employee_appraisal_proof_data8
						(
							employeeId,sessionId,
							act1_marks,strength_indus,location_indus,indus_datefrom,indus_dateto,
							act2_marks,strength_indus2,location_indus2,test_input6,test_input7,
							reportSubmitted,act3_marks,
							act4_marks,strength_trips,location_trips,trips_datefrom,trips_dateto,
							act5_marks,strength_trips2,location_trips2,test_input8,test_input9
						)
				 VALUES (
						 $employeeId,$sessionId,
						 '$act1_marks','$strength_indus','$location_indus','$indus_datefrom','$indus_dateto',
						 '$act2_marks','$strength_indus2','$location_indus2','$indus_datefrom2','$indus_dateto2',
						 $reportSubmitted,'$act3_marks',
						 '$act4_marks','$strength_trips','$location_trips','$trips_datefrom','$trips_dateto',
						 '$act5_marks','$strength_trips2','$location_trips2','$trips_datefrom2','$trips_dateto2'
						)";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData10($employeeId,$sessionId){
	$query="DELETE FROM employee_appraisal_proof_data9 WHERE employeeId=$employeeId AND sessionId=$sessionId";
	return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData10($employeeId,$sessionId,$eventname_org,$org_strength,$org_budget,$org_heldfrom,$org_heldto,$act1_marks,$eventname_org2,$org_strength2,$org_budget2,$org_heldfrom2,$org_heldto2,$act2_marks,$eventname_assisted,$assisted_strength,$assisted_budget,$assisted_heldfrom,$assisted_heldto,$act3_marks,$eventname_assisted2,$assisted_strength2,$assisted_budget2,$assisted_heldfrom2,$assisted_heldto2,$act4_marks){
		$query="INSERT INTO 
						employee_appraisal_proof_data9
			(
				employeeId,sessionId,
				eventname_org,org_strength,org_budget,org_heldfrom,org_heldto,act1_marks,
				eventname_org2,org_strength2,org_budget2,org_heldfrom2,org_heldto2,act2_marks,
				eventname_assisted,assisted_strength,assisted_budget,assisted_heldfrom,assisted_heldto,act3_marks,
				eventname_assisted2,assisted_strength2,assisted_budget2,assisted_heldfrom2,assisted_heldto2,act4_marks
			)
			VALUES 
			(
				$employeeId,$sessionId,
				'$eventname_org','$org_strength','$org_budget','$org_heldfrom','$org_heldto','$act1_marks',
				'$eventname_org2','$org_strength2','$org_budget2','$org_heldfrom2','$org_heldto2','$act2_marks',
				'$eventname_assisted','$assisted_strength','$assisted_budget','$assisted_heldfrom','$assisted_heldto','$act3_marks',
				'$eventname_assisted2','$assisted_strength2','$assisted_budget2','$assisted_heldfrom2','$assisted_heldto2','$act4_marks'
			)";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData11($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data10 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData11($employeeId,$sessionId,$even_incharge,$even_cases,$even_achieve1,$even_desc1,$even_role1,$even_achieve2,$even_desc2,$even_role2,$odd_incharge,$odd_cases,$odd_achieve1,$odd_desc1,$odd_role1,$odd_achieve2,$odd_desc2,$odd_role2,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks,$even_checked,$even_cases_count,$odd_checked,$odd_cases_count){
    $query="INSERT INTO 
                       employee_appraisal_proof_data10
            (
              employeeId,sessionId,
              even_incharge,even_cases,
              even_achieve1,even_desc1,even_role1,
              even_achieve2,even_desc2,even_role2,
              odd_incharge,odd_cases,
              odd_achieve1,odd_desc1,odd_role1,
              odd_achieve2,odd_desc2,odd_role2,
              act1_marks,act2_marks,act3_marks,
              act4_marks,act5_marks,act6_marks,
              even_checked,even_cases_count,odd_checked,odd_cases_count
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$even_incharge','$even_cases',
                     '$even_achieve1','$even_desc1','$even_role1',
                     '$even_achieve2','$even_desc2','$even_role2',
                     '$odd_incharge','$odd_cases',
                     '$odd_achieve1','$odd_desc1','$odd_role1',
                     '$odd_achieve2','$odd_desc2','$odd_role2',
                     '$act1_marks','$act2_marks','$act3_marks',
                     '$act4_marks','$act5_marks','$act6_marks',
                     '$even_checked','$even_cases_count','$odd_checked','$odd_cases_count'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData12($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data11 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData12($employeeId,$sessionId,$track1,$track2,$track3,$track4,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks,$act7_marks,$act8_marks){
    $query="INSERT INTO 
                       employee_appraisal_proof_data11
            (
              employeeId,sessionId,
              track1,track2,track3,track4,
              act1_marks,act2_marks,act3_marks,act4_marks,
              act5_marks,act6_marks,act7_marks,act8_marks
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$track1','$track2','$track3','$track4',
                     '$act1_marks','$act2_marks','$act3_marks','$act4_marks',
                     '$act5_marks','$act6_marks','$act7_marks','$act8_marks'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function checkCoordinator($employeeId,$conditions='') {
       
        $query = "SELECT 
                        employeeId,isCoordinator
                  FROM 
                        employee 
                  WHERE
                        employeeId=$employeeId
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function checkStudentWelfareDepartment($employeeId,$conditions='') {
       
        $query = "SELECT 
                        e.employeeId,
                        d.isStudentWelfare
                  FROM 
                        employee e,`department` d
                  WHERE
                        e.departmentId=d.departmentId
                        AND e.employeeId=$employeeId
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function deleteProofData13($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data12 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData13($employeeId,$sessionId,$feed_even,$feed_odd){
    $query="INSERT INTO 
                       employee_appraisal_proof_data12
            (
              employeeId,sessionId,
              feed_even,feed_odd
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$feed_even','$feed_odd'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData15($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data13 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData15($employeeId,$sessionId,$even_prob_design,$even_avg_marks,$even_eminent,$odd_prob_design,$odd_avg_marks,$odd_eminent,$times1,$times2,$times3,$times4,$times5,$times6,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks){
    $query="INSERT INTO 
                       employee_appraisal_proof_data13
            (
              employeeId,sessionId,
              even_prob_design,even_avg_marks,even_eminent,
              odd_prob_design,odd_avg_marks,odd_eminent,
              times1,times2,times3,
              times4,times5,times6,
              act1_marks,act2_marks,act3_marks,
              act4_marks,act5_marks,act6_marks
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$even_prob_design','$even_avg_marks','$even_eminent',
                     '$odd_prob_design','$odd_avg_marks','$odd_eminent',
                     '$times1','$times2','$times3',
                     '$times4','$times5','$times6',
                     '$act1_marks','$act2_marks','$act3_marks',
                     '$act4_marks','$act5_marks','$act6_marks'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData16($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data14 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData16($employeeId,$sessionId,$even_advisor,$even_avg_gfs,$even_adv_mt,$even_indis,$odd_advisor,$odd_avg_gfs,$odd_adv_mt,$odd_indis,$times1,$times2,$times3,$times4,$times5,$times6,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$act5_marks,$act6_marks){
    $query="INSERT INTO 
                       employee_appraisal_proof_data14
            (
              employeeId,sessionId,
              even_advisor,even_avg_gfs,even_adv_mt,even_indis,
              odd_advisor,odd_avg_gfs,odd_adv_mt,odd_indis,
              times1,times2,times3,
              times4,times5,times6,
              act1_marks,act2_marks,act3_marks,
              act4_marks,act5_marks,act6_marks
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$even_advisor','$even_avg_gfs','$even_adv_mt','$even_indis',
                     '$odd_advisor','$odd_avg_gfs','$odd_adv_mt','$odd_indis',
                     '$times1','$times2','$times3',
                     '$times4','$times5','$times6',
                     '$act1_marks','$act2_marks','$act3_marks',
                     '$act4_marks','$act5_marks','$act6_marks'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData17($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data15 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData17($employeeId,$sessionId,$act1,$act2,$act3,$act4,$budget1,$budget2,$budget3,$budget4,$org_duties,$imp_duties,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$duties1,$act5_marks,$duties2,$act6_marks){
    $query="INSERT INTO 
                       employee_appraisal_proof_data15
            (
              employeeId,sessionId,
              act1,act2,act3,act4,
              budget1,budget2,budget3,budget4,
              org_duties,imp_duties,
              act1_marks,act2_marks,act3_marks,act4_marks,
              duties1,act5_marks,
              duties2,act6_marks
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$act1','$act2','$act3','$act4',
                     '$budget1','$budget2','$budget3','$budget4',
                     '$org_duties','$imp_duties',
                     '$act1_marks','$act2_marks','$act3_marks','$act4_marks',
                     '$duties1','$act5_marks',
                     '$duties2','$act6_marks'
                   )";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function getEmployeeDepartment($employeeId,$conditions='') {
       
        $query = "SELECT 
                        d.departmentName,d.abbr
                  FROM 
                        employee e,
                        department d
                  WHERE
                        e.employeeId=$employeeId
                        AND e.departmentId=d.departmentId
                        $conditions
                  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


public function deleteProofData18($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data18 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData18($employeeId,$sessionId,$patent_name,$cofiler1,$patent_granted,$cofiler2,$act1_marks,$act2_marks,$file1,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data18
            (
              employeeId,sessionId,
              patent_name,cofiler1,
              patent_granted,cofiler2,
              act1_marks,act2_marks,
              file1,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$patent_name','$cofiler1',
                     '$patent_granted','$cofiler2',
                     '$act1_marks','$act2_marks',
                     '$file1','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData18($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data18 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data18 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData18_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data18 
              SET 
                    patent_name='',
                    cofiler1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data18 
              SET 
                    patent_granted='',
                    cofiler2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}




public function deleteProofData19($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data19 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData19($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$act1_marks,$act2_marks,$file1,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data19
            (
              employeeId,sessionId,
              pub1,co1,jname1,impact1,
              pub2,co2,jname2,impact2,
              act1_marks,act2_marks,
              file1,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$jname1','$impact1',
                     '$pub2','$co2','$jname2','$impact2',
                     '$act1_marks','$act2_marks',
                     '$file1','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData19($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data19 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data19 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData19_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data19 
              SET 
                    pub1='',
                    co1='',
                    jname1='',
                    impact1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data19 
              SET 
                    pub2='',
                    co2='',
                    jname2='',
                    impact2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



public function deleteProofData20($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data20 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData20($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$act1_marks,$act2_marks,$file1,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data20
            (
              employeeId,sessionId,
              pub1,co1,jname1,impact1,
              pub2,co2,jname2,impact2,
              act1_marks,act2_marks,
              file1,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$jname1','$impact1',
                     '$pub2','$co2','$jname2','$impact2',
                     '$act1_marks','$act2_marks',
                     '$file1','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData20($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data20 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data20 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData20_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data20 
              SET 
                    pub1='',
                    co1='',
                    jname1='',
                    impact1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data20 
              SET 
                    pub2='',
                    co2='',
                    jname2='',
                    impact2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData21($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data21 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData21($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$act1_marks,$act2_marks,$act3_marks,$file1,$file2,$file3){
    $query="INSERT INTO 
                       employee_appraisal_proof_data21
            (
              employeeId,sessionId,
              pub1,co1,jname1,impact1,
              pub2,co2,jname2,impact2,
              pub3,co3,jname3,impact3,
              act1_marks,act2_marks,act3_marks,
              file1,file2,file3
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$jname1','$impact1',
                     '$pub2','$co2','$jname2','$impact2',
                     '$pub3','$co3','$jname3','$impact3',
                     '$act1_marks','$act2_marks','$act3_marks',
                     '$file1','$file2','$file3'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData21($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    file3='".$fileName."',
                    act3_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData21_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    pub1='',
                    co1='',
                    jname1='',
                    impact1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    pub2='',
                    co2='',
                    jname2='',
                    impact2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data21 
              SET 
                    pub3='',
                    co3='',
                    jname3='',
                    impact3='',
                    file3=NULL,
                    act3_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData22($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data22 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData22($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$act1_marks,$act2_marks,$act3_marks,$file1,$file2,$file3){
    $query="INSERT INTO 
                       employee_appraisal_proof_data22
            (
              employeeId,sessionId,
              pub1,co1,jname1,impact1,
              pub2,co2,jname2,impact2,
              pub3,co3,jname3,impact3,
              act1_marks,act2_marks,act3_marks,
              file1,file2,file3
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$jname1','$impact1',
                     '$pub2','$co2','$jname2','$impact2',
                     '$pub3','$co3','$jname3','$impact3',
                     '$act1_marks','$act2_marks','$act3_marks',
                     '$file1','$file2','$file3'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData22($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    file3='".$fileName."',
                    act3_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData22_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    pub1='',
                    co1='',
                    jname1='',
                    impact1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    pub2='',
                    co2='',
                    jname2='',
                    impact2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data22 
              SET 
                    pub3='',
                    co3='',
                    jname3='',
                    impact3='',
                    file3=NULL,
                    act3_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData23($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data23 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData23($employeeId,$sessionId,$pub,$co,$publish,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data23
            (
              employeeId,sessionId,
              pub,co,publish,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub','$co','$publish','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData23($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data23 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
       return false;
   } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData23_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data23 
              SET 
                    pub='',
                    co='',
                    publish='',
                    file=NULL,
                    act_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      return false;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



public function deleteProofData24($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data24 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData24($employeeId,$sessionId,$pub1,$co1,$publish1,$act1_marks,$file1,$pub2,$co2,$publish2,$act2_marks,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data24
            (
              employeeId,sessionId,
              pub1,co1,publish1,act1_marks,file1,
              pub2,co2,publish2,act2_marks,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$publish1','$act_marks1','$file1',
                     '$pub2','$co2','$publish2','$act_marks2','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData24($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data24
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
       $query="UPDATE 
                    employee_appraisal_proof_data24
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData24_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data24
              SET 
                    pub1='',
                    co1='',
                    publish1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data24
              SET 
                    pub2='',
                    co2='',
                    publish2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData25($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data25 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData25($employeeId,$sessionId,$pub1,$co1,$conf_name1,$act1_marks,$file1,$pub2,$co2,$conf_name2,$act2_marks,$file1,$file2){
  $query="INSERT INTO 
                       employee_appraisal_proof_data25
            (
              employeeId,sessionId,
              pub1,co1,conf_name1,act1_marks,file1,
              pub2,co2,conf_name2,act2_marks,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$conf_name1','$act1_marks','$file1',
                     '$pub2','$co2','$conf_name2','$act2_marks','$file2'
                   )";
                   
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData25($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data25
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
       $query="UPDATE 
                    employee_appraisal_proof_data25
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData25_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data25
              SET 
                    pub1='',
                    co1='',
                    conf_name1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data25
              SET 
                    pub2='',
                    co2='',
                    conf_name2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



public function deleteProofData26($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data26 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData26($employeeId,$sessionId,$pub1,$co1,$jname1,$impact1,$pub2,$co2,$jname2,$impact2,$pub3,$co3,$jname3,$impact3,$pub4,$co4,$jname4,$impact4,$act1_marks,$act2_marks,$act3_marks,$act4_marks,$file1,$file2,$file3,$file4){
    $query="INSERT INTO 
                       employee_appraisal_proof_data26
            (
              employeeId,sessionId,
              pub1,co1,jname1,impact1,
              pub2,co2,jname2,impact2,
              pub3,co3,jname3,impact3,
              pub4,co4,jname4,impact4,
              act1_marks,act2_marks,act3_marks,act4_marks,
              file1,file2,file3,file4
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$pub1','$co1','$jname1','$impact1',
                     '$pub2','$co2','$jname2','$impact2',
                     '$pub3','$co3','$jname3','$impact3',
                     '$pub4','$co4','$jname4','$impact4',
                     '$act1_marks','$act2_marks','$act3_marks','$act4_marks',
                     '$file1','$file2','$file3','$file4'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData26($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
   else if($mode==3){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    file3='".$fileName."',
                    act3_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
  else{
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    file4='".$fileName."',
                    act4_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
  } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData26_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    pub1='',
                    co1='',
                    jname1='',
                    impact1='',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else if($mode==2){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    pub2='',
                    co2='',
                    jname2='',
                    impact2='',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
   else if($mode==3){
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    pub3='',
                    co3='',
                    jname3='',
                    impact3='',
                    file3=NULL,
                    act3_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
  else{
      $query="UPDATE 
                    employee_appraisal_proof_data26 
              SET 
                    pub4='',
                    co4='',
                    jname4='',
                    impact4='',
                    file4=NULL,
                    act4_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
  } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



public function deleteProofData27($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data27 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData27($employeeId,$sessionId,$workshop1,$institute1,$test_input1,$test_input2,$act1_marks,$file1,$workshop2,$institute2,$test_input3,$test_input4,$act2_marks,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data27
            (
              employeeId,sessionId,
              workshop1,institute1,test_input1,test_input2,act1_marks,file1,
              workshop2,institute2,test_input3,test_input4,act2_marks,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$workshop1','$institute1','$test_input1','$test_input2','$act1_marks','$file1',
                     '$workshop2','$institute2','$test_input3','$test_input4','$act2_marks','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData27($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data27 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data27 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData27_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data27 
              SET 
                    workshop1='',
                    institute1='',
                    test_input1='0000-00-00',
                    test_input2='0000-00-00',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data27 
              SET 
                    workshop2='',
                    institute2='',
                    test_input3='0000-00-00',
                    test_input4='0000-00-00',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData28($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data28 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData28($employeeId,$sessionId,$workshop1,$institute1,$test_input1,$test_input2,$act1_marks,$file1,$workshop2,$institute2,$test_input3,$test_input4,$act2_marks,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data28
            (
              employeeId,sessionId,
              workshop1,institute1,test_input1,test_input2,act1_marks,file1,
              workshop2,institute2,test_input3,test_input4,act2_marks,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$workshop1','$institute1','$test_input1','$test_input2','$act1_marks','$file1',
                     '$workshop2','$institute2','$test_input3','$test_input4','$act2_marks','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData28($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data28 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data28 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData28_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data28 
              SET 
                    workshop1='',
                    institute1='',
                    test_input1='0000-00-00',
                    test_input2='0000-00-00',
                    file1=NULL,
                    act1_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
   else{
      $query="UPDATE 
                    employee_appraisal_proof_data28 
              SET 
                    workshop2='',
                    institute2='',
                    test_input3='0000-00-00',
                    test_input4='0000-00-00',
                    file2=NULL,
                    act2_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData29($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data29 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData29($employeeId,$sessionId,$proposal,$agency,$test_input,$costing,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data29
            (
              employeeId,sessionId,
              proposal,agency,test_input,costing,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$proposal','$agency','$test_input','$costing','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData29($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data29 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData29_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data29 
              SET 
                    proposal='',
                    agency='',
                    costing='0',
                    test_input='0000-00-00',
                    file=NULL,
                    act_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData30($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data30 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData30($employeeId,$sessionId,$proposal,$agency,$test_input,$costing,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data30
            (
              employeeId,sessionId,
              proposal,agency,test_input,costing,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$proposal','$agency','$test_input','$costing','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData30($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data30 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
    }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData30_Adv($employeeId,$sessionId,$mode){
    //actually this function performs updations
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data30 
              SET 
                    proposal='',
                    agency='',
                    costing='0',
                    test_input='0000-00-00',
                    file=NULL,
                    act_marks=0
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
    }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}



public function deleteProofData31($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data31 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData31($employeeId,$sessionId,$project,$agency,$test_input,$costing,$add_amount,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data31
            (
              employeeId,sessionId,
              project,agency,test_input,costing,add_amount,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$project','$agency','$test_input','$costing','$add_amount','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData31($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data31 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData31_Adv($employeeId,$sessionId,$mode){

   if($mode==1){
      $query="DELETE 
              FROM  
                    employee_appraisal_proof_data31 
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData32($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data32 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData32($employeeId,$sessionId,$project,$agency,$test_input,$costing,$add_amount,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data32
            (
              employeeId,sessionId,
              project,agency,test_input,costing,add_amount,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$project','$agency','$test_input','$costing','$add_amount','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData32($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data32 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData32_Adv($employeeId,$sessionId,$mode){

   if($mode==1){
      $query="DELETE 
              FROM  
                    employee_appraisal_proof_data32 
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData33($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data33 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData33($employeeId,$sessionId,$description,$byWhom,$test_input,$act_marks,$file){
    $query="INSERT INTO 
                       employee_appraisal_proof_data33
            (
              employeeId,sessionId,
              description,byWhom,test_input,act_marks,file
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$description','$byWhom','$test_input','$act_marks','$file'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData33($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data33 
              SET 
                    file='".$fileName."',
                    act_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData33_Adv($employeeId,$sessionId,$mode){

   if($mode==1){
      $query="DELETE 
              FROM  
                    employee_appraisal_proof_data33 
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData34($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data34 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData34($employeeId,$sessionId,$description1,$byWhom1,$test_input1,$act1_marks,$file1,$description2,$byWhom2,$test_input2,$act2_marks,$file2){
    $query="INSERT INTO 
                       employee_appraisal_proof_data34
            (
              employeeId,sessionId,
              description1,byWhom1,test_input1,act1_marks,file1,
              description2,byWhom2,test_input2,act2_marks,file2
            )
            VALUES (
                     $employeeId,$sessionId,
                     '$description1','$byWhom1','$test_input1','$act1_marks','$file1',
                     '$description2','$byWhom2','$test_input2','$act2_marks','$file2'
                   )";
    
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function updateProofData34($proofDataId,$fileName,$marks,$mode){
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data34 
              SET 
                    file1='".$fileName."',
                    act1_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
   }
  else{
      $query="UPDATE 
                    employee_appraisal_proof_data34 
              SET 
                    file2='".$fileName."',
                    act2_marks=".$marks."
              WHERE
                    proofDataId=".$proofDataId;
  } 
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function deleteProofData34_Adv($employeeId,$sessionId,$mode){

    //this function actually performs updation operation
    //as two records are stored in one row and displayed as two 
    //seperate records to the users
    
   if($mode==1){
      $query="UPDATE 
                    employee_appraisal_proof_data34
              SET
                    description1='',
                    byWhom1='',
                    test_input1='0000-00-00',
                    act1_marks=0,
                    file1=NULL
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;
   }
   else{
     $query="UPDATE 
                    employee_appraisal_proof_data34
              SET
                    description2='',
                    byWhom2='',
                    test_input2='0000-00-00',
                    act2_marks=0,
                    file2=NULL
              WHERE
                    employeeId=".$employeeId."
                    AND sessionId=".$sessionId;  
   }
    
  return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


//********************************THIS FUNCTIONS ARE NEEDED HOD(SUPERIOR) PART****************************************

public function getEmployeeList($superiorEmpId,$conditions='', $limit = '', $orderBy=' e.employeeName') {

       global $sessionHandler;
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       
       $query = "SELECT 
                            e.employeeId, e.employeeName,e.employeeCode,e.employeeAbbreviation,
                            IF(e.isTeaching=1,'YES','NO') AS isTeaching,e.qualification,
                            e.dateOfJoining,
                            d.designationName,br.branchCode,r.roleName
                  FROM 
                            designation d,`user` u,`role` r,`branch` br,
                            employee e
                            inner JOIN employee_hierarchy eh ON ( eh.subordinateEmployeeId=e.employeeId AND eh.superiorEmployeeId=$superiorEmpId AND eh.sessionId=$sessionId AND eh.instituteId=$instituteId )
                  WHERE     
                            e.designationId=d.designationId
                            AND e.isActive=1
                            AND e.branchId=br.branchId
                            AND e.instituteId=".$instituteId."
                            AND e.employeeId !=$superiorEmpId
                            AND e.userId=u.userId AND u.roleId=r.roleId
                  $conditions 
                  ORDER BY $orderBy $limit";
        
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function deleteProofData14($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data16 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData14($employeeId,$sessionId,$oddsem,$evensem,$score_gained){
    $query="INSERT INTO 
                       employee_appraisal_proof_data16
            (employeeId,sessionId,oddsem,evensem,score_gained)
            VALUES ($employeeId,$sessionId,'$oddsem','$evensem','$score_gained')";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}


public function deleteProofData1_1($employeeId,$sessionId){
    $query="DELETE FROM employee_appraisal_proof_data17 WHERE employeeId=$employeeId AND sessionId=$sessionId";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}

public function insertProofData1_1($employeeId,$sessionId,$internal,$external,$copies_checked,$superValue,$weekends,$score_gained){
    $query="INSERT INTO 
                       employee_appraisal_proof_data17
            (employeeId,sessionId,internal,external,copies_checked,super,weekends,score_gained)
            VALUES ($employeeId,$sessionId,'$internal','$external','$copies_checked','$superValue','$weekends','$score_gained')";
    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
}    

    
//********************************THIS FUNCTIONS ARE NEEDED HOD(SUPERIOR) PART****************************************************
  
}

// $History: AppraisalDataManager.inc.php $
?>