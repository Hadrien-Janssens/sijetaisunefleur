<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Facture client</title>
</head>


<body>
    <header style="width: 100%; line-height: 0.5em;">
        <div>
            <div style="float: left;">


                {{-- <img style="margin-bottom : 30px;" src="{{ asset('/public/logo.jpg') }}" alt="si j'étais une fleur"
                    width="200"> --}}
                <p style="margin-bottom : 30px; font-weight:bold ">SI J'ETAIS UNE FLEUR</p>
                <p>Rue de l'Espinette</p>
                <p>7160 Godarville</p>
                <p>Tél : +32 497/40 27 45</p>
                <p>Email : contact@sijetaisunefleur.com</p>
                <p>TVA : BE0535 542 047</p>
                <p>BELFIUS IBAN : BE06 0688 9751 1422</p>
            </div>

            <div style="float: right; font-weight: bold; ">
                <p>Facture n°
                    {{ $ticket->created_at->format('Y') }}-{{ str_pad($ticket->reference, 3, '0', STR_PAD_LEFT) }}{{ $ticket->with_tva ? 'A' : '' }}
                </p>
                <br><br>
                @if ($ticket->client)
                    <div style="width: 300px; word-wrap: break-word; line-height: 1.1em;">
                        <p>Client : {{ $ticket->client->company }} c/o {{ $ticket->client->firstname }}
                            {{ $ticket->client->lastname }}</p>
                        <p>Adresse : {{ $ticket->client->address }}</p>
                        <p>{{ $ticket->client->city }}</p>
                    </div>
                @endif
            </div>
        </div>
    </header>

    {{-- table headers --}}
    <table style="margin-top: 260px; border: 1px solid black; width: 100%;">
        <thead>
            <tr>
                @if ($ticket->client && $ticket->client->company)
                    <th>Client : {{ $ticket->client->company }} </th>
                @endif
                @if ($with_tva)
                    @if (isset($tva_number))
                        <th>N° TVA : {{ $tva_number }} </th>
                    @else
                        <th>N° TVA : {{ $ticket->client->tva_number }} </th>
                    @endif
                @endif
                <th>Date : {{ $ticket->created_at->format('Y-m-d') }}</th>

            </tr>
        </thead>
    </table>

    {{-- main table --}}
    <table style="margin-top: 20px; border: 1px solid black; width: 100%;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Qté</th>
                <th>Prix Unit (htva)</th>
                <th>Remise</th>
                <th>Prix Total (htva)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket->ticketRows as $row)
                <tr>
                    <th>{{ $row->category->name }}</th>
                    <th>{{ $row->quantity }}</th>
                    <th>{{ number_format($row->price / (1 + $row->category->tva / 100), 2) }} €</th>
                    <th>0%</th>
                    <th>{{ number_format($row->price / (1 + $row->category->tva / 100), 2) * $row->quantity }} €</th>
                </tr>
            @endforeach
            @if ($ticket->remiseAmount)
                <tr>
                    <th>Remise</th>
                    <th></th>
                    <th></th>
                    <th style="font-weigth: bold ;"> -{{ $ticket->remiseAmount }}€</th>
                    <th></th>
                </tr>
            @endif

        </tbody>
    </table>

    {{-- COMMENTAIRE --}}
    @if ($ticket->comment)
        <p style="margin-top: 50px">Commentaire : {{ $ticket->comment }}</p>
    @endif

    {{-- footer --}}
    <footer style="margin-top: 150px">
        @php
            $base = 0;
            $base21 = 0;
            $base6 = 0;
            // $tvaTotal = 0;
            $tva21 = 0;
            $tva6 = 0;

            foreach ($ticket->ticketRows as $row) {
                $base += number_format($row->price / (1 + $row->category->tva / 100), 2) * $row->quantity;
                // $tvaTotal +=
                //     $row->price - number_format($row->price / (1 + $row->category->tva / 100), 2) * $row->quantity;
                if ($row->category->tva == 21) {
                    $base21 += number_format($row->price / (1 + $row->category->tva / 100), 2) * $row->quantity;
                }
                if ($row->category->tva == 6) {
                    $base6 += number_format($row->price / (1 + $row->category->tva / 100), 2) * $row->quantity;
                }
            }
            $tva6 = $base6 * 0.06;
            $tva21 = $base21 * 0.21;
        @endphp
        <p style="font-weight: bold; ">TOTAL € {{ $base }} hTVA</p>
        <p>Pour acquis : (cash/banconact)</p>

        <table style="margin-top: 50px; border: 1px solid black; width: 100%;">
            <thead>
                <tr>
                    <th>Taux</th>
                    <th>(0) 0%</th>
                    <th>(1) 6%</th>
                    <th>(2) 12%</th>
                    <th>(3) 21%</th>
                    <th>Totaux</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Base</th>
                    <th>€ -</th>
                    <th>€ {{ $base6 }} </th>
                    <th>€ -</th>
                    <th>€ {{ $base21 }}</th>
                    <th>€ {{ $base }}</th>
                </tr>
                <tr>
                    <th>TVA</th>
                    <th>€ -</th>
                    <th>€ {{ number_format($tva6, 2) }}</th>
                    <th>€ -</th>
                    <th>€ {{ number_format($tva21, 2) }}</th>
                    <th>€ {{ number_format($tva6 + $tva21, 2) }}</th>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                        {{ !$ticket->is_paid && $ticket->echeance ? $ticket->echeance : 'Echéance : Acquitté' }}</th>
                    <th> A PAYER <br> € {{ number_format($base + $tva6 + $tva21, 2) }}</th>
                </tr>
        </table>
        <p style="text-align: right">Exemplaire client</p>
    </footer>
</body>

</html>

<style>
    html {
        font-family: sans-serif;
    }

    table {
        border-collapse: collapse;
    }

    th {
        border: 1px solid black;

    }

    tbody th {
        font-weight: normal;
    }
