create database 10task;

use 10task;

create table system1_inventory (
    product_id int primary key,
    product_name varchar(100),
    stock int
);

insert into system1_inventory values
(101,'laptop',50),
(102,'mouse',30),
(103,'keyboard',20),
(104,'monitor',15);

create table system2_inventory (
    product_id int primary key,
    product_name varchar(100),
    stock int
);

insert into system2_inventory values
(101,'laptop',50),
(102,'mouse',25),
(104,'monitor',15),
(105,'printer',40);

select 
	s1.product_id,
    s1.stock as s1_stock,
    s2.stock as s2_stock,
    case
		when s1.product_id is null then 'missing_in_sys1'
        when s2.product_id  is null then 'missing_in_sys2'
        when s1.stock = s2.stock then 'match'
		else 'stock_mismatch'
    end as status
from system1_inventory s1
left join system2_inventory s2
on s1.product_id = s2.product_id

union

select
	s2.product_id,
    s1.stock as s1_stock,
    s2.stock as s2_stock,
    case
		when s1.product_id is null then 'missing_in_sys1'
        when s2.product_id is null then 'missing_in_sys2'
        when s1.stock = s2.stock then 'match'
        else 'stock_mismatch'
	end as status

from system2_inventory s2
left join system1_inventory s1
on s2.product_id = s1.product_id
where s1.product_id is null;




