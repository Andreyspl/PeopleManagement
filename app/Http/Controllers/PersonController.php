<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $query = Person::query();

        if ($request->has('query')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->query('query') . '%')
                  ->orWhere('cpf', 'like', '%' . $request->query('query') . '%');
            });
        }

        if ($request->has('state')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('state', $request->query('state'));
            });
        }

        if ($request->has('city')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('city', $request->query('city'));
            });
        }

        if ($request->has('neighborhood')) {
            $query->whereHas('addresses', function ($q) use ($request) {
                $q->where('neighborhood', 'like', '%' . $request->query('neighborhood') . '%');
            });
        }

        $people = $query->get();

        return response()->json($people);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'cpf' => 'required|string|max:14', // Assumindo que a máscara é aplicada
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Remove pontos e traços do CPF
        $validated['cpf'] = preg_replace('/[\.\-]/', '', $validated['cpf']);

        $person = Person::create($validated);

        return response()->json($person, 201);
    }

    public function show(Person $person)
    {
        $person->load('addresses');
        return response()->json($person);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'cpf' => 'required|string|max:14', // Assumindo que a máscara é aplicada
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        // Remove pontos e traços do CPF
        $validated['cpf'] = preg_replace('/[\.\-]/', '', $validated['cpf']);

        $person = Person::findOrFail($id);
        $person->update($validated);

        return response()->json($person);
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(null, 204);
    }

    public function getById($id)
    {
        $person = Person::findOrFail($id);
        return response()->json($person);
    }
}
