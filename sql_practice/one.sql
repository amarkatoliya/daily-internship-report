create database practice_db;

use practice_db;

-- create emp table

CREATE TABLE employees(
	emp_id INT PRIMARY KEY,
    emp_name VARCHAR(256),
    manager_id INT,
    
    FOREIGN KEY (manager_id) REFERENCES employees(emp_id)
); 

INSERT INTO employees VALUES
(1,'CEO',NULL),
(2,'Manager1',1),
(3,'Manager2',1),
(4,'Employee1',2),
(5,'Employee2',2);

-- query to get the hierarchy of employees with their managers 

with recursive org as(
	select emp_id, emp_name, manager_id, 1 as level, emp_name as path
    from employees
    where manager_id is null
    
    union all
    
    select e.emp_id, e.emp_name, e.manager_id, o.level + 1,
		concat(o.path,' -> ' ,e.emp_name)
    from employees e
    join org o on e.manager_id = o.emp_id
)

select * from org;


