use practice_db;


create table report_categories (
    category_id int primary key,
    category_name varchar(50)
);

insert into report_categories values
(1,'electronics'),
(2,'clothing'),
(3,'home');

create table report_products (
    product_id int primary key,
    product_name varchar(50),
    category_id int,
    foreign key (category_id) references report_categories(category_id)
);

insert into report_products values
(101,'laptop',1),
(102,'phone',1),
(103,'tshirt',2),
(104,'jeans',2),
(105,'mixer',3);

create table report_orders (
    order_id int primary key,
    order_date date,
    order_status varchar(20)
);

insert into report_orders values
(1,'2026-01-10','completed'),
(2,'2026-02-12','pending'),
(3,'2026-03-15','cancelled'),
(4,'2026-04-05','completed'),
(5,'2026-05-18','completed'),
(6,'2026-06-10','pending'),
(7,'2026-07-20','completed'),
(8,'2026-08-01','cancelled'),
(9,'2026-09-10','completed'),
(10,'2026-10-05','pending');

create table report_order_items (
    order_item_id int primary key,
    order_id int,
    product_id int,
    quantity int,
    price int,
    foreign key (order_id) references report_orders(order_id),
    foreign key (product_id) references report_products(product_id)
);

insert into report_order_items values
(1,1,101,1,50000),
(2,1,102,2,20000),
(3,2,103,3,500),
(4,2,104,1,1500),
(5,3,105,1,3000),
(6,4,101,1,52000),
(7,4,103,2,500),
(8,5,104,2,1600),
(9,6,102,1,21000),
(10,7,101,1,51000),
(11,7,105,1,3200),
(12,8,103,4,450),
(13,9,102,2,20500),
(14,10,104,1,1700);

-- query to get the sales report with total completed, pending and cancelled orders and their amounts for each category and quarter
with sales_data as (
	select 
		c.category_name,
        quarter(o.order_date) as quarter,
        o.order_status,
        (oi.quantity * oi.price) as amount
	from report_orders o
    join report_order_items oi
		on o.order_id = oi.order_id
	join report_products p
		on oi.product_id = p.product_id
	join report_categories c
		on p.category_id = c.category_id
)
-- final query to get the sales report with total completed, pending and cancelled orders and their amounts for each category and quarter
select 
	category_name,
    quarter,
    
    count(case when order_status = 'completed' then 1 end)  as completed_orders,
    sum(case when order_status = 'completed' then amount else 0 end) as completed_amount,
    
    count(case when order_status = 'pending' then 1 end) as pending_orders,
    sum(case when order_status = 'pending' then amount else 0 end) as pending_amount,
    
    count(case when order_status = 'cancelled' then 1 end) as cancelled_orders,
    sum(case when order_status = 'cancelled' then amount else 0 end) as cancelled_amount
    
from sales_data
group by category_name,quarter
order by category_name,quarter;
