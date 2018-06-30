<?php
error_reporting(~0);
ini_set('display_errors', 1);


$config_path = '../application/config/config.php';
$constants_path = '../application/config/constants.php';

// Only load the classes in case the user submitted the form
if($_POST) {

    // Load the classes and create the new objects
    require_once('Install_Config_Worker.php');
    $configClass = new Install_Config_Worker();

    // Validate the post data
    if ($configClass->validate_post($_POST) === true)
    {
        if ($configClass->write_config($_POST) === false)
        {
            $message = $configClass->show_message("Please verify your settings.");
        }

        // If no errors, redirect to registration page
        if(!isset($message)) {
            session_start();
            $_SESSION['encryption_key'] = $_POST['encryption_key'];
            $_SESSION['cookie_domain'] = $_POST['cookie_domain'];
            $_SESSION['cookie_name'] = $_POST['cookie_name'];
            header('Location: install_db.php');
        }
    }
    else {
        $message = $configClass->show_message('Not all fields have been filled in correctly.');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CIMembership installer</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container-fluid">
    <h1 class="text-center">CIMembership Installer</h1>

    <h2 class="fg-danger text-center">The installer is in beta - please report any issues.</h2>

    <div class="row pd-15">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>PHP version</th>
                        <th>mysqli enabled?</th>
                        <th>cURL enabled?</th>
                        <th>finfo_file enabled?</th>
                        <th>OpenSSL</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="f700">
                        <td><?php print phpversion() . " = " . (version_compare(substr(phpversion(), 0, 3), '5.5', '>=') == true ? "ok" : "nok"); ?></td>
                        <td><?php print (function_exists('mysqli_connect') == true ? "ok" : "nok"); ?></td>
                        <td><?php print (function_exists('curl_version') == true ? "ok" : "nok"); ?></td>
                        <td><?php print (function_exists('finfo_file') == true ? "ok" : "nok"); ?></td>
                        <td><?php print (extension_loaded('openssl') == true ? "ok" : "nok"); ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <h2 class="text-center">STEP 1/2: config &amp; constants</h2>

    <?php

    if ((!version_compare(substr(phpversion(), 0, 3), '5.6', '>=')) ||
        !function_exists('mysqli_connect') ||
        !function_exists('curl_version') ||
        !function_exists('finfo_file') ||
        !extension_loaded('openssl')
    ) {
        print '<h2 class="text-center fg-danger">Please fix the requirements first.</h2>';
    }elseif (is_writable($config_path) && is_writable($constants_path)) {
    ?>

        <div class="row">

            <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">

                <?php if(isset($message)) {echo '<p class="alert alert-danger mg-t-10">' . $message . '</p>';} ?>

                <form id="install_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <fieldset>
                        <legend>A. config.php</legend>

                        <div class="form-group">
                            <label for="encryption_key">Encryption Key *</label>
                            <p class="small">A 32-character key for 2-way encryption. It is advised to use the generate button for a very secure key.</p>
                            <div class="input-group">
                                <input type="text" name="encryption_key" id="encryption_key" class="form-control">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick='document.getElementById("encryption_key").value = Password.generate(32)'>Generate</button>
                            </span>
                            </div><!-- /input-group -->
                        </div>

                        <div class="form-group">
                            <label for="cookie_name">Cookie Name *</label>
                            <p class="small">
                                The session cookie name, must contain only [0-9a-z_-] characters.<br>
                                Doesn't need to be complex, for example I use "cimembershipsessionv3".
                            </p>
                            <input type="text" name="cookie_name" id="cookie_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="cookie_domain">Cookie domain (optional)</label>
                            <p class="small">Start with a dot, for example ".cimembership.io". Not required on localhost.</p>
                            <input type="text" name="cookie_domain" id="cookie_domain" class="form-control">
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend>B. constants.php</legend>
                        <div class="form-group">
                            <label for="site_key">Site Key *</label>
                            <p class="small">A 64-character site key for creating secure tokens. It is advised to use the generate button for a very secure key.</p>
                            <div class="input-group">
                                <input type="text" name="site_key" id="site_key" class="form-control">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick='document.getElementById("site_key").value = Password.generate(64)'>Generate</button>
                            </span>
                            </div><!-- /input-group -->
                        </div>
                    </fieldset>

                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-primary f700"><i class="fa fa-check pd-r-5"></i> Confirm and go to Step 2</button>
                    </div>

                </form>
            </div>
        </div>


    <?php } else { ?>
        <p class="error">
            It seems something did not work, please contact support.<br>
        </p>
    <?php } ?>

</div>

<script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="./assets/js/vendor/jquery-3.2.1.min.js">\x3C/script>')</script>
<script>

    !function ($) {
        $(function() {
            // let's show our hidden body after everything is done
            var $pageContainer = $('body');
            $pageContainer.css('display', 'none');
            $pageContainer.css('visibility', 'inherit');
            $pageContainer.fadeIn(400);
            $pageContainer.removeClass("preload");
        });
    }(window.jQuery);

    var Password = {

        _pattern : /[a-zA-Z0-9_\-+#%&()\[\]!@?.]/,


        _getRandomByte : function()
        {
            // http://caniuse.com/#feat=getrandomvalues
            if(window.crypto && window.crypto.getRandomValues)
            {
                var result = new Uint8Array(1);
                window.crypto.getRandomValues(result);
                return result[0];
            }
            else if(window.msCrypto && window.msCrypto.getRandomValues)
            {
                var result = new Uint8Array(1);
                window.msCrypto.getRandomValues(result);
                return result[0];
            }
            else
            {
                return Math.floor(Math.random() * 256);
            }
        },

        generate : function(length)
        {
            return Array.apply(null, {'length': length})
                .map(function()
                {
                    var result;
                    while(true)
                    {
                        result = String.fromCharCode(this._getRandomByte());
                        if(this._pattern.test(result))
                        {
                            return result;
                        }
                    }
                }, this)
                .join('');
        }

    };



</script>
</body>
</html>