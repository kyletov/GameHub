<?php

class GameStats {
	
	public function __construct() {
	}

	public function get_stats($dbconn, $user){
        $gamestats = array();
        $query = "SELECT * FROM gamestats gs ORDER BY gs.game, gs.nummoves;";
        $result = pg_prepare($dbconn, "get_stats", $query);
        $result = pg_execute($dbconn, "get_stats", array());
        while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            if(!$row){
                break;
            }
            else {
                if($row['userid'] == $user){
                    $gamestats[] = "<tr bgcolor='aqua'><td>".$row['userid']."</td><td>".$row['game']."</td><td>".$row['nummoves']."</td></tr>";
                } else {
                    $gamestats[] = "<tr><td>".$row['userid']."</td><td>".$row['game']."</td><td>".$row['nummoves']."</td></tr>";
                }
            }
        }
        return $gamestats;
    }

    public function insert_game_stats($dbconn, $user, $game, $nummoves){
        $query = "INSERT INTO gamestats VALUES($1, $2, $3);";
        $result = pg_prepare($dbconn, "insert_stats", $query);
        $result = pg_execute($dbconn, "insert_stats", array($user, $game, $nummoves));
        if(!$result){
            return False;
        }
        return True;
    }

    public function update_game_stats($dbconn, $user, $game, $nummoves){
        $query = "UPDATE gamestats SET nummoves=$3 WHERE userid=$1 AND game=$2;";
        $result = pg_prepare($dbconn, "update_stats", $query);
        $result = pg_execute($dbconn, "update_stats", array($user, $game, $nummoves));
        if(!$result){
            return False;
        }
        return True;
    }

}
?>
