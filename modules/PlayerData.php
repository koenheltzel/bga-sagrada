<?php

namespace Sag;

class PlayerData {

    public $playerId;

    /**
     * @var Pattern[]
     */
    public $patterns;

    public $privateObjectives;

    public $tokens;

    public function __construct($data) {
        $this->playerId = $data['player_id'];
        $this->patterns = Patterns::getPatterns(explode(',', $data['sag_patterns']));
        $this->privateObjectives = $data['sag_private_objectives'];
        $this->tokens = $data['sag_tokens'];
    }

}