<?php

namespace Send4\PhpSigep;

use PhpSigep\Bootstrap;
use PhpSigep\Config;
use PhpSigep\Model\AccessData;
use PhpSigep\Model\AccessDataHomologacao;
use PhpSigep\Services\Real\SoapClientFactory;
use PhpSigep\Services\SoapClient\Real;

use PhpSigep\Model\SolicitaPostagemReversa;
use PhpSigep\Model\Destinatario;
use PhpSigep\Model\Remetente;
use PhpSigep\Model\ColetasSolicitadas;
use PhpSigep\Model\Produto;
use PhpSigep\Model\ObjCol;

/**
 *
 * @author William Novak <williamnvk@gmail.com>
 */
class LogisticaReversa extends Bootstrap {

    /**
     * @var object
     */
    private $instance;
    /**
     * @var object
     */
    private $accessData;

    /**
     * LogisticaReversa constructor.
     */
    public function __construct()
    {
        $config = new Config();
        $config->setLogisticaReversa(true);
        $config->setEnv($config::ENV_PRODUCTION, false); // TODO fix
        $this->instance = new \StdClass;
        $this->instance->config = $config;
    }

    /**
     * Dados de acesso ao webservice.
     * @param array $accessData
     * @access public
     * @return void
     */
    public function init($accessData)
    {
        $accessDataInstance = new AccessData($accessData);
        $this->instance->config->setAccessData($accessDataInstance);
        $this->accessData = $this->instance->config->getAccessData();
        parent::__construct($this->instance->config);
    }

    /**
     * Envia para os correios a solicitação de autorização de postagem
     *
     * @param array $solicitacaoDePostagem
     * @return object
     */
    public function criarPostagem($solicitacaoDePostagem = array())
    {

        /**
         * Verifica se a chave existe na solicitação
         */
        if (!array_key_exists('destinatario', $solicitacaoDePostagem)) {
            return (object) [
                'success' => false,
                'error' => 'Dados do destinatário não informado na solicitacão de postagem.'
            ];
        }

        if (!array_key_exists('remetente', $solicitacaoDePostagem)) {
            return (object) [
                'success' => false,
                'error' => 'Dados do remetente não informado na solicitacão de postagem.'
            ];
        }

        if (!array_key_exists('produtos', $solicitacaoDePostagem)) {
            return (object) [
                'success' => false,
                'error' => 'Produto(s) não informados na solicitacão de postagem.'
            ];
        }

        //
        $object = (object) $solicitacaoDePostagem;

        /**
         * Define os dados de autenticação.
         */
        $this->init($object->accessData);

        /**
         * Dados do Destinatário (quem vai receber).
         */
        $destinatario = new Destinatario($object->destinatario);

        /**
         * Dados do Remetente (quem está enviando).
         */
        $remetente = new Remetente($object->remetente);

        /**
         * Listagem de produtos (obj_col).
         */
        $produtos = [];
        if ($object->produtos) {
            foreach ($object->produtos as $produto) {
                $obj_col = new ObjCol($produto);
                $produtos[] = $obj_col->getObjects();
            }
        }

        /**
         * Coletas Solicitadas, agrupa remetente, pacote para embalagem (produto) e produtos (objcol)
         */
        $object->coletas_solicitadas['remetente'] = $remetente;
        $object->coletas_solicitadas['produto'] = new Produto(['tipo' => '0', 'codigo' => '765000636', 'qtd' => '1']);
        $object->coletas_solicitadas['obj_col'] = $produtos;
        $coletasSolicitadas = new ColetasSolicitadas($object->coletas_solicitadas);

        /**
         * Monta o objeto para envio.
         */
        $solicitacaoPostagemReversa = new SolicitaPostagemReversa(
            [
                'destinatario' => $destinatario,
                'accessData' => $this->accessData,
                'coletasSolicitadas' => $coletasSolicitadas
            ]
        );

        $phpSigep = new Real();

        return $phpSigep->solicitarPostagemReversa($solicitacaoPostagemReversa);

    }

    public function tracking ($data)
    {

        $accessData = [
            'usuario'           => $data['usuario'],
            'senha'             => $data['senha'],
            'codAdministrativo' => $data['codAdministrativo'],
            'cartaoPostagem'    => $data['cartaoPostagem']
        ];
        $accessObject = new \PhpSigep\Model\AccessData($accessData);

        /**
         * Define os dados de autenticação.
         */
        $this->init($accessData);

        $this->instance->config->setAccessData($accessObject);

        $phpSigep = new Real();
        $acompanhaPostagem = new \PhpSigep\Model\AcompanhaPostagemReversa();
        $acompanhaPostagem->setAccessData($accessObject);
        $acompanhaPostagem->setTipoBusca('H');
        $acompanhaPostagem->setTipoSolicitacao('A');
        $acompanhaPostagem->setNumeroPedido($data['tracking_code']);

        return $phpSigep->acompanharPostagemReversa($acompanhaPostagem)->getResult();
    }

    public function trackingObjectNumber ($data)
    {

        $accessData = [
            'usuario'           => $data['correio_sro_username'],
            'senha'             => $data['correio_sro_password'],
            'codAdministrativo' => $data['codAdministrativo'],
            'cartaoPostagem'    => $data['cartaoPostagem']
        ];
        $accessObject = new \PhpSigep\Model\AccessData($accessData);

        /**
         * Define os dados de autenticação.
         */
        $this->init($accessData);

        $this->instance->config->setAccessData($accessObject);

        $params = new \PhpSigep\Model\RastrearObjeto();
        $params->setAccessData($accessObject);

        $etiqueta = new \PhpSigep\Model\Etiqueta();
        $etiqueta->setEtiquetaComDv(trim($data['object_number']));
        $etiquetas[] = $etiqueta;

        $params->setEtiquetas($etiquetas);

        $phpSigep = new Real();
        return $phpSigep->rastrearObjeto($params)->getResult();

    }

}
