<style>
    .imgLinkRemove1{ cursor: default; }
</style>  
<?php
    require_once(BL_PATH.'/helpMessage.inc.php');
	require_once(TEMPLATES_PATH . "/breadCrumb.php");
?>
    <tr>
		<td valign="top" colspan="2">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="405">
            <tr>
             <td valign="top" class="content">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr height="30">
                                <td class="contenttab_border" width="25%" valign="top" style="border:0px;">
                                    <?php require_once(TEMPLATES_PATH . "/searchForm.php"); ?>
                                </td>
                                <td class="contenttab_border" width="50%" valign="top" style="border:0px;color:black;font-size:11px;"><nobr>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                       <tr>
                                          <td align="left" width="20px"><b>Note&nbsp;:&nbsp;&nbsp;&nbsp;</b></td>
                                          <td align="left">
                                          <b>A.&nbsp;&nbsp;Greeting message will Displayed when user Logs in.</b></td>
                                       </tr>   
                                          <td align="left" width="20px"></td>
                                          <td align="left">
                                            <b>B.&nbsp;&nbsp;If Visible Mode is 'No' then it will not show you greeting message. </b></td>
                                       </tr>   
                                    </table>
                                    </nobr>
                                </td>
								<td width="15%" class="contenttab_border" align="right" nowrap="nowrap" style="border-left:0px;margin-right:5px;"><img src="<?php echo IMG_HTTP_PATH;?>/add.gif" onClick="displayFloatingDiv('AddEventDiv','',600,400,screen.width/4.8, screen.height/10);blankValues();return false;" />&nbsp;</td></tr>
             <tr>
				<td class="contenttab_row" colspan="3" valign="top" >
                 <form name="myForm" id="myForm" method="post" onSubmit="return false;" >
                  <div id="results"></div>
                 </form> 
                </td>
          </tr>
           <tr>
								<td align="right" colspan="3">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
                        <tr>
                            <td class="content_title" valign="middle" align="right" width="20%">
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/print.gif" onClick="printReport();" >&nbsp;
                              <input type="image"  src="<?php echo IMG_HTTP_PATH; ?>/excel.gif" onClick="printReportCSV();" >
                            </td>
                        </tr>
                    </table>
                </td>
           </tr>
          </table>
        </td>
    </tr>
    </table>
		</td>
	</tr>
</table>
<?php floatingDiv_Start('AddEventDiv','Add Greeting'); ?>
<form name="addEvent" id="addEvent" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
<table width="100%" border="0" cellspacing="1px" cellpadding="1px" class="border">
    <tr>
      <td class="contenttab_internal_rows"><nobr><strong>Greeting Date</strong></nobr></td>
      <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('eventWishDate',date('Y-m-d'));
        ?>
     </td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top"><strong>Comments<?php echo REQUIRED_FIELD ?></strong></td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
        <!-- <textarea cols="56" rows="8"  id="noticeText" name="noticeText" ></textarea> -->
        <input type="text" id="elm11" name="elm11" maxlength="50" class="inputbox" style="width:480px" ><br>
        <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
           i.e. Happy Diwali
        </span>
      </td>
    </tr>
    <tr>
      <td width="30%" class="contenttab_internal_rows"><b>Abbreviation<?php echo REQUIRED_FIELD ?></b></td>
      <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >   
         <input type="text" maxlength="10" class="inputbox" name="eventAbbrevation" id="eventAbbrevation">&nbsp;
         <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
           <!-- Enter Keyword according -->
         </span>
      </td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top"><strong>Photo<?php echo REQUIRED_FIELD ?></strong></td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" > 
        <nobr>
        <table border="0" cellspacing="0px" cellpadding="0px">
          <tr>
            <td class="contenttab_internal_rows" nowrap align="left">
               <input type="file" id="eventPicture" name="eventPicture" class="inputbox" tabindex="15" >
            </td>
           </tr>
           <tr>
             <td class="contenttab_internal_rows" colspan="3">  
               <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
                Format: gif, jpg, jpeg (Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)<br>
                Photo automatically get cropped by default size (250px 240px) and<br>If photo size increases from default size will give error message.
               </span>
             </td>
           </tr>
         </table>       
       </nobr>
      </td>
    </tr>
    <tr>
      <td  class="contenttab_internal_rows" valign="top"><nobr><strong>Greeting Visible To<?php echo REQUIRED_FIELD ?></strong></nobr></td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
        <select size="4" multiple="multiple"  name="roleId[]" id="roleId" style="width:255px">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roleId']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roleId'] );
          ?>
        </select>
        <div>
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("roleId[]","All","addEvent");'>All</a> 
          <a class="allReportLink" href='javascript:makeSelection("roleId","None","addEvent");'>None</a>
         </div>
      </td>
    </tr>
    <tr>
      <td  class="contenttab_internal_rows" valign="top"><nobr><strong>Visible</strong></td>
      <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" valign="top" nowrap>  
        <input name="visibility" id="visibility1" value="1" type="radio">Yes
        <input name="visibility" id="visibility2" value="0" type="radio" checked="checked">No&nbsp;
      </td>
    </tr>
    <tr><td colspan="3" height="5px"></td></tr>
    <tr>
        <td align="center" colspan="3">
          <iframe id="uploadTargetAdd" name="uploadTargetAdd" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Add');" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('AddEventDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />

        </td>
    </tr>
</table>

</form>
<?php floatingDiv_End(); ?>



<?php floatingDiv_Start('EditEventDiv','Edit Greeting'); ?>
<form name="editEvent" id="editEvent" method="post" enctype="multipart/form-data" style="display:inline" onSubmit="return false;" >
<input type="hidden"  name="userWishEventId" id="userWishEventId"/> 
<input type="hidden"  name="editEventDate" id="editEventDate"/> 
<table width="100%" border="0" cellspacing="1px" cellpadding="1px" class="border">
    <tr>
      <td class="contenttab_internal_rows" width="2%"><nobr><strong>Greeting Date</strong></nobr></td>
      <td class="contenttab_internal_rows" width="2%"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" width="96%" nowrap valign="top" >
        <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->datePicker('eventWishDate1',date('Y-m-d'));
        ?>
     </td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top"><strong>Comments<?php echo REQUIRED_FIELD ?></strong></td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
        <!-- <textarea cols="56" rows="8"  id="noticeText" name="noticeText" ></textarea> -->
        <input type="text" id="elm12" name="elm12" maxlength="50" class="inputbox" style="width:480px" ><br>
        <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
           i.e. Happy Diwali
        </span>
      </td>
    </tr>
    <tr>
      <td width="30%" class="contenttab_internal_rows"><b>Abbreviation<?php echo REQUIRED_FIELD ?></b></td>
      <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
         <input type="text" maxlength="10" class="inputbox" name="eventAbbrevation" id="eventAbbrevation">&nbsp;
         <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
           <!-- Enter Keyword according -->
         </span>
      </td>
    </tr>
    <tr>
      <td class="contenttab_internal_rows" valign="top" ><strong>Photo<?php echo REQUIRED_FIELD ?></strong></td>
      <td class="contenttab_internal_rows" valign="top" ><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" valign="top" >
        <nobr>
        <table border="0" cellspacing="0px" cellpadding="0px" width="100%" >
          <tr>
            <td class="contenttab_internal_rows" valign="top" align="left" width="20%" >
              <nobr><input type="file" id="eventPicture" name="eventPicture" class="inputbox" tabindex="15" >
              <span class="contenttab_internal_rows" style="padding-left:25px">
                  <!-- <span id="editLogoPlace" class="cl" onClick="this.style.display='none';"> -->
                  <span id="editLogoPlace" class="cl" style="display:none;">
          <a href="">
          <img src="<?php echo IMG_HTTP_PATH;?>/download.gif" alt="Download Photo" title="Download Photo" onClick="download1(); return false;" />
          </a>&nbsp;  
                      <!--<a href="">  
                        <img src="<?php echo IMG_HTTP_PATH;?>/delete.gif" alt="Delete Photo" title="Delete Photo" onClick="deatach(); return false;" />
                      </a>-->
                   </span>
                   <input type="hidden" id="downloadFileName" name="downloadFileName" class="inputbox"> 
             </span>
             </nobr>  
            </td>
           </tr>
           <tr>
             <td class="contenttab_internal_rows">  
               <span style="font-weight: normal; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: 9px; color: red;"> 
                Format: gif, jpg, jpeg (Max. Size : <?php echo (MAXIMUM_FILE_SIZE/1024); ?>KB)<br>
                Photo automatically get cropped by default size (250px 240px) and<br>If photo size increases from default size will give error message.
               </span>
             </td>
           </tr>
         </table>       
       </nobr>
      </td>
    </tr>
    <tr>
      <td  class="contenttab_internal_rows" valign="top"><nobr><strong>Greeting Visible To</strong></nobr></td>
      <td class="contenttab_internal_rows" valign="top"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" nowrap valign="top" >
        <select size="4" multiple="multiple"  name="roleId[]" id="roleId" style="width:255px">
         <?php
            require_once(BL_PATH.'/HtmlFunctions.inc.php');
            echo HtmlFunctions::getInstance()->getRoleData($REQUEST_DATA['roleId']==''? $eventRecordArray[0]['roleId'] : $REQUEST_DATA['roleId'] );
          ?>
        </select>
        <div>
          Select &nbsp;<a class="allReportLink" href='javascript:makeSelection("roleId[]","All","editEvent");'>All</a> 
          <a class="allReportLink" href='javascript:makeSelection("roleId","None","editEvent");'>None</a>
         </div>
      </td>
    </tr>
    <tr>                                                           
      <td  class="contenttab_internal_rows" valign="top"><nobr><strong>Visible</strong></td>
      <td class="contenttab_internal_rows"><nobr><b>:</b></nobr></td>
      <td class="contenttab_internal_rows" valign="top" nowrap>  
        <input name="visibility" id="visibility1" value="1" type="radio">Yes
        <input name="visibility" id="visibility2" value="0" type="radio" checked="checked">No&nbsp;
      </td>
    </tr>
    <tr><td colspan="3" height="5px"></td></tr>
    <tr>
        <td align="center" colspan="3">
          <iframe id="uploadTargetEdit" name="uploadTargetEdit" style="width:0px;height:0px;border:0px solid #fff;"></iframe>
          <input type="image" name="imageField" src="<?php echo IMG_HTTP_PATH;?>/save.gif" onClick="return validateAddForm(this.form,'Edit');" />
          <input type="image" name="addCancel" src="<?php echo IMG_HTTP_PATH;?>/cancel.gif"  onclick="javascript:hiddenFloatingDiv('EditEventDiv');if(flag==true){sendReq(listURL,divResultName,searchFormName,'');flag=false;}return false;" />
        </td>
    </tr>
</table>
</form>
<?php floatingDiv_End(); ?>
  <!-- Help Div Starts -->
<?php  floatingDiv_Start('divHelpInfo','Help','12','','','1'); ?>
<div id="helpInfoDiv" style="overflow:auto; WIDTH:390px; vertical-align:top;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
        <tr>
            <td height="5px"></td></tr>
        <tr>
        <tr>
            <td width="89%">
                <div id="helpInfo" style="vertical-align:top;" ></div>
            </td>
        </tr>
    </table>
</div>
<?php floatingDiv_End(); ?>
 <!-- Help Div Ends -->




<?php
// ListEvetContents HISTORY :::


?>
