<?php
//This file creates output for "ListStudentReports " Module and provides the option for "export to CSV" and "Printout"
//
// Author :Arvind Singh Rawat
// Created on : 8-July-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
     $countHeader=count($REQUEST_DATA);
     $countHeader= $countHeader-24;
?>


<!--    <table  <?php if($countHeader > 6){echo "width='1200'"; ob_start();}
    else{echo "width='1000'";ob_start(); } ?> border="0" cellspacing="0" cellpadding="0" >
        <tr align='center' width="90%" >
            <td align='center' ><br /><h3>Students  Lists Report</h3></td>
            <td align="right" width="10%">
<div id="printing" style="display;block">
            <?php if($REQUEST_DATA['id']=='excel' || $REQUEST_DATA['id']=='print'){  }else{ ?>
            <input type="image" name="excel"  src="<?php echo IMG_HTTP_PATH;?>/excel1.jpg"  onclick="location.href='<?php echo UI_HTTP_PATH;?>/exportStudentListToCsv.php?<?php echo $querystring;?>&act=excel'" />
            <input type="image" name="print"  src="<?php echo IMG_HTTP_PATH;?>/print.jpg"  onClick="printout()" />
            <?php } ?>
</div>
            </td>
        </tr>
    <tr>
    <td colspan="2" >
       -->
<?php
    require_once(BL_PATH . "/UtilityManager.inc.php");
    require_once(BL_PATH . '/ReportManager.inc.php');
    $reportManager = ReportManager::getInstance();

    
    $includePreAdmission = $REQUEST_DATA['includePreAdmission'];  
    if($includePreAdmission=='') {
      $includePreAdmission='0';  
    }
    
    $incAll  = add_slashes($REQUEST_DATA['incAll']);

    if($incAll=='') {
      $incAll=0;
    }

    $formattedDate = date('d-M-y'); //UtilityManager::formatDate($tillDate);
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

        $genName=" OR CONCAT(TRIM(a.firstName),' ',TRIM(a.lastName)) LIKE '".$genName."%'";
    }

    return $genName;
    }

    //Roll Number
    $rollNo = $REQUEST_DATA['rollNo'];
    if (!empty($rollNo)) {
        $conditionsArray[] = " a.rollNo LIKE '$rollNo%' ";
        $qryString.= "&rollNo=$rollNo";
        $searchCrieria .="<b>Roll No</b>:$rollNo&nbsp;";
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
        $searchCrieria .="<b>studentName</b>:$studentName&nbsp;";
    }

    //Student Gender
    $gender = $REQUEST_DATA['gender'];
    if (!empty($gender)) {
        $conditionsArray[] = " a.studentGender = '$gender' ";
        $qryString .= "&gender=$gender";
        $gender1 = $gender=='M' ? "Male" : "Female";

        $searchCrieria .="<b>Gender</b>:$gender1&nbsp;";
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
        $searchCrieria .="<b>Date Of Birth</b>:$thisDate";
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
        $searchCrieria .="<b>$toDate</b>$thisDate";
    }

    //degree
    $degs = $REQUEST_DATA['degs'];
    $degsText = $REQUEST_DATA['degsText'];
    if (!empty($degreeId)) {
        $conditionsArray[] = " b.degreeId in ($degs) ";
        $qryString.= "&degreeId=$degs";

    }
    $searchCrieria .="&nbsp;<b>Degree:</b>$degsText";

    //branch
    $brans = $REQUEST_DATA['brans'];
    $bransText = $REQUEST_DATA['bransText'];
    if (!empty($brans)) {
        $conditionsArray[] = " b.branchId in ($brans) ";
        $qryString.= "&branchId=$brans";
    }
    $searchCrieria .="&nbsp;<b>Branches:</b>$bransText";

    //periodicity
    $periods = $REQUEST_DATA['periods'];
    $periodsText = $REQUEST_DATA['periodsText'];
    if (!empty($periods)) {
        $conditionsArray[] = " b.studyPeriodId IN ($periods) ";
        $qryString.= "&periodicityId=$periods";
    }
    $searchCrieria .="&nbsp;<b>StudyPeriod:</b>$periodsText";

    //blood group
    global $bloodResults;

    $bloodGroup = $bloodResults[$REQUEST_DATA['bloodGroup']];
    $bloodGroupText ="ALL";
    if (!empty($bloodGroup)) {
        $conditionsArray[] = " a.studentBloodGroup IN ($bloodGroup) ";
        $qryString .= "&bloodGroup=$bloodGroup";
        $bloodGroupText = $REQUEST_DATA['bloodGroupText'];
    }
    $searchCrieria .="&nbsp;<b>Blood Group:</b>$bloodGroupText";

    //course
    $course = $REQUEST_DATA['courseId'];
    $courseText = $REQUEST_DATA['courseText'];
    if (!empty($courseId)) {
        $conditionsArray[] = " a.classId IN (SELECT DISTINCT(classId) FROM subject_to_class s WHERE s.classId = a.classId AND s.subjectId IN ($course)) ";
        $qryString.= "&subjectId=$course";
    }
    $searchCrieria .="&nbsp;<b>Subject:</b>$courseText";

    //group
    $group = $REQUEST_DATA['groupId'];
    $groupText = $REQUEST_DATA['groupText'];
    if (!empty($group)) {
        $conditionsArray[] = " a.studentId IN (SELECT DISTINCT(studentId) FROM student_groups sg WHERE
                                sg.classId = a.classId AND sg.studentId = a.studentId AND sg.groupId IN ($group)) ";
        $qryString.= "&groupId=$group";
    }
    $searchCrieria .="&nbsp;<b>Group:</b>$groupText";

    //university
    $univs = $REQUEST_DATA['univs'];
    $univsText = $REQUEST_DATA['univsText'];
    if (!empty($univs)) {
        $conditionsArray[] = " b.universityId IN ($univs) ";
        $qryString .= "&universityId=$univs";
    }
    $searchCrieria .="&nbsp;<b>University:</b>$univsText";


    //city
    $citys = $REQUEST_DATA['citys'];
    $citysText = $REQUEST_DATA['citysText'];
    if (!empty($citys)) {
        $conditionsArray[] = " (a.corrCityId IN ($citys)) ";
        $qryString .= "&cityId=$citys";
    }
    $searchCrieria .="&nbsp;<b>City:</b>$citysText";

    //states
    $states = $REQUEST_DATA['states'];
    $statesText = $REQUEST_DATA['statesText'];
    if (!empty($states)) {
        $conditionsArray[] = " (a.corrStateId IN ($states) OR a.permStateId IN ($states)) ";
        $qryString .= "&stateId=$states";
    }
    $searchCrieria .="&nbsp;<b>State:</b>$statesText";

    //country
    $cnts = $REQUEST_DATA['cnts'];
    $cntsText = $REQUEST_DATA['cntsText'];
    if (!empty($cnts)) {
        $conditionsArray[] = " (a.corrCountryId IN ($cnts) OR a.permCountryId IN ($cnts)) ";
        $qryString .= "&countryId=$cnts";
    }
    $searchCrieria .="&nbsp;<b>Country:</b>$cntsText";



    //management category
    $categoryId = $REQUEST_DATA['categoryId'];
    if (!empty($categoryId)) {
        $conditionsArray[] = " a.managementCategory = $categoryId ";
        $qryString .= "&categoryId=$categoryId";

    }
    if($categoryId=="0")
        $searchCrieria .="&nbsp;<b>Management Category:</b>No";
    if($categoryId==1)
        $searchCrieria .="&nbsp;<b>Management Category:</b>Yes";
    if($categoryId=="")
        $searchCrieria .="&nbsp;<b>Management Category:</b>ALL";

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
        $searchCrieria .="<b>Admission Date</b>:$thisDate";
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
        $searchCrieria .="<b>$toDate</b>$thisDate";
    }

    //hostel
    $hostels = $REQUEST_DATA['hostels'];
    $hostelsText = $REQUEST_DATA['hostelsText'];
    if (!empty($hostels)) {
        $conditionsArray[] = " a.hostelId IN ('$hostels') ";
        $qryString .= "&hostelId=$hostels";
    }
    $searchCrieria .="&nbsp;<b>Hostel:</b>$hostelsText";

    //bus stop
    $buss = $REQUEST_DATA['buss'];
    $bussText = $REQUEST_DATA['bussText'];
    if (!empty($buss)) {
        $conditionsArray[] = " a.busStopId IN ('$buss') ";
        $qryString .= "&busStopId=$buss";
    }
    $searchCrieria .="&nbsp;<b>Bus Stop:</b>$bussText";

    //bus route
    $routs = $REQUEST_DATA['routs'];
    $routsText = $REQUEST_DATA['routsText'];
    if (!empty($routs)) {
        $conditionsArray[] = " a.busRouteId IN ($routs) ";
        $qryString .= "&busRouteId=$routs";
    }
    $searchCrieria .="&nbsp;<b>Bus Route:</b>$routsText";

    //quota
    $quotaId = $REQUEST_DATA['quotaId'];

    $quotaText ="ALL";
    if (!empty($quotaId)) {
        $conditionsArray[] = " a.quotaId IN ($quotaId) ";
        $qryString .= "&quotaId=$quotaId";
        $quotaText = $REQUEST_DATA['quotaText'];
    }
    $searchCrieria .="&nbsp;<b>Category:</b>$quotaText";

    $conditions = '';
    if (count($conditionsArray) > 0) {
        $conditions = ' AND '.implode(' AND ',$conditionsArray);
    }



    if($sortField=="studyPeriod")
        $orderBy= "b.studyPeriodId $sortOrderBy";

// Arvind Coding
        $valueArray = array();
   /*    for($i=0;$i<$cnt;$i++) {
            // add stateId in actionId to populate edit/delete icons in User Interface
            if(trim($reportRecordArray[$i]['Photo'])=='Photo'){
            echo "hello";
            }
            $valueArray[] = array_merge(array('srNo' => ($records+$i+1) ),$reportRecordArray[$i]);
         }
         print_r($valueArray[0]);
   */
   //echo "<pre>";
   //  print_r($reportRecordArray);
   //die;
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

        global $results;
	    $start = $REQUEST_DATA['start'];
        for($i=0;$i<$countRows;$i++) {
               /* add stateId in actionId to populate edit/delete icons in User Interface
                  if(trim($reportRecordArray[$i]['Photo'])!=''){
                    $reportRecordArray[$i]['Photo']="<img src=\"".STUDENT_PHOTO_PATH."/".stripslashes($reportRecordArray[$i]['Photo'])."\" height=\"64px\" width=\"64px\" valign=\"middle\" >";
                  } 
               */

                $find=0;
                if($REQUEST_DATA['studentPhoto']=='1') {
                  $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                  if($reportRecordArray[$i]['Photo'] != ''){
                     $File = STORAGE_PATH."/Images/Student/".$reportRecordArray[$i]['Photo'];
                     if(file_exists($File)){
                        $imgSrc= IMG_HTTP_PATH.'/Student/'.$reportRecordArray[$i]['Photo']."?y=".rand(0,1000);
                     }
                  }
                  $reportRecordArray[$i]['Photo'] = "<img src=\"".$imgSrc."\" height=\"64px\" width=\"64px\" valign=\"middle\" >";
                  $find=1;
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

            /*    if(trim($reportRecordArray[$i]['Hostel'])=='1'){
                    $reportRecordArray[$i]['Hostel']="Availed";
                }
                else {
                    $reportRecordArray[$i]['Hostel']="Not Availed";
                }
                if(trim($reportRecordArray[$i]['Bus'])=='1'){
                    $reportRecordArray[$i]['Bus']="Availed";
                }
                else {
                    $reportRecordArray[$i]['Bus']="Not Availed";
                }
            */

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
                    $newValueArray[$z]['Group'].= ",".$valueArray[$i]['Group'];
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

//    $classId=$classIdArray[0]['classId'];
//    $classNameArray = $studentReportsManager->getSingleField('class', 'substring_index(className,"-",-3) as className', "where classId  = $classId");
//    $className = $classNameArray[0]['className'];
//    $className2 = str_replace(CLASS_SEPRATOR,' ',$className);
       $width=800;
/*     if($cnter == 1 || $cnter==2){
          $width=500;
     }
     if($cnter ==3 || $cnter==4){
          $width=600;
     }
*/

    $reportManager->setReportWidth($width);

    $querySearch="Students Data Report";
    if($incAll==1) {
      $querySearch="Missing Students Data Report";
      $formattedDate .= " <br> '---' Missing Data";
    }
    $reportManager->setReportHeading($querySearch);

    $reportManager->setReportInformation("For ".$searchCrieria."<br> As On $formattedDate");

    if($find==0) {
      $reportManager->setRecordsPerPage(RECORDS_PER_PAGE);
    }
    else {
       $reportManager->setRecordsPerPage(12);
    }
    if(isset($REQUEST_DATA['groupIdForm'])){
        $reportManager->setReportData($reportTableHead, $newValueArray);
    }
    else{
        $reportManager->setReportData($reportTableHead, $valueArray);
    }
    $reportManager->showReport();
    die;
?>








