<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>
    <div class="uk-width-1-1">
        <div class="uk-container">
            <div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
                <div class="uk-width-1-1@m">
                    <form method="POST" action="/php/login.php"
                        class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
                        <ul class="uk-tab uk-flex-center">
                            <li>
                                <a href="/register">Sign Up</a>
                            </li>
                            <li class="uk-active">
                                <a href="/login">Log In</a>
                            </li>
                        </ul>
                        <h3 class="uk-card-title uk-text-center">Welcome back!</h3>
                        <form>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: mail"></span>
                                    <input class="uk-input uk-form-large <?= errorClass("email"); ?>" type="text"
                                        name="email" placeholder="Email address">
                                    <?= errorMsg("email"); ?>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <div class="uk-inline uk-width-1-1">
                                    <span class="uk-form-icon" uk-icon="icon: lock"></span>
                                    <input class="uk-input uk-form-large <?= errorClass("password"); ?>" name="password"
                                        type="password" placeholder="Password">
                                    <?= errorMsg("password"); ?>
                                </div>
                            </div>
                            <div class="uk-margin">
                                <button class="uk-button uk-button-primary uk-button-large uk-width-1-1">Login</button>
                            </div>
                            <div class="uk-text-small uk-text-center">
                                Not registered? <a href="/register">Create an account</a>
                            </div>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>