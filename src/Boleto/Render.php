<?php
namespace Diegomoura\LaravelBoleto\Boleto;

use Diegomoura\LaravelBoleto\Contracts\Boleto\Boleto as BoletoContract;
use Diegomoura\LaravelBoleto\Contracts\Boleto\Render as RenderContract;

class Render implements RenderContract
{

    const OUTPUT_INLINE     = 'I';
    const OUTPUT_DOWNLOAD   = 'D';
    const OUTPUT_PATH       = 'P';
    const OUTPUT_STRING     = 'S';

    private $totalBoletos = 0;

    /**
     * @var BoletoContract[]
     */
    private $boleto = array();

    /**
     * @var bool
     */
    private $print = false;

    /**
     * @var bool
     */
    private $showInstrucoes = true;

    /**
     * Addiciona o boletos
     *
     * @param array $boletos
     *
     * @return $this
     */
    public function addBoletos(array $boletos)
    {
        foreach ($boletos as $boleto) {
            $this->addBoleto($boleto);
        }
        return $this;
    }

    /**
     * Addiciona o boleto
     *
     * @param BoletoContract $boleto
     *
     * @return $this
     */
    public function addBoleto(BoletoContract $boleto)
    {
        $dados = $boleto->toArray();
        $dados['codigo_barras'] = $this->getImagemCodigoDeBarras($dados['codigo_barras']);
        $this->boleto[] = $dados;
        return $this;
    }
    /**
     * @return $this
     */
    public function hideInstrucoes() {
        $this->showInstrucoes = false;
        return $this;
    }
    /**
     * @return $this
     */
    public function showPrint() {
        $this->print = true;
        return $this;
    }

    /**
     * Retorna a string contendo as imagens do código de barras, segundo o padrão Febraban
     *
     * @param $codigo_barras
     *
     * @return string
     */
    public function getImagemCodigoDeBarras($codigo_barras)
    {
        $codigo_barras = (strlen($codigo_barras)%2 != 0 ? '0' : '') . $codigo_barras;
        $barcodes = ['00110', '10001', '01001', '11000', '00101', '10100', '01100', '00011', '10010', '01010'];
        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1*10) + $f2;
                $texto = "";
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }
        
        // Guarda inicial
        $retorno = '<div class="barcode">' .
            '<div class="black thin"></div>' .
            '<div class="white thin"></div>' .
            '<div class="black thin"></div>' .
            '<div class="white thin"></div>';

        // Draw dos dados
        while (strlen($codigo_barras) > 0) {
            $i = round(substr($codigo_barras, 0, 2));
            $codigo_barras = substr($codigo_barras, strlen($codigo_barras) - (strlen($codigo_barras) - 2), strlen($codigo_barras) - 2);
            ;
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i += 2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = 'thin';
                } else {
                    $f1 = 'large';
                }
                $retorno .= "<div class='black {$f1}'></div>";
                if (substr($f, $i, 1) == "0") {
                    $f2 = 'thin';
                } else {
                    $f2 = 'large';
                }
                $retorno .= "<div class='white {$f2}'></div>";
            }
        }

        // Final
        return $retorno . '<div class="black large"></div>' .
        '<div class="white thin"></div>' .
        '<div class="black thin"></div>' .
        '</div>';
    }

    /**
     * função para gerar o boleto em html
     *
     * @return string
     * @throws \Throwable'
     */
    public function gerarBoletoHtml()
    {
        if (count($this->boleto) == 0) {
            throw new \Exception('Nenhum Boleto adicionado');
        }

        return view('boleto::boleto', [
            'boletos' => $this->boleto,
            'imprimir_carregamento' => (bool) $this->print,
            'mostrar_instrucoes' => (bool) $this->showInstrucoes,
        ])->render();
    }


    /**
     * função para gerar o boleto em pdf
     *
     * @param string $dest
     * @param null $save_path
     * @return mixed
     * @throws \Exception
     */
    public function gerarBoletoPdf($dest = self::OUTPUT_INLINE, $save_path = null)
    {
        if (count($this->boleto) == 0) {
            throw new \Exception('Nenhum Boleto adicionado');
        }

        $pdf = \PDF::loadView('boleto::boleto', [
            'boletos' => $this->boleto,
            'imprimir_carregamento' => (bool) $this->print,
            'mostrar_instrucoes' => (bool) $this->showInstrucoes,
        ]);

        if ($dest == self::OUTPUT_PATH) {
            return $pdf->save($save_path);
        }
        elseif ($dest == self::OUTPUT_DOWNLOAD) {
            return $pdf->download('Boleto - Grupo Lírios');
        }
        elseif ($dest == self::OUTPUT_STRING){
            return $pdf;
        }
        else{
            return $pdf->inline('Boleto - Grupo Lírios.pdf');
        }
    }
}
