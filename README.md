Yii2 İYS(İleti Yönetim Sistemi)
========
Yii2 İleti Yönetim Sistemi (IYS) entegrasyonu. Http Request için [Guzzle](https://docs.guzzlephp.org/en/stable/) kullanılmıştır.

Kurulum
------------

Bu uzantıyı kurmanın tercih edilen yolu [composer](http://getcomposer.org/download/) aracılığıyladır.

Komutu çalıştır

```
composer require --prefer-dist mhunesi/yii2-iys "*"
```

veya

```
"mhunesi/yii2-iys": "*"
```

`composer.json` dosyanızın gerekli bölümüne ekleyin.


Kullanım
-----
IYS API dokümantasyonu ve dönen istek cevaplarına [https://apidocs.iys.org.tr/](https://apidocs.iys.org.tr/) adresinden ulaşabilirsiniz.

Not: İYS veya İş ortağı ile çalışıyorsanız yine bu paketi kullanabilirsiniz. Elbette bazı iş ortakları farklı yöntemler izlemiş olabilir.

Uzantı yüklendikten sonra, kodunuzda şu şekilde config dosyanıza ekleyin;

```php
'components' => [
    ...
    
    'iys' => [
        'class' => \mhunesi\iys\Iys::className(),
        'url' => 'IYS_URL', // Varsayılan Değer https://api.iys.org.tr
        'username' => 'IYS_USERNAME',
        'password' => 'IYS_PASSWORD',
        'iys_code' => 'IYS_CODE',
        'brand_code' => 'IYS_BRAND_CODE', // Ana Marka. Birden fazla marka ile çalışıyorsanız boş geçebilirsiniz.
    ],
    
    ...
],

``` 

`brand_code` alanını birden fazla marka ile çalışıyorsanız boş geçebilirsiniz. Hangi markanız ile işlem yapacaksanız `setBrandCode` metodu ile set edebiliriniz.

```php
/** @var Iys $iys */
$iys = Yii::$app->iys;

$iys->setBrandCode('A Marka Kodu')->consents(); // A Markası İzin İşlemleri
$iys->setBrandCode('B Marka Kodu')->consents(); // B Markası İzin İşlemleri

$iys
```

### İzin Yönetimi
#### Tekil İzin Ekleme
Bu metot, alıcıdan alınmış izinlerin tekil olarak İYS'ye yüklenmesine imkan tanır.
```php
/** @var Iys $iys */
$iys = Yii::$app->iys;

$response = $iys->consents()->add([
                'consentDate'    => '2018-02-10 09:30:00',
                'source'         => 'HS_CAGRI_MERKEZI',
                'recipient'      => '+905813334455',
                'recipientType'  => 'BIREYSEL',
                'status'         => 'ONAY',
                'type'           => 'ARAMA',
                'retailerCode '  => 11223344,
                'retailerAccess' => [
                     22233344,
                     44222419,
                     13239987
                ]
            ]);
```

#### Tekil İzin Durumu Sorgulama
Bu metot, hizmet sağlayıcıların İYS'de kayıtlı olan izinlerini tekil olarak listelemelerini sağlar.
```php
$response = $iys->consents()->detail(([
                  'recipient'     => '+905813334455',
                  'recipientType' => 'BIREYSEL',
                  'type'          => 'MESAJ',
              ]);
```

#### Asenkron Çoklu İzin Ekleme
Bu metot, alıcıdan alınmış izinlerin yığın olarak İYS'ye yüklenmesine imkan tanır.

```php
$response = $iys->consents()->addBatch([
             [
                 'consentDate'    => '2018-02-10 09:30:00',
                 'source'         => 'HS_MESAJ',
                 'recipient'      => '+905813334455',
                 'recipientType'  => 'BIREYSEL',
                 'status'         => 'RET',
                 'type'           => 'ARAMA',
                 'retailerCode '  => 11223344,
                 'retailerAccess' => [
                     22233344,
                     44222419,
                     13239987
                 ]
             ],
             [
                 'consentDate'    => '2018-02-10 09:40:00',
                 'source'         => 'HS_WEB',
                 'recipient'      => 'ornek@adiniz.com',
                 'recipientType'  => 'BIREYSEL',
                 'status'         => 'ONAY',
                 'type'           => 'EPOSTA',
                 'retailerCode '  => 11223344,
                 'retailerAccess' => [
                     22233344,
                     44222419,
                     13239987
                 ]
             ],
         ]);
```

#### Asenkron Çoklu İzin İsteğinin Durumunu Sorgulama
Bu metot, asenkron çoklu izin ekleme işlemi sonunda dönen işlem sorgulama bilgisiyle izin kayıt isteklerinin sonuçlarını sorgular.

```php
$response = $iys->consents()->requestDetails('73b75030-3a92-4f1e-b247-b0509dbadbfc');
```

#### İzin Hareketi Sorgulama (Pull)

```php
$response = $iys->consents()->changes();
```

### Marka Yönetimi
#### Marka Listeleme
Bu metotla hizmet sağlayıcı hesabınızın altında bulunan markalarınızın listesi elde edilir.

```php
$response = $iys->brands()->all();
```

#### İş Ortaklarına Yetkilendirilmiş Marka Listeleme
İş ortakları, yetkilendirildikleri tüm markaları bu metot aracılığıyla listeler.

```php
$response = $iys->brands()->allIntegratorBrands();
```

#### İş Ortaklarına Yetkilendirilmiş Marka Sorgulama
İş ortakları, istek gövdesinde (path param) belirttikleri iysCode değerine ait yetkili oldukları markaları bu metot aracılığıyla listeler.

```php
$response = $iys->brands()->oneIntegratorBrand('1111111');
```

### İYS Yolu
Hizmet sağlayıcıların, markalarına izin ekleyebilmesi için İYS aracılığıyla alıcılardan onay isteyebilmesini sağlayan izin ekleme yöntemidir. İYS aracılığıyla izin onayı istenilmesi ve onay verilen iznin markaya eklenebilmesi için iki metot bulunmaktadır.
#### Onay Alma İşlemi Başlatma

```php
$response = $iys->consents()->overIys([
            'recipient' => 'abc@deneme.com',
            'recipientType' => 'BIREYSEL',
            'type' => [
                "EPOSTA"
            ],
            'source' => 'IYS_EPOSTA'
        ]);
```

#### Onay Alma İşlemi Tamamlama

```php
$response = $iys->consents()->verificationCode([
            'requestId' => '111ad006-6210-6axx-oa7c-y672f66e2536',
            'verificationCode' => '5AW5XX'
        ]);
```

### İnfo Servisleri

#### İl Listeleme
Tüm illeri isim ve kod bilgileriyle birlikte listeler.

```php
$response = $iys->info()->cities();
```

#### İl Sorgulama
Sorgulanan ilin bilgisini getirir.


```php
$response = $iys->info()->cityDetails(34);
```

#### İlçe Listeleme
Tüm ilçeleri bağlı bulundukları illerin kodlarıyla birlikte listeler.


```php
$response = $iys->info()->towns();
```

#### İlçe Sorgulama
Sorgulanan ilin bilgisini getirir.


```php
$response = $iys->info()->townDetails(514);
```


### Bayi İzin Yönetimi

Yakında...

#### Mutabakat Yönetimi

Yakında...

