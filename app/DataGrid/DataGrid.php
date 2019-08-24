<?php

namespace App\DataGrid;

use Illuminate\Database\Eloquent\Builder;

class DataGrid
{
    /**
     * Instancia singleton
     *
     * @var DataGrid
     */
    // private static $instance = null;

    /**
     * Arrays de Inicialização
     */
    private $rows = [];
    private $columns = [];
    private $actions = [];
    private $filters = [];
    private $defaultOrder = [];

    /**
     * @var Builder
     */
    private $model = null;
    private $modelOrigin = null;

    /**
     * Número de registros por página
     *
     * @var integer
     */
    private $perPage = 10;


    /** Métodos GET **/

     /**
     * Retorna as colunas da tabela
     *
     * @param string $columns
     * @return void
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Retorna a ordenação padrão
     *
     * @return void
     */
    public function getDefaultOrder()
    {
        return $this->defaultOrder;
    }

    /**
     * Seta a ordenação padrão das consultas
     *
     * @param string $name
     * @param string $order
     * @return void
     */
    public function defaultOrder($name, $order = 'desc')
    {
        $this->defaultOrder = [
            'name' => $name,
            'order' => $order
        ];

        return $this;
    }


    /**
     * Retorna a quantidade de registros por página
     *
     * @param integer $perPage
     * @return void
     */
    public function paginate($perPage = null)
    {
        $this->perPage = $perPage == null ? $this->perPage : $perPage;

        return $this;
    }

    /**
     * Retorna uma instancia do Model
     *
     * @param string|object $model
     * @return void
     */
    public function model($model = null)
    {
        if ($model == null) {
            return $this->model;
        }

        $this->model = is_object($model) == null ? new $model : $model;

        // Clone do Model do Eloquent
        $this->modelOrigin = clone $this->model;

        return $this;
    }

    /**
     * Retorma os filtros
     *
     * @param string $name
     * @param string $operator
     * @return void
     */
    public function addFilter($name, $operator = 'like')
    {
        $this->filters[] = [
            'name' => $name,
            'operator' => $operator
        ];

        return $this;
    }
    
    /**
     * Retorna as linhas da tabela
     *
     * @return void
     */
    public function rows()
    {
        return $this->rows;
    }


    /**
     * Retorma as colunas da tabela
     *
     * @param string $label
     * @param string $name
     * @return void
     */
    public function addColumn($label, $name, $order = true, $width = null)
    {    
        $this->columns[] = [
            'label' => $label,
            'name' => $name,
            'order' => $order,
            'width' => $width
        ];

        return $this;
    }

    /**
     * Retorna o array da ação
     *
     * @return void
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * Inicializa um array com os parametros para a ação
     *
     * @param string $label
     * @param string $route
     * @param string $view
     * @return void
     */
    public function addAction($label, $route, $view)
    {
        $this->actions[] = [
            'label' => $label,
            'route' => $route,
            'view' => $view
        ];

        return $this;
    }

    /**
     * Retorna a ação de Editar
     *
     * @param string $route
     * @param string $view
     * @return void
     */
    public function addEditAction($route, $view = null)
    {
        $view = $view == null ? 'forms.edit_action' : $view;

        $this->addAction('Editar', $route, $view);

        return $this;
    }


    /**
     * Retorna a ação de Remover
     *
     * @param string $route
     * @param string $view
     * @return void
     */
    public function addDeleteAction($route, $view = null)
    {
        $view = $view == null ? 'forms.delete_action' : $view;

        $this->addAction('Remover', $route, $view);

        return $this;
    }

    /**
     * Retorna a busca dos registros
     *
     * @return void
     */
    public function search($additionalColumns = [])
    {
        // Obtem a chave primária do Model (normalmente o ID)
        $keyName = $this->modelOrigin->getKeyName();

        // Obtem apenas as colunas com indice igual a Name
        // para poder realizar o select das colunas informadas
        $columns = collect($this->getColumns())->pluck('name');

        // Adiciona a variavel keyName para inserir a primary key no inicio do Array
        
        $columnsWithoutDot = $columns->filter(function($value){
            return strpos($value, ".") === false;
        })->toArray();

        $columnsWithDot = $columns->diff($columnsWithoutDot);
        
        $relations = $columnsWithDot->map(function($value){
            return explode('.',$value)[0];
        })->toArray();
        
        array_unshift($columnsWithoutDot, $keyName);

        // Aplica Filtros
        $this->applyFilters();

        // Aplica Ordenação
        $this->applyOrders();

        $this->rows = $this->model
                ->with($relations)
                ->paginate($this->perPage, array_merge($columnsWithoutDot, $additionalColumns));

                // dd($this->rows);

        // dd($this->model->find(2)->toArray());
        
        // foreach ($columns as $column) {   
        //     if (strpos($column, '.')) {
        //         list($relation, $field) = explode('.', $column);

        //         $this->rows = $this->model->orWhereHas($relation, function($query) use ($field) {
        //             $query->paginate($this->perPage, $field);
        //         });

        //         dd($this->rows, $relation, $field);
                                
        //     } 
        // }


        // dd($columns);

        // Obtem as colunas informadas
        // foreach ($columns as $key => $column) {               
        //     if (strpos($column, '.')) {

        //         $array = [$key => $column];
        //         // list($relation, $field) = explode('.', $column);
        //         // Entrada: category.name
        //         // Saida: $relation = category e $field = name

        //         // $this->rows = $this->model->orWhereHas($relation, function($query) use ($field) {
        //         //     $query->paginate($this->perPage, $field);
        //         // });

        //     } else {                
        //         // Continua aqui para campos sem relacionamento
        //         // $this->rows = $this->model->paginate($this->perPage, $columns);
        //     }
        // }

        // Remove campo com (.)
        // dd(array_diff($columns, $array));       
        
        // $this->rows = $this->model->with('category')->paginate($this->perPage, $columns); // function($query) use ($columns) {
            // $query->paginate($this->perPage, $columns);
            // $query->where('id', 2);
        // });
        // $this->rows = $this->model->with('category')->get(array_diff($columns, $array));    
        
        // dd($this->rows);

        // $this->rows = $this->model->paginate($this->perPage, $columns);

        return $this;
    }

    /**
     * Retorna os registros filtrados pelo usuário
     *
     * @return void
     */
    protected function applyFilters()
    {
        foreach ($this->filters as $filter) {
            $field = $filter['name']; // Nome do campo que será feito a busca
            $operator = $filter['operator']; // Operador de busca - LIKE
            $search = \Request::get('search'); // Variavel enviada via GET
            $search = strtolower($operator) === 'like' ? "%$search%" : $search;

            // Verifica se existe uma busca por um campo que possui relacionamento
            if (strpos($filter['name'], '.')) {
                list($relation, $field) = explode('.', $filter['name']);
                
                $this->model = $this->model->orWhereHas($relation, function($query) use ($field, $operator, $search) {
                    $query->where($field, $operator, $search);
                });
            } else {
                $this->model = $this->model->orWhere($field, $operator, $search);
            }

        }
    }

   

    /**
     * Retorna a busca ordenada
     *
     * @return void
     */
    protected function applyOrders()
    {
        // Ordenação via GET
        $fieldOrderParam = \Request::get('field_order');
        $orderParam = \Request::get('order') == 'desc' ? 'desc' : 'asc';

        // Retorna a ordenação default caso não for informado nada
        if (!$this->getDefaultOrder() && $fieldOrderParam == null) {
            $this->model->orderBy('id', 'desc');
        }

        // Retorna a ordenação default informada no controller
        if ($this->getDefaultOrder() && $fieldOrderParam == null) {
            $fieldOrderParam = $this->getDefaultOrder()['name'];
            $orderParam = $this->getDefaultOrder()['order'] == 'desc' ? 'desc' : 'asc';

            $this->model->orderBy($fieldOrderParam, $orderParam);
        }

        foreach ($this->getColumns() as $key => $column) {
            // Ordenação via GET
            if ($column['name'] === $fieldOrderParam && $column['order'] != false) {

                $this->columns[$key]['_order'] = $orderParam;
                $this->model->orderBy($column['name'], $orderParam);
                
            } elseif ($column['order'] != false) {
                $this->columns[$key]['_order'] = $column['order'];
                
                if ($column['order'] === 'asc' || $column['order'] === 'desc') {
                    $this->model->orderBy($column['name'], $column['order']);
                }
            }
        }

        
    }

}