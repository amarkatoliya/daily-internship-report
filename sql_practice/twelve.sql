create database 12task;

use 12task;

create table categories (
    category_id int primary key,
    category_name varchar(100)
);

insert into categories values
(1,'electronics'),
(2,'clothing'),
(3,'home');

create table regions (
    region_id int primary key,
    region_name varchar(100)
);

insert into regions values
(1,'north'),
(2,'south'),
(3,'west');

create table sales (
    sale_id int primary key,
    category_id int,
    region_id int,
    amount int,
    
    foreign key (category_id) references categories(category_id),
    foreign key (region_id) references regions(region_id)
);

insert into sales values
(1,1,1,5000),
(2,1,2,4000),
(3,1,3,3000),
(4,2,1,3500),
(5,2,2,2800),
(6,2,3,2000),
(7,3,1,4200),
(8,3,2,3100),
(9,3,3,2500),
(10,1,1,1500),
(11,2,2,1200),
(12,3,3,1700);


select 
    coalesce(c.category_name,'all categories') as category,
    coalesce(r.region_name,'all regions') as region,
    sum(s.amount) as total_sales
from sales s
join categories c 
    on s.category_id = c.category_id
join regions r 
    on s.region_id = r.region_id
    
group by c.category_name, r.region_name with rollup;