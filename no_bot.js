/**
 * @brief Write an error in the html render 
 * @param { id }        id of a random element in a page  
 * @param { error }     true if display error false for hide error
 * @param { textError } The text to write in the champ.id+error field
 * @return / 
*/
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
  
/**
 * @brief Delay the bot action (faster than a precise delay @see fasterBot
 * @param /
 * @return /
*/
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
  
/**
 * @brief Display the button to answer to the question with a delay 
 * @param /
 * @return /
*/
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

/**
 * @brief Delay the bot action (faster than a precise delay @see fasterBot
 * @param /
 * @return /
 * @version 1.0 -> check to update (maybe fastest not necessary (fasterBot Maybe))
*/
function checkBot() { // à refaire du côté PHP pour la gestion du captcha maison 
	var val_bot = (document.getElementById('select-bot')).value;
	(document.getElementById('select-bot-hide')).innerText = val_bot;
	fastestClick();
}

/**
 * @brief A POST request for ban a user who not respond correctly in any question 
 * @param /
 * @return /
*/
function deleteSessionv2() {
	document.getElementById('botQuestion').style.backgroundColor = "green";
	let xmlhttp=new XMLHttpRequest()
	xmlhttp.onreadystatechange = function() {
	  if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
		const reponse = JSON.parse(this.responseText);
		console.log(reponse);
	  };
	}
	xmlhttp.open("POST",",,,.php");
	xmlhttp.send("session=ban");
}

/**
 * @brief Check the response of the question for bot protection
 * @param e	the event 
 * @return /
*/
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
	  url = ",,,.php";
	  xmlhttp.open("POST",url,true);
	  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xmlhttp.send("q="+vQ+"&a="+elem);
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
	xmlhttp.open("POST",",,,.php");
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("session=connected");
}

(document.getElementById("NoBotBtn")).addEventListener("click", verifBotResponse, false);