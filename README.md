## référence API (à compléter)

## URL
##### /api/?action=login

fichier login.php :
// user, password
    verif BDD

## Index.php
switch (action)
 case isloggedIn:
    ....
 case login:
    .....
 case invoice :
    .....
 case logOut:
    .....


## Fonctionnalités
-  Connexion.
-  Recherche Facture -> renvoie les datas qui serviront aux graphiques ou Liste ou détaille facture individuelle
-  Déconnexion -> fin session

~ Suppresion Facture 
~ Ajout Facture 

## ACTION (php)
##### Action (paramètres POST) -> ce qu'elle fait

isLoggedIn  - > renvoie un booléen (analyse la variable session)
login (id, psw ) -> pour le connecter (Session id et rôle PO, ...)
invoice (id_invoice ) -> renvoie détails facture individuelle 
****invoices (type (liste ou graph), DateDebut, DateFin, Prix,... facultatifs) -> renvoie la liste des datas
logOut- > destruction session 


## FIN des fichier php
##### Renvoie un json avec le code d'erreur (ou 200), avec détails et les différents datas demandés

{"success": true, ...}
{"success": false, "error":"...", ...}

---------------------------------------------
$response = [
"success" => false,
"horaires" => "Fermé",
"isOpen" => false
];
header('Content-Type: application/json');
echo json_encode($response);
exit();
