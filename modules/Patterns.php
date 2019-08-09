<?php

namespace Sag;

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
        $result = Sagrada::DbQuery($sql);
        $patterns = [];
        while ($pattern = $result->fetch_object("Sag\Pattern")) {
            $patterns[] = $pattern;
        }
        return $patterns;
    }

    /**
     * @param $id
     * @return Pattern
     */
    public static function getPattern($id) {
        return Patterns::getPatterns([$id])[0];
    }

}