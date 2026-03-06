CREATE DATABASE 07task;

use 07task;

CREATE TABLE customers ( 
	customer_id INT PRIMARY KEY,
    customer_name VARCHAR(128)
);

-- drop table products;

CREATE TABLE products (
	product_id INT PRIMARY KEY,
    product_name TEXT,
    category_id INT
);

CREATE TABLE purchases (
	purchase_id INT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);


-- data insert 

INSERT INTO customers VALUES 
(1,'Ram'),
(2,'Shyam'),
(3,'Riya'),
(4,'Abhi'),
(5,'Karan');

INSERT INTO products VALUES 
(101,'laptop',1),
(102,'mouse',1),
(103,'handbag',2),
(104,'mobile',3),
(105,'tv',3);

INSERT INTO purchases VALUES
(1,1,101),
(2,1,103),
(3,1,105),
(4,2,101),
(5,3,101),
(6,2,101),
(7,3,101),
(8,1,102),
(9,1,104);

-- query to get the customers who have purchased products from all categories
select 
	c.customer_id,
    c.customer_name
from 
	customers c
    -- subquery to get the categories of products purchased by each customer
where not exists (
	select p.category_id
    from products p
    group by p.category_id
    
    -- subquery to check if the customer has purchased any product from the category
    having not exists (
		select 1
        from purchases pu
        join products pr 
        on pu.product_id = pr.product_id
        where pu.customer_id = c.customer_id
        and pr.category_id = p.category_id
    )
);




