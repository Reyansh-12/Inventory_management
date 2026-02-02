<?php
include "../../controllers/dbConnection.php";

$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$userId = $_POST['userId'] ?? 0;

$response = [
    'emailExists' => false,
    'phoneExists' => false
];

if ($email) {
    $q = mysqli_query($con,
        "SELECT id FROM new_user 
         WHERE user_email='$email' AND id != '$userId'"
    );
    if (mysqli_num_rows($q) > 0) {
        $response['emailExists'] = true;
    }
}

if ($phone) {
    $q = mysqli_query($con,
        "SELECT id FROM new_user 
         WHERE user_contact='$phone' AND id != '$userId'"
    );
    if (mysqli_num_rows($q) > 0) {
        $response['phoneExists'] = true;
    }
}

echo json_encode($response);
?>
<script>
let emailValid = true;
let phoneValid = true;

function checkUserData(type, value) {
    $.ajax({
        url: "/Backend/src/Pages/Users/checkUser.php",
        type: "POST",
        data: {
            [type]: value,
            userId: <?= $userId ?? 0 ?>
        },
        dataType: "json",
        success: function(res) {

            if (res.emailExists) {
                $('#emailError').text('Email already exists');
                emailValid = false;
            } else {
                $('#emailError').text('');
                emailValid = true;
            }

            if (res.phoneExists) {
                $('#contactError').text('Phone number already exists');
                phoneValid = false;
            } else {
                $('#contactError').text('');
                phoneValid = true;
            }
        }
    });
}

// Email check
$('#userEmail').on('blur', function () {
    const val = $(this).val();
    if (val.length > 5) {
        checkUserData('email', val);
    }
});

// Phone check
$('#contact').on('blur', function () {
    const val = $(this).val();
    if (val.length === 10) {
        checkUserData('phone', val);
    }
});

// Stop form submit
$('#myForm').on('submit', function (e) {
    if (!emailValid || !phoneValid) {
        e.preventDefault();
    }
});
</script>
