<?php
/**
 * Created by PhpStorm.
 * User: afdsilva
 * Date: 23/01/2018
 * Time: 11:11.
 */

namespace App\Http\Controllers;

use App\Data\Models\Processo;
use App\Data\Repositories\TiposJuizes;
use App\Data\Repositories\Acoes;
use App\Data\Repositories\Meios;
use App\Data\Repositories\Juizes;
use App\Data\Repositories\Tribunais;
use App\Data\Repositories\Processos;
use App\Data\Repositories\Andamentos;
use App\Data\Repositories\Users;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class Excel extends Controller
{
    public function importExport()
    {
        return view('excel.importExport');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importExcel()
    {
        if (Input::hasFile('import_file')) {
            $data = Cache::remember('importExcel', 30, function () {
                $path = Input::file('import_file')->getRealPath();

                return \Maatwebsite\Excel\Facades\Excel::load($path, function ($reader) {
                })->get();
            });

            if (!empty($data) && $data->count()) {
                foreach ($data[0] as $key => $value) {
                    $tribunal = null;
                    $vara = null;
                    if (!is_null($value->origem)) {
                        list($tribunal, $vara) = $this->split($value);
                    }
                    $tribunal =
                            app(Tribunais::class)
                                    ->firstOrCreate(['nome' => trim($tribunal) ?: 'N/C']);
                    // Nome e Abreviação receberam os mesmos dados , já que ora esta abreviado e ora esta 'nomeado'
                    $acao =
                            app(Acoes::class)
                                    ->firstOrCreate([
                                            'nome' => trim($value->acao) ?: 'N/C',
                                            'abreviacao' => trim($value->acao) ?: 'N/C',
                                    ]);

                    //Tipo_Relator → (juiz, Ministro, Desembargador, N/C)
                    $tipo_relator = strtolower(trim($value->relator));

                    if (starts_with($tipo_relator, 'mi')) {
                        $tipo_relator = "Ministro";
                    } elseif (starts_with($tipo_relator, 'de')) {
                        $tipo_relator = 'Desembargador';
                    } elseif (starts_with($tipo_relator, 'ju')) {
                        $tipo_relator = 'Juiz';
                    } else {
                        $tipo_relator = 'N/C';
                    }

                    $tipo_relator =
                            app(TiposJuizes::class)
                                    ->firstOrCreate(['nome' => $tipo_relator]);

                    $relator_juiz =
                            app(Juizes::class)
                                    ->firstOrCreate([
                                            'nome' => trim($value->relator),
                                            'lotacao_id' => $tribunal->id,
                                            'tipo_juiz_id' => $tipo_relator->id
                                    ]);
//                    $procurador =
//                            app(Users::class)
//                                    ->firstOrCreate(['name' => trim($value->procurador), 'username' => 'N/C - ', 'email' => 'N/C', 'password' => 'N/C']); //TODO => 'N/C'
//                    $estagiario =
//                            app(Users::class)
//                                    ->firstOrCreate(['name' => trim($value->estagiario), 'username' => 'N/C', 'email' => 'N/C', 'password' => 'N/C']);//TODO => 'N/C'
//                    $assessor =
//                            app(Users::class)
//                                    ->firstOrCreate(['name' => trim($value->assessor), 'username' => 'N/C', 'email' => 'N/C', 'password' => 'N/C']);//TODO => 'N/C'

                    $tipo_meio = strtolower(trim($value->relator));

                    if (starts_with($tipo_meio, 'F')) {
                        $tipo_meio = "Físico";
                    } elseif (starts_with($tipo_meio, 'E')) {
                        $tipo_meio = 'Eletrônico';
                    } else {
                        $tipo_meio = 'N/C';
                    }

                    $tipo_meio =
                            app(Meios::class)
                                    ->firstOrCreate(['nome' => trim($tipo_meio)]);//TODO => 'N/C'
                    $insert[] =
                            [
                                    'numero_judicial' => $value->no_judicial,
                                    'numero_alerj' => $value->no_alerj,
                                    'apensos_obs' => $value->apensos,
                                    'tribunal_id' => $tribunal->id, //Origem
                                    'vara' => trim($vara),
                                    'acao_id' => $acao->id,
                                    'relator_id' => $relator_juiz->id,
                                //'tipo_juiz_id' => $tipo_relator->id, //Tipo_Relator → (juiz, Ministro, Desembargador, N/C)
                                    'autor' => $value->autor,
                                    'reu' => $value->reu,
                                    'objeto' => $value->objeto,
                                    'merito' => $value->merito,
                                    'liminar' => $value->liminar,
                                    'recurso' => $value->recurso,
//                                'procurador_id'     => $procurador,
//                                'estagiario_id'     => $estagiario,
//                                'assessor_id'       => $assessor,
                            'tipo_meio_id' => $tipo_meio->id,
                    ];
                }
                if (!empty($insert)) {
                    Processo::insert($insert);
                    dd('Insert Record successfully.');
                }
            }
        }

        return back();
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function split($value): array
    {
        $split = explode('-', $value->origem);
        $tribunal = isset($split[0]) ? trim($split[0]) : null;
        $vara = isset($split[1]) ? trim($split[1]) : null;
        return [$tribunal, $vara];
    }
}
