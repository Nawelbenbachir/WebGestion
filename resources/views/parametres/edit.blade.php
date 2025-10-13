@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Modifier la société : {{ $societe->nom_societe }}</h2>

    <form action="{{ route('societes.update', $societe->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom_societe" class="form-label">Nom *</label>
            <input type="text" name="nom_societe" class="form-control" value="{{ old('nom_societe', $societe->nom_societe) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $societe->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control" value="{{ old('ville', $societe->ville) }}">
        </div>

        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" name="pays" class="form-control" value="{{ old('pays', $societe->pays) }}">
        </div>
        <div class="mb-3">
        <label for="iban" class="form-label">IBAN</label>
        <input type="text" name="iban" id="iban"
            class="form-control @error('iban') is-invalid @enderror"
            value="{{ old('iban', $societe->iban) }}" maxlength="34" placeholder="FR76..." >
        @error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="swift" class="form-label">SWIFT / BIC</label>
        <input type="text" name="swift" id="swift"
            class="form-control @error('swift') is-invalid @enderror"
            value="{{ old('swift', $societe->swift) }}" maxlength="11" placeholder="BNPAFRPP" >
        @error('swift') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('societes.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection
