<?php 
//it contain the template of event comments on student activities 
//
// Author :Jaineesh
// Created on : 02-09-2008
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
                <td valign="top">Student Info &nbsp;&raquo;&nbsp; Insitute Events</td>
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
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
						<tr>
							<td class="content_title">Event Detail :</td>
						</tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
    <table width="100%" border="0" cellspacing="1" cellpadding="1"  id="anyid">
         <tr class="rowheading2">
            <td width="6%" style="padding-left:15px"><b>#</b></td>
            <td width="45%" class="searchhead_text"><b>Title</b></td>
			<td width="40%" class="searchhead_text"><b>Short Description</b></td>
			<td width="10%" class="searchhead_text"><b>Action</b></td>
			
         </tr>
         <?php  
                $recordCount = count($instituteRecordArray);
                if($recordCount >0 && is_array($instituteRecordArray) ) { 
                   //  $j = $records;
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" style="padding-left:15px">'.($records+$i+1).'</td>
						<td class="padding_top" valign="top">'.trim_output(strip_slashes(strip_tags($instituteRecordArray[$i]['eventTitle'])),35).'</td>
						<td class="padding_top" valign="top">'.trim_output(strip_slashes(strip_tags($instituteRecordArray[$i]['shortDescription'])),35).'</td>


						<td  class="searchhead_text1" align="right" style="padding-right:50px"><a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif" width="15" border="0" onClick="editWindow('.$instituteRecordArray[$i]['eventId'].',\'ViewEvents\',600,600); return false;"/></a>&nbsp;&nbsp;</td>

                        </tr>';
                   }
                    if($totalEventsArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalEventsArray[0]['totalRecords']);
                          echo '<tr><td colspan="5" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
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

<?php floatingDiv_Start('ViewEvents','Event Description'); ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<form name="viewEvents" action="" method="post"> 
<tr>
    <td height="5px"></td></tr>
<tr>
<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Title </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerNotice" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Short Description </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="innerDescription" style="overflow:auto; width:380px;" ></div><br>
	</td>
</tr>

<tr>
   <td width="100%"  align="Left" class="rowheading">&nbsp;<b>Long Description </b></td>
</tr>
<tr>
	<td width="100%"  align="left" style="padding-left:10px">
	<br />
	<div id="longDescription" style="overflow:auto; width:580px; height:100px" ></div>
	</td>
</tr>

<tr>
    <td height="5px"></td>
</tr>

   </form>
</table>

<?php floatingDiv_End(); ?>
	
<?php
//$History: instituteEventContents.php $
//
//*****************  Version 1  *****************
//User: Parveen      Date: 7/01/09    Time: 6:26p
//Created in $/LeapCC/Templates/Parent
//file added
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 1/13/09    Time: 6:28p
//Updated in $/LeapCC/Templates/Student
//modified for left alignment and giving cell padding, cell spacing 1
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Student
//
//*****************  Version 10  *****************
//User: Jaineesh     Date: 11/04/08   Time: 10:38a
//Updated in $/Leap/Source/Templates/Student
//remove unsortable class from sr. no.
//
//*****************  Version 9  *****************
//User: Jaineesh     Date: 10/21/08   Time: 3:21p
//Updated in $/Leap/Source/Templates/Student
//modified in size of div
//
//*****************  Version 8  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:09p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 7  *****************
//User: Jaineesh     Date: 10/17/08   Time: 5:01p
//Updated in $/Leap/Source/Templates/Student
//modified
//
//*****************  Version 6  *****************
//User: Jaineesh     Date: 10/17/08   Time: 4:32p
//Updated in $/Leap/Source/Templates/Student
//remove the html tags through strip_tags function
//
//*****************  Version 5  *****************
//User: Jaineesh     Date: 9/18/08    Time: 7:43p
//Updated in $/Leap/Source/Templates/Student
//modified for institute event
//
//*****************  Version 4  *****************
//User: Jaineesh     Date: 9/16/08    Time: 7:09p
//Updated in $/Leap/Source/Templates/Student
//fix bug
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 9/12/08    Time: 7:16p
//Updated in $/Leap/Source/Templates/Student
//modification
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 9/12/08    Time: 6:51p
//Updated in $/Leap/Source/Templates/Student
//bug fixed
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 9/12/08    Time: 2:31p
//Created in $/Leap/Source/Templates/Student
//to show the template of Institute Event
//
//*****************  Version 4  *****************
//User: Administrator Date: 9/01/08    Time: 1:27p
//Updated in $/Leap/Source/Templates/Student
//
//*****************  Version 3  *****************
//User: Jaineesh     Date: 8/12/08    Time: 7:37p
//Updated in $/Leap/Source/Templates/Student
//modified in template
//
//*****************  Version 2  *****************
//User: Jaineesh     Date: 7/23/08    Time: 7:41p
//Updated in $/Leap/Source/Templates/Student
//contain the teacher comments
//
//*****************  Version 1  *****************
//User: Jaineesh     Date: 7/23/08    Time: 10:14a
//Created in $/Leap/Source/Templates/Student
//contain header, footer, menu and templates
//


?>
