# drupalmodule_informaid_mailjet
Drupal module for Mailjet

TODO:
-----
Finaliser SettingsForm: implémentation du test deconnexion via le constructeur.
Créer le service de mails:
- envoyer un mail
- voir la liste des modèles Mailjet
- Lors de l'envoi d'un mail, sélectionner un modèle Mailjet dans la liste
- Créer des modèles de textes (configurations entities).
- Stocker les mails envoyés dans des nodes de type "mail" contenant:
  - une adresse e-mail du destinataire
  - associer le client (content) depuis l'adresse e-mail
  - état du mail: réussite/échec
  - identifiant du mail
  - numéro de modèle
  - texte du mail envoyé
  - numéro de version Drupal source si mise à jour,
  - numéro de version Drupal cible si mise à jour
