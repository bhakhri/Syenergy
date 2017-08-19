<?php 
// This file creates Html Form output in Branch Module 
//
// Author :Rajeev Aggarwal
// Created on : 12-12-2008
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
                <td valign="top">Setup&nbsp;&raquo;&nbsp;Branch Detail</td>
                <td valign="top" align="right">   
                <form action="" method="" name="searchForm">
                <input type="text" name="searchbox" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-bottom: 2px;" size="30" />
                  &nbsp;
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
                        <td class="content_title">Branch Details :</td>
                        
                    </tr>
                    </table>
                </td>
             </tr>
             <tr>
                <td class="contenttab_row" valign="top"><div id="results"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
         <tr class="rowheading">
                       <td width="3%" ><b>#</b></td>
                      <td width="85%" class="searchhead_text"><strong>Name</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                      <td width="12%"  class="searchhead_text"><strong>Abbr</strong><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=branchCode')" /></td>
                      
                      </tr>
         <?php  
                $recordCount = count($branchRecordArray);
                if($recordCount >0 && is_array($branchRecordArray) ) { 
                    // $j = $records;
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                        
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($branchRecordArray[$i]['branchName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($branchRecordArray[$i]['branchCode']).'</td>
                         
                        
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
<?php floatingDiv_Start('AddBranch','Add Branch'); ?>
<form name="addBranch" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
 
<tr>
    <td width="21%" class="contenttab_internal_rows"><nobr><b> Name : </b></nobr></td>
    <td width="79%" style="width:100px" class="padding"><input type="text" id="branchName" name="branchName" class="inputbox" maxlength="50" /></td>
</tr>
<tr>    
    <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr : </b></nobr></td>
    <td width="79%" style="width:100px" class="padding"><input type="text" id="branchCode" name="branchCode" class="inputbox" maxlength="5" /></td>
</tr>

<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" />
                    <input type="image" name="AddCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddBranch');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
 
</table>
  </form>
<?php floatingDiv_End(); ?>

<?php floatingDiv_Start('EditBranch','Edit Branch'); ?>
  <form name="editBranch" action="" method="post">   
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
  
      
    <input type="hidden" name="branchId" id="branchId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Name : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="branchName" name="branchName" class="inputbox"  maxlength="50"/></td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Abbr : </b></nobr></td>
        <td width="79%" class="padding"><input type="text" id="branchCode" name="branchCode" class="inputbox" maxlength="5"/></td>
    </tr>
    <tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2"><input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');return false;" /><input type="image" name="EditCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif" onclick="javascript:hiddenFloatingDiv('EditBranch');return false;" /></td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
</form>
<?php floatingDiv_End(); ?>
<?php 
//$History: listBranchContents.php $
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 12/19/08   Time: 3:02p
//Created in $/LeapCC/Templates/Management
//Initial checkin
?>