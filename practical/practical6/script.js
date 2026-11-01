// const studentListElement = document.getElementById('studentlist');

// fetch('student.json')
//   .then((response) => response.json())

//   .then((data) => {
//     console.log(data);

//     data.forEach((student) => {
//       studentListElement.innerHTML += `

//         <div>

//           <h3>${student.id}</h3>
//           <p>name: ${student.name}</p>
//           <p>department: ${student.Department}</p>
//           <p>semester: ${student.Semester}</p>

//         </div>

//       `;
//     });
//   })
//   .catch((error) => console.error('Error loading data:', error));

const studentListElement = document.getElementById('studentlist');
const deptDropdown = document.getElementById('deptDropdown');

let allStudents = [];


fetch('student.json')
  .then(response => response.json())
  .then(data => {
    allStudents = data;
    renderStudents(allStudents); 
  });


deptDropdown.addEventListener('change', () => {
  const selectedValue = deptDropdown.value;

  if (selectedValue === 'all') {
    renderStudents(allStudents);
  } else {
    
    const filtered = allStudents.filter(student => student.department.toLowerCase() === selectedValue);
    renderStudents(filtered);
  }
});


function renderStudents(students) {
  studentListElement.innerHTML = '';

  students.forEach(student => {
    studentListElement.innerHTML += `
      <div>
        <h3>${student.name}</h3>
        <p>Department: ${student.department}</p>
        <p>Semester: ${student.semester}</p>
      </div>
    `;
  });
}

