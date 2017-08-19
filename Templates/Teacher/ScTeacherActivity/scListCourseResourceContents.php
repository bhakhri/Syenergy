<?php
//-------------------------------------------------------
// THIS FILE IS USED AS TEMPLATE FOR Cource Resource 
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
                <td valign="top">Messaging &nbsp;&raquo;&nbsp;Upload Cource Resource</td>
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
                        <td class="content_title">Uploaded Resources : </td>
                        <td class="content_title" title="Add"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" 
                        align="right" onClick="displayWindow('AddResourceDiv','',300,250);blankValues();return false;" />&nbsp;</td>
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
                    <td width="10%" class="searchhead_text"><b>Course</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-up.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=DESC&sortField='+sortField)" /></td>
                    <td width="15%" class="searchhead_text"><b>Description</b></td>
                    <td width="10%" class="searchhead_text"><b>Type</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=resourceName')" /></td>
                    <td width="8%" class="searchhead_text"><b>Date</b><img src="<?php echo IMG_HTTP_PATH;?>/arrow-none.gif" onClick="sendReq(listURL,divResultName,searchFormName,'page=1&sortOrderBy=ASC&sortField=postedDate')" /></td>
                    <td width="8%" class="searchhead_text"><b>Link</b></td>
                    <td width="5%" class="searchhead_text"><b>Attachment</b></td>
                    <td width="10%" class="searchhead_text" align="right"><b>Action</b></td>
                 </tr>
                <?php
                require_once(BL_PATH.'/HtmlFunctions.inc.php');
                $recordCount = count($resourceRecordArray);
                if($recordCount >0 && is_array($resourceRecordArray) ) { 
                     
                    for($i=0; $i<$recordCount; $i++ ) {
                        
                        $bg = $bg =='row0' ? 'row1' : 'row0';
                    
                    //for file downloading
                    $fileStr='<img src="'.IMG_HTTP_PATH.'/download.gif" name="'.strip_slashes($resourceRecordArray[$i]['attachmentFile']).'" onclick="download(this.name);" title="Download File" />';    
                    //for url clicking
                    $urlStr='<a href="'.strip_slashes($resourceRecordArray[$i]['resourceUrl']).'" target="_blank">'.trim_output(strip_slashes($resourceRecordArray[$i]['resourceUrl']),40).'</a>';
                    
                    echo '<tr class="'.$bg.'">
                        <td valign="top" class="padding_top" >'.($records+$i+1).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['subject']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes(trim_output($resourceRecordArray[$i]['description'],100)).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['resourceName']).'</td>
                        <td class="padding_top" valign="top">'.strip_slashes($resourceRecordArray[$i]['postedDate']).'</td>
                        <td class="padding_top" valign="top">'.(strip_slashes($resourceRecordArray[$i]['resourceUrl'])==-1 ? '' : $urlStr).'</td>
                        <td class="padding_top" valign="top" align="center">'.(strip_slashes($resourceRecordArray[$i]['attachmentFile'])==-1 ? '' :$fileStr).'</td>
                        <td width="10%" class="padding_top" align="right"><a href="#" title="Edit"><img src="'.IMG_HTTP_PATH.'/edit.gif"  border="0" onClick="editWindow('.$resourceRecordArray[$i]['courseResourceId'].',\'EditResourceDiv\',315,250); return false;"/></a>&nbsp;&nbsp;<img src="'.IMG_HTTP_PATH.'/delete.gif" border="0" alt="Delete" onClick="return deleteResource('.$resourceRecordArray[$i]['courseResourceId'].');"/></td>
                        </tr>';
                    }
               if($totalArray[0]['totalRecords']>RECORDS_PER_PAGE) {
                          $bg = $bg =='row0' ? 'row1' : 'row0';
                          require_once(BL_PATH . "/Paging.php");
                          $paging = Paging::getInstance(RECORDS_PER_PAGE,$totalArray[0]['totalRecords']);
                          echo '<tr><td colspan="8" align="right">'.$paging->ajaxPrintLinks().'&nbsp;</td></tr>';                   
                    }
                }
                else {
                    echo '<tr><td colspan="8" align="center">No record found</td></tr>';
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
    
<!--Start Add Div-->     
<?php floatingDiv_Start('AddResourceDiv','Add Resource'); ?>
    <form name="AddResource" id="AddResource" action="<?php echo HTTP_LIB_PATH;?>/Teacher/ScTeacherActivity/resourceFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Cource : </b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="subject" id="subject" tabindex="1">
         <option value="">Select Course</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
          ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category : </b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="category" id="category" tabindex="2">
         <option value="">Select Category</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getResourceCategoryData();
          ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Description: </b></nobr></td>
        <td width="79%" class="padding">
         <textarea id="description" name="description" rows="5" cols="20" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="3">
         </textarea>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>URL: </b></nobr></td>
        <td width="79%" class="padding">
         <input type="text" id="resourceUrl" name="resourceUrl" class="inputbox" maxlength="100" tabindex="4"/>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Upload File: </b></nobr></td>
        <td width="79%" class="padding">
          <input type="file" id="resourceFile" name="resourceFile" class="inputbox" tabindex="5">
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td width="79%" class="padding">
          Maximum File Size : <?php echo round(MAXIMUM_FILE_SIZE/(1024*1024),2); ?> MB
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr>
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');return false;" tabindex="6" />
      <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddResourceDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" tabindex="7"/>
        </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetAdd" name="uploadTargetAdd" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<!--Start Edit Div-->     
<?php floatingDiv_Start('EditResourceDiv','Edit Resource'); ?>
   <form name="EditResource" id="EditResource" action="<?php echo HTTP_LIB_PATH;?>/Teacher/ScTeacherActivity/resourceFileUpload.php" method="post" enctype="multipart/form-data" style="display:inline">  
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="border">
    <input type="hidden" name="courseResourceId" id="courseResourceId" value="" />
    <tr>
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Cource : </b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="subject" id="subject" tabindex="1">
         <option value="">Select Course</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getTeacherSubjectData();
          ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Category : </b></nobr></td>
        <td width="79%" class="padding">
        <select size="1" class="selectfield" name="category" id="category" tabindex="2">
         <option value="">Select Category</option>
          <?php
           require_once(BL_PATH.'/HtmlFunctions.inc.php');
           echo HtmlFunctions::getInstance()->getResourceCategoryData();
          ?>
        </select>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Description: </b></nobr></td>
        <td width="79%" class="padding">
         <textarea id="description" name="description" rows="5" cols="20" maxlength="255" onkeyup="return ismaxlength(this)" tabindex="3">
         </textarea>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>URL: </b></nobr></td>
        <td width="79%" class="padding">
         <input type="text" id="resourceUrl" name="resourceUrl" class="inputbox" maxlength="100" tabindex="4" />
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows"><nobr><b>Upload File: </b></nobr></td>
        <td width="79%" class="padding">
          <input type="file" id="resourceFile" name="resourceFile" class="inputbox" tabindex="5"> &nbsp;&nbsp;&nbsp;<label id="uploadIconLabel"></label>
        </td>
    </tr>
    <tr>    
        <td width="21%" class="contenttab_internal_rows">&nbsp;</td>
        <td width="79%" class="padding">
          Maximum File Size : <?php echo round(MAXIMUM_FILE_SIZE/(1024*1024),2); ?> MB
        </td>
    </tr>
<tr>
    <td height="5px"></td></tr>
<tr colspan="2">
    <td align="center" style="padding-right:10px" colspan="2">
        <input type="image" name="imageEdit" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');"  tabindex="6" />
       <input type="image" name="editCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onClick="javascript:hiddenFloatingDiv('EditResourceDiv');return false;" tabindex="7" />
   </td>
</tr>
<tr>
    <td height="5px"></td></tr>
<tr>
</table>
<iframe id="uploadTargetEdit" name="uploadTargetEdit" src="" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
</form>
<?php floatingDiv_End(); ?>
<!--End Add Div-->


<?php
// $History: scListCourseResourceContents.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Templates/Teacher/ScTeacherActivity
//
//*****************  Version 4  *****************
//User: Dipanjan     Date: 11/05/08   Time: 4:20p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected "Course" dropdown
//
//*****************  Version 3  *****************
//User: Dipanjan     Date: 11/05/08   Time: 3:06p
//Updated in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Corrected BreadCrumb String
//
//*****************  Version 1  *****************
//User: Dipanjan     Date: 11/05/08   Time: 2:48p
//Created in $/Leap/Source/Templates/Teacher/ScTeacherActivity
//Created CourseResource Module
?>