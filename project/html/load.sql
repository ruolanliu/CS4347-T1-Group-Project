
/*TO IMPLEMENT - Replace 'C:/ProgramData/... with whatever directory is set as your 
secure-file-priv for the server.
Folder can be located via:
SELECT @@secure_file_priv;

NOTE mySQL does not interpret \ for file systems. 
All instances of '\' must be replaced with '/'.
*/

USE b2b;
START TRANSACTION; /*start of commit*/
SET autocommit = 0; /*Makes sure all statements are executed in a single commit, makes it easier for version control*/
SET unique_checks = 0; /*Overrides error handling for unique constraint*/
SET foreign_key_checks = 0;  /*Overrides error handling for foreign key constraint*/

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(category).csv'
INTO TABLE CATEGORY
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(product).csv'
INTO TABLE PRODUCT
FIELDS TERMINATED BY ','
ENCLOSED BY '"' /*special case since 'ProductDescription' is several sentences.*/
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(item).csv'
INTO TABLE ITEM
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(customer).csv'
INTO TABLE CUSTOMER
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(employee).csv'
INTO TABLE EMPLOYEE
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(order).csv'
INTO TABLE ORDERS
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(invoice).csv'
INTO TABLE INVOICE
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA 
INFILE 'C:/ProgramData/MySQL/MySQL Server 8.0/Uploads/b2b_db(itemized_receipt).csv'
INTO TABLE ITEMIZED_RECEIPT
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SET unique_checks = 1; /*Re-enables error handling for unique constraint*/
SET foreign_key_checks = 1;/*Re-enables error handling for foreign key constraint*/
COMMIT; /*End of transaction */

