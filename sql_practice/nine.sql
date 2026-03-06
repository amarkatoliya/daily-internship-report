create database 09task;

use 09task;

create table customers (
    customer_id int primary key,
    customer_name varchar(100)
);

insert into customers values
(1,'amit'),
(2,'rahul'),
(3,'priya');

create table products (
    product_id int primary key,
    product_name varchar(100),
    price int
);

insert into products values
(101,'laptop',50000),
(102,'mouse',1000),
(103,'keyboard',1500),
(104,'phone',30000);

create table orders (
    order_id int primary key,
    customer_id int,
    order_date date,
    
    foreign key (customer_id) references customers(customer_id)
);

insert into orders values
(201,1,'2026-03-01'),
(202,1,'2026-03-05'),
(203,2,'2026-03-07');

create table order_items (
    order_item_id int primary key,
    order_id int,
    product_id int,
    quantity int,
    
    foreign key (order_id) references orders(order_id),
    foreign key (product_id) references products(product_id)
);

insert into order_items values
(1,201,101,1),
(2,201,102,2),
(3,202,104,1),
(4,203,103,1),
(5,203,102,1);

select 
    json_object(
        'customer_id', c.customer_id,
        'customer_name', c.customer_name,
        'orders',
        (
            select json_arrayagg(
                json_object(
                    'order_id', o.order_id,
                    'items',
                    (
                        select json_arrayagg(
                            json_object(
                                'product_name', p.product_name,
                                'quantity', oi.quantity,
                                'price', p.price
                            )
                        )
                        from order_items oi
                        join products p 
                            on oi.product_id = p.product_id
                        where oi.order_id = o.order_id
                    )
                )
            )
            from orders o
            where o.customer_id = c.customer_id
        )
    ) as customer_json
from customers c
where exists (
    select 1 
    from orders o 
    where o.customer_id = c.customer_id
);



