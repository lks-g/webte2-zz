function deleteFile(file) {
    var confirmation = confirm("Are you sure you want to delete the file '" + file + "'?");
    if (confirmation) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "delete.php?file=" + file, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert(response.message);
                        updateFileList(response.files);
                    } else {
                        alert(response.message);
                    }
                } else {
                    alert("Error: " + xhr.status);
                }
            }
        };
        xhr.send();
    }
}

function deleteAssignmentSet(setId) {
    if (confirm("Are you sure you want to delete this assignment set?")) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                location.reload();
            }
        };
        xhttp.open("GET", "../teacher/delete_assignment_set.php?set_id=" + setId, true);
        xhttp.send();
    }
}


function updateFileList(files) {
    var table = document.querySelector(".data-table table");
    var tbody = table.getElementsByTagName("tbody")[0];

    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }

    files.forEach(function (file) {
        var row = document.createElement("tr");
        var nameCell = document.createElement("td");
        var dateCell = document.createElement("td");
        var actionCell = document.createElement("td");
        var deleteButton = document.createElement("button");

        nameCell.textContent = file.file_name;
        dateCell.textContent = file.date_created;
        deleteButton.textContent = "Delete";
        deleteButton.addEventListener("click", function () {
            deleteFile(file.file_name);
        });

        actionCell.appendChild(deleteButton);
        row.appendChild(nameCell);
        row.appendChild(dateCell);
        row.appendChild(actionCell);
        tbody.appendChild(row);
    });
}