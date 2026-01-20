document.getElementById("addInternBtn").addEventListener("click", async () => {
  state.ui.error = null;

  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const skills = document.getElementById("skills").value.split(",").map(s => s.trim());

  const error = validateIntern({ name, email });
  if (error) {
    state.ui.error = error;
    return renderApp();
  }

  const isUnique = await checkEmailUnique(email);
  if (!isUnique) {
    state.ui.error = "Email already exists";
    return renderApp();
  }

  state.interns.push({
    id: generateInternId(),
    name,
    email,
    skills,
    status: "ACTIVE"
  });

  renderApp();
});

document.getElementById("addTaskBtn").addEventListener("click", () => {
  state.ui.error = null;

  const title = document.getElementById("taskTitle").value;
  const skills = document.getElementById("taskSkills").value.split(",").map(s => s.trim());
  const internId = document.getElementById("internSelect").value;

  const intern = state.interns.find(i => i.id === internId);
  if (!intern) {
    state.ui.error = "Invalid intern";
    return renderApp();
  }

  if (!canAssignTask(intern, skills)) {
    state.ui.error = "Intern lacks required skills";
    return renderApp();
  }

  state.tasks.push({
    id: state.counters.task++,
    title,
    skills,
    internId,
    status: "ASSIGNED"
  });

  renderApp();
});

renderApp();
