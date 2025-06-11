<?php 
require_once("parent.php");

class User extends ParentClass {
    public function __construct() {
        parent::__construct();
    }

    public function getUser($username) {
        $stmt = $this->mysqli->prepare("Select * FROM member Where username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function doLogin($username, $plainPassword){
        $user = $this->getUser($username);
        if($user){
            $is_authenticate = password_verify($plainPassword, $user['password']);
            return $is_authenticate;
        } else{
            return false;   
        }
    }
}
?>