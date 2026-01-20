function validateIntern(data) {
  if (!data.name || !data.email) {
    return "Name and Email are required";
  }
  return null;
}

function validateTask(data) {
  if (!data.title || !data.internId) {
    return "Task title and intern required";
  }
  return null;
}
