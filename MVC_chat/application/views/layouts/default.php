<!DOCTYPE html>
<html lang="en">
<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title><?php echo $title ?></title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/new_style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="/css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="/images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="/css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <script>
        window.setTimeout(function() {
            $("#alert-success").fadeTo(500, 0).slideUp(600, function(){
                $(this).remove();
            });
        }, 4000);

        window.setTimeout(function() {
            $("#alert-danger").fadeTo(500, 0).slideUp(600, function(){
                $(this).remove();
            });
        }, 4000);
    </script>
</head>
<!-- body -->
<body class="main-layout">
<!-- loader  -->
<div class="loader_bg">
    <div class="loader"><img src="/images/loading.gif" alt="#" /></div>
</div>
<!-- end loader -->
<!-- header -->
<header>
    <!-- header inner -->
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col logo_section">
                    <div class="full">
                        <div class="center-desk">
                            <div class="logo"> <a href="/"><img src="/images/logo.png" alt="#"></a> </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
                    <div class="menu-area">
                        <div class="limit-box">
                            <nav class="main-menu">
                                <ul class="menu-area-main">
                                    <li class="active"> <a href="/">Home</a> </li>
                                    <li> <a href="#about">About</a> </li>
                                    <li><a href="#gallery">Album</a></li>
                                    <li><a href="#plant">All profile</a></li>
<!--                                    <li><a href="#contact">Contact Us</a></li>-->
                                    <?php
                                    if (isset($_SESSION['user_id'])) {?>
                                        <li><a href="/account/logout">Logout</a></li>
                                        <li><a href="/profile<?php if(isset($_SESSION['user_id']))echo'?id='.$_SESSION['user_id'] ?>" style="color: #308409;"><?php if(isset($_SESSION['first_name']))  echo $_SESSION['first_name'] ?></a></li>
                                        <div class="btn-group dropright">
                                            <button type="button" class="btn btn-secondary">
                                                <i class="fas fa-bell"></i><sup id="not_count"></sup>
                                            </button>
                                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropright</span>
                                            </button>
                                            <div id="dropdown-menu" style="display:none!important;" >
                                                <div class="dropdown-menu" >
                                                    <ul id="notification" style="overflow: auto; width:250px; height:150px;font-size: 16px">

                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    <?php }else { ?>
                                    <li><a href="/account/login">Login</a></li>
                                    <li><a href="/account/register">Register</a></li>
                                    <?php } ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end header inner -->
</header>
<?php require VIEWS.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR."alert.php";?>
<?php echo $content; ?>
    <footer>
        <div id="contact" class="footer">
            <div class="container">
                <div class="row pdn-top-30">
                    <div class="col-md-12 ">
                        <div class="footer-box">
                            <div class="headinga">
                                <h3>Address</h3>
                                <span>Demo Store .New York  United States</span>
                                <p>(+71 98765348)</p>
                            </div>
                            <div class="menu-bottom">
                                <ul class="link">
                                    <li> <a href="#">Home</a></li>
                                    <li> <a href="#">About</a></li>
                                    <li> <a href="#"> Plant</a></li>
                                    <li> <a href="#">Gallery</a></li>
                                    <li> <a href="#"> Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <p>Copyright 2019 All Rights Reserved Design By  <a href="https://html.design/">Free Html Templates</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- end footer -->
    <!-- Javascript files-->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/jquery-3.0.0.min.js"></script>
    <script src="/js/plugin.js"></script>
    <!-- sidebar -->
    <script src="/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/js/custom.js"></script>
    <!-- javascript -->
<!--    <script src="/js/owl.carousel.js"></script>-->
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });

            $(".zoom").hover(function(){

                $(this).addClass('transition');
            }, function(){

                $(this).removeClass('transition');
            });

            $('#action_menu_btn').click(function(){
                $('.action_menu').toggle();
            });

        });
        $('#message').keydown(function (e) {
            if (e.keyCode === 10 || e.keyCode  == 13 && e.ctrlKey) {
                var message = $("#message").val();
                $("#message").val(message + '\n' );
                console.log(message)
            }
        });

        window.setTimeout(function(){notification()}, 4000);
        function notification() {
            var formData = {
                'id': <?php echo $_SESSION['user_id']?>
            };
            $.ajax({
                url: "message/notification",
                type: "post",
                dataType: "JSON",
                data: formData,
                success: function(d) {
                    var length = d.length;
                    $("#not_count").text(length);
                    var li = '';
                    for (var i=0; i<d.length; i++) {
                        console.log(li);
                        li = li + '<li style="height: 50px!important;"><a style="padding: 20px;font-size: 12px!important;" href='+"message?id="+d[i]['from']+'>'+'<img src=images/profile/'+d[i]['prof_img'] +' class="img-circle" alt="Cinque Terre" style="width: 40px;height: 40px!important;"> '+d[i]['first_name']+' '+d[i]['last_name']+'</a> </li>'
                    }

                    $("#dropdown-menu").css('display','block');
                    $("#notification").html(li);
                },
                error: function(d) {
                    console.log('error');
                }
            });
        }

        function sendMessage(){
            $("#message_error").text('');
            var message = $("#message").val().trim();
            var formData = {
                'message': message,
                'id': <?php echo !empty($_GET['id'])?$_GET['id']:0?>
            };
            if(message.length > 0 ) {
                $.ajax({
                    url: "message/create",
                    type: "post",
                    dataType: "HTML",
                    data: formData,
                    success: function(d) {
                        if(JSON.parse(d).success) {
                            var img = '<img src='+"<?php if (!empty($_SESSION['prof_img']) && file_exists(IMAGES . '/profile/' . $_SESSION['prof_img'])) {echo '/images/profile/' . $_SESSION['prof_img'];} else { echo "\images\profile\default.jpg";} ?>"+' class="rounded-circle user_img_msg">';
                            var new_mess = '<div class="d-flex justify-content-start mb-4"><div class="img_cont_msg">'+ img +'</div><div class="msg_cotainer">'+message+'<span class="msg_time" id="msg_time">'+ JSON.parse(d).time +'</span></div></div>';
                            $('#msg_card_body').append($(new_mess ));
                            $("#message").val(' ');
                            console.log(d)
                        }

                    },
                    error: function(d) {
                        console.log(d);
                    }
                });
            } else {
                $("#message_error").text('Please enter a message');
                return false
            }

        };


        $(function(){
            $("#btn_message").click(function(event) {
                sendMessage();
            });
        });

        document.onkeypress = function keypressed() {
            if (window.event.keyCode == 13) {
                sendMessage();
            }
        }


    </script>
</body>
</html>