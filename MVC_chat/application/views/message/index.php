<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.js"></script>
<div class="container h-100" >
    <div class="row justify-content-center h-100" >
        <div class="col-md-12 col-xl-12 chat" style="margin-bottom: 50px; margin-top: 120px!important;">
            <div class="card">
                <div class="card-header msg_head">
                    <div class="d-flex bd-highlight">
                        <div class="img_cont">
                            <img src="<?php if(!empty($_SESSION['prof_img']) && file_exists(IMAGES.'profile\\'.$_SESSION['prof_img'])){ echo '\images\profile\\'.$_SESSION['prof_img'];} else{ echo "\images\profile\default.jpg"; }?>" class="rounded-circle user_img">
                            <span class="online_icon"></span>
                        </div>
                        <div class="user_info">
                            <span>Chat with <?php echo $interlocutor['first_name']?></span>
                            <p><?php echo count($chat)?> Messages</p>
                        </div>
                    </div>
                </div>
                <div class="card-body msg_card_body" id="msg_card_body">
                    <?php foreach ($chat as $item) { ?>
                    <?php if ($item->from === $_SESSION['user_id'] && $item->to === $interlocutor['id']) { ?>
                        <div class="d-flex justify-content-start mb-4">
                            <div class="img_cont_msg">
                                <img src="<?php if (!empty($_SESSION['prof_img']) && file_exists(IMAGES . 'profile\\' . $_SESSION['prof_img'])) {
                                    echo '\images\profile\\' . $_SESSION['prof_img'];
                                } else {
                                    echo "\images\profile\default.jpg";
                                } ?>" class="rounded-circle user_img_msg">
                            </div>
                            <div class="msg_cotainer">
                                <?php echo $item->message ?>
                                <span class="msg_time"><?php echo $item->created_at ?></span>
                            </div>
                        </div>
                    <?php } elseif ($item->to === $_SESSION['user_id'] && $item->from === $interlocutor['id'] ) { ?>
                        <div class="d-flex justify-content-end mb-4">
                            <div class="msg_cotainer_send">
                                <?php echo $item->message ?>
                                <span class="msg_time_send"><?php echo $item->created_at ?></span>
                            </div>
                            <div class="img_cont_msg">
                                <img src="<?php if ($interlocutor['prof_img'] && file_exists(IMAGES . 'profile\\' . $interlocutor['prof_img'])) {
                                    echo '\images\profile\\' . $interlocutor['prof_img'];
                                } else {
                                    echo "\images\profile\default.jpg";
                                } ?>" class="rounded-circle user_img_msg">
                            </div>
                        </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                <div class="card-footer">
                    <div class="input-group">
                        <div class="input-group-append">
                            <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                        </div>
                        <textarea name="" class="form-control type_msg" id="message" placeholder="Type your message..."></textarea>
                        <div class="input-group-append">
                            <span class="input-group-text send_btn" id="btn_message"><i class="fas fa-location-arrow"></i></span>
                        </div>
                    </div>
                    <span class="text-danger" role="alert">
                              <strong id="message_error"></strong>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>