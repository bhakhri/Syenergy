var gl_el; //global element
var noDataFoundVar = 'No Data Found';
//following variable is for data display throughout application.
globalTB = '<table border="0" cellpadding="1" cellspacing="1" width="100%">';

//function compares two dates (string format) date format = YYYY-MM-DD
/* @return: -1 value1 < value2
			 1 value1 > value2
			 0  value1 = value2
*/			
// trim() function defined to remove spaces
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/, ''); };


function dateCompare (value1, value2) {
   var date1, date2;
   var month1, month2;
   var year1, year2;

   year1  = value1.substring (0, value1.indexOf ("-"));
   month1 = value1.substring(value1.indexOf ("-")+1, value1.lastIndexOf ("-"));
   date1  = value1.substring (value1.lastIndexOf ("-")+1, value1.length);

   year2  = value2.substring (0, value2.indexOf ("-"));
   month2 = value2.substring(value2.indexOf ("-")+1, value2.lastIndexOf ("-"));
   date2  = value2.substring (value2.lastIndexOf ("-")+1, value2.length);

   year1  = parseFloat(year1);
   month1 = parseFloat(month1);
   date1  = parseFloat(date1);

   year2  = parseFloat(year2);
   month2 = parseFloat(month2);
   date2  = parseFloat(date2);

   if (year1 > year2){ return 1;}
   else { 
		if (year1 < year2){  return -1;}
		else { //means year1=year2
			if (month1 > month2){ return 1;}
			else{ 
				if (month1 < month2) { return -1;}
				else {//means month1=month2
					if (date1 > date2){ return 1;}
					else { 
						if (date1 < date2) {  return -1;}
						else{ //means date1=date2
							return 0;
						}
					}
				}
			}
		}
   }
}
function checkLandlinePhoneLimit(s,upperLimit,lowerLimit){
	if(s>upperLimit || s<lowerLimit){
		alert("invalid contact no length");
	}
}
function checkMobilePhoneLimit(s,limit){
	if(s>limit || s<limit){
		alert("invalid mobilePhone length");
	}
}
function counterText(field, countfield, maxlimit) {
	/*
	* The input parameters are: the field name;
	* field that holds the number of characters remaining;
	* the max. numb. of characters.
	*/
	if (field.value.length > maxlimit) // if the current length is more than allowed
		field.value =field.value.substring(0, maxlimit); // don't allow further input
	else
		countfield.value = maxlimit - field.value.length;
} // set the display field to remaining number

//parameter field is the id of field to be autosuggested
function autoSuggest(field) {
return false;
var oTextbox = new AutoSuggestControl(field, new SuggestionProvider(), 'rollNumber');

}

/*
	validation function
	
*/
function trim(str){
    str = str.replace(/^\s+/, '');
    for (var i = str.length - 1; i >= 0; i--) {
        if (/\S/.test(str.charAt(i))) {
            str = str.substring(0, i + 1);
            break;
        }
    } 
    return str;
}

function isCharsInBag (s, bag)
  {
    var i;
    // Search through string's characters one by one.
    // If character is in bag, append to returnString.
	if(s == null) {
		return false;
	}

    for (i = 0; i < s.length; i++)
    {
        // Check that current character isn't whitespace.
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) return false;
    }
    return true;
 }

//function to check valid zip code
function isZIP(s) 
  {
    return isAlphaNumeric(s);
 }
 
//function to check time input (hh:mm:ss)
function isTime(s){
    var i;
    var str="";
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        str=str+""+trim(c);
        if (c !=':' && !isNumeric(c)){
         return false;
        } 
    }
    var s1= str.split(':');
    if(s1.length != 3){
      return false;
    }
   else{
    if( (s1[0]!="" && s1[0] <24 ) && (s1[1]!="" && s1[1] <=59 ) && (s1[2]!="" && s1[2] <=59) ){
     return true;
    }
    else{
    return false;
   }  
 } 
}

//function to check time input(hh:mm)
function isTime2(s){
    var i;
    var str="";
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        str=str+""+trim(c);
        if (c !=':' && !isNumeric(c)){
         return false;
        } 
    }
    var s1= str.split(':');
    if(s1.length != 2){
      return false;
    }
   else{
    if( (s1[0]!="" && s1[0] <=24 ) && (s1[1]!="" && s1[1] <=59 )){
     return true;
    }
    else{
		alert("Please enter minutes less than 60");
   }  
 } 
}
function isTime3(s){
    var i;
    var str="";
	
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        str=str+""+trim(c);

        if (c !=':' && !isNumeric(c)){
         return false;
        } 
	
	else {
			return true;
		}
    }
}
  

//function to check valid Telephone,. Fax no. etc
function isPhone(s)
  {
	return isCharsInBag (s, "0123456789-+(). ");//simple test
	
	var PNum = new String(s);
	
	//	555-555-5555
	//	(555)555-5555
	//	(555) 555-5555
	//	555-5555

    // NOTE: COMBINE THE FOLLOWING FOUR LINES ONTO ONE LINE.
	var regex = /^[0-9]{3,3}\-[0-9]{3,3}\-[0-9]{4,4}$|^\([0-9]{3,3}\) [0-9]{3,3}\-[0-9]{4,4}$|^\([0-9]{3,3}\)[0-9]{3,3}\-[0-9]{4,4}$|^[0-9]{3,3}\-[0-9]{4,4}$/;
//	var regex = /^\([1-9]\d{2}\)\s?\d{3}\-\d{4}$/; //(999) 999-9999 or (999)999-9999
	if( regex.test(PNum))
		return true;
	else
		return false;
	
	/*//code1
	var phone2 = /^(\+\d)*\s*(\(\d{3}\)\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/; 
	if (s.match(phone2)) {
   		return true;
 	} else {
 		return false;
 	}

	
	*/
	
	/*//code2
	var stripped = s.replace(/[\(\)\.\-\ ]/g, '');
//strip out acceptable non-numeric characters
	if (isNaN(parseInt(stripped))) {
	   return false;
	}
	
	
	if (!(stripped.length == 10)) {
			return false;
	}
	*/
	
	/*//code3
	if (isCharsInBag (s, "- +().,/;0123456789") == false)
    {
        return false;
    }
    return true;
	*/
 }

/*
function added to apply custom checking. 
@param s: string to be checked
@param includeChars: string of chars, to be included in a-zA-Z0-9
*/
function isAlphaNumericCustom(s, includeChars) {
	if (typeof includeChars === "undefined") {
		includeChars = "";
	}
	var str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789" + includeChars;
	return isCharsInBag (s, str);
}


/*
function added to apply custom checking. 
@param s: string to be checked
@param includeChars: string of chars, to be included in a-zA-Z0-9
*/
function isAlphaCharCustom(s, includeChars) {
    if (typeof includeChars === "undefined") {
        includeChars = "";
    }
    var str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" + includeChars;
    return isCharsInBag (s, str);
}

/*
function added to apply custom checking.
@Created By: Pushpender Kumar Chauhan  
@param s: string to be checked
@param includeChars: string of chars, to be included in a-zA-Z0-9
*/
function isNumericCustom(s, includeChars) {
    if (typeof includeChars === "undefined") {
        includeChars = "";
    }
    var str = "0123456789" + includeChars;
    return isCharsInBag (s, str);
}

function isNumeric(s) {
	var str = "0123456789";
	return isCharsInBag (s, str);
}

function isAlphaNumeric(s){
    return isCharsInBag (s, "()-_abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789 ");
}
//
function isAlphaNumericdot(s){
    return isCharsInBag (s, ":.0123456789 ");
}
function isAlphabetCharacters(s){
    return isCharsInBag (s, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&.-() ");
}

function isAlphabetSpecialCharacters(s){
    return isCharsInBag (s, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&.-,/+ ");
}

function isEmpty(s)
{
		  s=trim(s);
		  return ((s == null) || (s.length == 0))
}

//function to check valid email id
function isEmail(s)
{
	 
	/*//code 3.
	var regex = /^([\w]+)(.[\w]+)*@([\w]+)(.[\w]{2,3}){1,2}$/;
    return regex.test(s);
	*/
	
	var regex = /(^[a-z]([a-z_\.]*)@([a-z_\.]*)([.][a-z]{3})$)|(^[a-z]([a-z_\.]*)@([a-z_\.]*)(\.[a-z]{3})(\.[a-z]{2})*$)/i
	var regex =	/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return regex.test(s);
	

	/*//code 2.
	var emailReg = "^[\\w-_\.]*[\\w-_\.]\@[\\w]\.+[\\w]+[\\w]$";
	var regex = new RegExp(emailReg);
	return regex.test(s)
	*/

	/* //code 1.
	var emailFilter = /^.+@.+\..{2,3}$/;
	if (!(emailFilter.test(s))) { 
		return false;
	}

	//we want to check to make sure that no forbidden characters have slipped in. For email addresses, we’re forbidding the following: ( ) < > [ ] , ; : \ / "
	var illegalChars= /[\(\)\<\>\,\;\:\\\/\"\[\]]/
	if (s.match(illegalChars)) {
	   return false;
	}	
	return true;
	*/
}

function checkDomain(nname)
{
var arr = new Array(
'.com','.net','.org','.biz','.coop','.info','.museum','.name',
'.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag',
'.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw',
'.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm',
'.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc',
'.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr',
'.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz',
'.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm',
'.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm',
'.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm',
'.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq',
'.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki',
'.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li',
'.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg',
'.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt',
'.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng',
'.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf',
'.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py',
'.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg',
'.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv',
'.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn',
'.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um',
'.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws',
'.wf','.ye','.yt','.yu','.za','.zm','.zw');

var mai = nname;
var val = true;

var dot = mai.lastIndexOf(".");
var dname = mai.substring(0,dot);
var ext = mai.substring(dot,mai.length);
//alert(ext);
    
if(dot>2 && dot<57)
{
    for(var i=0; i<arr.length; i++)
    {
      if(ext == arr[i])
      {
         val = true;
        break;
      }    
      else
      {
         val = false;
      }
    }
    if(val == false)
    {
         alert("Website extension "+ext+" is not correct");
         return false;
    }
    else
    {
        for(var j=0; j<dname.length; j++)
        {
          var dh = dname.charAt(j);
          var hh = dh.charCodeAt(0);
          if((hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==45 || hh==46)
          {
             if((j==0 || j==dname.length-1) && hh == 45)    
               {
                    alert("Website name should not begin are end with '-'");
                  return false;
              }
          }
        else    {
               alert("Website name should not have special characters");
             return false;
          }
        }
    }
}
else
{
 alert("Website name is too short/long");
 return false;
}    

return true;
}

//format YYYY-MM-DD
function isValidDate(strdate) { 
 // alert(strdate)
  var datedelimiter = '-';
  var datesplit = strdate.split(datedelimiter)
  if (datesplit.length > 3) {return false;}
  var month = 0; 
  month = datesplit[1];
  if (month < 1 || month >12 ) {return false;}
  if (isNaN(datesplit[0])) {return false;}
  else if (isNaN(datesplit[1])) {return false;}
  else if (isNaN(datesplit[2])) {return false;}
  else {
    //var year = parseInt(datesplit[2],10);
    var yearLn = (datesplit[0].length);
    var year= datesplit[0];
    	
    if (yearLn==1){return false;}
    if (yearLn==3){return false;}
    if (year<1){return false;}
    if (yearLn==2){
         year = '20'+ year
    }

   //var year = year;
   // alert(year)
    // var year = (datesplit[2],10);

    var day = parseInt(datesplit[2],10);
     if(day<0){return false;}
	if (day>31){return false;}
    if ((day > 30) && ((month == 4) || (month == 6) || (month == 9) || (month == 11))) {return false;}
    if (month == 2) {  // This calculates the basic leap year no matter the format, i.e. 2000 or 00. 
		var leap = ((year/4) == parseInt(year/4))
		if (leap) {if (day > 29) {return false;}
		}else {if (day > 28) {return false;}
      }
    }
  } 
  return true;
}

function isbigdateTime(StDate, StTime, EdDate, EdTime)
 {
//alert('start' + StTime + 'end' + EdTime)
	//var DTDelimiter = ' ';
	var DateDelimiter='/';
	var TimeDelimiter=':';
	
	//Split start date and time.
	//var StDTSplit = StDateTime.split(DTDelimiter);
	//if (StDTSplit.length>2){return false;}
	//var StDate=StDTSplit[0];
	//var StTime=StDTSplit[1];
	var StDSplit = StDate.split(DateDelimiter);//Splite date into MM/DD/YYYY
	//alert(StDSplit.length)
	if (StDSplit.length>3){return false;}
	var StMM=parseInt(StDSplit[0]);
	var StDD=parseInt(StDSplit[1]);
	var StYY=parseInt(StDSplit[2]);
	var StTSplit = StTime.split(TimeDelimiter);//Splite time into H:M
	if (StTSplit.length>2){return false;}
	var StH=StTSplit[0];
	var StM=StTSplit[1];
	//alert('StMM' + StMM + '  StDD' + StDD + '  StYY' + StYY + '  StH' + StH + '  StM' 

//+ StM);
	
	//Split end date and time.
	//var EdDTSplit = EdDateTime.split(DTDelimiter);
	//if (EdDTSplit.length>2){return false;}
	//var EdDate=EdDTSplit[0];
	//var EdTime=EdDTSplit[1];
	var EdDSplit = EdDate.split(DateDelimiter);//Splite date into MM/DD/YYYY
	if (EdDSplit.length>3){return false;}
	
	var EdMM=parseInt(EdDSplit[0]);
	var EdDD=parseInt(EdDSplit[1]);
	var EdYY=parseInt(EdDSplit[2]);
	var EdTSplit = EdTime.split(TimeDelimiter);//Splite time into H:M
	//alert(EdTSplit.length)
	if (EdTSplit.length>2){return false;}
	var EdH=EdTSplit[0];
	var EdM=EdTSplit[1];
	//alert('time'+ EdH)
	//alert('EdMM' + EdMM + '  EdDD' + EdDD + '  EdYY' + EdYY + '  EdH' + EdH + '  EdM' 

//+ EdM);
	
	if(StYY>EdYY){
	    form1.txtDateEnd.focus()
		return false;
	}
	else if(StYY==EdYY && StMM>EdMM){return false;}	
	else if(StYY==EdYY && StMM==EdMM && StDD>EdDD){return false;}	
	else if(StYY==EdYY && StMM==EdMM && StDD==EdDD && StH>EdH){
		//alert("time HH")
		form1.selEndTime.focus(); 
		return false;
	}else if(StYY==EdYY && StMM==EdMM && StDD==EdDD && StH==EdH && StM>=EdM){
		//alert("time MM")
		form1.selEndTime.focus(); 
		//form.selEndTime.focus();
		return false;
	}	return true;	
}

function OpenWin(width,height,URL,title)
{
	window.open(URL,'',"height=" + height + ",width=" + width + ",toolbar=no,location=no,directories=no,status=no,menubar=no,,scrollbars=yes,resizable=yes");
}


function SelectOption(pObj,pSelOption)
{
	for(var i=0;i<pObj.options.length;i++)
	{
		if(pObj.options[i].value==pSelOption)
		{
			pObj.options[i].selected=true;
		}
	}
}

//Validation for numeric fields
function isInteger(var1){
	str = var1;
	return isCharsInBag (str, "0123456789");
}
//--------------------------------------------------------------------------
//Purpose : validation for decimal values
//Author:Dipanjan Bhattacharjee
//Date :09.08.2008
//--------------------------------------------------------------------------
function isDecimal(value){
   var temp=value;
   var a=value.split(".");
   if(a[0]=="" && value !=""){
    temp="0"+temp;
   }
  var r=(/^-?\d+(\.\d+)?$/.test(temp));
  return r;
}

//Validation for radio buttons
function validateRadio(var1, var2){
	var sCheck = "N";
	for (i = 0; i < var1.length; i++){
		if (var1[i].checked == true){
		  sCheck = "Y";
		}
	}
	if (sCheck != "Y"){
		return false;
	}
	return true;
}

//Validation for percentage
function validatePercentage(var1, var2){
	var fld = "";
	fld = var1.value;
	if (fld > 100 && fld != ""){
		return false;
	}
	return true;
}

function isValidateUrl(strUrl) {
    var v = new RegExp();
    v.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
    if (!v.test(strUrl)) { 
        return false;
    }
	return true;
} 
  // This function accepts a string variable and verifies if it is a
  // proper date or not. It validates format matching either
  // mm-dd-yyyy or mm/dd/yyyy. Then it checks to make sure the month
  // has the proper number of days, based on which month it is.	 
  // The function returns true if a valid date, false if not.
  // ******************************************************************	 
  function isDate(dateStr) 
  {

	   var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	   var matchArray = dateStr.match(datePat); // is the format ok?
	   months= new Array(12);
	   months[0]="Jan";
	   months[1]="Feb";
	   months[2]="Mar";
	   months[3]="Apr";
	   months[4]="May";
	   months[5]="Jun";
	   months[6]="Jul";
	   months[7]="Aug";
	   months[8]="Sep";
	   months[9]="Oct";
	   months[10]="Nov";
	   months[11]="Dec"; 
	  if (matchArray == null) 
	  {
		 alert("Please enter date as either mm/dd/yy or mm-dd-yy.");
         return false;
	  }
 
	  month = matchArray[1]; // parse date into variables
	  day = matchArray[3];
	  year = matchArray[5];

	  if (month < 1 || month > 12) // check month range
	  { 
		  alert("Month must be between 1 and 12.");
		  return false;
	  }
 
	  if (day < 1 || day > 31) 
	  {
		  alert("Day must be between 1 and 31.");
		  return false;
	  }
 
	  if ((month==4 || month==6 || month==9 || month==11) && day==31) 
	  {
		  alert("Month "+ months[month-1]+" doesn't have 31 days!")
		  return false;
	  }
 
	  if (month == 2)  // check for february 29th
	  {
		  var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		  if (day > 29 || (day==29 && !isleap)) 
		  {
			  alert("February " + year + " doesn't have " + day + " days!");
			  return false;
		  }
	  }
	  return true; // date is valid
  }
  
  
 
  // This function accepts a string variable and verifies if it is a
  // proper date or not. It validates format matching either
  // mm-dd-yyyy. Then it checks to make sure the month
  // has the proper number of days, based on which month it is.     
  // The function returns true if a valid date, false if not.
  // ******************************************************************     
  function isDate2(dateStr) 
  {

       var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
       var matchArray = dateStr.match(datePat); // is the format ok?
       months= new Array(12);
       months[0]="Jan";
       months[1]="Feb";
       months[2]="Mar";
       months[3]="Apr";
       months[4]="May";
       months[5]="Jun";
       months[6]="Jul";
       months[7]="Aug";
       months[8]="Sep";
       months[9]="Oct";
       months[10]="Nov";
       months[11]="Dec"; 
      if (matchArray == null) 
      {
         alert("Enter date in correct format (dd-mm-yy).");
         return false;
      }
 
      month = matchArray[1]; // parse date into variables
      day = matchArray[3];
      year = matchArray[5];

      if (month < 1 || month > 12) // check month range
      { 
          alert("Month must be between 1 and 12.");
          return false;
      }
 
      if (day < 1 || day > 31) 
      {
          alert("Day must be between 1 and 31.");
          return false;
      }
 
      if ((month==4 || month==6 || month==9 || month==11) && day==31) 
      {
          alert("Month "+ months[month-1]+" doesn't have 31 days!")
          return false;
      }
 
      if (month == 2)  // check for february 29th
      {
          var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
          if (day > 29 || (day==29 && !isleap)) 
          {
              alert("February " + year + " doesn't have " + day + " days!");
              return false;
          }
      }
      return true; // date is valid
  } 

// This function accepts a string variable and verifies if it is a
  // proper date or not. It validates format matching either
  // mm-dd-yyyy or mm/dd/yyyy. Then it checks to make sure the month
  // has the proper number of days, based on which month it is.	 
  // The function returns true if a valid date, false if not.
  // This function is modified for message 
  // Modified by Jaineesh
  // ******************************************************************	 
  function isDate1(dateStr) 
  {

	   var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
	   var matchArray = dateStr.match(datePat); // is the format ok?
	   months= new Array(12);
	   months[0]="Jan";
	   months[1]="Feb";
	   months[2]="Mar";
	   months[3]="Apr";
	   months[4]="May";
	   months[5]="Jun";
	   months[6]="Jul";
	   months[7]="Aug";
	   months[8]="Sep";
	   months[9]="Oct";
	   months[10]="Nov";
	   months[11]="Dec"; 
	  if (matchArray == null) 
	  {
		  alert("Please enter date");
		  return false;
	  }
 
	  month = matchArray[1]; // parse date into variables
	  day = matchArray[3];
	  year = matchArray[5];

	  if (month < 1 || month > 12) // check month range
	  { 
		  alert("Month must be between 1 and 12.");
		  return false;
	  }
 
	  if (day < 1 || day > 31) 
	  {
		  alert("Day must be between 1 and 31.");
		  return false;
	  }
 
	  if ((month==4 || month==6 || month==9 || month==11) && day==31) 
	  {
		  alert("Month "+ months[month-1]+" doesn't have 31 days!")
		  return false;
	  }
 
	  if (month == 2)  // check for february 29th
	  {
		  var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
		  if (day > 29 || (day==29 && !isleap)) 
		  {
			  alert("February " + year + " doesn't have " + day + " days!");
			  return false;
		  }
	  }
	  return true; // date is valid
  }

  function selval(sellist, selvalue){
  	for(iVar=0;iVar<sellist.options.length;iVar++){
		if (selvalue == ""){
			sellist.selectedIndex = 0;
			return;
		} else if (sellist.options[iVar].value == selvalue){
			sellist.selectedIndex = iVar;
			return;
		}
	}
	return;
  }


  //set focus on el or global variable gl_el;
  function setFocus(el){
	if(el != "undefined"){
		document.getElementById(el).focus();
		setAlertStyle(el);
	//	addEvent(document.getElementById(el),'onblur',function(){ alert('hello!'); })
	}else if(typeof gl_el != "undefined"){
		gl_el.focus();	
	}
	return true;
	
  }
  
  function setAlertStyle(el){
	document.getElementById(el).style.border = '2px solid';
	document.getElementById(el).style.borderColor = 'RED';
  }
  
  function unsetAlertStyle(el){
	//document.anchors[elm].removeProperty("color");  
	//document.getElementById(el).style.removeProperty("border");  
	//document.getElementById(el).style.removeProperty("borderColor");  
	document.getElementById(el).style.border = '1px solid';
	document.getElementById(el).style.borderColor = '';
  }

  function addEvent( obj, type, fn ) {
   if ( obj.attachEvent ) {
     obj['e'+type+fn] = fn;
     obj[type+fn] = function(){obj['e'+type+fn]( window.event );}
     obj.attachEvent( 'on'+type, obj[type+fn] );
   } else
     obj.addEventListener( type, fn, false );
 }
 
 
 
/*
 * msg = message to print
 * callback = function to be called when ok is clicked
 * el is the element to be passed when cllback is called
 */
function winAlert(msg,el,callback){
	if(!callback)
		callback = 'setFocus';
	
	/*
	alert(el);
	eval(callback+'("'+el+'");');*/
	
	Dialog.alert(
		'<span class="arial14">'+msg+'</span>', 
		{
			windowParameters: 
			{
				className: "alphacube",
				width:300
			},
			
			okLabel: "OK",
			ok: function(win) { eval(callback+'("'+el+'");'); return true;}
		}
	);
	
}


/*
 * msg = message to print
 * callback = function to be called when ok is clicked
 * el is the element to be passed when cllback is called
 */
function winConfirm(msg,okcallback,cancelcallback){
	Dialog.confirm(
		msg,
		{
			windowParameters: 
			{
				className: "alphacube",
				width:300
			}, 
			
			okLable: "Ok",
			cancelLable: "Cancel",
			ok: function(win) { eval(okcallback); return true;},
			cancel:function(win) {
				if(cancelcallback){
					eval(cancelcallback);
				}
				return true;
			} 
		 }
	);	
}

function winOpen(url,width,height,name){
	if(!name)
		name = '';
	if(!width)
		width  = 800;
	if(!height)
		height = 500;
	leftVal = (screen.width - width) / 2;
	topVal = (screen.height - height) / 2;
	popUp = window.open(url,name,'scrollbars=1,resizable=1,width='+width+',height='+height+',left='+leftVal+',top='+topVal);
	return popUp;
}



function winClose(){
	window.close();
}

function intergerValue(value) {
	return parseInt(value);
}

function addOption(obj, val, txt) {
	var objOption = new Option(txt, val);
	obj.options.add(objOption);
}



function checkAtLeastOneBox(name) {
	count = 0;
	elements = document.getElementsByName(name);
	len = elements.length;

	for (var i = 0; i < len; i++) {
		var e = elements[i];
		if (e.checked) {
			count=count+1;
		}
	}

	if (count == 0){
		return false;
	}
	else {
		return true;
	}
}

	function redirectURL(rediectUrl) {
		location.href= rediectUrl;
	}

	function removeHTMLTags(html){
		if(!isEmpty(html)){
			var strInputCode = html;
			/* 
				This line is optional, it replaces escaped brackets with real ones, 
				i.e. &lt; is replaced with < and &gt; is replaced with >
			*/	
			strInputCode = strInputCode.replace(/&(lt|gt);/g, function (strMatch, p1){
				return (p1 == "lt")? "<" : ">";
			});
			strTagStrippedText = strInputCode.replace(/(&nbsp;)/ig, "");
			return strTagStrippedText = strTagStrippedText.replace(/<\/?[^>]+(>|$)/g, "");
		}	
	}

function displayWindow(id,w,h) {
    w = (w=='undefined' || w=='' ) ? 300 : w;
    h = (h=='undefined' || h=='' ) ? 200 : h;
    l = screen.width/3;
    t = screen.height/4;

    /*if(w <= document.body.clientWidth) {
        l = (document.body.clientWidth - w)/2;
    }
    else {
        l = 0;
    }
    if(h <= document.body.clientHeight) {
        t = (document.body.clientHeight - h)/2;
    }
    else {
        t = 0;
    }   
    */      
    displayFloatingDiv(id,'', w, h, l, t);
}


function displayWindowNew(id,w,h) {
   w = (w=='undefined' || w=='' ) ? 300 : w;
   h = (h=='undefined' || h=='' ) ? 200 : h;
   l = screen.width/3;
   t = 100; 
   displayFloatingDivNew(id,'', w, h, l, t);
}

/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 30.07.2008
@@purpose: This shows the 'Loading' popup message when ajax reguest goes to the server
@@params: nothing
@@return: nothing
*/
function showWaitDialog(disableScreenValue) {
          if(typeof disableScreenValue == "undefined") {
              disableScreenValue = false;
          }
          if (disableScreenValue == true) {
              makeScreenDisable();
          }
          if(document.getElementById('dialog_overlay_wait')) {
              dvObj = document.getElementById('dialog_overlay_wait');
          }
          else {
              dvObj = document.createElement('div');
              dvObj.id = 'dialog_overlay_wait';
              document.body.insertBefore(dvObj, document.body.childNodes[0]);              
          }
          dvObj.style.display='none';
          dvObj.innerHTML = '&nbsp;Thinking...&nbsp;';
          //dvObj.innerHTML = '<img src="'+imagePathURL+'/loading.gif" border="0" />'
          dvWidth  = 100;
          dvHeight = 20;
          winH = document.body.clientHeight;
          winW = document.body.clientWidth;
          dvObj.style.top = ((winH - dvHeight)/2) +'px';
          dvObj.style.left = ((winW - dvWidth)/2)+'px';
          dvObj.style.zIndex = 1000000;
          dvObj.style.border = '4px solid #454545';
          dvObj.style.width  = dvWidth;
          dvObj.style.height = dvHeight;
          dvObj.style.font   = 'normal bold 10pt verdana';
          dvObj.style.color   = '#ffffff';
          dvObj.style.backgroundColor = '#73AACC';  //#DD6F00
          dvObj.style.position = 'absolute';
          dvObj.style.display = 'block';
}
/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 30.07.2008
@@purpose: This hides the message showed by showWaitDialog() function 
@@params: nothing
@@return: nothing
*/
function hideWaitDialog(enableScreenValue) {
        if(typeof enableScreenValue == "undefined") {
          enableScreenValue = false;
        }
        if (enableScreenValue == true) {
          makeScreenEnable();
        }
        if(document.getElementById('dialog_overlay_wait')) {
            document.getElementById('dialog_overlay_wait').style.display = 'none';
        }
}
/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 25.06.2008
@@purpose: it facilitates you to send http request to the server with taking 4 parameters
@@params: URL= server php file, resultDiv= div for results, formName= name of search form(optional), queryString for pagination
@@return: nothing, just populate the results on the web page.
*/
function sendReq(url, resultDiv, formName, queryString,asyn) {

    // get all the form values

//	if ((typeof dontMakeQueryString === "undefined")) {
		if(formName) {
		  if(queryString) {
			  // generate query string from all form fields
             try{ 
			  queryString += '&'+generateQueryString(formName);
             }catch(e){}
		  }
		  else {
              try{
			    queryString = generateQueryString(formName);
              }catch(e){}
		  }
		}
//	}
  //alert(queryString);
  //alert($(formName).serialize(true));
  new Ajax.Request(url, {
  method: 'post',
  asynchronous: ((asyn=='undefined' ? true : asyn ) ),
  onCreate: function() {
    showWaitDialog(true);
  },
  parameters: queryString,
  onSuccess: function(httpObj) {
          //alert(httpObj.responseText);
          //j = eval('('+httpObj.responseText+')');
          // create Json object
          hideWaitDialog(true);
          j = httpObj.responseText.evalJSON();
          //assign page value to global variable
          page = j.page != 'undefined' ? j.page : page;
          sortField = j.sortField != 'undefined' ? j.sortField : sortField;
          sortOrderBy = j.sortOrderBy != 'undefined' ? j.sortOrderBy : sortOrderBy;
          // populate table with results 
          printResults(resultDiv, j.info, j.page, j.totalRecords, tableHeadArray, formName);
  }
});

}
/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 25.06.2008
@@purpose: To print results from the database through ajax
@@params: dv=div layer in which the results to be populated, resultArray=json array containing the data, page=which page we are currently on, totalRecords= total records found as per search, tableHeadArray=the array which contains the key(field name from the table) and label for table head, formName=the name of search form if any
@@return: returns the results into tabular format
@@modified by : Dipanjan Bhattacharjee
*/
var specialFormatting=1; //this indicates whether special formatting should be done or not.Default : 1
function printResults(dv, resultArray, page, totalRecords, tableHeadArray, formName) {
     newArr.splice(0,newArr.length);
     lastColoredRow=0;
     var sortFieldActualName='';
            var tdCnt=0; 
            tb ='';
            tb = globalTB;  
            //for head labels
            headLength = tableHeadArray.length;
            tb += '<tr class="rowheading">';
            for(i=0;i<headLength;i++) {
              // alert(tableHeadArray[i][4]);
              if(tableHeadArray[i][4]!='undefined' && tableHeadArray[i][4]===true) {
                 //if(tableHeadArray[i][5]=='undefined') {  
                    if( tableHeadArray[i][0] == sortField) {
                        sortFieldActualName=tableHeadArray[i][1];      //get the actual name of sort field i.e which is diplayed to the user
                        if(sortOrderBy == 'ASC') {
                            showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page='+page+'&sortField='+sortField+'&sortOrderBy=DESC\');return false;" src="'+imagePathURL+'/arrow-up.gif" border="0"/>';
                        }
                        else {  
                            showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page='+page+'&sortField='+sortField+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-down.gif" border="0"/>';
                        }
                    }
                    else {
                         showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page=1&sortField='+tableHeadArray[i][0]+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-none.gif" border="0"/>';
                    }
                    //sortTableField
                 //}
              }
              else {
                  showSortBox = '';
              } 
               wdth = tableHeadArray[i][2]!='undefined' ? tableHeadArray[i][2] : '';  //cell width
               aln = tableHeadArray[i][3]!='undefined' ? tableHeadArray[i][3] : '';  //align
               tb += '<td class="searchhead_text" '+wdth+' '+aln+'>'+tableHeadArray[i][1]+'&nbsp;'+showSortBox+'</td>';                        
               if(tableHeadArray[i][5]!='undefined' && tableHeadArray[i][5]===true) {
                  tb += '</tr>';
                  tb += '<tr class="rowheading">';
                  tdCnt++;
               }
             }
             tb += '</tr>';
              
              //headLength=headLength-tdCnt; 
            
                len = resultArray.length;
                            
                var exRow=0;
                var exFlag=0;
                var mField='';
                var mRow='';
                if(len!='undefined' && len>0) {
                        for(i=0;i<len;i++) {
                           mRow='';
                           if(resultArray[i][this.sortField]){  
                              if(trim(resultArray[i][sortField]).toUpperCase()== mField) {
                                exRow ++;
                              }
                              else{
                                exRow=0;
                                exFlag++;
                                mField = trim(resultArray[i][sortField]).toUpperCase();
                              }
                              if(specialFormatting=='1'){
                                //pushes "summary rows"
                                arrayPush(sortFieldActualName,resultArray[i][sortField],exRow,(sortField+exFlag),headLength);
                              }
                           }
                            
                           var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                           var bg2 = bg2 == "row0" ? "row1" : "row0";
                           trId = 'trPrintResult_'+i;
                           mRow +='<tr '+bg+' id="'+sortField+exFlag+""+"_"+exRow+'" value="'+bg2+'" onmouseover="if(this.className != \'specialHighlight\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\') this.className=\''+bg2+'\'" >';
                           for(h=0;h<headLength;h++) {
                                aln = tableHeadArray[h][3]!='undefined' ? tableHeadArray[h][3] : '';  //align
                                if(tableHeadArray[h][5]!='undefined' && tableHeadArray[h][5]===true) {
                                }
                                else {
                                  if(tableHeadArray[h][0]=='action') {
                                     mRow +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><img src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="editWindow('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+',\''+editFormName+'\','+winLayerWidth+','+winLayerHeight+');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'+imagePathURL+'/delete.gif" border="0" onClick="'+deleteFunction+'('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+');"/></a></td>';
                                    //mRow +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><input type="image" name="edit_icon_image" src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="editWindow('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+',\''+editFormName+'\','+winLayerWidth+','+winLayerHeight+');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><input type="image" name="delete_icon_image" src="'+imagePathURL+'/delete.gif" border="0" onClick="'+deleteFunction+'('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+');"/></a></td>';
                                  }
                                  else {

                                          resultVal = eval('resultArray['+i+'].'+tableHeadArray[h][0]);
                                          if(typeof resultVal !== "undefined") {
                                            cssClass = isNumericCustom(resultVal,'.-') ? '' : 'padding_top'; 
                                          }
                                          else {
                                            cssClass = 'padding_right'; 
                                          }    
                                          mRow +='<td class="'+cssClass+'" '+aln+'>'+resultVal+'&nbsp;</td>';
                                  }
                                }
                           }
                           mRow +='</tr>';
                           
                           //pushes "normal rows"
                           newArr.push(new Array('!~@~!',mRow,0));
                        }
                        
                        var length=newArr.length;
                        for(var k=0;k<length;k++){
                          tb +=newArr[k][1];
                        }
                }
                else {
                    tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';
                    // in case user deletes all the records on some page say page=7, then redirect to previous page
                    if(parseInt(page)>1 && parseInt(totalRecords) >0) {
                        sendReq(listURL,divResultName,searchFormName,'page='+(parseInt(page)-1)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    }
            }
            
            pageLinks = pagination(page,totalRecords,recordsPerPage,linksPerPage,formName,dv);

            if(pageLinks!='') {
                tb +='<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
            }
            tb +='</table>'; 
            if(dv!='') {
                document.getElementById(dv).innerHTML = tb;
            }
}

/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 25.06.2008
@@purpose: To print results from the database through ajax
@@params: dv=div layer in which the results to be populated, resultArray=json array containing the data, page=which page we are currently on, totalRecords= total records found as per search, tableHeadArray=the array which contains the key(field name from the table) and label for table head, formName=the name of search form if any
@@return: returns the results into tabular format
*/
function printResults_old(dv, resultArray, page, totalRecords, tableHeadArray, formName) {
    
    //alert('printResults('+dv+', '+resultArray+', '+page+', '+totalRecords+', '+tableHeadArray+', '+formName+')');
    
            tb ='';

            //tb = globalTB;
			
            //for head labels
            headLength = tableHeadArray.length;
            tb += '<tr class="rowheading">';
            for(i=0;i<headLength;i++) {
               
              // alert(tableHeadArray[i][4]);
              if(tableHeadArray[i][4]!='undefined' && tableHeadArray[i][4]===true) {
                    if( tableHeadArray[i][0] == sortField) {
                    
                        if(sortOrderBy == 'ASC') {
                            showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page='+page+'&sortField='+sortField+'&sortOrderBy=DESC\');return false;" src="'+imagePathURL+'/arrow-up.gif" border="0"/>';
                        }
                        else {  
                            showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page='+page+'&sortField='+sortField+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-down.gif" border="0"/>';
                        }
                    }
                    else {
                         showSortBox = '<img onClick="sendReq(listURL,divResultName,searchFormName,\'page=1&sortField='+tableHeadArray[i][0]+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-none.gif" border="0"/>';
                    }
                    //sortTableField
              }
              else {
                  showSortBox = '';
              } 
              
               wdth = tableHeadArray[i][2]!='undefined' ? tableHeadArray[i][2] : '';  //cell width
               
               aln = tableHeadArray[i][3]!='undefined' ? tableHeadArray[i][3] : '';  //align
                 
               tb += '<td class="searchhead_text" '+wdth+' '+aln+'>'+tableHeadArray[i][1]+'&nbsp;'+showSortBox+'</td>';                         }
            tb += '</tr>';

                len = resultArray.length;
                if(len!='undefined' && len>0) {
                        //for values
                        for(i=0;i<len;i++) {
                            var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                            var bg2 = bg2 == "row0" ? "row1" : "row0";
							trId = 'trPrintResult_'+i;
                            tb +='<tr '+bg+' value="'+bg2+'" onmouseover="if(this.className != \'specialHighlight\' && this.className != \'redClass\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\' && this.className != \'redClass\') this.className=\''+bg2+'\'" >';
                             for(h=0;h<headLength;h++) {
                                  aln = tableHeadArray[h][3]!='undefined' ? tableHeadArray[h][3] : '';  //align
                                  
                                  if(tableHeadArray[h][0]=='action') {
                                          tb +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><img src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="editWindow('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+',\''+editFormName+'\','+winLayerWidth+','+winLayerHeight+');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'+imagePathURL+'/delete.gif" border="0" onClick="'+deleteFunction+'('+(eval('resultArray['+i+'].'+tableHeadArray[h][0]))+');"/></a></td>';
                                  }
                                  else {
                                      resultVal = eval('resultArray['+i+'].'+tableHeadArray[h][0]);
                                      cssClass = isNumericCustom(resultVal,'.-') ? '' : 'padding_top';
                                      //alert('result='+resultVal+', css='+cssClass); 
                                      
                                      tb +='<td class="'+cssClass+'" '+aln+'>'+resultVal+'&nbsp;</td>';                                                               // tb +='<td class="padding_top" '+aln+'>'+eval('resultArray['+i+'].'+tableHeadArray[h][0])+'&nbsp;</td>';
                                  }
                              }
                          tb +='</tr>';
                        }
                }
                else {
                    tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';
                    // in case user deletes all the records on some page say page=7, then redirect to previous page
                    if(parseInt(page)>1 && parseInt(totalRecords) >0) {
                        sendReq(listURL,divResultName,searchFormName,'page='+(parseInt(page)-1)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField);
                    }
            }
            pageLinks = pagination(page,totalRecords,recordsPerPage,linksPerPage,formName,dv);
            //alert("pagination("+page+","+totalRecords+","+recordsPerPage+","+linksPerPage+","+formName+","+dv+");");
            
            if(pageLinks!='') {
                //tb +='<tr><td class="padding_top" colspan="'+headLength+'">&nbsp;</td></tr>';
                if(globalPaginationPosition==1){ //bottom of page
                  tb =globalTB+tb+'<tr><td colspan="'+headLength+'" align="">'+pageLinks+'&nbsp;</td></tr>';
                }
                else if(globalPaginationPosition==2){ //top of page
                  tb =globalTB+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>'+tb;
                }
                else if(globalPaginationPosition==3){ //both on bottom and top of page
                  tb =globalTB+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>'+tb+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
                }
                else{ //by default bottom of page
                   tb =globalTB+tb+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
                }
            }
            else{
              tb =globalTB+tb;  
            }
            tb +='</table>'; 

            if(dv!='') {
                document.getElementById(dv).innerHTML = tb;
            }
			window.scroll(0,0);
}


function printResultsNoSorting(dv, resultArray, tableHeadArray) {
    
    //alert('printResults('+dv+', '+resultArray+', '+page+', '+totalRecords+', '+tableHeadArray+', '+formName+')');
    
            tb ='';
 
            tb = '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
            //for head labels
            headLength = tableHeadArray.length;
            tb += '<tr class="rowheading">';
            for(i=0;i<headLength;i++) {
               
               wdth = tableHeadArray[i][2]!='undefined' ? tableHeadArray[i][2] : '';  //cell width
               
               aln = tableHeadArray[i][3]!='undefined' ? tableHeadArray[i][3] : '';  //align
                 
               tb += '<td class="searchhead_text" valign="top"  '+wdth+' '+aln+'>'+tableHeadArray[i][1]+'&nbsp;</td>';                         
     }
            tb += '</tr>';
 
                len = resultArray.length;
                if(len!='undefined' && len>0) {
                        //for values
                        for(i=0;i<len;i++) {
                            var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                            
                            tb +='<tr '+bg+'>';
                             for(h=0;h<headLength;h++) {
                                  aln = tableHeadArray[h][3]!='undefined' ? tableHeadArray[h][3] : '';  //align
                                  if(h>=2) {
                                    tb +='<td class="padding_top" valign="top" nowrap '+aln+'>'+eval('resultArray['+i+'].'+tableHeadArray[h][0])+'&nbsp;</td>';
                                  }
                                  else {
                                    tb +='<td class="padding_top" valign="top" '+aln+'>'+eval('resultArray['+i+'].'+tableHeadArray[h][0])+'&nbsp;</td>';
                                  }
                              }
                          tb +='</tr>';
                        }
                }
                else {
                    tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';
            }
 
            tb +='</table>'; 
 
            if(dv!='') {
                document.getElementById(dv).innerHTML = tb;
            }
}


/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 25.06.2008
@@purpose: it will populate the pagination links
@@params: page=which page we are currently on, totalRecords= total records found as per search, recordsPerPage=how many records to be displayed on a page, linksPerPage= how many paging links to be displayed on a page, formName=the name of search form if any, dv=div layer in which the results to be populated
@@return: returns the pagination links
*/
function pagination(page,totalRecords,recordsPerPage,linksPerPage,formName,dv) {
           printLink='';
           totalPages = Math.ceil(parseFloat(totalRecords)/parseFloat(recordsPerPage)); 
           if(parseFloat(page)>parseFloat(totalPages)) {
              page = totalPages;
           }
           //show paging links when total records are more than records per page
           if(parseFloat(totalRecords) > parseFloat(recordsPerPage) ) {
                  printLink='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="fontText">';
                  //printLink+='<tr><td height="5px" colspan="2"></td></tr>';
                  printLink += '<tr><td width="30%" align="left"><b>Total Records:</b> '+totalRecords+'</td><td align="right" width="70%"><b>Pages:</b> '+totalPages+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                  if(parseFloat(page)>1) {
                      
                   printLink += ' <a href="#" onClick="sendReq(\''+listURL+'\',\''+dv+'\',\''+formName+'\',\'page=1&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'\');return false;" class="pagingLinks"><img src="'+imagePathURL+'/first.gif" border="0" title="First" align="absmiddle" /></a>&nbsp;&nbsp;'; 

                    printLink += ' <a onClick="sendReq(\''+listURL+'\',\''+dv+'\',\''+formName+'\',\'page='+(parseInt(page)-1)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'\');return false;" href="#" class="pagingLinks"><img src="'+imagePathURL+'/back.gif" border="0"  title="Previous" align="absmiddle" /></a>&nbsp;&nbsp;';                    
                  }
                  
                   half = Math.floor(parseFloat(linksPerPage)/2);
                   if(page>half) {
                     start = parseFloat(page) - half;
                     limit = parseFloat(page) + parseFloat(half);
                     if(limit > totalPages)
                         limit = totalPages;
                   }
                   else {
                     start = 1;
                     limit = linksPerPage;
                     if(limit>totalPages)
                         limit = totalPages;
                   }
                   // alert(limit + '==' +start);
                    
                    for(link=start;link<=parseFloat(limit);link++) {
                         if(link==page) {
                             printLink += ' <b>'+link+'</b>';
                         }
                         else {
                              printLink +=' <a onClick="sendReq(\''+listURL+'\', \''+dv+'\', \''+formName+'\', \'page='+link+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'\' );return false;" class="pagingLinks" href="#"><u>'+link+'</u></a> ';
                         } 
                    }

                   if(parseFloat(totalRecords)>( parseFloat(page)*parseFloat(recordsPerPage) )) {
                      printLink += '&nbsp;&nbsp;<a href="#" onClick="sendReq(\''+listURL+'\', \''+dv+'\', \''+formName+'\', \'page='+(parseInt(page)+1)+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'\' );return false;" class="pagingLinks"><img src="'+imagePathURL+'/next.gif" border="0" title="Next" align="absmiddle" /></a>&nbsp;';
                      printLink +=  ' <a onClick="sendReq(\''+listURL+'\', \''+dv+'\', \''+formName+'\', \'page='+totalPages+'&sortOrderBy='+sortOrderBy+'&sortField='+sortField+'\' );return false;" href="#" class="paginLinks"><img src="'+imagePathURL+'/last.gif" border="0"  title="Last" align="absmiddle" /></a>';
                    } 
                    
                    printLink +='</td></tr></table>';                 

           }
           return printLink;
}

/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 25.06.2008
@@purpose: it generates the query string on form fields which is passed as an argument
@@params: formName=the name of search form
@@return: it returns the data like &page=1&stateCode=PU
*/
function generateQueryString(frmName) {
  frmObj = document.forms[frmName].elements;
  len = frmObj.length;
  queryString = '';
  for(i=0;i<len;i++) {
    try{ // try-catch block added to prevent problem regarding fieldset element
	  if(frmObj[i].type.toUpperCase() == 'TEXT') {
		  frmObj[i].value=frmObj[i].value.replace('“','"');
		  frmObj[i].value=frmObj[i].value.replace('”','"');
	  }
      if(frmObj[i].checked == true && (frmObj[i].type =='radio' || frmObj[i].type =='checkbox')) {
            if(queryString!='') {
                queryString +='&';
            }
        queryString +=frmObj[i].name + '=' + frmObj[i].value;
      }
      else if (frmObj[i].type !='radio' && frmObj[i].type !='checkbox') {
			if(frmObj[i].type == 'select-multiple') {
				eleName = frmObj[i].name;
				eleLength = frmObj[eleName].length;
				selectedEle='';
				for(m=0;m<eleLength;m++) {
					if (frmObj[i][m].selected == true) {
						if (selectedEle != '') {
							selectedEle += ',';
						}
						selectedEle += frmObj[i][m].value;
					}
				}
				if(queryString!='') {
					queryString +='&';
				}
				newEleName = eleName.replace('[]','');
				queryString += newEleName+'='+selectedEle;
			}
			else {
				if(queryString!='') {
					queryString +='&';
				}          
				nm = frmObj[i].value;
				nm = escape(nm);
				//nm = nm.replace('%','');   // prototype js framework does not work with % in URL 
				queryString +=frmObj[i].name + '=' + nm;

			}
      }
    }
    catch(e){
    
    }
  }
  return queryString;  
}

/* New function Created to save values in form defaults functionality
	Changes are made for print function to take values according to ID*/
	
function generateQueryString1(frmName) {
  frmObj = document.forms[frmName].elements;
  len = frmObj.length;
  queryString = '';
  for(i=0;i<len;i++) {
    try{ // try-catch block added to prevent problem regarding fieldset element
	  if(frmObj[i].type.toUpperCase() == 'TEXT') {
		  frmObj[i].value=frmObj[i].value.replace('“','"');
		  frmObj[i].value=frmObj[i].value.replace('”','"');
	  }
      if(frmObj[i].checked == true && (frmObj[i].type =='radio' || frmObj[i].type =='checkbox')) {
            if(queryString!='') {
                queryString +='&';
            }
        queryString +=frmObj[i].id + '=' + frmObj[i].value;
      }
      else if (frmObj[i].type !='radio' && frmObj[i].type !='checkbox') {
			if(frmObj[i].type == 'select-multiple') {
				eleName = frmObj[i].id;
				eleLength = frmObj[eleName].length;
				selectedEle='';
				for(m=0;m<eleLength;m++) {
					if (frmObj[i][m].selected == true) {
						if (selectedEle != '') {
							selectedEle += ',';
						}
						selectedEle += frmObj[i][m].value;
					}
				}
				if(queryString!='') {
					queryString +='&';
				}
				newEleName = eleName.replace('[]','');
				queryString += newEleName+'='+selectedEle;
			}
			else {
				if(queryString!='') {
					queryString +='&';
				}          
				nm = frmObj[i].value;
				nm = escape(nm);
				//nm = nm.replace('%','');   // prototype js framework does not work with % in URL 
				queryString +=frmObj[i].id + '=' + nm;

			}
      }
    }
    catch(e){
    
    }
  }
  return queryString;  
}                          

/*
function generateQueryString(frmName) {
    
  frmObj = document.forms[frmName].elements;
  len = frmObj.length;
  queryString = '';
  for(i=0;i<len;i++) {
      if(frmObj[i].checked == true && (frmObj[i].type =='radio' || frmObj[i].type =='checkbox')) {
            if(queryString!='') {
                queryString +='&';
            }
        queryString +=frmObj[i].name + '=' + frmObj[i].value;
      }
      else if (frmObj[i].type !='radio' && frmObj[i].type !='checkbox') {
            if(queryString!='') {
                queryString +='&';
            }          
        nm = frmObj[i].value.replace('&','\\&');
        nm = nm.replace('%','');   // prototype js framework does not work with % in URL 
        queryString +=frmObj[i].name + '=' + nm;
      }
  }
  //alert(queryString);
  return queryString;  
}
*/
//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED RESTRICT NO OF CHARACTERS IN ADDRESS FIELDS (as textarea  does not have
//                                                                   any restriction)
//Author : Dipanjan Bhattacharjee
// Created on : (26.6.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//
//----------------------------------------------------------------------------------------------------

function ismaxlength(obj){
var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
if (obj.getAttribute && obj.value.length>mlength)
obj.value=obj.value.substring(0,mlength)
}


//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO MAKING PAGING 
// Author : Ajinder Singh
// Created on : 25-oct-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// elmentName: id of the multiselect control
//----------------------------------------------------------------------------------------------------
function pagination2(page,totalRecords,recordsPerPage,linksPerPage) {
           printLink='';
           totalPages = Math.ceil(parseFloat(totalRecords)/parseFloat(recordsPerPage)); 
           if(parseFloat(page)>parseFloat(totalPages)) {
              page = totalPages;
           }
           //show paging links when total records are more than records per page
           if(parseFloat(totalRecords) > parseFloat(recordsPerPage) ) {
               
                  printLink = '<span class="fontText"><b>Pages:</b> '+totalPages+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                  if(parseFloat(page)>1) {
                      
                   printLink += ' <a href="#" onClick="showReport(\'1\');return false;" class="pagingLinks"><img src="'+imagePathURL+'/first.gif" border="0" title="First" align="absmiddle" /></a>&nbsp;&nbsp;'; 
                    printLink += ' <a onClick="showReport(\''+(parseInt(page)-1)+'\');return false;" href="#" class="pagingLinks"><img src="'+imagePathURL+'/back.gif" border="0"  title="Previous" align="absmiddle" /></a>&nbsp;&nbsp;';                    
                  }
                   half = Math.floor(parseFloat(linksPerPage)/2);
                   if(page>half) {
                     start = parseFloat(page) - half;
                     limit = parseFloat(page) + parseFloat(half);
                     if(limit > totalPages)
                         limit = totalPages;
                   }
                   else {
                     start = 1;
                     limit = linksPerPage;
                     if(limit>totalPages)
                         limit = totalPages;
                   }
                   // alert(limit + '==' +start);
                    
                    for(link=start;link<=parseFloat(limit);link++) {
                         if(link==page) {
                             printLink += ' <b>'+link+'</b>';
                         }
                         else {
                              printLink +=' <a onClick="showReport(\''+link+'\' );" class="pagingLinks" href="#"><u>'+link+'</u></a> ';
                         } 
                    }

                   if(parseFloat(totalRecords)>( parseFloat(page)*parseFloat(recordsPerPage))) {
                      printLink += '&nbsp;&nbsp;<a href="#" onClick="showReport(\''+(parseInt(page)+1)+'\' );return false;" class="pagingLinks"><img src="'+imagePathURL+'/next.gif" border="0" title="Next" align="absmiddle" /></a>&nbsp;';
                      printLink +=  ' <a onClick="showReport(\''+totalPages+'\' );return false;" href="#" class="paginLinks"><img src="'+imagePathURL+'/last.gif" border="0"  title="Last" align="absmiddle" /></a>';
                    } 
                    
                    printLink +='</span>';                 
           }

           return printLink;
}


/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 27.06.2008
@@purpose: it displays the message
@@params: message=any text you want to show on the web page as an alert
@@return: nothing
*/
function messageBox(message) {
    alert(message);    
}

/*
@@Author: Dipanjan Bhattacharjee
@@Created On: 4.7.08
@@purpose: date difference between two dates
@@params: date1(startdate),date2(enddate),deliminator
@@return: 0,1
*/
function dateDifference(date1,date2,delim){
 var arr1=date1.split(delim);
 var arr2=date2.split(delim);
 
 var sdate=arr1[0]+arr1[1]+arr1[2];
 var edate=arr2[0]+arr2[1]+arr2[2]; 

 if(parseInt(edate) < parseInt(sdate) ){
  return 0;
 }
 else {
   return 1;
 } 
 
}

/*
@@Author: Dipanjan Bhattacharjee
@@Created On: 18.09.08
@@purpose: date equality between two dates
@@params: date1(startdate),date2(enddate),deliminator
@@return: 0,1
*/
function dateEqual(date1,date2,delim){

 var arr1=date1.split(delim);
 var arr2=date2.split(delim);
 
 var sdate=arr1[0]+arr1[1]+arr1[2];
 var edate=arr2[0]+arr2[1]+arr2[2]; 

 if(parseInt(edate) == parseInt(sdate) ){
   return 1;
 }
 else {
   return 0;
 } 
 
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED FORMAT DATE
//Author : Dipanjan Bhattacharjee
// Created on : (08.08.2008)
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: date value,seperator: as usual
//----------------------------------------------------------------------------------------------------
function customParseDate(value,seperator){
 var dt=value.split(seperator);
 var y=0;
 var m=''; 
 var d=0;
 if(dt.length >2){
  if(dt[1]=="01"){
   m="Jan";
  }
 else if(dt[1]=="02"){
   m="Feb";
  } 
 else if(dt[1]=="03"){
   m="Mar";
  }
 else if(dt[1]=="04"){
   m="Apr";
  }
 else if(dt[1]=="05"){
   m="May";
  }
 else if(dt[1]=="06"){
   m="Jun";
  }
 else if(dt[1]=="07"){
   m="Jul";
  }
 else if(dt[1]=="08"){
   m="Aug";
  }
 else if(dt[1]=="09"){
   m="Sep";
  }
 else if(dt[1]=="10"){
   m="Oct";
  }
 else if(dt[1]=="11"){
   m="Nov";
  }
 else{
   m="Dec";
  }                     
 }
dt[0]=dt[0].substring(2);
var fullDate=""; 
 if(dt[2]==0){           //if day is 0
  fullDate=m+"-"+dt[0];  
 }
else{
  fullDate=dt[2]+"-"+m+"-"+dt[0];
} 
 
 return fullDate;
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO SELECT/UNSELECT ALL VALUES IN A SELECT BOX WHOSE TYPE IS ARRAY
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id, "All" or "None"
//----------------------------------------------------------------------------------------------------
function makeSelection(ele, selType,selectionForm) {
    if(selectionForm && selectionForm!=''){ //if it is defined
      form = eval('document.'+selectionForm);
    }
    else{
        form = document.allDetailsForm;
    }
    totalLen = form.elements[ele].length;
    for(i=0;i<totalLen;i++) {
        if (selType == "All") {
            form.elements[ele][i].selected = true;
        }
        else {
            form.elements[ele][i].selected = false;
        }
    }
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO SELECT/UNSELECT ALL VALUES IN A SELECT BOX
//Author : Dipanjan Bhattacharjee
// Created on : 17-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id, "All" or "None"
//----------------------------------------------------------------------------------------------------
function makeSelectDeselect(ele, selType) {
    var element=document.getElementById(ele);  
	totalLen = element.length;
	for(i=0;i<totalLen;i++) {
		if (selType == "All") {
			element.options[ i ].selected = true;
		}
		else {
			element.options[ i ].selected = false;
		}
	}
}

function calculateOffsetTop(srcElement){
  var tmpSrcElement=srcElement;
  var top=0;
  try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetTop)){
           top += tmpSrcElement.offsetTop;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
   }
  catch(e){}
  return top;   
}

function calculateOffsetLeft(srcElement){
  var tmpSrcElement=srcElement;
  var left=0;
  try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetLeft)){
           left += tmpSrcElement.offsetLeft;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
   }
  catch(e){}
  return left;   
}

function adjustMultipleDropDownsInStudentFilterForAddress(){
  var srcElement1=document.getElementById('cityId');
  var cityHeight=srcElement1.offsetHeight;
  var cityTop=calculateOffsetTop(srcElement1);
  var cityLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('cityD1').style.top=cityHeight+cityTop+'px';
  document.getElementById('cityD1').style.left=cityLeft+'px';
  document.getElementById('cityD2').style.left=cityLeft+'px';
  document.getElementById('cityD2').style.top=cityTop+'px';
  
  var srcElement2=document.getElementById('stateId');
  var stateHeight=srcElement2.offsetHeight;
  var stateTop=calculateOffsetTop(srcElement2);
  var stateLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('stateD1').style.top=stateHeight+stateTop+'px';
  document.getElementById('stateD1').style.left=stateLeft+'px';
  document.getElementById('stateD2').style.left=stateLeft+'px';
  document.getElementById('stateD2').style.top=stateTop+'px';
  
  var srcElement3=document.getElementById('countryId');
  var countryHeight=srcElement3.offsetHeight;
  var countryTop=calculateOffsetTop(srcElement3);
  var countryLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('countryD1').style.top=countryHeight+countryTop+'px';
  document.getElementById('countryD1').style.left=countryLeft+'px';
  document.getElementById('countryD2').style.left=countryLeft+'px';
  document.getElementById('countryD2').style.top=countryTop+'px';
}

function adjustMultipleDropDownsInStudentFilterForMisc(){
  var srcElement1=document.getElementById('hostelId');
  var hostelHeight=srcElement1.offsetHeight;
  var hostelTop=calculateOffsetTop(srcElement1);
  var hostelLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('hostelD1').style.top=hostelHeight+hostelTop+'px';
  document.getElementById('hostelD1').style.left=hostelLeft+'px';
  document.getElementById('hostelD2').style.left=hostelLeft+'px';
  document.getElementById('hostelD2').style.top=hostelTop+'px';
  
  var srcElement2=document.getElementById('busStopId');
  var busStopHeight=srcElement2.offsetHeight;
  var busStopTop=calculateOffsetTop(srcElement2);
  var busStopLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('busStopD1').style.top=busStopHeight+busStopTop+'px';
  document.getElementById('busStopD1').style.left=busStopLeft+'px';
  document.getElementById('busStopD2').style.left=busStopLeft+'px';
  document.getElementById('busStopD2').style.top=busStopTop+'px';
  
  var srcElement3=document.getElementById('busRouteId');
  var busRouteHeight=srcElement3.offsetHeight;
  var busRouteTop=calculateOffsetTop(srcElement3);
  var busRouteLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('busRouteD1').style.top=busRouteHeight+busRouteTop+'px';
  document.getElementById('busRouteD1').style.left=busRouteLeft+'px';
  document.getElementById('busRouteD2').style.left=busRouteLeft+'px';
  document.getElementById('busRouteD2').style.top=busRouteTop+'px';
}


function adjustMultipleDropDownsInParentFilterForAddress(){
  var srcElement1=document.getElementById('city_parentId');
  var cityHeight=srcElement1.offsetHeight;
  var cityTop=calculateOffsetTop(srcElement1);
  var cityLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('city_parentD1').style.top=cityHeight+cityTop+'px';
  document.getElementById('city_parentD1').style.left=cityLeft+'px';
  document.getElementById('city_parentD2').style.left=cityLeft+'px';
  document.getElementById('city_parentD2').style.top=cityTop+'px';
  
  var srcElement2=document.getElementById('state_parentId');
  var stateHeight=srcElement2.offsetHeight;
  var stateTop=calculateOffsetTop(srcElement2);
  var stateLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('state_parentD1').style.top=stateHeight+stateTop+'px';
  document.getElementById('state_parentD1').style.left=stateLeft+'px';
  document.getElementById('state_parentD2').style.left=stateLeft+'px';
  document.getElementById('state_parentD2').style.top=stateTop+'px';
  
  var srcElement3=document.getElementById('country_parentId');
  var countryHeight=srcElement3.offsetHeight;
  var countryTop=calculateOffsetTop(srcElement3);
  var countryLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('country_parentD1').style.top=countryHeight+countryTop+'px';
  document.getElementById('country_parentD1').style.left=countryLeft+'px';
  document.getElementById('country_parentD2').style.left=countryLeft+'px';
  document.getElementById('country_parentD2').style.top=countryTop+'px';
}

function adjustMultipleDropDownsInParentFilterForMisc(){
  var srcElement1=document.getElementById('hostel_parentId');
  var hostelHeight=srcElement1.offsetHeight;
  var hostelTop=calculateOffsetTop(srcElement1);
  var hostelLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('hostel_parentD1').style.top=hostelHeight+hostelTop+'px';
  document.getElementById('hostel_parentD1').style.left=hostelLeft+'px';
  document.getElementById('hostel_parentD2').style.left=hostelLeft+'px';
  document.getElementById('hostel_parentD2').style.top=hostelTop+'px';
  
  var srcElement2=document.getElementById('busStop_parentId');
  var busStopHeight=srcElement2.offsetHeight;
  var busStopTop=calculateOffsetTop(srcElement2);
  var busStopLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('busStop_parentD1').style.top=busStopHeight+busStopTop+'px';
  document.getElementById('busStop_parentD1').style.left=busStopLeft+'px';
  document.getElementById('busStop_parentD2').style.left=busStopLeft+'px';
  document.getElementById('busStop_parentD2').style.top=busStopTop+'px';
  
  var srcElement3=document.getElementById('busRoute_parentId');
  var busRouteHeight=srcElement3.offsetHeight;
  var busRouteTop=calculateOffsetTop(srcElement3);
  var busRouteLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('busRoute_parentD1').style.top=busRouteHeight+busRouteTop+'px';
  document.getElementById('busRoute_parentD1').style.left=busRouteLeft+'px';
  document.getElementById('busRoute_parentD2').style.left=busRouteLeft+'px';
  document.getElementById('busRoute_parentD2').style.top=busRouteTop+'px';
}


function adjustMultipleDropDownsInEmployeeFilterForAddress(){
  var srcElement1=document.getElementById('cityId');
  var cityHeight=srcElement1.offsetHeight;
  var cityTop=calculateOffsetTop(srcElement1);
  var cityLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('cityD1').style.top=cityHeight+cityTop+'px';
  document.getElementById('cityD1').style.left=cityLeft+'px';
  document.getElementById('cityD2').style.left=cityLeft+'px';
  document.getElementById('cityD2').style.top=cityTop+'px';
  
  var srcElement2=document.getElementById('stateId');
  var stateHeight=srcElement2.offsetHeight;
  var stateTop=calculateOffsetTop(srcElement2);
  var stateLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('stateD1').style.top=stateHeight+stateTop+'px';
  document.getElementById('stateD1').style.left=stateLeft+'px';
  document.getElementById('stateD2').style.left=stateLeft+'px';
  document.getElementById('stateD2').style.top=stateTop+'px';
  
  var srcElement3=document.getElementById('countryId');
  var countryHeight=srcElement3.offsetHeight;
  var countryTop=calculateOffsetTop(srcElement3);
  var countryLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('countryD1').style.top=countryHeight+countryTop+'px';
  document.getElementById('countryD1').style.left=countryLeft+'px';
  document.getElementById('countryD2').style.left=countryLeft-1+'px';
  document.getElementById('countryD2').style.top=countryTop+'px';
}


function adjustMultipleDropDownsInEmployeeFeedBackFilterForAddress(){
  var srcElement1=document.getElementById('city_empId');
  var cityHeight=srcElement1.offsetHeight;
  var cityTop=calculateOffsetTop(srcElement1);
  var cityLeft=calculateOffsetLeft(srcElement1);
  document.getElementById('city_empD1').style.top=cityHeight+cityTop+'px';
  document.getElementById('city_empD1').style.left=cityLeft+'px';
  document.getElementById('city_empD2').style.left=cityLeft+'px';
  document.getElementById('city_empD2').style.top=cityTop+'px';
  
  var srcElement2=document.getElementById('state_empId');
  var stateHeight=srcElement2.offsetHeight;
  var stateTop=calculateOffsetTop(srcElement2);
  var stateLeft=calculateOffsetLeft(srcElement2);
  document.getElementById('state_empD1').style.top=stateHeight+stateTop+'px';
  document.getElementById('state_empD1').style.left=stateLeft+'px';
  document.getElementById('state_empD2').style.left=stateLeft+'px';
  document.getElementById('state_empD2').style.top=stateTop+'px';
  
  var srcElement3=document.getElementById('country_empId');
  var countryHeight=srcElement3.offsetHeight;
  var countryTop=calculateOffsetTop(srcElement3);
  var countryLeft=calculateOffsetLeft(srcElement3);
  document.getElementById('country_empD1').style.top=countryHeight+countryTop+'px';
  document.getElementById('country_empD1').style.left=countryLeft+'px';
  document.getElementById('country_empD2').style.left=countryLeft-1+'px';
  document.getElementById('country_empD2').style.top=countryTop+'px';
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO TOGGLE ROW DISPLAY
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function showHide(ele,mode) {
	
	if (ele=="academic") {
		checkAndDisplay("academic1");
		checkAndDisplay("academic2");
		checkAndDisplay("academic3");
        checkAndDisplay("academicDummyRow");
		showHideLabel(ele);
        if(document.getElementById('academic1').style.display!=''){
         //for student filter
         try{
         if(mode==1){
          closeTargetDiv('d1','degreeContainerDiv');
          closeTargetDiv('d11','branchContainerDiv');
          closeTargetDiv('d111','periodicityContainerDiv');
          closeTargetDiv('d1111','courseContainerDiv');
          closeTargetDiv('d11111','groupContainerDiv');
          closeTargetDiv('d111111','univContainerDiv');
          adjustMultipleDropDownsInStudentFilterForAddress();
          adjustMultipleDropDownsInStudentFilterForMisc();
         }
         }
         catch(e){}
         
         //for employee filter
         try{
         if(mode==2){
          //closeTargetDiv('instituteD1','instituteContainerDiv');
          closeTargetDiv('departmentD1','departmentContainerDiv');
          closeTargetDiv('designationD1','designationContainerDiv');
          adjustMultipleDropDownsInEmployeeFilterForAddress();
         }
         }
         catch(e){}
         
         
        }
        else{
        //for student filter
        try{
           if(mode==1){
             makeDDHide('degreeId','d2','d3');
             totalSelected('degreeId','d3');
             
             makeDDHide('branchId','d22','d33');
             totalSelected('branchId','d33');
             
             makeDDHide('periodicityId','d222','d333');
             totalSelected('periodicityId','d333');
             
             makeDDHide('courseId','d2222','d3333');
             totalSelected('courseId','d3333');
             
             makeDDHide('groupId','d22222','d33333');
             totalSelected('groupId','d33333');
             
             makeDDHide('universityId','d222222','d333333');
             totalSelected('universityId','d333333');
             
             adjustMultipleDropDownsInStudentFilterForAddress();
             adjustMultipleDropDownsInStudentFilterForMisc();
           }
         }
         catch(e){}
         
         //for employee filter
         try{
         if(mode==2){
             //makeDDHide('instituteId','instituteD2','instituteD3');
             //totalSelected('instituteId','instituteD3');

             makeDDHide('departmentId','departmentD2','departmentD3');
             totalSelected('departmentId','departmentD3');
             
             makeDDHide('designationId','designationD2','designationD3');
             totalSelected('designationId','designationD3');
             adjustMultipleDropDownsInEmployeeFilterForAddress();
           }
         }
         catch(e){} 
       }
/*
		hide("address1");
		hide("misc1");
		hide("misc2");
		hide("misc3");
*/
	}
	else if(ele=="address") {
/*
		hide("academic1");
		hide("academic2");
*/
		checkAndDisplay("address1");
		showHideLabel(ele);
        if(document.getElementById('address1').style.display!=''){
           //for student filter
           try{
            if(mode==1){
              closeTargetDiv('cityD1','cityContainerDiv');
              closeTargetDiv('stateD1','stateContainerDiv');
              closeTargetDiv('countryD1','countryContainerDiv');
              adjustMultipleDropDownsInStudentFilterForMisc();
             }
            }
            catch(e){}
            
           //for student filter
           try{
            if(mode==2){
              closeTargetDiv('cityD1','cityContainerDiv');
              closeTargetDiv('stateD1','stateContainerDiv');
              closeTargetDiv('countryD1','countryContainerDiv');
             }
            }
            catch(e){}
        }
        else{
            //for student filter
            try{
             if(mode==1){
              makeDDHide('cityId','cityD2','cityD3');
              totalSelected('cityId','cityD3');
             
              makeDDHide('stateId','stateD2','stateD3');
              totalSelected('stateId','stateD3');
             
              makeDDHide('countryId','countryD2','countryD3');
              totalSelected('countryId','countryD3');
              adjustMultipleDropDownsInStudentFilterForMisc();
             }
            }
            catch(e){}
            
          try{
           if(mode==2){
              makeDDHide('cityId','cityD2','cityD3');
              totalSelected('cityId','cityD3');
             
              makeDDHide('stateId','stateD2','stateD3');
              totalSelected('stateId','stateD3');
             
              makeDDHide('countryId','countryD2','countryD3');
              totalSelected('countryId','countryD3');
           }
          }
          catch(e){}  
        }
/*		
		hide("misc1");
		hide("misc2");
		hide("misc3");
*/
	}
    else if(ele=="misc") {
/*
        hide("academic1");
        hide("academic2");
        hide("address1");
*/

        
        checkAndDisplay("misc1");
        checkAndDisplay("misc2");
        checkAndDisplay("misc3");
        showHideLabel(ele);
        
        if(document.getElementById('misc1').style.display!=''){
         try{
         if(mode==1){
          closeTargetDiv('hostelD1','hostelContainerDiv');
          closeTargetDiv('busStopD1','busStopContainerDiv');
          closeTargetDiv('busRouteD1','busRouteContainerDiv');
          }
         }
         catch(e){}
        }
        else{
        try{
        if(mode==1){
          makeDDHide('hostelId','hostelD2','hostelD3');
          totalSelected('hostelId','hostelD3');
         
          makeDDHide('busStopId','busStopD2','busStopD3');
          totalSelected('busStopId','busStopD3');
         
          makeDDHide('busRouteId','busRouteD2','busRouteD3');
          totalSelected('busRouteId','busRouteD3');
         }
        }
        catch(e){}
         
        }
        /*
        document.getElementById('admissionYearF').value='';
        document.getElementById('admissionMonthF').value='';
        document.getElementById('admissionDateF').value='';
        document.getElementById('admissionYearT').value='';
        document.getElementById('admissionMonthT').value='';
        document.getElementById('admissionDateT').value='';
       */ 
        
    }
    else if(ele=="miscEmployee") {
/*
        hide("academic1");
        hide("academic2");
        hide("address1");
*/

        
        checkAndDisplay("misc1");
        checkAndDisplay("misc2");
        checkAndDisplay("misc3");
        showHideLabel(ele);
    }
	else {
		hide("academic1");
		hide("academic2");
		hide("academic3");
		hide("address1");
		hide("misc1");
		hide("misc2");
		hide("misc3");
		hideLabel("academic");
		hideLabel("address");
		hideLabel("misc");
	}
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO TOGGLE ROW DISPLAY
//Author : Ajinder Singh9+Dipanjan Bhattacharjee
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function showHideParent(ele,mode) {
    if (ele=="parent_academic") {
        checkAndDisplay("parent_academic1");
        checkAndDisplay("parent_academic2");
        checkAndDisplay("parent_academic3");
        showHideLabel(ele);
        
        if(document.getElementById('parent_academic1').style.display!=''){
         //for parent filter
         try{
             if(mode==1){
              closeTargetDiv('degree_parentD1','degree_parentContainerDiv');
              closeTargetDiv('branch_parentD1','branch_parentContainerDiv');
              closeTargetDiv('periodicity_parentD1','periodicity_parentContainerDiv');
              closeTargetDiv('course_parentD1','course_parentContainerDiv');
              closeTargetDiv('group_parentD1','group_parentContainerDiv');
              closeTargetDiv('univ_parentD1','univ_parentContainerDiv');
              adjustMultipleDropDownsInParentFilterForAddress();
              adjustMultipleDropDownsInParentFilterForMisc();
             }
             }
             catch(e){}
        }
        else{
         //for student filter
        try{
           if(mode==1){
             makeDDHide('degree_parentId','degree_parentD2','degree_parentD3');
             totalSelected('degree_parentId','degree_parentD3');
             
             makeDDHide('branch_parentId','branch_parentD2','branch_parentD3');
             totalSelected('branch_parentId','branch_parentD3');
             
             makeDDHide('periodicity_parentId','periodicity_parentD2','periodicity_parentD3');
             totalSelected('periodicity_parentId','periodicity_parentD3');
             
             makeDDHide('course_parentId','course_parentD2','course_parentD3');
             totalSelected('course_parentId','course_parentD3');
             
             makeDDHide('group_parentId','group_parentD2','group_parentD3');
             totalSelected('group_parentId','group_parentD3');
             
             makeDDHide('university_parentId','univ_parentD2','univ_parentD3');
             totalSelected('university_parentId','univ_parentD3');
             
             adjustMultipleDropDownsInParentFilterForAddress();
             adjustMultipleDropDownsInParentFilterForMisc();
           }
         }
         catch(e){}
       }
    }
    else if(ele=="parent_address") {
        checkAndDisplay("parent_address1");
        showHideLabel(ele);
        if(document.getElementById('parent_address1').style.display!=''){
           //for parent filter
           try{
            if(mode==1){
              closeTargetDiv('city_parentD1','city_parentContainerDiv');
              closeTargetDiv('state_parentD1','state_parentContainerDiv');
              closeTargetDiv('country_parentD1','country_parentContainerDiv');
              adjustMultipleDropDownsInParentFilterForMisc();
             }
            }
            catch(e){}
        }
        else{
            //for parent filter
            try{
             if(mode==1){
              makeDDHide('city_parentId','city_parentD2','city_parentD3');
              totalSelected('city_parentId','city_parentD3');
             
              makeDDHide('state_parentId','state_parentD2','state_parentD3');
              totalSelected('state_parentId','state_parentD3');
             
              makeDDHide('country_parentId','country_parentD2','country_parentD3');
              totalSelected('country_parentId','country_parentD3');
              adjustMultipleDropDownsInParentFilterForMisc();
             }
            }
            catch(e){}
        }
    }
    else if(ele=="parent_misc") {
        checkAndDisplay("parent_misc1");
        checkAndDisplay("parent_misc2");
        checkAndDisplay("parent_misc3");
        showHideLabel(ele);
        if(document.getElementById('parent_misc1').style.display!=''){
         try{
         if(mode==1){
          closeTargetDiv('hostel_parentD1','hostel_parentContainerDiv');
          closeTargetDiv('busStop_parentD1','busStop_parentContainerDiv');
          closeTargetDiv('busRoute_parentD1','busRoute_parentContainerDiv');
          }
         }
         catch(e){}
        }
        else{
        try{
        if(mode==1){
          makeDDHide('hostel_parentId','hostel_parentD2','hostel_parentD3');
          totalSelected('hostel_parentId','hostel_parentD3');
         
          makeDDHide('busStop_parentId','busStop_parentD2','busStop_parentD3');
          totalSelected('busStop_parentId','busStop_parentD3');
         
          makeDDHide('busRoute_parentId','busRoute_parentD2','busRoute_parentD3');
          totalSelected('busRoute_parentId','busRoute_parentD3');
         }
        }
        catch(e){}
         
        }
    }
    else if(ele=="parent_miscEmployee") {
        checkAndDisplay("parent_misc1");
        checkAndDisplay("parent_misc2");
        checkAndDisplay("parent_misc3");
        showHideLabel(ele);
    }
    else {
        hide("parent_academic1");
        hide("parent_academic2");
        hide("parent_academic3");
        hide("parent_address1");
        hide("parent_misc1");
        hide("parent_misc2");
        hide("parent_misc3");
        hideLabel("parent_academic");
        hideLabel("parent_address");
        hideLabel("parent_misc");
    }
}

/*Used to show/hide row in common filters*/
/*
f1:main field to show/hide
f2,f3: always hide
*/
function showHideAdvanced(preFix,f1,f2,f3){
 if(document.getElementById((preFix+f1)).innerHTML=='Expand'){
     document.getElementById((preFix+f1)).innerHTML='Collapse';
        document.getElementById((preFix+f1+'1')).style.display='';
/*     
     document.getElementById((preFix+f2)).innerHTML='Show';
     document.getElementById((preFix+f2+'1')).style.display='none';

     document.getElementById((preFix+f3)).innerHTML='Show';
     document.getElementById((preFix+f3+'1')).style.display='none';
     if(f2=='miscEmployee'){
         document.getElementById((preFix+f2+'2')).style.display='none';
     }
     if(f3=='miscEmployee'){
         document.getElementById((preFix+f3+'2')).style.display='none';
     }
*/
     if(f1=='miscEmployee'){
         document.getElementById((preFix+f1+'2')).style.display='';
     }

 }
 else{
     document.getElementById((preFix+f1)).innerHTML='Expand';
     document.getElementById((preFix+f1+'1')).style.display='none';

     if(f1=='miscEmployee'){
         document.getElementById((preFix+f1+'2')).style.display='none';
     }
 }
 
 if((preFix+f1+'1')=='emp_academic1'){ //for academic row
            if(document.getElementById('emp_academic1').style.display!=''){
                 try{
                  closeTargetDiv('designation_empD1','designation_empContainerDiv');
                  closeTargetDiv('institute_empD1','institute_empContainerDiv');
                  adjustMultipleDropDownsInEmployeeFeedBackFilterForAddress();
                 }
                 catch(e){}
            }
            else{
                 try{
                 makeDDHide('designation_empId','designation_empD2','designation_empD3');
                 totalSelected('designation_empId','designation_empD3');
                 
                 makeDDHide('institute_empId','institute_empD2','institute_empD3');
                 totalSelected('institute_empId','institute_empD3');
                 
                 adjustMultipleDropDownsInEmployeeFeedBackFilterForAddress();
                }
                catch(e){}
            }
      }
     else if((preFix+f1+'1')=='emp_address1'){ //for address row
        
        if(document.getElementById('emp_address1').style.display!=''){
                 try{
                  closeTargetDiv('city_empD1','city_empContainerDiv');
                  closeTargetDiv('state_empD1','state_empContainerDiv');
                  closeTargetDiv('country_empD1','country_empContainerDiv');
                 }
                 catch(e){}
            }
            else{
                 try{
                 makeDDHide('city_empId','city_empD2','city_empD3');
                 totalSelected('city_empId','city_empD3');
                 
                 makeDDHide('state_empId','state_empD2','state_empD3');
                 totalSelected('state_empId','state_empD3');
                 
                 makeDDHide('country_empId','country_empD2','country_empD3');
                 totalSelected('country_empId','country_empD3');
                 
                }
                catch(e){}
            }
     
     }
     
     
   }

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO CHANGE LINK TEXT
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function hideLabel(ele) {
	document.getElementById(ele).innerHTML = 'Expand';
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO CHANGE LINK TEXT
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function showLabel(ele) {
	document.getElementById(ele).innerHTML = 'Collapse';
}


//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO CHECK AND CHANGE LINK TEXT
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function showHideLabel(ele) {
	if (document.getElementById(ele).innerHTML == 'Expand') {
		showLabel(ele);
	}
	else {
		hideLabel(ele);
	}
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO CHECK AND SHOW/HIDE ROW
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function checkAndDisplay(ele) {
	if (document.getElementById(ele)) {
		if (document.getElementById(ele).style.display == '') {
			hide(ele);
		}
		else {
			show(ele);
		}
	}
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO SHOW ELEMENT
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function show(ele) {
	document.getElementById(ele).style.display='';
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO HIDE ELEMENT
//Author : Ajinder Singh
// Created on : 13-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
//value: element Id
//----------------------------------------------------------------------------------------------------
function hide(ele) {
	document.getElementById(ele).style.display='none';
}

//----------------------------------------------------------------------------------------------------
//THIS FUNCTION IS USED TO GET comma seprated value from multiselect control
// Author : Rajeev Aggarwal
// Created on : 24-Sep-2008
// Copyright 2008-2009 - syenergy Technologies Pvt. Ltd.
// elmentName: id of the multiselect control
//----------------------------------------------------------------------------------------------------
function getCommaSepratedResource(elmentName,formName)
{
	//alert(formName);
	var commValue ='';
	var c=eval("document."+formName+"."+elmentName+".length");
	for(i=0;i<c;i++){
		if(eval("document."+formName+"."+elmentName+"[i].selected")){

			if(eval("document."+formName+"."+elmentName+"[i].value")!=""){  
				if(commValue==""){
					commValue= eval("document."+formName+"."+elmentName+"[i].value");
				}
			else{
				commValue=commValue + "," + eval("document."+formName+"."+elmentName+"[i].value");
			}
		}  
		}  
	}
	return commValue;
} 
function getCommaSeprated(elmentName)
{
	var commValue ='';
	var c=eval("document.getElementById('"+elmentName+"').length");
	for(i=0;i<c;i++){
		if(eval("document.allDetailsForm."+elmentName+"[i].selected")){

			if(eval("document.allDetailsForm."+elmentName+"[i].value")!=""){  
				if(commValue==""){
					commValue= eval("document.allDetailsForm."+elmentName+"[i].value");
				}
			else{
				commValue=commValue + "," + eval("document.allDetailsForm."+elmentName+"[i].value");
			}
		}  
		}  
	}
	return commValue;
}

/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 11.11.2008
@@purpose: it sends the request through xmlhttp 
@@params: url=the url which will be processed to get records from db in json format, pageObj= object of all initialised variables, queryString = if any argument is passed through query string 
@@return: it returns the data like &page=1&stateCode=PU
*/
// url, page object which is created by calling initPage function, custom query string if any
function sendRequest(url, pageObj, queryString,asyn ) {

    // get all the form values
    

    if(pageObj.searchFormName) {
       try{
        params =pageObj.generateQueryString(pageObj.searchFormName);
       }
       catch(e){}

    }

    else {

       params = '';

    }
    
    if(queryString) {

     // generate query string from all form fields

     params += '&'+queryString;

    }
    
    if(pageObj.initialQueryString) {
        params += '&'+pageObj.initialQueryString;
    }

    

  //alert($(formName).serialize(true));

  //alert(params);

  //alert(url+'=='+pageObj);

 

  new Ajax.Request(url, {

    method: 'post',
    asynchronous: ((asyn=='undefined' ? true : asyn ) ),
    parameters: params,

    onCreate: function() {

       showWaitDialog(true);
       

    },

    onSuccess: function(httpObj) {

         hideWaitDialog(true);

         responseData = trim(httpObj.responseText);

		 if (responseData == sessionTimeOut) {
			 messageBox(responseData);
			 return;
		 }


         j = responseData.evalJSON();

         if(j) {

         //assign page value to global variable

           pageObj.page = (j.page != 'undefined' && j.page!='') ? j.page : pageObj.page;

           //alert(j.page + '===' + pageObj.page);

           pageObj.sortField = (j.sortField != 'undefined' && j.sortField !='' ) ? j.sortField : pageObj.sortField;

           pageObj.sortOrderBy = (j.sortOrderBy != 'undefined' && j.sortOrderBy!='') ? j.sortOrderBy : pageObj.sortOrderBy;

           pageObj.totalRecords = (j.totalRecords!= 'undefined' && j.totalRecords!='') ? j.totalRecords : pageObj.totalRecords;

           //alert(j.page + '=jp===' + pageObj.page + '=lp===='+pageObj.sortField);

 

           // populate table with results

           if(document.getElementById(pageObj.divAddEdit)) {

            document.getElementById(pageObj.divAddEdit).style.display='none';

           }

           if(document.getElementById(pageObj.divResultName)) {

            document.getElementById(pageObj.divResultName).style.display='block';

           }

           document.getElementById(pageObj.divResultName).innerHTML=pageObj.printResults(j.info, pageObj);
           /*To toggle HELP images on ajax requests*/
		    toggleHelpFacility(document.getElementById('helpChk').checked);

         }

         else {

            messageBox(responseData);

         }

    }

  });

}

/** Pagination */
/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 11.11.2008
@@purpose: it is used to initialize the variables
@@params: mentioned below
@@return: it returns nothing
*/

//url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,pageObjectName, table columns array

function initPage(url,recordsPerPage,linksPerPage,page,formName,sortField,sortOrderBy,divResult,divAddEdit,listTitle,pagingFlag,objName,colArray,editFunctionName,deleteFunctionName,initialQueryString) {

         this.URL = url; // ajax URL that will generate the list from the database

         this.recordsPerPage = recordsPerPage; // no of records that will be displayed in the list

         this.linksPerPage = linksPerPage; // no of links will be displayed in the pagination

         this.page = page; //default page no 

         this.searchFormName = formName; // search form name if search is required

         this.sortField = sortField; // default sort field

         this.sortOrderBy = sortOrderBy; // default sort order ASC/DESC

         this.divResultName = divResult; // results div, that will contain the ajax results

         this.divAddEdit = divAddEdit; // div for add/edit records in the list

         this.listTitle = listTitle; // title of the list

         this.paging = pagingFlag; // for pagination true/false, if true, paging will be displayed

         this.totalRecords = 0; // initialize total records;

         this.objName = objName; // this is name of initPage's object

         this.tableColumnsArray = colArray; // table columns (Sr No, Name etc)

         this.editFunctionName    = (editFunctionName)? editFunctionName : 'abcd';  //abcd fake name

         this.deleteFunctionName  = (deleteFunctionName) ? deleteFunctionName : 'abcd';  //abcd fake name
         this.initialQueryString  = (initialQueryString) ? initialQueryString : '';  //abcd fake name

}
/*
@@Author: Pushpender Kumar Chauhan
@@Created On: 11.11.2008
@@purpose: it generates pagination
@@params: nothing
@@return: it returns paging links
*/
initPage.prototype.pagination = function() {

           printLink=''; //initialize the links

           totalPages = Math.ceil(parseFloat(this.totalRecords)/parseFloat(this.recordsPerPage));

           if(parseFloat(this.page)>parseFloat(totalPages)) {

              this.page = totalPages;

           }

           //show paging links when total records are more than records per page

           if(parseFloat(this.totalRecords) > parseFloat(this.recordsPerPage) ) {

                  //printLink = '<span class="fontText"><b>Pages:</b> '+totalPages+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                  printLink='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="fontText">';
                  //printLink+='<tr><td height="5px" colspan="2"></td></tr>';
                  printLink += '<tr><td width="30%" align="left"><b>Total Records:</b> '+this.totalRecords+'</td><td align="right" width="70%"><b>Pages:</b> '+totalPages+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                  if(parseFloat(this.page)>1) {

 

                   printLink += ' <a href="#" onClick="sendRequest(\''+this.URL+'\','+this.objName+',\'page=1&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField+'\');return false;" class="pagingLinks"><img src="'+imagePathURL+'/first.gif" border="0" title="First" align="absmiddle" /></a>&nbsp;&nbsp;';

 

                    printLink += ' <a onClick="sendRequest(\''+this.URL+'\','+this.objName+',\'page='+(parseInt(this.page)-1)+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField+'\');return false;" href="#" class="pagingLinks"><img src="'+imagePathURL+'/back.gif" border="0"  title="Previous" align="absmiddle" /></a>&nbsp;&nbsp;';

                  }

 

                   half = Math.floor(parseFloat(this.linksPerPage)/2);

                   if(this.page>half) {

                     start = this.page - half;

                     limit = parseFloat(this.page) + parseFloat(half);

                     if(limit > totalPages)

                         limit = totalPages;

                   }

                   else {

                     start = 1;

                     limit = this.linksPerPage;

                     if(limit>totalPages)

                         limit = totalPages;

                   }

                   // alert(limit + '==' +start);

 

                    for(link=start;link<=parseFloat(limit);link++) {

                         if(link==this.page) {

                             printLink += ' <b>'+link+'</b>';

                         }

                         else {

                              printLink +=' <a onClick="sendRequest(\''+this.URL+'\', '+this.objName+',\'page='+link+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField+'\' );return false;" class="pagingLinks" href="#"><u>'+link+'</u></a> ';

                         }

                    }

 

                   if(parseFloat(this.totalRecords)>( parseFloat(this.page)*parseFloat(this.recordsPerPage) )) {

                      printLink += '&nbsp;&nbsp;<a href="#" onClick="sendRequest(\''+this.URL+'\','+this.objName+', \'page='+(parseInt(this.page)+1)+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField+'\' );return false;" class="pagingLinks"><img src="'+imagePathURL+'/next.gif" border="0" title="Next" align="absmiddle" /></a>&nbsp;';

                      printLink +=  ' <a onClick="sendRequest(\''+this.URL+'\', '+this.objName+', \'page='+totalPages+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField+'\' );return false;" href="#" class="pagingLinks"><img src="'+imagePathURL+'/last.gif" border="0"  title="Last" align="absmiddle" /></a>';

                    }

 

                    printLink +='</td></tr></table>';

 

           }

           //alert(printLink);

           return printLink;
}


//@@Author: Pushpender Kumar Chauhan
//@@Created On: 11.11.2008
//paging true or false
//modified by :Dipanjan Bhattacharjee
initPage.prototype.printResults = function(resultArray, pageObj){

            newArr.splice(0,newArr.length);
            lastColoredRow=0;
            var sortFieldActualName='';
             
            tb ='';

            tb += '<table border="0" cellpadding="1" cellspacing="1" width="100%" align="center">';
            headLength = this.tableColumnsArray.length;

            if( (headLength && !resultArray.length && this.totalRecords>0) || (headLength && resultArray.length) ){

                tb += '<tr class="rowheading">';

                for(i=0;i<headLength;i++) {
                  if(this.tableColumnsArray[i][3]!='undefined' && this.tableColumnsArray[i][3]===true) {
                        if( this.tableColumnsArray[i][0] == this.sortField) {
                        sortFieldActualName=this.tableColumnsArray[i][1];
                            if(this.sortOrderBy == 'ASC') {
                                showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page='+pageObj.page+'&sortField='+pageObj.sortField+'&sortOrderBy=DESC\');return false;" src="'+imagePathURL+'/arrow-up.gif" border="0"/>';
                            }
                            else {
                                showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page='+pageObj.page+'&sortField='+pageObj.sortField+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-down.gif" border="0"/>';
                            }
                        }
                        else {
                             showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page=1&sortField='+this.tableColumnsArray[i][0]+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-none.gif" border="0"/>';
                        }
                  }
                  else {
                      showSortBox = '';
                  }
                  columnAttributes = this.tableColumnsArray[i][2]!='undefined' ? this.tableColumnsArray[i][2] : '';

                   tb += '<td class="searchhead_text" '+columnAttributes+'>'+this.tableColumnsArray[i][1]+'&nbsp;'+showSortBox+'</td>';
                 }

                tb += '</tr>';

                    len = resultArray.length;
                    var exRow=0;



                    var exFlag=0;
                    var mField='';
                    if(len!='undefined' && len>0) {

                            for(i=0;i<len;i++) {
                               mRow='';
                               if(resultArray[i][this.sortField]){ 
                                if(trim(resultArray[i][this.sortField]).toUpperCase()== mField) {
                                  exRow ++;
                                }
                                else{
                                  exRow=0;
                                  exFlag++;
                                  mField = trim(resultArray[i][this.sortField]).toUpperCase();
                                }
                                if(specialFormatting=='1'){
                                  //pushes "summary rows"
                                  arrayPush(sortFieldActualName,resultArray[i][this.sortField],exRow,(this.sortField+exFlag),headLength);
                                }
                               }
                                var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

 

                                //tb +='<tr '+bg+'>';
                                mRow +='<tr id="'+this.sortField+exFlag+""+"_"+exRow+'"'+bg+'  >';

                                 for(h=0;h<headLength;h++) {

                                      aln = this.tableColumnsArray[h][2]!='undefined' ? this.tableColumnsArray[h][2] : '';

                                      if(this.tableColumnsArray[h][0]=='action') {

                                              //tb +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><img src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="'+this.editFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+',\''+this.divAddEdit+'\');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'+imagePathURL+'/delete.gif" border="0" alt="Delete" onClick="return '+this.deleteFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+');return false;"/></a></td>';
                                              mRow +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><img src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="'+this.editFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+',\''+this.divAddEdit+'\');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'+imagePathURL+'/delete.gif" border="0" alt="Delete" onClick="return '+this.deleteFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+');return false;"/></a></td>';
                                              //mRow +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><input type="image" name="edit_icon_image" src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="'+this.editFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+',\''+this.divAddEdit+'\');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><input type="image" name="delete_icon_image" src="'+imagePathURL+'/delete.gif" border="0" alt="Delete" onClick="return '+this.deleteFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+');return false;"/></a></td>';

                                      }

                                      else {

                                              resultVal = eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]);
                                              cssClass = isNumericCustom(resultVal,'.-') ? '' : 'padding_top';
                                              
                                              //tb +='<td class="'+cssClass+'" '+aln+'>'+resultVal+'&nbsp;</td>';              
                                              mRow +='<td class="'+cssClass+'" '+aln+'>'+resultVal+'&nbsp;</td>';
                                      }

                                  }

                              //tb +='</tr>';
                              mRow +='</tr>';
                              //pushes "normal rows"
                              newArr.push(new Array('!~@~!',mRow,0));
                            }
                            var length=newArr.length;
                            for(var k=0;k<length;k++){
                              tb +=newArr[k][1];
                            }
                    }

                    else {

                        tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';

                        // in case user deletes all the records on some page say page=7, then redirect to previous page

                        if(parseInt(this.page)>1 && parseInt(this.totalRecords) >0) {

                            sendRequest(this.URL,pageObj,'page='+(parseInt(this.page)-1)+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField);

                        }

                }

                if(this.paging) {

                   var pageLinks = this.pagination();

                   if(pageLinks!='') {

                       //tb +='<tr><td class="padding_top" colspan="'+headLength+'">&nbsp;</td></tr>';

                       tb +='<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';

                   }

                }

            }

            else {

                tb +='<tr><td align="center" class="searchhead_text">'+noDataFoundVar+'</td></tr>';            

            }

            tb +='</table>';
            return tb;
}

//@@Author: Pushpender Kumar Chauhan
//@@Created On: 11.11.2008
//paging true or false
initPage.prototype.printResults_OLD = function(resultArray, pageObj){

            tb ='';

            //tb ='<table border="0" cellpadding="0" cellspacing="0" width="100%" class="border1" align="center">';

            //tb +='<tr><td class="listTitle">'+pageObj.listTitle+'</td></tr><tr><td>';

            //tb += '<table border="0" cellpadding="1" cellspacing="1" width="100%" align="center" class="border2">';
            //tb +=globalTB;

            //for head labels

            headLength = this.tableColumnsArray.length;

            if( (headLength && !resultArray.length && this.totalRecords>0) || (headLength && resultArray.length) ){

                tb += '<tr class="rowheading">';

                for(i=0;i<headLength;i++) {

 

                  // alert(this.tableColumnsArray[i][4]);

                  if(this.tableColumnsArray[i][3]!='undefined' && this.tableColumnsArray[i][3]===true) {

                        if( this.tableColumnsArray[i][0] == trim(this.sortField)) {

 

                            if(this.sortOrderBy == 'ASC') {

                                showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page='+pageObj.page+'&sortField='+pageObj.sortField+'&sortOrderBy=DESC\');return false;" src="'+imagePathURL+'/arrow-up.gif" border="0"/>';

                            }

                            else {

                                showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page='+pageObj.page+'&sortField='+pageObj.sortField+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-down.gif" border="0"/>';

                            }

                        }

                        else {

                             showSortBox = '<img onClick="sendRequest('+this.objName+'.URL,'+this.objName+',\'page=1&sortField='+this.tableColumnsArray[i][0]+'&sortOrderBy=ASC\');return false;" src="'+imagePathURL+'/arrow-none.gif" border="0"/>';

                        }

                        //sortTableField

                  }

                  else {

                      showSortBox = '';

                  }

 

                   columnAttributes = this.tableColumnsArray[i][2]!='undefined' ? this.tableColumnsArray[i][2] : '';

                   tb += '<td class="searchhead_text" '+columnAttributes+'>'+this.tableColumnsArray[i][1]+'&nbsp;'+showSortBox+'</td>';

                   }

                tb += '</tr>';

 

                    len = resultArray.length;

                    if(len!='undefined' && len>0) {

                            //for values

                            for(i=0;i<len;i++) {

                                var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
								var bg2 = bg2 == "row0" ? "row1" : "row0";

 
							trId = 'trPrintResult_'+i;
                            tb +='<tr '+bg+' value="'+bg2+'" onmouseover="if(this.className != \'specialHighlight\' && this.className != \'redClass\') this.className=\'highlightPermission\'" onmouseout="if(this.className != \'specialHighlight\' && this.className != \'redClass\') this.className=\''+bg2+'\'" >';

                                //tb +='<tr '+bg+'>';

                                 for(h=0;h<headLength;h++) {

                                      aln = this.tableColumnsArray[h][2]!='undefined' ? this.tableColumnsArray[h][2] : '';

                                      if(this.tableColumnsArray[h][0]=='action') {

                                              tb +='<td class="padding_top" '+aln+'><a href="#" title="Edit"><img src="'+imagePathURL+'/edit.gif" border="0" alt="Edit" onclick="'+this.editFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+',\''+this.divAddEdit+'\');return false;"/></a>&nbsp;&nbsp;<a href="#" title="Delete"><img src="'+imagePathURL+'/delete.gif" border="0" alt="Delete" onClick="return '+this.deleteFunctionName+'('+(eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]))+');return false;"/></a></td>';

                                      }

                                      else {
                                              resultVal = eval('resultArray['+i+'].'+this.tableColumnsArray[h][0]);
                                              cssClass = isNumericCustom(resultVal,'.-') ? '' : 'padding_top';
                                              //alert('result='+resultVal+', css='+cssClass); 
                                              
                                              tb +='<td class="'+cssClass+'" '+aln+'>'+resultVal+'&nbsp;</td>';              
                                      }

                                  }

                              tb +='</tr>';

                            }

                    }

                    else {

                        tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';

                        // in case user deletes all the records on some page say page=7, then redirect to previous page

                        if(parseInt(this.page)>1 && parseInt(this.totalRecords) >0) {

                            sendRequest(this.URL,pageObj,'page='+(parseInt(this.page)-1)+'&sortOrderBy='+this.sortOrderBy+'&sortField='+this.sortField);

                        }

                }

                if(this.paging) {

                   var pageLinks = this.pagination();

                   if(pageLinks!='') {
                    
                      if(globalPaginationPosition==1){ //bottom of page
                          tb =globalTB+tb+'<tr><td colspan="'+headLength+'" align="">'+pageLinks+'&nbsp;</td></tr>';
                        }
                        else if(globalPaginationPosition==2){ //top of page
                          tb =globalTB+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>'+tb;
                        }
                        else if(globalPaginationPosition==3){ //both on bottom and top of page
                          tb =globalTB+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>'+tb+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
                        }
                        else{ //by default bottom of page
                           tb =globalTB+tb+'<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
                        }
                       //tb +='<tr><td class="padding_top" colspan="'+headLength+'">&nbsp;</td></tr>';
                       //tb +='<tr><td colspan="'+headLength+'" align="right">'+pageLinks+'&nbsp;</td></tr>';
                   }
                   else{
                    tb =globalTB+tb;
                    }
                }
                tb =globalTB+tb;
            }

            else {

                tb +=globalTB+'<tr><td align="center" class="searchhead_text">'+noDataFoundVar+'</td></tr>';            

            }

            tb +='</table>';

            //tb +='</td></tr></table>';

            //alert('pushpender==='+tb);

            return tb;
}
//@@Author: Pushpender Kumar Chauhan
//@@Created On: 11.11.2008
initPage.prototype.generateQueryString = function(frmName) {

  frmObj = document.forms[frmName].elements;

  len = frmObj.length;

  queryString = '';

  for(i=0;i<len;i++) {

      if(frmObj[i].checked == true && (frmObj[i].type =='radio' || frmObj[i].type =='checkbox')) {

            if(queryString!='') {

                queryString +='&';

            }

        queryString +=frmObj[i].name + '=' + frmObj[i].value;

      }

      else if (frmObj[i].type !='radio' && frmObj[i].type !='checkbox') {

            if(frmObj[i].type == 'select-multiple') {

                eleName = frmObj[i].name;

                eleLength = frmObj[eleName].length;

                selectedEle='';

                for(m=0;m<eleLength;m++) {

                    if (frmObj[i][m].selected == true) {

                        if (selectedEle != '') {

                            selectedEle += ',';

                        }

                        selectedEle += frmObj[i][m].value;

                    }

                }

                if(queryString!='') {

                    queryString +='&';

                }

                newEleName = eleName.replace('[]','');

                queryString += newEleName+'='+selectedEle;

            }

            else {

                if(queryString!='') {

                    queryString +='&';

                }         

                // replace invalid characters

                nm = frmObj[i].value.replace('&','\\&');

                nm = nm.replace('%','');

                nm = nm.replace('#','');   

                queryString +=frmObj[i].name + '=' + nm;

            }
      }
  }
  return queryString;  
}

function changeHomeImage(param) {
	if (param=='over') {
		document.getElementById("homeImage").src = imagePathURL+'/home1hover.gif';
	}
	else {
		document.getElementById("homeImage").src = imagePathURL+'/home1.gif';
	}
}

//to change themes dynamically
//Author:Dipanjan Bhattacharjee
//Date:16.12.2008                                    
function changePrefs(value){
if(value==currentThemeId){
  return false; //do not call ajax.same theme
}
 url = themeFilePathURL+'/ChangePreference/changePreference.php';
         
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 themeId: value
             },
             onCreate: function() {
                 //showWaitDialog();
             },
             onSuccess: function(transport){
                    // hideWaitDialog();
                     if("CHANGE_THEME_OK" == trim(transport.responseText)) {                     
                       //change the css
                       changeCSS(value);
                       currentThemeId=value;
                     } 
                     else if("CHANGE_THEME_NOK" == trim(transport.responseText)){
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ alert("<?php echo TECHNICAL_PROBLEM; ?>") }
           });


}



//For changing CSS dynamically
//Author:Dipanjan Bhattacharjee
//Date:17.12.2008
function changeCSS(value){
 preloader(value);
 var css=document.getElementById('css1');
 var css2=document.getElementById('css2');

 if(value==1){
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=1');
  //css2.setAttribute('href',cssPathURL+'/menu.css');  
 }
 else if(value==2)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=2');
  //css2.setAttribute('href',cssPathURL+'/menu_brown.css');
 }
 else if(value==3)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=3');
  //css2.setAttribute('href',cssPathURL+'/menu_green.css');
 }
 else if(value==4)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=4');
  //css2.setAttribute('href',cssPathURL+'/menu_green_dark.css');
 }
 else if(value==5)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=5');
 // css2.setAttribute('href',cssPathURL+'/menu_green_light.css');
 }
 else if(value==6)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=6');
  //css2.setAttribute('href',cssPathURL+'/menu_red.css');
 }
 else if(value==7)
 {
  css.setAttribute('href',cssPathURL+'/themeCss.php?id=7');
 // css2.setAttribute('href',cssPathURL+'/menu_blue.css');
 }
 else{
   css.setAttribute('href',cssPathURL+'/themeCss.php?id=1');
   //css2.setAttribute('href',cssPathURL+'/menu.css');
 }
 changeColor(value);
}

//These variable will be used 
//to keep track of latest theme path.
var globalCurrentThemePath='';

function changeColor(value) {
    if (document.getElementById("innerIframe")){
        document.getElementById("innerIframe").src = document.getElementById("innerIframe").src;
    } 
    inputElementsArray = document.getElementsByTagName('input');
    for(i = 0; i < inputElementsArray.length; i++) {
        if(inputElementsArray[i].type == 'image' && (inputElementsArray[i].name!='add_icon_image' && inputElementsArray[i].name!='edit_icon_image' && inputElementsArray[i].name!='delete_icon_image')) {
            oldTheme = inputElementsArray[i].src;
            fileName = oldTheme.substring(oldTheme.lastIndexOf('/')+1);
            if (value == 1) {
                inputElementsArray[i].src = imagePathURL + '/' + fileName;
                globalCurrentThemePath=imagePathURL;
            }
            else if (value == 2) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Brown/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Brown/';
            }
            else if (value == 3) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Green/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Green/';
            }
            else if (value == 4) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Orange/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Orange/';
            }
            else if (value == 5) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Green_light/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Green_light/';
            }
            else if (value == 6) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Violet/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Violet/';
            }
            else if (value == 7) {
                inputElementsArray[i].src = imagePathURL + '/Themes/Blue_light/' + fileName;
                globalCurrentThemePath=imagePathURL+ '/Themes/Blue_light/';
            }
            else {
                inputElementsArray[i].src = imagePathURL + '/' + fileName;
                globalCurrentThemePath=imagePathURL;
            }
        }
    }
}



//pre-loading images
function preloader(value) 
{
 // counter
 var i = 0;
 // create object
 imageObj1 = new Image();
 imageObj2 = new Image();
 imageObj3 = new Image();
 imageObj4 = new Image();
 imageObj5 = new Image();
 imageObj6 = new Image();
 imageObj7 = new Image();   
 
 // set image list
 images = new Array();
 
 
  //Default
 images[0]=imagePathURL+'/navmid.gif';
 images[1]=imagePathURL+'/navleft.gif';
 images[2]=imagePathURL+'/navright.gif';
 images[3]=imagePathURL+'/arrow_18.gif';
 images[4]=imagePathURL+'/topmid.gif';
 images[5]=imagePathURL+'/topleft.gif';
 images[6]=imagePathURL+'/topright.gif';
 
 //start preloading
 if(value==1){
 for(i=0; i<=6; i++){
     imageObj1 = new Image();
     imageObj1.src=images[i];
 }
 }
 
 //Brown
 images[0]=imagePathURL+'/Themes/Brown/navmid.gif';
 images[1]=imagePathURL+'/Themes/Brown/navleft.gif';
 images[2]=imagePathURL+'/Themes/Brown/navright.gif';
 images[3]=imagePathURL+'/Themes/Brown/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Brown/topmid.gif';
 images[5]=imagePathURL+'/Themes/Brown/topleft.gif';
 images[6]=imagePathURL+'/Themes/Brown/topright.gif';
 
 //start preloading
 if(value==2){
 for(i=0; i<=6; i++){
     imageObj2 = new Image();
     imageObj2.src=images[i];
 }
 }
 
 //Green
 images[0]=imagePathURL+'/Themes/Green/navmid.gif';
 images[1]=imagePathURL+'/Themes/Green/navleft.gif';
 images[2]=imagePathURL+'/Themes/Green/navright.gif';
 images[3]=imagePathURL+'/Themes/Green/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Green/topmid.gif';
 images[5]=imagePathURL+'/Themes/Green/topleft.gif';
 images[6]=imagePathURL+'/Themes/Green/topright.gif';
 
 
 //start preloading
 if(value==3){
 for(i=0; i<=6; i++){
     imageObj3=new Image();
     imageObj3.src=images[i];
 }
 }
 
 //Dark Green
 images[0]=imagePathURL+'/Themes/Orange/navmid.gif';
 images[1]=imagePathURL+'/Themes/Orange/navleft.gif';
 images[2]=imagePathURL+'/Themes/Orange/navright.gif';
 images[3]=imagePathURL+'/Themes/Orange/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Orange/topmid.gif';
 images[5]=imagePathURL+'/Themes/Orange/topleft.gif';
 images[6]=imagePathURL+'/Themes/Orange/topright.gif';
 
 //start preloading
 if(value==4){
 for(i=0; i<=6; i++){
     imageObj4=new Image();
     imageObj4.src=images[i];
 }
 }
 
  //Light Green
 images[0]=imagePathURL+'/Themes/Green_light/navmid.gif';
 images[1]=imagePathURL+'/Themes/Green_light/navleft.gif';
 images[2]=imagePathURL+'/Themes/Green_light/navright.gif';
 images[3]=imagePathURL+'/Themes/Green_light/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Green_light/topmid.gif';
 images[5]=imagePathURL+'/Themes/Green_light/topleft.gif';
 images[6]=imagePathURL+'/Themes/Green_light/topright.gif';
 
 //start preloading
 if(value==5){
 for(i=0; i<=6; i++){
     imageObj5=new Image();
     imageObj5.src=images[i];
 }
 }
 
 //Red
 images[0]=imagePathURL+'/Themes/Violet/navmid.gif';
 images[1]=imagePathURL+'/Themes/Violet/navleft.gif';
 images[2]=imagePathURL+'/Themes/Violet/navright.gif';
 images[3]=imagePathURL+'/Themes/Violet/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Violet/topmid.gif';
 images[5]=imagePathURL+'/Themes/Violet/topleft.gif';
 images[6]=imagePathURL+'/Themes/Violet/topright.gif';
 
 //start preloading
 if(value==6){
 for(i=0; i<=6; i++){
     imageObj6=new Image();
     imageObj6.src=images[i];
 }
 }
 
 //Blue Light
 images[0]=imagePathURL+'/Themes/Blue_light/navmid.gif';
 images[1]=imagePathURL+'/Themes/Blue_light/navleft.gif';
 images[2]=imagePathURL+'/Themes/Blue_light/navright.gif';
 images[3]=imagePathURL+'/Themes/Blue_light/arrow_18.gif';
 images[4]=imagePathURL+'/Themes/Blue_light/topmid.gif';
 images[5]=imagePathURL+'/Themes/Blue_light/topleft.gif';
 images[6]=imagePathURL+'/Themes/Blue_light/topright.gif';

 
 //start preloading
 if(value==7){
 for(var i=0; i<=6; i++){
     imageObj7=new Image();
     imageObj7.src=images[ i ];
 }
 }

}
//----------------------------------------------------------------------
//function used for parse query string
//collected by :dipanjan bhattacharjee
//dt:03.06.2009
//----------------------------------------------------------------------
function querySt(ji) {
    hu = window.location.search.substring(1);
    gy = hu.split("&");
    for (i=0;i<gy.length;i++) {
        ft = gy[i].split("=");
          if (ft[0] == ji) {
           return ft[1];
          }
    }
 }
 
 
 /*
@@Author: Dipanjan Bhattacharjee
@@Created On: 25.06.2009
@@purpose: date difference between two dates and returns number of days
@@params: date1(startdate),date2(enddate),deliminator
@@return: Number of days
*/
function dateDifferenceCalculation(date1,date2,delim){
 var arr1=date1.split(delim); 
 var arr2=date2.split(delim); 

//var sdate=arr1[0]+arr1[1]+arr1[2]; 
//var edate=arr2[0]+arr2[1]+arr2[2]; 

var s1=new Date(arr1[0],arr1[1],arr1[2]); 
var s2=new Date(arr2[0],arr2[1],arr2[2]); 
//Set 1 day in milliseconds 
var one_day=1000*60*60*24 
return (Math.ceil((s2.getTime()-s1.getTime())/(one_day))) 

//return parseInt( parseInt(edate) - parseInt(sdate) ); 
 
}

//To make whole screen disable
function makeScreenDisable(){
 
    document.getElementById('modalPage').style.display = "block";
    document.getElementById('modalPage').style.height=(document.body.clientHeight)+'px';
    document.getElementById('modalPage').style.width=(document.body.clientWidth)+'px';
}
 
 

//To make whole screen enable
function makeScreenEnable(){
 if(DivID==""){
   document.getElementById('modalPage').style.display = "none";
  }
}


function checkFileExtensions(value) {
      //get the extension of the file 
      var val=value.substring(value.lastIndexOf('.')+1,value.length);

      var extArr = new Array('gif','jpg','jpeg','png','bmp','doc','pdf','xls','csv','txt','rar','zip','gz','tar','docx', 'xlsx','pptx','ppt');

      var fl=0;
      var ln=extArr.length;
      
      for(var i=0; i <ln; i++){
          if(val.toUpperCase()==extArr[i].toUpperCase()){
              fl=1;
              break;
          }
      }
      
      if(fl==1){
        return true;
      }
      else{
        return false;
      }   
}


//to change help functionality dynamically
//Author:Parveen Sharma
//Date:16.08.2009                                    
function changeHelpFacility(ele){
   
   var help=ele.checked==true ? 1 : 0;   
   var url = themeFilePathURL+'/ChangePreference/changeHelpPreference.php';
         
   new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 helpId: help
             },
             onCreate: function() {
                 //showWaitDialog();
             },
             onSuccess: function(transport){
                    // hideWaitDialog();
                     if("HELP_CHANGE_OK" == trim(transport.responseText)) {
                       toggleHelpFacility(help);
                       if(help==1) {
                           document.getElementById('helpChkImg').src=globalCurrentThemePath+'/help_on.gif';
                           document.getElementById('helpChkImg').title='Help Off';
                       }
                       else{
                          document.getElementById('helpChkImg').src=globalCurrentThemePath+'/help_off.gif';
                          document.getElementById('helpChkImg').title='Help On';
                          document.getElementById('divHelpInfo').style.visibility='hidden';
                       }
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
   });
}

function toggleHelpFacility(help){
 var eles=document.getElementsByTagName("INPUT");
   var len=eles.length;
   for(var i=0;i<len;i++){
    if(eles[i].name=='helpOnOFF'){
      eles[i].style.display=(help==1?'':'none');
    }
 }
}


//to help images download
//Author:Parveen Sharma
//Date:16.08.2009                                    
function getHelpImageDownLoad(ele,title) {
  
    var path=imagePathURL+'/Help/'+ele;
    
    var myImage = new Image();
    myImage.name = "imgPath";
    myImage.src = path;
  
    var hh = myImage.height;
    var ww = myImage.width;
  
    if(ww <= 200) {
      ww = 800;
    }
    
    if(hh <= 200) {
      hh = 600;
    }
    
    path = interfacePathURL+"/listHelp.php?ele="+ele+"&title="+title;
    window.open(path,title,"status=1,menubar=1,scrollbars=1, width="+ww+", height="+hh+", top=100,left=50");
}


/*FUNCTIONS NEEDED TO MAKE SPECIAL MULTI DRODOWNS*/

function totalSelected(element,topDiv){
    var len=document.getElementById(element).options.length;
    var ele=document.getElementById(element);
    var fl=0;
    for(var i=0;i<len;i++){
        if(ele[i].selected==true){
            fl++;
        }
    }
    if(fl>0){
       document.getElementById(topDiv).innerHTML='Total '+fl+' '+selectTextForMultiDropDowns+'(s) are selected';
    }
    else{
        document.getElementById(topDiv).innerHTML=initialTextForMultiDropDowns;
    }
}
//to select all checkboxes+dropdowns in popuped div
function selectAllCheckBoxes(state,targetDiv,src,topDiv){
   var chkElements = document.getElementById(targetDiv).getElementsByTagName('INPUT');
   var len=chkElements.length;
   for(var i=0;i<len;i++){
    if (chkElements[i].type.toUpperCase()=='CHECKBOX' && chkElements[i].name=='multiSpecialChk'){
       chkElements[i].checked=state;
       document.getElementById(src).options[chkElements[i].value].selected=state;
    }
   }
   totalSelected(src,topDiv,initialTextForMultiDropDowns);//to update the top div
  }
  
  //to select dropdowns in multiselect dds
  function selectDDS(state,index,src,topDiv){
   document.getElementById(src).options[index].selected=state;
   totalSelected(src,topDiv,initialTextForMultiDropDowns);//to update the top div
  }

  //main function
  function popupMultiSelectDiv(src,target,container,topTarget,setZIndexManually,setHeightManually){
    var srcElement=document.getElementById(src);
    var tmpSrcElement=srcElement;
    //calculating top   
    var top=0;
    try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetTop)){
           top += tmpSrcElement.offsetTop;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
    }
    catch(e){}

    //calculating left
    tmpSrcElement=srcElement;
    var left=0;
    try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetLeft)){
           left += tmpSrcElement.offsetLeft;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
    }
    catch(e){}

    var len=srcElement.options.length;
    if(!len){ //if no elements are there
        document.getElementById(topTarget).innerHTML=noDataFoundVar;
        return false;
    }

    var targetElement=document.getElementById(target);
    //if pop-up is open,close it
    if(targetElement.style.display!='none'){
        closeTargetDiv(target,container);
        return false;
    }
    var height=srcElement.offsetHeight;
    var width=srcElement.offsetWidth;
    targetElement.style.left=left-1+'px';
    targetElement.style.top=height+top+'px';
    targetElement.style.width=(width+2)+'px';
    //targetElement.style.height=2*height+'px';
    if(!setHeightManually){
     targetElement.style.height='260px';
    }
    targetElement.style.display='';
    targetElement.style.overflow='auto';
    if(!setZIndexManually){
     targetElement.style.zIndex=10;
    }

    //building table
    var tablsStr='';
    var checked='';
    var totalFl=0;
    
    for(var i=0;i<len;i++){
     var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
     tablsStr +='<tr '+bg+'>';
     if(srcElement.options[i].selected==true){
      checked='checked';
      totalFl ++;
     }
     else{
       checked='';
     }
     tablsStr +='<td width="5%" class="padding_top" align="left"><input type="checkbox" value="'+i+'" name="multiSpecialChk" '+checked+' onclick=selectDDS(this.checked,'+i+',"'+src+'","'+topTarget+'"); /></td><td style="width:85%" class="padding_top" align="left">'+srcElement.options[i].text+'</td>';
       tablsStr +='</tr>';
    }
    
    if(i==totalFl){
      checked='checked';
    }
    else{
      checked='';
    }
   if(totalFl>0){ 
     document.getElementById(topTarget).innerHTML='Total '+totalFl+' '+selectTextForMultiDropDowns+'(s) are selected';
   }
   else{
       document.getElementById(topTarget).innerHTML=initialTextForMultiDropDowns;
   }
    
    var isFFB = (document.all) ? 0 : 1;
    var multiTableWidth=100;
    var multiTableRightPadding=5;
    var extraCnt=12;
    if(!isFFB){
      multiTableWidth=98;
      multiTableRightPadding=15;
      extraCnt=11;
    }
    var headerString='<table width="'+multiTableWidth+'%" border="0" cellpadding="0" cellspacing="0" style="background-color:#FFFFEA;">';

    var footerString='<tr ><td colspan="2" align="right" class="padding_top" style="padding-right:'+multiTableRightPadding+'px;"><a title="Close" href="JavaScript:void(0);" onclick=closeTargetDiv("'+target+'","'+container+'");><b>X</b></a></td></tr></table>';

    if(tablsStr!=''){ 
     tablsStr ='<tr class="rowheading"><td align="left"><input type="checkbox" id="selectAllChksMulti_'+src+'" name="selectAllChksMulti" '+checked+' onclick=selectAllCheckBoxes(this.checked,"'+target+'","'+src+'","'+topTarget+'"); /></td><td class="padding_top" style="padding-right:'+multiTableRightPadding+'px;"> Select All</td><td align=right><a title="Close" href="JavaScript:void(0);" onclick=closeTargetDiv("'+target+'","'+container+'");><b>X</b></a></td></tr>'+tablsStr; 
     
       var extraRows='';
       for(;i<extraCnt;i++){//to make "close" button comes at bottom of div
          extraRows +='<tr class="row0"><td style="background-color:#FFFFFF" colspan="2" class="padding_top">&nbsp;</td></tr>'; 
       }
    }
    else{
        tablsStr ='<tr><td class="padding_top" valign="top" height="'+(2*height-10)+'px">'+noDataFoundVar+'</td></tr>'
    }
    tablsStr =headerString+tablsStr+extraRows+footerString;
    targetElement.innerHTML=tablsStr;
    
    if(i>0){
      document.getElementById('selectAllChksMulti_'+src).focus();
    }
    
    document.getElementById(container).style.visibility='hidden';
   }
//used to close popuped div
function closeTargetDiv(target,container){
  document.getElementById(target).style.display='none';
  document.getElementById(container).style.visibility='visible'
}
//to overlap dd with the div
function makeDDHide(src,target,ele){
    
    //as this coding is not working in IE6
    if(isIE6Browser()){
       return true;
    }
    var srcElement=document.getElementById(src);
    var isFFB = (document.all) ? 0 : 1;
    if(isFFB){
     document.getElementById('downArrawId').style.marginBottom='0px';
    }
    else{
        document.getElementById('downArrawId').style.marginBottom='-4px';
    }
    //this will make multi dd to size=1
    srcElement.size=1;
    var tmpSrcElement=srcElement;
    var top=0;
    try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetTop)){
           top += tmpSrcElement.offsetTop;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
    }
    catch(e){}

    tmpSrcElement=srcElement;
    var left=0;
    try{
    while(tmpSrcElement.tagName != "BODY"){
       if(isNumeric(tmpSrcElement.offsetLeft)){
           left += tmpSrcElement.offsetLeft;
       }
       tmpSrcElement=tmpSrcElement.offsetParent;
     }
    }
    catch(e){}
    
    var targetElement=document.getElementById(target); 
    var height=srcElement.offsetHeight;
    var width=srcElement.offsetWidth;
    targetElement.style.left=left-1+'px';
    targetElement.style.top=top+'px';
    targetElement.style.width=(width-0)+'px';
    targetElement.style.height=(height-3)+'px';
    targetElement.style.display='';
    document.getElementById(ele).style.textAlign='left';
    document.getElementById(ele).innerHTML=initialTextForMultiDropDowns;
    document.getElementById(ele).className="textClass"; 
}

/*FUNCTIONS NEEDED TO MAKE SPECIAL MULTI DRODOWNS*/

//*******************************************************************************************
/**********FUNCTIONS USED TO BUILD AND CONTROL COLOR PICKER*/
//Author: Dipanjan Bhattacharjee
//Date: 18.03.2009
//********************************************************************************************

/*
  divId:    where the colorpicker should appear
  prevId:   where the preview should appear
  targetId: where the target should appear
*/
function colorTable(divId,prevId,targetId){
    var clrfix = Array("#000000","#333333","#666666","#999999","#cccccc","#ffffff","#ff0000","#00ff00","#0000ff","#ffff00","#00ffff","#ff00ff");
    var table ='<table border="0"  cellpadding="0" cellspacing="0" bgcolor="#000000" style="border:1px solid black">';
    //table +='<tr><td colspan="5" align="left" bgcolor="white"><input type="text" class="inputbox" name="preCol" id="'+prevId+'" style="width:215px"/></tr><tr>';
    table +='<tr><td colspan="5" align="left" bgcolor="white" id="'+prevId+'" height="15px"></td><tr>';
    table += '';
    for(var j=0;j<3;j++){
        table += '<td width="11"><table bgcolor="#000000"  border="0"  cellpadding="0" cellspacing="1"  class="color_table">';
        for(var i=0;i<12;i++){
            var clr ='#000000';
            if(j==1){
                clr = clrfix[i];    
            }
            table += '<tr><td bgcolor="'+clr+'" class="cell_color" onmouseover="showClr('+"'"+clr+"'"+','+"'"+prevId+"'"+')" onclick="setClr('+"'"+clr+"'"+','+"'"+targetId+"'"+')"></td></tr>';
        }
        table += '</table></td>';        
    }
    table +='<td><table border="0" cellpadding="0" cellspacing="0">';    
    for (var c = 0; c<6; c++) {
        if(c==0 || c==3){
            table +="<tr>";    
        }
        table += "<td>"    
        
        table = table+'<table border="0" cellpadding="0" cellspacing="1" class="color_table"> ';
        for (var j = 0; j<6; j++) {
            table +="<tr>";
            for (var i = 0; i<6; i++) {
                var clrhex = rgb2hex(j*255/5,i*255/5,c*255/5);
                //var clrhex=rgb2hex(255/5,255/5,255/5);
                table += '<td bgcolor="'+clrhex+'" class="cell_color" onmouseover="showClr('+"'"+clrhex+"'"+','+"'"+prevId+"'"+')" onclick="setClr('+"'"+clrhex+"'"+','+"'"+targetId+"'"+')""></td>';
            }
            table +="</tr>";
        }
        table +="</table>";
        table += "</td>"    
        if(c==2 || c==5){
            table +="</tr>";    
        }    
    }
    table +='</table></td></tr></table>';    
    document.getElementById(divId).innerHTML=table
}

/*Used to generate color*/
function rgb2hex(red, green, blue)
{
    var decColor = red + 256 * green + 65536 * blue;
    var clr = decColor.toString(16);
    for(var i =clr.length;i<6;i++){
        clr = "0"+clr;    
    }
    return '#'+clr;
}

/*Used to show color preview*/
function showClr(color,prev){
    Obj = document.getElementById(prev);
    //Obj.value = color;
    Obj.style.backgroundColor=color;    
}

/*Used to set color in the target*/
function setClr(color,target){
    Obj = document.getElementById(target);
    Obj.value = color.replace('#','');
    Obj.style.backgroundColor=color;    
}

/*
  Sample:
  call this after body onload
  colorTable('div1','test11','test22');
*/


//detecting browser type and version
function isIE6Browser(){
 var isIE6 = /msie|MSIE 6/.test(navigator.userAgent);
 return isIE6;
}


// create a roman numeral from a number
//  param int $num
//  return string
function romanNumerals(num) {
    if (!+num)
        return false;
    var    digits = String(+num).split(""),
        key = ["","C","CC","CCC","CD","D","DC","DCC","DCCC","CM",
               "","X","XX","XXX","XL","L","LX","LXX","LXXX","XC",
               "","I","II","III","IV","V","VI","VII","VIII","IX"],
        roman = "",
        i = 3;
    while (i--)
        roman = (key[+digits.pop() + (i * 10)] || "") + roman;
    return Array(+digits.join("") + 1).join("M") + roman;
}


//to change grouping functionality dynamically
//Author:Dipanjan Bhattacharjee
//Date:16.08.2009                                    
function changeGroupingFacility(ele){
 var url = themeFilePathURL+'/ChangePreference/changeGrouping.php';
 var grouping=ele.checked==true ? 1 : 0;        
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 grouping: grouping
             },
             onCreate: function() {
                 //showWaitDialog();
             },
             onSuccess: function(transport){
                    // hideWaitDialog();
                     if("GROUPING_THEME_OK" == trim(transport.responseText)) {                     
                       //change the grouping
                       makeGroupingOnOff(ele.checked);
                       if(grouping==1){
                           document.getElementById('groupingChkImg').src=imagePathURL+'/group_icon.gif';
                           document.getElementById('groupingChkImg').title='Make Grouping Off';
                       }
                       else{
                          document.getElementById('groupingChkImg').src=imagePathURL+'/group_icon_bw.gif';
                          document.getElementById('groupingChkImg').title='Make Grouping On';
                       }
                     } 
                     else if("GROUPING_THEME_NOK" == trim(transport.responseText)){
                     } 
                     else {
                        messageBox(trim(transport.responseText)); 
                     }

             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
}


var newArr=new Array();
var lastColoredRow=0

//this function is used to push array elements into global array
//Author :Dipanjan Bhattacharjee
//Date : 12.05.2009
function arrayPush(sortField,field,counter,showHide,colspan){
  if(EXPAND_COLLAPSE_CONFIG_PERMISSION !='1'){ //if this functionality is made off via config
    return false;
  }
  
  if(EXPAND_COLLAPSE_USER_PERMISSION=='1'){
    var style='style="display:\'\'"';
  }
  else{
    var style='style="display:none"';
  }
  
  var expandString='<img src="'+imagePathURL+'/plus.gif" title="Expand"/>&nbsp;&nbsp;';
  var collapseString='<img src="'+imagePathURL+'/minus.GIF" title="Collapse" />&nbsp;&nbsp;';
  var l=newArr.length;
  var fl=-1;
  for(var i=0;i<l;i++){
    if(newArr[i][0]!='!~@~!'){  //excluding normal rows
       if(trim(newArr[i][0]).toUpperCase()==trim(field).toUpperCase()){
         fl=i;
         break;
       }
    }
  }
  
  if(fl==-1){
    if(lastColoredRow>0){
     newArr.push(new Array('!~@~!','<tr class="expand_collapse_row_class_for_js" '+style+'><td colspan="'+colspan+'" height="2px" bgcolor="#99BBE8"></td>',0))
    }
    lastColoredRow=0;
    
    //newArr.push(new Array('!~@~!','<tr><td colspan="'+colspan+'" height="5px"></td>',0));
    newArr.push(new Array('!~@~!','',0));
    //newArr.push(new Array(field,'<tr><td colspan="'+colspan+'"><a style="text-decoration:none" onclick=excf("'+showHide+'",'+(counter+1)+',this) name="c">'+collapseString+'</a><span onclick=excf("'+showHide+'",'+(counter+1)+',this.previousSibling) style="color:#3764A0;cursor:pointer"><b>'+sortField+': '+field+' ( '+(counter+1)+' Item )'+'</b></span></td>',counter));
    newArr.push(new Array(field,'',counter));
    //newArr.push(new Array('!~@~!','<tr><td colspan="'+colspan+'" height="2px" bgcolor="#99BBE8"></td>',0));
    newArr.push(new Array('!~@~!','',0));
  }
  else{
    newArr[fl][1]='<tr   class="expand_collapse_row_class_for_js" '+style+'><td colspan="'+colspan+'" align="left"><a style="text-decoration:none" onclick=excf("'+showHide+'",'+(counter+1)+',this) name="c">'+collapseString+'</a><span onclick=excf("'+showHide+'",'+(counter+1)+',this.previousSibling) style="color:#3764A0;cursor:pointer"><b>'+sortField+': '+field+' ( '+(counter+1)+' Items )'+'</b></span></td>';
    newArr[fl][2]=counter;
    newArr[fl-1][1]='<tr class="expand_collapse_row_class_for_js" '+style+'><td colspan="'+colspan+'" height="5px"></td>';
    newArr[fl+1][1]='<tr class="expand_collapse_row_class_for_js" '+style+'><td colspan="'+colspan+'" height="2px" bgcolor="#99BBE8"></td>';
    lastColoredRow++;
  }
}
//this function is used to expand/collapse rows
//Author :Dipanjan Bhattacharjee
//Date : 12.05.2009
function excf(id,cnt,ele){
   var parent=ele;
   var expandString='<img src="'+imagePathURL+'/plus.gif" title="Expand"/>&nbsp;&nbsp;';
   var collapseString='<img src="'+imagePathURL+'/minus.GIF" title="Collapse" />&nbsp;&nbsp;';
   
   if(parent.name=='c'){
       for(var i=0;i<cnt;i++){
         document.getElementById(id+'_'+i).style.display='none';
       }
     parent.name='e';
     parent.innerHTML=expandString;
       
    }
    else if(parent.name=='e'){
       for(var i=0;i<cnt;i++){
         document.getElementById(id+'_'+i).style.display='';
       }
     parent.name='c'; 
     parent.innerHTML=collapseString;  
    }
}

function makeGroupingOnOff(ele){
  var state = ele==true ? '' : 'none';
  EXPAND_COLLAPSE_USER_PERMISSION =ele==true ? 1 : 0;
  
  var nrows=document.getElementsByTagName('TR');
  var len=nrows.length;
  for(var i=0;i<len;i++){
    if(nrows[i].className=='expand_collapse_row_class_for_js'){
       nrows[i].style.display=state;
    }
  }
}

var topPos = 0;
var leftPos = 0;
function showHelp(fileName, fileType) {
	//alert(fileName+'		'+fileType);
	var url = themeFilePathURL+'/Populate/getHelpVideoInfo.php';
         new Ajax.Request(url,
           {
             method:'post',
             parameters: {
                 fileName : fileName,
                 fileType : fileType
             },
             onCreate: function() {
                 showWaitDialog(true);
             },
             onSuccess: function(transport){
				 hideWaitDialog(true);
				 document.getElementById('helpVideoContainerDiv').style.textAlign='';
				 var ret=trim(transport.responseText);
				 if("HELP_MEDIA_FILE_NOT_EXISTS" != ret && "INVALID_HELP_MEDIA_REQUEST" != ret && "HELP_FILE_NOT_FOUND" != ret){
					 if(fileType==1){
                       document.getElementById('helpVideoContainerDiv').innerHTML=ret;
					   //displayWindow('helpVideoDiv',650,330);
					   displayFloatingDiv('helpVideoDiv', 'Help', 300, 150, leftPos, topPos,1);
				       leftPos = document.getElementById('helpVideoDiv').style.left;
					   topPos = document.getElementById('helpVideoDiv').style.top;
					 }
					 else if(fileType==2){
						 var so1 = new SWFObject(jsPathURL+"/YTPlayer.swf", "YTPlayer", "640", "320", "8",  null, true);
 	                     so1.addParam("allowFullScreen", "false");
                         so1.addParam("allowSciptAccess", "always");
                         so1.addVariable("movieName", ret);
                         so1.addVariable("autoStart", "true");
                         so1.addVariable("logoPath", ""); // 60*60 dimension
                         so1.addVariable("logoPosition", "top_left"); // accepted values are top_left, top_right, bottom_left and bottom_right
                         so1.addVariable("logoClickURL", "");
                         so1.write("helpVideoContainerDiv"); 
						 //displayWindow('helpVideoDiv',650,330);
						 displayFloatingDiv('helpVideoDiv', 'Help', 300, 150, leftPos, topPos,1);
	 			         leftPos = document.getElementById('helpVideoDiv').style.left;
					     topPos = document.getElementById('helpVideoDiv').style.top;
					 }
					 else{
                        messageBox("<?php echo INVALID_HELP_MEDIA_REQUEST;?>");
					 }
				 }
				 else {
					messageBox(trim(transport.responseText)); 
				 }
             },
             onFailure: function(){ messageBox("<?php echo TECHNICAL_PROBLEM; ?>") }
           });
	document.getElementById('modalPage').style.display = "none"; //removes black screen



}

function checkDomainWithoutMsg(nname)
{
var arr = new Array(
'.com','.net','.org','.biz','.coop','.info','.museum','.name',
'.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag',
'.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw',
'.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm',
'.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc',
'.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr',
'.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz',
'.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm',
'.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm',
'.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm',
'.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq',
'.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki',
'.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li',
'.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg',
'.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt',
'.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng',
'.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf',
'.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py',
'.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg',
'.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv',
'.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn',
'.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um',
'.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws',
'.wf','.ye','.yt','.yu','.za','.zm','.zw');

var mai = nname;
var val = true;

var dot = mai.lastIndexOf(".");
var dname = mai.substring(0,dot);
var ext = mai.substring(dot,mai.length);
//alert(ext);
    
if(dot>2 && dot<57)
{
    for(var i=0; i<arr.length; i++)
    {
      if(ext == arr[i])
      {
         val = true;
        break;
      }    
      else
      {
         val = false;
      }
    }
    if(val == false)
    {
        // alert("Website extension "+ext+" is not correct");
         return false;
    }
    else
    {
        for(var j=0; j<dname.length; j++)
        {
          var dh = dname.charAt(j);
          var hh = dh.charCodeAt(0);
          if((hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==45 || hh==46)
          {
             if((j==0 || j==dname.length-1) && hh == 45)    
               {
                  //  alert("Website name should not begin are end with '-'");
                  return false;
              }
          }
        else    {
               //alert("Website name should not have special characters");
             return false;
          }
        }
    }
}
else
{
 //alert("Website name is too short/long");
 return false;
}    

return true;
}


function printResultsNoSortingColSpan(dv, resultArray, tableHeadArray) {
    
            tb ='';
            tb = '<table border="0" cellpadding="0" cellspacing="1" width="100%">';
            //for head labels
            headLength = tableHeadArray.length;
            //alert(tableHeadArray+"\n\r"+headLength);
            totalAmount =0;
            paidAmount =0;
            
                tb += '<tr class="rowheading">';
                find='0';            
                for(i=0;i<headLength;i++) {
                   wdth = tableHeadArray[i][2]!='undefined' ? tableHeadArray[i][2] : '';  //cell width
                   aln = tableHeadArray[i][3]!='undefined' ? tableHeadArray[i][3] : '';  //align
                   tb += '<td class="searchhead_text" valign="middle"  '+wdth+' '+aln+'>'+tableHeadArray[i][1]+'&nbsp;</td>';                         
                   if(tableHeadArray[i][5]==true) {
                     find=i+1;            
                     break;
                   }
                }
                tb += '</tr>';
                if(find>0) {
                  tb += '<tr class="rowheading">'; 
                  for(i=find;i<headLength;i++) {
                    wdth = tableHeadArray[i][2]!='undefined' ? tableHeadArray[i][2] : '';  //cell width
                    aln = tableHeadArray[i][3]!='undefined' ? tableHeadArray[i][3] : '';  //align
                    tb += '<td class="searchhead_text" valign="middle"  '+wdth+' '+aln+'>'+tableHeadArray[i][1]+'&nbsp;</td>';                         
                  }
                  tb += '</tr>';
                }

                len = resultArray.length;
                if(len!='undefined' && len>0) {
                        //for values
                        for(i=0;i<len;i++) {
                            var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';
                            tb +='<tr '+bg+'>';
                             for(h=0;h<headLength;h++) {
                                if(find>0 && tableHeadArray[h][5]==true) {
                                  h++;                          
                                }
                                aln = tableHeadArray[h][3]!='undefined' ? tableHeadArray[h][3] : '';  //align
                                if(h>=2) {
                                  tb +='<td class="padding_top" valign="top" nowrap '+aln+'>'+eval('resultArray['+i+'].'+tableHeadArray[h][0])+'&nbsp;</td>';
                                }
                                else {
                                  tb +='<td class="padding_top" valign="top" '+aln+'>'+eval('resultArray['+i+'].'+tableHeadArray[h][0])+'&nbsp;</td>';
                                }
                                
                                if(h==3) {
                                  str = eval('resultArray['+i+'].'+tableHeadArray[h][0]);
                                  str = str.replace("<span class='redColor'>","");
                                  str = str.replace("</span>","");
                                  str = trim(str);
                                  if(str!="---") {
                                    totalAmount = parseFloat(totalAmount,2) + parseFloat(str,2);
                                  }
                                }
                                if(h==7) {
                                  str = eval('resultArray['+i+'].'+tableHeadArray[h][0]);
                                  str = str.replace("<span class='redColor'>","");
                                  str = str.replace("</span>","");
                                  str = trim(str);
                                  if(str!="---") {
                                    paidAmount = parseFloat(paidAmount,2) + parseFloat(str,2);
                                  }
                                }
                             }
                          tb +='</tr>';
                        }
                        var bg = bg=='class="row0"' ? 'class="row1"' : 'class="row0"';

						balance = "0";
						if(totalAmount !="---") {
						  balance = balance + parseFloat(totalAmount,10);
						}
						if(paidAmount !="---") {
						  balance = balance - parseFloat(paidAmount,10);
						}
                        tb +='<tr '+bg+'>';
                          tb +='<td class="padding_top" colspan="6" align="right" valign="top"><b>Total Amount</b></td>';
                          tb +='<td class="padding_top" align="right" valign="top"><b>'+totalAmount+'</b></td></tr>';
                          tb +='<tr><td class="padding_top" colspan="6" align="right" valign="top"><b>Prev. Paid Amount</b></td>';
                          tb +='<td class="padding_top" align="right" valign="top"><b>'+paidAmount+'</b></td>';
                          tb +='</tr>';
						  tb +='<tr><td class="padding_top" colspan="6" align="right" valign="top"><b>Balance Amount</b></td>';
                          tb +='<td class="padding_top" align="right" valign="top"><b>'+balance+'</b></td></tr>';
                        
                }
                else {
                    tb +='<tr><td colspan="'+headLength+'" align="center">'+noDataFoundVar+'</td></tr>';
            }
 
            tb +='</table>'; 
 
            if(dv!='') {
                document.getElementById(dv).innerHTML = tb;
            }
}
function refreshCaptcha() {
  var img = document.images['captchaimg'];
  img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}


//==============================function for remove data from url===============
function hideUrlData(data,print){
	
	data=data.split("?");
	url=data[0];
	postData=data[1];
	postData=postData.split("&");
	
	var form = document.createElement("form");
	document.body.appendChild(form);
	
	form.method = "POST";
	form.action = url;
	
	var dataVariable = new Array();
	var dataValue = new Array();
	totalVariable=postData.length;
	for(i=0;i<totalVariable;i++){
		saprateData=postData[i].split("=");
		element1 = document.createElement("INPUT");
		element1.type="hidden";
		
		element1.name=saprateData[0];
		element1.value=saprateData[1];
		form.appendChild(element1);
	}
	if(print==true){
		form.target = "Print";
		window.open("Print.htm", "Print","status=1,menubar=1,scrollbars=1, width=700, height=500, top=100,left=50");
	}
	form.submit();
	document.body.removeChild(form);
}
