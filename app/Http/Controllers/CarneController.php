<?php

namespace App\Http\Controllers;

use App\Models\Carne;
use App\Models\Parcela;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CarneController extends Controller
{
    public function criarCarne(Request $request)
    {
        $validatedData = $request->validate([
            'valor_total' => 'required|numeric',
            'qtd_parcelas' => 'required|integer|min:1',
            'data_primeiro_vencimento' => 'required|date',
            'periodicidade' => 'required|in:mensal,semanal',
            'valor_entrada' => 'nullable|numeric',
        ]);

        // Criar o carnê
        $carne = Carne::create($validatedData);

        // Calcular as parcelas
        $this->gerarParcelas($carne);

        // Retornar a resposta com o carnê e as parcelas
        return response()->json($carne->load('parcelas'));
    }

    private function gerarParcelas(Carne $carne)
    {
        $valor_restante = $carne->valor_total - ($carne->valor_entrada ?? 0);
        $valor_parcela = round($valor_restante / $carne->qtd_parcelas, 2);

        $data_vencimento = Carbon::parse($carne->data_primeiro_vencimento);

        if ($carne->valor_entrada) {
            $carne->parcelas()->create([
                'data_vencimento' => $data_vencimento,
                'valor' => $carne->valor_entrada,
                'numero' => 1,
                'entrada' => true,
            ]);
            $data_vencimento = $this->proximaData($data_vencimento, $carne->periodicidade);
        }

        for ($i = 1; $i <= $carne->qtd_parcelas; $i++) {
            $carne->parcelas()->create([
                'data_vencimento' => $data_vencimento,
                'valor' => $valor_parcela,
                'numero' => $carne->valor_entrada ? $i + 1 : $i,
            ]);
            $data_vencimento = $this->proximaData($data_vencimento, $carne->periodicidade);
        }
    }

    private function proximaData($data, $periodicidade)
    {
        return $periodicidade === 'mensal' ? $data->addMonth() : $data->addWeek();
    }

    public function recuperarParcelas($id)
    {
        $parcelas = Parcela::where('carne_id', $id)->get();

        return response()->json($parcelas);
    }
}
