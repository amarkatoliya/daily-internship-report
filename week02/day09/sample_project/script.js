const inputWeight = document.getElementById('inWeight');
const inputHeight = document.getElementById('inHeight');  // Height in cm
const calBtn = document.getElementById('bmiBtn');
const outputBtn = document.getElementById('output');

calBtn.addEventListener('click', (e) => {
    const w = parseFloat(inputWeight.value); // Convert input to float
    const h = parseFloat(inputHeight.value); // Convert input to float

    // Debugging: Log the values
    console.log("Weight:", w);
    console.log("Height (cm):", h);

    if (isNaN(w) || isNaN(h) || w < 0 || h < 0) {
        alert('Please enter valid numbers for weight and height.');
    } else {
        // Convert height from cm to meters
        const heightInMeters = h / 100;

        // BMI formula: weight / (height in meters)^2
        const result = w / (heightInMeters * heightInMeters);
        console.log("BMI Result:", result); // Debugging: Log the result


        let statusMessage = '';

        if (result <= 18.5) {
            statusMessage = 'You are Underweight';
        } else if (result > 18.5 && result <= 24.9) {
            statusMessage = 'You are Normal';
        } else if (result > 25 && result <= 29.9) {
            statusMessage = 'You are Overweight';
        } else if (result > 30 && result <= 34.9){
            statusMessage = 'You are Obese class 1';
        } else if (result > 35 && result <= 39.9){
            statusMessage = 'You are Obese class 2';
        } else {
            statusMessage = 'You are Obese class 3';
        }

        // Display the message along with BMI
        outputBtn.textContent = `Your BMI is: ${result.toFixed(2)}. ${statusMessage}`;
    }
});