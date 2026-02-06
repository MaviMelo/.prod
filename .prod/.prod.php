<?php

/**
------------ Node JS 22 -----------------

curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -

sudo apt install nodejs

------------- Laravel -------------------

/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"

laravel new example-app

npm install               # -g @google/generative-ai

php artisan key:generate

php artisan migrate       # :rollback --step=3

php artisan make:model NameModel -mcr

composer run dev          #  php artisan serve   &&   npm run dev

php artisan route:list

php artisan tinker        # h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5

composer remove laravel/boost
composer require laravel/boost --dev
php artisan boost:install --force

------------- VS Code -------------------

Estenções: 
	Laravel; Laravel Blade Formatter.
 
 */

/**
 * Outros Comandos artisan úteis:
 */

php artisan list;          // lista os comados artisan possíveis
php artisan db:monitor;    // Mostra as Base de Dados conectadas 
php artisan migrate:status // Mostra status das migrations
php artisan db:show        // Mostra todos os bancos de dados (possívelmente os autorizados)
php artisan db:table 	   // Mostra menú de Tabelas dos Bancos para ver seus deralhes
php artisan model:show NomeDoModelo   // Mostra os detalhes do Modelo Eloquent especificado

/* limpeza de cache */
php artisan cache:clear    // limpesa geral
php artisan route:clear	   // Após modificar arquivos de rota (routes/*.php) e as mudanças não aparecerem
php artisan config:clear   // Após alterar arquivos de configuração (.env ou config/*.php)
php artisan view:clear     // Após modificar templates Blade (.blade.php) e as alterações não refletirem
php artisan optimize:clear // executa todos os clears de uma vez + outros

composer dump-autoload     // Após adicionar/remover classes manualmente sem usar composer require

/**

php artisan tinker  

*/

// ===================================
// 1. BUSCAS BÁSICAS COM O ELOQUENT DENTO DO AMBIENTE DO TINKER.
//
// Eloquent é o ORM (Object-Relational Mapping) padrão do Laravel. Ele é uma camada de abstração que permite trabalhar com bancos de dados relacionais usando objetos PHP e modelos em vez de SQL direto, como na Facede DB.
// ===================================


// Listar tabelas do Banco de dados passado como parâmentro (similar à consulta com DB Facade "DB::select('SHOW TABLES;');" para o SGBD MySQL). Caso não tenha especificado Parâmentro, busca de todos os Bancos de dados autorizados. 
Schema::getTableListing('nome_do_banco');

// Informações das colunas (similar à consulta com DB Facade "DB::select('SHOW COLUMNS FROM users;');" ou "DB::select('DESCRIBE users;');". Dependendo do SQL do SGBD usado).
Schema::getColumns('nome_da_tabela');

// Apenas os nomes das colunas.
Schema::getColumnListing('nome_banco.nome_tabela');

// Busca todos os registros da tabela.
Product::all();

// Busca um registro pelo seu ID (ex: ID 5).
Product::find(5);

// Executa a consulta e obtém uma coleção de resultados (usado após 'where', 'orderBy', etc.).
Product::where('is_active', true)->get();

// Busca o primeiro registro que corresponde à condição.
Product::where('price', '>', 500)->first();

// Busca um registro pelo ID ou falha com um erro se não encontrar.
Product::findOrFail(999);

// Extrai uma lista (coleção) de uma única coluna.
Product::pluck('name');

// Extrai um array associativo (chave => valor) de duas colunas.
Product::pluck('price', 'name');


// ===================================
// 2. FILTRAGEM COM 'where'
// ===================================

// Filtra por igualdade (ex: produtos da categoria 3).
Product::where('category_id', 3)->get();

// Filtra usando um operador (ex: preço maior que 100).
Product::where('price', '>', 100)->get();

// Filtra usando 'like' para buscar por parte de um texto (ex: nome começa com "Livro").
Product::where('name', 'like', 'Livro%')->get();

// Encadeia 'where' para criar uma condição 'E' (AND).
Product::where('category_id', 2)->where('price', '<', 50)->get();

// Usa 'orWhere' para criar uma condição 'OU' (OR).
Product::where('price', '<', 20)->orWhere('stock', '>', 100)->get();

// Filtra registros cujo valor está dentro de um array (ex: categorias 1, 3 ou 5).
Product::whereIn('category_id', [1, 3, 5])->get();

// Filtra registros cujo valor NÃO está dentro de um array.
Product::whereNotIn('category_id', [2, 4])->get();

// Filtra registros cujo valor está entre dois valores (ex: preço entre 50 e 100).
Product::whereBetween('price', [50, 100])->get();

// Filtra registros onde a coluna NÃO é nula.
Product::whereNotNull('description')->get();

// Filtra registros onde a coluna É nula.
Product::whereNull('description')->get();


// ===================================
// 3. ORDENAÇÃO E LIMITES
// ===================================

// Ordena os resultados em ordem crescente (A-Z, 1-9).
Product::orderBy('name', 'asc')->get();

// Ordena os resultados em ordem decrescente (Z-A, 9-1).
Product::orderBy('price', 'desc')->get();

// Atalho para ordenar pelos mais recentes (orderBy('created_at', 'desc')).
Product::latest()->get();

// Limita o número de resultados retornados (ex: pega apenas 5).
Product::take(5)->get();

// Pula um número de resultados antes de começar a pegar (ex: pula 10 e pega os próximos 5).
Product::skip(10)->take(5)->get();


// ===================================
// 4. AGREGAÇÕES (CÁLCULOS)
// ===================================

// Conta o número total de registros.
Product::count();

// Conta o número de registros que correspondem a uma condição.
Product::where('is_active', true)->count();

// Encontra o valor máximo de uma coluna.
Product::max('price');

// Encontra o valor mínimo de uma coluna.
Product::min('price');

// Calcula a média dos valores de uma coluna.
Product::avg('price');

// Soma todos os valores de uma coluna.
Product::sum('stock');


// ===================================
// 5. CONSULTAS EM RELACIONAMENTOS
// ===================================

// Busca os 'pais' (Categorias) que TÊM o relacionamento 'filho' (produtos).
Category::has('products')->get();

// Busca os 'pais' (Categorias) onde os 'filhos' (produtos) correspondem a uma condição.
Category::whereHas('products', function ($query) {
    $query->where('price', '>', 500);
})->get();

// Carrega os registros junto com seus relacionamentos para evitar o problema N+1.
Product::with('category')->get();

// Busca os 'pais' (Categorias) que NÃO TÊM o relacionamento 'filho' (produtos).
Category::doesntHave('products')->get();






// ===================================================

// bootstrap/app.php


<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->booting(function (){
        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
        Gate::define('librarianGate', function ($user){
            return $user->role === 'librarian';
        });
        Gate::define('clientGate', function ($user){
            return $user->role === 'librarian' || $user->role === 'client';
        });
    })
    ->create();






// ------------------------------------------------------------------




// app/Http/Controllers/Api/PublisherApiController.php

<?php

namespace App\Http\Controllers\Api; // Mude para namespace Api se quiser separar

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Book;

class PublisherApiController extends Controller
{
    // Middleware de autenticação se necessário
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }
    
    public function index()
    {
        $this->authorize('clientGate');
        
        $publishers = Publisher::all();
        
        // ORIGINAL: return view('publishers.index', compact('publishers'));
        return response()->json([
            'data' => $publishers
        ], 200);
    }

    public function create()
    {
        $this->authorize('librarianGate');
        
        // ORIGINAL: return view('publishers.create');
        // API não retorna formulários HTML, apenas informação sobre como criar
        return response()->json([
            'message' => 'Para criar uma editora, envie uma requisição POST para este endpoint com os campos "name" e "address".'
        ], 200);
    }

    public function store(Request $request)
    {
        $this->authorize('librarianGate');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
            'address' => 'nullable|string|max:255',
        ]);
        
        $publisher = Publisher::create($request->all());
        
        // ORIGINAL: return redirect()->route('publishers.index')->with('success', 'Publicação cadastrada com sucesso.');
        return response()->json([
            'message' => 'Publicação cadastrada com sucesso.',
            'data' => $publisher
        ], 201);
    }

    public function show(Publisher $publisher)
    {
        $this->authorize('clientGate');
        
        $books = Book::where('publisher_id', $publisher->id)->get();

        // ORIGINAL: return view('publishers.show', compact('publisher', 'book'));
        return response()->json([
            'data' => [
                'publisher' => $publisher,
                'books' => $books
            ]
        ], 200);
    }

    public function edit(Publisher $publisher)
    {
        $this->authorize('librarianGate');
        
        // ORIGINAL: return view('publishers.edit', compact('publisher'));
        // API não retorna formulários de edição, apenas os dados para edição
        return response()->json([
            'data' => $publisher,
            'message' => 'Para atualizar, envie uma requisição PUT/PATCH para este endpoint.'
        ], 200);
    }

    public function update(Request $request, Publisher $publisher)
    {
        $this->authorize('librarianGate');
        
        $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,'.$publisher->id,
            'address' => 'nullable|string|max:255',
        ]);
        
        $publisher->update($request->all());
        
        // ORIGINAL: return redirect()->route('publishers.index')->with('success', 'Publicação atualizada com sucesso.');
        return response()->json([
            'message' => 'Publicação atualizada com sucesso.',
            'data' => $publisher
        ], 200);
    }

    public function destroy(Publisher $publisher)
    {
        $this->authorize('librarianGate');
        
        $publisher->delete();

        // ORIGINAL: return redirect()->route('publishers.index')->with('success', 'Publicação excluida:'.' '.$publisher->name);
        return response()->json([
            'message' => 'Publicação excluída: ' . $publisher->name,
            'deleted_data' => $publisher
        ], 200);
    }
}



// =====================================================



/*
 
Olá, estamos na raiz de um projeto. Escanei-o, identifique e o descreva. 


 Gora, use os recursos do Laravel Boost para conferir as Migrations para entender a persistêcia do banco de dados e a proposta do projeto e configure, com Código nais simples possível e as boas práticas da arquitetura MVC no Laravel/Boost. Configure todas as respectivas partes do projeto, na sequência:  Models, com todos os seus atributos e relacionamentos necessários; Controllers, com todos os métodos padrão (index, create, store, show, edit, update e destroy); Views necessárias (create, edit, index e show.blade.php), com a interface em português do Brasil e botões padronizados, para executar dotas as funções básicas de um CRUD (GET, POST, PUT/PATCH e DELETE);  e Rotas necessárias. Tudo isso com os códigos mais simples e minimalistas possível.
 */
