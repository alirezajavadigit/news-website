<?php

require_once(BASE_PATH . '/template/admin/layouts/header.php');


?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h5"><i class="fas fa-newspaper"></i> Users</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
    </div>
</div>
<section class="table-responsive">
    <table class="table table-striped table-sm">
        <caption>List of users</caption>
        <thead>
            <tr>
                <th>#</th>
                <th>username</th>
                <th>email</th>
                <th>permission</th>
                <th>created at</th>
                <th>setting</th>
            </tr>
        </thead>
        <tbody>
            <?php $numberPlus = 1;
            foreach ($users as $user) { ?>
                <tr>
                    <td>
                        <?= $numberPlus ?>
                    </td>
                    <td>
                        <?= $user['username'] ?>
                    </td>
                    <td>
                        <?= $user['email'] ?>
                    </td>
                    <td>
                        <?= $user['permission'] ?>
                    </td>
                    <td>
                        <?= jalaliDate($user['created_at']) ?>
                    </td>
                    <td>

                        <?php if ($user['permission'] == "admin") { ?>
                            <a role="button" class="btn btn-sm btn-warning text-white" href="<?= url("admin/user/permission-change/" . $user['id']) ?>">click not to be admin</a>
                        <?php } else { ?>
                            <a role="button" class="btn btn-sm btn-success text-white" href="<?= url("admin/user/permission-change/" . $user['id']) ?>">click to be admin</a>
                        <?php } ?>


                        <a role="button" class="btn btn-sm btn-primary text-white" href="<?= url("admin/user/edit/" . $user['id']) ?>">edit</a>
                        <a role="button" class="btn btn-sm btn-danger text-white" href="<?= url("admin/user/delete/" . $user['id']) ?>">delete</a>
                    </td>
                </tr>
                <?php $numberPlus++;
            } ?>
        </tbody>
    </table>
</section>


<?php

require_once(BASE_PATH . '/template/admin/layouts/footer.php');


?>