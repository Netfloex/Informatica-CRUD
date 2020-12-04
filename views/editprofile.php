<div class="page">
    <section class="uk-section uk-section-small">
        <div id="author-wrap" class="uk-container uk-container-small">
            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle" data-uk-grid="">
                <div class="uk-width-auto uk-first-column">
                    <img src="https://unsplash.it/seed/<?= $_SESSION["account"]["username"] ?>/80/80/" alt=""
                        class="uk-border-circle">
                </div>
                <div class="uk-width-auto">
                    <h4 class="uk-margin-remove uk-text-bold"><?= $_SESSION["account"]["username"] ?></h4>
                    <span class="uk-text-small uk-text-muted"><?= $_SESSION["account"]["email"] ?></span>
                </div>
            </div>
        </div>
    </section>
    <div class="uk-container uk-container-small">
        <hr class="uk-margin-remove">
    </div>
    <div class="uk-container uk-container-small">
        <div class="tw-contact-form uk-first-column">
            <form method="POST" action="/php/profile.php">
                <h4 class="uk-text-uppercase uk-margin-medium-bottom">Edit Profile</h4>
                <div class="uk-form-stacked tw-form-style-1 uk-grid-margin-small uk-child-width-1-2 uk-grid">

                    <div>
                        <div class="uk-margin-medium">
                            <label class="uk-form-label" for="firstnameI">First name </label>
                            <input name="firstname" class="uk-input" id="firstnameI" type="text">
                        </div>
                    </div>
                    <div>
                        <div class="uk-margin-medium">
                            <label class="uk-form-label" for="lastnameI">Last name </label>
                            <input name="lastname" class="uk-input" id="lastnameI" type="text">
                        </div>

                    </div>
                </div>

                <div class=" uk-grid-margin-small uk-child-width-1-2 uk-grid">

                    <div class="uk-first-column">
                        <div class="uk-margin-medium">
                            <label class="uk-form-label" for="usernameI">Username </label>
                            <input name="username" class="uk-input <?= errorClass("username") ?>" id="usernameI"
                                type="text" value="<?= $_SESSION["account"]["username"] ?>">
                            <?= errorMsg("username") ?>
                        </div>
                    </div>

                    <div>
                        <div class="uk-margin-medium">
                            <label class="uk-form-label" for="emailI">Email Address </label>
                            <input name="email" class="uk-input 
                            <?= errorClass("email") ?>" id="emailI" type="text"
                                value="<?= $_SESSION["account"]["email"] ?>">

                            <?= errorMsg("email") ?>
                        </div>

                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="countryI">Country </label>
                    <select name="country" class="uk-select" id="countryI">
                        <?php include jPath("elements/countryoptions.html") ?>
                    </select>
                </div>
                <div class="uk-margin-small">
                    <label class="uk-form-label" for="addressI">Address </label>
                    <input name="address" class="uk-input" placeholder="Street Address" id="addressI" type="text">
                </div>
                <div class="uk-margin-small">
                    <label class="uk-form-label" for="addressI">Bio </label>
                    <textarea name="bio" class="uk-textarea" placeholder="Tell us a little bit about yourself"
                        id="addressI" type="text"></textarea>
                </div>
                <input type="submit" value="Update Profile" class="uk-button">
            </form>
        </div>
    </div>
</div>