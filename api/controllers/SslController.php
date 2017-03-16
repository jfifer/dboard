<?php
include_once 'AbstractController.php';

class SslController extends AbstractController {
   public function postAction($request) {
      $params = $request->parameters;
      if(isset($request->url_elements[2])) {
         switch($request->url_elements[2]) {
            case "createkey" :
               $ssl = new SslModel();
               $data = $ssl->createKey($params);
               break;
            case "generatecsr" :
               $ssl = new SslModel();
               $data = $ssl->generateCSR($params);
               break;
            default :
               break;
         }
      } else {
         $data = $this->errorResponse("Invalid Request");
      }
      return $data;
   }

   public function getAction($request) { }
}
