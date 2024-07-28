document.addEventListener('DOMContentLoaded', function() {
    if (sessionStorage.getItem('role')) {
        const role = sessionStorage.getItem('role');
        if (role === 'teacher') {
            loadTeacherData();
        } else if (role === 'student') {
            loadStudentData();
        }
        document.getElementById('auth-forms').style.display = 'none';
        document.getElementById(`${role}-interface`).style.display = 'block';
    }
});

function ajaxRequest(action, data, callback, method = 'POST') {
    const xhr = new XMLHttpRequest();
    xhr.open(method, 'backend.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            callback(xhr.responseText);
        }
    };
    const postData = `action=${action}&${Object.keys(data).map(key => `${key}=${data[key]}`).join('&')}`;
    xhr.send(postData);
}

function login() {
    const username = document.getElementById('login-username').value;
    const password = document.getElementById('login-password').value;
    ajaxRequest('login', { username, password }, function(response) {
        const res = JSON.parse(response);
        if (res.success) {
            sessionStorage.setItem('role', res.role);
            document.getElementById('auth-forms').style.display = 'none';
            if (res.role === 'teacher') {
                document.getElementById('teacher-interface').style.display = 'block';
                loadTeacherData();
            } else if (res.role === 'student') {
                document.getElementById('student-interface').style.display = 'block';
                loadStudentData();
            }
        } else {
            alert(res.message);
        }
    });
}

function register() {
    const id = document.getElementById('register-id').value;
    const username = document.getElementById('register-username').value;
    const password = document.getElementById('register-password').value;
    const role = document.getElementById('register-role').value;
    ajaxRequest('register', { id, username, password, role }, function(response) {
        alert(response);
    });
}

function showAddClassroom() {
    document.getElementById('teacher-operations').innerHTML = `
        <h3>Add Classroom</h3>
        <input type="text" id="classroom-name" placeholder="Classroom Name">
        <button onclick="addClassroom()">Add Classroom</button>
    `;
}

function addClassroom() {
    const classroomName = document.getElementById('classroom-name').value;
    ajaxRequest('add_classroom', { classroom_name: classroomName }, function(response) {
        alert(response);
        loadTeacherData();
    });
}

function showAddStudent() {
    document.getElementById('teacher-operations').innerHTML = `
        <h3>Add Student</h3>
        <input type="text" id="student-id" placeholder="Student ID">
        <input type="text" id="class-name" placeholder="Class Name">
        <button onclick="addStudent()">Add Student</button>
    `;
}

function addStudent() {
    const studentId = document.getElementById('student-id').value;
    const className = document.getElementById('class-name').value;
    ajaxRequest('add_student', { student_id: studentId, class_name: className }, function(response) {
        alert(response);
        loadTeacherData();
    });
}

function showCreateAssignment() {
    document.getElementById('teacher-operations').innerHTML = `
        <h3>Create Assignment</h3>
        <input type="text" id="assignment-class-name" placeholder="Class Name">
        <textarea id="assignment-details" placeholder="Assignment Details"></textarea>
        <button onclick="createAssignment()">Create Assignment</button>
    `;
}

function createAssignment() {
    const className = document.getElementById('assignment-class-name').value;
    const details = document.getElementById('assignment-details').value;
    ajaxRequest('create_assignment', { class_name: className, details }, function(response) {
        alert(response);
        loadTeacherData();
    });
}

function loadTeacherData() {
    ajaxRequest('get_teacher_data', {}, function(response) {
        const data = JSON.parse(response);
        
        // Display classrooms
        let classroomsHtml = '<p>Classrooms:</p>';
        classroomsHtml+= data.classrooms.map(classroom => ` <li>${classroom.name}</li>`).join('');
        document.getElementById('classrooms').innerHTML = classroomsHtml;

        // Display assignments
        let assignmentsHtml = '<p>Assignments:</p>';
        assignmentsHtml += data.assignments.map(assignment => `<li>${assignment.details} (Class: ${assignment.class_name})</li>`).join('');
        document.getElementById('assignments').innerHTML = assignmentsHtml;

        // Display students in each classroom
        let studentsHtml = '';
        for (const [classroomName, students] of Object.entries(data.students)) {
            studentsHtml += `<p>Classroom: ${classroomName}</p><ul>`;
            students.forEach(student => {
                studentsHtml += `<li>${student}</li>`;
            });
            studentsHtml += `</ul>`;
        }
        document.getElementById('students').innerHTML = studentsHtml;
    });
}


function loadStudentData() {
    ajaxRequest('get_student_data', {}, function(response) {
        const data = JSON.parse(response);
        const assignments = data.assignments.map(assignment => `<p>Assignment: ${assignment.details} for ${assignment.class_name}</p>`).join('');
        const submissions = data.submissions.map(submission => `<p>Submission: ${submission.details} for ${submission.class_name}</p>`).join('');
        document.getElementById('assignment-list').innerHTML = assignments;
        document.getElementById('submission-list').innerHTML = submissions;
    });
}

function showSubmitAssignment() {
    document.getElementById('student-operations').innerHTML = `
        <h3>Submit Assignment</h3>
        <input type="text" id="submit-class-name" placeholder="Class Name">
        <textarea id="submit-details" placeholder="Submission Details"></textarea>
        <button onclick="submitAssignment()">Submit Assignment</button>
    `;
}

function submitAssignment() {
    const className = document.getElementById('submit-class-name').value;
    const details = document.getElementById('submit-details').value;
    ajaxRequest('submit_assignment', { class_name: className, details }, function(response) {
        alert(response);
        loadStudentData();
    });
}
