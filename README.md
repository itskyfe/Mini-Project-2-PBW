Muhammad Rizky Febrianto | 2409116045
# Web Portofolio — Minpro 2

## Tujuan

Mengubah website portofolio statis menjadi **dinamis** dengan mengambil data dari database MySQL. Data yang sebelumnya hard-coded di HTML dipindahkan ke database dan ditampilkan secara real-time menggunakan PHP.

## Struktur File

```
portofolio/
├── index.php       # Halaman utama (render data dari DB)
├── conn.php        # Konfigurasi koneksi database
├── ajax.php        # Handler semua request AJAX (CRUD)
├── tambah.php      # Tambah data via form POST
├── edit.php        # Update data via form POST
├── delete.php      # Hapus data via GET
├── src/css/
│   └── style.css   # Styling halaman
└── upload/         # Folder penyimpanan file sertifikat
```

---

## Database

Database bernama `portofolio_db` dengan 3 tabel:

**`experiences`**
| Kolom | Tipe |
|---|---|
| id | INT AUTO_INCREMENT |
| role | VARCHAR |
| year | VARCHAR |

**`skills`**
| Kolom | Tipe |
|---|---|
| id | INT AUTO_INCREMENT |
| name | VARCHAR |
| pct | INT |

**`certificates`**
| Kolom | Tipe |
|---|---|
| id | INT AUTO_INCREMENT |
| title | VARCHAR |
| tag | VARCHAR |
| file | VARCHAR |

---

## Penjelasan Kode

### 1. `conn.php` — Koneksi Database

```php
$conn = new mysqli($host, $user, $pass, $dbname);
$conn->set_charset('utf8mb4');
```

File ini di-`include` di semua file PHP yang butuh akses ke database. Charset `utf8mb4` dipasang supaya karakter Indonesia tidak rusak.

---

### 2. `index.php` — Render Data dari Database

**Ambil data di awal halaman:**

```php
include 'conn.php';

$experiences  = $conn->query("SELECT * FROM experiences ORDER BY id DESC");
$skills       = $conn->query("SELECT * FROM skills ORDER BY id DESC");
$certificates = $conn->query("SELECT * FROM certificates ORDER BY id DESC");
```

**Tampilkan di HTML menggunakan loop PHP:**

```php
<?php while ($e = $experiences->fetch_assoc()) : ?>
  <div class="exp-item" data-id="<?= $e['id'] ?>">
    <span class="exp-role"><?= htmlspecialchars($e['role']) ?></span>
    <span class="exp-year"><?= htmlspecialchars($e['year']) ?></span>
  </div>
<?php endwhile; ?>
```

`htmlspecialchars()` dipakai untuk mencegah XSS — karakter seperti `<` atau `>` di input user tidak akan dieksekusi sebagai HTML.

---

### 3. `ajax.php` — Handler CRUD via AJAX

Semua operasi data (tambah, edit, hapus) dikirim ke file ini via `fetch()` JavaScript. Tipe operasi ditentukan dari field `type` di FormData.

**Tambah experience:**
```php
if ($type === 'add_exp') {
    $stmt = $conn->prepare("INSERT INTO experiences (role, year) VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST['role'], $_POST['year']);
    $stmt->execute();

    echo json_encode(['id' => $conn->insert_id, 'role' => $_POST['role'], 'year' => $_POST['year']]);
}
```

Server merespons dengan JSON berisi data yang baru disimpan, lalu JavaScript langsung menampilkannya ke DOM tanpa reload halaman.

**Upload sertifikat:**
```php
$newName = time() . '_' . $fileName;
move_uploaded_file($tmpPath, 'upload/' . $newName);
```

Nama file diberi prefix timestamp (`time()`) supaya tidak bentrok jika ada file dengan nama yang sama.

**Validasi sebelum simpan ke database:**
```php
$allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
if (!in_array($ext, $allowedTypes)) { /* tolak */ }
if ($fileSize > 2 * 1024 * 1024) { /* tolak */ }
```

**Whitelist tabel saat delete:**
```php
$allowedTables = ['experiences', 'skills', 'certificates'];
if (!in_array($table, $allowedTables)) { /* tolak */ }
```

Ini penting — kalau nama tabel langsung diambil dari input user tanpa dicek, bisa jadi celah SQL injection.

Semua query menggunakan **Prepared Statement** (`prepare` + `bind_param`) bukan string langsung, sehingga aman dari SQL injection.

---

### 4. AJAX di `index.php` — Tambah Data Tanpa Reload

```js
document.getElementById('formExp').onsubmit = function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  formData.append('type', 'add_exp');

  fetch('ajax.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
      // Langsung tambahkan elemen baru ke DOM
      document.getElementById('expList').innerHTML += `
        <div class="exp-item" data-id="${data.id}">...</div>`;
      this.reset();
    });
};
```

`e.preventDefault()` mencegah form submit secara normal (yang akan reload halaman). Data dikirim via `fetch()` dan hasilnya langsung dirender ke halaman.

---

### 5. Inline Edit — Edit Langsung di Tampilan

Klik item experience atau skill untuk mengubahnya jadi form edit:

```js
function editExp(el) {
  const id   = el.closest('.exp-item').dataset.id;
  const role = el.querySelector('.exp-role').innerText;
  const year = el.querySelector('.exp-year').innerText;

  el.innerHTML = `
    <div class="inline-edit">
      <input id="role-${id}" value="${role}">
      <input id="year-${id}" value="${year}">
      <button onclick="saveExp(${id})">✔</button>
    </div>`;
}
```

Setelah disimpan, halaman di-reload untuk memastikan data tampil sesuai database.

---

### 6. Hapus Data

```js
function deleteItem(id, table, el) {
  if (!confirm('Yakin mau dihapus?')) return;

  const formData = new FormData();
  formData.append('type', 'delete');
  formData.append('id', id);
  formData.append('table', table);

  fetch('ajax.php', { method: 'POST', body: formData })
    .then(() => el.closest('.exp-item, .skill-item, .col-lg-4').remove());
}
```

Elemen langsung dihapus dari DOM setelah server mengkonfirmasi penghapusan dari database.

---

## Tampilan

### About Me — Experience & Skills

<table>
  <tr>
    <th>Desktop</th>
    <th>Mobile</th>
  </tr>
  <tr>
    <td><img width="1892" height="872" alt="image" src="https://github.com/user-attachments/assets/dabbd05d-04bd-468b-b9d0-bc9aa6a5d8ce" /></td>
    <td><img width="349" height="677" alt="image" src="https://github.com/user-attachments/assets/1c16d1c6-d887-4c0c-9517-a797d478cb0c" /></td>
  </tr>
</table>

### Certificates

<table>
  <tr>
    <th>Desktop</th>
    <th>Mobile</th>
  </tr>
  <tr>
    <td><img width="1895" height="884" alt="image" src="https://github.com/user-attachments/assets/ee0be8ef-7f94-402a-b64f-b6a1dc1b96a9" /></td>
    <td><img width="350" height="676" alt="image" src="https://github.com/user-attachments/assets/5f98b3f8-8c8d-429e-8c3b-89debb2b508a" /></td>
  </tr>
</table>
