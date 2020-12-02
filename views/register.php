<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
    <div class="uk-width-1-1">
        <div class="uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <form method="POST" action="/php/register.php"
                        class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                        <ul class="uk-tab uk-flex-center">
                            <li class="uk-active">
                                <a href="/register">Sign Up</a>
                            </li>
                            <li>
                                <a href="/login">Log In</a>
                            </li>
                        </ul>
                        <h3 class="uk-card-title uk-text-center">Sign up today. It's free!</h3>
                        <form>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: user"></span>
                                    <input required name="username"
                                        class="uk-input uk-form-large <?= errorClass("username"); ?>" type="text"
                                        placeholder="Username">
                                    <?= errorMsg("username") ?>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                    <input required name="email"
                                        class="uk-input uk-form-large <?= errorClass("email"); ?>" type="text"
                                        placeholder="Email address">
                                    <?= errorMsg("email") ?>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                    <input required name="password"
                                        class="uk-input uk-form-large <?= errorClass("password"); ?>" type="password"
                                        placeholder="Set a password">
                                    <?= errorMsg("password") ?>

                                </div>
                            </div>
                            <div class="uk-margin uk-grid-small uk-child-width-auto uk-grid">
                                <label>
                                    <input required class="uk-checkbox" type="checkbox"> I agree the Terms and
                                    Conditions.
                                </label>
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-primary uk-button-large uk-width-1-1">Login</button>
                            </div>
                            <div class="uk-text-small uk-text-center">
                                Already have an account? <a href="/login">Log in</a>
                            </div>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>