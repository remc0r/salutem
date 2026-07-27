# Salutem ESGI Nantes 2025-2026 - Rémi CORNET


## TODO

- [x] Faire le CRUD des horaires d'ouverture dans l'admin
- [x] S'assurer que l'on ne puisse pas créer un jour qui n'existe pas dans la semaine
  et qu'on ne puisse pas créer deux horaires pour le même jour
- [x] Afficher dynamiquement le message "Votre centre est actuellement ouvert/fermé" dans le header du site
- [x] Ajouter la liste des utilisateurs dans l'admin et pouvoir modifier le statut d'un utilisateur (patient, docteur, admin).
  S'il est un docteur, on doit pouvoir associer son compte à un docteur existant dans la base de données.
- [x] Ajouter la possibilité pour un docteur de voir ses rendez-vous sur la page profil (docteur peut prendre des rdv, patient peut prendre des rdv)


**J'ai également ajouté une option d'impersonation pour les admins, ce qui leur permet de se connecter en tant qu'un autre utilisateur (patient ou docteur) pour voir le site comme cet utilisateur le verrait. Cela peut être utile pour le support ou pour tester des fonctionnalités spécifiques à certains types d'utilisateurs.**



## Prérequis

- PHP 8.2+
- Composer
- Symfony CLI (optionnel mais recommandé)
- SQLite (base de données par défaut)

## Installation

```symfony composer install```

### 2. Configurer l'environnement

Créer ou vérifier le fichier .env.local (optionnel si .env suffit) :

```DATABASE_URL="sqlite:///%kernel.project_dir%/var/data_dev.db"```

### 3. Créer la base de données

```symfony console doctrine:database:create```

### 4. Exécuter les migrations

```symfony console doctrine:migrations:migrate```

### 5. Charger les fixtures (données de test)

```symfony console doctrine:fixtures:load```

Répondre **yes** quand on te demande de confirmer la purge de la base.

## Comptes de test

Une fois les fixtures chargées, tu peux te connecter avec :

| Email              | Mot de passe | Rôle     |
|--------------------|--------------|----------|
| admin@admin.fr     | admin        | Admin    |
| doctor@doctor.fr   | doctor       | Médecin  |
| patient@patient.fr | patient      | Patient  |

## Lancer l'application

```symfony serve```

L'app sera accessible sur http://localhost:8000

