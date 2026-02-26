function showForm(formId) {
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

function togglePassword(inputId, iconElement) {
    const input = document.getElementById(inputId);
    if (!input) return;

    if (input.type === "password") {
        input.type = "text";
        iconElement.classList.remove("fa-eye");
        iconElement.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        iconElement.classList.remove("fa-eye-slash");
        iconElement.classList.add("fa-eye");
    }
}

// Get current date
const dateInput = document.getElementById('attendance-date');
if (dateInput) {
  const todayStr = new Date().toISOString().split('T')[0];
  dateInput.value = todayStr;
}

function buildTable(date) {
    const tbody = document.getElementById('attendance-body');
    tbody.innerHTML = '';

    interns.forEach(intern => {
        const tr = document.createElement('tr');

        const tdId = document.createElement('td');
        tdId.innerHTML = `${intern.id}<input type="hidden" name="intern_id[]" value="${intern.id}">`;

        const tdName = document.createElement('td');
        tdName.textContent = intern.name;
        const tdStatus = document.createElement('td');
        const template = document.getElementById('status-select-template');
        const select = template.content.firstElementChild.cloneNode(true);

        select.name = "status[]";

        tdStatus.appendChild(select);
        tr.appendChild(tdId);
        tr.appendChild(tdName);
        tr.appendChild(tdStatus);
        tbody.appendChild(tr);
    });
}

if (dateInput) {
  dateInput.addEventListener('change', () => {
    buildTable(dateInput.value);
  });
  buildTable(dateInput.value);
}


const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
    datasets: [
      {
        label: 'Present Days',
        data: weeklyData,
        backgroundColor: '#5d83eb'
      },
      {
        label: 'Absent Days',
        data: weeklyAbsent,
        backgroundColor: '#e74c3c'
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top'
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          stepSize: 1
        }
      }
    }
  }
});
