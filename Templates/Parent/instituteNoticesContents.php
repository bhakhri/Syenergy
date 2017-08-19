<?php 
//it contain the template of Institute Notices on student activities 
//
// Author :Jaineesh
// Created on : 22-07-2008
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td valign="top">Student Activites &nbsp;&raquo;&nbsp; Institute Notices </td>
					<td valign="top" align="right">   
				   <form action="" method="" name="searchForm">
		  
					  </form>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
				<tr>
				 <td valign="top" class="content">
				 <table width="100%" border="0" cellspacing="0" cellpadding="0">
				 <tr>
					<td class="contenttab_border" height="20">
					<!--	<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title">Institute Notices :</td>
						 
						</tr>
						</table> -->
					</td>
				 </tr>
				 <tr>
					<td class="contenttab_row" valign="top" ><div id="results">
		<table width="100%" border="0" cellspacing="1" cellpadding="1"  id="anyid">
			 <tr class="rowheading">
				<td width="10%" style="padding-left:15px"><b>#</b></td>
				<td width="20%" class="searchhead_text"><b>Subject</b></td>
				<td width="20%" class="searchhead_text"><b>Department Name</b></td>
				<td width="30%" class="searchhead_text"><b>Notice</b></td>
				<td width="10%" class="searchhead_text" align="center"><b>Attachment</b></td>
				<td width="10%" class="searchhead_text" align="center" ><b>Detail</b></td>
			   
			 </tr>
         <?php  
                
				$recordCount = count($studentRecordArray);
				if($recordCount >0 && is_array($studentRecordArray) ) { 
                   //  $j = $records;
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" style="padding-left:15px">'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.trim_output(strip_slashes(strip_tags($studentRecordArray[$i]['noticeSubject'])),35).'</td>
						<td class="padding_top" valign="top">'.strip_slashes($studentRecordArray[$i]['departmentName']).'</td>
						<td class="padding_top" valign="top">'.trim_output(strip_slashes(strip_tags($studentRecordArray[$i]['noticeText'])),35).'</td>';
						//<td class="padding_top" valign="top">';
						$fileName = IMG_PATH."/Notice/".$studentRecordArray[$i]['noticeAttachment'];
							if(file_exists($fileName) && ($studentRecordArray[$i]['noticeAttachment']!="")){

								$fileName1 = IMG_HTTP_PATH."/Notice/".$studentRecordArray[$i]['noticeAttachment'];
									echo '<td valign="top" align="center"><a href="'.$fileName1.'" target="_blank" title="'.$title.'"><img src="'.IMG_HTTP_PATH.'/download.gif"></a></td>';
							}
						else {
							echo '<td align="center">'.NOT_APPLICABLE_STRING.'</td>';
						}

					echo '<td  class="searchhead_text1" align="center" ><a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif" width="15" border="0" onClick="editWindow('.strip_slashes($studentRecordArray[$i]['noticeId']).',\'ViewNotices\',600,600); return false;"/></a>&nbsp;&nbsp;</td>';
                        
                    echo '</tr>';
                   }

				   if($totalNoticesArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalNoticesArray[0]['totalRecords']);
                          echo '<tr><td colspan="6" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    } 
                }
                else {
                    echo '<tr><td colspan="5" align="center">No record found</td></tr>';
                }
                ?>                 
                 </table></div>           
             </td>
          </tr>
          
          </table>
        </td>
    </tr>
    
    </table>
<?php floatingDiv_Start('ViewNotices','Notice Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewNotices" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Subject </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerNotice" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Detail </b></td>
</tr>

<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerText" style="overflow:auto; width:580px; height:200px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
	
<?php
//$History: instituteNoticesContents.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:26p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 12/09/08   Time: 5:45p
//Updated in $/LeapCC/Templates/Student
//modification in code for cc
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 17  *****************
//User: Jaineesh     Date: 11/04/08   Time: 6:21p
//Updated in $/Leap/Source/Templates/Student
//Put the attachment in ajax file also
//
//*****************  Version 16  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:38a
//Updated in $/Leap/Source/Templates/Student
//remove unsortable class from sr. no.
//
//*****************  Version 15  *****************
//User: Jaineesh     Date: 10/22/08   Time: 11:37a
//Updated in $/Leap/Source/Templates/Student
//remove the close button from notice contents
//
//*****************  Version 14  *****************
//User: Jaineesh     Date: 10/21/08   Time: 3:21p
//Updated in $/Leap/Source/Templates/Student
//modified in size of div
//
//*****************  Version 13  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 12  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//remove the html tags through strip_tags function
//
//*****************  Version 11  *****************
//User: Jaineesh     Date: 10/17/08   Time: 1:47p
//Updated in $/Leap/Source/Templates/Student
//modified for attachment
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 9/12/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Student
//bug fixed
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 9/11/08    Time: 6:34p
//Updated in $/Leap/Source/Templates/Student
//modify for paging
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 9/10/08    Time: 7:53p
//Updated in $/Leap/Source/Templates/Student
//put paging
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 9/06/08    Time: 6:43p
//Updated in $/Leap/Source/Templates/Student
//fixation bugs
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:19p
//Updated in $/Leap/Source/Templates/Student
//modification in dimming div size
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/03/08    Time: 4:06p
//Updated in $/Leap/Source/Templates/Student
//modification for view detail
//
//*****************  Version 3  *****************
//User: Administrator Date: 9/01/08    Time: 1:27p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 8/02/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Student
//modification in template
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/25/08    Time: 12:46p
//Created in $/Leap/Source/Templates/Student
//contain the template of institute notice for student
//
//


?>
