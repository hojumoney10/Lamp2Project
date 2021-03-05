// Title:       employees.js
// Application: INFO-5094 LAMP 2 Employee Project
// Purpose:     jQuery handling AJAX calls
// Author:      G. Blandford, Group 5, INFO-5094-01-21W
// Date:        March 2nd, 2021 (March 2nd, 2021)

$(document).ready(function() {

    $("#form-employees").submit( function(event){
		$.post("../ajax/employees.php", $(this).serialize(), displayEmployee);
		event.preventDefault();	 
	});

    $("#form-employee").submit( function(event){
		$.post("../ajax/employees.php", $(this).serialize(), saveEmployee);
		event.preventDefault();	 
	});

    // function post-save actions
    var saveEmployee = function(response) {

        // Reset action
        $('#save-action').val("");

console.log(response);
        if (response.status == "OK") {

            // Show employees
            $.get("../ajax/employees.php", showEmployees);

            // Dismiss the modal
            $("#edit-employee-modal").modal('hide');

        } else {
            // show error!
        }
    }

    // function to display the employee form
    var displayEmployee = function(response) {

        // Reset action
        $('#action').val("");

console.log(response);
        let employee;

        if (response.status == "OK" || response.employee.length()) {

            employee = response.employee[0];

            $('#employee-id').val(employee.employee_id);
            $('#first-name').val(employee.first_name);
            $('#middle-name').val(employee.middle_name);
            $('#last-name').val(employee.last_name);
            $('#job-type').val(employee.job_type);
            $('#date-of-birth').val(employee.date_of_birth);
            $('#gender').val(employee.gender);
            $('#date-hired').val(employee.date_hired);
            $('#hired-salary-level').val(employee.hired_salary_level);

            // Show the modal form
            $("#edit-employee-modal").modal('show');
        
        } else {
            // show error!
        }
    }

    // function to show employees table
    var showEmployees = function(response) {

console.log(response);        
        let employee;

        if (response.status == "OK" || response.employees.length()) {
            $("#tbody-employees").html("")

            for (row in response.employees) {
                employee = response.employees[row];

                $("#tbody-employees").append(
                    "<tr>"
                    + '<th><input type="radio" style="width:10px;" name="selected[]" value="' + employee.employee_id + '"></th>'
                    + "<td>" + employee.employee_id + "</td>"
                    + "<td>" + employee.full_name + "</td>"
                    + "<td>" + employee.job_type + "</td>"
                    + "<td>" + employee.date_of_birth + "</td>"
                    + "<td>" + employee.gender + "</td>"
                    + "<td>" + employee.date_hired + "</td>"
                    + "<td>" + employee.hired_salary_level + "</td>"
                    + "</tr>"
                );
            }
        } else {
            $("#tbody-employees").html('<tr>' +
                '<td>No employees found.</td>' +
                '</tr>');
        }
    }
    $.get("../ajax/employees.php", showEmployees);
});

