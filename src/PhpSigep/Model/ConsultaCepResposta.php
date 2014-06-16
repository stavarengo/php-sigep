<?php
/**
 * prestashop Project ${PROJECT_URL}
 *
 * @link      ${GITHUB_URL} Source code
 */

namespace PhpSigep\Model;

class ConsultaCepResposta extends AbstractModel
{
    /**
     * @var string
     */
    protected $bairro;
    /**
     * @var string
     */
    protected $cep;
    /**
     * @var string
     */
    protected $cidade;
    /**
     * @var string
     */
    protected $complemento1;
    /**
     * @var string
     */
    protected $complemento2;
    /**
     * @var string
     */
    protected $endereco;
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $uf;

    /**
     * @param string $bairro
     * @return $this;
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param string $cep
     * @return $this;
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * @return string
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * @param string $cidade
     * @return $this;
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * @return string
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * @param string $complemento1
     * @return $this;
     */
    public function setComplemento1($complemento1)
    {
        $this->complemento1 = $complemento1;

        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento1()
    {
        return $this->complemento1;
    }

    /**
     * @param string $complemento2
     * @return $this;
     */
    public function setComplemento2($complemento2)
    {
        $this->complemento2 = $complemento2;

        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento2()
    {
        return $this->complemento2;
    }

    /**
     * @param string $endereco
     * @return $this;
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * @param int $id
     * @return $this;
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $uf
     * @return $this;
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }
   
} 