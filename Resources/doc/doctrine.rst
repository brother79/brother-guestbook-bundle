Doctrine configuration
======================

The BrotherGuestbookBundle supports both Doctrine ORM and Doctrine ODM.
It is configured for ORM by default. To use Doctrine ODM, you must set this in the ``db_driver`` option.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        db_driver: mongodb


Using a custom model class
--------------------------

You can specify a custom model class by overriding the guestbook model class option e.g.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        class:
            model: MyProject\MyBundle\Entity\MyGuestbook

Your custom model class may extend the ``Brother\GuestbookBundle\Model\Entry`` class. If you are not extending the
``Brother\GuestbookBundle\Model\Entry`` class, your custom manager class must implement the
``Brother\GuestbookBundle\Model\EntryInterface`` interface.


Using a custom manager class
----------------------------

You can specify a custom guestbook entry manager class by overriding the manager class option e.g.

.. code-block:: yml

    # app/config/config.yml
    brother_guestbook:
        class:
            manager: MyProject\MyBundle\Entity\MyGuestbookManager

Your custom class may extend the ``Brother\GuestbookBundle\Model\EntryManager`` class. If you are not extending the
``Brother\GuestbookBundle\Model\EntryManager`` class, your custom manager class must implement the
``Brother\GuestbookBundle\Model\EntryManagerInterface`` interface.


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
.. _`Mailer Configuration`: Resources/doc/mailer.rst
.. _`Pager Configuration`: Resources/doc/pager.rst
.. _`Spam Detection`: Resources/doc/spam_detection.rst
.. _`Views/Templates`: Resources/doc/views.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
.. _`Default Configuration`: Resources/doc/default_configuration.rst
