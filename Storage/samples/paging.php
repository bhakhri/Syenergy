<?php
	if(isset($REQUEST_DATA['page']) && ($REQUEST_DATA['page'] != "")){
		$page = $REQUEST_DATA['page'];
	}else{
		$page=1; //whatever page number is currently
	}	
	if(isset($REQUEST_DATA['sortBy']) && ($REQUEST_DATA['sortBy'] !="" )){
		$sortBy = $REQUEST_DATA['sortBy'];
	}
	$records = RECORDS_PER_PAGE;//number of records to be shown per page
	$page = $page - 1;
	$total = ($records * $page);
	if($total == 0){
		$startIndex = 0;
	}else{
		$startIndex = $total;
	}
	$endIndex = $records;
	if($REQUEST_DATA['hidAct'] == 'sortBy' ){			
		if($REQUEST_DATA['sortorder'] == '' || $REQUEST_DATA['sortorder'] == 'DESC') {				
			$REQUEST_DATA['sortorder']='ASC';
		}	
		else {				
			$REQUEST_DATA['sortorder']='DESC';
		}	
	}
?>