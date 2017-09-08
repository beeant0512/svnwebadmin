<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>
    <h1 class="orange"><?php echo $repo_name ?> 目录列表 <a class="f-right" href="<?php echo site_url('admin') ?>">返回</a></h1>
    <div id="body">
        <table class="ui celled table">
            <thead>
            <tr>
                <th>目录</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($repo_tree as $index => $folder) { ?>
                <tr>
                    <td>
                        <!--                        <a href="-->
                        <?php //echo $svn_server . $repo_name .'/'.  $folder ?><!--" target="_blank">-->
                        <?php echo str_replace(' ', '&nbsp;&nbsp;', $folder) ?>
                        <!--                        </a>-->
                    </td>
                    <td>
                        <button class="ui button set_authority">设置权限</button>
                        <button class="ui button view_authority">查看权限用户</button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>

    <div class="ui modal" id="create_repository_modal">
        <i class="close icon"></i>
        <div class="header">
            设置权限
        </div>
        <div class="content">
            <form class="ui large form" id="create_repository_form">
                <div class="ui stacked segment">
                    <div class="field">
                        <div class="ui left icon input">
                            <i class="cube icon"></i>
                            <input type="text" name="repo_name" placeholder="库名称">
                        </div>
                    </div>
                </div>

                <div class="ui error message"></div>
            </form>
        </div>
        <div class="actions">
            <div class="ui black deny button">
                取消
            </div>
            <div class="ui positive right labeled icon button">
                确认
                <i class="checkmark icon"></i>
            </div>
        </div>
    </div>

    <p class="footer">Page rendered in <strong>{elapsed_time}</strong>
        seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?>
    </p>

    <script>
        (function () {
            $(document).ready(function () {
                var $form = $('#create_repository_form');
                $form
                    .form({
                        fields: {
                            repo_name: 'empty'
                        }
                    });

                $('.set_authority').on('click', function () {
                    var created = false;
                    $('#create_repository_modal')
                        .modal({
                            closable: false,
                            onApprove: function () {
                                var closable = false;
                                if ($form.form('is valid')) {
                                    $.ajax({
                                        method: 'post',
                                        async: false,
                                        url: '<?php echo site_url('admin/create_repo')?>',
                                        data: {repo: $form.form('get value', 'repo_name')},
                                        success: function () {
                                            created = true;
                                            closable = true;
                                        }
                                    })
                                }
                                return closable;
                            },
                            onHide: function () {
                                if(created){
                                    window.location.reload();
                                }
                            }
                        }).modal('show');
                })
            })
        })();
    </script>
<?php $this->load->view('template/footer.php'); ?>