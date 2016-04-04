# 0.1.2 (04.04.2016)

## Enhancements
- #64 Refresh product.
- #108 Edit products.
- #112 Add more product attributes.
- #117 Log into files and enhance logging.

## Features
- #114 Return and validate error if error occurs.

# 0.1.1 (22.02.2016)

## Features
- #75 Enable setting the maximum wait time for next request.
- #80 Save formatted value in price.

## Bugfixes
- #76 Delete locale parameter of product getName().
- #78 and #87 Fix and refactor slideshow.
- #79 Fix Product getPrice.
- #82 Use Shop in an unstatic way.
- #83 Unstatic many objects.
- #86 Fix output of some __toString() functions.

## Enhancements
- #81 Use __toString() functions.

# 0.1.0 (28.01.2016)

## Enhancements
- #1 Get the product price.
- #4 Delete a product.
- #29 Gets the slideshow of a product.
- #30 Get attributes and their values of the product.
- #32 Get stock level of product.
- #34 Set the stock level of product.
- #36 Use *enums* for LogLevel and LogOutput.
- #37 Use *enums* for HTTPRequestMethod.
- #52 Extend the test suite - Step 1.
- #63 You don't can set the requested Locale in get functions from now. Used Locale can be set in Shop object.

## Bugfixes
- #38 Precache shop values for a while.
- #51 Allow requests without a AuthToken.
- #53 Delete whitespaces on line ending.
- #55 Fix README file.
- #60 Fix product search without a defined Locale.
- #66 Unstatic some variables and the productFilter class.

# 0.0.3 (17.12.2015)

## Enhancements
- #42 Change project to name *epages-rest-php*.
- #44 Automatically set error_reporting on set the ep6\Logger.
- #47 Create the documentation with another engine.

## Bugfixes
- #39 Fix typo in REAME.
- #43 Fixing relative path problem.

# 0.0.2 (09.12.2015)

This release does not have relevant changes in the productive code and is not downloadable.

## Enhancements
- #26 Add CHANGELOG.md.

# 0.0.1 (06.12.2015)
## Enhancements
- #17 Expand the README file.
- #18 Use phpDoc parameters all over.
- #21 Finalize some *InputVaidator* functions.
- #23 Compile *.phar* file to another file name.
- #24 Fix phpDoc in *Product* constructor.
- #25 Avoid *printStackTrace()* printing Logger functions.

## Bugfixes
- #20 Default value of attributes should be *null* instead of *""*.
- #22 Set default and destrcutor value of *RESTClient* *ISSSL* to *null* instead of *""*.

## Features
- #13 Define a product filter with an array.

# 0.0.0 (03.12.2015)
Finalize first draft.