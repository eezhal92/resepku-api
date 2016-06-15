# Resepku.com

> Dokumentasi web api versi 1



## Resources

### Mendapatkan JWT Token

**POST /api/v1/auth**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
email| string | email user
password| string | password user

<hr>

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

**GET api/v1/recipes/{recipe_id}**

```
Mendapatkan resep spesifik
```

<hr>

**POST api/v1/recipes**

> Butuh header Authorization Bearer {jwt_token}

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

**PATCH api/v1/recipes/{recipe_id}**

> Butuh header Authorization Bearer {jwt_token}

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

**DELETE api/v1/recipes/{recipe_id}**

> Butuh header Authorization Bearer {jwt_token}

```
Menghapus resep.
```

<hr>

**POST api/v1/recipes/{recipe_id}/image**

> Butuh header Authorization Bearer {jwt_token}

```
Set gambar resep.
```

**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
image | file |

<hr>

### 2. Users

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

<hr>

**GET api/v1/users**

```
Mendapatkan data user.
```
**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
page | integer |

<hr>

### 3. Comments

**GET api/v1/recipes/{recipe_id}/comments**

```
Mengambil komentar dari resep spesifik.
```

**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
page | integer |

<hr>

**POST api/v1/recipes/{recipe_id}/comments**

> Butuh header Authorization Bearer {jwt_token}

```
Menyimpan komentar untuk resep spesifik.
```

**Parameter**

Param | Nilai | Deskripsi
------------ | ------------- | -------------
body | string | isi komentar
title | string | judul komentar. optional

<hr>

**DELETE api/v1/recipes/{recipe_id}/comments/{comment_id}**

> Butuh header Authorization Bearer {jwt_token}

```
Menghapus komentar spesifik dari sebuah resep.
```
