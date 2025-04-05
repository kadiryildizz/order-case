##  Order Challenge
Bu proje, Laravel ile geliştirilmiş bir sepete ürün ekleme, silme, listeleme ve kampanya ekleme özelliklerini içeren bir RESTful API servisi sunmaktadır.

Projede dört ana endpoint üzerinden işlemler yapılır:
1. Sepete ürün ekleme (`quantity` miktarını negatif olarak verilirse ürün çıkarılır)
2. Sepetteki ürünlerin önizlenmesi
3. Kampanya uygulama
4. Sepet iptali



Kampanyalar laravel seed olarak tanımlanmıştır.
- Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
- 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
- 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.

### Kullanılan Teknolojiler
- **PHP 8.x**
- **Laravel 10.x**
- **MySQL 8.1**
- **Docker**
- **Postman** (API testleri için)

# Kurulum
1. Reposu klonlayın:
```bash
git clone https://github.com/kadiryildizz/order-case.git
```
2. Çalıştırın:

```bash
/bin/bash ./install.sh
```

# Postman
API'yi test etmek için Postman koleksiyonlarını kullanabilirsiniz. Aşağıdaki bağlantıya tıklayarak testleri içeri aktarabilirsiniz:

[Postman Collection](https://github.com/kadiryildizz/order-case/tree/main/postman)
```markdown
## API Endpoints

### 1. Sepete Ürün Ekle.

- Endpoint: `POST api/basket/add`
- Açıklama: Sepete ürün ekler.
- Request Body:
  {
    "product_id": 80,
    "quantity": 1,
    "customer_id": 2
   }

### 2. Sepeteki ürünleri listele.

- Endpoint: `POST api/basket/preview`
- Açıklama: Sepeteki ürünleri listeler.
- Request Body:
  {
    "customer_id": 1
  }
    
### 3. Sepete kampanya uygula.

- Endpoint: `POST api/basket/campaign/{orderId}`
- Açıklama: Sepeteki ürünlere kampanya uygular.
- Request Body:
  {
    "campaign_id":1
  }
### 4. Sepeti iptal et.

- Endpoint: `DELETE api/basket/{orderId}`
- Açıklama: Sepeti iptal eder.

 ```

### SQL (TEST DB VERİLERİ)

Postman collectiondaki test sonuçlarında kullandığım sql datasını, buradan yükleye bilirsiniz.

[sql dump](https://github.com/kadiryildizz/order-case/blob/master/sql%20dump.sql)

