<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;


/**
 * Class GameController
 * @package App\Controller
 * @Route ("/api")
 */
class GameController extends AbstractController
{


    /**
     * @Route("/game", name="game", methods={"POST"})
     *
     * @SWG\Post(
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="",
     *          required=true,
     *          format="application/json",
     *          @Model(type=Game::class)
     *      ),
     *     @SWG\Response(
     *     response=200,
     *     description=""
     * )
     *
     * )
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer)
    {

        /** @var Game $game */
        $game = $serializer->deserialize($request->getContent(), Game::class, 'json');
        $entityManager->persist($game);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Saved new game with id ' . $game->getId()]);
//        $game = (new Game())
//            ->setName('Minecraft')
//            ->setCategory('Survival')
//            ->setImage('https://i1.pngguru.com/preview/964/49/199/minecraft-icon-minecraft-minecraft-png-clipart.jpg')
//            ->setDeveloppers('Mojang')
//            ->setPlatform('None')
//            ->setPublisher('Microsoft');
//
//        $entityManager->persist($game);
//        $entityManager->flush();
//        return new JsonResponse(['message' => 'Saved new product with id ' . $game->getId()]);
    }

    /**
     * @Route ("/game/{id}", name="game_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="",
     *     @Model(type=Game::class)
     * )
     * @param int $id
     * @param GameRepository $gameRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function fetch(int $id, GameRepository $gameRepository, SerializerInterface $serializer)
    {
        $game = $gameRepository->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found for id' . $id
            );
        }
        $jsonContent = $serializer->serialize($game, 'json');
        return new Response($jsonContent, 200, [
            "Content-Type" => 'application/json'
        ]);
    }

    /**
     * @Route ("/game", name="get_games",methods={"GET"})
     * @param GameRepository $gameRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function fetchAll(GameRepository $gameRepository, SerializerInterface $serializer)
    {
        $games = $gameRepository->findAll();
        if (!$games) {
            throw $this->createNotFoundException(
                'No games found'
            );
        }
        $jsonContent = $serializer->serialize($games, 'json');
        return new Response($jsonContent, 200, [
            "Content-Type" => 'application/json' //show that the response is in JSON
        ]);
    }

    /**
     * @Route ("/game/{id}", name="delete_game", methods={"DELETE"})
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @param GameRepository $gameRepository
     * @return Response
     */
    public function delete(EntityManagerInterface $entityManager, int $id, GameRepository $gameRepository): Response
    {
        $game = $gameRepository->find($id);
        if (!$game) {
            throw $this->createNotFoundException(
                'No game found'
            );
        }
        $entityManager->remove($game);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Deleted game with id ' . $id]);
    }

    /**
     * @Route ("/game/{id}", name="edit_game", methods={"PUT"})
     * @param EntityManagerInterface $entityManager
     * @param int $id
     * @param GameRepository $gameRepository
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function edit(EntityManagerInterface $entityManager, int $id, GameRepository $gameRepository, Request $request, SerializerInterface $serializer): Response
    {
        $game = $gameRepository->find($id);

        if (!$game) {
            throw $this->createNotFoundException(
                'No game found'
            );
        }
        /** @var Game $gameEdit */
        $gameEdit = $serializer->deserialize($request->getContent(), Game::class, 'json');
        $game->setName($gameEdit->getName())
            ->setPublisher($gameEdit->getPublisher())
            ->setCategory($gameEdit->getCategory())
            ->setDeveloppers($gameEdit->getDeveloppers())
            ->setPlatform($gameEdit->getPlatform())
            ->setImage($gameEdit->getImage());
        $entityManager->flush();

        return new JsonResponse(['message' => 'Edited game with id ' . $id]);
    }
}
