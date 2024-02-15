<!DOCTYPE html>
<?php require 'db_usage.php' ?>
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
	<header id="H">
        <h2>Les Recettes de Mimi</h2>
        <nav>
			<a href="#HA1">Favoris</a>
			<a href="#HA2">Découvertes</a>
			<a href="#HA3">Mes Ingrédients</a>
			<a href="#HA4">Ta Recette</a>
        </nav>
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
				<!--<aside>
					<h4>La Choucroute</h4>
					<img src="imgs/choucroute.jpg">
					<p>
						Un plat typique de la région qui m'a acceuillit pendant
						études qui peut se déguster en toute saison car il se décline
						en plusieurs version.
					</p>	
            	</aside>-->
                <?php display_all_recipes();?>
			</div>
        </article>
		<h2 id="HA3">Mes Ingrédients</h2>
		<article id="A3">
            <div class="container">
                <!--<aside>
                    <h4>Manioc</h4>
                    <img src="imgs/manioc.jpg">
					<p>
						...
                    </p>
                </aside>
                <aside>
                    <h4>Banane Plantain</h4>
					<img src="imgs/banane_plantain.jpg">
					<p>
						...
                    </p>
                </aside>
                <aside>
                    <h4>Patate Douce</h4>
					<img src="imgs/patate_douce.jpg">
                    <p>
						...
                    </p>
                </aside>-->
                <?php display_all_ingredients(); ?>
            </div>
        </article>
    </section>

	<h2 id="HA4">Ta Recette</h2>
    <form name="contacts" id="A4" method="POST" onsubmit="return verifform(this)">
        <fieldset>
			<div id="Nom">
                <label for="nom">Nom de la Recette</label>
                <input id="nom" type="text" placeholder="Nom" aria-labelledby="nom">
            </div>
			<div>
				<label for="type_prepa">Type de Préparation</label>
				<select name="type_prepa" aria-labelledby="type_prepa">
					<option value="sucree">Sucrée</option>
					<option value="salee">Salée</option>
					<option value="mixte">Mixte</option>
				</select>
			</div>
			<div>
                <label for="ingredients">Ingrédients</label>
                <textarea 	name="message"  aria-labelledby="message" 
							placeholder="Ingrédient1,Ingrédient2">
				</textarea>
            </div>
			<div>
                <label for="prepa">Préparation</label>
                <textarea 	name="prepa"  aria-labelledby="prepa" 
							placeholder="Étape1,Étape2">
				</textarea>
            </div>
			<div>
                <label for="description">Description</label>
                <textarea 	name="description"  aria-labelledby="description" 
							placeholder="Description de la recette">
				</textarea>
            </div>
			<button name="submitrecette"
                    type="submit" value="EnvoyerRecette">Enregistrer</button>
		</fieldset>
	</form>

	<div id="top">
        <a id="top_a" href="#H">
            <i id="top_a_i" class="material-icons">expand_less</i>
        </a>
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