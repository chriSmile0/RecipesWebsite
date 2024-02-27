<!DOCTYPE html>
<?php require 'db_usage.php'; ?>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="chriSmile0">
    <meta name="description" content="Mimi Recipes">
    <meta charset="UTF-8">
    <link href="style.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" 
          rel="stylesheet">
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Les Recettes de Mimi</title>
</head>

<body>
    <noscript id="js-check-container">
        <meta http-equiv="refresh" content="0; url=LOVE_JS.html" />
        <!--<span id="js-check-text">Please activate Javascript!</span>-->
    </noscript>
    <?php include '___.php';?>
	<header id="H">
        <h2>Les Recettes de Mimi</h2>
	</header>

	<section>
        <h2 id="HA1">Favoris</h2>
        <article id="A1">
            <div class="container">
				<aside>
					<h4>Le poulet Yassa</h4>
					<img src="imgs/choucroute.jpg">
					<p>
						Ce plat sénégalais regorge de richesse et d'arômes
						car on y retrouvent du citron, de la moutarde, des oignons et des olives
						des ingrédients que l'on peut retrouver au sud de la France 
						afin d'avoir un poulet yassa très frais et local.
					</p>
            	</aside>
			</div>
        </article>
        <h2 id="HA2">Découvertes</h2>
        <article id="A2">
			<div class="container">
                <?php display_all_recipes();?>
			</div>
        </article>
		<h2 id="HA3">Mes Ingrédients</h2>
		<article id="A3">
            <div class="container">
                <?php display_all_ingredients(); ?>
            </div>
        </article>
    </section>

	<div id="top">
        <a id="top_a" href="#H">
            <i id="top_a_i" class="material-icons">expand_less</i>
        </a>
    </div>
    <div>
        <span id="hidingFormText">C</span>
    </div>

	<footer>
        <h3 style="padding-bottom:0px;margin-bottom:0px;">Mes Réseaux Sociaux</h3>
        <br>
        <a href="http://www.facebook.com" target="_blank" title="Facebook">
        <!--_blank sert a ouvrir la page facebook sur un autre onglet-->
            <i class="fa fa-facebook-square" style="font-size:40px;"></i>
        </a>
        <a href="http://www.twitter.com" target="_blank" title="Twitter">
        <!--_blank sert a ouvrir la page twitter sur un autre onglet-->
            <i class="fa fa-twitter-square" style="font-size:40px;"></i>
        </a>
        <a href="http://www.instagram.com" target="_blank" title="Instagram">
        <!--_blank sert a ouvrir la page instagram sur un autre onglet-->
            <i class="fa fa-instagram" style="font-size:40px"></i>
        </a>
    </footer>
	<script src="my_js.js"></script>
</body>
</html>