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

# Documenter son API

    -Installation de NelmioApiDocBundle
        -"composer require nelmio/api-doc-bundle"
            -Lors de l’installation, Symfony Flex vous proposera d’exécuter une recette propre au bundle,
                vous pourrez donc écrire
                    « yes »
                et appuyer sur entrée pour valider la recipe.
            -Après l'installation decommenter la seconde partie du code, dans le
                "config/routes/nelmio_api_doc.yaml" qui donne:
                    -(
                        # Expose your documentation as JSON swagger compliant
                        app.swagger:
                            path: /api/doc.json
                            methods: GET
                            defaults: { _controller: nelmio_api_doc.controller.swagger }

                        ## Requires the Asset component and the Twig bundle
                        ## $ composer require twig asset
                        app.swagger_ui:
                            path: /api/doc
                            methods: GET
                            defaults: { _controller: nelmio_api_doc.controller.swagger_ui }
                    )
    -Installation de twig asset afin que Nelmio puisse afficher des rendus HTML
        -"composer require twig asset"
            -N’oublions pas, nous avions protégé toutes nos routes via la sécurité Symfony
                depuis le fichier « config/packages/security.yaml ». Il nous faut donc spécifier
                à notre firewall de ne pas protéger la route de la documentation pour pouvoir y accéder
                en public et par conséquent ajouter un « access_control » de cette URL :
                (
                    # config/packages/security.yaml
                    security:
                        // […]
                        access_control :
                            - { path : ^/api/registration, roles : PUBLIC_ACCESS }
                            - { path : ^/api/login, roles : PUBLIC_ACCESS }
                            - { path : ^/api/doc, roles : PUBLIC_ACCESS }
                            - { path : ^/api, roles : ROLE_USER }
                )
        -Ensuite accéder à la documentation via l'url:"http://127.0.0.1:8000/api/doc"

# La gestion de la sécurité

    -Création d'un compte via la documentation,
        -On ouvre le fichier « SecurityController.php » et ajoutez
            les Attributes au-dessus de notre méthode « register », en oubliant pas d'importer la:
            "use OpenApi\Annotations as OA;"
    -Se connecter via la documentation:
        -Tout comme « app_api_registration », notre requête pour se connecter est de type
            POST et nécessite des champs obligatoires comme le « username » et le « password »
            ajoutez les Attributes au-dessus de notre méthode « login »,
    -Activer le module de Security de NelmioApiDocBundle
        -Maintenant que nous sommes en mesure de récupérer notre « apiToken », il nous faut
            garder celui-ci pour l’ensemble des futures requêtes.
            Pour ce faire, nous avons 2 solutions :
                -Nous pouvons décrire pour chaque route, qu’il nous faut dans le header de
                    la requête HTTP le champ « X-AUTH-TOKEN », mais cela serait redondant et
                    pas pratique en cas de mise à jour, car nous devrons modifier toutes nos routes.
            -Nous pouvons activer le module de sécurité de Nelmio, permettant d’enregistrer
                le token dans une session et l’envoyer dans le header de chaque requête.
            -Nous pouvons modifier le fichier de configuration « config/packages/nelmio_api_doc.yaml »
                de la sorte :
                    (
                        nelmio_api_doc :
                            documentation :
                                info :
                                    title: Restaurant API
                                    description : Documentation de l'API Restaurant Studi.
                                    version : 1.0.0
                                components :
                                    securitySchemes :
                                        X-AUTH-TOKEN:
                                            type : apiKey
                                            name: X-AUTH-TOKEN
                                            in: header
                                security:
                                    - X-AUTH-TOKEN: [ ]
                            areas: # to filter documented areas
                                path_patterns:
                                    - ^/api(?!/doc$) # Accepts routes under /api except /api/doc
                    )
    -Documenter notre CRUD
        -Mise à jour de tout nos controller et leur CRUD

# Tester son application web

    -Installation de phpUnit
        -"composer require --dev symfony/test-pack"
            -Symfony Flex a également mis à jour vos fichiers liés à Composer, votre « .gitignore »,
                mais a surtout ajouté le binaire « phpunit » au sein de votre dossier « bin/ »
                qui vous servira à exécuter vos classes de tests.
    -Création du fichier ".env.test.local" avec le même environnent que notre fichier ".env.local"
    -Pour créer notre BDD test "php bin/console doctrine:database:create --env=test"
    -Pour exécuter un test au sein de votre projet, rien de plus simple !
        -Il vous suffit d’appeler les suites de scénarios de test que vous avez écrits, via la commande suivante :
            -"php bin/phpunit"
                -Aucun test n’a été exécuté et c’est normal,
    -Création de notre premier test unitaire
        -Pour écrire un test, il nous faut créer une classe de test
            -Les classes de test sont suffixées par le mot clé « Test » et se trouvent dans
                le dossier « tests/ » de votre projet.
            -Ce dossier doit obligatoirement reproduire la même arborescence que
                le dossier « src/ » de votre projet.
            -Si la classe « User.php » est dans le dossier « Entity »,
                il nous faudra créer ce dossier au préalable.
            -Créez donc un fichier « UserTest » au sein du répertoire « tests/Entity » :
                -(
                    <?php
                        namespace App\Tests\Entity;
                        use App\Entity\User;
                        use PHPUnit\Framework\TestCase;
                        class UserTest extends TestCase
                        {
                        }
                )
            -Comme vous pouvez le voir, « UserTest » hérite de la classe « TestCase » fournie par PHPUnit.
                -C’est elle qui contient toutes les méthodes qui vous permettront de comparer
                    un résultat souhaité avec un résultat récupéré de votre code.
                -Il existe aussi la classe mère « WebTestCase » qui vous sera utile plus
                    tard pour l’écriture des tests fonctionnels.
                -Maintenant, on peut tester une partie de notre code en créant une
                    méthode étant préfixée par le mot « test ».
                -Le nom de la méthode doit être explicite et permettre de comprendre
                    rapidement ce qui est testé pour faciliter la documentation.
                -Vérifions ici si un utilisateur récemment créé possède bien une clé d’API
                    automatiquement généré par le constructeur de la classe « User » :
                        -(
                            <?php
                            namespace App\Tests\Entity;
                            use App\Entity\User;
                            use PHPUnit\Framework\TestCase;
                            class UserTest extends TestCase
                            {
                                public function testTheAutomaticApiTokenSettingWhenAnUserIsCreated(): void
                                {
                                    $user = new User();
                                    $this->assertNotNull($user->getApiToken());
                                }
                            }
                        )
                -Vous pouvez vérifier le résultat de test, en réexécutant la commande suivante :
                    -"php bin/phpunit"
    -Les tests fonctionnels
        -À la différence d’un test unitaire, votre classe de test fonctionnel héritera
            de « WebTestCase » pour disposer de toutes les méthodes de test du client HTTP.
        -En test fonctionnel, nous couvrons principalement les services et les controllers créés.
            -Pour simplifier la tâche, nous vérifierons juste que chacune de nos URLs soit valide
                et ne retourne pas d’erreur HTTP de type 4.x.x (côté client) ou 5.x.x (côté serveur).
            -On appelle ça un « SmokeTest ».
            -Ce sont les tests minimums à implémenter sur son projet pour s’assurer que chacune
                des routes est fonctionnelle.
            -En respectant l’arborescence comme pour les tests unitaires, vous pouvez créer
                la classe « SmokeTest.php » héritant de « WebTestCase » dans le dossier « tests/Controller » :
                    -(
                        <?php
                        namespace App\Tests\Controller;
                        use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
                        class SmokeTest extends WebTestCase
                        {
                            public function testApiDocUrlIsSuccessful(): void
                            {
                                $client = self::createClient();
                                $client->followRedirects(false);
                                $client->request('GET', '/api/doc');
                                self::assertResponseIsSuccessful();
                            }
                        }
                    )
            -Ajout des Mocks,
                -Les tests précédemment écrits sont des tests simples sans complexité.
                -Mais il est rare de pouvoir tester unitairement du code sans pouvoir en retirer ses dépendances.
                -Pour cela, il existe la possibilité de simuler des dépendances via les « Mocks ».
                -Notion un peu plus avancée dans l’univers du test, les « Mocks » vous permettront
                    de remplacer les dépendances dans vos constructeurs de controllers ou de services,
                    comme des repositories, d’autres composants, etc.
                -Si nous prenons l’exemple d’une classe modifiant les devises d’une monnaie.
                -Cette classe est injectée à un service « BankAccountService.php ».
                -Il nous faut donc appeler cette classe de devise sans pour autant
                    utiliser ses méthodes, car celles-ci ne sont pas testées (mais le seront ailleurs).
                -Nous créons donc un « Mock », une classe simulée dans laquelle nous y
                    ajoutons nous-mêmes une fausse méthode retournant un résultat fixe, permettant
                    à « BankAccountService » de fonctionner normalement en appelant sa « fausse »
                    dépendance de devise.

# Remplir sa base de données avec les DataFixtures

    -Installation de DoctrineFixturesBundle
        -DoctrineFixturesBundle est un bundle développé par Doctrine permettant de générer de la donnée en base.
        -Si vous êtes minimum en Symfony 4, vous pouvez exécuter la commande suivante
            dans votre projet afin de l’installer :
                -"composer require --dev orm-fixtures"
        -Cet alias de Symfony Flex installera et configurera le composant au sein de votre projet.
        -Il ajoutera également le bundle dans votre fichier de configuration,
            le fichier « config/bundles.php » de votre projet.
    -La création de notre première fixture
        -une commande CLI liée aux fixtures est disponible en tapant :
            -"php bin/console list"
        -la commande suivante permette de populer vos fausses données dans votre base :
            -"php bin/console doctrine:fixtures:load"
        -deux choix s’offrent pour créer notre fixture :
            1. Vous pouvez utiliser le maker de Symfony qui créera une classe
                de Fixtures à remplir (tout comme l’exemple AppFixtures) via :
                    -"php bin/console make:fixtures"
            2. Ou la créer par vous-même, en ajoutant une nouvelle classe PHP dans votre
                dossier « src/Datafixtures ».
                    -Cette classe devra :
                        -Se terminer par le suffixe « Fixtures »,
                        -Hériter de la classe mère Fixture « use Doctrine\Bundle\FixturesBundle\Fixture; »,
                        -Implémenter obligatoirement la fonction publique « load(ObjectManager $manager) »
                            imposée par l’interface « FixtureInterface » présente dans notre classe mère
                            « Fixture ».
                        -Sans celle-ci la commande qui appellera vos Fixtures ne pourra charger vos données en base.
                        -Le corps d’une Fixture ressemblera donc à la classe d’exemple :
                            -(
                                // src/DataFixtures/AppFixtures.php
                                namespace App\DataFixtures;
                                use Doctrine\Bundle\FixturesBundle\Fixture;
                                use Doctrine\Persistence\ObjectManager;
                                class AppFixtures extends Fixture
                                {
                                    public function load(ObjectManager $manager)
                                    {
                                        // $product = new Product();
                                        // $manager->persist($product);
                                        $manager->flush();
                                    }
                                }
                            )
        -Si nous reprenons nos entités précédemment créées, nous pouvons et DEVONS
            remplir en premier lieu des entités comme « User », « Restaurant », « Picture », etc.
        -Pour commencer, nous allons remplir l’entité « User » ayant besoin de celle-ci pour
            se connecter rapidement sur notre site.
        -D’autant plus que c’est une entité simple n’ayant aucune relation avec d’autres entités pour le moment.
        -Pour cela, nous allons créer une classe « UserFixtures » avec le code suivant :
            -(
                <?php
                namespace App\DataFixtures;
                use App\Entity\User;
                use DateTimeImmutable;
                use Doctrine\Bundle\FixturesBundle\Fixture;
                use Doctrine\Persistence\ObjectManager;
                use Exception;
                use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
                class UserFixtures extends Fixture
                {
                    public function __construct(private UserPasswordHasherInterface $passwordHasher)
                    {
                    }
                    /** @throws Exception */
                    public function load(ObjectManager $manager): void
                    {
                        for ($i = 1; $i <= 20; $i++) {
                            $user = (new User())
                                ->setFirstName("Firstname $i")
                                ->setLastName("Lastname $i")
                                ->setGuestNumber(random_int(0,5))
                                ->setEmail("email.$i@studi.fr")
                                ->setCreatedAt(new DateTimeImmutable());
                            $user->setPassword($this->passwordHasher->hashPassword($user, 'password' . $i));
                            $manager->persist($user);
                        }
                        $manager->flush();
                    }
                }
            )
        -Si nous résumons le code écrit :
        -La classe « UserFixtures » hérite de la classe mère « Fixture ».
        -Nous ajoutons un constructeur afin d’injecter la dépendance permettant d’hasher et
            de crypter le mot de passe de nos utilisateurs dans la méthode « ->load() ».
        -Nous déclarons la fonction « ->load() » qui sera appelée par la commande:
            -"php bin/console doctrine:fixtures:load"
        -Cette fonction prend en paramètre un objet de type « ObjectManager » qui nous permettra
            de persister et de flusher nos objets en base à la fin de la fonction.
        -Nous bouclons plusieurs fois afin de créer des objets « User » à répétition avec
            leurs attributs settés (prénom X, nom X, un chiffre aléatoire d’invités, un email,
            une date de création) puis les persistons pour les flusher en base de données à la sortie de la boucle.
        -À l’issue, il nous suffit d’exécuter la commande suivante pour jouer cette fixture :
            -"php bin/console doctrine:fixtures:load"
        -Dès lors, vous pouvez constater dans votre base de données et notamment votre table User,
            qu’une nouvelle ligne de données (tuple) a été ajoutée.
        -Vous pouvez à tout moment la supprimer ou enrichir en plus celle-ci via l’option append.
        -Il est utile de rappeler qu’en cas de contribution sur votre projet, vos collaborateurs pourront
            installer votre projet avec des données déjà présentes en base avec les commandes suivantes,
            qui se résument à : créer la base, jouer les migrations, jouer les fixtures :
                -"php bin/console d:d:c" (doctrine:database:create)
                -"php bin/console d:m:m" (doctrine:make:migration)
                -"php bin/console d:f:l" (doctrine:fixtures:load)

# Créer des fixtures avancées Travailler avec des fixtures partagées

    -Il faut bien parametrer les fixtures dans le bon ordre exemple de creation des fixtures entre le Restaurant
        et Picture, le bon ordre est la creation du Restaurant puis de Picture car sans le Restaurant, Picture
        n'existera pas
    -Une fois le parametrage bien preciser on peut vider la BDD et la réécrire:
        --"php bin/console d:d:d --force" (doctrine:database:drop) (Suppreseion de la BDD)
        -"php bin/console d:d:c" (doctrine:database:create) (Création de la BDD)
        -"php bin/console d:m:m" (doctrine:make:migration) (Migration de la BDD)
        -"php bin/console d:f:l" (doctrine:fixtures:load) (Fixtures et chargement en BDD)
