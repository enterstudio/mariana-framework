<?php
/**
 * Created by PhpStorm.
 * User: fsa
 * Date: 17/01/2016
 * Time: 12:39
 */
?>
<h1 class="red-text">Login</h1>
<p>
    <small>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."</small>
</p>
<form id="login_form">
    <?php
    # Inputs Array
    $input_fields = array(
        array("id"    =>  "login_email", "placeholder"   =>  "Email Address", "type"  => "email"),
        array("id"    =>  "login_password", "placeholder"   =>  "Password", "type"  =>  "password"),
    );
    ?>
    <!-- // Autogenerate Form Fields -->
    <?php foreach ($input_fields as $input_field) {?>
        <label for="<?= $input_field["id"] ?>" style="width:100%;"> <?= $input_field["placeholder"] ?> :
            <input type="<?= $input_field["type"] ?>" name="<?= $input_field["id"] ?>" id="<?= $input_field["id"] ?>" required class="form-control"/>
        </label>
    <?php } ?>
    <div>
        <button onclick="login()" type="submit"><b>Login</b></button>
        <small ><a onclick="recovery()">Forgot your password?</a></small>
        <br>
        <small><span ><ul id="recovery_errors"></ul></span></small>
        <small>
            <span >
                <ul id="login_errors">
                    <?php if(\Mariana\Framework\Session\Flash::showMessages()){ ?>
                        <?php foreach(\Mariana\Framework\Session\Flash::showMessages() as $flash){?>
                            <p><?= $flash; ?></p>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </span>
        </small>
    </div>
</form>
