<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!--        <link rel="shortcut icon" href="../../assets/ico/favicon.ico">-->

        <title>PHP Sigep</title>

        <link href="<?php echo $baseUrl ?>/site/css/twbs/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $baseUrl ?>/site/css/site.css" rel="stylesheet">
        <link href="<?php echo $baseUrl ?>/site/js/highlight/styles/default.css" rel="stylesheet">
        <script src="<?php echo $baseUrl ?>/site/js/jquery-1.10.2.min.js"></script>

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="jumbotron section">
            <div class="inner">
                <h1><a href="<?php echo $baseUrl ?>/">PHP Sigep</a></h1>
                <div class="social">
                    <iframe src="<?php echo $baseUrl ?>/site/github-buttons/github-btn.html?user=stavarengo&repo=php-sigep&type=watch&count=true&size=large"
                        allowtransparency="true" frameborder="0" scrolling="0" width="160" height="40"></iframe>
                    <iframe src="<?php echo $baseUrl ?>/site/github-buttons/github-btn.html?user=stavarengo&repo=php-sigep&type=fork&count=true&size=large"
                            allowtransparency="true" frameborder="0" scrolling="0" width="160" height="40"></iframe>
                </div>
                <a href="#demo-calc-preco-prazo" class="btn btn-success btn-lg" role="button">Demonstração online</a>
                &nbsp;&nbsp;&nbsp;
                <a href="https://github.com/stavarengo/php-sigep" class="btn btn-success btn-lg" role="button">Download on GitHub</a>
            </div>
        </div>
        
        <div class="container">
            <div class="row">
                <div class="col-sm-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active"><a href="#home">Home</a></li>
                        <li><a href="#demo-calc-preco-prazo">Calcular preços e prazos</a></li>
                        <li><a href="#demo-relatorios-pdf">Imprimir etiquetas e PLP</a></li>
                        <li><a href="#demo-disponibilidade-servico">Verificar disponibilidade do serviço</a></li>
                        <li><a href="#demo-solicitar-etiquetas">Solicitar etiquetas</a></li>
                        <li><a href="#demo-gerar-etiquetas-dv">Calcular <abbr title="Dígito Verificador">DV</abbr> das etiquetas</a></li>
                    </ul>
                </div>
                <div class="col-sm-10 the-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="home"></a>
                            <h1 style="padding-top: 0">Home page</h1>
                            <div id="home-page-wp">
                                <?php include __DIR__ . '/home-page.phtml' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="demo-calc-preco-prazo"></a>
                            <h1>Calcular preços e prazos</h1>
                            <div id="demo-calc-preco-prazo-wp">
                                <?php include __DIR__ . '/demo-calc-preco-prazo.phtml' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="demo-relatorios-pdf"></a>
                            <h1>Imprimir etiquetas e PLP</h1>
                            <div id="demo-relatorios-pdf-wp">
                                <?php include __DIR__ . '/demo-relatorios-pdf.phtml' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="demo-disponibilidade-servico"></a>
                            <h1>Verificar disponibilidade do serviço</h1>
                            <div id="demo-disponibilidade-servico-wp">
                                <?php include __DIR__ . '/demo-disponibilidade-servico.phtml' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="demo-solicitar-etiquetas"></a>
                            <h1>Solicitar etiquetas</h1>
                            <div id="demo-solicitar-etiquetas-wp">
                                <?php include __DIR__ . '/demo-solicitar-etiquetas.phtml' ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <a id="demo-gerar-etiquetas-dv"></a>
                            <h1>Solicitar <abbr title="Dígito Verificador">DV</abbr> das etiquetas</h1>
                            <div id="demo-gerar-etiquetas-dv-wp">
                                <?php include __DIR__ . '/demo-gerar-etiquetas-dv.phtml' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="dlg-result" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Resultado</h4>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo $baseUrl ?>/site/js/dojo.js"></script>
        <script src="<?php echo $baseUrl ?>/site/css/twbs/js/bootstrap.min.js"></script>
        <script src="<?php echo $baseUrl ?>/site/js/highlight/highlight.pack.js"></script>
        <script src="<?php echo $baseUrl ?>/site/js/app.js"></script>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-12926898-13');
            ga('send', 'pageview');

        </script>
    </body>
</html>
