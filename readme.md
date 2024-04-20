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
