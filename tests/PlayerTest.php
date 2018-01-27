<?php

require __DIR__ . '/../player.php';


class PlayerTest extends \PHPUnit\Framework\TestCase {

    private $gameState = <<<EOL
  {
      "tournament_id":"550d1d68cd7bd10003000003",
      "game_id":"550da1cb2d909006e90004b1",
      "round":0,
      "bet_index":0,
      "small_blind": 10,
      "current_buy_in": 320,
      "pot": 400,
      "minimum_raise": 240,
      "dealer": 1,
      "orbits": 7,
      "in_action": 2,
      "players": [
        {
              "id": 0,
              "name": "Albert",
              "status": "active",
              "version": "Default random player",
              "stack": 1010,
              "bet": 320
          },
          {
              "id": 1,
              "name": "Chuck",
              "status": "active",
              "version": "Default random player",
              "stack": 0,
              "bet": 0
          },
          {
              "id": 2,
              "name": "Bob",
              "status": "active",
              "version": "Default random player",
              "stack": 1590,
              "bet": 80,
              "hole_cards": [
                  {
                      "rank": "6",
                      "suit": "hearts"
                  },
                  {
                      "rank": "K",
                      "suit": "spades"
                  }
              ]
          }
      ],
      "community_cards": [
          {
              "rank": "4",
              "suit": "spades"
          },
          {
              "rank": "A",
              "suit": "hearts"
          },
          {
              "rank": "6",
              "suit": "clubs"
          }
      ]
  }
EOL;
    private $gameState2 = <<<EOL
  {
      "tournament_id":"550d1d68cd7bd10003000003",
      "game_id":"550da1cb2d909006e90004b1",
      "round":0,
      "bet_index":0,
      "small_blind": 10,
      "current_buy_in": 320,
      "pot": 400,
      "minimum_raise": 240,
      "dealer": 1,
      "orbits": 7,
      "in_action": 1,
      "players": [
        {
              "id": 0,
              "name": "Albert",
              "status": "active",
              "version": "Default random player",
              "stack": 1010,
              "bet": 320
          },
          {
              "id": 1,
              "name": "Bob",
              "status": "active",
              "version": "Default random player",
              "stack": 1590,
              "bet": 80,
              "hole_cards": [
                  {
                      "rank": "10",
                      "suit": "hearts"
                  },
                  {
                      "rank": "10",
                      "suit": "spades"
                  }
              ]
          }
      ],
      "community_cards": [
          {
              "rank": "4",
              "suit": "spades"
          },
          {
              "rank": "A",
              "suit": "hearts"
          },
          {
              "rank": "6",
              "suit": "clubs"
          }
      ]
  }
EOL;

    public function setUp(){
        $this->gameState = json_decode($this->gameState, true);
        $this->gameState2 = json_decode($this->gameState2, true);
    }

    /** @test */
    public function it_returns_an_integer()
    {
        $player = new \Player();
        $response = $player->betRequest($this->gameState);
        $this->assertTrue(is_integer($response));
    }

    /** @test */
    public function it_folds_if_we_have_more_than_one_active_players()
    {
        $player = new \Player();
        $response = $player->betRequest($this->gameState);
        $this->assertTrue($response == 0);
    }


    /** @test */
    public function it_returns_player(){
        $player = new \Player();
        $player = $player->getPlayer($this->gameState2);
        $this->assertTrue($player == $this->gameState2['players'][1]);
    }

    /** @test */
    public function it_returns_hole_cards(){
        $player = new \Player();
        $holeCards = $player->getHoleCards($this->gameState2);
        $this->assertTrue($holeCards == $this->gameState2['players'][1]['hole_cards']);
    }

    /** @test */
    public function it_all_in_if_we_have_pair()
    {
        $player = new \Player();
        $response = $player->betRequest($this->gameState2);
        $this->assertTrue($response == 10000);
    }
}


