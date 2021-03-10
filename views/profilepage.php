<?php
$view_account = $db->account_from("username", $req);
?>
<div class="page">
    <section class="uk-section uk-section-small">
        <div id="author-wrap" class="uk-container uk-container-small">
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
                <?php if ($is_logged_in && $view_account["id"] == $_SESSION["account"]["id"]) : ?>
                <div class="uk-width-expand">
                    <a href="/logout" class="uk-button uk-float-right">Logout</a>
                    <a href="/profile" class="uk-button uk-float-right">Edit Profile</a>
                </div>
                <?php endif; ?>
                <div class="uk-width-auto">
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
                </div>
                <div class="uk-position-absolute uk-position-bottom-right">
                    <h6>
                        Laatst gezien: <?= $db->last_online($view_account["username"]) ?>
                    </h6>
                    <h6>
                        Geregistreerd op: <?= $view_account["reg_date"] ?>
                    </h6>
                </div>
            </div>
            <hr>
            <p>
                <?= str_replace("\n", "<br>", $view_account["bio"]) ?>
            </p>

        </div>
    </section>
</div>