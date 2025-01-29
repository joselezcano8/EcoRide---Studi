# 📌 Environnement de travail – EcoRide  

## 🖥️ Mise en place de l’environnement de développement  

Pour le développement de **EcoRide**, j’ai mis en place un environnement de travail structuré en utilisant plusieurs outils et technologies adaptés aux besoins du projet.  

### 🔧 Outils et technologies utilisés  

- **Serveur local** : **XAMPP**  
  - J’ai choisi XAMPP pour son installation simple et son intégration directe de **Apache, MySQL, PHP et phpMyAdmin**, ce qui facilite le développement et les tests en local.  
- **Base de données** : **MySQL**  
  - MySQL est un choix naturel pour gérer les informations des utilisateurs, des trajets et des réservations. L’utilisation de **phpMyAdmin** permet une gestion intuitive des tables et des requêtes SQL.  
- **Langage backend** : **PHP**  
  - PHP est utilisé pour gérer l’authentification, les requêtes de la base de données et la logique métier du projet. Il est également bien adapté aux applications web dynamiques comme EcoRide.  
- **Langages frontend** : **HTML, CSS, JavaScript**  
  - HTML et CSS assurent la structure et le style de l’interface utilisateur. JavaScript permet d’améliorer l’expérience utilisateur avec des interactions dynamiques.  
- **Système de contrôle de version** : **Git & GitHub**  
  - L’utilisation de GitHub permet de gérer les versions du projet, de collaborer efficacement et d’assurer une sauvegarde sécurisée du code source.  

### 📂 Organisation du projet  

```plaintext
htdocs/
│── ecoride/
│   ├── private/       # Fichiers sensibles et configurations
│   ├── inc/           # Fichiers PHP réutilisables (ex : nav.inc.php, get-covoiturages.php)
│   ├── styles/        # Fichiers CSS pour le design
│   ├── script/        # Scripts JavaScript pour les interactions dynamiques
│   ├── database.sql   # Fichier SQL pour la création des tables
```
## 🎯 Justification des choix  

### ✅ Facilité d’installation et compatibilité  
- XAMPP permet un développement local rapide sans configuration complexe.  
- PHP et MySQL sont largement documentés et compatibles avec les besoins du projet.  

### 🔄 Gestion efficace des versions  
- GitHub facilite le suivi des modifications et la collaboration éventuelle avec d’autres développeurs.  

## 🚀 Déploiement  
Le projet est actuellement hébergé sur **Hostinger**, une solution économique et performante qui supporte PHP et MySQL.  
