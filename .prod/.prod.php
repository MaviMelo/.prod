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

php  artisan make:model NameModel -mcr

composer run dev          #  php artisan serve   &&   npm run dev

php artisan route:list

php artisan tinker        # h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5

*/




/**

php artisan tinker  

*/

// ===================================
// 1. BUSCAS BÁSICAS
// ===================================

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
