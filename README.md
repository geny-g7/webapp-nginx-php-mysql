# Webapp-ansible-nginx-php-mysql

## Description du projet
Ce travail s'effectue dans le contexte d'une évaluation à caractère synthèse. Il consiste à automatiser des tâches avec l'utilisation de Ansible pour installer des outils et des containers Docker et déployer une application web sur un server distant. L'application web utilise un proxy Nginx qui fait circuler le trafic en provenance d'un server web en PHP qui communique avec un server de base de données MySQL. Pour effectuer ce travail, nous faisons usage d'un poste de gestion déjà configuré sur lequel des outils tels que Ansible et SSH sont déjà installés. Les tâches à réaliser seront effectuées dupuis notre poste de gestion, qui controlera la machine distante via des connexions ssh. Et le serveur distant que nous utilisaons est le (srv-mysql-1). 

## Les étapes du prejet

1 - <b>Mise en place des fichier préliminaires de Ansible</b>
- Sur notre poste de gestion, nous travaillons avec l'utilisateur 'deploy', conçu à cet effet. Nous avons préparé un répertoire pour le projet (efcs_webapp) dans l'espace de travail de 'deploy' et le dépôt git a été initialisé. Nous y avons ensuite copié un fichier de configuration ansible 'ansible.cfg' que nous avions préalablement placé dans le répertoire home de 'deploy' pour permetre à Ansible de prendre en charge notre répertoire. La copie de ce fichier a été réalisé avec la commande suivante : <br>
```bash
cp ansible.cfg efcs_webapp/
```

- Nous poursuivons avec la création d'un fichier d'inventaire ansible dans le répertoire du projet. Ce fichier d'inventaire appelé 'hosts' a  le contenu initial suivant : <br>
```bash
nano hosts
# Contenu du fichier d'inventaire 'hosts'...

[Web]
srv-mysql-1 ansible_host=10.100.2.40

[local]
control ansible_connection=local
``` 
