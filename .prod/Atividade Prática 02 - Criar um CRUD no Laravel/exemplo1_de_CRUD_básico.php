<?php

php artisan make:migration create_products_table


Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name',200);
    $table->text('description');
    $table->double('value');
    $table->date('expiration_date');            
    $table->integer('stock');
    $table->timestamps();            
});

php artisan migrate


php artisan make:model Product
	
app/Models/Product -------------------------------

// código do model
class Product extends Model
{
    protected $fillable = ['name', 'description', 'value', 'expiration_date', 'stock'];
}
---------------------

php artisan make:controller ProductController --resource

app/Http/Controllers/ProductController.php ------------------------

use App\Models\Product;
//Código do products controller

 public function index()    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    



public function create()
    {
        return view('products.create');
    }

public function store(Request $request)
    {   
        // dd($request->all());
        Product::create($request->all());
        return redirect()->route('products.create');
    }
    
    
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }
    
    public function update(Request $request, Product $product)
    {   
        $product->update($request->all());

        return redirect()->route('products.create');
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
---------------------------

//criar a pasta resources/views/products


Rotas (routes/web.php)--------------------------------
Route::resource('products', ProductController::class);

resources/views/products/create.blade.php --------------------------------------

<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</head>
<body>
    <h1 class="my-4">Adicionar Autor</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div >
            <label for="name" >Nome do produto</label>
            <input type="text" id="name" name="name" required>            
        </div>
        <div >
            <label for="description" >Descrição do produto: </label>
            <input type="text" id="description" name="description" required>            
        </div>

        <div >
            <label for="value" >Valor do produto: </label>
            <input type="number" id="value" name="value" required>            
        </div>

        <div >
            <label for="expiration_date" >Validade: </label>
            <input type="date" id="expiration_date" name="expiration_date" required>            
        </div>

        <div >
            <label for="stock" >Estoque: </label>
            <input type="number" id="stock" name="stock" required>            
        </div>

        <button type="submit" class="btn btn-success">
            <i class="bi bi-save"></i> Salvar
        </button>
        
    </form>
</body>
</html>


resources/views/products/show.blade.php ----------------------------------
<html>
<head></head>
<body>
    
    <h1 >Detalhes do Produto</h1>        
        <div >
            <p><strong>ID:</strong> {{ $product->id }}</p>
            <p><strong>Nome:</strong> {{ $product->name }}</p>
            <p><strong>Descrição:</strong> {{ $product->description }}</p>
            <p><strong>Valor:</strong> {{ $product->value }}</p>
            <p><strong>Validade:</strong> {{ $product->expiration_date }}</p>
            <p><strong>Estoque:</strong> {{ $product->stock }}</p>
        </div>
    </div>    
</div>
</body>
</html>

resources/views/products/edit.blade.php ----------------------------------

<html>
<head></head>
<body>
    Editar Produto

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        
            
        <div >
            <label for="name" >Nome do produto</label>
            <input type="text" id="name" name="name" value="{{ $product->name }}" required>            
        </div>
        <div >
            <label for="description" >Descrição do produto: </label>
            <input type="text" id="description" name="description" value="{{ $product->description }}" required>            
        </div>

        <div >
            <label for="value" >Valor do produto: </label>
            <input type="number" id="value" name="value" value="{{ $product->value }}" required>            
        </div>

        <div >
            <label for="expiration_date" >Validade: </label>
            <input type="date" id="expiration_date" name="expiration_date" value="{{ $product->expiration_date }}" required>            
        </div>

        <div >
            <label for="stock" >Estoque: </label>
            <input type="number" id="stock" name="stock" value="{{ $product->stock }}" required>            
        </div>

        <button type="submit"> Atualizar </button>
        
    </form>
</div>
 
</body>
</html>



resources/views/products/index.blade.php ----------------------------------
 <html>
<head></head>
<body>
    
    <h1 >Lista do Produto</h1>        
       
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Value</th>
    <th>Ações</th>
  </tr>
    @foreach($products as $product)
    <tr>
        <td> {{ $product->id }}</td>
        <td> {{ $product->name }}</td>
        <td> {{ $product->value }}</td>
        <td>
            
        <!-- Botão de Visualizar -->
        <a href="{{ route('products.show', $product) }}" >
             Visualizar
        </a>

        <!-- Botão de Editar -->
        <a href="{{ route('products.edit', $product) }}" >
             Editar
        </a>


        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Deseja excluir este autor?')">
            Excluir
            </button>
        </form>

        </td>
    </tr>
    @endforeach
  
</table>   
</div>
</body>
</html>



