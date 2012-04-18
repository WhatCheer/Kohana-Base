# What is K3-SimpleTest

It is an alternative unit testing module for the Kohana 3 framework.

# Usage

Using K3-SimpleTest is easy!

## 1) Write A Unit Test

By convention, these would live at <tt>application/tests/unit/[name.php]</tt>.

All of the assertions from [SimpleTest](http://www.simpletest.org/) are available to use.

For an example, look at <tt>tests/unit/example.php</tt> in this package.

### Naming Tip!

If you name your test method with underscores for spaces (like: <tt>test_Validates_Start_Is_Before_Or_Same_As_End</tt>) the HTML test reporter will convert those underscores into spaces, and lop off the "test" at the beginning (like: <tt>Validates Start Is Before Or Same As End</tt>)

## 2) Setup Config

Copy <tt>config/simpletest.php.example</tt> to <tt>application/config/simpletest.php</tt> and add the paths to your tests, grouping however you would like.

## 3) Run The Tests

To run the tests, you can either go to <tt>/simpletest/all</tt> in a browser, or run them from the command line:

    php index.php --uri="/simpletest/all"

# License

K3-SimpleTest is licensed under MIT.

SimpleTest (included in vendor/simpletest for your convenience) is licensed under the GPL

