@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-3">
                    Processos
                </div>

                <div class="col-md-9">
                    <a href="#" class="btn btn-primary pull-right" onclick="f_editar()">editar</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <script>
                function f_editar(){
                    $('form *').removeAttr('readonly').removeAttr('disabled');
                }
            </script>

            <form action="{{ route('processos.store') }}" method="POST">
                {{ csrf_field() }}

                <input name="id" type="hidden" value="{{$processo->id}}">

                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero_judicial">Número Judicial</label>
                            <input value="{{$processo->numero_judicial}}"
                                   name="numero_judicial"
                                   class="form-control"
                                   readonly="true"
                                   id="numero_judicial"
                                   placeholder="Número Judicial"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="numero_alerj">Número Alerj</label>
                            <input value="{{$processo->numero_alerj}}" name="numero_alerj" class="form-control" readonly="true" id="numero_alerj" placeholder="Número Alerj">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tribunal_id">Tribunal</label>
                            <select name="tribunal_id" class="js-example-basic-single form-control" disabled="disabled" id="tribunal_id">
                                @foreach ($tribunais as $key => $tribunal)
                                    @if($processo->tribunal->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $tribunal }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $tribunal }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="vara">Vara</label>
                            <input value="{{$processo->vara}}" name="vara" class="form-control" readonly="readonly" id="vara" placeholder="Digite a Vara">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="data_distribuicao">Data Distribuição</label>
                            <input value="{{$processo->data_distribuicao}}" type="date" name="data_distribuicao" class="form-control" id="data_distribuicao" readonly="readonly" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="acao_id">Ação</label>
                            <select name="acao_id" class="js-example-basic-single form-control" disabled="disabled" id="acao_id">
                                @foreach ($acoes as $key => $acao)
                                    <option value="{{ $key }}">{{ $acao }}</option>
                                    @if($processo->acao->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $acao }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $acao }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="relator_id">Relator</label>
                            <select name="relator_id" class="js-example-basic-single form-control" disabled="disabled" id="relator_id">
                                @foreach ($juizes as $key => $relator)
                                    @if($processo->relator->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $relator }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $relator }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="juiz_id">Juiz</label>
                            <select name="juiz_id" class="js-example-basic-single form-control" disabled="disabled" id="juiz_id">
                                @foreach ($juizes as $key => $juiz)
                                    @if($processo->juiz->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $juiz }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $juiz }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="autor">Autor</label>
                            <input value="{{$processo->autor}}" name="autor" type="text" class="form-control" readonly="true" id="autor" placeholder="Informe o Autor">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="reu">Réu</label>
                            <input value="{{$processo->reu}}" name="reu" type="text" class="form-control" readonly="true" id="reu" placeholder="Informe o Réu">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="procurador_id">Procurador</label>
                            <select name="procurador_id" class="js-example-basic-single form-control" disabled="disabled" id="procurador_id">
                                @foreach ($usuarios as $key => $procurador)
                                    @if($processo->procurador->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $procurador }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $procurador }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="estagiario_id">Estagiario</label>
                            <select name="estagiario_id" class="js-example-basic-single form-control" disabled="disabled" id="estagiario_id">
                                @foreach ($usuarios as $key => $estagiario)
                                    @if($processo->estagiario->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $estagiario }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $estagiario }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="assessor_id">Assessor</label>
                            <select name="assessor_id" class="js-example-basic-single form-control" disabled="disabled" id="assessor_id">
                                @foreach ($usuarios as $key => $assessor)
                                    @if($processo->assessor->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $assessor }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $assessor }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tipo_meio">Meio</label>
                            <select name="tipo_meio" class="js-example-basic-single form-control" disabled="disabled" id="tipo_meio">
                                @foreach ($meios as $key => $meio)
                                    @if($processo->tipoMeio->id == $key)
                                        <option value="{{ $key }}" selected="selected">{{ $meio }}</option>
                                    @else
                                        <option value="{{ $key }}">{{ $meio }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="objeto">Objeto</label>
                            <textarea name="objeto" class="form-control" readonly="true" id="objeto" placeholder="Informe o Objeto">{{$processo->objeto}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="merito">Mérito</label>
                            <textarea name="merito" class="form-control" readonly="true" id="merito" placeholder="Informe o Mérito">{{$processo->merito}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="liminar">Liminar</label>
                            <textarea name="liminar" class="form-control" readonly="true" id="liminar" placeholder="Informe o Liminar">{{$processo->liminar}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="apensos_obs">Apensos</label>
                            <textarea name="apensos_obs" class="form-control" readonly="true" id="apensos_obs" placeholder="Informe os Apensos">{{$processo->apensos_obs}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="recurso">Recurso</label>
                            <textarea name="recurso" class="form-control" readonly="true" id="recurso" placeholder="Informe o Recurso">{{$processo->recurso}}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success pull-right" disabled="true">Gravar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
