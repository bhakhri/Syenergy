<style>
.imgLinkRemove{
    cursor: default;
}
</style>
<?php 
//This file is used as printing version for testwise marks report.
//
// Author :Rajeev Aggarwal
// Created on : 14-Aug-2008
// Copyright 2008-2009: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
    set_time_limit(0);  
	global $FE;
	require_once($FE . "/Library/common.inc.php");
	require_once(BL_PATH . "/UtilityManager.inc.php");
	UtilityManager::ifNotLoggedIn();
    UtilityManager::headerNoCache();

	require_once(MODEL_PATH . "/StudentReportsManager.inc.php");
	$studentReportsManager = StudentReportsManager::getInstance();

	require_once(BL_PATH . '/ReportManager.inc.php');
	$reportManager = ReportManager::getInstance();

	$timeTable = $REQUEST_DATA['timeTable'];
	$degree = $REQUEST_DATA['degree'];
	$testCategoryId = $REQUEST_DATA['testCategoryId'];
	$timeName = $REQUEST_DATA['timeName'];
	$className = $REQUEST_DATA['className']; 
	$categoryName = $REQUEST_DATA['categoryName'];
	$studentCheck = $REQUEST_DATA['studentCheck'];
    
    $signature = add_slashes($REQUEST_DATA['signature']);
    $address = add_slashes($REQUEST_DATA['address']);
    $photo = add_slashes($REQUEST_DATA['photo']);
    $signatureContents = add_slashes($REQUEST_DATA['signatureContents']);   
    

	$fromDate = $REQUEST_DATA['fromDate'];
	$toDate = $REQUEST_DATA['toDate'];

	$studentCheckArr = explode(",",$studentCheck);

	if($fromDate!='' AND $toDate!=''){

		$classFilter = "  AND testDate BETWEEN '".$fromDate."' AND '".$toDate."'";   
	}
	
	if($fromDate) {
			$where .= " AND fromDate BETWEEN '$fromDate' AND '$toDate'";
	}
	if($toDate) {
			$where .= " AND toDate BETWEEN '$fromDate' AND '$toDate'";
	} 
	 
    if($studentCheck=='') {
      $studentCheck=-1;
    } 
    
    if($degree=='') {
      $degree =-1;  
    }
    
    $notes='<b>Note:&nbsp;</b><i>This is a computer generated report and requires no signatures.</i>';  
    
    $studentDetailArray = $studentReportsManager->getStudentAddressPhoto($degree,$studentCheck);
                         
                         
	$subjectDetailArray = $studentReportsManager->getStudentArray(" AND stc.classId=$degree"); 
    $cnt1 = count($subjectDetailArray);
    
    
	foreach($studentCheckArr as $studentId){
    
       $fatherName='';
       $studentAddress ='';
       $studentPhoto = '';
       $displayAddress = '';
       $displayPhoto = '';
        
       if($address=='1' || $address=='2' || $photo=='1') {
            for($jj=0; $jj<count($studentDetailArray);$jj++) {
               if($studentDetailArray[$jj]['studentId']==$studentId) {
                  $fatherName=$studentDetailArray[$jj]['fatherName'];
                  $studentPhoto=$studentDetailArray[$jj]['studentPhoto'];
                  if($address=='1') {   
                     $studentAddress = $studentDetailArray[$jj]['corrAdd'];  
                  }
                  else if($address=='2') {   
                    $studentAddress = $studentDetailArray[$jj]['permAdd'];  
                  }
                  break;  
               }
            }
            if($address=='1' || $address=='2') {
               $displayAddress ='To,
                                    <div style="padding-left:60px;font-size:13px;width:38%">Mr. '.
                                    $fatherName.',<br>'.$studentAddress.'</div><br>';
            }
            if($photo=='1') {  
                if($studentPhoto != ''){ 
                    $File = STORAGE_PATH."/Images/Student/".$studentPhoto;
                    if(file_exists($File)){
                       $imgSrc= IMG_HTTP_PATH.'/Student/'.$studentPhoto."?stu=".rand(0,1000);
                    }
                    else{
                       $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                    }
                }
                else{
                  $imgSrc= IMG_HTTP_PATH."/notfound.jpg";
                }
                $displayPhoto = "<div style='margin-top:5px;'>
                                    <img src='".$imgSrc."' width='50' height='50' style='border:1px solid #cccccc' id='studentImageId' class='imgLinkRemove' />
                                 </div>";
            }
        }
		$marksDetailArray = $studentReportsManager->getStudentMarksArray(" AND ttc.testTypeCategoryId= $testCategoryId AND tt.classId=$degree AND tm.studentId=$studentId".$classFilter);
        $attendanceDetailArray = $studentReportsManager->getStudentAttendanceArray(" AND c.classId=$degree AND scs.studentId=$studentId".$where);
		//echo "<pre>";
		//print_r($attendanceDetailArray);
	?>	
	<table border="0" cellspacing="0" cellpadding="0" width="90%" align="center">
	<tr>
		<td align="left" colspan="1" width="25%" class=""><?php echo $reportManager->showHeader();?></td>
		<th align="center" colspan="1" width="50%" <?php echo $reportManager->getReportTitleStyle();?>><?php echo $reportManager->getInstituteName(); ?></th>
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
	<tr><th colspan="3" <?php echo $reportManager->getReportHeadingStyle(); ?> align="center"><B>SUBJECT: MID Session Academic Performance Report</B><br><br></th></tr>
    <tr>
        <th colspan="3" <?php echo $reportManager->getReportDataStyle(); ?> align="left">
            <?php 
                if($address=='1' || $address=='2' || $photo=='1') {
                    echo '<table border="0" width="100%" cellspacing="0" cellpadding="0"> 
                              <tr>
                                 <td align="left"  valign="top"  width="60%" nowrap>'.$displayAddress.'</td>
                                 <td align="right" valign="top"  width="40%" nowrap>'.$displayPhoto.'</td>
                              </tr>
                          </table>';
                }    
            ?>
            Your Ward <b><?php echo $attendanceDetailArray[0]['studentName']?></b>, college roll no:<b><?php echo $attendanceDetailArray[0]['rollNo']?></b>,a student of Class:<B><?php echo $className?></b> appeared in the <B><?php echo $categoryName?></b>.<br>The performance in the <?php echo $categoryName?> and the attendance status is given below:-
        </th>
    </tr>
	</table> <br>
	<table border='1' cellspacing='0' width='90%' class='reportTableBorder'  align='center'>
	<tr>
		<td valign='' <?php echo $reportManager->getFooterStyle();?>>Subject Code</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?>>Subject Name</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">Marks Obtained</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">Max Marks</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">%Marks</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">Classes Attended</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">Classes Held</td>
		<td valign='' <?php echo $reportManager->getFooterStyle();?> align="right">%Attended</td>
	</tr>
	<?php   
    
    $charArray= array();
    $m=0;
    for($i=0;$i<$cnt1;$i++) {  
        $totalMarksScored = NOT_APPLICABLE_STRING;
        $totalMaxMarks = NOT_APPLICABLE_STRING;
        $percentageValue = NOT_APPLICABLE_STRING;
        $totalAttended = NOT_APPLICABLE_STRING;
        $totalDelivered = NOT_APPLICABLE_STRING;
        $percentageAttValue = NOT_APPLICABLE_STRING; 
      
        for($ss=0;$ss<count($marksDetailArray);$ss++) {
            if($marksDetailArray[$ss]['subjectId']==$subjectDetailArray[$i]['subjectId'])  {
                
                $totalMaxMarks= $marksDetailArray[$ss][totalMaxMarks];
		        if($totalMaxMarks==''){
			      $totalMaxMarks=NOT_APPLICABLE_STRING;  
		        }
                
		        $totalMarksScored= $marksDetailArray[$ss][totalMarksScored];
		        if($totalMarksScored=='0'){
		          $totalMarksScored=NOT_APPLICABLE_STRING;  
		        }
                
		        if($totalMaxMarks==NOT_APPLICABLE_STRING || $totalMarksScored==NOT_APPLICABLE_STRING ) {
		          $percentageValue = '0';
		        }
		        else{
		          $percentageValue =number_format((($totalMarksScored/$totalMaxMarks)*100), 2, '.', ''); 
		        }
                break;
            }
         }

         for($ss=0;$ss<count($attendanceDetailArray);$ss++) {
            if($attendanceDetailArray[$ss]['subjectId']==$subjectDetailArray[$i]['subjectId'])  {
		        
                $totalAttended= $attendanceDetailArray[$ss][attended];
                if($totalAttended==''){
		          $totalAttended=NOT_APPLICABLE_STRING;
		        }
		        
                $totalDelivered= $attendanceDetailArray[$ss][delivered];
		        if($totalDelivered==''){
 		          $totalDelivered=NOT_APPLICABLE_STRING;
		        }

		        if($totalAttended==NOT_APPLICABLE_STRING  || $totalDelivered==NOT_APPLICABLE_STRING){
                  $percentageAttValue = '0';
		        }
		        else{
		          $percentageAttValue = number_format((($totalAttended/$totalDelivered)*100), 2, '.', '');
		        }
                break;   
            }
        }
        
		echo "<tr><td ".$reportManager->getReportDataStyle().">".$subjectDetailArray[$i][subjectCode]."</td>
			        <td ".$reportManager->getReportDataStyle().">".$subjectDetailArray[$i][subjectName]."</td> 
                    <td ".$reportManager->getReportDataStyle()." align='right'>".$totalMarksScored."</td>
			        <td ".$reportManager->getReportDataStyle()." align='right'>".$totalMaxMarks."</td>
			        <td ".$reportManager->getReportDataStyle()." align='right'>".$percentageValue."</td>
			        <td ".$reportManager->getReportDataStyle()." align='right'>".$totalAttended."</td>
			        <td ".$reportManager->getReportDataStyle()." align='right'>".$totalDelivered."</td>
			        <td ".$reportManager->getReportDataStyle()." align='right'>".$percentageAttValue."</td>
			   </tr>";
        $charArray[$m]['subjectName'] = $subjectDetailArray[$i]['subjectName'];
        $charArray[$m]['subjectCode'] = $subjectDetailArray[$i]['subjectCode'];
        $charArray[$m]['subjectId'] = $subjectDetailArray[$i]['subjectId'];   
        $charArray[$m]['percentage'] = 0;
        if($percentageValue!=NOT_APPLICABLE_STRING) {
          $charArray[$m]['percentage'] = $percentageValue;
        }
        $m++;
	}
	?>
	</table>
    <?php
    
     $chartResult ='';
     $chartResult1 ='';
     $chartResult2 ='';
     
     
     for($j=0;$j<count($charArray);$j++) {
        $color = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
        $percentage = $charArray[$j]['percentage'];               
        $subjectName = $charArray[$j]['subjectName'];
        if($percentage=='0') {
          $percentage1 = '';  
          $percentage  ='';
        } 
        else {
          $msg = "alt='$subjectName ($percentage%)' title='$subjectName ($percentage%)'";
          $per = (int)$percentage;
         /*
          if($per>=45) {
            $per = (int)$percentage-6; 
          }
          else if($per>10 && ($per%10)==0) {
            $per = (int)$percentage-2; 
          }
         */
          
          $perpx = (int)($per*250)/100;
          $style = "style='width:10px;height:$perpx;'"; 
          
          $img = "<img src='".HTTP_PATH."/Storage/Images/footer_midbar.gif' width='10px' height='$perpx' $msg>";
          $percentage1 = "<div>$percentage</div><div>$img</div>"; 
          $percentage2 = "$percentage 
                          <table border='1' $style cellpadding='0' cellspacing='0' valign='bottom'>
                            <tr>
                               <td  align='center' valign='bottom' $style >&nbsp;</td>
                            </tr>
                         </table>";
        }    
        $chartResult1 .= "<td ".$reportManager->getReportDataStyle()." rowspan='11' valign='bottom' height='100%' align='center'>$percentage1</td>";
        $chartResult2 .= "<td ".$reportManager->getReportDataStyle()." align='center'>".$charArray[$j]['subjectCode']."</td>";
     }
     $cntColSpan = '';
     if(count($charArray)>0) {
       $cntColSpan = "colspan='".(count($charArray)+1)."'";
     }
     
     $chartResult  =  "
     <table border='0' cellpadding='0' cellspacing='0'  width='80%' class='reportTableBorder'  align='center'>
     <td ".$reportManager->getReportDataStyle()." valign='middle' align='right' style='padding-right:10px'><b>%Marks</b></td> 
     <td>
         <table border='1' cellpadding='0' cellspacing='0'  width='100%' class='reportTableBorder'>";
         for($i=100;$i>=0;$i-=10) {
             $cc=$i;
             if($i==0) {
               $cc=1;  
             }
             $chartResult .="<tr>
                               <td valign='bottom' height='25px' ".$reportManager->getReportDataStyle()." >".$cc."</td>";
             if($i==100) {                    
              $chartResult .= $chartResult1;
             }
             $chartResult .= "</tr>";
         }
    
     $chartResult .= "<tr><td valign='bottom' ".$reportManager->getReportDataStyle()."></td>".$chartResult2."</tr>
                      </table>
                      </td>
                      </tr>
                      <tr><td colspan='2' ".$reportManager->getReportDataStyle()." align='center'><b>Subject Name</b></td></tr>
                     </table>";
    ?>
        
	<br><table border='0' cellspacing='0' width='90%' align='center'>
	<tr><th colspan="3" <?php echo $reportManager->getReportDataStyle(); ?> align="left">It is also informed that in case the attendance is below 75% of the aggregate scheduled periods, in each prescribed course of Theory(Lecture plus Tutorial) and Practical, The student will be detained in the Punjab Technical University Examination. </th></tr>
	 
	</table> 
    <br>
    <table border='0' cellspacing='0' width='90%' align='center'>
    <tr>
        <td height="20"></td>
    </tr>
    <tr>
        <?php echo $chartResult; ?>
    </tr>
    </table>	
	<br>
	<table border='0' cellspacing='0' width='90%' align='center'>
	<tr>
		<td height="20"></td>
	</tr>
    <tr>
        <td valign='' align='left' <?php echo $reportManager->getReportDataStyle();?>>
        <?php
            if($signature!='1') {
              echo $notes;  
            } 
            else {
              echo $signatureContents;  
            }  
        ?>
        </td>
    </tr>
    <tr>
        <td height="15"></td>
    </tr>
	<tr>
		<td valign='' align='left' <?php echo $reportManager->getFooterStyle();?>><?php echo $reportManager->showFooter();?></td>
	</tr>
	</table>
	<br class="page" />
	<?php
		}
	?>
	
	
<?php
	 
//$History : listTestWiseMarksReportPrint.php $
//
?>