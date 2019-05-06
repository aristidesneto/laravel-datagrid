<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\DataGrid\DataGrid;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $category;
    protected $post;

    /**
     * @var DataGrid
     */
    private $dataGrid;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DataGrid $dataGrid, Category $category, Post $post)
    {
        $this->middleware('auth');

        $this->dataGrid = $dataGrid;
        $this->category = $category;
        $this->post = $post;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $this->dataGrid
                ->model($this->post)
                ->addColumn('Imagem', 'image', false, 130)
                ->addColumn('Título', 'title')
                //->addColumn('Categoria', 'category_id')
                // Pretendo passar desse jeito quando tiver relacionamento
                ->addColumn('Categoria', 'category.name')
                ->addColumn('Data', 'created_at')
                ->addFilter('title')
                ->addFilter('category.name')
                ->addEditAction('home.edit')
                ->addDeleteAction('home.destroy')
                ->search(['category_id']);

        return view('home', [
            'dataGrid' => $this->dataGrid
        ]);
    }

    // Edit
    public function edit($id)
    {
        echo "Editando: $id";
    }

    // Delete
    public function destroy($id)
    {
        echo "Deletando: $id";
    }
}
