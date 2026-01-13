console.log("Day 07 internship practice work");

// --> loops : loops are used for repetation of task until condition will be false

// 1. for loop

// console.log("for loop example :");
for (let i = 1; i <= 5; i++) {   // simple loop print 1 to 5
    // console.log(i);
}

//---> 2.while loop

let ins = 1;
// console.log("while loop example :");

while (ins <= 6) {    // you don't know num of iteration
    // console.log(ins * 2);
    ins++;
}

//---> 3. do while loop

let val = 10;
// console.log("do while loop example :");

do {
    // console.log(val *= 10);  // val = val*10
    val += 20;
} while (val < 1000);   // it will always run first iteration and then chheck condition

//---> 4. for of loop : for of loop need value not

let colors = ["black", "red", "white"];
// console.log("for_of loop example 1 :");

for (let color of colors) {   // for of loop used for array & string
    // console.log(color); 
}

let fname = 'abcd';
// console.log("for_of loop example 2 :");

for (let ch of fname) {  // ch standard name for string
    // console.log(ch);
}


//---> 5. for in loop : used in object

let student = {
    name: "john doe",
    age: "Infinity",
    work: "everywhere"
};
// console.log("for_in loop example :");

for (let key in student) {
    // console.log(key + " : " + student[key]);
}

//---> break statement used for stoping loop

// console.log("break loop example :");

for (let index = 0; index < 5; index++) {
    if (index === 3) break;
    // console.log(index);
}

//---> continue used for skipping condition

// console.log("continue loop example :");

for (let x = 10; x < 15; x++) {
    if (x === 12) continue;  // 12 will be skip
    // console.log(x);
}

//---> pattern

// for (let p = 1; p <= 3; p++) {
//     for (let q = 1; q <= 3; q++) {
//         console.log(p, q);
//     }
// }

// nested loop

// console.log("nested loop example :");

for (let m = 1; m <= 4; m++) {
    for (let n = m; n <= 5; n++) {
        // console.log(m,n);
    }
};

// ---> label break : it break specified loop 

// console.log("loop_break example :");

loop1: for (let z = 1; z < 5; z++) {
    loop2: for (let w = 1; w <= 10; w++) {
        if (z === 3) {
            break loop1;
            // break;
        }
        // console.log(z + "*" + w + "=" + z * w);
    }
}

// ---------Function---------------
// function are reusable block of code that can be called multiple time

function printMe() {
    console.log("Hi i am function !")
}

// printMe();    // function call

//--example of function cal to fahrenheit--

function toCal(fahrenheit) {   // function expression
    return (5 / 9) * (fahrenheit - 32);
}

let result = toCal(100);
// console.log(result);


// normal function 
let myFunction = function (a, b) { return a * b };  // function declaration

// same example with arraow function 
let myFun = (a, b) => a * b;
// arrow fun must be defined before used

// ----------Arrow function ex ------------

let myfun = (value) => {
    console.log(`Hi this is arrow function ${value}`)
}

// myfun("User");


//function rest parameter

function sum(...nums) {
    let sum = 0;
    for(let num of nums) {
        sum += num;
    }
    return sum;
}

// we can find sum of any numbers
let finalres = sum(10,50,60,80) // 200
// console.log(`total sum is : ${finalres}`);


//--example--

const multy = (...args) => {
    let res = 1;

    for(let arg of args) {
        res *= arg;
    }
    return res;
}
// console.log("answer of Multyple :");
// console.log(multy(2,3,6));

let obj = {
    name: "Amar",
    address: { city: "Ahmedabad" }
};

// shallow copy create
let deep = { ...obj };       // pass by value
// copy.address.city = "Surat"; // this affect original

console.log(obj.address.city); // Surat 

function sum1(a, b, c) {
    return a + b + c;
}

let nams = [10, 20, 30];

// console.log(...nams);

// console.log(sum1(...nams)); // 60


let userId = Symbol();

// console.log(typeof(userId)); // Symbol(userId)

let id = Symbol("id");

let user = {
  name25: "Amar",
  [id]: 101
};

user.id = "ADMIN"; // different key

console.log(user.name25);     // Amar
console.log(user[id]);      // 101
console.log(user.id);       // ADMIN




