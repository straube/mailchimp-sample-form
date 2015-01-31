<?php

// See the README to more information about the config.

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

define('MAILCHIMP_API_KEY',  ''); // Your Mailchimp API key
define('MAILCHIMP_LIST_ID',  ''); // Your list ID
define('MAILCHIMP_CITY_TAG', ''); // Your custom field tag

$form = $_POST;

if (!empty($form['email']) && !empty($form['city'])) {
    try {
        $mailchimp = new Mailchimp(MAILCHIMP_API_KEY);
        $lists = new Mailchimp_Lists($mailchimp);
        $email = [
            'email' => $form['email'],
        ];
        $merge = [
            MAILCHIMP_CITY_TAG => $form['city'],
        ];
        $lists->subscribe(
            MAILCHIMP_LIST_ID, // List ID
            $email,            // Subscriber ID, his/her email
            $merge,            // Custom fields
            'html',            // E-mail type
            false              // Confirm subscription by email (double opt-in)?
        );
        echo 'Successful subscribed!';
    } catch (Mailchimp_List_AlreadySubscribed $e) {
        echo 'Already subscribed.';
    } catch (Mailchimp_Email_AlreadySubscribed $e) {
        echo 'Already subscribed.';
    } catch (Mailchimp_Email_NotExists $e) {
        echo 'Email not exists.';
    } catch (Mailchimp_Invalid_Email $e) {
        echo 'Invalid email.';
    } catch (Mailchimp_List_InvalidImport $e) {
        echo 'Invalid data, probably your email.';
    } catch (Exception $e) {
        echo $e->getMessage(); // Do not show it to the user
    }
} else {
    echo 'Please fill all fields. <a href="newsletter.php">Go back</a>';
}
