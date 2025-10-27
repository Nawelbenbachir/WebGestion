<?php
use App\Models\Document;
use Illuminate\Support\Facades\Route;
?>
<form method="POST" action="{{ route('lignes.store') }}">
    @csrf
    <input type="hidden" name="document_id" value="{{ $document->id }}">

    <input type="text" name="designation" placeholder="Désignation" required>
    <input type="number" step="0.01" name="prix_unitaire_ht" placeholder="Prix HT" required>
    <input type="number" name="quantite" placeholder="Quantité" required>
    
    <textarea name="commentaire" placeholder="Commentaire sur cette ligne..."></textarea>

    <button type="submit">Ajouter la ligne</button>
</form>
