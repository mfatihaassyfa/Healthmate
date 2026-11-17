<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include("../config/db.php");

$user_id = $_SESSION['user_id'];
$selectedDate = $_GET['tanggal'] ?? date("Y-m-d");

// === DATA USER ===
$sqlUser = "SELECT * FROM users WHERE id_user='$user_id'";
$user = $conn->query($sqlUser)->fetch_assoc();

$tinggi = $user['tinggi_cm'];
$usia = $user['usia'];
$gender = $user['gender'];
$aktivitas = $user['aktivitas'];

// === DATA BERAT ===
$sqlWeight = "SELECT berat_kg FROM activities WHERE id_user='$user_id' AND tanggal='$selectedDate' LIMIT 1";
$weightToday = $conn->query($sqlWeight)->fetch_assoc();
$berat_hari_ini = $weightToday['berat_kg'] ?? 0;

// === BERAT TERAKHIR ===
$sqlWeightLast = "SELECT berat_kg FROM activities WHERE id_user='$user_id' AND tanggal <= '$selectedDate' ORDER BY tanggal DESC LIMIT 1";
$latestWeight = $conn->query($sqlWeightLast)->fetch_assoc();
$berat = $latestWeight['berat_kg'] ?? 0;

// === HITUNG BMI & TDEE ===
$bmi = ($berat_hari_ini && $tinggi) ? $berat_hari_ini / pow(($tinggi/100), 2) : 0;
$bmr = ($gender == "Pria") ? (10*$berat)+(6.25*$tinggi)-(5*$usia)+5 : (10*$berat)+(6.25*$tinggi)-(5*$usia)-161;
$factor = [
  "Sedentary" => 1.2,
  "Ringan" => 1.375,
  "Sedang" => 1.55,
  "Berat" => 1.725,
  "Sangat Berat" => 1.9
];
$tdee = $bmr ? $bmr * $factor[$aktivitas] : 0;

// === STATUS BMI ===
$bmiStatus = "-";
$bmiColor = "secondary";
if ($bmi) {
  if ($bmi < 18.5) { $bmiStatus = "Kekurangan Berat"; $bmiColor = "warning"; }
  elseif ($bmi < 25) { $bmiStatus = "Normal"; $bmiColor = "success"; }
  elseif ($bmi < 30) { $bmiStatus = "Kelebihan Berat"; $bmiColor = "info"; }
  else { $bmiStatus = "Obesitas"; $bmiColor = "danger"; }
}

// === DATA AKTIVITAS HARI INI ===
$sqlToday = "SELECT * FROM activities WHERE id_user='$user_id' AND tanggal='$selectedDate' LIMIT 1";
$dataToday = $conn->query($sqlToday)->fetch_assoc();

$kalori_harian = $dataToday['kalori'] ?? 0;
$air_harian = $dataToday['air_liter'] ?? 0;
$tidur_harian = $dataToday['jam_tidur'] ?? 0;

// === STATUS KALORI ===
$kaloriStatus = "-";
$kaloriColor = "secondary";
if ($tdee && $kalori_harian > 0) {
  $rasio = $kalori_harian / $tdee;
  if ($rasio < 0.9) { $kaloriStatus = "Kekurangan Kalori"; $kaloriColor = "warning"; }
  elseif ($rasio <= 1.1) { $kaloriStatus = "Normal"; $kaloriColor = "success"; }
  else { $kaloriStatus = "Kelebihan Kalori"; $kaloriColor = "danger"; }
}

// === DATA MINGGUAN ===
$sqlWeek = "SELECT * FROM activities WHERE id_user='$user_id' ORDER BY tanggal DESC LIMIT 7";
$dataWeek = $conn->query($sqlWeek)->fetch_all(MYSQLI_ASSOC);

$tglWeek = array_reverse(array_column($dataWeek, 'tanggal'));
$tidurWeek = array_reverse(array_column($dataWeek, 'jam_tidur'));
$olahragaWeek = array_reverse(array_column($dataWeek, 'durasi_olahraga'));
$airWeek = array_reverse(array_column($dataWeek, 'air_liter'));
$kaloriWeek = array_reverse(array_column($dataWeek, 'kalori'));
$beratWeek = array_reverse(array_column($dataWeek, 'berat_kg'));

// === REMINDER ===
$reminderHarian = [];
if ($dataToday) {
  if ($tidur_harian < 6) $reminderHarian[] = "⚠️ Tidur kamu hari ini kurang dari 6 jam.";
  if ($air_harian < 2) $reminderHarian[] = "💧 Air minum kamu hari ini kurang dari 2 liter.";
  if ($tdee && $kalori_harian > $tdee) $reminderHarian[] = "🍔 Asupan kalori kamu melebihi kebutuhan.";
}

$avgTidur = $tidurWeek ? array_sum($tidurWeek)/count($tidurWeek) : 0;
$avgAir   = $airWeek ? array_sum($airWeek)/count($airWeek) : 0;
$avgKalori= $kaloriWeek ? array_sum($kaloriWeek)/count($kaloriWeek) : 0;
$avgBerat = $beratWeek ? array_sum($beratWeek)/count($beratWeek) : 0;

$reminderMingguan = [];
if ($avgTidur < 6) $reminderMingguan[] = "😴 Rata-rata tidur kamu minggu ini kurang dari 6 jam.";
if ($avgAir < 2) $reminderMingguan[] = "💧 Rata-rata minum air kamu minggu ini kurang dari 2 liter.";
if ($tdee && $avgKalori > $tdee) $reminderMingguan[] = "🍟 Rata-rata kalori minggu ini melebihi kebutuhan tubuh.";

// === HEALTH SUMMARY ===
$healthSummary = "✅ Sehat Hari Ini!";
$healthColor = "success";
if (!$dataToday) {
  $healthSummary = "ℹ️ Belum ada data aktivitas hari ini.";
  $healthColor = "secondary";
} else {
  $issues = [];
  if ($tidur_harian < 6) $issues[] = "Kurang Tidur";
  if ($air_harian < 2) $issues[] = "Kurang Air";
  if ($kalori_harian < (0.9 * $tdee)) $issues[] = "Kekurangan Kalori";
  if ($kalori_harian > (1.1 * $tdee)) $issues[] = "Kelebihan Kalori";
  if ($bmi < 18.5 || $bmi > 25) $issues[] = "BMI Tidak Normal";

  if ($issues) {
    $healthSummary = "⚠️ " . implode(", ", $issues);
    $healthColor = "warning";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard - Health Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f4f7fb; font-family:'Poppins',sans-serif; display:flex; }
.sidebar { width:250px; background:linear-gradient(180deg,#0b2447,#19376d); color:white; height:100vh; position:fixed; }
.sidebar a { display:block; color:white; padding:10px 20px; text-decoration:none; border-radius:6px; margin-bottom:8px; }
.sidebar a.active,.sidebar a:hover{background:#4fc3f7;color:#000;}
.main { margin-left:260px; padding:30px; width:100%; }
.card-stat { border:none; border-radius:15px; box-shadow:0 3px 8px rgba(0,0,0,0.05);}
.alert-custom { border-left:6px solid #ff4d4d; background:#fff3f3; }
canvas { max-height:400px; }
</style>
</head>

<body>
<div class="sidebar">
  <h4 class="text-center mt-3 mb-4"><i class="bi bi-heart-pulse"></i> HealthMate</h4>
  <a href="edit_profile.php"><i class="bi bi-person-lines-fill"></i> Edit Profil</a>
  <a href="statistik.php"><i class="bi bi-bar-chart-line"></i> Statistik</a>
  <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="food_input.php"><i class="bi bi-egg-fried"></i> Input Makanan</a>
  <a href="input.php"><i class="bi bi-activity"></i> Input Aktivitas</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Halo, <?= htmlspecialchars($user['nama']); ?> 👋</h3>
    <form method="GET" class="d-flex">
      <input type="date" name="tanggal" value="<?= $selectedDate ?>" class="form-control me-2" required>
      <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Tampilkan</button>
    </form>
  </div>

  <!-- Reminder -->
  <?php if ($reminderHarian): ?>
  <div class="alert alert-danger alert-custom">
    <h5>⚡ Reminder Harian</h5>
    <ul><?php foreach($reminderHarian as $r) echo "<li>$r</li>"; ?></ul>
  </div>
  <?php endif; ?>
  <?php if ($reminderMingguan): ?>
  <div class="alert alert-warning">
    <h5>📅 Reminder Mingguan</h5>
    <ul><?php foreach($reminderMingguan as $r) echo "<li>$r</li>"; ?></ul>
  </div>
  <?php endif; ?>

  <!-- Statistik Card -->
  <div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card card-stat p-3 text-center"><h6>BMI</h6><h3><?= $bmi ? round($bmi,1) : '-' ?></h3><span class="badge bg-<?= $bmiColor ?>"><?= $bmiStatus ?></span></div></div>
    <div class="col-md-3"><div class="card card-stat p-3 text-center"><h6>Kalori</h6><h3><?= $kalori_harian ?> kcal</h3><span class="badge bg-<?= $kaloriColor ?>"><?= $kaloriStatus ?></span></div></div>
    <div class="col-md-3"><div class="card card-stat p-3 text-center"><h6>TDEE</h6><h3><?= round($tdee) ?> kcal</h3></div></div>
    <div class="col-md-3"><div class="card card-stat p-3 text-center"><h6>Tanggal</h6><h3><?= $selectedDate ?></h3></div></div>
  </div>

  <!-- Health Summary -->
  <div class="alert alert-<?= $healthColor ?> text-center fw-bold fs-5 py-3 mb-4">
    <?= $healthSummary ?>
  </div>

  <!-- Grafik Mingguan -->
  <div class="bg-white p-4 rounded shadow mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5><i class="bi bi-graph-up"></i> Grafik Aktivitas Mingguan</h5>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="toggleChart">
        <label class="form-check-label" for="toggleChart">Pisahkan Grafik</label>
      </div>
    </div>
    <div id="chartCombined"><canvas id="chartAll"></canvas></div>
    <div id="chartSeparated" style="display:none;">
      <div class="mb-3"><canvas id="chartTidur"></canvas></div>
      <div class="mb-3"><canvas id="chartOlahraga"></canvas></div>
      <div class="mb-3"><canvas id="chartAir"></canvas></div>
      <div class="mb-3"><canvas id="chartKalori"></canvas></div>
      <div class="mb-3"><canvas id="chartBerat"></canvas></div>
    </div>
  </div>
</div>

<script>
const labels = <?= json_encode($tglWeek) ?>;
const dataTidur = <?= json_encode($tidurWeek) ?>;
const dataOlahraga = <?= json_encode($olahragaWeek) ?>;
const dataAir = <?= json_encode($airWeek) ?>;
const dataKalori = <?= json_encode($kaloriWeek) ?>;
const dataBerat = <?= json_encode($beratWeek) ?>;

const baseTidur = 7, baseAir = 2, baseOlahraga = 30, baseKalori = <?= round($tdee) ?>, baseBerat = <?= round($avgBerat,1) ?>;

const ctxAll = document.getElementById('chartAll');
let chartAll = new Chart(ctxAll, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [
      {label:'Tidur (jam)', data:dataTidur, borderColor:'blue', tension:0.3, fill:false},
      {label:'Olahraga (menit)', data:dataOlahraga, borderColor:'green', tension:0.3, fill:false},
      {label:'Air (liter)', data:dataAir, borderColor:'aqua', tension:0.3, fill:false},
      {label:'Kalori (kcal)', data:dataKalori, borderColor:'orange', tension:0.3, fill:false},
      {label:'Berat (kg)', data:dataBerat, borderColor:'red', tension:0.3, fill:false}
    ]
  },
  options: {plugins:{legend:{position:'bottom'}}, scales:{y:{beginAtZero:true}}}
});

function makeChart(id,label,data,color,baseValue){
  return new Chart(document.getElementById(id),{
    type:'line',
    data:{labels:labels,datasets:[
      {label:label,data:data,borderColor:color,tension:0.3,fill:false},
      {label:'Batas Normal',data:Array(data.length).fill(baseValue),borderColor:'rgba(128,128,128,0.6)',borderDash:[6,6],borderWidth:1.3,pointRadius:0}
    ]},
    options:{plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true}}}
  });
}

const chartTidur = makeChart('chartTidur','Tidur (jam)',dataTidur,'blue',baseTidur);
const chartOlahraga = makeChart('chartOlahraga','Olahraga (menit)',dataOlahraga,'green',baseOlahraga);
const chartAir = makeChart('chartAir','Air (liter)',dataAir,'aqua',baseAir);
const chartKalori = makeChart('chartKalori','Kalori (kcal)',dataKalori,'orange',baseKalori);
const chartBerat = makeChart('chartBerat','Berat (kg)',dataBerat,'red',baseBerat);

document.getElementById('toggleChart').addEventListener('change',()=> {
  document.getElementById('chartCombined').style.display = event.target.checked ? 'none' : 'block';
  document.getElementById('chartSeparated').style.display = event.target.checked ? 'block' : 'none';
});

</script>
</body>
</html>
