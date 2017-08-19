<?php
if(count($designationRecordArray)>0 && is_array($designationRecordArray) ) {
    $label = "Edit";
}
else {
    $label = "Add";
}
?>
<form id="frmDesignation" name="frmDesignation" method="POST" action="" onSubmit="return validateAddForm();";>
    <input name="designationId" id="designationId" type="hidden" value="<?php echo $REQUEST_DATA['designationId'];?>" />
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
				<th bgcolor="#ECF1FB" colspan="2" align="left"><?php echo $label;?> Designation</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
			  <td>Designation Name</td>
			  <td><input name="designationName" id="designationName" type="text" value="<?php echo $designationRecordArray[0]['designationName'];?>" /></td>
			</tr>
			<tr>
			  <td>Designation Code</td>
			  <td><input name="designationCode" id="designationCode" type="text" value="<?php echo $designationRecordArray[0]['designationCode'];?>" /></td>
			</tr>
			
			<tr> 
			  <td colspan="2" align="center">
                <input value="<?php echo $label;?>" name="designationSubmit" id="designationSubmit" type="button"onClick="return validateAddForm('<?php echo $label; ?>'); " />
              </td>
			</tr>
		  </tbody>
	</table>
</form>		