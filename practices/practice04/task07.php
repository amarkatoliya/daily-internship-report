<?php

class Employee
{
    public $name;      // Accessible anywhere
    private $salary;    //  // Accessible anywhere

    // constructor

    public function __construct($name, $salary)
    {
        $this->name = $name;
        $this->salary = $salary;
    }

    // public method  to  access private salary
    public function getSalary()
    {
        return $this->salary;
    }
}

$emp1 = new Employee("Amit", 50000);

echo $emp1->name;
echo "<br>";
echo $emp1->getSalary();


// Another Example (Different Concept)

class Student
{

    public $name;
    private $marks;

    public function __construct($name, $marks)
    {
        $this->name = $name;
        $this->marks = $marks;
    }

    public function getResult()
    {
        if ($this->marks >= 40) {
            return "Pass";
        } else {
            return "Fail";
        }
    }
}

$s1 = new Student("Rahul", 72);

echo $s1->name;
echo "<br>";
echo $s1->getResult()

?>