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
                    {{ $ticket->created_at->format('Y') }}-{{ $ticket->reference }}{{ $ticket->with_tva ? 'A' : '' }}
                </p>
                <br><br>
                <div style="width: 300px; word-wrap: break-word; line-height: 1.1em;">
                    <p>Client : {{ $ticket->client->company }} c/o {{ $ticket->client->firstname }}
                        {{ $ticket->client->lastname }}</p>
                    <p>Adresse : {{ $ticket->client->address }}</p>
                    <p>{{ $ticket->client->city }}</p>
                </div>
            </div>
        </div>
    </header>

    {{-- table headers --}}
    <table style="margin-top: 260px; border: 1px solid black; width: 100%;">
        <thead>
            <tr>
                <th>Client : {{ $ticket->client->company }} </th>
                <th>N° TVA : {{ $ticket->client->tva_number }} </th>
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
                <th>Prix Unit</th>
                <th>Remise</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ticket->ticketRows as $row)
                <tr>
                    <th>{{ $row->category->name }}</th>
                    <th>{{ $row->quantity }}</th>
                    <th>€ {{ $row->price }}</th>
                    <th>0%</th>
                    <th>€ {{ $row->price * $row->quantity }}</th>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- footer --}}
    <footer style="margin-top: 250px">
        <p style="font-weight: bold; ">TOTAL € 118,84 hTVA</p>
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
                    <th>€ -</th>
                    <th>€ -</th>
                    <th>€ 118,84</th>
                    <th>€ 118,84</th>
                </tr>
                <tr>
                    <th>TVA</th>
                    <th>€ -</th>
                    <th>€ -</th>
                    <th>€ -</th>
                    <th>€ 24,96</th>
                    <th>€ 24,96</th>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">Echéance : Acquitté</th>
                    <th> A PAYER <br> € 143,80</th>
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
