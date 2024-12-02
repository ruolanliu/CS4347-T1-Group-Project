Steps to install system and run server:
  Make sure that PHP is installed and configured properly, with PDO and mysqli extensions enabled.
  Open the ZIP file, or clone the following repository: https://github.com/ruolanliu/CS4347-T1-Group-Project/
  Change to the directory “project/html”
  Create a MySQL database called “b2b” with port number 3306, and use the SQL code in “create.sql” and “load.sql” to load the csv files    in the “Uploads” directory to the SQL database.
  Run the following command:  php -S 0.0.0.0:8888
  The server has now started, and you can navigate to the login page by opening the file “signin.html” in a web browser
  A customer may create an account by registering, or change their password if they forgot it.
  After logging in, customer can use the menu bar to navigate to the current inventory, as well as their own order history.
  
Steps to create an order (invoke the database system):
  Navigate to the “New Order” page.
  Use the “Add Item” button to add dropdowns for items you wish to order.
  Do the following for each item (row of dropdowns).
  Use the first dropdown to select a category.
  Use the second dropdown to select a product.
  Use the third dropdown to select an item (base quantity).
  Use the final input to select the quantity you would like for each item.

Once you are satisfied with the items, click on the “Go to Shopping Cart” button. You will be able to view your items in a table, and you may delete items from the table.
Once you are satisfied, click the “Confirm Order” button, and you will reach the below page. You may click on the “View Order History” button to view your updated order history and the status of your new order.
