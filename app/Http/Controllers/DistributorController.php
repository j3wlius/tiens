<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::latest()->get();
        return view('distributors.index', compact('distributors'));
    }

    public function create()
    {
        return view('distributors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:distributors',
            'phone_number' => 'required|string|max:20',
            'residence' => 'required|string',
            'national_id' => [
                'required',
                'string',
                'size:14',
                'unique:distributors',
                'regex:/^(CF|CM)[A-Za-z0-9]{12}$/',
            ],
        ], [
            'national_id.regex' => 'The national ID must start with CF or CM and be exactly 14 characters long.',
        ]);

        Distributor::create($validated);

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor created successfully.');
    }

    public function edit(Distributor $distributor)
    {
        return view('distributors.edit', compact('distributor'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('distributors')->ignore($distributor)],
            'phone_number' => 'required|string|max:20',
            'residence' => 'required|string',
            'national_id' => [
                'required',
                'string',
                'size:14',
                Rule::unique('distributors')->ignore($distributor),
                'regex:/^(CF|CM)[A-Za-z0-9]{12}$/',
            ],
        ], [
            'national_id.regex' => 'The national ID must start with CF or CM and be exactly 14 characters long.',
        ]);

        $distributor->update($validated);

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor updated successfully.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();

        return redirect()->route('distributors.index')
            ->with('success', 'Distributor deleted successfully.');
    }
}
