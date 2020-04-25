<?php
namespace Project;

class Connection {

    public static function connect() {
        global $connection;
        
        if(!$connection){            
            $db_conn = Settings::get("db_conn");

            //=== Подключение к базе
            $connection = mysqli_connect($db_conn['host'], $db_conn['login'], $db_conn['password'], $db_conn['name']);
            if ($connection === false) {
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        }

        return $connection;
    }
    
    public static function close() {
        global $connection;
        
        if($connection){
            mysqli_close($connection);

            $connection = false;
        }
    }

}
?>
