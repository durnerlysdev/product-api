<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Recupera una lista de productos.
     *
     * Incluye la relación 'currency' para evitar N+1 queries al serializar.
     * Considerar paginación para grandes volúmenes de datos.
     *
     * @return \Illuminate\Http\JsonResponse
     * @api GET /products
     */
    public function index()
    {
        $products = Product::with('currency')->get();

        return response()->json([
            'message' => 'Lista de productos recuperada exitosamente.',
            'data' => $products,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     *
     * Realiza una validación de los datos de entrada.
     * Retorna el producto recién creado junto con un código de estado 201 Created.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @api POST /products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency_id' => 'required|exists:currencies,id',
            'tax_cost' => 'required|numeric|min:0',
            'manufacturing_cost' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'message' => 'Producto creado exitosamente.',
            'data' => $product,
            'status' => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Muestra los detalles de un producto específico.
     *
     * Carga eager load de la divisa base del producto y las relaciones anidadas
     * de 'prices.currency' para evitar N+1 queries al construir la respuesta.
     *
     * @param  \App\Models\Product  $product  La instancia del modelo Product inyectada por Laravel.
     * @return \Illuminate\Http\JsonResponse
     * @api GET /products/{id}
     */
    public function show(Product $product)
    {
        $product->load('currency', 'prices.currency');

        return response()->json([
            'message' => 'Detalles del producto recuperados exitosamente.',
            'data' => $product,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Actualiza un producto existente en la base de datos.
     *
     * Permite actualizaciones parciales ('sometimes') y valida los datos de entrada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product  La instancia del modelo Product a actualizar.
     * @return \Illuminate\Http\JsonResponse
     * @api PUT /products/{id}
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'currency_id' => 'sometimes|required|exists:currencies,id',
            'tax_cost' => 'sometimes|required|numeric|min:0',
            'manufacturing_cost' => 'sometimes|required|numeric|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'message' => 'Producto actualizado exitosamente.',
            'data' => $product,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Elimina un producto de la base de datos.
     *
     * Retorna un mensaje de confirmación de éxito junto con el ID del producto eliminado.
     * El estado 200 OK es utilizado para indicar éxito con un cuerpo de respuesta.
     *
     * @param  \App\Models\Product  $product  La instancia del modelo Product a eliminar.
     * @return \Illuminate\Http\JsonResponse
     * @api DELETE /products/{id}
     */
    public function destroy(Product $product)
    {
        $productName = $product->name;
        $product->delete();

        return response()->json([
            'message' => "El producto '{$productName}' ha sido eliminado exitosamente.",
            'data' => ['product_id' => $product->id],
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
