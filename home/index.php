<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="chriSmile0">
    <meta name="description" content="Mimi Recipes">
    <meta charset="UTF-8">
    <link href="style_home.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" 
          rel="stylesheet">
	<link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Les Recettes de Meli</title>
</head>
<body>
    <noscript id="js-check-container">
        <meta http-equiv="refresh" content="0; url=LOVE_JS.html" />
        <!--<span id="js-check-text">Please activate Javascript!</span>-->
    </noscript>
    <?php include '../___.php';?>
    <div id="title">
        <h1>Les recettes de Meli</h1>
    </div>

    <div id="content">
        <div id="mimi" onclick="document.location.href='../index_meli.php'">
            <h2>Mes Recettes</h2>
        </div>
        <div id="viewers" onclick="document.location.href='../index_viewers.php'">
            <h2>Vos Recettes</h2>
        </div>
    </div>
    <script src="home_js.js"></script>
</body>
</html>