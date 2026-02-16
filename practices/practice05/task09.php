<?php

// inherits properties and methods of another class ex :

class Staff {    // Base Class

    public $employeeName;
    protected $employeeSalary;

    public function __construct($employeeName, $employeeSalary) {
        $this->employeeName = $employeeName;
        $this->employeeSalary = $employeeSalary;
    }

    public function getInfo() {
        return "Name: $this->employeeName, Salary: $this->employeeSalary";
    }
}

// Child Class

class TeamLead extends Staff {

    public $teamName;

    public function __construct($employeeName, $employeeSalary, $teamName) {
        parent::__construct($employeeName, $employeeSalary);
        $this->teamName = $teamName;
    }

    // Method overriding
    public function getInfo() {
        return "Name: $this->employeeName, Salary: $this->employeeSalary, Team: $this->teamName";
    }
}

$leadObj = new TeamLead("Amit", 90000, "Development");

echo $leadObj->getInfo();

?>
