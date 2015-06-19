<?php
require_once 'class/ConnectToDB.php';
require_once 'lib/function.php';
class User{
	// UserLogin vars
	public $login;
	public $password;
	public $login_date;
	// UserRegister vars
// 	public $new_login;
// 	public $email;
// 	public $new_password;
// 	public $r_password;
// 	public $registration_date;
	
	public function UserLogin() {
		$this->login = trim ( $_POST ['login'] );
		$this->password = trim ( $_POST ['password'] );
		$this->login = stripslashes ( $this->login );
		$this->password = stripslashes ( $this->password );
		$this->login = mysql_real_escape_string ( $this->login );
		$this->password = mysql_real_escape_string ( $this->password );
		$this->login_date = date ( "Y:m:d h:m:s" );
		if (isset ( $_POST ['submit'] )) {
			
			$loginUserConnection = new ConnectToDb ();
			$sql = "SELECT * FROM users WHERE login = '{$this->login}' AND password = '" . sha1 ( 'ololo' . $this->password ) . "' ";
			$result = $loginUserConnection->sqlQuery ( $sql );
			
			$count = mysqli_num_rows ( $result );
			echo ' <br />';
			
			if ($count == 1) {
				$cookie_name = 'Username';
				$cookie_value = $this->login;
				
				header('Location: index.php');
				setcookie ( "Username", $cookie_value, time () + (86400 * 30), "/php/hw11/" ); // 86400 = 1 day
				if (isset ( $_COOKIE ["$cookie_name"] )) {
					header ( "Location: index.php" );
				}
				echo "Welcome " . $this->login;
				$sql = "UPDATE users
					SET	 update_at= '" . $this->login_date . "'
					Where `login` = '" . $this->login . "'";
				$result = $loginUserConnection->sqlQuery ( $sql );
			} else {
				echo 'Wrong username or password <br />';
				echo "Try again or <a href='index.php'>Register</a>";
			}
		}
	}
	
	public function UserRegister() {
		$new_login = trim ( $_POST ['new_login'] );
		$email = trim ( $_POST ['email'] );
		$new_password = trim ( $_POST ['new_password'] );
		$r_password = trim ( $_POST ['new_r_password'] );
		$registration_date = date ( "Y:m:d h:m:s" );
		
		$new_login = stripslashes ( $new_login );
		$email = stripslashes ( $email );
		$new_password = stripslashes ( $new_password );
		$r_password = stripslashes ( $r_password );
		$new_login = mysql_real_escape_string ( $new_login );
		$email = mysql_real_escape_string ( $email );
		$new_password = mysql_real_escape_string ( $new_password );
		$r_password = mysql_real_escape_string ( $r_password );
		
		if (isset ( $_POST ['register'] )) {
			$registerUserConnection = new ConnectToDb ();
			
			if ($new_password === $r_password) {
				$new_password = sha1 ( 'ololo' . $new_password );
			} else {
				exit ("passwords do not match");
			}
			
			if (! (filter_var ( $email, FILTER_VALIDATE_EMAIL ))) {
				exit ("This ($email) email address is not valid.");
			}
			
			if (! preg_match ( "#^[A-Za-z0-9]+$#", $new_login )) {
				exit("Please use letters or digits");
			}
			
			$sql = "SELECT * FROM users WHERE `email` = '{$email}' OR `login` = '{$new_login}'";
			$result = $registerUserConnection->sqlQuery ( $sql );
			
			$count = mysqli_num_rows ( $result );
			
			if ($count >= 1) {
				exit ("USER OR EMAIL is occupied");
			} else {
				$sql = "INSERT INTO users(login, password, email, create_at )
					 VALUES ('" . $new_login . "','" . $new_password . "', '" . $email . "', '" . $registration_date . "')";
				$result = $registerUserConnection->sqlQuery ( $sql );
				
				if ($result) {
					echo "Welcome <br />";
					mail ( $email, "Сообщение с сайта " . $_SERVER ['SERVER_NAME'], "Приветствуем Вас на сайте " . $_SERVER ['SERVER_NAME'] );
					echo "Email has been set to " . $email . "<br /> Now you can <a href='login.php'>log in</a>";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	}
	
	public function logoutUser() {
		if (isset ( $_POST ['logout'] )){
			
			echo 11;
		}
	}
}