<?php

namespace Module\Customer\Controllers;

use App\Http\Controllers\Controller;
use Module\Customer\Controllers\Requests\StoreUpdateCustomerRequest;
use Module\Customer\Exceptions\CustomerNotFoundException;
use Module\Customer\Models\Customer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $customers = Customer::get();
            return new JsonResponse(['data' => $customers], Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreUpdateCustomerRequest $request): JsonResponse
    {
        try {
            $customer = Customer::create($request->validated());
            return new JsonResponse(['data' => $customer], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                throw new CustomerNotFoundException();
            }
            return new JsonResponse(['data' => $customer], Response::HTTP_OK);
        } catch (CustomerNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(StoreUpdateCustomerRequest $request, int $id): JsonResponse
    {
        try {

            $customer = Customer::find($id);
            if (!$customer) {
                throw new CustomerNotFoundException();
            }
            $customer->update($request->validated());
            $customer->refresh();
            return new JsonResponse(['data' => $customer], Response::HTTP_OK);
        } catch (CustomerNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $customer = Customer::find($id);
            if (!$customer) {
                throw new CustomerNotFoundException();
            }
            $customer->delete();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (CustomerNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
