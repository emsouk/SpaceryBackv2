# Spacery API

API REST du projet **Spacery**, une application web autour de la découverte de lieux liés à l’architecture, au design et à la culture.

Cette API permet de gérer les données de l’application : lieux, types, utilisateurs, parcours personnalisés et administration.

🔗 Front-end du projet : https://github.com/emsouk/spacery  
🔗 Démo : https://spacery.vercel.app/

---

## Fonctionnalités

- API REST JSON
- CRUD des lieux
- Gestion des parcours personnalisés
- Gestion des utilisateurs
- Relations entre les entités avec Doctrine ORM
- Back-office d’administration avec EasyAdmin
- Sérialisation / désérialisation des données
- Architecture Symfony structurée
- Base de données relationnelle MySQL

---

## Stack technique

- Symfony
- PHP
- Doctrine ORM
- API Platform
- EasyAdmin
- MySQL
- Composer
- NelmioCorsBundle

---

## Architecture

Le projet suit une architecture découplée :

- Front-end : Next.js
- Back-end : Symfony API REST

L’API expose les données au format JSON afin d’être consommées par le front-end.

---

## Installation

### Cloner le projet

```bash
git clone https://github.com/emsouk/SpaceryBackv2.git
cd SpaceryBackv2
