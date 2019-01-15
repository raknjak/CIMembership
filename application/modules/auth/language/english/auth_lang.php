<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Login
$lang['login']                          = 'Log in';
$lang['logout']                         = 'Log out';
$lang['login_username']                 = "Username";
$lang['login_identification']           = "Username%s";
$lang['login_password']                 = 'Password';
$lang['login_remember_me']              = 'Remember me';
$lang['login_incorrect']                = 'Login incorrect.';
$lang['login_disabled']                 = 'Login has been disabled.';
$lang['max_login_attempts_reached']     = 'Max login attempts hard ceiling reached. Please contact us to unlock your account.';
$lang['login_loading_text']             = 'Validating...';
$lang['login_needs_approval']           = 'Your account needs to be approved by an admin before you can log in.';

// Renew password
$lang['renew_password_title']           = 'Renew password';
$lang['renew_password_email_address']   = 'Email address';
$lang['renew_password_email_subject']   = 'Reset password requested';
$lang['renew_password_message']         = "The password reset procedure was initiated.<br>Please click the link below and receive a new password via e-mail.";
$lang['renew_password_message_txt']     = "The password reset procedure was initiated.". PHP_EOL ."Please click the link below and receive a new password via e-mail.";
$lang['renew_password_link']            = "click here to reset";
$lang['renew_password_success']         = 'You will receive an email with instructions shortly.';
$lang['renew_password_btn_send']        = 'Send password';
$lang['renew_password_subject']         = 'New password created';
$lang['renew_password_failed_db']       = 'Unable to reset password.';
$lang['renew_password_failed_token']    = 'Security token verification failed.';
$lang['renew_password_loading_text']    = 'Checking...';

// Forgot username
$lang['forgot_username_title']          = 'Retrieve username';
$lang['forgot_username_subject']        = 'Your username';
$lang['forgot_username_message']        = "Someone (probably you) requested to send this account info:<br>Your username: ";
$lang['forgot_username_message_txt']    = "Someone (probably you) requested to send this account info:". PHP_EOL ."Your username: ";
$lang['forgot_username_success']        = 'A username has been sent to your e-mail address.';
$lang['forgot_username_send']           = 'Send username';
$lang['forgot_username_loading_text']   = 'Checking...';

// Resend activation link
$lang['resend_activation_title']            = 'Resend activation link';
$lang['resend_activation_subject']          = 'Activation required - resend';
$lang['resend_activation_email_address']    = 'Email address';
$lang['resend_activation_message']          = "Someone (probably you) requested to resend your activation link.<br>To activate your account please visit the link below (or copy-paste into your browser).";
$lang['resend_activation_link']             = "click here to reactivate";
$lang['resend_activation_message_txt']      = "Someone (probably you) requested to resend your activation link.". PHP_EOL ."To activate your account please visit the link below (or copy-paste into your browser).";
$lang['resend_activation_success']          = 'Activation e-mail has been resent - please check the link in your mailbox to activate your membership.';
$lang['resend_activation_loading_text']     = 'Checking...';
$lang['resend_activation_email_send']       = 'Send activation e-mail';
$lang['resend_activation_email_active_subject'] = "Your account is activated!";
$lang['resend_activation_email_active_message'] = "Thank you for registering with us, %s.<br><br>Your account is active and you can log in <a href=\"". base_url() ."login\">here</a>.";
$lang['resend_activation_email_active_success'] = "Your account is active and you can now log in.";

// Register
$lang['register_title']                         = 'Register a free account';
$lang['register_username']                      = "Username";
$lang['register_first_name']                    = 'First name';
$lang['register_last_name']                     = 'Last name';
$lang['register_email_address']                 = 'Email address';
$lang['register_password']                      = 'Password';
$lang['register_password_confirm']              = 'Confirm password';
$lang['register_required_fields']               = '(All fields are required)';
$lang['register_email_activation_subject']      = 'Please activate your account.';
$lang['register_email_activation_message']      = "Thank you for registering with us.<br>To activate your account please visit the link below (or copy-paste into your browser)";
$lang['register_email_activation_message_txt']  = "Thank you for registering with us.". PHP_EOL ."To activate your account please visit the link below (or copy-paste into your browser)";
$lang['register_email_activation_link']         = "click here to activate";
$lang['register_email_active_subject']          = "Your account is activated!";
$lang['register_email_active_message']          = "Thank you for registering with us.<br>Your account is active and you can log in <a href=\"". base_url() ."login\">here</a>.";
$lang['register_email_active_message_txt']      = "Thank you for registering with us.". PHP_EOL ."Your account is active and you can log in here:". PHP_EOL ." ". base_url() ."login";
$lang['register_email_active_success']          = "Your account is active. Welcome!";
$lang['register_email_approve_subject']         = "Your account has been created!";
$lang['register_email_approve_message']         = "Thank you for registering with us.<br>Your account will be approved by one of our administrators. Once this has happened you can log in <a href=\"". base_url() ."login\">here</a>.";
$lang['register_email_approve_message_txt']     = "Thank you for registering with us.". PHP_EOL ."Your account will be approved by one of our administrators. Once this has happened you can log in here: ". PHP_EOL ." ". base_url() ."login";
$lang['register_email_approve_success']         = "Your account has been created and will be approved by one of our administrators.";
$lang['register_email_success']                 = 'Account created - please check the link in your mailbox to activate your membership.';
$lang['register_failed_db']                     = 'Unable to register - please try again later.';
$lang['register_req_first_name']                = 'Requirements:<br>2-40 characters.';
$lang['register_req_last_name']                 = 'Requirements:<br>2-60 characters.';
$lang['register_req_email']                     = 'Requirements:<br>please provide a valid email address.';
$lang['register_req_username']                  = 'Requirements:<br>6-24 characters;<br>the characters a-z, A-Z, 0-9, "_", "-" and "." are allowed.';
$lang['register_req_password']                  = 'Requirements:<br>9-255 characters;<br>use at least one uppercase letter;<br>use at least one non alphabet character;<br>add at least one number.';
$lang['register_req_password_2']                = 'Requirements:<br>Must be the same as chosen password.';
$lang['register_req_password_parsley']          = "Your password must contain at least (1) lowercase, (1) uppercase, (1) number and (1) special character.";
$lang['register_button_text']                   = 'Create account';
$lang['register_btn_loading_text']              = 'Registering...';
$lang['register_password_warning']              = "Please GENERATE a password that is secure for the 21st century!<br>Focus on words, not on special characters.";
$lang['registration_disabled']                  = 'Registration has been disabled.';

// Activate account
$lang['activate_account_title']         = 'Account activation';
$lang['activate_account_not_found']     = 'Account not found or already active. Please try logging in or request a new activation link.';
$lang['activate_account_error']         = "An error occurred, please try again.";
$lang['activate_account_activated']     = "Your account was activated.";

// Retrieve username
$lang['retrieve_username_title']            = "Retrieve username";
$lang['retrieve_username_email_address']    = 'Email address';

// Auth links
$lang['auth_renew']         = 'Renew password';
$lang['auth_retrieve']      = 'Retrieve username';
$lang['auth_resend']        = 'Resend activation link';

// OAuth1
$lang['oauth1_username']                    = "Username";
$lang['oauth1_add_username']                = "Finalize your account";
$lang['oauth1_invalid_session']             = "Invalid session.";
$lang['oauth1_illegal_provider_name']       = "Illegal provider name.";
$lang['oauth1_login_disabled']              = 'Social login has been disabled.';
$lang['oauth1_social_login_disabled']       = "Social login has been temporarily disabled.";
$lang['oauth1_provider']                    = 'Provider';
$lang['oauth1_illegal_provider_name']       = "Illegal provider name.";
$lang['oauth1_email_address']               = 'Email address';
$lang['oauth1_illegal_provider_init']       = "Illegal provider initiation.";
$lang['oauth1_not_active']                  = 'Account is inactive.';
$lang['oauth1_finish_account_creation']     = "Finish account creation";
$lang['oauth1_choose_username']             = "Username";
$lang['oauth1_choose_email']                = "Email address";
$lang['oauth1_finalize_loading_text']       = 'Finalizing...';
$lang['oauth1_provider_disabled']           = 'Sorry this provider has been disabled.';
$lang['oauth1_welcome_subject']             = "Membership completed.";
$lang['oauth1_welcome_msg']                 = "You have successfully subscribed to our membership system.";
$lang['oauth1_email_found']                 = "An email address was found - <strong>%s</strong> - and will be used to confirm your registration.";
$lang['oauth1_member_creation_failed']      = "Could not create social login - please try again or contact us.";
$lang['oauth1_roles_creation_failed']       = "Could not create roles for this user.";
$lang['oauth1_login_needs_approval']        = $lang['login_needs_approval'];

// Oauth2
$lang['oauth2_username']                    = $lang['oauth1_username'];
$lang['oauth2_add_username']                = $lang['oauth1_add_username'];
$lang['oauth2_login_disabled']              = $lang['oauth1_login_disabled'];
$lang['oauth2_provider']                    = $lang['oauth1_provider'];
$lang['oauth2_invalid_state']               = "Invalid state.";
$lang['oauth2_no_provider_found']           = "No provider found in DB in oauth2 method.";
$lang['oauth2_invalid_token']               = 'Invalid or expired token.';
$lang['oauth2_load_userdata_failed']        = "Could not load userdata.";
$lang['oauth2_email_address']               = $lang['oauth1_email_address'];
$lang['oauth2_email_not_returned']          = "No email was returned. For some providers making your email public will help.";
$lang['oauth2_refresh_token_failed']        = 'Unable to refresh token, please try again.';
$lang['oauth2_finish_account_creation']     = $lang['oauth1_finish_account_creation'];
$lang['oauth2_not_active']                  = $lang['oauth1_not_active'];
$lang['oauth2_illegal_provider_name']       = $lang['oauth1_illegal_provider_name'];
$lang['oauth2_illegal_provider_init']       = $lang['oauth1_illegal_provider_init'];
$lang['oauth2_social_login_disabled']       = $lang['oauth1_social_login_disabled'];
$lang['oauth2_choose_username']             = $lang['oauth1_choose_username'];
$lang['oauth2_choose_email']                = $lang['oauth1_choose_email'];
$lang['oauth2_finalize_loading_text']       = $lang['oauth1_finalize_loading_text'];
$lang['oauth2_provider_disabled']           = $lang['oauth1_provider_disabled'];
$lang['oauth2_welcome_subject']             = $lang['oauth1_welcome_subject'];
$lang['oauth2_welcome_msg']                 = $lang['oauth1_welcome_msg'];
$lang['oauth2_email_found']                 = $lang['oauth1_email_found'];
$lang['oauth2_member_creation_failed']      = $lang['oauth1_member_creation_failed'];
$lang['oauth2_roles_creation_failed']       = $lang['oauth1_roles_creation_failed'];
$lang['oauth2_login_needs_approval']        = $lang['oauth1_login_needs_approval'];

// New password
$lang['new_password_title']                 = "Create a new password";
$lang['new_password_placeholder']           = "Your new password";
$lang['new_password_field_repeat']          = "Repeat chosen password";
$lang['new_password_error_email']           = "Invalid email detected.";
$lang['new_password_error_token']           = "Invalid or missing token.";
$lang['new_password_error_db']              = "No recovery data found - please try again.";
$lang['new_password_button_text']           = "Create password";
$lang['new_password_button_loading_text']   = "Creating...";
$lang['new_password_no_flash']              = "No session data found - aborting.";
$lang['new_password_warning']               = $lang['register_password_warning'];
$lang['new_password_done']                  = "Your password was successfully changed.";
$lang['new_password_fail']                  = "Error when trying to change password.";
$lang['new_password_expired']               = "Password renewal link has expired. Please request a new password.";
$lang['new_password_password']              = "password";

// Account (mutiple usages possible)
$lang['account_is_active']                  = 'Account is not active. Please activate your account first.';
$lang['account_activate']                   = 'Please activate your account by clicking the link in the e-mail you received.';
$lang['account_active']                     = 'Your account is already active.';
$lang['account_is_banned']                  = "Your account is banned.";
$lang['account_access_denied']              = 'Access has been denied for this account.';
$lang['account_activation_link_expired']    = "your account exists but this activation link has expired. Please <a href=\"". base_url() ."auth/resend_activation\" class=\"fg-white fg-darken\">click here</a> to request a new activation link.";