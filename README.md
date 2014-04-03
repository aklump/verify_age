# Overview
Provides **age verification to your website** that is **SEO friendly** and allows Google and other search engines to index your website, yet requires human visitors to confirm their age.  _Do not mistake this for a secure way to hide content from unprivilaged users. This is NOT an authentication solution._

# Live Demo
<http://www.intheloftstudios.com/packages/php/verify_age>

# How it Works
1. Extra HTML is written at the bottom of every page and positioned with css to completely cover up that page.
2. If a user verifies their age, the extra HTML will either 1) go away or 2) be replaced with an option to exit at any time.
3. If the user says they're not old enough, they'll be taken to your exit page.
4. The choice is remembered until the user quits their browser (or otherwise destroys the session), so the user only has to do this on the page where they've entered your website.

#Features
* Uses ajax to process age verification input.
* Stores data in the session.
* Provides a no javascript fallback.
* SEO friendly and allows Google bots to crawl it.
* Provides [Twig](http://twig.sensiolabs.org/) style templates for html content.


# Installation
1. Install this folder somewhere in your website project (Make sure to set `base_path` in `config.yaml` down below.)
1. These folders are not needed for production and should be deleted.
        
        /demo
        /tests

1. Copy `config_default.yaml` somewhere outside of this directory as `config.yaml` and make adjustments as needed.
1. If you are going to override the included snippets, copy those files somewhere outside of this directory and update `config.yaml`.

## Optional CSS
1. Include the optional css for positionin and colors for a base to work from.

## CSS Troubleshooting
1. If the overlay is not covering the entire page content try adding:

        body {
          position: relative;
        }

2. Also make sure that the getBody() output is a direct DOM child of `<body>`.

## Composer install dependencies
1. Install dependencies using composer.
2. `composer install --no-dev` from inside this directory, in shell.

## Snippets
1. The provided snippets in `templates` use [Twig](http://twig.sensiolabs.org/) style variable replacements; however we have not implemented a full [Twig](http://twig.sensiolabs.org/) parser in this project.

# Content
There are three pieces of content you need to think about with this solution.

1. The popup to ask for age verification. E.g., `yes-no.html.twig`.
2. A page to display when users are underage (exit page). E.g., `page-underage.html`.
3. (Optional.) A snippet of code to display that allows a user to change their mind about their age. E.g. `exit.html.twig`

# Do These 3 Steps on Every Page
On every html page for which you require age verification you will need to add the following three snippets, if your pages were not previously `.php` you will need to change their extension from `.html` to `.php`.  If you skip a page, the age verification will be non-existent for that page only.

## Before any output

    <?php
    require_once('verify_age/vendor/autoload.php');
    $age = new AKlump\VerifyAge\VerifyAge('config.yaml');
    ?>

1. Make sure the `require_once` and `VerifyAge` arguments point to the paths relative to the control file.
    
## Inside the `<head/>`
Make sure to place this before and css that may override this output.  Also if you've turned off the `jquery_cdn` option this needs to come AFTER your jquery script include.

    <head>
      ...after your jquery script...
      <?php print $age->getHead(); ?>
      ...before any css overrides...
    </head>

## Just before closing `</body>` tag

     <?php print $age->getBody(); ?>
     ... before any closing scripts...
    </body>

## Javascript hooks
You can register one or more hooks like this; they will called when ajax is returned

        /**
         * Register a callback for processing our body class
         *
         * @param  {object} data
         */
        $.fn.verifyAge.callback['body_class'] = function(data) {
          $('body').removeClass('verify-age-verified, verify-age-unverified');
          if (data.verified) {
            $('body').addClass('verify-age-verified');
          }
          else {
            $('body').addClass('verify-age-unverified');
          }
        }


# QA
## Check Implementation
1. Point your website to `/verify.php?ajax=1&o=3`; if everything is working you should see some JSON like the following, however values will change based on the session variables.

        {"mode":"inquiry","verified":false,"status":"denied","redirect":"\/"}

Using this table you can manually set the status and inquire if it's working.

| Action | Path |
|----------|----------|
| Verify Age | /verify.php?ajax=1&o=1 |
| Deny Age | /verify.php?ajax=1&o=2 |
| Status Inquiry | /verify.php?ajax=1&o=3 |

## Automated Tests
1. PHPUnit tests are provided in `tests`.
2. Run `composer install ` to get phpunit.

#References
* <http://stackoverflow.com/questions/3212063/combining-age-verification-and-google-indexing>
* <https://groups.google.com/forum/#!topic/Google_Webmaster_Help-Tools/PObAs8xy7eg>
* <https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag>
* <https://support.google.com/webmasters/answer/96569?hl=en>

##Contact
* **In the Loft Studios**
* Aaron Klump - Developer
* PO Box 29294 Bellingham, WA 98228-1294
* _aim_: theloft101
* _skype_: intheloftstudios
* _d.o_: aklump
* <http://www.InTheLoftStudios.com>