<?php
	$style = " style = 'display:none;'";
	if ($menuCreationManager->showSearch(MODULE) == true) {
		$style = " style = 'display:;'";
	}

    if($specialSearchCondition==''){
        $formSearchCondition="sendReq(listURL,divResultName,searchFormName,'')";
    }
    else{
        $formSearchCondition=$specialSearchCondition; 
    }
?>
<form action="" method="" name="searchForm" onSubmit="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; <?php echo $formSearchCondition; ?>; return false;" <?php echo $style;?>>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20">
	<?php
	 if(UtilityManager::isIEBrowser()==0){//for FF
	?>
		<tr height="30">
			<td width="19%" align="left"><input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-left:5px;margin-top: 2px;" size="30" /></td>
			<td align="left">
				<img  src="<?php echo IMG_HTTP_PATH;?>/search1.gif" style="margin-right: 5px;margin-top:0px;" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; <?php echo $formSearchCondition; ?>; return false;"/>
				<input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
			</td>
		</tr>
   <?php
    }
	else //for IE
	{
	?>
  <tr height="30">
			<td width="19%" align="left"><input type="text" name="searchbox_h" class="inputbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" style="margin-left:5px;height:16px;" size="30" /></td>
			<td align="left">
				<img src="<?php echo IMG_HTTP_PATH;?>/search1.gif" style="margin-right:5px;margin-top:4px;height:20px;border:0px" onClick="document.searchForm.searchbox.value=document.searchForm.searchbox_h.value; <?php echo $formSearchCondition; ?>; return false;"/>
				<input type="hidden" name="searchbox" value="<?php echo $REQUEST_DATA['searchbox'];?>" />
			</td>
		</tr>
	<?php
	}
	?>

	</table>
</form>