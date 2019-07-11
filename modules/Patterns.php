<?php

namespace Sagrada;

use Sagrada;

class Patterns {

    /**
     * @param Array $ids
     * @return Pattern[]
     */
    public static function getPatterns($ids) {
        $idsString = implode(',', $ids);
        $sql = "
            SELECT *
            FROM sag_patterns
            WHERE id IN ($idsString)
            ORDER BY FIELD(id, {$idsString})
        ";
        $result = Sagrada::db($sql);
        $patterns = [];
        while ($pattern = $result->fetch_object("Sagrada\Pattern")) {
            $patterns[] = $pattern;
        }
        return $patterns;
    }

}