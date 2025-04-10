<?php

namespace App\Http\Controllers;

use App\Exports\ClientExport;
use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = request()->input('search');
        $actif = request()->input('actif');

        $query = Client::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%$search%")
                    ->orWhere('lastname', 'like', "%$search%")
                    ->orWhere('company', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('tva_number', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($actif === 'with_tva') {
            $query->where('tva_number', '!=', '')->where('tva_number', '!=', 'N/A');
        } elseif ($actif === 'without_tva') {
            $query->whereNull('tva_number')->orWhere('tva_number', '=', '')->orWhere('tva_number', '=', 'N/A');
        } elseif ($actif !== 'active') {
            $query->onlyTrashed();
        }

        return inertia('client/Index', [
            'clients' => $query->paginate(12),
            'filters' => $request->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'company' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'tva_number' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'country' => 'nullable',
        ]);
        Client::create($validated);


        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $message = "$firstname $lastname a été ajouté à la liste des clients";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $client = Client::withTrashed()->findOrFail($id);

        return inertia('client/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $client->update($request->all());
        $message = "La fiche de $client->firstname $client->lastname a été modifié";

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $clientFirstname = $client->firstname;
        $clientLastname = $client->lastname;

        $message = "$clientFirstname $clientLastname a été supprimé";

        $client->delete();

        return redirect()->route('client.index')->with('success', $message);
    }

    public function restore(String $id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $clientFirstname = $client->firstname;
        $clientLastname = $client->lastname;

        $message = "$clientFirstname $clientLastname a été restauré";

        $client->restore();

        return redirect()->route('client.index')->with('success', $message);
    }

    public function export(Request $request)
    {
        $filename = 'clients_actifs.xlsx';

        if ($request->input('tab') === 'with_tva') {
            $filename = 'clients_assujettis.xlsx';
        }
        if ($request->input('tab') === 'without_tva') {
            $filename = 'clients_non_assujettis.xlsx';
        }
        if ($request->input('tab') === 'deleted') {
            $filename = 'clients_supprimés.xlsx';
        }



        return Excel::download(new ClientExport($request->input('tab')), $filename);
    }
    public function forceDelete($id)
    {
        $client = Client::withTrashed()->findOrFail($id);
        $clientFirstname = $client->firstname;
        $clientLastname = $client->lastname;

        $message = "$clientFirstname $clientLastname a été supprimé définitivement";

        $client->forceDelete();

        return redirect()->route('client.index')->with('success', $message);
    }
}
