<form id="frmLogin" name="frmLogin" method="POST" action="" onSubmit="return submitForm();";>
<input type="hidden" name="cmdSubmit" value="1" />
	<table cellpadding="3" cellspacing="0" style="border:1px solid #B6C7EB;width:350px;" >
		<thead>
			<tr>
				<th bgcolor="#ECF1FB" colspan="2" align="left">Member Login</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($errorMessage) && trim($errorMessage) != "") {
			?>
			<tr>
				<td class="error" colspan="2"><?php
				print $errorMessage; ?>
				</td>
			</tr>
			<?php
			}
			?>
			
			<tr> 
			  <td>Username</td>
			  <td><input name="userID" value="" id="userID" type="text"></td>
			</tr>
			<tr> 
			  <td>Password</td>
			  <td><input name="password" id="password" type="password"></td>
			</tr>
			<tr> 
			  <td colspan="2" align="center"><input value="Login" type="submit"></td>
			</tr>
			<!--tr> 
			  <td colspan="2" align="right"> Forgotten : <a href="javascript:void(0)" class="title">password</a></td>
			</tr-->
		  </tbody>
	</table>
</form>		