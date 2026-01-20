function renderMessage() {
  const msg = document.getElementById("message");
  msg.textContent = state.ui.error || "";
}

function renderInterns() {
  const tbody = document.getElementById("internTable");
  tbody.innerHTML = "";

  state.interns.forEach(intern => {
    const tr = document.createElement("tr");

    const taskCount = state.tasks.filter(t => t.internId === intern.id).length;

    tr.innerHTML = `
      <td>${intern.id}</td>
      <td>${intern.name}</td>
      <td>${intern.status}</td>
      <td>${intern.skills.join(", ")}</td>
      <td>${taskCount}</td>
    `;
    tbody.appendChild(tr);
  });
}

function renderInternDropdown() {
  const select = document.getElementById("internSelect");
  select.innerHTML = "<option value=''>Select Intern</option>";

  state.interns
    .filter(i => i.status === "ACTIVE")
    .forEach(i => {
      const option = document.createElement("option");
      option.value = i.id;
      option.textContent = i.name;
      select.appendChild(option);
    });
}

function renderApp() {
  renderMessage();
  renderInterns();
  renderInternDropdown();
}
