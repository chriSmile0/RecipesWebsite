function writeErrorv2(id,error,textError) {
	if(error) {
	  var elemn = document.getElementById(id);
	  elemn.style.color = "red";
	  elemn.innerText = textError;
	}
	else {
	  (document.getElementById(id)).innerText = "";
	}
}
  
function fastestClick() {
	const button = document.getElementById('NoBotBtn');
	let elementClicked = false;
	button.addEventListener('click', function handleClick() {
	  if (elementClicked) 
		return;
	  elementClicked = true;
	});
	if(elementClicked === true)
	  console.log("YOUR VERY FASTER!! ");
	else 
	  console.log("Human ? (maybe yes (answer check now!!");
}
  
function fasterBot() {
	(document.getElementById('NoBotBtn')).style.display = "none";
	const wait = setTimeout(viewButton,400);
	var val_select = document.getElementById('select-bot-hide').value;
	console.log(val_select);
	if(val_select !== undefined)
		console.log("YOUR VERY FASTER!! ");
	else 
		console.log("Human ? (let me check again\n");
	function viewButton() {
		(document.getElementById('NoBotBtn')).style.display = "block";
		fastestClick();
	}
}
  
window.onload = fasterBot();
function checkBot() { // à refaire du côté PHP pour la gestion du captcha maison 
	var val_bot = (document.getElementById('select-bot')).value;
	(document.getElementById('select-bot-hide')).innerText = val_bot;
	fastestClick();
}

function deleteSessionv2() {
	document.getElementById('botQuestion').style.backgroundColor = "green";
	let xmlhttp=new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	  if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
		const reponse = JSON.parse(this.responseText);
		console.log(reponse);
	  };
	}
	xmlhttp.open("GET",",,,.php?session=ban");
	xmlhttp.send();
}

function verifBotResponse(e) {
	var elem = (document.getElementById('select-bot')).value;
	var vAnswer = (elem == "oui") || (elem == "non");
	var vQ = document.getElementById('question_php').innerText;
	vQ = vQ.replaceAll("'","_");
	if(vAnswer == true) {
	  e.preventDefault();
	  e.stopPropagation();
	  let xmlhttp=new XMLHttpRequest()
	  xmlhttp.onreadystatechange = function() {
		if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
		  const reponse = JSON.parse(this.responseText);
		  console.log(reponse);
		  if(reponse[0] == true) {
			window.location.assign("redirect.php");
			//welcomeSession();
		  }
		  else {
			if(reponse[1] <= 0) {
				deleteSessionv2();
				writeErrorv2('question_error',true,"Banni pendant 24h !!");
			}
			else {
				writeErrorv2('question_error',true,"Essais restants =  "+reponse[1]);
				document.getElementById('question_php').innerHTML = reponse[2];
			}
			
		  }
		};
	  }
	  xmlhttp.open("GET",",,,.php?q="+vQ+"&a="+elem,true);
	  xmlhttp.send();
	}
	else 
	  console.log("PLEASE NOT MODIFY ELEMENT");
}
  
document.getElementById('select-bot').onchange = function() {checkBot()};
  

function welcomeSession() {
	document.getElementById('botQuestion').style.backgroundColor = "lightblue";
	let xmlhttp=new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	  if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
		const reponse = JSON.parse(this.responseText);
		console.log(reponse);
	  };
	}
	xmlhttp.open("GET",",,,.php?session=connected");
	xmlhttp.send();
}



  
(document.getElementById("NoBotBtn")).addEventListener("click", verifBotResponse, false);