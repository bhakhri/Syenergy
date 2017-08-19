<?php

	$host = "192.168.1.11";
	$userName = "trainee";
	$password = "trainee";
	$dbName = "trainee_broadcast_feature";
	$conn = mysql_connect('192.168.1.11','trainee','trainee') or die('could not find host');
	mysql_select_db('trainee_broadcast_feature') or die('could not connect to database');
	function  selectDescription($featureId) {
																	
										  $query ="select
														featureTitle ,featureDescription 
												from
													broadcast_feature
												where 
													featureId = '$featureId'
											";
										$result = mysql_query($query); 
										return $result;
	}
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$descArray = selectDescription($id);
			while($row = mysql_fetch_Array($descArray)) {
			  $featureTitle = $row['featureTitle'];
			  $featureDescription = $row['featureDescription'];
			}
	
	
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top" class="title">
				<table border="1" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td height="10"></td>
					</tr>
					<tr>
						<td valign="top">New Feature</td>
						<td valign="top" align="right"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table width="100%" border="1" cellspacing="0" cellpadding="0">
					<tr>
						<td valign="top" class="content">
							 <table width="100%" border="0" cellspacing="0" cellpadding="0">
								 <tr>
									<td class="contenttab_border" height="20">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" height="20" style = "font-size:20px;">
											<tr >
												
											</tr>
										</table>						
										</td>
									</tr>
							</table>
							 <table border="1" width="1000px" style = "font-size:15px;">
									<tr>
										<th width = "8%" style="text-align:left;"><?php $featureTitle;?></th>
										<th width = "22%" style="text-align:left;"><?php $featureDescription;?> </th>
										
										
									</tr>
							</table> 
								
					</table>
			   </td>
		</tr>
    </table>
	<?php
		}
					
	?>
							
							
	

    





