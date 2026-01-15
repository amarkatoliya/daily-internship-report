console.log("Hello!");

// creating obj
const person = {
    fname: "xyz",
    lname: "abc",
    age : 20,
    fullname: function() {
        console.log(this.fname + "  "+this.lname)
    }
}

let s = "fname ";
let w = "age";

console.log(person[s]+ " and " + person[w] + " is good" );

// Array of student objects
let students = [
  { name: "Amar", age: 19, course: "IT" },
  { name: "Raj", age: 20, course: "CS" },
  { name: "Neha", age: 18, course: "AI" }
];

// Print whole array
console.log(students);

// Print first student
console.log(students[0]);   

// Print first student name
console.log(students[0].name);

person.add = "surat city"

person.fullname();

let res = person;

console.log(res);  // show full obj

const fruits1 = {Bananas:300, Oranges:200, Apples:500};

let text = "";
for (let [fruit, value] of Object.entries(fruits1)) {
  text += fruit + " : " + value + ", " ;
}

console.log(text);
let tempres = JSON.stringify(fruits1);
console.log(tempres);

// console.log(typeof(tempres)); // string


// Array

const color = ["red","black","green"];

color.push("white")

color[5] = "blue"

console.log(color);
// console.log(color[4]);   //undefined

console.log(color.toString());   // create in str format

// color.length() // give length

color.sort() // sort array
color.reverse() // reverse array
color.pop() // remove last element
color.shift() // remove first element
color.unshift("blue") // add first element


//-----------Example--------------- 
// 1. Create an array of objects
// Array of objects (each object is a user)
let users = [
  { name: "Amar", age: 19, city: "Ahmedabad" },
  { name: "Raj", age: 20, city: "Surat" },
  { name: "Neha", age: 18, city: "Vadodara" }
];

// Iterate using for...of loop
for (let user of users) {
  console.log("Name:", user.name);   // access name
  console.log("Age:", user.age);     // access age
  console.log("City:", user.city);   // access city
  console.log("------------");
}


// 2. array example

// Simple array
let fruits = ["Apple", "Banana", "Mango", "Orange"];

// Iteration using for loop
for (let i = 0; i < fruits.length; i++) {
  console.log(fruits[i]); // print each fruit
}

// for...of loop
for (let fruit of fruits) {
  console.log(fruit); // print each fruit
}

// forEach method
fruits.forEach(function(fruit) {
  console.log(fruit); // print each fruit
});

// Arrow function with forEach
fruits.forEach(fruit => {
  console.log(fruit);
});

// map method
let numbers = [1, 2, 3, 4, 5];
let squaredNumbers = numbers.map(num => num * num);
console.log(squaredNumbers);

// filter method
let evenNumbers = numbers.filter(num => num % 2 === 0);
console.log(evenNumbers);

