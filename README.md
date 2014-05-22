# Selenium and phpunit - links and resources

Some links and code samples and things to accompany my Nomad php lightning talk, May 2014.

## Selenium

Start [here](http://docs.seleniumhq.org/)  for the docs and downloads for Selenium.  Michelangelo van Dam has [blogged](http://www.dragonbe.com/2013/05/ua-testing-with-selenium-and-phpunit.html) about getting set up (although he starts with Selenium IDE, something I recommend you don't do for very long).

## IE VMs

[modern.ie](http://modern.ie/en-gb/virtualization-tools#downloads) has a bunch of Windows VMs with different IE versions for testing.  These are a fantastic resource and I wish apple offered the same.

## Mailcatcher

[Mailcatcher](http://mailcatcher.me/) is "a super simple SMTP server which catches any message sent to" which you can retrieve via a simple web interface, or more importantly and json-ish RESTey API.

In my application config file I have mail settings: for the [functest] section I point the mail at localhost:1025 (I'm using Zend_Mail).

I run Mailcatcher on the host box.

Then, I'd recommend a [php client from Alexandre Salomé](https://github.com/alexandresalome/mailcatcher) to retrieve the emails from Mailcatcher and make assertions about them.

## PHPUnit / Selenium extension

The best documentation I've found for the PHPUnit Selenium 2 extension is [the tests](https://github.com/sebastianbergmann/phpunit-selenium/blob/master/Tests/Selenium2TestCaseTest.php).  There's also the [phpunit manual](http://phpunit.de/manual/3.7/en/selenium.html) of course.  Note that there's both `PHPUnit_Extensions_Selenium2TestCase` and `PHPUnit_Extensions_SeleniumTestCase`.  I use the former because 2 is greater than 1.

## Code samples

I mentioned some helper methods that either reside in the base extension of `PHPUnit_Extensions_Selenium2TestCase` or in traits that I use when they are not applicable all the time (e.g. assertions about emails).

 - [`one` and `all`](./one-and-all.php) methods to locate DOM elements.

## My blog

I've blogged some of this as I've worked it out...

- [Setting up Mailcatcher](http://mattatl.blogspot.co.uk/2014/01/functional-testing-emails-with.html)
- [Adding CLI options to target particular browsers/platforms](http://mattatl.blogspot.co.uk/2014/01/adding-cli-options-to-phpunit.html)
- [Fixing the date and time on Windows VMs](http://mattatl.blogspot.co.uk/2014/04/set-time-date-on-virtualbox-windows-vm.html)

and there's a few other bits and pieces there.  Excuse the formatting.


## On logging in

If your application has a log in, your tests need to log in too.

I read somewhere that one way to solve this is to add a backdoor to your application so the tests can access the restricted content.  **This seems like a terrible idea** to me.  I seem to get a SANS bulletin detailing some vulnerability something almost every week where the problem was a hard-coded admin password or some other backdoor access.

Instead, I log in manually.  It takes a bit longer, but I'd rather pay that than deliberately introduce a vulnerability to my application.


