<!-- end header -->
<section >
    <div id="main_slider" class="carousel slide banner-main" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="container">
                    <div class="row marginii">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="carousel-caption ">
                                <?php if(isset($_SESSION['user_id'])){?>
                                <h1><?php if(isset($_SESSION['first_name'])) echo $_SESSION['first_name'].' ' ; if(isset($_SESSION['last_name'])) echo $_SESSION['last_name'] ?></h1>
                                <h3>Email: <strong class="color"><?php if(isset($_SESSION['email'])) echo $_SESSION['email']?></strong></h3>
                                <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using</p>
                                <a class="btn btn-lg btn-primary" href="/profile<?php if(isset($_SESSION['user_id']))echo'?id='.$_SESSION['user_id']?>" role="button">Edit Profile</a>
                                <?php } else { ?>
                                    <h1>Please register to create a profile</h1>
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using</p>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="img-box">
                                <figure><img src="<?php if(!empty($_SESSION['prof_img']) && file_exists('images\profile\\'.$_SESSION['prof_img'])){ echo 'images\profile\\'.$_SESSION['prof_img'];} else{ echo 'images\profile\\'."default.jpg"; }?>" alt="img" style="width: 100%;height: 100%"></figure>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- plant -->
<div id="plant" class="plants">
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="titlepage">
                    <h2>All profile</h2>
                    <span>looking at its layout. The point of using Lorem Ipsumletters, as opposed to usingl</span>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <?php if (count($users) >0 ) {
                foreach ($users as $user) {
                    if(isset($_SESSION['user_id']) && $user->id == $_SESSION['user_id']){
                        continue;
                    }?>
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                        <div class="plants-box">
                            <figure><img src="<?php if(isset($user->prof_img) && file_exists('images\profile\\'.$user->prof_img)){ echo 'images\profile\\'.$user->prof_img;} else{ echo "images\profile\default.jpg"; }?>" alt="img" style="height: 200px"></figure>
                            <h3><?php echo $user->first_name ?><br><?php echo $user->last_name ?></h3>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page   when looking at its layout. The point of using Lorem Ipsumletters, as opposed to using</p>
                            <a class="btn btn-lg btn-primary" href="message<?php echo'?id='.$user->id?>" role="button" style="max-width: 200px">send a message</a>
                        </div>
                    </div>
                <?php } } ?>
        </div>
    </div>
</div>
<!-- end plant -->
<!-- about -->
<div id="about" class="about">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="Nursery-img">
                        <figure>
                            <img src="/images/contactimg.jpg" alt="img"/>
                            <div class="text-box">
                                <h3>Best Green Nursery</h3>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="about-box">
                    <div class="titlepage">
                        <h2>About US</h2>
                        <span>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web pageweb page</span>
                        <div class="read-more">
                            <a  href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end about -->
<!--Gallery -->
<div id="gallery" class="Gallery">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Our Album</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid margin-r-l">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 thumb">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/1.jpg" class="fancybox" rel="ligthbox">
                            <img  src="images/1.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/1.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 thumb">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/2.jpg" class="fancybox" rel="ligthbox">
                            <img  src="/images/2.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/1.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 thumb">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/3.jpg" class="fancybox" rel="ligthbox">
                            <img  src="/images/3.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/3.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 thumb">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/4.jpg" class="fancybox" rel="ligthbox">
                            <img  src="/images/4.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/4.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 thumb">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/5.jpg" class="fancybox" rel="ligthbox">
                            <img  src="/images/5.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/5.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="Gallery-box">
                    <figure>
                        <a href="/images/6.jpg" class="fancybox" rel="ligthbox">
                            <img  src="/images/6.jpg" class="zoom img-fluid "  alt="">
                        </a>
                        <span class="hoverle">
                     <a href="/images/6.jpg" class="fancybox" rel="ligthbox">View</a>
                     </span>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <!-- end Gallery -->
    <!--contact -->
    <div id="contact" class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Contact Us</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 paddimg-right">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <form>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <input class="form-control" placeholder="Name" type="text" name="Name">
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <input class="form-control" placeholder="Email" type="text" name="Email">
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <input class="form-control" placeholder="Phone" type="text" name="Phone">
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <textarea class="textarea>" placeholder="Message" type="text" name="Message"></textarea>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <a href="#">Read More</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <div class="map_section">
                                <figure><img src="/images/map.jpg"></figure>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 paddimg-left">
                    <div class="Nursery-img">
                        <figure>
                            <img src="/images/contactimg.jpg" alt="img"/>
                            <div class="text-box">
                                <h3>Best Green Nursery</h3>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
