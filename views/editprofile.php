<?php
if (!$is_logged_in) {
    redirect_to("/");
}

/**
 * Geeft een value attribuut terug met de data
 * @param String $type Het type
 * @return String 
 */
function add_value(String $type) {
    $value = $_SESSION["account"][$type];
    return "value=\"$value\"";
}
?>
<div class="page">
    <section class="uk-section uk-section-small">
        <div id="author-wrap" class="uk-container uk-container-small">
            <div class="uk-grid uk-grid-medium uk-flex uk-flex-middle" data-uk-grid="">
                <div class="uk-width-auto uk-first-column">
                    <form action="/php/profilepicture.php" method="post" id="profile-picture"
                        enctype="multipart/form-data" class="uk-position-relative">
                        <img class="uk-display-inline uk-border-circle profile-picture" width="80" height="80"
                            src="<?= $_SESSION["account"]["profile_picture"] ?? "https://picsum.photos/seed/{$_SESSION["account"]["username"]}/80/80/" ?>"
                            alt="">
                        <label for="upload-pfp" class="pfp-overlay text-center align-items-center">
                            <span class="ctc">
                                Click to change
                            </span>
                        </label>
                        <input onchange="window['profile-picture'].submit()" type="file" name="profilepicture"
                            class="uk-hidden" id="upload-pfp" accept="image/*">
                    </form>
                </div>
                <div class="uk-width-auto">
                    <h4 class="uk-margin-remove uk-text-bold"><?= $_SESSION["account"]["username"] ?></h4>
                    <span class="uk-text-small uk-text-muted"><?= $_SESSION["account"]["email"] ?></span>
                    <br>
                    <?= errorMsg("image") ?>
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
                            <input <?= add_value("firstname") ?> name="firstname" class="uk-input" id="firstnameI"
                                type="text">
                        </div>
                    </div>
                    <div>
                        <div class="uk-margin-medium">
                            <label class="uk-form-label" for="lastnameI">Last name </label>
                            <input <?= add_value("lastname") ?> name="lastname" class="uk-input" id="lastnameI"
                                type="text">
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
                            <input <?= add_value("email") ?> name="email" class="uk-input 
                            <?= errorClass("email") ?>" id="emailI" type="text"
                                value="<?= $_SESSION["account"]["email"] ?>">

                            <?= errorMsg("email") ?>
                        </div>

                    </div>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label" for="countryI">Country </label>
                    <select name="country" class="uk-select" id="countryI">
                        <?php
                        $json = json_decode(file_get_contents(jPath("elements/countries.json")));
                        foreach ($json as $country) :
                        ?>
                        <option value="<?= $country[0] ?>"
                            <?php if ($_SESSION["account"]["country"] == $country[0]) : ?> selected="selected"
                            <?php endif; ?>>
                            <?= $country[1] ?>
                        </option>
                        <?php endforeach; ?>

                    </select>
                </div>
                <div class="uk-margin-small">
                    <label class="uk-form-label" for="addressI">Address </label>
                    <input <?= add_value("address") ?> name="address" class="uk-input" placeholder="Street Address"
                        id="addressI" type="text">
                </div>
                <div class="uk-margin-small">
                    <label class="uk-form-label" for="addressI">Bio </label>
                    <textarea <?= add_value("bio") ?> name="bio" class="uk-textarea"
                        placeholder="Tell us a little bit about yourself" id="addressI"
                        type="text"><?= $_SESSION["account"]["bio"] ?></textarea>
                </div>
                <input type="submit" value="Update Profile" class="uk-button">
                <a type="submit" class="uk-button uk-button-danger uk-float-right" id="deleteaccountbutton">Delete
                    Account</a>
                <script>
                document.querySelector("#deleteaccountbutton").addEventListener("click", () => {
                    if (confirm("Weet je het zeker?")) location.href = "/deleteaccount";
                })
                </script>
            </form>
        </div>
    </div>
</div>