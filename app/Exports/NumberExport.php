<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NumberExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    // Largeur personnalisée des colonnes
    public function columnWidths(): array
    {

        return [
            'A' => 15,  // Colonne ID
            'B' => 10,  // Colonne Montant
            'C' => 10,  // Colonne Nom du Client
            'D' => 10,  // Colonne Date
            'E' => 10,
            'F' => 15,
        ];
    }
    // Fusionner des cellules après la création de la feuille
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Fusion de la première ligne (titre)
                $event->sheet->getDelegate()->mergeCells('A1:E1');
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(14);

                // style de la deuxieme ligne, premiere colonne
                $event->sheet->getDelegate()->getStyle('A2')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('A2')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getDelegate()->getStyle('E2')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('E2')->getFont()->setBold(true)->setSize(14);

                // Fusion de B2:C2
                $event->sheet->getDelegate()->mergeCells('B2:C2');
                $event->sheet->getDelegate()->getStyle('B2')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('B2')->getFont()->setBold(true)->setSize(14);

                // Fusion de D2:E2
                $event->sheet->getDelegate()->mergeCells('D2:E2');
                $event->sheet->getDelegate()->getStyle('D2')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('D2')->getFont()->setBold(true)->setSize(14);
            },
        ];
    }

    // Ajout du style au fichier Excel
    public function styles(Worksheet $sheet)
    {
        return [
            // Style de l'en-tête
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4CAF50']], // Vert
                'alignment' => ['horizontal' => 'center'],
            ],

            // Colonnes spécifiques (ex: la colonne 3 = Montant)
            // 'C' => [
            //     'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']], // Rouge
            // ],
        ];
    }
    // Définition des colonnes
    public function headings(): array
    {
        return [
            ['Rapport des montants journaliers'],
            [
                'Clients',
                'Sans n° TVA',
                '',
                'Avec n° TVA',
                '',
            ],
            [
                '#',
                '6%',
                '21%',
                '6%',
                '21%',
            ],

        ];
    }

    // Sélection et formatage des données pour chaque ligne
    public function map($dayAmount): array
    {
        // dd($dayAmount->ticket);
        return [

            $dayAmount->day, // Format date français
            $dayAmount->total_6_na,
            $dayAmount->total_21_na,
            $dayAmount->total_6_a,
            $dayAmount->total_21_a,

        ];
    }


    public function collection()
    {
        $timeSlot = $this->request['time_slot'];
        $startDay = null;
        $endDay = null;

        if ($timeSlot !== null) {
            if ($timeSlot === 'crenaux') {

                $startDay =   Carbon::parse($this->request['start_date'])->startOfDay();
                $endDay =   Carbon::parse($this->request['end_date'])->endOfDay();
            } else if ($timeSlot !== 'day') {

                $dates = dateFilter($this->request['start_date'], $timeSlot);

                $startDay = Carbon::parse($dates['start'])->startOfDay();
                $endDay =   Carbon::parse($dates['end'])->endOfDay();
            } else {
                $startDay =  Carbon::parse($this->request['start_date'])->startOfDay();
                $endDay =  Carbon::parse($this->request['start_date'])->endOfDay();
            }
        } else {
            $startDay =  Carbon::parse($this->request['start_date'])->startOfDay();
            $endDay =  Carbon::parse($this->request['start_date'])->endOfDay();
        }
        // Étape 1 : Récupérer les données existantes
        $dailyAmount = DB::table('tickets')
            ->join('ticket_rows', 'tickets.id', '=', 'ticket_rows.ticket_id')
            ->join('categories', 'ticket_rows.category_id', '=', 'categories.id')
            ->leftJoin('clients', 'tickets.client_id', '=', 'clients.id')
            ->select(
                DB::raw('DATE(tickets.created_at) as day'),
                DB::raw("SUM(CASE WHEN categories.tva = 6 AND tickets.with_tva = 0 THEN ticket_rows.price * ticket_rows.quantity * (1 - ticket_rows.reduction / 100.0) ELSE 0 END) as total_6_na"),
                DB::raw("SUM(CASE WHEN categories.tva = 21 AND tickets.with_tva = 0 THEN ticket_rows.price * ticket_rows.quantity * (1 - ticket_rows.reduction / 100.0) ELSE 0 END) as total_21_na"),
                DB::raw("SUM(CASE WHEN categories.tva = 6 AND tickets.with_tva = 1 THEN ticket_rows.price * ticket_rows.quantity * (1 - ticket_rows.reduction / 100.0) ELSE 0 END) as total_6_a"),
                DB::raw("SUM(CASE WHEN categories.tva = 21 AND tickets.with_tva = 1 THEN ticket_rows.price * ticket_rows.quantity * (1 - ticket_rows.reduction / 100.0) ELSE 0 END) as total_21_a")
            )

            ->whereBetween('tickets.created_at', [
                $startDay,
                $endDay
            ])
            ->groupBy(DB::raw('DATE(tickets.created_at)'))
            ->orderBy('day', 'desc')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->day)->toDateString(); // Format de date en chaîne correcte
            });



        $fullData = collect();

        // Initialisation des totaux
        $total6na = 0;
        $total21na = 0;
        $total6a = 0;
        $total21a = 0;

        for ($date = $startDay->copy(); $date <= $endDay; $date->addDay()) {
            $formattedDate = $date->toDateString();

            if ($dailyAmount->has($formattedDate)) {
                $entry = $dailyAmount[$formattedDate];
                $entry->day = $date->format('d/m/Y');

                // Accumuler les totaux
                $total6na += $entry->total_6_na;
                $total21na += $entry->total_21_na;
                $total6a += $entry->total_6_a;
                $total21a += $entry->total_21_a;

                $fullData->push($entry);
            } else {
                $fullData->push((object)[
                    'total_6_na' => '/',
                    'total_21_na' => '/',
                    'total_6_a' => '/',
                    'total_21_a' => '/',
                    'day' => $date->format('d/m/Y')
                ]);
            }
        }

        // Ajouter la ligne de total à la fin
        $fullData->push((object)[
            'total_6_na' => $total6na,
            'total_21_na' => $total21na,
            'total_6_a' => $total6a,
            'total_21_a' => $total21a,
            'day' => 'TOTAL'
        ]);

        return $fullData;
    }
}
