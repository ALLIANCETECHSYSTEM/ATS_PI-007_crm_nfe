<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Devolucao;
use App\ConfigNota;

class Venda extends Model
{
    protected $fillable = [
        'cliente_id', 'usuario_id', 'frete_id', 'valor_total', 'forma_pagamento', 'NfNumero',
        'natureza_id', 'chave', 'path_xml', 'estado', 'observacao', 'desconto', 'transportadora_id', 'sequencia_cce', 'tipo_pagamento'
    ];

    public function duplicatas(){
        return $this->hasMany('App\ContaReceber', 'venda_id', 'id');
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function natureza(){
        return $this->belongsTo(NaturezaOperacao::class, 'natureza_id');
    }

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function frete(){
        return $this->belongsTo(Frete::class, 'frete_id');
    }

    public function transportadora(){
        return $this->belongsTo(Transportadora::class, 'transportadora_id');
    }

    public function itens(){
        return $this->hasMany('App\ItemVenda', 'venda_id', 'id');
    }

    public static function lastNF(){
        $venda = Venda::
        where('NfNumero', '!=', 0)
        ->orderBy('NfNumero', 'desc')
        ->first();

        $devolucao = Devolucao::
        where('nNf', '!=', 0)
        ->orderBy('nNf', 'desc')
        ->first();

        if($venda != null || $devolucao != null){
            $numeroConfig = ConfigNota::first()->ultimo_numero_nfe ?? 0;
            if($devolucao != null){
                if($numeroConfig > $venda->NfNumero && $numeroConfig > $devolucao->nNf){
                    return $numeroConfig;
                }
                if($venda->NfNumero > $devolucao->nNf) return $venda->NfNumero;
                else return $devolucao->nNf;
            }else{
                if($numeroConfig > $venda->NfNumero){
                    return $numeroConfig;
                }
                else return $venda->NfNumero;
            }
        }else{
            return ConfigNota::first()->ultimo_numero_nfe;
        }

    }


    public static function tiposPagamento(){
        return [
            '01' => 'Dinheiro',
            '02' => 'Cheque',
            '03' => 'Cartão de Crédito',
            '04' => 'Cartão de Débito',
            '05' => 'Crédito Loja',
            '10' => 'Vale Alimentação',
            '11' => 'Vale Refeição',
            '12' => 'Vale Presente',
            '13' => 'Vale Combustível',
            '14' => 'Duplicata Mercantil',
            '15' => 'Boleto Bancário',
            '90' => 'Sem pagamento',
            '99' => 'Outros',
        ];
    }


    public static function filtroData($dataInicial, $dataFinal, $estado){
        $c = Venda::
        select('vendas.*')
        ->whereBetween('data_registro', [$dataInicial, 
            $dataFinal])
        ->where('vendas.forma_pagamento', '!=', 'conta_crediario');

        if($estado != 'TODOS') $c->where('vendas.estado', $estado);
        
        return $c->get();
    }

    public static function filtroDataCliente($cliente, $dataInicial, $dataFinal, $estado){
        $c = Venda::
        select('vendas.*')
        ->join('clientes', 'clientes.id' , '=', 'vendas.cliente_id')
        ->where('clientes.razao_social', 'LIKE', "%$cliente%")
        ->where('vendas.forma_pagamento', '!=', 'conta_crediario')

        ->whereBetween('data_registro', [$dataInicial, 
            $dataFinal]);

        if($estado != 'TODOS') $c->where('vendas.estado', $estado);
        return $c->get();
    }

    public static function filtroCliente($cliente, $estado){
        $c = Venda::
        select('vendas.*')
        ->join('clientes', 'clientes.id' , '=', 'vendas.cliente_id')
        ->where('clientes.razao_social', 'LIKE', "%$cliente%")
        ->where('vendas.forma_pagamento', '!=', 'conta_crediario');

        if($estado != 'TODOS') $c->where('vendas.estado', $estado);
        
        return $c->get();
    }

    public static function filtroEstado($estado){
        $c = Venda::
        where('vendas.estado', $estado)
        ->where('vendas.forma_pagamento', '!=', 'conta_crediario');
        return $c->get();
    }

    public function getTipoPagamento(){
        foreach(Venda::tiposPagamento() as $key => $t){
            if($this->tipo_pagamento == $key) return $t;
        }
    }
}
