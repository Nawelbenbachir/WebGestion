<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $document->code_document }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 40px;
        }
        .header table {
            width: 100%;
        }
        .societe-info {
            width: 50%;
        }
        .client-info {
            width: 50%;
            text-align: right;
        }
        .client-box {
            display: inline-block;
            text-align: left;
            padding: 15px;
            border: 1px solid #eee;
            background: #fafafa;
            min-width: 250px;
        }
        .doc-details {
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .doc-title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.items th {
            background: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        table.items td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .totals {
            width: 100%;
        }
        .totals-table {
            width: 250px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 8px 0;
        }
        .totals-table .label {
            text-align: left;
            font-weight: bold;
        }
        .totals-table .value {
            text-align: right;
        }
        .totals-table .grand-total {
            font-size: 16px;
            border-top: 2px solid #333;
            padding-top: 10px;
            color: #d9480f;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .invoice-date {
        text-align: right;
        width: 50%;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td class="societe-info">
                    <strong>{{ $societe->nom_societe ?? 'Ma Super Entreprise' }}</strong><br>
                    {{ $societe->adresse1 ?? '123 Rue du Commerce' }}<br>
                    {{ $societe->code_postal ?? '75000' }} {{ $societe->ville ?? 'Paris' }}<br>
                    Tél : {{ $societe->telephone ?? '01 02 03 04 05' }}
                </td>
                <td class="client-info">
                    <div class="client-box">
                        <small>Facturé à :</small><br>
                        <strong>{{ $document->client_nom }}</strong><br>
                        {{ $client->adresse1 ?? 'Adresse client non renseignée' }}<br>
                        {{ $client->code_postal }} {{ $client->ville }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-details">
        <table>
            <tr>
                <td class="doc-title">
                    Facture n° {{ $document->code_document }}
                    
                </td>
                <td class="invoice-date">
                    Date : {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center; width: 80px;">Qté</th>
                <th style="text-align: right; width: 100px;">P.U. HT</th>
                <th style="text-align: center; width: 60px;">TVA</th>
                <th style="text-align: right; width: 100px;">Total TTC</th>
            </tr>
        </thead>
        <tbody>
            
            @foreach($document->lignes as $ligne)
            <tr>
                <td>
                    {{ $ligne->designation }}<br>
                    <small style="color: #666;">Ref: {{ $ligne->description }}</small>
                </td>
                <td style="text-align: center;">{{ $ligne->quantite }}</td>
                <td style="text-align: right;">{{ number_format($ligne->prix_unitaire_ht, 2, ',', ' ') }} €</td>
                <td style="text-align: center;">{{ $ligne->taux_tva }}%</td>
                <td style="text-align: right;">{{ number_format($ligne->total_ttc, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table class="totals-table">
            <tr>
                <td class="label">Total HT</td>
                <td class="value">{{ number_format($document->total_ht, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td class="label">TVA</td>
                <td class="value">{{ number_format($document->total_tva, 2, ',', ' ') }} €</td>
            </tr>
            <tr class="grand-total">
                <td class="label">TOTAL TTC</td>
                <td class="value"><strong>{{ number_format($document->total_ttc, 2, ',', ' ') }} €</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        {{ $societe->nom_societe ?? 'Ma Super Entreprise' }} - 
        SIRET : {{ $societe->siret ?? '123 456 789 00012' }} - 
        TVA Intra : {{ $societe->tva ?? 'FR 12 3456789' }}
    </div>

</body>
</html>