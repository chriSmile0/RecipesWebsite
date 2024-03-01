<!--SECURITY NOBot here -->
<?php require 'upload_recipe.php';?>
<?php require 'header.php'; ?>
<?php require '___.php';?>
<!DOCTYPE html>
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

    <title>Les Recettes de Meli</title>
</head>
<body>
    <?php echo no_script();?>

	<h2 id="HA5">Vos Recettes</h2>
	<article id="A2">
		<div class="container_n">
            <?php display_all_recipes_viewers();?>
		</div>
    </article>

	<h2 id="HA4">Ta Recette</h2>
    <form name="submitrecette" id="A4_n" method="POST" enctype="multipart/form-data">
        <fieldset>
			<div id="Nom">
                <div>
                    <label for="name">Nom de la Recette</label>
                    <input name="name" id="name" type="text" placeholder="Nom" 
                           aria-labelledby="name" value="" >
                </div>
                <div class="error">
                    <span id="nameerror"></span>
                    <?php echo $GLOBALS['nameErr'];?>
                </div>
            </div>
			<div>
                <div>
                    <label for="type_prepa">Type de Préparation</label>
                    <select id="type_prepa" name="type_prepa" aria-labelledby="type_prepa">
                        <option value="sucree">Sucrée</option>
                        <option value="salee">Salée</option>
                        <option value="mixte">Mixte</option>
                    </select>
                </div>
                <div class="error">
                    <?php echo $GLOBALS['typeErr'];?>
                </div>
			</div>
            <div>
                <div>
                    <label for="uploaded">Illustration de la Recette</label>
                    <input id="uploaded" name="uploaded" type="file">
                </div>
                <div class="error"><?php echo $GLOBALS['imageErr'];?></div>
            </div>
			<div>
                <div>
                    <label for="ingredients">Ingrédients</label>
                    <textarea id="ingredients" name="ingredients" aria-labelledby="ingredients" 
							placeholder="Ingrédient1,Ingrédient2" value="">
				    </textarea>
                </div>
                <div class="error">
                    <span id="ingredientserror"></span>
                    <?php echo $GLOBALS['ingreErr'];?>
                </div>
            </div>
			<div>
                <div>
                    <label for="prepa">Préparation</label>
                    <textarea 	id="prepa" name="prepa"  aria-labelledby="prepa" 
				    			placeholder="Étape1,Étape2" value="">
			    	</textarea>
                </div>
                <div class="error">
                    <span id="prepaerror"></span>
                    <?php echo $GLOBALS['prepaErr'];?>
                </div>
            </div>
			<div>
                <div>
                    <label for="description">Description</label>
                    <textarea 	id="description" name="description"  aria-labelledby="description" 
				    			placeholder="Description de la recette" value="">
				    </textarea>
                </div>
                <div class="error">
                    <span id="descriptionerror"></span>
                    <?php echo $GLOBALS['descroErr'];?>
                </div>
            </div>
            <div>
                <div>
                    <label for="price">Prix (en €)</label>
                    <input id="price" name="price" type="number" min="2" max="100"
                           placeholder="20">
                </div>  
                <div class="error"><?php echo $GLOBALS['priceErr'];?></div>         
            </div>
            <div>
                <div>
                    <label for="convives">Convives</label>
                    <select id="convives" name="convives" aria-labelledby="convives">
                        <option value="2">2</option>
                        <option value="4">4</option>
                        <option value="6">6</option>
                        <option value="8">8</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="error">
                    <?php echo $GLOBALS['nbrPeopleErr'];?>
                </div>
            </div>
            <div>
                <div>
                    <label for="author">Auteur</label>
                    <input id="author" name="author" type="text">
                </div>
                <div class="error">
                    <span id="authorerror"></span>
                    <?php echo $GLOBALS['authorErr'];?>
                </div>
            </div>
			<button id="btnsubmit" name="submitrecette" value="EnvoyerRecette" >Enregistrer</button>
		</fieldset>
	</form>
    <div id="content-progressBar">
        <div id="text-progressBar"></div>
        <div id="progressBar"></div>
    </div>
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
	<script src="my_js.js">
        (document.getElementById('A4_n')).onsubmit = function () {return veriform(document.getElementById('A4_n'))};
    </script>
    <?php echo load_bePatient(); ?>
</body>
</html>





