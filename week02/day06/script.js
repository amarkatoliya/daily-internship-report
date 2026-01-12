// Used for message printing or debug
console.log("Hi! There");

//  Used to show error messages (red color).
console.error("here at line 5 error");

// Used to show warnings (yellow color)
console.warn("here at line 8 warning given");

// Used to message to the console at the 'info' log level
console.info("info message");
// console.info(document.body)

// object data type
let users = [
    { name: "Amar", age: 20 },
    { name: "Rahul", age: 22 }
];

// print data in table format
console.table(users);

// used for measurement of execution
console.time("timing");

for (let i = 0; i < 1000; i++) { }

console.timeEnd("timing");

// oprators

let a = 10, b = 20;

// 1.Arithmatic Operator
console.log("Here arithmatic opration answer :");

console.log(a + b);
console.log(b - a);
console.log(a * b);
console.log(b / a);
console.log(2 * (a + b) / 10);
console.log(a ** 2); //power
console.log(b % 2); //reminder

// 2.Assignment Operator: used to assign value
console.log("Here assignment opration answer :");

let x = 90;

x += 25; // x = x+25  = 115
x -= 15; // x = x-15  = 100
x *= 5;  // x = x*5   = 500
x /= 10; // x = x/10  = 50

console.log(x);

// 3. Comparision Operator: used for compare
console.log("Here Comparision opration answer :");
console.log(10 !== "10");

10 == "10";   // true (value only)
10 === "10";  // false (value + type)
10 != 5;      // true
10 !== "10";  // true
10 > 5;       // true
10 < 5;       // false



// 4. logical Operator: used with boolean

true && false // false AND
true || false // true OR
!true // false NOT

// let age = 22;
// age > 18 && age < 60 // true

// 5. String Operator

let fname = "Narendra";
let lname = "Modi";

console.log("Here String opration answer :");
console.log(fname + " " + lname);

// 6. Type Operator

console.log("Here type opration answer :");

console.log(typeof (10));  //num
console.log(typeof ("Hey"));  //string
console.log(typeof (null));  //object
console.log(typeof (undefined));  //undefined
console.log(typeof (Function));  //function

// 7. Bitwise Operator

// &  AND
// |  OR
// ~  NOT


// 8. Ternary Operator

let ageLimit = 17;

let result = ageLimit >= 18 ? "Adult" : "Minor";


// if else case

console.log("Here if-else example answer :");

let age = 18;

if (age >= 18) {
    console.log("Eligible for vote");
} else {
    console.log("Not Eligible for vote");
}

// -- second example
let marks = 75;

if (marks >= 90) {
    console.log("Grade A");
} else if (marks >= 70) {
    console.log("Grade B");
} else if (marks >= 50) {
    console.log("Grade C");
} else {
    console.log("Fail");
}

// -- third example

let username = "admin";
let password = "12345";

if (username === "admin" && password === "12345") {
    console.log("Login successful");
} else {
    console.log("Invalid credentials");
}


// switch case
console.log("Here switch case example answer :");

let choice = 2;

switch (choice) {
    case 1:
        console.log("Check Balance");
        break;
    case 2:
        console.log("Withdraw Money");
        break;
    case 3:
        console.log("Deposit Balance");
        break;

    default:
        console.log("Invalid choice");
}

// second example

let day = 50;

switch (true) {
    case 1:
        console.log("Monday");
        break;
    case 2:
        console.log("Tuesday");
        break;
    case 3:
        console.log("Wednesday");
        break;
    case 4:
        console.log("Thresday");
        break;
    case 5:
        console.log("Friday");
        break;
    case 6:
        console.log("Saturday");
        break;
    case 7:
        console.log("Sunday");
        break;

    default:
        console.log("Invaid option");
}


console.log("falsy value");


// -----truthy values-------

// true
// 1
// -1
// "hello"
// " "        // string with space
// "0"
// []
// {}
// function(){}
// there are many truthy values

// -----falsy value-------

// false
// 0
// -0
// 0n        // BigInt zero
// ""        // empty string
// null
// undefined
// NaN


// comparing two object is not adviceable

const m = { city: "surat" };  // comparision done pass by reference
const n = { city: "surat" };

console.log(m === n);  // false 


// random picker game

let min = 10;
let max = 20;

let val = Math.floor(Math.random() * (max - min + 1) + min)

console.log(`Your pick is : ${val}`);

// ---------------------------------------
let val1 = NaN;

if (val1 === val1) {
    console.log("NaN is equal");
} else {
    console.log("NaN is not equal with NaN");
}
// -------------------------------------

let val3 = "abc";

if (val3 === val3 && val3 - val3 === 0) {
    console.log("Valid Num");
} else {
    console.log("Not Valid Num")
}

// str - str === NaN
// str * str === NaN
// str + str === strstr

let num1 = Math.PI;
let num9 = num1.toPrecision(50);
console.log(num9);
// ---------
//  "" - "" === 0   ------> true
// ---------
