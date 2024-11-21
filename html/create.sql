
/*CREATE SCHEMA 4347g1;*/
USE b2b; /*Name of database, assumed already created based on assignment description. If database does not exist, remove the quotes from the first line.  */
START TRANSACTION; /*start of commit*/
SET autocommit = 0; /*Makes sure all statements are executed in a single commit, makes it easier for version control*/
SET unique_checks = 0; /*Overrides error handling for unique constraint*/
SET foreign_key_checks = 0;  /*Overrides error handling for foreign key constraint*/

CREATE TABLE CATEGORY(
	CategoryID INT NOT NULL DEFAULT '0',
	CategoryName varchar(50) NOT NULL DEFAULT 'No Category',
	CONSTRAINT PK_Category PRIMARY KEY (CategoryID)
);

CREATE TABLE PRODUCT(
	ProductID INT NOT NULL,
	CategoryID INT NOT NULL DEFAULT '0',
	ProductName varchar(150) NOT NULL DEFAULT 'Discontinued',
	ProductDesc varchar(400),
	UnitMeasurement varchar(20) DEFAULT 'Units',
	CONSTRAINT PK_Product PRIMARY KEY (ProductID),
	CONSTRAINT FK_Product_Category FOREIGN KEY (CategoryID) REFERENCES CATEGORY(CategoryID)
	ON DELETE SET DEFAULT 
	ON UPDATE CASCADE
);

CREATE TABLE ITEM(
	ItemID INT NOT NULL,
	ProductID INT NOT NULL DEFAULT '0',
	UnitQuantity INT,
	PPU FLOAT (5,3),
	AvailableStock INT,
	CONSTRAINT PK_Item PRIMARY KEY (ItemID),
	CONSTRAINT FK_Item_Product FOREIGN KEY (ProductID) REFERENCES PRODUCT(ProductID)
	ON DELETE SET DEFAULT
	ON UPDATE CASCADE
);

CREATE TABLE CUSTOMER(
	CustomerID INT NOT NULL,
	FName varchar(20),
	MInit char(1),
	LName varchar(20),
	Email varchar(100) NOT NULL,
    Password varchar(30) NOT NULL,
	PhoneNumber varchar(10),
	Address varchar(200),
	CONSTRAINT PK_Customer PRIMARY KEY (CustomerID),
	CONSTRAINT Unique_Email UNIQUE (Email)
);

CREATE TABLE EMPLOYEE(
	EmployeeID INT NOT NULL,
	FName varchar(20),
	MInit char(1),
	LName varchar(20),
	Title varchar(50),
	Email varchar(200),
	PhoneNumber varchar(10),
	CONSTRAINT PK_Employee PRIMARY KEY (EmployeeID),
	CONSTRAINT Unique_Email UNIQUE (Email),
	CONSTRAINT Unique_PhoneNumber UNIQUE (PhoneNumber)
);

CREATE TABLE ORDERS(
	OrderID INT NOT NULL,
	CustomerID INT,
	FufilledBy INT,
	DeliveryAddress varchar(200) NOT NULL,
	OrderedOn TIMESTAMP NOT NULL,
	DeliveryEstimate TIMESTAMP,
	OrderStatus varchar(20) NOT NULL DEFAULT 'Order Placed',
	CONSTRAINT PK_Orders PRIMARY KEY (OrderID),
	CONSTRAINT FK_Orders_Customer FOREIGN KEY (CustomerID) REFERENCES CUSTOMER(CustomerID)
	ON DELETE SET NULL
	ON UPDATE RESTRICT,
	CONSTRAINT FK_Orders_Employee FOREIGN KEY (FufilledBy) REFERENCES EMPLOYEE(EmployeeID)
	ON DELETE SET NULL
	ON UPDATE RESTRICT 
);

CREATE TABLE INVOICE(
	InvoiceID INT NOT NULL,
	OrderID INT,
	CONSTRAINT PK_Invoice PRIMARY KEY (InvoiceID),
	CONSTRAINT FK_Invoice_Orders FOREIGN KEY (OrderID) REFERENCES ORDERS(OrderID)
	ON DELETE CASCADE
	ON UPDATE RESTRICT
);

CREATE TABLE ITEMIZED_RECEIPT(
	InvoiceID INT,
	ItemID INT,
	ItemQuantity INT NOT NULL,
	CONSTRAINT PK_Receipt PRIMARY KEY (InvoiceID, ItemID),
	CONSTRAINT FK_Receipt_Invoice FOREIGN KEY (InvoiceID) REFERENCES INVOICE(InvoiceID)
	ON DELETE CASCADE
	ON UPDATE RESTRICT,
	CONSTRAINT FK_Receipt_Item FOREIGN KEY (ItemID) REFERENCES ITEM(ItemID)
	ON DELETE CASCADE
	ON UPDATE RESTRICT
);

SET unique_checks = 1; /*Re-enables error handling for unique constraint*/
SET foreign_key_checks = 1;/*Re-enables error handling for foreign key constraint*/
COMMIT; /*End of transaction*/

