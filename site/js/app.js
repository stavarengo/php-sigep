(function(){
    var errback = function(e) {
        if (e && e.errorMsg) {
            $('#modal-body').html('<div class="text-danger"><strong>Atenção!</strong> ' + e.errorMsg + '</div>');
        } else {
            $('#modal-body').html('<div class="text-danger"><strong>Opa!</strong> Ocorreu um erro ao tentar calcular os preços e prazos. <br/>Por favor, tente novamente.</div>');
        }
    };
    
//    hljs.configure({tabReplace: '    '}); // 4 spaces
    dojo.toJsonIndentStr = '    ';
    
    window.app = {
        btCalcPrecosClick: function() {
            this._request('calc-preco-prazo', function(data) {
                if (!data || data.errorMsg || !data.resultado || !data.help) {
                    errback(data);
                } else {
                    var resultado = '<pre>' + hljs.highlight('json', dojo.toJson(data.resultado, true)).value + '</pre>';
                    var html = '<div class="row"><div class="col-sm-6">' + data.help + '</div><div class="col-sm-6">' + resultado + '</div></div>';
                    $('#modal-body').html(html);
                }
            });
        },

        btDisponibilidadeServicoClick: function() {
            this._request('disponibilidade-servico');
        },

        btSolicitarEtiquetasClick: function() {
            this._request('solicitar-etiquetas');
        },
        
        btGerarEtiquetasDvClick: function() {
            this._request('gerar-etiquetas-dv');
        },
        
        _request: function(action, callback) {
            $('#modal-body').html('<div class="text-center"><i class="load-spin-xlarge"></i> Aguarde...</div>');
            $('#dlg-result').modal({});
            
            var cacheBust = new Date();
            dojo.xhr('POST', {
                url: '/?action=' + action + '&' + cacheBust.getTime(),
                content: this._getFormData('#demo-' + action + '-wp'),
                preventCache: true,
                handleAs: 'json'
            }).then(function(data) {
                if (!data || data.errorMsg || !data.resultado) {
                    errback(data);
                } else {
                    try {
                        if (callback) {
                            callback(data);
                        } else {
                            var html = '<pre>' + hljs.highlight('json', dojo.toJson(data.resultado, true)).value + '</pre>';
                            $('#modal-body').html(html);
                        }
                    } catch (e) {
                        errback(e);
                    }
                }
            }, errback);
        },
        
        servicosAdicionaisChange: function(sender, target) {
            var servicosAdicionais = $(sender).val() || [];
            var temValorDeclarado = dojo.some(servicosAdicionais, function(value){
                return (value == 'vd');
            });

            var target = $(target);
            if (temValorDeclarado) {
                target.removeClass('hide');
            } else {
                target.addClass('hide');
            }
        },
        
        _getFormData: function(_parent) {
            var data = {};
            $('input[name],select[name],textarea[name]', _parent).each(function(idx, element) {
                if (element.name) {
                    if (dojo.hasAttr(element, 'multiple')) {
                        var multiple = $(element).val() || [];
                        dojo.forEach(multiple, function(selected, i){
                            data[element.name.replace('[]', '') + '[' + i + ']'] = selected;
                        });
                    } else {
                        data[element.name] = $.trim(element.value);
                    }
                }
            });
            return data;
        }
    };
})();