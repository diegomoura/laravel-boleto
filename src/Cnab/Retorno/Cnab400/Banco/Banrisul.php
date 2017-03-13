<?php
namespace Diegomoura\LaravelBoleto\Cnab\Retorno\Cnab400\Banco;

use Diegomoura\LaravelBoleto\Cnab\Retorno\Cnab400\AbstractRetorno;
use Diegomoura\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;
use Diegomoura\LaravelBoleto\Contracts\Cnab\RetornoCnab400;
use Diegomoura\LaravelBoleto\Util;

class Banrisul extends AbstractRetorno implements RetornoCnab400
{
    /**
     * Código do banco
     *
     * @var string
     */
    protected $codigoBanco = BoletoContract::COD_BANCO_BANRISUL;



    protected function processarHeader(array $header)
    {
        return true;
    }

    protected function processarDetalhe(array $detalhe)
    {
        return true;
    }

    protected function processarTrailer(array $trailer)
    {
        return true;
    }
}
