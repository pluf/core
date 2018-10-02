# Database

Pluf has different classes to connect to different databases (such as Pluf_DB_MySQL for MySql, Pluf_DB_PostgreSQL for PostgreSql, and Pluf_DB_SQLite for SQLite) but all classes have unified implementation, i.e all of them have some same functions and feilds. Here are some of common features: 

### $con_id

The connection resource. This feild contains an object to connect to database.

### getServerInfo()

Get the version of the database server.

### close()
### select($query)
### execute($query)
### getLastID()
### getError()

Returns a string ready to be used in the exception.

### esc()

Escapes special characters in a string for use in an SQL statement, taking into account the current charset of the connection

## Security

Pluf provides some features for security such as escaping special characters. While you are using Pluf_SQL to set filter or sort items Pluf will escape special characters automatically to prevent SQL injection. For other cases you could use `esc()` function manually. This function escapes special characters in a string value.