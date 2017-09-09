<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>
    <div class="ui top attached block header" style="height:60px">
        <div class="ui right floated primary labeled icon button" id="create_user">
            <i class="plus icon"></i> 创建账号
        </div>
    </div>
    <div class="ui bottom attached segment">
        <table class="ui orange table">
            <thead>
            <tr>
                <th>账号</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $idx => $user) { ?>
                <tr>
                    <td><?php echo $user ?></td>
                    <td><a href="<?php echo site_url('/?account=' . $user) ?>">权限查看</a></td>
                </tr>
            <?php } ?>
            </tbody>
            <!--        <tfoot>-->
            <!--        <tr>-->
            <!--            <th colspan="3">-->
            <!--                <div class="ui right floated pagination menu">-->
            <!--                    <a class="icon item">-->
            <!--                        <i class="left chevron icon"></i>-->
            <!--                    </a>-->
            <!--                    <a class="item">1</a>-->
            <!--                    <a class="item">2</a>-->
            <!--                    <a class="item">3</a>-->
            <!--                    <a class="item">4</a>-->
            <!--                    <a class="icon item">-->
            <!--                        <i class="right chevron icon"></i>-->
            <!--                    </a>-->
            <!--                </div>-->
            <!--            </th>-->
            <!--        </tr>-->
            <!--        </tfoot>-->
        </table>
    </div>

    <div class="ui modal" id="create_user_modal">
        <i class="close icon"></i>
        <div class="header">
            创建账号
        </div>
        <div class="content">
            <form class="ui large form" id="create_user_form">
                <div class="ui stacked segment">
                    <div class="field">
                        <label>账号</label>
                        <div class="ui left icon input">
                            <i class="cube icon"></i>
                            <input type="text" name="account" placeholder="账号">
                        </div>
                    </div>
                    <div class="field">
                        <label>密码</label>
                        <div class="ui left icon input">
                            <i class="cube icon"></i>
                            <input type="password" name="pwd" placeholder="密码">
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
    <script>
        (function () {
            (function () {
                $(document).ready(function () {
                    var $form = $('#create_user_form');
                    $form
                        .form({
                            fields: {
                                account: 'empty',
                                pwd: 'empty'
                            }
                        });

                    $('#create_user').on('click', function () {
                        var created = false;
                        $('#create_user_modal')
                            .modal({
                                closable: false,
                                onApprove: function () {
                                    var closable = false;
                                    if ($form.form('is valid')) {
                                        $.ajax({
                                            method: 'post',
                                            async: false,
                                            url: '<?php echo site_url('admin/create_user')?>',
                                            data: $form.form('get values'),
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
        })();
    </script>
<?php $this->load->view('template/footer.php'); ?>