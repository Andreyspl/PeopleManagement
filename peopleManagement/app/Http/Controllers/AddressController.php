<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'type' => 'required|in:residential,commercial',
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
        ]);

        $address = Address::create($validated);
        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'person_id' => 'required|exists:people,id',
            'type' => 'required|in:residential,commercial',
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'state' => 'required|string|max:2',
            'city' => 'required|string|max:255',
        ]);

        $address = Address::findOrFail($id);
        $address->delete();

        $newAddress = Address::create($validated);

        return response()->json($newAddress);
    }

    public function history($personId)
    {
        $addresses = Address::withTrashed()->where('person_id', $personId)->get();
        return response()->json($addresses);
    }


    public function destroy(Address $address)
    {
        $address->delete();
        return response()->json(null, 204);
    }
}