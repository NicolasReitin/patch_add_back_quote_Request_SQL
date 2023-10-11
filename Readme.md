# **Patch to add backquotes to SQL queries**


This is a patch designed to automatically add back quotes (``) in SQL queries if they have been omitted right after "SELECT," "DELETE," "UPDATE," or "INSERT INTO" and before "FROM." (Query to be adjusted as needed).

If the original query already contains Back Quote, it doesn't modify it, otherwise it adds Back quote to column names.

Exemple :

    ```php

        "DELETE name, age, FROM t_users WHERE id=? LIMIT 1"

        // =>Use patch with function to convert request

        "DELETE `name`, `age`, FROM t_users WHERE id=? LIMIT 1"

    ```
