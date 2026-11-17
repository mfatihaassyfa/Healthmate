<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
include("../config/db.php");

$user_id = $_SESSION['user_id'];
$range = $_GET['range'] ?? 'all';

// Ambil data aktivitas
if ($range === '7') {
  $sql = "SELECT * FROM activities WHERE id_user='$user_id' ORDER BY tanggal DESC LIMIT 7";
} elseif ($range === '30') {
  $sql = "SELECT * FROM activities WHERE id_user='$user_id' ORDER BY tanggal DESC LIMIT 30";
} else {
  $sql = "SELECT * FROM activities WHERE id_user='$user_id' ORDER BY tanggal ASC";
}
$result = $conn->query($sql);
$data = array_reverse($result->fetch_all(MYSQLI_ASSOC));

if (!$data) {
  $msg = "<div class='alert alert-info mt-4'>Belum ada data aktivitas untuk ditampilkan.</div>";
} else {
  // Ambil kolom data
  $tanggal = array_column($data, 'tanggal');
  $tidur = array_column($data, 'jam_tidur');
  $air = array_column($data, 'air_liter');
  $kalori = array_column($data, 'kalori');
  $olahraga = array_column($data, 'durasi_olahraga');
  $berat = array_column($data, 'berat_kg');

  // Rata-rata
  $avgTidur = round(array_sum($tidur)/count($tidur), 2);
  $avgAir = round(array_sum($air)/count($air), 2);
  $avgKalori = round(array_sum($kalori)/count($kalori), 0);
  $avgOlahraga = round(array_sum($olahraga)/count($olahraga), 1);
  $avgBerat = round(array_sum($berat)/count($berat), 1);

  // Standar WHO
  $stdTidur = 8; $stdAir = 2;
  $pTidur = min(round(($avgTidur/$stdTidur)*100), 100);
  $pAir = min(round(($avgAir/$stdAir)*100), 100);

  // Standar Personal
  $targetKalori = $avgKalori ?: 2000;
  $targetAktivitas = 30;
  $targetBerat = $avgBerat;
  $pKalori = min(round(($avgKalori/$targetKalori)*100), 100);
  $pAktivitas = min(round(($avgOlahraga/$targetAktivitas)*100), 100);
  $pBerat = 100;

  // Tren
  $weekData = array_slice($data, -14);
  $week1 = array_slice($weekData, 0, 7);
  $week2 = array_slice($weekData, -7);
  function avg($arr,$key){return empty($arr)?0:array_sum(array_column($arr,$key))/count($arr);}
  $trendTidur = round(avg($week2,'jam_tidur') - avg($week1,'jam_tidur'),1);
  $trendAir = round(avg($week2,'air_liter') - avg($week1,'air_liter'),1);
  $trendKalori = round(avg($week2,'kalori') - avg($week1,'kalori'),0);
  $trendAktivitas = round(avg($week2,'durasi_olahraga') - avg($week1,'durasi_olahraga'),1);

  // Skor total & AI Insight
  $scoreThis = ($pTidur + $pAir + $pKalori + $pAktivitas + $pBerat) / 5;
  $scoreLast = $scoreThis - (($trendTidur*10) + ($trendAir*5) + ($trendAktivitas*3)) / 5;
  $trendScore = round($scoreThis - $scoreLast, 1);

  if ($trendScore > 5) {
    $aiStatus = "🟢 Kesehatan kamu meningkat " . abs($trendScore) . "% dibanding minggu lalu.";
    $aiDesc = "Tidur dan aktivitas membaik, tubuh tampak lebih bugar!";
  } elseif ($trendScore < -5) {
    $aiStatus = "🔴 Kesehatan kamu menurun " . abs($trendScore) . "% dibanding minggu lalu.";
    $aiDesc = "Perhatikan waktu tidur, air, dan pola makan kamu ya.";
  } else {
    $aiStatus = "🟡 Kesehatan kamu relatif stabil minggu ini.";
    $aiDesc = "Pertahankan kebiasaan baikmu, tetap jaga keseimbangan.";
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Statistik - Health Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<style>
body{background:#f4f7fb;font-family:'Poppins',sans-serif;display:flex;}
.sidebar{width:250px;background:linear-gradient(180deg,#0b2447,#19376d);color:white;height:100vh;position:fixed;}
.sidebar a{display:block;color:white;padding:10px 20px;text-decoration:none;border-radius:6px;margin-bottom:8px;}
.sidebar a.active,.sidebar a:hover{background:#4fc3f7;color:#000;}
.main{margin-left:260px;padding:30px;width:100%;}
.progress{height:18px;border-radius:8px;}
canvas{max-height:350px;}
.card-stat{border:none;border-radius:15px;box-shadow:0 3px 8px rgba(0,0,0,0.05);}
.ai-card{border-left:6px solid #0d6efd;background:white;}
</style>
</head>
<body>
<div class="sidebar">
  <h4 class="text-center mt-3 mb-4"><i class="bi bi-heart-pulse"></i> HealthMate</h4>
  <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="food_input.php"><i class="bi bi-egg-fried"></i> Input Makanan</a>
  <a href="input.php"><i class="bi bi-activity"></i> Input Aktivitas</a>
  <a href="#" class="active"><i class="bi bi-bar-chart-line"></i> Statistik</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="bi bi-bar-chart-line"></i> Statistik Kesehatan</h3>
    <form method="GET" class="d-flex">
      <select name="range" class="form-select me-2" onchange="this.form.submit()">
        <option value="7" <?=($range=='7')?'selected':''?>>7 Hari</option>
        <option value="30" <?=($range=='30')?'selected':''?>>30 Hari</option>
        <option value="all" <?=($range=='all')?'selected':''?>>Semua</option>
      </select>
    </form>
  </div>

  <?php if(isset($msg)){echo $msg;}else{?>

  <div class="card ai-card p-4 mb-4 shadow-sm">
    <h5>🧠 Analisis AI Mingguan</h5>
    <p class="mb-1"><?=$aiStatus?></p>
    <small class="text-muted"><?=$aiDesc?></small>
  </div>

  <div class="d-flex gap-4">
    <div class="card p-4 shadow-sm flex-grow-1">
      <h5 class="mb-3">📊 Rata-rata & Pencapaian</h5>
      <table class="table text-center align-middle" id="tableStats">
        <thead class="table-dark">
          <tr><th>Indikator</th><th>Rata-rata</th><th>Standar WHO</th><th>Pencapaian</th></tr>
        </thead>
        <tbody>
          <tr><td>Tidur</td><td><?=$avgTidur?> jam</td><td>7–9 jam</td><td><?=$pTidur?>%</td></tr>
          <tr><td>Air</td><td><?=$avgAir?> L</td><td>≥ 2 L</td><td><?=$pAir?>%</td></tr>
          <tr><td>Kalori</td><td><?=$avgKalori?> kcal</td><td>± <?=$targetKalori?> kcal</td><td><?=$pKalori?>%</td></tr>
          <tr><td>Aktivitas</td><td><?=$avgOlahraga?> menit</td><td>≥ 30 menit</td><td><?=$pAktivitas?>%</td></tr>
          <tr><td>Berat</td><td><?=$avgBerat?> kg</td><td>Stabil</td><td><?=$pBerat?>%</td></tr>
        </tbody>
      </table>
      <div class="text-end mt-3">
        <button onclick="downloadPDF()" class="btn btn-danger btn-sm">
          <i class="bi bi-file-earmark-pdf"></i> Download Laporan PDF
        </button>
      </div>
    </div>

    <div class="card p-3 shadow-sm text-center" style="width:300px;">
      <h6 class="text-primary"><i class="bi bi-globe"></i> WHO Progress</h6>
      <canvas id="chartWHO"></canvas>
    </div>

    <div class="card p-3 shadow-sm text-center" style="width:300px;">
      <h6 class="text-warning"><i class="bi bi-person-check"></i> Personal Progress</h6>
      <canvas id="chartPersonal"></canvas>
    </div>
  </div>

  <div class="card p-4 mt-4 shadow-sm">
    <h5><i class="bi bi-graph-up"></i> Grafik Tren Aktivitas</h5>
    <canvas id="chartTren"></canvas>
  </div>

  <?php } ?>
</div>

<script>
const ctxT = document.getElementById('chartTren');
new Chart(ctxT,{
  type:'line',
  data:{labels:<?=json_encode($tanggal)?>,datasets:[
    {label:'Tidur (jam)',data:<?=json_encode($tidur)?>,borderColor:'blue',tension:0.3,fill:false},
    {label:'Air (L)',data:<?=json_encode($air)?>,borderColor:'aqua',tension:0.3,fill:false},
    {label:'Kalori (kcal)',data:<?=json_encode($kalori)?>,borderColor:'orange',tension:0.3,fill:false},
    {label:'Aktivitas (menit)',data:<?=json_encode($olahraga)?>,borderColor:'green',tension:0.3,fill:false},
    {label:'Berat (kg)',data:<?=json_encode($berat)?>,borderColor:'red',tension:0.3,fill:false}
  ]},options:{plugins:{legend:{position:'bottom'}},scales:{y:{beginAtZero:true}}}}
);

// Donut WHO
const chartWHO = new Chart(document.getElementById('chartWHO'),{
  type:'doughnut',
  data:{labels:['Tidur','Air'],datasets:[{data:[<?=$pTidur?>,<?=$pAir?>],
  backgroundColor:['#0055ff','#00e5ff'],borderWidth:1}]},
  options:{plugins:{legend:{position:'bottom'}},cutout:'70%'}
});

// Donut Personal
const chartPersonal = new Chart(document.getElementById('chartPersonal'),{
  type:'doughnut',
  data:{labels:['Tidur','Air','Kalori','Aktivitas','Berat'],datasets:[{data:[<?=$pTidur?>,<?=$pAir?>,<?=$pKalori?>,<?=$pAktivitas?>,<?=$pBerat?>],
  backgroundColor:['#007bff','#00c9a7','#ff9f43','#28a745','#ff4757'],borderWidth:1}]},
  options:{plugins:{legend:{position:'bottom'}},cutout:'70%'}
});

// ✅ Export PDF termasuk grafik
async function downloadPDF(){
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF('p','pt','a4');

  doc.setFontSize(16);
  doc.text("Health Tracker - Laporan Statistik", 40, 50);
  doc.setFontSize(10);
  doc.text("Dihasilkan pada: <?=date('Y-m-d')?>", 40, 65);

  // Tambahkan tabel
  doc.autoTable({ html: '#tableStats', startY: 90, styles:{fontSize:9}, headStyles:{fillColor:[0,86,179]} });

  // Tambahkan grafik (convert canvas ke image)
  const chartTrenImg = document.getElementById('chartTren').toDataURL('image/png', 1.0);
  doc.addPage();
  doc.setFontSize(14);
  doc.text("Grafik Tren Aktivitas", 40, 40);
  doc.addImage(chartTrenImg, 'PNG', 40, 60, 500, 250);

  doc.save("Health_Statistics_Report.pdf");
}
</script>
</body>
</html>
