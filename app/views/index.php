<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>Future CI</title>

        <link href="<?= $baseUrl ?>/assets/css/f500ci<?= $minify ? '.min' : '' ?>.css" rel="stylesheet" type="text/css" />

        <!--[if lt IE 9]>
        <link href="<?= $baseUrl ?>/bower_components/html5shiv/dist/html5shiv.js" rel="stylesheet" type="text/css" />
        <link href="<?= $baseUrl ?>/bower_components/respond/dest/respond.min.js" rel="stylesheet" type="text/css" />
        <![endif]-->
    </head>

    <body>

        <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?= $baseUrl ?>/">Future <em>CI</em></a>
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="<?= $baseUrl ?>/">Home</a></li>
                        <li><a href="<?= $baseUrl ?>/">About</a></li>
                        <li><a href="<?= $baseUrl ?>/">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="jumbotron">
            <div class="container">
                <h1>Hello, world!</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam sagittis velit eget malesuada hendrerit. Nam massa sem, ullamcorper in odio sed, luctus aliquet eros. Proin.</p>
                <p><a class="btn btn-primary btn-lg" href="<?= $baseUrl ?>/" role="button">Learn more &raquo;</a></p>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h2><i class="fa fa-gears"></i> Curabitur</h2>
                    <p>Nec mi ipsum. Maecenas auctor, tellus eget tempus rhoncus, nisl mauris accumsan tellus, in molestie eros massa nec libero. Donec egestas lorem sit amet tellus convallis hendrerit vel a felis. Donec sit amet dolor dui. Nulla facilisi. Cras nec iaculis turpis. Pellentesque congue posuere quam.</p>
                    <p><a class="btn btn-default" href="<?= $baseUrl ?>/" role="button">View details &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2><i class="fa fa-globe"></i> Proin</h2>
                    <p>Tincidunt quam in erat varius, ultrices adipiscing ipsum pulvinar. Curabitur venenatis vehicula luctus. Aliquam pellentesque risus augue, vitae tristique ligula malesuada a. Donec tempus tincidunt ante eget dapibus. Nunc pellentesque erat ut velit pulvinar, non adipiscing erat volutpat.</p>
                    <p><a class="btn btn-default" href="<?= $baseUrl ?>/" role="button">View details &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2><i class="fa fa-trophy"></i> Integer</h2>
                    <p>Ullamcorper, enim ac facilisis tincidunt, felis urna vehicula dolor, facilisis dictum metus massa a ante. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum iaculis dui in tortor dignissim malesuada. Ut condimentum eu neque quis dictum.</p>
                    <p><a class="btn btn-default" href="<?= $baseUrl ?>/" role="button">View details &raquo;</a></p>
                </div>
            </div>

            <hr />

            <footer>
                <p>&copy; <?= $copyrightYears ?> <a href="http://future500.nl/" target="_blank">Future500 B.V.</a></p>
            </footer>

        </div>

        <script src="<?= $baseUrl ?>/bower_components/jquery/dist/jquery<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
        <script src="<?= $baseUrl ?>/bower_components/bootstrap/dist/js/bootstrap<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>
        <script src="<?= $baseUrl ?>/assets/js/f500ci<?= $minify ? '.min' : '' ?>.js" type="text/javascript"></script>

    </body>

</html>
