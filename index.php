<?php
include 'conn.php';

$exp = $conn->query("SELECT * FROM experiences");
$skills = $conn->query("SELECT * FROM skills");
$certs = $conn->query("SELECT * FROM certificates");
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter&family=Poppins&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="src/css/style.css">

<title>Portfolio</title>
</head>

<body>

<!-- NAVBAR -->
<nav class="sticky-nav">
  <div class="container">
    <a class="navbar-brand">KYFE</a>
    <ul class="nav-links">
      <li><a href="#">Home</a></li>
      <li><a href="#about">About Me</a></li>
      <li><a href="#certificates">Certificates</a></li>
    </ul>
  </div>
</nav>

<!-- HERO  -->
<section class="hero">
  <div class="hero-bg"></div>

  <div class="hero-content">
    <div class="hero-center-wrap">

      <div class="hero-name-row">
        <p class="name-label">MUHAMMAD <span class="accent">RIZKY</span> FEBRIANTO</p>
      </div>

      <div class="hero-middle">
        <h1 class="portfolio-word">PORTFOLIO</h1>

        <div class="hero-photo">
          <img src="assets/hero-img.png">
        </div>
      </div>

    </div>

    <div class="hero-bottom-row">
      <span></span>
      <a href="#about" class="btn-cta">GET STARTED</a>
      <p class="sub-label">Information System Student</p>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="about-me" id="about">
<div class="container">
<div class="row g-5">

<div class="about-title">
    <span class="about-title-sub">GET TO KNOW ME</span>
    <h2 class="about-title-main">About <span class="accent">Me</span></h2>
</div>

<div class="col-lg-4">
  <div class="about-photo-wrap">
    <img src="assets/img-me.jpeg">
  </div>
</div>

<div class="col-lg-8">
<p class="about-bio">
Halo Semua, Aku <strong>Muhammad Rizky Febrianto</strong> atau biasa dipanggil
<strong>Rizky</strong>. Aku adalah seorang mahasiswa
<a href="https://si.ft.unmul.ac.id/" target="_blank" class="about-link">Program Studi Sistem Informasi</a>
di Universitas Mulawarman. Aku tertarik dengan dunia programming khususnya
web development sejak di SMA. Salam kenal semua!
</p>

<!-- EXPERIENCE -->
<div class="experience-wrap">
<p class="section-label">EXPERIENCE</p>

<div id="expList">
<?php while($e = $exp->fetch_assoc()) { ?>
<div class="exp-item" data-id="<?= $e['id']; ?>">

  <div class="exp-content" onclick="editExp(this)">
    <span class="exp-role"><?= $e['role']; ?></span>
    <span class="exp-year"><?= $e['year']; ?></span>
  </div>

  <button onclick="deleteItem(<?= $e['id']; ?>,'experiences',this)" class="btn btn-danger btn-sm">X</button>
</div>
<?php } ?>
</div>

<form id="formExp" class="mt-3 d-flex gap-2">
<input name="role" placeholder="Role" class="form-modern">
<input name="year" placeholder="Year" class="form-modern">
<button class="btn btn-success btn-sm">✔</button>
</form>

</div>

<!-- SKILLS -->
<div class="skills-wrap">
<p class="section-label">SKILLS</p>

<div id="skillList">
<?php while($s = $skills->fetch_assoc()) { ?>
<div class="skill-item" data-id="<?= $s['id']; ?>">

<div class="skill-header" onclick="editSkill(this)">
  <span class="skill-name"><?= $s['name']; ?></span>
  <span class="skill-pct"><?= $s['pct']; ?>%</span>
</div>

<div class="skill-bar-track">
<div class="skill-bar-fill" style="width: <?= $s['pct']; ?>%"></div>
</div>

<button onclick="deleteItem(<?= $s['id']; ?>,'skills',this)" class="btn btn-danger btn-sm mt-2">X</button>

</div>
<?php } ?>
</div>

<form id="formSkill" class="mt-3 d-flex gap-2">
<input name="name" placeholder="Skill" class="form-modern">
<input name="pct" placeholder="Percent" class="form-modern">
<button class="btn btn-success btn-sm">✔</button>
</form>

</div>

</div>
</div>
</div>
</section>

<!-- CERTIFICATE -->
<section class="certificates" id="certificates">
<div class="container">

<div class="cert-title">
<span class="cert-title-sub">WHAT I'VE EARNED</span>
<h2 class="cert-title-main">Certi<span class="accent">ficates</span></h2>
</div>

<div class="row g-4 mt-3" id="certList">

<?php while($c = $certs->fetch_assoc()) { ?>
<div class="col-lg-4 col-md-6">
  <div class="cert-card">

    <div class="cert-img-wrap">
      <img src="upload/<?= $c['file']; ?>">
    </div>

    <div class="cert-info">
      <p class="cert-tag"><?= $c['tag']; ?></p>
      <h3 class="cert-name"><?= $c['title']; ?></h3>
    </div>

    <div class="cert-actions">
      <a href="upload/<?= $c['file']; ?>" target="_blank" class="btn-view">View</a>
      <button onclick="deleteItem(<?= $c['id']; ?>,'certificates',this)" class="btn-delete">X</button>
    </div>

  </div>
</div>
<?php } ?>

</div>

<!-- FORM CERT -->
<form id="formCert" class="mt-4 d-flex gap-2" enctype="multipart/form-data">
<input type="hidden" name="type" value="add_cert">

<input name="title" placeholder="Title" class="form-modern">
<input name="tag" placeholder="Tag" class="form-modern">
<input type="file" name="file" accept="image/*">

<button class="btn btn-success">Upload</button>
</form>

</div>
</section>

<!-- JS AJAX -->
<script>

// ADD EXP
formExp.onsubmit = e=>{
e.preventDefault();
let d = new FormData(formExp);
d.append("type","add_exp");

fetch("ajax.php",{method:"POST",body:d})
.then(r=>r.json())
.then(res=>{
expList.innerHTML += `
<div class="exp-item" data-id="${res.id}">
<div class="exp-content" onclick="editExp(this)">
<span class="exp-role">${res.role}</span>
<span class="exp-year">${res.year}</span>
</div>
<button onclick="deleteItem(${res.id},'experiences',this)" class="btn btn-danger btn-sm">X</button>
</div>`;
formExp.reset();
});
}

function editExp(el){
  let parent = el.closest(".exp-item");
  let id = parent.dataset.id;

  let role = el.querySelector(".exp-role").innerText;
  let year = el.querySelector(".exp-year").innerText;

  el.innerHTML = `
    <div class="inline-edit">
      <input class="inline-input" id="r${id}" value="${role}">
      <input class="inline-input" id="y${id}" value="${year}">
      <button class="btn-save" onclick="saveExp(${id})">✔</button>
    </div>
  `;
}

function saveExp(id){
  let d = new FormData();
  d.append("type","edit_exp");
  d.append("id",id);
  d.append("role", document.getElementById("r"+id).value);
  d.append("year", document.getElementById("y"+id).value);

  fetch("ajax.php",{method:"POST",body:d})
  .then(()=> location.reload());
}

// ADD SKILL
formSkill.onsubmit = e=>{
e.preventDefault();
let d = new FormData(formSkill);
d.append("type","add_skill");

fetch("ajax.php",{method:"POST",body:d})
.then(r=>r.json())
.then(res=>{
skillList.innerHTML += `
<div class="skill-item" data-id="${res.id}">
<div class="skill-header" onclick="editSkill(this)">
<span>${res.name}</span>
<span>${res.pct}%</span>
</div>
<div class="skill-bar-track">
<div class="skill-bar-fill" style="width:${res.pct}%"></div>
</div>
<button onclick="deleteItem(${res.id},'skills',this)" class="btn btn-danger btn-sm">X</button>
</div>`;
formSkill.reset();
});
}

function editSkill(el){
  let parent = el.closest(".skill-item");
  let id = parent.dataset.id;

  let name = el.querySelector(".skill-name").innerText;
  let pct = el.querySelector(".skill-pct").innerText.replace("%","");

  el.innerHTML = `
    <div class="inline-edit">
      <input class="inline-input" id="n${id}" value="${name}">
      <input class="inline-input" id="p${id}" value="${pct}">
      <button class="btn-save" onclick="saveSkill(${id})">✔</button>
    </div>
  `;
}

function saveSkill(id){
  let d = new FormData();
  d.append("type","edit_skill");
  d.append("id",id);
  d.append("name", document.getElementById("n"+id).value);
  d.append("pct", document.getElementById("p"+id).value);

  fetch("ajax.php",{method:"POST",body:d})
  .then(()=> location.reload());
}

// ADD CERT
formCert.onsubmit = e=>{
e.preventDefault();
let d = new FormData(formCert);

fetch("ajax.php",{method:"POST",body:d})
.then(r=>r.json())
.then(res=>{
certList.innerHTML += `
<div class="col-lg-4 col-md-6">
<div class="cert-card">
<div class="cert-img-wrap">
<img src="upload/${res.file}">
</div>
<div class="cert-info">
<p class="cert-tag">${res.tag}</p>
<h3>${res.title}</h3>
</div>
<div class="cert-actions">
<a href="upload/${res.file}" target="_blank" class="btn-view">View</a>
<button onclick="deleteItem(${res.id},'certificates',this)" class="btn-delete">X</button>
</div>
</div>
</div>`;
formCert.reset();
});
}

// DELETE
function deleteItem(id,table,el){
let d=new FormData();
d.append("type","delete");
d.append("id",id);
d.append("table",table);

fetch("ajax.php",{method:"POST",body:d})
.then(()=> el.closest(".exp-item,.skill-item,.col-lg-4").remove());
}

</script>

</body>
</html>