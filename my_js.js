window.onscroll = function() {AfficherRemonter()};
if(document.getElementById('content-progressBar') != null)
  window.addEventListener('load',bePatientv2(),false);

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

//(document.getElementById('A4_n')).onsubmit = function () {return veriform(document.getElementById('A4_n'))};
//(document.getElementById('A4')).onsubmit = function () {return veriform(document.getElementById('A4'))};


//window.onload = bePatientv2();

function bePatientv2() { // progressBar
  let cpt = 0;
  let width = 0;
  const id = setInterval(update,400);
  (document.getElementById('content-progressBar')).style.display = "block";
  function update() {
    if(cpt == 33) {
      clearInterval(id);
      (document.getElementsByClassName('container_n')[0]).style.display = "block";
      (document.getElementById('A4_n')).style.display = "block";
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
