<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontabl</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.css" integrity="sha256-lYnQDpcf+FFMWvFyNlfYM5Zis7/ENdFurMo6UK58k4E=" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/app.css?v=<?= md5_file('./assets/app.css') ?>">

    <style>
        body {
            --primary: #006d3a;
            --on-primary: #ffffff;
            --primary-container: #99f6b4;
            --on-primary-container: #00210c;
            --secondary: #4f6353;
            --on-secondary: #ffffff;
            --secondary-container: #d1e8d3;
            --on-secondary-container: #0c1f12;
            --tertiary: #3a646f;
            --on-tertiary: #ffffff;
            --tertiary-container: #beeaf6;
            --on-tertiary-container: #001f26;
            --error: #ba1b1b;
            --error-container: #ffdad4;
            --on-error: #ffffff;
            --on-error-container: #410001;
            --background: #fbfdf7;
            --on-background: #1a1c1a;
            --surface: #fbfdf7;
            --on-surface: #1a1c1a;
            --surface-variant: #dde5db;
            --on-surface-variant: #414941;
            --outline: #707970;
            --inverse-on-surface: #f0f1ec;
            --inverse-surface: #2e312e;
            --inverse-primary: #7dda9a;
            --shadow: #000000;
        }
    </style>
</head>

<body x-data x-init="$router.config({ mode: 'hash' })">

    <template x-route="/" template="/pages/search.html"></template>

    <template x-route="/login" template="/pages/login.html"></template>

    <template x-route="/results" template="/pages/results.html"></template>

    <template x-route="/graphs" template="/pages/graphs.html"></template>

    <template x-route="/add-merchant" template="/pages/add_merchant.html"></template>

    <template x-route="/accept-merchant" template="/pages/accept_merchant.html"></template>

    <template x-route="/delete-merchant" template="/pages/delete_merchant.html"></template>

    <script src="https://cdn.jsdelivr.net/npm/@shaun/alpinejs-router@1.2.1/dist/cdn.min.js" integrity="sha256-Wtqusj1IzuvR9tsDJTMPNZJKGOGTq4i3Fg75WMdupS8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/beercss@2.2.11/dist/cdn/beer.min.js" integrity="sha256-xzR/l8vcDeDcQ5fylcz9H0gl3JE31ho4nddII0gSMYw=" crossorigin="anonymous"></script>
    <script src="/assets/app.js?v=<?= md5_file('./assets/app.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.4/dist/cdn.min.js" integrity="sha256-6GXzaY8Bwd7jFZRPj4zcj0SZnnb37LkkvkthlOdYSwg=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/highcharts@10.3.1/highcharts.js" integrity="sha256-TNuOEzzC/7kcr+gSjQriz55hZqrcWq1d1rALfEXj51w=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/highcharts@10.3.1/modules/exporting.js" integrity="sha256-6cXCKCEbp8pKmzW/yTM+2BO70rhH1MYnqBksQYraMN4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/highcharts@10.3.1/modules/accessibility.js" integrity="sha256-9aA/NBPNmcqEKb6Wn5HJdKz4wOAYpuryXRbsNx87NtI=" crossorigin="anonymous"></script>
</body>

</html>