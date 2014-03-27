<!DOCTYPE html>
<html lang="en" data-ng-app="fciApp">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Future CI</title>

    <link href="/assets/css/f500ci<?= $minify ? '.min' : '' ?>.css" rel="stylesheet" type="text/css">

    <!--[if lt IE 9]>
    <link href="/bower_components/html5shiv/dist/html5shiv.js" rel="stylesheet" type="text/css">
    <link href="/bower_components/respond/dest/respond.min.js" rel="stylesheet" type="text/css">
    <![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Future <em>CI</em></a>
        </div>

        <ul class="nav navbar-nav" data-fci-navbar>
            <li><a href="/">Home</a></li>
            <li><a href="/build" data-nav-pattern="/build.*">Builds</a></li>
        </ul>
    </div>
</div>

<div data-ng-view></div>

<div class="container">
    <hr>

    <footer>
        <p>&copy; <?= $copyrightYears ?> <a href="http://future500.nl/" target="_blank">Future500 B.V.</a></p>
    </footer>
</div>

<script src="/bower_components/jquery/dist/jquery<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
<script src="/bower_components/angular/angular<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
<script src="/bower_components/angular-route/angular-route<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
<script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
<script src="/assets/js/f500ci<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>

</body>
</html>
