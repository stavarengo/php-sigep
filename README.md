SIGEP-PHP
===

Implementação do Web Service dos correios SIGEP Web.
Consulte a [documentação](http://endel.github.io/sigep).

Serviços implementados
---

- Fechar pre lista de postagem
- Gerar dígito verificador de etiquetas
- Solicitar etiquetas
- Verificar disponibilidade

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
