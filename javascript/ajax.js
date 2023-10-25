// JavaScript Document

var request = null;
try {
  request = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    request = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      request = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      request = null;
    }
  }
}

if (request == null)
  alert("Error creating request object!");
  
  
function cria_request()
{
	
	var req = null;
	try {
  req = new XMLHttpRequest();
} catch (trymicrosoft) {
  try {
    req = new ActiveXObject("Msxml2.XMLHTTP");
  } catch (othermicrosoft) {
    try {
      req = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (failed) {
      req = null;
    }
  }
}

if (req == null)
  alert("Error creating request object!");
  
  return req;
}