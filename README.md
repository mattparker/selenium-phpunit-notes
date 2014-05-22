# Selenium and phpunit - links and resources

Some links and code samples and things to accompany my Nomad php lightning talk, May 2014.

## Selenium

Start [here](http://docs.seleniumhq.org/)  for the docs and downloads for Selenium.  Michelangelo van Dam has [blogged](http://www.dragonbe.com/2013/05/ua-testing-with-selenium-and-phpunit.html) about getting set up (although he starts with Selenium IDE, something I recommend you don't do for very long).

## IE VMs

[modern.ie](http://modern.ie/en-gb/virtualization-tools#downloads) has a bunch of Windows VMs with different IE versions for testing.  These are a fantastic resource and I wish apple offered the same.

## Mailcatcher

[Mailcatcher](http://mailcatcher.me/) is "a super simple SMTP server which catches any message sent to" which you can retrieve via a simple web interface, or more importantly using it's REST API.

In my application config file I have mail settings: for the [functest] section I point the mail at localhost:1025 (I'm using Zend_Mail).

I run Mailcatcher on the host box.

Then, I'd recommend a [php client from Alexandre Salomé](https://github.com/alexandresalome/mailcatcher) to retrieve the emails from Mailcatcher and make assertions about them.

## PHPUnit / Selenium extension

The best documentation I've found for the PHPUnit Selenium 2 extension is [the tests](https://github.com/sebastianbergmann/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php).  There's also the [phpunit manual](http://phpunit.de/manual/3.7/en/selenium.html) of course.  Note that there's both `PHPUnit_Extensions_Selenium2TestCase` and `PHPUnit_Extensions_SeleniumTestCase`.  I use the former because 2 is greater than 1.

## Code samples

I mentioned some helper methods that either reside in the base extension of `PHPUnit_Extensions_Selenium2TestCase` or in traits that I use when they are not applicable all the time (e.g. assertions about emails).

 - [`one` and `all`](./one-and-all.php) methods to locate DOM elements.
 - [`spinAssert`](./spinAssert.php) for assertions that'll happen soon (i.e. testing ajaxey type things where something doesn't happen immediately).
 - [Email helpers](./email-helper.php) for checking that an email sent by your application is as expected.
 - [Setting form values](./formsetter.php) in bulk, a simple time saver.


## My blog

I've blogged some of this as I've worked it out...

- [Setting up Mailcatcher](http://mattatl.blogspot.co.uk/2014/01/functional-testing-emails-with.html)
- [Adding CLI options to target particular browsers/platforms](http://mattatl.blogspot.co.uk/2014/01/adding-cli-options-to-phpunit.html)
- [Fixing the date and time on Windows VMs](http://mattatl.blogspot.co.uk/2014/04/set-time-date-on-virtualbox-windows-vm.html)

and there's a few other bits and pieces there.  Excuse the formatting.


## Other stuff I cut from the talk or didn't talk about...

### CLI option to choose platform/browser

It's mentioned briefly above, but it's really handy to be able to specify on the command line which platform and/or browser I want to target on a particular run.  How to do so is covered in the blog post linked above.

For example:

 - when writing tests, I just want one browser (usually FF) on one platform
 - if I get an browser specific bug report I want to write a test for that browser on one or all platforms
 - a full run is everything.  But I might do intermediate runs on all browsers on one platform more frequently.

### Checking file downloads

Is slightly tricky.  The files are dynamically generated and so I have to

 1. scrape the url from the response
 2. get the current session cookie value
 3. use Guzzle, and set the session cookie to the same, current value
 4. make a Guzzle request to the scraped url to get the file
 5. compare the files (I actually compare md5s)

### Tests using rich text editors (editable iframes)

iframes are tricky too.  They have their own DOM, so you need some extra funny business.

At a simple level, you should be able to do something like this:

```php
// sets the 'context' to the iframe DOM.
$this->frame($cssOfIframeElement); 
// now type:
$this->keys('the text to enter into the text editor');
// now go back to the parent:
$this->frame(null);
```

However, I had all kinds of cross browser trouble with my (rather heavily customised) YUI editors.  In the end I have to browser sniff (in the phpunit test).  I can't get IE to behave at all: instead I send some javascript directly for IE to set the form value.  Chrome seems to need an extra click on the body of the iframe before the `keys()` bit will work.  Firefox does appear to behave.

This is, I think, all rather specific to my application but you may find you end up with those same cross browser issues in the edge cases.


### Skipping tests

In the talk I mentioned that you can take shortcuts.  One of my unmentioned shortcuts is that if a test class is there to test correctness of the system (ie rather than cross-browser correctness) I'll only run those tests once per run, not for every browser/platform combo.  There's some extra code in the base `browsers` method that does this checking.


### Multi-tenant applications

Our application is a multi-tenant system.  That is, each customer has their own config file, and a bunch of other config options, that can very significantly alter the behaviour and appearance of the system.

This makes testing rather more complicated.

At the moment, in the testing I have two 'customers' with different config options but *the same* base test database.  The first 'customer' is a simple setup; the second has lots of things switched on.  When I do a full test run, it runs all the tests(+) on all the browser/platform combinations * for each 'customer' *.  That is, I want to test that all the simple stuff works on the simple system and the complex system.  And I also want to test the extra stuff on the complex system that I can't test on the simple system.

(+) Except when I don't - see skipping tests above.


### On logging in

If your application has a log in, your tests need to log in too.

I read somewhere that one way to solve this is to add a backdoor to your application so the tests can access the restricted content.  **This seems like a terrible idea** to me.  I seem to get a SANS bulletin detailing some vulnerability something almost every week where the problem was a hard-coded admin password or some other backdoor access.

Instead, I log in manually.  It takes a bit longer, but I'd rather pay that than deliberately introduce a vulnerability to my application.

I override the `url()` method to check if we're logged in, and log in first if not.



