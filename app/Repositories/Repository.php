<?php
namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Container\Container as App;

abstract class Repository implements RepositoryInterface {
    private $app;
    protected $model;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof $model) {
            throw new Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model!");
        }

        return $this->model = $model;
    }

    public function all($columns = array('*'))
    {
        return $this->model->get($columns);
    }

    public function paginate($perPage = 15, $columns = array('*')) {
        return $this->model->paginate($perPage, $columns);
    }

    public function create($data)
    {
        // USE TRANSACTIONS
        return $this->model->create($data);
    }
}