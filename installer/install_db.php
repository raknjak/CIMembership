<?php
error_reporting(~0);
ini_set('display_errors', 1);


$db_config_path = '../application/config/database.php';
$constants_path = '../application/config/constants.php';

@chmod($db_config_path, 0777);
@chmod($constants_path, 0777);

// Only load the classes in case the user submitted the form
if($_POST) {
//var_dump($_POST);die;
    // Load the classes and create the new objects
    require_once('./Install_Database.php');
    $database = new Install_Database();

    // Validate the post data
    if($database->validate_post($_POST) == true)
    {

        if (! $database->is_valid_username($_POST['username'])) {
            $message = $database->show_message("Invalid username.");
        }

        if (! $database->validate_email($_POST['email'])) {
            $message = $database->show_message("Invalid email address.");
        }

        if (! $database->is_valid_password($_POST['password'])) {
            $message = $database->show_message("Invalid password.");
        }

        if ($database->create_tables($_POST) == false)
        {
            $message = $database->show_message("The database tables could not be created, please verify your settings.");
        }
        else if ($database->write_db_config($_POST) == false)
        {
            $message = $database->show_message("The database configuration file could not be written, please chmod application/config/database.php file to 777");
        }

        // create directory
        if (!file_exists('../assets/img/members/'. $_POST['username'])) {
            mkdir('../assets/img/members/'. $_POST['username']);
        }/*else{
            $message = $database->show_message('Failed to create profile image directory for '. $_POST['username']);
        }*/

        // If no errors, redirect to registration page
        if(!isset($message)) {
            $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
            $redir .= "://" . $_SERVER['HTTP_HOST'];
            $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
            $redir = str_replace('installer/', '', $redir);
            header( 'Location: ' . $redir . 'login' );
        }
    }
    else {
        $message = $database->show_message('Not all fields have been filled in correctly.');
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

<h2 class="text-center">STEP 2/2: Database</h2>

<?php
if(is_writable($db_config_path)){?>

    <div class="row pd-15">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">

            <?php if(isset($message)) {echo '<p class="alert alert-danger mg-t-10">' . $message . '</p>';}?>

            <form id="install_form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                <fieldset>
                    <legend>Administrator account</legend>

                    <div class="form-group">
                        <label for="username">Admin username</label>
                        <input type="text" name="username" id="username" value="administrator" class="form-control" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="password">Admin password</label>
                        <div class="form-group pd-10 bg-info fg-white text-center f700">
                            Please GENERATE a password that is secure for the 21st century!<br>Focus on words, not on special characters.
                        </div>
                        <div class="form-group">
                            <div class="btn-group js-password-btn-group" role="group">
                                <a href="javascript:" class="btn btn-default js-genWordsButton">Generate</a>
                                <a href="javascript:" class="btn btn-default js-show-pwd">Show</a>
                            </div>
                        </div>
                        <input type="password" name="password" id="password" class="form-control" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="email">Administrator Email</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>

                </fieldset>

                <fieldset>
                    <legend>Database settings</legend>

                    <div class="form-group">
                        <label for="hostname">Hostname</label>
                        <input type="text" name="hostname" id="hostname" value="localhost" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="db_username">Database username</label>
                        <input type="text" name="db_username" id="db_username" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="db_password">Database password</label>
                        <input type="password" name="db_password" id="db_password" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="database">Database Name</label>
                        <input type="text" name="database" id="database" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="database">Database Prefix</label>
                        <span class="sml">Tip: use an underscore (_) at the end if you need a prefix</span>
                        <input type="text" name="db_prefix" id="db_prefix" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="database">Database Port</label>
                        <input type="text" name="dbport" id="dbport" value="3306" class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" id="submit" class="btn btn-primary f700"><i class="fa fa-database pd-r-5"></i> Install database</button>
                    </div>
                </fieldset>
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
<script>window.jQuery || document.write('./assets/vendor/jquery/jquery-3.2.1.min.js">\x3C/script>')</script>
<script src="../assets/vendor/toastr/toastr.min.js"></script>
<script src="../assets/vendor/diceware/components/big.min.js"></script>
<script src="../assets/vendor/diceware/lists/special-min.js"></script>
<script src="../assets/vendor/diceware/lists/diceware-min.js"></script>
<script src="../assets/vendor/diceware/lists/eff.js"></script>
<script src="../assets/vendor/diceware/password_generator.js"></script>
<script src="../assets/vendor/clipboard/clipboard.min.js"></script>
<script>

    var LANG = {
        'button_generate_txt' : 'Generate',
        'button_show_txt' : 'Show',
        'button_hide_txt' : 'Hide',
        'button_copy_txt' : 'Copy',
        'copy_to_clipboard_ok' : 'ok',
        'copy_to_clipboard_fail' : 'failed'
    };

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

</script>
</body>
</html>