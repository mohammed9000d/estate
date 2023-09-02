<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;

class TenantController extends Controller
{
    //

    public function index()
    {
        return User::all();
    }

    public function store()
    {
        $tenant = Tenant::create(['id' => Tenant::generateId()]);
        return response()->json($tenant, 201);
    }
}
