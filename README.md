# Information
# Installation
## Get the bundle

Let Composer download and install the bundle by running
```sh
php composer.phar require brother/guestbook-bundle:dev-master
```
in a shell.
## Enable the bundle
```php
// in app/AppKernel.php
public function registerBundles() {
	$bundles = array(
		// ...
            new Brother\GuestbookBundle\BrotherGuestbookBundle(),
	);
	// ...
}
```
## enable error notifier

# Usage



