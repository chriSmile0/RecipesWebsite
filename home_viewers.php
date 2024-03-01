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

    <title>Les Recettes de Mimi</title>
</head>
<body>
    <?php echo no_script();?>

    <div id="NoBot">
        <h4>Robot cuiseur?</h4>
        <div id="botQuestion">
            <span id="question_php" class="q_elems"><?php echo bot_question("");?></span>
            <span id="question_error" class="q_elems"></span>
            <select id="select-bot" class="q_elems" value="">
                <option value="oui">Oui</option>
                <option value="non">Non</option>
            </select>
            <button id="NoBotBtn" class="q_elems">Vérifier</button>
        </div>
        <span id="select-bot-hide"></span>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="no_bot.js"></script>
</body>
</html>