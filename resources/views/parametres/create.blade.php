

    <form action="{{ route('societes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="code_societe" class="form-label">Code société *</label>
            <input type="text" name="code_societe" class="form-control" value="{{ old('code_societe') }}" required>
        </div>

        <div class="mb-3">
            <label for="nom_societe" class="form-label">Nom *</label>
            <input type="text" name="nom_societe" class="form-control" value="{{ old('nom_societe') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" name="ville" class="form-control" value="{{ old('ville') }}">
        </div>

        <div class="mb-3">
            <label for="pays" class="form-label">Pays</label>
            <input type="text" name="pays" class="form-control" value="{{ old('pays', 'France') }}">
        </div>
        <div class="mb-3">
        <label for="iban" class="form-label">IBAN</label>
        <input type="text" name="iban" id="iban"
            class="form-control @error('iban') is-invalid @enderror"
            value="{{ old('iban') }}" maxlength="34" placeholder="FR76..." >
        @error('iban') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label for="swift" class="form-label">SWIFT / BIC</label>
        <input type="text" name="swift" id="swift"
            class="form-control @error('swift') is-invalid @enderror"
            value="{{ old('swift') }}" maxlength="11" placeholder="BNPAFRPP" >
        @error('swift') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('societes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>

