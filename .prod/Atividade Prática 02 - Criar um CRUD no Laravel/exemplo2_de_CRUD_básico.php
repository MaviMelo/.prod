1 - Criar um novo projeto

2 - Criando tabela de movies
	php artisan make:migration create_movies_table

3 - Arquivo de Migração: 2025_10_03_122341_create_movies_table.php

Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('director');
            $table->enum('category', ['drama', 'adventure', 'action']);
            $table->time('length');
            $table->integer('staff_size');
            $table->timestamps();
        });
        
4 - Rodar as migrações
	php artisan migrate

5- Criar arquivo de Model
	php artisan make:model Movie

5.1 - Incluir campos fillable no model

class Movie extends Model
{
    protected $fillable = ['title','director', 'category', 'length','staff_size'];
}
  
6 - Criar o controller
	php artisan make:controller MovieController --resource

7 - Criar rotas
Adicione em /routes/web.php
	use App\Http\Controllers\MovieController;	

	Route::resource('movies', MovieController::class);

8 - criar a pasta resources/views/movies

9 - Criar arquivo resources/views/movies/create.blade.php
		
    
<html>
<head></head>
<body>

    <h1 >Cadastrar Movie</h1>

    <form action="{{ route('movies.store') }}" method="POST">
        @csrf
        <div >
            <label for="title" >Title</label>
            <input type="text" id="title" name="title" required>            
        </div>
        <div >
            <label for="director" >Diretor: </label>
            <input type="text" id="director" name="director" required>            
        </div>

        <div >
            <label for="category" >Categoria: </label>
            <select name="category" id="category">
                <option value="drama">Drama</option>
                <option value="adventure">Aventura</option>
                <option value="action">Action</option>
            </select>          
        </div>

        <div >
            <label for="length" >Duração: </label>
            <input type="time" id="length" name="length" required>            
        </div>

        <div >
            <label for="staff_size" >Tamanho da Equipe: </label>
            <input type="number" id="staff_size" name="staff_size" required>            
        </div>
        
        <button type="submit">
            Salvar
        </button>        
    </form>
</body>
</html>

----------------------------------------------------------------------------------
10 - Criar a função create e store no MovieController

	php artisan make:controller MovieController --resource

		use App\Models\Movie;

		public function create()
    {
        return view('movies.create');
    }


		public function store(Request $request)
    {
        // dd($request->all());
        Movie::create($request->all());

        return view('movies.create');
    }


11 - Teste o funcionamento do formulário;

12 - Crie o arquivo resources/views/movies/show.blade.php
    <html>
    <head></head>
    <body>    
        <h1 >Detalhes do Movie</h1>        
            <div >
                <p><strong>ID:</strong> {{ $movie->id }}</p>
                <p><strong>Título:</strong> {{ $movie->title }}</p>
                <p><strong>Diretor:</strong> {{ $movie->director }}</p>
                <p><strong>Categoria:</strong> {{ $movie->category }}</p>
                <p><strong>Duração:</strong> {{ $movie->length }}</p>
                <p><strong>Tamanho da Equipe:</strong> {{ $movie->staff_size }}</p>
            </div>
        </div>    
    </div>
    </body>
    </html>

13 - Crie a função show no MovieController.php

		public function show(Movie $movie)
    {       
        return view('movies.show', compact('movie'));
    }

14 - Criar arquivo resources/views/movies/edit.blade.php

<html>
<head></head>
<body>
    <h1 >Editar Movie</h1>

    <form action="{{ route('movies.update', $movie) }}" method="POST">
        @csrf
        @method('PUT')

        <div >
            <label for="title" >Title</label>
            <input type="text" id="title" name="title" value='{{$movie->title}}' required>            
        </div>
        <div >
            <label for="director" >Diretor: </label>
            <input type="text" id="director" name="director" value="{{$movie->director}}" required>            
        </div>

        <div >
            <label for="category" >Categoria: </label>
            <select name="category" id="category">
                <option value="" >Selecione a Categoria</option>
                <option value="drama" @if ($movie->category == 'drama') selected @endif>Drama</option>
                <option value="adventure" @if ($movie->category == 'adventure') selected @endif  >Aventura</option>
                <option value="action" @if ($movie->category == 'action') selected @endif>Action</option>
            </select>          
        </div>

        <div >
            <label for="length" >Duração: </label>
            <input type="time" id="length" name="length" value="{{$movie->length}}" required>            
        </div>

        <div >
            <label for="staff_size" >Tamanho da Equipe: </label>
            <input type="number" id="staff_size" name="staff_size" value="{{$movie->staff_size}}" required>            
        </div>

        <button type="submit">
            Salvar
        </button>        
    </form>
</body>
</html>


15 - Criar a função update no MovieController.php

		public function update(Request $request, Movie $movie)
    {
        $movie->update($request->all());
        return view('movies.create');
    }
    
16 - Criar a action index em MovieController.php

		public function index()
    {
        $movies = Movie::all();
        return view('movies.index', compact('movies'));
    }
    
17 -  Criar arquivo resources/views/movies/index.blade.php

<html>
<head></head>
<body>    
    <h1 >Lista do Movies</h1>        
       
<table>
  <tr>
    <th>ID</th>
    <th>Title</th>
    <th>Duração</th>
    <th>Ações</th>
  </tr>
    @foreach($movies as $movie)
    <tr>
        <td> {{ $movie->id }}</td>
        <td> {{ $movie->title }}</td>
        <td> {{ $movie->length }}</td>

        <td>
            <!-- Botão de Visualizar -->
        <a href="{{ route('movies.show', $movie) }}" >
             Visualizar
        </a>

        <!-- Botão de Editar -->
        <a href="{{ route('movies.edit', $movie) }}" >
             Editar
        </a>


            <form action="{{ route('movies.destroy', $movie) }}" method="POST" style="display: inline;">
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

