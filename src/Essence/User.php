<?php

namespace Project;

class User {
    //=== Название таблицы c товарами
    private static $table_name = 'user';
    
    //=== Получаем имя таблицы
    public static function getTableName()
    {
        return self::$table_name;
    }

    //=== Получаем список элементов
    public static function getList($name = "") {
        //=== Подключение к базе
        $connection = Connection::connect();

        $sql_where = "";
        //=== Получаем определенный элемент
        if ($name != "") {
            $sql_where = "WHERE name LIKE '" . $name . "'";
        }

        //=== SQL
        $result = array();
        $sql = "SELECT * FROM `".self::getTableName()."` " . $sql_where . " ORDER BY id ASC;";
        if ($result_query = mysqli_query($connection, $sql)) {
            while ($elem = $result_query->fetch_assoc()) {
                $result[] = $elem;
            }
        }

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Добавляем таблицу
    public static function createTable() {
        //=== Подключение к базе
        $connection = Connection::connect();

        //=== SQL
        $result = FALSE;
        $sql = "CREATE TABLE IF NOT EXISTS ".self::getTableName()."(
		    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    name varchar(255),
                    password varchar(255)
		)";
        if (mysqli_query($connection, $sql)) {
            $result = TRUE;
            echo "Table create successfully.";
        } else {
            echo "False table create.";
        }

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Удаляем таблицу
    public static function dropTable() {
        //=== Подключение к базе
        $connection = Connection::connect();

        //=== SQL
        $result = FALSE;
        $sql = "DROP TABLE `".self::getTableName()."`";
        if (mysqli_query($connection, $sql)) {
            $result = TRUE;
            echo "Table drop successfully.";
        } else {
            echo "False table drop.";
        }

        //=== Возвращаем значения
        return $result;
    }

    //=== Добавляем элемент
    public static function add($data_query = array()) {
        $error = array();
        $result['success'] = 0;
        
        if (strlen($data_query['name']) < 6) {
            $error[] = array('text'=>'Имя должно быть длинее 6 символов');
        }
        if (strlen($data_query['password']) < 6) {
            $error[] = array('text'=>'Пароль должен быть длинее 6 символов');
        }
        if (!$data_query['password_confirm']) {
            $error[] = array('text'=>'Введите подтверждение пароль');
        }
        if ($data_query['password_confirm'] != $data_query['password']) {
            $error[] = array('text'=>'Пароль и подтверждение не совпадают');
        }
        
        if (empty($errors)) {
            $user = end(Project\User::getList($messageData['name']));
            if ($user['id']) {
                $error[] = array('text'=>'Пользователь уже существует');
            }
        }
        
        if (empty($error)) {
            //=== Подключение к базе
            $connection = Connection::connect();

            //=== Обработка элементов
            $data_query['password'] = password_hash($data_query['password'], PASSWORD_DEFAULT);

            //=== SQL
            $result['success'] = FALSE;
            $sql = "INSERT INTO `".self::getTableName()."` (name, password) VALUES ('" . $data_query['name'] . "', '" . $data_query['password'] . "')";

            if (mysqli_query($connection, $sql)) {
                $result['success'] = 1;
                $result['message'][] = array('text'=>'Запись успешно добавлена');
            } else {
                $error[] = array('text'=>'Ошибка записи');
            }
        }

        $result['error'] = $error;
        
        return $result;
    }

    //=== Изменяем элемент
    public static function update($data_query) {
        $error = array();
        $result['success'] = 0;
        
        if (!$data_query['name']) {
            $error[] = array('text'=>'Введите логин');
        }
        if (!$data_query['password']) {
            $error[] = array('text'=>'Введите пароль');
        }
        if (!$data_query['password_confirm']) {
            $error[] = array('text'=>'Введите подтверждение пароль');
        }
        if ($data_query['password_confirm'] != $data_query['password']) {
            $error[] = array('text'=>'Пароль и подтверждение не совпадают');
        }
        
        //=== Подключение к базе
        $connection = Connection::connect();

        $update_param_sql = "";
        $update_param_arr = [];
        //=== Обработка элементов
        $update_param_arr[] = "password = " . password_hash($data_query['password']);

        //=== Собираем строку
        if (!empty($update_param_arr)) {
            $update_param_sql = implode(",", $update_param_arr);
        }

        //=== SQL
        $user = self::getList($data_query['name']);
        $data_query['id'] = $user['id'];

        $result['success'] = FALSE;
        $sql = "UPDATE `".self::getTableName()."` SET
				" . $update_param_sql . "
     			WHERE id =" . $data_query['id'] . "";

        if (mysqli_query($connection, $sql)) {
            $result['success'] = TRUE;
            $result['message'][] = "Запись успешно обновлена";
        } else {
            $error[] = array('text'=>'Ошибка подключения');
        }

        //=== Возвращаем значения
        return $result;
    }

    //=== Удаляем элемент
    public static function delete($params) {
        $id = $params['id'];
        //=== Удаляем запись
        $result['success'] = FALSE;
        $sql = "DELETE FROM `".self::getTableName()."` WHERE id =" . $params['id'] . "";
        if (mysqli_query($connection, $sql)) {
            $result['success'] = TRUE;
            $result['message'][] = "Запись успешно удалена";
        } else {
            $result['error'][] = "Ошибка";
        }

        //=== Возвращаем значения
        return $result;
    }

    //=== Авторизуем пользователя
    public static function auth($data_query) {
        $error = array();
        $result['success'] = 0;
        
        if (!$data_query['name']) {
            $error[] = array('text'=>'Введите логин');
        }
        if (!$data_query['password']) {
            $error[] = array('text'=>'Введите пароль');
        }
        
        if (empty($error)) {
            $user_list = self::getList($data_query['name']);
            $user = end($user_list);

            if ($user['id'] > 0 && (password_verify($data_query['password'], $user['password']))) {
                $result['user_id'] = $user['id'];
                $result['user_name'] = $user['name'];
                $result['auth_token'] = self::getRandomString(20);

                $result['success'] = 1;
                $result['message'][] = array("text"=>"Авторизация успешна");
            } else {
                $result['success'] = 0;
                $error[] = array("text"=>"Неверный логин или пароль");
            }
        }
        
        $result['error'] = $error;
        
        /*echo "<pre style='font-size:14px;'>";
        print_r($data_query);
        echo "<br/>";
        print_r($user);
        echo "<br/>";
        echo password_verify($data_query['password'], $user['password']);
        echo "<br/>";
        print_r($result);
        echo "</pre>";*/

        return $result;
    }

    //=== Пользователь админ?
    public static function isAdmin() {
        session_start();
        return ($_SESSION['IS_ADMIN'] === TRUE) ? TRUE : FALSE;
    }

    public static function logout() {
        session_start();
        unset($_SESSION['IS_ADMIN']);
    }

    public static function getRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }
}

?>
