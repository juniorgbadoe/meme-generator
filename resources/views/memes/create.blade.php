@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer un Meme</h1>
        <div class="row">
            <!-- Colonne gauche pour le formulaire -->
            <div class="col-md-6">
                <form action="{{ route('memes.store') }}" method="POST" enctype="multipart/form-data" id="create-meme-form">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="image" class="form-label">Image du Meme :</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                        <div class="invalid-feedback" id="image-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="top-text" class="form-label">Texte supérieur :</label>
                        <input type="text" class="form-control" id="top-text" name="top_text" maxlength="255">
                    </div>
                    <div class="mb-3">
                        <label for="bottom-text" class="form-label">Texte inférieur :</label>
                        <input type="text" class="form-control" id="bottom-text" name="bottom_text" maxlength="255">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Créer Meme</button>
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

    <!-- Modal pour partager sur les réseaux sociaux -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Partager sur les réseaux sociaux</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Boutons de partage -->
                <a href="https://www.facebook.com/" class="btn btn-primary" target="_blank">Partager sur Facebook</a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}" class="btn btn-info" target="_blank">Partager sur Twitter</a>
                <a href="https://www.instagram.com" class="btn btn-danger" target="_blank">Partager sur Instagram</a>
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

        // Téléchargement de l'aperçu et affichage du modal
        document.getElementById('download-btn').addEventListener('click', function() {
            var dataURL = canvas.toDataURL('image/jpeg');
            var link = document.createElement('a');
            link.href = dataURL;
            link.download = 'meme_preview.jpg';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Afficher le modal après le téléchargement de l'image
            var myModal = new bootstrap.Modal(document.getElementById('shareModal'));
            myModal.show();
        });
    </script>
@endsection
