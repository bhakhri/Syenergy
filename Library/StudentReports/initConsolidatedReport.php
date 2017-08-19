<?php
//--------------------------------------------------------
//This file returns the array of subjects, based on class
//
// Author :Rajeev Aggarwal
// Created on : 13-Aug-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

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

    require_once(MODEL_PATH."/CommonQueryManager.inc.php");
    $commonQueryManager = CommonQueryManager::getInstance();

	$timeTable     = $REQUEST_DATA['timeTable'];
	$degree	       = $REQUEST_DATA['degree'];
	$subjectTypeId = $REQUEST_DATA['subjectTypeId'];
	$subjectId     = $REQUEST_DATA['subjectId'];
    $groupId       = $REQUEST_DATA['groupId'];
	$reportFor     = $REQUEST_DATA['reportFor'];
	$marksFor      = $REQUEST_DATA['marksFor'];
	$average       = $REQUEST_DATA['average'];
	$percentage    = $REQUEST_DATA['percentage'];
    
    $includeGrace='';  
    if($marksFor=='3') {
      $includeGrace='1';  
      $marksFor=0;
    }
    

	$sortField    = $REQUEST_DATA['sortField'];
	if($sortField == "firstName") {
		$sortField = "studentName";
	}
	$sortOrderBy    = $REQUEST_DATA['sortOrderBy'];
	$showGraceMarks    = $REQUEST_DATA['showGraceMarks'];
	$showGrades    = $REQUEST_DATA['showGrades'];

    $attCondition = " AND c.classId=$degree";
	$conditions	   = " AND c.classId=$degree";
	$conditionsS   = " AND c.classId=$degree";
	if($subjectTypeId){
	    $attCondition  .= " AND su.subjectTypeId = $subjectTypeId";
		$conditions	   .= " AND sub.subjectTypeId = $subjectTypeId";
	}
	if($subjectId){
	    $attCondition  .= " AND su.subjectId = $subjectId";
		$conditions	   .= " AND sub.subjectId = $subjectId";
	}
	if($groupId){
	    $attCondition  .= " AND grp.groupId = $groupId";
		$conditions	   .= " AND sg.groupId = $groupId";
	}
	if($marksFor==1){

		$conditions1	= " AND ttm.conductingAuthority IN (1,3) ";
	}
	if($marksFor==2){

		$conditions1	= " AND ttm.conductingAuthority IN (2) ";
	}
	if($average==1){

		$conditions2	= "  having ((marksScored/maxMarks)*100)>=$percentage "; 
	}
	if($average==2){

		$conditions2	= "  having ((marksScored/maxMarks)*100)<$percentage ";
	}
	if($average==3){

		//$conditions3	= "  having attended>=$percentage ";
        $conditions3    = "  WHERE (IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100)) >= $percentage ";
	}
	if($average==4){

		//$conditions3	= "  having attended<$percentage ";
        $conditions3    = "  WHERE (IF(t.lectureDelivered=0,0,((t.lectureAttended+t.leaveTaken)/t.lectureDelivered)*100)) < $percentage ";
	}

    
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
       $tableName = "employee emp, subject su, time_table tt, `group` g ";
       $fieldsName ="su.subjectId, GROUP_CONCAT(DISTINCT CONCAT(emp.employeeName,' (',emp.employeeCode,')') ORDER BY emp.employeeName SEPARATOR ', ') AS employeeName";
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



       $employeeList1 = '';
       if(count($recordArray)>0) {
           $employeeList1 = "<table width='100%' border='1' class='reportTableBorder'>
                                  <tr>
                                     <td width='2%' class='dataFont'><b>#</b></td>
                                     <td width='10%' class='dataFont'><b>Subject Code</b></td>
                                     <td width='25%' class='dataFont'><b>Subject Name</b></td>
                                     <td width='65%' class='dataFont'><b>Teacher</b></td>
                                  </tr>";

           $j=0;
           for($i=0;$i<count($recordArray);$i++) {
              $employeeList1 .= "<tr>
                                 <td class='dataFont'>".($i+1)."</nobr></td>
                                 <td class='dataFont'>".strtoupper($recordArray[$i]['subjectCode'])."</td>
                                 <td class='dataFont'>".strtoupper($recordArray[$i]['subjectName'])."</td>";
              $employeeName ='';
              $subjectId = $recordArray[$i]['subjectId'];
              if($employeeArray[$j]['subjectId']==$subjectId) {
                $employeeList1 .= "<td width='98%' class='dataFont'>".strtoupper($employeeArray[$j]['employeeName'])."</td>";
                $j++;
              }
              else {
                $employeeList1 .= "<td width='98%' class='dataFont'>".NOT_APPLICABLE_STRING."</td>";
              }
              $employeeList1 .= "</tr>";
           }
           $employeeList1 .= "</table>";
       }


     $orderBySubject =  $sortField." ".$sortOrderBy;
   
       
  /** START Code to fetch marks for students **/
  if($reportFor==2){
          
   
  if($marksFor=='0'){
     $conditions1	= " AND ttm.conductingAuthority IN (1,3) ";
     $fetchInternalMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,'',$sortField,$sortOrderBy,$includeGrace);
//echo "<pre>";
//print_r($fetchInternalMarksDetailArray);die;
     
     $conditions1	= " AND ttm.conductingAuthority IN (2) ";
     $fetchExternalMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,'',$sortField,$sortOrderBy,$includeGrace);
     $conditions1='';
//echo "<pre>";
//print_r($fetchExternalMarksDetailArray);die;
 }
 $fetchMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy,$includeGrace);
//echo "<pre>";
//print_r($fetchMarksDetailArray);die;

 $cnt2 = count($fetchMarksDetailArray);     
 
        
		$studentIdList2 = UtilityManager::makeCSList($fetchMarksDetailArray, 'studentId');
		if (empty($studentIdList2)) {
			$studentIdList2 = 0;
		}

		$studentIdM = "";
		$totalMarks1 = 0;
		$totalMarks2 = 0;
		for($i=0;$i<$cnt2;$i++){

			$studentDetailArr[]=$fetchMarksDetailArray[$i]['studentId'].'~'.$fetchMarksDetailArray[$i]['rollNo'].'~'.$fetchMarksDetailArray[$i]['universityRollNo'].'~'.$fetchMarksDetailArray[$i]['studentName'];
    
          
 
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
//echo $intmsBoth;die;
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
	       //echo $finalScoredMarks;die;
               $maximumMarks=$fetchInternalMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks'];
               $internalMarksArr[]= $fetchMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks'];
               $subjectArr[]=$fetchMarksDetailArray[$i]['subjectCode']." (".($fetchMarksDetailArray[$i]['internalTotalMarks']+$fetchMarksDetailArray[$i]['externalTotalMarks']).")";
            }
			$subjectIdArr[]=$fetchMarksDetailArray[$i]['subjectId'];
			$studentId2 = $fetchMarksDetailArray[$i]['studentId'];
			//grace marks
			$abcArray = $studentReportsManager->getGraceMarks($fetchMarksDetailArray[$i]['studentId'], $degree, $fetchMarksDetailArray[$i]['subjectId']);
//echo "<pre>";
//print_r($abcArray);die;
			
            $internalGraceMarks = $abcArray[0]['internalGraceMarks'];
	    //echo $internalGraceMarks;
            $externalGraceMarks = $abcArray[0]['externalGraceMarks'];
	    //echo $externalGraceMarks;           
$totalGraceMarks = $abcArray[0]['totalGraceMarks'];
           //echo $totalGraceMarks;die;
            $graceMarks1 ='0';
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
            $init=($finalScoredMarks+$graceMarks1);
//echo $init;die;
			if(($studentId2==$studentIdM)){
				$array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($finalScoredMarks).'/'.$maximumMarks."~".$graceMarks."~".$fetchMarksDetailArray[$i]['grade'];
				//$array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($fetchMarksDetailArray[$i]['marksScored']+$graceMarks)."~".$graceMarks;
				//$totalMarks += $fetchMarksDetailArray[$i]['marksScored']+$graceMarks;
			}
			else{

				$array2=array();
				$array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($finalScoredMarks).'/'.$maximumMarks."~".$graceMarks."~".$fetchMarksDetailArray[$i]['grade'];
				//$array2[$fetchMarksDetailArray[$i]['studentId']."~".$fetchMarksDetailArray[$i]['subjectId']."~".$fetchMarksDetailArray[$i]['subjectTypeId']]=($fetchMarksDetailArray[$i]['marksScored']+$graceMarks)."~".$graceMarks;
				//$totalMarks += $fetchMarksDetailArray[$i]['marksScored']+$graceMarks;
			}
			$studentArr2[$fetchMarksDetailArray[$i]['studentId']] = $array2;
			if($studentId2 != ""){

				$studentIdM = $studentId2;
			}
		}


		if(count($subjectArr)) {

			$subjectArr1 = array_values(array_unique($subjectArr));
			//asort($subjectArr1);
			
		}
		if(count($subjectIdArr)) {
			$subjectIdArr1 = array_values(array_unique($subjectIdArr));
			//asort($subjectIdArr1);
		}

		if(count($studentDetailArr))
			$studentDetailArr1 = array_values(array_unique($studentDetailArr));
        		


		$cnt1 = count($studentDetailArr1);

		$m=0;

		if($cnt1){

			$timeTableStr="";
			$timeTableStr='<table width="100%" border="0" cellspacing="2" cellpadding="0" class="">';
			$timeTableStr .= '<tr class="rowheading">
				<td width="2%" valign="middle" class="dataFont" rowspan="2"><b>&nbsp;#</b>
				<td width="12%" valign="middle" class="dataFont" rowspan="2"><b>&nbsp;Univ. Roll No.</b>
				<td valign="middle" align="left" width="10%" class="dataFont" rowspan="2"><b>Roll No.</b></td>
				<td valign="middle" align="left" width="15%"  class="dataFont"  rowspan="2"><b>Student Name</b></td>';
                 
				if(count($subjectArr)){
					$subId=0;
					foreach($subjectArr1 as $keySubject=>$keyValue){
						if($showGraceMarks==1){
							if($showGrades==1){
								$timeTableStr .="<td align='center'  colspan='4'><b>".$subjectArr1[$keySubject]."</b></td>";
							}
							else{

								$timeTableStr .="<td align='center' colspan='2'><b>".$subjectArr1[$keySubject]."</b></td>";
							}
						}
						else{

							if($showGrades==1){
								$timeTableStr .="<td align='center'  colspan='3'><b>".$subjectArr1[$keySubject]."</b></td>";
							}
							else{

								$timeTableStr .="<td align='center' ><b>".$subjectArr1[$keySubject]."</b></td>";
							}
						}
						//$totalSubjectMarks += $internalMarksArr[$subId];
						$subId++;
					}

                   
                     if(trim($REQUEST_DATA['subjectId'])==''){
                        $subjectIdCondition=" WHERE classId = $degree";
			//FETCHING subjectIds  
                     $subjectIdArray=$studentManager->getSubjectIds(TOTAL_TRANSFERRED_MARKS_TABLE,$subjectIdCondition);
			//echo "<pre>";
//print_r($subjectIdArray);die;
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
                         $totalSubjectMarks = number_format($totalSubjectMarks, 2, '.', '');
                      }
                    }  

					$timeTableStr .="<td align='center'  colspan='2'><B></B></td>";
					$timeTableStr .=' </tr><tr class="rowheading">';
					for($j=0;$j<count($subjectArr1);$j++){

						$timeTableStr .="<td  align='center'><b>Marks</b></td>";

						if($showGraceMarks==1){
							$timeTableStr .="<td align='center'><b>Grace</b></td>";
						}
						if($showGrades==1){
							$timeTableStr .="<td  align='center'><b>Grade</b></td>";
							$timeTableStr .="<td  align='center'><b>Point</b></td>";
						}
					}
					$timeTableStr .="<td align='center'><b>Grand</b></td>";
					$timeTableStr .="<td align='center' ><b>%age</b></td>";
					$timeTableStr .=' </tr>';
				}
			$timeTableStr .='';
			$j=1;

			//echo "<pre>";
		//print_r($studentDetailArr1);
		//die();
			foreach($studentDetailArr1 as $arrayValue){

				$completeDetail = explode("~", $arrayValue);
				
				$bg = $bg =='row0' ? 'row1' : 'row0';
				$timeTableStr .= '<tr class="'.$bg.'">
				<td width="2%" valign="middle" style="font-size:12px">&nbsp;'.$j.'</td>
				<td width="12%" valign="middle" style="font-size:12px">&nbsp;'.$completeDetail[2].'</td>
				<td valign="middle" align="left" width="10%">&nbsp;'.$completeDetail[1].'</td>
				<td valign="middle" align="left" width="20%">&nbsp;'.$completeDetail[3].'</td>';

                	$subjectValueArr = array();  
					if(count($studentArr2[$completeDetail[0]])){
					  foreach($studentArr2[$completeDetail[0]] as $key=>$att) {
  						$explodeArr = explode('~',$key);
					  	$subjectValueArr[$explodeArr[1]]=$att;
					  }
					}        
                    
                    $totalMarks = 0;
					foreach($subjectIdArr1 as $keyValue){

						$marksValue = $subjectValueArr[$keyValue];
						$marksValueArr = explode('~',$marksValue);

						//echo "<pre>";
						//print_r($marksValue);
						//die();
						if($marksValueArr[0]==''){

							$marksValue ='--&nbsp;';
						}

						$receivedMarksArr = explode('/',$marksValueArr[0]);
						//echo "<pre>";
                        			//print_r($receivedMarksArr);die;
                        if($receivedMarksArr[0]!='') {            
						  $timeTableStr .="<td align='center'>".number_format($receivedMarksArr[0], 2, '.', '')."</td>";
                        }
                        else {
                         $timeTableStr .="<td class = 'dataFont' align='center'>".NOT_APPLICABLE_STRING."</td>";    
                        }
						//$timeTableStr .="<td align='center'>".$marksValueArr[2]."</td>";
						$totalMarks +=$receivedMarksArr[0];
						$totalMarks1 +=$receivedMarksArr[1];
						if($showGraceMarks==1){
							$gMarks= $marksValueArr[1];
							if($gMarks==''){
							  $gMarks=0;
							}
							$timeTableStr .="<td align='center'>".$gMarks."</td>";
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
							$timeTableStr .="<td align='center'>".$gradeMarks1."</td>";
							$timeTableStr .="<td align='center'>".$gradeMarks2."</td>";
						}

					}


					$timeTableStr .="<td align='center'>".number_format($totalMarks, 2, '.', '')."</td>";
					//$timeTableStr .="<td align='center'>".$totalMarks1."</td>";
					$timeTableStr .="<td align='center'>".number_format((($totalMarks/$totalMarks1)*100), 2, '.', '')."</td>";
					$totalMarks1 = 0;
			  $timeTableStr .= '</tr>';

			  $j++;
			  $m++;
		  }


		  if(count($subjectArr)){
				$timeTableStr .= '<tr class="rowheading"><td colspan="4"></td>';
				/*foreach($subjectArr1 as $keySubject=>$keyValue){
					if($showGraceMarks==1){
						$timeTableStr .="<td align='center' width='100' colspan='2'><b>".$subjectArr1[$keySubject]."</b></td>";
					}
					else{

						$timeTableStr .="<td align='center' width='100'><b>".$subjectArr1[$keySubject]."</b></td>";
					}

				}*/
				foreach($subjectArr1 as $keySubject=>$keyValue){
                        $colspan=1;
						if($showGraceMarks==1){
                          $colspan=2;
						}
						if($showGrades==1){
						  $colspan=$colspan+2;
						}
                        $colspans = "";
                        if($colspan!=1) {
                           $colspans = "colspan=$colspan";
                        }
					    $timeTableStr .="<td align='center' $colspans width='100'><b>".$subjectArr1[$keySubject]."</b></td>";
					}
                 $timeTableStr .="<td align='center' colspan='2' width='100'><b></b></td>";
				//$timeTableStr .="<td align='center' width='100'><b>Total</b></td>";
				//$timeTableStr .="<td align='center' width='100'><b>%age</b></td>";
				$timeTableStr .=' </tr>';

			}

		  $timeTableStr .= '<tr><td colspan="4" align="right"><B>Class Average</B></td>';

          $fetchMarksDetailArray1 = $studentReportsManager->getTotalConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);


          
          for($j=0; $j<count($subjectIdArr1); $j++) {   
              $subjectId = $subjectIdArr1[$j];          
              for($i=0;$i<count($fetchMarksDetailArray1);$i++){
			     if($subjectId== $fetchMarksDetailArray1[$i]['subjectId']) {
                    //Total grace marks
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
                     //$subjectAvg = ((($fetchMarksDetailArray1[$i]['marksScored']+$grcMarks)/$fetchMarksDetailArray1[$i]['maxMarks'])*100);
			        $subjectAvg = number_format($subjectAvg, 2, '.', '');
			        $timeTableStr .="<td align='center' $colspans ><b>".$subjectAvg."</b></td>";
                    break;
                 }
		      }
          }

		  $timeTableStr .= '</tr>';
		  $timeTableStr .='</table>';


			//echo $timeTableStr.'!~~!~~!'.$employeeList1;
		}

	}
	/** END Code to fetch marks for students **/

	/** START Code to fetch Attendance for students **/
	if($reportFor==1){

        //$fetchMarksDetailArray = $studentReportsManager->getConsolidatedAttendanceDetails($conditions,$conditions3,$sortField,$sortOrderBy);
        //$fetchMarksDetailArray = CommonQueryManager::getInstance()->getStudentAttendanceReport($attCondition,$orderBySubject,1,'',$conditions3);
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

			$timeTableStr="";
			$timeTableStr='<table width="100%" border="0" cellspacing="1" cellpadding="3"  id="anyid" class="timtd">';
			$timeTableStr .= '<tr class="rowheading">
				<td width="2%" valign="middle" style="font-size:12px" rowspan="2"><b>&nbsp;#</b>
				<td width="12%" valign="middle" style="font-size:12px" rowspan="2"><b>&nbsp;Univ. Roll No.</b>
				<td valign="middle" align="left" width="10%" style="font-size:12px" rowspan="2"><b>Roll No.</b></td>
				<td valign="middle" align="left" width="15%" style="font-size:12px" rowspan="2"><b>Student Name</b></td>
				';

				if(count($subjectArr)){

					foreach($subjectArr1 as $keySubject=>$keyValue){

						$timeTableStr .="<td align='center' ><b>".$subjectArr1[$keySubject]."</b></td>";
					}
					$timeTableStr .=' </tr><tr class="rowheading">';
					for($j=0;$j<count($subjectArr1);$j++){

						$timeTableStr .="<td  align='center'><b>Attendance</b></td>";
					}
					$timeTableStr .=' </tr>';
				}
			//$timeTableStr .='</table></td></tr>';
			$j=1;
			foreach($studentDetailArr1 as $arrayValue){

				$completeDetail = explode("~", $arrayValue);

				$bg = $bg =='row0' ? 'row1' : 'row0';
				$timeTableStr .= '<tr class="'.$bg.'">
				<td width="2%" valign="middle" style="font-size:12px">&nbsp;'.$j.'</td>
				<td width="12%" valign="middle" style="font-size:12px">&nbsp;'.$completeDetail[2].'</td>
				<td valign="middle" align="left" width="10%">&nbsp;'.$completeDetail[1].'</td>
				<td valign="middle" align="left" width="20%">&nbsp;'.$completeDetail[3].'</td>
				';

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

							$marksValue ='--&nbsp;';
						}
						$timeTableStr .="<td align='center'>".$marksValue."</td>";
					}
			  $timeTableStr .= '</tr>';
			  $j++;
			  $m++;
		  }
			$timeTableStr .='</table>';
			//echo $timeTableStr.'!~~!~~!'.$employeeList1;
		}


	}
	/** END Code to fetch Attendance for students **/

	/** START  Code to fetch Both Marks and  Attendance for students **/
	if($reportFor==0){

		$fetchMarksDetailArray = $studentReportsManager->getConsolidatedMarksDetails($conditions.$conditions1,$conditions2,$sortField,$sortOrderBy);
			//echo "<pre>";
//print_r($fetchMarksDetailArray);die;
		//$fetchAttDetailArray = $studentReportsManager->getConsolidatedAttendanceDetails($conditions,$conditions3,$sortField,$sortOrderBy);
        $fetchAttDetailArray = CommonQueryManager::getInstance()->getStudentOldAttendanceReport($attCondition,'',$conditions3,$orderBySubject);




		$cnt2 = count($fetchMarksDetailArray);
		$cnt3 = count($fetchAttDetailArray);
		$studentIdM = "";
		$studentIdA = "";
		for($i=0;$i<$cnt3;$i++){

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

				$array1[$fetchAttDetailArray[$i]['studentId']."~".$fetchAttDetailArray[$i]['subjectId']."~".$fetchAttDetailArray[$i]['subjectTypeId']]=($fetchAttDetailArray[$i]['lectureAttended']+$fetchAttDetailArray[$i]['leaveTaken']).'/'.$fetchAttDetailArray[$i]['lectureDelivered'];
			}
			else{

				$array1=array();
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
		if(count($subjectArr)){

			$subjectArr1 = array_values(array_unique($subjectArr));
			asort($subjectArr1);
		}
		if(count($subjectIdArr))
			$subjectIdArr1 = array_values(array_unique($subjectIdArr));

		if(count($studentDetailArr))
			$studentDetailArr1 = array_values(array_unique($studentDetailArr));
		$cnt1 = count($studentDetailArr1);
		$m=0;
		if($cnt1){

			$timeTableStr="";
			$timeTableStr='<table width="100%" border="0" cellspacing="2" cellpadding="0" class="">';
			$timeTableStr .= '<tr class="rowheading">
				<td width="2%" valign="middle" rowspan="2"><b>&nbsp;#</b>
				<td width="8%" valign="middle" rowspan="2"><b>&nbsp;Univ Reg No</b>
				<td valign="middle" align="left" width="8%" rowspan="2"><b>Roll No</b></td>
				<td valign="middle" align="left" width="15%" rowspan="2"><b>Student Name</b></td>
				';

				if(count($subjectArr)){


					foreach($subjectArr1 as $keySubject=>$keyValue){

						$timeTableStr .="<td colspan=2 align='center' ><b>".$subjectArr1[$keySubject]."</b></td>";
					}
					$timeTableStr .=' </tr><tr class="rowheading">';
					for($j=0;$j<count($subjectArr1);$j++){

						$timeTableStr .="<td  align='center'><b>Marks</b></td>";
						$timeTableStr .="<td align='center'><b>Att</b></td>";
					}
					$timeTableStr .=' </tr>';
				}
			//$timeTableStr .='</table></td></tr>';
			$j=1;
			foreach($studentDetailArr1 as $arrayValue){

				$completeDetail = explode("~", $arrayValue);

				$bg = $bg =='row0' ? 'row1' : 'row0';
				$timeTableStr .= '<tr class="'.$bg.'">
				<td valign="middle">&nbsp;'.$j.'</td>
				<td valign="middle">&nbsp;'.$completeDetail[2].'</td>
				<td valign="middle" align="left">'.$completeDetail[1].'</td>
				<td valign="middle" align="left">'.$completeDetail[3].'</td>
				';

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

							$marksValue ='--&nbsp;';
						}

						$attValue = $subjectAttValueArr[$keyValue];
						if($attValue==''){

							$attValue ='--&nbsp;';
						}

						$timeTableStr .="<td align='center' >".$marksValue."</td>";
						$timeTableStr .="<td align='center' >".$attValue."</td>";
					}
			  $timeTableStr .= '</tr>';
			  $j++;
			  $m++;
		  }
			$timeTableStr .='</table>';
			//echo $timeTableStr.'!~~!~~!'.$employeeList1;
		}
	}
	/** END Code to fetch Both Marks and  Attendance for students **/

	echo $timeTableStr.'!~~!~~!'.$employeeList1;

// $History: initConsolidatedReport.php $
//
//*****************  Version 6  *****************
//User: Gurkeerat    Date: 2/17/10    Time: 5:12p
//Updated in $/LeapCC/Library/StudentReports
//added access defines for management login
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 8/10/09    Time: 5:38p
//Updated in $/LeapCC/Library/StudentReports
//Gurkeerat: updated access defines
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 5/15/09    Time: 11:03a
//Updated in $/LeapCC/Library/StudentReports
//Updated Student consolidated report with live data
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 4/30/09    Time: 10:07a
//Updated in $/LeapCC/Library/StudentReports
//Updated formatting
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 4/29/09    Time: 7:19p
//Updated in $/LeapCC/Library/StudentReports
//changed variable name
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 4/29/09    Time: 6:22p
//Created in $/LeapCC/Library/StudentReports
//Intial checkin

?>
