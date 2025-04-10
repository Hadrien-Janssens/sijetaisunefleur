<img src="https://sijetaisunefleur.hadrien-janssens.com/logo.jpg" alt="si j'étais une fleur" width="100%"
    style="display: block; margin: 0 auto;">
<p>Bonjour @if ($ticket?->client?->firstname && $ticket?->client?->lastname)
        <span style="font-weight: bold"> {{ $ticket?->client?->firstname }} {{ $ticket?->client?->lastname }} </span>
    @endif,</p>
<p>Veuillez trouver ci-joint votre facture <span style="font-weight: bold">N°
        {{ $ticket->created_at->format('Y') }}-{{ str_pad($ticket->reference, 3, '0', STR_PAD_LEFT) }}@if ($ticket?->with_tva)
            -A
        @endif
    </span>
</p>
<p>Nous vous remercions de votre confiance et vos achats dans notre magasin. <br>
    Nous restons à votre disposition, n'hésitez pas à revenir vers nous via l'adresse contact@sijetaisunefleur.com si
    besoin.</p>

<p>Bien à vous ,</p>

<p>Si j'étais une fleur,</p>
<p>Virginie Wautié</p>
<br>

<div>

    <button
        style="        background-color: rgb(162, 207, 88);
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 5px;
        transition: 300ms;"><a
            href="https://www.sijetaisunefleur.com/" style="  text-decoration: none;
        color: white;"> Visiter le
            site web </a></button>
    <button
        style="        background-color: #1877F2;
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
    transition: 300ms;"><a
            style="  text-decoration: none;
        color: white;"
            href="https://www.facebook.com/sijetaisunefleurgodarville?locale=fr_FR"> Visiter la page facebook
        </a></button>
</div>

<p>**Ceci est une boite mail automatique, merci de ne pas y répondre.** <br>
    pour toute question, merci de contacter cette adresse : ✉️ contact@sijetaisunefleur.com</p>
