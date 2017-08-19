<?php 
//This file creates Html Form output in Notice Module 
//
// Author :Rajeev Aggarwal
// Created on : 15-10-2008
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
                <td valign="top">Institute Notices&nbsp;&raquo;&nbsp;Notice Detail</td>
                <td valign="top" align="right">   
                <form action="" method="" name="searchForm">
					<input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />&nbsp;
					<img src="<?php echo IMG_HTTP_PATH; ?>/search.gif" value="Search" name="submit" style="margin-bottom: -5px;"    onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" ></img> &nbsp;  
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
                        <td class="content_title">Notice Detail :</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" ><div id="results">
				<table width="100%" border="0" cellspacing="0" cellpadding="0"  >
				<tr class="rowheading">
					<td width="3%"><b>#</b></td>
					<td width="30%" class="searchhead_text"><b>Subject </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
					<td width="20%" class="searchhead_text"><b>Department </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
					<td width="10%" class="searchhead_text"><b>Visible From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleFromDate')" /></td>
					 <td width="10%" class="searchhead_text"><b>Visible To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleToDate')" /></td>
					 <td width="8%" class="searchhead_text"><b>Attachment</b></td> 
					 <td width="4%" class="searchhead_text"><b>Detail</b></td> 
				 </tr>
				 <?php  
					$recordCount = count($noticeRecordArray);
					if($recordCount >0 && is_array($noticeRecordArray) ) { 
					
					   //  $j = $records;
						for($i=0; $i<$recordCount; $i++ ) {
							
							$bg = $bg =='row0' ? 'row1' : 'row0';
							$attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
							$pic=split('_-',strip_slashes($noticeRecordArray[$i]['noticeAttachment']));   
							 echo '<tr class="'.$bg.'">
								<td  class="padding_top">'.($records+$i+1).'</td>
								<td class="padding_top" >'.trim_output(strip_slashes($noticeRecordArray[$i]['noticeSubject']),100).'</td>
								<td class="padding_top" >'.strip_slashes($noticeRecordArray[$i]['departmentName']).'</td>
								<td class="padding_top" >'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])).'</td>
								<td class="padding_top" >'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])).'</td>
							<td class="padding_top" >';
							   if(isset($pic[0])){   ?>
						<img src="<?php echo IMG_HTTP_PATH ?>/download.gif" title="<?php echo $pic[1]; ?>" onclick="download('<?php echo $attactment?>')" /> 
								<?php
								}
								else{
									echo "--";
								}
								echo ' </td> 
								<td width="100" class="padding_top" align="center" valign="top"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onclick="showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].',\'divNotice\',650,350);"/></td> 
							</tr>';
						
						}
				if($totalArray[0]['totalRecords']>=RECORDS_PER_PAGE) {
						
							$bg = $bg =='row0' ? 'row1' : 'row0';
							require_once(BL_PATH . "/Paging.php");
							$paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
							echo '<tr><td colspan=2>Total Records:'.$paging->ajaxGetTotalRecords().'</td><td colspan="6" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td> </tr>'   ;              
		
						}
					}
					else{
						echo '<tr><td colspan="6" align="center">No record found</td></tr>';
					} 
					?>                 
					</table></div>           
				</td>
			</tr>
          </table>
        </td>
    </tr>
    </table>
    
<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice ','',''); ?>
<form name="NoticeForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeSubject" style="overflow:auto; width:550px;" ></div></td>
    </tr>
	<tr>
        <td width="11%" valign="top" align="right"><nobr><b>Department: &nbsp;</b></nobr></td>
        <td width="89%"><div id="noticeDepartment" style="width:300px; height:20px"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Notice: &nbsp;</b></nobr></td>
        <td><div id="noticeText" style="overflow:auto; width:550px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td valign="top" align="right"><nobr><b>From: &nbsp;</b></nobr></td>
        <td><div id="visibleFromDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>

    <tr>    
        <td valign="top" align="right"><nobr><b>To: &nbsp;</b></nobr></td>
        <td><div id="visibleToDate" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td>
	</tr>
</table>
</form> 
<?php floatingDiv_End(); ?>
 
<?php 
//$History: listNoticeContents.php $
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 09-09-04   Time: 4:11p
//Updated in $/LeapCC/Templates/Management
//Updated Popup div formatting which was due to implementation of new
//theme
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Updated in $/LeapCC/Templates/Management
//Updated as per CC functionality
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/21/08   Time: 1:04p
//Updated in $/Leap/Source/Templates/Management
//updated div popup formatting
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:28p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>