<div class="wrapper">
    <div class="page-header page-header-small">
        <div class="page-header-image" data-parallax="true">
        </div>
        <div class="content-center">
            <div class="container">
                <h1 class="title">Dit is een geweldige CRUD app.</h1>
                <div class="text-center">
                    Gemaakt door <a href="https://samtaen.nl">Sam Taen</a> voor een informatica opdracht.
                </div>
            </div>
        </div>
    </div>
    <h1 class="uk-text-center">Publieke Users</h1>
    <div class="uk-grid uk-flex uk-flex-center">
        <div>
            <?php
            $users = $db->all_users(22);
            jsDump($users);
            foreach ($users as $user) :
                jsDump($user);
            ?>
            <a href="/u/<?= $user[3] ?>"><?= $user[0] ?> <?= $user[1] ?></a>

            <br>
            <?php endforeach; ?>
        </div>
    </div>
</div>