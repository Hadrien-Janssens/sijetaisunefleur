<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

            <div style="float: right; font-weight: bold;">
                <p>Facture n° 2024-054A</p>
                <br><br>
                <p>Client : DFA1 c/o Charles Renard</p>
                <p>Adesse : Noorderplaats 5 bus 2</p>
                <p>2000 Antwerpen</p>
            </div>
        </div>
    </header>

    {{-- table headers --}}
    <table style="margin-top: 260px; border: 1px solid black; width: 100%;">
        <thead>
            <tr>
                <th>Client : DFA </th>
                <th>N° TVA : BE0 506 543 443 </th>
                <th>Date : 28/03/2024</th>
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
            <tr>
                <th>Décoration</th>
                <th>1</th>
                <th>€ 118,84</th>
                <th>0%</th>
                <th>€ 118,84</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>€ -</th>
                <th></th>
                <th>€ -</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>€ -</th>
                <th></th>
                <th>€ -</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>€ -</th>
                <th></th>
                <th>€ -</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>€ -</th>
                <th></th>
                <th>€ -</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>€ -</th>
                <th></th>
                <th>€ -</th>
            </tr>
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
