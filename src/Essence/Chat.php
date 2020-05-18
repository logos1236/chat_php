<?php
namespace Project;

class Chat {
    //=== Название таблицы c товарами
    private static $table_name = 'chat';
    
    //=== Получаем имя таблицы
    public static function getTableName()
    {
        return self::$table_name;
    }

    //=== Добавляем таблицу
    public static function createTable() {
        //=== Подключение к базе
        $connection = Connection::connect();

        //=== SQL
        $result = FALSE;
        $sql = "CREATE TABLE IF NOT EXISTS ".self::getTableName()."(
                    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    chat_id int,
		    user_author int,
                    date datetime DEFAULT CURRENT_TIMESTAMP,
                    message text
		)";
        if (mysqli_query($connection, $sql)) {
            $result = TRUE;
            echo "Table create successfully.";
        } else {
            echo "False table create.";
        }
        
        Connection::close();

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
        
        Connection::close();

        //=== Возвращаем значения
        return $result;
    }
    
    //=== Добавляем сообщение
    public static function setMessage($data_query = array()) {
        //=== Подключение к базе
        $connection = Connection::connect();

        if (!($data_query['user_author'])) {
            throw new \Exception('Empty user_author');
        }
        
        if (!($data_query['chat_id'])) {
            throw new \Exception('Empty chat_id');
        }
        
        //=== SQL
        $result['success'] = FALSE;
        $sql = "INSERT INTO `".self::getTableName()."` (chat_id, user_author, message) VALUES (". $data_query['chat_id'] . ", ". $data_query['user_author'] . ", '" . $data_query['message'] . "')";

        if (mysqli_query($connection, $sql)) {
            $result['success'] = TRUE;
            $result['message'][] = "Запись успешно добавлена";
        } else {
            $result['error'][] = "Ошибка записи";
        }
        
        Connection::close();
        
        //=== Возвращаем значения
        return $result;
    }
    
    //=== Получаем список элементов
    public static function getMessageList($filter = array(), $additional_select = array(), $order = array(), $count = FALSE, $page = 1, $get_count_element = FALSE) {
        //=== Подключение к базе
        $connection = Connection::connect();

        $result = array();
        $result['count'] = 0;
        $result['list'] = array();
        
        $sql_get_additional = "";
        $filter_additional = "";
        $group_by = "";
        $order_by = "";
        
        //=== Сортировка
            $order_by = "ORDER BY id DESC";
            
        //=== Дополнительные поля  
            /*if (!empty($additional_select)) {
                $sql_get_additional .= implode(",", $additional_select).",";
            }*/
            
        //=== Фильтрация    
            //=== По id
                unset($input_name);
                $input_name = 'id'; 
                if ($filter[$input_name]>0) {
                    $filter_additional .= "AND (id > ".(int)$filter[$input_name].")";
                }
                
            //=== По id чата
                unset($input_name);
                $input_name = 'chat_id'; 
                if ($filter[$input_name]>0) {
                    $filter_additional .= "AND (chat_id = ".(int)$filter[$input_name].")";
                }     
            
        //=== Пагинация
            $limit = "";
            $offset = "";
            if ($get_count_element == FALSE) {
                if ($count != FALSE) {
                    $limit = "LIMIT ".$count;
                    if ($page > 1) {
                        $offset = "OFFSET ".$count*($page-1);
                    }
                }
            }     
            
        //=== Вывод полей
            if ($get_count_element == FALSE) {
                $sql_get =  "*";
            } else {
                $sql_get =  "COUNT(id) as count";
            }
            
        //=== Запрос
            $sql_query = "SELECT 
                            ".$sql_get."
                        FROM `".self::getTableName()."`
                        WHERE id > 0  
                        ".$filter_additional."
                        ".$group_by."
                        ".$order_by." 
                        ".$limit." 
                        ".$offset."
                        ;";

        
        
        //=== SQL            
        if ($result_query = mysqli_query($connection, $sql_query)) {
            while ($item = $result_query->fetch_assoc()) {  
                if ($get_count_element == FALSE) {
                    $result['list'][] = $item;
                    $result['count']++;
                } else {
                    $result['count'] = $item['count'];
                }
            }
        }
        
        Connection::close();

        //=== Возвращаем значения
        return $result;
    }
    
    public static function sendMessageArray($item) {
        if (!empty($item)) {
            $result['list'][] = $item;
            $result['count']=1;
        }
        
        return $result;
    }
}

?>
