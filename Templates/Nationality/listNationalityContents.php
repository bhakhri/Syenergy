<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR NATIONALITY LISTING 
//
//
// Author :Dipanjan Bhattacharjee 
// Created on : (12.06.2008 )
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Global Masters&nbsp;&raquo;&nbsp;Nationality Master</td>
                <td valign="top" align="right">
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
                  <input type="submit" value="Search" name="submit"  class="button" style="margin-bottom: 3px;" />&nbsp;
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
                        <td class="content_title">Nationality Detail : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddNationality',315,250);return false;" />&nbsp;</td>
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top" >
                 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="sortable" id="anyid">
                 <tr class="rowheading">
                    <td width="50" class="searchhead_text sortable">&nbsp;&nbsp;<b>#</b></td>
                    <td width="650" class="searchhead_text"><b>Nationality</b></td>
                    <td width="10%" class="unsortable" align="right"><b>Action</b></td>
                 </tr>
                <?php
                $recordCount = count($nationalityRecordArray);
                if($recordCount >0 && is_array($nationalityRecordArray) ) { 
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($nationalityRecordArray[$i]['nationName']).'</td>
                        <td width="100" class="searchhead_text1" align="right">
                         <a href="#" title="Edit">
                          <img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$nationalityRecordArray[$i]['nationId'].',\'EditNationality\',315,250); return false;"/>
                         </a>
                          &nbsp;&nbsp;
                         <a href="'.$_SERVER['PHP_SELF'].'?nationId='.$nationalityRecordArray[$i]['nationId'].'&act=del" title="Delete" onClick="return confirm(\'Do you want to delete this record?\');return false;">
                          <img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete"/>
                         </a>
                        </td>
                        </tr>';
                    }
                }
                ?>                 
                 </table>            
             </td>
          </tr>
       <?php
        if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
              require_once(BL_PATH . "/Paging.php");
              $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
              echo '<tr class="'.$bg.'"><td class="paging" align="right">'.$paging->printLinks('fontText').'</td></tr>';                   
        }
        else {
            echo '<tr><td class="paging">&nbsp;</td></tr>';
        }
        ?>
            </table>
        </td>
    </tr>
    
    </table>
    </td>
    </tr>
    </table>
    <!--Start Add Div-->

<?php floatingDiv_Start('AddNationality','Add Nationality'); ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <form name="addNationality" action="" method="post">  
    <tr>
          <td width="40%" class="contenttab_internal_rows"><strong>Nationality:</strong></td>
          <td width="60%" class="padding">
           <input type="text" id="nationName" class="nationName" style="width:200px"  class="inputbox" />
          </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
      <img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" width="90" height="28" onclick="javascript:hiddenFloatingDiv('AddNationality');" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</form>
</table>
<?php floatingDiv_End(); ?>
<!--End Add Div-->

<!--Start Edit Div-->
<?php floatingDiv_Start('EditNationality','Edit Nationality'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <form name="editNationality" action="" method="post">  
    <input type="hidden" name="nationId" id="nationId" value="" />  
    <tr>
          <td width="40%" class="contenttab_internal_rows"><strong>Nationality:</strong></td>
          <td width="60%" class="padding">
           <input type="text" id="nationName" class="nationName" style="width:200px"  class="inputbox" />
          </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" />
      <img src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" width="90" height="28" onclick="javascript:hiddenFloatingDiv('EditNationality');" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</form>
</table>
    <?php floatingDiv_End(); ?>
    <!--End: Div To Edit The Table-->


<?php
// $History: listNationalityContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Nationality
//
//*****************  Version 2  *****************
//User: Dipanjan     Date: 6/13/08    Time: 11:02a
//Updated in $/Leap/Source/Templates/Nationality
//Modifying Comments Complete
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 6/12/08    Time: 7:59p
//Created in $/Leap/Source/Templates/Nationality
//Initial Checkin
?>