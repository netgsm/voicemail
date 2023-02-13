<?php

namespace  Netgsm\Seslimesaj;

use CURLFile;
use Exception;
use Ramsey\Uuid\Type\Integer;
use SimpleXMLElement;

class Package
{

    private $username;
    private $password;
    public function __construct()
    {
     if(isset($_ENV['NETGSM_USERCODE']))
      {
          $this->username=$_ENV['NETGSM_USERCODE'];
      }
      else{
          $this->username='x';
      }
      if(isset($_ENV['NETGSM_PASSWORD']))
      {
          $this->password=$_ENV['NETGSM_PASSWORD'];
      }
      else{
          $this->password='x';
      }
        
    }
    public function sesyukle($data):array
    {

        $target_url ="https://api.netgsm.com.tr/voicesms/upload";
        $fname = $data['fname'];	// dosya yolunu belirtin.
        $res=[];
        $res['durum']='';
        if(!file_exists($fname))
        {
            $res['durum']='Dosya yolu geçersiz.';
            return $res;
        }
        $cfile = new CURLFile(realpath($fname));

        $post = array (
                  'dosya' => $cfile,
                  'username' => $this->username,
                  'password' => $this->password
                  );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: multipart/form-data'));
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec ($ch);

        $sonuc=array(
            30=>"Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.",
            40=>"Göndermeye çalıştığınız dosya boyutunun maksimum gönderebileceğiniz dosya boyutunu aştığını ifade eder. (Maksimum dosya boyutu: 4 MB)",
            10=>"Dosyanızın sistemimize başarılı bir şekilde alınamadığını ifade eder.",
            20=>"Geçersiz dosya uzantısını ifade eder. (İzinli Uzantılar : '.wav'"
        );
        if ($result === FALSE) {
            echo "Error sending" . $fname .  " " . curl_error($ch);
            curl_close ($ch);
        }
        elseif($result==10||$result==20||$result==30||$result==40)
        {
                $cevap['durum']=$sonuc[$result];
                $cevap['code']=$result;
        }
        else{
            $cevap['durum']="İşlem başarılı";
            $cevap['sesid']=$result;
        }
        return $cevap;

    }
    public function seslistele($data):array
    {
        $startdate=null;
        $stopdate=null;
        if(!isset($data['startdate']))
        {
            $startdate=null;
        }
        else{
            $startdate=$data['startdate'];
        }
        if(!isset($data['stopdate']))
        {
            $stopdate=null;
        }
        else{
            $stopdate=$data['stopdate'];
        }

        $sonuc=array(
            "30"=>"Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.",
            "40"=>"Arama kriterlerinize göre listelenecek kayıt olmadığını ifade eder.",
            "50"=>"Arama kriterlerindeki tarih formatının hatalı olduğunu ifade eder. (ddMMyyyyHHmm)",
            "60"=>"Arama kiterlerindeki startdate ve stopdate zaman farkının 30 günden fazla olduğunu ifade eder.",
            "70"=>"Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder."
        );
      $xmlData='<?xml version="1.0"?>
     <mainbody>
       <header>
         <usercode>'.$this->username.'</usercode>
         <password>'.$this->password.'</password>
         <startdate>'.$startdate.'</startdate>
         <stopdate>'.$stopdate.'</stopdate>
       </header>
     </mainbody>';
     $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://api.netgsm.com.tr/voicesms/uploadlist');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		$result = curl_exec($ch);
        
        $dz=array_filter(explode("<br>",$result));
        
        if(count($dz)>1){
            $sesDz=array();
            foreach($dz as $d=>$v){
                $sesDz=explode("|",$v);
                $response[$d]['AudioID']=trim($sesDz[0]);
                $response[$d]['gonderentelno']=trim($sesDz[1]);
                $response[$d]['tarih']=trim($sesDz[2]);
                $response[$d]['mesajsuresi']=trim($sesDz[3]);
                $response[$d]['yuklenmisdosya']=trim($sesDz[4]);

            }
        }
        elseif($dz[0]==30 || $dz[0]==40 || $dz[0]==50 ||$dz[0]==60 || $dz[0]==70  )
        {
            $response['code']=$dz[0];
            $response['message']=$sonuc[$dz[0]];
        }
        else{
            $response['durum']="Sonuç bulunamadı";
        }

        return $response;
    }
    public function basitSesliMsg($data):array
    {


        if(!isset($data['startdate']))
        {
            $data['startdate']=null;
        }
        if(!isset($data['starttime']))
        {
            $data['starttime']=null;
        }
        if(!isset($data['stopdate']))
        {
            $data['stopdate']=null;
        }
        if(!isset($data['stoptime']))
        {
            $data['stoptime']=null;
        }
        if(!isset($data['key']))
        {
            $response['durum']='key değerini istenilen biçimde giriniz.key=1 Ses kaydının sonunda tuşa basılmasını istiyorum. key=0	Ses kaydının sonunda tuşa basılmasını istemiyorum. ';
            return $response;
        }
        if(!isset($data['ringtime']))
        {
            $data['ringtime']=30;
        }
        if(!isset($data['filter']))
        {
            $data['filter']=null;
        }
        if(!isset($data['relationid']))
        {
            $data['relationid']=rand(100000,999999);
        }

        if(empty($data['no']) || !isset($data['no']))
        {
            $response['durum']='Numara giriniz.';
            return $response;
        }
        else{
            $gsm='';
            foreach($data['no'] as $d)
            {

               $gsm.="<no>".$d."</no>\r\n";
            }

        }

        if(!isset($data['baslangicaudioid']) &&  !isset($data['baslangictext'])   )
        {
            $response['durum']='baslangicaudioid ve ya baslangictext giriniz... ';
            return $response;
        }
        if(empty($data['baslangicaudioid']) &&  empty($data['baslangictext'])   )
        {
            $response['durum']='baslangicaudioid ve ya baslangictext giriniz... ';
            return $response;
        }
        else{
            if(isset($data['baslangictext']))
            {
                $baslangic='<text>'.$data['baslangictext'].'</text>';
            }
            elseif(isset($data['baslangicaudioid']))
            {
                $baslangic='<audioid>'.$data['baslangicaudioid'].'</audioid>';
            }
        }

        if($data['key']==1){

            $keys="";
            foreach($data['keyinfo'] as $d )
            {
                $keys.="<keys>\r\n";
               if(!isset($d['tus'])){
                    $response['durum']='tus bilgisi bulunmamaktadır.Lütfen tekrar kontrol ediniz. ';
                    return $response;
               }
               if($d['tus']>10 || $d['tus']<0 ){
                $response['durum']='tus bilgisi 0-9 arası olmalıdır. ';
                return $response;;
           }
               $keys.="<keydetail>\r\n <keyinfo>".$d['tus']."</keyinfo>\r\n";

               if(!isset($d['ses']) && !isset($d['text']) ){
                    $response['durum']='ses yada text parametrelerini doldurmadınız...';
                    return $response;
               }
               else{
                    if(isset($d['ses']))
                    {
                        $gonderilen="<audioid>".$d['ses']."</audioid>\r\n</keydetail>\r\n";
                    }
                    elseif(isset($d['text']))
                    {
                        $gonderilen="<text>".$d['text']."</text>\r\n</keydetail>\r\n";
                    }
                    else{
                        $response['durum']="Hatalı format";
                        return $response;
                    }
               }
               $keys.=$gonderilen;
               $keys.="</keys>";
            }

        }
        elseif($data['key']==0){
            $keys="";
        }
        else{
            $response['durum']='key değerini istenilen biçimde giriniz.key=1 Ses kaydının sonunda tuşa basılmasını istiyorum. key=0	Ses kaydının sonunda tuşa basılmasını istemiyorum. ';
            return $response;
        }


        $xmlData='<?xml version="1.0"?>
       <mainbody>
       <header>
       <usercode>'.$this->username.'</usercode>
       <password>'.$this->password.'</password>
       <startdate>'.$data['startdate'].'</startdate>
       <starttime>'.$data['starttime'].'</starttime>
       <stopdate>'.$data['stopdate'].'</stopdate>
       <stoptime>'.$data['stoptime'].'</stoptime>
       <key>'.$data['key'].'</key>
       <ringtime>'.$data['ringtime'].'</ringtime>
       </header>
       <body>'
       .$baslangic.$gsm.$keys.'


       </body>
       </mainbody>';



       $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://api.netgsm.com.tr/voicesms/send");
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		$result = curl_exec($ch);
        $result=explode(" ",$result);

        $sonuc=array(
            30=>"Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir. Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.",
            40=>"Ses dosyasının olmadığını ifade eder.",
            45=>"Gönderilecek telefon numarasının bulunmadığını ifade eder",
            70=>"Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.",
            01=>"Mesaj gönderim baslangıç tarihinde hata var. Sistem tarihi ile değiştirilip işleme alındı.",
            02=>"Mesaj gönderim sonlandırılma tarihinde hata var.Sistem tarihi ile değiştirilip işleme alındı.Bitiş tarihi başlangıç tarihinden küçük girilmiş ise, sistem bitiş tarihine içinde bulunduğu tarihe 24 saat ekler.
            ",


       );
       
       if($result[0]==00 || $result[0]==01 ||$result[0]==02)
        {
            $response['cevap']='İşlem başarılı.';
            $response["code"]=$result[0];
            $response['bulkid']=$result[1];

        }
        elseif($result[0]==30|| $result[0]==40|| $result[0]==45|| $result[0]==70 )
        {

            $response["code"]=$result[0];
            $response['durum']=$sonuc[$result[0]];

        }

       else{
        $response["code"]=$result[0];
        $response['cevap']="Sistem Hatası";
       }
       return $response;

    }
    public function iptal($data)
    {
       
        if(!isset($data['bulkid']) || empty($data['bulkid']))
        {
            $res['cevap']='bulkid giriniz...';
            return $res;
        }

        $url= "https://api.netgsm.com.tr/voicesms/edit?usercode=".$this->username."&password=".$this->password."&bulkid=".$data['bulkid'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($http_code != 200){
            echo "$http_code $http_response\n";
            return false;
        }
        $balanceInfo = $http_response;
       

        $balanceInfo=(array)json_decode($balanceInfo);

       
        
        return $balanceInfo;
    }
    public function rapor($data):array
    {

        if(!isset($data["tus"]))
        {
            $data['tus']=null;
        }
        if(!isset($data["status"]))
        {
            $data['status']=null;
        }
        if(!isset($data["status"]))
        {
            $data['status']=null;
        }
        if(!isset($data["bulkid"]))
        {
            $data["bulkid"]=null;
        }
        if(!isset($data["type"]))
        {
            $data["type"]=0;
        }
        if(!isset($data["bastar"]))
        {
            $data['bastar']=null;
        }
        if(!isset($data["bittar"]))
        {
            $data['bittar']=null;
        }
        
        $url= "https://api.netgsm.com.tr/voicesms/report/?usercode=".$this->username."&password=".$this->password."&bulkid=".$data['bulkid']."&type=".$data['type']."&status=".$data['status']."&tus=".$data['tus']."&bastar=".$data['bastar']."&bittar=".$data['bittar'];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $http_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($http_code != 200){
            echo "$http_code $http_response\n";
            return false;
        }
        $balanceInfo = $http_response;
        // echo $balanceInfo;


        
       $dz=array_values(array_filter(explode("<br>",$balanceInfo)));
       
        
       $sonuc=array(
            30=>"Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir. Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.",
            60=>"Arama kriterlerinize göre listelenecek kayıt olmadığını ifade eder.",
            80=>"Sorgulama sınır aşımını ifade eder, dakikada 2 kez sorgulanabilir.",
            70=>"Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.",
            100=>"Sistem hatası"

       );
       
       
       if($dz[0]==30|| $dz[0]==60|| $dz[0]==70|| $dz[0]==80|| $dz[0]==100 )
       {
        
        $response["code"]=$dz[0];
        $response['durum']=$sonuc[$dz[0]];

       }

       else{

        foreach($dz as $key=>$value){
            
            $dz2=array_values(array_filter(explode(" ",$value)));
            
            $response[$key]['bulkid']=$dz2[0];
            $response[$key]['numara']=$dz2[1];
            $response[$key]['cagricevapdurumu']=$dz2[2];
            if(isset($dz2[3]))
            {
                $response[$key]['tuslananrakam']=$dz2[3];
            }
            else
            {
                $response[$key]['tuslananrakam']='-';
            }
            
        }
            

       }
       
       return $response;



    }
    public function dinamikseslimesaj($data):array
    {


        if(!isset($data['startdate']))
        {
            $data['startdate']=null;
        }
        if(!isset($data['starttime']))
        {
            $data['starttime']=null;
        }
        if(!isset($data['stopdate']))
        {
            $data['stopdate']=null;
        }
        if(!isset($data['stoptime']))
        {
            $data['stoptime']=null;
        }
        if(empty($data['no']) || !isset($data['no']))
        {
            $response['durum']='Numara giriniz.';
            return $response;
        }
        else{
            $gsm='';
            foreach($data['no'] as $d)
            {

               $gsm.="<no>".$d."</no>\r\n";
            }

        }
        if(!isset($data['series'])){
            $response['durum']='series parametrelerini doldurunuz.';
            return $response;
        }

        $series="";
        foreach($data["series"] as $key=>$d)
        {

           $series.="<series s='".$key."'>";
            if(isset($d["text"]))
            {
                $series.="<text>".$d["text"]."</text>\r\n";
            }
            elseif(isset($d["audioid"]))
            {
                $series.="<audioid>".$d["audioid"]."</audioid>\r\n";
            }
            else{
                return $res['durum']="Hata";
            }
            $series.="</series>\r\n";
        }
        if($data['key']==1){

            $keys="";
            foreach($data['keyinfo'] as $d )
            {
                $keys.="<keys>\r\n";
               if(!isset($d['tus'])){
                    $response['durum']='tus bilgisi bulunmamaktadır.Lütfen tekrar kontrol ediniz. ';
                    return $response;
               }
               if($d['tus']>10 || $d['tus']<0 ){
                $response['durum']='tus bilgisi 0-9 arası olmalıdır. ';
                return $response;
           }
               $keys.="<keydetail>\r\n <keyinfo>".$d['tus']."</keyinfo>\r\n";

               if(!isset($d['ses']) && !isset($d['text']) ){
                    $response['durum']='ses yada text parametrelerini doldurmadınız...';
                    return $response;
               }
               else{
                    if(isset($d['ses']))
                    {
                        $gonderilen="<audioid>".$d['ses']."</audioid>\r\n</keydetail>\r\n";
                    }
                    elseif(isset($d['text']))
                    {
                        $gonderilen="<text>".$d['text']."</text>\r\n</keydetail>\r\n";
                    }
                    else{
                        $response['durum']="Hatalı format";
                        return $response;
                    }
               }
               $keys.=$gonderilen;
               $keys.="</keys>";
            }

        }
        elseif($data['key']==0){
            $keys="";
        }
        else{
            $response['durum']='key değerini istenilen biçimde giriniz.key=1 Ses kaydının sonunda tuşa basılmasını istiyorum. key=0	Ses kaydının sonunda tuşa basılmasını istemiyorum. ';
            return $response;
        }
        $xmlData="<?xml version='1.0' encoding='UTF-8'?>
            <mainbody>
            <header>
                <usercode>".$this->username."</usercode>
                <password>".$this->password."</password>
                <startdate>".$data['startdate']."</startdate>
                <starttime>".$data['starttime']."</starttime>
                <stopdate>".$data['stopdate']."</stopdate>
                <stoptime>".$data['stoptime']."</stoptime>
                <ringtime>30</ringtime>
                <key>1</key>
            </header>
            <body>
            <voicemail>
                <scenario>
                    ".$series."
                    <number>
                        ".$gsm."
                    </number>
                    ".$keys."
                </scenario>
            </voicemail>
            </body>
            </mainbody>";

            $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,'https://api.netgsm.com.tr/voicesms/send');
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlData);
		$result = curl_exec($ch);
        $dz=explode(" ",$result);
        $sonuc=array(
            30=>"Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir. Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.",
            40=>"Ses dosyasının olmadığını ifade eder.",
            45=>"Gönderilecek telefon numarasının bulunmadığını ifade eder",
            70=>"Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder."

       );

       if($dz[0]==30|| $dz[0]==40|| $dz[0]==45|| $dz[0]==70 )
       {

        $response["code"]=$dz[0];
        $response['durum']=$sonuc[$dz[0]];

       }

       elseif($dz[0]==00 || $dz[0]==01 ||$dz[0]==02){

            $response['code']=$dz[0];
            $response['bulkid']=$dz[1];
            $response["durum"]="işlem başarılı";



       }
       else{
            $response['durum']='Hata var';
       }
       return $response;

    }
}
