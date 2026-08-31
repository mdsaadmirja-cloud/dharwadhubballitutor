<?php
class userLoginModel
{
    private $id;
    private $user_name;
    private $user_password;
    private $user_type;
    private $user_status;
    private $user_role;

    function set_id($id){
        $this->id = $id;
    }

    function get_id(){
        return $this->id;
    }

    function set_username($username){
        $this->user_name = $username;
    }

    function get_username(){
        return $this->user_name;
    }

    function set_userpassword($userpassword){
        $this->user_password = $userpassword;
    }

    function get_userpassword(){
        return $this->user_password;
    }

    function set_usertype($usertype){
        $this->user_type = $usertype;
    }

    function get_usertype(){
        return $this->user_type;
    }

    function set_userstatus($userstatus){
        $this->user_status = $userstatus;
    }

    function get_userstatus(){
        return $this->user_status;
    }

    function setUserRole($userRole){
        $this->user_role = $userRole;
    }

    function getUserRole(){
        return $this->user_role;
    }
}
?>