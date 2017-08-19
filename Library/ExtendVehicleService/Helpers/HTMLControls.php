<?php
/*
			-------File Info Header Starts-------
			
			*	@	Module Name			:	This Class is create and return different html controls
			*	@	Tables Refered		:	tblCountires
			*	@	Tables Updated		:	NA
			*	@	Bugs Listed			:	NA
			
			
*/
class HTMLControls{
	
	public function __construct(){;
	}	
	
	
	/**
		* getCountryControl()		: create html for countries drop down menu with specified country as selected
		* 
		* @param $default			: Country id to be set as default
		* @return 					: String - html
	**/
	
	public static function getCountryControl($default=''){
		require_once(BL_PATH."/Countries.php");
		$objCounries = new Countries();
		$countries = $objCounries->getCountries();
		$HTML = '';
		foreach($countries as $country){
			$selected = ($country['code']==$default)?" selected = 'selected'":"";
			if(strlen($country['country'])>32)
				$country['country'] = substr($country['country'],0,32)."...";
			$HTML .= '<option value="'.$country['code'].'"  '.$selected.'>'.$country['country'].'</option>\n';
		}
		return $HTML;
	}
	
	/*
	function getYears 
	@param $startingYear First option value to be displayed in select box
	@param $totalOptions Total number of records to be shown
	@param $order='ASC' order in which years should be displayed ASC or DESC
	@param $selected any selected value
	@return string representation of option value pair
	*/
	
	public static function getYears($startingYear, $totalOptions, $order='ASC', $selected=''){
		if($order == 'ASC'){
			$slot = 1;
		}else {
			$slot = -1;
		}
		$str = "";
		for($i=0; $i <= $totalOptions; $i++){
			$str .= "<option value='" . $startingYear . "'";
			if($selected == $startingYear){
				$str .= " selected='selected' ";
			}
			$str .=">" .$startingYear . "</option>";
			$startingYear += $slot;
		}
		return $str;
		
	}
	
	
		/*
	function getMonths 
	@param $selected option value to be selected
	@returns list of options values for select box for month drop down
	*/
	
		
	public static function getMonths($selected=''){
		$montharray = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		$str = "";
		for($i=1; $i<=12; $i++){
			$str .= "<option value='".$i."' ";
			if($selected == $i){
				$str .=" selected='selected' ";
			}
			$str .=">". $montharray[$i-1] ."</option> ";
		} 
		return $str;
	}
	
		/*
	function getDays 
	@param $selected option value to be selected
	@returns list of options values for select box for days drop down
	*/
	
		
	public static function getDays($selected=''){
		$str = "";
		for($i=1; $i<=31; $i++){
			$str .= "<option value='".$i."' ";
			if($selected == $i){
				$str .=" selected='selected' ";
			}
			$str .=">". $i ."</option> ";
		} 
		return $str;
	}
}
?>