<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Ticket;
use App\Models\Ticket_row;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return inertia('Vente', [
            'tickets' => Ticket::with(['ticketRows', 'client'])->orderBy('created_at', 'desc')->paginate(10),
            'clients' => Client::all(),
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
        // dd($request->ticket);
        $ticketRows = $request->ticket;
        $ticket = Ticket::create();
        $ticket->client_id = $request->client_id;
        $ticket->save();

        foreach ($ticketRows as $key => $row) {

            $ticketRow = new Ticket_row();
            $ticketRow->ticket_id = $ticket->id;
            $ticketRow->price = $row['price'];
            $ticketRow->quantity = $row['quantity'];
            $ticketRow->category_id = $row['category_id'];

            $ticketRow->save();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
