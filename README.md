This repository contains some miscelaneous command line PHP scripts

## generate-redirects.php

This script is useful if you plan to move one domain name to another and needs 301 redirections.
For example, it can be used if your host doesn't allow you to set redirect URLs with .htaccess.

You will need to provide a urls.txt file with each URLs you whish to redirect from. for example :

---
http://www.old-domain.com/url-to-redirect
http://www.old-domain.com/another-url-to-redirect
---

Then, set the constant in this file, particularly the 'old domain' and 'new domain' constants.
In this case, if you want to redirect from old-domain.com to new-domain.com:

<code>
	// Domain name you wish to redirect
	define('OLD_DOMAIN_NAME', 'old-domain.com');

	// The domain name where the old one will be redirected to
	define('NEW_DOMAIN_NAME', 'new-domain.com');
</code>

Then, all you need to do is to call this script from command line.

<code>
php generate-redirects.php
</code>

A folder 'generated-urls' should be created with all the corresponding folders and an index.php file.
The index.php file will make the redirection individually for each pages.
