<?php

class Player
{
    const VERSION = "Default PHP folding player";

    public function betRequest($gameState)
    {

        if ($this->hasPair($gameState, 6)){
            $this->log('PAIR TACTICS');
            return 10000;
        }

        if ($this->hasHighCard($gameState, 11) && !$this->hasCommunityCards($gameState)){
            $this->log('HIGHCARD TACTICS minimum bet: ' .  $this->minimumBet($gameState) . ' current buy in ' . $gameState['current_buy_in']);
            return $this->minimumBet($gameState);
        }

        if ($this->hasHighCard($gameState, 11) && $this->hasCommunityCards($gameState) && $this->hasPairWithCommunityCards($gameState, 11)){
            $this->log('HAS PAIR WITH COMMUNITY');
            return $this->minimumBet($gameState);
        }

        if ($this->activePlayersCount($gameState) > 2){
            $this->log('MORE THAN 2 ACTIVE PLAYERS, FOLD');

            return 0;
        }


        if ($this->hasPair($gameState, 8)){
            $this->log('2 player and have pair');
            return 10000;
        }

        $this->log('2 player and dont have pair');
        return 0;

    }

    public function showdown($game_state)
    {

    }

    public function activePlayersCount($gameState){
        $sum = 0;

        $outCount = 0;

        foreach ($gameState['players'] as $player){
            $sum++;
            if ($player['status'] == 'out'){
                $outCount++;
            }
        }

        return $sum-$outCount;
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
              'rank' => intval($rank),
              'suit' => $holeCard['suit'],
            ];
        }

        return $cards;
    }

    public function getCommunityCards($gameState)
    {
        $holeCards = $gameState['community_cards'];

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
                'rank' => intval($rank),
                'suit' => $holeCard['suit'],
            ];
        }

        return $cards;
    }

    public function hasHighCard($gameState, $limit = 12){
        $holeCards = $this->getHoleCards($gameState);

        return ($holeCards[0]['rank'] >= $limit) || ($holeCards[1]['rank'] >= $limit);
    }

    public function hasPair($gameState, $limit = 2)
    {
        $cards = $this->getHoleCards($gameState);

        if (($cards[0]['rank'] == $cards[1]['rank']) && $cards[0]['rank'] >= $limit){
            return true;
        }

        return false;
    }

    public function hasPairWithCommunityCards($gameState, $limit = 2)
    {
        $holeCards = $this->getHoleCards($gameState);
        $communityCards = $this->getCommunityCards($gameState);

        $cards = array_merge($holeCards, $communityCards);

        $aggregated = [];

        foreach ($cards as $card){
            if (isset($aggregated[$card['rank']])){
                $aggregated[$card['rank']] += 1;
            } else {
                $aggregated[$card['rank']] = 1;
            }
        }

        foreach ($aggregated as $index => $a){
            if ($a >1 && $index > $limit){
                return true;
            }
        }

        return false;
    }

    public function log($message) {
        file_put_contents("php://stderr", '####THIS####  ' . $message);
    }

    public function minimumBet($gameState)
    {
        $player = $this->getPlayer($gameState);

        return $gameState['current_buy_in'] - $player['bet'];
    }

    public function bigBlind($gameState){
        return $gameState['small_blind'] * 2;
    }

    public function hasCommunityCards($gameState)
    {
        return boolval(count($gameState['community_cards']));
    }


}
