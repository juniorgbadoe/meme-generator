@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Editer le Meme</h1>
        <div class="row">
            <!-- Colonne gauche pour le formulaire -->
            <div class="col-md-6">
                <form action="{{ route('memes.update', $meme->id) }}" method="POST" enctype="multipart/form-data" id="edit-meme-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                <label for="image">Image du Meme :</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage()">
                @if($meme->image)
                    <img id="image-preview" src="{{ asset('storage/memes/' . basename($meme->image)) }}" class="card-img-top" alt="Meme Image">
                @else
                    <p>Aucune image sélectionnée</p>
                @endif
            </div>
                    <div class="mb-3">
                        <label for="top-text" class="form-label">Texte supérieur :</label>
                        <input type="text" class="form-control" id="top-text" name="top_text" value="{{ $meme->top_text }}" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="bottom-text" class="form-label">Texte inférieur :</label>
                        <input type="text" class="form-control" id="bottom-text" name="bottom_text" value="{{ $meme->bottom_text }}" maxlength="255">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Editer Meme</button>
                    <button type="button" class="btn btn-success" id="download-btn">Télécharger Aperçu</button>
                </form>
            </div>
            <!-- Colonne droite pour l'aperçu de l'image -->
            <div class="col-md-6">
                <h2>Aperçu de l'image</h2>
                <div class="image-preview-container">
                    <canvas id="image-canvas" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        var canvas = document.getElementById('image-canvas');
        var ctx = canvas.getContext('2d');

        // Taille de police par défaut
        var textSize = 50;

        function drawImageWithText() {
            ctx.clearRect(0, 0, canvas.width, canvas.height); // Effacer le canvas

            var img = new Image();
            img.onload = function() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                var topText = document.getElementById('top-text').value;
                var bottomText = document.getElementById('bottom-text').value;
                var textColor = '#000000'; // Couleur du texte en noir

                ctx.fillStyle = textColor;
                ctx.font = textSize + 'px Arial';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'top';
                ctx.fillText(topText, canvas.width / 2, 10); // Texte supérieur
                ctx.textBaseline = 'bottom';
                ctx.fillText(bottomText, canvas.width / 2, canvas.height - 10); // Texte inférieur
            };

            var fileInput = document.getElementById('image');
            var file = fileInput.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // Appeler drawImageWithText() lors de la modification des champs
        document.getElementById('image').addEventListener('change', drawImageWithText);
        document.getElementById('top-text').addEventListener('input', drawImageWithText);
        document.getElementById('bottom-text').addEventListener('input', drawImageWithText);

        // Téléchargement de l'aperçu
        document.getElementById('download-btn').addEventListener('click', function() {
            var dataURL = canvas.toDataURL('image/jpeg');
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = 'meme_preview.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
@endsection
