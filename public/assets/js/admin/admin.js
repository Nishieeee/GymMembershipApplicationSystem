$(document).ready(function () {
  // ===== TAB SWITCHING =====
  $(".tab-button").on("click", function () {
    const tabName = $(this).data("tab");

    // Remove active class from all buttons and contents
    $(".tab-button").removeClass("active").addClass("text-gray-400");
    $(".tab-content").removeClass("active");

    // Add active class to clicked button and corresponding content
    $(this).addClass("active").removeClass("text-gray-400");
    $("#" + tabName).addClass("active");
  });

  // ===== PLAN MODAL =====
  $("#btnAddPlan").on("click", function () {
    $("#planForm")[0].reset();
    $("#planForm h3").text("Add New Plan");
    $("#submitPlanBtn").text("Create Plan");
    $("#planModal").addClass("show");
    $("body").css("overflow", "hidden");
  });

  // Close plan modal
  $(".modal-close, .modal-cancel").on("click", function () {
    $(this).closest(".modal-backdrop").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // Close modal when clicking backdrop
  $("#planModal, #memberModal").on("click", function (e) {
    if ($(e.target).is(this)) {
      $(this).removeClass("show");
      $("body").css("overflow", "auto");
    }
  });
});

// ===== PLAN ACTIONS =====
$(document).on("click", ".btn-edit-plan", function () {
  showAlert("Edit plan functionality - Add your implementation", "info");
});

$(document).on("click", ".btn-delete-plan", function () {
  const planCard = $(this).closest(".plan-card");
  if (confirm("Are you sure you want to delete this plan?")) {
    planCard.fadeOut(300, function () {
      $(this).remove();
      showAlert("Plan deleted successfully", "success");
    });
  }
});
// Close member modal
$(".member-modal-close").on("click", function () {
  $("#memberModal").removeClass("show");
  $("body").css("overflow", "auto");
});
$(document).ready(function () {
  // ===== ADD MEMBER MODAL =====
  $("#btnAddMember").on("click", function () {
    $("#addMemberForm")[0].reset();
    $("#addMemberMessage").addClass("hidden");
    $("#addMemberModal").addClass("show");
    $("body").css("overflow", "hidden");
  });

  // Close add member modal
  $(".add-member-close, .add-member-cancel").on("click", function () {
    $("#addMemberModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // ===== ADD WALK-IN MODAL =====
  $("#btnAddWalkIn").on("click", function () {
    $("#addWalkInForm")[0].reset();
    $("#walkInMessage").addClass("hidden");
    // Set today's date and time as default
    const now = new Date();
    const datetime = now.toISOString().slice(0, 16);
    $("#visitTime").val(datetime);
    $("#addWalkInModal").addClass("show");
    $("body").css("overflow", "hidden");
  });

  // Calculate payment amount based on session type
  $("#sessionType").on("change", function () {
    const selectedOption = $(this).find("option:selected");
    const price = selectedOption.data("price");
    const sessionType = selectedOption.val();

    if (price) {
      $("#paymentAmount").val(price);

      // Calculate end date based on session type
      const visitTime = $("#visitTime").val();
      if (visitTime) {
        calculateEndDate(visitTime, sessionType);
      }
    } else {
      $("#paymentAmount").val("");
      $("#endDate").val("");
    }
  });

  // Update end date when visit time changes
  $("#visitTime").on("change", function () {
    const visitTime = $(this).val();
    const sessionType = $("#sessionType").val();

    if (visitTime && sessionType) {
      calculateEndDate(visitTime, sessionType);
    }
  });

  function calculateEndDate(visitTime, sessionType) {
    const startDate = new Date(visitTime);
    let endDate = new Date(startDate);

    switch (sessionType) {
      case "single":
        // Single session - 3 hours
        endDate.setHours(endDate.getHours() + 3);
        break;
      case "day_pass":
        // Day pass - until end of day (11:59 PM)
        endDate.setHours(23, 59, 0, 0);
        break;
      case "weekend":
        // Weekend pass - 2 days
        endDate.setDate(endDate.getDate() + 2);
        endDate.setHours(23, 59, 0, 0);
        break;
    }

    $("#endDate").val(endDate.toISOString().slice(0, 16));
  }

  // Close walk-in modal
  $(".walkin-close, .walkin-cancel").on("click", function () {
    $("#addWalkInModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // ===== ADD WALK-IN FORM SUBMISSION WITH AJAX =====
  $("#addWalkInForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = $("#btnSubmitWalkIn");
    const originalText = submitBtn.text();

    // Show loading state
    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=User&action=validateWalkin",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showWalkInMessage("✓ " + response.message, "success");
          setTimeout(() => {
            $("#addWalkInModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          showWalkInMessage(
            response.message || "Failed to register walk-in",
            "error"
          );
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.error("Walk-in registration error:", error);
        let errorMessage = "An error occurred. Please try again.";

        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.error) {
          errorMessage = xhr.responseJSON.error;
        }

        showWalkInMessage(errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });

  // ===== ADD MEMBER FORM VALIDATION =====
  $("#addMemberForm").on("submit", function (e) {
    e.preventDefault();

    const password = $('input[name="password"]').val();
    const confirmPassword = $('input[name="cPassword"]').val();

    if (password.length < 8) {
      showAddMemberMessage("Password must be at least 8 characters", "error");
      return false;
    }

    if (password !== confirmPassword) {
      showAddMemberMessage("Passwords do not match", "error");
      return false;
    }

    // If validation passes, submit via AJAX
    const formData = new FormData(this);
    const submitBtn = $("#btnSubmitMember");
    const originalText = submitBtn.text();

    // Show loading state
    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=Admin&action=registerMember",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showAddMemberMessage("✓ " + response.message, "success");
          setTimeout(() => {
            $("#addMemberModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          // Handle validation errors
          if (response.errors) {
            let errorMessages = "";
            for (let field in response.errors) {
              errorMessages += response.errors[field] + "<br>";
            }
            showAddMemberMessage(errorMessages, "error");
          } else {
            showAddMemberMessage(
              response.message || "Failed to add member",
              "error"
            );
          }
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.error("Add member error:", error);
        let errorMessage = "An error occurred. Please try again.";

        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
          let errorMessages = "";
          for (let field in xhr.responseJSON.errors) {
            errorMessages += xhr.responseJSON.errors[field] + "<br>";
          }
          errorMessage = errorMessages;
        }

        showAddMemberMessage(errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });

  // ===== HELPER FUNCTIONS =====
  function showAddMemberMessage(message, type) {
    const messageDiv = $("#addMemberMessage");
    const bgColor = type === "error" ? "bg-red-500" : "bg-green-500";

    messageDiv
      .html(
        `
                    <div class="p-3 rounded-lg ${bgColor} text-white text-sm">
                        ${message}
                    </div>
                `
      )
      .removeClass("hidden");

    if (type !== "error") {
      setTimeout(() => {
        messageDiv.addClass("hidden");
      }, 3000);
    }
  }

  function showWalkInMessage(message, type) {
    const messageDiv = $("#walkInMessage");
    const bgColor = type === "error" ? "bg-red-500" : "bg-green-500";

    messageDiv
      .html(
        `
                    <div class="p-3 rounded-lg ${bgColor} text-white text-sm">
                        ${message}
                    </div>
                `
      )
      .removeClass("hidden");

    if (type !== "error") {
      setTimeout(() => {
        messageDiv.addClass("hidden");
      }, 3000);
    }
  }
});
