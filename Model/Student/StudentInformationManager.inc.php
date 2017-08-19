<?php
require_once(DA_PATH . '/SystemDatabaseManager.inc.php');
require_once($FE . "/Library/common.inc.php"); //for sessionId   and InstituteId

class StudentInformationManager {
	private static $instance = null;

	private function __construct() {
	}
	public static function getInstance() {
		if (self::$instance === null) {
			$class = __CLASS__;
			return self::$instance = new $class;
		}
		return self::$instance;
	}

//-------------------------------------------------------
//  THIS FUNCTION IS UPDATE TO parent Password (father/Mother/guardian)
//
// Author :Parveen Sharma
// Created on : (29-May-2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function updateParentPassword($condition='') {

        $query = "UPDATE ".$condition;

        return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query);
    }

//----------------------- Student Information Detail---------------------------------------

 //------------------------------------------------------------------------------------------------
// This Function  give the list of student information
//
// Author : Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getStudentInformationList($studentId){
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
        $query = "	SELECT
								s.*,
                                sa.*,
                                CONCAT(IFNULL(s.firstName,''),' ',IFNULL(s.lastName,'')) AS studentName,
								c.countryName,
								c.nationalityName,
								st.stateName,
								q.quotaName,
								u.userName,
								uf.userName as fatherUserName,
								um.userName as motherUserName,
								ug.userName as guardianUserName,
								un.universityName,
								deg.degreeName,
								br.branchName,
								bt.batchName,
								sp.periodName,
								icardNumber,
								if(managementCategory=1, managementReference,''),
								hs.hostelName,
								hr.roomName,
								bsr.routeName,
								bs.stopName,
								cc.countryName as corrCountry,
								cs.stateName as corrState,
								cct.cityName as corrCity,
                                cc.countryId as corrCountryId,
                                cs.stateId as corrStateId,
                                cct.cityId as corrCityId,
								permcn.countryName as permCountry,
								permst.stateName as permState,
								permct.cityName as permCity,
                                permcn.countryId as permCountryId,
                                permst.stateId as permStateId,
                                permct.cityId as permCityId,
								fatcn.countryName as fatherCountry,
								fatst.stateName as fatherState,
								fatct.cityName as fatherCity,
								motcn.countryName as motherCountry,
								motst.stateName as motherState,
								motct.cityName as motherCity,
								gudcn.countryName as guardCountry,
								gudst.stateName as guardState,
								gudct.CityName as guardCity,
                                hst.possibleDateOfCheckOut,
                                s.highestEducationalQualification,
                                IFNULL(spf.programFeeId,'') AS programFeeId
					FROM		class cl,
								degree deg,
								branch br,
								batch bt,
								study_period sp,
								university un,
								student s
                    LEFT JOIN student_program_fee spf ON spf.programFeeId=s.programFeeId
					LEFT JOIN	countries c ON ( s.nationalityId = c.countryId )
					LEFT JOIN	states st ON ( s.domicileId = st.stateId )
					LEFT JOIN	quota q ON ( s.quotaId = q.quotaId )
					LEFT JOIN	user u ON ( s.userId = u.userId )
					LEFT JOIN	countries cc ON ( s.corrCountryId=cc.countryId )
					LEFT JOIN	states cs ON ( s.corrStateId=cs.stateId )
					LEFT JOIN	city cct ON ( s.corrCityId = cct.cityId )
					LEFT JOIN	countries permcn ON ( s.permCountryId = permcn.countryId )
					LEFT JOIN	states permst ON ( s.permStateId = permst.stateId )
					LEFT JOIN	city permct ON ( s.permCityId=permct.cityId )
					LEFT JOIN	countries fatcn ON ( s.fatherCountryId=fatcn.countryId )
					LEFT JOIN	states fatst ON ( s.fatherStateId=fatst.stateId )
					LEFT JOIN	city fatct ON ( s.fatherCityId=fatct.cityId )
					LEFT JOIN	countries motcn ON ( s.motherCountryId=motcn.countryId )
					LEFT JOIN	states motst ON ( s.motherStateId=motst.stateId )
					LEFT JOIN	city motct ON ( s.motherCityId=motct.cityId )
					LEFT JOIN	countries gudcn ON ( s.guardianCountryId=gudcn.countryId )
					LEFT JOIN	states gudst ON ( s.guardianStateId=gudst.stateId )
					LEFT JOIN	city gudct ON ( s.guardianCityId=gudct.cityId )
					LEFT JOIN	user uf ON ( s.fatherUserId=uf.userId )
					LEFT JOIN	user um ON ( s.motherUserId=um.userId )
					LEFT JOIN	user ug ON ( s.guardianUserId=ug.userId )
					LEFT JOIN	hostel hs ON ( s.hostelId=hs.hostelId )
					LEFT JOIN	hostel_room hr ON (s.hostelRoomId = hr.hostelRoomId)
                    LEFT JOIN   hostel_students hst ON (s.studentId = hst.studentId)
					LEFT JOIN	bus_route bsr ON (s.busRouteId=bsr.busRouteId)
					LEFT JOIN	bus_stop bs ON (s.busStopId=bs.busStopId)
                    LEFT JOIN   student_ailment sa ON s.studentId = sa.studentId

					WHERE		s.studentId=$studentId
					AND			s.classId=cl.classId
					AND			cl.universityId=un.universityId
					AND			cl.degreeId=deg.degreeId
					AND			cl.branchId=br.branchId
					AND			cl.batchId=bt.batchId
					AND			cl.studyPeriodId=sp.studyPeriodId
					AND			cl.instituteId=$instituteId
					AND			cl.sessionId=$sessionId";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//this function fetches documents attached by students
public function getStudentAttachedDocuments($studentId,$conditions=''){
    $query="SELECT * FROM student_document WHERE studentId=$studentId $conditions";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getStudentPaymentDetails($studentId,$conditions=''){
    $query="SELECT * FROM student_payment_details WHERE studentId=$studentId $conditions";
    return SystemDatabaseManager::getInstance()->executeQuery($query);
}


//-------------------- Teacher Comments On Student ------------------------------------------

//------------------------------------------------------------------------------------------------
// This Function  give the list of teacher comments
//
// Author : Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
    public function getTeacherComments($conditions='', $limit = '', $orderBy='comments') {
    global $sessionHandler;
       $query = "SELECT comments,studentId
       FROM
       teacher_comment
       WHERE
       studentId='".$sessionHandler->getSessionVariable('StudentId')."'
       AND curdate() BETWEEN visibleFromDate AND visibleToDate

        $conditions
        ORDER BY $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  counts the total comments of student
//
// Author : Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


   /* public function getTotalComments($conditions='') {
    global $sessionHandler;
        $query = "SELECT COUNT(*) AS totalRecords
        FROM
       teacher_comment
       WHERE
       studentId='".$sessionHandler->getSessionVariable('StudentId')."'
       AND curdate() BETWEEN visibleFromDate AND visibleToDate";
       return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }*/


//------------------------------------------------------------------------------------------------
// This Function  gets the Comments List  for "DashBoard" Module
//
// Author : Jaineesh
// Created on : 15-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


    public function getCommentsListing($conditions='') {

		global $sessionHandler;
		$query = "SELECT tc.commentId, tc.comments,e.employeeName,tcd.visibleFromDate,tcd.visibleToDate,tc.commentAttachment
		FROM
		teacher_comment_detail tcd,employee e, teacher_comment tc
		WHERE
		tc.teacherId=e.employeeId
		AND
		tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
		AND
		e.isTeaching='1' AND tcd.dashboard='1' AND tcd.toStudent='1' AND tcd.toParent='0' AND tc.commentId=tcd.commentId
		AND tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
		AND (curdate() BETWEEN tcd.visibleFromDate AND tcd.visibleToDate )

        $conditions $limit
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getTotalComments($conditions='') {

		global $sessionHandler;
		$query = "SELECT COUNT(*) as totalRecords
		FROM
		teacher_comment_detail tcd,employee e, teacher_comment tc
		WHERE
		tc.teacherId=e.employeeId
		AND
		tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
		AND
		e.isTeaching='1' AND tcd.dashboard='1' AND tcd.toStudent='1' AND tcd.toParent='0' AND tc.commentId=tcd.commentId
		AND tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
		AND (curdate() BETWEEN tcd.visibleFromDate AND tcd.visibleToDate )

        $conditions $limit
        ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getCommentsListing1($conditions='', $orderBy='tcd.visibleToDate',$limit='LIMIT 0,5') {

		global $sessionHandler;
		$query = "SELECT tc.commentId, tc.comments,e.employeeName,tcd.visibleFromDate,tcd.visibleToDate
		FROM
		teacher_comment_detail tcd,employee e, teacher_comment tc
		WHERE
		tc.teacherId=e.employeeId
		AND
		tcd.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
		AND
		e.isTeaching='1' AND tcd.dashboard='1' AND tcd.toStudent='1' AND tcd.toParent='0' AND tc.commentId=tcd.commentId
		AND tc.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
		AND tc.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
		AND (curdate() BETWEEN tcd.visibleFromDate AND tcd.visibleToDate )
		$conditions ORDER BY $orderBy $limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

   //-------------------------------------------------------------------------------
// THIS FUNCTION IS USED To Check STUDENT FEE IS PENDING OR NOT
// Author :Nishu Bindal
// Created on : (8.Feb.2012)
// Copyright 2012-2013: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------           

    public function showFeeAlert($migrationStudyPeriod=''){

        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
        $classId = $sessionHandler->getSessionVariable('ClassId');
        $studentId = $sessionHandler->getSessionVariable('StudentId');
        
	    if($migrationStudyPeriod==''){
	      $migrationStudyPeriod = 0;
	    }
                
        //$query = "SELECT count(feeCycleId) AS cnt ,cycleName FROM `fee_cycle_new` WHERE instituteId = '$instituteId' AND now('Y-m-d') between fromDate and toDate AND  (SELECT DISTINCT classId FROM fee_head_values_new where classId IN( SELECT min(classId) FROM class cc WHERE cc.classId > '$classId' AND CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN (SELECT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) FROM student s, class c WHERE c.classId = s.classId AND s.studentId='$studentId'))) AND status =1 Group By feeCycleId";
	    //fee alert according to fee cycle academic ,hostel,transport from and to dates
	    $isFeeCycleCheck='1'; 
	    if($isFeeCycleCheck=='0'){  
	      $feeCycleAcedemicCondition=  "AND (now('Y-m-d') BETWEEN fcn.fromDate AND fcn.toDate)";
	      $feeCycleHostelCondition=    "AND (now('Y-m-d') BETWEEN f.fromDate AND f.toDate)";
	      $feeCycleTransportCondition= "AND (now('Y-m-d') BETWEEN ff.fromDate AND ff.toDate)";
	    }
        else {
	      // $feeCycleAcedemicCondition=  "AND (now('Y-m-d') BETWEEN fcn.academicFromDate AND fcn.academicToDate)";
	      // $feeCycleHostelCondition=    "AND (now('Y-m-d') BETWEEN f.hostelFromDate AND f.hostelToDate)";
	       //$feeCycleTransportCondition= "AND (now('Y-m-d') BETWEEN ff.transportFromDate AND ff.transportToDate)";
	    }

        $query ="SELECT    
                        DISTINCT frm.classId AS feeClassId, 
                                 CONCAT(TRIM(SUBSTRING_INDEX(cls.className,'-',-1)),' (',fcn.cycleName,')') AS cycleName
                    FROM     
                        `fee_cycle_new` fcn , `fee_head_values_new` frm, class cls, study_period sp   
                    WHERE 
                        cls.classId = frm.classId   
                        AND fcn.feeCycleId = frm.feeCycleId
			            AND cls.studyPeriodId = sp.studyPeriodId                       
			                                    AND frm.classId IN (SELECT 
                                                 DISTINCT classId 
                                             FROM 
                                                 class cc 
                                             WHERE 
                                                 CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN 
                                                 (SELECT 
                                                      DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) 
                                                  FROM 
                                                      student s, class c WHERE c.classId = s.classId 
						                          AND s.studentId='$studentId'))
			            AND sp.periodValue >=$migrationStudyPeriod
                    ORDER BY 
                       frm.classId";
		 $query1 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                 
         
         $query ="SELECT    
                        DISTINCT hs.classId AS feeClassId, 
                                 CONCAT(TRIM(SUBSTRING_INDEX(hc.className,'-',-1)),' (',f.cycleName,')') AS cycleName
                  FROM     
                        `fee_cycle_new` f , `hostel_students` hs, class hc
                  WHERE   
                        f.feeCycleId = hs.feeCycleId 
                        AND hc.classId = hs.classId
			           
                        AND hs.studentId = '$studentId'
                  ORDER BY
                       hs.classId";
          $query2 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                                                   
          
          
          $query ="SELECT    
                        DISTINCT brsm.classId AS feeClassId, CONCAT(TRIM(SUBSTRING_INDEX(cc.className,'-',-1)),' (',ff.cycleName,')') AS cycleName
                    FROM     
                        `fee_cycle_new` ff , `bus_route_student_mapping` brsm, class cc
                    WHERE   
                        ff.feeCycleId = brsm.feeCycleId 
                        AND brsm.classId = cc.classId
			           			
                        AND brsm.studentId = '$studentId'      
                  ORDER BY 
                      brsm.classId";
          $query3 = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");              
                        
          $valueArray = array();
          for($i=0;$i<count($query1);$i++) {
            $valueArray[]= array("feeClassId"=>$query1[$i]['feeClassId'],
                                   "cycleName"=>$query1[$i]['cycleName']
                                 );
          }                       
          for($i=0;$i<count($query2);$i++) {
            $feeClassId = $query2[$i]['feeClassId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query2[$i]['feeClassId'],
                                    "cycleName"=>$query2[$i]['cycleName']
                                    );
            }
          }
          
          for($i=0;$i<count($query3);$i++) {
            $feeClassId = $query3[$i]['feeClassId'];  
            $findId='';
            for($j=0;$j<count($valueArray);$j++) {
              if($valueArray[$j]['feeClassId']==$feeClassId) {
                $findId='1';
                break;  
              }
            }
            if($findId=='') {
               $valueArray[]= array("feeClassId"=>$query3[$i]['feeClassId'],
                                    "cycleName"=>$query3[$i]['cycleName']
                                    );
            }
          }
          return $valueArray;            
    }
    

	public function showFeeAlertOld(){
		global $sessionHandler;
        	$instituteId = $sessionHandler->getSessionVariable('InstituteId');
        	$classId = $sessionHandler->getSessionVariable('ClassId');
        	$studentId = $sessionHandler->getSessionVariable('StudentId');
        		
		//$query = "SELECT count(feeCycleId) AS cnt ,cycleName FROM `fee_cycle_new` WHERE instituteId = '$instituteId' AND now('Y-m-d') between fromDate and toDate AND  (SELECT DISTINCT classId FROM fee_head_values_new where classId IN( SELECT min(classId) FROM class cc WHERE cc.classId > '$classId' AND CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN (SELECT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) FROM student s, class c WHERE c.classId = s.classId AND s.studentId='$studentId'))) AND status =1 Group By feeCycleId";
		
		$query ="SELECT	
                        DISTINCT frm.feeReceiptId,frm.feeClassId, fcn.cycleName 
					FROM 	
                        `fee_cycle_new` fcn , `fee_receipt_master` frm 
					WHERE	
                        fcn.feeCycleId = frm.feeCycleId
					    AND	now('Y-m-d') between fcn.fromDate and fcn.toDate
					    AND	frm.studentId = '$studentId'
					    AND	frm.status =1  	
					    AND	frm.feeClassId IN 
                            (SELECT 
                                 DISTINCT classId 
                             FROM 
                                 class cc 
                             WHERE 
                                 CONCAT_WS(',',cc.batchId,cc.degreeId,cc.branchId) IN 
                                 (SELECT 
                                      DISTINCT CONCAT_WS(',',c.batchId,c.degreeId,c.branchId) 
                                  FROM 
                                      student s, class c WHERE c.classId = s.classId AND s.studentId='$studentId')
                            )
					ORDER BY 
                        frm.feeClassId";
					
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//----------------- INSTITUTE NOTICES ON STUDENT ---------------------------------------

//----------------------------------------------------------------------------------------
//  This Function gets the data of Institute Notice
// Author :Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------

public function getClassMiscInfo($classId){
    $query="SELECT * FROM class WHERE classId=$classId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getStudentClass($studentId){
    $query="SELECT classId FROM student WHERE studentId=$studentId";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getInstituteNoticesCount($conditions='') {
  
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $curDate=date('Y')."-".date('m')."-".date('d');

    $extraCondition='';
    if($roleId==4 or $roleId==3){
        if($roleId==4){
         $classId=$sessionHandler->getSessionVariable('ClassId');
        }
        else{
          $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
          $classId=$classArray[0]['classId'];
        }
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )';
        }
    }

    $query="SELECT
                    COUNT(*) AS totalRecords
            FROM        
                    (SELECT 
                            DISTINCT n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName ,
                            n.visibleMode
                    FROM    
                            department d, notice n INNER JOIN notice_visible_to_role nvr ON  ( n.noticeId=nvr.noticeId $extraCondition ) 
                            AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
                    WHERE    
                            nvr.roleId=$roleId          
                            AND nvr.instituteId=$instituteId 
                            AND n.instituteId=$instituteId 
                            AND nvr.sessionId=$sessionId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                    GROUP BY 
                            n.noticeId
                    UNION  
                    SELECT 
                            DISTINCT  n.noticeId, 
                            n.noticeText,
                            n.noticeSubject,
                            n.visibleFromDate,
                            n.visibleToDate,
                            n.noticeAttachment,
                            n.downloadCount,
                            d.abbr,
                            d.departmentName,
                            n.visibleMode
                      FROM  
                            department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
                      WHERE        
                            nvr.noticeInstituteId=$instituteId 
                            AND n.departmentId = d.departmentId 
                            $conditions 
                      GROUP BY 
                            n.noticeId ) AS tt " ;              
           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    public function getInstituteNotices($conditions='',$limit='',$orderBy='') {
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
    $instituteId=$sessionHandler->getSessionVariable('InstituteId');
    $sessionId=$sessionHandler->getSessionVariable('SessionId');
    $curDate=date('Y')."-".date('m')."-".date('d');

    $extraCondition='';
    if($roleId==4 or $roleId==3){
        if($roleId==4){
         $classId=$sessionHandler->getSessionVariable('ClassId');
        }
        else{
          $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
          $classId=$classArray[0]['classId'];
        }
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )
                                ';
        }
    }

    $query="SELECT 
                    DISTINCT n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName ,
                    n.visibleMode
            FROM    
                    department d, notice n INNER JOIN notice_visible_to_role nvr ON  (n.noticeId=nvr.noticeId $extraCondition) 
                    AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
            WHERE    
                    nvr.roleId=$roleId          
                    AND nvr.instituteId=$instituteId 
                    AND n.instituteId=$instituteId 
                    AND nvr.sessionId=$sessionId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
            GROUP BY 
                    n.noticeId
            UNION  
            SELECT 
                    DISTINCT  n.noticeId, 
                    n.noticeText,
                    n.noticeSubject,
                    n.visibleFromDate,
                    n.visibleToDate,
                    n.noticeAttachment,
                    n.downloadCount,
                    d.abbr,
                    d.departmentName,
                    n.visibleMode
              FROM  
                    department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
              WHERE        
                    nvr.noticeInstituteId=$instituteId 
                    AND n.departmentId = d.departmentId 
                    $conditions 
              GROUP BY 
                    n.noticeId
              ORDER BY 
                    $orderBy $limit " ;              
           
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	public function getInstituteNotices1($conditions='', $orderBy=' visibleFromDate DESC, visibleMode DESC, noticeId DESC ',$limit = 'LIMIT 0,15') {
    global $sessionHandler;
    $roleId=$sessionHandler->getSessionVariable('RoleId');
	$instituteId=$sessionHandler->getSessionVariable('InstituteId');
	$sessionId=$sessionHandler->getSessionVariable('SessionId');
	$curDate=date('Y')."-".date('m')."-".date('d');

    $classId='0';
    
    $extraCondition='';
    if($roleId==4 or $roleId==3){
        if($roleId==4){
         $classId=$sessionHandler->getSessionVariable('ClassId');
        }
        else{
          $classArray=$this->getStudentClass($sessionHandler->getSessionVariable('StudentId'));
          $classId=$classArray[0]['classId'];
        }
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )
                               ';
        }
    }

    $query="SELECT
				DISTINCT  n.noticeId, 
                n.noticeText,
                n.noticeSubject,
                n.visibleFromDate,
                n.visibleToDate,
                n.noticeAttachment,
                n.downloadCount,
                d.abbr,
                d.departmentName,
                n.visibleMode
		FROM
                department d, notice n
                INNER JOIN notice_visible_to_role nvr ON (n.noticeId=nvr.noticeId) $extraCondition
                AND isClass = CASE WHEN '1' THEN (SELECT 
                                                        DISTINCT 1 FROM notice_visible_to_class c 
                                                  WHERE 
                                                        n.noticeId=c.noticeId AND c.classId='$classId' LIMIT 0,1)  ELSE '0' END
		WHERE		
                nvr.roleId=$roleId
                AND	nvr.instituteId=$instituteId
                AND	n.instituteId=$instituteId
                AND	nvr.sessionId=$sessionId
                AND	n.departmentId = d.departmentId
                AND	'$curDate' >= n.visibleFromDate
                AND	'$curDate' <= n.visibleToDate
                $conditions
        GROUP BY
               n.noticeId
        UNION  
        SELECT 
                DISTINCT  n.noticeId, 
                n.noticeText,
                n.noticeSubject,
                n.visibleFromDate,
                n.visibleToDate,
                n.noticeAttachment,
                n.downloadCount,
                d.abbr,
                d.departmentName,
                n.visibleMode
          FROM  
                department d, notice n INNER JOIN notice_visible_to_institute nvr ON (n.noticeId=nvr.noticeId) 
          WHERE        
                nvr.noticeInstituteId=$instituteId 
                AND n.departmentId = d.departmentId 
                AND '$curDate' >= n.visibleFromDate
                AND '$curDate' <= n.visibleToDate
                $conditions 
          GROUP BY 
                n.noticeId                     
          ORDER BY 
                $orderBy $limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//----------------------------------------------------------------------------------------
//  Count the total no. of fields in notice table
//
// Author :Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------

    public function getTotalNotices($conditions='') {
    global $sessionHandler;

 $roleId=$sessionHandler->getSessionVariable('RoleId');
 $instituteId=$sessionHandler->getSessionVariable('InstituteId');
 $sessionId=$sessionHandler->getSessionVariable('SessionId');
 $curDate=date('Y')."-".date('m')."-".date('d');

 $extraCondition='';
 if($roleId==4){
        $classId=$sessionHandler->getSessionVariable('ClassId');
        //get university,degree and branchId of this class
        $classArray=$this->getClassMiscInfo($classId);
        if(is_array($classArray)>0 and count($classArray)>0){
            $extraCondition=' AND (
                                    (nvr.universityId IS NULL OR nvr.universityId='.$classArray[0]['universityId'].')
                                     AND
                                    (nvr.degreeId IS NULL OR nvr.degreeId='.$classArray[0]['degreeId'].')
                                     AND
                                    (nvr.branchId IS NULL OR nvr.branchId='.$classArray[0]['branchId'].')
                                   )';
        }
    }

 $query="	SELECT
					COUNT(*) AS totalRecords
            FROM
                    department d,
                    notice n
                    INNER JOIN notice_visible_to_role nvr ON ( n.noticeId=nvr.noticeId ) $extraCondition
		WHERE		nvr.roleId=$roleId
		AND			n.instituteId=$instituteId
		AND			nvr.instituteId=$instituteId
		AND			nvr.sessionId=$sessionId
		AND			n.departmentId = d.departmentId
		AND			'$curDate' >= n.visibleFromDate
		AND			'$curDate' <= n.visibleToDate
					$conditions ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//----------------------------------------------------------------------------------------
//  This Function inserts the notices read into notice_read table
//
//
// Author :Abhiraj
// Created on : 19-May-2010
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------

    public function noticeStatus($noticeId) {

     global $sessionHandler;
     $query="select noticeId from notice_read where noticeId=".$noticeId." and userId=".$sessionHandler->getSessionVariable('UserId');
     logError($query);
     $found=SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     if(count($found)==0)
     {
         if($sessionHandler->getSessionVariable('RoleId')==3 || $sessionHandler->getSessionVariable('RoleId')==4)
         {
             $query1 = "insert into notice_read(userId,noticeId) values(".$sessionHandler->getSessionVariable('UserId').",".$noticeId.")";
             logError($query1);
             return SystemDatabaseManager::getInstance()->executeUpdate($query1,"Query: $query");
         }
     }
    }

//---------------------- STUDENT MARKS DETAIL -----------------------------------------------


//------------------------------------------------------------------------------------------------
// This function contains the subject name from subject table
//
// Author : Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getSubject() {
        global $sessionHandler;
       $query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls
        WHERE
        sub.subjectId = subcls.subjectId
        AND
        subcls.classId='". $sessionHandler->getSessionVariable('ClassId')."'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------------------------------------
// This function contains the marks & percentage on internal & external test
//
// Author : Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getStudentMarks($studentId,$classId,$limit,$orderby,$holdStudentClassId='') {

		
		global $REQUEST_DATA;
		global $sessionHandler;
        if ($classId != "" and $classId != "0") {
		  $classCond =" AND cl.classId =".add_slashes($classId);
		}
		     
					 
        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cl.holdTestMarks = '0' "; 
        }
        
        if($holdStudentClassId!='') {
           $classCond .= " AND cl.classId NOT IN (".$holdStudentClassId.")";
        }



        $query = "	SELECT
						CONCAT(IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName),' (',IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode),')') AS subject,
						CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						ttc.testTypeName,t.testDate,
						emp.employeeName,
						CONCAT( t.testAbbr, t.testIndex ) AS testName,
						IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode) AS subjectCode,
  IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName) AS subjectName,
						(tm.maxMarks) AS totalMarks,
						IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')) AS obtained,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
						gr.groupName
				FROM	test_type_category ttc,
						 ".TEST_MARKS_TABLE."  tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl,
						`group` gr,
						subject_to_class b
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND     b.classId=t.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND     b.subjectId = tm.subjectId
				AND		tm.studentId =$studentId
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$classCond
						$isHoldCondition
						ORDER BY $orderby $limit ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	//------------------------------------------------------------------------------------------------
// This function contains the marks & percentage on internal & external test
//
// Author : Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getStudentMarks1($classId,$limit='') {
         if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA;
		global $sessionHandler;


	


$query = "	SELECT
						CONCAT(IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName),' (',IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode),')') AS subject,
						CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						ttc.testTypeName,DATE_FORMAT(t.testDate,'%d-%b-%Y') AS testDate,
						emp.employeeName,
						CONCAT( t.testAbbr, t.testIndex ) AS testName,
						IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode) AS subjectCode,
  						IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName) AS subjectName,
						(tm.maxMarks) AS totalMarks,
						ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')),1)  AS obtained,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
						gr.groupName, cl.classId
				FROM	test_type_category ttc,
						 ".TEST_MARKS_TABLE." tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl,
						`group` gr,
						subject_to_class b
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND     b.classId = t.classId
				AND     b.subjectId = tm.subjectId
				AND		tm.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$classCond
						
						ORDER BY t.testDate DESC $limit ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

		//------------------------------------------------------------------------------------------------
// This function contains the marks & percentage on internal & external test
//
// Author : Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getStudentMarks2($classId,$limit='') {
        
		global $REQUEST_DATA;
		global $sessionHandler;
		
		 if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

				 
        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cl.holdTestMarks = '0' "; 
        }


 $query = "	SELECT
						CONCAT( IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName),' (',IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode),')') AS subject,
						CONCAT(IF( ttc.examType = 'PC', 'Internal', 'External' ), ' (' , ttc.testTypeName, ')' ) AS examType,
						ttc.testTypeName,DATE_FORMAT(t.testDate,'%d-%b-%Y') AS testDate,
						emp.employeeName,
						CONCAT( t.testAbbr, t.testIndex ) AS testName,
						IF(b.isAlternateSubject='0',su.subjectCode,su.alternateSubjectCode) AS subjectCode,
 						 IF(b.isAlternateSubject='0',su.subjectName,su.alternateSubjectName) AS subjectName,
						(tm.maxMarks) AS totalMarks,
						ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')),1)  AS obtained,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
						gr.groupName, cl.classId
				FROM	test_type_category ttc,
						 ".TEST_MARKS_TABLE." tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl,
						`group` gr,
						subject_to_class b
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND     b.classId = t.classId
				AND     b.subjectId = tm.subjectId
				AND		tm.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$classCond
						$isHoldCondition
						ORDER BY t.testDate DESC $limit ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This function contains the marks & percentage on internal & external test
//
// Author : Jaineesh
// Created on : 05.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getTotalStudentMarks($studentId,$classId,$holdStudentClassId='') {

		
		global $REQUEST_DATA;
		global $sessionHandler;

         if($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		 }

			 
        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cl.holdTestMarks = '0' "; 
        }

        if($holdStudentClassId!='') {
           $classCond .= " AND cl.classId NOT IN (".$holdStudentClassId.")";
        }

        $query = "	SELECT
						COUNT(*) as totalRecords
				FROM	test_type_category ttc,
						 ".TEST_MARKS_TABLE." tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl,
						`group` gr
				WHERE	t.testTypeCategoryId = ttc.testTypeCategoryId
				AND		t.classId=cl.classId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		t.groupId = gr.groupId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND		tm.studentId =$studentId
				AND		cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND		cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
						$classCond
				$isHoldCondition ";


        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
// ------------------------ DASHBOARD LISTING -------------------------------------------

 //------------------------------------------------------------------------------------------------
// This Function  gets the Events List  for "DashBoard" Module
//
// Author : Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getTotalEvents($conditions=''){

		global $sessionHandler;
		$roleId=$sessionHandler->getSessionVariable('RoleId');
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$curDate=date('Y-m-d');

		$query="SELECT		COUNT(*) as totalRecords
				FROM		event e
				WHERE		FIND_IN_SET('$roleId',REPLACE(e.roleIds,\"~\",\",\"))
				AND			e.instituteId=$instituteId AND e.sessionId=$sessionId
				AND			DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY)<='$curDate'
				AND			e.endDate>='$curDate'
							$conditions " ;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

	public function getEventList($conditions='', $orderBy='',$limit = ''){

		global $sessionHandler;
		$roleId=$sessionHandler->getSessionVariable('RoleId');
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$curDate=date('Y-m-d');

		$query="SELECT
							e.eventId,
							e.eventTitle,
							e.shortDescription,
							e.longDescription,
							e.startDate,
							e.endDate
				FROM		event e
				WHERE		FIND_IN_SET('$roleId',REPLACE(e.roleIds,\"~\",\",\"))
				AND			e.instituteId=$instituteId
				AND			e.sessionId=$sessionId
				AND			DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY)<='$curDate'

		$conditions ORDER BY $orderBy $limit" ;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}


	public function getEventList1($conditions='', $orderBy=' e.startDate desc',$limit = 'LIMIT 0,5'){

		global $sessionHandler;
		$roleId=$sessionHandler->getSessionVariable('RoleId');
		$instituteId=$sessionHandler->getSessionVariable('InstituteId');
		$sessionId=$sessionHandler->getSessionVariable('SessionId');
		$curDate=date('Y-m-d');

		$query="SELECT
						e.eventId,
						e.eventTitle,
						e.shortDescription,
						e.longDescription,
						e.startDate,e.endDate
				FROM	event e
				WHERE	FIND_IN_SET('$roleId',REPLACE(e.roleIds,\"~\",\",\"))
				AND 	e.instituteId=$instituteId AND e.sessionId=$sessionId
				AND		DATE_SUB(e.startDate,INTERVAL ".EVENT_DAY_PRIOR." DAY)<='$curDate'
				AND		e.endDate>='$curDate'
						$conditions ORDER BY $orderBy $limit " ;
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

//------------------------------------------------------------------------------------------------
// This Function  gets the Comments List  for "Display Marks" Module
//
// Author : Jaineesh
// Created on : 30.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

    public function getDisplayMarksSubjectClass() {
        global $sessionHandler;
        $query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls
        WHERE
        sub.subjectId = subcls.subjectId
        AND
         subcls.classId='". $sessionHandler->getSessionVariable('ClassId')."'";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------
/////                         Display Attendance module Function                          ////////
//------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------
// This Function gets the subjects from
//
// Author : Jaineesh
// Created on : 23.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
    public function getSubjectClass() {
       global $sessionHandler;
        $query = "SELECT sub.subjectId, sub.subjectName
        FROM subject sub, subject_to_class subcls,student s
        WHERE sub.subjectId = subcls.subjectId
         AND
          subcls.classId=s.classId
          AND
          s.studentId='".$sessionHandler->getSessionVariable('StudentId')."'
           ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


    //------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of student
//
// Author : Jaineesh
// Created on : 22.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

      public function getStudentTimeTable ($classId,$classFetchId,$orderBy=' p.periodNumber, tt.daysOfWeek',$fieldName='') {

        if($classId != "" and $classId != "0") {
		   $classCond =" AND cl.classId =".add_slashes($classId);
		}
		if($classId==0) {
		   $classCond = " AND cl.classId =".add_slashes($classFetchId);
		}
        global $sessionHandler;

        if($fieldName=='' ) {
          $fieldName = " tt.periodId,  tt.daysOfWeek, p.periodNumber,
                         CONCAT(p.startTime,p.startAmPm,' ',endTime,endAmPm) AS pTime,
                         s.studentId, sub.subjectCode,  sub.subjectName,
                         sub.subjectAbbreviation, emp.employeeName, r.roomName,
                         r.roomAbbreviation, gr.groupName,
                         SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName,
                         cl.className, ttc.timeTableLabelId, gr.groupShort,
                         tt.fromDate, ttl.timeTableType";
        }

	    $query = "SELECT
				        $fieldName
	              FROM
                         ".TIME_TABLE_TABLE."  tt, `period` p,
				        `student` s, `subject` sub,
				        `employee` emp, `room` r,
				        `block` bl, `student_groups` sg,
				        `time_table_labels` ttl, `time_table_classes` ttc,
				        `group` gr, class cl
	                WHERE
                        tt.periodId = p.periodId
	                    AND	 s.studentId=sg.studentId
	                    AND	 tt.subjectId = sub.subjectId
	                    AND	 sg.groupId = gr.groupId
	                    AND	 tt.groupId = sg.groupId
	                    AND	 tt.employeeId=emp.employeeId
	                    AND	 r.blockId = bl.blockId
	                    AND	 tt.roomId = r.roomId
	                    AND	 tt.toDate IS NULL
	                    AND	 tt.timeTableLabelId = ttl.timeTableLabelId
	                    AND	 ttl.timeTableLabelId = ttc.timeTableLabelId
	                    AND	 sg.classId = ttc.classId
	                    AND	 sg.classId = cl.classId
	                    AND	 sg.studentId=".$sessionHandler->getSessionVariable('StudentId')."
	                    AND	 tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
	                    AND	 tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
				    $classCond
                    UNION
                    SELECT
                            $fieldName
                    FROM
                             ".TIME_TABLE_TABLE."  tt, `period` p,
                            `student` s, `subject` sub,
                            `employee` emp, `room` r,
                            `block` bl, `student_optional_subject` sg,
                            `time_table_labels` ttl, `time_table_classes` ttc,
                            `group` gr, class cl
                    WHERE
                            tt.periodId = p.periodId
                            AND  s.studentId=sg.studentId
                            AND  tt.subjectId = sub.subjectId
                            AND  sg.groupId = gr.groupId
                            AND  tt.groupId = sg.groupId
                            AND  tt.employeeId=emp.employeeId
                            AND  r.blockId = bl.blockId
                            AND  tt.roomId = r.roomId
                            AND  tt.toDate IS NULL
                            AND  tt.timeTableLabelId = ttl.timeTableLabelId
                            AND  ttl.timeTableLabelId = ttc.timeTableLabelId
                            AND  sg.classId = ttc.classId
                            AND  sg.classId = cl.classId
                            AND  sg.studentId=".$sessionHandler->getSessionVariable('StudentId')."
                            AND  tt.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
                            AND  tt.sessionId=".$sessionHandler->getSessionVariable('SessionId')."
                    $classCond
				    ORDER BY $orderBy";

         return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }


//--------------------------------------------------------------------------------
// THIS FUNCTION IS USED TO FETCH PERIOD LIST IN manage Time table
//
// Author :Parveen Sharma
// Created on : (08.04.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------
    public function getTimeTablePeriodList($conditions='',$orderBy=' p.periodSlotId, p.periodNumber') {

       $query = "SELECT
                        DISTINCT periodNumber, CONCAT(startTime,startAmPm) AS psTime, CONCAT(endTime,endAmPm) AS peTime
                  FROM
                        period p LEFT JOIN  ".TIME_TABLE_TABLE."  tt ON
                        p.periodSlotId IN (SELECT DISTINCT periodSlotId FROM  period p1 WHERE p1.periodId=tt.periodId AND $conditions)
                  WHERE
                        $conditions
                  ORDER BY $orderBy";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  executes if time table change
//
// Author : Jaineesh
// Created on : 15.10.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getStudentTimeTable1 ($classId)
      {
           global $sessionHandler;
		   $curDate=date('Y')."-".date('m')."-".date('d');
		   if ($classId != "" ) {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }
$query = "SELECT
						count(tt.fromDate) as cnt
				FROM	 ".TIME_TABLE_TABLE."  tt,
						`time_table_labels` ttl,
						`student_groups` sg,
						 student s,
						 subject sub,
						 class cl

				WHERE	tt.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				AND		tt.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."

				AND		tt.timeTableLabelId = ttl.timeTableLabelId
				AND		ttl.isActive = '1'
				AND		tt.fromDate = '$curDate'
				AND		tt.subjectId = sub.subjectId
				AND		s.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
				AND     sg.studentId = s.studentId
						$classCond";

return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//------------------------------------------------------------------------------------------------
// This Function is used to get student mobile numbers
// Created on : 8.03.11
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function getStudentMobileNumbers($classId){
	$query = "SELECT
							studentMobileNo
				FROM	`student`
				WHERE	classId = $classId";

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//this function is used to check for update in timetable
public function checkForChangeInTimeTable($classId){
	$query = "SELECT
							COUNT(employeeId) AS cnt
					FROM	 ".TIME_TABLE_TABLE." 
					WHERE	classId = $classId
					AND		fromDate = curdate()
					AND		toDate IS NULL";

	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}


//------------------------------------------------------------------------------------------------
// This Function  gets the data from fee_receipt of student
//
// Author : Jaineesh
// Created on : 29.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


      public function getStudentFee($conditions='', $orderBy=' receiptNo',$limit='') {
         global $sessionHandler;
		 $query="SELECT
                 DISTINCT(totalFeePayable), SUM( fine ) AS totalFine, SUM( totalAmountPaid ) AS totalAmountPaid, s.periodName
                 FROM fee_receipt fr, study_period s
                 WHERE s.studyPeriodId = fr.feeStudyPeriodId
                 AND fr.studentId=".$sessionHandler->getSessionVariable('StudentId')."
				AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                GROUP BY fr.feeCycleId,fr.feeStudyPeriodId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//------------------------------------------------------------------------------------------------
// This Function  count the data from fee_receipt of student
//
// Author : Jaineesh
// Created on : 29.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


      public function getTotalFee($conditions='') {
         global $sessionHandler;
        $query = "SELECT count(*) as totalRecords
        FROM fee_receipt
        WHERE studentId=".$sessionHandler->getSessionVariable('StudentId')."

        AND instituteId = ".$sessionHandler->getSessionVariable('InstituteId')." ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //------------------------------------------------------------------------------------------------
// This Function  gets the "Fee status" in ALERT List for "DashBoard" Module
//
// Author : Jaineesh
// Created on : 30.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

public function getFeeStatus()
{
    global $sessionHandler;
   $query = "SELECT sp.periodName,((fr.totalFeePayable+fr.fine)-
	sum(fr.totalAmountPaid)) as pending
	FROM `fee_receipt` fr, fee_cycle fc,study_period sp
	WHERE fr.studentId ='".$sessionHandler->getSessionVariable('StudentId')."'
	AND fc.feeCycleId = fr.feeCycleId
	AND (CURRENT_DATE() BETWEEN fc.fromDate AND fc.toDate)
	AND sp.studyPeriodId=fr.currentStudyPeriodId
	GROUP BY fr.currentStudyPeriodId,fr.feeCycleId
	HAVING pending>0";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

public function getFeeStatus1($limit='')
{
    global $sessionHandler;
  $query = "SELECT sp.periodName,((fr.totalFeePayable+fr.fine)-
	sum(fr.totalAmountPaid)) as pending
	FROM `fee_receipt` fr, fee_cycle fc,study_period sp
	WHERE fr.studentId ='".$sessionHandler->getSessionVariable('StudentId')."'
	AND fc.feeCycleId = fr.feeCycleId
	AND (CURRENT_DATE() BETWEEN fc.fromDate AND fc.toDate)
	AND sp.studyPeriodId=fr.currentStudyPeriodId
	GROUP BY fr.currentStudyPeriodId,fr.feeCycleId
	HAVING pending>0
	ORDER BY fc.toDate DESC $limit";

    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//------------------------------------------------------------------------------------------------
// This Function  gets the short attendance of student
//
// Author : Jaineesh
// Created on : 22.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
public function getShortAttendance($classId,$limit='') {
		global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$attendance = $sessionHandler->getSessionVariable('ATTENDANCE_THRESHOLD');
		$studentId = $sessionHandler->getSessionVariable('StudentId');
		if ($classId != "" and $classId != "0") {
			$classCond =" AND d.classId =".add_slashes($classId);
		   }
		 $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND d.holdAttendance = '0' "; 
        }


	$query = "
				SELECT
							a.studentId,
							e.subjectCode,
							ROUND(SUM(IF(a.isMemberOfClass = 0,0, if(b.attendanceCodePercentage IS NULL, a.lectureAttended, b.attendanceCodePercentage/100))) / SUM(IF(a.isMemberOfClass = 0,0, IF(b.attendanceCodePercentage IS NULL, a.lectureDelivered, 1))) * 100,2) AS per
				FROM		student c, subject e, class d,  ".ATTENDANCE_TABLE." a
				LEFT JOIN	attendance_code b
				ON			(a.attendanceCodeId = b.attendanceCodeId and b.instituteId = $instituteId)
				WHERE		a.subjectId in (select subjectId from subject_to_class where classId = a.classId)
				AND			a.studentId = c.studentId
				AND			a.classId=c.classId
				AND			c.classId=d.classId
				AND			d.isActive = 1
    			AND			d.sessionId = $sessionId
				AND			d.instituteId = $instituteId
				AND			a.studentId=$studentId
				AND			a.subjectId = e.subjectId
				AND			(CURRENT_DATE() BETWEEN a.fromDate AND a.toDate)
							$classCond
							$isHoldCondition 
				GROUP BY	a.subjectId having per < ".$attendance."
				ORDER BY	a.fromDate DESC
							$limit";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

// THIS FUNCTION IS USED FOR displaying student birthday
//
// Author :Jaineesh
// Created on : (30.07.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------
public function checkBirthDay(){
    global $sessionHandler;
    $studentId=$sessionHandler->getSessionVariable('StudentId');

 $query="SELECT COUNT(*) AS birthDay
            FROM student WHERE studentId=".$studentId." AND day(dateOfBirth)=day(CURDATE()) AND month(dateofBirth)=month(CURDATE())";
    return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//------------------------------------------------------------------------------------------------
// This Function  gets the data from time table of student
//
// Author : Jaineesh
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------


 public function getClassName ()
      {
           global $sessionHandler;
        $query = "SELECT  c.className, s.rollNo
                  FROM class c,student s
                  WHERE c.classId=s.classId
AND s.studentId=".$sessionHandler->getSessionVariable('StudentId')."
AND c.instituteId=".$sessionHandler->getSessionVariable('InstituteId')."
AND c.sessionId=".$sessionHandler->getSessionVariable('SessionId')."


";
   return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

	public function getTotalAdminMessages ($condition='') {
        global $sessionHandler;

		$studentId = $sessionHandler->getSessionVariable('StudentId');
		$query = "SELECT COUNT(*) AS totalRecords FROM	admin_messages am,
							user u
					WHERE	FIND_IN_SET('$studentId',REPLACE(receiverIds,\"~\",\",\"))
					AND		am.messageType='Dashboard'
					AND		am.senderId=u.userId
					AND		am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					AND		u.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					AND		am.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
					AND		am.receiverType='Student'
					AND		am.visibleToDate >= curdate()
							$condition $limit" ;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

	public function getAdminMessages ($condition='',$orderBy='',$limit='') {
        global $sessionHandler;

		$studentId = $sessionHandler->getSessionVariable('StudentId');

			$query = "SELECT am.messageId, am.message, am.subject, u.userName, am.visibleFromDate, am.visibleToDate, am.messageFile FROM admin_messages am, user u
			WHERE FIND_IN_SET('$studentId',REPLACE(receiverIds,\"~\",\",\"))
			AND am.messageType='Dashboard'
			AND am.senderId=u.userId
			AND am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
			AND u.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
			AND am.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
			AND am.receiverType='Student'
			AND	am.visibleToDate >= curdate()
			$condition ORDER BY $orderBy $limit" ;

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

	  public function getAdminMessages1 ($orderBy="visibleFromDate DESC",$limit='LIMIT 0,5') {
        global $sessionHandler;

		$studentId = $sessionHandler->getSessionVariable('StudentId');
		//TO make the Admin message to parent appear in the parent dashboard BEGIN
		$roleId = $sessionHandler->getSessionVariable('RoleId');
		$userId = $sessionHandler->getSessionVariable('UserId');
		$receiveType = '';
		if ($roleId=='3') 
		{ 	$query = "SELECT userId,fatherUserId,motherUserId,guardianUserId FROM student WHERE fatherUserId = '$userId' LIMIT 	0,1";
			$returnArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
			if(count($returnArray)>0 && is_array($returnArray)) 
			{	if($userId==$returnArray[0]['userId'])
				{	$receiveType = 'Student';
					$studentUserId=$userId;
				}
				elseif ($userId==$returnArray[0]['fatherUserId'])
				{	$receiverType = 'Father';
					$studentUserId=$returnArray[0]['userId']; // Because messages are stored as per student userId even if addressed to parents
				}
				elseif ($userId==$returnArray[0]['motherUserId'])
				{	$studentUserId=$returnArray[0]['userId'];// Because messages are stored as per student userId even if addressed
					$receiverType = 'Mother';
				}
				elseif ($userId==$returnArray[0]['guardianUserId'])
				{	$studentUserId=$returnArray[0]['userId'];// Because messages are stored as per student userId even if addressed
					$receiverType = 'Guardian';
				}
				else 
				{	echo "Wrong User Id, Kindly contact syenergy help desk";
					die;
				}
			}
		}
		//TO make the Admin message to parent appear in the parent dashboard END
		$query = "	SELECT
							am.messageId,
							am.message,
							am.subject,
							am.dated,
							am.visibleFromDate,
							am.visibleToDate,
							u.userName, am.messageFile
					FROM	admin_messages am,
							user u
					WHERE	FIND_IN_SET('$studentUserId',REPLACE(receiverIds,\"~\",\",\"))
					AND		am.messageType='Dashboard'
					AND 	u.userId='$studentUserId'
					AND		am.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					AND		u.instituteId='".$sessionHandler->getSessionVariable('InstituteId')."'
					AND		am.sessionId='".$sessionHandler->getSessionVariable('SessionId')."'
					AND		am.receiverType='$receiverType'
					AND		am.visibleToDate >= curdate()
							ORDER BY $orderBy $limit" ;
		// TO make the Admin message to parent appear in the parent dashboard --Line about receiver type corrected
		// we don't check the sender type. SO MESSAGES FROM ALL SENDERS WILL APPEAR IN ADMINB MESSAGES
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
      }

//------------------------------------------------------------------------------------------------
// This Function  gets the student test marks
//
// Author : Jaineesh
// Created on : 03.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	  public function getScStudentMarks1($limit='') {
        global $REQUEST_DATA;
        global $sessionHandler;
		$sessionId = $sessionHandler->getSessionVariable('SessionId');
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$studentId = $sessionHandler->getSessionVariable('StudentId');

    $query = "SELECT
						CONCAT(su.subjectAbbreviation,' (',su.subjectCode,')') AS subject,
						CONCAT(IF( tt.conductingAuthority =1, 'Internal', 'External' ), ' (' , tt.testTypeName, ')' ) AS examType,
						tt.testTypeName,DATE_FORMAT(t.testDate,'%d-%b-%Y') AS testDate,
						emp.employeeName,
						CONCAT( t.testAbbr, t.testIndex ) AS testName,
						su.subjectCode,
						(tm.maxMarks) AS totalMarks,
						ROUND(IF(tm.isMemberOfClass =0, 'Not MOC',IF(isPresent=1,tm.marksScored,'A')))  AS obtained,
						SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName
				FROM	test_type tt,
						 ".TEST_MARKS_TABLE." tm,
						student s,
						subject su,
						employee emp,
						".TEST_TABLE." t,
						class cl
				WHERE	t.testTypeId = tt.testTypeId
				AND		emp.employeeId=t.employeeId
				AND		t.testId = tm.testId
				AND		tm.studentId = s.studentId
				AND		tm.subjectId = su.subjectId
				AND		tm.studentId =$studentId
				AND		cl.sessionId = $sessionId
				AND		cl.instituteId = $instituteId
				AND		tt.instituteId = $instituteId
ORDER BY				t.testDate DESC $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//------------------------------------------------------------------------------------------------
// This Function  gets the data from course_resources, resource_category
//
// Author : Jaineesh
// Created on : 04.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------
		 public function getStudentCourseResourceLists($studentId='',$classId='',$filter='',$orderBy='',$limit){
       global $REQUEST_DATA;
     global $sessionHandler;

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
     $classCondition='';
	 if ($classId != "" and $classId != "0") {
		$classCondition =" AND c.classId =".add_slashes($classId);
	 }
	 
	$query ="SELECT
	                DISTINCT sc.subjectId
	          FROM
	                class c, subject_to_class sc, student_groups sg
	          WHERE
	          	    c.classId = sc.classId
	                AND sc.classId=sg.classId
	                AND sg.studentId=$studentId
	                AND sg.instituteId=$instituteId
	                AND sg.sessionId=$sessionId
	                $classCondition
	          UNION
	          SELECT
	                 DISTINCT sg.subjectId
	          FROM
	                 class c, student_optional_subject sg
	          WHERE
	                 c.classId=sg.classId
	                 AND sg.studentId=$studentId
	                 AND c.instituteId=$instituteId
	                 AND c.sessionId=$sessionId
	                 $classCondition
	          UNION
	          SELECT
	                 DISTINCT sg.subjectId
	           FROM
	                 class c,  subject_to_class s, optional_subject_to_class sg
	           WHERE
		           sg.classId = s.classId AND s.optional=1 AND 
				   sg.parentOfSubjectId = s.subjectId AND
				   c.classId = s.classId
				   AND s.subjectId = sg.subjectId   
				   AND c.classId=sg.classId
	   			   AND c.instituteId=$instituteId
	 			   AND c.sessionId=$sessionId
 			  	   $classCondition ";
 			  	   
    $retArray = SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");                         
	
	$subjectIds='0';
	for($i=0;$i<count($retArray);$i++) {
	  $subjectIds .=",".$retArray[$i]['subjectId']; 
	}
	 
    $query="	SELECT
						courseResourceId,
						resourceName,
						description,
						s.subjectCode,
						e.employeeName,
						resourceUrl,
						attachmentFile,
						postedDate

				FROM
						course_resources a,
						resource_category r,
						`subject` s,
						employee e

				WHERE	a.resourceTypeId=r.resourceTypeId
				AND		a.subjectId=s.subjectId
				AND		a.employeeId=e.employeeId
				AND		a.instituteId=$instituteId
				AND		r.instituteId=$instituteId
				AND		a.sessionId=$sessionId
				AND		a.subjectId IN ($subjectIds)
  			    $filter 
  			    ORDER BY 
  			    	$orderBy $limit " ;
						
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }



	 public function getStudentCourseResourceList($studentId,$classId,$filter, $orderBy='',$limit){

     global $REQUEST_DATA;
     global $sessionHandler;
//     $studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
	 if ($classId != "" and $classId != "0") {
		$classCond =" AND stc.classId =".add_slashes($classId);
	 }

    $query="	SELECT
						courseResourceId,
						resourceName,
						description,
						IF(b.isAlternateSubject='0',s.subjectCode,s.alternateSubjectCode) AS subjectCode,
  						IF(b.isAlternateSubject='0',s.subjectName,s.alternateSubjectName) AS subjectName,
						e.employeeName,
						resourceUrl,
						attachmentFile,
						postedDate

				FROM
						course_resources a,
						resource_category r,
						`subject` s,
						employee e,
						subject_to_class b

				WHERE	a.resourceTypeId=r.resourceTypeId
				AND		a.subjectId=s.subjectId
				AND     b.subjectId=a.subjectId
				AND     b.classId IN ($classId)
				AND		a.employeeId=e.employeeId
				AND		a.instituteId=$instituteId
				AND		r.instituteId=$instituteId
				AND		a.sessionId=$sessionId
				AND		a.subjectId
				
				 IN
                    (
                      SELECT
                            DISTINCT sc.subjectId
                      FROM
                            subject_to_class sc, student_groups sg
                      WHERE
                            sc.classId=sg.classId
                            AND sg.studentId=$studentId
                            AND sg.instituteId=$instituteId
                            AND sg.sessionId=$sessionId
                            $classCondition
                      UNION
                       SELECT
                             DISTINCT sg.subjectId
                       FROM
                             class c, student_optional_subject sg
                       WHERE
                             c.classId=sg.classId
                             AND sg.studentId=$studentId
                             AND c.instituteId=$instituteId
                             AND c.sessionId=$sessionId
                             $classCondition
                    )

						$conditions $filter ORDER BY $orderBy $limit " ;
		//echo $query;die;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------------------------------------
// This Function  gets the data from course_resources, resource_category
//
// Author : Jaineesh
// Created on : 04.12.08


// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	 public function getCourseResourceList($studentId,$classList,$filter, $orderBy='',$limit){
     global $REQUEST_DATA;
     global $sessionHandler;
//     $studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
	 if($classList != '') {
		$query="	SELECT
							courseResourceId,
							resourceName,
							description,
							s.subjectCode,
							e.employeeName,
							resourceUrl,
							attachmentFile,
							postedDate
					FROM
							course_resources a,
							resource_category r,
							`subject` s,
							employee e

					WHERE	a.resourceTypeId=r.resourceTypeId
					AND		a.subjectId=s.subjectId
					AND		a.employeeId=e.employeeId
					AND		a.instituteId=$instituteId
					AND		r.instituteId=$instituteId
					AND		a.sessionId=$sessionId
					AND		a.subjectId
					IN
							(
								SELECT DISTINCT stc.subjectId
								FROM subject_to_class stc, `student_groups` sg
								WHERE stc.classId=sg.classId
								AND sg.instituteId = $instituteId
								AND sg.sessionId = $sessionId
								AND stc.classId IN ($classList)
							)

							$conditions $filter ORDER BY $orderBy $limit" ;
	 }
//echo $query;die;
  return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
}

//------------------------------------------------------------------------------------------------
// This Function  gets the data from course_resources, resource_category
//
// Author : Jaineesh
// Created on : 04.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	 public function getTotalCourseResourceList($studentId,$classList,$filter){
     global $REQUEST_DATA;
     global $sessionHandler;
//     $studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
	 if($classList != '') {
		 $query="	SELECT
							COUNT(*) AS totalRecords
					FROM
							course_resources,
							resource_category,
							subject,
							employee

					WHERE	course_resources.resourceTypeId=resource_category.resourceTypeId
					AND		course_resources.subjectId=subject.subjectId
					AND		course_resources.employeeId=employee.employeeId
					AND		course_resources.instituteId=$instituteId
					AND		course_resources.sessionId=$sessionId
					AND		resource_category.instituteId=$instituteId
					AND		course_resources.subjectId
					IN
							(
								SELECT DISTINCT stc.subjectId
								FROM subject_to_class stc, `student_groups` sg
								WHERE stc.classId=sg.classId
								AND sg.instituteId = $instituteId
								AND sg.sessionId = $sessionId
								AND stc.classId IN ($classList)
							)

							$conditions $filter" ;
		}

     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//------------------------------------------------------------------------------------------------
// This Function  gets the data from course_resources, resource_category
//
// Author : Jaineesh
// Created on : 04.12.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	 public function getTotalStudentCourseResourceList($studentId,$classId,$filter){
     global $REQUEST_DATA;
     global $sessionHandler;
//     $studentId=( trim($REQUEST_DATA['id'])=="" ? 0 : trim($REQUEST_DATA['id']) );

     $instituteId=$sessionHandler->getSessionVariable('InstituteId');
     $sessionId=$sessionHandler->getSessionVariable('SessionId');
	 if ($classId != "" and $classId != "0") {
		$classCond =" AND stc.classId =".add_slashes($classId);
	   }
	   else {
		$classCond =" AND stc.classId = -1";
	  }

if ($classId != 0) {
  $query="	SELECT
						COUNT(*) AS totalRecords
				FROM
						course_resources a,
						resource_category r,
						subject s,
						employee e

				WHERE	a.resourceTypeId=r.resourceTypeId
				AND		a.subjectId=s.subjectId
				AND		a.employeeId=e.employeeId
				AND		a.instituteId=$instituteId
				AND		a.sessionId=$sessionId
				AND		r.instituteId=$instituteId
				AND		a.subjectId
				IN
						(
							SELECT DISTINCT stc.subjectId
							FROM subject_to_class stc, `student_groups` sg
							WHERE stc.classId=sg.classId
							AND sg.instituteId = $instituteId
							AND sg.sessionId = $sessionId
							$classCond
						)

						$conditions $filter " ;
}
else {
	 $query="	SELECT
						COUNT(*) AS totalRecords
				FROM
						course_resources a,
						resource_category r,
						subject s,
						employee e

				WHERE	a.resourceTypeId=r.resourceTypeId
				AND		a.subjectId=s.subjectId
				AND		a.employeeId=e.employeeId
				AND		a.instituteId=$instituteId
				AND		a.sessionId=$sessionId
				AND		r.instituteId=$instituteId
				AND		a.subjectId
				IN
						(
							SELECT DISTINCT stc.subjectId
							FROM subject_to_class stc, `student_groups` sg
							WHERE stc.classId=sg.classId
							AND sg.instituteId = $instituteId
							AND sg.sessionId = $sessionId
						)

						$conditions $filter" ;
}

     //echo $query;
      return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

		//------------------------------------------------------------------------------------------------
// This Function  gets the data of student group detail
//
// Author : Jaineesh
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getStudentGroup($studentId,$classId,$limit,$orderBy) {

		if ($classId != "" and $classId != "0") {
			$classCond =" AND sg.classId =".add_slashes($classId);
		   }

		global $sessionHandler;

 $query = "	SELECT
								gr.groupName,
								IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  								IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
								gt.groupTypeName,
                                gt.groupTypeCode,
								
								SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS periodName
					FROM		`student_groups` sg,
								`group` gr,
								`class` cls ,
								`subject_to_class` stc,
								`subject` sub,
								`group_type` gt,
								student s
					WHERE		cls.classId = sg.classId
					AND			stc.subjectId = sub.subjectId
					AND			stc.classId = cls.classId
					AND			sg.groupId = gr.groupId
					AND			gr.groupTypeId = gt.groupTypeId
					AND			sg.studentId = s.studentId
					AND			sg.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			sg.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			s.studentId=$studentId

								$classCond
					UNION
								SELECT
								gr.groupName,
								IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  								IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
								gt.groupTypeName,
                                gt.groupTypeCode,
								SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS periodName
					FROM		`group` gr,
								`class` cls ,
								`subject_to_class` stc,
								`subject` sub,
								`group_type` gt,
								student_optional_subject sos,
								student s
					WHERE		s.studentId=$studentId
					AND			sos.classId = cls.classId
					AND			sos.groupId = gr.groupId
					AND			sos.studentId = s.studentId
					AND			gr.groupTypeId=gt.groupTypeId
					AND 		sos.subjectId = sub.subjectId
					AND			cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
								ORDER BY  $orderBy $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	//------------------------------------------------------------------------------------------------
// This Function  gets the data of student group detail
//
// Author : Jaineesh
// Created on : 25.07.08
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//------------------------------------------------------------------------------------------------

	public function getStudentGroupDetail($studentId,$classId,$orderBy) {

		if ($classId != "" and $classId != "0") {
			$classCond =" AND sg.classId =".add_slashes($classId);
		   }

		global $sessionHandler;

 $query = "	SELECT
								gr.groupName,
								IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
								gt.groupTypeName,
                                gt.groupTypeCode,
								SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS periodName
					FROM		`student_groups` sg,
								`group` gr,
								`class` cls ,
								`subject_to_class` stc,
								`subject` sub,
								`group_type` gt,
								student s
					WHERE		cls.classId = sg.classId
					AND			stc.subjectId = sub.subjectId
					AND			stc.classId = cls.classId
					AND			sg.groupId = gr.groupId
					AND			gr.groupTypeId = gt.groupTypeId
					AND			sg.studentId = s.studentId
					AND			sg.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			sg.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
					AND			s.studentId=$studentId

								$classCond
					UNION
								SELECT
								gr.groupName,
								IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
								gt.groupTypeName,
                                gt.groupTypeCode,
								SUBSTRING_INDEX(cls.className,'".CLASS_SEPRATOR."',-1) AS periodName
					FROM		`group` gr,
								`class` cls ,
								`subject_to_class` stc,
								`subject` sub,
								`group_type` gt,
								student_optional_subject sos,
								student s
					WHERE		s.studentId=$studentId
					AND			sos.classId = cls.classId
					AND			sos.groupId = gr.groupId
					AND			sos.studentId = s.studentId
					AND			gr.groupTypeId=gt.groupTypeId
					AND 		sos.subjectId = sub.subjectId
					AND			cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
					AND			cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
								ORDER BY  $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Student CGPA
//
//
// Author :Jaineesh
// Created on : 10-11-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getStudentCGPA($studentId = '',$classId) {
		global $sessionHandler;
		$instituteId = $sessionHandler->getSessionVariable('InstituteId');
		$str = "";
		if ($studentId != '') {
		  $str .= " AND a.studentId = $studentId ";
		}
		else {
		  $str .= " AND a.studentId = " . $sessionHandler->getSessionVariable('StudentId');
		}
		$query = "
				SELECT
								distinct a.subjectId, b.gradePoints, c.credits
				FROM			".TOTAL_TRANSFERRED_MARKS_TABLE." a, grades b, subject_to_class c
				WHERE			a.classId = c.classId
				AND				a.gradeId = b.gradeId
				AND				a.classId = $classId
				AND				a.subjectId = c.subjectId
				AND				b.instituteId = $instituteId
				AND				a.isActive = 1 $str
				UNION
				SELECT
								distinct a.subjectId, b.gradePoints, c.credits
				FROM			".TOTAL_UPDATED_MARKS_TABLE." a, grades b, subject_to_class c
				WHERE			a.classId = c.classId
				AND				a.gradeId = b.gradeId
				AND				a.classId = $classId
				AND				a.subjectId = c.subjectId
				AND				b.instituteId = $instituteId
				AND				a.isActive = 1 $str";
				//echo $query;die;
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET A LIST OF STUDENT ATTENDANCE FOR SC Modules
//
//
// Author :Jaineesh
// Created on : (13-08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
	public function getAttendanceDashboard($studentId,$classId) {
		global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');
		 $query = "SELECT
							s.studentId,
							sub.subjectCode,
							ROUND( SUM( IF( att.isMemberOfClass = 0, 0, IF( att.attendanceType = 2, ( ac.attendanceCodePercentage / 100 ), att.lectureAttended ) ) ), 2 ) AS attended,
							SUM( IF( att.isMemberOfClass = 0, 0, att.lectureDelivered ) ) AS delivered
				 FROM			student s,
							`subject` sub,
							attendance1 att
				LEFT JOIN attendance_code ac
					ON(ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
				WHERE s.studentId = $studentId
			   AND s.classId = $classId
               AND s.studentId = att.studentId
			   AND s.classId = att.classId
			   AND att.subjectId = sub.subjectId
			GROUP BY att.subjectId;";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
	}

   /*  public function getAttendanceDashboard($studentId,$classId){
        if ($classId != "" ) {
        $classCond =" and s.classId = $classId ";
        }
        global $sessionHandler;
        $instituteId = $sessionHandler->getSessionVariable('InstituteId');

        $query="
                SELECT
											s.rollNo,
											su.subjectCode,
											CONCAT( su.subjectName, ' (', su.subjectCode, ')' ) AS `subject`,
											sp.periodName,
											ROUND( SUM( IF( att.isMemberOfClass = 0, 0, IF( att.attendanceType = 2, ( ac.attendanceCodePercentage / 100 ), att.lectureAttended ) ) ), 2 ) AS attended,
											SUM( IF( att.isMemberOfClass = 0, 0, att.lectureDelivered ) ) AS delivered,
											DATE_FORMAT( MIN( att.fromDate ), '%d-%b-%y' ) AS fromDate,
											DATE_FORMAT( MAX( att.toDate ), '%d-%b-%y' ) AS toDate
						FROM				class c,
											study_period sp,
											periodicity p,
											student s
						LEFT OUTER JOIN ".ATTENDANCE_TABLE." att ON (att.studentId = s.studentId)
						LEFT OUTER JOIN attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId AND ac.instituteId = $instituteId)
						LEFT OUTER JOIN `group` gr ON (gr.groupId = att.groupId)
						LEFT OUTER JOIN subject su ON (su.subjectId = att.subjectId)
						LEFT OUTER JOIN employee emp ON (emp.employeeId = att.employeeId)
						WHERE				 c.classId IN(
																SELECT
																			DISTINCT sg.classId
																FROM		student_groups sg,
																			`group` gr
																WHERE		sg.studentId = $studentId
																AND		sg.groupId = gr.groupId
																AND		s.classId = $classId
																)
						AND				sp.studyPeriodId = c.studyPeriodId
						AND				p.periodicityId = sp.periodicityId
						AND				c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
						AND				c.isActive = 1
						GROUP BY			att.subjectId
              ";
            return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

            } */
/*
public function getAttendanceDashboard($studentId,$classId){
   if ($classId != "" ) {
	$classCond =" and s.classId = $classId ";
   }
    global $sessionHandler;
	$instituteId = $sessionHandler->getSessionVariable('InstituteId');

$query="
			SELECT
						CONCAT( s.firstName , ' ' , s.lastName ) AS studentName ,
						su.subjectCode,
						CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subject ,
						gr.groupName,
						emp.employeeName ,
						sp.periodName ,
						ROUND( SUM( IF( att.isMemberOfClass = 0 , 0 , IF( att.attendanceType = 2 , ( ac.attendanceCodePercentage / 100 ) , att.lectureAttended ) ) ) , 2 ) AS attended ,
						SUM( IF( att.isMemberOfClass = 0 , 0 , att.lectureDelivered ) ) AS delivered ,
						DATE_FORMAT( MIN( att.fromDate ) , '%d-%b-%y' ) as fromDate ,
						DATE_FORMAT( MAX( att.toDate ) , '%d-%b-%y' ) as toDate
			FROM		class c,
						study_period sp,
						periodicity p,
						student s
			INNER JOIN	 ".ATTENDANCE_TABLE." att ON att.studentId = s.studentId
			LEFT JOIN	attendance_code ac ON (ac.attendanceCodeId = att.attendanceCodeId  and ac.instituteId = $instituteId)
			INNER JOIN	`group` gr ON gr.groupId = att.groupId
			INNER JOIN	subject su ON su.subjectId = att.subjectId
			INNER JOIN	employee emp ON emp.employeeId = att.employeeId
			AND			s.studentId = $studentId
			where
						c.classId in (
						  select distinct sg.classId
						  from student_groups sg,
						  `group` gr
						  where sg.studentId=$studentId
						  AND sg.groupId=gr.groupId
						  $classCond
						  )
			AND			sp.studyPeriodId = c.studyPeriodId
			AND			p.periodicityId = sp.periodicityId
			AND			c.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
			AND			c.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			AND			att.classId = c.classId
			AND			c.isActive = 1
						GROUP BY att.subjectId";
	return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  } */

  //-------------------------------------------------------------------------------
//
//getTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function checkStudentTransferredMarks($studentId,$classId) {
		global $REQUEST_DATA;
		global $sessionHandler;

	$query = "	SELECT
							COUNT(*) AS totalRecords
				FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm
				WHERE		ttm.conductingAuthority = 2
				AND			ttm.studentId = $studentId
				AND			ttm.classId = $classId";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

/*
	public function getStudentTransferredMarks($studentId,$classId,$limit,$orderby) {
		//echo ($classId);
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA;
		global $sessionHandler;

 $query = "	SELECT
				CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subjectCode,
				if (ttm.marksScored=-1,'A',if(ttm.marksScored=-2,'UMC',concat(ttm.marksScored,'/',ttm.maxMarks))) AS preCompre,
				if (ttm1.marksScored=-1,'A',if(ttm1.marksScored=-2,'UMC',concat(ttm1.marksScored,'/',ttm1.maxMarks))) AS compre,
				if (ttm2.marksScored=-1,'A',if(ttm2.marksScored=-2,'UMC',concat(ttm2.marksScored,'/',ttm2.maxMarks))) AS  attendance,
				SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName
FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm1,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2,
			student s,
			subject su,
			class cl
WHERE		ttm.conductingAuthority = 1
AND			ttm1.conductingAuthority = 2
AND			ttm2.conductingAuthority = 3
AND			ttm.studentId = s.studentId
AND			ttm1.studentId = s.studentId
AND			ttm2.studentId = s.studentId
AND			ttm.subjectId = su.subjectId
AND			ttm1.subjectId = su.subjectId
AND			ttm2.subjectId = su.subjectId
AND			s.studentId = $studentId
AND			ttm.classId = cl.classId
AND			ttm.holdResult = 0
AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			$classCond
			GROUP BY ttm.subjectId, ttm1.subjectId, ttm2.subjectId
			ORDER BY $orderby
			$limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
*/

	//-------------------------------------------------------------------------------
//
//getTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function getStudentTransferredMarks($studentId,$classId,$limit,$orderby) {
		//echo ($classId);
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA;
		global $sessionHandler;

 $query = "	SELECT
				CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subjectCode,
				if (ttm.marksScoredStatus='Marks',concat(ttm.marksScored,'/',ttm.maxMarks),ttm.marksScoredStatus) AS preCompre,
				if (ttm1.marksScoredStatus='Marks',concat(ttm1.marksScored,'/',ttm1.maxMarks),ttm1.marksScoredStatus) AS compre,
				if (ttm2.marksScoredStatus='Marks',concat(ttm2.marksScored,'/',ttm2.maxMarks),ttm2.marksScoredStatus) AS  attendance,
				SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName
FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm1,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2,
			student s,
			subject su,
			class cl
WHERE		ttm.conductingAuthority = 1
AND			ttm1.conductingAuthority = 2
AND			ttm2.conductingAuthority = 3
AND			ttm.studentId = s.studentId
AND			ttm1.studentId = s.studentId
AND			ttm2.studentId = s.studentId
AND			ttm.subjectId = su.subjectId
AND			ttm1.subjectId = su.subjectId
AND			ttm2.subjectId = su.subjectId
AND			s.studentId = $studentId
AND			ttm.classId = cl.classId
AND			ttm.holdResult = 0
AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			$classCond
			GROUP BY ttm.subjectId, ttm1.subjectId, ttm2.subjectId
			ORDER BY $orderby
			$limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//-------------------------------------------------------------------------------
//
//getTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

/*
	public function getStudentConductingTransferredMarks($studentId,$classId,$limit,$orderby) {
		//echo ($classId);
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA, $sessionHandler;

 $query = "	SELECT
				CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subjectCode,
				if (ttm.marksScored=-1,'A',if(ttm.marksScored=-2,'UMC',concat(ttm.marksScored,'/',ttm.maxMarks))) AS preCompre,
				if (ttm2.marksScored=-1,'A',if(ttm2.marksScored=-2,'UMC',concat(ttm2.marksScored,'/',ttm2.maxMarks))) AS attendance,
				'".NOT_APPLICABLE_STRING."' AS compre,
				SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName
FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2,
			student s,
			subject su,
			class cl
WHERE		ttm.conductingAuthority = 1
AND			ttm2.conductingAuthority = 3
AND			ttm.studentId = s.studentId

AND			ttm2.studentId = s.studentId
AND			ttm.subjectId = su.subjectId
AND			ttm2.subjectId = su.subjectId
AND			s.studentId = $studentId
AND			ttm.classId = cl.classId
AND			ttm.holdResult = 0
AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			$classCond
			GROUP BY ttm.subjectId, ttm2.subjectId
			ORDER BY $orderby
			$limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
*/

public function getStudentConductingTransferredMarks($studentId,$classId,$limit,$orderby) {
		//echo ($classId);
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA, $sessionHandler;

 $query = "	SELECT
				CONCAT( su.subjectName , ' (' , su.subjectCode , ')' ) as subjectCode,
				if (ttm.marksScoredStatus='Marks',concat(ttm.marksScored,'/',ttm.maxMarks),ttm.marksScoredStatus) AS preCompre,
				if (ttm2.marksScoredStatus='Marks',concat(ttm2.marksScored,'/',ttm2.maxMarks),ttm2.marksScoredStatus) AS  attendance,
				'".NOT_APPLICABLE_STRING."' AS compre,
				SUBSTRING_INDEX(cl.className,'".CLASS_SEPRATOR."',-1) AS periodName
FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm2,
			student s,
			subject su,
			class cl
WHERE		ttm.conductingAuthority = 1
AND			ttm2.conductingAuthority = 3
AND			ttm.studentId = s.studentId
AND			ttm2.studentId = s.studentId
AND			ttm.subjectId = su.subjectId
AND			ttm2.subjectId = su.subjectId
AND			s.studentId = $studentId
AND			ttm.classId = cl.classId
AND			ttm.holdResult = 0
AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			$classCond
			GROUP BY ttm.subjectId, ttm2.subjectId
			ORDER BY $orderby
			$limit ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------
//
//getTotalTransferredMarks() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------


	public function getStudentTotalTransferredMarks($studentId,$classId,$orderby) {
		//echo ($classId);
        if ($classId != "" and $classId != "0") {
			$classCond =" AND cl.classId =".add_slashes($classId);
		   }

		global $REQUEST_DATA;
		global $sessionHandler;

 $query = "	SELECT
				COUNT(*) as totalRecords
FROM		".TOTAL_TRANSFERRED_MARKS_TABLE." ttm,
			".TOTAL_TRANSFERRED_MARKS_TABLE." ttm1,
			student s,
			subject su,
			class cl
WHERE		ttm.conductingAuthority = 1
AND			ttm1.conductingAuthority = 2
AND			ttm.studentId = s.studentId
AND			ttm1.studentId = s.studentId
AND			ttm.subjectId = su.subjectId
AND			ttm1.subjectId = su.subjectId
AND			s.studentId = $studentId
AND			ttm.classId = cl.classId
AND			ttm.holdResult = 0
AND			cl.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
AND			cl.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
			$classCond
			GROUP BY ttm.subjectId, ttm1.subjectId
			ORDER BY $orderby
			";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


	public function getStudentFinalResultListAdv($studentId,$classId='',$orderBy='',$limit=''){

        global $REQUEST_DATA;
        global $sessionHandler;


        
        $isHoldCondition = "";
        $roleId=$sessionHandler->getSessionVariable('RoleId');
        if($roleId==3 || $roleId==4){
          $isHoldCondition = " AND cls.holdFinalResult = '0' "; 
        }


        if($classId!='' and $classId!=0){
           $classCondition=" AND cls.classId IN (".add_slashes($classId).')';
        }

        $instituteId=$sessionHandler->getSessionVariable('InstituteId');

       $query="SELECT
                    DISTINCT a.subjectId, a.classId,
                    CONCAT(sub.subjectName,'- (',sub.subjectCode,')') as subjectCode,
                    SUBSTRING_INDEX(cls.className,'-',-1) AS periodName,
                    IFNULL(
                    (SELECT
                          IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                        FROM
                          ".TOTAL_TRANSFERRED_MARKS_TABLE."
                        WHERE
                          studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=1
                      ),'".NOT_APPLICABLE_STRING."'
                    ) AS preCompre,
                    IFNULL(
                     (
                           SELECT
                                  IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                           FROM
                                  ".TOTAL_TRANSFERRED_MARKS_TABLE."
                           WHERE
                                  studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=2
                          ),'".NOT_APPLICABLE_STRING."'
                     ) AS compre,
                     IFNULL(
                     (
                           SELECT
                                  IF(marksScoredStatus='Marks',concat(FORMAT(marksScored,2),'/',FORMAT(maxMarks,2)),marksScoredStatus)
                           FROM
                                  ".TOTAL_TRANSFERRED_MARKS_TABLE."
                           WHERE
                                  studentId = a.studentId and subjectId = a.subjectId
                          AND classId = a.classId
                          AND conductingAuthority=3
                          ),'".NOT_APPLICABLE_STRING."'
                     ) AS attendance
                    FROM
                    ".TOTAL_TRANSFERRED_MARKS_TABLE."  a,
                    `subject` sub,class cls
                  WHERE
                  a.studentId = $studentId
                  $classCondition
                  AND a.subjectId=sub.subjectId
                  AND a.classId=cls.classId
                  AND a.holdResult=0
                  AND cls.instituteId=$instituteId
		  $isHoldCondition
                 GROUP BY a.subjectId, a.classId
                ORDER BY $orderBy
                $limit";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Feedback Data
//
// Author :Jaineesh
// Created on : 10-11-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getFeedBackData($condition) {
		global $sessionHandler;

 $query = "
				SELECT
								fc.feedbackCategoryName,
								fq.feedbackQuestion,
								fc.feedbackCategoryId,
								fq.feedbackQuestionId,
								fs.feedbackSurveyId

				FROM			feedback_category fc,
								feedback_questions fq,
								feedback_survey fs,
								student ssc
				WHERE			fq.feedbackCategoryId = fc.feedbackCategoryId
				AND				fq.feedbackSurveyId = fs.feedbackSurveyId
				AND				fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND				fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				AND				fc.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				AND				ssc.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
								$condition ORDER BY fc.feedbackCategoryId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//--------------------------------------------------------------------------------------------------------------------------------------------
//  THIS FUNCTION IS USED TO GET Feedback Data
//
// Author :Jaineesh
// Created on : 10-11-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//---------------------------------------------------------------------------------------------------------------------------------------------
  public function getCheckFeedBackData($condition) {
		global $sessionHandler;

	$query = "
				SELECT
								COUNT(*) AS found

				FROM			feedback_category fc,
								feedback_questions fq,
								feedback_survey fs,
								student ssc
				WHERE			fq.feedbackCategoryId = fc.feedbackCategoryId
				AND				fq.feedbackSurveyId = fs.feedbackSurveyId
				AND				fs.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
				AND				fs.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				AND				fc.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
				AND				ssc.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
								$condition ORDER BY fc.feedbackCategoryId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
  }

//-------------------------------------------------------------------------------
//
//insertFeedBackTeacher() function used to insert into teacher_feedback table
// Author : Jaineesh
// Created on : 18.11.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function insertFeedBackTeacher($feedbackInsert) {


$query = "INSERT INTO `feedback_teacher` (employeeId,feedbackQuestionId,feedbackGradeId,dated,studentId,classId) VALUES".$feedbackInsert;
			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;

}

//-------------------------------------------------------------------------------
//
//insertGeneralSurvey() function used to insert into teacher_feedback table
// Author : Jaineesh
// Created on : 30.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function insertGeneralSurvey($feedbackInsert) {


$query = "INSERT INTO `feedback_survey_answer` (feedbackQuestionId,feedbackGradeId,dated,userId,attempts) VALUES".$feedbackInsert;
			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;

}

//-------------------------------------------------------------------------------
//
//updateGeneralSurvey() function used to insert into teacher_feedback table
// Author : Jaineesh
// Created on : 30.12.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function updateGeneralSurvey($questionId, $gradeId,$dated,$userId) {

 $query = "
				UPDATE
								`feedback_survey_answer`
				SET				feedbackGradeId = $gradeId ,
								dated = '$dated',
								attempts=  attempts+1
				where			feedbackQuestionId=$questionId
				and				userId = $userId";
			SystemDatabaseManager::getInstance()->executeUpdate($query);
			return true;

}

//-------------------------------------------------------------------------------
//
//getSurveyAttempt() function used to get total no. of attempts
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function getSurveyAttempts($condition) {


$query = "	SELECT noAttempts, feedbackSurveyId
			FROM feedback_survey $condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//getStudentQuestionGradeId() function used to get answerId
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getStudentQuestionGradeId($questionId) {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
		$query = "
					SELECT
								feedbackGradeId
					FROM		feedback_survey_answer
					WHERE		feedbackQuestionId = $questionId
					AND			userId = $userId";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

//-------------------------------------------------------------------------------
//
//getAttempt() function used to get no. of attempts
// Author : Jaineesh
// Created on : 05.01.09
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
public function getAttempts($condition) {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
$query = "	SELECT	attempts
			FROM	feedback_survey_answer fsa,
					feedback_questions fq,
					feedback_survey fs
			WHERE	fq.feedbackSurveyId = fs.feedbackSurveyId
			AND		fsa.feedbackQuestionId = fq.feedbackQuestionId
			AND		fsa.userId = $userId
					$condition";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

}

//-------------------------------------------------------------------------------
//
//getTeacherName() function returns total no. of records from user
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 14.06.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------

    public function getTeacherName($conditions='') {
        $systemDatabaseManager = SystemDatabaseManager::getInstance();
		global $sessionHandler;
    $query = "	SELECT
							COUNT(*) as found
					FROM	feedback_teacher sft,
							feedback_questions fq,
							feedback_survey fs
					WHERE	sft.feedbackQuestionId = fq.feedbackQuestionId
					AND		fq.feedbackSurveyId = fs.feedbackSurveyId
					AND		fs.isActive = 1
					AND		fs.surveyType = 2
					AND		sft.studentId = ".$sessionHandler->getSessionVariable('StudentId')."
							$conditions ";
        return $systemDatabaseManager->executeQuery($query,"Query: $query");
    }


////////////// NEW SQL QUERIES FOR TASK MODULE /////////////////

	//-------------------------------------------------------
// THIS FUNCTION IS USED FOR ADDING A DOCUMENT
//
// Author :Jaineesh
// Created on : (14.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function addTask($reminderOptions) {
        global $REQUEST_DATA;
		global $sessionHandler;

		$userId = $sessionHandler->getSessionVariable('UserId');

     $query="INSERT INTO task (title,shortDesc,dueDate,reminderOptions,daysPrior,status,userId)
      VALUES('".add_slashes($REQUEST_DATA['title'])."','".add_slashes($REQUEST_DATA['shortDesc'])."','".$REQUEST_DATA['dueDate']."','".$reminderOptions."','".$REQUEST_DATA['daysPrior']."','".$REQUEST_DATA['status']."','".$userId."')";

      return SystemDatabaseManager::getInstance()->executeUpdate($query);

    }


//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A DOCUMENT
//
//$id:documentId
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editTask($id,$reminderOptions) {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

   $query="UPDATE task SET	title ='".add_slashes($REQUEST_DATA['title'])."',
							shortDesc ='".add_slashes($REQUEST_DATA['shortDesc'])."',
							dueDate = '".$REQUEST_DATA['dueDate']."',
							daysPrior = '".$REQUEST_DATA['daysPrior']."',
							reminderOptions = '".$reminderOptions."',
							status = '".$REQUEST_DATA['status']."'
							WHERE taskId=".$id."
							AND userId=".$userId;

       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR EDITING A STATUS
//
//$id:documentId
// Author :Jaineesh
// Created on : (28.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function editTaskStatus($id,$status) {
        global $REQUEST_DATA;
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

		if($status == 0) {

			$query="UPDATE task SET	status = 1
									WHERE taskId = ".$id."
									AND userId = ".$userId;
		}
		else {
			$query="UPDATE task SET	status = 0
									WHERE taskId = ".$id."
									AND userId = ".$userId;
		}

       return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }

//-------------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR DELETING A Document
//
//$publishId :publishId   of document
// Author :Jaineesh
// Created on : (05.03.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------------------------------------------------------
    public function deleteTask($Id) {
		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        $query = "	DELETE
					FROM task
					WHERE userId = $userId
					AND taskId=$Id";
        return SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TASK
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (18.3.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    public function getTask($conditions='') {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

 $query = "	SELECT	*,
						( date_sub( dueDate, INTERVAL daysPrior DAY ) ) AS Result
					FROM	task
					WHERE	userId = $userId
					$conditions
					ORDER BY dueDate desc";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TASK LIST
//
//$conditions :db clauses
//$limit:specifies limit
//orderBy:sort on which column
// Author :Jaineesh
// Created on : (19.02.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

     public function getTaskList($filter, $orderBy='',$limit = '') {

		global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');

    $query = "	SELECT
							*,
							( date_sub( dueDate, INTERVAL daysPrior DAY ) ) AS Result
					FROM	task
					WHERE	userId = $userId
							$filter
							ORDER BY $orderBy
							$limit";
        //echo $query;

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

	//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF DOCUMENT
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTotalTask($filter) {
         global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

   $query = "	SELECT	COUNT(*) AS totalRecords
				FROM	task
				WHERE	userId = $userId
							$filter
							$conditions  ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//---------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR GETTING TOTAL NUMBER OF TASKS
//
//$conditions :db clauses
// Author :Jaineesh
// Created on : (28.02.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------
    public function getTaskMessages($limit = 'LIMIT 0,5') {
         global $sessionHandler;
		$userId = $sessionHandler->getSessionVariable('UserId');
        //no joining is donw with study period table  as we dont need to display studyPeriod in the table listing

 $query = "	SELECT	*,
					( date_sub( dueDate, INTERVAL daysPrior DAY ) ) AS Result
			FROM	task
			WHERE	userId = $userId
			AND		( date_sub( dueDate, INTERVAL daysPrior DAY ) ) <= curdate()
			ORDER BY Result asc
			$limit
				";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }


//-------------------------------------------------------------------------------
//
//getPrevClass() function returns previous class
// $condition :  used to check the condition of the table
// Author : Jaineesh
// Created on : 13.04.08
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//-------------------------------------------------------------------------------
	public function getPrevClass($degreeId) {
	$query = "
					SELECT
							  b.classId,
							  b.isActive, b.marksTransferred, b.className
					FROM      class a, class b, study_period c, study_period d
					WHERE     CONCAT(a.instituteId,'#',a.universityId, '#', a.batchId, '#', a.degreeId, '#', a.branchId) =
							  CONCAT(b.instituteId,'#',b.universityId, '#', b.batchId, '#', b.degreeId, '#', b.branchId)
					AND       a.studyPeriodId = c.studyPeriodId
					AND       b.studyPeriodId = d.studyPeriodId
					AND       c.periodicityId = d.periodicityId
					AND       d.periodValue = c.periodValue - 1
					AND       a.classId = $degreeId";

		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");

	}

//-------------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR getting Academic in student tabs
//
//$conditions :db clauses
// Author :Rajeev Aggarwal
// Created on : (28.05.2009)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//-----------------------------------------------------------------------------------------------
    public function getStudentAcademicList($condition,$orderBy=''){

		global $REQUEST_DATA;
		global $sessionHandler;

		$query="	SELECT
							sa.previousClassId,
							sa.previousRollNo,
							sa.previousSession,
							sa.previousInstitute,
							sa.previousBoard,
							sa.previousMarks,
							sa.previousMaxMarks,
							sa.previousPercentage,
							sa.previousEducationStream
					FROM	`student_academic` sa
							$condition
							ORDER BY $orderBy ";
		return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//******These functions are needed for adv. feedback modules************
    public function checkFeedbackStudentStatus($userId){
        
        global $sessionHandler;     
        
        
        $query="
                 SELECT
                       status
                 FROM
                       feedbackadv_student_status
                 WHERE
                       userId=$userId
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
    
    //******These functions are needed for checking the block status of students who have not given  feedback************
    public function checkBlockFeedbackStudentStatus($studentId){
        
        global $sessionHandler;     
        
        
        $query="
                 SELECT
                       status
                 FROM
                       feedback_student_status
                 WHERE
                       studentId=$studentId AND isStatus!=1
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    
    //This function will check the feedback status of the student  
    public function checkFeedbackStudentStatusNew($userId=''){
        
        global $sessionHandler;  
         
        $query="
                 SELECT
                       DISTINCT isStatus
                 FROM
                       feedback_student_status
                 WHERE
                       studentId = '$userId'
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
       //This function will check the student record for feedback  
    public function checkFeedbackRecordNew(){
        
       global $sessionHandler;  
        
       //$query = "DELETE FROM feedback_student_status WHERE totalAnswer IS NULL AND finalId IS NULL";
       //$returnArray = SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
        
       $instituteId=$sessionHandler->getSessionVariable('InstituteId');
       $sessionId=$sessionHandler->getSessionVariable('SessionId');
       $userId=$sessionHandler->getSessionVariable('UserId');
       $studentId=$sessionHandler->getSessionVariable('StudentId');
       
       if($studentId =='') {
         $studentId='0'; 
       }
       
       $query=" SELECT
                    DISTINCT class.className, class.classId, student.studentId, student.rollNo, student.firstName, student.lastName, 
                    feedbackadv_survey.feedbackSurveyLabel, feedbackadv_survey.visibleFrom, feedbackadv_survey.visibleTo, 
                    feedbackadv_survey.extendTo, feedback_student_status.isStatus, feedback_student_status.status, 
                    feedbackadv_survey.feedbackSurveyId, feedbackadv_survey.timeTableLabelId, 
                    IF(IFNULL(feedback_student_status.isStatus,'')='1','Completed',
                      IF(IFNULL(feedback_student_status.isStatus,'')='0','Partial','Pending')) AS isCompleted, 
                    IF(feedbackadv_survey.extendTo > CURRENT_DATE( ) , 'No', 'Yes' ) AS dateOver, 
                    IF(feedbackadv_survey.extendTo > CURRENT_DATE( ) , 0, 1 ) AS dateOverFlag
               FROM
                    student
                    LEFT JOIN feedbackadv_survey_visible_to_users 
                        ON (student.userId = feedbackadv_survey_visible_to_users.userId)
                    LEFT JOIN feedbackadv_survey_mapping 
                        ON (feedbackadv_survey_visible_to_users.feedbackMappingId = feedbackadv_survey_mapping.feedbackMappingId)
                    LEFT JOIN feedbackadv_survey 
                        ON (feedbackadv_survey_mapping.feedbackSurveyId = feedbackadv_survey.feedbackSurveyId)
                    LEFT JOIN feedback_student_status 
                        ON (feedback_student_status.studentId = student.studentId) AND 
                           (feedback_student_status.surveyId = feedbackadv_survey.feedbackSurveyId) 
                    LEFT JOIN time_table_classes 
                        ON (feedbackadv_survey.timeTableLabelId = time_table_classes.timeTableLabelId) 
                    LEFT JOIN class 
                        ON (class.classId = time_table_classes.classId) 
               WHERE
                    (class.batchId,class.degreeId,class.branchId) IN (SELECT batchId,degreeId,branchId FROM class WHERE classId=student.classId) AND    
			         student.studentId IN ($studentId) AND
                     class.instituteId = '$instituteId' AND
                     class.sessionId = '$sessionId' 
                     AND feedbackadv_survey.isNew=1 ";
                     
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }





       //This function will check the whether feedback id is there in table or not 
    public function checkFeedbackRecordIdNew($surveyId){
        
        global $sessionHandler;  
        $studentId=$sessionHandler->getSessionVariable('StudentId');
        
        //$query = "DELETE FROM feedback_student_status WHERE totalAnswer IS NULL AND finalId IS NULL";
        //$returnArray = SystemDatabaseManager::getInstance()->executeDelete($query,"Query: $query");
        
        $query="SELECT 
        		    COUNT(*) AS status
        	    FROM 
        	            feedback_student_status
        	    WHERE
        		    surveyId='$surveyId' AND studentId='$studentId'";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

    //This function will insert the pending feedback in table
    public function insertPendingFeedbackRecord($str){
        
        if($str=='') {
          return false;  
        }
        $query="INSERT INTO feedback_student_status
      	       (`surveyId` , `classId` , `studentId` , `isStatus` , `status` )
	           VALUES 
               $str " ;
               
	    return SystemDatabaseManager::getInstance()->executeUpdateInTransaction($query,"Query: $query");
    }
    
    //This function will get the class Id for Pending feedback
      public function getClassIdPending($pendingRecordId){
        global $sessionHandler;  
        $studentId=$sessionHandler->getSessionVariable('StudentId');
        $query="SELECT 
        		feedbackSurveyId,classId
		FROM
			 `feedbackadv_survey_mapping`
	    	WHERE	
	    		  feedbackSurveyId IN ($pendingRecordId)";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    

public function getMaxDateOfFeedbackLabels($labelIds){
        $query="
                 SELECT
                       MAX(visibleTo) AS maxDate
                 FROM
                       feedbackadv_survey
                 WHERE
                       feedbackSurveyId IN ( $labelIds )
                 ";
 return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
     }




//-------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching expected date of checkout for a student
// $conditions :db clauses
// Author : Dipanjan Bhattacharjee
// Created on : (30.04.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------
    public function getExpectedDateOfCheckOut($studentId){
        $query="
                SELECT
                      IFNULL(datediff(possibleDateOfCheckOut,current_date()),0) AS daysLeft
                FROM
                      hostel_students
                WHERE
                      dateOfCheckOut='0000-00-00'
                      AND studentId='$studentId';
                 ";
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR fetching file name from "course_resources" table
// $conditions :db clauses
// Author : Dipanjan Bhattacharjee
// Created on : (06.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------
    public function getCourceResourceFileName($fileId){
        $query="
                 SELECT
                       attachmentFile,employeeId
                 FROM
                       course_resources
                 WHERE
                       courseResourceId='$fileId'";
                       
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

//-------------------------------------------------------------------------------------------
// THIS FUNCTION IS USED FOR inserting into "course_resource_download" table
// $conditions :db clauses
// Author : Dipanjan Bhattacharjee
// Created on : (06.05.2010)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//-------------------------------------------------------------------------------------------
    public function insertCourceResourceCounter($fileId,$studentId,$dated,$employeeId){
        $query="
                 INSERT INTO
                       course_resource_download (studentId,courseResourceId,downloadDateTime,employeeId)
                 VALUES ($studentId,$fileId,'".$dated."',$employeeId)
                 ";
        return SystemDatabaseManager::getInstance()->executeUpdate($query);
    }
    
    public function getStudentFinalSubject($studentId,$classId,$orderBy='subjectCode') {

        global $sessionHandler;        
        
        if ($classId != "" and $classId != "0") {
            $classCond =" AND sg.classId =".add_slashes($classId);
        }

        $query = "SELECT
                          DISTINCT   
                               IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  							   IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
                                cls.className,
                                cls.classId,
                                sub.subjectId,
                                gr.groupId
                  FROM        `student_groups` sg,
                                `group` gr,
                                `class` cls ,
                                `subject_to_class` stc,
                                `subject` sub,
                                `group_type` gt,
                                student s
                  WHERE        cls.classId = sg.classId
                    AND            stc.subjectId = sub.subjectId
                    AND            stc.classId = cls.classId
                    AND            sg.groupId = gr.groupId
                    AND            gr.groupTypeId = gt.groupTypeId
                    AND            sg.studentId = s.studentId
                    AND            sg.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                    AND            sg.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                    AND            s.studentId=$studentId
                                $classCond
                    UNION
                    SELECT
                           DISTINCT 
                           	   IF(stc.isAlternateSubject='0',sub.subjectCode,sub.alternateSubjectCode) AS subjectCode,
  							   IF(stc.isAlternateSubject='0',sub.subjectName,sub.alternateSubjectName) AS subjectName,
                                cls.className,
                                cls.classId,
                                sub.subjectId,
                                 gr.groupId    
                    FROM        `group` gr,
                                `class` cls ,
                                `subject_to_class` stc,
                                `subject` sub,
                                `group_type` gt,
                                student_optional_subject sos,
                                student s
                    WHERE        s.studentId=$studentId
                    AND            sos.classId = cls.classId
                    AND            sos.groupId = gr.groupId
                    AND            sos.studentId = s.studentId
                    AND            gr.groupTypeId=gt.groupTypeId
                    AND         sos.subjectId = sub.subjectId
                    AND            cls.sessionId = ".$sessionHandler->getSessionVariable('SessionId')."
                    AND            cls.instituteId = ".$sessionHandler->getSessionVariable('InstituteId')."
                                ORDER BY  $orderBy ";

        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getHoldStudentsData($studentId='') {
    
        $query = "SELECT 
			           shr.studentId, shr.classId, 
                       shr.holdAttendance, shr.holdTestMarks, shr.holdFinalResult, shr.holdGrades                        
                   FROM 
                       student_hold_result shr 
                   WHERE 
                     	shr.studentId = '$studentId' ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentMigrationCheck($studentId='') {
    
        $query = "SELECT 
			 st.studentId,st.classId,st.isMigration,
                         st.migrationClassId,st.isLeet                        
                   FROM 
                       student st 
                   WHERE 
                     	st.studentId = '$studentId' ";
		
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }

public function getStudentFullDetails($studentId='') {
    
        $query = "SELECT 
				 		  st.studentId,st.classId,c.className,st.rollNo,CONCAT(IFNULL(st.firstName,''),' ',IFNULL(st.lastName,'')) AS studentName
                                    
                   FROM 
                      	class c, student st 
                        
                   WHERE                   	
						 st.classId = c.classId			  	
                 		   AND st.studentId = '$studentId' ";
	            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
public function getStudentHostelDetailsCheck($studentId='',$classId='') {
    
        $query = "SELECT 
                       h.hostelCode, h.hostelName, c.className,hs.classId AS hostelClassId,               
                        CONCAT(h.hostelName,'-',r.roomName,' (',hrt.roomType,')') AS hostelDetails,r.roomName as roomName,
                        hs.dateOfCheckIn,hs.dateOfCheckOut,hs.studentId,
                         If(hs.dateOfCheckIn='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckIn,'%d-%b-%y')) AS checkInDate,
                        If(hs.dateOfCheckOut='0000-00-00','".NOT_APPLICABLE_STRING."',DATE_FORMAT(hs.dateOfCheckOut,'%d-%b-%y')) AS checkOutDate        
               		FROM 
                          class c, hostel_room_type hrt,hostel h,hostel_room r,
                           hostel_students hs 
                        
                   WHERE                       
                          hrt.hostelRoomTypeId = r.hostelRoomTypeId  
                          AND hs.hostelRoomId=r.hostelRoomId
                           AND h.hostelId=r.hostelId                  
                            AND hs.studentId = '$studentId'
                            AND hs.classId = '$classId' 
                      GROUP BY
                      hs.studentId,hs.classId ";
            
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }    
    
    public function getStudentHostelRegistration($studentId='',$classId='') {
    
        $query = "INSERT INTO 
        		`hostel_registration`(studentId,classId,dateOfEntry,isConfrim) 
        				values('$studentId','$classId',now(),'0')  ";
	
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
    
    public function getPreviousHostelRegistration($studentId='',$classId='') {
    
        $query = "SELECT 
        				studentId,classId,dateOfEntry,isConfrim
        			 FROM 
        				`hostel_registration` 
        		WHERE 
        			studentId ='$studentId'  AND isConfrim = '0' ";
        			
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    }
    
     public function deletePreviousHostelRegistration($studentId='',$classId='') {
    
        $query = "UPDATE  
        				`hostel_registration`
        				SET isConfrim ='1'				        			 
        		WHERE 
        			studentId ='$studentId' AND classId = '$classId' ";
					
        return SystemDatabaseManager::getInstance()->executeUpdate($query,"Query: $query");
    }
   
   
    public function getStudentOnlineDetails($studentId='') {
    
        $query = "SELECT 
				 		  st.studentId,st.classId,c.className,st.fatherName,	   
                          st.rollNo,CONCAT(IFNULL(st.firstName,''),' ',IFNULL(st.lastName,'')) AS studentName
                                 
                   FROM 
                      	class c, student st 
                        
                   WHERE                   	
						 st.classId = c.classId			  	
                 		   AND st.studentId = '$studentId'  ";
        			
        return SystemDatabaseManager::getInstance()->executeQuery($query,"Query: $query");
    } 

}


//

?>
