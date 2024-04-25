<?php
include 'blocks/brain.php';
include 'functions.php';

$page = "quron";
$pageTitle = "Quron";
$headerImg = "quran.webp";
$headerColor = "linear-gradient(to right bottom, #87addf, #91b8e0, #9dc2e1, #accbe1, #bcd4e2, #aec7d7, #a1bacb, #94adc0, #6a88a7, #47638e, #2c3f73, #181c55);";

// $duoDaily = mysqli_query($db, "SELECT * FROM duolar WHERE type=1");

include 'blocks/head.php';
include 'blocks/header.php';

if(isset($_GET['saralash'])) $saralash = $_GET['saralash'];
if(isset($_GET['sahifa'])) $sahifa = $_GET['sahifa'];
if(isset($_GET['sura'])) $getsura = $_GET['sura'];
if(isset($_GET['kitob'])) $kitob = $_GET['kitob'];

if(empty($_GET)){
	$globalQuery = mysqli_query($db, "SELECT * FROM suranames");
}elseif(isset($getsura)){
	$suraName = mysqli_query($db, "SELECT * FROM suranames WHERE id='$getsura'");
	$rsn = $suraName->fetch_assoc();
	$sqlSura = mysqli_query($db, "SELECT * FROM suralar WHERE ns='$getsura'");
	$rowSura = mysqli_fetch_assoc($sqlSura);
}elseif(isset($kitob)){
	$sqlSura = mysqli_query($db, "SELECT * FROM suralar WHERE np='$kitob'");
}else{
	if($saralash == "sahifa"){
		$globalQuery = mysqli_query($db, "SELECT DISTINCT np FROM suralar");
	}elseif($saralash == "juz"){
		$globalQuery = mysqli_query($db, "SELECT * FROM suralar WHERE nj != '0' ORDER by id");
	}elseif($saralash == "sajda"){
		$globalQuery = mysqli_query($db, "SELECT * FROM suralar WHERE sajda='1' ORDER by id");
	}
}

$querySura = mysqli_query($db, "SELECT * FROM suralar");
$sura = $querySura->fetch_assoc();

$allSura = mysqli_query($db, "SELECT * FROM suranames");

$duolar = mysqli_query($db,getAllOrderLimit('duolar','id',9));

?>
<!-- <div class="triangle"></div> -->

<main>
	<section>
		<div class="container-fluid">
			<div class="page-title d-flex align-items-center justify-content-between">
				<h2 class="h2">Qur'on</h2>
				<div>
					<span>Hofizni o'zgartirish:</span>
					<select class="qorilar" id="qorilar">
						<option value="Alafasy_128kbps" <?php if($_COOKIE["qori"]=="Alafasy_128kbps" ) echo "selected" ;?>>Alafasy</option>
						<option value="Husary_128kbps" <?php if($_COOKIE["qori"]=="Husary_128kbps" ) echo "selected" ;?>>Husary</option>
						<option value="Ghamadi_40kbps" <?php if($_COOKIE["qori"]=="Ghamadi_40kbps" ) echo "selected" ;?>>Ghamadi</option>
						<option value="Abu_Bakr_Ash-Shaatree_128kbps" <?php if($_COOKIE["qori"]=="Abu_Bakr_Ash-Shaatree_128kbps" ) echo "selected" ;?>>Abu Bakr Ash-Shaatriy</option>
						<option value="ahmed_ibn_ali_al_ajamy_128kbps" <?php if($_COOKIE["qori"]=="ahmed_ibn_ali_al_ajamy_128kbps" ) echo "selected" ;?>>Ahmed Ibn Ali Al Ajamy</option>
						<option value="Abdurrahmaan_As-Sudais_192kbps" <?php if($_COOKIE["qori"]=="Abdurrahmaan_As-Sudais_192kbps" ) echo "selected" ;?>>Abdurrahmaan As-Sudais</option>
						<option value="Abdul_Basit_Mujawwad_128kbps" <?php if($_COOKIE["qori"]=="Abdul_Basit_Mujawwad_128kbps" ) echo "selected" ;?>>Abdul Basit Mujawwad</option>
						<option value="Abdul_Basit_Murattal_192kbps" <?php if($_COOKIE["qori"]=="Abdul_Basit_Murattal_192kbps" ) echo "selected" ;?>>Abdul Basit Murattal</option>
						<option value="Husary_128kbps" <?php if($_COOKIE["qori"]=="Husary_128kbps" ) echo "selected" ;?>>Husary</option>
						<option value="MaherAlMuaiqly128kbps" <?php if($_COOKIE["qori"]=="MaherAlMuaiqly128kbps" ) echo "selected" ;?>>Maher Al Muaiqly</option>
						<option value="Minshawy_Mujawwad_192kbps" <?php if($_COOKIE["qori"]=="Minshawy_Mujawwad_192kbps" ) echo "selected" ;?>>Minshawy Mujawwad</option>
						<option value="Minshawy_Murattal_128kbps" <?php if($_COOKIE["qori"]=="Minshawy_Murattal_128kbps" ) echo "selected" ;?>>Minshawy Murattal</option>
						<option value="Saood_ash-Shuraym_128kbps" <?php if($_COOKIE["qori"]=="Saood_ash-Shuraym_128kbps" ) echo "selected" ;?>>Saood bin Ibraaheem Ash-Shuraym</option>
						<option value="Nasser_Alqatami_128kbps" <?php if($_COOKIE["qori"]=="Nasser_Alqatami_128kbps" ) echo "selected" ;?>>Nasser_Alqatami</option>
					</select>
				</div>
			</div>
			<ul class="page-nav nav nav-tabs sortQuron mb-4">
				<li class="nav-item"><a class="nav-link <?php if(empty($_GET))echo 'active';?>" href="quron"><?php echo word('Sura raqami')?></a></li>
				<li class="nav-item"><a class="nav-link <?php if($saralash == " sahifa")echo 'active' ;?>" href="quron?saralash=sahifa"><?php echo word('Sahifa')?></a></li>
				<li class="nav-item"><a class="nav-link <?php if($saralash == " juz")echo 'active' ;?>" href="quron?saralash=juz"><?php echo word('Juz (Pora)')?></a></li>
				<li class="nav-item"><a class="nav-link <?php if($saralash == " sajda")echo 'active' ;?>" href="quron?saralash=sajda"><?php echo word('Sajda oyatlari')?></a></li>
			</ul>

			<?php if(isset($getsura)){ ?>

				<div class="d-flex">
					<div class="d-md-block d-none">
						<ul class="list-group listSurah">
							<?while($row = $allSura->fetch_assoc()){ ?>
							<a href="quron?sura=<?php echo  $row['id'] ?>#gosidebar<?php echo  $row['id'] ?>" id="gosidebar<?php echo  $row['id'] ?>" class="list-group-item list-group-item-action <?php if($row['id'] == $getsura)echo 'active';?>"><?php echo  $row['id'] . '. ' . $row['title'] ?></a>
							<?}?>
						</ul>
					</div>

					<div class="surahContent">
						<h2 class="text-center py-3 d-flex align-items-center justify-content-center">
							<?php echo  $rsn['title'] . ' surasi &nbsp;&nbsp;&nbsp;&nbsp; <span style="font-family:Basmala; font-size:50px;">' . $rsn['title_page'] ?> \</span>
						</h2>
						<h4 class="px-3 mb-3 text-right linkBook">
							<a href="?kitob=<?php echo  $rowSura['np'] ?>">
								<i class="fas fa-quran"></i>
								Kitob ko'rinishida o'qish
							</a>
						</h4>
						<?php if(!empty($rsn['info']) && $rsn['info'] != ''){
							echo '<p class="p-3 indent-20">'.$rsn['info'].'</p>';
						}
						if($show): ?>
						<p>
							<label>To'liq eshitish</label>
							<i onclick="document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').play();document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').loop = false;" class="fas fa-play c-pointer playSura" id="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="Eshitish"></i>
							<i onclick="document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').pause();document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').loop = false;" class="fas fa-pause c-pointer d-none pauseSura" id="pause<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
							<i onclick="document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').play();document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').loop = true;" class="fas fa-sync-alt c-pointer loopSura" id="loop<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
							<i onclick="document.getElementById('fullsura<?php echo  $rowSura['ns'] ?>').pause();document.getElementById('fullsura<?php echo  $rowSura['no'] ?>').currentTime=0" class="fas fa-stop c-pointer stopSura d-none" id="stop<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
							<audio preload="none" id="fullsura<?php echo  $rowSura['ns'] ?>">
								<?php
									if($rowSura['ns'] < 10) $fullsura = '00'.$rowSura['ns'];else
									if($rowSura['ns'] > 9 and $rowSura['ns'] < 100) $fullsura = '0'.$rowSura['ns'];else
									if($rowSura['ns'] > 99) $fullsura = $rowSura['ns'];

									if($_COOKIE['qori']=="ahmed_ibn_ali_al_ajamy_128kbps") { $readerDir = 'ahmed-al-ajmi'; $reader = 'ahmed-al-ajmi-'.$fullsura; }
									if($_COOKIE['qori']=="Alafasy_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Husary_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Ghamadi_40kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Abu_Bakr_Ash-Shaatree_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Abdurrahmaan_As-Sudais_192kbps") { $readerDir = 'abdul-rahman-al-sudais/192'; $reader = 'abdul-rahman-al-sudais-'.$fullsura.'-qurancentral.com-192'; }
									if($_COOKIE['qori']=="Abdul_Basit_Mujawwad_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Abdul_Basit_Murattal_192kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="MaherAlMuaiqly128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Minshawy_Mujawwad_192kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Minshawy_Murattal_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Saood_ash-Shuraym_128kbps") { $readerDir = ''; $reader = ''; }
									if($_COOKIE['qori']=="Nasser_Alqatami_128kbps") { $readerDir = ''; $reader = ''; }
								?>
								<source src="https://media.blubrry.com/muslim_central_quran/podcasts.qurancentral.com/<?php echo  $readerDir . '/' . $reader ?>.mp3" type="audio/mpeg">
							</audio>
						</p>
						<?php endif; ?>
						<p class="basmala_surah text-center">
							<?php if($getsura == 1){ ?>
							ﭷ ﭸ ﭹ ﭺ ﭻ
							<p style="text-align: center;font-size:20px;font-weight: 600"><?php echo  $words['auzu'] ?>.</p>
							<?}else{ ?>
							ﭑ ﭒ ﭓ
							<!-- <p style="text-align: center;font-size:20px;font-weight: 600"><?php //echo  $words['swnoA'] ?>.</p> -->
							<?}?>
						</p>
						<?do{

							if($rowSura['ns'] < 10) $ns = '00'.$rowSura['ns'];else
							if($rowSura['ns'] > 9 and $rowSura['ns'] < 100) $ns = '0'.$rowSura['ns'];else
							if($rowSura['ns'] > 99) $ns = $rowSura['ns'];
							if($rowSura['no'] < 10) $no = '00'.$rowSura['no'];else
							if($rowSura['no'] > 9 and $rowSura['no'] < 100) $no = '0'.$rowSura['no'];else
							if($rowSura['no'] > 99) $no = $rowSura['no'];

						$linkSura = 'https://everyayah.com/data/'.$_COOKIE['qori'].'/'.$ns.$no;
						$share = 'share'.$rowSura['ns'].$rowSura['no'];
						$copy = 'copy'.$rowSura['ns'].$rowSura['no'];
					?>
						<div class="suraItem">
							<div class="suraItem-media">
								<!-- <i class="fas fa-share-alt" data-toggle="tooltip" data-placement="top" title="Do'stlar bilan ulashish" id="<?php echo  $share ?>"></i> -->
								<i onclick="copytext('#<?php echo  $copy ?>')" class="fas fa-copy" data-toggle="tooltip" data-placement="top" title="Matnni ko'chirib olish"></i>
								<i onclick="document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').play();document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').loop = false;" class="fas fa-play c-pointer playAyah" id="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="Eshitish"></i>
								<i onclick="document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').pause();document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').loop = false;" class="fas fa-pause c-pointer d-none pauseAyah" id="pause<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
								<i onclick="document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').play();document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').loop = true;" class="fas fa-sync-alt c-pointer loopAyah" id="loop<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
								<i onclick="document.getElementById('oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>').pause();document.getElementById('oyat<?php echo  $rowSura['no'] ?>').currentTime=0" class="fas fa-stop c-pointer stopAyah d-none" id="stop<?php echo  $rowSura['id'] ?>" alt="<?php echo  $rowSura['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
								<audio preload="none" id="oyat<?php echo  $rowSura['ns'] . $rowSura['no'] ?>">
									<source src="<?php echo  $linkSura ?>.mp3" type="audio/mpeg">
								</audio>
								<p class="d-none" id="<?php echo  $copy ?>"><i style="text-align:right"><?php echo $rowSura['textar']."</i>\nMa'nosi: ".$rowSura['mano']."\n[".$rowSura['title']." surasi ".$rowSura['no']."-oyat]";?></p>
							</div>
							<div class="suraItem-content">
								<div style="text-align:right">
								<?
									// $getword = mysqli_query($db, "SELECT * FROM `words` WHERE oyat = '$rowSura[no]' and sura = '$rowSura[ns]' ORDER BY `word`");
									// while($row = $getword->fetch_assoc()){
									// 	echo '<span class="wordsarabic tooltip2" style="font-family:page'.$row['page'].'">'.$row['font']; if(!empty($row['text_ar']) and !empty($row['mano'])) echo '<span class="custom"><h1 style="font-family:page'.$row['page'].'">'.$row['font'].'</h1><em>'.$row['text'].' - '.$row['mano'].'</em></span>'; echo '</span>';
									// }
								?>
								</div>
								<p id="<?php echo  $rowSura['no'] ?>" data-ayah-num="<?php echo  $rowSura['no'] ?>" class="suraAr d-flex flex-wrap" data-font="<?php echo $rowSura['np']?>" style="font-family:page<?php echo  $rowSura['np'] ?>" data-surah="<?php echo $rsn['title']?>" data-surah-num="<?php echo $rowSura['ns']?>">
								<?
											// $explode = substr($rowSura['textp'],0,-1);
											$explode = explode(' ', $rowSura['textp']);
											$n = 1;
											foreach($explode as $key => $val){
												echo '<span data-toggle="modal" data-word-num="'.$n++.'" ayahcontent="'.$val.'" data-target="#ayah">'.$val.'</span>';
											}

										?>
								</p>
								<b class="indent-20"><?php echo $rowSura['no'].'. '. $rowSura['mano'] ?></b>
							</div>
						</div>
						<?}while($rowSura = $sqlSura->fetch_assoc());

										$prvs = $_GET['sura'] - 1;
										$next = $_GET['sura'] + 1;

										?>
						<div class="suraItem-bottom d-flex justify-content-between">
							<a href="quron?sura=<?php echo  $prvs ?>" class="btn <?php if($_GET['sura'] < 2)echo 'disabled';?> btn-primary m-3"><i class="fas fa-arrow-left"></i> &nbsp; Avvalgi sura</a>
							<a href="quron?sura=<?php echo  $next ?>" class="btn <?php if($_GET['sura'] > 113)echo 'disabled';?> btn-primary btn-pill m-3">Keyingi sura &nbsp; <i class="fas fa-arrow-right"></i></a>
						</div>
					</div>
				</div>
			<?}elseif(isset($kitob)){ ?>
				<div class="pagesz-bg py-4">
					<div class="pagesz">
						<?php if($kitob == 1){ ?>
							<p class="oyat">
								<span class='headeroyat'><em style='font-family:Basmala'> ﮍ\</em></span>
							</p>
							<p class="oyat fatiha" style="text-align:center;line-height:1.4">
								<em style="font-family:page1">ﭑ ﭒ ﭓ ﭔﭕ</em><br>
								<em style="font-family:page1">ﭖ ﭗ ﭘ ﭙ ﭚ</em><br>
								<em style="font-family:page1">ﭛ ﭜ ﭝ</em>
								<em style="font-family:page1">ﭞ ﭟ ﭠ ﭡ</em><br>
								<em style="font-family:page1">ﭢ ﭣ ﭤ ﭥ ﭦ</em>
								<em style="font-family:page1">ﭧ</em><br>
								<em style="font-family:page1">ﭨ ﭩ ﭦ</em>
								<em style="font-family:page1">ﭫ ﭬ ﭭ</em><br>
								<em style="font-family:page1">ﭮ ﭯ ﭰ ﭱ</em><br>
								<em style="font-family:page1">ﭲ ﭳﭪ</em>
							</p>
						<?}elseif($kitob == 2){ ?>
							<span class='headeroyat'><em style='font-family:Basmala'> ^\</em></span>
							<p class="fatiha" style="text-align:center;line-height:1.4">
								<em style="font-family:page1">ﭑ ﭒ ﭓ ﭔ</em><br>
								<em style="font-family:page2">ﭑ ﭒ</em>
								<em style="font-family:page2">ﭓ ﭔ ﭕ ﭖﭗ ﭘﭙ ﭚ</em><br>
								<em style="font-family:page2"> ﭛ ﭜ ﭝ ﭞ ﭟ ﭠ ﭡ </em><br>
								<em style="font-family:page2">ﭢ ﭣ ﭤ ﭥ ﭦ ﭧ ﭨ ﭩ</em><br>
								<em style="font-family:page2">ﭪ ﭫ ﭬ ﭭ ﭮ ﭯ ﭰ ﭱ ﭲ</em><br>
								<em style="font-family:page2">ﭳ ﭴ ﭵ ﭶ ﭷﭸ ﭹ</em><br>
								<em style="font-family:page2">ﭺ ﭻ ﭼ</em>
							</p>
						<?}else{ ?>
							<p class="basmala_surah">
								<?while($row = $sqlSura->fetch_assoc()){
									if($row['ns'] < 10) $nsp = '00'.$row['ns'];else
									if($row['ns'] > 9 and $row['ns'] < 100) $nsp = '0'.$row['ns'];else
									if($row['ns'] > 99) $nsp = $row['ns'];
									$linkSura = 'assets/audio/quron/'.$_COOKIE['qori'].'/'.$nsp;?>
									<?php if($row['no'] == '1'){ ?>
										<span class="headeroyat">
											<!-- <i class="fas fa-play playHeader" onclick="document.getElementById('surap<?php echo  $row['ns'] ?>').play();document.getElementById('surap<?php echo  $row['ns'] ?>').loop = false;" id="<?php echo  $row['ns'] ?>" data-toggle="tooltip" data-placement="top" title="Eshitish"></i> -->
											<!-- <i class="fas fa-pause pauseHeader d-none" onclick="document.getElementById('surap<?php echo  $row['ns'] ?>').pause();document.getElementById('surap<?php echo  $row['ns'] ?>').loop = false;" id="pause<?php echo  $row['ns'] ?>" alt="<?php echo  $row['ns'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i> -->
											<!-- <i class="fas fa-sync-alt loopHeader" onclick="document.getElementById('surap<?php echo  $row['ns'] ?>').play();document.getElementById('surap<?php echo  $row['ns'] ?>').loop = true;" id="loop<?php echo  $row['ns'] ?>" alt="<?php echo  $row['ns'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta qayta eshitish"></i> -->
											<!-- <i class="fas fa-stop stopHeader d-none" onclick="document.getElementById('surap<?php echo  $row['ns'] ?>').pause();document.getElementById('surap<?php echo  $row['ns'] ?>').currentTime=0;" id="stop<?php echo  $row['ns'] ?>" alt="<?php echo  $row['ns'] ?>" data-toggle="tooltip" data-placement="top" title="Eshitish"></i> -->
											<a class="linkSuraArabic" href="quron?sura=<?php echo  $row['ns'] ?>">
												<em style="font-family:Basmala">\
													<?php if($row['ns'] == 3){echo"_";}if($row['ns'] == 4){echo"`";}if($row['ns'] == 5){echo"a";}if($row['ns'] == 6){echo"b";}if($row['ns'] == 7){echo"c";}if($row['ns'] == 8){echo"d";}if($row['ns'] == 9){echo"e";}if($row['ns'] == 10){echo"f";}if($row['ns'] == 11){echo"g";}if($row['ns'] == 12){echo"h";}if($row['ns'] == 13){echo"i";}if($row['ns'] == 14){echo"j";}if($row['ns'] == 15){echo"k";}if($row['ns'] == 16){echo"l";}if($row['ns'] == 17){echo"m";}if($row['ns'] == 18){echo"n";}if($row['ns'] == 19){echo"o";}if($row['ns'] == 20){echo"p";}if($row['ns'] == 21){echo"q";}if($row['ns'] == 22){echo"r";}if($row['ns'] == 23){echo"s";}if($row['ns'] == 24){echo"t";}if($row['ns'] == 25){echo"u";}if($row['ns'] == 26){echo"v";}if($row['ns'] == 27){echo"w";}if($row['ns'] == 28){echo"x";}if($row['ns'] == 29){echo"y";}if($row['ns'] == 30){echo"z";}if($row['ns'] == 31){echo"{";}if($row['ns'] == 32){echo"|";}if($row['ns'] == 33){echo"}";}if($row['ns'] == 34){echo"~";}if($row['ns'] == 35){echo"ﮯ";}if($row['ns'] == 36){echo"¡";}if($row['ns'] == 37){echo"¢";}if($row['ns'] == 38){echo"£";}if($row['ns'] == 39){echo"¤";}if($row['ns'] == 40){echo"¥";}if($row['ns'] == 41){echo"¦";}if($row['ns'] == 42){echo"§";}if($row['ns'] == 43){echo"¨";}if($row['ns'] == 44){echo"©";}if($row['ns'] == 45){echo"ª";}if($row['ns'] == 46){echo"«";}if($row['ns'] == 47){echo"¬";}if($row['ns'] == 48){echo"®";}if($row['ns'] == 49){echo"¯";}if($row['ns'] == 50){echo"°";}if($row['ns'] == 51){echo"±";}if($row['ns'] == 52){echo"²";}if($row['ns'] == 53){echo"³";}if($row['ns'] == 54){echo"´";}if($row['ns'] == 55){echo"µ";}if($row['ns'] == 56){echo"¶";}if($row['ns'] == 57){echo"¸";}if($row['ns'] == 58){echo"¹";}if($row['ns'] == 59){echo"º";}if($row['ns'] == 60){echo"»";}if($row['ns'] == 61){echo"¼";}if($row['ns'] == 62){echo"½";}if($row['ns'] == 63){echo"¾";}if($row['ns'] == 64){echo"¿";}if($row['ns'] == 65){echo"À";}if($row['ns'] == 66){echo"Á";}if($row['ns'] == 67){echo"Â";}if($row['ns'] == 68){echo"Ã";}if($row['ns'] == 69){echo"Ä";}if($row['ns'] == 70){echo"Å";}if($row['ns'] == 71){echo"Æ";}if($row['ns'] == 72){echo"Ç";}if($row['ns'] == 73){echo"È";}if($row['ns'] == 74){echo"É";}if($row['ns'] == 75){echo"Ê";}if($row['ns'] == 76){echo"Ë";}if($row['ns'] == 77){echo"Ì";}if($row['ns'] == 78){echo"Í";}if($row['ns'] == 79){echo"Î";}if($row['ns'] == 80){echo"Ï";}if($row['ns'] == 81){echo"Ð";}if($row['ns'] == 82){echo"Ñ";}if($row['ns'] == 83){echo"Ò";}if($row['ns'] == 84){echo"Ó";}if($row['ns'] == 85){echo"Ô";}if($row['ns'] == 86){echo"Õ";}if($row['ns'] == 87){echo"Ö";}if($row['ns'] == 88){echo"×";}if($row['ns'] == 89){echo"Ø";}if($row['ns'] == 90){echo"Ù";}if($row['ns'] == 91){echo"Ú";}if($row['ns'] == 92){echo"Û";}if($row['ns'] == 93){echo"Ü";}if($row['ns'] == 94){echo"Ý";}if($row['ns'] == 95){echo"Þ";}if($row['ns'] == 96){echo"ß";}if($row['ns'] == 97){echo"à";}if($row['ns'] == 98){echo"á";}if($row['ns'] == 99){echo"â";}if($row['ns'] == 100){echo"ã";}if($row['ns'] == 101){echo"ä";}if($row['ns'] == 102){echo"å";}if($row['ns'] == 103){echo"æ";}if($row['ns'] == 104){echo"ç";}if($row['ns'] == 105){echo"è";}if($row['ns'] == 106){echo"é";}if($row['ns'] == 107){echo"ê";}if($row['ns'] == 108){echo"ë";}if($row['ns'] == 109){echo"ì";}if($row['ns'] == 110){echo"í";}if($row['ns'] == 111){echo"î";}if($row['ns'] == 112){echo"ï";}if($row['ns'] == 113){echo"ð";}if($row['ns'] == 114){echo"ñ";}?>
												</em>
											</a>
										</span>
										<audio preload="none" id="surap<?php echo  $row['ns'] ?>">
											<source src="<?php echo  $linkSura ?>.mp3" type="audio/mpeg">
										</audio>
										<em class='bismillah'>ﭑ ﭒ ﭓ</em>
									<?}?>
									<em id="asok" style="font-family:page<?php echo  $row['np'] ?>;"><?php echo  $row['textp'] ?></em>
								<?}?>
							</p>
						<?}?>
						<div class="numPage"><?php echo arabicNumbers($kitob)?></div>
					</div>
					<?$prvs = $_GET['kitob'] - 1;$next = $_GET['kitob'] + 1;$disabled = 'disabled';?>
					<div class="kitobPag d-flex justify-content-between">
						<a href="quron?kitob=<?php echo  $next ?>" class="btn btn-primary <?php if($_GET['kitob'] == 604)echo $disabled;?>">
							<i class="fas fa-arrow-left"></i> &nbsp; Keyingi sahifa
						</a>
						<a href="quron?kitob=<?php echo  $prvs ?>" class="btn btn-primary <?php if($_GET['kitob'] == 1)echo $disabled;?>">
							<i class="fas fa-arrow-right"></i> &nbsp; Avvalgi sahifa
						</a>
					</div>
				</div>
			<?}elseif(empty($_GET)){ ?>
				<!-- <svg id="play" viewBox="0 0 163 163" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"="0px">
					<g fill="none">
						<g  transform="translate(2.000000, 2.000000)" stroke-width="4">
								<path d="M10,80 C10,118.107648 40.8923523,149 79,149 L79,149 C117.107648,149 148,118.107648 148,80 C148,41.8923523 117.107648,11 79,11" id="lineOne" stroke="#007BFF"></path>
								<path d="M105.9,74.4158594 L67.2,44.2158594 C63.5,41.3158594 58,43.9158594 58,48.7158594 L58,109.015859 C58,113.715859 63.4,116.415859 67.2,113.515859 L105.9,83.3158594 C108.8,81.1158594 108.8,76.6158594 105.9,74.4158594 L105.9,74.4158594 Z" id="triangle" stroke="#007BFF"></path>
								<path d="M159,79.5 C159,35.5933624 123.406638,0 79.5,0 C35.5933624,0 0,35.5933624 0,79.5 C0,123.406638 35.5933624,159 79.5,159 L79.5,159" id="lineTwo" stroke="#007BFF"></path>
						</g>
					</g>
				</svg> -->
				<!-- <svg>
					<path id="playsvg" fill="none" d="M105.9,74.4158594 L67.2,44.2158594 C63.5,41.3158594 58,43.9158594 58,48.7158594 L58,109.015859 C58,113.715859 63.4,116.415859 67.2,113.515859 L105.9,83.3158594 C108.8,81.1158594 108.8,76.6158594 105.9,74.4158594 L105.9,74.4158594 Z" stroke="red" stroke-width="3"/>
				</svg> -->
				<table class="table quronFullTable">
					<thead>
						<tr>
							<th>№</th>
							<!-- <th class="text-center" width="100"><i class="fas fa-music"></i></th> -->
							<th>Nomi</th>
							<th class="text-center">Oyat soni</th>
							<th class="text-right">Arabcha nomi</th>
							<th>№</th>
						</tr>
					</thead>
					<tbody>
						<?$n=1; $an=1;
						while($row = $globalQuery->fetch_assoc()){
							$qtyOyat = mysqli_query($db,getAll("suralar WHERE ns='$row[id]'"));
							$rowO = $qtyOyat->fetch_assoc();
							if($rowO['ns'] < 10) $ns = '00'.$rowO['ns'];else
							if($rowO['ns'] > 9 and $rowO['ns'] < 100) $ns = '0'.$rowO['ns'];else
							if($rowO['ns'] > 99) $ns = $rowO['ns'];
							// $linkSura = 'assets/audio/quron/'.$_COOKIE['qori'].'/'.$ns;?>
							<tr>
								<td align="center" width="20"><?php echo  $n++ ?></td>
								<!-- <td align="center" width="20"> -->
									<!-- <i onclick="document.getElementById('sura<?php //echo  $rowO['ns'] ?>').play();document.getElementById('sura<?php //echo  $rowO['ns'] ?>').loop = false;" class="fas fa-play mr-2 c-pointer playSura" id="<?php // echo  $rowO['id'] ?>" data-toggle="tooltip" data-placement="top" title="Eshitish"></i> -->
									<!-- <i onclick="document.getElementById('sura<?php //echo  $rowO['ns'] ?>').pause();document.getElementById('sura<?php //echo  $rowO['ns'] ?>').loop = false;" class="fas fa-pause mr-2 c-pointer d-none pauseSura" id="pause<?php // echo  $rowO['id'] ?>" alt="<?php echo  $rowO['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i> -->
									<!-- <i onclick="document.getElementById('sura<?php //echo  $rowO['ns'] ?>').play();document.getElementById('sura<?php //echo  $rowO['ns'] ?>').loop = true;" class="fas fa-sync-alt c-pointer loopSura" id="loop<?php // echo  $rowO['id'] ?>" alt="<?php echo  $rowO['id'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i> -->
									<!-- <i onclick="document.getElementById('sura<?php //echo  $rowO['ns'] ?>').pause();document.getElementById('sura<?php //echo  $rowO['ns'] ?>').currentTime=0" class="fas fa-stop c-pointer stopSura d-none" id="stop<?php // echo  $rowO['id'] ?>" alt="<?php echo  $rowO['id'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i> -->
									<!-- <audio preload="none" id="sura<?php echo  $rowO['ns'] ?>">
										<source src="<?php //echo  $linkSura ?>.mp3" type="audio/mpeg">
									</audio> -->
								<!-- </td> -->
								<td align="left" width="150"><a href="?sura=<?php echo  $rowO['ns'] ?>"><?php echo  $row['title'] ?></a></td>
								<td align="center"><?php echo  $qtyOyat->num_rows ?></td>
								<td align="right" id="ayahName" width="150"><a href="?sura=<?php echo  $rowO['ns'] ?>"><?php echo  $row['titlear'] ?></a></td>
								<td align="center" width="20"><?php echo arabicNumbers($an++)?></td>
							</tr>
						<?}?>
					</tbody>
				</table>
			<?}else{
				if(isset($getsura)){ ?>
					Sura
				<?}elseif(isset($sahifa)){ ?>
						okoddsa
				<?}elseif(isset($saralash)){ ?>
					<?php if($saralash == "sahifa"){ ?>
						<table class="table quronFullTable">
							<thead>
								<tr>
									<th width="80" class="text-center"><i class="fas fa-music"></i></th>
									<th>Sahifa</th>
									<th>Oyat</th>
								</tr>
							</thead>
							<tbody>
								<?$n=1; $an=1;
								while($row = $globalQuery->fetch_assoc()){
									$query = mysqli_query($db,getAll("suralar WHERE np='$row[np]'"));
									$rowSN = $query->fetch_assoc();

									if($rowSN['np'] < 10) $ns = '00'.$rowSN['np'];else
									if($rowSN['np'] > 9 and $rowSN['np'] < 100) $ns = '0'.$rowSN['np'];else
									if($rowSN['np'] > 99) $ns = $rowSN['np'];
									$linkSura = 'https://everyayah.com/data/'.$_COOKIE['qori'].'/PageMp3s/Page'.$ns;?>
									<tr>
										<td align="center">
											<i onclick="document.getElementById('sura<?php echo  $rowSN['np'] ?>').play();document.getElementById('sura<?php echo  $rowSN['np'] ?>').loop = false;" class="fas fa-play mr-2 c-pointer playSura" id="<?php echo  $rowSN['np'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['np'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['np'] ?>').loop = false;" class="fas fa-pause mr-2 c-pointer d-none pauseSura" id="pause<?php echo  $rowSN['np'] ?>" alt="<?php echo  $rowSN['np'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['np'] ?>').play();document.getElementById('sura<?php echo  $rowSN['np'] ?>').loop = true;" class="fas fa-sync-alt mr-2 c-pointer loopSura" id="loop<?php echo  $rowSN['np'] ?>" alt="<?php echo  $rowSN['np'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['np'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['np'] ?>').currentTime=0" class="fas fa-stop mr-2 c-pointer stopSura d-none" id="stop<?php echo  $rowSN['np'] ?>" alt="<?php echo  $rowSN['np'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<audio preload="none" id="sura<?php echo  $rowSN['np'] ?>">
												<source src="<?php echo  $linkSura ?>.mp3" type="audio/mpeg">
											</audio>
										</td>
										<td>
											<a href="quron?sahifa=<?php echo  $rowSN['np'] ?>"><?php echo  $rowSN['np'] . ' - sahifa'; ?></a>
										</td>
										<td><a href="quron?sura=<?php echo  $rowSN['ns'] . '#' . $rowSN['no'] ?>"><?php echo  $rowSN['title'] . ' surasi ' . $rowSN['no'] . '-oyat'; ?></a></td>
									</tr>
								<?}?>
							</tbody>
						</table>
					<?}elseif($saralash == "juz"){ ?>
						<table class="table quronFullTable">
							<thead>
								<tr>
									<th width="100" class="text-center">№</th>
									<th width="80" class="text-center" class="text-center"><i class="fas fa-music"></i></th>
									<th>Oyat ... dan boshlanadi</th>
								</tr>
							</thead>
							<tbody>
								<?$n=1; $an=1;
								while($row = $globalQuery->fetch_assoc()){
									$query = mysqli_query($db,getAll("suralar WHERE np='$row[np]'"));
									$rowSN = $query->fetch_assoc();

									if($rowSN['ns'] < 10) $ns = '00'.$rowSN['ns'];else
									if($rowSN['ns'] > 9 and $rowSN['ns'] < 100) $ns = '0'.$rowSN['ns'];else
									if($rowSN['ns'] > 99) $ns = $rowSN['ns'];

									if($rowSN['no'] < 10) $no = '00'.$rowSN['no'];else
									if($rowSN['no'] > 9 and $rowSN['no'] < 100) $no = '0'.$rowSN['no'];else
									if($rowSN['no'] > 99) $no = $rowSN['no'];

									$linkSura = 'https://everyayah.com/data/'.$_COOKIE['qori'].'/'.$ns.$no;?>
									<tr>
										<td align="center"><?php echo  $n++ ?> - juz'</td>
										<td align="center">
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').play();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = false;" class="fas fa-play mr-2 c-pointer playSura" id="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = false;" class="fas fa-pause mr-2 c-pointer d-none pauseSura" id="pause<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').play();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = true;" class="fas fa-sync-alt mr-2 c-pointer loopSura" id="loop<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').currentTime=0" class="fas fa-stop mr-2 c-pointer stopSura d-none" id="stop<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<audio preload="none" id="sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>">
												<source src="<?php echo  $linkSura ?>.mp3" type="audio/mpeg">
											</audio>
										</td>
										<td><a href="quron?sura=<?php echo  $rowSN['ns'] . '#' . $rowSN['no'] ?>"><?php echo  $rowSN['title'] . ' surasi ' . $rowSN['no'] . '-oyat'; ?></a></td>
									</tr>
								<?}?>
							</tbody>
						</table>
					<?}elseif($saralash == "sajda"){ ?>
						<table class="table quronFullTable">
							<thead>
								<tr>
									<th width="80" class="text-center">№</th>
									<th width="80" class="text-center"><i class="fas fa-music"></i></th>
									<th><?php echo word('Oyat')?></th>
								</tr>
							</thead>
							<tbody>
								<?$n=1; $an=1;
								while($row = $globalQuery->fetch_assoc()){
									$query = mysqli_query($db,getAll("suralar WHERE np='$row[np]'"));
									$rowSN = $query->fetch_assoc();

									if($rowSN['ns'] < 10) $ns = '00'.$rowSN['ns'];else
									if($rowSN['ns'] > 9 and $rowSN['ns'] < 100) $ns = '0'.$rowSN['ns'];else
									if($rowSN['ns'] > 99) $ns = $rowSN['ns'];

									if($rowSN['no'] < 10) $no = '00'.$rowSN['no'];else
									if($rowSN['no'] > 9 and $rowSN['no'] < 100) $no = '0'.$rowSN['no'];else
									if($rowSN['no'] > 99) $no = $rowSN['no'];
									$linkSura = 'https://everyayah.com/data/'.$_COOKIE['qori'].'/'.$ns.$no;?>
									<tr>
										<td align="center"><?php echo  $n++ ?></td>
										<td align="center">
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').play();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = false;" class="fas fa-play mr-2 c-pointer playSura" id="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = false;" class="fas fa-pause mr-2 c-pointer d-none pauseSura" id="pause<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').play();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').loop = true;" class="fas fa-sync-alt mr-2 c-pointer loopSura" id="loop<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="Qayta-qayta eshitish"></i>
											<i onclick="document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').pause();document.getElementById('sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>').currentTime=0" class="fas fa-stop mr-2 c-pointer stopSura d-none" id="stop<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" alt="<?php echo  $rowSN['ns'] . $rowSN['no'] ?>" data-toggle="tooltip" data-placement="top" title="To'xtatish"></i>
											<audio preload="none" id="sura<?php echo  $rowSN['ns'] . $rowSN['no'] ?>">
												<source src="<?php echo  $linkSura ?>.mp3" type="audio/mpeg">
											</audio>
										</td>
										<td><a href="quron?sura=<?php echo  $rowSN['ns'] . '#' . $rowSN['no'] ?>"><?php echo  $rowSN['title'] . ' surasi ' . $rowSN['no'] . '-oyat'; ?></a></td>
									</tr>
								<?}?>
							</tbody>
						</table>
					<?}
				}
			}?>
		</div>
	</section>
</main>
<?include 'blocks/footer.php'?>
<div class="modal fade" id="ayah" tabindex="-1" role="dialog" aria-labelledby="ayahLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ayahLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
				<div class="word" style="font-family: ;"></div>
				<div class="other"></div>
			</div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
  </div>
</div>
<script>
	$(".playSura").on("click", function() {
		var id = this.id
		$(this).addClass('d-none');
		$("#pause" + id).removeClass('d-none');
	})
	$(".pauseSura").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
	})
	$(".loopSura").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).addClass('d-none');
		$("#pause" + id).addClass('d-none');
		$("#stop" + id).removeClass('d-none');
	})
	$(".stopSura").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
		$("#loop" + id).removeClass('d-none');
	})

	$(".playAyah").on("click", function() {
		var id = this.id
		$(this).addClass('d-none');
		$("#pause" + id).removeClass('d-none');
	})
	$(".pauseAyah").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
	})
	$(".loopAyah").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).addClass('d-none');
		$("#pause" + id).addClass('d-none');
		$("#stop" + id).removeClass('d-none');
	})
	$(".stopAyah").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
		$("#loop" + id).removeClass('d-none');
	})

	$(".playHeader").on("click", function() {
		var id = this.id
		$(this).addClass('d-none');
		$("#pause" + id).removeClass('d-none');
	})
	$(".pauseHeader").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
		$("#stop" + id).addClass('d-none');
		$("#loop" + id).removeClass('d-none');
	})
	$(".loopHeader").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).addClass('d-none');
		$("#pause" + id).removeClass('d-none');
		$("#stop" + id).removeClass('d-none');
	})
	$(".stopHeader").on("click", function() {
		var id = $(this).attr("alt")
		$(this).addClass('d-none');
		$("#" + id).removeClass('d-none');
		$("#pause" + id).addClass('d-none');
		$("#loop" + id).removeClass('d-none');
	})
	// $(window).scroll(function() {
	// 	var scrolTop = $(window).scrollTop()
	// 	var documentHeight = $(document).height()
	// 	var neededVar = documentHeight - scrolTop;
	// 	var ifelse = neededVar < 1100;

	// 	if (neededVar < 1100) {
	// 		$(".listSurah").removeClass('fixed')
	// 		$(".listSurah").addClass('absolute')
	// 	} else {
	// 		$(".listSurah").removeClass('absolute')
	// 	}

	// 	if ($(this).scrollTop() > 563 && neededVar > 1100) {
	// 		$(".listSurah").addClass('fixed')
	// 	} else {
	// 		$(".listSurah").removeClass('fixed')
	// 	}
	// });
	$('.suraItem .suraAr span').on("click", function(){
		let pageFont = $(this).parent().data('font');
		let surahNumber = $(this).parent().data('surah-num');
		let ayahNumber = $(this).parent().data('ayah-num');
		let wordNumber = $(this).data('word-num');
		let surahName = $(this).parent().data('surah');
		$('.modal-title').html(surahName + ' surasi '+ surahNumber + ':' + ayahNumber + ':' + wordNumber);
		$('.modal-body .word').css('font','50px page'+pageFont).html($(this).attr('ayahcontent') + '<br>');
	});
</script>
</body>

</html>