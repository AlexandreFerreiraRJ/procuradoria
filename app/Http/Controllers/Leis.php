<?php

namespace App\Http\Controllers;

use App\Data\Models\Lei;
use App\Data\Repositories\Leis as LeisRepository;
use App\Http\Requests\Lei as LeiRequest;
use Illuminate\Http\Request;

class Leis extends Controller
{
    /**
     * @var LeisRepository
     */
    private $repository;

    public function __construct(LeisRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create($processo_id = null)
    {
        return view('leis.form')
                ->with('processo_id', $processo_id)
                ->with(['lei' => $this->repository->new()]);
    }

    public function store(LeiRequest $request, LeisRepository $repository)
    {
        $repository->createFromRequest($request);

        return redirect()->route('leis.index');
    }

    public function index(LeisRepository $leis, Request $request)
    {
        return view('leis.index')
            ->with('pesquisa', $request->get('pesquisa'))
            ->with('leis', $leis->search($request));
    }

    public function show($id)
    {
        return view('leis.form')
            ->with('formDisabled', true)
            ->with(['lei' => Lei::find($id)]);
    }
}
