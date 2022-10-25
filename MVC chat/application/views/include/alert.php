<?php if(isset($_SESSION['success'])) {?>
    <div class="alert alert-success" role="alert" id="alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $_SESSION['success']['mess'] ?>
    </div>
<?php  }?>
<?php if(isset($_SESSION['errors'])) {?>
    <div class="alert alert-danger" role="alert" id="alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?php echo $_SESSION['errors']['mess'] ?>
    </div>
<?php  }?>
