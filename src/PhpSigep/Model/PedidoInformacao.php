<?php

namespace PhpSigep\Model;

/**
 * Class PedidoInformacao
 *
 * @package PhpSigep\Model
 * @author: Bruno Maia <email:brunopmaia@gmail.com>
 */
class PedidoInformacao extends AbstractModel
{

    /**
     * Remessa/Objeto postal entregue em local divergente
     */
    const CODIGO_RECLAMACAO_REMESSA_ENTREGUE_LOCAL_DIVERGENTE = 132;

    /**
     * Remessa/Objeto postal violada
     */
    const CODIGO_RECLAMACAO_REMESSA_VIOLADA = 133;

    /**
     * Remessa/Objeto postal avariada/danificada
     */
    const CODIGO_RECLAMACAO_REMESSA_AVARIADA = 134;

    /**
     * Remessa/Objeto postal entregue com atraso
     */
    const CODIGO_RECLAMACAO_REMESSA_ENTREGUE_COM_ATRASO = 135;

    /**
     * Remessa/Objeto postal devolvida indevidamente
     */
    const CODIGO_RECLAMACAO_REMESSA_DEVOLVIDA_INDEVIDAMENTE = 136;

    /**
     * Não recebimento do pedido de confirmação
     */
    const CODIGO_RECLAMACAO_NAO_RECEBIMENTO_PEDIDO_CONFIRMACAO = 141;

    /**
     * Remetente não recebeu o pedido de cópia
     */
    const CODIGO_RECLAMACAO_REMETENTE_NAO_RECEBEU_PEDIDO_COPIA = 142;

    /**
     * Remetente não recebeu o AR
     */
    const CODIGO_RECLAMACAO_REMETENTE_NAO_RECEBEU_AR = 148;

    /**
     * Remessa/Objeto postal não entregue
     */
    const CODIGO_RECLAMACAO_REMESSA_NAO_ENTREGUE = 211;

    /**
     * AR Digital - Imagem não disponível
     */
    const CODIGO_RECLAMACAO_AR_DIGITAL_IMAGEM_NAO_DISPONIVEL = 240;

    /**
     * Entrega em Caixa de Correio Inteligente
     */
    const CODIGO_RECLAMACAO_ENTREGA_CAIXA_CORREIO_INTELIGENTE = 310;

    /**
     * Postagem cancelada a pedido do remetente
     */
    const CODIGO_RECLAMACAO_POSTAGEM_CANCELADA_A_PEDIDO_REMETENTE = 313;

    /**
     * Destinatário Ausente - Contestar Informação
     */
    const CODIGO_RECLAMACAO_CONTESTAR_INFORMACAO_DESTINATARIO_AUSENTE = 316;

    /**
     * Tipo de embalagem "E" igual a Envelope
     */
    const TIPO_EMBALAGEM_ENVELOPE = 'E';

    /**
     * Tipo de embalagem "C" igual a Caixa
     */
    const TIPO_EMBALAGEM_CAIXA = 'C';

    /**
     * Tipo de Manifestação "I" Solicitação de ressarcimento
     */
    const TIPO_MANIFESTACAO_RESSARCIMENTO = 'I';

    /**
     * Tipo de Manifestação "R" Reclamação
     */
    const TIPO_MANIFESTACAO_RECLAMACAO = 'R';

    /**
     * @var AccessData
     */
    protected $accessData;

    /**
     * @var string DDD + Telefone Contato
     */
    protected $telefone;

    /**
     * @var string Código da etiqueta/Código do Rastreamento
     */
    protected $codigoObjeto;

    /**
     * @var string E-mail para Contato
     */
    protected $emailResposta;

    /**
     * @var string Nome do Destinatário da Remessa/Encomenda
     */
    protected $nomeDestinatario;

    /**
     * @var int Código da Reclamação/Motivo da Reclamação
     */
    protected $codigoMotivoReclamacao = self::CODIGO_RECLAMACAO_REMESSA_NAO_ENTREGUE;

    /**
     * @var string Tipo de Embalagem utilizada na postagem
     */
    protected $tipoEmbalagem = self::TIPO_EMBALAGEM_CAIXA;

    /**
     * @var string Tipo de Manifestação
     */
    protected $tipoManifestacao = self::TIPO_MANIFESTACAO_RECLAMACAO;

    /**
     * @param \PhpSigep\Model\AccessData $accessData
     */
    public function setAccessData($accessData)
    {
        $this->accessData = $accessData;
    }

    /**
     * @return \PhpSigep\Model\AccessData
     */
    public function getAccessData()
    {
        return $this->accessData;
    }

    /**
     * Define o telefone para contato
     *
     * @param $telefone
     * @return $this
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Obtem o telefone para contato
     *
     * @return string
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Define o Código do Objeto
     *
     * @param string $codigoObjeto
     * @return $this
     */
    public function setCodigoObjeto($codigoObjeto)
    {
        $this->codigoObjeto = $codigoObjeto;

        return $this;
    }

    /**
     * Obtem o Código do Objeto
     *
     * @return string
     */
    public function getCodigoObjeto()
    {
        return $this->codigoObjeto;
    }

    /**
     * Define o e-mail para resposta
     *
     * @param string $emailResposta
     * @return $this
     */
    public function setEmailResposta($emailResposta)
    {
        $this->emailResposta = $emailResposta;

        return $this;
    }

    /**
     * Obtem o e-mail para resposta
     *
     * @return string
     */
    public function getEmailResposta()
    {
        return $this->emailResposta;
    }

    /**
     * Define o Nome do Destinatário
     *
     * @param string $nomeDestinatario
     * @return $this
     */
    public function setNomeDestinatario($nomeDestinatario)
    {
        $this->nomeDestinatario = $nomeDestinatario;

        return $this;
    }

    /**
     * Obtem o Nome do Destinatário
     *
     * @return string
     */
    public function getNomeDestinatario()
    {
        return $this->nomeDestinatario;
    }

    /**
     * Define o código da reclamação
     *
     * @param int $codigoMotivoReclamacao
     * @return $this;
     */
    public function setCodigoMotivoReclamacao($codigoMotivoReclamacao)
    {
        $this->codigoMotivoReclamacao = $codigoMotivoReclamacao;

        return $this;
    }

    /**
     * Obtem o código da reclamação
     *
     * @return int
     */
    public function getCodigoMotivoReclamacao()
    {
        return $this->codigoMotivoReclamacao;
    }

    /**
     * Define o tipo de embalagem
     *
     * @param string $tipoEmbalagem
     * @return $this;
     */
    public function setTipoEmbalagem($tipoEmbalagem)
    {
        $this->tipoEmbalagem = $tipoEmbalagem;

        return $this;
    }

    /**
     * Obtem o tipo de embalagem
     *
     * @return string
     */
    public function getTipoEmbalagem()
    {
        return $this->tipoEmbalagem;
    }

    /**
     * Define o tipo de manifestção
     *
     * @param string $tipoManifestacao
     * @return $this;
     */
    public function setTipoManifestacao($tipoManifestacao)
    {
        $this->tipoManifestacao = $tipoManifestacao;

        return $this;
    }

    /**
     * Obtem o tipo de manifestação
     *
     * @return string
     */
    public function getTipoManifestacao()
    {
        return $this->tipoManifestacao;
    }
    
}