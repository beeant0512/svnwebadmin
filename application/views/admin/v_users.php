<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php $this->load->view('template/header.php'); ?>

    <table class="ui orange table">
        <thead>
        <tr>
            <th>用户账户</th>
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