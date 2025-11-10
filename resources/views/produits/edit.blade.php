
    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="code_produit" class="form-label">Code</label>
            <input type="text" name="code_produit" id="code_produit"
                class="form-control"
                value="{{ old('code_produit', $produit->code_produit) }}" required>
        </div>

        <div class="mb-3">
            <label for="code_comptable" class="form-label">Code comptable</label>
            <input type="text" name="code_comptable" id="code_comptable"
                class="form-control"
                value="{{ old('code_comptable', $produit->code_comptable) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description"
                class="form-control @error('description') is-invalid @enderror"
                value="{{ old('description', $produit->description) }}" required>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="prix_ht" class="form-label">Prix HT</label>
            <input type="number" name="prix_ht" id="prix_ht" step="0.01"
                class="form-control @error('prix_ht') is-invalid @enderror"
                value="{{ old('prix_ht', $produit->prix_ht) }}" required>
            @error('prix_ht')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tva" class="form-label">TVA (%)</label>
            <input type="number" name="tva" id="tva" step="0.01"
                class="form-control @error('tva') is-invalid @enderror"
                value="{{ old('tva', $produit->tva ?? 20) }}" required>
            @error('tva')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="qt_stock" class="form-label">Stock</label>
            <input type="number" name="qt_stock" id="qt_stock"
                class="form-control @error('qt_stock') is-invalid @enderror"
                value="{{ old('qt_stock', $produit->qt_stock ?? 0) }}" required>
            @error('qt_stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="categorie" class="form-label">Catégorie</label>
            <input type="text" name="categorie" id="categorie"
                class="form-control @error('categorie') is-invalid @enderror"
                value="{{ old('categorie', $produit->categorie) }}">
            @error('categorie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        <a href="{{ route('produits.index') }}" class="btn btn-secondary"> Retour</a>
    </form>

