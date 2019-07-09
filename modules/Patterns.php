<?php

namespace Sagrada;

class Patterns {

    /**
     * @var Pattern[]
     */
    public $patterns;

    public function __construct() {
        $this->patterns = [
            new Pattern(
                "Sun's Glory",
                6,
                [
                    "1PY 4",
                    "PY  6",
                    "Y  53",
                    " 5421"
                ]
            ),
            new Pattern(
                "Firelight",
                5,
                [
                    "3415 ",
                    " 62 Y",
                    "   YR",
                    "5 YR6"
                ]
            ),
        ];
    }

}