<?php 
//This file creates output for "ListStudentReports " Module and provides the option for "export to CSV" and "Printout"
//
// Author :Arvind Singh Rawat
// Created on : 8-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------

    require_once(BL_PATH . "/UtilityManager.inc.php");
   
    global $bloodResults;    

    $includePreAdmission = $REQUEST_DATA['includePreAdmission'];  
    if($includePreAdmission=='') {
      $includePreAdmission='0';  
    }
    
    $incAll  = add_slashes($REQUEST_DATA['incAll']);   
    
    if($incAll=='') {
      $incAll=0;  
    }
    
   
    $formattedDate = date('d-M-y');//UtilityManager::formatDate($tillDate);             
    $conditionsArray = array();
    $qryString = "";
    $searchCrieria = "";
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
        $genName=" OR CONCAT(IFNULL(a.firstName,''), ' ',IFNULL(a.lastName,'')) LIKE '".$genName."%'";
    }  
  
    return $genName;
    }
    
    //Roll Number
    $rollNo = $REQUEST_DATA['rollNo'];
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
        $qryString.= "&rollNo=$rollNo";
        $searchCrieria .="Roll No:$rollNo ";
    }

    //Student Name
    $studentName = $REQUEST_DATA['studentName'];
    if (!empty($studentName)) {

         
        //$conditionsArray[] = " CONCAT(a.firstName, ' ', a.lastName) like '%$studentName%' ";
        $parsedName=parseName(trim($studentName));    //parse the name for compatibality
        $conditionsArray[] = " (
                                  CONCAT(IFNULL(a.firstName,''), ' ',IFNULL(a.lastName,'')) LIKE '%$studentName%' 
                                  $parsedName
                               )";
        $qryString.= "&studentName=$studentName";
        $searchCrieria .="studentName:$studentName ";
    }

    //Student Gender
    $gender = $REQUEST_DATA['gender'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
        $qryString .= "&gender=$gender";
        $gender1 = $gender=='M' ? "Male" : "Female";

        $searchCrieria .="Gender:$gender1 ";
    }

    //From Date of birth
    $birthDateF = $REQUEST_DATA['birthDateF'];
    $birthMonthF = $REQUEST_DATA['birthMonthF'];
    $birthYearF = $REQUEST_DATA['birthYearF'];

    if (!empty($birthDateF) && !empty($birthMonthF) && !empty($birthYearF)) {

        if (false !== checkdate($birthMonthF, $birthDateF, $birthYearF)) {
            $thisDate = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $thisDate1 = $birthYearF.'-'.$birthMonthF.'-'.$birthDateF;
            $conditionsArray[] = " a.dateOfBirth >= '$thisDate' ";
        }
        $qryString.= "&birthDateF=$birthDateF&birthMonthF=$birthMonthF&birthYearF=$birthYearF";
        $searchCrieria .="Date Of Birth:$thisDate";
    }

    //To Date of birth
    $birthDateT = $REQUEST_DATA['birthDateT'];
    $birthMonthT = $REQUEST_DATA['birthMonthT'];
    $birthYearT = $REQUEST_DATA['birthYearT'];

    if (!empty($birthDateT) && !empty($birthMonthT) && !empty($birthYearT)) {

        if (false !== checkdate($birthMonthT, $birthDateT, $birthYearT)) {
            $thisDate = $birthYearT.'-'.$birthMonthT.'-'.$birthDateT;
            $conditionsArray[] = " a.dateOfBirth <= '$thisDate' ";
        }
        $qryString.= "&birthDateT=$birthDateT&birthMonthT=$birthMonthT&birthYearT=$birthYearT";
         
        if($thisDate1)
            $toDate = " To: ";
        else
            $toDate ="";
        $searchCrieria .="$toDate$thisDate";
    }

    //degree
    $degs = $REQUEST_DATA['degs'];
    $degsText = $REQUEST_DATA['degsText'];
    if (!empty($degreeId)) {
        $conditionsArray[] = " b.degreeId in ($degs) ";
        $qryString.= "&degreeId=$degs";
        
    }
    $searchCrieria .=" Degree:$degsText";

    //branch
    $brans = $REQUEST_DATA['brans'];
    $bransText = $REQUEST_DATA['bransText'];
    if (!empty($brans)) {
        $conditionsArray[] = " b.branchId in ($brans) ";
        $qryString.= "&branchId=$brans";
    }
    $searchCrieria .=" Branches:$bransText";

    //periodicity
    $periods = $REQUEST_DATA['periods'];
    $periodsText = $REQUEST_DATA['periodsText'];
    if (!empty($periods)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periods) ";
        $qryString.= "&periodicityId=$periods";
    }
    $searchCrieria .=" StudyPeriod:$periodsText";
    
    //blood group
    $bloodGroup = $REQUEST_DATA['bloodGroup'];
    $bloodGroupText ="ALL";
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " a.studentBloodGroup IN ($bloodGroup) ";
        $qryString .= "&bloodGroup=$bloodGroup";
    }
    $searchCrieria .=" Blood Group:$bloodGroupText";
    
    //course
    $course = $REQUEST_DATA['courseId'];
    $courseText = $REQUEST_DATA['courseText'];
    if (!empty($course)) {
      $conditionsArray[] = " a.classId IN (SELECT DISTINCT(classId) FROM subject_to_class s WHERE s.subjectId IN ($course)) ";
      $qryString.= "&subjectId=$course";
    } 
    $searchCrieria .=" Subject:$courseText";

    //group
    $group = $REQUEST_DATA['group'];
    $groupText = $REQUEST_DATA['groupText'];
    if (!empty($group)) {
        $conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student_groups WHERE groupId IN ($group)) ";
        $qryString.= "&groupId=$group";
    }
    $searchCrieria .=" Group:$groupText";
    
    //university
    $univs = $REQUEST_DATA['univs'];
    $univsText = $REQUEST_DATA['univsText'];
    if (!empty($univs)) {
        $conditionsArray[] = " b.universityId IN ($univs) ";
        $qryString .= "&universityId=$univs";
    }
    $searchCrieria .=" University:$univsText";

    //city
    $citys = $REQUEST_DATA['citys'];
    $citysText = $REQUEST_DATA['citysText'];
    if (!empty($citys)) {
        $conditionsArray[] = " (a.corrCityId IN ($citys) OR  a.permCityId IN ($citys)) ";
        $qryString .= "&cityId=$citys";
    }
    $searchCrieria .=" City: $citysText";

    //states
    $states = $REQUEST_DATA['states'];
    $statesText = $REQUEST_DATA['statesText'];
    if (!empty($states)) {
        $conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
        $qryString .= "&stateId=$states";
    }
    $searchCrieria .=" State: $statesText";

    //country
    $cnts = $REQUEST_DATA['cnts'];
    $cntsText = $REQUEST_DATA['cntsText'];
    if (!empty($cnts)) {
        $conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts)) ";
        $qryString .= "&countryId=$cnts";
    }
    $searchCrieria .=" Country: $cntsText";

    //management category
    $categoryId = $REQUEST_DATA['categoryId'];
    if (!empty($categoryId)) {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
        $qryString .= "&categoryId=$categoryId";
        
    }
    if($categoryId=="0")
        $searchCrieria .=" Management Category:No";
    if($categoryId==1)
        $searchCrieria .=" Management Category:Yes";
    if($categoryId=="")
        $searchCrieria .=" Management Category:ALL";

    //From Admission Date
    $admissionDateF = $REQUEST_DATA['admissionDateF'];
    $admissionMonthF = $REQUEST_DATA['admissionMonthF'];
    $admissionYearF = $REQUEST_DATA['admissionYearF'];
    $thisDate1 ="";
    if (!empty($admissionDateF) && !empty($admissionMonthF) && !empty($admissionYearF)) {

        if (false !== checkdate($admissionMonthF, $admissionDateF, $admissionYearF)) {
            $thisDate = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
            $thisDate1 = $admissionYearF.'-'.$admissionMonthF.'-'.$admissionDateF;
            $conditionsArray[] = " a.dateOfAdmission >= '$thisDate' ";
        }
        $qryString.= "&admissionDateF=$admissionDateF&admissionMonthF=$admissionMonthF&admissionYearF=$admissionYearF";
        $searchCrieria .="Admission Date:$thisDate";
    }

    //To Admission Date
    $admissionDateT = $REQUEST_DATA['admissionDateT'];
    $admissionMonthT = $REQUEST_DATA['admissionMonthT'];
    $admissionYearT = $REQUEST_DATA['admissionYearT'];
     
    if (!empty($admissionDateT) && !empty($admissionMonthT) && !empty($admissionYearT)) {

        if (false !== checkdate($admissionMonthT, $admissionDateT, $admissionYearT)) {
            $thisDate = $admissionYearT.'-'.$admissionMonthT.'-'.$admissionDateT;
            $conditionsArray[] = " a.dateOfAdmission <= '$thisDate' ";
        }
        $qryString.= "&admissionDateT=$admissionDateT&admissionMonthT=$admissionMonthT&admissionYearT=$admissionYearT";
        if($thisDate1)
            $toDate = " To: ";
        else
            $toDate ="";
        $searchCrieria .="$toDate$thisDate";
    }

    //hostel
    $hostels = $REQUEST_DATA['hostels'];
    $hostelsText = $REQUEST_DATA['hostelsText'];
    if (!empty($hostels)) {
        $conditionsArray[] = " a.hostelId IN ('$hostels') ";
        $qryString .= "&hostelId=$hostels";
    }
    $searchCrieria .=" Hostel:$hostelsText";

    //bus stop
    $buss = $REQUEST_DATA['buss'];
    $bussText = $REQUEST_DATA['bussText'];
    if (!empty($buss)) {
        $conditionsArray[] = " a.busStopId IN ('$buss') ";
        $qryString .= "&busStopId=$buss";
    }
    $searchCrieria .=" Bus Stop:$bussText";

    //bus route
    $routs = $REQUEST_DATA['routs'];
    $routsText = $REQUEST_DATA['routsText'];
    if (!empty($routs)) {
        $conditionsArray[] = " a.busRouteId IN ($routs) ";
        $qryString .= "&busRouteId=$routs";
    } 
    $searchCrieria .=" Bus Route:$routsText";

    //quota
    $quotaId = $REQUEST_DATA['quotaId'];
    
    $quotaText ="ALL";
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
        $qryString .= "&quotaId=$quotaId";
        $quotaText = $REQUEST_DATA['quotaText'];
    }
    $searchCrieria .=" Category:$quotaText";
    
    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }



    if($sortField=="studyPeriod")
        $orderBy= "b.studyPeriodId $sortOrderBy"; 
   
   
    $valueArray = array();

//--------------------------------------------------------       
//Purpose:To escape any newline or comma present in data
//Author: Dipanjan Bhattacharee
//Date: 31.10.2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------   
function parseCSVComments($comments) {
    $comments = str_replace('"', '""', $comments);
    //$comments = str_ireplace('<br/>', "\n", $comments);
    $comments = str_ireplace('<br/>', " ", $comments);
    $comments = str_ireplace('<br>', " ", $comments);
    if(eregi(",", $comments) or eregi("\n", $comments)) {
      return '"'.$comments.'"'; 
    } 
    else {
      return $comments.chr(160); 
    }
}



   $cnter=0;
    $countRows = count($reportRecordArray);
    $reportTableHead    =    array();
        if($countRows>0) {
            $reportTableHead['srNo'] = array('#',  'width="1%" align="left"', "align='left' valign='top'");
            foreach($reportRecordArray[0] AS $listRecords => $listRecordvalue){
                    if($listRecords=='Photo'){
                    $cnter++;
                    $reportTableHead[$listRecords]                =    array($listRecords,  'width="4%" align="left"', "align='left' valign='top' ");
                    }
                    elseif($listRecords=='studentId'){

                    }
                    else if($listRecords=='Date of Admission' || $listRecords=='DOB') {
                       $cnter++;
                       $reportTableHead[$listRecords]    =  array($listRecords,  'width="4%" align="center"', "align='center' valign='top'");
                    }
                    else if($listRecords=='Marks in 10th' || $listRecords=='PG (if any)' || $listRecords=='Marks in 12th' || $listRecords=='Marks in Graduation' || $listRecords=='Any Diploma' ) {
                         
                       if($includePreAdmission=='1') {
                          
                          $ttId='';
                          if($listRecords=='Marks in 10th') {
                            $ttId='1';  
                          }                  
                          else if($listRecords=='Marks in 12th') {
                            $ttId='2';  
                          }                  
                          else if($listRecords=='Marks in Graduation') {
                            $ttId='3';  
                          }                  
                          else if($listRecords=='PG (if any)') {
                            $ttId='4';  
                          }                  
                          else if($listRecords=='Any Diploma') {
                            $ttId='5';  
                          }    
                           
                          $cnter++;
                          $reportTableHead['previousRollNo'.$ttId] =  array('Roll No',  'width="4%" align="left"', "align='left' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousSession'.$ttId] =  array('Session',  'width="4%" align="left"', "align='left' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousInstitute'.$ttId] =  array('Institute',  'width="4%" align="left"', "align='left' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousBoard'.$ttId] =  array('Board/University',  'width="4%" align="left"', "align='left' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousMarks'.$ttId] =  array("$listRecords",  'width="4%" align="right"', "align='right' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousMaxMarks'.$ttId] =  array('Max Marks',  'width="4%" align="right"', "align='right' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousPercentage'.$ttId] =  array('Percentage',  'width="4%" align="right"', "align='right' valign='top'");  
                          
                          $cnter++;
                          $reportTableHead['previousEducationStream'.$ttId] =  array('Education Stream',  'width="4%" align="left"', "align='left' valign='top'");  
                       }
                       else {
                         $cnter++;
                         $reportTableHead[$listRecords]    =  array($listRecords,  'width="4%" align="right"', "align='right' valign='top'"); 
                       }
                    }
                    else {
                       $cnter++;
                       $reportTableHead[$listRecords]    =    array($listRecords,  'width="4%" align="left"', "align='left' valign='top'");
                    }
            }
            
          
            
        $start = $REQUEST_DATA['start'];
        global $results;       
        for($i=0;$i<$countRows;$i++) {
               // add stateId in actionId to populate edit/delete icons in User Interface   
                
                if($REQUEST_DATA['studentPhoto']=='1') {
                  $reportRecordArray[$i]['Photo'] = "";
               }
                
               if($REQUEST_DATA['isLeet']=='1') {
                    if(trim($reportRecordArray[$i]['IsLeet'])=='1'){
                        $reportRecordArray[$i]['IsLeet']="Yes";
                    }
                    else {        
                        $reportRecordArray[$i]['IsLeet']="No";
                    }
               }
               
               if($includePreAdmission=='1') {  
                  if($REQUEST_DATA['mks_1']=='1' || $REQUEST_DATA['mks_2']=='1' || $REQUEST_DATA['mks_3']=='1' || $REQUEST_DATA['mks_4']=='1' || $REQUEST_DATA['mks_5']=='1') {
                      $ttId='';
                      if($REQUEST_DATA['mks_1']=='1') {
                        $ttId='1'; 
                        $mksId =  "Marks in 10th"; 
                        $previousExamMks = explode('!~!!~!',$reportRecordArray[$i]["$mksId"]);
                        $reportRecordArray[$i]['previousRollNo'.$ttId]=$previousExamMks[0]==''?NOT_APPLICABLE_STRING:$previousExamMks[0];
                        $reportRecordArray[$i]['previousSession'.$ttId]=$previousExamMks[1]==''?NOT_APPLICABLE_STRING:$previousExamMks[1];
                        $reportRecordArray[$i]['previousInstitute'.$ttId]=$previousExamMks[2]==''?NOT_APPLICABLE_STRING:$previousExamMks[2];
                        $reportRecordArray[$i]['previousBoard'.$ttId]=$previousExamMks[3]==''?NOT_APPLICABLE_STRING:$previousExamMks[3];
                        $reportRecordArray[$i]['previousMarks'.$ttId]=$previousExamMks[4]==''?NOT_APPLICABLE_STRING:$previousExamMks[4];
                        $reportRecordArray[$i]['previousMaxMarks'.$ttId]=$previousExamMks[5]==''?NOT_APPLICABLE_STRING:$previousExamMks[5];
                        $reportRecordArray[$i]['previousPercentage'.$ttId]=$previousExamMks[6]==''?NOT_APPLICABLE_STRING:$previousExamMks[6];
                        $reportRecordArray[$i]['previousEducationStream'.$ttId]=$previousExamMks[7]==''?NOT_APPLICABLE_STRING:$previousExamMks[7];
                      }                  
                      if($REQUEST_DATA['mks_2']=='1') {
                        $ttId='2';  
                        $mksId =  "Marks in 12th"; 
                        $previousExamMks = explode('!~!!~!',$reportRecordArray[$i]["$mksId"]);
                        $reportRecordArray[$i]['previousRollNo'.$ttId]=$previousExamMks[0]==''?NOT_APPLICABLE_STRING:$previousExamMks[0];
                        $reportRecordArray[$i]['previousSession'.$ttId]=$previousExamMks[1]==''?NOT_APPLICABLE_STRING:$previousExamMks[1];
                        $reportRecordArray[$i]['previousInstitute'.$ttId]=$previousExamMks[2]==''?NOT_APPLICABLE_STRING:$previousExamMks[2];
                        $reportRecordArray[$i]['previousBoard'.$ttId]=$previousExamMks[3]==''?NOT_APPLICABLE_STRING:$previousExamMks[3];
                        $reportRecordArray[$i]['previousMarks'.$ttId]=$previousExamMks[4]==''?NOT_APPLICABLE_STRING:$previousExamMks[4];
                        $reportRecordArray[$i]['previousMaxMarks'.$ttId]=$previousExamMks[5]==''?NOT_APPLICABLE_STRING:$previousExamMks[5];
                        $reportRecordArray[$i]['previousPercentage'.$ttId]=$previousExamMks[6]==''?NOT_APPLICABLE_STRING:$previousExamMks[6];
                        $reportRecordArray[$i]['previousEducationStream'.$ttId]=$previousExamMks[7]==''?NOT_APPLICABLE_STRING:$previousExamMks[7];
                      }                  
                      if($REQUEST_DATA['mks_3']=='1') {
                        $ttId='3';  
                        $mksId =  "Marks in Graduation"; 
                        $previousExamMks = explode('!~!!~!',$reportRecordArray[$i]["$mksId"]);
                        $reportRecordArray[$i]['previousRollNo'.$ttId]=$previousExamMks[0]==''?NOT_APPLICABLE_STRING:$previousExamMks[0];
                        $reportRecordArray[$i]['previousSession'.$ttId]=$previousExamMks[1]==''?NOT_APPLICABLE_STRING:$previousExamMks[1];
                        $reportRecordArray[$i]['previousInstitute'.$ttId]=$previousExamMks[2]==''?NOT_APPLICABLE_STRING:$previousExamMks[2];
                        $reportRecordArray[$i]['previousBoard'.$ttId]=$previousExamMks[3]==''?NOT_APPLICABLE_STRING:$previousExamMks[3];
                        $reportRecordArray[$i]['previousMarks'.$ttId]=$previousExamMks[4]==''?NOT_APPLICABLE_STRING:$previousExamMks[4];
                        $reportRecordArray[$i]['previousMaxMarks'.$ttId]=$previousExamMks[5]==''?NOT_APPLICABLE_STRING:$previousExamMks[5];
                        $reportRecordArray[$i]['previousPercentage'.$ttId]=$previousExamMks[6]==''?NOT_APPLICABLE_STRING:$previousExamMks[6];
                        $reportRecordArray[$i]['previousEducationStream'.$ttId]=$previousExamMks[7]==''?NOT_APPLICABLE_STRING:$previousExamMks[7];
                      }                  
                      if($REQUEST_DATA['mks_4']=='1') {
                        $ttId='4';  
                        $mksId =  "PG (if any)"; 
                        $previousExamMks = explode('!~!!~!',$reportRecordArray[$i]["$mksId"]);
                        $reportRecordArray[$i]['previousRollNo'.$ttId]=$previousExamMks[0]==''?NOT_APPLICABLE_STRING:$previousExamMks[0];
                        $reportRecordArray[$i]['previousSession'.$ttId]=$previousExamMks[1]==''?NOT_APPLICABLE_STRING:$previousExamMks[1];
                        $reportRecordArray[$i]['previousInstitute'.$ttId]=$previousExamMks[2]==''?NOT_APPLICABLE_STRING:$previousExamMks[2];
                        $reportRecordArray[$i]['previousBoard'.$ttId]=$previousExamMks[3]==''?NOT_APPLICABLE_STRING:$previousExamMks[3];
                        $reportRecordArray[$i]['previousMarks'.$ttId]=$previousExamMks[4]==''?NOT_APPLICABLE_STRING:$previousExamMks[4];
                        $reportRecordArray[$i]['previousMaxMarks'.$ttId]=$previousExamMks[5]==''?NOT_APPLICABLE_STRING:$previousExamMks[5];
                        $reportRecordArray[$i]['previousPercentage'.$ttId]=$previousExamMks[6]==''?NOT_APPLICABLE_STRING:$previousExamMks[6];
                        $reportRecordArray[$i]['previousEducationStream'.$ttId]=$previousExamMks[7]==''?NOT_APPLICABLE_STRING:$previousExamMks[7];
                      }                  
                      if($REQUEST_DATA['mks_5']=='1') {
                        $ttId='5';  
                        $mksId =  "Any Diploma"; 
                        $previousExamMks = explode('!~!!~!',$reportRecordArray[$i]["$mksId"]);
                        $reportRecordArray[$i]['previousRollNo'.$ttId]=$previousExamMks[0]==''?NOT_APPLICABLE_STRING:$previousExamMks[0];
                        $reportRecordArray[$i]['previousSession'.$ttId]=$previousExamMks[1]==''?NOT_APPLICABLE_STRING:$previousExamMks[1];
                        $reportRecordArray[$i]['previousInstitute'.$ttId]=$previousExamMks[2]==''?NOT_APPLICABLE_STRING:$previousExamMks[2];
                        $reportRecordArray[$i]['previousBoard'.$ttId]=$previousExamMks[3]==''?NOT_APPLICABLE_STRING:$previousExamMks[3];
                        $reportRecordArray[$i]['previousMarks'.$ttId]=$previousExamMks[4]==''?NOT_APPLICABLE_STRING:$previousExamMks[4];
                        $reportRecordArray[$i]['previousMaxMarks'.$ttId]=$previousExamMks[5]==''?NOT_APPLICABLE_STRING:$previousExamMks[5];
                        $reportRecordArray[$i]['previousPercentage'.$ttId]=$previousExamMks[6]==''?NOT_APPLICABLE_STRING:$previousExamMks[6];
                        $reportRecordArray[$i]['previousEducationStream'.$ttId]=$previousExamMks[7]==''?NOT_APPLICABLE_STRING:$previousExamMks[7];
                      }                  
                  }  
               }
               
                
                if($REQUEST_DATA['compExamBy']=='1') {   
                  if(trim($reportRecordArray[$i]['Comp. Exam. By'])==NOT_APPLICABLE_STRING){
                    $reportRecordArray[$i]['Comp. Exam. By']=NOT_APPLICABLE_STRING;
                  }
                  else {  
                     $id=$reportRecordArray[$i]['Comp. Exam. By']; 
                     $reportRecordArray[$i]['Comp. Exam. By']=$results[$id]; 
                     if($reportRecordArray[$i]['Comp. Exam. By']=='') {
                       $reportRecordArray[$i]['Comp. Exam. By']=NOT_APPLICABLE_STRING;   
                     }   
                  } 
                }
                
                if($REQUEST_DATA['bloodGroupForm']=='1') {
                    if($bloodResults[$reportRecordArray[$i]['Blood Group']]=='') {
                      $blood = NOT_APPLICABLE_STRING;
                    }
                    else {
                      $blood = $bloodResults[$reportRecordArray[$i]['Blood Group']];   
                    }
                    $reportRecordArray[$i]['Blood Group']=$blood;
                } 
                
                if($REQUEST_DATA['dateOfBirth']=='1') {   
                    if($reportRecordArray[$i]['DOB']=='' && $reportRecordArray[$i]['DOB']= NOT_APPLICABLE_STRING) {
                       $reportRecordArray[$i]['DOB']= NOT_APPLICABLE_STRING;
                    }
                    else {
                       $reportRecordArray[$i]['DOB']=UtilityManager::formatDate($reportRecordArray[$i]['DOB']);
                    }
                }

                if($REQUEST_DATA['dateOfAdmission']=='1') {  
				    if($reportRecordArray[$i]['Date of Admission']=='' OR $reportRecordArray[$i]['Date of Admission']== '0000-00-00') {
                       $reportRecordArray[$i]['Date of Admission']= NOT_APPLICABLE_STRING;
                    }
				    else {
				       $reportRecordArray[$i]['Date of Admission']=UtilityManager::formatDate($reportRecordArray[$i]['Date of Admission']);
				    }
                }
                
                $valueArray[] = array_merge(array('srNo' => ($start+$i) ),$reportRecordArray[$i]) ;  
            }
           // print_r($reportRecordArray);
            if(isset($REQUEST_DATA['groupIdForm'])){
            $z=-1;
            $oldId="";
            $chk=0;
            for($i=0;$i<$countRows;$i++){
                if($oldId == $valueArray[$i]['studentId']){
                    $chk++;
                    if($chk>1){
                        //$z=$z-1;
                    }
                    $newValueArray[$z]['Group'].= " ; ".$valueArray[$i]['Group'];
                }
                else{    
                    $z++;
                    $chk=0;    
                        foreach($valueArray[$i] AS $head => $value){
                            $serialNo=$z+1;
                            if($head == "studentId"){
                                
                            }
                            elseif($head==trim("srNo")){
                                $newValueArray[$z]['srNo']=$serialNo;
                            }
                            else{
                                $newValueArray[$z][$head]=$value;
                            }//echo $value;
                        }
                        
                    }
                $oldId = $valueArray[$i]['studentId'];
                }    
            }
     }
 
   $csvData = '';
   
   $querySearch="Students Data Report";
   if($incAll==1) {
      $querySearch="Missing Students Data Report";
      $formattedDate .= " '---' Missing Data \n";
   }
   
   $csvData .= "$querySearch \n For $searchCrieria As On $formattedDate \n";
   
    foreach($reportTableHead as $head => $record){
      $csvData .= $record[0].',';
    }
    
    $csvData=substr(trim($csvData),0,-1);
    $csvData .="\n";
    if(isset($REQUEST_DATA['groupIdForm'])){
        $cnt=count($newValueArray);
        for($i=0;$i<$cnt;$i++){
            foreach($newValueArray[$i] as $head => $record) {
              //$record = $record=='' ? NOT_APPLICABLE_STRING : $record;
              $notIncluded='';
              if($includePreAdmission=='1') { 
                if($head=='Marks in 10th' || $head=='Marks in 12th' || $head=='Marks in Graduation' || $head=='PG (if any)' || $head=='Any Diploma') {
                   $notIncluded='1';
                }
              }
              if($notIncluded=='') {
                $csvData .= parseCSVComments($record).','; 
              }
            }
            $csvData .= "\n";   
        }
    }
    else{
        $cnt=count($valueArray);
        for($i=0;$i<$cnt;$i++){
            foreach($valueArray[$i] as $head => $record) {
              //$record = $record=='' ? NOT_APPLICABLE_STRING : $record;    
              $notIncluded='';
              if($includePreAdmission=='1') { 
                if($head=='Marks in 10th' || $head=='Marks in 12th' || $head=='Marks in Graduation' || $head=='PG (if any)' || $head=='Any Diploma') {
                   $notIncluded='1';
                }
              }
              if($notIncluded=='') {
                $csvData .= parseCSVComments($record).','; 
              }
            }
            $csvData .= "\n";   
        }
    }         
  
 //print_r($csvData);
require_once(BL_PATH . "/UtilityManager.inc.php");
UtilityManager::makeCSV($csvData, 'studentListsReport.csv');
die;
   
//$History: listStudentListPrintCSV.php $
//
//*****************  Version 9  *****************
//User: Parveen      Date: 12/24/09   Time: 11:01a
//Updated in $/LeapCC/Templates/StudentReports
//parseCSVComments function updated
//
//*****************  Version 8  *****************
//User: Parveen      Date: 12/23/09   Time: 6:39p
//Updated in $/LeapCC/Templates/StudentReports
//role permission check & format updated
//
//*****************  Version 7  *****************
//User: Parveen      Date: 12/02/09   Time: 1:20p
//Updated in $/LeapCC/Templates/StudentReports
//student Status field checks updated
//
//*****************  Version 6  *****************
//User: Parveen      Date: 11/23/09   Time: 4:28p
//Updated in $/LeapCC/Templates/StudentReports
//blood group check updated
//
//*****************  Version 5  *****************
//User: Parveen      Date: 7/11/09    Time: 12:13p
//Updated in $/LeapCC/Templates/StudentReports
//new enhacments added (bloodGroup, regNo, className added)
//
//*****************  Version 4  *****************
//User: Parveen      Date: 2/23/09    Time: 5:00p
//Updated in $/LeapCC/Templates/StudentReports
//issue fix
//
//*****************  Version 3  *****************
//User: Parveen      Date: 2/23/09    Time: 12:38p
//Updated in $/LeapCC/Templates/StudentReports
//issue fix
//
//*****************  Version 2  *****************
//User: Parveen      Date: 2/21/09    Time: 5:32p
//Updated in $/LeapCC/Templates/StudentReports
//bug fix
//
//*****************  Version 1  *****************
//User: Parveen      Date: 2/21/09    Time: 2:42p
//Created in $/LeapCC/Templates/StudentReports
//initial checkin
//

?>
