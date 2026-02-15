# Projet SGBD - Gestion de Restaurant

**Auteurs :** EDOUARD Justin & COUTURIER Alexis  
**Technologies :** PHP (Architecture MVC), Eloquent ORM, PDO (Transactions SGBD).

## Introduction
Ce projet est une application de gestion pour serveurs permettant de gérer les réservations, les commandes et les stocks d'un restaurant. L'architecture repose sur un **Repository centralisé** qui combine la souplesse d'Eloquent pour les lectures simples et la puissance de **PDO Transactionnel** pour les opérations critiques.

## Installation & Configuration

1. **Base de données :** Importer le fichier `donnee.sql` dans votre SGBD.
2. **Configuration :** Modifier le fichier `src/conf/conf.ini` avec vos accès :
   ```ini
   driver=mysql
   host=localhost
   database=Projet_SGBD
   username=votre_user
   password=votre_mdp
