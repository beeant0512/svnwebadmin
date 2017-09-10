<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>
    <h1 class="orange"><?php echo $repo_name ?> 目录列表 <a class="f-right" href="<?php echo site_url('admin') ?>">返回</a>
    </h1>
    <div id="body">
        <table class="ui celled table">
            <thead>
            <tr>
                <th>目录</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            function tr($svn_server, $repo_name, $folder)
            {
                ?>
                <tr>
                    <td>
                        <a href="
                            <?php echo $svn_server . $repo_name . $folder->fullpath ?>" target="_blank">
                            <?php echo str_replace(' ', '&nbsp;&nbsp;', $folder->name) ?>
                        </a>
                    </td>
                    <td>
                        <button class="ui button set_authority"
                                data-folder="<?php echo $repo_name . ":" . $folder->fullpath ?>">设置权限
                        </button>
                        <button class="ui button view_authority"
                                data-folder="<?php echo $repo_name . ":" . $folder->fullpath ?>">查看权限用户
                        </button>
                    </td>
                </tr>
                <?php
            } ?>
            <?php
            function iteration($svn_server, $repo_name, $folder)
            {
                if (sizeof($folder->child) == 0) {
                    tr($svn_server, $repo_name, $folder);
                } else {
                    tr($svn_server, $repo_name, $folder);
                    foreach ($folder->child as $index => $child) {
                        iteration($svn_server, $repo_name, $child);
                    }
                }
            }

            iteration($svn_server, $repo_name, $repo_tree);
            ?>

            </tbody>
        </table>
    </div>

    <div class="ui modal" id="create_repository_modal">
        <i class="close icon"></i>
        <div class="header">
            设置权限
        </div>
        <div class="content">
            <form class="ui large form" id="authority_user_form">
                <div class="field">
                    <label>SVN账户</label>
                    <select name="users" id="authority_user_select" multiple="" class="ui dropdown">
                        <option value=""> -- 请选择SVN账户 --</option>
                        <?php foreach ($users as $user) { ?>
                            <option value="<?php echo $user ?>"><?php echo $user ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="field">
                    <label>权限</label>
                    <select name="rights" id="authority_rights_select" class="ui dropdown">
                        <option value=""> -- 请选择权限 --</option>
                        <option value="-"> 不可访问</option>
                        <option value="r"> 只读</option>
                        <option value="rw"> 读写</option>
                    </select>
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
                $('select.dropdown').dropdown();
                var $form = $('#authority_user_form');
                $form
                    .form({
                        fields: {
                            repo_name: 'empty'
                        }
                    });

                $('.view_authority').on('click', function () {
                    var folder = $(this).data('folder');
                    $modal = $('#view_authority_modal');
                    $.ajax({
                        method: 'post',
                        async: false,
                        url: '<?php echo site_url('admin/get_folder_authority_users')?>',
                        data: {folder: folder},
                        success: function () {
                            closable = true;
                        }
                    });
                    $modal.modal('show');
                });

                $('.set_authority').on('click', function () {
                    var created = false,
                        folder = $(this).data('folder');
                    $modal = $('#create_repository_modal');
                    $modal.data(folder);
                    $modal
                        .modal({
                            closable: false,
                            onApprove: function () {
                                var closable = false;
                                if ($form.form('is valid')) {
                                    $.ajax({
                                        method: 'post',
                                        async: false,
                                        url: '<?php echo site_url('admin/set_user_authority')?>',
                                        data: {
                                            users: $form.form('get value', 'users'),
                                            rights: $form.form('get value', 'rights'),
                                            folder: folder
                                        },
                                        success: function () {
                                            created = true;
                                            closable = true;
                                        }
                                    })
                                }
                                return closable;
                            },
                            onHide: function () {
                                if (created) {
                                    window.location.reload();
                                }
                            }
                        }).modal('show');
                })
            })
        })();
    </script>
<?php $this->load->view('template/footer.php'); ?>