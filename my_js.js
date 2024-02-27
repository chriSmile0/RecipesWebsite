window.onscroll = function() {AfficherRemonter()};

var regexNomPrenom =  /^[a-zA-Z- ]{1,30}$/;
var regexMessage = /^[a-zA-Z0-9 ,áàçéèÁÀÇÉÈÍÌÚÙ.-]{1,}$/;


let nomErr = "Espace et tiret autorisés ainsi que les majuscules (30 caractères max)";
let messageErr = "Espace et tiret autorisés ainsi que les majuscules";


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


function verifNom(champNom) {
  var rtn = regexNomPrenom.test(champNom.value);
  writeError(champNom,!rtn,messageErr);
  return rtn;
}

function verifMessage(champMessage) {
  var rtn = regexMessage.test(champMessage.value);
  writeError(champMessage,!rtn,messageErr);
  return rtn;
}


function veriform(form) {
  var vN    = (verifNom(form.name));
  var vT    = ((form.type_prepa.value=="sucree")||(form.type_prepa.value=="salee")||(form.type_prepa.value=="mixte"));
  var vImg  = (form.uploaded.type=="file");
  var vIng  = (verifMessage(form.ingredients));
  var vPre  = (verifMessage(form.prepa));
  var vDes  = (verifMessage(form.description));
  var vPri  = (form.price.type=="number");
  var vPeo  = ((form.convives.value > 1) || (form.convives.value < 11));
  var vAut  = (verifNom(form.author));

  if(vN && vT && vImg && vIng && vPre && vDes && vPri && vPeo && vAut) 
    return true;
  
  console.log('vN'+vN+","+'vT'+vT+","+'vImg'+vImg+","+'vIng'+vIng+","+'vPre'+
              vPre+","+'vDes'+vDes+","+'vPri'+vPri+","+'vPeo'+vPeo+","+'vAut'+vAut);
  return false;
}

(document.getElementById('A4')).onsubmit = function () {return veriform(document.getElementById('A4'))};

function bePatient() {
  let cpt = 0;
  const id = setInterval(update,1000);
  function update() {
    if(cpt == 15) {
      clearInterval(id);
      showForm();
    }
    else {
      cpt++;
      (document.getElementById('hidingFormText')).innerText = cpt;
    }
  };
}

function bePatientv2() { // progressBar
  let cpt = 0;
  let width = 0;
  const id = setInterval(update,400);
  function update() {
    if(cpt == 33) {
      clearInterval(id);
      //showForm();
      (document.getElementById('A4')).style.display = "block";
      (document.getElementById('content-progressBar')).style.display = "none";
    }
    else {
      cpt++;
      width = width+5.5;
      (document.getElementById('progressBar')).style.width = width+"px";
      (document.getElementById('text-progressBar')).innerText = ((cpt*3.04).toFixed(0))+"%";
    }
  };
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
window.onload = bePatientv2();


/*
//window.onload = bePatient();
const wait = setTimeout(showForm,30000);
function showForm() {
  (document.getElementById('A4')).style.display = "block";
}*/
function checkBot() { // à refaire du côté PHP pour la gestion du captcha maison 
  var val_bot = (document.getElementById('select-bot')).value;
  (document.getElementById('select-bot-hide')).innerText = val_bot;
  fastestClick();
}

(document.getElementById('destroy-session')).addEventListener("click", deleteSession, false);

function verifBotResponse(e) {
  console.log("check response \n");
  var elem = (document.getElementById('select-bot')).value;
  var vAnswer = (elem == "oui") || (elem == "non");
  var vQ = document.getElementById('question_php').innerText;
  vQ = vQ.replaceAll("'","_");
  console.log(vQ);
  /*vQ = vQ.replace(" ","+");*/
  console.log("f vQ : "+vQ);
  if(vAnswer == true) {
    console.log("OK \n");
    /*e.preventDefault();
    e.stopPropagation();*/
    let xmlhttp=new XMLHttpRequest()
    xmlhttp.onreadystatechange = function() {
      if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
        const reponse = JSON.parse(this.responseText);
        console.log(reponse);
        if(reponse[0] == true) {
          console.log("GOOD");
        }
        else {
          if(reponse[1] <= 0) {
            console.log("DEBUGGEUR AGAIN ??");
            // NOT GODD IS IMPORTANT TO BAN IP ADDRESS NOT DESTROY FOR RELOAD
            // IT'S JUST A TEST 
           
            writeErrorv2('question_error',true,"Banni !");
            //(document.getElementById('destroy-session')).click();
            deleteSessionv2();
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
  /*var val_bot = (document.getElementById('select-bot')).value;
  (document.getElementById('select-bot-hide')).innerText = val_bot;*/
}

document.getElementById('select-bot').onchange = function() {checkBot()};
/*(document.getElementById('select-bot-hide')).innerText = "";*/
//(document.getElementById('NoBotBtn')).onclick = function() {verifBotResponse()};


function deleteSession(e) {
  document.getElementById('botQuestion').style.backgroundColor = "blue";
  e.preventDefault();
  e.stopPropagation();
  let xmlhttp=new XMLHttpRequest()
  xmlhttp.onreadystatechange = function() {
    if((this.readyState === XMLHttpRequest.DONE) && (this.status === 200)) {
      const reponse = JSON.parse(this.responseText);
      console.log(reponse);
    };
  }
  xmlhttp.open("GET",",,,.php?session=destroy");
  xmlhttp.send();
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
  window.location.assign("home/index.php");
  xmlhttp.open("GET",",,,.php?session=destroy");
  xmlhttp.send();
}

(document.getElementById("NoBotBtn")).addEventListener("click", verifBotResponse, false);

