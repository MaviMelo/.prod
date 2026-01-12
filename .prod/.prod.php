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










/*
 
Olá, estamos na raiz de um projeto. Escanei-o, identifique e o descreva. 


 Gora, use os recursos do Laravel Boost para conferir as Migrations para entender a persistêcia do banco de dados e a proposta do projeto e configure, com Código nais simples possível e as boas práticas da arquitetura MVC no Laravel/Boost. Configure todas as respectivas partes do projeto, na sequência:  Models, com todos os seus atributos e relacionamentos necessários; Controllers, com todos os métodos padrão (index, create, store, show, edit, update e destroy); Views necessárias (create, edit, index e show.blade.php), com a interface em português do Brasil e botões padronizados, para executar dotas as funções básicas de um CRUD (GET, POST, PUT/PATCH e DELETE);  e Rotas necessárias. Tudo isso com os códigos mais simples e minimalistas possível.
 */
