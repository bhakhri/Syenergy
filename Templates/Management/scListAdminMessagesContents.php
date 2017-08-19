<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Admin messages
//
//
// Author :Rajeev Aggarwal
// Created on : (22.07.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
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
                <td valign="top">Notices &nbsp;&raquo;&nbsp;Messages</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                    <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;"  onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
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
                        <td class="content_title">Messages : </td>
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
                    <td width="3%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>   
                    <td width="100" class="searchhead_text"><b>Sender </b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="200" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=subject')" /></td>
                    <td width="400" class="searchhead_text"><b>Synopsis</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=message')" /></td>
                    <td width="100" class="searchhead_text"><b>Dated</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=dated')" /></td>
                    <td width="100" class="searchhead_text" align="right"><b>Details</b></td>
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($msgRecordArray);
                if($recordCount >0 && is_array($msgRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($msgRecordArray[$i]['userName']).'</td>
                        <td class="padding_top" valign="top">'.trim_output(strip_slashes(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['subject'])),200,1).'</td>
                        <td class="padding_top" valign="top">'.trim_output(strip_slashes(strip_tags(HtmlFunctions::getInstance()->removePHPJS($msgRecordArray[$i]['message']))),700).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($msgRecordArray[$i]['dated'])).'</td>
                        <td width="100" class="padding_top" align="right" valign="top"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onclick="showMessageDetails('.$msgRecordArray[$i]['messageId'].',\'divMessage\',650,250);return false;"/></td>
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

<!--Start Message  Div-->
<?php floatingDiv_Start('divMessage','Message '); ?>
<form name="MessageForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="11%" valign="top" align="right"><nobr><b>Subject: &nbsp;</b></nobr></td>
        <td width="89%"><div id="subject" style="overflow:auto; width:550px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Message: &nbsp;</b></nobr></td>
        <td><div id="message" name="message" style="height:200px;width:550px;overflow:auto"></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Dated: &nbsp;</b></nobr></td>
        <td width="79%"><div id="dated" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form> 
<?php floatingDiv_End(); ?>

<?php
// $History: scListAdminMessagesContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/20/08   Time: 3:40p
//Updated in $/Leap/Source/Templates/Management
//updated with new pie charts
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/18/08   Time: 11:28a
//Updated in $/Leap/Source/Templates/Management
//updated div popup format
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 10/15/08   Time: 5:28p
//Created in $/Leap/Source/Templates/Management
//intial checkin
?>