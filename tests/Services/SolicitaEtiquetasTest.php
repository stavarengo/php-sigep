<?php

class SolicitaEtiquetasTest extends TestCase {

	public function testEtiquetas() {
		$etiquetas = new Sigep\Services\SolicitaEtiquetas();
		$params = new \Sigep\Model\SolicitaEtiquetas();
		vaR_dumP($params);
		$etiquetas->execute($params);
	}

}
