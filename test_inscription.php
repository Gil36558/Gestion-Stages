<?php

// Test simple pour vérifier l'inscription étudiant
echo "=== TEST INSCRIPTION ÉTUDIANT ===\n";

// Simuler les données d'un étudiant
$etudiantData = [
    'role' => 'etudiant',
    'email' => 'test.etudiant@example.com',
    'telephone' => '0123456789',
    'prenom' => 'Jean',
    'nom' => 'Dupont',
    'universite' => 'Université de Test',
    'domaine' => 'Informatique',
    'niveau' => 'Bac+3',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!'
];

echo "Données étudiant:\n";
foreach ($etudiantData as $key => $value) {
    echo "- $key: $value\n";
}

echo "\n=== TEST INSCRIPTION ENTREPRISE ===\n";

// Simuler les données d'une entreprise
$entrepriseData = [
    'role' => 'entreprise',
    'email' => 'contact@entreprise-test.com',
    'telephone' => '0987654321',
    'nom_entreprise' => 'Entreprise Test',
    'secteur' => 'Technologie',
    'taille' => '11-50',
    'adresse' => '123 Rue de Test, 75000 Paris',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!'
];

echo "Données entreprise:\n";
foreach ($entrepriseData as $key => $value) {
    echo "- $key: $value\n";
}

echo "\n=== VÉRIFICATION DES CHAMPS REQUIS ===\n";

// Vérifier les champs requis pour étudiant
$requiredEtudiant = ['role', 'email', 'telephone', 'prenom', 'nom', 'universite', 'domaine', 'niveau', 'password'];
echo "Champs requis pour étudiant:\n";
foreach ($requiredEtudiant as $field) {
    $status = isset($etudiantData[$field]) && !empty($etudiantData[$field]) ? '✓' : '✗';
    echo "$status $field\n";
}

echo "\nChamps requis pour entreprise:\n";
$requiredEntreprise = ['role', 'email', 'telephone', 'nom_entreprise', 'secteur', 'taille', 'adresse', 'password'];
foreach ($requiredEntreprise as $field) {
    $status = isset($entrepriseData[$field]) && !empty($entrepriseData[$field]) ? '✓' : '✗';
    echo "$status $field\n";
}

echo "\n=== TEST TERMINÉ ===\n";
