<?php

class SolicitaEtiquetasTest extends TestCase
{

	public function testEtiquetas()
	{
		$etiquetas = new PhpSigep\Services\SolicitaEtiquetas();
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

}
