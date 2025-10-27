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
$(document).ready(function () {
  // ===== VIEW MEMBER DETAILS =====
  $(document).on("click", ".btn-view-member", function () {
    const row = $(this).closest(".table-row");
    const userId = row.data("user-id");

    if (!userId) {
      alert(
        "User ID not found. Make sure table rows have data-user-id attribute."
      );
      return;
    }

    // Fetch member data
    $.ajax({
      type: "GET",
      url: "index.php?controller=User&action=getMemberData&user_id=" + userId,
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const member = response.data;

          // Build HTML content
          let detailsHTML = `
                        <div class="bg-gray-800 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">User ID</p>
                                    <p class="text-white font-semibold">${
                                      member.user_id
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">Role</p>
                                    <span class="px-2 py-1 ${
                                      member.role === "admin"
                                        ? "bg-red-600"
                                        : member.role === "trainer"
                                        ? "bg-purple-600"
                                        : "bg-blue-600"
                                    } text-white text-xs font-bold rounded">${
            member.role || "user"
          }</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4 mb-4">
                            <h4 class="text-white font-semibold mb-3">Personal Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-400 text-sm">First Name</p>
                                    <p class="text-white font-semibold">${
                                      member.first_name
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Middle Name</p>
                                    <p class="text-white font-semibold">${
                                      member.middle_name || "N/A"
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Last Name</p>
                                    <p class="text-white font-semibold">${
                                      member.last_name
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Email</p>
                                    <p class="text-white font-semibold">${
                                      member.email
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Member Since</p>
                                    <p class="text-white font-semibold">${
                                      member.created_at
                                    }</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h4 class="text-white font-semibold mb-3">Membership</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-400 text-sm">Current Plan</p>
                                    <p class="text-white font-semibold">${
                                      member.plan_name || "No Active Plan"
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Status</p>
                                    <span class="status-badge ${
                                      member.status === "active"
                                        ? "status-active"
                                        : "status-inactive"
                                    }">${member.status || "inactive"}</span>
                                </div>
                            </div>
                        </div>
                    `;

          $("#memberDetails").html(detailsHTML);
          $("#memberModal").data("user-id", userId);
          $("#memberModal").addClass("show");
          $("body").css("overflow", "hidden");
        } else {
          alert("Failed to load member data");
        }
      },
      error: function () {
        alert("An error occurred while loading member data");
      },
    });
  });

  // ===== OPEN EDIT FROM VIEW MODAL =====
  $("#btnEditMemberFromView").on("click", function () {
    const userId = $("#memberModal").data("user-id");
    $("#memberModal").removeClass("show");
    fetchMemberForEdit(userId);
  });

  // ===== DIRECT EDIT FROM TABLE =====
  $(document).on("click", ".btn-edit-member", function () {
    const row = $(this).closest(".table-row");
    const userId = row.data("user-id");
    fetchMemberForEdit(userId);
  });

  // ===== FETCH MEMBER DATA FOR EDITING =====
  function fetchMemberForEdit(userId) {
    $.ajax({
      type: "GET",
      url: "index.php?controller=User&action=getMemberData&user_id=" + userId,
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const member = response.data;

          $("#edit_user_id").val(member.user_id);
          $("#edit_first_name").val(member.first_name);
          $("#edit_last_name").val(member.last_name);
          $("#edit_middle_name").val(member.middle_name || "");
          $("#edit_email").val(member.email);
          $("#edit_role").val(member.role || "user");
          $("#edit_password").val("");
          $("#edit_confirm_password").val("");

          $("#editMemberModal").addClass("show");
          $("body").css("overflow", "hidden");
        } else {
          alert("Failed to load member data for editing");
        }
      },
      error: function () {
        alert("An error occurred while loading member data");
      },
    });
  }

  // ===== UPDATE MEMBER FORM SUBMISSION =====
  $("#editMemberForm").on("submit", function (e) {
    e.preventDefault();

    const password = $("#edit_password").val();
    const confirmPassword = $("#edit_confirm_password").val();
    const userId = $("#edit_user_id").val();
    if (password || confirmPassword) {
      if (password.length < 8) {
        showMessage("Password must be at least 8 characters", "error");
        return false;
      }
      if (password !== confirmPassword) {
        showMessage("Passwords do not match", "error");
        return false;
      }
    }

    const formData = new FormData(this);
    const submitBtn = $("#btnUpdateMember");
    const originalText = submitBtn.text();

    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=User&action=updateMember&user_id=" + userId,
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showMessage("✓ " + response.message, "success");
          setTimeout(() => {
            $("#editMemberModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          showMessage(response.message || "Failed to update member", "error");
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr) {
        let errorMessage = "An error occurred. Please try again.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        showMessage(errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });

  // ===== CLOSE MODALS =====
  $(".member-modal-close").on("click", function () {
    $("#memberModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $(".edit-member-close, .edit-member-cancel").on("click", function () {
    $("#editMemberModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  // ===== HELPER FUNCTION =====
  function showMessage(message, type) {
    const messageDiv = $("#editMemberMessage");
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
      setTimeout(() => messageDiv.addClass("hidden"), 3000);
    }
  }

  // ===== DIRECT DELETE FROM TABLE =====
  $(document).on("click", ".btn-delete-member", function () {
    const row = $(this).closest(".table-row");
    const userId = row.data("user-id");
    $("#deleteMemberModal").addClass("show");
    $("#delete_user_id").val(userId);

    const deleteBtn = $("#deleteBtn");
    const originalText = deleteBtn.text();
    deleteBtn.html('<span class="loading"></span>').prop("disabled", true);
    $("body").css("overflow", "hidden");

    setTimeout(() => {
      deleteBtn.html(originalText).prop("disabled", false);
    }, 2000);
  });
  // ===== CLOSE DELETE MODAL =====
  $(".delete-modal-close").on("click", function () {
    $("#deleteMemberModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $(".delete-member-close, .delete-member-cancel").on("click", function () {
    $("#deleteMemberModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $("#deleteForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = $("#deleteBtn");
    const originalText = submitBtn.text();
    const userId = $("#delete_user_id").val();
    console.log(userId);
    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=User&action=deleteMember",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showDeleteMessage("✓ " + response.message, "success");
          setTimeout(() => {
            $("#deleteMemberModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          showDeleteMessage(
            response.message || "Failed to update member",
            "error"
          );
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr) {
        let errorMessage = "An error occurred. Please try again.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        showDeleteMessage(errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });
  // ===== HELPER FUNCTION =====
  function showDeleteMessage(message, type) {
    const messageDiv = $("#deleteMemberMessage");
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
      setTimeout(() => messageDiv.addClass("hidden"), 3000);
    }
  }
});

$(document).ready(function () {
  // ===== OPEN ADD TRAINER MODAL =====
  $(document).on("click", "#btnAddNewTrainer", function () {
    $("#addTrainerForm")[0].reset();
    $("#addTrainerMessage").addClass("hidden");
    $("#addTrainerModal").addClass("show");
    $("body").css("overflow", "hidden");
  });

  // ===== ADD TRAINER FORM SUBMISSION =====
  $("#addTrainerForm").on("submit", function (e) {
    e.preventDefault();

    const password = $('input[name="password"]').val();
    const confirmPassword = $('input[name="confirm_password"]').val();

    if (password.length < 8) {
      showMessage(
        "addTrainerMessage",
        "Password must be at least 8 characters",
        "error"
      );
      return false;
    }

    if (password !== confirmPassword) {
      showMessage("addTrainerMessage", "Passwords do not match", "error");
      return false;
    }

    const formData = new FormData(this);
    const submitBtn = $("#btnAddTrainer");
    const originalText = submitBtn.text();

    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=Admin&action=addTrainer",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showMessage("addTrainerMessage", "✓ " + response.message, "success");
          setTimeout(() => {
            $("#addTrainerModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          showMessage(
            "addTrainerMessage",
            response.message || "Failed to add trainer",
            "error"
          );
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr) {
        let errorMessage = "An error occurred. Please try again.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        showMessage("addTrainerMessage", errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });

  // ===== VIEW TRAINER DETAILS =====
  $(document).on("click", ".btn-view-trainer", function () {
    const row = $(this).closest(".table-row");
    const trainerId = row.data("trainer-id");

    if (!trainerId) {
      alert("Trainer ID not found");
      return;
    }

    $.ajax({
      type: "GET",
      url:
        "index.php?controller=Admin&action=getTrainerData&trainer_id=" +
        trainerId,
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const trainer = response.data;

          let detailsHTML = `
                        <div class="bg-gray-800 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">Trainer ID</p>
                                    <p class="text-white font-semibold">${
                                      trainer.trainer_id
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm mb-1">Status</p>
                                    <span class="status-badge status-${
                                      trainer.status
                                    }">${trainer.status}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4 mb-4">
                            <h4 class="text-white font-semibold mb-3">Personal Information</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-400 text-sm">Full Name</p>
                                    <p class="text-white font-semibold">${
                                      trainer.name
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Email</p>
                                    <p class="text-white font-semibold">${
                                      trainer.email
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Contact Number</p>
                                    <p class="text-white font-semibold">${
                                      trainer.contact_no || "N/A"
                                    }</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-800 rounded-lg p-4">
                            <h4 class="text-white font-semibold mb-3">Trainer Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-gray-400 text-sm">Specialization</p>
                                    <p class="text-white font-semibold">${
                                      trainer.specialization
                                    }</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Experience</p>
                                    <p class="text-white font-semibold">${
                                      trainer.experience_years
                                    } years</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-sm">Join Date</p>
                                    <p class="text-white font-semibold">${
                                      trainer.join_date
                                    }</p>
                                </div>
                            </div>
                        </div>
                    `;

          $("#trainerDetails").html(detailsHTML);
          $("#viewTrainerModal").data("trainer-id", trainerId);
          $("#viewTrainerModal").addClass("show");
          $("body").css("overflow", "hidden");
        } else {
          alert("Failed to load trainer data");
        }
      },
      error: function () {
        alert("An error occurred while loading trainer data");
      },
    });
  });

  // ===== OPEN EDIT FROM VIEW MODAL =====
  $("#btnEditTrainer").on("click", function () {
    const row = $(this).closest(".table-row");
    const trainerId = row.data("trainer-id");
    $("#viewTrainerModal").removeClass("show");
    loadTrainerForEdit(trainerId);
  });

  // ===== DIRECT EDIT FROM TABLE =====
  $(document).on("click", ".btn-edit-trainer", function () {
    const row = $(this).closest(".table-row");
    const trainerId = row.data("trainer-id");
    loadTrainerForEdit(trainerId);
  });

  // ===== LOAD TRAINER DATA FOR EDITING =====
  function loadTrainerForEdit(trainerId) {
    $.ajax({
      type: "GET",
      url:
        "index.php?controller=Admin&action=getTrainerData&trainer_id=" +
        trainerId,
      dataType: "json",
      success: function (response) {
        if (response.success && response.data) {
          const trainer = response.data;

          $("#edit_trainer_id").val(trainer.trainer_id);
          $("#edit_user_id").val(trainer.user_id);
          $("#edit_trainer_first_name").val(trainer.first_name);
          $("#edit_trainer_last_name").val(trainer.last_name);
          $("#edit_trainer_middle_name").val(trainer.middle_name || "");
          $("#edit_trainer_email").val(trainer.email);
          $("#edit_trainer_contact_no").val(trainer.contact_no || "");
          $("#edit_specialization").val(trainer.specialization);
          $("#edit_experience_years").val(trainer.experience_years);
          $("#edit_trainer_status").val(trainer.status);
          $("#edit_bio").val(trainer.bio || "");
          $("#edit_trainer_password").val("");
          $("#edit_confirm_trainer_password").val("");

          $("#editTrainerModal").addClass("show");
          $("body").css("overflow", "hidden");
        } else {
          alert("Failed to load trainer data for editing");
        }
      },
      error: function () {
        alert("An error occurred while loading trainer data");
      },
    });
  }

  // ===== UPDATE TRAINER FORM SUBMISSION =====
  $("#editTrainerForm").on("submit", function (e) {
    e.preventDefault();

    const password = $("#edit_password").val();
    const confirmPassword = $("#edit_confirm_password").val();

    if (password || confirmPassword) {
      if (password.length < 8) {
        showMessage(
          "editTrainerMessage",
          "Password must be at least 8 characters",
          "error"
        );
        return false;
      }
      if (password !== confirmPassword) {
        showMessage("editTrainerMessage", "Passwords do not match", "error");
        return false;
      }
    }

    const formData = new FormData(this);
    const submitBtn = $("#btnUpdateTrainer");
    const originalText = submitBtn.text();

    submitBtn.html('<span class="loading"></span>').prop("disabled", true);

    $.ajax({
      type: "POST",
      url: "index.php?controller=Admin&action=updateTrainer",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (response) {
        if (response.success) {
          showMessage("editTrainerMessage", "✓ " + response.message, "success");
          setTimeout(() => {
            $("#editTrainerModal").removeClass("show");
            $("body").css("overflow", "auto");
            location.reload();
          }, 2000);
        } else {
          showMessage(
            "editTrainerMessage",
            response.message || "Failed to update trainer",
            "error"
          );
          submitBtn.html(originalText).prop("disabled", false);
        }
      },
      error: function (xhr) {
        let errorMessage = "An error occurred. Please try again.";
        if (xhr.responseJSON && xhr.responseJSON.message) {
          errorMessage = xhr.responseJSON.message;
        }
        showMessage("editTrainerMessage", errorMessage, "error");
        submitBtn.html(originalText).prop("disabled", false);
      },
    });
  });
  //add trainer modal close
  $(".add-trainer-close").on("click", function () {
    $("#addTrainerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $(".add-trainer-close, .add-trainer-cancel").on("click", function () {
    $("#addTrainerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });
  //view trainer modal
  $(".view-trainer-close, .edit-trainer-cancel").on("click", function () {
    $("#viewTrainerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });
  //close edit trainer modal
  $(".edit-trainer-close").on("click", function () {
    $("#editTrainerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });

  $(".edit-trainer-close, .edit-trainer-cancel").on("click", function () {
    $("#editTrainerModal").removeClass("show");
    $("body").css("overflow", "auto");
  });
});
