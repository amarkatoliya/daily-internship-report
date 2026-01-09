console.log("Hello in browser for js debugging")

// js comment

var x = 10;  // now not used not recommanded       

let y = 20;  // use whenever you have flexible value

const z = 30;  // constant not change


let case1 = 13;        // Number
let case2 = "Ramesh";  // String
let case3 = undefined; //undefined
let case4 = null;      // Object
let case5 = true;      //boolean
let case6 = 56;       //num

console.log(typeof (case4));


let age = 18;

console.log('Age is', age);  // output : Age is 18

let name = "xyz";

console.log(`Welcome,${name}`);
//console type

console.dir(document.body);

console.time("loop");
for (let i = 0; i < 100; i++) { }
console.timeEnd("loop");


console.warn("Warning message");
console.error("Error message");

function myFunction() {
    document.getElementById('textId').innerText = "This is Changed text"
}

