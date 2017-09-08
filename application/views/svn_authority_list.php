<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>
    <h1>SVN权限列表 <a class="f-right" href="<?php echo base_url('/')?>">返回</a></h1>
    <div id="body">
        <table class="ui celled table">
            <thead>
            <tr>
                <th>svn路径</th>
                <th>权限</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($authorities as $folder => $right) { ?>
                <tr>
                    <td>
                        <a href="<?php echo $svn_server . $folder ?>" target="_blank"><?php echo $svn_server . $folder ?></a>
                    </td>
                    <td><?php echo $right ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong>
        seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
    </p>
<?php $this->load->view('template/footer.php'); ?>