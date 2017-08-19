<?php
//This file is used as printing version for testwise marks report.
//
// Author :Rajeev Aggarwal
// Created on : 14-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    ini_set('MEMORY_LIMIT','5000M'); 
    set_time_limit(0);  
    global $FE;
    require_once($FE . "/Library/common.inc.php");
    require_once(BL_PATH . "/UtilityManager.inc.php");
    define('MODULE','COMMON');
    define('ACCESS','view');
    define("MANAGEMENT_ACCESS",1);
    UtilityManager::ifNotLoggedIn(true);
    UtilityManager::headerNoCache();

    require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
    $studentReportsManager = StudentReportsManager::getInstance();

    require_once(MODEL_PATH . "/StudentManager.inc.php");
    $studentManager = StudentManager::getInstance();
   
	$range     = $REQUEST_DATA['range'];
	$range = str_replace('-',',',$range);
	$rangeArray = explode(',',$range);
	$rangeCount =count($rangeArray);
	for($i=0;$i<$rangeCount; $i++){
		for($j=$i+1;$j<$rangeCount;$j++){
			if(floatVal($rangeArray[$i]) > floatVal($rangeArray[$j])){
				echo "RANGE_SHOULD_BE_IN_ASSENDING_ORDER";
				die;
			}
		}
	}
    $timeTable       = $REQUEST_DATA['timeTable'];
    $degree           = $REQUEST_DATA['degree'];
    $subjectTypeId = $REQUEST_DATA['subjectTypeId'];
    $subjectId     = $REQUEST_DATA['subjectId'];
    $groupId       = $REQUEST_DATA['groupId'];
    $reportFor       = $REQUEST_DATA['reportFor'];
    $marksFor      = $REQUEST_DATA['marksFor'];
    $average       = $REQUEST_DATA['average'];
    $percentage    = $REQUEST_DATA['percentage'];
    
    $includeGrace='';  
    if($marksFor=='3') {
      $includeGrace='1';  
      $marksFor=0;
    }

    $degreeName    = $REQUEST_DATA['degreeName'];
    $typeName      = $REQUEST_DATA['typeName'];

    $subjectName   = $REQUEST_DATA['subjectName'];
    $groupName     = $REQUEST_DATA['groupName'];

    $sortField     = $REQUEST_DATA['sortField'];
	if($sortField == "firstName") {
		$sortField = "studentName";
	}
    $sortOrderBy     = $REQUEST_DATA['sortOrderBy'];
    $showGraceMarks    = $REQUEST_DATA['showGraceMarks'];
    $showGrades    = $REQUEST_DATA['showGrades'];

    if($groupName=='Select'){

        $groupName ="ALL";
    }
    $markName    = $REQUEST_DATA['markName'];

    $attCondition = " AND c.classId=$degree";
    $conditions       = " AND c.classId=$degree";
    if($subjectTypeId!=''){
        $attCondition  .= " AND su.subjectTypeId = $subjectTypeId";
        $conditions       .= " AND sub.subjectTypeId = $subjectTypeId";
    }
    if($subjectId!=''){
        $attCondition  .= " AND su.subjectId = $subjectId";
        $conditions       .= " AND sub.subjectId = $subjectId";
    }
    if($groupId!=''){
        $attCondition  .= " AND grp.groupId = $groupId";
        $conditions       .= " AND sg.groupId = $groupId";
    }

    if($marksFor==1){

        $conditions1    = " AND ttm.conductingAuthority IN (1,3) ";
    }
    if($marksFor==2){

        $conditions1    = " AND ttm.conductingAuthority IN (2) ";
    }
    if($average==1){

        $conditions2    = "  having ((marksScored/maxMarks)*100)>=$percentage ";
    }
    if($average==2){

        $conditions2    = "  having ((marksScored/maxMarks)*100)<$percentage ";
    }
    if($average==3){
        //$conditions3    = "  having attended>=$percentage ";
        $conditions3    = "  WHERE (IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100)) >= $percentage ";
    }
    if($average==4){
        //$conditions3    = "  having attended<$percentage ";
        $conditions3    = "  WHERE (IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100)) < $percentage ";
    }


        //to parse csv values
  function parseCSVComments($comments) {
     $comments = str_replace('"', '""', $comments);
     if(eregi(",", $comments) or eregi("\n", $comments)) {
       return '"'.$comments.'"'; 
     } 
     else {
       return $comments.chr(160);  
     }
   }

    $timeTableNameArray =  $studentManager->getSingleField('time_table_labels','labelName',"WHERE timeTableLabelId = $timeTable");
    $labelName = $timeTableNameArray[0]['labelName'];

    $csvData = "For Time Table,".parseCSVComments($labelName)."\n";
    $csvData  = "Class,".parseCSVComments($degreeName);
    if($groupName!='ALL') {
      $csvData .= ',Group,'.parseCSVComments($groupName);
    }
    $csvData .= "\n";
    $csvData .= "#,Univ. Roll No.,Roll No.,Student Name";
    $orderBySubject =  $sortField." ".$sortOrderBy;


    /** START Code to fetch marks for students **/
    if($reportFor==2){
        if($marksFor=='0'){
            $conditions1	= " AND ttm.conductingAuthority IN (1,3) ";
            $fetchInternalMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,'',$sortField,$sortOrderBy,$includeGrace);
            $conditions1	= " AND ttm.conductingAuthority IN (2) ";
            $fetchExternalMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,'',$sortField,$sortOrderBy,$includeGrace);
            $conditions1=''; 
        }
        $fetchMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy,$includeGrace);
        $cnt2 = count($fetchMarksDetailArray);

        $studentIdList2 = UtilityManager::makeCSList($fetchMarksDetailArray, 'studentId');
        if (empty($studentIdList2)) {
            $studentIdList2 = 0;
        }

        $studentIdM = "";
        for($i=0;$i<$cnt2;$i++){

            $studentDetailArr[]=$fetchMarksDetailArray[$i]['studentId'].'~'.$fetchMarksDetailArray[$i]['rollNo'].'~'.$fetchMarksDetailArray[$i]['universityRollNo'].'~'.$fetchMarksDetailArray[$i]['studentName'];
            //$subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode'];
            if($marksFor=='1') {  
              $internalMarksArr[]= $fetchMarksDetailArray[$i]['internalTotalMarks'];
       
              $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode']." (".$fetchMarksDetailArray[$i]['internalTotalMarks'].")";
          $intTotMarks=$fetchMarksDetailArray[$i]['internalTotalMarks'];
          $intms=$fetchMarksDetailArray[$i]['marksScored'];
          $intmm=$fetchMarksDetailArray[$i]['maxMarks'];
          //$finalScoredMarks= ($intms/$intmm)* $intTotMarks;
	    $finalScoredMarks= ($intms/$intTotMarks)* $intTotMarks;
          $maximumMarks=$fetchMarksDetailArray[$i]['internalTotalMarks'];
            }
            else if($marksFor=='2') {  
              $internalMarksArr[]= $fetchMarksDetailArray[$i]['externalTotalMarks'];
              $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode']." (".$fetchMarksDetailArray[$i]['externalTotalMarks'].")";
              $extTotMarks=$fetchMarksDetailArray[$i]['externalTotalMarks'];
              $extms=$fetchMarksDetailArray[$i]['marksScored'];
              $exmm=$fetchMarksDetailArray[$i]['maxMarks'];
              //$finalScoredMarks= ($extms/$exmm)* $extTotMarks;
		$finalScoredMarks= ($extms/$extTotMarks)* $extTotMarks;
              $maximumMarks=$fetchMarksDetailArray[$i]['externalTotalMarks'];
            }
            else if($marksFor=='0') {  
               $finalScoredMarksInternal =0; 
               $intmsBoth=0;
               $intmmBoth=0;
               $intTotMarksForBoth=0;
               
               $finalScoredMarksExternal=0;      
               $extTotMarksForBoth=0;
               $extmsBoth=0;
               $extmmBoth=0;
               
               for($jj=0;$jj<count($fetchInternalMarksDetailArray);$jj++) {
                 $ggStudentId = $fetchInternalMarksDetailArray[$jj]['studentId'];
                 $ggSubjectId = $fetchInternalMarksDetailArray[$jj]['subjectId'];
                 if($fetchMarksDetailArray[$i]['studentId']==$ggStudentId && $fetchMarksDetailArray[$i]['subjectId'] == $ggSubjectId) {
                    $intTotMarksForBoth=$fetchInternalMarksDetailArray[$jj]['internalTotalMarks'];
                    $intmsBoth=$fetchInternalMarksDetailArray[$jj]['marksScored'];
                    $intmmBoth=$fetchInternalMarksDetailArray[$jj]['maxMarks'];
                    //$finalScoredMarksInternal= ($intmsBoth/$intmmBoth)* $intTotMarksForBoth;   
		      $finalScoredMarksInternal= ($intmsBoth/$intTotMarksForBoth)* $intTotMarksForBoth; 
                 }
               } 
               for($jj=0;$jj<count($fetchExternalMarksDetailArray);$jj++) {
                 $ggStudentId = $fetchExternalMarksDetailArray[$jj]['studentId'];
                 $ggSubjectId = $fetchExternalMarksDetailArray[$jj]['subjectId'];
                 if($fetchMarksDetailArray[$i]['studentId']==$ggStudentId && $fetchMarksDetailArray[$i]['subjectId'] == $ggSubjectId) {
                    $extTotMarksForBoth=$fetchExternalMarksDetailArray[$jj]['externalTotalMarks'];
                    $extmsBoth=$fetchExternalMarksDetailArray[$jj]['marksScored'];
                    $extmmBoth=$fetchExternalMarksDetailArray[$jj]['maxMarks'];
                    //$finalScoredMarksExternal= ($extmsBoth/$extmmBoth)* $extTotMarksForBoth;
		      $finalScoredMarksExternal= ($extmsBoth/$extTotMarksForBoth)* $extTotMarksForBoth;
                 }
               }
               $finalScoredMarks =$finalScoredMarksInternal+$finalScoredMarksExternal;
               $maximumMarks=$fetchInternalMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks'];
               $internalMarksArr[]= $fetchMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks'];
               $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode']." (".($fetchMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks']).")";
            }
            
            
            $subjectIdArr[]=$fetchMarksDetailArray[$i]['subjectId'];
            $studentId2 = $fetchMarksDetailArray[$i]['studentId'];
            $abcArray = $studentReportsManager->getGraceMarks($fetchMarksDetailArray[$i]['studentId'], $degree, $fetchMarksDetailArray[$i]['subjectId']);
            
            $internalGraceMarks = $abcArray[0]['internalGraceMarks'];
            $externalGraceMarks = $abcArray[0]['externalGraceMarks'];
            $totalGraceMarks = $abcArray[0]['totalGraceMarks'];
            
            if($marksFor=='1') {  
              $graceMarks1 = $internalGraceMarks+$totalGraceMarks;
            }
            else if($marksFor=='2') {  
              $graceMarks1 = $externalGraceMarks;
            }
            else if($marksFor=='0') {  
              $graceMarks1 = $abcArray[0]['graceMarks'];
            }
            
            if($showGraceMarks==1){
              if($marksFor=='1') {  
                $graceMarks = $internalGraceMarks+$totalGraceMarks;
              }
              else if($marksFor=='2') {  
                $graceMarks = $externalGraceMarks;
              }
              else if($marksFor=='0') {  
                $graceMarks = $abcArray[0]['graceMarks'];
              }
            }
            else{
              $graceMarks = 0;
            }

            if(($studentId2==$studentIdM)){

                $array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($finalScoredMarks).'/'.$maximumMarks."~".$graceMarks."~".$fetchMarksDetailArray[$i]['grade'];
            }
            else{

                $array2=array();
				$array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($finalScoredMarks).'/'.$maximumMarks."~".$graceMarks."~".$fetchMarksDetailArray[$i]['grade'];
            }
            $studentArr2[$fetchMarksDetailArray[$i]['studentId']] = $array2;
            if($studentId2 != ""){

                $studentIdM = $studentId2;
            }
            $studentIdList = UtilityManager::makeCSList($fetchMarksDetailArray, 'studentId');


        }
        //echo "<pre>";
        //print_r($studentArr2);
        //    die();
        //echo $studentIdArr = substr($studentIdArr,0,(count($studentIdArr)-1));

        if(count($subjectArr)) {
            $subjectArr1 = array_values(array_unique($subjectArr));
            //asort($subjectArr1);
        }
        if(count($subjectIdArr))
            $subjectIdArr1 = array_values(array_unique($subjectIdArr));

        if(count($studentDetailArr))
            $studentDetailArr1 = array_values(array_unique($studentDetailArr));
        $cnt1 = count($studentDetailArr1);

        $m=0;
        if($cnt1){

                if(count($subjectArr)){
                    $subId=0;
                    foreach($subjectArr1 as $keySubject=>$keyValue){
                        if($showGraceMarks==1){

                            if($showGrades==1){
                                $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                            }
                            else{
                                $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                            }
                        }
                        else{
                            if($showGrades==1){
                                $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                            }
                            else{
                                $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                            }
                        }
                        if($showGraceMarks==1){
                           $csvData .= ",";
                        }
                        if($showGrades==1){
                           $csvData .= ",,";
                        }
                        //$totalSubjectMarks += $internalMarksArr[$subId];
                        $subId++;
                    }


                    if(trim($REQUEST_DATA['subjectId'])==''){
                        $subjectIdCondition=" WHERE classId = $degree";
                       $subjectIdArray=$studentManager->getSubjectIds(TOTAL_TRANSFERRED_MARKS_TABLE,$subjectIdCondition);
                       $SubjectIdCount=count($subjectIdArray);
                       $subjectId='';
                       for($i=0;$i<$SubjectIdCount;$i++){
                         $subjectId.=",".$subjectIdArray[$i]['subjectId'];
                         }
                       $trimmedIds = ltrim($subjectId, " , ");

                 }  $mksSubjectCondition=''; 
                    $totalSubjectMarks = ''; 
                                                                                                   
                    if(trim($REQUEST_DATA['subjectId'])!='') {
                      $mksSubjectCondition = " AND subjectId = '".trim($REQUEST_DATA['subjectId'])."'";
                    }
                    else{
                      $mksSubjectCondition = " AND subjectId IN ($trimmedIds)";

                           }
                    $mksCondition =  " WHERE classId = $degree $mksSubjectCondition ORDER BY cnt DESC LIMIT 0,1 ";

                   $grandFinalArray = $studentManager->getSingleFieldForGrandMarks("SUM(`internalTotalMarks`+`externalTotalMarks`) AS cnt ",$mksCondition);
                    if(is_array($grandFinalArray) && count($grandFinalArray)>0 ) {
                      $totalSubjectMarks = $grandFinalArray[0]['cnt'];
                      if($totalSubjectMarks=='' || $totalSubjectMarks==0) {
                        $totalSubjectMarks = '';
                      }
                      else {
                         $totalSubjectMarks = "(".number_format($totalSubjectMarks, 2, '.', '').")";
                      }
                    }

                    $csvData .= ",Grand ,%age";

                    //$timeTableStr1 .="<td align='center' width='100' colspan='2' class = 'headingFont'><B>".$totalSubjectMarks."</B></td>";
                    //$timeTableStr1 .="<td align='center' class = 'headingFont'></td>";
                    //$timeTableStr1 .="<td align='center' class = 'headingFont'></td>";
                    $csvData .= "\n";
                    $csvData .= ",,,";
                    for($j=0;$j<count($subjectArr1);$j++){
                        $csvData .= ",Marks";
                        if($showGraceMarks==1){
                          $csvData .= ",Grace";
                        }
                        if($showGrades==1){
                          $csvData .= ",Grade";
                          $csvData .= ",Point";
                        }
                    }
                    $csvData .= "\n";
                    //$timeTableStr1 .=' </tr>';
                }
            //$timeTableStr1 .='</table></td></tr>';
            $j=1;
            echo $timeTableStr1;
            foreach($studentDetailArr1 as $arrayValue){

                $completeDetail = explode("~", $arrayValue);
                $csvData .= parseCSVComments($j);
                $csvData .= ",".parseCSVComments($completeDetail[2]);
                $csvData .= ",".parseCSVComments($completeDetail[1]);
                $csvData .= ",".parseCSVComments($completeDetail[3]);


                    $subjectValueArr = array();
                    if(count($studentArr2[$completeDetail[0]])){

                        foreach($studentArr2[$completeDetail[0]] as $key=>$att){

                                $explodeArr = explode('~',$key);
                                $subjectValueArr[$explodeArr[1]]=$att;
                        }
                    }
                    foreach($subjectIdArr1 as $keyValue){

                        $marksValue = $subjectValueArr[$keyValue];
                        $marksValueArr = explode('~',$marksValue);
                        if($marksValue==''){
                          $marksValue ='--';
                        }
                        $receivedMarksArr = explode('/',$marksValueArr[0]);
                        if($receivedMarksArr[0]!='') {
                          $csvData .= ",".parseCSVComments(number_format($receivedMarksArr[0], 2, '.', ''));
                        }
                        else {
                           $csvData .= ","; 
                        }
                        $totalMarks +=$receivedMarksArr[0];
                        $totalMarks1 +=$receivedMarksArr[1];
                        if($showGraceMarks==1){
                            $gMarks= $marksValueArr[1];
                            if($gMarks==''){
                              $gMarks=0;
                            }
                            $csvData .= ",".parseCSVComments($gMarks);
                        }
                        if($showGrades==1){

                            $gradeMarksArr= explode('%^%',$marksValueArr[2]);
                            if($gradeMarksArr[0]==''){
                                $gradeMarks1='--';
                                $gradeMarks2='--';
                            }
                            else{
                                $gradeMarks1=$gradeMarksArr[0];
                                $gradeMarks2=$gradeMarksArr[1];
                            }
                            $csvData .= ",".parseCSVComments($gradeMarks1);
                            $csvData .= ",".parseCSVComments($gradeMarks2);
                        }
                    }
                    $csvData .= ",".parseCSVComments(number_format($totalMarks, 2, '.', ''));
                    $csvData .= ",".parseCSVComments(number_format((($totalMarks/$totalMarks1)*100), 2, '.', ''));
                    $totalMarks = 0;
                    $totalMarks1 = 0;
                    $csvData .= "\n";
              $j++;
              $m++;
          }

           if(count($subjectArr)){
                 $csvData .= ",,,";
                /*foreach($subjectArr1 as $keySubject=>$keyValue){
                    if($showGraceMarks==1){
                        $timeTableStr .="<td align='center' width='100' colspan='2' class = 'headingFont'><b>".$subjectArr1[$keySubject]."</b></td>";
                    }
                    else{

                        $timeTableStr .="<td align='center' width='100' class = 'headingFont'><b>".$subjectArr1[$keySubject]."</b></td>";
                    }
                }*/

                foreach($subjectArr1 as $keySubject=>$keyValue){
                    $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                    if($showGraceMarks==1){
                      $csvData .= ",";
                    }
                    if($showGrades==1){
                      $csvData .= ",,";
                    }
                }
                //$timeTableStr .="<td align='center' class = 'headingFont'><b>Grand</b></td>";
                //$timeTableStr .="<td align='center' class = 'headingFont'><b>%</b></td>";
                 $csvData .= "\n";

             }
             $csvData .= ",,,Class Average";




          $fetchMarksDetailArray1 = $studentReportsManager->getTotalConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);

           for($j=0; $j<count($subjectIdArr1); $j++) {   
              $subjectId = $subjectIdArr1[$j];          
              for($i=0;$i<count($fetchMarksDetailArray1);$i++){
                 //if($showGraceMarks==1){
                 if($subjectId== $fetchMarksDetailArray1[$i]['subjectId']) {
                     $graceArray = $studentReportsManager->getTotalGraceMarks($studentIdList2, $degree, $subjectIdArr1[$j]);
                     $grcMarks = $graceArray[0]['marksScored'];
                     /*
                     if($showGraceMarks==1){ 
                       $graceArray = $studentReportsManager->getTotalGraceMarks($studentIdList2, $degree, $subjectIdArr1[$j]);
                       $grcMarks = $graceArray[0]['marksScored'];
                     }
                     else{
                       $grcMarks = 0;
                     }
                     
                     $mksTotal=0;
                     $mksSubjectCondition = " AND subjectId = ".$fetchMarksDetailArray1[$i]['subjectId'];
                     $mksCondition =  " WHERE classId = $degree $mksSubjectCondition GROUP BY studentId ORDER BY cnt DESC LIMIT 0,1 ";
                     $grandFinalArray = $studentManager->getSingleField(TOTAL_TRANSFERRED_MARKS_TABLE, "studentId, SUM(maxMarks) AS cnt ",$mksCondition);
                     if(is_array($grandFinalArray) && count($grandFinalArray)>0 ) {
                       $mksTotal=$grandFinalArray[0]['cnt'];    
                     }
                     $tmaxMarks = doubleval($mksTotal)*doubleval($cnt1);
                     $subjectAvg = ((($fetchMarksDetailArray1[$i]['marksScored']+$grcMarks)/$tmaxMarks)*100); 
                     */
                     $subjectAvg = ($fetchMarksDetailArray1[$i]['marksScored']+$grcMarks)/doubleval($cnt1);
                     $subjectAvg = number_format($subjectAvg, 2, '.', '');
                     $csvData .= ",".parseCSVComments($subjectAvg);
                     if($showGraceMarks==1){
                        $csvData .= ",";
                     }
                     if($showGrades==1){
                       $csvData .= ",,";
                     }
                     break;
                  }
              }
           }
          $csvData .= "\n";
        //}

        ///////
        // Fetch Subject List
       $filter1 = "";
       $filter= " DISTINCT su.subjectTypeId, su.subjectId, su.subjectName, su.subjectCode, st.subjectTypeName, c.classId ";
       $groupBy = "";
       $orderBy = " classId, subjectTypeId, subjectCode ";
       $cond = " AND c.classId = $degree";
       $recordArray =  $studentReportsManager->getAllSubjectAndSubjectTypes($cond, $filter, $groupBy,  $orderBy );
       $recordCount = count($recordArray);

      $subjectIds =0;
      for($i=0; $i<$recordCount; $i++) {
        $subjectIds .=",".$recordArray[$i]['subjectId'];
      }


       $employeeName='';
       $tableName = "employee emp, subject su,  ".TIME_TABLE_TABLE."  tt, `group` g ";
       $fieldsName ="su.subjectId, GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,') ',g.groupName)
				  ORDER BY emp.employeeName, g.groupName SEPARATOR ', ') AS employeeName";
       $empCondition = " WHERE
                                tt.timeTableLabelId=$timeTable AND
                                tt.toDate IS NULL AND
                                g.groupId = tt.groupId AND
                                tt.subjectId = su.subjectId AND
                                tt.subjectId IN ($subjectIds) AND
                                emp.employeeId = tt.employeeId AND
                                g.classId = $degree
                                $showGroupG
                                $conditionsEmp
                          GROUP BY
                                su.subjectId
                          ORDER BY
                                su.subjectTypeId, su.subjectCode  ";
       $employeeArray = $studentManager->getSingleField($tableName, $fieldsName, $empCondition);




       if(count($recordArray)>0) {
           $csvData .= "\n";
           $csvData .= "#,Subject Code,Subject Name,Teacher";
           $csvData .= "\n";

           $j=0;
           for($i=0;$i<count($recordArray);$i++) {
              $csvData .= parseCSVComments(($i+1));
              $csvData .= ",".parseCSVComments($recordArray[$i]['subjectCode']);
              $csvData .= ",".parseCSVComments($recordArray[$i]['subjectName']);

              $employeeName ='';
              $subjectId = $recordArray[$i]['subjectId'];
              if($employeeArray[$j]['subjectId']==$subjectId) {
                $csvData .= ",".parseCSVComments($employeeArray[$j]['employeeName']);
                $j++;
              }
              else {
                $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);
              }
              $csvData .= "\n";
           }
       }



        ///////
        /**************************/
       // $rangeArray = $studentReportsManager->getRanges();

  
            if($showGraceMarks==1){

            $csvData .= "\n\n";
            $csvData .= "Marks Scored,Student Count";
            $csvData .= "\n";


            if (count($subjectArr1)) {
                foreach($subjectArr1 as $keySubject=>$keyValue){
                    $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                }
            }
                $csvData .= "\n";

               // foreach($rangeArray as $rangeRecord) {

                 //   $lowMarksValue = $rangeRecord['lowMarksValue'];
                   // $highMarksValue = $rangeRecord['highMarksValue'];
				for($i=0;$i<count($rangeArray);$i++){
					$lowMarksValue = $rangeArray[$i];
					$i=$i+1;
					$highMarksValue = $rangeArray[$i];

					$csvData .= parseCSVComments($lowMarksValue." - ".$highMarksValue);
                    // print_r($subjectIdArr1);
                    // die();
                    for($j=0;$j<count($subjectIdArr1);$j++){
                        $studentCountArray = $studentReportsManager->getRangeStudentCountWithGraceNEW($studentIdList, $degree, $subjectIdArr1[$j], $lowMarksValue, $highMarksValue);
                        $studentCount = $studentCountArray[0]['studentCount'];
                        $csvData .= ",".parseCSVComments($studentCount);
                    }
                    $csvData .="\n";
                }
            }else{

            $csvData .= "\n\n";
            $csvData .= "Marks Scored,Student Count";
            $csvData .= "\n";


            if (count($subjectArr1)) {
                foreach($subjectArr1 as $keySubject=>$keyValue){
                    $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                }
            }
                 $csvData .= "\n";
               // foreach($rangeArray as $rangeRecord) {

                    //$lowMarksValue = $rangeRecord['lowMarksValue'];
                    //$highMarksValue = $rangeRecord['highMarksValue'];
					for($i=0;$i<count($rangeArray);$i++){
					$lowMarksValue = $rangeArray[$i];
					$i=$i+1;
					$highMarksValue = $rangeArray[$i];

                    if ($rangeStr != '') {
                        //$rangeStr .= '<br>';
                    }
                     $csvData .= parseCSVComments($lowMarksValue." - ".$highMarksValue);
                    //echo count($subjectIdArr1);
                    //print_r($subjectIdArr1);
                    //die();
                    if($studentIdList=='') {
                      $studentIdList=0;
                    }
                    if(count($subjectIdArr1) > 0) {
                        foreach($subjectIdArr1 as $subjectId){
                          $studentCountArray = $studentReportsManager->getRangeStudentCountWithGraceNEW($studentIdList, $degree,  $subjectId, $lowMarksValue, $highMarksValue);
                          $studentCount = $studentCountArray[0]['studentCount'];
                          $csvData .= ",".parseCSVComments($studentCount);
                       }
                    }
                    else {
                        $csvData .= ",".parseCSVComments(NOT_APPLICABLE_STRING);
                    }
                     $csvData .= "\n";
                }
            }
		}
		else{
			$csvData .="\n";
			$csvData .=" No Data Found ";
		}

            //}


     /***************************/
    }



    /** START Code to fetch Attendance for students **/
    if($reportFor==1){

        //$fetchMarksDetailArray = $studentReportsManager->getConsolidatedAttendanceDetails($conditions,$conditions3,$sortField,$sortOrderBy);
        $fetchMarksDetailArray = CommonQueryManager::getInstance()->getStudentOldAttendanceReport($attCondition,'',$conditions3,$orderBySubject);
        $cnt2 = count($fetchMarksDetailArray);

        $studentIdM = "";
        for($i=0;$i<$cnt2;$i++){

            $studentDetailArr[]=$fetchMarksDetailArray[$i]['studentId'].'~'.$fetchMarksDetailArray[$i]['rollNo'].'~'.$fetchMarksDetailArray[$i]['universityRollNo'].'~'.$fetchMarksDetailArray[$i]['studentName'];
            $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode'];
            $subjectIdArr[]=$fetchMarksDetailArray[$i]['subjectId'];
            $studentId2 = $fetchMarksDetailArray[$i]['studentId'];

            if(($studentId2==$studentIdM)){

                $array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($fetchMarksDetailArray[$i]['lectureAttended']+$fetchMarksDetailArray[$i]['leaveTaken']).'/'.$fetchMarksDetailArray[$i]['lectureDelivered'];
            }
            else{

                $array2=array();
                $array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($fetchMarksDetailArray[$i]['lectureAttended']+$fetchMarksDetailArray[$i]['leaveTaken']).'/'.$fetchMarksDetailArray[$i]['lectureDelivered'];
            }
            $studentArr2[$fetchMarksDetailArray[$i]['studentId']] = $array2;
            if($studentId2 != ""){

                $studentIdM = $studentId2;
            }
        }

        if(count($subjectArr)){
            $subjectArr1 = array_values(array_unique($subjectArr));
            //asort($subjectArr1);
        }
        if(count($subjectIdArr))
            $subjectIdArr1 = array_values(array_unique($subjectIdArr));

        if(count($studentDetailArr))
            $studentDetailArr1 = array_values(array_unique($studentDetailArr));
        $cnt1 = count($studentDetailArr1);

        $m=0;
        if($cnt1){

                if(count($subjectArr)){

                    foreach($subjectArr1 as $keySubject=>$keyValue){
                        $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]);
                    }
                    $csvData .= "\n";
                    $csvData .= ",,,";
                    for($j=0;$j<count($subjectArr1);$j++){
                      $csvData .= ",".parseCSVComments('Attendance');
                    }
                    $csvData .= "\n";
                }
            //$timeTableStr1 .='</table></td></tr>';
            $j=1;
            echo $timeTableStr1;
            foreach($studentDetailArr1 as $arrayValue){

                $completeDetail = explode("~", $arrayValue);

                $csvData .= parseCSVComments($j);
                $csvData .= ",".parseCSVComments($completeDetail[2]);
                $csvData .= ",".parseCSVComments($completeDetail[1]);
                $csvData .= ",".parseCSVComments($completeDetail[3]);

                    $subjectValueArr = array();
                    if(count($studentArr2[$completeDetail[0]])){

                        foreach($studentArr2[$completeDetail[0]] as $key=>$att){

                                $explodeArr = explode('~',$key);
                                $subjectValueArr[$explodeArr[1]]=$att;
                        }
                    }
                    foreach($subjectIdArr1 as $keyValue){

                        $marksValue = $subjectValueArr[$keyValue];
                        if($marksValue==''){

                            $marksValue ='--';
                        }
                        $csvData .= ",".parseCSVComments($marksValue);
                    }
              $csvData .= "\n";
              $j++;
              $m++;
          }
        }
	    else{
			$csvData .="\n";
			$csvData .=" No Data Found ";
		}
    }
    /** END Code to fetch Attendance for students **/
    /** END Code to fetch marks for students **/

    /** START  Code to fetch Both Marks and  Attendance for students **/
    if($reportFor==0){

        $fetchMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);
        //$fetchAttDetailArray = $studentReportsManager->getConsolidatedAttendanceDetails($conditions,$conditions3,$sortField,$sortOrderBy);
        $fetchAttDetailArray = CommonQueryManager::getInstance()->getStudentOldAttendanceReport($attCondition,'',$conditions3,$orderBySubject);

        //echo "<pre>";
        //print_r($fetchAttDetailArray);
        //die();
        $cnt2 = count($fetchMarksDetailArray);

        $studentIdM = "";
        $studentIdA = "";
        for($i=0;$i<$cnt2;$i++){

            $studentDetailArr[]=$fetchMarksDetailArray[$i]['studentId'].'~'.$fetchMarksDetailArray[$i]['rollNo'].'~'.$fetchMarksDetailArray[$i]['universityRollNo'].'~'.$fetchMarksDetailArray[$i]['studentName'];
            $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode'];
            $subjectIdArr[]=$fetchMarksDetailArray[$i]['subjectId'];
            $studentId2 = $fetchMarksDetailArray[$i]['studentId'];

            if(($studentId2==$studentIdM)){

                $array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=$fetchMarksDetailArray[$i]['marksScored'].'/'.$fetchMarksDetailArray[$i]['maxMarks'];
            }
            else{

                $array2=array();
                $array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=$fetchMarksDetailArray[$i]['marksScored'].'/'.$fetchMarksDetailArray[$i]['maxMarks'];
            }


            $studentId1 = $fetchAttDetailArray[$i]['studentId'];
            if(($studentId1==$studentIdA)){

                //$array1[$fetchAttDetailArray[$i]['studentId']."~".$fetchAttDetailArray[$i]['subjectId']."~".$fetchAttDetailArray[$i]['subjectTypeId']]=$fetchAttDetailArray[$i]['attended'].'/'.$fetchAttDetailArray[$i]['delivered'];
                $array1[$fetchAttDetailArray[$i]['studentId']."~".$fetchAttDetailArray[$i]['subjectId']."~".$fetchAttDetailArray[$i]['subjectTypeId']]=($fetchAttDetailArray[$i]['lectureAttended']+$fetchAttDetailArray[$i]['leaveTaken']).'/'.$fetchAttDetailArray[$i]['lectureDelivered'];
            }
            else{

                $array1=array();
                //$array1[$fetchAttDetailArray[$i]['studentId']."~".$fetchAttDetailArray[$i]['subjectId']."~".$fetchAttDetailArray[$i]['subjectTypeId']]=$fetchAttDetailArray[$i]['attended'].'/'.$fetchAttDetailArray[$i]['delivered'];
                $array1[$fetchAttDetailArray[$i]['studentId']."~".$fetchAttDetailArray[$i]['subjectId']."~".$fetchAttDetailArray[$i]['subjectTypeId']]=($fetchAttDetailArray[$i]['lectureAttended']+$fetchAttDetailArray[$i]['leaveTaken']).'/'.$fetchAttDetailArray[$i]['lectureDelivered'];
            }
            $studentArr1[$fetchAttDetailArray[$i]['studentId']] = $array1;
            $studentArr2[$fetchMarksDetailArray[$i]['studentId']] = $array2;

            if($studentId2 != ""){

                $studentIdM = $studentId2;
            }
            if($studentId1 != ""){

                $studentIdA = $studentId1;
            }
        }

        if(count($subjectArr))
            $subjectArr1 = array_values(array_unique($subjectArr));
        if(count($subjectIdArr))
            $subjectIdArr1 = array_values(array_unique($subjectIdArr));

        if(count($studentDetailArr))
            $studentDetailArr1 = array_values(array_unique($studentDetailArr));

        $cnt1 = count($studentDetailArr1);

        //echo "<pre>";
        //print_r($studentArr1);
        $m=0;
        if($cnt1){

                if(count($subjectArr)){
                    foreach($subjectArr1 as $keySubject=>$keyValue){
                       $csvData .= ",".parseCSVComments($subjectArr1[$keySubject]).",";
                    }
                    $csvData .= "\n";
                    $csvData .= ",,,";
                    for($j=0;$j<count($subjectArr1);$j++){
                        $csvData .= ",Marks";
                        $csvData .= ",Att";
                    }
                    $timeTableStr1 .=' </tr>';
                }
            //$timeTableStr1 .='</table></td></tr>';
            $j=1;
            echo $timeTableStr1;
            foreach($studentDetailArr1 as $arrayValue){

                $completeDetail = explode("~", $arrayValue);

                $csvData .= parseCSVComments($j);
                $csvData .= ",".parseCSVComments($completeDetail[2]);
                $csvData .= ",".parseCSVComments($completeDetail[1]);
                $csvData .= ",".parseCSVComments($completeDetail[3]);

                    $subjectValueArr = array();
                    if(count($studentArr2[$completeDetail[0]])){

                        foreach($studentArr2[$completeDetail[0]] as $key=>$att){

                                $explodeArr = explode('~',$key);
                                $subjectValueArr[$explodeArr[1]]=$att;
                        }
                    }

                    if(count($studentArr1[$completeDetail[0]])){

                        foreach($studentArr1[$completeDetail[0]] as $key=>$att){

                                $explodeArr = explode('~',$key);
                                $subjectAttValueArr[$explodeArr[1]]=$att;
                        }
                    }

                    foreach($subjectIdArr1 as $keyValue){

                        $marksValue = $subjectValueArr[$keyValue];
                        if($marksValue==''){

                            $marksValue ='--';
                        }

                        $attValue = $subjectAttValueArr[$keyValue];
                        if($attValue==''){

                            $attValue ='--';
                        }
                        $csvData .= ",".parseCSVComments($marksValue);
                        $csvData .= ",".parseCSVComments($attValue);
                    }
              $csvData .="\n";
              $j++;
              $m++;
          }

        }
    }

    /** END Code to fetch Both Marks and  Attendance for students **/

    UtilityManager::makeCSV($csvData,'StudentConsolidatedReport.csv');
    die;

?>
