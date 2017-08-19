<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Institute List 
//
//
// Author :Dipanjan Bhattacharjee 
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
                <td valign="top">Notices &nbsp;&raquo;&nbsp;Display Institute Notices</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="image"  name="submit" src="<?php echo IMG_HTTP_PATH;?>/search.gif" title="Search"   style="margin-bottom: -5px;" onClick="sendReq(listURL,divResultName,searchFormName,'');return false;"/>&nbsp;
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
                    <td width="2%" class="searchhead_text">&nbsp;&nbsp;<b>#</b></td>
                    <td width="25%" class="searchhead_text"><b>Notice</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="15%" class="searchhead_text"><b>Subject</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=noticeSubject')" /></td>
                    <td width="5%" class="searchhead_text"><b>From</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleFromDate')" /></td>
                    <td width="5%" class="searchhead_text"><b>To</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=visibleToDate')" /></td>
                    <td width="5%" class="searchhead_text"><b>Attachment</b></td>
                    <td width="5%" class="searchhead_text" align="right"><b>Detail</b></td>
                    
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($noticeRecordArray);//print_r($noticeRecordArray);
                if($recordCount >0 && is_array($noticeRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                       $attactment=strip_slashes($noticeRecordArray[$i]['noticeAttachment']);
                    $pic=split('_-',strip_slashes($noticeRecordArray[$i]['noticeAttachment']));   
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeText']))).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output(HtmlFunctions::getInstance()->removePHPJS($noticeRecordArray[$i]['noticeSubject']))).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleFromDate'])).'</td>
                        <td class="padding_top" valign="top">'.UtilityManager::formatDate(strip_slashes($noticeRecordArray[$i]['visibleToDate'])).'</td>
                        <td class="padding_top" >';
                       if(isset($pic[1])){   ?>
                        <img src="<?php echo IMG_HTTP_PATH ?>/download.gif" title="<?php echo $pic[1]; ?>" onclick="download('<?php echo $attactment?>')" /> 
                        <?php
                        }        
                        
                        echo ' </td>
                        <td width="100" class="padding_top" align="right" valign="top"><img src="'.IMG_HTTP_PATH.'/zoom.gif" border="0" alt="Details" onClick="return showNoticeDetails('.$noticeRecordArray[$i]['noticeId'].');"/></td>
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


<!--Start Notice  Div-->
<?php floatingDiv_Start('divNotice','Notice '); ?>
<form name="NoticeForm" action="" method="post">  
 <?php
 //THIS Code IS USED TO DISPLAY Notice Div     
 // Author :Dipanjan Bhattacharjee 
// Created on : (22.07.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 ?>
 <!--<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows" ><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding" >
         <textarea id="noticeSubject" cols="20" rows="2" readonly="true"></textarea>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Notice : </b></nobr></td>
        <td width="79%" class="padding" >
         <textarea id="noticeText" cols="20" rows="4" readonly="true"></textarea>
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>From : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleFromDate" name="visibleFromDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>To: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleToDate" name="visibleToDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('divNotice');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>           -->

 <?php
// The Above Code is modified as below 
//
//
// Author :Arvind Singh Rawat 
// Created on : (18.10.2008)
// Copyright 2008-2000: Chalkpad Technologies Pvt. Ltd.
//
//--------------------------------------------------------
 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows" ><nobr><b>Subject : </b></nobr></td>
        <td width="79%" class="padding" >
      <!--   <textarea id="noticeSubject" cols="20" rows="2" readonly="true"></textarea>  -->
         <div id="noticeSubject" style="overflow:auto; width:500px; height:15px" >  </div> 
        </td>
    </tr>
    <tr>
        <td width="21%" valign="top" align="right"><nobr><b>Notice : </b></nobr></td>
        <td width="79%" valign="top">
       <!--  <textarea id="noticeText" cols="20" rows="4" readonly="true"></textarea>     -->
        <div id="noticeText" style="overflow:auto; width:500px; height:400px" >  </div>  
        </td>
    </tr>
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>From : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleFromDate" name="visibleFromDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>To: </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="visibleToDate" name="visibleToDate" class="inputbox" style="border:0px" readonly="true" /></td>
    </tr>
    
<tr>
    <td height="5px"></td></tr>
    <!--
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
       <input type="image" name="editclose_icon" src="<?php echo IMG_HTTP_PATH;?>/close_icon.gif"  onclick="javascript:hiddenFloatingDiv('divNotice');return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>     -->
</table>
</form> 
<?php floatingDiv_End(); ?>    

<?php
// $History: scListInstituteNoticeContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Arvind       Date: 10/18/08   Time: 1:59p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//modified the display of notice div
//
//*****************  Version 3  *****************
//User: Arvind       Date: 10/07/08   Time: 12:09p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//added noticeAttachment download option
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 9/29/08    Time: 12:22p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/10/08    Time: 6:37p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 9/09/08    Time: 5:19p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 7  *****************
//User: Dipanjan     Date: 9/09/08    Time: 4:53p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 6  *****************
//User: Dipanjan     Date: 9/09/08    Time: 1:58p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 5  *****************
//User: Dipanjan     Date: 9/02/08    Time: 3:40p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 9/01/08    Time: 11:34a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//Corrected the problem of header row of tables(which displays the list )
//which is taking much
//vertical space in IE.
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 8/25/08    Time: 1:07p
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 8/09/08    Time: 11:24a
//Updated in $/Leap/Source/Templates/Teacher/TeacherActivity
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 7/22/08    Time: 6:57p
//Created in $/Leap/Source/Templates/Teacher/TeacherActivity
//Initial Checkin
?>