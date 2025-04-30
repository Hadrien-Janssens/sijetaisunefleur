<?php

namespace App\Http\Middleware;

use App\Models\Ticket;
use App\Models\Ticket_row;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        $dailyAmount = 0;

        $tickets = Ticket::with('ticketRows')->whereDate('created_at', today())->get();

        foreach ($tickets as $key => $ticket) {
            foreach ($ticket->ticketRows as $key => $row) {
                if ($ticket->remise > 0) {
                    if ($row->reduction > 0) {
                        $dailyAmount += ($row->price - ($row->price * $row->reduction) / 100) * $row->quantity;
                    } else {
                        $dailyAmount += ($row->price - ($row->price * $ticket->remise) / 100) * $row->quantity;
                    }
                } else {
                    if ($row->reduction > 0) {
                        $dailyAmount += ($row->price - ($row->price * $row->reduction) / 100) * $row->quantity;
                    } else {
                        $dailyAmount += $row->price * $row->quantity;
                    }
                }
            }
            if ($ticket->remiseAmount > 0) {
                $dailyAmount -= $ticket->remiseAmount;
            }
        }



        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
            ],
            'dailyAmount' => $dailyAmount,
        ];
    }
}
