<?php


class Role extends DatabaseLike
{
    public $id;
    public $name;

    /**
     * User constructor.
     * @param string $name
     * @param int $id
     */
    public function __construct($name = '', $id = 0)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('role', Role::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('name','string'));
        return $result;
    }

    /**
     * @param int $allow
     * @param User $user
     * @return bool
     */
    public static function allowed($allow, $user){
        if($allow == 0){
            return true;
        }
        $userRoles = $user->roles();
        if($allow < 0) {
            $allow += (pow(2, static::selectOne('', 'ORDER BY id DESC')->id*2)-1);
        }
        $allow = static::roles($allow);
        return count(array_intersect($allow, $userRoles)) > 0;
    }

    /**
     * @return string[]
     */
    public static function colours(){
        return ['#000000', '#516a9a', '#008c00', '#0000ff', '#00ff00', '#ff0000', '#ff00ff'];
    }

    /**
     * @param int $input
     * @return int[]
     */
    //method identical to user->roles()
    public static function roles($input){
        $max = static::selectOne('1 = 1', 'ORDER BY id DESC')->id;
        if($input < 0){
            $input += (pow(2, $max+1)-1);
        }
        $usr = new User();
        $usr->roles = $input;
        return $usr->roles();
    }

    /**
     * @param int $roles
     * @return string
     */
    public static function toString($roles){
        $prefix = '';
        /** @var Role[] $allRoles */
        $allRoles = static::selectMany('', 'ORDER BY id ASC');
        if($roles == 0){
            return '<span style="color: '.self::colours()[0].'">Visi</span>';
        }
        if($roles < 0){
            $prefix = 'NE ';
            $roles *= -1;
        }
        $result = [];
        $allowed = self::roles($roles);
        foreach ($allowed as $role) {
            $role = log($role, 2);
            $id = 0;
            for($i = 0; $i < count($allRoles); $i++){
                if($role == $allRoles[$i]->id){
                    $id = $i;
                    break;
                }
            }
            $result[] = '<span style="color: ' . self::colours()[$id] . '">' . $prefix . $allRoles[$id]->name . '</span>';
        }
        return join('<br />', $result);
    }

    /**
     * @param int $roles
     * @return void
     */
    public static function makeForm($roles = 0){
        ?>
        Rolės<br />
        <?php
        $checked = ($roles == 0)?'checked':'';
        ?>
        <label>Visi <input type="checkbox" name="roles[]" value="0" <?php echo $checked; ?> /></label>
        <?php
        $checked = ($roles < 0)?'checked':'';
        ?>
        <label>NE <input type="checkbox" name="no" value="true" <?php echo $checked; ?> /></label><br />
        <b>Visi</b> - reišks, kad bet gali galės matyt šį meniu. <br />
        <b>NE</b> - reišks, kad meniu matyt galės visi išskyrus pasirinktos rolės, t.y.<br />
        jeigu pasirinksite <i>NE</i> ir <i>Adminas</i>, tai meniu punkto <i>ne</i>matys tik <i>Adminas</i>.<br />
        <?php
        /** @var Role[] $allRoles */
        $allRoles = static::selectMany('', 'ORDER BY id ASC');
        $allowed = static::roles($roles);
        foreach($allRoles as $role){
            $id = $role->id*1+1;
            $checked = (in_array(pow(2, $id-1), $allowed))?'checked':'';
            echo '<label>'.$role->name.'<input type="checkbox" name="roles[]" value="'.$id.'" '.$checked.'/></label><br />';
        }
    }
}