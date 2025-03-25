<?php

namespace App\Exports;

use App\Models\Client;
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

class ClientExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithEvents
{

    protected $tab;

    public function __construct($tab)
    {
        $this->tab = $tab;
    }
    // Largeur personnalisée des colonnes
    public function columnWidths(): array
    {
        return [
            'A' => 3,  // Colonne ID
            'B' => 10,  // Colonne Montant
            'C' => 10,  // Colonne Nom du Client
            'D' => 20,  // Colonne Date
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 15,
            'I' => 20,
            'J' => 20,
            'K' => 20,
        ];
    }
    // Fusionner des cellules après la création de la feuille
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Fusion de la première ligne (titre)
                $event->sheet->getDelegate()->mergeCells('A1:K1');
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal('center');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(14);
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
            2 => [
                'font' => ['bold' => true],
                //border
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
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
        $filename = 'Liste des clients actifs';

        if ($this->tab === 'with_tva') {
            $filename = 'Liste des clients assujettis';
        }
        if ($this->tab === 'without_tva') {
            $filename = 'Liste des clients non assujettis';
        }
        if ($this->tab === 'deleted') {
            $filename = 'Liste des clients supprimés';
        }
        return [
            [
                $filename,
            ],
            [
                'Id',
                'Nom',
                'Prénom',
                'N° TVA',
                'Email',
                'Téléphone',
                'Adresse',
                'Ville',
                'Pays',
                'Société',
                'Date de création',
            ],


        ];
    }

    // Sélection et formatage des données pour chaque ligne
    public function map($client): array
    {
        return [
            $client->id,
            $client->lastname,
            $client->firstname,
            $client->tva_number,
            $client->email,
            $client->phone,
            $client->address,
            $client->city,
            $client->country,
            $client->company,
            $client->created_at->format('d/m/Y'), // Format date français



        ];
    }

    public function collection()
    {

        if ($this->tab == 'active') {
            return Client::all();
        }
        if ($this->tab == 'with_tva') {
            return Client::whereNotNull('tva_number')->get();
        }
        if ($this->tab == 'without_tva') {
            return Client::whereNull('tva_number')->get();
        }
        if ($this->tab == 'deleted') {
            return Client::onlyTrashed()->get();
        }
    }
}