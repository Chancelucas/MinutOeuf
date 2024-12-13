# MinutOeuf

Une application web pour la cuisson parfaite des œufs, développée en PHP avec MongoDB Atlas.

## Prérequis

- Docker
- Docker Compose
- Une connexion MongoDB Atlas

## Installation

1. Clonez le repository
2. Configurez votre connexion MongoDB Atlas dans le fichier `docker-compose.yml`
3. Lancez l'application avec Docker Compose :

```bash
docker-compose up -d
```

L'application sera accessible à l'adresse : http://localhost:8080

## Structure du Projet

```
minutoeuf/
├── assets/
│   ├── css/
│   └── js/
├── config/
├── controllers/
├── core/
├── images/
├── models/
├── sounds/
└── views/
```

## Base de données

La base de données MongoDB doit contenir une collection `eggs` avec la structure suivante :

```json
{
    "type": "À la coque",
    "cookingTime": 180,
    "instructions": "Instructions de cuisson...",
    "tips": "Conseils pour réussir..."
}
```
