<?php
$view_account = account_from("username", $req);
?>
<div class="page">
    <section class="uk-section uk-section-small">
        <div id="author-wrap" class="uk-container uk-container-small">
            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle" data-uk-grid="">
                <div class="uk-width-auto uk-first-column">
                    <img src="https://unsplash.it/seed/<?= $view_account["username"] ?>/80/80/" alt=""
                        class="uk-border-circle">
                </div>
                <div class="uk-width-expand">
                    <h4 class="uk-margin-remove uk-text-bold"><?= $view_account["username"] ?></h4>
                    <span class="uk-text-small uk-text-muted"><?= $view_account["email"] ?></span>
                </div>
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
            </div>
        </div>
    </section>
    <div class="uk-container uk-container-small">
        <hr class="uk-margin-remove">
    </div>
</div>