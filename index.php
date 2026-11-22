
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Management System</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body {
    background: #eef2f7;
    padding: 30px;
}

.container {
    max-width: 1100px;
    margin: auto;
}

header {
    background: #182848;
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
}

header h1 {
    margin-bottom: 8px;
}

.dashboard {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    flex: 1;
    box-shadow: 0 3px 10px #ddd;
}

.card h2 {
    color: #182848;
    font-size: 30px;
}

.form-box, .table-box {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 25px;
}

.form-box h2, .table-box h2 {
    margin-bottom: 20px;
    color: #182848;
}

input, select {
    width: 100%;
    padding: 12px;
    margin: 8px 0 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    background: #182848;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #3455a4;
}

.search {
    max-width: 350px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 13px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background: #182848;
    color: white;
}

.delete {
    background: #dc3545;
    padding: 8px 12px;
}

@media(max-width:700px) {
    .dashboard {
        flex-direction: column;
    }

    body {
        padding: 12px;
    }

    .table-box {
        overflow-x: auto;
    }
}
</style>
</head>

<body>

<div class="container">

<header>
    <h1>Student Management System</h1>
    <p>Admin Dashboard | Academic Management</p>
</header>

<div class="dashboard">
    <div class="card">
        <p>Total Students</p>
        <h2 id="total">0</h2>
    </div>

    <div class="card">
        <p>System Status</p>
        <h2>Active</h2>
    </div>
</div>

<div class="form-box">
    <h2>Register New Student</h2>

    <form id="studentForm">
        <label>Student Name</label>
        <input type="text" id="name"
        placeholder="Enter full name" required>

        <label>Roll Number</label>
        <input type="text" id="roll"
        placeholder="Enter roll number" required>

        <label>Email Address</label>
        <input type="email" id="email"
        placeholder="Enter email" required>

        <label>Department</label>
        <select id="department" required>
            <option value="">Select Department</option>
            <option>Computer Engineering</option>
            <option>Information Technology</option>
            <option>AI & Data Science</option>
            <option>Mechanical Engineering</option>
        </select>

        <button type="submit">Add Student</button>
    </form>
</div>

<div class="table-box">
    <h2>Student Records</h2>

    <input type="text" class="search"
    id="search" placeholder="Search student..."
    onkeyup="searchStudent()">

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Roll No.</th>
                <th>Email</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="studentList"></tbody>
    </table>
</div>

</div>

<script>
let students = [];

document.getElementById("studentForm")
.addEventListener("submit", function(e) {

    e.preventDefault();

    let name = document.getElementById("name").value;
    let roll = document.getElementById("roll").value;
    let email = document.getElementById("email").value;
    let department = document.getElementById("department").value;

    if(students.some(s => s.roll === roll)) {
        alert("Roll number already exists!");
        return;
    }

    students.push({
        name, roll, email, department
    });

    displayStudents();
    this.reset();
});

function displayStudents(data = students) {

    let list = document.getElementById("studentList");

    list.innerHTML = "";

    data.forEach((student) => {

        let index = students.indexOf(student);

        list.innerHTML += `
        <tr>
            <td>${student.name}</td>
            <td>${student.roll}</td>
            <td>${student.email}</td>
            <td>${student.department}</td>
            <td>
                <button class="delete"
                onclick="deleteStudent(${index})">
                Delete
                </button>
            </td>
        </tr>`;
    });

    document.getElementById("total").innerText =
    students.length;
}

function deleteStudent(index) {

    if(confirm("Are you sure you want to delete?")) {
        students.splice(index, 1);
        displayStudents();
    }
}

function searchStudent() {

    let value = document.getElementById("search")
    .value.toLowerCase();

    let filtered = students.filter(s =>
        s.name.toLowerCase().includes(value) ||
        s.roll.toLowerCase().includes(value) ||
        s.department.toLowerCase().includes(value)
    );

    displayStudents(filtered);
}
</script>

</body>
</html>
