<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $department = Department::create($validated);
        return response()->json($department, 201); 
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_name' => 'sometimes|required|string|max:255',
        ]);

        $department = Department::findOrFail($id);
        $department->update($validated);

        return response()->json($department);
    }


    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['message' => 'Department deleted successfully']);
    }


    public function index()
    {
        $departments = Department::all();
        return response()->json($departments);
    }
}
