<?php
header('Content-type: text/css');
global $FE;
require_once($FE . "/Library/common.inc.php");
require_once(BL_PATH . "/UtilityManager.inc.php");
if (!isset($_GET['id']) or empty($_GET['id']) or ($_GET['id'] == 1)) {
	$folderName = IMG_HTTP_PATH;
	$color = '#73AACC';
	$color2 = '#1A4A76';
	$color3 = '#8BBEE9';
	$color4 = '#E4F0FA';
	$color5 = '#306483';
	$color6= '#c5e7f9';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 2) {
	$folderName = IMG_HTTP_PATH . '/Themes/Brown';
	$color = '#CEA36E';
	$color2 = '#9B6824';
	$color3 = '#C9995F';
	$color4 = '#C9995F';
	$color5 = '#835B2C';
	$color6= '#be8236';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 3) {
	$folderName = IMG_HTTP_PATH . '/Themes/Green';
	$color = '#8AB62B';
	$color2 = '#54701B';
	$color3 = '#8AB62B';
	$color4 = '#8AB62B';
	$color5 = '#59771C';
	$color6= '#a1bf61';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 4) {
	$folderName = IMG_HTTP_PATH . '/Themes/Orange';
	$color = '#EFCB6C';
	$color2 = '#D1A023';
	$color3 = '#EFCB6C';
	$color4 = '#EFCB6C';
	$color5 = '#967110';
	$color6= '#fcdd8a';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 5) {
	$folderName = IMG_HTTP_PATH . '/Themes/Green_light';
	$color = '#93BCA7';
	$color2 = '#4F7D66';
	$color3 = '#93BCA7';
	$color4 = '#93BCA7';
	$color5 = '#4A7761';
	$color6= '#afd3c0';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 6) {
	$folderName = IMG_HTTP_PATH . '/Themes/Violet';
	$color = '#CEA1DB';
	$color2 = '#AE73C0';
	$color3 = '#CEA1DB';
	$color4 = '#CEA1DB';
	$color5 = '#8C3FA3';
	$color6= '#d6ace2';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
elseif ($_GET['id'] == 7) {
	$folderName = IMG_HTTP_PATH . '/Themes/Blue_light';
	$color = '#C1DBFA';
	$color2 = '#0C4892';
	$color3 = '#C1DBFA';
	$color4 = '#C1DBFA';
	$color5 = '#0E50A0';
	$color6= '#68aaf5';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}
else {
	$folderName = IMG_HTTP_PATH;
	$color = '#73AACC';
	$color2 = '#1A4A76';
	$color3 = '#8BBEE9';
	$color4 = '#E4F0FA';
	$color5 = '#306483';
	$color6= '#c5e7f9';			//Used on StudentGradeCard Form for Edit/Save button Gradient
}

$browser = $_SERVER['HTTP_USER_AGENT'];
if (stristr($browser,'MSIE')) {
	$topLeftImage = 'top_left_n.gif';
	$topMidImage = 'top_mid_n.gif';
	$topRightImage = 'top_right_n.gif';
	$midLeftImage = 'mid_left_n.gif';
	$midRightImage = 'mid_right_n.gif';
	$bottomLeftImage = 'bottom_left_n.gif';
	$bottomMidImage = 'bottom_mid_n.gif';
	$bottomRightImage = 'bottom_right_n.gif';
}
else {
	$topLeftImage = 'top_left1.png';
	$topMidImage = 'top_mid1.png';
	$topRightImage = 'top_right1.png';
	$midLeftImage = 'mid_left1.png';
	$midRightImage = 'mid_right1.png';
	$bottomLeftImage = 'bottom_left1.png';
	$bottomMidImage = 'bottom_mid1.png';
	$bottomRightImage = 'bottom_right1.png';
}

?>
.box_middle
{
 background-image:url(<?php echo $folderName;?>/topmid.gif) ;
}
.box_left
{
  background-image:url(<?php echo $folderName;?>/topleft.gif) ;
}
.box_right
{
 background-image:url(<?php echo $folderName;?>/topright.gif) ;
}
.floatingDivClass
{
  background-color:<?php echo $color;?>;
  height:25px;
  border-color:<?php echo $color;?>;
  border:2px;
  zoom:1
}
/*Dimmin Divs*/

/*First Modal Type*/
/*General*/
.dimmer
{
	visibility: hidden;
	position:absolute;
	left:0px;
	top:0px;
	/*font-family:verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-weight:bold;
	padding:0px;
	z-index:5000;
}

/*Firefox*/
.dimmer_ff
{
	visibility: hidden;
	position:absolute;
	left:0px;
	top:0px;
	/*font-family:verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-weight:bold;
	padding:0px;
	z-index:5000;
}


/*Internat Explorer*/
.dimmer_ie
{
	visibility: hidden;
	position:absolute;
	left:0px;
	top:0px;
	/*font-family:verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-weight:bold;
	padding:0px;
	z-index:5000;
}

.tl
 {
	background: url(<?php echo $folderName;?>/tl.gif) no-repeat top left;
	margin:-1px;
	background-color:#EDEDED;
}
.tr {background: url(<?php echo $folderName;?>/tr.gif) no-repeat top right;}
.br {background: url(<?php echo $folderName;?>/brcorner.gif) no-repeat bottom right;}
.br1 {background: url(<?php echo $folderName;?>/br.gif) no-repeat bottom right;}
.bl {background: url(<?php echo $folderName;?>/bl.gif) no-repeat bottom left;}
.tr, .tl, .bl, .br {zoom:1;position:relative;}
.bl{padding:10px;}
/*Curly Div style Ends*/


.text_menu {
	/*font-family: verdana, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;

	color: #FFFFFF;
	height:20px;
    /*background-color:#3A92D9;*/
    background-color:<?php echo $color;?>;
	/*background: url(<?php echo $folderName;?>/bg.gif)*/;
	/*background-color:#4FA9DD;*/
}


.payment_menu {
    /*font-family: verdana, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
    color: #FFFFFF;
    height:30px;
    /*background-color:#3A92D9;*/
}


.border
{
 /*border:2px solid <?php echo $color;?>;*/
 border-top:0px;
}


.box {
    background:#fff url(<?php echo $folderName;?>/box10000.gif) top left no-repeat;
    margin-bottom:12px;
    }

.box .b2 {
    background:transparent url(<?php echo $folderName;?>/box20000.gif) bottom right no-repeat;
    }

.box .b3 {
    background:transparent url(<?php echo $folderName;?>/box30000.gif) bottom left no-repeat;
    }


.left .box .b4 {
    padding:12px;
    min-height:100px;
    background:transparent url(<?php echo $folderName;?>/box40000.gif) top right no-repeat;
    }


.showHideRow {
	background-color:<?php echo $color;?>;
}

.reverseshowHideRow {
    <?php
     $col=explode('#',$color);
     if($col[1]!=''){
         $col='#'.strrev($col[1]);
     }
     else{
         $col=$color;
     }
    ?>
    background-color:<?php echo $col;?>;
}



.headingSubMenuRow {
	background-color:<?php echo $color;?>;

}



    .img_class
    {
      background-image:url('<?php echo $folderName;?>/arrow_18.gif');
      background-repeat: no-repeat;
      background-position:100% 50%;
    }





	/*"""""""" Individual Vertical Dividers""""""""*/
	#qm0 .qmdividery
	{
		border-left-width:1px;
		height:15px;
		margin:4px 2px 3px 2px;
		border-color:<?php echo $color3;?>;
	}




	/*"""""""" (main) Rounded Items"""" change font or color for visible menu """"*/
	#qm0 .qmritem span
	{
		border-color:#ffffff;
		background-color:<?php echo $color4;?>;
        color: #000000;
        font-size: 8px;
        font-weight: normal;
	}





	/*"""""""" Custom Rule""""""""*/
	ul#qm0 ul
	{
		padding:10px;
		margin:-2px 0px 0px 0px;
		background-color:<?php echo $color3;?>;
		border-width:1px;
		border-style:solid;
		border-color:#cdcdcd;
	}

    .menu_middle{
     background-image:url(<?php echo $folderName;?>/navmid.gif);
    }
    .menu_left{
     background-image:url(<?php echo $folderName;?>/navleft.gif);
    }
    .menu_right{
     background-image:url(<?php echo $folderName;?>/navright.gif);
    }

	/*"""""""" Custom Rule""""""""*/
	ul#qm0 li:hover > a
	{
		background-color:#000000;
	}
	.qmfv{visibility:visible !important;}.qmfh{visibility:hidden !important;}

   .spanClass1
    {
      background-color:<?php echo $color4;?>;
      border-color:#ffffff;
      overflow:hidden;
      line-height:0px;
      font-size:1px;
      display:block;
      border-style:solid;
      border-width:0px 1px 0px 1px
    }

    .spanClass2
    {
      background-color:<?php echo $color4;?>;
    }


.contenttab_border
{
	border: 1px solid #c6c6c6;
	height:20px;
    /*background-color: #7aa3cd;*/
    /*background-color: #8BBEE9;*/
    background-color: <?php echo $color;?>;
}

.highlightPermission {
	background-color:<?php echo $color5;?>;
	color:#FFFFFF;
}


/*Blue theme table - left, right, bottom border*/
.leftBorder{border-left: 1px solid #cdcdcd;}
.rightBorder{border-right: 1px solid #cdcdcd;}
.bottomBorder{border-top: 1px solid #cdcdcd;}
.fontTitle{
 /*font-family: Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size: 14px; COLOR:<?php echo $color2;?>;font-weight: bold;}
/*font medium*/
.fontTitleM{
 /*font-family: Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size: 12px; COLOR:#000000;font-weight: none;}
.opacityit img{
	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
-moz-opacity: 1;
}

.opacityit:hover img{

	filter:progid:DXImageTransform.Microsoft.Alpha(opacity=40);
	-moz-opacity: 0.4;
}
.image{

	border: 1px solid #ccc;
}

.fontTitleM1
{
	/*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px; COLOR:#4f4f4f;font-weight: none;
}

.modalBackground
{
    filter: Alpha(Opacity=80); -moz-opacity:0.80; opacity: 0.80;
    width: 100%;
    height: 100% !important;
    height: 100%;
    background-color: #41464A;
    position:fixed !important;
	position:absolute;
    z-index: 500;
    top: 0px;
    left: 0px;
}

	.modalBackground IFRAME
	{
	 display:none;/*sorry for IE5*/
	 display/**/:block;/*sorry for IE5*/
	 position:absolute;/*must have*/
	 top:0;/*must have*/
	 left:0;/*must have*/
	 z-index:-1;/*must have*/
	 filter:mask();/*must have*/
	 width:100%;
	 height:100%;
	}


.dimming {
    /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-style: normal;
    position:fixed !important;
	position:absolute;
	/* set z-index higher than possible */
	z-index:1000;
	visibility: hidden;

	border-style: solid;
	border-color: #FFFFFF;
	border-width: 4px;

}



.selectfieldBottomBorder2
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 0px solid #c6c6c6;
	//height:20px;
	border-bottom: 1px solid <?php echo $color2;?>;
	background-color:<?php echo $color2;?>;

	color:#fff;
    width: 186px;
}

.popup_middle
{
 background-image:url(<?php echo $folderName;?>/head_mid.gif);
}
.popup_left
{
  background-image:url(<?php echo $folderName;?>/head_left.gif);
}
.popup_right
{
 background-image:url(<?php echo $folderName;?>/head_right.gif);
}



.top_left{background:url(<?php echo IMG_HTTP_PATH . '/' . $topLeftImage;?>) no-repeat;
}
.top_mid{background:url(<?php echo IMG_HTTP_PATH . '/' . $topMidImage; ?>) repeat-x;
}
.top_right{background:url(<?php echo IMG_HTTP_PATH . '/' . $topRightImage; ?>) no-repeat;
}

.mid_left{background:url(<?php echo IMG_HTTP_PATH . '/' . $midLeftImage; ?>) repeat-y;
}
.mid_right{background:url(<?php echo IMG_HTTP_PATH . '/' . $midRightImage; ?>) repeat-y;
}

.bottom_left{background:url(<?php echo IMG_HTTP_PATH . '/' . $bottomLeftImage; ?>) no-repeat;
}
.bottom_mid{background:url(<?php echo IMG_HTTP_PATH . '/' . $bottomMidImage; ?>) repeat-x;
}
.bottom_right{background:url(<?php echo IMG_HTTP_PATH . '/' . $bottomRightImage; ?>) no-repeat;
}

#contents{
	position:fixed;
	width:879px;
	background-image:url('<?php echo $folderName;?>/footer_midbar.gif');
	background-repeat:repeat-x;
	bottom:0px;
}
.headingtxt12{font-size:16px; font-weight:bold; color:#CC0000; width:970px; border-bottom:#CCCCCC solid 1px; padding-bottom:5px; border-top:#CCCCCC solid 1px; padding-top:5px; padding-left:10px;}

.title_txt{font-size:18px; color:#578510; font-family:Arial, Helvetica, sans-serif; }

.dhtmlgoodies_question{	/* Styling question */
	/* Start layout CSS */
	color:#000;
	font-size:12px;
	/*background-color:#365700;*/
	/*background:#325100 url(images/hide_11.jpg) repeat-x;*/
    background:url(../Storage/Images/faq/link_arrow1.gif) no-repeat left top;
	font-weight:normal;
	font-family:Arial, Helvetica, sans-serif;

	width:950px;
	margin-bottom:2px;
	margin-top:2px;
	padding-top:5px;
	padding-bottom:5px;
	padding-left:10px;

		/* End layout CSS */

	overflow:hidden;
	cursor:pointer;
}
.dhtmlgoodies_question1{	/* Styling question */
	/* Start layout CSS */
	color:#2cab00;
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
	/**font-size:0.9em; **/
	/*background-color:#365700;*/
	/*background:#325100 url(images/hide_11.jpg) repeat-x;*/
	background:url(../Storage/Images/faq/link_arrow2.gif) no-repeat left top;
	font-weight:bold;
	width:950px;
	margin-bottom:2px;
	margin-top:2px;
	padding-top:5px;
	padding-bottom:5px;
	padding-left:10px;

		/* End layout CSS */

	overflow:hidden;
	cursor:pointer;
}



.dhtmlgoodies_answer{	/* Parent box of slide down content */
	/* Start layout CSS */
	/*border:1px solid #317082;
	background-color:#ddebc5;*/
	font-size:12px;
	font-family:Arial, Helvetica, sans-serif;
	text-align:justify;
	margin-top:5px;
	padding-top:5px;
	padding-left:8px;
	padding-bottom:10px;
	background:#f5f5f5;
	width:950px;
	/*width:500px;*/

	/* End layout CSS */

	visibility:hidden;
	height:0px;
	overflow:hidden;
	position:relative;

}
.dhtmlgoodies_answer_content{	/* Content that is slided down */
	padding:5px 5px 5px 10px;

	position:relative;
}

.content_table_border
{
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    border-top:1px solid #c6c6c6;
    border-left:1px solid #c6c6c6;
    border-right:1px solid #c6c6c6;
    border-bottom:1px solid #c6c6c6;
    padding:1px;
    color:#000000;
}

a.linkWhiteText:hover{
   color:#FFFFFF;
   text-decoration:none;
}
a.linkWhiteText {
   color:#FFFFFF;
}

.textClass{
  color: <?php echo $color3;?>
}

.borderColorClass{
  border:2px solid <?php echo $color3;?>
}

/* Reset */
body,img,p,h1,h2,h3,h4,h5,h6,ul {margin:0; padding:0; list-style:none; border:none;}
/* End Reset */

/*body {font-size:0.8em; font-family:Arial,Verdana,Sans-Serif; background: #000;}*/
/*a {color:white;}*/

/* Colors */
.color-yellow {background:#f2bc00;}
.color-red    {background:#dd0000;}
.color-blue   {background:#148ea4;}
.color-white  {background:<?php echo $color; ?>;}
.color-orange {background:#f66e00;}
.color-green  {background:#8dc100;}
.color-yellow h3,
.color-white h3,
.color-green h3
    {color:#000;}
.color-red h3,
.color-blue h3,
.color-orange h3
    {color:#FFF;}
/* End Colors */

/* Head section */
#head {
    /*background: #000 url(../Storage/Images/widget_images/head-bg.png) repeat-x;*/
    height: 1px;
}
#head h1 {
    line-height: 100px;
    color: #FFF;
    text-align: center;
    /*background: url(../Storage/Images/widget_images/inettuts.png) no-repeat center;*/
    text-indent: -9999em
}
/* End Head Section */

/* Columns section */
#columns .column {
    float: left;
    width: 33.3%;
        /* Min-height: */
        min-height: 400px;
        height: auto !important;
        height: 400px;
}

/* Column dividers (background-images) : */
    /*#columns #column1 { background: url(img1/column-bg-left.png) no-repeat right top; }*/
    /*#columns #column3 { background: url(img1/column-bg-right.png) no-repeat left top; }*/

#columns #column1 .widget { margin: 10px 10px 0 5px; }
#columns #column3 .widget { margin: 10px 10px 0 5px; }
#columns .widget {
    margin: 10px 10px 0 5px;
    padding: 2px;
    -moz-border-radius: 4px;
    -webkit-border-radius: 4px;
}
#columns .widget .widget-head {
    color: #FFFFFF;
    overflow: hidden;
    width: 100%;
    height: 30px;
    line-height: 30px;
}
#columns .widget .widget-head h3 {
    padding: 0 5px;
    float: left;
}
#columns .widget .widget-content {
    /*background: #FFFFFF url(img1/widget-content-bg.png) repeat-x;*/
    background: #FFFFFF;
    padding: 0 5px;
    color: #DDD;
    -moz-border-radius-bottomleft: 2px;
    -moz-border-radius-bottomright: 2px;
    -webkit-border-bottom-left-radius: 2px;
    -webkit-border-bottom-right-radius: 2px;
    line-height: 1.2em;
    overflow: hidden;
}
#columns .widget .widget-content p {
    padding: 0.8em 0;
    border-bottom: 1px solid #666;
}
#columns .widget .widget-content img {
    float: right;
    margin: 10px;
    border: 1px solid #FFF;
}
#columns .widget .widget-content pre {
    padding: 0.5em 5px;
    color: #EEE;
    font-size: 12px;
}
#columns .widget .widget-content ul {
    padding: 2px 0 3px 20px;
    list-style: disc;
}
#columns .widget .widget-content ul li {padding: 3px 0;}
#columns .widget .widget-content ul.images {
    padding: 7px 0 0 0;
    list-style: none;
    height: 1%;
}
#columns .widget .widget-content ul.images li {
    display: inline;
    float: left;
}
#columns .widget .widget-content ul.images img {
    display: inline;
    float: left;
    margin: 0 0 7px 7px;
}
/* End Columns section */

/* JS-Enabled CSS */

.widget-head a.remove  {
    float: right;
    display: inline;
    /*background: url(../Storage/Images/widget_images/buttons.gif) no-repeat -24px 0;*/
    background: url(<?php echo $folderName;?>/widget_buttons.gif) no-repeat -24px 0;
    width: 14px;
    height: 14px;
    margin: 8px 4px 8px 0;
    text-indent: -9999em;
    outline: none;
}

.widget-head a.edit  {
    float: right;
    display: inline;
    background: url(../Storage/Images/widget_images/buttons.gif) no-repeat;
    width: 24px;
    height: 14px;
    text-indent: -9999em;
    margin: 8px 4px 8px 4px;
    outline: none;
}

.widget-head a.collapse  {
    float: left;
    display: inline;
    /*background: url(../Storage/Images/widget_images/buttons.gif) no-repeat -52px 0;*/
    background: url(<?php echo $folderName;?>/widget_buttons.gif) no-repeat -52px 0;
    width: 14px;
    height: 14px;
    text-indent: -9999em;
    margin: 8px 0 8px 4px;
    outline: none;
}

.collapsed .widget-head a.collapse {background-position:-38px 0;}
.collapsed .widget-content {display:none !important;}

.widget-placeholder { border: 2px dashed #999;}
#column1 .widget-placeholder { margin: 30px 35px 0 25px; }
#column2 .widget-placeholder { margin: 30px 20px 0 20px; }
#column3 .widget-placeholder { margin: 30px 25px 0 35px; }

.edit-box {
    overflow: hidden;
    background: #333 url(../Storage/Images/widget_images/widget-content-bg.png) repeat-x;
    margin-bottom: 2px;
    padding: 10px 0;
}

.edit-box li.item {
    padding: 10px 0;
    overflow: hidden;
    float: left;
    width: 100%;
    clear: both;
}


.edit-box label {
    float: left;
    width: 30%;
    color: #FFF;
    padding: 0 0 0 10px;
}

.edit-box ul.colors li {
    width: 20px;
    height: 20px;
    border: 1px solid #EEE;
    float: left;
    display: inline;
    margin: 0 5px 0 0;
    cursor: pointer;
}

.instrunctions
{
    /*color:#FF0000;*/
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    padding:1px 1px 1px 1px;
    color:#000000;
    text-align:justify; 
    padding-right:10px;
    text-decoration:none;     
}

.online_instrunctions
{
    /*color:#FF0000;*/
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    padding:2px 5px 2px 5px;
    line-height:20px;
    color:#000000;
    text-align:justify; 
    padding-right:10px;
    text-decoration:none;     
}
/*Hyper link click Set default values in reports*/
.set_default_values{
				color: blue;
				text-decoration: 
				underline; 
				cursor: pointer;
				font-family: arial,helvetica,verdana,sans-serif;
				font-size: 12px;
				}
