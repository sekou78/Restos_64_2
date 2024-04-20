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
