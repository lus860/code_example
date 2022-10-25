<div id="contact" class="contact" style="margin:80px auto!important;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Register</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-1 ">
                <div class="row ">
                    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8">
                        <form action="<?php echo URL_ROOT; ?>/account/register" method="POST">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control<?php  if (isset($first_name_error)) :?> is-invalid <?php endif ?>" placeholder="First Name" type="text" name="first_name" value="<?php if (isset($first_name)) echo $first_name  ?>">
                                    <?php  if (isset($first_name_error)) :?>
                                        <span class="text-danger" role="alert">
                                        <strong><?php echo $first_name_error ?></strong>
                                    </span>
                                    <?php endif ?>
                                </div>

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control<?php  if (isset($last_name_error)) :?> is-invalid <?php endif ?>" placeholder="Last Name" type="text" name="last_name" value="<?php if (isset($last_name)) echo $last_name ?>">
                                    <?php  if (isset($last_name_error)) :?>
                                        <span class="text-danger" role="alert">
                                        <strong><?php echo $last_name_error ?></strong>
                                    </span>
                                    <?php endif ?>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control<?php  if (isset($email_error)) :?> is-invalid <?php endif ?>" placeholder="Email" type="text" name="email" value="<?php  if (isset($email)) echo $email?>">
                                    <?php  if (isset($email_error)) :?>
                                        <span class="text-danger" role="alert">
                                        <strong><?php echo $email_error ?></strong>
                                    </span>
                                    <?php endif ?>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control<?php  if (isset($password_error)) :?> is-invalid <?php endif ?>" placeholder="Password" type="password" name="password">
                                    <?php  if (isset($password_error)) :?>
                                        <span class="text-danger" role="alert">
                                        <strong><?php echo $password_error ?></strong>
                                    </span>
                                    <?php endif ?>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <input class="form-control<?php  if (isset($confirm_password_error)) :?> is-invalid <?php endif ?>" placeholder="Confirm Password" type="password" name="confirm_password">
                                    <?php  if (isset($confirm_password_error)) :?>
                                        <span class="text-danger" role="alert">
                                        <strong><?php echo $confirm_password_error ?></strong>
                                    </span>
                                    <?php endif ?>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>