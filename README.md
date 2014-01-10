#Features
* Uses ajax to process age verification input.
* Still works when Javascript is disabled.
* SEO friendly and allows Google bots to crawl it.

# Installation
1. **Do not install the root folder of this project** in your website, instead you need only install the contents of `verify_age` in the document root of your website so that `/verify_age/verify.php` is loaded correctly when you ping it in a browser.
1. **Never modify any files except those inside `verify_age/user/`.**
2. Copy `verify_age/config_default.yaml` to `verify_age/user/config.yaml` and make adjustments as needed.
1. Copy necessary snippet files into `/user/` and update `verify_age/user/config.yaml` to point to those new files.

## Composer install dependencies
1. Install dependencies using composer.
2. In shell, inside `verify_user/` run `composer install`.

# Content
There are three pieces of content you need to think about

1. The popup to ask for age verification. E.g., `over-21.html.twig`.
2. A page to display when users are underage. E.g., `underage.html`.
3. (Optional.) A snippet of code to display that allows a user to change their mind about their age. E.g. `exit.html.twig`

# Changes to Website Code
On every html page for which you require age verification you will need to add the following three snippets:

## Before any output

    <?php
    require_once('/verify_age/vendor/autoload.php');
    $age = new AKlump\VerifyAge\VerifyAge('/verify_age/user/config.yaml', __FILE__);
    ?>

1. If your config file is found elsewhere, you may change argument one above.
    
## Inside the `<head/>` section

    <head>
      ...
      <?php print $age->getHead(); ?>
      ...
    </head>

## Just before closing `</body>` tag

    <?php print $age->getBody(); ?>
    </body>


## Automated Tests
1. PHPUnit tests are provided in `tests`.

#References
* <http://stackoverflow.com/questions/3212063/combining-age-verification-and-google-indexing>
* <https://groups.google.com/forum/#!topic/Google_Webmaster_Help-Tools/PObAs8xy7eg>

##Contact
* **In the Loft Studios**
* Aaron Klump - Developer
* PO Box 29294 Bellingham, WA 98228-1294
* _aim_: theloft101
* _skype_: intheloftstudios
* _d.o_: aklump
* <http://www.InTheLoftStudios.com>