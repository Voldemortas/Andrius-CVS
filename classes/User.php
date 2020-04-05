<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.26
 * Time: 10.50
 * Update 11:09
 */

class User extends DatabaseLike
{
    public $id;
    public $email;
    public $password;
    public $roles;

    /**
     * User constructor.
     * @param string $email
     * @param string $password
     * @param int $roles
     * @param int $id
     */
    public function __construct($email = '', $password = '', $roles = 1, $id = 0)
    {
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('user', User::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('email','string'));
        $result->add(new Property('password','string'));
        $result->add(new Property('roles','int'));
        return $result;
    }

    /**
     * @return int[]
     */
    public function roles(){
        $roles = [];
        $temp = $this->roles;
        $powered = pow(2, floor(log($this->roles, 2)));//iš 11 padarom 8, iš 5 - 4, iš 4 - 4
        while($temp > 0){
            $divided = floor($temp/$powered);
            if($divided == 1){
                $roles[] = $powered;
            }
            $temp -= $divided*$powered;
            $powered /= 2;
        }
        return $roles;
    }
}