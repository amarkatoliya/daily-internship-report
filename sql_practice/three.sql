use practice_db;

create table customers(
	customer_id int primary key,
    customer_name varchar(128)
);

-- drop table orders;

create table orders(
	order_id int primary key,
    customer_id int,
    order_date date,
    
    foreign key (customer_id) references customers(customer_id)
);

create table order_items(
	order_item_id int primary key,
    order_id int,
    quantity int,
    price int,
    
    foreign key (order_id) references orders(order_id)
);

INSERT INTO customers VALUES
(1,'Amit'),
(2,'Rahul'),
(3,'Priya');

INSERT INTO orders VALUES
(101,1,'2026-02-20'),
(102,1,'2026-03-01'),
(103,2,'2026-03-02'),
(104,3,'2026-02-28');

INSERT INTO order_items VALUES
(1,101,2,500),
(2,102,1,1000),
(3,103,3,400),
(4,104,1,300);

-- query to get customers who have made purchases in the last 30 days and their total spending, along with the average spending of all customers in the same period

with customer_spending as (
    select 
        c.customer_id,
        c.customer_name,
        count(o.order_id) as purchase_count,
        sum(oi.quantity * oi.price) as total_spending
    from customers c
    join orders o on c.customer_id = o.customer_id
    join order_items oi on o.order_id = oi.order_id
    where o.order_date >= curdate() - interval 30 day
    group by c.customer_id, c.customer_name
),

avg_spending as (
    select avg(total_spending) as avg_total
    from customer_spending
)

select 
    cs.customer_id,
    cs.customer_name,
    cs.purchase_count,
    cs.total_spending
from customer_spending cs
join avg_spending a
on cs.total_spending > a.avg_total;