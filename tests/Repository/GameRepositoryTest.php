<?php


namespace App\Tests\Repository;


use App\Entity\Game;
use App\Repository\GameRepository;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    public function setUp()
    {
        self::bootKernel();
        $this->loadFixtureFiles([
            __DIR__ . '/../fixtures/games.yaml'
        ]);
    }

    private function getRepository()
    {
        return self::$container->get(GameRepository::class);
    }

    public function testCount()
    {
        $gameRepository = $this->getRepository();
        $count = $gameRepository->count([]);
        $this->assertEquals(10, $count);
    }

    public function testGetVideoGame()
    {
        $gameRepository = $this->getRepository();
        $game = $gameRepository->findOneBy([]);
        $this->assertTrue(isset($game));
        $this->assertInstanceOf(Game::class, $game);
    }
}
