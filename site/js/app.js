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
            $('#modal-body').html('<div class="text-center"><i class="load-spin-xlarge"></i> Aguarde...</div>');
            $('#dlg-result').modal({});
            this._getFormData();
            
            dojo.xhr('POST', {
                url: '/site/php/calc-preco-prazo.php',
                content: this._getFormData(),
                preventCache: true,
                handleAs: 'json'
            }).then(function(data) {
                if (!data || data.errorMsg || !data.resultado || !data.help) {
                    errback(data);
                } else {
                    var resultado = '<pre>' + hljs.highlight('json', dojo.toJson(data.resultado, true)).value + '</pre>';
                    var html = '<div class="row"><div class="col-sm-6">' + data.help + '</div><div class="col-sm-6">' + resultado + '</div></div>';
                    $('#modal-body').html(html);
                }
            }, errback);
        },

        servicosAdicionaisChange: function() {
            var servicosAdicionais = $('#servicosAdicionais').val() || [];
            var temValorDeclarado = dojo.some(servicosAdicionais, function(value){
                return (value == 'vd');
            });
            
            if (temValorDeclarado) {
                $('#valorDeclarado-wp').removeClass('hide');
            } else {
                $('#valorDeclarado-wp').addClass('hide');
            }
        },
        
        _getFormData: function() {
            var data = {};
            $('.container input[name],.container select[name]').each(function(idx, element) {
                if (element.name) {
                    if (dojo.hasAttr(element, 'multiple')) {
                        var multiple = $(element).val() || [];
                        dojo.forEach(multiple, function(selected, i){
                            data[element.id + '[' + i + ']'] = selected;
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