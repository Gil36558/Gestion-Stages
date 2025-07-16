<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

public function index()
{
    $documents = auth()->user()->documents;
    return view('etudiant.documents.index', compact('documents'));
}

public function store(Request $request)
{
    $request->validate([
        'fichier' => 'required|file|max:2048',
        'type' => 'nullable|string|max:255'
    ]);

    $path = $request->file('fichier')->store('documents', 'public');

    Document::create([
        'user_id' => auth()->id(),
        'nom' => $request->file('fichier')->getClientOriginalName(),
        'chemin' => $path,
        'type' => $request->type,
    ]);

    return redirect()->back()->with('success', 'Document uploadé avec succès.');
}
