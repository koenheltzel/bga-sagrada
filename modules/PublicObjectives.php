<?php

namespace Sag;

use Sagrada;

class PublicObjectives {

    /**
     * @param Array $ids
     * @return PublicObjective[]
     */
    public static function getPublicObjectives($ids=null) {
        if (is_null($ids)) {
            $ids = GameState::get()->publicObjectiveIds;
        }
        $idsString = implode(',', $ids);
        $sql = "
            SELECT *
            FROM sag_publicobjectives
            WHERE id IN ($idsString)
            ORDER BY FIELD(id, {$idsString})
        ";
        $result = Sagrada::DbQuery($sql);
        $publicObjectives = [];
        while ($publicObjective = $result->fetch_object("Sag\PublicObjective")) {
            $publicObjectives[] = $publicObjective;
        }
        return $publicObjectives;
    }

    /**
     * @param $id
     * @return PublicObjective
     */
    public static function getPublicObjective($id) {
        return self::getPatterns([$id])[0];
    }

}