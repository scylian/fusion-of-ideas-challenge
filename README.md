# Fusion Of Ideas Code Challenge

## Objective

Create PHP web services that can be called to add/edit/delete 3 main entities
that each have a one to many relationship.

The 3 main entities are organized in a database in 3 tables:
* clients: id, name
* sections: id, name, client_id
* links: id, name, section_id

Each has a one to many relationship cascading down. Clients -> Sections -> Links.

## Database Schema While Developing

### Clients

```
CREATE TABLE clients (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT
);
```

### Sections

```
CREATE TABLE sections (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    client_id INTEGER,
    FOREIGN KEY (client_id)
    REFERENCES clients(id)
    ON DELETE CASCADE
);
```

### Links

```
CREATE TABLE links (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name TEXT,
    section_id INTEGER,
    FOREIGN KEY (section_id)
    REFERENCES sections(id)
    ON DELETE CASCADE
);
```

## Assumptions & Details

I wrote this code to be implemented with more than just the requisite parameters
passed in through HTTP methods.

The two of note are `action_type` and `table_name`.

* `action_type` determines whether the user is attempting to Add, Edit, or Delete data from the database.
* `table_name` references the table to alter between the 3 declared.

This code also restricts users to only Add/Edit/Delete one entry at a time,
but can be scaled out to include INNER and OUTER JOINS allowing for multiple rows
and tables to be queried.

I also assumed that each table's *id* would be a primary key and thus un-editable.

Lastly, I did not restrict users from adding Sections and Links that were not related
to a parent entity.