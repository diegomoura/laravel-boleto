<?php
namespace Diegomoura\LaravelBoleto\Contracts\Boleto;

interface Render
{
    public function getImagemCodigoDeBarras($codigo_barras);

    public function gerarBoletoHtml();

    public function gerarBoletoPdf($dest = self::OUTPUT_STANDARD, $save_path = null);
}
