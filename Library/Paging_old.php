<?php

class paging
{
	
	var $p;
	var $page;
	var $q;
	var $query;
	var $first;
	var $next;
	var $prev;
	var $number;
	var $strExtraAttributes;
	var $strOrder;
	var $arPrintInfo;
	var $msg;
	function paging($intPRecordPerPage=10, $intPPageNoShow=5,$mess='Record', $prev="&laquo; Prev", $next="Next &raquo;", $number="[%%number%%]",$first="First",$last="Last")
	{
		$this->first = $first;	
		$this->last = $last;
		$this->next=$next;
		$this->prev=$prev;
		$this->number=$number;
		$this->msg=$mess;
		if($_REQUEST['hidAct'] == 'sortBy' ){			
			if($_REQUEST['sortorder'] == '' || $_REQUEST['sortorder'] == 'DESC') {				
				$_REQUEST['sortorder']='ASC';
			}	
			else {				
				$_REQUEST['sortorder']='DESC';
			}	
		}
		
		$this->strOrder = $_REQUEST['sortorder'];
		if(is_array($_REQUEST) && count($_REQUEST)>0){
			$arAttributes = array_unique($_REQUEST);
			foreach($arAttributes as $k=>$v) {
			 if($k!='msg' && $k!='chkRecordId' && $k != 'hidAllId' && $k != 'PHPSESSID' && $k != 'cmdSubmit' && $k!='errMsg' && $k!='hidAct')	{
				if($v !='') {
					$strExtraAttributes .= $k."=".strip_tags($v)."&";
				}	
			 }	
			}
		}
		
			$this->strExtraAttributes = $strExtraAttributes;
	//	$extraAttributes = 1 ;
		
		if($_REQUEST['recPerPage'] != '' && is_numeric($_REQUEST['recPerPage']))
			$this->p["recordsPerPage"] = $_REQUEST['recPerPage'];
		else 			
			$this->p["recordsPerPage"] = $intPRecordPerPage;
		
		
		$this->p["pageNoShow"] = $intPPageNoShow;

		$_SERVER["QUERY_STRING"]=preg_replace("/&page=[0-9]*/","",$_SERVER["QUERY_STRING"]);
		
		if (empty($_GET["page"])) {
			$this->page=1;
		} else {
			$this->page=$_GET["page"];
		}
	}


	function setPagingParameters($intPTotalRecords)
	{
		//$kondisi=false;
		
		// get the total records		
		$this->p["count"]= $intPTotalRecords;

		// total page
		$this->p["total_page"] = ceil( $this->p["count"] / $this->p["recordsPerPage"] );

		// filter page
		if($this->page<=1)
			$this->page=1;
		elseif($this->page>$this->p["total_page"])
			$this->page=$this->p["total_page"];

		// awal data yang diambil
		$this->p["start"]=$this->page*$this->p["recordsPerPage"]-$this->p["recordsPerPage"];				
	}
	


	function print_no()
	{
		$number=$this->p["start"]+=1;
		return $number;
	}
	
	function print_color($color1,$color2)
	{
		if (empty($this->p["count_color"]))
			$this->p["count_color"] = 0;
		if ($this->p["count_color"]++ % 2 == 0 ) {
			return $color=$color1;
		} else {
			return $color=$color2;
		}
	}

	function print_info()
	{
		$this->arPrintInfo = array();
		$this->arPrintInfo["start"]	=$this->p["start"]+1;
		$this->arPrintInfo["end"]	=$this->p["start"]+$this->p["recordsPerPage"];
		$this->arPrintInfo["total"]	=$this->p["count"];
		$this->arPrintInfo["total_pages"]=$this->p["total_page"];
			
		if ($this->arPrintInfo["end"] > $this->arPrintInfo["total"]) {
				$this->arPrintInfo["end"]=$this->arPrintInfo["total"];
		}
		if (empty($this->p["count"])) {
				$this->arPrintInfo["start"]=0;
		}

		
	}

	function number($i,$number)
		{
			return ereg_replace("^(.*)%%number%%(.*)$","\\1$i\\2",$number);
		}
	function print_link()
	{
		//generate template
		
		
		$print_link = false;
		

		if($this->p["count"] < 1 ){
			$print_link .="<span class='errMsg'> No ".$this->msg." Found.</span>";
			return  $print_link;
		}
	
		$this->print_info();	
			

		if ($this->p["count"]>$this->p["recordsPerPage"]) { 
			
			$print_link .= "<b> " . $this->msg . " </b> ".$this->arPrintInfo["start"]." - ".$this->arPrintInfo["end"]." of ".$this->arPrintInfo["total"]." [Total ". $this->arPrintInfo["total_pages"]." Pages]<Br>";				
			if ($this->page>1) {
				$print_link .= "<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=1\">".$this->first."</a>&nbsp;&nbsp;\n";
				$print_link .= "<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=".($this->page-1)."\">".$this->prev."</a>&nbsp;&nbsp;\n";
			}

			// set number
			$this->p["bawah"] = $this->page - $this->p["pageNoShow"];
			
			// get the half of the total no. to be shown on the page
			$intPageDiff = (int)($this->p["pageNoShow"]/2);
			
			// get the half of the total no. to be shown on the page
			//VKS: don't use ceil function here as it may
//			$intPageDiff = ceil($this->p["pageNoShow"]/2);
			
			// get the number left from the starting position 
			$intNoLeft = $intPageDiff - $this->page;
			
			// get the max. no. shown on the right side of paging
			$intAddMore = $this->page + $intPageDiff;
			
			// get the least no. shown on the left side of paging
			$intSubMore = $this->p["total_page"] - $intAddMore;
		
			// if the current page no. is less than the half of the total no. on the page
			if($this->page <= $intPageDiff)
				 $this->p["bawah"] = 1;	
			else{
				if($intSubMore < 0 )
				 	$this->p["bawah"] = $this->page - $intPageDiff + $intSubMore;	
				else 
					$this->p["bawah"] = $this->page - $intPageDiff  ;					
			}
			
			// set the max no. to be shown on the paging
			if($intNoLeft > 0){	
				$this->p["atas"] = $intAddMore + $intNoLeft;			 
			}else{ 
				 $this->p["atas"] = $intAddMore;			 
			}	 
			if ($this->p["atas"]>$this->p["total_page"]) $this->p["atas"]=$this->p["total_page"];
			// print start
			if ($this->page <> 1)
			{
				for ($i=$this->p["bawah"];$i<=$this->page-1;$i++){
					if($i>0){
					$print_link .="<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=$i\">".$this->number($i,$this->number)."</a>\n";
					}
				}
			}
			
			// print active
			if ($this->p["total_page"]>1){
				$print_link .= "<b>".$this->number($this->page,$this->number)."</b>\n";
			}
				

			// print end
			for ($i=$this->page+1;$i<=$this->p["atas"];$i++)
				$print_link .= "<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=$i\">".$this->number($i,$this->number)."</a>\n";

			// print next
			if ($this->page< $this->p["total_page"]) {
				$print_link .= "&nbsp;&nbsp;<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=".($this->page+1)."\">".$this->next."</a>\n";
				$print_link .= "&nbsp;&nbsp;<a class='link_txt' href=\"".$_SERVER["PHP_SELF"]."?".$this->strExtraAttributes."page=".$this->p["total_page"]."\">".$this->last."</a>&nbsp;&nbsp;\n";
			}	
			//print $print_link;
			
			
			
			
		}
		else { 
			$print_link .= "<b>" . $this->msg . "</b> ".$this->arPrintInfo["start"]." - ".$this->arPrintInfo["end"]." of ".$this->arPrintInfo["total"]."<Br>";				
		}
		return $print_link;
	}

	public function printWCTHotelsLinks($printHotelInfo = false)	{
		//generate template
		$print_link = false;
		if($this->p["count"] < 1 ){
			$print_link .= "<span class='errMsg'> No " . $this->msg . " Found.</span>";
			return  $print_link;
		}
		if ($printHotelInfo === true) {
			$this->print_info();	
		}			

		if ($this->p["count"] > $this->p["recordsPerPage"]) {
			
			//$print_link .= "<b> " . $this->msg . " </b> ".$this->arPrintInfo["start"]." - ".$this->arPrintInfo["end"]." of ".$this->arPrintInfo["total"]." [Total ". $this->arPrintInfo["total_pages"]." Pages]<Br>";
			if ($this->page > 1) {
				//$print_link .= "<a class='link_txt' href=\"" . $_SERVER["PHP_SELF"] . "?" . $this->strExtraAttributes . "page=1\">" . $this->first . "</a>";
				$print_link .= "<a class='mid_tabl_link' href=\"" . $_SERVER["PHP_SELF"] . "?" . "page=" . ($this->page-1) . "\">" . $this->prev . "</a> ";
			}

			// set number
			$this->p["bawah"] = $this->page - $this->p["pageNoShow"];
			
			// get the half of the total no. to be shown on the page
			$intPageDiff = (int)($this->p["pageNoShow"] / 2);
			
			// get the half of the total no. to be shown on the page
			//VKS: don't use ceil function here as it may
//			$intPageDiff = ceil($this->p["pageNoShow"]/2);
			
			// get the number left from the starting position 
			$intNoLeft = $intPageDiff - $this->page;
			
			// get the max. no. shown on the right side of paging
			$intAddMore = $this->page + $intPageDiff;
			
			// get the least no. shown on the left side of paging
			$intSubMore = $this->p["total_page"] - $intAddMore;
		
			// if the current page no. is less than the half of the total no. on the page
			if($this->page <= $intPageDiff)
				 $this->p["bawah"] = 1;	
			else{
				if($intSubMore < 0 )
				 	$this->p["bawah"] = $this->page - $intPageDiff + $intSubMore;	
				else 
					$this->p["bawah"] = $this->page - $intPageDiff  ;					
			}
			
			// set the max no. to be shown on the paging
			if($intNoLeft > 0){	
				$this->p["atas"] = $intAddMore + $intNoLeft;			 
			}else{ 
				 $this->p["atas"] = $intAddMore;			 
			}	 
			if ($this->p["atas"] > $this->p["total_page"]) $this->p["atas"]=$this->p["total_page"];
			// print start
			if ($this->page <> 1) {
				for ($i = $this->p["bawah"]; $i <= $this->page-1;$i++){
					if($i > 0){
						$currentPageNumber = ($this->p["recordsPerPage"] * ($i - 1));
						$print_link .= "<a class='mid_tabl_link' href=\"" . $_SERVER["PHP_SELF"] . "?page=$i\">" . ($currentPageNumber + 1) .'-' . ($currentPageNumber + $this->p["recordsPerPage"]) . "</a>";
						if (($i + 1) <= ($this->page)) {
							$print_link .= " | ";
						}
					}
				}
			}
			
			// print active
			if ($this->p["total_page"] > 1){
				$currentPageNumber = ($this->page - 1) * $this->p["recordsPerPage"];
				$print_link .= "<b>" . ($currentPageNumber + 1) . "-" . (($currentPageNumber) + $this->p["recordsPerPage"]) . "</b>";
				if ($this->p["total_page"] > $this->page) {
					$print_link .= " | ";
				}
			}

			// print end
			for ($i=$this->page+1; $i <= $this->p["atas"]; $i++) {
				
				$currentPageNumber = ($this->p["recordsPerPage"] * ($i - 1));
				$lastPageRecordCount = ($currentPageNumber + $this->p["recordsPerPage"]);
				if ($lastPageRecordCount > $this->p["count"]) {
					$lastPageRecordCount = $this->p["count"];
				}

				$print_link .= "<a class='mid_tabl_link' href=\"".$_SERVER["PHP_SELF"]."?page=$i\">" . ($currentPageNumber + 1) . "-" .  $lastPageRecordCount . "</a>&nbsp;|&nbsp;";				  
			}

			// print next
			if ($this->page < $this->p["total_page"]) {
				$print_link .= "<a class='mid_tabl_link' href=\"".$_SERVER["PHP_SELF"]."?page=" . ($this->page + 1)."\">".$this->next."</a>";
			}	
		}
		else { 
			$print_link .= "<b>" . $this->msg . "</b> ".$this->arPrintInfo["start"]." - ".$this->arPrintInfo["end"]." of ".$this->arPrintInfo["total"]."<Br>";				
		}
		return $print_link;
	}
}
?>