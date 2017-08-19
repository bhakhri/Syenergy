<tr id="smsTemplateDiv" style="display:none">           
			<?php
			if(SMS_TEMPLATE_DISPLAY==1)
			{
			?>
    <td colspan="4" class="contenttab_internal_rows">
     <table border="0" cellpadding="0" cellspacing="0">    
          <tr>
            <td valign="top" width="100%" colspan="4" class="contenttab_internal_rows" style="padding-left:0px;padding-right:5px">
            <b>SMS Template:</b> 
                 <select id="smsTemplate" name="smsTemplate" class="selectfield" style="width:410px" size="1" onClick="getTextBox(this.value,'A'); return false;">
                   <option value='' selected="selected">Select</option>
                <?php
                    require_once(BL_PATH.'/HtmlFunctions.inc.php');
                    echo HtmlFunctions::getInstance()->getSmsTemplate();
                ?>    
               </select>
               </td>
           </tr>
           <tr><td height="5px"></td></tr>
           <tr>
              <td valign="top" width="100%" colspan="4" class="contenttab_internal_rows"  style="padding-left:0px;padding-right:5px">
                <span id='spanTextBox'></span>
              </td>    
           </tr>    
     </table> 
    </td>
	<?php
		}
	?>
</tr> 
	<tr><td height="5px" colspan="2"></td></tr> 
    
	<tr id="nameNotTinyMCE" style="display:none">
	<?php
	if(SMS_TEMPLATE_DISPLAY==1)
	{
	?>
    <td valign="top" width="100%" colspan="15" style="padding-left:5px;padding-right:5px">
    <textarea id="txtSmsmsg" name="txtSmsmsg" class="inputbox" readonly="readonly" rows="10" cols="50" style="width:100%"></textarea>
    </td>  
		<?php
			}
			else{
		?>
		<td valign="top" width="100%" colspan="15" style="padding-left:5px;padding-right:5px">
		<textarea id="txtSmsmsg" name="txtSmsmsg" class="inputbox"  rows="10" cols="50" style="width:100%" onkeyup='smsCalculation(this.value,SMSML,"sms_no");return false;'></textarea>
    </td>
		<?php
			}
		?>	
    </tr>
 