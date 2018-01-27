<?php

class Player
{
    const VERSION = "Default PHP folding player";

    public function betRequest($gameState)
    {
        if ($this->hasPair($gameState, 8)){
            return 10000;
        }

        if ($this->activePlayersCount($gameState) > 2){
            return 0;
        }

        return 10000;
    }

    public function showdown($game_state)
    {

    }

    public function activePlayersCount($gameState){
        $sum = 0;

        foreach ($gameState['players'] as $player){
            if ($player['status'] == 'active'){
                $sum++;
            }
        }

        return $sum;
    }

    public function getPlayer($gameState){
        foreach ($gameState['players'] as $index => $player){
            if ($player['id'] == $gameState['in_action']){
                return $gameState['players'][$index];
            }
        }

        return false;
    }

    public function getHoleCards($gameState)
    {
        $player = $this->getPlayer($gameState);
        $holeCards = $player['hole_cards'];

        $cards = [];

        foreach ($holeCards as $holeCard){
            $rank = $holeCard['rank'];

            if ($holeCard['rank'] == 'J'){
                $rank = 11;
            } elseif ($holeCard['rank'] == 'Q'){
                $rank = 12;
            } elseif ($holeCard['rank'] == 'K'){
                $rank = 13;
            } elseif ($holeCard['rank'] == 'A'){
                $rank = 14;
            }

            $cards[] = [
              'rank' => $rank,
              'suit' => $holeCard['suit'],
            ];
        }

        return $holeCards;
    }

    /*public function hasHighCard($gameState){
        $player = $this->getPlayer($gameState);

        foreach ()
    }*/

    public function hasPair($gameState, $limit = 2)
    {
        $cards = $this->getHoleCards($gameState);

        if (($cards[0]['rank'] == $cards[1]['rank']) && $cards[0]['rank'] >= $limit){
            return true;
        }

        return false;
    }
}
