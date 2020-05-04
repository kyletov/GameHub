<?php

class UserProfile {
	private $userid;
	private $password;
	private $fname="";
	private $lname="";
	private $gender="";
	private $age;
	private $bio="";
	
	public function __construct($userid, $password, $first_name="", $last_name="", $gender="", $age=null, $bio="") {
    	$this->userid = $userid;
    	$this->password = $password;
    	$this->fname = $first_name;
		$this->lname = $last_name;
		$this->gender = $gender;
		$this->age = $age;
		$this->bio = $bio;
	}

	public function getUserid(){
		return $this->userid;
	}

	public function setFirstName($first_name){
        $this->fname=$first_name;
	}

	public function getFirstName(){
		return $this->fname;
	}

	public function setLastName($last_name){
        $this->lname=$last_name;
	}
	
	public function getLastName(){
		return $this->lname;
	}

	public function setGender($gender){
        $this->gender=$gender;
	}

	public function getGender(){
		return $this->gender;
	}

	public function setAge($age){
        $this->age=$age;
	}

	public function getAge(){
		return $this->age;
	}

	public function setBio($bio){
        $this->bio=$bio;
	}

	public function getBio(){
		return $this->bio;
	}

	public function confirm_password($password){
		return $password == $this->password;
	}

	public function change_password($password){
		
	}

	public function update_profile($dbconn, $info){
		$query = "UPDATE userprofile SET fname=$2, lname=$3, gender=$4, age=$5, bio=$6 WHERE userid=$1;";
		$result = pg_prepare($dbconn, "update_profile", $query);
		$result = pg_execute($dbconn, "update_profile", array($this->userid, $info['fname'], $info['lname'], $info['gender'], $info['age'], $info['bio']));
		if(!$result){
        	return False;
        }
        return True;
	}

	public function get_highscores($dbconn){
        $highscores = array();
        $query = "SELECT game, nummoves FROM gamestats gs WHERE gs.userid=$1;";
        $result = pg_prepare($dbconn, "get_highscores", $query);
        $result = pg_execute($dbconn, "get_highscores", array($this->userid));
        while($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
            if(!$row){
                break;
            }
            else {
                $highscores[$row['game']] = $row['nummoves'];
            }
        }
        return $highscores;
    }

	public function toString(){
		return "This is $this->userid.";
	}
}
?>
