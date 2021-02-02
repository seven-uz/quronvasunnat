<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 pb-md-0 pb-4 text-sm-left text-center">
                <h4>
                    <? echo word('Biz bilan bog‘lanish')?>
                </h4>
                <form id="contactform">
                    <input type="text" name="name" class="form-control my-2" placeholder="<? echo word('Ismingiz:')?>" required>
                    <input type="email" name="email" class="form-control" placeholder="<? echo word('E-mail manzilingiz:')?>" required>
                    <textarea name="text" class="form-control my-2" placeholder="<? echo word('Matnni yozing:')?>" required></textarea>
                    <div class="d-flex justify-content-between align-items-center">
                        <? $mt1 = mt_rand(10,99);?>
                        <? $mt2 = mt_rand(10,99);?>
                        <input type="hidden" name="mt1" value="<?= $mt1 ?>">
                        <input type="hidden" name="mt2" value="<?= $mt2 ?>">
                        <span style="font-size:16px;font-weight:bold;width:100%;display:block">
                            <? echo $mt1.' + '.$mt2.' = ?';?></span>
                        <input type="number" name="code" placeholder="<? echo word('Javob:')?>" class="form-control mx-3" autocomplete="off" required>
                        <button type="submit" class="btn btn-primary">
                            <? echo word('Yuborish')?></button>
                    </div>
                </form>
            </div>
            <div class="col-md-3 pb-md-0 pb-4 links text-sm-left text-center">
                <h4>
                    <? echo word('Havolalar')?>
                </h4>
                <div class="d-flex flex-column">
                    <?
                    $link3 = mysqli_query($db, "SELECT * FROM duolar ORDER BY RAND() LIMIT 1");
                    $link3Row = $link3->fetch_assoc();

                    $link5 = mysqli_query($db, "SELECT * FROM roviylar ORDER BY RAND() LIMIT 1");
                    $link5Row = $link5->fetch_assoc();

                    $link6 = mysqli_query($db, "SELECT * FROM rivoyatchilar ORDER BY RAND() LIMIT 1");
                    $link6Row = $link6->fetch_assoc();

                    $link7 = mysqli_query($db, "SELECT * FROM qorilar ORDER BY RAND() LIMIT 1");
                    $link7Row = $link7->fetch_assoc();
                    ?>
                    <a href="about"><i class="fa fa-info-circle"></i>&nbsp;
                        <? echo word('Biz haqimizda') ?>
                    </a>
                    <a href="duo?id=<?= $link3Row['id'] ?>"><i class="fas fa-pray"></i>&nbsp;
                        <? echo word($link3Row['title']) ?>
                    </a>
                    <a href="person?imom=<?= $link5Row['id'] ?>"><i class="fas fa-mosque"></i>&nbsp;
                        <? echo word($link5Row['name']) ?>
                    </a>
                    <a href="person?id=<?= $link6Row['id'] ?>"><i class="fas fa-star-and-crescent"></i>&nbsp;
                        <? echo word($link6Row['name']) ?>
                    </a>
                    <a href="person?qori=<?= $link7Row['id'] ?>"><i class="fas fa-music"></i>&nbsp;
                        <? echo word($link7Row['name']) ?>
                    </a>
                </div>
            </div>
            <div class="col-md-5 text-sm-left text-center">
                <h4>
                    <? echo word('Teglar') ?>
                </h4>
                <?$tags = mysqli_query($db, "SELECT * FROM tags ORDER BY RAND() LIMIT 20");

                while($row = $tags->fetch_assoc()){
                    echo '<a class="tagFooter" href="search?q='.word($row['title']).'">#'.word($row['title']).'</a>';
                }?>
            </div>
        </div>
    </div>
</footer>
<div class="container-fluid bottom">
    <div class="container d-flex justify-content-md-between justify-content-center align-items-center">
        <div>Made by <a href="https://uzseven.uz"><img src="assets/images/logouzseven.png">uzseven</a> / <?= date("Y") ?></div>
        <div class="d-md-block d-none">
            <a href="https://t.me/quronvasunnatsite" target="_blank"><i class="fab fa-telegram"></i></a>
            <a href="https://www.facebook.com/quronvasunnatuz" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="https://instagram.com/quronvasunnatuz" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</div>


<!-- Fixed right block -->
<!-- <div class="right-profile">
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="kt-offcanvas-panel__title"><? echo word('Profil') ?></h3>
        <i class="fas fa-times close-right-profile fz-20 c-pointer" id="close-right-profile"></i>
    </div>
    <div class="kt-offcanvas-panel__body kt-scroll ps ps--active-y" style="height: 666px; overflow: hidden;">
        <div class="kt-user-card-v3 kt-margin-b-30">
            <div class="kt-user-card-v3__avatar">
                <img src="" alt="">
            </div>
            <div class="kt-user-card-v3__detalis">
                <a href="#" class="kt-user-card-v3__name">
                    James Jones
                </a>
                <div class="kt-user-card-v3__desc">
                    Application Developer
                </div>
                <div class="kt-user-card-v3__info">
                    <a href="#" class="kt-user-card-v3__item">
                        <i class="flaticon-email-black-circular-button kt-font-brand"></i>
                        <span class="kt-user-card-v3__tag">jm@softplus.com</span>
                    </a>
                    <a href="/" class="kt-user-card-v3__item">
                        <i class="flaticon-twitter-logo-button kt-font-success"></i>
                        <span class="kt-user-card-v3__tag">@jmdev</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="kt-offcanvas-panel__section kt-margin-t-50">
            Top Products
        </div>

        <div class="kt-widget-4">
            <div class="kt-widget-4__items">
                <div class="kt-widget-4__item">
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-section">
                            <div class="kt-widget-4__item-pic">
                                <img class="" src="" alt="">
                            </div>
                            <div class="kt-widget-4__item-info">
                                <a href="#">
                                    <div class="kt-widget-4__item-username">Circle Desige</div>
                                </a>
                                <div class="kt-widget-4__item-desc">UI/UX, Animation, Design</div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-price">
                            <span class="kt-widget-4__item-badge">$</span>
                            <span class="kt-widget-4__item-number">2,830</span>
                        </div>
                    </div>
                </div>

                <div class="kt-widget-4__item">
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-section">
                            <div class="kt-widget-4__item-pic">
                                <img class="" src="" alt="">
                            </div>
                            <div class="kt-widget-4__item-info">
                                <a href="#">
                                    <div class="kt-widget-4__item-username">Clip Code</div>
                                </a>
                                <div class="kt-widget-4__item-desc">PHP, NET, Python, Ruby</div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-price">
                            <span class="kt-widget-4__item-badge">$</span>
                            <span class="kt-widget-4__item-number">4,975</span>
                        </div>
                    </div>
                </div>

                <div class="kt-widget-4__item">
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-section">
                            <div class="kt-widget-4__item-pic">
                                <img class="" src="" alt="">
                            </div>
                            <div class="kt-widget-4__item-info">
                                <a href="#">
                                    <div class="kt-widget-4__item-username">JS-Nijas</div>
                                </a>
                                <div class="kt-widget-4__item-desc">jQuery, AngularJS, Recct</div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget-4__item-content">
                        <div class="kt-widget-4__item-price">
                            <span class="kt-widget-4__item-badge">$</span>
                            <span class="kt-widget-4__item-number">3.594</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kt-margin-t-40">
            <button type="button" class="btn btn-brand btn-font-sm btn-upper btn-bold">full profile</button>
        </div>
    </div>
</div> -->

<!-- Modals, Popups, Fixed blocks -->

<div class="modal fade" id="auth" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link active" href="#login-tab">
                            <? echo word('Sayt sozlamalari') ?></a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link" href="#reg-tab">
                            <? echo word('Royhatdan otish') ?></a>
                    </li>
                    <!-- <li class="nav-item">
                <a data-toggle="tab" class="nav-link disabled" href="#menu3" tabindex="-1" aria-disabled="true">Disabled</a>
            </li> -->
                </ul>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="tab-content p-3">
                    <div id="login-tab" class="tab-pane active p-3">
                        <form id="login">
                            <div class="form-group">
                                <input type="text" class="form-control" name="login" placeholder="Login">
                                <input type="password" class="form-control my-3" name="password" placeholder="Parol">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <input type="checkbox" name="remember" id="remember">
                                        <label for="remember">
                                            <? echo word('Eslab qolish') ?></label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <? echo word('Kirish') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div id="reg-tab" class="tab-pane fade">
                        <form id="reg">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-settings" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link active" href="#menu1">
                            <? echo word('Sayt sozlamalari') ?></a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link" href="#home">
                            <? echo word('Audioni sozlash') ?></a>
                    </li>
                    <li class="nav-item">
                        <a data-toggle="tab" class="nav-link" href="#menu2">
                            <? echo word('Qo‘shimcha') ?></a>
                    </li>
                    <!-- <li class="nav-item">
                <a data-toggle="tab" class="nav-link disabled" href="#menu3" tabindex="-1" aria-disabled="true">Disabled</a>
            </li> -->
                </ul>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="tab-content p-3">
                    <div id="menu1" class="tab-pane active p-3">
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Sayt tili') ?>:</label>
                            <div class="col-6 custom-control pl-0">
                                <select class="form-control" onchange="if (this.value) window.location.href = this.value">
                                    <option value="?lang=uzl" <?if($_COOKIE['lang']=='uzl' ) echo 'selected' ;?> />O‘zbekcha</option>
                                    <option value="?lang=uzk" <?if($_COOKIE['lang']=='uzk' ) echo 'selected' ;?> />Ўзбекча</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Sayt asosiy rangi') ?>:
                                <?if(!empty($_COOKIE['globalColor'])){?> <i class="fas fa-times" id="resetGbColor"></i>
                                <?}?></label>
                            <div class="col-6 custom-control pl-0">
                                <input type="color" class="form-control" id="globalColor" value="<?if(empty($_COOKIE['globalColor']))echo '#007BFF';else echo $_COOKIE['globalColor'];?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Sayt 2-rangi') ?>:</label>
                            <div class="col-6 custom-control pl-0">
                                <input type="color" class="form-control" value="#cccccc">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Tungi rejim') ?>:</label>
                            <div class="col-6 custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="customSwitch1">
                                <label class="custom-control-label" for="customSwitch1"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Sarlavha rasmlari') ?>:</label>
                            <div class="col-6 custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="headerImg" <?if($_COOKIE['headerImg']=='on' )echo "checked" ;?>>
                                <label class="custom-control-label" for="headerImg"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-6">
                                <? echo word('Slayder') ?>:</label>
                            <div class="col-6 custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="headerSlider" <?if($_COOKIE['headerSlider']=='on' )echo "checked" ;?>>
                                <label class="custom-control-label" for="headerSlider"></label>
                            </div>
                        </div>
                        <h5 class="text-center text-success d-none saved">
                            <? echo word('Saqlandi') ?>...</h5>
                    </div>
                    <div id="home" class="tab-pane fade">
                        <form action="">
                            <div class="form-group">
                                <label>
                                    <? echo word('Qaysi qorini eshitishni istaysiz') ?>?</label>
                                <select class="form-control" id="qorilar">
                                    <option value="Alafasy_128kbps" <?if($_COOKIE['qori']=="Alafasy_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Alafasy') ?>
                                    </option>
                                    <option value="Husary_128kbps" <?if($_COOKIE['qori']=="Husary_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Husary') ?>
                                    </option>
                                    <option value="Ghamadi_40kbps" <?if($_COOKIE['qori']=="Ghamadi_40kbps" ) echo 'selected' ;?>>
                                        <? echo word('Ghamadi') ?>
                                    </option>
                                    <option value="Abu_Bakr_Ash-Shaatree_128kbps" <?if($_COOKIE['qori']=="Abu_Bakr_Ash-Shaatree_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Abu Bakr Ash-Shaatriy') ?>
                                    </option>
                                    <option value="ahmed_ibn_ali_al_ajamy_128kbps" <?if($_COOKIE['qori']=="ahmed_ibn_ali_al_ajamy_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Ahmed Ibn Ali Al Ajamy') ?>
                                    </option>
                                    <option value="Abdurrahmaan_As-Sudais_192kbps" <?if($_COOKIE['qori']=="Abdurrahmaan_As-Sudais_192kbps" ) echo 'selected' ;?>>
                                        <? echo word('Abdurrahmaan As-Sudais') ?>
                                    </option>
                                    <option value="Abdul_Basit_Mujawwad_128kbps" <?if($_COOKIE['qori']=="Abdul_Basit_Mujawwad_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Abdul Basit Mujawwad') ?>
                                    </option>
                                    <option value="Abdul_Basit_Murattal_192kbps" <?if($_COOKIE['qori']=="Abdul_Basit_Murattal_192kbps" ) echo 'selected' ;?>>
                                        <? echo word('Abdul Basit Murattal') ?>
                                    </option>
                                    <option value="MaherAlMuaiqly128kbps" <?if($_COOKIE['qori']=="MaherAlMuaiqly128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Maher Al Muaiqly') ?>
                                    </option>
                                    <option value="Minshawy_Mujawwad_192kbps" <?if($_COOKIE['qori']=="Minshawy_Mujawwad_192kbps" ) echo 'selected' ;?>>
                                        <? echo word('Minshawy Mujawwad') ?>
                                    </option>
                                    <option value="Minshawy_Murattal_128kbps" <?if($_COOKIE['qori']=="Minshawy_Murattal_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Minshawy Murattal') ?>
                                    </option>
                                    <option value="Saood_ash-Shuraym_128kbps" <?if($_COOKIE['qori']=="Saood_ash-Shuraym_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Saood bin Ibraaheem Ash-Shuraym') ?>
                                    </option>
                                    <option value="Nasser_Alqatami_128kbps" <?if($_COOKIE['qori']=="Nasser_Alqatami_128kbps" ) echo 'selected' ;?>>
                                        <? echo word('Nasser_Alqatami') ?>
                                    </option>
                                </select>
                            </div>
                            <h5 class="text-center text-success d-none saved">
                                <? echo word('Saqlandi') ?>...</h5>
                        </form>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <h3>
                            <? echo word('Qo‘shimcha sozlamalar') ?>
                        </h3>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="up-down">
    <i class="fas fa-angle-up"></i>
    <i class="fas fa-angle-down"></i>
</div>
<div class="copiedBlock d-none" id="copiedBlock">
    Matn ko'chirib olindi. Kerakli joyga qo'yish uchun "CTRL + V" ni bosing!
</div>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<!-- Owl Carousel 2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha256-pTxD+DSzIwmwhOqTFN+DB+nHjO4iAsbgfyFq5K5bcE0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js" integrity="sha256-jnOjDTXIPqall8M0MyTSt98JetJuZ7Yu+1Jm7hLTF7U=" crossorigin="anonymous"></script>

<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

<!-- Custom Js -->
<script src="assets/js/main.js"></script>
<script src="assets/js/livesearch.js"></script>

<script>
	$("#contactform").on('submit', (function(el) {
		el.preventDefault();
      $.ajax({
			url: "actions/contactform.php",
         type: "POST",
         data: new FormData(this),
         contentType: false,
         cache: false,
         processData: false,
         success: function(data) {
            $("#output22").html(data);
         }
      });
	}));
</script>