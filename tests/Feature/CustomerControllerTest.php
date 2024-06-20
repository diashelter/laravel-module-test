<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Module\Customer\Models\Customer;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testListCustomers()
    {
        Customer::factory()->count(10)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $this->assertDatabaseCount('customers', 10);
    }

    public function testCustomerNotFound()
    {
        $response = $this->getJson("/api/customers/" . 0);
        $response->assertStatus(404);
        $response->assertJsonPath('error', 'Customer not found');
    }

    public function testShowCustomer()
    {
        $customer = Customer::factory()->create();
        $response = $this->getJson("/api/customers/{$customer->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
        ]);

        $response->assertJsonPath('data.id', $customer->id);
        $response->assertJsonPath('data.name', $customer->name);
        $response->assertJsonPath('data.email', $customer->email);
    }

    public function testCreateCustomer()
    {
        $response = $this->postJson("/api/customers", [
            'name' => 'Test Customer',
            'email' => 'test@test.com',
            'phone' => '0123456789',
            'date_of_birth' => '2024-01-01',
            'address' => 'Test Address',
            'complement' => 'Test Complement',
            'neighborhood' => 'Test Neighborhood',
            'zipcode' => '12345',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'test@test.com',
            'phone' => '0123456789',
            'date_of_birth' => '2024-01-01',
            'address' => 'Test Address',
            'complement' => 'Test Complement',
            'neighborhood' => 'Test Neighborhood',
            'zipcode' => '12345',
        ]);
    }

    public function testValidateFieldsToCreateCustomer()
    {
        $response = $this->postJson("/api/customers", []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
        $response->assertJsonValidationErrors('email');
    }

    public
    function testUpdateCustomer()
    {
        $customer = Customer::factory()->create();
        $response = $this->putJson("/api/customers/{$customer->id}", [
            'name' => 'Test Customer',
            'email' => 'test@test.com',
            'phone' => $customer->phone,
            'date_of_birth' => $customer->date_of_birth,
            'address' => $customer->address,
            'complement' => null,
            'neighborhood' => $customer->neighborhood,
            'zipcode' => $customer->zipcode,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Test Customer',
            'email' => 'test@test.com',
            'phone' => $customer->phone,
            'date_of_birth' => $customer->date_of_birth,
            'address' => $customer->address,
            'complement' => null,
            'neighborhood' => $customer->neighborhood,
            'zipcode' => $customer->zipcode,
        ]);
    }

    public function testDeleteCustomer()
    {
        $customer = Customer::factory()->create();
        $response = $this->deleteJson("/api/customers/{$customer->id}");
        $response->assertStatus(204);
        $this->assertDatabaseCount('customers', 1);
        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
    }
}
