# 🚨 DIAGNOSTIC CANDIDATURES - PROBLÈME IDENTIFIÉ

## 🔍 **PROBLÈME TROUVÉ**
La configuration PHP limite la taille des fichiers uploadés :
- `upload_max_filesize = 2M` (trop petit)
- `post_max_size = 8M` (correct)

Le contrôleur Laravel accepte jusqu'à 5MB mais PHP refuse les fichiers > 2MB.

## ✅ **SOLUTION IMMÉDIATE**
Réduire la validation Laravel à 2MB pour correspondre à la limite PHP :

```php
// Dans CandidatureController@store()
'cv' => 'required|file|mimes:pdf,doc,docx|max:2048', // 2MB au lieu de 5120
'lettre' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
```

## 🔧 **SOLUTION PERMANENTE**
Modifier la configuration PHP dans php.ini :
```ini
upload_max_filesize = 6M
post_max_size = 10M
max_file_uploads = 20
```

## 🎯 **ACTION IMMÉDIATE**
Je vais corriger la validation pour permettre les candidatures avec des fichiers ≤ 2MB.
