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
            $query->where('name', 'like', '%' . $request->query('query') . '%')
                ->orWhere('cpf', 'like', '%' . $request->query('query') . '%');
        }

        $people = $query->get();
        return response()->json($people);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'cpf' => 'required|string|max:14|unique:people',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:people',
        ]);

        $person = Person::create($validated);
        return response()->json($person, 201);
    }

    public function show(Person $person)
    {
        $person->load('addresses');
        return response()->json($person);
    }

    public function update(Request $request, Person $person)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'social_name' => 'nullable|string|max:255',
            'cpf' => 'required|string|max:14|unique:people,cpf,' . $person->id,
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:people,email,' . $person->id,
        ]);

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
