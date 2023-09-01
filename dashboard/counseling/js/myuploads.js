String.prototype.isMatch = function(s){
   return this.match(s)!==null
}

function deleteRow(rowid){   
	var row = document.getElementById(rowid);
	var table = row.parentNode;
	while ( table && table.tagName != 'TABLE' )
		table = table.parentNode;
	if ( !table )
		return;
	table.deleteRow(row.rowIndex);
}

function fireentersearch(event){
	if (event.keyCode == 13) {
		searchAjax();
	}
}

document.onkeydown=function(e){
	if(e.which == 27) {
		hideComboBox();
		hideIframe();
		return false;
	}
}

function searchAjax(){
  var query = document.getElementById('queryInput').value;
 
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
	if(this.readyState == 4 && this.status == 200) {
		document.getElementById('dataTable2').innerHTML = this.responseText;
	}
  }
  xmlhttp.open("GET","ajax.php?data=myuploads&q="+query, true);
  xmlhttp.send();
}



function refreshTable(){
  xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
	if(this.readyState == 4 && this.status == 200) {
		document.getElementById('dataTable2').innerHTML = this.responseText;
	}
  }
  xmlhttp.open("GET","ajax.php?data=myuploads&q=", true);
  xmlhttp.send();
}

function hideComboBox(){
	document.getElementsByClassName('notific')[0].style.display = 'none';
	deletename.innerHTML = "";
	confirmdelete.setAttribute('onclick','');
	notificationComboBoxText.innerHTML = "";
}

function DeleteContent(id, name){
	document.getElementById('deletename').innerHTML = name;
	document.getElementById('confirmdelete').setAttribute('onclick','confirmDelete("'+id+'")');
	document.getElementsByClassName('notific')[0].style.display = "inherit";
}

function confirmDelete(id){
	notificationComboBoxText.innerHTML = "Processing...";
	setTimeout(function(){
		notificationComboBoxText.innerHTML="Taking Too Long, Server or Network Issue.";
	}, 4000);
	xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function() {
	if (this.readyState == 4 && this.status == 200) {
		//var ReturnJson = JSON.parse(this.responseText);
		document.getElementsByClassName('notific')[0].style.display = "none";
		//var trid = ;
		deleteRow("datacont"+id);
		notificationComboBoxText.innerHTML = "";
	}
	}
	xmlhttp.open("GET","ajax.php?data=deleteContent&id="+id,true);
	xmlhttp.send();	
}

