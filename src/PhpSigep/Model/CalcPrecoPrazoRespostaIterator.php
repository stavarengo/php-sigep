<?php
namespace PhpSigep\Model;

/**
 * @author: Stavarengo
 *
 * method \PhpSigep\Model\CalcPrecoPrazoResposta offsetGet(int $index)
 * method offsetSet(int $index, \PhpSigep\Model\CalcPrecoPrazoResposta $newval)
 * method append(\PhpSigep\Model\CalcPrecoPrazoResposta $value)
 * method \PhpSigep\Model\CalcPrecoPrazoResposta[] exchangeArray(\PhpSigep\Model\CalcPrecoPrazoResposta[] $input)
 */
class CalcPrecoPrazoRespostaIterator extends \ArrayObject
{
    protected $todosTemErro = null;

    public function __construct(array $itens)
    {
        parent::__construct($itens);
    }

    public function todosTemErro()
    {
        if ($this->todosTemErro === null) {
            $this->todosTemErro = $this->count() > 0;
            /** @var $item \PhpSigep\Model\CalcPrecoPrazoResposta */
            foreach ($this as $item) {
                if (!$item->hasError()) {
                    $this->todosTemErro = false;
                    break;
                }
            }
        }

        return $this->todosTemErro;
    }
}
