<?php

require "task13.php";


use Base\Payment\Service as PaymentService;
use Base\Notification\Service as NotificationService;

$payment = new PaymentService();
$notify = new NotificationService();

$payment->process();
echo "<br>";
$notify->send();


//---> School example


use School\Staff\Person as TeacherPerson;

use School\Members\Person as StudentPerson;

$teacher = new TeacherPerson();

$student = new StudentPerson();

$teacher->info();
echo "<br>";
$student->info();

?>