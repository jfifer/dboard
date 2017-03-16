<?php

require_once 'AbstractController.php';

class JiraController extends AbstractController {
   public function postAction($request) {
      $params = $request->url_elements;
      $jira = new JiraModel();
      if(isset($params[2])) {
         if(isset($params[3])) {
            switch($params[2]) {
               case "issue" :
                  $data = $jira->getIssueById($request->parameters['username'], $request->parameters['password'], $params[3]);
                  break;
               default:
                  break;
            }
         } else {
            $data = $jira->findAllOpenIssues($request->parameters['username'], $request->parameters['password']);
         }
      }
      return $data;
   }

   public function getAction($request) {
     $params = $request->url_elements;
   
   }
}
