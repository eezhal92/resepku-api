# Resepku.com

> Dokumentasi web api versi 1

## Resources

### 1. Recipes

**GET /api/v1/recipes**

```
Mendapatkan semua data resep.
```

**Parameter/Query String**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
categories| string | id dari kategori yang ingin di filter. Contoh: 1,2
user_id| integer | filter hasil berdasarkan user

**Contoh**

`GET /api/recipes?categories=1,2`

<hr>

**GET api/v1/{username}/recipes/{recipe_id}**

```
Mendapatkan resep spesifik
```

<hr>

**POST api/v1/{username}/recipes**

> Butuh header Authorization Basic

```
Membuat resep baru.
```
**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
title | string | judul resep
body | string | penjelasan resep
categories | array | id dari kategori resep

<hr>

**PATCH api/v1/{username}/recipes/{recipe_id}**

> Butuh header Authorization Basic

```
Memperbarui resep.
```
**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
title | string | judul resep
body | string | penjelasan resep
categories | array | id dari kategori resep

<hr>

**DELETE api/v1/{username}/recipes/{recipe_id}**

> Butuh header Authorization Basic

```
Menghapus resep.
```

<hr>

**POST api/v1/accounts**

```
Membuat user baru.
```
**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
name | string | nama user
email | string | email user
password | string | password user