# srcf.net
This is the repository for the [main SRCF website](https://srcf.net).

The directory `_srcf` contains the assets (CSS, images etc.) that are used by 
all websites under the `srcf.net` domain.

The `faq` directory is a legacy path that has been superseded by the 
information at the [SRCF documentation website](https://docs.srcf.net). Apache 
server redirects in the main [`.htaccess`](.htaccess) file ensure that all 
URLs under `faq` (except for `index.html`) will be directed to the appropriate 
documentation page.

## Development build
The website is largely vanilla HTML but uses server-side includes (SSI) run 
by the Apache web server at runtime to add header and footer information. 
Dynamic information is added to some of the pages, such as the number of users 
in the database, by calling Python scripts. Since the SSI and dynamic 
information are only available on the server it isn't currently possible to 
run a build locally and get a fully rendered website just from cloning the 
repo alone.
    
To build the pages run from the project's root:

`$ make`

Then navigate to `index.html` in your browser.