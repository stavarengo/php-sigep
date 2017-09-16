<?php

class SolicitaEtiquetasTest extends TestCase
{

    public function setUp() {
        
        $config = new \PhpSigep\Config();
        
        $config->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
        
        $config->setEnv(\PhpSigep\Config::ENV_PRODUCTION);
        
        
        $config->setCacheOptions(array(
           'storageOptions' => array(
                'enabled' => false,
                'ttl' => 10,
                'cacheDir' => sys_get_temp_dir()
            )
        ));
        
        \PhpSigep\Bootstrap::start($config);
        
    }
    
    public function Etiquetas(){
        $etiquetas = new PhpSigep\Services\Real\SolicitaEtiquetas();
        $params    = new \PhpSigep\Model\SolicitaEtiquetas(array(
                'qtdEtiquetas'      => 1,
                'servicoDePostagem' => new PhpSigep\Model\ServicoDePostagem(PhpSigep\Model\ServicoDePostagem::SERVICE_E_SEDEX_STANDARD),
                'accessData'        => new PhpSigep\Model\AccessData(array(
                        'cnpjEmpresa' => '16.646.849/0001-77',
                        'usuario'     => 'usuario',
                        'senha'       => 'senha',
                ))
        ));

        $this->assertCount(1, $etiquetas->execute($params), "Deve ter reservado uma etiqueta");
    }
    
    public function testCreateEtiquetasByRange() {

        $service = new \PhpSigep\Services\Real\SolicitaEtiquetas();
        
        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081471 BR';
        $retornoEsperado = array('EC31081466 BR', 'EC31081467 BR', 'EC31081468 BR', 'EC31081469 BR', 'EC31081470 BR', 'EC31081471 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081466 BR';
        $retornoEsperado = array('EC31081466 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081467 BR';
        $retornoEsperado = array('EC31081466 BR', 'EC31081467 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC31081466 BR';
        $ultimaEtiqueta = 'EC31081465 BR';
        $retornoEsperado = array();
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);
        
        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
    }
    
    public function testCreateEtiquetasByRangeWithZero() {

        $service = new \PhpSigep\Services\Real\SolicitaEtiquetas();
        
        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081471 BR';
        $retornoEsperado = array('EC01081466 BR', 'EC01081467 BR', 'EC01081468 BR', 'EC01081469 BR', 'EC01081470 BR', 'EC01081471 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081466 BR';
        $retornoEsperado = array('EC01081466 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081467 BR';
        $retornoEsperado = array('EC01081466 BR', 'EC01081467 BR');
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);

        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
        $primeiraEtiqueta = 'EC01081466 BR';
        $ultimaEtiqueta = 'EC01081465 BR';
        $retornoEsperado = array();
        $numerosEtiquetas = $service->createNumberEtiquetasByRange($primeiraEtiqueta, $ultimaEtiqueta);
        
        $this->assertEquals($retornoEsperado, $numerosEtiquetas);
        
    }

    public function testSolicitarEtiquetaUmRequisicao(){
        
        $quantidadeEtiquetas = 10;
        
        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas($quantidadeEtiquetas);
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_41068);
        $params->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
        $params->setModoUmaRequisicao();

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $etiquetaSolicitada = $phpSigep->solicitaEtiquetas($params);
        
        $this->assertInstanceOf('\PhpSigep\Services\Result', $etiquetaSolicitada);

        $etiquetas  = $etiquetaSolicitada->getResult();
        
        $this->assertCount($quantidadeEtiquetas, $etiquetas);
        
    }
    
    public function testclerSolicitarEtiquetaVariasRequisicoes(){
        
        $quantidadeEtiquetas = 10;
            
        $params = new \PhpSigep\Model\SolicitaEtiquetas();
        $params->setQtdEtiquetas($quantidadeEtiquetas);
        $params->setServicoDePostagem(\PhpSigep\Model\ServicoDePostagem::SERVICE_PAC_41068);
        $params->setAccessData(new \PhpSigep\Model\AccessDataHomologacao());
        $params->setModoMultiplasRequisicoes();

        $phpSigep = new PhpSigep\Services\SoapClient\Real();

        $etiquetaSolicitada = $phpSigep->solicitaEtiquetas($params);
        
        $this->assertInstanceOf('\PhpSigep\Services\Result', $etiquetaSolicitada);

        $etiquetas  = $etiquetaSolicitada->getResult();
        
        $this->assertCount($quantidadeEtiquetas, $etiquetas);
        
    }
        

}
