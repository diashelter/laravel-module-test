<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Module\Product\Model\Product;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListAllProducts()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $this->assertDatabaseCount('products', 5);
    }

    public function testShowProduct()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.id', $product->id);
        $response->assertJsonPath('data.name', $product->name);
        $response->assertJsonPath('data.price_in_cents', $product->price_in_cents);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product->name,
            'price_in_cents' => $product->price_in_cents,
        ]);
    }

    public function testProductNotFoundException()
    {
        $response = $this->getJson("/api/products/" . 0);
        $response->assertStatus(404);
        $response->assertJsonPath('error', 'Product not found');
    }

    public function testCreateProduct()
    {
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->postJson("/api/products", [
            'name' => 'Test Product',
            'price_in_cents' => 500,
            'photo' => $file
        ]);
        $response->assertStatus(201);
        $response->assertJsonPath('data.name', 'Test Product');
        $response->assertJsonPath('data.price_in_cents', 500);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price_in_cents' => 500,
        ]);
    }

    public function testUpdateProduct()
    {
        $product = Product::factory()->create();
        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Test Product',
            'price_in_cents' => $product->price_in_cents,
        ]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Test Product');
        $response->assertJsonPath('data.price_in_cents', $product->price_in_cents);
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price_in_cents' => $product->price_in_cents,
        ]);
    }

    public function testDeleteProduct()
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
        ]);
        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);
    }

    public function tearDown(): void
    {
        $allFiles = Storage::disk('public')->allFiles('products');
        foreach ($allFiles as $file) {
            Storage::disk('public')->delete($file);
        }
        parent::tearDown();
    }
}
