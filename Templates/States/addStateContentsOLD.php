<?php
if(count($stateRecordArray)>0 && is_array($stateRecordArray) ) {
    $label = "Edit";
}
else {
    $label = "Add";
}
?>

<style type="text/css">

.message {
    border:5px solid #BDC7D8;
    font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
    padding-left:5px;
    padding-top:5px;
    padding-bottom:5px;
    padding-right:5px;
    width:400px;
}
</style>
<script language="javascript">
function showMessage(msg) {
    dv = document.createElement('messageDiv');
    dv.innerHTML = msg;
    dv.style.border = '5px solid #BDC7D8';
    dv.style.position = 'relative';
    dv.style.left = '100px';
    dv.style.top = '100px';
    document.body.insertBefore(dv,document.getElementById('frmState'));
}
</script>


<a href="#-1" onClick="showMessage('Processing...')">show</a>


<form id="frmState" name="frmState" method="POST" action="" onSubmit="return validateAddForm();";>
    <input name="stateId" id="stateId" type="hidden" value="<?php echo $REQUEST_DATA['stateId'];?>" />
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
				<th bgcolor="#ECF1FB" colspan="2" align="left"><?php echo $label;?> State</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
			  <td>State Name</td>
			  <td><input name="stateName" id="stateName" type="text" value="<?php echo $stateRecordArray[0]['stateName'];?>" /></td>
			</tr>
			<tr>
			  <td>State Code</td>
			  <td><input name="stateCode" id="stateCode" type="text" value="<?php echo $stateRecordArray[0]['stateCode'];?>" /></td>
			</tr>
			<tr>
			  <td>Country</td>
			  <td><select name="countries" id="countries"><option value="">Select</option> 
			  <?php
				  require_once(BL_PATH.'/HtmlFunctions.inc.php');
				  echo HtmlFunctions::getInstance()->getCountriesData($REQUEST_DATA['countries']==''? $stateRecordArray[0]['countryId'] : $REQUEST_DATA['countries'] );
			  ?>
			   </select></td>
			</tr>
			<tr> 
			  <td colspan="2" align="center">
                <input value="<?php echo $label;?>" name="stateSubmit" id="stateSubmit" type="button"onClick="return validateAddForm('Add'); addState();" />
              </td>
			</tr>
		  </tbody>
	</table>
</form>		