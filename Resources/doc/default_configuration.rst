Default Configuration
=====================

.. code-block:: yml

    brother_guestbook:
        db_driver: orm
        entry_per_page: 25                                          # number of entries to show on a page
        auto_publish: true                                          # publish new entries or wait for admin approval
        notify_admin: false                                         # send notification mail to admin when a new entry is saved
        date_format: "d/m/Y H:i:s"                                  # date format used

        mailer:
            admin_email: admin@localhost.com                        # email the admin notification is sent to
            sender_email: admin@localhost.com                       # sender email used
            email_title: New guestbook entry from {name}            # (optional) notification email title

        class:
            model: Brother\GuestbookBundle\Entity\Entry                 # (optional) guestbook model class
            manager: Brother\GuestbookBundle\Entity\EntryManager        # (optional) guestbook manager class
            pager : Brother\GuestbookBundle\Pager\DefaultORM              # (optional) pager class
            mailer: Brother\GuestbookBundle\Mailer\Mailer               # (optional) mailer class

        form:
            entry:
                name: brother_guestbook_entry
                type: brother_guestbook_entry
                class: Brother\GuestbookBundle\Form\EntryType      # guestbook entry form class

            edit:
                name: brother_guestbook_entry_edit
                type: brother_guestbook_entry_edit
                class: Brother\GuestbookBundle\Form\EntryEditType  # guestbook entry edit form class

            reply:
                name: brother_guestbook_entry_reply
                type: brother_guestbook_entry_reply
                class: Brother\GuestbookBundle\Form\EntryReplyType # guestbook entry reply form class

        service:
            pager: ~                                                # (optional) custom pager service
            mailer: ~                                               # (optional) custom mailer service

Each of these configuration options can be overridden in your app/config/config.yml file


Other topics
============

#. `Installation`_

#. `Doctrine Configuration`_

#. `Mailer Configuration`_

#. `Pager Configuration`_

#. `Spam Detection`_

#. `Views/Templates`_

.. _Installation: Resources/doc/index.rst
.. _Doctrine Configuration: Resources/doc/doctrine.rst
.. _Mailer Configuration: Resources/doc/mailer.rst
.. _Pager Configuration: Resources/doc/pager.rst
.. _`Guestbook Administration`: Resources/doc/admin.rst
