<?php


class Employee {

    public $name;
    private $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    // Getter     
    // Getter = method that reads private property.
    public function getSalary() {
        return $this->salary;
    }

    // Setter
    // Setter = method that updates private property with validation.
    public function setSalary($amount) {
        if ($amount > 0) {
            $this->salary = $amount;
        } else {
            echo "Salary must be positive<br>";
        }
    }
}

$emp = new Employee("Neha", 40000);

echo $emp->getSalary();
echo "<br>";

$emp->setSalary(55000);
echo $emp->getSalary();
echo "<br>";

$emp->setSalary(-2000);

echo "<br>";

class BankAccount {

    private $balance = 0;

    public function deposit($amount) {
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }

    public function withdraw($amount) {
        if ($amount <= $this->balance) {
            $this->balance -= $amount;
        }
    }

    public function getBalance() {
        return $this->balance;
    }
}

$acc = new BankAccount();

$acc->deposit(5000);
$acc->withdraw(2000);

echo $acc->getBalance();


?>