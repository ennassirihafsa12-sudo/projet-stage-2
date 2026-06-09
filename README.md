# Application de Suivi des Marchés Publics

Application Laravel pour le suivi des marchés publics (mairie), conforme au wireframe : tableau de bord, marchés, étapes, rappels, notifications, lettres et rapports.

## Prérequis

- PHP 8.2+ avec extensions `pdo_sqlite` (ou MySQL)
- Composer
- Node.js 18+

## Installation

```bash
composer install
cp .env.example .env   # si nécessaire
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

Ouvrir [http://127.0.0.1:8000](http://127.0.0.1:8000).

### SQLite sur Windows

Si `could not find driver` apparaît, activez dans `php.ini` :

```ini
extension=pdo_sqlite
extension=sqlite3
```

Ou configurez MySQL dans `.env`.

## Structure

| Modèle | Table | Rôle |
|--------|-------|------|
| `Marche` | `marches` | Contrat / marché public |
| `Etape` | `etapes` | 4 étapes séquentielles par marché |
| `Notification` | `notifications` | Rappels et messages système |
| `Lettre` | `lettres` | Lettres d'acceptation / refus |

## Routes principales

- `/` — Tableau de bord
- `/marches` — CRUD marchés
- `/etapes` — Suivi des étapes
- `/rappels` — Délais et rappels
- `/notifications` — Liste des notifications
- `/lettres` — Génération et aperçu des lettres
- `/rapports` — Statistiques (Chart.js)

## Données de démo

Le seeder `MarcheSeeder` crée 4 marchés d'exemple avec étapes et notifications.
