use practice_db;

create table products(
	product_id int primary key,
    category_id int,
    product_name varchar(128),
    revenue int
);

INSERT INTO products VALUES
(1, 101, 'Laptop', 50000),
(2, 101, 'Tablet', 40000),
(3, 101, 'Smartphone', 40000),
(4, 101, 'Mouse', 10000),
(5, 102, 'Shirt', 2000),
(6, 102, 'Jeans', 2500),
(7, 102, 'Jacket', 2500),
(8, 102, 'T-Shirt', 1500),
(9, 103, 'Refrigerator', 30000),
(10, 103, 'Microwave', 20000),
(11, 103, 'Washing Machine', 25000),
(12, 103, 'Mixer', 10000);

-- query to get top 3 products by revenue for each category
with ranked_output as (
    select product_id, category_id, product_name, revenue,
           dense_rank() over (partition by category_id order by revenue desc) as rank_num
    from products
)

select product_id, category_id, product_name, revenue, rank_num
from ranked_output
where rank_num <= 3;