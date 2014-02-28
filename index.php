<?php
    $addresses = array(
        array(
            '${tipo}' => 'remetente',
            '${valueNome}'        => 'Google São Paulo',
            '${valueLogradouro}'  => 'Av. Brigadeiro Faria Lima',
            '${valueNumero}'      => '3900',
            '${valueComplemento}' => '5th floor',
            '${valueBairro}'      => 'Itaim',
            '${valueCidade}'      => 'São Paulo',
            '${valueEstado}'          => '101',
            '${valueCep}'         => '04538-132',
        ),
        array(
            '${tipo}' => 'destinatario',
            '${valueNome}'        => 'Google Belo Horizonte',
            '${valueLogradouro}'  => 'Av. Bias Fortes',
            '${valueNumero}'      => '382',
            '${valueComplemento}' => '6º andar',
            '${valueBairro}'      => 'Lourdes',
            '${valueCidade}'      => 'Belo Horizonte',
            '${valueEstado}'          => '77',
            '${valueCep}'         => '30170-010',
        ),
    );
    $addressForm = file_get_contents(__DIR__ . '/_addressForm.html');
    $remetenteForm = str_replace(array_keys($addresses[0]), array_values($addresses[0]), $addressForm);
    $destinatarioForm = str_replace(array_keys($addresses[1]), array_values($addresses[1]), $addressForm);
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
        <link href="/site/js/highlight/styles/default.css" rel="stylesheet">
        <script src="/site/js/jquery-1.10.2.min.js"></script>

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="contratoCodAdministrativo">Código administrativo</label>
                        <input type="text" class="form-control" name="contratoCodAdministrativo" id="contratoCodAdministrativo">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="contratoSenha">Senha</label>
                        <input type="text" class="form-control" name="contratoSenha" id="contratoSenha">
                    </div>
                </div>
            </div>
                    
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <h3>Remetente</h3>
                    <?php echo $remetenteForm ?>
                </div>
                
                <div class="col-md-6 col-lg-6">
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
                                <label for="tipoTransporte">Tipo de transporte</label>
                                <select class="form-control" name="tipoTransporte[]" id="tipoTransporte" multiple size="6">
                                    <option value="81019" selected>81019 - E-sedex</option>
                                    <option value="41068" selected>41068 - Pac</option>
                                    <option value="40096" selected>40096 - Sedex</option>
                                    <option value="40215" selected>40215 - Sedex 10 Envelope</option>
                                    <option value="40886" selected>40886 - Sedex 10 Pacote</option>
                                    <option value="40878" selected>40878 - Sedex Hoje</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="servicosAdicionais">Serviços adicionais</label>
                                <select class="form-control" name="servicosAdicionais[]" id="servicosAdicionais" multiple size="6" onchange="app.servicosAdicionaisChange()">
                                    <option value="mp" selected>Mão própria</option>
                                    <option value="vd" selected>Valor declarado</option>
                                    <option value="ar" selected>Aviso de recebimento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="peso">
                                    Peso total
                                    <span style="font-size: 10px">Em gramas (caixa + produto)</span>
                                </label>
                                <input type="text" class="form-control" id="peso" name="peso" value="0.500">
                            </div>
                            <div class="form-group" id="valorDeclarado-wp">
                                <label for="valorDeclarado">Valor declarado</label>
                                <input type="text" class="form-control" id="valorDeclarado" name="valorDeclarado" value="75.90">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="well">
                <button class="btn btn-primary" onclick="app.btCalcPrecosClick()">Calcular preços e prazos</button>
                <button class="btn btn-primary">Simular envio dos dados para o Correios</button>
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
        
        <script src="/site/js/dojo.js"></script>
        <script src="/site/css/twbs/js/bootstrap.min.js"></script>
        <script src="/site/js/highlight/highlight.pack.js"></script>
        <script src="/site/js/app.js"></script>
    </body>
</html>
