### Ajude o projeto a crescer. Envie pull request de suas alterações no código fonte ou na documentação do projeto.

PHP SIGEP - Correios
====================

* Baixe a última versão estável aqui: [https://github.com/stavarengo/php-sigep/tags](https://github.com/stavarengo/php-sigep/tags)

Implementação do Web Service dos correios SIGEP Web.
### [Veja a demo online, exemplos e documentação em nossa página](http://stavarengo.github.io/php-sigep).

Integração com Web Service do Correios. Consulta preços e prazos, imprime etiquetas e PLP, etc.
Esta API pode:
* Calcular preços e prazos de entrega da encomenda.   
* Obter os dados de rastreamento das encomendas.   
* Verificar se um tipo de serviço (Sedex, PAC, ...) é permitido entre dois endereços.   
* Gerar e enviar o XML da pre-lista de postagem (PLP) para o Correios.   
* Gerar novos números de etiquetas de postagem.
* Criar e/ou verificar validade do dígito verificador das etiquetas (através do web service ou não).   
* Gerar o relatório da PLP no formato PDF.   
* Gerar as etiquetas de postagem no formato PDF.
* Gerar em PDF as chancelas para cada tipo de serviço (logo de cada tipo de servico).
* Obter dados de PLP após postagem [processamento pelo Sara]
* [Novo] Suspender a entrega de postagem (Também chamado de Entrega Interativa)
* [Novo] Listagem de Agências (Necessário para o Clique e Retire)
* [Novo] Gerar as etiquetas de postagem no formato PDF para o Clique e Retire.

Requisitos
---

* PHP >= 5.4.0
* Se você precisar imprimir as etiquetas e relatórios, baixe também o FPDF 1.7 [www.fpdf.org](http://www.fpdf.org/).   
  Não esqueça de configurar o FPDF para ser auto carregado antes de tentar imprimir os relatórios.

Instalação com Composer (recomendado)
---

* Nós não controlamos versão através das tags, porem, a branch master só é atualizada quando o código está estável.
  Portanto, a versão estável mais atual sempre será a branch master.

Adicione as seguintes linha ao seu arquivo `composer.json`:
	"stavarengo/php-sigep": "dev-master"    
    "stavarengo/php-sigep-fpdf": "dev-master"

E então execute `composer update` via linha de comando.



Instalação manual
---

* Nós não controlamos versão através das tags, porem, a branch master só é atualizada quando o código está estável.
  Portanto, a versão estável mais atual sempre será a branch master.

* Faça o download da última versão.
* Para usar as classe do php-sigep, você só precisa carregar o arquivo "php-sigep/src/PhpSigep/Bootstrap.php". Isso fara com que o loader seja registrado.

# Problemas Comuns

## Autorização de acesso negada para o sistema
Antes de utilizar este projeto em modo produção, é necessário solicitar ao representante comercial dos correios habilitação e senha para o webservice dos correios.

## Estou tendo problema ao utilizar o ambiente de homologação
Se você está recebendo a mensagem abaixo ao tentar utilizar o ambiente de homologação, significa que o webservice do correio está temporariamente indisponível. Não adianta criar _issue_, o melhor a fazer é aguardar ou tentar entrar em contato com o suporte técnico do correio.

**Mensagem de erro**: Parsing WSDL: Couldn't load from 'https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl' : failed to load external entity "https://apphom.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsd

## Problemas com o PHP 5.3
Este problema foi reportado aqui: https://github.com/stavarengo/php-sigep/issues/35
Alguns usuarios tiveram problemas de conexão e autentificação com WebService do Correios em ambiente de produção devido a versão do PHP.
Para resolver o problema, você pode ou utilizar uma versão masi rescente do PHP (>=5.4) ou fazer o download do WSDL do Correios e utilizar ele no seu servidor para fazer conexão.
Caso escolha fazer o download o WSDL, siga os passos abaixo:
<a id="example-change-wsdl"></a>
1. Salve este arquivo em seu ambiente local https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl
2. Altere as configurações do seu ambiente de produção para apotar para o arquivo que você baixou. Vejo exemplo abaixo.
```php
$config = new \PhpSigep\Config();
$config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
$config->setWsdlAtendeCliente('CAMINHO-DO-SEU-ARQUIVO-LOCAL');
\PhpSigep\Bootstrap::start($config);
```
OBS: Não irá funcionar em um servidor local, como Wamp, Xammp entre outros.

Funções
---

Cache
---

O componente de cache do PhpSigep foi inspirado no [componente de cache do Zend Framework](http://framework.zend.com/manual/2.3/en/index.html#zend-cache).

Por padrão o cache do PhpSigep está desabilitado.   
Este cache armazena algumas respostadas do WebService dos correios que podem ser reutilizadas posteriomente.
Alem de aumentar a velocidade de respostas das requisições, também evitamos que os usuários fiquem impedidos de continuar
mesmo quando o servidor do Correios esteja instavel (acredite: ele fica instável com muita frequencia).

Para habilitar o cache, use a chave "cacheOptions" ao criar a configuração do PhpSigep.
Ex:
    ```php
        new \PhpSigep\Config(
            array(
                'cacheOptions' => array(
                    'storageOptions' => array(
                        'enabled' => true,
                        'ttl' => 60*60*24*7,// Uma semana
                    ),
                ),
                ...
            ),
        );
    ```
Dentro do `array` `storageOptions` você pode usar o nome de qualquer atributo da classe `PhpSigep\Cache\Storage\Adapter\AdapterOptions`.

Contribua
---

Para executar a testsuite, execute `./vendor/bin/phpunit` via linha de comando.

1. Faça um fork
2. Crie sua branch para a funcionalidade (`git checkout -b nova-funcionalidade`)
3. Faça o commit suas modificações (`git commit -am 'Adiciona nova funcionalidade'`)
4. Faça o push para a branch (`git push origin nova-funcionalidade`)
5. Crie um novo Pull Request
