
String.prototype.isMatch = function(s){
   return this.match(s)!==null
}

function Cancel(){
	if(confirm('Are You Sure?') == true){parent.hideIframe();}
}



function PostNow(){
	let textData = tinyMCE.activeEditor.getContent();
	console.log(textData);
	
	if(textData.split(' ').length >= 20){
		document.getElementById('hiddenValue').value = textData;
		console.log(document.getElementById('hiddenValue').value);
		document.tokenCreate.submit();
	}else{
		if(textData.length <= 1){
			document.getElementById('notific').innerHTML = "The Post field is Empty.";
		}else{	
			document.getElementById('notific').innerHTML = "Minimum 20 Words is Required.";
		}
	}
}


function DoPostText(){
	let textData = tinyMCE.activeEditor.getContent();
	const url = 'ajax.php?data=tokenCreate';
	let data2 = {
		"problem" : textData
		};
		
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.open("POST", url, true);
	xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	
	xmlhttp.onreadystatechange=function() {
		if (this.readyState == 4 && this.status == 200){
			console.log(this.responseText);
			let RetJson = JSON.parse(this.responseText);
			if(RetJson.posted.isMatch('true')){
				console.log(RetJson.posted);
				console.log(this.responseText);
				console.log(JSON.stringify(this.responseText));
				parent.location.reload();
			}
		} else{
			return 0;
		}
	}
	let sendJson = JSON.stringify(data2);
	console.log(sendJson);
	console.log(groupid);
	xmlhttp.send('submit&post_data='+sendJson);
}




	
	