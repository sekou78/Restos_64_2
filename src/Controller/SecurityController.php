<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\Items;

#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface $serializer,
        private UserRepository $repository
    )
    {
        
    }

    #[Route('/registration', name: 'registration', methods: 'POST')]
    #[OA\Post(
        path: "/api/registration",
        summary: "Inscription d'un nouvel utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: 'Données de l\'utilisateur à inscrire',
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["email", "password"],
                    properties: [
                        new OA\Property(
                            property: "email", 
                            type: "string", 
                            format: "email", 
                            example: "adresse@email.com"
                        ),
                        new OA\Property(
                            property: "password", 
                            type: "string", 
                            format: "password", 
                            example: "Mot de passe"
                        ),
                        new OA\Property(
                            property: "firstName", 
                            type: "string", 
                            example: "Nom d'utilisateur"
                        ),
                        new OA\Property(
                            property: "lastName", 
                            type: "string", 
                            example: "prenom d'utilisateur"
                        ),
                        new OA\Property(
                            property: "guestNumber", 
                            type: "smallint", 
                            example: "50"
                        ),
                        new OA\Property(
                            property: "allergy", 
                            type: "string", 
                            example: "Cacahuètes"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Utilisateur inscrit avec succès',
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user", 
                                type: "string", 
                                example: "Mail de connexions"
                            ),
                            new OA\Property(
                                property: "apiToken", 
                                type: "string", 
                                example: "31a023e212f116124a36af14ea0c1c3806eb9378"
                            ),
                            new OA\Property(
                                property: "roles", 
                                type: "array", 
                                items: new OA\Items(
                                type: "string", 
                                example: "ROLE_USER"
                                )
                            ),
                            new OA\Property(
                                property: "firstName", 
                                type: "string", 
                                example: "Nom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "lastName", 
                                type: "string", 
                                example: "prenom d'utilisateur"
                            ),
                            new OA\Property(
                                property: "guestNumber", 
                                type: "smallint", 
                                example: "50"
                            ),
                            new OA\Property(
                                property: "allergy", 
                                type: "string", 
                                example: "Cacahuètes"
                            )
                        ]
                    )
                )
            )
        ]
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
            ['user' => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    #[OA\Post(
        path: "/api/login",
        summary: "Connecter un utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Données de l'utilisateur pour se connecter",
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["username", "password"],
                    properties: [
                        new OA\Property(
                            property: "username",
                            type: "string",
                            example: "adresse@email.com"
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example:"Mot de passe"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Connexion reussie",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        type: "object",
                        properties: [
                            new OA\Property(
                                property: "user",
                                type: "string",
                                example:"adresse de connexion"
                            ),
                            new OA\Property(
                                property: "apiToken",
                                type: "string",
                                example: "31a023e212f116124a36af14ea0c1c3806eb9378"
                            ),
                            new OA\Property(
                                property: "roles",
                                type: "array",
                                items: new OA\Items(
                                    type: "string",
                                    example: "ROLE_USER"
                                )
                            )
                        ]
                    )
                )
            )
        ]
    )]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(
                ['message' => 'Missing credentials'], 
                Response::HTTP_UNAUTHORIZED
            );
        }
        return new JsonResponse([
            'user'  => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }

    // #[Route('/{id}', name: 'me', methods: 'POST')]
    // public function me(int $id): JsonResponse
    // {
    //     $user = $this->repository->findOneBy(['id' => $id]);
    //     if ($user) {
    //         $responseData = $this->serializer->serialize($user, 'json');

    //         return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    //     }

    //     return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    // }

    // //Editer le user et le sauvegarde en base de données
    // #[Route('/{id}', name: 'edit', methods: 'PUT')]
    // public function edit(int $id, Request $request): JsonResponse
    // {
    //     $user = $this->repository->findOneBy(['id' => $id]);
    //     if ($user) {
    //         $user = $this->serializer->deserialize(
    //             $request->getContent(),
    //             user::class,
    //             'json',
    //             [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
    //         );

    //         $user->setUpdatedAt(new DateTimeImmutable());
    
    //         $this->manager->flush();
    
    //         return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    //     }

    //     return new JsonResponse(null, Response::HTTP_NOT_FOUND);

    // }
    // #[Route('/account/me', name: 'me', methods: 'GET')]
    // #[OA\Get(
    //     path: "/api/account/me",
    //     summary: "Récupérer toutes les informations de l'objet User",
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         description: "Donnée de User pour voir les informations",
    //         content: new OA\MediaType(
    //             mediaType: "application/json",
    //             schema: new OA\Schema(
    //                 type: "object",
    //                 required: ["username"],
    //                 properties: [
    //                     new OA\Property(
    //                         property: "username",
    //                         type: "string",
    //                         example: "adresse@email.fr"
    //                     )
    //                 ]
    //             )
    //         )
    //     ),
    //     responses: [
    //         new OA\Response(
    //             response: 200,
    //             description: "Tous les champs utilisateurs retournés",
    //         )
    //     ]
    // )]

    // public function me(): JsonResponse
    // {
    //     $user = $this->getUser();

    //     $responseData = $this->serializer->serialize($user, 'json');

    //     return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    // }

    // #[Route('/account/edit', name: 'edit', methods: 'PUT')]
    // #[OA\Put(
    //     path: "/api/account/edit",
    //     summary: "Modifier son compte utilisateur avec l'un ou tous les champs",
    //     requestBody: new OA\RequestBody(
    //         required: true,
    //         description: "Nouvelles données éventuelles de l'utilisateur à mettre à jour",
    //         content: new OA\MediaType(
    //             mediaType: "application/json",
    //             schema: new OA\Schema(
    //                 type: "object",
    //                 properties: [
    //                     new OA\Property(
    //                         property: "firstname",
    //                         type: "string",
    //                         example: "Nouveau Nom",
    //                     )
    //                 ]
    //             )
    //         )
    //     ),
    //     responses: [
    //         new OA\Response(
    //             response: 204,
    //             description: "Utilisateur modifié avec succès"
    //         )
    //     ]
    // )]
    
    // public function edit(Request $request): JsonResponse
    // {
    //     $user = $this->serializer->deserialize(
    //         $request->getContent(),
    //         User::class,
    //         'json',
    //         [AbstractNormalizer::OBJECT_TO_POPULATE => $this->getUser()],
    //     );
    //     $user->setUpdatedAt(new DateTimeImmutable());

    //     if (isset($request->toArray()['password'])) {
    //         $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
    //     }

    //     $this->manager->flush();

    //     return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    // }
}
