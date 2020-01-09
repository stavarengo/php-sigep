<?php
namespace PhpSigep\Model;

use PhpSigep\InvalidArgument;

/**
 * @author: Stavarengo
 */
class AccessData extends AbstractModel
{

    /**
     * @var string
     */
    protected $codAdministrativo;
    /**
     * @var string
     */
    protected $usuario;
    /**
     * @var string
     */
    protected $senha;
    /**
     * @var string
     */
    protected $idCorreiosUsuario;
    /**
     * @var string
     */
    protected $idCorreiosSenha;
    /**
     * @var string
     */
    protected $cartaoPostagem;
    /**
     * @var string
     */
    protected $cnpjEmpresa;
    /**
     * @var string
     */
    protected $numeroContrato;
    /**
     * @var int
     */
    protected $anoContrato;
    /**
     * @var Diretoria
     */
    protected $diretoria;

    /**
     * @return string
     */
    public function getCodAdministrativo()
    {
        return $this->codAdministrativo;
    }

    /**
     * @param string $codAdministrativo
     */
    public function setCodAdministrativo($codAdministrativo)
    {
        $this->codAdministrativo = $codAdministrativo;
    }

    /**
     * @return string
     */
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    /**
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param string $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getCartaoPostagem()
    {
        
        return $this->cartaoPostagem;
    }

    /**
     * @param string $cartaoPostagem
     */
    public function setCartaoPostagem($cartaoPostagem)
    {
        $this->cartaoPostagem = $cartaoPostagem;
    }

    /**
     * @return string
     */
    public function getCnpjEmpresa()
    {
        return $this->cnpjEmpresa;
    }

    /**
     * @param string $cnpjEmpresa
     */
    public function setCnpjEmpresa($cnpjEmpresa)
    {
        $this->cnpjEmpresa = $cnpjEmpresa;
    }

    /**
     * @return string
     */
    public function getNumeroContrato()
    {
        return $this->numeroContrato;
    }

    /**
     * @param string $numeroContrato
     */
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;
    }

    /**
     * @return int
     */
    public function getAnoContrato()
    {
        return $this->anoContrato;
    }

    /**
     * @param int $anoContrato
     */
    public function setAnoContrato($anoContrato)
    {
        $this->anoContrato = $anoContrato;
    }

    /**
     * @return \PhpSigep\Model\Diretoria
     */
    public function getDiretoria()
    {
        return $this->diretoria;
    }

    /**
     * @param \PhpSigep\Model\Diretoria|int $diretoria
     * @throws InvalidArgument
     */
    public function setDiretoria($diretoria)
    {
        if (is_int($diretoria)) {
            $diretoria = new Diretoria($diretoria);
        }
        if (!($diretoria instanceof Diretoria)) {
            throw new InvalidArgument('A Diretoria deve ser ser uma instância de \PhpSigep\Model\Diretoria.');
        }
        $this->diretoria = $diretoria;
    }

    /**
     * @return string
     */
    public function getIdCorreiosUsuario()
    {
        return $this->idCorreiosUsuario;
    }

    /**
     * @param string $idCorreiosUsuario
     */
    public function setIdCorreiosUsuario($idCorreiosUsuario)
    {
        $this->idCorreiosUsuario = $idCorreiosUsuario;
    }

    /**
     * @return string
     */
    public function getIdCorreiosSenha()
    {
        return $this->idCorreiosSenha;
    }

    /**
     * @param string $idCorreiosSenha
     */
    public function setIdCorreiosSenha($idCorreiosSenha)
    {
        $this->idCorreiosSenha = $idCorreiosSenha;
    }
}