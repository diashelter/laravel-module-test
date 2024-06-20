<?php

namespace Module\Product\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Module\Product\Controllers\Requests\StoreProductRequest;
use Module\Product\Controllers\Requests\UpdateProductRequest;
use Module\Product\Exceptions\ProductNotFoundException;
use Module\Product\Model\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $products = Product::get();
            return new JsonResponse(['data' => $products], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new ProductNotFoundException();
            }
            return new JsonResponse(['data' => $product], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = Product::create([
                'name' => $request->validated('name'),
                'price_in_cents' => $request->validated('price_in_cents'),
                'photo' => $request->file('photo')->store('products'),
            ]);
            return new JsonResponse(['data' => $product], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new ProductNotFoundException();
            }
            $product->update($request->validated());
            return new JsonResponse(['data' => $product->refresh()], Response::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new ProductNotFoundException();
            }
            $product->delete();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
