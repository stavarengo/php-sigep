<?php

class DigitoVerificadorTest extends TestCase
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
    
    public function testCodigoVerificador(){
        $etiqueta1 = new \PhpSigep\Model\Etiqueta();
        $etiqueta1->setEtiquetaSemDv('PN77768520BR');
        $this->assertEquals('PN777685204BR', $etiqueta1->getEtiquetaComDv());
        
        $etiqueta2 = new \PhpSigep\Model\Etiqueta();
        $etiqueta2->setEtiquetaSemDv('PN77768521BR');
        $this->assertEquals('PN777685218BR', $etiqueta2->getEtiquetaComDv());
        
        $etiqueta3 = new \PhpSigep\Model\Etiqueta();
        $etiqueta3->setEtiquetaSemDv('PN77768524BR');
        $this->assertEquals('PN777685249BR', $etiqueta3->getEtiquetaComDv());
        
        $etiqueta4 = new \PhpSigep\Model\Etiqueta();
        $etiqueta4->setEtiquetaSemDv('PN77768525BR');
        $this->assertEquals('PN777685252BR', $etiqueta4->getEtiquetaComDv());
        
        $etiqueta5 = new \PhpSigep\Model\Etiqueta();
        $etiqueta5->setEtiquetaSemDv('PN77768526BR');
        $this->assertEquals('PN777685266BR', $etiqueta5->getEtiquetaComDv());
        
        $etiqueta6 = new \PhpSigep\Model\Etiqueta();
        $etiqueta6->setEtiquetaSemDv('PO02325102BR');
        $this->assertEquals('PO023251024BR', $etiqueta6->getEtiquetaComDv());
        
        $etiqueta7 = new \PhpSigep\Model\Etiqueta();
        $etiqueta7->setEtiquetaSemDv('PO02603026BR');
        $this->assertEquals('PO026030265BR', $etiqueta7->getEtiquetaComDv());
    }

}
