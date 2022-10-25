
<div class="container emp-profile" style="margin-top: 150px;margin-bottom: 50px!important;">
    <?php if(isset($errors)) {
        foreach ($errors as $error) {?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $error ?>
    </div>
    <?php } }?>
    <form action="<?php echo URL_ROOT.'/profile/edit'?>" method="POST" enctype="multipart/form-data" >
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="<?php if(!empty($_SESSION['prof_img']) && file_exists(IMAGES.'profile\\'.$_SESSION['prof_img'])){ echo '\images\profile\\'.$_SESSION['prof_img'];} else{ echo "\images\profile\default.jpg"; }?>" alt="img"/>
                    <div class="file btn btn-lg btn-primary">
                        Change Photo
                        <input type="file" name="image">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="profile-head">
                    <?php if(isset($_SESSION['first_name'])):?>
                    <h5 ><span><?php echo 'First Name: '.$_SESSION['first_name']?></span></h5>
                    <?php endif;?>
                    <?php if(isset($_SESSION['last_name'])):?>
                        <h5><span><?php echo 'Last Name: '. $_SESSION['last_name']?></span></h5>
                    <?php endif;?>
                    <h4 class="proile-rating" style="color: #000!important;">Email: <span><?php if(isset($_SESSION['email'])) echo $_SESSION['email']?></span></h4>
                </div>
            </div>
            <div class="col-md-2">
                <input type="submit" class="profile-edit-btn" name="submit" value="Edit Profile"/>
            </div>
        </div>
    </form>
</div>