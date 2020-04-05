<?php


abstract class DatabaseLike
{
    /**
     * @return BasicInfo
     */
    abstract static public function properties();

    /**
     * @return string
     */
    private function columns(){
        $properties = array_filter($this->properties()->properties, function($e){return $e->name != 'id';});
        return join(', ',
            array_map(
                function($element){
                    return $element->name;
                },
                $properties)
        );
    }

    /**
     * @return string
     */
    private function values(){
        $properties = array_filter($this->properties()->properties, function($e){return $e->name != 'id';});
        return join(', ',
            array_map(
            /**
             * @param Property $element
             * @return int|string
             */
                function($element){
                    $value = ($element->name);
                    if($element->type == 'string'){
                        return '\''.SQLite3::escapeString($this->$value).'\'';
                    }elseif($element->type = 'int'){

                        return $this->$value * 1;
                    }else{
                        return $this->$value;
                    }
                },
                $properties)
        );
    }

    /**
     * @return string
     */
    private function pairs(){
        $properties = array_filter($this->properties()->properties, function($e){return $e->name != 'id';});
        return join(', ',
            array_map(
            /**
             * @param Property $element
             * @return string
             */
                function($element){
                    $answer = $element->name.' = ';
                    $value = ($element->name);
                    if($element->type == 'string'){
                        $answer .= '\''.SQLite3::escapeString($this->$value).'\'';
                    }elseif($element->type = 'int'){
                        $answer .= $this->$value * 1;
                    }else{
                        $answer .= $this->$value;
                    }
                    return $answer;
                },
                $properties)
        );
    }

    /**
     * @param DatabaseLike $object
     * @param mixed $data
     * @return DatabaseLike
     */
    private static function setup($object, $data){
        $properties = static::properties();
        for($i = 0; $i < count($properties->properties); $i++){
            $name = $properties->properties[$i]->name;
            if($properties->properties[$i]->type == 'int'){
                $data[$name] *= 1;
            }
            $object->$name = $data[$name];
        }
        return $object;
    }

    /**
     * @return string
     */
    public function insert(){
        $properties = $this->properties();
        return 'INSERT INTO '.$properties->database.' ('.self::columns().') VALUES ('. self::values() .');';
    }

    /**
     * @param string $where
     * @param string $other
     * @return DatabaseLike[]
     */

    public static function selectMany($where = '', $other = ''){
        global $db;
        $properties = static::properties();
        if($where != ''){
            $where = 'WHERE '.$where.' ';
        }
        $sql = 'SELECT * FROM '.$properties->database.' '.$where.$other.';';
        $result = [];
        $i = 0;
        $query = $db->query($sql);
        while($res = $query->fetchArray()) {
            $temp = new $properties->object();
            $temp = static::setup($temp, $res);
            $result[] = $temp;
            $i++;
        }
        if(count($result) == 0){
            $result[0] = null;
        }
        return $result;
    }

    /**
     * @param string $where
     * @param string $other
     * @return DatabaseLike
     */
    public static function selectOne($where = '', $other = ''){
        return static::selectMany($where, $other.' LIMIT 1')[0];
    }

    /**
     * @param string $where
     * @return string
     */
    public static function deleteAny($where = '1 <> 0'){
        $properties = static::properties();
        return 'DELETE FROM '.$properties->database.' WHERE '.$where.';';
    }

    /**
     * @return string
     */
    public function deleteThis(){
        return static::deleteAny('id = '.$this->id*1);
    }

    public function edit($where){
        $properties = static::properties();
        return 'UPDATE '.$properties->database.' SET '.self::pairs().' WHERE '.$where.';';
    }
}