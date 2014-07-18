<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
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
     * @param mixed $tipoResultado
     * @return $this;
     */
    public function setTipoResultado(mixed $tipoResultado)
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
    
}
