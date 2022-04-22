<?php
global $message_global;

$message_global['generic_error'] = "Something went wrong.";
icl_register_string('api-msg', 'generic-error', $message_global['generic_error']);

// CHECK INTRODUCER CODE MESSAGES TRANSLATION
$message_global['checkintroducercode']['email_not_provided'] =  "Please provide email address.";
$message_global['checkintroducercode']['client_name_not_provided'] =  "Please provide client name.";
$message_global['checkintroducercode']['introducercode_not_provided'] = "Please provide introducer code.";
$message_global['checkintroducercode']['email_not_found'] =  "No user found with given email address.";
$message_global['checkintroducercode']['account_already_activated'] = "You have already activated your account. Please login using login credentials.";
$message_global['checkintroducercode']['verification_success'] = "User found with given email address and introducer code.";
$message_global['checkintroducercode']['verification_failure'] = "The Introducer Code or email address you entered does not match our records. Please double check and try again";

icl_register_string('checkintroducercode-msg', 'email-not-provided', $message_global['checkintroducercode']['email_not_provided']);
icl_register_string('checkintroducercode-msg', 'client-name-not-provided', $message_global['checkintroducercode']['client_name_not_provided']);
icl_register_string('checkintroducercode-msg', 'introducercode-not-provided', $message_global['checkintroducercode']['introducercode_not_provided']);
icl_register_string('checkintroducercode-msg', 'email-not-found', $message_global['checkintroducercode']['email_not_found']);
icl_register_string('checkintroducercode-msg', 'account-already-activated', $message_global['checkintroducercode']['account_already_activated']);
icl_register_string('checkintroducercode-msg', 'verification-success', $message_global['checkintroducercode']['verification_success']);
icl_register_string('checkintroducercode-msg', 'verification-failure', $message_global['checkintroducercode']['verification_failure']);
// CHECK INTRODUCER CODE MESSAGES TRANSLATION

// NEW PASSWORD SETUP MESSAGES TRANSLATION
$message_global['newpasswordsetup']['password_not_provided'] =  "Please provide password.";
$message_global['newpasswordsetup']['confirmpassword_not_provided'] = "Please provide confirm password.";
$message_global['newpasswordsetup']['password_not_matching'] = "Password and confirm password are not matching.";
$message_global['newpasswordsetup']['success'] = "Password updated successfully.";
$message_global['newpasswordsetup']['user_not_found'] = "User not found.";
icl_register_string('newpasswordsetup-msg', 'password-not-provided', $message_global['newpasswordsetup']['password_not_provided']);
icl_register_string('newpasswordsetup-msg', 'confirmpassword-not-provided', $message_global['newpasswordsetup']['confirmpassword_not_provided']);
icl_register_string('newpasswordsetup-msg', 'password-not-matching', $message_global['newpasswordsetup']['password_not_matching']);
icl_register_string('newpasswordsetup-msg', 'success', $message_global['newpasswordsetup']['success']);
icl_register_string('newpasswordsetup-msg', 'user-not-found', $message_global['newpasswordsetup']['user_not_found']);
// NEW PASSWORD SETUP MESSAGES TRANSLATION

// LOGIN MESSAGES TRANSLATION
$message_global['login']['email_not_provided'] = "Please provide email address.";
$message_global['login']['password_not_provided'] = "Please provide password.";
$message_global['login']['incorrect_password'] = "Password is incorrect.";
$message_global['login']['email_not_found'] = "No user found with given email address.";
$message_global['login']['user_not_verified'] = "Please activate your account to login.";
$message_global['login']['user_login'] = "You have successfully logged in.";
icl_register_string('login-msg', 'email-not-provided', $message_global['login']['email_not_provided']);
icl_register_string('login-msg', 'password-not-provided', $message_global['login']['password_not_provided']);
icl_register_string('login-msg', 'incorrect-password', $message_global['login']['incorrect_password']);
icl_register_string('login-msg', 'email-not-found', $message_global['login']['email_not_found']);
icl_register_string('login-msg', 'user-not-verified', $message_global['login']['user_not_verified']);
icl_register_string('login-msg', 'user-login', $message_global['login']['user_login']);
// LOGIN MESSAGES TRANSLATION

// CHECK INTRODUCER MESSAGES TRANSLATION
$message_global['checkintroducerforgot']['introducercode_not_provided'] = "Please provide introducer code.";
$message_global['checkintroducerforgot']['introducer_code_invalid'] = "Invalid introducer code.";
$message_global['checkintroducerforgot']['emai_sent_success'] = "Please check your email for the confirmation code.";
$message_global['checkintroducerforgot']['user_not_verified'] = "Your account is not activate.";
icl_register_string('checkintroducerforgot-msg', 'introducercode-not-provided', $message_global['checkintroducerforgot']['introducercode_not_provided']);
icl_register_string('checkintroducerforgot-msg', 'introducer-code-invalid', $message_global['checkintroducerforgot']['introducer_code_invalid']);
icl_register_string('checkintroducerforgot-msg', 'emai-sent-success', $message_global['checkintroducerforgot']['emai_sent_success']);
icl_register_string('checkintroducerforgot-msg', 'user-not-verified', $message_global['checkintroducerforgot']['user_not_verified']);
// CHECK INTRODUCER MESSAGES TRANSLATION

// RESEND INTRODUCER MESSAGES TRANSLATION
$message_global['resendconfirmcode']['user_id_not_provided'] = "Please provide user id.";
$message_global['resendconfirmcode']['user_not_found'] = "User not found.";
$message_global['resendconfirmcode']['emai_sent_success'] = "Please check your email for the confirmation code.";
$message_global['resendconfirmcode']['request_for_code'] = "Please request for a new confirmation code.";
$message_global['resendconfirmcode']['confirmation_code_time_limit'] =  "Cofirmation code time is expired. Please request for a new confirmation code.";
icl_register_string('resendconfirmcode-msg', 'confirmation-code-time-limit', $message_global['resendconfirmcode']['confirmation_code_time_limit']);
icl_register_string('resendconfirmcode-msg', 'user-id-not-provided', $message_global['resendconfirmcode']['user_id_not_provided']);
icl_register_string('resendconfirmcode-msg', 'user-not-found', $message_global['resendconfirmcode']['user_not_found']);
icl_register_string('resendconfirmcode-msg', 'emai-sent-success', $message_global['resendconfirmcode']['emai_sent_success']);
icl_register_string('resendconfirmcode-msg', 'request-for-code', $message_global['resendconfirmcode']['request_for_code']);
// RESEND INTRODUCER MESSAGES TRANSLATION

// CONFIRM CODE MESSAGES TRANSLATION
$message_global['confirmcode']['confirmation_code_not_provided'] =  "Please provide confirmation code sent to your email address.";
$message_global['confirmcode']['invalid_confirmation_code'] =  "Cofirmation code you have entered is wrong.";
$message_global['confirmcode']['confirmation_code_valid'] =  "Cofirmation code is verified.";
$message_global['confirmcode']['confirmation_code_time_limit'] =  "Cofirmation code time is expired. Please request for a new confirmation code.";
icl_register_string('confirmcode-msg', 'confirmation-code-not-provided', $message_global['confirmcode']['confirmation_code_not_provided']);
icl_register_string('confirmcode-msg', 'confirmation-code-not-valid', $message_global['confirmcode']['invalid_confirmation_code']);
icl_register_string('confirmcode-msg', 'confirmation-code-valid', $message_global['confirmcode']['confirmation_code_valid']);
icl_register_string('confirmcode-msg', 'confirmation-code-time-limit', $message_global['confirmcode']['confirmation_code_time_limit']);
// CONFIRM CODE MESSAGES TRANSLATION

// FORGOT PASSWORD MESSAGES TRANSLATION
$message_global['forgotPassword']['new_password_not_provided'] = "Please provide new password.";
$message_global['forgotPassword']['confirmpassword_not_provided'] = "Please provide confirm password.";
$message_global['forgotPassword']['password_not_matching'] = "Password and confirm password are not matching.";
$message_global['forgotPassword']['password_change_success'] = "Password updated successfully.";
$message_global['forgotPassword']['user_not_found'] = "User not found.";

icl_register_string('forgotpassword-msg', 'new-password-not-provided', $message_global['forgotPassword']['new_password_not_provided']);
icl_register_string('forgotpassword-msg', 'confirmpassword-not-provided', $message_global['forgotPassword']['confirmpassword_not_provided']);
icl_register_string('forgotpassword-msg', 'password-not-matching', $message_global['forgotPassword']['password_not_matching']);
icl_register_string('forgotpassword-msg', 'password-change-success', $message_global['forgotPassword']['password_change_success']);
icl_register_string('forgotpassword-msg', 'user-not-found', $message_global['forgotPassword']['user_not_found']);
// FORGOT PASSWORD MESSAGES TRANSLATION

// SIGNOUT MESSAGES TRANSLATION
$message_global['signout']['user_logout'] = "Logged out successfully.";
$message_global['signout']['user_id_not_provided'] = "Please provide user id.";
$message_global['signout']['device_token_not_provided'] = "Please provide device token.";
icl_register_string('signout-msg', 'user-logout', $message_global['signout']['user_logout']);
icl_register_string('signout-msg', 'user-id-not-provided', $message_global['signout']['user_id_not_provided']);
icl_register_string('signout-msg', 'device-token-not-provided', $message_global['signout']['device_token_not_provided']);
// SIGNOUT MESSAGES TRANSLATION

/*$message_global['auth']['confirmation_subject'] = " is your ITA Connect recovery code";
icl_register_string('auth-msg', 'email-subject', $message_global['auth']['confirmation_subject']);
$message_global['auth']['email_content_line_one'] = "Hi";
$message_global['auth']['email_content_line_two'] = 'To ensure your account’s security, we need to verify your identity. Enter the code below into the app where prompted.';
$message_global['auth']['email_content_line_three'] = 'Recovery Code: ';
$message_global['auth']['email_content_line_four'] = 'Sincerely, ';
$message_global['auth']['email_content_line_five'] = 'ITA Connect App Team';
$message_global['auth']['email_content_automated'] = 'This e-mail was auto generated. Please do not respond.';
$message_global['auth']['email_content_line_six_part_one'] = '* This message was sent to';
$message_global['auth']['email_content_line_six_part_two'] = ' and intended for ';
$message_global['auth']['email_content_line_six_part_three'] = 'This message contains confidential information solely for the use of the intended recipient. If the reader of this message is not the intended recipient, the reader is notified that any use, dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please delete it immediately and notify Investors Trust by sending an email to the ITA Connect App Team at appteam@investors-trust.com.';
icl_register_string('auth-msg', 'email-content-line-one', $message_global['auth']['email_content_line_one']);
icl_register_string('auth-msg', 'email-content-line-two', $message_global['auth']['email_content_line_two']);
icl_register_string('auth-msg', 'email-content-line-three', $message_global['auth']['email_content_line_three']);
icl_register_string('auth-msg', 'email-content-line-four', $message_global['auth']['email_content_line_four']);
icl_register_string('auth-msg', 'email-content-line-five', $message_global['auth']['email_content_line_five']);
icl_register_string('auth-msg', 'email-content-automated', $message_global['auth']['email_content_automated']);
icl_register_string('auth-msg', 'email-content-line-six-part-one', $message_global['auth']['email_content_line_six_part_one']);
icl_register_string('auth-msg', 'email-content-line-six-part-two', $message_global['auth']['email_content_line_six_part_two']);
icl_register_string('auth-msg', 'email-content-line-six-part-three', $message_global['auth']['email_content_line_six_part_three']);*/

// CHANGE LANGUAGE MESSAGES TRANSLATION
$message_global['changelanguage']['user_not_found'] = "User does not exsist.";
$message_global['changelanguage']['language_code_not_provided'] = "Please provide language code.";
$message_global['changelanguage']['invalid_language_code_provided'] = "Please provide valid language code.";
$message_global['changelanguage']['success'] = "language changed successfully.";
icl_register_string('changelanguage-msg', 'user-not-found', $message_global['changelanguage']['user_not_found']);
icl_register_string('changelanguage-msg', 'language-code-not-provided', $message_global['changelanguage']['language_code_not_provided']);
icl_register_string('changelanguage-msg', 'invalid-language-code-provided', $message_global['changelanguage']['invalid_language_code_provided']);
icl_register_string('changelanguage-msg', 'success', $message_global['changelanguage']['success']);
// CHANGE LANGUAGE MESSAGES TRANSLATION

// CHANGE PASSWORD MESSAGES TRANSLATION
$message_global['changepassword']['incorrect_password'] = "Old password is incorrect.";
$message_global['changepassword']['new_password_not_provided'] = "Please provide new password.";
$message_global['changepassword']['success'] =  "Password updated successfully.";
$message_global['changepassword']['user_not_found'] =  "User not found.";
$message_global['changepassword']['old_password_not_provided'] = "Please provide old password.";
$message_global['changepassword']['same_password'] = "Old and new password should be different.";
icl_register_string('changepassword-msg', 'incorrect-password', $message_global['changepassword']['incorrect_password']);
icl_register_string('changepassword-msg', 'new-password-not-provided', $message_global['changepassword']['new_password_not_provided']);
icl_register_string('changepassword-msg', 'success', $message_global['changepassword']['success']);
icl_register_string('changepassword-msg', 'user-not-found', $message_global['changepassword']['user_not_found']);
icl_register_string('changepassword-msg', 'old-password-not-provided', $message_global['changepassword']['old_password_not_provided']);
icl_register_string('changepassword-msg', 'same-password', $message_global['changepassword']['same_password']);
// CHANGE PASSWORD MESSAGES TRANSLATION

// UPDATE NOTIFICATION MESSAGES TRANSLATION
$message_global['updatenotification']['user_not_found'] =  "User not found.";
$message_global['updatenotification']['news_status_not_provided'] = "Please provide news status.";
$message_global['updatenotification']['library_status_not_provided'] = "Please provide library status.";
$message_global['updatenotification']['push_notification_success'] = "Push notification settings saved successfully.";
icl_register_string('updatenotification-msg', 'user-not-found', $message_global['updatenotification']['user_not_found']);
icl_register_string('updatenotification-msg', 'news-status-not-provided', $message_global['updatenotification']['news_status_not_provided']);
icl_register_string('updatenotification-msg', 'library-status-not-provided', $message_global['updatenotification']['library_status_not_provided']);
icl_register_string('updatenotification-msg', 'push-notification-success', $message_global['updatenotification']['push_notification_success']);
// UPDATE NOTIFICATION MESSAGES TRANSLATION

// UPDATE NIGHT MODE MESSAGES TRANSLATION
$message_global['updatenightmode']['status_not_provided'] = "Please provide status.";
$message_global['updatenightmode']['night_mode_turned_on'] = "Night mode turned on successfully.";
$message_global['updatenightmode']['night_mode_turned_off'] = "Night mode turned off successfully.";
$message_global['updatenightmode']['user_not_found'] =  "User not found.";
icl_register_string('updatenightmode-msg', 'user-not-found', $message_global['updatenightmode']['user_not_found']);
icl_register_string('updatenightmode-msg', 'status-not-provided', $message_global['updatenightmode']['status_not_provided']);
icl_register_string('updatenightmode-msg', 'night-mode-turned-on', $message_global['updatenightmode']['night_mode_turned_on']);
icl_register_string('updatenightmode-msg', 'night-mode-turned-off', $message_global['updatenightmode']['night_mode_turned_off']);
// UPDATE NIGHT MODE MESSAGES TRANSLATION

// SET DEVICE TOKEN MESSAGES TRANSLATION
$message_global['setdevicetoken']['user_id_not_provided'] = "Please provide user id.";
$message_global['setdevicetoken']['device_token_not_provided'] = "Please provide device token.";
$message_global['setdevicetoken']['os_type_not_provided'] = "Please provide OS type.";
$message_global['setdevicetoken']['success'] = "Device token updated successfully.";
icl_register_string('setdevicetoken-msg', 'user-id-not-provided', $message_global['setdevicetoken']['user_id_not_provided']);
icl_register_string('setdevicetoken-msg', 'device-token-not-provided', $message_global['setdevicetoken']['device_token_not_provided']);
icl_register_string('setdevicetoken-msg', 'os-type-not-provided', $message_global['setdevicetoken']['os_type_not_provided']);
icl_register_string('setdevicetoken-msg', 'success', $message_global['setdevicetoken']['success']);
// SET DEVICE TOKEN MESSAGES TRANSLATION

// ADD REMOVE FAVORITE MESSAGES TRANSLATION
$message_global['addremovebookmark']['user_id_not_provided'] = "Please provide user id.";
$message_global['addremovebookmark']['post_id_not_provided'] = "Please provide post id.";
$message_global['addremovebookmark']['added_success'] = "Your post added to bookmarks successfully.";
$message_global['addremovebookmark']['remove_success'] = "Your post removed from bookmarks successfully.";
$message_global['addremovebookmark']['user_not_exsist'] =  "User does not exsist.";
$message_global['addremovebookmark']['post_not_exsist'] =  "Post does not exsist.";
$message_global['addremovebookmark']['can_not_be_added'] = "This post can not be added to bookmark posts.";
$message_global['addremovebookmark']['post_status_error'] = "This post can not be added to bookmark posts. Post status is not published.";
icl_register_string('addremovebookmark-msg', 'user-id-not-provided', $message_global['addremovebookmark']['user_id_not_provided']);
icl_register_string('addremovebookmark-msg', 'post-id-not-provided', $message_global['addremovebookmark']['post_id_not_provided']);
icl_register_string('addremovebookmark-msg', 'added-success', $message_global['addremovebookmark']['added_success']);
icl_register_string('addremovebookmark-msg', 'remove-success', $message_global['addremovebookmark']['remove_success']);
icl_register_string('addremovebookmark-msg', 'user-not-exsist', $message_global['addremovebookmark']['user_not_exsist']);
icl_register_string('addremovebookmark-msg', 'post-not-exsist', $message_global['addremovebookmark']['post_not_exsist']);
icl_register_string('addremovebookmark-msg', 'can-not-be-added', $message_global['addremovebookmark']['can_not_be_added']);
icl_register_string('addremovebookmark-msg', 'post-status-error', $message_global['addremovebookmark']['post_status_error']);
// ADD REMOVE FAVORITE MESSAGES TRANSLATION

// DELETE RECENT MESSAGES TRANSLATION
$message_global['deleterecent']['user_id_not_provided'] = "Please provide user id.";
$message_global['deleterecent']['post_id_not_provided'] = "Please provide post id.";
$message_global['deleterecent']['delete_success'] = "Your posts deleted from recent successfully.";
$message_global['deleterecent']['user_not_exsist'] =  "User does not exsist.";
icl_register_string('deleterecent-msg', 'user-id-not-provided', $message_global['deleterecent']['user_id_not_provided']);
icl_register_string('deleterecent-msg', 'post-id-not-provided', $message_global['deleterecent']['post_id_not_provided']);
icl_register_string('deleterecent-msg', 'delete-success', $message_global['deleterecent']['delete_success']);
icl_register_string('deleterecent-msg', 'user-not-exsist', $message_global['deleterecent']['user_not_exsist']);
// DELETE RECENT MESSAGES TRANSLATION

// REORDER FAVORITE MESSAGES TRANSLATION
$message_global['reorderfavorite']['user_id_not_provided'] = "Please provide user id.";
$message_global['reorderfavorite']['user_not_exsist'] =  "User does not exsist.";
$message_global['reorderfavorite']['post_id_not_provided'] = "Please provide post id.";
$message_global['reorderfavorite']['reorder_success'] = "Your post reorder successfully.";
icl_register_string('reorderfavorite-msg', 'user-id-not-provided', $message_global['reorderfavorite']['user_id_not_provided']);
icl_register_string('reorderfavorite-msg', 'user-not-exsist', $message_global['reorderfavorite']['user_not_exsist']);
icl_register_string('reorderfavorite-msg', 'post-id-not-provided', $message_global['reorderfavorite']['post_id_not_provided']);
icl_register_string('reorderfavorite-msg', 'reorder-success', $message_global['reorderfavorite']['reorder_success']);
// REORDER FAVORITE MESSAGES TRANSLATION

// SET RECENT FLYER MESSAGES TRANSLATION
$message_global['setrecentflyer']['user_id_not_provided'] = "Please provide user id.";
$message_global['setrecentflyer']['user_not_exsist'] = "User does not exsist.";
$message_global['setrecentflyer']['post_not_exsist'] =  "Post does not exsist.";
$message_global['setrecentflyer']['post_id_not_provided'] = "Please provide post id.";
$message_global['setrecentflyer']['can_not_be_added'] = "This post can not be added to recent posts.";
$message_global['setrecentflyer']['post_status_error'] = "This post can not be added to recent posts. Post status is not published.";
$message_global['setrecentflyer']['added_success'] = "Post added to recents successfully.";
icl_register_string('setrecentflyer-msg', 'user-id-not-provided', $message_global['setrecentflyer']['user_id_not_provided']);
icl_register_string('setrecentflyer-msg', 'user-not-exsist', $message_global['setrecentflyer']['user_not_exsist']);
icl_register_string('setrecentflyer-msg', 'post-not-exsist', $message_global['setrecentflyer']['post_not_exsist']);
icl_register_string('setrecentflyer-msg', 'post-id-not-provided', $message_global['setrecentflyer']['post_id_not_provided']);
icl_register_string('setrecentflyer-msg', 'can-not-be-added', $message_global['setrecentflyer']['can_not_be_added']);
icl_register_string('setrecentflyer-msg', 'post-status-error', $message_global['setrecentflyer']['post_status_error']);
icl_register_string('setrecentflyer-msg', 'added-success', $message_global['setrecentflyer']['added_success']);
// SET RECENT FLYER MESSAGES TRANSLATION

// GET FAVORITE MESSAGES TRANSLATION
$message_global['getbookmark']['success'] = "Bookmarks listed successfully.";
$message_global['getbookmark']['user_id_not_provided'] = "Please provide user id.";
$message_global['getbookmark']['user_not_exsist'] =  "User does not exsist.";
$message_global['getbookmark']['no_data'] = "No bookmarks found.";
$message_global['getbookmark']['no_more_data'] = "No more data to load.";
icl_register_string('getbookmark-msg', 'success', $message_global['getbookmark']['success']);
icl_register_string('getbookmark-msg', 'user-id-not-provided', $message_global['getbookmark']['user_id_not_provided']);
icl_register_string('getbookmark-msg', 'user-not-exsist', $message_global['getbookmark']['user_not_exsist']);
icl_register_string('getbookmark-msg', 'no-data', $message_global['getbookmark']['no_data']);
icl_register_string('getbookmark-msg', 'no-more-data', $message_global['getbookmark']['no_more_data']);
// GET FAVORITE MESSAGES TRANSLATION

// GET RECENTS MESSAGES TRANSLATION
$message_global['getrecent']['user_id_not_provided'] = "Please provide user id.";
$message_global['getrecent']['user_not_exsist'] = "User does not exsist.";
$message_global['getrecent']['no_data'] = "No recents found.";
$message_global['getrecent']['success'] = "Recents loaded successfully.";
icl_register_string('getrecent-msg', 'user-id-not-provided', $message_global['getrecent']['user_id_not_provided']);
icl_register_string('getrecent-msg', 'user-not-exsist', $message_global['getrecent']['user_not_exsist']);
icl_register_string('getrecent-msg', 'no-data', $message_global['getrecent']['no_data']);
icl_register_string('getrecent-msg', 'success', $message_global['getrecent']['success']);
// GET RECENTS MESSAGES TRANSLATION

// DISCOVER LISTING MESSAGES TRANSLATION
$message_global['getdiscoverlist']['show_option_disabled'] = "All display options are disabled.";
$message_global['getdiscoverlist']['no_data_found'] = 'No posts found.';
$message_global['getdiscoverlist']['survey_end_text'] = "This survey will end on ";
$message_global['getdiscoverlist']['poll_end_text'] = "This poll will end on ";
$message_global['getdiscoverlist']['success'] = "Posts listed successfully.";
icl_register_string('getdiscoverlist-msg', 'options-disabled', $message_global['getdiscoverlist']['show_option_disabled']);
icl_register_string('getdiscoverlist-msg', 'no-data', $message_global['getdiscoverlist']['no_data_found']);
icl_register_string('getdiscoverlist-msg', 'survey-end-text', $message_global['getdiscoverlist']['survey_end_text']);
icl_register_string('getdiscoverlist-msg', 'poll-end-text', $message_global['getdiscoverlist']['poll_end_text']);
icl_register_string('getdiscoverlist-msg', 'success', $message_global['getdiscoverlist']['success']);
// DISCOVER LISTING MESSAGES TRANSLATION

// DISCOVER DETAIL MESSAGES TRANSLATION
$message_global['getdiscoverdetail']['post_id_not_provided'] = "Please provide post id.";
$message_global['getdiscoverdetail']['post_not_found'] = "Post does not exsist.";
$message_global['getdiscoverdetail']['discover_detail_success'] = "Discover post is successfully generated.";
icl_register_string('getdiscoverdetail-msg', 'post-id-not-provided', $message_global['getdiscoverdetail']['post_id_not_provided']);
icl_register_string('getdiscoverdetail-msg', 'post-not-found', $message_global['getdiscoverdetail']['post_not_found']);
icl_register_string('getdiscoverdetail-msg', 'discover-detail-success', $message_global['getdiscoverdetail']['discover_detail_success']);
// DISCOVER DETAIL MESSAGES TRANSLATION

// MEDIA CENTER LISTING MESSAGES TRANSLATION
$message_global['getmediacenterlist']['no_data_found'] = 'No posts found.';
$message_global['getmediacenterlist']['success'] = "Posts listed successfully.";
icl_register_string('getmediacenterlist-msg', 'no-data', $message_global['getmediacenterlist']['no_data_found']);
icl_register_string('getmediacenterlist-msg', 'success', $message_global['getmediacenterlist']['success']);
// MEDIA CENTER LISTING MESSAGES TRANSLATION

// TIMELINE LISTING MESSAGES TRANSLATION
$message_global['gettimelinelist']['no_data_found'] = 'No timeline found.';
$message_global['gettimelinelist']['success'] = "Timeline listed successfully.";
icl_register_string('gettimelinelist-msg', 'no-data', $message_global['gettimelinelist']['no_data_found']);
icl_register_string('gettimelinelist-msg', 'success', $message_global['gettimelinelist']['success']);
// TIMELINE LISTING MESSAGES TRANSLATION

// TIMELINE DETAIL MESSAGES TRANSLATION
$message_global['gettimelinedetail']['post_id_not_provided'] = "Please provide post id.";
$message_global['gettimelinedetail']['timeline_not_found'] = "Timeline does not exsist.";
$message_global['gettimelinedetail']['timeline_detail_success'] = "Timeline is successfully generated.";
icl_register_string('gettimelinedetail-msg', 'post-id-not-provided', $message_global['gettimelinedetail']['post_id_not_provided']);
icl_register_string('gettimelinedetail-msg', 'timeline-not-found', $message_global['gettimelinedetail']['timeline_not_found']);
icl_register_string('gettimelinedetail-msg', 'timeline-detail-success', $message_global['gettimelinedetail']['timeline_detail_success']);
// TIMELINE DETAIL MESSAGES TRANSLATION

// POPUP DATE VALIDATION MESSAGES TRANSLATION
$message_global['popupdatefieldvalidation']['popup_date_error'] = 'End date must be greater then start date. please change the End date.';
$message_global['popupstartdatefieldvalidation']['popup_date_error'] = 'Start date conflicts with another popup schedule. Please change start date';
$message_global['popupenddatefieldvalidation']['popup_date_error'] = 'End date conflicts with another popup schedule. Please change end date';
icl_register_string('popupdatefieldvalidation-msg', 'popup-date-error', $message_global['popupdatefieldvalidation']['popup_date_error']);
icl_register_string('popupstartdatefieldvalidation-msg', 'popup-date-error', $message_global['popupstartdatefieldvalidation']['popup_date_error']);
icl_register_string('popupenddatefieldvalidation-msg', 'popup-date-error', $message_global['popupenddatefieldvalidation']['popup_date_error']);
// POPUP LISTING MESSAGES TRANSLATION

// POPUP LISTING MESSAGES TRANSLATION
$message_global['getpopuplist']['no_data_found'] = 'No popup found.';
$message_global['getpopuplist']['success'] = "Popup listed successfully.";
icl_register_string('getpopuplist-msg', 'no-data', $message_global['getpopuplist']['no_data_found']);
icl_register_string('getpopuplist-msg', 'success', $message_global['getpopuplist']['success']);
// POPUP LISTING MESSAGES TRANSLATION

// LIBRARY LIST MESSAGES TRANSLATION
$message_global['getlibrary']['library_listing_success'] = "All library data is generated.";
$message_global['getlibrary']['library_not_found'] = "No library post found.";
icl_register_string('getlibrary-msg', 'library-listing-success', $message_global['getlibrary']['library_listing_success']);
icl_register_string('getlibrary-msg', 'library-not-found', $message_global['getlibrary']['library_not_found']);
// LIBRARY LIST MESSAGES TRANSLATION

// VIEW ALL VIDEO LIST MESSAGES TRANSLATION
$message_global['getallvideos']['video_listing_success'] = "All video data is generated.";
$message_global['getallvideos']['video_not_found'] = "No video found.";
icl_register_string('getallvideos-msg', 'video-listing-success', $message_global['getallvideos']['video_listing_success']);
icl_register_string('getallvideos-msg', 'video-not-found', $message_global['getallvideos']['video_not_found']);
// VIEW ALL VIDEO LIST MESSAGES TRANSLATION

// VIEW ALL DATA MESSAGES TRANSLATION
$message_global['viewalldata']['category_not_provided'] = "Please provide category.";
$message_global['viewalldata']['invalid_category'] = "Please provide valid category.";
$message_global['viewalldata']['no_data_found'] = "No data found.";
$message_global['viewalldata']['success'] = "Posts listed successfully.";
$message_global['viewalldata']['no_products'] = "No products found in this category.";
$message_global['viewalldata']['no_flyer'] = "No flyers found in this category.";
$message_global['viewalldata']['no_social_post'] = "No social post found in this category.";
icl_register_string('viewalldata-msg', 'category-not-provided', $message_global['viewalldata']['category_not_provided']);
icl_register_string('viewalldata-msg', 'invalid-category', $message_global['viewalldata']['invalid_category']);
icl_register_string('viewalldata-msg', 'no-data-found', $message_global['viewalldata']['no_data_found']);
icl_register_string('viewalldata-msg', 'success', $message_global['viewalldata']['success']);
icl_register_string('viewalldata-msg', 'no-products', $message_global['viewalldata']['no_products']);
icl_register_string('viewalldata-msg', 'no-flyer', $message_global['viewalldata']['no_flyer']);
icl_register_string('viewalldata-msg', 'no-social-post', $message_global['viewalldata']['no_social_post']);
// VIEW ALL DATA MESSAGES TRANSLATION

$message_global['getresources']['resources_listed'] = "Resources data listed successfully.";
icl_register_string('getresources-msg', 'resources-listed', $message_global['getresources']['resources_listed']);

$message_global['getconfiguration']['success'] = "Data loaded successfully.";
icl_register_string('getconfiguration-msg', 'success', $message_global['getconfiguration']['success']);

// VERSION CONTROLLER MESSAGE TRANSLATION
$message_global['versioncontroller']['version_not_provided'] = "Please provide version.";
$message_global['versioncontroller']['os_type_not_provided'] = "Please provide os type.";
icl_register_string('versioncontroller-msg', 'version-not-provided', $message_global['versioncontroller']['version_not_provided']);
icl_register_string('versioncontroller-msg', 'os-type-not-provided', $message_global['versioncontroller']['os_type_not_provided']);
// VERSION CONTROLLER MESSAGE TRANSLATION

$message_global['sessionmanagement']['user_id_not_provided'] = __('Please provide user id.','investorstrust');
$message_global['sessionmanagement']['os_type_not_provided'] = __('Please provide os type.','investorstrust');
$message_global['sessionmanagement']['session_data_not_provided'] = __('Please provide session data.','investorstrust');
$message_global['sessionmanagement']['session_data_save_success'] = __('Session data saved successfully.','investorstrust');

// ERROR HANDLING MESSAGE TRANSLATION
$message_global['errorhandling']['success'] = "Email sent successfully.";
$message_global['errorhandling']['failure'] = "Email sending failed.";
icl_register_string('errorhandling-msg', 'success', $message_global['errorhandling']['success']);
icl_register_string('errorhandling-msg', 'failure', $message_global['errorhandling']['failure']);
// ERROR HANDLING MESSAGE TRANSLATION

// GENERATE NEW REPORT MESSAGE TRANSLATION
$message_global['generatenewreport']['user_id_not_provided'] = "Please provide user id.";
$message_global['generatenewreport']['blank_message_content'] = "Please enter message content.";
$message_global['generatenewreport']['message_sent'] = "Your message has been sent, if a reply is needed we will contact you shortly.";
$message_global['generatenewreport']['user_not_found'] = "User not found.";
icl_register_string('generatenewreport-msg', 'user-id-not-provided', $message_global['generatenewreport']['user_id_not_provided']);
icl_register_string('generatenewreport-msg', 'blank-message-content', $message_global['generatenewreport']['blank_message_content']);
icl_register_string('generatenewreport-msg', 'message-sent', $message_global['generatenewreport']['message_sent']);
icl_register_string('generatenewreport-msg', 'user-not-found', $message_global['generatenewreport']['user_not_found']);
// GENERATE NEW REPORT MESSAGE TRANSLATION
/*
$message_global['generatenewreport']['email_subject'] = "Thank you, we have received your submission.";
$message_global['generatenewreport']['email_content_line_one_report'] = "Hi";
$message_global['generatenewreport']['email_content_line_two_report'] = "Thank you for your feedback! We have received your submission and are working towards a solution. We appreciate your patience in the meantime. ";
$message_global['generatenewreport']['email_content_line_three_report'] = 'Sincerely, ';
$message_global['generatenewreport']['email_content_line_four_report'] = 'ITA Connect App Team';
$message_global['generatenewreport']['email_automated_text'] = 'This e-mail was auto generated. Please do not respond.';
$message_global['generatenewreport']['email_content_line_five_part_one'] = '* This message was sent to';
$message_global['generatenewreport']['email_content_line_five_part_two'] = ' and intended for ';
$message_global['generatenewreport']['email_content_line_five_part_three'] = 'This message contains confidential information solely for the use of the intended recipient. If the reader of this message is not the intended recipient, the reader is notified that any use, dissemination, distribution or copying of this communication is strictly prohibited. If you have received this communication in error, please delete it immediately and notify Investors Trust by sending an email to the ITA Connect App Team at appteam@investors-trust.com.';
icl_register_string('generatenewreport-msg', 'email-subject', $message_global['generatenewreport']['email_subject']);
icl_register_string('generatenewreport-msg', 'email-content-line-one-report', $message_global['generatenewreport']['email_content_line_one_report']);
icl_register_string('generatenewreport-msg', 'email-content-line-two-report', $message_global['generatenewreport']['email_content_line_two_report']);
icl_register_string('generatenewreport-msg', 'email-content-line-three-report', $message_global['generatenewreport']['email_content_line_three_report']);
icl_register_string('generatenewreport-msg', 'email-content-line-four-report', $message_global['generatenewreport']['email_content_line_four_report']);
icl_register_string('generatenewreport-msg', 'email-automated-text', $message_global['generatenewreport']['email_automated_text']);
icl_register_string('generatenewreport-msg', 'email-content-line-five-part-one', $message_global['generatenewreport']['email_content_line_five_part_one']);
icl_register_string('generatenewreport-msg', 'email-content-line-five-part-two', $message_global['generatenewreport']['email_content_line_five_part_two']);
icl_register_string('generatenewreport-msg', 'email-content-line-five-part-three', $message_global['generatenewreport']['email_content_line_five_part_three']);
*/

// GET MESSAGE DATA MESSAGE TRANSLATION
$message_global['getmessagedata']['message_data_found'] = "Message data found successfully.";
$message_global['getmessagedata']['message_data_not_found'] = "No messages found for this ticket.";
$message_global['getmessagedata']['post_id_not_provided'] = "Please provide post id.";
$message_global['getmessagedata']['post_id_not_founded'] = "Post does not found.";
icl_register_string('getmessagedata-msg', 'message-data-found', $message_global['getmessagedata']['message_data_found']);
icl_register_string('getmessagedata-msg', 'message-data-not-found', $message_global['getmessagedata']['message_data_not_found']);
icl_register_string('getmessagedata-msg', 'post-id-not-provided', $message_global['getmessagedata']['post_id_not_provided']);
icl_register_string('getmessagedata-msg', 'post-id-not-founded', $message_global['getmessagedata']['post_id_not_founded']);
// GET MESSAGE DATA MESSAGE TRANSLATION

// GET LIST REPORT MESSAGE TRANSLATION
$message_global['getlistreport']['user_id_not_provided'] = "Please provide user id.";
$message_global['getlistreport']['user_not_found'] = "User not found.";
$message_global['getlistreport']['ticket_list_generated'] = "Ticket list generated successfully.";
$message_global['getlistreport']['admin_text_me'] = "Me";
$message_global['getlistreport']['ticket_not_found'] = "No ticket found.";
icl_register_string('getlistreport-msg', 'user-id-not-provided', $message_global['getlistreport']['user_id_not_provided']);
icl_register_string('getlistreport-msg', 'ticket-list-generated', $message_global['getlistreport']['ticket_list_generated']);
icl_register_string('getlistreport-msg', 'ticket-not-found', $message_global['getlistreport']['ticket_not_found']);
icl_register_string('getlistreport-msg', 'admin-text-me', $message_global['getlistreport']['admin_text_me']);
icl_register_string('getlistreport-msg', 'user-not-found', $message_global['getlistreport']['user_not_found']);
// GET LIST REPORT MESSAGE TRANSLATION

// GET LIST REPORT MESSAGE TRANSLATION
$message_global['addpollanswers']['user_not_found'] = "User not found.";
$message_global['addpollanswers']['user_id_not_provided'] = "Please provide user id.";
$message_global['addpollanswers']['poll_not_found'] = "No poll found.";
$message_global['addpollanswers']['poll_id_not_provided'] = "Please provide poll id.";
$message_global['addpollanswers']['answer_not_provided'] = "Please select answer.";
$message_global['addpollanswers']['answer_submitted'] = "Answer submitted successfully.";
$message_global['addpollanswers']['add_proper_answer'] = "Please select answer from given options.";
$message_global['addpollanswers']['already_submitted_answer'] = "You have already submitted answer.";

icl_register_string('addpollanswers-msg', 'user-id-not-provided', $message_global['addpollanswers']['user_id_not_provided']);
icl_register_string('addpollanswers-msg', 'poll-not-found', $message_global['addpollanswers']['poll_not_found']);
icl_register_string('addpollanswers-msg', 'poll-id-not-provided', $message_global['addpollanswers']['poll_id_not_provided']);
icl_register_string('addpollanswers-msg', 'answer-not-provided', $message_global['addpollanswers']['answer_not_provided']);
icl_register_string('addpollanswers-msg', 'user-not-found', $message_global['addpollanswers']['user_not_found']);
icl_register_string('addpollanswers-msg', 'answer-submitted', $message_global['addpollanswers']['answer_submitted']);
icl_register_string('addpollanswers-msg', 'add-proper-answer', $message_global['addpollanswers']['add_proper_answer']);
icl_register_string('addpollanswers-msg', 'already-submitted-ansers', $message_global['addpollanswers']['already_submitted_answer']);

// GET LIST REPORT MESSAGE TRANSLATION
$message_global['addsurveyanswers']['user_not_found'] = "User not found.";
$message_global['addsurveyanswers']['user_id_not_provided'] = "Please provide user id.";
$message_global['addsurveyanswers']['survey_not_found'] = "No survey found.";
$message_global['addsurveyanswers']['survey_id_not_provided'] = "Please provide survey id.";
$message_global['addsurveyanswers']['answer_not_provided'] = "Please select answer.";
$message_global['addsurveyanswers']['answer_submitted'] = "Answer submitted successfully.";
$message_global['addsurveyanswers']['add_proper_answer'] = "Please select answer from given options.";
$message_global['addsurveyanswers']['already_submitted_answer'] = "You have already submitted answer.";

icl_register_string('addsurveyanswers-msg', 'user-id-not-provided', $message_global['addsurveyanswers']['user_id_not_provided']);
icl_register_string('addsurveyanswers-msg', 'survey-not-found', $message_global['addsurveyanswers']['survey_not_found']);
icl_register_string('addsurveyanswers-msg', 'survey-id-not-provided', $message_global['addsurveyanswers']['survey_id_not_provided']);
icl_register_string('addsurveyanswers-msg', 'answer-not-provided', $message_global['addsurveyanswers']['answer_not_provided']);
icl_register_string('addsurveyanswers-msg', 'user-not-found', $message_global['addsurveyanswers']['user_not_found']);
icl_register_string('addsurveyanswers-msg', 'answer-submitted', $message_global['addsurveyanswers']['answer_submitted']);
icl_register_string('addsurveyanswers-msg', 'add-proper-answer', $message_global['addsurveyanswers']['add_proper_answer']);
icl_register_string('addsurveyanswers-msg', 'already-submitted-ansers', $message_global['addsurveyanswers']['already_submitted_answer']);

// GET LIST REPORT MESSAGE TRANSLATION
$message_global['getsearchfund']['currency_code_not_provided'] = 'Please provide currency code.';
$message_global['getsearchfund']['wrong_currency_code'] = 'Please provide valid currency code.';
$message_global['getsearchfund']['no_data'] = 'No fund found.';
$message_global['getsearchfund']['success'] = 'Funds listed successfully.';
icl_register_string('getsearchfund-msg', 'currency-code-not-provided', $message_global['getsearchfund']['currency_code_not_provided']);
icl_register_string('getsearchfund-msg', 'wrong-currency-code', $message_global['getsearchfund']['wrong_currency_code']);
icl_register_string('getsearchfund-msg', 'no-data', $message_global['getsearchfund']['no_data']);
icl_register_string('getsearchfund-msg', 'success', $message_global['getsearchfund']['success']);

$message_global['getriskquestion']['no_data'] = 'No risk calculator found.';
$message_global['getriskquestion']['success'] = 'Questions data listed successfully.';
icl_register_string('getriskquestion-msg', 'no-data', $message_global['getriskquestion']['no_data']);
icl_register_string('getriskquestion-msg', 'success', $message_global['getriskquestion']['success']);

$message_global['getriskdetail']['no_resource_id'] = 'Please provide resource id.';
$message_global['getriskdetail']['post_id_not_found'] = 'Resource does not exist.';
$message_global['getriskdetail']['total_score_not_provided'] = 'Please provide calculated score.';
$message_global['getriskdetail']['no_profile_found'] = 'No profile details found for calculated score.';
icl_register_string('getriskdetail-msg', 'no-resource-id', $message_global['getriskdetail']['no_resource_id']);
icl_register_string('getriskdetail-msg', 'post-id-not-found', $message_global['getriskdetail']['post_id_not_found']);
icl_register_string('getriskdetail-msg', 'total-score-not-provided', $message_global['getriskdetail']['total_score_not_provided']);
icl_register_string('getriskdetail-msg', 'no-profile-found', $message_global['getriskdetail']['no_profile_found']);

$message_global['uploadimage']['no_image_provided'] = 'Please provide image.';
$message_global['uploadimage']['success'] = 'Image uploaded successfully.';
$message_global['uploadimage']['error_uploading_file'] = 'Error uploading file on server.';
icl_register_string('uploadimage-msg', 'no-resource-id', $message_global['uploadimage']['no_image_provided']);
icl_register_string('uploadimage-msg', 'success', $message_global['uploadimage']['success']);
icl_register_string('uploadimage-msg', 'error-uploading-file', $message_global['uploadimage']['error_uploading_file']);

/*
$message_global['getlibcategory']['provide_type'] = __('Please provide the type.', 'investorstrust');
$message_global['getlibcategory']['success_get_category'] =  __('SuccessFully generated the categories.', 'investorstrust');
$message_global['getlibcategory']['selected_type_not_found'] = __('Selected type is not found.', 'investorstrust');

$message_global['getdetail']['post_id_not_provided'] = __('Please provide post id.' , 'investorstrust');
$message_global['getdetail']['post_not_found'] = __('Post does not exsist.' , 'investorstrust');
$message_global['getdetail']['get_detail_success'] = __('Post is successfully generated.' , 'investorstrust');

$message_global['getfund']['provide_type'] = __('Please provide the currrency type.','investorstrust');
$message_global['getfund']['success'] = __('Funds listed successfully.','investorstrust');
$message_global['getfund']['no_data'] = __('No fund found.','investorstrust');
$message_global['getfund']['wrong_currency_code'] = __('Please provide valid currency code.','investorstrust');

$message_global['getfundcurrency']['success'] = __('Fund currency list successfully generated.','investorstrust');
$message_global['getfundcurrency']['not_available'] = __('Fund currency list not available.','investorstrust');

$message_global['viewalldata']['product_search_cat_fail'] = __('No products found in this category.' , 'investorstrust');
$message_global['viewalldata']['flyer_search_cat_fail'] = __('No flyers found in this category.' , 'investorstrust');
$message_global['viewalldata']['social_post_search_cat_fail'] = __('No social post found in this category.' , 'investorstrust');
$message_global['viewalldata']['product_search_fail'] = __('No products found matching your search criteria.' , 'investorstrust');
$message_global['viewalldata']['flyer_search_fail'] = __('No flyers found matching your search criteria.' , 'investorstrust');
$message_global['viewalldata']['social_post_search_fail'] = __('No social post found matching your search criteria.' , 'investorstrust');
*/

// GET EDUCATION DETAIL CALCULATOR MESSAGES TRANSLATION
$message_global['calculator']['user_id_not_provided'] = "Please provide user id.";
$message_global['calculator']['user_not_exsist'] =  "User does not exsist.";
$message_global['calculator']['no_text_listed'] = "No text listed.";
$message_global['calculator']['text_listed'] = "calculator text listed successfully.";
$message_global['calculator']['retirement_table_data_not_generated'] = "Retirement calculator table data not generated.";
icl_register_string('calculator-msg', 'user-id-not-provided', $message_global['calculator']['user_id_not_provided']);
icl_register_string('calculator-msg', 'user-not-exsist', $message_global['calculator']['user_not_exsist']);
icl_register_string('calculator-msg', 'no-text-listed', $message_global['calculator']['no_text_listed']);
icl_register_string('calculator-msg', 'text-listed', $message_global['calculator']['text_listed']);
icl_register_string('calculator-msg', 'retirement-table-data-not-generated', $message_global['calculator']['retirement_table_data_not_generated']);
// GET EDUCATION DETAIL CALCULATOR MESSAGES TRANSLATION

// GET PORTFOLIO DETAIL MESSAGES TRANSLATION
$message_global['getportfoliodetail']['text_listed'] = "Portfolio Builder text listed successfully.";
$message_global['getportfoliodetail']['no_text_listed'] = "No text listed.";
$message_global['getportfoliodetail']['no_funds'] = "No funds found.";
$message_global['getportfoliodetail']['success'] = "Funds listed successfully.";
$message_global['getportfoliodetail']['currency_code_not_provided'] = 'Please provide currency code.';
$message_global['getportfoliodetail']['wrong_currency_code'] = 'Please provide valid currency code.';
$message_global['getportfoliodetail']['fund_ids_not_provided'] = 'Please provide fund id(s).';
$message_global['getportfoliodetail']['holdings_not_provided'] = 'Please provide holdings.';
$message_global['getportfoliodetail']['name_not_provided'] = 'Please provide name.';
$message_global['getportfoliodetail']['amount_not_provided'] = 'Please provide total amount.';
$message_global['getportfoliodetail']['user_id_not_provided'] = 'Please provide User ID.';
$message_global['getportfoliodetail']['user_id_not_valid'] = 'Please provide a valid User ID.';
$message_global['getportfoliodetail']['mstar_api_error'] = 'Something went wrong. Please try again.';
$message_global['getportfoliodetail']['report_generated'] = 'Portfolio Analysis Report generated successfully.';
$message_global['getportfoliodetail']['fund_weight_exceeded'] = 'The Sum of fund wieght should not be greater than 100.';
icl_register_string('getportfoliodetail-msg', 'no-text-listed', $message_global['getportfoliodetail']['no_text_listed']);
icl_register_string('getportfoliodetail-msg', 'text-listed', $message_global['getportfoliodetail']['text_listed']);
icl_register_string('getportfoliodetail-msg', 'no-funds', $message_global['getportfoliodetail']['no_funds']);
icl_register_string('getportfoliodetail-msg', 'success', $message_global['getportfoliodetail']['success']);
icl_register_string('getportfoliodetail-msg', 'currency-code-not-provided', $message_global['getportfoliodetail']['currency_code_not_provided']);
icl_register_string('getportfoliodetail-msg', 'wrong-currency-code', $message_global['getportfoliodetail']['wrong_currency_code']);
icl_register_string('getportfoliodetail-msg', 'fund-ids-not-provided', $message_global['getportfoliodetail']['fund_ids_not_provided']);
icl_register_string('getportfoliodetail-msg', 'holdings-not-provided', $message_global['getportfoliodetail']['holdings_not_provided']);
icl_register_string('getportfoliodetail-msg', 'name-not-provided', $message_global['getportfoliodetail']['name_not_provided']);
icl_register_string('getportfoliodetail-msg', 'amount-not-provided', $message_global['getportfoliodetail']['amount_not_provided']);
icl_register_string('getportfoliodetail-msg', 'user-id-not-provided', $message_global['getportfoliodetail']['user_id_not_provided']);
icl_register_string('getportfoliodetail-msg', 'user-id-not-valid', $message_global['getportfoliodetail']['user_id_not_valid']);
icl_register_string('getportfoliodetail-msg', 'mstar-api-error', $message_global['getportfoliodetail']['mstar_api_error']);
icl_register_string('getportfoliodetail-msg', 'report-generated', $message_global['getportfoliodetail']['report_generated']);
icl_register_string('getportfoliodetail-msg', 'fund-weight-exceeded', $message_global['getportfoliodetail']['fund_weight_exceeded']);

// GET PORTFOLIO DETAIL MESSAGES TRANSLATION