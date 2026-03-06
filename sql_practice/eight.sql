create database 08db;

use 08db;

create table transactions (
    transaction_id int primary key,
    transaction_date date,
    amount int
);

insert into transactions values
(1,'2024-01-05',1000),
(2,'2024-01-15',1500),
(3,'2024-02-10',2000),
(4,'2024-02-18',1200),
(5,'2024-03-12',1800),
(6,'2024-03-25',2200),
(7,'2024-04-03',2500),
(8,'2024-04-20',1700),
(9,'2024-05-11',3000),
(10,'2024-05-28',2100),
(11,'2024-06-14',2600),
(12,'2024-06-30',1900),
(13,'2024-07-07',3200),
(14,'2024-07-19',2800),
(15,'2024-08-05',3500),
(16,'2024-08-22',2400),
(17,'2024-09-10',2700),
(18,'2024-09-26',3100),
(19,'2024-10-08',2900),
(20,'2024-10-21',3300),
(21,'2024-11-06',3600),
(22,'2024-11-25',3400),
(23,'2024-12-09',4000),
(24,'2024-12-28',3700),
(25,'2025-01-10',4200),
(26,'2025-01-23',3900),
(27,'2025-02-14',4500),
(28,'2025-02-26',4100),
(29,'2025-03-12',4800),
(30,'2025-03-25',4300);



with monthly_rev as (
	select 
	date_format(transaction_date, '%Y-%m') as month,
    year(transaction_date) as yr,
    month(transaction_date) as mn,
    sum(amount) as revenue
    from 
    transactions
    where
    transaction_date >= curdate() - interval 24 month
    group by 
    date_format(transaction_date,'%Y-%m'),
    year(transaction_date),
    month(transaction_date)
)
select
	month,
    revenue as monthly_rev,
    
    -- running total
    sum(revenue) over (order by yr, mn) as running_total,

    -- year to date total
    sum(revenue) over (partition by yr order by mn) as ytd_total,
    
    -- previous month revenue
    lag(revenue) over (order by yr, mn) as previous_month_revenue
    
from 
    monthly_rev
order by yr, mn;




