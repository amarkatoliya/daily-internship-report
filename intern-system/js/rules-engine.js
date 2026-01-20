function generateInternId() {
  const year = new Date().getFullYear();
  const id = `${year}-${String(state.counters.intern).padStart(3, "0")}`;
  state.counters.intern++;
  return id;
}

function canAssignTask(intern, skills) {
  return skills.every(skill => intern.skills.includes(skill));
}
