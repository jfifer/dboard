<?php
class SslModel extends AbstractDataModel {
   public $descriptorspec = array(
      0 => array("pipe", "r"),
      1 => array("pipe", "w")
   );

   function createKey($params) {
      $keysize = 2048;
      $ssl = openssl_pkey_new(array("private_key_bits"=>$keysize));
      if($ssl) {
         return $this->generateCSR($ssl, $params);
      } else {
         return array("err"=>true, "message"=>"Something went horribly wrong.");
      }
   }

   function generateCSR($ssl, $dn) {
      if($csr = openssl_csr_new($dn, $ssl, array())) {
         return $this->exportKeys($ssl, $csr, $dn);
      } else {
         return array("err"=>true, "message"=>"goddamnit");
      }
   }

   function exportKeys($ssl, $csr, $dn) {
      $CN = $dn['commonName'];
      $pkeyout = "../keys/" . $CN . ".key";
      $csrout = "../keys/" . $CN . ".csr";
      if(openssl_csr_export_to_file($csr, $csrout) && openssl_pkey_export_to_file($ssl, $pkeyout, "")) {
         return $this->emailCSR($csr, $dn);
      } else {
         return array("err"=>true, "message"=>"Something screwed up");
      }
   }

   function emailCSR($csr, $dn) {
      $CN = $dn['commonName'];
      $path = "../keys/" . $CN . ".csr";
      $to = 'jfifer@coredial.com,'.$dn['emailAddress'];
      $subject = 'Test email with attachment';
      $random_hash = md5(date('r', time()));
      $headers = "From: jfifer@coredial.com\r\nReply-To: jfifer@coredial.com";
      $headers .= "\r\nContent-Type: application/text; name=\"$path\"";
      $headers .= "\r\nContent-Transfer-Encoding: base64";
      $headers .= "\r\nContent-Disposition: attachment";
      $attachment = chunk_split(base64_encode(file_get_contents($path)));
      $message = "hello";
      $mailres = @mail($to, $subject, $message, $headers);
      if($mailres) {
         return array("err"=>false, "res"=>$mailres);
      } else {
         return array("err"=>true, "res"=>$mailres);
      }
   }
}
