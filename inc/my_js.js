window.onscroll = function() {AfficherRemonter()};

var regexNomPrenom =  /^[a-zA-Z- ]{1,30}$/;
var regexMessage = /^[a-zA-Z0-9 ,áàçéèÁÀÇÉÈÍÌÚÙ.-]{1,}$/;


let nomErr = "Espace et tiret autorisés ainsi que les majuscules (30 caractères max)";
let messageErr = "Espace et tiret autorisés ainsi que les majuscules";

/**
 * @brief Write an error in the html render 
 * @param { champ }     a form field 
 * @param { error }     true if display error false for hide error
 * @param { textError } The text to write in the champ.id+error field
 * @return / 
*/
function writeError(champ,error,textError) {
  if(error) {
    var elemn = document.getElementById(champ.id+'error');
    elemn.style.color = "red";
    elemn.innerText = textError;
  }
  else {
    (document.getElementById(champ.id+'error')).innerText = "";
  }
}

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
 * @brief Show or Hide the 'return to top' button  
 * @param / 
 * @return / 
*/
function AfficherRemonter() {
  if(document.body.scrollTop > 0 || document.documentElement.scrollTop > 0) {
    document.getElementById('top').style.display = "flex";
    document.getElementById('top_a').style.display = "block";
  } 
  else {
    document.getElementById('top').style.display = "none";
    document.getElementById('top_a').style.display = "none";
  }
}

/**
 * @brief Check of the content of champNom targets
 * @param { champNom }  input field to check
 * @return / 
*/
function verifNom(champNom) {
  var rtn = regexNomPrenom.test(champNom.value);
  writeError(champNom,!rtn,messageErr);
  return rtn;
}

/**
 * @brief Check of the content of champMessage target
 * @param { champMessage }  textarea field to check
 * @return / 
*/
function verifMessage(champMessage) {
  var rtn = regexMessage.test(champMessage.value);
  writeError(champMessage,!rtn,messageErr);
  return rtn;
}

/**
 * @brief Check of the content of the form 
 *        If all it's correct (check to the type not change by a visitor with devtools)
 * @param { form }  the form element
 * @return bool true if all tests are passed, false else
*/
function veriform(form) {
  var vN    = (verifNom(form.name));
  var vT    = ((form.type_prepa.value=="sucree")||(form.type_prepa.value=="salee")||(form.type_prepa.value=="mixte"));
  var vImg  = (form.uploaded.type=="file");
  var vIng  = (verifMessage(form.ingredients));
  var vPre  = (verifMessage(form.prepa));
  var vDes  = (verifMessage(form.description));
  var vPri  = (form.price.type=="number");
  var vPeo  = ((form.convives.value > 1) && (form.convives.value < 11));
  var vAut  = (verifNom(form.author));

  if(vN && vT && vImg && vIng && vPre && vDes && vPri && vPeo && vAut) 
    return true;

  return false;
}

/**
 * @brief Show the content of the vierwers page
 * @param /
 * @return /
*/
function showForm() {
  (document.getElementsByClassName('container_n')[0]).style.display = "block";
  (document.getElementById('A4_n')).style.display = "block";
  (document.getElementById('content-progressBar')).style.display = "none";
}

/**
 * @brief bePatient the viewer to check if it"s not a bot in the second time
 *        after the no_bot.js check
 * @param /
 * @return /
*/
function bePatientv2() { // progressBar
  let cpt = 0;
  let width = 0;
  const id = setInterval(update,400);
  (document.getElementById('content-progressBar')).style.display = "block";
  function update() {
    if(cpt == 33) {
      clearInterval(id);
      showForm();
    }
    else {
      cpt++;
      width = width+5.5;
      (document.getElementById('progressBar')).style.width = width+"px";
      (document.getElementById('text-progressBar')).innerText = ((cpt*3.04).toFixed(0))+"%";
    }
  };
}
