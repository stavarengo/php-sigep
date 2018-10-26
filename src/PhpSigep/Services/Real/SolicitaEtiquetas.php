<?php
namespace PhpSigep\Services\Real;

use PhpSigep\Model\AbstractModel;
use PhpSigep\Model\Etiqueta;
use PhpSigep\Services\Exception;
use PhpSigep\Services\Result;

/**
 * @author: Stavarengo
 */
class SolicitaEtiquetas implements RealServiceInterface
{

    private function validAfterRequest($request){
        if (class_exists('\StaLib_Logger',false)) {
            \StaLib_Logger::log('Retorno SIGEP solicitar etiquetas: ' . print_r($request, true));
        }
        
        if ($request && is_object($request) && isset($request->return) && !($request instanceof \SoapFault)) {
            return true;
        } else {
            if ($request instanceof \SoapFault) {
                throw $request;
            }
            throw new \Exception('Não foi possível obter as etiquetas solicitadas. Retorno: "' . $request . '"');
        }
    }
    
    /**
     * Busca numero da etiqueta em uma unica requisição assim garantindo que os numeros das etiquetas não tenham intervalos
     * @param array $soapArgs
     * @param AbstractModel $params
     * @return Etiqueta[]
     */
    private function findOneRequest($soapArgs, $params) {

        $etiquetasReservadas = array();
        
        //Atribui a quantidade de etiquetas desejadas
        $soapArgs['qtdEtiquetas'] = $params->getQtdEtiquetas();

        $request = SoapClientFactory::getSoapClient()->solicitaEtiquetas($soapArgs);

        if($this->validAfterRequest($request)){

            $faixaEtiqueta = explode(',', $request->return);
            
            $numerosEtiqueta = $this->createNumberEtiquetasByRange($faixaEtiqueta[0], $faixaEtiqueta[1]);

            foreach ($numerosEtiqueta as $numeroEtiqueta){
                $etiqueta = new Etiqueta();
                $etiqueta->setEtiquetaSemDv($numeroEtiqueta);
                $etiquetasReservadas[] = $etiqueta;
            }

        }
        
        return $etiquetasReservadas;
        
    }
    
    /**
     * Busca numero da etiqueta em uma unica requisição assim garantindo que os numeros das etiquetas não tenham intervalos
     * @param array $soapArgs
     * @param AbstractModel $params
     * @return Etiqueta[]
     */
    private function findMultRequest($soapArgs, $params) {
        
        $etiquetasReservadas = array();
        
        for ($i = 0; $i < $params->getQtdEtiquetas(); $i++) {
            
            $request = SoapClientFactory::getSoapClient()->solicitaEtiquetas($soapArgs);
            
            if($this->validAfterRequest($request)){
                $request = explode(',', $request->return);

                $etiqueta = new Etiqueta();
                $etiqueta->setEtiquetaSemDv($request[0]);
                $etiquetasReservadas[] = $etiqueta;
            }
        }
        
        return $etiquetasReservadas;
        
    }

    /**
     * @param \PhpSigep\Model\AbstractModel|\PhpSigep\Model\SolicitaEtiquetas $params
     *
     * @throws \PhpSigep\Services\Exception
     * @throws InvalidArgument
     * @return Result<Etiqueta[]>
     */
    public function execute(AbstractModel $params)
    {
        if (!$params instanceof \PhpSigep\Model\SolicitaEtiquetas) {
            throw new InvalidArgument();
        }
        
        $soapArgs = array(
            'tipoDestinatario' => 'C',
            'identificador'    => $params->getAccessData()->getCnpjEmpresa(),
            'idServico'        => $params->getServicoDePostagem()->getIdServico(),
            'qtdEtiquetas'     => 1,
            'usuario'          => $params->getAccessData()->getUsuario(),
            'senha'            => $params->getAccessData()->getSenha(),
        );

        $result = new Result();
        
        try {
            if (!$params->getAccessData() || !$params->getAccessData()->getUsuario()
                || !$params->getAccessData()->getSenha()
            ) {
                throw new Exception('Para usar este serviço você precisa setar o nome de usuário e senha.');
            }

            if($params->isModoMultiplasRequisicoes()){
                $result->setResult($this->findMultRequest($soapArgs, $params));
            }else{
                $result->setResult($this->findOneRequest($soapArgs, $params));
            }
            
        } catch (\Exception $e) {
            if ($e instanceof \SoapFault) {
                $result->setIsSoapFault(true);
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg("Resposta do Correios: " . SoapClientFactory::convertEncoding($e->getMessage()));
            } else {
                $result->setErrorCode($e->getCode());
                $result->setErrorMsg($e->getMessage());
            }
        }

        return $result;
    }
    
    public function createNumberEtiquetasByRange($startingEtiqueta, $finalEtiqueta){
        
        $prefix = \substr($startingEtiqueta, 0, 2);
        $sufix  = \substr($startingEtiqueta, 11);
                
        $startingNumber = (int)$this->getNumbersEtiqueta($startingEtiqueta);
        $finalNumber    = (int)$this->getNumbersEtiqueta($finalEtiqueta);
        
        $numbersEtiquetas = array();
        
        for ($i = $startingNumber; $i <= $finalNumber; $i++){
            //Colocando 0 a esquerda para completar os 8 digitos 
            $inc = \str_pad($i, 8, '0', \STR_PAD_LEFT);
            $numbersEtiquetas[] = $prefix.$inc.' '.$sufix;
        }
        
        return $numbersEtiquetas;
        
    }
    
    private function getNumbersEtiqueta($etiqueta){
        return preg_replace('/\D/', '', $etiqueta);
    }
    
}
