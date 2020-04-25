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

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Добавляем элемент
    public static function add($params = array()) {
        //=== Подключение к базе
        $connection = Connection::connect();

        //=== Обработка элементов
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

        //=== SQL
        $result['success'] = FALSE;
        $sql = "INSERT INTO `".self::getTableName()."` (name, password) VALUES ('" . $params['name'] . "', '" . $params['password'] . "')";

        if (mysqli_query($connection, $sql)) {
            $result['success'] = TRUE;
            $result['message'][] = "Запись успешно добавлена";
        } else {
            $result['error'][] = "Ошибка записи";
        }

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Изменяем элемент
    public static function update($params) {
        //=== Подключение к базе
        $connection = Connection::connect();

        $update_param_sql = "";
        $update_param_arr = [];
        //=== Обработка элементов
        $update_param_arr[] = "password = " . password_hash($params['password']);

        //=== Собираем строку
        if (!empty($update_param_arr)) {
            $update_param_sql = implode(",", $update_param_arr);
        }

        //=== SQL
        $user = self::getList($params['name']);
        $params['id'] = $user['id'];

        $result['success'] = FALSE;
        $sql = "UPDATE `".self::getTableName()."` SET
				" . $update_param_sql . "
     			WHERE id =" . $params['id'] . "";

        if (mysqli_query($connection, $sql)) {
            $result['success'] = TRUE;
            $result['message'][] = "Запись успешно обновлена";
        } else {
            $result['error'][] = "Ошибка";
        }

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Удаляем элемент
    public static function delete($params) {
        //=== Подключение к базе
        $connection = Connection::connect();

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

        //=== Close connection
        mysqli_close($connection);

        //=== Возвращаем значения
        return $result;
    }

    //=== Авторизуем пользователя
    public static function auth($params) {
        session_start();

        $user_list = self::getList($params['name']);
        $user = $user_list[0];

        echo "<pre style='font-size:14px;'>";
        print_r($params);
        echo "<br/>";
        print_r($user_list);
        echo "<br/>";
        echo password_verify($params['password'], $user['password']);
        echo "</pre>";

        if ($user['id'] > 0 && (password_verify($params['password'], $user['password']))) {
            $_SESSION['IS_ADMIN'] = TRUE;

            $result['success'] = TRUE;
            $result['message'][] = "Авторизация успешна";
        } else {
            $_SESSION['IS_ADMIN'] = FALSE;

            $result['success'] = FALSE;
            $result['error'][] = "Неверный логин или пароль";
        }

        return $result;
    }

    //=== Пользователь админ?
    public static function isAdmin() {
        session_start();
        return ($_SESSION['IS_ADMIN'] === TRUE) ? TRUE : FALSE;
    }

    //=== Авторизуем пользователя
    public static function logout() {
        session_start();
        unset($_SESSION['IS_ADMIN']);
        echo "111111111";
    }

}

?>
