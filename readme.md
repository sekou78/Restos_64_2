# Préparer l’environnement et la création d’un projet Symfony

    -Creation du projet symfony:
        -Creation du projet: "composer create-project symfony/skeleton:"6.4.*" NOM DU PROJET",
        -Positionnement dans le dossier projet:  "cd NOM DU PROJET",
        -Installation de la dependance webapp: "composer require webapp",
    -Lancement du server: "symfony server:start -d",
    -Creation des controllers pour tester notre route
        -Manuellement:
            -Creation du fichier dans le dossier controller (src/controller):
                -(HomeController.php et TestRouteController.php(facultative))
                    -Inserer ce code dans le HomeController.php
                        (
                            <?php
                                namespace App\Controller;
                                use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
                                use Symfony\Component\HttpFoundation\Response;
                                use Symfony\Component\Routing\Annotation\Route;
                                class HomeController extends AbstractController
                                {
                                    #[Route('/')]
                                    public function home() : Response
                                    {
                                        return new Response("Bonjour à VOUS!!!");
                                    }
                                }
                        )
        -Ou via la commande en ligne:
            -"php bin/console make:controller HomeController"
                -Puis parametrer ce fichier,
    -Ensuite revenir sur la page server et actualiser pour afficher la representation du HomeController.

# Créer la base de données de l’application

    -Comprendre et modéliser le brief client (le cahier des charges),
    -Installer Doctrine si celui-ci n'est pas déjà installer, à verifier sur le fichier "composer.json"
        ou bien l'installer même si c'est present il le mettra à jour si nécessaire,
    -On peut faire un check des dependances installer: "symfony check:requirements",
        pour voir si on a un projet symfony et une configuration minimale pour continuer le projet,
        si ce n'est pas le cas réinstaller un nouveau projet symfony,
    -Installation de Doctrine ORM Pack et MakerBundle:
        -ORM Symfony Pack qui contient toutes les librairies nécessaires de Doctrine propres à Symfony:
            -"composer require symfony/orm-pack",
        -Puis le composant MakerBundle qui nous permettra de générer du code facilement en ligne de commande,
            cela nous aidera notamment à créer nos entités Doctrine (nos tables), et plus encore:
            -"composer require --dev symfony/maker-bundle",
    -Une fois ces deux composants installés, nous pouvons nous assurer que notre projet est à jour avec la commande suivante:
        -"composer update",
    -Maintenant que notre projet est à jour et que nos composants sont fraîchement installés dans le dossier "vendor/",
        il ne nous reste plus qu’à configurer la connexion entre notre projet et notre base de données SQL.
    -Configuration de doctrine avec le fichier .env (le fichier à la racine de notre projet permet aux développeurs
        de stocker des variables d’environnement),
            - Pensez à récupérer vos identifiants SQL, car nous en aurons besoin (souvent avec l’utilisateur root,
                même si ce n’est pas recommandé),
            -Pour plus de securité d'accès a notre base de donnée(BDD), nous allons externalisée notre fichier .env,
                en créant un autre fichier ".env.local", ignorer par ".gitignore", cela fera en sorte de ne pas publier
                l'accès a notre BDD, on peut consulter le fichier ".gitignore", pour savoir les fichiers ignorer lors
                des commits sur git.
                    -Creation du fichier ".env.local", puis le parametrage de l'accès a notre BDD.
    -Il nous faut notre base de données:
        -"php bin/console doctrine:database :create",
    -Creation de notre entité doctrine:
        -Verifier dans le fichier "composer.json" que la dépendance "DoctrineMigrationsBundle", soit bien installé,
            il fait partie du "symfony/orm-pack" sinon l'installer avec cette commande suivie de la version voulu ici "3.0":
                "composer require doctrine/doctrine-migrations-bundle "^3.0"",
        -Via le composant Symfony/maker-bundle:
            -Ici la creation de l'entité "Restaurant" avec les données du rélévé du cahier des charges (tables):
                -"php bin/console make:entity Restaurant",
                -Suivre les instructions pour complèter l'entité,
                -Ensuite effectuer une migration de l'entité:
                    -"php bin/console make:migration", sui créera un fichier de migration dans le dossier "migrations"
                        qui se trouve à la racine de notre projet,
                -Enfin envoyer l'entité créer dans notre BDD:
                    -"php bin/console doctrine:migrations:migrate", qui enverra l'entité et ses données créer dans la table
                        de la BDD.
        -Nous pouvons ensuite créer nos autres entité avec le même procédé une par une.
    -Creation et liaison de nos entités:
        -Relation 1-1, One-To-One
        -Relation 1-n / n-1, One-To-Many / Many-To-One
        -Relation n-n / Many-To-Many
        -Ajouter vos relations ManyToMany entre « Category » et « Food », ainsi que « Menu » et « Category »,
            ce qui créera les deux tables de jointes.
        -Pour chaque création de relation entre entité penser à faire la migration:
            -"php bin/console make:migration", sui créera un autre fichier de migration dans le dossier "migrations"
                qui se trouve à la racine de notre projet,
            -Enfin envoyer l'entité et sa relation créer dans notre BDD:
                -"php bin/console doctrine:migrations:migrate", qui enverra l'entité et ses relation créer dans la table
                    de la BDD.
    -ESSENTIEL:
        -php bin/console doctrine:database:create    // Pour créer une base de données depuis le .env
        -php bin/console make:entity Restaurant    // Pour créer une entité Restaurant avec ses attributs
        -php bin/console make:migration    // Pour créer la migration contenant les requêtes SQL de la classe
        -php bin/console doctrine:migrations:migrate    // Pour exécuter les requêtes SQL de la migration

# Mise en place de l'interface utilisateur

    -Création de nos controlleurs:
        -Veuillez à la bonne installation de se dependance sinon la réinstallée:
            -"composer require --dev symfony/maker-bundle",
        -Deux façons crées les controlleurs:
            -Manuellement:
                -Pour créer un controller à la main, vous devez tout d’abord créer une classe PHP suffixée par le mot « Controller ».
                    -Par exemple, si je dois créer un controller d’accès pour mon entité « Restaurant », je peux créer une classe PHP
                        «RestaurantController » au sein de mon dossier « src/Controller/ ».
                    -Une fois fait, pensez à bien hériter votre « RestaurantController » de la classe abstraite «AbstractController»,
                        sans quoi votre classe PHP resterait une classe PHP banale.
                    -Attention au bon import de cette classe, via le mot clé use et le chemin suivant :
                        -"use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;"
            -Automatiquement avec MakerBundle:
                -Pour créer un controller en ligne de commande, il vous suffit de taper la commande suivante:
                    -"php bin/console make:controller Restaurant",
                -Et voilà ! Vous disposez maintenant d’un tout nouveau controller écrit automatiquement pour vous!
                    celui-ci contient déjà les use et la structure de la classe.
        -Il ne nous reste plus qu’à créer et configurer toutes nos routes d’accès par méthode.
    -Création de nos routes pour les controlleurs,
    -Configuration de nos routes:
        -Lorsque vous déclarez une route, vous avez la possibilité de spécifier plusieurs options comme le type de requête que vous
             souhaitez traiter, la valeur par défaut à setter s’il manque un paramètre, des prérequis pour vos paramètres de requête,
             avec la mise en place des CRUD
                (
                    -Create (POST),
                    -Read (GET),
                    -Update (PUT),
                    -Delete (DELETE)
                )
        -il nous faut spécifier que ces routes respectent les principes RESTful de l’API, c’est-à-dire avoir
            des verboses HTTP (GET, POST, PUT, DELETE) en accord avec notre CRUD.
            -Mise en place des CRUD et configuration
            -Ensuite teste du CRUD avec Postman.

# Mise en place des composants d’accès aux données

    -Sérialiser et désérialiser avec Symfony
    -Installation du composant sérialiser de Symfony:
        -"composer require symfony/serializer-pacK",
            si c'est déjà installer cela ne change rien ou le mettra à jour.
    -Nous reprenons notre CRUD restaurant créé, nous pouvons remplacer l’ancienne méthode « new »
        par la nouvelle utilisant « Serializer »
        -Après importation des librairies "serializerInterface"et "UrlGeneratorInterface",
        -Une fois la serialization en place nous testons notre CRUD avec Postman.
    -Maintenant que nous permettons à notre API de diffuser des données, il se peut que
        des clients (web, mobile, etc…) effectuent des requêtes HTTP sur notre serveur.
        -Si vous réalisez un test hors Postman avec un réel client, Symfony refusera probablement
            de vous donner accès aux données. C'est ce que l'on appelle la sécurité CORS (cross-origin resource sharing),
            une politique de sécurité permettant à un client d'accéder à un autre domaine que le sien.
        -Tout simplement, nous devons permettre à notre API via CORS d’autoriser les requêtes HTTP provenant
            d’origines différentes que celle où tourne notre serveur.
        -Et pour cela Nelmio (la même organisation qui nous permettra de documenter notre API)
            a créé un bundle : « NelmioCorsBundle ».
        -Pour l’installer et permettre de requêter sur votre API, utilisez la commande suivante :
            -"composer require nelmio/cors-bundle"
        -Symfony Flex vous mettra automatiquement en place la configuration nécessaire, mais surtout
             vous créera un fichier « config/packages/nelmio_cors.yaml » qui doit être édité par cette configuration :
             -(
                nelmio_cors:
                    defaults:
                        origin_regex: true
                        allow_origin: ['%env(CORS_ALLOW_ORIGIN)%']
                    allow_methods: ['GET', 'OPTIONS', 'POST', 'PUT', 'PATCH', 'DELETE']
                    allow_headers: ['Content-Type', 'Authorization', 'X-AUTH-TOKEN']
                    expose_headers: ['Link']
                    max_age: 3600
                    paths:
                        '^/': null
             )
        -Voila, votre API peut réellement être utilisée par n’importe quel client sur internet,
            notamment lorsque l’on mettra celle-ci en ligne !

# Sécurisation de l’accès aux données

    -L'Authentification
        -Installation de symfony/security-bundle
            -"composer require symfony/security-bundle"
        -Création de l'entité User makerBundle:
            -"php bin/console make:user"
            -Et répondre aux questions suivantes :
                -The name of the security user class (e.g. User) [User]:
                    -> User
                -Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
                    -> yes
                -Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]:
                    -> email
                -Will this app need to hash/check user passwords? Choose No if passwords are not needed or
                    will be checked/hashed by some other system (e.g. a single sign-on server).
                    Does this app need to hash/check user passwords? (yes/no) [yes]:
                    -> yes
                created: src/Entity/User.php
                created: src/Repository/UserRepository.php
                updated: src/Entity/User.php
                updated: config/packages/security.yaml
                -Une fois la classe d’entité « User » créée avec son repository,
                    -On rentre dans le fichier "entity/User.php" et on rajoute cette paramètre en plus
                        pour la configuration de l'apiToken:
                    (
                        #[ORM\Column(length: 255)]
                        private ?string $apiToken;

                        /**@throws \Exception */
                        public function __construct()
                        {
                            $this->apiToken = bin2hex(random_bytes(20));
                        }
                    )
                -Il ne nous reste plus qu’à envoyer celle-ci en base de données via les deux commandes :
                -Ensuite faire la migration de cette nouvelle entité User l'envoyerà la BDD:
                    -"php bin/console make:migration",
                    -"php bin/console doctrine:migrations:migrate"
                -On peut en profiter pour compléter l'entité User pour qu'il correspond a notre cahier des charges
                    le rajout de la relation.
        -Creation du systeme d'inscription
            -On peut créer notre controller qui recevra cette nouvelle route via:
                -"php bin/console make:controller SecurityController"
        -Création du système d'authentification
            -Nous allons donc créer une route de login dans notre controller « SecurityController.php »
            -Et modifier en rajoutant sur le fichier de configuration « security.yaml » :
                (
                    # config/packages/security.yaml
                    security:
                        # ...
                        firewalls:
                            main:
                                # ...
                            stateless: true
                            json_login:
                                    # api_login is a route we will create below
                                    check_path: app_api_login
                )
            -Ensuite on fait le teste sur Postman avec "username" et "password".
        -Les autorisations:
            -Sécuriser et bloquer les accès aux utilisateurs non connectés,
            -Nous allons donc :
                -Autoriser publiquement l’accès aux routes de connexion et d’inscription
                    (pour qu’un utilisateur non authentifié puisse quand même créer un compte ou se loger).
                -Bloquer les accès à toutes les autres routes dont les URL commencent par « /api ».
                -Pour cela, vous pouvez modifier la propriété « access_control » :
                    (
                        # config/packages/security.yaml
                        security:
                            # ...
                            firewalls:
                                # ...
                                main:
                                    # ...
                            access_control:
                                - { path: ^/api/registration, roles : PUBLIC_ACCESS }
                                - { path: ^/api/login, roles: PUBLIC_ACCESS }
                                - { path: ^/api, roles : ROLE_USER }
                    )
                -Désormais, si vous souhaitez requêter avec Postman sur une route de l’API non publique
                    (comme « /api/restaurant en POST pour poster un nouveau restaurant »), vous serez
                    bloqué avec un code de retour 401 Unauthorized.
            -Création de l'ApiTokenAuthenticator:
                -"php bin/console make:auth"
                    -select "0",
                    -nom de l'api "ApiTokenAuthenticator"
