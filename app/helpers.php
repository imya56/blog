<?php

require_once 'db_config.php';

if (!function_exists('db_connect')) {
/**
 *
 * create connect with db.
 *
 *  
 * @param [$dbname (string) ] name of db 
 * 
 * @return null
 *
 */
    function db_connect($dbname = 'smile')
    {
        global $mysql_link;
        if (is_null($mysql_link)) {

            $mysql_link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, $dbname);
            mysqli_set_charset($mysql_link, 'utf8');

        }
    }
}

if (!function_exists('old')) {
    /**
 *
 *  return old value of inputs.
 *
 *  
 * @param $fn (string) name of input
 * 
 * @return old input value
 *
 */
    function old($fn)
    {
        return $_REQUEST[$fn] ?? '';
    }
}

if (!function_exists('db_insert')) {
/**
 *
 *  insert into db
 *
 *  
 * @param $sql(string) the sql query, [$id (bool)] true if want return id
 * 
 * @return inserted id / affected rows
 *
 */
    function db_insert($sql, $id = false)
    {
        global $mysql_link;
        db_connect();

        $res = mysqli_query($mysql_link, $sql);
        if (!$res) {
            return false;
        }
        return $id ? mysqli_insert_id($mysql_link) : mysqli_affected_rows($mysql_link);

    }
}
if (!function_exists('db_delete')) {
/**
 *
 * delete from db.
 *
 *  
 * @param $sql(string) the sql query 
 * 
 * @return affected rows
 *
 */
    function db_delete($sql)
    {
        global $mysql_link;
        db_connect();

        $res = mysqli_query($mysql_link, $sql);
      
        return mysqli_affected_rows($mysql_link);

    }
}
if (!function_exists('db_update')) {
/**
 *
 * db update.
 *
 *  
 * @param $sql(string) the sql query
 * 
 * @return affected rows
 *
 */
    function db_update($sql)
    {
        global $mysql_link;
        db_connect();

        $res = mysqli_query($mysql_link, $sql);
      
        return mysqli_affected_rows($mysql_link);

    }
}

if (!function_exists('db_query')) {
    /**
 *
 * db query to 1 row.
 *
 *  
 * @param $sql(string) the sql query
 * 
 * @return assoc array
 *
 */
    function db_query($sql)
    {
        global $mysql_link;
        db_connect();
        $res = mysqli_query($mysql_link, $sql);
        return mysqli_fetch_assoc($res);

    }
}

if (!function_exists('db_query_all')) {
  /**
 *
 * db query to more than 1 row.
 *
 *  
 * @param $sql(string) the sql query
 * 
 * @return  array
 *
 */
    function db_query_all($sql)
    {
        db_connect();
        global $mysql_link;
        $data = [];
        $res = mysqli_query($mysql_link, $sql);
        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        return $data;
    }

}

if (!function_exists('output')) {
/**
 *
 * prepare string to output
 *
 *  
 * @param $str(string) the str that need prepare
 * 
 * @return string
 *
 */
    function output($str)
    {

        $str = htmlspecialchars($str);
        $str = str_replace("\n", '<br>', $str);
        if(preg_match("/[א-ת]/", $str)){
            $str = '<span dir="rtl" class="float-right">' . $str . '</span><div class="clearfix"></div>';
        }
        return $str;
    }

}

if(!function_exists('sess_login')){
/**
 *
 *  start session.
 *
 *  
 * @param $id(int) the session id 
 * @param $name(string) user name
 * 
 * @return null
 *
 */
    
    function sess_login($id, $name){
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];     
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $name;
    }

}

if(!function_exists('veryfy_user')){

    /**
 *
 * Verify user by ip, id and user agent.
 *
 *  
 * 
 * 
 * @return bool
 *
 */
    function verify_user(){
        $verify = false;

        if(isset($_SESSION['user_id']) && $_SERVER['REMOTE_ADDR'] == $_SESSION['user_ip']){
            
            if(isset($_SESSION['user_agent']) && $_SERVER['HTTP_USER_AGENT'] == $_SESSION['user_agent']  ){
                $verify = true;
            }

        }
        return $verify;
    }
}

if (!function_exists('start_session')){
/**
 *
 * My session start.
 *
 *  
 * @param $name(string) the session name
 * 
 * @return null
 *
 */

        function start_session($name = null){
            if (!is_null($name)){
                session_name($name);
            } 
            session_start();
            session_regenerate_id();
    
        }
    
    }

    if (!function_exists('csrf_token')) {
/**
 *
 * My session start.
 *
 *  
 * @param $name(string) the session name
 * 
 * @return null
 *
 */
        function csrf_token() {
            $token = 'smile_token' . rand(1000,10000) . 'smilysmile';
            $_SESSION['token'] = $token;
            return $token;
        }

    }