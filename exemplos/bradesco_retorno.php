<?php
require 'autoload.php';
$retorno = \Diegomoura\LaravelBoleto\Cnab\Retorno\Factory::make(__DIR__ . DIRECTORY_SEPARATOR . 'arquivos' . DIRECTORY_SEPARATOR . 'bradesco.ret');
$retorno->processar();

echo $retorno->getBancoNome();
dd($retorno->getDetalhes());
