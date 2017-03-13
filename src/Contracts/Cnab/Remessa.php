<?php
namespace Diegomoura\LaravelBoleto\Contracts\Cnab;

interface Remessa extends Cnab
{
    public function gerar();
}
