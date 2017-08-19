<?php
//-------------------------------------------------------
// Purpose: To generate integer value to words
// functionality 
//
// Author : Rajeev Aggarwal
// Created on : (02.09.2008 )
// Copyright 2008-2000: syenergy Technologies Pvt. Ltd.
//
//--------------------------------------------------------
class NumberToWord{
	
	var $word;
	
function CalculateTenth($twoDigitData)
{
	$firstDigit = floor($twoDigitData/10);
	$secondDigit = $twoDigitData % 10;
	
	switch($firstDigit)
	{
		case 0:
		$firstString = "";
		break;
		
		case 1:
		switch($secondDigit)
		{
			case 0:
			return "ten";
			break;
		
			case 1:
			return "eleven";
			break;
		
			case 2:
			return "twelve";
			break;
		
			case 3:
			return "thirteen";
			break;
		
			case 4:
			return "fourteen";
			break;
		
			case 5:
			return "fiveteen";
			break;
		
			case 6:
			return "sixteen";
			break;
		
			case 7:
			return  "seventeen";
			break;
		
			case 8:
			return "eighteen";
			break;
		
			case 9:
			return "nineteen";
			break;
		
			default:
			return "Error";
			
		}
		break;
		
		case 2:
		$firstString = "twenty";
		break;
		
		case 3:
		$firstString = "thirty";
		break;
		
		case 4:
		$firstString = "forty";
		break;
		
		case 5:
		$firstString = "fifty";
		break;
		
		case 6:
		$firstString = "sixty";
		break;
		
		case 7:
		$firstString = "seventy";
		break;
		
		case 8:
		$firstString = "eighty";
		break;
		
		case 9:
		$firstString = "ninety";
		break;
		
		default:
		return "Error";
	}
	
	switch($secondDigit)
	{
		case 0:
		$secondString = "";
		break;
		
		case 1:
		$secondString = "one";
		break;
		case 2:
		$secondString = "two";
		break;
		
		case 3:
		$secondString = "three";
		break;
		
		case 4:
		$secondString = "four";
		break;
		
		case 5:
		$secondString = "five";
		break;
		
		case 6:
		$secondString = "six";
		break;
		
		case 7:
		$secondString = "seven";
		break;
		
		case 8:
		$secondString = "eight";
		break;
		
		case 9:
		$secondString = "nine";
		break;
		
		default:
		return "Error";
	}
	
	return $firstString." ".$secondString;	
	 
}

function CalculateLastSeven($num)
{
	$length = strlen($num);
	if($length > 5)
	{
		$tenth = substr($num,-2,2); 
		$hundred = substr($num,-3,1);
		
		if($hundred == 0)
			$hundredString = "";
		else
			$hundredString = " hundred ";
			
		$thousand = substr($num,-5,2);
		
		if($thousand == 0)
			$thousandString = "";
		else
			$thousandString = " thousand ";
		
		if($length == 6)
			$lakh = substr($num,-6,1);
		else
			$lakh = substr($num,-7,2);
			
		if($lakh == 0)
			$lakhString = "";
		else
			$lakhString = " lakh ";
		
		return $this->CalculateTenth($lakh).$lakhString.$this->CalculateTenth($thousand).$thousandString.$this->CalculateTenth($hundred).$hundredString.$this->CalculateTenth($tenth);
	}
	else if($length < 6 &&  $length > 3)
	{
		$tenth = substr($num,-2,2); 
		$hundred = substr($num,-3,1);
		
		if($hundred == 0)
			$hundredString = "";
		else
			$hundredString = " hundred ";
			
		$thousand = substr($num,-5,2);
		
		if($length == 4)
			$thousand = substr($num,-4,1);
		else
			$thousand = substr($num,-5,2);
		
		if($thousand == 0)
			$thousandString = "";
		else
			$thousandString = " thousand ";
		
		return $this->CalculateTenth($thousand).$thousandString.$this->CalculateTenth($hundred).$hundredString.$this->CalculateTenth($tenth);
	}
	else if($length < 4 &&  $length > 2)
	{
		$tenth = substr($num,-2,2); 
		$hundred = substr($num,-3,1);
		
		if($hundred == 0)
			$hundredString = "";
		else
			$hundredString = " hundred ";
		
		return $this->CalculateTenth($hundred).$hundredString.$this->CalculateTenth($tenth);
	}
	else if($length < 3)
	{
		return $this->CalculateTenth($num);
	}
	
	else
	{
		return "morethan7";
	}
}

function Convert($string)
{
	$totalLength = strlen($string);
	$startString = substr($string,0,$totalLength % 7);
	$converted = $this->CalculateLastSeven($startString);
	
	$start = $totalLength % 7;
//	$i = 0;
	
	while($part = substr($string,$start,7))
	{
		if($startString != 0)
			$croreString = " crore ";
		$converted .= $croreString.$this->CalculateLastSeven($part);
		$start += 7;
	}
	
	return $converted;
}

function NumberToWord($string)
{
	$stringArr = explode(".", $string);
	$this->word = $this->Convert($stringArr[0]);
	if($stringArr[1]>0)
		$this->word .= " Rupees and ".$this->Convert($stringArr[1])." Paise";

}
}

// $History: NumToWord.class.php $
//
//*****************  Version 1  *****************
//User: Pushpender   Date: 12/02/08   Time: 6:00p
//Created in $/LeapCC/Library
//
//*****************  Version 2  *****************
//User: Rajeev       Date: 10/08/08   Time: 3:49p
//Updated in $/Leap/Source/Library
//updated QA bugs
//
//*****************  Version 1  *****************
//User: Rajeev       Date: 9/02/08    Time: 3:52p
//Created in $/Leap/Source/Library
//intial checkin
?>