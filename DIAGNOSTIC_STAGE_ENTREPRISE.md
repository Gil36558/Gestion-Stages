# 🔍 DIAGNOSTIC - PROBLÈME ACCÈS STAGE ENTREPRISE

## 🚨 **PROBLÈME SIGNALÉ**
Quand l'entreprise clique sur "Voir détails" d'un stage sur `/entreprise/stages`, elle est redirigée vers `/entreprise/dashboard` au lieu d'accéder à la page de détail du stage.

## 🔍 **ANALYSE DU PROBLÈME**

### **Route utilisée :**
```php
<a href="{{ route('stages.show', $stage) }}" class="btn btn-primary">
    <i class="fas fa-eye"></i>
    Voir détails
</a>
```

### **Contrôleur `StageController@show()` :**
```php
public function show(Stage $stage)
{
    // Vérification étudiant - OK
    if (Auth::user()->role === 'etudiant' && $stage->user_id !== Auth::id()) {
        abort(403, 'Accès non autorisé');
    }
    
    // Vérification entreprise - PROBLÈME POTENTIEL ICI
    if (Auth::user()->role === 'entreprise') {
        $entreprise = Auth::user()->entreprise;  // ← Peut être NULL
        if (!$entreprise || $stage->entreprise_id !== $entreprise->id) {
            abort(403, 'Accès non autorisé');  // ← REDIRECTION VERS DASHBOARD
        }
    }
    
    return view('stages.show', compact('stage'));
}
```

## 🎯 **CAUSES POSSIBLES**

### **1. Profil entreprise manquant**
L'utilisateur avec `role = 'entreprise'` n'a pas d'enregistrement correspondant dans la table `entreprises`.

### **2. Relation incorrecte**
La relation `Auth::user()->entreprise` retourne `null` à cause d'un problème de clé étrangère.

### **3. ID entreprise incorrect**
Le `stage->entreprise_id` ne correspond pas à l'ID de l'entreprise connectée.

## 🧪 **TESTS À EFFECTUER**

### **Test 1 : Vérifier la relation entreprise**
```sql
-- Vérifier si l'utilisateur entreprise a un profil
SELECT u.id, u.name, u.email, u.role, e.id as entreprise_id, e.nom as entreprise_nom
FROM users u
LEFT JOIN entreprises e ON u.id = e.user_id
WHERE u.role = 'entreprise';
```

### **Test 2 : Vérifier les stages de l'entreprise**
```sql
-- Vérifier les stages associés à l'entreprise
SELECT s.id, s.titre, s.entreprise_id, e.nom as entreprise_nom, u.name as etudiant_nom
FROM stages s
JOIN entreprises e ON s.entreprise_id = e.id
JOIN users u ON s.user_id = u.id;
```

### **Test 3 : Vérifier la cohérence**
```sql
-- Vérifier si l'entreprise connectée correspond aux stages affichés
SELECT 
    u.email as user_email,
    e.id as entreprise_id,
    e.nom as entreprise_nom,
    s.id as stage_id,
    s.titre as stage_titre
FROM users u
JOIN entreprises e ON u.id = e.user_id
JOIN stages s ON e.id = s.entreprise_id
WHERE u.role = 'entreprise';
```

## 🔧 **SOLUTIONS POSSIBLES**

### **Solution 1 : Créer le profil entreprise manquant**
Si l'utilisateur entreprise n'a pas de profil dans la table `entreprises` :
```php
// Dans le contrôleur ou via une migration
Entreprise::create([
    'user_id' => $user->id,
    'nom' => $user->name,
    'email' => $user->email,
    // autres champs...
]);
```

### **Solution 2 : Corriger la vérification dans le contrôleur**
```php
if (Auth::user()->role === 'entreprise') {
    $entreprise = Auth::user()->entreprise;
    if (!$entreprise) {
        // Rediriger vers la création du profil entreprise
        return redirect()->route('entreprise.create')
            ->with('error', 'Veuillez compléter votre profil entreprise');
    }
    if ($stage->entreprise_id !== $entreprise->id) {
        abort(403, 'Accès non autorisé');
    }
}
```

### **Solution 3 : Debug temporaire**
Ajouter du debug dans le contrôleur :
```php
if (Auth::user()->role === 'entreprise') {
    $entreprise = Auth::user()->entreprise;
    \Log::info('Debug entreprise', [
        'user_id' => Auth::id(),
        'entreprise' => $entreprise,
        'stage_entreprise_id' => $stage->entreprise_id
    ]);
    // ... reste du code
}
```

## 📋 **CHECKLIST DE VÉRIFICATION**

- [ ] L'utilisateur entreprise a-t-il un enregistrement dans la table `entreprises` ?
- [ ] La relation `user_id` dans `entreprises` correspond-elle à l'ID utilisateur ?
- [ ] Le `stage->entreprise_id` correspond-il à l'ID de l'entreprise ?
- [ ] La méthode `Auth::user()->entreprise` retourne-t-elle bien un objet ?
- [ ] Les logs Laravel montrent-ils une erreur 403 ?

## 🎯 **PROCHAINES ÉTAPES**

1. **Exécuter les requêtes SQL de test**
2. **Identifier le point de blocage exact**
3. **Appliquer la solution appropriée**
4. **Tester l'accès aux détails du stage**

---

**Status** : 🟡 **Diagnostic en cours** - Vérification des relations entreprise requise
