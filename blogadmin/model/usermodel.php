<?php
class User implements JsonSerializable
{
    private $id;
    private $user_name;
    private $user_email;
    private $user_password;
    private $user_contact;
    private $user_type;
    private $user_status;
    private $user_created_on;
    private $table_name = "user";


    function set_id($id)
    {
        $this->id = $id;
    }
    function get_id()
    {
        return $this->id;
    }

    function set_username($username)
    {
        $this->user_name = $username;
    }
    function get_username()
    {
        return $this->user_name;
    }

    function set_usercontact($usercontact)
    {
        $this->user_contact = $usercontact;
    }
    function get_usercontact()
    {
        return $this->user_contact;
    }
    function set_useremail($useremail)
    {
        $this->user_email = $useremail;
    }
    function get_useremail()
    {
        return $this->user_email;
    }

    function set_userpassword($userpassword)
    {
        $this->user_password = $userpassword;
    }
    function get_userpassword()
    {
        return $this->user_password;
    }

    function set_usertype($usertype)
    {
        $this->user_type = $usertype;
    }
    function get_usertype()
    {
        return $this->user_type;
    }

    function set_userstatus($userstatus)
    {
        $this->user_status = $userstatus;
    }
    function get_userstatus()
    {
        return $this->user_status;
    }

    function set_usercreatedon($usercreatedon)
    {
        $this->user_created_on = $usercreatedon;
    }
    function get_usercreatedon()
    {
        return $this->user_created_on;
    }
    public function jsonSerialize()
    {
        return [
            'user' => [
                'userid' => $this->id,
                'username' => $this->user_name,
                'usercontact' => $this->user_contact,
                'useremail' => $this->user_email,
                'userpassword' => $this->user_password,
                'usertype'=>$this->user_type,
                'userstatus' =>$this->user_status
            ]
        ];
    }
}
