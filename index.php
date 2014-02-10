<?php
    $addressForm = file_get_contents(__DIR__ . '/_addressForm.html');
    $remetenteForm = str_replace('${tipo}', 'remetente', $addressForm);
    $destinatarioForm = str_replace('${tipo}', 'destinatario', $addressForm);
?>
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

        <link href="/site/css/twbs/css/bootstrap.min.css" rel="stylesheet">
        <link href="/site/css/site.css" rel="stylesheet">

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h3>Remetente</h3>
                    <?php echo $remetenteForm ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>Destinatário</h3>
                    <?php echo $destinatarioForm ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h3>Embalagem</h3>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="peso">Peso total</label>
                                <input type="text" class="form-control" id="peso" name="peso">
                                <span class="help-block">Embalagem + mercadoria.</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="tipoTransporte">Tipo de transporte</label>
                                <select class="form-control" name="tipoTransporte">
                                    <option value="81019">E-sedex</option>
                                    <option value="41068">Pac</option>
                                    <option value="40096">Sedex</option>
                                    <option value="40215">Sedex 10 Envelope</option>
                                    <option value="10065">Carta</option>
                                    <option value="40886">Sedex 10 Pacote</option>
                                    <option value="40878">Sedex Hoje</option>
                                    <option value="41009">Sedex Agrupado</option>
                                    <option value="10138">Carta Registrada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="/site/js/jquery-1.10.2.min.js"></script>
        <script src="/site/css/twbs/js/bootstrap.min.js"></script>
    </body>
</html>
