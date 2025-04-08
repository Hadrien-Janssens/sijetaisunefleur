<?php

namespace App\Http\Controllers;

use App\Exports\NumberExport;
use App\Exports\TicketExport;
use App\Mail\InvoiceMail;
use App\Models\Category;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\Ticket_row;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

require_once __DIR__ . '/../lib/helper.php';

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Récupérer la requête de recherche
        $search = $request->input('search');
        $withInvoice = $request->input('withInvoice');
        $actif = $request->input('actif');
        $timeSlot = $request->input('time_slot');

        if ($actif === 'active') {
            // Construire la requête des tickets
            $query = Ticket::with(['ticketRows.category', 'client'])
                ->orderBy('created_at', 'desc');
        } else {

            $query = Ticket::onlyTrashed()->with(['ticketRows.category', 'client'])
                ->orderBy('created_at', 'desc');
        }

        // Appliquer la recherche si un terme est présent
        if (!empty($search)) {
            $query->where('reference', 'like', "%$search%")
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('company', 'like', "%$search%")
                        ->orWhere('lastname', 'like', "%$search%");
                });
        }

        if ($withInvoice === 'true') {
            $query->where('with_tva', '=', true);
        }

        if ($timeSlot !== null) {
            if ($timeSlot === 'crenaux') {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            } else if ($timeSlot !== 'day') {

                $dates = dateFilter($request->start_date, $timeSlot);
                $query->whereBetween('created_at', [
                    Carbon::parse($dates['start'])->startOfDay(),
                    Carbon::parse($dates['end'])->endOfDay()
                ]);
            } else {

                if ($request->start_date) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->start_date)->endOfDay()
                    ]);
                }
            }
        } else {
            $query->whereDate('created_at', Carbon::today());
        }


        return inertia('Vente', [
            'tickets' => $query->paginate(10)->withQueryString(), // Garde les filtres dans l'URL
            'clients' => Client::all(),
            'filters' => $request->only('search'), // Garde la recherche en mémoire pour le front
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

        $reference = '';
        $currentYear = now()->year;

        if ($request->with_tva) {
            $lastNumber = DB::table('tickets')->where('deleted_at', null)->where('with_tva', true)->whereYear('created_at', $currentYear)->max('reference');

            $reference =  ($lastNumber ? $lastNumber + 1 : 1);
        } else {
            $lastNumber = DB::table('tickets')->where('deleted_at', '!=', null)->where('with_tva', false)->whereYear('created_at', $currentYear)->max('reference');

            $reference =  $lastNumber ? $lastNumber + 1 : 1;
        };

        $ticket = Ticket::create([
            'client_id' => $request->client_id,
            'with_tva' =>  $request->with_tva,
            'is_paid' => $request->is_paid,
            'reference' => $reference,
            'comment' => $request->comment,
            'echeance' => $request->echeance,
            'remise' => $request->remise,
            'remiseAmount' => $request->remiseAmount,
        ]);



        $ticketRows = $request->ticket;

        foreach ($ticketRows as $key => $row) {

            $ticketRow = new Ticket_row();
            $ticketRow->ticket_id = $ticket->id;
            $ticketRow->price = $row['price'];
            $ticketRow->quantity = $row['quantity'];
            $ticketRow->category_id = $row['category_id'];
            $ticketRow->reduction = $row['reduction'] ?? 0;

            $ticketRow->save();
        }

        if ($ticket->client_id) {

            $message = "La facture a été envoyé à " . $ticket->client->email;

            $pdf = Pdf::loadView('facture', ['ticket' => $ticket, 'with_tva' => $request->with_tva]);

            Mail::to($ticket->client->email)->send(new InvoiceMail($pdf->output(), "facture.pdf"));
            // Mail::to("contact@sijetaisunefleur.com")->send(new InvoiceMail($pdf->output(), "facture.pdf"));

            return redirect()->route('caisse')->with('success', $message);
        } else if ($request->input('email')) {
            // Send an email to the client
            $message = "La facture a été envoyé à " . $request->input('email');

            $pdf = Pdf::loadView('facture', ['ticket' => $ticket, 'with_tva' => $request->with_tva, 'tva_number' => $request->tva_number]);

            Mail::to($request->input('email'))->send(new InvoiceMail($pdf->output(), "facture.pdf"));

            return redirect()->route('caisse')->with('success', $message);
        }
        return redirect()->route('caisse');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ticket = Ticket::withTrashed()->with(['ticketRows.category', 'client'])->findOrFail($id);
        $client = Client::all();
        $category = Category::all();

        return inertia('vente/Edit', [
            'ticket' => $ticket,
            'clients' => $client,
            'category' => $category,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        $reference = '';
        $currentYear = now()->year;

        if ($request->with_tva) {
            $lastNumber = DB::table('tickets')->where('with_tva', true)->whereYear('created_at', $currentYear)->max('reference');

            $reference =  ($lastNumber ? $lastNumber + 1 : 1);
        } else {
            $lastNumber = DB::table('tickets')->where('with_tva', false)->whereYear('created_at', $currentYear)->max('reference');

            $reference =  $lastNumber ? $lastNumber + 1 : 1;
        };
        $ticket->update([
            'client_id' => $request->client,
            'with_tva' => $request->with_tva,
            'comment' => $request->comment,
            'reference' => $reference,
            'created_at' => $request->date,
            'remise' => $request->remise,
            'remiseAmount' => $request->remiseAmount,
            'is_paid' => $request->is_paid,
        ]);

        $ticketRows = $request->ticket_rows;
        $ticket->ticketRows()->delete();

        foreach ($ticketRows as $key => $row) {

            $ticketRow = new Ticket_row();
            $ticketRow->ticket_id = $ticket->id;
            $ticketRow->price = $row['price'];
            $ticketRow->quantity = $row['quantity'];
            $ticketRow->reduction = $row['reduction'] ?? 0;
            $ticketRow->category_id = $row['category_id'];

            $ticketRow->save();
        }

        return redirect()->back()->with('success', 'Le ticket a été mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        $message = "Le ticket a été supprimé";

        $ticket->delete();

        return redirect()->back()->with('success', $message);
    }

    public function forceDelete(string $id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        $message = "Le ticket a été supprimé définitivement";

        $ticket->forceDelete();

        return redirect()->back()->with('success', $message);
    }

    public function restore(string $id)
    {
        $ticket = Ticket::withTrashed()->findOrFail($id);

        $message = "Le ticket a été restauré";

        $ticket->restore();

        return redirect()->back()->with('success', $message);
    }


    public function exportArticles(Request $request)
    {
        return Excel::download(new TicketExport($request->all()), 'tickets.xlsx');
    }

    public function exportNumber(Request $request)
    {
        return Excel::download(new NumberExport($request->all()), 'chiffres_journaliers.xlsx');
    }


    /**
     * Generate a PDF for the specified ticket.
     *
     * @param  int  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function generatePDF($ticketId)
    {
        // Récupérer les données du ticket
        $ticket = Ticket::findOrFail($ticketId);


        try {
            $pdf = Pdf::loadView('facture', ['ticket' => $ticket, 'with_tva' => $ticket->with_tva]);
            return response($pdf->stream("facture_{$ticket->id}.pdf"))
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendMail($id)
    {
        $ticket = Ticket::with(['ticketRows.category', 'client'])->find($id);

        $pdf = Pdf::loadView('facture', ['ticket' => $ticket, 'with_tva' => $ticket->with_tva]);

        $email = $ticket->client->email;

        Mail::to($email)->send(new InvoiceMail($pdf->output(), "facture.pdf"));
        // Mail::to("contact@sijetaisunefleur.com")->send(new InvoiceMail($pdf->output(), "facture.pdf"));

        return redirect()->back()->with('success', 'Facture envoyée par email.');
    }
    public function downloadInvoice(Request $request)
    {
        // Récupérer la requête de recherche
        $search = $request->input('search');
        $withInvoice = $request->input('withInvoice');
        $actif = $request->input('actif');
        $timeSlot = $request->input('time_slot');

        if ($actif === 'active') {
            // Construire la requête des tickets
            $query = Ticket::with(['ticketRows.category', 'client'])
                ->orderBy('created_at', 'desc');
        } else {

            $query = Ticket::onlyTrashed()->with(['ticketRows.category', 'client'])
                ->orderBy('created_at', 'desc');
        }

        // Appliquer la recherche si un terme est présent
        if (!empty($search)) {
            $query->where('reference', 'like', "%$search%")
                ->orWhereHas('client', function ($q) use ($search) {
                    $q->where('firstname', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%")
                        ->orWhere('company', 'like', "%$search%")
                        ->orWhere('lastname', 'like', "%$search%");
                });
        }

        if ($withInvoice == 1) {
            $query->where('with_tva', '=', true);
        }

        if ($timeSlot !== null) {
            if ($timeSlot === 'crenaux') {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            } else if ($timeSlot !== 'day') {

                $dates = dateFilter($request->start_date, $timeSlot);
                $query->whereBetween('created_at', [
                    Carbon::parse($dates['start'])->startOfDay(),
                    Carbon::parse($dates['end'])->endOfDay()
                ]);
            } else {

                if ($request->start_date) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->start_date)->endOfDay()
                    ]);
                }
            }
        } else {
            $query->whereDate('created_at', Carbon::today());
        }


        // $ticket = Ticket::with(['ticketRows.category', 'client'])->find($id);
        $tickets = $query->get();

        // Créer un fichier zip
        $zip = new ZipArchive();
        $zipFileName = "factures.zip";
        $zip->open(public_path($zipFileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);


        foreach ($tickets as $ticket) {
            // Générer le PDF pour chaque ticket
            $pdf = Pdf::loadView('facture', ['ticket' => $ticket, 'with_tva' => $ticket->with_tva]);

            // Ajouter le PDF dans le zip
            $year = Carbon::parse($ticket->created_at)->year;
            $suffix = $ticket->with_tva ? 'A' : '';

            $zip->addFromString("factures/facture_{$year}_{$ticket->reference}_{$suffix}.pdf", $pdf->output());
        }


        // Fermer le zip
        $zip->close();

        return response()->download(public_path($zipFileName))->deleteFileAfterSend(true);
    }
}
