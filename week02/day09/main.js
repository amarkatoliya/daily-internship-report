document.title = "D9"; // Document to D9

// document.body.style.backgroundColor = "lightblue";

//---> select element with id
const myText = document.getElementById('demo');

//---> add text in html tag
// myText.innerText = "This is my text";

// it will select all p tags and create element array
let element = document.getElementsByTagName("p");     // gives htmlcollection

// element[2].innerText = "";

//---> select with class name

const x = document.getElementsByClassName("main")    // gives htmlcollection

// x[0].innerHTML = "<b>rt</b>"

// ---> create full div in js
const xyz = document.createElement("div");  // create div tag

xyz.innerHTML = "<b> Hello i am custom div </b>";

document.body.appendChild(xyz);

xyz.remove();  // remove created xyz div


// ---> btn events

const btn = document.getElementById("btn");
btn.onclick = function () {
    alert("button 1 clicked")
};

// here eventlistener that log standard time
const button = document.getElementById("btnSec");

const date = new Date();
button.addEventListener("click", () => {
    console.log(date);
});

dateBtn.addEventListener("mouseover", () => {
    dateBtn.innerHTML = date;
})
dateBtn.addEventListener("mouseout", () => {
    dateBtn.innerHTML = "Time ??";
})

// form value get

const form = document.getElementById("frm1");
const input = document.getElementById("username");
const input2 = document.getElementById("username2");

form.addEventListener("submit", (event) => {
    event.preventDefault();   // stop page reload
    console.log(input.value); // input 1 res
    console.log(input2.value); // input 2 res
});


function hideText() {
    document.getElementById('p1').style.visibility = 'hidden'
}

function showText() {
    document.getElementById('p1').style.visibility = 'visible'
}

// getting all elements in code with *
const all = document.getElementsByTagName('*');

let allTags = "";
for (let i = 0; i < all.length; i++) {
    allTags += all[i].tagName + "<br>";
}

// document.getElementById("all").innerHTML = allTags;

// box.addEventListener("mousemove", (e) => {
//   console.log(e.clientX, e.clientY);
// });
