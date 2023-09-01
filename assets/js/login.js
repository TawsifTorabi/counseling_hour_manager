var button = document.getElementById('buttonSbmt');
var nameinput = document.getElementById('name');
var pass = document.getElementById('pass');

button.setAttribute("onmouseover","flyAway()");
//button.setAttribute("disabled","");

var InputArr = [nameinput, pass,];

Validate();

for(var i=0; i < InputArr.length; i++){
	InputArr[i].addEventListener("keyup", function(){
		Validate();
	});
	InputArr[i].addEventListener("change", function(){
		Validate();
	});
}

function resetButtonStyle(){
	button.style.removeProperty("margin-right");
	button.style.removeProperty("margin-left");
	//button.removeAttribute("disabled","");
}

function flyAway(){
	if(button.style.marginRight == false){   
		button.style.removeProperty("margin-left");
		button.style.marginRight = "-"+((Math.random()*200)+70)+"px";
	}else{
		button.style.removeProperty("margin-right");
		button.style.marginLeft = "-"+((Math.random()*200)+80)+"px";
	}
}

function formEnabled(msg){
	notificDiv.style.display = "inline-block";
	notificDiv.style.background = "limegreen";
	notification.innerHTML = msg;
	button.removeAttribute("onmouseover");
	resetButtonStyle();
}

function formDisabled(msg){
	notificDiv.style.display = "inline-block";
	notification.innerHTML = msg;
	notificDiv.style.background = "crimson";
	button.setAttribute("onmouseover","flyAway()");
	//button.setAttribute("disabled","");
}

document.onload= function(){
	console.log('Loaded!');
	Validate();
};


function Validate(){
	let validated = 0;
	
	if(nameinput.value.length > 0){
		//First Check if full name is entered
		if(nameinput.value.length < 2){
			formDisabled("Input Username");
			//nameinput.classList.add("inputAreaAlert");
		}else{
			//nameinput.classList.remove("inputAreaAlert");
			notification.parentElement.style.display= 'none';
			validated++;
		}
	}


	if(pass.value.length > 0){
		if(pass.value.length > 3){
			//If Password Value is longer than 3
			//pass.classList.remove("inputAreaAlert");
			notification.parentElement.style.display= 'none';
			validated++;
		}

		if(pass.value.length <= 4){
			formDisabled("Password Should be more than 4");
			//pass.classList.add("inputAreaAlert");
		}
	}
	
	console.log(validated);

	if(validated > 1){
		//All Two Inputs Validated
		formEnabled("Green Light! Green Light!");
	}
}
