<?php
		
		
		function  selectAttachment($featureId) {
			 $query ="select attachment from broadcast_feature where featureId = '$featureId'";
			 $result = mysql_query($query) or die('Error while executing following query. <br>'.$query);
			 return $result;
		}
		
		
		function updateAttachmentBroadcastFeature($featureId) {
			$query ="update broadcast_feature set attachment = '' where featureId = '$featureId'";
			return  mysql_query($query);  
		}

		
		function selectBroadcastRole($roleId) {
			$query = "SELECT
								featureId,featureTitle,featureDescription,menuPath,attachment
					  from
								broadcast_feature
					  WHERE
								roleId=$roleId
					  AND 
								visibleToDate >= CURDATE()";
			$result = mysql_query($query) or die('Error while executing following query. <br>'.$query);
			return $result;
	   }
	 
		
		function selectBroadcast() {
			$query = "SELECT
								featureId,featureTitle,featureDescription,menuPath,attachment 
			     	  from
								broadcast_feature
					  WHERE
								visibleToDate >= CURDATE()";
		
			$result = mysql_query($query) or die('Error while executing following query. <br>'.$query);
		    return $result;
				
		}
		
		
		function  selectDescription($featureId) {
																	
			  $query ="select
								roleId,featureTitle ,featureDescription 
						from
								broadcast_feature
						where 
								featureId = '$featureId'
					";
			 $res = mysql_query($query) or die('Error while executing following query. <br>'.$query);
			 return $res;
										
	}
	function  selectNewBroadcast() {
			$query = "select
								count(*) as cnt
						from
								broadcast_feature
						WHERE 
							    visibleToDate = CURDATE()";
								  
				 $res = mysql_query($query) or die('Error while executing following query. <br>'.$query);
				 return $res;

	}
?>