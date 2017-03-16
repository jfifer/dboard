<?php
require_once 'db/config.php';

abstract class AbstractDataModel {

   private $dbh_portal = null;

   private $row_limit = 50;

   function __construct() {

   }

   public function get_row_limit() {
      return $this->row_limit;
   }

   function jiraGet($username, $password, $url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
      $result = curl_exec($ch);

      if($ch_err = curl_error($ch)) {
         return array("err"=>true, "message"=>$ch_err);
      } else {
         return $result;
      }
   }

   function jiraPost($username, $password, $data, $url) {
      $ch = curl_init();
      $headers = array("Accept: application/json",
                       "Content-Type: application/json");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_VERBOSE, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
      $result = curl_exec($ch);
      
      if($ch_err = curl_error($ch)) {
         return array("err"=>true, "message"=>$ch_err);
      } else {
         return $result;
      }
   }
   
   function connect_portal_db() {
      // Establish a connection to the database server.
      if($this->dbh_portal == null) {
         $this->dbh_portal = mysqli_connect(DB_SERVER_PORTAL, DB_USER_PORTAL, DB_PASS_PORTAL, DB_NAME_PORTAL, DB_PORT_PORTAL);
         if (mysqli_connect_errno()) {
            $err_params = array();
            $err_params['sql_error'] = mysqli_connect_error($this->dbh_portal);
            $err_params['db_host'] = DB_SERVER_PORTAL;
            $err_params['db_name'] = DB_NAME_PORTAL;
            return false;
         }
      }
      return true;
   }
   function get_dbh_portal() {
      if($this->dbh_portal == null) {
         $this->connect_portal_db();
      }
      return $this->dbh_portal;
   }

   function connect_itop_db() {
      // Establish a connection to the database server.
      if($this->itop_dbh == null) {
         $this->itop_dbh = mysqli_connect(DB_ITOP_SERVER, DB_ITOP_USER, DB_ITOP_PASS, DB_ITOP_NAME, DB_ITOP_PORT);
         if (mysqli_connect_errno()) {
            $err_params = array();
            $err_params['sql_error'] = mysqli_connect_error($this->itop_dbh);
            $err_params['db_host'] = DB_ITOP_SERVER;
            $err_params['db_name'] = DB_ITOP_NAME;
            return false;
         }
      }
      return true;
   }
   function get_itop_dbh() {
      if($this->itop_dbh == null) {
         $this->connect_itop_db();
      }
      return $this->itop_dbh;
   }
   
   function do_curl($uri) {
      // inject common variables to data container
        $data['tid'] = 1;
        $data['type'] = "rpc";
        // fetch authorization cookie
        $ch = curl_init("https://monitor.coredial.com:443/zport/acl_users/cookieAuthHelper/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "jfifer:zB7JTp");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
        curl_exec($ch);
        // execute xmlrpc action
        curl_setopt($ch, CURLOPT_URL, "https://monitoring.coredial:443{$uri}");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);
        // error handling
        if($result===false)
            throw new Exception('Curl error: ' . curl_error($ch));
        // cleanup
        curl_close($ch);
        return $result;
   }
   
   function convert_to_array2($dataResource) {
      $newArray = array();
      $var_type = gettype($dataResource);
      if ($var_type == "object") {
         for ($i = 0; $i < mysqli_num_rows($dataResource); $i++) {
            $data = mysqli_fetch_assoc($dataResource);
            foreach ($data as $key => $value) {
               $newArray[$i][$key] = $value;
            }
         }
      }
      return $newArray;
   }
   
   function last_insert_id() {
        return $this->get_dbh_portal()->insert_id;
    }
};
