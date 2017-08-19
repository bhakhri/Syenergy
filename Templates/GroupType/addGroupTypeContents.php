<?php
if(count($groupTypeRecordArray)>0 && is_array($groupTypeRecordArray) ) {
    $label = "Edit";
}
else {
    $label = "Add";
}
?>
<form id="frmGroupType" name="frmGroupType" method="POST" action="" onSubmit="return validateAddForm();";>
    <input name="groupTypeId" id="groupTypeId" type="hidden" value="<?php echo $REQUEST_DATA['groupTypeId'];?>" />
	<table cellpadding="3" cellspacing="0" style="border:1px solid #B6C7EB;width:350px;" align="center">
		<thead>
            <?php
            if (isset($errorMessage) && trim($errorMessage) != "") {
                echo '
                <tr>
                <td class="error" colspan="2">'.$errorMessage.'</td>
                </tr>';
            } 
            else {
                 if(isset($REQUEST_DATA['status']) ) {
                    echo '
                    <tr>
                    <td class="error" colspan="2">'.($REQUEST_DATA['status'] == 1 ? $messagesArray['1'] : $messagesArray['2'] ).'</td>
                    </tr>';                         
                 }
            }
            ?>
			<tr>
				<th bgcolor="#ECF1FB" colspan="2" align="left"><?php echo $label;?> Group Type</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
			  <td>Group Type Name</td>
			  <td><input name="groupTypeName" id="groupTypeName" type="text" value="<?php echo $groupTypeRecordArray[0]['groupTypeName'];?>" /></td>
			</tr>
			<tr>
			  <td>Abbr.</td>
			  <td><input name="groupTypeCode" id="groupTypeCode" type="text" value="<?php echo $groupTypeRecordArray[0]['groupTypeCode'];?>" /></td>
			</tr>
			
			<tr> 
			  <td colspan="2" align="center">
                <input value="<?php echo $label;?>" name="groupTypeSubmit" id="groupTypeSubmit" type="button" onClick="return validateAddForm('<?php echo $label; ?>'); " />
              </td>
			</tr>
		  </tbody>
	</table>
</form>		