<?php

namespace Sagrada;

class Patterns {

    public $patterns;

    public function __construct() {
        print "<PRE>" . print_r(__FILE__, true) . "</PRE>";
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