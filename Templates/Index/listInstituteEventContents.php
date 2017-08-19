<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
//
// Author :Rajeev Aggarwal
// Created on : (13.08.2008)
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
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
                <td valign="top">Dashboard &nbsp;&raquo;&nbsp;Display Institute Events</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
				  <img src="<?php echo IMG_HTTP_PATH; ?>/search.gif" value="Search" name="submit" style="margin-bottom: -5px;"    onClick="sendReq(listURL,divResultName,searchFormName,'');return false;" >

                  &nbsp;
                  </form>
                  </td>
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
                        <td class="content_title">Institute Events : </td>
                        <td class="content_title" >&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                <div id="results">  
                 <table width="100%" border="0" cellspacing="0" cellpadding="0"  id="anyid">
                 <tr class="rowheading">
                    <td width="2%">&nbsp;&nbsp;<b>#</b></td>
                    <td width="20%" class="searchhead_text"><b>Event</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="55%" class="searchhead_text"><b>Short Desc</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=shortDescription')" /></td>
                   
                    <td width="7%" class="searchhead_text"><b>From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=startDate')" /></td>
                    <td width="7%" class="searchhead_text"><b>To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=endDate')" /></td>
					 <td width="5%" class="searchhead_text"><b>Action</b></td>
                 </tr>
                <?php
                $recordCount = count($eventRecordArray);
                if($recordCount >0 && is_array($eventRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                    $bg = $bg =='row0' ? 'row1' : 'row0';
                    echo '<tr class="'.$bg.'">
                        <td valign="middle" >'.($records+$i+1).'</td>
                        <td valign="middle">'.strip_slashes($eventRecordArray[$i]['eventTitle']).'</td>
                        <td valign="middle">'.strip_slashes($eventRecordArray[$i]['shortDescription']).'</td>
                        <td valign="middle">'.UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate'])).'</td>
                        <td valign="middle">'.UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate'])).'</td>
						<td align="center"><a href="#" title="View Details"><img src="'.IMG_HTTP_PATH.'/zoom.gif"  border="0" onClick="editWindow('.$eventRecordArray[$i]['eventId'].',\'ViewEvents\',400,400); return false;"/></a>&nbsp;&nbsp;</td>
                        </tr>';
               }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          
						  $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="6" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    
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
    </td>
    </tr>
    </table>
<?php floatingDiv_Start('ViewEvents','Event Description'); ?>
<form name="viewNotices" action="" method="post"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
<tr>
    <td height="5px"></td>
</tr>
<tr>
<tr>
	<td width="100%" align="left" style="padding-left:5px"><B><div id="innerEvent"></div></B>
</td>
<tr>
    <td height="5px"></td>
</tr>
<tr>
<tr>
	<td width="100%" align="left" style="padding-left:5px"><div id="innerEventDesc" style="overflow:auto; width:300px; height:200px" ></div></td>
</tr>
<tr>
    <td align="center" style="padding-right:5px" ><input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/close_icon.GIF" title="Close Window" onclick="javascript:hiddenFloatingDiv('ViewEvents');return false;" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End();  


 
// $History: listInstituteEventContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Index
//
//*****************  Version 6  *****************
//User: Rajeev       Date: 9/29/08    Time: 1:50p
//Updated in $/Leap/Source/Templates/Index
//updated formatting
//
//*****************  Version 5  *****************
//User: Rajeev       Date: 9/05/08    Time: 6:22p
//Updated in $/Leap/Source/Templates/Index
//updated icons for zoom and close
//
//*****************  Version 4  *****************
//User: Rajeev       Date: 9/04/08    Time: 2:12p
//Updated in $/Leap/Source/Templates/Index
//updated formatting for ajax based list
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 9/04/08    Time: 12:57p
//Updated in $/Leap/Source/Templates/Index
//updated the formatting and made floating div for event description
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 9/02/08    Time: 8:05p
//Updated in $/Leap/Source/Templates/Index
//updated file with bug fixes
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:36p
//Created in $/Leap/Source/Templates/Index
//intial checkin
?>