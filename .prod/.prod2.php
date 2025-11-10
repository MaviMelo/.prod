

######################## views categories ################

# ---------------------- categories.index ----------------

<?
# @extends('layouts.app')

# @section('content')
?>

<div class="container">
        <h1 class="my-4">Lista de Categorias</h1>

        <a href="{{ route('categories.create') }}" class="btn btn-success mb-3">
            <i class="bi bi-plus"></i> Adicionar Categoria
        </a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <!-- Botão de Visualizar -->
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Visualizar
                            </a>

                            <!-- Botão de Editar -->
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-sm"><i
                                    class="bi bi-pencil"></i> Editar
                            </a>

                            <!-- Botão de Excluir -->
                            <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir esta categoria?')">
                                    <i class="bi bi-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Nenhuma categoria encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<? # @endsection ?> 




# ---------------------- categories.show ----------------

<?
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Detalhes da Categoria</h1>

        <div class="card">
            <div class="card-header">
                Categoria: {{ $category->name }}
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $category->id }}</p>
                <p><strong>Nome:</strong> {{ $category->name }}</p>
            </div>
        </div>

        <a href="{{ route('categories.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
<?
    #@endsection
?> 





# ---------------------- categories.create ----------------

<? 
# @extends('layouts.app')

# @section('content')
?>

    <div class="container">
        <h1 class="my-4">Adicionar Categoria</h1>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid
@enderror" id="name" name="name"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Salvar</button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </form>
    </div>
<? 
# @endsection
?>




# ---------------------- categories.edit ----------------

<?
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Editar Categoria</h1>

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid
@enderror" id="name" name="name"
                    value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Atualizar
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </form>
    </div>

<?
# @endsection
 ?>
 
 
 
 
 
 
 
 

######################## views products ################

# ---------------------- products.index ----------------


<? 
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Lista de Produtos</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus"></i> Adicionar Livro (Com Select)
        </a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Categoria</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price}}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <!-- Botão de Visualizar -->
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> Visualizar
                            </a>

                            <!-- Botão de Editar -->
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil"></i> Editar
                            </a>

                            <!-- Botão de Deletar -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir este livro?')">
                                    <i class="bi bi-trash"></i> Deletar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Nenhum produto encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
<? 
#    @endsection
?>




# ---------------------- products.show ----------------

<? 
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Detalhes do Produto</h1>

        <div class="card">
            <div class="card-header">
                <strong>Nome:</strong> {{ $product->name }}
            </div>
            <div class="card-header">
                <strong>Descrição:</strong> {{ $product->description }}
            </div>
            <div class="card-header">
                <strong>Preço:</strong> {{ $product->price }}
            </div>
            <div class="card-body">
                <p><strong>Categoria:</strong>
                    <a href="{{ route('categories.show', $product->category->id) }}">
                        {{ $product->category->name }}</a>
                </p>

            </div>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
<? 
# @endsection
?>






# ---------------------- products.create ----------------

<?
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Adicionar Produtos (Com Select)</h1>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <div class="mb-3">

                <label for="name" class="form-label">Nome</label>

                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    required>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="description" class="form-label">Descição</label>

                <textarea class="form-control @error('name') is-invalid @enderror" id="description" name="description"  placeholder="Descrição">{{old('escription', $product->description ?? '')}}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="price" class="form-label">Preço</label>

                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" step="0.01" min="0.01" placeholder="99.90" required>

                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="category_id" class="form-label">Categoria</label>

                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
                    required>
                    <option value="" selected>Selecione uma categoria</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach

                </select>

                @error('category_id')
                    <div class="invalid-feedback">
                        {{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-success">Salvar</button>
        </form>
    </div>
<?
#@endsection
?> 






# ---------------------- products.edit ----------------

<?
# @extends('layouts.app')

# @section('content')
?>
    <div class="container">
        <h1 class="my-4">Adicionar Produtos (Com Select)</h1>

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">

                <label for="name" class="form-label">Nome</label>

                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $product->name) }}" required>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="description" class="form-label">Descição</label>

                <textarea class="form-control @error('name') is-invalid @enderror" id="description" name="description"
                    placeholder="Descrição">{{ old('description', $product->description ?? '') }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="price" class="form-label">Preço</label>

                <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                    name="price" step="0.01" min="0.01" placeholder="99.90"
                    value="{{ old('price', $product->price) }}" required>

                @error('price')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label for="category_id" class="form-label">Categoria</label>

                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id"
                    required>
                    <option value="" selected>Selecione uma categoria</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>

                @error('category_id')
                    <div class="invalid-feedback">
                        {{ $message }}</div>
                @enderror

            </div>

            <button type="submit" class="btn btn-success">Atualizar</button>
            
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
<?
# @endsection
?>
