window.onscroll = function() {AfficherRemonter()};

function AfficherRemonter() {
    if (document.body.scrollTop > 0 || document.documentElement.scrollTop > 0) {
        document.getElementById('top').style.display = "flex";
        document.getElementById('top_a').style.display = "block";
    } 
    else {
    document.getElementById('top').style.display = "none";
    document.getElementById('top_a').style.display = "none";
  }
}