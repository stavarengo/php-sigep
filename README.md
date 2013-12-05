PHP SIGEP - Correios
====================

Implementação do Web Service dos correios SIGEP Web.

Consulte a [documentação](http://stavarengo.github.io/php-sigep).


This API can:
* Enviar a pre-lista de postagem (PLP) para o Correios.
   Veja: https://github.com/stavarengo/php-sigep/tree/master/src/Sigep/Services/FecharPreListaDePostagem.php
* Verificar se um tipo de serviço (Sedex, PAC, ...) é permitido entre dois endereços.
   Veja: https://github.com/stavarengo/php-sigep/tree/master/src/Sigep/Services/VerificaDisponibilidadeServico.php
* Gerar novas etiquetas de postagem.
   Veja: https://github.com/stavarengo/php-sigep/tree/master/src/Sigep/Services/SolicitaEtiquetas.php
* Criar e/ou verificar validade do dígito verificador das etiquetas.
   Veja: https://github.com/stavarengo/php-sigep/tree/master/src/Sigep/Services/GeraDigitoVerificadorEtiquetas.php
* Gerar o relatório da PLP no formato PDF.
   Veja: https://github.com/stavarengo/php-sigep/tree/master/src/Sigep/Services/GeraDigitoVerificadorEtiquetas.php
* Gerar as etiquetas de postagem no formato PDF.
* Gerar em PDF as chancelas para cada tipo de serviço (logo de cada tipo de servico). 

Requisitos
---

* PHP >= 5.1.0
* Se você precisar imprimir as etiquetas e relatórios, baixe também o FPDF 1.7. Não esqueça de configurar o FPDF para ser auto carregado antes de tentar imprimir os relatórios.

Instalação manual
---

* Faça o download da última versão.
* Para usar as classe do php-sigep, você só precisa carregar o arquivo "php-sigep/src/PhpSigep/Bootstrap.php". Isso fara com que o loader seja registrado.

Instalação com Composer
---

Adicione a seguinte linha ao seu arquivo `composer.json`:

	"stavarengo/php-sigep": "1.0.0-rc"

E então execute `composer update` via linha de comando.

Contribua
---

Para executar a testsuite, execute `./vendor/bin/phpunit` via linha de comando.

1. Faça um fork
2. Crie sua branch para a funcionalidade (`git checkout -b nova-funcionalidade`)
3. Faça o commit suas modificações (`git commit -am 'Adiciona nova funcionalidade'`)
4. Faça o push para a branch (`git push origin nova-funcionalidade`)
5. Crie um novo Pull Request
