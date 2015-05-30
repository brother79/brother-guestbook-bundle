Spam Detection
==============

Spam detection is **disabled** by default.

To enable spam detection, you must set the ``spam_detection`` config option.

.. code-block:: yml

    brother_guestbook:
        spam_detection: true

You must either have the ``OrnicarAkismentBundle`` installed or use a different spam detector
and integrate it with the BrotherGuestbookBundle.


Using Akismet for Spam Detection
--------------------------------

The BrotherGuestbookBundle is integrated with the OrnicarAkismentBundle.

To use AkismetBundle for spam detection, you must install the OrnicarAkismentBundle_
and configure it properly (see the docs for more information).

.. _OrnicarAkismentBundle: https://github.com/ornicar/OrnicarAkismetBundle

The BrotherGuestbookBundle automatically checks if the ``OrnicarAkismentBundle`` is installed.
If the OrnicarAkismentBundle is not installed, the BrotherGuestbookBundle will disable spam detection.
Setting the ``spam_detection`` config option in your app/config/config file
will override this setting.


Using a custom spam detection service
-------------------------------------

You can also specify a custom spam detection service by setting the ``spam_detection service`` config option.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        service:
            spam_detector: my_spam_detector

Your spam detector service class must implement the
``Brother\GuestbookBundle\SpamDetection\SpamDetectorInterface`` interface.


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Mailer Configuration`_

#. `Pager Configuration`_

#. `Spam Detection`_

#. `Views/Templates`_

#. `Guestbook Administration`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _`Mailer Configuration`: Resources/doc/mailer.rst
.. _`Pager Configuration`: Resources/doc/pager.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst

