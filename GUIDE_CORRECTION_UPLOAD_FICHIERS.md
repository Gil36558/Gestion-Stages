# 🔧 GUIDE DE CORRECTION - PROBLÈME UPLOAD FICHIERS

## 🚨 **PROBLÈME IDENTIFIÉ**
Les fichiers ne s'enregistrent pas en base de données (valeurs NULL) à cause de :
1. **Lien symbolique manquant** pour le stockage public
2. **Gestion incorrecte des uploads** dans les contrôleurs
3. **Dossiers de stockage manquants**

## ✅ **CORRECTIONS APPLIQUÉES**

### 1. **Lien symbolique créé**
```bash
php artisan storage:link
```
✅ **Résultat** : Le lien `public/storage` → `storage/app/public` est maintenant actif

### 2. **Dossiers de stockage créés**
```bash
mkdir -p storage/app/public/{candidatures/{cv,lettres},stages/{rapports,attestations},journal,logos/entreprises,documents}
```
✅ **Structure créée** :
- `storage/app/public/candidatures/cv/`
- `storage/app/public/candidatures/lettres/`
- `storage/app/public/stages/rapports/`
- `storage/app/public/stages/attestations/`
- `storage/app/public/journal/`
- `storage/app/public/logos/entreprises/`
- `storage/app/public/documents/`

### 3. **DemandeStageController corrigé**
✅ **Avant** (problématique) :
```php
$cv = $request->file('cv')?->store('demandes/cv', 'public');
```

✅ **Après** (corrigé) :
```php
$cv = null;
if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
    $cv = $request->file('cv')->store('demandes/cv', 'public');
}
// Ajouter seulement si le fichier existe
if ($cv) $donnees['cv'] = $cv;
```

## 🔍 **CONTRÔLEURS À CORRIGER**

### **CandidatureController.php**
**Ligne ~75-82** - Remplacer :
```php
if ($request->hasFile('cv')) {
    $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
}
```
**Par** :
```php
if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
    $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
}
```

### **StageController.php**
**Lignes ~115 et ~155** - Vérifier que les uploads utilisent :
```php
if ($request->hasFile('rapport_stage') && $request->file('rapport_stage')->isValid()) {
    $path = $request->file('rapport_stage')->store('stages/rapports', 'public');
    $updateData['rapport_stage'] = $path;
}
```

### **EntrepriseController.php**
**Upload de logo** - Vérifier :
```php
if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
    $validated['logo'] = $request->file('logo')->store('logos/entreprises', 'public');
}
```

### **JournalStageController.php**
**Upload de fichiers multiples** - Vérifier :
```php
if ($request->hasFile('fichiers')) {
    foreach ($request->file('fichiers') as $fichier) {
        if ($fichier->isValid()) {
            $path = $fichier->store('journal/' . $stage->id, 'public');
            // ...
        }
    }
}
```

## 🧪 **TESTS À EFFECTUER**

### **Test 1 : Demande de stage**
1. Aller sur `/demande-stage/form?type=academique&entreprise_id=4`
2. Remplir le formulaire avec tous les fichiers requis
3. Soumettre et vérifier en base que les champs ne sont plus NULL

### **Test 2 : Candidature à une offre**
1. Aller sur une offre de stage
2. Candidater avec CV et lettre de motivation
3. Vérifier que les fichiers sont bien enregistrés

### **Test 3 : Upload journal de stage**
1. Accéder au journal d'un stage en cours
2. Ajouter une entrée avec fichiers joints
3. Vérifier l'enregistrement

## 🔧 **COMMANDES DE VÉRIFICATION**

### **Vérifier le lien symbolique**
```bash
ls -la public/storage
# Doit afficher : public/storage -> ../storage/app/public
```

### **Vérifier les permissions**
```bash
ls -la storage/app/public/
# Tous les dossiers doivent être accessibles en écriture
```

### **Vérifier les uploads en base**
```sql
SELECT cv, lettre_motivation, piece_identite FROM demandes_stages WHERE id = 1;
-- Ne doit plus afficher NULL pour les fichiers uploadés
```

## 🚀 **RÉSULTAT ATTENDU**

Après ces corrections :
- ✅ Les fichiers s'uploadent correctement
- ✅ Les chemins s'enregistrent en base de données
- ✅ Les fichiers sont accessibles via `/storage/...`
- ✅ Aucune valeur NULL pour les uploads réussis

## 🔄 **PROCHAINES ÉTAPES**

1. **Tester chaque formulaire d'upload**
2. **Vérifier les téléchargements de fichiers**
3. **Contrôler les permissions d'accès aux fichiers**
4. **Optimiser la validation des types de fichiers**

---

**Status** : 🟡 **Corrections partielles appliquées** - Tests requis pour validation complète
