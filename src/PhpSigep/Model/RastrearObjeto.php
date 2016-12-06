<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 * @author: davidalves1
 */
class RastrearObjeto extends AbstractModel
{
    /**
     * O WebService retonará o rastreamento de cada objeto consultado.
     */
    const TIPO_LISTA_DE_OBJETOS = 1;
    /**
     * O WebService retornará o rastreamento de todos os objetos dentro do intervalo consultado.
     */
    const TIPO_INTERVALO_DE_OBJETOS = 2;

    /**
     * O WebService vai retornar todos os eventos dos objetos consultados. 
     */
    const TIPO_RESULTADO_TODOS_OS_EVENTOS = 1;
    /**
     * O WebService vai retornar apenas o último evento dos objetos consultados. 
     */
    const TIPO_RESULTADO_APENAS_O_ULTIMO_EVENTO = 2;

    /**
     * Na resposta traz os resultados com erro
     */
    const EXIBIR_RESULTADOS_COM_ERRO = true;

    /**
     * Na resposta não traz os resultados com erro
     */
    const ESCONDER_RESULTADOS_COM_ERRO = false;

    /**
     * Exibe as informações em Português do Brasil
     */
    const IDIOMA_PT_BR = '101';

    /**
     * Exibe as informações em Inglês
     */
    const IDIOMA_EN = '102';

    /**
     * @var AccessData
     */
    protected $accessData;

    /**
     * As etiquetas das encomendas que serão consultadas.
     * @var Etiqueta[]
     */
    protected $etiquetas;

    /**
     * Definição de como a lista de identificadores de objetos deverá ser interpretada pelo servidor SRO.
     * @var int
     */
    protected $tipo = self::TIPO_LISTA_DE_OBJETOS;

    /**
     * Define se vai retornar todos os eventos de rastreamento ou apenas o ultimo evento de cada objeto.
     * @var int
     */
    protected $tipoResultado = self::TIPO_RESULTADO_TODOS_OS_EVENTOS;

    /**
     * Define o idioma no qual as informações serão exibidas
     * @var string
     */
    protected $idioma = self::IDIOMA_PT_BR;

    /**
     * Informa se no retorno deve trazer os resultados com erro ou não
     * @var bool
     */
    protected $exibirErros =  self::ESCONDER_RESULTADOS_COM_ERRO;

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
     * @return \PhpSigep\Model\Etiqueta[]
     */
    public function getEtiquetas()
    {
        return $this->etiquetas;
    }

    /**
     * @param \PhpSigep\Model\Etiqueta[] $etiquetas
     * @return $this;
     */
    public function setEtiquetas(array $etiquetas)
    {
        $this->etiquetas = $etiquetas;

        return $this;
    }

    /**
     * @param \PhpSigep\Model\Etiqueta $etiqueta
     * @return $this;
     */
    public function addEtiqueta(Etiqueta $etiqueta)
    {
        if (!is_array($this->etiquetas)) {
            $this->etiquetas = array();
        }
        $this->etiquetas[] = $etiqueta;

        return $this;
    }

    /**
     * @param int $tipo
     * @return $this;
     */
    public function setTipo(int $tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipoResultado
     * @return $this;
     */
    public function setTipoResultado($tipoResultado)
    {
        $this->tipoResultado = $tipoResultado;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoResultado()
    {
        return $this->tipoResultado;
    }

    /**
     * @param string $idioma
     * @return $this
     */
    public function setIdioma(string $idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * @param bool $exibirErros
     * @return $this
     */
    public function setExibirErros($exibirErros)
    {
        $this->exibirErros = $exibirErros;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExibirErros()
    {
        return $this->exibirErros;
    }
    
}
