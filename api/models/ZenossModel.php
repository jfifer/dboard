<?php
class ZenossModel extends AbstractDataModel {
   function doSearch($params) {
      $action = $params[2];
      $method = $params[3];
      $type = $params[4];
      $tid = $params[5];
      $query = $params[6];

      $data = array(
         "action"=>$action,
         "method"=>$method,
         "type"=>$type,
         "tid"=>$tid,
         "data"=>array(array('query'=>$query))
      );
 
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_USERPWD, "jfifer:zB7JTp");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json',
                                            'Connection: Keep-Alive'
                                            ));
      $url = "https://monitoring.coredial.com/zport/dmd/search_router";

      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
      return array("res"=>$result);
   }

   function doEvents($params, $query) {
      $action = $params[2];
      $method = $params[3];
      $type = $params[4];
      $tid = $params[5];
      $data = array(
         "action"=>$action,
         "method"=>$method,
         "type"=>$type,
         "tid"=>$tid,
         "data"=>$query
      );
     
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_USERPWD, "jfifer:zB7JTp");
      curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                            'Content-Type: application/json',
                                            'Connection: Keep-Alive'
                                            ));
      $url = "https://monitoring.coredial.com/zport/dmd/evconsole_router";

      curl_setopt($ch, CURLOPT_URL, $url);
      $result = curl_exec($ch);
      return array("res"=>$result);
   }

}
