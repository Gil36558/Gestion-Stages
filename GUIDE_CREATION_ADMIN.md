# 👨‍💼 GUIDE DE CRÉATION D'ADMINISTRATEUR

## 🔧 **MÉTHODE 1 : Via Tinker (RAPIDE)**

Puisque Tinker est déjà ouvert dans votre terminal, tapez directement :

```php
$admin = App\Models\User::create([
    'name' => 'Mon Admin',
    'email' => 'admin@monsite.com',
    'password' => bcrypt('motdepasse123'),
    'role' => 'admin'
]);

echo "Admin créé : " . $admin->email;
```

Puis tapez `exit` pour sortir de Tinker.

---

## 🔧 **MÉTHODE 2 : Via Interface Admin (PRATIQUE)**

1. **Connectez-vous** avec l'admin existant : `admin@stageconnect.com` / `admin123`
2. **Allez sur** : `http://127.0.0.1:8000/admin/utilisateurs`
3. **Cliquez** : "Créer un utilisateur"
4. **Remplissez** le formulaire avec `role = admin`

---

## 🔧 **MÉTHODE 3 : Via Seeder (PROFESSIONNEL)**

Créer un fichier seeder :

```bash
php artisan make:seeder AdminSeeder
```

Dans `database/seeders/AdminSeeder.php` :

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@monsite.com',
            'password' => Hash::make('motdepasse123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
```

Puis exécuter :
```bash
php artisan db:seed --class=AdminSeeder
```

---

## 🔧 **MÉTHODE 4 : Via Commande Artisan (AVANCÉ)**

Créer une commande personnalisée :

```bash
php artisan make:command CreateAdmin
```

Dans `app/Console/Commands/CreateAdmin.php` :

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create {name} {email} {password}';
    protected $description = 'Créer un nouvel administrateur';

    public function handle()
    {
        $admin = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($this->argument('password')),
            'role' => 'admin',
        ]);

        $this->info("Admin créé : {$admin->email}");
    }
}
```

Utilisation :
```bash
php artisan admin:create "Nom Admin" "admin@email.com" "motdepasse"
```

---

## 🎯 **RECOMMANDATION**

**Utilisez la MÉTHODE 1** (Tinker) car c'est le plus rapide et vous avez déjà Tinker ouvert !

**Admin existant disponible :**
- Email : `admin@stageconnect.com`
- Mot de passe : `admin123`

**Pour créer un nouvel admin via Tinker :**
```php
App\Models\User::create(['name' => 'Votre Nom', 'email' => 'votre@email.com', 'password' => bcrypt('votre_mot_de_passe'), 'role' => 'admin']);
