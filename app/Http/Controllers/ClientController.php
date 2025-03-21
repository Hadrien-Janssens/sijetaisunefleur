<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return inertia('client/Index', [
            'clients' => Client::withTrashed()->get()
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
        Client::create($request->all());

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
    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        $message = "La fiche de $client->firstname $client->lastname a été modifié";

        return redirect()->route('client.index')->with('success', $message);
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
}
