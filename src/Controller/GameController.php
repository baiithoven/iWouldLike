<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class GameController extends AbstractController
{
    /**
     * @Route("/game", name="game", methods={"POST"})
     *
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $game =  (new Game())
            ->setName('Minecraft')
            ->setCategory('Survival')
            ->setImage('https://i1.pngguru.com/preview/964/49/199/minecraft-icon-minecraft-minecraft-png-clipart.jpg')
            ->setDeveloppers('Mojang')
            ->setPlatform('None')
            ->setPublisher('Microsoft')
        ;

        $entityManager->persist($game);
        $entityManager->flush();
        return new JsonResponse(['message' => 'Saved new product with id '.$game->getId()]);
    }

    /**
     * @Route ("/game/{id}", name="game_show", methods={"GET"})
     *
     * @param int $id
     * @param GameRepository $gameRepository
     */
    public function fetch(int $id, GameRepository $gameRepository)
    {
        $game = $gameRepository->find($id);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        if(!$game)
        {
            throw $this->createNotFoundException(
                'No game found for id' .$id
            );
        }
        $jsonContent = $serializer->serialize($game,'json');
        return new Response($jsonContent, 200, [
            "Content-Type" => 'application/json'
        ]);
    }
}
