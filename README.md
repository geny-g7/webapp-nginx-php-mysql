# Webapp-ansible-nginx-php-mysql

## Description du projet
Ce travail s'effectue dans le contexte d'une évaluation à caractère synthèse. Il consiste à automatiser des tâches avec l'utilisation de Ansible pour installer des outils et des containers Docker et déployer une application web sur un server distant. L'application web utilise un proxy Nginx qui fait circuler le trafic en provenance d'un server web en PHP qui communique avec un server de base de données MySQL. Pour effectuer ce travail, nous faisons usage d'un poste de gestion déjà configuré sur lequel des outils tels que Ansible et SSH sont déjà installés. Les tâches à réaliser seront effectuées dupuis notre poste de gestion, qui controlera la machine distante via des connexions ssh. Et le serveur distant que nous utilisaons est le (srv-mysql-1). 

## Les étapes du projet

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

- Les fichiers de configuration ansible étant mis en place, nous allons tester la connexion ssh sans mot-de-passe. Pour ce faire, nous nous servirons du module ping de ansible. La commande que nous utiliserons la commande suivante : <br>
```bash
# Tester seulement le noeud 'Web'
ansible -m ping Web 
# Tester tous les noeuds ('all)'
ansible -m ping all
``` 
**Figure 01 : Test de vérification de connexion au serveur**<br>
![Test de connexion.](img/ansible_ping_test_all.png)


2 - <h3>Création du playbook Ansible</h3>
Pour mettre en place le playbook ansible, nous allons créer un fichier initial nommé 'deploy.yaml'. Dans cette version du fichier, les tâches sont définies en trois groupes : l'installation des dépendances de Docker, l'installation de Docker et Docker-compose et le démarrages de Docker.<br>
- <h5>Installation des dépendances de Docker : </h5>
Comme le nom l'indique, cette partie prend en charge les dépendances de Docker. On notera qu'il y a essentiellement trois tâches dans cette partie. A ce niveau, le contenu du playbook est tel que suit :

```bash
nano deploy.yaml

# Playbook : deploy.yaml

# Contenu du fichier 

---
- name: "NGinx, PHP et MySqL installation avec Docker"
  hosts: Web
  become: true
  vars:
    ansible_sudo_pass: "egeorges*1"
  tasks:
    - name: INSTALLER LES DEPENDANCES DOCKER
      apt:
        name:
          - apt-transport-https
          - ca-certificates
          - curl
          - software-properties-common
        state: present
        update_cache: yes
      tags: docker-dep

    - name: AJOUT CLE GPG DE DOCKER
      apt_key:
        url: https://download.docker.com/linux/ubuntu/gpg
        state: present
      tags: docker-dep

    - name: AJOUT DEPOT APT DE DOCKER
      apt_repository:
        repo: deb [arch=amd64] https://download.docker.com/linux/ubuntu jammy stable
        state: present
      tags: docker-dep

```

**Figure 02 : Test de vérification des dépendances de Docker**<br>
![Test des dépendances de Docker.](img/ansible_playbook_docker_dep_test.png)

- <h5>Installation de Docker et Docker-Compose : <h5>
Une fois les dépendances de Docker mises en place, nous sommes en mesure de procéder à l'installation de docker et de docker-compose. Il n'y a qu'une tâche associée à cette partie et le code suivant réprsente la portion du playbook relative à ses activités : <br>

```bash

    - name: INSTALL DOCKER / DOCKER-COMPOSE
      apt:
        name:
          - docker-ce
          - docker-compose-plugin
        state: present
        update_cache: yes
      tags: docker-ins

```

**Figure 03 : Test d'installation Docker et docker-compose**<br>
![Test d'installation de docker et docker-compose.](img/ansible_playbook_docker_ins_test.png)

- <h5>Demarrage de Docker : </h5>
Les tâches exécutées dans cette partie procèdent au démarrage du service de docker. Au final, nous avons une seule tâche incluse pour prendre en charge cette activité. Voici la portion de code du playbook qui y est associée : <br> 

```bash

    - name: DEMARRER DOCKER
      ansible.builtin.systemd_service:
        state: started
        name: docker
      tags: docker-str

```

**Figure 04 : Test de démarrage de Docker**<br>
![Test de démarrage de Docker.](img/ansible_playbook_docker_str_test.png)


Voici le contenu intégral de la version finale du playbook (jusqu'à ce niveau du projet).

```bash
 Playbook : deploy.yaml

# Contenu du fichier 

---
- name: "NGinx, PHP et MySqL installation avec Docker"
  hosts: Web
  become: true
  vars:
    ansible_sudo_pass: "egeorges*1"
  tasks:
    - name: INSTALLER LES DEPENDANCES DOCKER
      apt:
        name:
          - apt-transport-https
          - ca-certificates
          - curl
          - software-properties-common
        state: present
        update_cache: yes
      tags: docker-dep

    - name: AJOUT CLE GPG DE DOCKER
      apt_key:
        url: https://download.docker.com/linux/ubuntu/gpg
        state: present
      tags: docker-dep

    - name: AJOUT DEPOT APT DE DOCKER
      apt_repository:
        repo: deb [arch=amd64] https://download.docker.com/linux/ubuntu jammy stable
        state: present
      tags: docker-dep

    - name: INSTALL DOCKER / DOCKER-COMPOSE
      apt:
        name:
          - docker-ce
          - docker-compose-plugin
        state: present
        update_cache: yes
      tags: docker-ins

    - name: DEMARRER DOCKER
      ansible.builtin.systemd_service:
        state: started
        name: docker
      tags: docker-str

```

