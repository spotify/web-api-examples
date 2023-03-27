# Contribution Guide

## Issues
Please submit all your bug reports, feature requests and pull requests here but note that this isn't the place for support requests. Please use [Stack Overflow](http://stackoverflow.com/) for this.

## Bug reports
1. Search the issues, has it already been reported?
2. Download the latest source, did this solve the problem?
4. If the answer to all of the above questions are "No" then open a bug report and include the following:
    * A short, descriptive title.
    * A summary of the problem.
    * The steps to reproduce the problem.
    * Possible solutions or other relevant information/suggestions.

## New features
If you have an idea for a new feature, please file an issue first to see if it fits the scope of this project. That way no one's time needs to be wasted.

## Coding Guidelines
We follow the coding standards outlined in [PSR-1](https://www.php-fig.org/psr/psr-1/) and [PSR-12](https://www.php-fig.org/psr/psr-12/). Please follow these guidelines when committing new code.

In addition to the PSR guidelines we try to adhere to the following points:
* We order all methods by visibility and then alphabetically, `private`/`protected` methods first and then `public`. For example:

```
protected function b() {}

public function a() {}
```

instead of

```
public function a() {}

protected function b() {}
```

* We strive to keep the inline documentation language consistent, take a look at existing docs for examples.

Before committing any code, be sure to run `composer test` to ensure that the code style is consistent and all the tests pass.
