<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductPriceController extends Controller
{
    /**
     * Recupera la lista de todos los precios registrados para un producto específico.
     *
     * Incluye eager loading de la relación 'currency' para cada precio para evitar N+1 queries.
     *
     * @param  \App\Models\Product  $product  La instancia del modelo Product para el cual se buscan los precios.
     * @return \Illuminate\Http\JsonResponse
     * @api GET /products/{id}/prices
     */
    public function index(Product $product)
    {
        $prices = $product->prices()->with('currency')->get();

        return response()->json([
            'message' => 'Lista de precios del producto recuperada exitosamente.',
            'data' => $prices,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Almacena un nuevo precio para un producto en una divisa específica.
     *
     * Valida los datos de entrada y asegura la unicidad de la combinación producto-divisa.
     * Si ya existe un precio para esa combinación, retorna un conflicto (409 HTTP_CONFLICT).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product  La instancia del modelo Product al que se asignará el precio.
     * @return \Illuminate\Http\JsonResponse
     * @api POST /products/{id}/prices
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'currency_id' => 'required|exists:currencies,id',
            'price' => 'required|numeric|min:0',
        ]);

        // Verificamos si ya existe un precio para este producto con esta divisa.
        $existingPrice = ProductPrice::where('product_id', $product->id)
            ->where('currency_id', $validated['currency_id'])
            ->first();

        if ($existingPrice) {
            return response()->json([
                'message' => 'El precio para esta divisa ya existe para este producto.',
                'data' => "El precio existente es: {$existingPrice->price}.",
                'status' => Response::HTTP_CONFLICT
            ], Response::HTTP_CONFLICT);
        }

        $price = $product->prices()->create($validated);
        $price->load('currency');

        return response()->json([
            'message' => 'Precio del producto creado exitosamente.',
            'data' => $price
        ], Response::HTTP_CREATED);
    }
}
