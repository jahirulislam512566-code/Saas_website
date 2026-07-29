<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return view('admin.permissions.index');
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.permissions.index')->with('success', 'Permission created.');
    }

    public function edit($id)
    {
        return view('admin.permissions.edit');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.permissions.index')->with('success', 'Permission updated.');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.permissions.index')->with('success', 'Permission deleted.');
    }

    public function groups()
    {
        return view('admin.permissions.groups');
    }

    public function sync(Request $request)
    {
        return redirect()->route('admin.permissions.index')->with('success', 'Permissions synced.');
    }

    public function export()
    {
        // Export logic
    }

    public function import(Request $request)
    {
        return redirect()->route('admin.permissions.index')->with('success', 'Permissions imported.');
    }
}