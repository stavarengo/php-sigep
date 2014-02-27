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
                if (!data || data.errorMsg) {
                    errback(data);
                } else {
                    $('#modal-body').html('<pre>' + hljs.highlight('json', dojo.toJson(data, true)).value + '</pre>');
//                    $('#modal-body').html('<pre><code>' + dojo.toJson(data, true) + '</code></pre>');
//                    
//                    $('#modal-body pre code').each(function(i, e) {hljs.highlightBlock(e)});
                }
            }, errback);
        },
        
        _getFormData: function() {
            var data = {};
            $('.container input[name],.container select[name]').each(function(idx, element) {
                if (element.name) {
                    if (dojo.hasAttr(element, 'multiple')) {
                        $(':selected', element).each(function(i, selected){
                            data[element.id + '[' + i + ']'] = $(selected).val()
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