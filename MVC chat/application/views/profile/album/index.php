<div class="container" style="margin-top: 150px;margin-bottom: 50px!important;">
    <div class="row">
        <?php if(count($users) >0 ){
            foreach ($users as $user) {
                if($user->id == $_SESSION['user_id']){
                    continue;
                }?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                    <div class="plants-box">
                        <figure><img src="<?php if(isset($user->prof_img) && file_exists(IMAGES.'profile\\'.$user->prof_img)){ echo '\images\profile\\'.$user->prof_img;} else{ echo "\images\profile\default.jpg"; }?>" alt="img" style="height: 200px"></figure>
                        <h3><?php echo $user->first_name ?><br><?php echo $user->last_name ?></h3>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a page   when looking at its layout. The point of using Lorem Ipsumletters, as opposed to using</p>
                        <a class="btn btn-lg btn-primary" href="message<?php echo'?id='.$user->id?>" role="button" style="max-width: 200px">send a message</a>
                    </div>
                </div>
            <?php } } ?>
    </div>
</div>