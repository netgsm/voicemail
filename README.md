


# Laravel Netgsm Voice Mail Entegrasyonu

Netgsm sesli mesaj paket aboneliği bulunan kullanıcılarımız için laravel paketidir.

# İletişim & Destek

 Netgsm API Servisi ile alakalı tüm sorularınızı ve önerilerinizi teknikdestek@netgsm.com.tr adresine iletebilirsiniz.


# Doküman 
https://www.netgsm.com.tr/dokuman/
 API Servisi için hazırlanmış kapsamlı dokümana ve farklı yazılım dillerinde örnek amaçlı hazırlanmış örnek kodlamalara 
 [https://www.netgsm.com.tr/dokuman](https://www.netgsm.com.tr/dokuman) adresinden ulaşabilirsiniz.


### Supported Laravel Versions

Laravel 6.x, Laravel 7.x, Laravel 8.x, Laravel 9.x, 

### Supported Symfony Versions

Symfony 4.x, Symfony 5.x, Symfony 6.x

### Supported Lumen Versions

Lumen 6.x, Lumen 7.x, Lumen 8.x, Lumen 9.x, 

### Supported Php Versions

PHP 7.2.5 ve üzeri

### Kurulum

<b>composer require netgsm/voicemail<b/>  

.env  dosyası içerisinde NETGSM ABONELİK bilgileriniz tanımlanması zorunludur.  

<b>NETGSM_USERCODE=""</b>  
<b>NETGSM_PASSWORD=""</b>  

### Ses Dosyası Yükleme

Sesli mesaj yapacağınız ses dosyasını bu fonksityon ile yükleyebilirsiniz.  

Bir dakika içerisinde bir dosya gönderebilirsiniz.

```php
       use Netgsm\Seslimesaj\Package;
       
       $islem=new Package;
       $data['fname']="C:/test.mp3";
       $sonuc=$islem->sesyukle($data);
       dd($sonuc);
       die;
```
#### Başarılı istek örnek
```php
Array
(
    [durum] => İşlem başarılı
    [sesid] => 5590xxxx
)
```
#### Başarısız istek örnek

```php
Array
(
    [durum] => Dosya yolu geçersiz.
)
```

### Ses Dosyası Listeleme

Yüklediğiniz ses dosyalarını sorguyarak bilgisine ulaşabilirsiniz.

```php
        $data=array('startdate'=>'260120231500','stopdate'=>'270120231500');
        $islem=new Package;
        $sonuc=$islem->seslistele($data);
        dd($sonuc);
        die;
```
####  Başarılı istek

```php
Array
(
    [0] => Array
        (
            [AudioID] => 552xxxxx
            [gonderentelno] => 312xxxxxxx
            [tarih] => 26.01.2023 15:03
            [mesajsuresi] => 174
            [yuklenmisdosya] => http://sesdosya.netgsm.com.tr/upload.php?tip=6&a=b454xxxxxxxx.........
        )

    [1] => Array
        (
            [AudioID] => 55258936
            [gonderentelno] => 312xxxxxxx
            [tarih] => 26.01.2023 15:02
            [mesajsuresi] => 174
            [yuklenmisdosya] => http://sesdosya.netgsm.com.tr/upload.php?tip=6&a=b454axxxxxxxxxxx........
        )

)

```

####  Başarısız istek
```php
Array
(
    [code] => 30
    [message] => Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.  
    Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız  
    30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzümüzden; sağ üst köşede  
    bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.
)
```

### Sesli Mesaj Başlatma

Sesli Mesaj API ile, sesli mesajlarınızı senaryonuza göre basit ya da dinamik şekilde başlatabilirsiniz. Sistemimize yüklü ses dosyanız ya da göndereceğiniz textin tarafımızda ses dosyasına dönüşümü ile Sesli Mesaj başlatabilirsiniz,
Ses dosyası yükleyebilir,  
Gelen sesli Mesajları listeleyebilir,  
Başlattığınız Sesli Mesajların durumlarını sorgulayarak Raporlama yapabilirsiniz.  
Sesli mesaj senaryoları bir adet tuşlama yapacak şekilde gerçekleştirebilir.  

### Basit Sesli Mesaj Başlatma
<table width="300">
  <th>Parametre</th>
  <th>Anlamı</th>
  <tr>
    <td><b> startdate</b> </td>
    <td> Gönderime başlayacağınız tarih. (ddMMyyyy)
 </td>
    
  </tr>
  <tr>
    <td><b> starttime</b> </td>
    <td>Mesajın gönderilmeye başlanacağı saat(ssdd)
  </td>
  </tr>
  <tr>
    <td><b> stopdate</b> </td>
    <td>İki tarih arası gönderimlerinizde bitiş tarihi.(ddMMyyyy) *Başlangıç ve bitiş tarihleri arasında en az 1 saat en fazla 21 saat olmalıdır.
  </td>
  </tr>
  <tr>
    <td><b> stoptime </b> </td>
    <td>Mesaj gönderimi bitiş saati(ssdd)
  </td>
  </tr>
  <tr>
    <td><b> key=1 </b> </td>
    <td> Ses kaydının sonunda tuşa basılmasını istiyorum.    </td>
  </tr>
  <tr>
    <td><b> key=0 </b> </td>
    <td> Ses kaydının sonunda tuşa basılmasını istemiyorum.
   </td>
  </tr>
  <tr>
    <td><b> audioid </b> </td>
    <td>Gönderilmek istenen sesid, sisteme daha önce yüklemiş olmanız gerekir.
   </td>
  </tr>
  <tr>
    <td><b> baslangictext </b> </td>
    <td>Sesli arama gerçekleştiğinde dinletilecek metin
   </td>
  </tr>
    <tr>
    <td><b> baslangicaudioid </b> </td>
    <td>Sesli arama gerçekleştiğinde dinletilecek sesin idsi.
   </td>
  </tr>
     <tr>
    <td><b> keys </b> </td>
    <td>Mesajın gönderildiği telefon numarası
   </td>
  </tr>
       <tr>
    <td><b> $data['keyinfo'][0][tus]  </b> </td>
    <td>tuşa basılığında anlamına gelir
   </td>
  </tr>
       <tr>
    <td><b> $data['keyinfo'][0][ses]  </b> </td>
    <td>tuşa basılığında bu sesi dinlet anlamına gelir.Daha önceden yüklenmiş ses id si girilmelidir.
   </td>
  </tr>
       </tr>
       <tr>
    <td><b> $data['keyinfo'][0][text]  </b> </td>
    <td>tuşa basılığında bu texti dinlet anlamına gelir
   </td>
  </tr>
       <tr>
    <td><b> filter </b> </td>
    <td>Ticari içerikli sesli mesaj gönderimlerinde bu parametreyi kullanabilirsiniz. Ticari içerikli bireysele gönderilecek numaralar için İYS kontrollü gönderimlerde ise "11" değerini, tacire gönderilecek İYS kontrollü gönderimlerde ise "12" değerini almalıdır. gönderilmediği taktirde İYS kontrolü uygulanmadan gönderilecektir.
   </td>
  </tr>
       <tr>
    <td><b> ringtime </b> </td>
    <td>Sesli mesaj için yapılan aramada telefoonun çalma süresi. (min 10 - max 30 sn)
   </td>
  </tr>
    <tr>
    <td><b> relationid </b> </td>
    <td>Tarafınızda belirleyeceğiniz bir random sayı ile sesli mesaj başlattığınızda, Raporlama servisini sorguladığınızda relationID bilgisi de dönecektir.
   </td>
  </tr>
  
</table>  

```php
        use Netgsm\Seslimesaj\Package;
        $data['startdate']="06022023";
        $data['starttime']="1606";
        $data['stopdate']="05022023";
        $data['stoptime']="1630";
        $data['key']=1;
        $data['relationid']='1234567';
        //$data['baslangicaudioid']=54325324;//baslangicaudioid varsa baslangictext parametresi gönderilmemelidir
        $data['baslangictext']='Merhaba';//baslangictext varsa baslangicaudioid parametresi gönderilmemelidir
        $data['keyinfo'][0]['tus']=1;//1 numaralı tuşa basıldığında anlamına gelir
        $data['keyinfo'][0]['ses']="55156219";//$data['keyinfo'][0]['tus']  parametresinde gönderilen tuşa basıldığında buradaki sesidli ses dinletilir.
        $data['keyinfo'][1]['tus']=2;
       // $data['keyinfo'][1]['ses']="55156219";//text varsa ses parametresini gönderilmemelidir.
        $data['keyinfo'][1]['text']="Merhaba ";//text varsa ses parametresi gönderilmemeilidir.
        $data['no']=['553xxxxxx'];
        $data['filter']=0;
        $data['ringtime']=20;
        $islem=new Package;
        $sonuc=$islem->basitSesliMsg($data);
        dd($sonuc);
        die;
```
#### Başarılı istek örnek
```php
Array
(
    [cevap] => İşlem başarılı.
    [code] => 00
    [bulkid] => 175343083
)
```
#### Başarısız istek örnek
```php
Array
(
    [code] => 70
    [durum] => Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.
)
```
### Dinamik Sesli Mesaj Başlatma

Senaryonuza göre sırası belirlenmiş şekilde gönderdiğiniz yüklü ses dosyaları ya da textlerinizle sesli mesajınız başlatılır. 1:n mantığı ile bir sesli mesajı birden fazla numaraya başlatabilirsiniz.

<table>
<thead>
<tr>
<th>Değişken</th>
<th>Anlamı</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>startdate</code></td>
<td>Gönderime başlayacağınız tarih. (ddMMyyyy)</td>
</tr>
<tr>
<td><code>starttime</code></td>
<td>Mesajın gönderilmeye başlanacağı saat(ssdd)</td>
</tr>
<tr>
<td><code>stopdate</code></td>
<td>İki tarih arası gönderimlerinizde bitiş tarihi.(ddMMyyyy) *Başlangıç ve bitiş tarihleri arasında en az 1 saat en fazla 21 saat olmalıdır.</td>
</tr>
<tr>
<td><code>stoptime</code></td>
<td>Mesaj gönderimi bitiş saati(ssdd).</td>
</tr>
<tr>

<tr>
<td><code>key=1</code></td>
<td>Ses kaydının sonunda tuşa basılmasını istiyorum.</td>
</tr>
<tr>
<td><code>key=0</code></td>
<td>Ses kaydının sonunda tuşa basılmasını istemiyorum.</td>
</tr>
<tr>
<td><code>audioid</code></td>
<td>Gönderilmek istenen sesid, sisteme daha önce yüklemiş olmanız gerekir.</td>
</tr>
<tr>
<td><code>text</code></td>
<td>Gönderilmek istenen metin</td>
</tr>
<tr>
<td><code>no</code></td>
<td>Mesajın gönderildiği telefon numarası</td>
</tr>
<tr>
<td><code>keyinfo</code></td>
<td>Tus bilgisi bu parametre ile gönderilir. Bu parametre sonrasında audioID ya da text bilgisi içeren parametre göndermeniz zorunludur.</td>
</tr>
<tr>
<td><code>filter</code></td>
<td>Ticari içerikli sesli mesaj gönderimlerinde bu parametreyi kullanabilirsiniz. Ticari içerikli bireysele gönderilecek numaralar için İYS kontrollü gönderimlerde ise "11" değerini, tacire gönderilecek  İYS kontrollü gönderimlerde ise "12" değerini almalıdır. gönderilmediği taktirde İYS kontrolü uygulanmadan gönderilecektir.</td>
</tr>
<tr>
<td><code>ringtime</code></td>
<td>Sesli mesaj için yapılan aramada telefoonun çalma süresi. (min 10 - max 30 sn)</td>
</tr>
</tbody>
</table>

```php
        use Netgsm\Seslimesaj\Package;
        $islem=new Package;
        $data['startdate']="02022023";
        $data['starttime']="0914";
        $data['stopdate']="02022023";
        $data['stoptime']="1015";
        $data['ringtime']=20;
        $data['key']=1;
        $data['no']=['553xxxxxxx'];
        $data['filter']=0;
        $data["series"][0]["text"]="Text 1";// ilk sırada dinletilmesi gereken text içerik.Burada text sesli mesaja çevrilir. istenilirse bu indislere ve diğer indislere geçerli audio id de girilir.Aşağıya indislere dikkate dilerek text yada audioid eklenebilir
        //$data["series"][1]["text"]="text2";
        //$data["series"][2]["text"]="Text 1";
        //$data["series"][3]["audioid"]="55183930";
        $data['keyinfo'][0]['tus']=1;//
        $data['keyinfo'][0]['text']=//1 tuşlandığında  okutulacak metin;
        $data['keyinfo'][1]['tus']=2;
       // $data['keyinfo'][1]['ses']="55156219";
        $data['keyinfo'][1]['text']="Merhaba ";//$data['keyinfo'][1] in tus keyinin valuesi 2 olduğu için 2 ye tıklandığında sesli mesaja çevrilecek metini ifade eder.burada audioid de kullanılabilir.
        $sonuc=$islem->dinamikseslimesaj($data);
       dd($sonuc);
       die;
```
#### Başarılı istek örnek sonuç
```php
Array
(
    [code] => 00
    [bulkid] => 175345216
    [durum] => işlem başarılı
)
```
#### Başarısız istek örnek sonuç
```php
Array
(
    [code] => 70
    [durum] => Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.
)
```
### Sesli Mesaj İptali

İleri tarihe zamanlanmış sesli mesajlarınızı iptal edebilirsiniz.  

bulkid	:İptal edilmek istenen, sesli mesaj gönderimi yapılırken dönen görevid(bulkid) nizdir. İstek yapılırken gönderilmesi zorunludur.


```php
        use Netgsm\Seslimesaj\Package;
        $data['bulkid']=17xxxxx;
        $islem=new Package;
        $sonuc=$islem->iptal($data);
        
        dd($sonuc);
        die;
```
#### Başarılı istek örnek sonuç
```php
Array
(
    [code] => 200
    [message] => bulkid iptal islemine alindi.
    [bulkid] => 175xxxx
)

```
#### Başarısız istek örnek sonuç
```php
Array
(
    [code] => 40
    [error] => ileri tarihli bulkid bulunamadi
)
```
### Sesli Mesaj ,Raporlama

HTTP Get yöntemini kullanarak; Sesli mesajlarınızı başlattıktan sonra tarafınıza dönen ID bilgisi bulkid ile göndereceğiniz ya da bastar- bittar parametreleri gibi sesli mesajlarınızı yaptığınız zaman aralığına göre sorgulayabilirsiniz.

<table>
<thead>
<tr>
<th>Değişken</th>
<th>Anlamı</th>
</tr>
</thead>
<tbody>

<tr>
<td><code>bulkid</code></td>
<td>Api ile başarılı sesli mesaj gönderimlerinizde dönen görevid(bulkid) nizdir.</td>
</tr>
<tr>
<td><code>type</code></td>
<td>Sorgulama tipini belirlemek için kullanılır.0: Tek bulkid'ye göre sorgulama yapar. 1: Birden çok bulkid'ye göre sorgulama yapar. (Not: Bu durumda bulkid parametresi 43234, 53453, 54332, ....gibi yazılır.) 2: Tarih aralığında sorgu yapabilmek için kullanılır. (Not: Parametrenin bu değerinde bulkid parametresine girilen değer dikkate alınmaz.)</td>
</tr>
<tr>
<td><code>bastar</code></td>
<td>İki tarih arası sorgulamanızda başlangıç tarihi (ddMMyyyyHHmm)</td>
</tr>
<tr>
<td><code>bittar</code></td>
<td>İki tarih arası sorgulamanızda bitiş tarihi(ddMMyyyyHHmm)</td>
</tr>
<tr>
<td><code>status</code></td>
<td>Mesajınızın durumunu sorgulamak için kullanılır. Status parametesi için açıklamaları aşağıdaki tabloda bulabilirsiniz.</td>
</tr>
<tr>
<td><code>tus</code></td>
<td>Arama yapıldığında komut edilen tuş numarası. Tuşlanan rakam eğer 10 şeklinde gönderilirse hiç bir tuşa basmayanlar listelenir.</td>
</tr>

</tbody>
</table>

```php
       
        use Netgsm\Seslimesaj\Package;
        $data['bulkid']="1712xxxx";
        // $data['bastar']='060220230000'; //bulkid var ise tarih girilmemelidir.tarih girilirse type 2 olmalıdır.
       //$data['bittar']='060220232000';//bulkid var ise tarih girilmemelidir.tarih girilirse type 2 olmalıdır.
        $data['type']='0';
        $data['status']=1;
        $data['tus']='1';
        $ses=new Package;
        $sonuc=$ses->rapor($data);
        dd($sonuc);
        die;
    
```

#### Başarılı istek örnek sonuç
```php
Array
(
    [0] => Array
        (
            [bulkid] => 1
            [numara] => 7
            [cagricevapdurumu] => 5
            [tuslananrakam] => 3
        )

    [1] => Array
        (
            [bulkid] => 1
            [numara] => 7
            [cagricevapdurumu] => 5
            [tuslananrakam] => 3
        )
 )
```
#### Başarısız istek örnek sonuç
```php
Array
(
    [code] => 70
    [durum] => Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.
)
```


    
  
  
</table>  

<table>
<thead>
<tr>
<th>Status Parametresi</th>
<th>Açıklamalar</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>0</code></td>
<td>Cevaplanmayı bekleyenler</td>
</tr>
<tr>
<td><code>1</code></td>
<td>Cevaplananlar / Açan</td>
</tr>
<tr>
<td><code>2</code></td>
<td>Cevaplanmayanlar</td>
</tr>
<tr>
<td><code>3</code></td>
<td>Ulaşılamayan</td>
</tr>
<tr>
<td><code>4</code></td>
<td>Ücretlendirelemeyen / Varlık Yetersiz</td>
</tr>
<tr>
<td><code>5</code></td>
<td>Iptal Edilen</td>
</tr>
<tr>
<td><code>6</code></td>
<td>Başarısız : başlatılamayan çağrılar, durdurulan, hata alanlar</td>
</tr>
<tr>
<td><code>7</code></td>
<td>Meşgule Alınan</td>
</tr>
<tr>
<td><code>8</code></td>
<td>Numara Geçersiz</td>
</tr>
<tr>
<td><code>9</code></td>
<td>Süresi Doldu</td>
</tr>
</tbody>
</table>

