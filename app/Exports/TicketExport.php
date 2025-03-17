<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\Ticket_row;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{
    // Largeur personnalisée des colonnes
    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Colonne ID
            'B' => 10,  // Colonne Montant
            'C' => 10,  // Colonne Nom du Client
            'D' => 10,  // Colonne Date
            'E' => 10
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
            'C' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']], // Rouge
            ],
        ];
    }
    // Définition des colonnes
    public function headings(): array
    {
        return [
            ['Rapport de Vente'],
            [
                '',
                'Sans n° TVA',
                '',
                'Avec n° TVA',
                ''
            ],
            [
                'Article',
                '6%',
                '21%',
                '6%',
                '21%',
            ],

        ];
    }

    // Sélection et formatage des données pour chaque ligne
    public function map($ticket): array
    {
        return [
            $ticket->id,
            $ticket->created_at->format('d/m/Y'), // Format date français
        ];
    }

    public function collection()
    {

        return Ticket_row::all();
    }
}
