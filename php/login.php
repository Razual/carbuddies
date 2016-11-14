<?php

/**
* Enthält Funktionen zum erstellen, nutzen und verwalten von Accounts
*/
class Accounts 
{
	
	function __construct($db_host, $db_name, $db_user, $user_data)
	{
		$this->db 	 		   = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user);
		$this->firstname 	   = $user_data['firstname'];
		$this->lastname  	   = $user_data['lastname'];
		$this->email 	 	   = $user_data['email'];
		$this->password 	   = sha1($user_data['password']);
		$this->repeat_password = sha1($user_data['repeat_password']);
		$this->username 	   = $user_data['username'];
	}

	function createNewAccount() {
		if(empty($this->firstname) || empty($this->lastname) || empty($this->email) || empty($this->password) || empty($this->repeat_password)) {
			echo "missing_fields \n";
		}

		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
			echo "invalid_mail_format \n";
			die();
		}

		if($this->password != $this->repeat_password) {
			echo "pw_not_match \n";
			die();
		}

		$user_exist_query = "SELECT COUNT(*) FROM users WHERE Email = '{$this->email}' AND Erased IS NOT NULL";
		$exist_result = $this->db->query($user_exist_query);
		$num_rows = $exist_result->fetchColumn();

		if($num_rows > 0) {
			echo "user_exist \n";
			die();
		} else {
			$insert_query = "INSERT INTO users (Firstname,Lastname,Email,Password) VALUES ('{$this->firstname}', '{$this->lastname}', '{$this->email}', '{$this->password}')";
			$count = $this->db->exec($insert_query);
			print("\n Inserted $count rows. \n");
		}
	}
}

$db_host = 'localhost';
$db_name = 'carbuddies';
$db_user = 'root';

$user_data = array(
	'firstname'		  => '',
	'lastname' 		  => '',
	'email'			  => '',
	'password'		  => '',
	'repeat_password' => '',
	'username' 		  => '',
);

//Fugly hack for Ajax
$json_data 	= array_keys($_POST);
$data 	    = json_decode($json_data[0], true);
$data['email'] = str_replace("_", ".", $data['email']); //Wieso ersetzt PHP überhaupt Punkte mit underscores?
switch ($data['action']) {
	case 'createAccount':
		$user_data['firstname'] 	  = $data['firstname'];
		$user_data['lastname']  	  = $data['lastname'];
		$user_data['email']			  = $data['email'];
		$user_data['password']		  = $data['password'];
		$user_data['repeat_password'] = $data['repeat_password'];
		$accs = new Accounts($db_host, $db_name, $db_user, $user_data);
		$accs->createNewAccount();
		break;
	
	default:
		echo "invalid_action";
		die();
		break;
}
?>
