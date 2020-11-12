Notes about module development and maintenance:

1) All modules have a hierarchical structure, controlled by the [prefix]_module_categories table

2) Each module has its own "items" table, which is unique for each module, based on the type of information the module must store.

3) All modules share the same [prefix]_module_settings table, and are distinguished by the 'module_id' field

4) All modules tables MUST have a site_key field, which must be varchar(50)

... more specs. to come ...