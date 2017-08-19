/*
 * Original Script from NETTUTS.com [by James Padolsey]
 * @requires jQuery($), jQuery UI & sortable/draggable UI modules
 * This xhtpr.js file from kandar.info writen by kandar (iskandarsoesman@gmail.com) @ 5 March 2009
 */
 
var xmlHttp = createXmlHttpRequest();
var obj = '';

function createXmlHttpRequest() {
  var xmlHttp = false;
  if (window.ActiveXObject) {
    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
  } else {
    xmlHttp = new XMLHttpRequest();
  }
  if (!xmlHttp) {
    alert("Ops sorry We found some error!!");
  }
  return xmlHttp;
}


function getData(source,values) {
  // Process for complited object (code = 4)
  // Or not initial yet (code = 0)
  if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0) {
	
    xmlHttp.open("POST", source, true);
    
	xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlHttp.setRequestHeader("Content-length", values.length);
	xmlHttp.setRequestHeader("Connection", "close");

    xmlHttp.send(values);
  } else {
    setTimeout('getData(source)', 100000);
  }
}
//end new