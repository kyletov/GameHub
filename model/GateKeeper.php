<?php

class GateKeeper {
	
	public function __construct() {
	}

	public function login($dbconn, $userid, $password){
        $query = "SELECT * FROM appuser WHERE userid=$1 and password=text(digest($2,'SHA256'));";
        $result = pg_prepare($dbconn, "login", $query);
        $result = pg_execute($dbconn, "login", array($userid, $password));
        if($row = pg_fetch_array($result)){ // Able to fetch with $row having a value means userid exists with specified password
            return True;
        }
        return False;
    }

    public function check_user_exists($dbconn, $userid){
        $query = "SELECT * FROM appuser WHERE userid=$1;";
        $result = pg_prepare($dbconn, "find_user", $query);
        $result = pg_execute($dbconn, "find_user", array($userid));
        if($row = pg_fetch_array($result)){ // Able to fetch with $row having a value means userid already exists
            return True;
        }
        return False;
    }

    public function register($dbconn, $userid, $password){
        $insert_appuser_query="INSERT INTO appuser (userid, password) VALUES($1,digest($2,'SHA256'));";
        $result = pg_prepare($dbconn, "insert_appuser", $insert_appuser_query);

        $insert_userprofile_query = "INSERT INTO userprofile VALUES($1,digest($2,'SHA256'))";
        $result = pg_prepare($dbconn, "insert_userprofile", $insert_userprofile_query);

        $result=pg_query($dbconn, "BEGIN;");
        if($result){ 
            $result = pg_execute($dbconn, "insert_appuser", array($userid, $password));
            $result = pg_execute($dbconn, "insert_userprofile", array($userid, $password));
            
            $stat = pg_transaction_status($dbconn);
            if ($stat === PGSQL_TRANSACTION_INERROR) {
                $result=pg_query($dbconn, "ROLLBACK;");
            } else {
                $result=pg_query($dbconn, "COMMIT;");
                if($result){
                    return True;
                } else {
                    $result=pg_query($dbconn, "ROLLBACK;");
                }
            }    
        } else {
            return False;
        }
        return False;
    }

    public function get_user_profile($dbconn, $userid){
        $info=array();
        $get_userprofile_query = "SELECT * FROM userprofile WHERE userid=$1";
        $result = pg_prepare($dbconn, "get_userprofile", $get_userprofile_query);
        $result = pg_execute($dbconn, "get_userprofile", array($userid));
        if($row = pg_fetch_array($result)){
            $info['userid']=$row['userid'];
            $info['password']=$row['password'];
            $info['fname']=$row['fname'];
            $info['lname']=$row['lname'];
            $info['gender']=$row['gender'];
            $info['age']=$row['age'];
            $info['bio']=$row['bio'];
        }
        return $info;
    }

}
?>
