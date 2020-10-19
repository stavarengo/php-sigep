<?php

namespace PhpSigep\Model;

/**
 * Class PedidoInformacaoResponse
 *
 * @package PhpSigep\Model
 * @author: Bruno Maia <email:brunopmaia@gmail.com>
 */
class PedidoInformacaoResponse extends AbstractModel
{

    /**
     * @var string Número do Lote (Controle)
     */
    protected $numeroLote;

    /**
     * @var string Data do Registro da manifestação
     */
    protected $dataHoraCadastro;

    /**
     * @var string Código da Etiqueta/ Código Rastreamento
     */
    protected $codigoObjeto;

    /**
     * @var string Número de Protocolo da Manifestação
     */
    protected $numeroPi;

    /**
     * @var string Código de Retorno
     */
    protected $codigoRetorno;

    /**
     * @var string Descrição do Retono
     */
    protected $descricaoRetorno;

    /**
     * @var array Resultado da consulta
     */
    protected $resultArray;

    /**
     * PedidoInformacaoResponse constructor.
     *
     * @param array $initialValues
     */
    public function __construct(array $initialValues = array())
    {
        $this->resultArray = $initialValues;

        parent::__construct($initialValues);
    }

    /**
     * Define o número do lote
     *
     * @param string $numeroLote
     * @return $this
     */
    public function setNumeroLote($numeroLote)
    {
        $this->numeroLote = $numeroLote;

        return $this;
    }

    /**
     * Obtem o número do lote
     *
     * @return string
     */
    public function getNumeroLote()
    {
        return $this->numeroLote;
    }

    /**
     * Define a data e hora de cadastro da manifestação
     *
     * @param string $dataHoraCadastro
     * @return $this
     */
    public function setDataHoraCadastro($dataHoraCadastro)
    {
        $this->dataHoraCadastro = $dataHoraCadastro;

        return $this;
    }

    /**
     * Obtem a data e hora de cadastro da manifestação
     *
     * @return string
     */
    public function getDataHoraCadastro()
    {
        return $this->dataHoraCadastro;
    }

    /**
     * Define o código do objeto
     *
     * @param string $codigoObjeto
     * @return $this;
     */
    public function setCodigoObjeto($codigoObjeto)
    {
        $this->codigoObjeto = $codigoObjeto;

        return $this;
    }

    /**
     * Obtem o código do objeto
     *
     * @return string
     */
    public function getCodigoObjeto()
    {
        return $this->codigoObjeto;
    }

    /**
     * Define o número do protocolo
     *
     * @param string $numeroPi
     * @return $this
     */
    public function setNumeroPi($numeroPi)
    {
        $this->numeroPi = $numeroPi;

        return $this;
    }

    /**
     * Obtem o número do protocolo
     *
     * @return string
     */
    public function getNumeroPi()
    {
        return $this->numeroPi;
    }

    /**
     * Define o código de retorno da consulta
     *
     * @param string $codigoRetorno
     * @return $this
     */
    public function setCodigoRetorno($codigoRetorno)
    {
        $this->codigoRetorno = $codigoRetorno;

        return $this;
    }

    /**
     * Obtem o código de retorno da consulta
     *
     * @return string
     */
    public function getCodigoRetorno()
    {
        return $this->codigoRetorno;
    }

    /**
     * Define a descrição do retorno da consulta
     *
     * @param string $descricaoRetorno
     * @return $this
     */
    public function setDescricaoRetorno($descricaoRetorno)
    {
        $this->descricaoRetorno = $descricaoRetorno;

        return $this;
    }

    /**
     * Obtem a descrição do retorno da consulta
     *
     * @return string
     */
    public function getDescricaoRetorno()
    {
        return $this->descricaoRetorno;
    }

}