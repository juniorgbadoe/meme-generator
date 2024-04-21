<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Meme;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MemeController extends Controller
{
    public function index()
{
    $memes = Meme::orderBy('created_at', 'desc')->get();
    return view('memes.index', compact('memes'));
}


    public function create()
    {
        return view('memes.create');
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
            'top_text' => 'nullable|string|max:255',
            'bottom_text' => 'nullable|string|max:255',
        ]);

        // Enregistrer l'image dans le dossier de stockage
        $imagePath = $request->file('image')->store('public/memes');

        // Superposer le texte sur l'image
        $image = Image::make(storage_path('app/' . $imagePath));
        $topText = $request->input('top_text');
        $bottomText = $request->input('bottom_text');

        if ($topText) {
            $image->text($topText, $image->width() / 2, 50, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(50);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        if ($bottomText) {
            $image->text($bottomText, $image->width() / 2, $image->height() - 50, function ($font) {
                $font->file(public_path('fonts/arial.ttf'));
                $font->size(50);
                $font->color('#000000');
                $font->align('center');
                $font->valign('bottom');
            });
        }

        // Enregistrer l'image modifiée
        $image->save();

        // Créer un nouveau Meme avec les données du formulaire
        $meme = new Meme();
        $meme->image = $imagePath;
        $meme->top_text = $topText;
        $meme->bottom_text = $bottomText;
        $meme->save();

        return redirect()->route('memes.index')->with('success', 'Meme créé avec succès.');
    }

    public function edit($id)
    {
        $meme = Meme::findOrFail($id);
        return view('memes.edit', compact('meme'));
    }

    public function update(Request $request, $id)
{
    // Recherche du Meme à mettre à jour
    $meme = Meme::findOrFail($id);

    // Validation des données du formulaire
    $request->validate([
        'image' => 'nullable|image|max:5000', 
        'top_text' => 'nullable|string|max:255',
        'bottom_text' => 'nullable|string|max:255',
    ]);

    // Remplacer l'image si un nouveau fichier est téléchargé
    if ($request->hasFile('image')) {
        // Enregistrer la nouvelle image dans le dossier de stockage
        $imagePath = $request->file('image')->store('public/memes');

        // Supprimer l'ancienne image
        Storage::delete($meme->image);

        // Mettre à jour le chemin de l'image
        $meme->image = $imagePath;
    }

    // Charger l'image à partir du chemin de stockage
    $image = Image::make(storage_path('app/' . $meme->image));

    // Ajouter le texte supérieur si présent
    if ($request->input('top_text')) {
        $image->text($request->input('top_text'), $image->width() / 2, 50, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(50);
            $font->color('#000000');
            $font->align('center');
            $font->valign('top');
        });
    }

    // Ajouter le texte inférieur si présent
    if ($request->input('bottom_text')) {
        $image->text($request->input('bottom_text'), $image->width() / 2, $image->height() - 50, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(50);
            $font->color('#000000');
            $font->align('center');
            $font->valign('bottom');
        });
    }

    // Enregistrer les modifications de l'image
    $image->save();

    // Mettre à jour les autres champs du Meme
    $meme->top_text = $request->input('top_text');
    $meme->bottom_text = $request->input('bottom_text');

    // Enregistrer les modifications dans la base de données
    $meme->save();

    // Redirection avec un message de succès
    return redirect()->route('memes.index')->with('success', 'Meme modifié avec succès.');
}



    public function destroy($id)
    {
        $meme = Meme::findOrFail($id);
        $meme->delete();

        // Redirection avec un message de succès
        return redirect()->route('memes.index')->with('success', 'Meme supprimé avec succès.');
    }
}
