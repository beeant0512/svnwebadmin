<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>

    <h1 class="orange">在线SVN权限查找</h1>

    <div id="body">
        <form class="ui form">
            <div class="field">
                <label>SVN账户 <a class="f-right" href="<?php echo site_url('admin') ?>">登录</a></label>
                <input type="text" name="account" placeholder="SVN账户">
            </div>
            <button class="ui button" type="submit">查找</button>
        </form>
    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong>
        seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
    </p>

    <script>
        (function () {
            $(document).ready(function () {
                $('.ui.form')
                    .form({
                        fields: {
                            account: 'empty'
                        }
                    })
            })
        })();
    </script>
<?php $this->load->view('template/footer.php'); ?>