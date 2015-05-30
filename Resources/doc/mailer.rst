Mailer Configuration
====================

To send emails, SwitfMailer must be installed and configured.

For more information about Swiftmailer configuration,
check the `SwiftmailerBundle Configuration documentation`_

.. _`SwiftmailerBundle Configuration documentation`: http://symfony.com/doc/current/reference/configuration/swiftmailer.html

To send admin notification emails (email sent to the admin each time a new guestbook entry is saved),
you must enable the mailer service and set the mail ``admin_email`` and ``sender_email`` config options

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        notify_admin: true

        mailer:
            admin_email: admin@localhost.com                # email the admin notification is sent to
            sender_email: admin@localhost.com               # sender email used
            email_title: New guestbook entry from {name}    # (optional)


Using a custom mailer class
---------------------------
You can specify your custom guestbook mailer manager class by overriding the mailer class option e.g.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        class:
            manager: MyProject\MyBundle\Mailer\Mailer

Your custom class may extend the ``Brother\GuestbookBundle\Mailer\BaseMailer`` class. If you are not extending the
``Brother\GuestbookBundle\Mailer\BaseMailer`` class, your custom mailer class must implement the
``Brother\GuestbookBundle\Mailer\MailerInterface`` interface.


Using a custom spam detection service
-------------------------------------

You can also specify a custom mailer service by setting the `` mailer service`` config option.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        service:
            mailer: my_mailer

Your mailer service class may extend the ``Brother\GuestbookBundle\Mailer\BaseMailer`` class. If you are not extending the
``Brother\GuestbookBundle\Mailer\BaseMailer`` class, your custom mailer class must implement the
``Brother\GuestbookBundle\Mailer\MailerInterface`` interface.


Using a custom notification template
------------------------------------

You can specify a custom notification template by overriding the mail template config setting

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        view:
            mail:
                notify: MyBundle:Mail:notify.txt.twig


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Pager Configuration`_

#. `Spam Detection`_

#. `Views/Templates`_

#. `Guestbook Administration`_

#. `Default Configuration`_

.. _Installation: Resources/doc/index.rst
.. _`Doctrine Configuration`: Resources/doc/doctrine.rst
.. _`Pager Configuration`: Resources/doc/pager.rst
.. _`Spam Detection`: Resources/doc/spam_detection.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
