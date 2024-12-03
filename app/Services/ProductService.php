<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProductService
{
    public function getProductInfo($productId)
    {
        $url = 'http://127.0.0.1:8000/getItem';

        $response = Http::get($url, ['produto' => $productId]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getProducts()
    {
        $url = 'http://127.0.0.1:8000/getItens';

        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}