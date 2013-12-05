SIGEP - Correios
================

Implementação do Web Service dos correios SIGEP Web.

Consulte a [documentação](http://stavarengo.github.io/sigep).

This API can:
* Send the posting list (PLP) to Correios.
   See: https://github.com/stavarengo/sigep/blob/master/Api/Services/FecharPreListaDePostagem.php
* Check if one kind of service (Sedex, PAC, ...) is allowed between two address.
   See: https://github.com/stavarengo/sigep/blob/master/Api/Services/VerificaDisponibilidadeServico.php
* Request new tags ("etiqueta de postagem" in portuguese).
   See: https://github.com/stavarengo/sigep/blob/master/Api/Services/SolicitaEtiquetas.php
* Create and/or check the verifier digit of the tags.
   See: https://github.com/stavarengo/sigep/blob/master/Api/Services/GeraDigitoVerificadorEtiquetas.php

Composer
---

Adicione a seguinte linha ao seu arquivo `composer.json`:

	"stavarengo/sigep": "1.0.0-rc"

E então execute `composer update` via linha de comando.

Contribua
---

Para executar a testsuite, execute `./vendor/bin/phpunit` via linha de comando.

1. Faça um fork
2. Crie sua branch para a funcionalidade (`git checkout -b nova-funcionalidade`)
3. Faça o commit suas modificações (`git commit -am 'Adiciona nova funcionalidade'`)
4. Faça o push para a branch (`git push origin nova-funcionalidade`)
5. Crie um novo Pull Request
