(function(){
    var errback = function(e) {
        if (e && e.errorMsg) {
            $('#modal-body').html('<div class="text-danger"><strong>Atenção!</strong> ' + e.errorMsg + '</div>');
        } else {
            $('#modal-body').html('<div class="text-danger"><strong>Opa!</strong> Ocorreu um erro ao tentar calcular os preços e prazos. <br/>Por favor, tente novamente.</div>');
        }
    };
    
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
                    $('#modal-body').html('<pre>' + dojo.toJson(data) + '</pre>');
                }
            }, errback);
        },
        
        _getFormData: function() {
            var data = {};
            $('.container input,.container select').each(function(idx, element) {
                if (element.id) {
                    data[element.id] = $.trim(element.value);
                }
            });
            return data;
        }
    };
})();