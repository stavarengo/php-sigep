<?php
namespace Sigep\Model;

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
	protected $cartaoPostagem;
	/**
	 * @var string
	 */
	protected $cnpjEmpresa;

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
	public function getCodAdministrativo()
	{
		return $this->codAdministrativo;
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
	public function getSenha()
	{
		return $this->senha;
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
	public function getUsuario()
	{
		return $this->usuario;
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
	public function getCartaoPostagem()
	{
		return $this->cartaoPostagem;
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
	public function getCnpjEmpresa()
	{
		return $this->cnpjEmpresa;
	}
	
}