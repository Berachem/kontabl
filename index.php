<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontabl</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.css" integrity="sha256-lYnQDpcf+FFMWvFyNlfYM5Zis7/ENdFurMo6UK58k4E=" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/app.css?v=<?= md5_file('./assets/app.css') ?>">
</head>

<body x-data x-init="$router.config({ mode: 'hash' })">

    <template x-route="/" template="/pages/search.html"></template>

    <template x-route="/login" template="/pages/login.html"></template>

    <template x-route="/results" template="/pages/results.html"></template>

    <template x-route="/graphs" template="/pages/graphs.html"></template>

    <script src="https://cdn.jsdelivr.net/npm/@shaun/alpinejs-router@1.2.1/dist/cdn.min.js" integrity="sha256-Wtqusj1IzuvR9tsDJTMPNZJKGOGTq4i3Fg75WMdupS8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.js" integrity="sha256-xzR/l8vcDeDcQ5fylcz9H0gl3JE31ho4nddII0gSMYw=" crossorigin="anonymous"></script>
    <script src="/assets/app.js?v=<?= md5_file('./assets/app.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.4/dist/cdn.min.js" integrity="sha256-6GXzaY8Bwd7jFZRPj4zcj0SZnnb37LkkvkthlOdYSwg=" crossorigin="anonymous"></script>
</body>

</html>