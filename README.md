# Individual Championships Server

Collect participations for individual championships organizations.

## Installation

Create a `config.php` in this directory.

Define the appropriate PHP constants, as in the following example:

```php
/**
 * Where the json files will be saved.
 * The directory should NOT be visible to the public.
 */
define( 'PATH', '../json' );

/**
 * Google reCAPTCHA v3 keys.
 * Documentation about Google reCAPTCHA can be found here:
 * https://developers.google.com/recaptcha
 */
define( 'RECAPTCHA_SITE_KEY',   'YOUR PUBLIC KEY' );
define( 'RECAPTCHA_SECRET_KEY', 'YOUR SECRET KEY' );
```
