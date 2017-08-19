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
                <td valign="top">Dashboard&nbsp;&raquo;&nbsp;Display Institute Notices</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
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
                        <td class="content_title">Institute Notices : </td>
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
                    <td width="2%" class="unsortable">&nbsp;&nbsp;<b>#</b></td>
                    <td width="25%" class="searchhead_text"><b>Notice</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="15%" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=noticeSubject')" /></td>
                    <td width="5%" class="searchhead_text"><b>From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleFromDate')" /></td>
                    <td width="5%" class="searchhead_text"><b>To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleToDate')" /></td>
                 </tr>
                <?php
                $recordCount = count($noticeRecordArray);
                if($recordCount >0 && is_array($noticeRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($noticeRecordArray[$i]['noticeText']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($noticeRecordArray[$i]['noticeSubject']).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])).'</td>
                        </tr>';
                    }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
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
    </td>
    </tr>
    </table>
<?php
// $History: listInstituteNoticeContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Index
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 8/13/08    Time: 6:25p
//Created in $/Leap/Source/Templates/Index
//intial checkin
?>