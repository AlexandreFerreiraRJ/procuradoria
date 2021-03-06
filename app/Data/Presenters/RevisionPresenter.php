<?php

namespace App\Data\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

class RevisionPresenter extends BasePresenter
{
    /**
     * @var array
     */
    protected $routes = [
        'App\Data\Models\Tribunal' => 'tribunais.show',
        'App\Data\Models\Acao' => 'acoes.show',
        'App\Data\Models\Juiz' => 'juizes.show',
        'App\Data\Models\Processo' => 'processos.show',
        'App\Data\Models\User' => 'users.show',
        'App\Data\Models\Andamento' => 'andamentos.show',
    ];

    /**
     * @return mixed
     */
    private function getRouteName()
    {
        if (!isset($this->routes[$this->wrappedObject->revisionable_type])) {
            return;
        }

        return $this->routes[$this->wrappedObject->revisionable_type];
    }

    /**
     * @return mixed
     */
    public function revisionable()
    {
        $parts = explode('\\', $this->wrappedObject->revisionable_type);

        return end($parts);
    }

    /**
     * @return string|void
     */
    public function link()
    {
        if (is_null($routeName = $this->getRouteName())) {
            return;
        }

        return route($routeName, $this->wrappedObject->revisionable_id);
    }
}
