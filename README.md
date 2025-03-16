# Mediatekformation
## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br>
La version ayant servi de base à la réalisation de ce projet est consultable sur le dépôt GitHub suivant : https://github.com/CNED-SLAM/mediatekformation.git
Cette version de l'application permet d'administrer son contenu. Elle contient les fonctionnalités globales suivantes :<br>
<img width="308" alt="Diagramme cas d'utilisation global" src="https://github.com/user-attachments/assets/0e67bbb9-7cd2-47e7-bf1c-67672c0eeb28" />
## Les différentes pages
Voici les 8 pages correspondant aux différents cas d’utilisation.
### Page 1 : le tableau de bord admin
Cette page présente le dashboard nous servant d'accueil.<br>
La partie du haut contient une bannière (logo, nom).<br>
Le centre contient les liens pour accéder aux 3 autres pages principales (Formations, Playlists, Catégories).<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![img2](https://github.com/user-attachments/assets/523b4233-3505-4b8c-9db0-5e7b72965bc6)
### Page 2 : La gestion des formations
Cette page affiche la gestion des formations.
En plus des fonctionnalités présentes dans le front, nous avons ici trois fonctionnalités supplémentaires. Nous pouvons ajouter une formation,
en modifier une ainsi qu'en supprimer une, uniquement après confirmation client.<br>
![alt text](<Gestion des Formations.png>)
### Page 3 : ajout d'une formation
Cette page nous sert à ajouter une formation. Nous avons plusieurs champs obligatoires à remplir comme le titre, la date de publication,
ainsi que la playlist à laquelle elle doit appartenir. Les autres champs sont facultatifs. Une fois les champs remplis, nous pouvons cliquer sur ajouter, ou bien retour.<br>
![img4](https://github.com/user-attachments/assets/f41d05d8-5980-4dc4-9eb7-58d1c31b8a25)
### Page 4 : modification d'une formation
Cette page est la même que ajout d'une formation, sauf que tous les champs sont préremplis avec les informations de la formation.
Une fois les champs modifiés à notre guise, nous pouvons appuyer sur le bouton modifier pour prendre en compte les modifications, ou appuyer sur retour.<br>
![img5](https://github.com/user-attachments/assets/bbe8934f-8d4b-4da2-8216-60b96b726d8a)
### Page 5 : La gestion des playlists
Cette page affiche la gestion des playlists.
En plus des fonctionnalités présentes dans le front, nous avons ici trois fonctionnalités supplémentaires. Nous pouvons ajouter une playlist,
en modifier une ainsi qu'en supprimer une, uniquement après confirmation client.<br>
![img6](https://github.com/user-attachments/assets/f216a9e7-084a-4683-9b4e-cada5574a0e2)
### Page 6 : ajout d'une playlist
Cette page nous sert à ajouter une playlist. Nous avons devons ajouter le titre ainsi que la description.
Une fois les champs remplis, nous pouvons cliquer sur ajouter, ou bien retour.<br>
### Page 7 : modification d'une playlist
Cette page est la même que ajout d'une playlist, sauf que les champs sont préremplis avec les informations de la playlist.
Une fois les champs modifiés à notre guise, nous pouvons appuyer sur le bouton modifier pour prendre en compte les modifications, ou appuyer sur retour.<br>
### Page 8 : La gestion des catégories
Cette page affiche la gestion des catégories. Nous avons la possibilité d'ajouter une catégorie dans un mini formulaire. Nous pouvons voir la liste des catégories avec un bouton "Supprimer" qui permet de supprimer une catégorie après confirmation client.<br>

## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
- L'adresse pour accéder au site en ligne est : http://mediatekformation.free.nf/mediatekformationsite/public/<br>
