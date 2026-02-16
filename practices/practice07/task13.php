<?php

//-- Namespace is like a folder for classes.

//-- It prevents class name conflicts.

namespace Base\Payment;

class Service {
    public function process() {
        echo "Processing payment...";
    }
}
namespace Base\Notification;

class Service {
    public function send() {
        echo "Sending notification...";
    }
}

//-- Two classes have same name like servise

namespace School\Staff;

class Person {
    public function info() {
        echo "I am Teacher";
    }
}

namespace School\Members;

class Person {
    public function info() {
        echo "I am Student";
    }
}
?>