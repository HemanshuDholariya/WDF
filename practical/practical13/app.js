const API_URL = "api/events.php";
const statusDiv = document.getElementById("status");
const eventsBody = document.getElementById("eventsBody");


function showStatus(msg, type) {
statusDiv.innerHTML = '<div class="alert alert-' + type + ' py-2 small">' + msg + '</div>'; setTimeout(function() { statusDiv.innerHTML = ""; }, 3000);
}


function loadEvents(search) {
eventsBody.innerHTML = '<tr><td colspan="6" class="text-center">Loading...</td></tr>'; var url = API_URL;
if (search) url += "?search=" + encodeURIComponent(search);


fetch(url)
.then(function(res) { return res.json(); })
.then(function(data) {

if (data.data.length === 0) {
eventsBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No events found.</td></tr>';
return;
}
var html = ""; data.data.forEach(function(ev) {
html += "<tr>";
html += "<td>" + ev.id + "</td>"; html += "<td>" + ev.title + "</td>";
html += "<td>" + ev.event_date + "</td>"; html += "<td>" + ev.category + "</td>"; html += "<td>" + ev.status + "</td>";
html += '<td><button class="btn btn-warning btn-sm me-1" onclick="editEvent(' + ev.id + ',\'' + ev.title.replace(/'/g, "\\'") + '\',\'' + ev.event_date + '\',\'' + ev.category + '\')">Edit</button>';
html += '<button class="btn btn-danger btn-sm" onclick="deleteEvent(' + ev.id + ')">Delete</button></td>';
html += "</tr>";
});
eventsBody.innerHTML = html;
showStatus("Loaded " + data.data.length + " events.", "success");
})
.catch(function(err) {
eventsBody.innerHTML = '<tr><td colspan="6" class="text-danger text-center">Error loading events.</td></tr>';
showStatus("Error: " + err.message, "danger");
});
}


// Add event via POST function addEvent() {
var title = document.getElementById("addTitle").value.trim();

var date = document.getElementById("addDate").value;
var category = document.getElementById("addCategory").value;


if (!title || !date) {
showStatus("Title and date are required.", "warning"); return;
}


fetch(API_URL, { method: "POST",
headers: { "Content-Type": "application/json" },
body: JSON.stringify({ action: "add", title: title, event_date: date, category: category })
})
.then(function(res) { return res.json(); })
.then(function(data) {
showStatus(data.message, data.status === "success" ? "success" : "danger"); if (data.status === "success") {
document.getElementById("addTitle").value = ""; document.getElementById("addDate").value = ""; loadEvents();
}
})
.catch(function(err) { showStatus("Error: " + err.message, "danger"); });
}


// Edit event via POST (prompt-based for simplicity) function editEvent(id, title, date, category) {
var newTitle = prompt("Edit event title:", title); if (newTitle === null) return;

fetch(API_URL, { method: "POST",

headers: { "Content-Type": "application/json" },
body: JSON.stringify({ action: "update", id: id, title: newTitle, event_date: date, category: category })
})
.then(function(res) { return res.json(); })
.then(function(data) {
showStatus(data.message, data.status === "success" ? "success" : "danger"); loadEvents();
})
.catch(function(err) { showStatus("Error: " + err.message, "danger"); });
}


// Delete event via POST function deleteEvent(id) {
if (!confirm("Are you sure you want to delete this event?")) return;


fetch(API_URL, { method: "POST",
headers: { "Content-Type": "application/json" }, body: JSON.stringify({ action: "delete", id: id })
})
.then(function(res) { return res.json(); })
.then(function(data) {
showStatus(data.message, data.status === "success" ? "success" : "danger"); loadEvents();
})
.catch(function(err) { showStatus("Error: " + err.message, "danger"); });
}


// Debounced AJAX Search var debounceTimer;
document.getElementById("searchBox").addEventListener("input", function() {

clearTimeout(debounceTimer); var value = this.value.trim();
debounceTimer = setTimeout(function() { loadEvents(value);
}, 400);
});
loadEvents();


