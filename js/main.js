$(document).ready(function () {
  // Load tasks order from local storage
  function loadTasksOrder() {
    var order = localStorage.getItem("tasksOrder");
    if (order) {
      var orderArray = order.split(",");
      var taskList = $("#task-list");
      orderArray.forEach(function (id) {
        var taskItem = $('li[data-id="' + id + '"]');
        taskList.append(taskItem);
      });
    }
  }

  loadTasksOrder();

  $("#task-form").on("submit", function (event) {
    event.preventDefault();
    var taskInput = $("#task-input").val();
    if (taskInput === "") return;

    $.ajax({
      url: "index.php",
      method: "POST",
      data: { task: taskInput },
      success: function (response) {
        $("#task-list").append(response);
        $("#task-input").val("");
        saveTasksOrder();
      },
    });
  });

  $("#task-list").on("click", ".delete", function () {
    var taskItem = $(this).closest("li");
    var taskId = taskItem.data("id");

    $.ajax({
      url: "index.php",
      method: "POST",
      data: { delete_id: taskId },
      success: function () {
        taskItem.remove();
        saveTasksOrder();
      },
    });
  });

  $("#task-list").on("click", ".complete", function () {
    var taskItem = $(this).closest("li");
    var taskId = taskItem.data("id");
    var taskStatus = taskItem.hasClass("completed") ? 0 : 1;

    $.ajax({
      url: "index.php",
      method: "POST",
      data: { update_id: taskId, status: taskStatus },
      success: function () {
        taskItem.toggleClass("completed");
        taskItem.find(".complete").text(taskStatus ? "Undo" : "Complete");
      },
    });
  });

  // Save tasks order to local storage
  function saveTasksOrder() {
    var order = [];
    $("#task-list li").each(function () {
      order.push($(this).data("id"));
    });
    localStorage.setItem("tasksOrder", order.join(","));
  }

  // Make task list sortable and save order on update
  $("#task-list").sortable({
    update: function (event, ui) {
      saveTasksOrder();
    },
  });
});
