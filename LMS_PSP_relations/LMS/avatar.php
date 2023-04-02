<div class="profile_content_main_sect d-flex flex-row-reverse user-info-header">
    <div id="user">
        <ul class="useroptions arrow_box">
            <?php if ($this->company->psp_company): ?>
                <li>
                    <a href="javascript:void(0)" class="performance_btn"><?php echo __("Performance"); ?></a>
                </li>
            <?php endif; ?>
        </ul>
        <iframe style="display:none;" src="<?php echo 'https://' . $this->company->domain . '.' . APM_DOMAIN . '/getlocalstorage.html';?>" id="ifr"></iframe>
        <script type="application/javascript">
            var api_url = "<?php echo APM_API_URL; ?>";

            document.querySelector('.quick_tour_btn').addEventListener('click', function () {
                localStorage.setItem('showHints', 'yes');
                location.replace("<?php echo $this->URL()->base() ?>");
            })

            document.querySelector('.performance_btn').addEventListener('click', function () {
                const token = "<?php echo password_hash($this->company->domain . $this->user->email, PASSWORD_BCRYPT); ?>";
                const data_psp = {
                    email: "<?php echo $this->user->email; ?>",
                    token: token,
                    domain: "<?php echo $this->company->domain; ?>"
                }
                new Ajax.Request(api_url + '/api/login_lms', {
                    method: 'POST',
                    parameters: data_psp,
                    contentType: 'application/x-www-form-urlencoded',
                    onSuccess: function (transport) {
                        const token_data = transport.responseText.evalJSON();
                        if (token_data && token_data.token) {
                            postCrossDomainMessage({auth_token: token_data.token});
                            let url = "<?php echo 'https://' . $this->company->domain . '.' . APM_DOMAIN;?>"
                            window.open(url, '_blank');
                        } else {
                            jQuery('#login_message').removeClass('hide');
                            setTimeout(function () {
                                jQuery('#login_message').addClass('hide');
                            }, 10000);
                        }
                    },
                    onFailure: function (err) {
                        console.log(err);
                        jQuery('#login_message').removeClass('hide');
                        setTimeout(function () {
                            jQuery('#login_message').addClass('hide');
                        }, 10000);
                    }
                });
                document.querySelector('#login_message .link_back').addEventListener('click', function () {
                    jQuery('#login_message').addClass('hide');
                })
            })
            function postCrossDomainMessage(msg) {
                var win = document.getElementById('ifr').contentWindow;
                win.postMessage(msg, "<?php echo 'https://' . $this->company->domain . '.' . APM_DOMAIN; ?>");
            }
        </script>
    </div>
</div>
