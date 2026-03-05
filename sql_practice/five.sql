use practice_db;

create table sales_orders (
    order_id int primary key,
    order_date date
);

create table sales_order_items (
    order_item_id int primary key,                      
    order_id int,
    product_id int,
    
    foreign key (order_id) references sales_orders(order_id)
);

select * from sales_order_items;

select * from sales_orders;

INSERT INTO sales_orders VALUES
(1,'2026-03-01'),
(2,'2026-03-01'),
(3,'2026-03-02'),
(4,'2026-03-02'),
(5,'2026-03-03'),
(6,'2026-03-03'),
(7,'2026-03-04'),
(8,'2026-03-04'),
(9,'2026-03-05'),
(10,'2026-03-05');

INSERT INTO sales_order_items VALUES
(1,1,101),
(2,1,102),
(3,1,103),
(4,2,101),
(5,2,102),
(6,3,101),
(7,3,103),
(8,4,102),
(9,4,103),
(10,5,101),
(11,5,102),
(12,6,101),
(13,6,102),
(14,7,101),
(15,7,103),
(16,8,102),
(17,8,103),
(18,9,101),
(19,9,102),
(20,10,101),
(21,10,102),
(22,10,103);

-- query to get all the product pairs that have been ordered together more than 3 times
with product_pairs as (
	select 
		a.product_id as product_1,
        b.product_id as product_2,
        count(*) as pair_count
	from sales_order_items a
    join sales_order_items b
		on a.order_id = b.order_id
        and a.product_id < b.product_id
        group by a.product_id , b.product_id
),
-- query to get total number of orders
total_orders as (
	select count(*) as total 
    from sales_orders
)
-- final query to get the product pairs with their count and percentage of orders they were ordered together in
select 
	p.product_1,
    p.product_2,
    p.pair_count,
    round((p.pair_count *100 / t.total),2) as percentage_orders
    from product_pairs p
    join total_orders t
    having p.pair_count > 3;
    -- here for output purpose i have added 3 for our task it will be 10 


