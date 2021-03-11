<?php
$view_account = $db->account_from("username", $req);
$own_account = $is_logged_in && $view_account["id"] == $_SESSION["account"]["id"]
?>
<div class="page">
    <section class="uk-section uk-section-small">
        <div class="uk-container uk-container-small">
            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle uk-position-relative" data-uk-grid="">
                <div class="uk-width-auto uk-first-column">
                    <img width="80" height="80"
                        src="<?= $view_account["profile_picture"] ?? "https://picsum.photos/seed/{$view_account["username"]}/80/80/" ?>"
                        alt="" class="uk-border-circle">
                </div>
                <div class="uk-width-auto">
                    <h4 class="uk-margin-remove uk-text-bold">
                        <?= $view_account["firstname"] . " " . $view_account["lastname"]  ?>
                    </h4>
                    <a class="uk-text-small uk-text-muted" href="mailto:<?= $view_account["email"] ?>">
                        <?= $view_account["email"] ?>
                    </a>
                    <br>
                    <br>
                    <h5 class="uk-margin-remove uk-text-bold">
                        <?= $view_account["username"] ?>
                    </h5>
                    <h6 class="uk-margin-remove uk-text-bold">
                        <?= $view_account["address"] ?>
                    </h6>
                    <br>
                </div>
                <?php if ($own_account) : ?>
                <div class="uk-width-expand padding-bottom">
                    <a href="/logout" class="uk-button uk-float-right">Logout</a>
                    <a href="/profile" class="uk-button uk-float-right">Edit Profile</a>
                </div>
                <?php endif; ?>
                <!-- <div class="uk-width-auto">
                    <div class="uk-inline">
                        <a href="#" class="uk-icon-button uk-icon" data-uk-icon="icon:more-vertical"
                            aria-expanded="false"></a>
                        <div data-uk-dropdown="mode:click; pos: bottom-right; boundary:#author-wrap"
                            class="uk-dropdown">
                            <ul class="uk-nav uk-dropdown-nav">
                                <li class="uk-nav-header">Actions</li>
                                <li><a href="#">Rate this author</a></li>
                                <li><a href="#">Follow this author</a></li>
                                <li><a href="#">Bookmark</a></li>
                                <li><a href="#">View more articles</a></li>


                            </ul>
                        </div>
                    </div>
                </div> -->
                <div class="uk-position-absolute uk-position-bottom-right">
                    <h6>
                        Laatst gezien: <?= $db->last_online($view_account["username"]) ?>
                        <br>
                        Geregistreerd op: <?= $view_account["reg_date"] ?>
                    </h6>
                </div>
            </div>
            <hr>


            <div class="uk-position-relative" id="postsbio">
                <p>
                    <?= str_replace("\n", "<br>", $view_account["bio"]) ?>
                </p>
                <div class="uk-width-auto">
                    <div class="uk-inline uk-position-absolute uk-position-top-right">
                        <?php if ($own_account) : ?>
                        <a href="#" class="uk-icon-button uk-icon" data-uk-icon="icon:plus" aria-expanded="false"></a>
                        <div data-uk-dropdown="mode:click; pos: top-right; boundary:#postsbio" class="uk-dropdown">
                            <form action="/php/addpost.php" method="post">
                                <input type="text" name="title" placeholder="Titel" class="uk-input">
                                <textarea name="content" placeholder="Omschrijf je leven"
                                    class="uk-textarea"></textarea>
                                <input type="submit" value="POST!" class="uk-button uk-button-primary">
                            </form>
                        </div>
                        <?php endif; ?>
                        <a href="/u/<?= $view_account["username"] ?><?= isset($_GET["reverse"]) ? '' : '?reverse' ?>"
                            class="uk-icon-button uk-icon"
                            data-uk-icon="icon:chevron-<?= isset($_GET["reverse"]) ? 'up' : 'down' ?>"
                            aria-expanded="false"></a>

                    </div>
                </div>
                <?php
                $posts = $db->user_posts($view_account["id"]);
                if (isset($_GET["reverse"])) {
                    $posts = array_reverse($posts);
                }
                foreach ($posts as $post) :
                ?>
                <hr>
                <div class="uk-position-relative">
                    <div class="uk-position-absolute uk-position-top-right">
                        <?= $post[2] ?>
                    </div>
                    <?php if ($own_account) : ?>
                    <form action="/php/updatepost.php" method="post" class="editableform">
                        <input name="title" type="text" class="uk-input editable" value="<?= $post[0] ?>">
                        <input name="content" type="text" class="uk-input editable" value="<?= $post[1] ?>">
                        <input name="id" type="hidden" value="<?= $post[3] ?>">
                        <input type="submit" class="uk-button uk-button-primary" value="Update">
                    </form>
                    <?php else : ?>
                    <h5><?= $post[0] ?></h5>
                    <p><?= $post[1] ?></p>

                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>