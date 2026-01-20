function fakeDelay(ms = 800) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

async function checkEmailUnique(email) {
  await fakeDelay();
  return !state.interns.some(i => i.email === email);
}
