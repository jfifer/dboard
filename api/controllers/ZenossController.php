<?php
include_once 'AbstractController.php';

class ZenossController extends AbstractController {
   public function getAction($request) {
   }

   public function postAction($request) {
      $params = $request->url_elements;
      $action = $params[2];
      $zenoss = new ZenossModel();
      switch($action) {
         case "SearchRouter" :
            $data = $zenoss->doSearch($params);
            break;
         case "EventsRouter" :
            $data = $zenoss->doEvents($params, $request->parameters);
            break;
         default :
            break;
      }
      return $data;
   }
}
