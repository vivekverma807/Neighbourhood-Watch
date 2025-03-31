document.addEventListener("DOMContentLoaded", function() {
    const userId = document.getElementById("user_id").value;

    // Fetch user data
    fetch(`../PHP/fetch-user.php?user_id=${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById("fullname").value = data.fullname;
            document.getElementById("email").value = data.email;
            document.getElementById("username").value = data.username;
            document.getElementById("phone").value = data.phone;
            document.getElementById("address").value = data.address;
            document.getElementById("profile-img").src = `../../profile-image/${data.profile_pic}`;
        });

    // Handle form submission
    document.getElementById("edit-profile-form").addEventListener("submit", function(event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append("user_id", userId);
        formData.append("fullname", document.getElementById("fullname").value);
        formData.append("email", document.getElementById("email").value);
        formData.append("username", document.getElementById("username").value);
        formData.append("password", document.getElementById("password").value);
        formData.append("phone", document.getElementById("phone").value);
        formData.append("address", document.getElementById("address").value);

        fetch("../PHP/update-profile.php", { method: "POST", body: formData })
            .then(response => response.text())
            .then(alert);
    });
});