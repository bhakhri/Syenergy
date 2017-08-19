<?php
header('Content-type: text/css');
?>
/* CSS Document FOR THEMES*/
html
{
margin:0 0 0 0;
padding:0 0 0 0;
height:100%;
font-size:8px;
}
body
{
margin:0 0 0 0;
padding:0 0 0 0;
height:100%;
}
img {cursor: pointer;}
form{display:inline}
.footer
{
background-color:#4C6D9D;
/*font-family:Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:14px;
font-weight:bold;
color:white;
padding-left:5px;
padding-right:5px;
padding-bottom:5px;
padding-top:5px;

}
.logo
{
 background: url(../Storage/Images/logo.gif) no-repeat top left;
 height:67px;
}

.action_heading
{
background-color:#708090;
/*font-family:Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:18px;
font-weight:bold;
color:white;
padding-left:5px;
padding-right:5px;
padding-bottom:3px;
padding-top:3px;
}
.row_heading
{
  background-color:#BFBFBF;
  font-size:18px;
  font-weight:bold;
  color:white;
  height:10px;
  padding-bottom:1px;
  padding-top:1px;
}

.field1_heading
{
 background-color:#708090;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 font-weight:bold;
 color:white;
 padding-left:5px;
 padding-right:5px;
 padding-top:3px;
 padding-bottom:3px;
 border:1px solid black;
}
.field2_heading
{
 background-color:#BFBFBF;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 font-weight:bold;
 color:white;
 padding-left:5px;
 padding-right:5px;
 padding-top:3px;
 padding-bottom:3px;

}
.field3_heading
{
 background-color:#FFFFFF;
 /*font-family:Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 /*color:white;*/
 COLOR:#1A4A76;
 padding-left:5px;
 padding-right:5px;
 padding-top:0px;
 padding-bottom:1px;

}


.misc_heading
{
 background-color:#BFBFBF;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 font-weight:bold;
 color:white;
 padding-left:5px;
 padding-right:5px;
 padding-bottom:3px;
 padding-top:3px;
 border:1px solid black;
}

.button_class
{
 background-color:white;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 font-weight:bold;
 color:black;
 border:2px outset #999999;
}
.select_class
{
 background-color:white;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 font-weight:bold;
 color:black;
 border:2px outset #999999;
}
.text_class
{
 color:#708090;
 /*font-family:Verdana, Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
}
a:link
{
 color:#000000;   /*#666666*/
 text-decoration:none;
}
a:visited
{
 color:#000000;
 text-decoration:none;
}
a:hover
{
 color:#1647A0;
 text-decoration:underline;
}

A.redLink{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #bb0000 !important;
    text-decoration: none;
}
A.redLink:hover{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #bb0000;
    text-decoration: underline;
}
A.whiteLink{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: normal;
    color: #FFFFFF !important;
    text-decoration: none;
}
A.whiteLink:hover{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: normal;
    color: #FFFFFF;
    text-decoration: underline;
}
A.redLink2{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #CC0000;
    text-decoration: none;

}
A.redLink2:hover{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #CC0000;
    text-decoration: underline;
	 border-bottom:1px solid #CC0000;
}


/*First Modal Type Ends*/

 /*Second Modal Type*/

#modalPage
{
    display: none;
    position:fixed !important;
	position:absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100% !important;
    height: 100%;
}
.modalContainer
{
    position:fixed !important;
	position:absolute;
    left: 50%;
    top: 50%;
    z-index: 1000;
}

/*Second Modal Type Ends*/

/*End of Dimin Divs*/

/*For curly Divs*/
.rounded
 {
   border: 1px solid #beceda;
   zoom:1;
 }

 .text {
	/*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
 font-weight:bold;
	color: #FFFFFF;
}
.text_loginpanel {
	/*font-family: Arial;*/
    font-family:arial,helvetica,verdana,sans-serif;
		font-size: 12px;
 font-weight:bold;

	font-size: 12px;
	color: #666666;
}
#footer
{
width:998px;
background-color:#eaebea;
border: 1px solid #d8d8d8;
height:20px;
position:absolute;
bottom:0px;
left:8px;
/*font-family:Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:10px;
color:#999999;

}
.text a
{
/*font-family: Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	color: #FFFFFF;
	text-decoration:none;
}
.text a:hover
{
/*font-family: Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	color: #FFFFFF;
	text-decoration:underline;
}
.text_topright_links {
	/*font-family: Verdana, Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 10px;
	color: #999999;
	border: 1px solid #f1f1f1; padding-right:15px;
	background-color:'#f7f7f7';
}

.title
{
    /*font-family: verdana, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	padding:0px 0px 5px 7px;
	color: #000000;
	border-bottom:1px dotted #c6c6c6;
}
.title1
{
    /*font-family: verdana, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;

    padding:0px 0px 5px 7px;
    color: #000000;
}
.content
{
   /*font-family: verdana, Helvetica, sans-serif;*/
   font-family:arial,helvetica,verdana,sans-serif;
	font-size: 16px;
	padding:3px 5px 0px 5px;
	color: #000000;
}
.tablecontent
{
   /*font-family: arial;*/
   font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
    padding:17px 5px 0px 5px;
    color: #000000;

}
.tableborder
{
border:1px solid black;
}
.tablefont{
font-size: 7px;

}
.content_title
{
    /*font-family: Arial;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	font-weight:bold;
	padding:5px;
	color:#FFFFFF;
}
.content_title_scroll
{
    /*font-family: Arial;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 15px;
    font-style:italic;
    color:#FFFFFF;
}

.contenttab_border1
{
border: 1px solid #c6c6c6;
border-bottom: 0px solid #c6c6c6;
}
.contenttab_row
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border-left:1px solid #c6c6c6;
	border-right:1px solid #c6c6c6;
    border-bottom:1px solid #c6c6c6;
	padding:2px;
	color:#000000;
}
.contenttab_row1
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	padding:5px;
	color:#000000;
}
.contenttab_row_last
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border-left:1px solid #c6c6c6;
	border-right:1px solid #c6c6c6;
	border-bottom:1px solid #c6c6c6;
	padding:0px;
	color:#000000;
}
.contenttab_internal_rows
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	padding:1px 1px 1px 1px;
	color:#000000;
	text-align:left;
}
.contenttab_internal_rows1
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
    padding:1px 1px 1px 1px;
    color:#000000;

}
.textfield
{
/*font-family: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	height:20px;
	width:200px;
}
.selectfield
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	/*height:20px;*/
	border: 1px solid #c6c6c6;
    width: 186px;
}
.selectfield2
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	height:20px;
	border: 1px solid #c6c6c6;
	width: 170px;
}
.selectfield3
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
    border: 1px solid #c6c6c6;
    height:20px;
    border: 1px solid #c6c6c6;
    width: 50px;
}

.upload
{
/*font-family: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	height:20px;
	width:200px;
}
.padding
{
padding:3px 5px 3px 5px;
font-weight: bold;
}
.padding_top
{
/*font-family: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:12px;
padding:2px 1px 1px 1px;
}
.padding_right
{
/*font-family: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:12px;
padding:1px 10px 1px 1px;
}
.padding2
{
padding:0px 5px 5px 5px;
}
.padding_top2
{
 padding-left:1px;
 padding-top:5px;
 padding-bottom:5px;
 padding-right:1px;
 }
.inputbox3
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;

	border: 1px solid #CCCCCC;
	text-align:right;
	border-width:1px;
	/*font-family:Verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;

}
.padding_top1
{
padding-top:2px;
padding-bottom:2px;
padding-right:1px;
font-size:11px;

}
.searchhead_text
{
 height:20px;
 /*font-family: Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size: 13px;
 font-weight:bold;
 border-bottom:1px solid #dcdcdc;
 padding:0px 0px 0px 1px;
}


.searchhead_text1
{
 height:25px;
 /*font-family: Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size: 13px;


}

.padding_left
{
padding:5px 3px 3px 3px;
padding-left:5px;
}

.padding_left1
{
padding:5px 3px 3px 3px;
padding-left:50px;
}

.padding_more_left
{
padding:5px 3px 3px 3px;
padding-left:171px;
}

.padding_more_right
{
padding:5px 3px 3px 3px;
padding-right:25px;
text-align:right;
}

.padding_more_right1
{
padding:5px 3px 3px 3px;
text-align:right;
padding-left:100px;
}

.textLabel
{
/*font-family: Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:12px;
font-weight:bold;
color:#FFFFFF;
}
.paging
{
 /*color:#FF0000;*/
	padding-right:2px;
	padding-top:0px;
	padding-bottom:0px;
	border-left:1px solid #c6c6c6;
	border-right:1px solid #c6c6c6;
	border-bottom:1px solid #c6c6c6;
	text-align:right;
	text-decoration:none;
}
.fontText {   /* paging text class*/
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight:none;
    color:#000000;
}
.pagingLinks {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight:none;
    color:#565352;
    padding: 2px;
}

.paging1
{
 /*color:#FF0000;*/
	/*font-family: Arial;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	font-weight:bold;
	color:#0066FF;
	padding-right:5px;
	padding-top:2px;
	padding-bottom:2px;

	padding-right:7px;
	text-align:right;
	text-decoration:none;
}


.row2
{
  background-color:#F8F8F8;
  font-size:13px;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  font-family:arial,helvetica,verdana,sans-serif;
}
/* ALTERNATE COLORS FOR TDs */
.row0
{
	background-color: #FBFBFB;
    /*background-color: #EAF3FB;*/
	height: 15px;
}

/* ALTERNATE COLORS FOR TDs */
.row1
{
	background-color: #EDEDED;
    /*background-color: #F1F1F1;*/
	height: 15px;
}

/* (Used in time table))ALTERNATE COLORS FOR TDs */
.trow0
{
    background-color: #FBFBFB;
    height: 15px;
    font-size:11px;
    /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
 }

/* ALTERNATE COLORS FOR TDs */
.trow1
{
    background-color: #EDEDED;
    height: 15px;
    font-size:11px;
    /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
 }
.timtd
{
 border:1px solid #C7C7C7;


}


.rowSelected
{
	background-color: #808080;
	height: 15px;
}
.scroll {
	scrollbar-arrow-color:#1b6078;
	scrollbar-3dlight-color: #e5ecdf;
	scrollbar-darkshadow-color: #a8c4af;
	scrollbar-face-color: #a7c3af;
	scrollbar-highlight-color: #a8c4af;
	scrollbar-shadow-color: #e5ecdf;
	scrollbar-track-color: #d7e5ee;
	overflow: auto;
	height:252px !important;
	height:197px;

	padding-right:10px;
}
.scroll1 {
	scrollbar-arrow-color:#1b6078;
	scrollbar-3dlight-color: #e5ecdf;
	scrollbar-darkshadow-color: #a8c4af;
	scrollbar-face-color: #a7c3af;
	scrollbar-highlight-color: #a8c4af;
	scrollbar-shadow-color: #e5ecdf;
	scrollbar-track-color: #d7e5ee;
	overflow: auto;
	height:330px !important;
	height:280px;

	padding-right:10px;
}
.inputbox, .inputbox0
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
/*	font-weight:bold; */
/* margin: 0px 0px 0px 0px;*/
	border: 1px solid #CCCCCC;
	border-width:1px;
	font-size: 12px;
	width:180px;

}
.inputbox1
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
/*	font-weight:bold; */
/* margin: 0px 0px 0px 0px;*/
	border: 1px solid #CCCCCC;

	border-width:1px;
	font-size: 12px;

}
.inputbox2
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
/*	font-weight:bold; */
/* margin: 0px 0px 0px 0px;*/
	border: 1px solid #CCCCCC;
	text-align:right;
	border-width:1px;
	font-size: 12px;

}
.button
{

	color: #6C737A;
	font: 10px arial;
	/*border: 1px solid #838383;*/
	padding-right: 10px;
	padding-left: 3px;

}

/* CAPTION SETTINGS FOR INPUT FORMS */
.input_form_caption_td
{
	padding-right: 6px;
	text-align: right;
	font-weight: bold;
	color: #0F52B6;
}
.input_form_caption_td2
{
	padding-right: 6px;
	text-align: left;
	font-weight: bold;
	color: #0F52B6;
}
.input_table td
{
	border-bottom: 1px solid #CCCCCC;
	background-color: #FFFFFF;
}
.unsortable
{
	background-color:#d4d4d4; border-bottom:1px solid #dcdcdc; padding:5px 5px 5px 5px;
}
.rowheading
{
	background:#D1D1D1 url(../Storage/Images/nav_li.gif) repeat-x;
	/*
	background-color:#d4d4d4;
	*/
	height:25px;
}
.rowheading2
{
	background-color:#d4d4d4;
	height:10px;
}
.error
{
/*font-family: Verdana, Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
font-size:12px;
font-weight:bold;
color:#9B1102;
}
/**/
.listing_table {
	BACKGROUND-COLOR: #cccccc
}
.listing_table TD {
	PADDING-LEFT: 3px; PADDING-TOP: 0px
}
#listpage_button_bar {
	BORDER-RIGHT: #bcb997 1px solid; BORDER-TOP: #bcb997 1px solid; MARGIN: 0px 0px 5px; BORDER-LEFT: #bcb997 1px solid; BORDER-BOTTOM: #bcb997 1px solid; HEIGHT: 75px; BACKGROUND-COLOR: #fcfcf8
}
.my_icon {
	FILTER: alpha(opacity=80); WIDTH: 113px; HEIGHT: 100px; TEXT-DECORATION: none; moz-opacity: .80; opacity: .80
}
.my_icon A:link {
	TEXT-DECORATION: none
}
.my_icon A:active {
	TEXT-DECORATION: none
}
.my_icon A:visited {
	TEXT-DECORATION: none
}
.my_icon A:hover {
	TEXT-DECORATION: none
}
.pressNo {

}
.pressDown {
	MARGIN-TOP: 3px; PADDING-LEFT: 1px; MARGIN-LEFT: 1px; PADDING-TOP: 2px; TEXT-DECORATION: none
}
.my_icon_hover {
	PADDING-LEFT: 1px; FILTER: alpha(opacity=100); PADDING-TOP: 1px; moz-opacity: .100; opacity: .100
}
#desktop_content_div {
	BORDER-RIGHT: #29379f 1px solid; PADDING-RIGHT: 5px; BORDER-TOP: #29379f 1px solid; PADDING-LEFT: 5px; PADDING-BOTTOM: 5px; MARGIN: 9px; BORDER-LEFT: #29379f 1px solid; PADDING-TOP: 5px; BORDER-BOTTOM: #29379f 1px solid; HEIGHT: auto; BACKGROUND-COLOR: #d4d8e2
}
.desktop_icon_table {
	BACKGROUND-COLOR: #cccccc
}
.headings {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    BACKGROUND-COLOR: #d4d8e2
}
.headings1 {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.footertext {
	FONT-SIZE: 11px; COLOR: #212121;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.maindeskbg {
	BORDER-RIGHT: #cecece 1px solid; BORDER-TOP: #cecece 1px solid; BORDER-LEFT: #cecece 1px solid; BORDER-BOTTOM: #cecece 1px solid; BACKGROUND-COLOR: #f6f7ff
}
.loginbox {
	BORDER-RIGHT: #c5c5c5 1px solid; BORDER-TOP: #c5c5c5 1px solid; BORDER-LEFT: #c5c5c5 1px solid; BORDER-BOTTOM: #c5c5c5 1px solid; BACKGROUND-COLOR: #f4f4f4
}
.login {
	FONT-WEIGHT: bold; FONT-SIZE: 11px; BACKGROUND-IMAGE: url(../../images/index/loginbutton.jpg); WIDTH: 55px; COLOR: #232323; BACKGROUND-REPEAT: no-repeat;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    HEIGHT: 23px
}
.boldgreytext {
	FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #232323;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.bluetext {
	PADDING-RIGHT: 5px; FONT-WEIGHT: bold; FONT-SIZE: 14px; COLOR: #001553;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.breadcrumbs {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #001553;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    TEXT-DECORATION: none
}
.breadcrumbs:hover {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #fe070e;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    TEXT-DECORATION: underline
}

.headerRow {
	COLOR:#FFFFFF; BACKGROUND-COLOR:#FF0000;FONT-WEIGHT: bold; FONT-SIZE: 11px;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.reportDataBold {
	FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.reportData {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.redColor{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
    font-weight: bold;
    COLOR: #BB0000;
}
.redColorBig{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 14px;
    font-weight: bold;
    COLOR: #BB0000;
}
.small_txt
{
 /*font-family: Arial, Helvetica, sans-serif;*/
 font-family:arial,helvetica,verdana,sans-serif;
 font-size: 10px;
 text-align: center;
 width:20px;
 height:15px;
 }

/*class added for reporting.*/
BR.page { page-break-after: always }

.headingFont {
	height:25px;FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.dataFont {
	FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

.reportTitle {
	height:35px;FONT-WEIGHT: bold; FONT-SIZE: 16px; text-decoration:underline; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

.reportDateTime {
	height:15px;FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif*/
    font-family:arial,helvetica,verdana,sans-serif;
}

.reportFooter {
	height:15px;FONT-WEIGHT: bold; FONT-SIZE: 11px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

.reportInformation {
	height:15px;FONT-WEIGHT: normal; FONT-SIZE: 11px; font-style:italic;COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.attendanceMessage{
	font-family:arial,helvetica,verdana,sans-serif;
	COLOR: #FFFFFF;
	background-color: #FF5706;

}

/*---------- bubble tooltip -----------*/
/*css moved to bt.css*/
/*---------- bubble tooltip ends-----------*/

/* class for reports, Please do make your table border='1' */
.reportTableBorder {
	border-collapse:collapse;
}

.htmlElement
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
	height:20px;
	color:#000000;
}

.contenttab_border2
{
border: 1px solid #c6c6c6;
}

.reportBorder
{
border: 1px solid #9F9F9F;
}


/*CSS for scrollers*/

#pscroller2{
width:600px;
height: 15px;
border: 0px solid black;
text-align:left;
}

#pscroller2 a{
text-decoration: none;
}

.someclass{
/*class to apply to your scroller(s) if desired*/
}

#headerimg
 {
  margin-top:1px;
  /*border: 1px solid #E5E5E5;*/
  float:left;
  padding:2px;
  white-space:nowrap;
 }

#headertextbox
{
  width:100%;
  float:left;
  margin: 3px 0 0 8px;
}
#headertextbox span
{
 /*border: 1px solid #E5E5E5;*/
 line-height:17px;
 width:68.8%;
 float:left;
 font-size:11px;
 color:#0F3FA1;
 /*font-family:Arial;*/
 font-family:arial,helvetica,verdana,sans-serif;
 text-align:left;
 padding:.2ex 0;
 margin-top:1px;
}
#headertextbox .text_scroll
{
    width:84.5% ! important;
    color:#FFFFFF;
    font-size: 12px;
}
#headertextbox h4
{
    font-size:11px;
    /*font-family:Georgia;*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.receiptClass
{
FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
/*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
 border-bottom:1px solid black;
}
.receiptBox{
FONT-WEIGHT: normal; FONT-SIZE: 11px; COLOR: #000000;
/*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
font-family:arial,helvetica,verdana,sans-serif;
 border:1px solid black;
}

.lightGrey {
	border:1px solid #C6C6C6;
	padding: 2px 2px 2px 2px;
	background-color: #FFF;
	color:#4A4A4A;
	font-family:arial,helvetica,verdana,sans-serif;
	font-size:12px;
}


.lightGrey:hover {
	background-color: #FFFFEA;
	border:1px solid #676B6E;
}

.internalTable {
	padding: 4px 4px 4px 4px;
	background-color: #FFFFFF;
	border:0px dotted #000;
}

/******************** CSS FOR DROP DOWN MENU*****************************/

  
/*--navigation wrapper--*/  
#navwrapper {  
    margin:0 auto;  
    background-color: #2D2D2D;  
}  
  
#nav {  
    font-family: Arial, Helvetica, sans-serif;  
    font-size:12px;  
    padding-top:4px;  
}  
  
#nav, #nav ul {  
    padding: 0;  
    margin: 0;  
    list-style: none;  
    font-family: Arial, Helvetica, sans-serif;  
}  
  
/*--main nav links style--*/  
#nav a {  
    display:block;  
    padding:7px 7px 7px 7px;  
    color:#000000;  
    text-decoration:none;  
}  
  
#nav a.dmenu {  
  
}  
  
#nav a.dmenu:hover {  
    color:#000000 !important;  
    background-color: #fff !important;  
}  
  
#nav li {  
    float: left;  
}  
  
#nav li {position: relative;}  
  
/* hide from IE, mac */  
#nav li {position: static; width: auto;}  
/* end hiding from IE5 mac */  
  
/*--drop down menu styling--*/  
#nav li ul {  
    position: absolute;  
    display: none;  
    margin-left:-1px;  
    padding-bottom:10px;  
    background-color: #FFFFFF;  
    border: 1px solid #bbb;  
    border-top:none;  
    -moz-box-shadow: 0 0 5px #ddd;  
    -webkit-box-shadow: 0 0 5px #ddd;  
    box-shadow: 0 0 5px #ddd;  
    /*--make it top of all the elements on page, so that it can be visible--*/  
    z-index:5000;  
}  
  
#nav li:hover a, #nav a:focus,  
#nav a:active {  
    padding:7px 7px 7px 7px;  
    color:#fff;  
    background:#444;  
    text-decoration:none;  
}  
  
#nav li ul, #nav ul li  {  
    width: 21em;  
}  
  
/*--drop down menu styling is different from main nav, strict rule--*/  
#nav ul li a  {  
    color:#000000 !important;  
    border-right: 0;  
}  
#nav ul li a:hover  {  
    color:#000000 !important;  
    background-color: #eef3fb !important;  
    border-right: 0;  
}  
  
/*--the selected, current item in the navigation menu--*/  
#nav a.current{  
    color:#000000;  
    font-weight:bold;  
    background:#2D2D2D;  
    text-decoration:none;  
    border-top:2px solid #C33;  
    padding-bottom:5px; /*--removing 2px border from 7px padding--*/  
}  
  
#nav a.current:hover{  
    padding-bottom:5px; /*--removing 2px border from 7px padding--*/  
}  
  
#nav li:hover ul {  
    display: block;  
}  
  
#nav li:hover ul a{  
    color: #000000;  
    background-color: transparent;  
}  
  
#nav ul a:hover {  
    background-color: #606060!important;  
    color: #FFFFFF !important;  
}  
  
 /*--used in drop down menu items--*/  
.menuseprator{border-bottom:1px solid #ddd; margin:10px 0 10px 0;}  
  
/*--for showing down arrow in drop down menu item--*/  
.arrowdown{color#eee; font-size:1em;}    
  
/*--for the left navigation menu--*/  
.floatleft{float:left;}   
  
/*--for the right options navigation menu--*/  
.floatright{float:right;}  
  
/*--clear all floating, left and right--*/  
.clear{clear:both;} 

/*CSS for scrollers Ends*/

/* */

/*
// $History: themeCss2.php $
//
//*****************  Version 31  *****************
//User: Ajinder      Date: 4/13/10    Time: 6:06p
//Updated in $/LeapCC/CSS
//changed background color
//
//*****************  Version 30  *****************
//User: Ajinder      Date: 4/13/10    Time: 12:42p
//Updated in $/LeapCC/CSS
//changed table header css class.
//
//*****************  Version 29  *****************
//User: Rajeev       Date: 10-03-26   Time: 2:07p
//Updated in $/LeapCC/CSS
//updated scroll class for collect fees
//
//*****************  Version 28  *****************
//User: Parveen      Date: 3/16/10    Time: 6:18p
//Updated in $/LeapCC/CSS
//noBorder css added
//
//*****************  Version 27  *****************
//User: Ajinder      Date: 2/15/10    Time: 3:20p
//Updated in $/LeapCC/CSS
//done customization changes
//
//*****************  Version 26  *****************
//User: Ajinder      Date: 1/21/10    Time: 3:43p
//Updated in $/LeapCC/CSS
//done changes for siteMap
//
//*****************  Version 25  *****************
//User: Ajinder      Date: 1/20/10    Time: 5:08p
//Updated in $/LeapCC/CSS
//done changes to Assign Colour scheme to test type and refect this
//colour in student tab. FCNS No. 1102
//
//*****************  Version 24  *****************
//User: Dipanjan     Date: 30/12/09   Time: 14:51
//Updated in $/LeapCC/CSS
//Made UI changes in "Test Wise Performance Report"
//
//*****************  Version 23  *****************
//User: Ajinder      Date: 12/28/09   Time: 4:42p
//Updated in $/LeapCC/CSS
//done changes to make new module for marks transfer
//
//*****************  Version 22  *****************
//User: Ajinder      Date: 11/25/09   Time: 6:42p
//Updated in $/LeapCC/CSS
//improved marks transfer page designing, done changes in final internal
//report as per requirement from sachin sir
//
//*****************  Version 21  *****************
//User: Ajinder      Date: 11/24/09   Time: 4:06p
//Updated in $/LeapCC/CSS
//done changes for help on marks transfer
//
//*****************  Version 20  *****************
//User: Rahul.nagpal Date: 11/11/09   Time: 11:47a
//Updated in $/LeapCC/CSS
//
//*****************  Version 19  *****************
//User: Parveen      Date: 10/24/09   Time: 12:39p
//Updated in $/LeapCC/CSS
//allParentAlertLink class  font-weight: normal updated
//
//*****************  Version 18  *****************
//User: Parveen      Date: 10/21/09   Time: 11:47a
//Updated in $/LeapCC/CSS
//allParentAlertLink function added
//
//*****************  Version 17  *****************
//User: Parveen      Date: 10/20/09   Time: 1:14p
//Updated in $/LeapCC/CSS
//allStudentLink, allReportLink hover class  font-weight:bold
//
//*****************  Version 16  *****************
//User: Parveen      Date: 10/20/09   Time: 12:48p
//Updated in $/LeapCC/CSS
//Anchor tags color code change (allReportLink, allStudentLink)
//
//*****************  Version 15  *****************
//User: Dipanjan     Date: 28/08/09   Time: 18:15
//Updated in $/LeapCC/CSS
//Corrected selectfield class by escaping height parameter correctly
//
//*****************  Version 14  *****************
//User: Dipanjan     Date: 31/07/09   Time: 16:25
//Updated in $/LeapCC/CSS
//Changed dimming divs design
//
//*****************  Version 13  *****************
//User: Ajinder      Date: 7/28/09    Time: 11:44a
//Updated in $/LeapCC/CSS
//reverted back to make it as on 22-july-2009
//
//*****************  Version 10  *****************
//User: Dipanjan     Date: 22/07/09   Time: 18:15
//Updated in $/LeapCC/CSS
//Updated font famility to "arial,helvetica,verdana,sans-serif"
//
//*****************  Version 9  *****************
//User: Rajeev       Date: 7/22/09    Time: 12:50p
//Updated in $/LeapCC/CSS
//added class to fix  	 0000637: Find Student (Admin) > “Show” hyperlink
//should be display in blank color and underlined.
//
//*****************  Version 8  *****************
//User: Dipanjan     Date: 10/07/09   Time: 13:57
//Updated in $/LeapCC/CSS
//Renamed "rightBorder" class to "rightBorderAccounts" as it clashes with
//"rightBorder" class defined in themeCss.php
//
//*****************  Version 7  *****************
//User: Ajinder      Date: 7/07/09    Time: 6:45p
//Updated in $/LeapCC/CSS
//added classes for links in accounts.
//
//*****************  Version 6  *****************
//User: Ajinder      Date: 7/07/09    Time: 10:55a
//Updated in $/LeapCC/CSS
//added classes for accounts.
//
//*****************  Version 5  *****************
//User: Ajinder      Date: 6/11/09    Time: 12:35p
//Updated in $/LeapCC/CSS
//removed un-necessary classes
//
//*****************  Version 4  *****************
//User: Ajinder      Date: 6/11/09    Time: 11:29a
//Updated in $/LeapCC/CSS
//added classes for I-Card and Admit Card.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 6/08/09    Time: 4:31p
//Updated in $/LeapCC/CSS
//added code as told by Dipanjan for hiding select boxes behind dimming
//div.
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 6/08/09    Time: 4:16p
//Updated in $/LeapCC/CSS
//added code for IFRAME.
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 6/08/09    Time: 3:02p
//Created in $/LeapCC/CSS
//file added for themes.
//
//*****************  Version 3  *****************
//User: Ajinder      Date: 4/04/09    Time: 2:45p
//Updated in $/Leap/Source/CSS
//removed class 'highlightPermission'
//
//*****************  Version 2  *****************
//User: Ajinder      Date: 2/19/09    Time: 10:42a
//Updated in $/Leap/Source/CSS
//changed properties for dimming div
//
//*****************  Version 1  *****************
//User: Ajinder      Date: 2/18/09    Time: 3:10p
//Created in $/Leap/Source/CSS
//file added for css merging
*/

 /*CSS FOR EXPANDIBLE/COLLAPSIBLE DIVS*/

.wrap {
    width:500px;
    max-width:500px;
    margin:0 1px;
    text-align:left;
    position:relative;
    }

.left {
    float:left;
    }

div.right, div.left {
    }

img{
border:0;
}
/*CSS FOR EXPANDIBLE/COLLAPSIBLE DIVS ENDS*/
p {margin: 0; padding: 1px;}

a.allReportLink {
	font-weight: normal; font-size: 12px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    text-decoration:underline;
}

a.allReportLink:hover {
	font-weight: bold; font-size: 12px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

a.allStudentLink {
	font-weight: normal; font-size: 12px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    text-decoration:underline;
}

a.allStudentLink:hover {
	font-weight: bold; font-size: 12px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

a.allParentAlertLink {
    font-weight: normal; font-size: 12px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    text-decoration:underline;
}

a.allParentAlertLink:hover {
    font-weight: normal; font-size: 12px; COLOR: #FFFFFF;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}

.highlightRow {
	background-color:#D3E4EF;
}
.small {
	font-weight: normal; font-size: 10px; COLOR: #000000;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
}
.htmlElement2
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 1px solid #c6c6c6;
}
.htmlElement3
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 0px solid #c6c6c6;
}

.lightBlue {
	background-color:#DAE9F1;
}
.inputboxRed
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	border: 1px solid #FF0000;
	border-width:1px;
	/*font-family:Verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;

}
.inputboxRedFilled
{
	padding: 1px;
	/*font-family:tahoma;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 11px;
	background-color: #FF0000;
	border-width:1px;
	/*font-family:Verdana;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;

}
.accessDenied {
	background-color:#CC0000;
	height:40px;
	font-weight: normal; font-size: 12px; COLOR: #FFFFFF;
    /*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	border:1px dotted #FFFF00;
	padding-left:20px;

}

.headingMenu {
	/*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 14px;
    font-weight: bold;
	padding-left:5px;
    color:#000000;
}

.headingMenuRow {
	background-color: #CC0000;
}

.headingSubMenu {
	font-weight: bold;
	font-size: 12px;
	COLOR: #000000;
	/*FONT-FAMILY: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	padding-left:15px;
}

.padLeft20 {
	padding-left:20px;
}

.specialHighlight {
	background-color:#FFFF00;
	color:#CC0000;
}

.sectionAllocationTable {
	border-top:1px solid #CCCCCC;
}
/* CSS Document FOR MENUS */

/*!!!!!!!!!!! QuickMenu Core CSS [Do Not Modify!] !!!!!!!!!!!!!*/
.qmmc .qmdivider{
 display:block;
 font-size:1px;
 border-width:0px;
 border-style:solid;
 position:relative;z-index:1;
}
.qmmc .qmdividery{
float:left;
width:0px;
}
.qmmc .qmtitle{
display:block;
cursor:default;
white-space:nowrap;
position:relative;
z-index:1;
}
.qmclear{
font-size:1px;
height:0px;
width:0px;
clear:left;
line-height:0px;
display:block;
float:none !important;
}
.qmmc{
position:relative;zoom:1;
z-index:10;
}
.qmmc a, .qmmc li{
float:left;
display:block;
white-space:nowrap;
position:relative;
z-index:1;
}
.qmmc div a, .qmmc ul a, .qmmc ul li{
float:none;
}
.qmsh div a {
float:left;
}
.qmmc div{
visibility:hidden;
position:absolute;
}
.qmmc .qmcbox{
cursor:default;
display:block;
position:relative;
z-index:1;
}
.qmmc .qmcbox a{
display:inline;
}
.qmmc .qmcbox div{
float:none;
position:static;
visibility:inherit;
left:auto;
}
.qmmc li {
z-index:auto;
}
.qmmc ul {
left:-10000px;
position:absolute;
z-index:10;
}
.qmmc, .qmmc ul{
list-style:none;
padding:0px;
margin:0px;
}
.qmmc li a{
float:none
}
.qmmc li:hover>ul{
left:auto;
}
#qm0 ul {
top:100%;
}
#qm0 ul li:hover>ul{
top:0px;left:100%;
}


/****************** CSS FOR MENU COLORS ************************************/

	#qm0 a
	{
		padding:5px 4px 5px 5px;
		color:#ffffff;
		/*font-family:Arial;*/
        font-family:arial,helvetica,verdana,sans-serif;
		font-size:10px;
		text-decoration:none;

	}

	/*"""""""" (SUB) Container""""""""*/
	#qm0 div, #qm0 ul
	{
		padding:10px;
		margin:-2px 0px 0px 0px;
		background-color:transparent;
		border-style:none;
	}


	/*"""""""" (SUB) Items"""""" sub menu font & color""*/
	#qm0 div a, #qm0 ul a
	{
		padding:3px 10px 3px 5px;
		background-color:transparent;
		font-size:11px;
		border-width:0px;
		border-style:none;
        color: #000000;
	}


	/*"""""""" (SUB) Hover State""""""""*/
	#qm0 div a:hover
	{
		background-color:#DCD9D2;
		color:#0F2E68;
	}


	/*"""""""" (SUB) Hover State - (duplicated for pure CSS)""""""""*/
	#qm0 ul li:hover>a
	{
		background-color:#DCD9D2;
		color:#CC0000;
	}


	/*"""""""" (SUB) Active State""""""""*/
	body #qm0 div .qmactive, body #qm0 div .qmactive:hover
	{
		background-color:#DCD9D2;
		color:#0F2E68;
	}


	/*"""""""" Individual Titles""""""""*/
	#qm0 .qmtitle
	{
		cursor:default;
		padding:3px 0px 3px 4px;
		color:#919191;
		/*font-family:arial;*/
        font-family:arial,helvetica,verdana,sans-serif;
		font-size:11px;
		font-weight:bold;
	}


	/*"""""""" Individual Horizontal Dividers""""""""*/
	#qm0 .qmdividerx
	{
		border-top-width:1px;
		margin:4px 10px 14px 20px;
		border-color:#F36358;
	}

	/*"""""""" Box Animation Styles""""""""*/
	#qm0 .qmbox
	{
		border-width:1px;
		border-style:solid;
		border-color:#999999;
	}


  	/*"""""""" (main) Rounded Items Content""""""""*/
	#qm0 .qmritemcontent
	{
		padding:0px 0px 0px 4px;
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

	.rightBorderAccounts {
		border-right:2px #7D7D7D solid;
	}



.selectfieldNoBorder0 {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	background-color: #FBFBFB;
	color:#000;
	border:0px solid;
    width: 186px;
	height: 15px;
}
.selectfieldNoBorder1 {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	background-color: #EDEDED;
	color:#000;
	border:0px solid;
    width: 186px;
	height: 15px;
}

.selectfieldBottomBorder{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 12px;
	border: 0px solid #c6c6c6;
	//height:20px;
    width: 186px;
}


a.accounts:link
{
 color:#00000;   /*#666666*/
 text-decoration:none;
 font-weight:bold;
}
a.accounts:visited
{
 color:#000000;
 text-decoration:none;
 font-weight:bold;
}
a.accounts:hover
{
 color:#1647A0;
 text-decoration:underline;
 font-weight:bold;
}

.marksTransferSuccess {
	border: 1px dotted #FF5706;
	height:30px;
    background-color: #FF5706;
	color:#FFFFDD;
	font-weight:bold;
}

.marksTransferFailure {
	border: 1px dotted #CC0000;
	height:30px;
    background-color: #FFFFDD;
	color:#CC0000;

}

.redLink{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #bb0000 !important;
    text-decoration: none;
}

.textClass
{
    padding: 1px;
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 12px;
}

/*Used For Color Picker*/
.cell_color {
    cursor:pointer;
    width:7px;
    height:6px;
}

.siteMapMainHeading
{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 13px;
	font-weight:bold;
	padding:1px 1px 1px 1px;
	color:#000000;
	text-align:left;
}

.siteMapMainHeading2 {
	/*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: bold;
    color: #000000 !important;
    text-decoration: none;
}


A.siteMapMainHeading {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
	font-size: 13px;
	font-weight:bold;
	padding:1px 1px 1px 1px;
    color: #bb0000;
	text-align:left;
}
A.siteMapMainHeading:hover{
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 13px;
    font-weight: bold;
    color: #bb0000;
    text-decoration: underline;
}

A.redLinkSimple {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: none;
    color: #bb0000 !important;
    text-decoration: none;
}
A.newLinkSimple {

    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: none;
    color: #FD7E00 !important;
    text-decoration: none;
}

A.redLinkSimple:hover {
    /*font-family: Arial, Helvetica, sans-serif;*/
    font-family:arial,helvetica,verdana,sans-serif;
    font-size: 11px;
    font-weight: none;
    color: #bb0000 !important;
    text-decoration: underline;
}

.noBorder
{
    padding: 1px;
    font-family:tahoma;
    font-size: 11px;
    border: 0px solid #CCCCCC;

    border-width:0px;
    font-family:Verdana;
    font-size: 12px;
}

.imgbor{padding:5px; border:1px #CCCCCC solid;}  

fieldset { -moz-border-radius: 8px; border-radius: px; }
<?php
	require_once('calendar-win2k-cold-1.css');
	require_once('bt.css');
?>

/*For Autosuggest*/
.fadeMenuText
{
 font-family:arial,helvetica,verdana,sans-serif;
 font-size:11px;
 color:#777777;
}
#menuLookupContainer
{
    background-color: #ffffff;
    margin-left:5px;
    list-style:none;
    text-align:left;
    border-right: 1px solid #7F9DB9;
    border-left: 1px solid #7F9DB9;
    border-bottom: 1px solid #7F9DB9;
    width:200px;
    color:#708090;
    font-family:arial,helvetica,verdana,sans-serif;
    font-size:12px;
    
    margin-right:2px;
}
#menuLookupContainer ul
{
    margin:0px;
    padding:0px;
}
/* Autosuggest styling ends*/
