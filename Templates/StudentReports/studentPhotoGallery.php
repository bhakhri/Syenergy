<?php 
//-------------------------------------------------------
// Purpose: to design the layout for student photo gallery.
//
// Author : Jaineesh
// Created on : (08.07.2008 )
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
			   //DISPLAYS ALL RECORDS 
               require_once($FE . "/Library/common.inc.php");
               global $sessionHandler;
                $studentCount = count($studentArray);

				$i = 0;
			if($studentCount >0 && is_array($studentArray)) {
				foreach($studentArray as $studentRecord){
					$j=$i+1;
					if($i %2 == 0) {
						?><table border="1" rules="ALL" cellpadding="0" cellspacing="3"><tr><?php
					} ?>
					<td><table border="0" cellpadding="0" cellspacing="0" width="500" style="font-size:10px">
								<tr><td rowspan="6" width="60px"><?php if(strip_slashes($studentArray[$i]['studentPhoto']=="")){echo '<table rules="all" border="1" width="60px" height="70px"><tr><td></td></tr></table>';
										 }
									else {
											echo "<img src=\"".IMG_HTTP_PATH."/Student/".strip_slashes($studentArray[$i]['studentPhoto'])."\" height=\"70px\" width=\"60px\" >";
										 } ?></td></tr>
								<tr>
									<td style="padding-left:5px" width="100px"><strong>Roll No.</strong></td>
									<td width="120px"><?php echo strip_slashes($studentArray[$i]['rollNo'])?></td>
									<td style="padding-right:5px" width="120px" ><strong>Univ. Roll No.</strong></td>
				                    <td><?php echo strip_slashes($studentArray[$i]['universityRollNo'])?></td>
								</tr>
								<tr>
									<td colspan="4" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px"><b>Name of Student</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['firstName']))?>  <?php echo strtoupper(strip_slashes($studentArray[$i]['lastName']))?></td>
									<td style="padding-left:5px"><b>Father Name</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['fatherName']))?> </td>
								</tr>
								<tr>
									<td colspan="4" height="5px"></td>
								</tr>
								<tr>
									<td style="padding-left:5px"><b>Permanent Address</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['permAddress1']))?>  <?php echo strtoupper(strip_slashes($studentArray[$i]['permAddress2']))?></td>
									<td style="padding-left:5px"><b>Corr. Address</b></td>
									<td><?php echo strtoupper(strip_slashes($studentArray[$i]['corrAddress1']))?>  <?php echo strtoupper(strip_slashes($studentArray[$i]['corrAddress2']))?></td>
								</tr>

							</table></td>
							<?php 
								$i++; 
								if($i%4 == 0) { 
									?></tr></table>
								<?php if($j%10==0) { ?>
									<BR CLASS='page'>
								<?php } ?>
							<?php
								}
							}
						}
						else { 
							echo '<table><tr><td colspan="5" align="center">No record found</td></tr></table>';
						}?> 
    
  


<?php 
// $History: studentPhotoGallery.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/StudentReports
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 8/29/08    Time: 11:06a
//Updated in $/Leap/Source/Templates/StudentReports
//modification in design
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 8/18/08    Time: 3:43p
//Updated in $/Leap/Source/Templates/StudentReports
//modification in template
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 8/16/08    Time: 4:46p
//Updated in $/Leap/Source/Templates/StudentReports
//modified for report student information
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 8/07/08    Time: 8:06p
//Updated in $/Leap/Source/Templates/StudentReports
//

?>
    <!--End: Div To Edit The Table-->
    


