<?php

namespace PhpSigep\Test\Services;

use PhpSigep\Config;
use PhpSigep\Model\AccessData;
use PhpSigep\Model\AccessDataHomologacao;
use PhpSigep\Model\ServicoDePostagem;
use PhpSigep\Services\Real\SolicitaEtiquetas;
use PhpSigep\Services\SoapClient\Real;
use PHPUnit\Framework\TestCase;

class SolicitaEtiquetasTest extends TestCase
{

    public function setUp(): void
    {

        $config = new Config();

        $config->setAccessData(new AccessDataHomologacao());

        $config->setEnv(Config::ENV_PRODUCTION);


        $config->setCacheOptions([
            'storageOptions' => [
                'enabled' => false,
                'ttl' => 10,
                'cacheDir' => sys_get_temp_dir(),
            ],
        ]);

        \PhpSigep\Bootstrap::start($config);
    }

    public function testCreateEtiquetasByRange(): void
    {
        $service = new SolicitaEtiquetas();

        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081471 BR';
        $retornoEsperado = ['EC31081466 BR', 'EC31081467 BR', 'EC31081468 BR', 'EC31081469 BR', 'EC31081470 BR', 'EC31081471 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081466 BR';
        $retornoEsperado = ['EC31081466 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081467 BR';
        $retornoEsperado = ['EC31081466 BR', 'EC31081467 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081465 BR';
        $retornoEsperado = [];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

    }

    public function testCreateEtiquetasByRangeWithZero(): void
    {

        $service = new SolicitaEtiquetas();

        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081471 BR';
        $retornoEsperado = ['EC01081466 BR', 'EC01081467 BR', 'EC01081468 BR', 'EC01081469 BR', 'EC01081470 BR', 'EC01081471 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081466 BR';
        $retornoEsperado = ['EC01081466 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081467 BR';
        $retornoEsperado = ['EC01081466 BR', 'EC01081467 BR'];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081465 BR';
        $retornoEsperado = [];
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);

    }

    public function testSolicitarEtiquetaUmRequisicao(): void
    {

        $quantidadeEtiquetas = 10;

        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas($quantidadeEtiquetas);
        $params->setServicoDePostagem(ServicoDePostagem::SERVICE_SEDEX_10);
        $params->setAccessData(new AccessDataHomologacao());
        $params->setModoUmaRequisicao();

        $phpSigep = new Real();

        $etiquetaSolicitada = $phpSigep->solicitaEtiquetas($params);

        $this->assertInstanceOf('\PhpSigep\Services\Result', $etiquetaSolicitada);

        $etiquetas = $etiquetaSolicitada->getResult();

        $this->assertCount($quantidadeEtiquetas, $etiquetas);

    }

    public function testclerSolicitarEtiquetaVariasRequisicoes(): void
    {
        $quantidadeEtiquetas = 10;

        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas($quantidadeEtiquetas);
        $params->setServicoDePostagem(ServicoDePostagem::SERVICE_SEDEX_10);
        $params->setAccessData(new AccessDataHomologacao());
        $params->setModoMultiplasRequisicoes();

        $phpSigep = new Real();

        $etiquetaSolicitada = $phpSigep->solicitaEtiquetas($params);

        $this->assertInstanceOf('\PhpSigep\Services\Result', $etiquetaSolicitada);

        $etiquetas = $etiquetaSolicitada->getResult();

        $this->assertCount($quantidadeEtiquetas, $etiquetas);

    }


}
