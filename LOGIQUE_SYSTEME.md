# 🎯 LOGIQUE DU SYSTÈME DE GESTION DE STAGES

## 📋 VUE D'ENSEMBLE

Le système distingue clairement **OFFRES** et **DEMANDES** selon deux flux différents :

---

## 🏢 FLUX OFFRES (Entreprises → Étudiants)

### **Qui :** Entreprises
### **Quoi :** Publication d'offres de stage avec détails spécifiques
### **Comment :** Les étudiants consultent et candidatent
### **Résultat :** Candidature → Acceptation/Refus → Stage automatique

### 🔄 Processus :
1. **Entreprise** publie une offre (`/entreprise/offres/create`)
2. **Étudiant** voit l'offre (`/offres`)
3. **Étudiant** candidate avec CV + lettre (`/offres/{id}` → modal)
4. **Entreprise** reçoit la candidature (`/entreprise/demandes`)
5. **Entreprise** accepte/refuse
6. **Si accepté** → Stage créé automatiquement

### 📊 Tables impliquées :
- `offres` (titre, description, compétences, lieu, dates, etc.)
- `candidatures` (user_id, offre_id, cv, lettre, statut, etc.)
- `stages` (créé automatiquement si candidature acceptée)

---

## 🎓 FLUX DEMANDES (Étudiants → Entreprises)

### **Qui :** Étudiants
### **Quoi :** Demandes directes de stage (académique/professionnel)
### **Comment :** Formulaire complet avec infos personnelles/académiques
### **Résultat :** Demande → Validation/Refus → Stage manuel

### 🔄 Processus :
1. **Étudiant** fait une demande directe (`/demande-stage/choix`)
2. **Étudiant** remplit le formulaire complet (`/demande-stage/form`)
3. **Entreprise** reçoit la demande (`/entreprise/demandes`)
4. **Entreprise** valide/refuse
5. **Si validé** → Stage créé automatiquement

### 📊 Tables impliquées :
- `demandes_stages` (type, objet, période, infos complètes, etc.)
- `demande_stage_etudiant` (pivot étudiant ↔ demande)
- `stages` (créé automatiquement si demande validée)

---

## 🎯 INTERFACE ENTREPRISE UNIFIÉE

### 📍 Route principale : `/entreprise/demandes`
### 🎨 Vue : `resources/views/entreprise/demandes.blade.php`

**Onglets :**
1. **Candidatures aux offres** (flux offres)
2. **Demandes directes** (flux demandes)

**Actions disponibles :**
- ✅ Accepter/Valider
- ❌ Refuser
- 👁️ Voir détails
- 📥 Télécharger documents
- 🔄 Filtrer par statut

---

## 🚀 CRÉATION AUTOMATIQUE DE STAGES

### Depuis une candidature acceptée :
```php
Stage::create([
    'user_id' => $candidature->user_id,
    'entreprise_id' => $offre->entreprise_id,
    'candidature_id' => $candidature->id,
    'titre' => $offre->titre,
    'description' => $offre->description,
    'date_debut' => $offre->date_debut,
    'date_fin' => $offre->date_fin,
    'lieu' => $offre->lieu,
    'statut' => 'en_attente_debut',
]);
```

### Depuis une demande validée :
```php
Stage::create([
    'user_id' => $demande->etudiants->first()->id,
    'entreprise_id' => $demande->entreprise_id,
    'demande_stage_id' => $demande->id,
    'titre' => $demande->objet,
    'description' => $demande->objectifs_stage,
    'date_debut' => $demande->periode_debut,
    'date_fin' => $demande->periode_fin,
    'lieu' => $demande->entreprise->adresse,
    'statut' => 'en_attente_debut',
]);
```

---

## 📱 NAVIGATION UTILISATEUR

### 🎓 Étudiant :
- **Dashboard** : `/etudiant/dashboard`
- **Voir offres** : `/offres` (candidater)
- **Faire demande** : `/demande-stage/choix`
- **Mes candidatures** : `/candidatures/mes-candidatures`
- **Mes stages** : `/stages`

### 🏢 Entreprise :
- **Dashboard** : `/entreprise/dashboard`
- **Publier offre** : `/entreprise/offres/create`
- **Gérer demandes** : `/entreprise/demandes` ⭐ (NOUVEAU)
- **Mes stages** : `/entreprise/stages`

---

## 🔧 CONTRÔLEURS PRINCIPAUX

### `EntrepriseController` :
- `demandes()` → Vue unifiée
- `approveCandidature()` → Accepter candidature
- `approveDemandeStage()` → Valider demande
- `creerStageDepuisCandidature()` → Stage auto
- `creerStageDepuisDemandeStage()` → Stage auto

### `CandidatureController` :
- `store()` → Candidater à une offre
- `approve()` → Accepter candidature
- `show()` → Détails candidature

### `DemandeStageController` :
- `choixType()` → Choisir type demande
- `form()` → Formulaire demande
- `store()` → Enregistrer demande

### `StageController` :
- `index()` → Stages étudiant
- `indexEntreprise()` → Stages entreprise
- `demarrer()` → Démarrer stage
- `terminer()` → Terminer stage

---

## ✅ STATUTS SYSTÈME

### Candidatures :
- `en attente` → En cours d'examen
- `acceptée` → Candidature retenue → Stage créé
- `refusée` → Candidature rejetée

### Demandes :
- `en attente` → En cours d'examen
- `validée` → Demande approuvée → Stage créé
- `refusée` → Demande rejetée

### Stages :
- `en_attente_debut` → Stage accepté, pas encore commencé
- `en_cours` → Stage en cours
- `termine` → Stage terminé
- `annule` → Stage annulé

---

## 🎨 DESIGN SYSTEM

- **Couleurs** : Bleu primaire (#2563eb), succès (#10b981), danger (#ef4444)
- **Animations** : AOS (Animate On Scroll)
- **Responsive** : Mobile-first avec Tailwind CSS
- **Icons** : Font Awesome 6
- **Typographie** : Inter font

---

## 🔐 SÉCURITÉ & AUTORISATIONS

- **Middleware** : `checkrole:etudiant` / `checkrole:entreprise`
- **Policies** : Vérification propriétaire pour modifications
- **Validation** : Formulaires avec règles strictes
- **Upload** : Fichiers sécurisés (CV, lettres, documents)

---

## 📈 STATISTIQUES DASHBOARD

### Entreprise :
- Nombre d'offres publiées
- Candidatures reçues (total)
- Candidatures en attente
- Candidatures acceptées
- Stages actifs

### Étudiant :
- Candidatures envoyées
- Candidatures acceptées/refusées
- Demandes en cours
- Stages actifs/terminés

---

## 🚀 PROCHAINES AMÉLIORATIONS

1. **Notifications** en temps réel
2. **Système de messagerie** entreprise ↔ étudiant
3. **Évaluations** de stages
4. **Rapports** et analytics
5. **API REST** pour mobile
6. **Intégration** calendrier
7. **Export PDF** des documents
