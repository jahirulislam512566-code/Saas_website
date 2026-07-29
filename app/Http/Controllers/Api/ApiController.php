<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Actions\Customer\CreateCustomerAction;
use App\Actions\Customer\UpdateCustomerAction;
use App\Actions\Customer\DeleteCustomerAction;
use App\Actions\Customer\SuspendCustomerAction;
use App\Actions\Customer\ActivateCustomerAction;
use App\Actions\Project\CreateProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\Actions\Project\DeleteProjectAction;
use App\Actions\Project\ArchiveProjectAction;
use App\Actions\Invoice\GenerateInvoiceAction;
use App\Actions\Invoice\SendInvoiceAction;
use App\Actions\Invoice\MarkInvoiceAsPaidAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\InvoiceRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Auth endpoints
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'Login successful',
            'data' => $result
        ]);
    }

    public function logout(LogoutAction $action): JsonResponse
    {
        $action->execute(auth()->user());
        
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'Registration successful',
            'data' => $user
        ], 201);
    }

    // Customer endpoints
    public function createCustomer(CustomerRequest $request, CreateCustomerAction $action): JsonResponse
    {
        $customer = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    public function updateCustomer(Customer $customer, CustomerRequest $request, UpdateCustomerAction $action): JsonResponse
    {
        $updatedCustomer = $action->execute($customer, $request->validated());
        
        return response()->json([
            'message' => 'Customer updated successfully',
            'data' => $updatedCustomer
        ]);
    }

    public function deleteCustomer(Customer $customer, DeleteCustomerAction $action): JsonResponse
    {
        $action->execute($customer);
        
        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }

    public function suspendCustomer(Customer $customer, Request $request, SuspendCustomerAction $action): JsonResponse
    {
        $suspendedCustomer = $action->execute($customer, $request->all());
        
        return response()->json([
            'message' => 'Customer suspended successfully',
            'data' => $suspendedCustomer
        ]);
    }

    public function activateCustomer(Customer $customer, Request $request, ActivateCustomerAction $action): JsonResponse
    {
        $activatedCustomer = $action->execute($customer, $request->all());
        
        return response()->json([
            'message' => 'Customer activated successfully',
            'data' => $activatedCustomer
        ]);
    }

    // Project endpoints
    public function createProject(ProjectRequest $request, CreateProjectAction $action): JsonResponse
    {
        $project = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function updateProject(Project $project, ProjectRequest $request, UpdateProjectAction $action): JsonResponse
    {
        $updatedProject = $action->execute($project, $request->validated());
        
        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $updatedProject
        ]);
    }

    public function deleteProject(Project $project, DeleteProjectAction $action): JsonResponse
    {
        $action->execute($project);
        
        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    public function archiveProject(Project $project, Request $request, ArchiveProjectAction $action): JsonResponse
    {
        $archivedProject = $action->execute($project, $request->all());
        
        return response()->json([
            'message' => 'Project archived successfully',
            'data' => $archivedProject
        ]);
    }

    // Invoice endpoints
    public function generateInvoice(InvoiceRequest $request, GenerateInvoiceAction $action): JsonResponse
    {
        $invoice = $action->execute($request->validated());
        
        return response()->json([
            'message' => 'Invoice generated successfully',
            'data' => $invoice
        ], 201);
    }

    public function sendInvoice(Invoice $invoice, Request $request, SendInvoiceAction $action): JsonResponse
    {
        $action->execute($invoice, $request->all());
        
        return response()->json([
            'message' => 'Invoice sent successfully'
        ]);
    }

    public function markInvoicePaid(Invoice $invoice, Request $request, MarkInvoiceAsPaidAction $action): JsonResponse
    {
        $paidInvoice = $action->execute($invoice, $request->all());
        
        return response()->json([
            'message' => 'Invoice marked as paid',
            'data' => $paidInvoice
        ]);
    }
}