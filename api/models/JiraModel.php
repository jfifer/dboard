<?php
class JiraModel extends AbstractDataModel {
   function doAuth() { }
 
   function createJiraIssue() { }

   function findAllOpenIssues($username, $password) {
      $url = "https://jira.coredial.com/rest/api/2/search/";
      $data = array(
         "jql"=>"project = FIFE AND reporter = \"jfifer@coredial.com\" AND resolution = Unresolved"
         //"fields"=>array("id","key","resolution", "cf[10909]")
      );
      return $this->jiraPost($username, $password, $data, $url);
   }

   function findIssueByName($username, $password, $organizationName) {
      $url = "https://jira.coredial.com/rest/api/2/search/";
      $data = array(
         "jql"=>"project = FIFE AND reporter = \"jfifer@coredial.com\" AND \"O - Organization Name\" ~ \"" . $organizationName ."\"",
         "fields"=>array("id","key","resolution")
      );
      return $this->jiraPost($username, $password, $data, $url);
   }

   function getIssueById($username, $password, $issueId) {
      $url = "https://jira.coredial.com/rest/api/2/issue/".$issueId;
      return $this->jiraGet($username, $password, $url);
   }
}
