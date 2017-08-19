<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
// Author :Rajeev Aggarwal
// Created on : (15.10.2008)
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
                <td valign="top">Institute Events &nbsp;&raquo;&nbsp;Event Detail</td>
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
                    <td width="2%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="10%" class="searchhead_text"><b>Event</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="25%" class="searchhead_text"><b>Short Desc</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=shortDescription')" /></td>
                    <td width="35%" class="searchhead_text"><b>Long Desc</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=longDescription')" /></td>
                    <td width="5%" class="searchhead_text"><b>From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=startDate')" /></td>
                    <td width="5%" class="searchhead_text"><b>To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=endDate')" /></td>
                    <td width="5%" class="searchhead_text" align="right"><b>Detail</b></td> 
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($eventRecordArray);
                if($recordCount >0 && is_array($eventRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['eventTitle']))).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['shortDescription']))).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($eventRecordArray[$i]['longDescription']))).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['startDate'])).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($eventRecordArray[$i]['endDate'])).'</td>
                        <td width="100" class="padding_top" align="right" valign="top"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onclick="showEventDetails('.$eventRecordArray[$i]['eventId'].',\'divEvent\',650,350);return false;"/></td>
                        </tr>';
                    }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="7" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="7" align="center">No record found</td></tr>';
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

<?php floatingDiv_Start('divEvent','Event '); ?>
<form name="EventForm" action="" method="post">  
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border" align="center">
	<tr>
		<td height="5px"></td></tr>
	<tr>
    <tr>
        <td width="20%" valign="top" align="right"><nobr><b>Event: &nbsp;</b></nobr></td>
        <td width="80%"><div id="eventTitle" style="overflow:auto; width:300px; height:20px" ></div></td>
    </tr>
    <tr>
    <td colspan="2" valign="top" align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
        <td width="20%" align="right" valign="top"><nobr><b>From: &nbsp;</b></nobr></td>
        <td width="15%" valign="top" align="left" nowrap><div id="startDate" style="width:30px; height:20px"></div></td>
        <td width="5%" valign="top"><nobr><b>To: &nbsp;</b></nobr></td>
        <td valign="top" align="left" nowrap><div id="endDate" style="width:30px; height:20px"></div></td>
      </tr>
     </table>
    </td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(S): &nbsp;</b></nobr></td>
        <td valign="top"><div id="shortDescription" style="overflow:auto; width:500px; height:20px" ></div></td>
    </tr>
    <tr>
        <td valign="top" align="right"><nobr><b>Description(L): &nbsp;</b></nobr></td>
        <td  valign="top"><div id="longDescription" style="overflow:auto; width:500px; height:200px" ></div></td>
    </tr>
	<tr>
		<td height="5px"></td></tr>
	<tr>
</table>
</form>
<?php floatingDiv_End(); ?>

<?php
// $History: scListInstituteEventContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Management
//
//*****************  Version 3  *****************
//User: Rajeev       Date: 10/22/08   Time: 11:53a
//Updated in $/Leap/Source/Templates/Management
//updated with validations for mangement role
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