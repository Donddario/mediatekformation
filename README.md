# Mediatekformation
## Présentation
Ce site, développé avec Symfony 6.4, permet d'accéder aux vidéos d'auto-formation proposées par une chaîne de médiathèques et qui sont aussi accessibles sur YouTube.<br>
La version ayant servi de base à la réalisation de ce projet est consultable sur le dépôt GitHub suivant : https://github.com/CNED-SLAM/mediatekformation.git
Cette version de l'application permet d'administrer son contenu. Elle contient les fonctionnalités globales suivantes :<br>
<img width="308" alt="Diagramme cas d'utilisation global" src="https://github.com/user-attachments/assets/0e67bbb9-7cd2-47e7-bf1c-67672c0eeb28" /><br>
## Les différentes pages<br>
Voici les 8 pages correspondant aux différents cas d’utilisation.<br>
### Page 1 : le tableau de bord admin<br>
Cette page présente le dashboard nous servant d'accueil.<br>
La partie du haut contient une bannière (logo, nom).<br>
Le centre contient les liens pour accéder aux 3 autres pages principales (Formations, Playlists, Catégories).<br>
Le bas de page contient un lien pour accéder à la page des CGU : ce lien est présent en bas de chaque page excepté la page des CGU.<br>
![Dashboard Admin](https://github.com/user-attachments/assets/31210899-0bf2-42bb-affd-b5d90ed3cf8f)<br>
### Page 2 : La gestion des formations
Cette page affiche la gestion des formations.
En plus des fonctionnalités présentes dans le front, nous avons ici trois fonctionnalités supplémentaires. Nous pouvons ajouter une formation,
en modifier une ainsi qu'en supprimer une, uniquement après confirmation client.<br>
![alt text](<Gestion des Formations.png>)<br>
### Page 3 : ajout d'une formation<br>
Cette page nous sert à ajouter une formation. Nous avons plusieurs champs obligatoires à remplir comme le titre, la date de publication,
ainsi que la playlist à laquelle elle doit appartenir. Les autres champs sont facultatifs. Une fois les champs remplis, nous pouvons cliquer sur ajouter, ou bien retour.<br>
![Ajouter Formation](https://github.com/user-attachments/assets/36740c8a-9198-469e-a1c8-1f0ebe8a63a0)<br>
### Page 4 : modification d'une formation<br>
Cette page est la même que ajout d'une formation, sauf que tous les champs sont préremplis avec les informations de la formation.
Une fois les champs modifiés à notre guise, nous pouvons appuyer sur le bouton modifier pour prendre en compte les modifications, ou appuyer sur retour.<br>
<img width="899" alt="Formation Modifier" src="https://github.com/user-attachments/assets/4bb3411d-5b87-4c5d-a3c6-a44578732a0f" /><br>
### Page 5 : La gestion des playlists<br>
Cette page affiche la gestion des playlists.
En plus des fonctionnalités présentes dans le front, nous avons ici trois fonctionnalités supplémentaires. Nous pouvons ajouter une playlist,
en modifier une ainsi qu'en supprimer une, uniquement après confirmation client.<br>
![Gestion Playlist](https://github.com/user-attachments/assets/69addaae-d674-4bc8-8414-0b81f100a446)<br>
### Page 6 : ajout d'une playlist<br>
Cette page nous sert à ajouter une playlist. Nous avons devons ajouter le titre ainsi que la description.
Une fois les champs remplis, nous pouvons cliquer sur ajouter, ou bien retour.<br>
![Ajout playlist](https://github.com/user-attachments/assets/c9c2be91-aa5b-4445-8a73-780661f16029)
<br>
### Page 7 : modification d'une playlist<br>
Cette page est la même que ajout d'une playlist, sauf que les champs sont préremplis avec les informations de la playlist.
Une fois les champs modifiés à notre guise, nous pouvons appuyer sur le bouton modifier pour prendre en compte les modifications, ou appuyer sur retour.<br>
<img width="947" alt="Modifier Playlist" src="https://github.com/user-attachments/assets/401e4834-aef9-4a99-9745-00219b3a5b7a" />
<br>
### Page 8 : La gestion des catégories<br>
Cette page affiche la gestion des catégories. Nous avons la possibilité d'ajouter une catégorie dans un mini formulaire. Nous pouvons voir la liste des catégories avec un bouton "Supprimer" qui permet de supprimer une catégorie après confirmation client.<br>
![Gestion Catégorie](https://github.com/user-attachments/assets/cf226840-5e52-46b9-a588-14880d1ed6f3)<br>


## Test de l'application en local
- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
- L'adresse pour accéder au site en ligne est : http://mediatekformation.free.nf/mediatekformationsite/public/<br>
