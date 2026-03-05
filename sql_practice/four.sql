use practice_db;

create table product (
    product_id int primary key,
    product_name varchar(50)
);

create table price_history (
    price_id int primary key,
    product_id int,
    price decimal(10,2),
    price_date date,
    foreign key (product_id) references product(product_id)
);

insert into product values
(1,'laptop'),
(2,'phone');

insert into price_history values
(1,1,50000,'2026-01-01'),
(2,1,52000,'2026-02-01'),
(3,1,51000,'2026-03-01'),
(4,2,20000,'2026-01-10'),
(5,2,21000,'2026-02-15'),
(6,2,22000,'2026-03-01');

-- query to get the price changes for each product over the last 90 days, along with the percentage change from the previous price and the next price (if available)

with price_data as (
    select 
        p.product_name,
        ph.product_id,
        ph.price,
        ph.price_date,
        lag(ph.price) over (partition by ph.product_id order by ph.price_date) as previous_price,
        lead(ph.price) over (partition by ph.product_id order by ph.price_date) as next_price
    from price_history ph
    join product p on ph.product_id = p.product_id
    where ph.price_date >= curdate() - interval 90 day
)

select 
    product_name,
    price as current_price,
    previous_price,
    next_price,
    round(
        ((price - previous_price) / previous_price) * 100,
        2
    ) as percent_change
from price_data;