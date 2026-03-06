create table customers (
    customer_id int primary key,
    customer_name varchar(100)
);

insert into customers values
(1,'amit'),
(2,'rahul'),
(3,'priya');


create table orders (
    order_id int primary key,
    customer_id int,
    order_date date,
    amount int,
    
    foreign key (customer_id) references customers(customer_id)
);

insert into orders values
(101,1,'2026-01-10',2000),
(102,1,'2026-01-20',1500),
(103,1,'2026-02-05',3000),
(104,1,'2026-02-25',2500),
(105,1,'2026-03-05',1800),
(106,1,'2026-03-20',2200),

(107,2,'2026-01-15',1200),
(108,2,'2026-02-01',1700),
(109,2,'2026-02-18',2100),
(110,2,'2026-03-10',1900),
(111,2,'2026-03-25',2400),

(112,3,'2026-01-12',1300),
(113,3,'2026-02-08',1600),
(114,3,'2026-02-28',2000),
(115,3,'2026-03-15',2200),
(116,3,'2026-03-30',2600);


-- query to get the last 5 orders for each customer
select 
    c.customer_id,
    c.customer_name,
    o.order_id,
    o.order_date,
    o.amount,
    -- row number to rank orders
    row_number() over (
        partition by c.customer_id 
        order by o.order_date desc
    ) as row_num
from customers c

-- lateral join
join lateral (
    select *
    from orders o
    where o.customer_id = c.customer_id
    order by o.order_date desc
    limit 5
) o on true
order by c.customer_id, row_num;