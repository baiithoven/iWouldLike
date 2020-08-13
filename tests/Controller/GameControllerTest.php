<?php


namespace App\Tests\Controller;


use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GameControllerTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    private function getVideoGameFromRepo(): Game
    {
        return self::$container->get(GameRepository::class)->findOneBy([]);
    }

    private function getVideoGameToPost()
    {
        return [
            "name" => "Minecraft",
            "publisher" => "Mojang",
            "developpers" => "Mojang",
            "image" => "https://test.com/img.png",
            "platform" => "PC",
            "category" => "Action"
        ];
    }

    public function testFetchVideoGames()
    {
        $this->client->request('GET', '/game');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testFetchOneVideoGame()
    {
        $game = $this->getVideoGameFromRepo();
        $this->client->request('GET', "/game/{$game->getId()}");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAddVideoGame()
    {
        $this->client->request('POST', '/game', [], [] ,[] , json_encode($this->getVideoGameToPost()));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testUpdateOneVideoGame()
    {
        $game = $this->getVideoGameFromRepo();
        $gameUpdate = $this->getVideoGameToPost();
        $gameUpdate['publisher'] = 'Microsoft';
        $this->client->request('PUT', "/game/{$game->getId()}", [], [] ,[] , json_encode($gameUpdate));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteOneVideoGame()
    {
        $game = $this->getVideoGameFromRepo();
        $this->client->request('DELETE', "/game/{$game->getId()}");
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
