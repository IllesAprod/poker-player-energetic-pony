<?php

class Player
{
    const VERSION = "Default PHP folding player";

    public function betRequest($gameState)
    {

        if ($this->activePlayersCount($gameState) > 0){
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
}
