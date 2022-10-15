<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontabl</title>

    <link rel="stylesheet" href="/assets/app.css?v=<?= md5_file('./assets/app.css') ?>">
    <!-- ajouter un hash à la fin du fichier pour prévenir le cache du naigateur -->
</head>

<body>

    <!-- 
    HTML principal
    
    Toutes les vues dans le même fichier, routing geré par le JS, voir https://www.npmjs.com/package/pinecone-router (avec des #)
    Interface dynamique avec alpinejs
    -->

    <script src="/assets/app.js?v=<?= md5_file('./assets/app.js') ?>"></script>
</body>

</html>