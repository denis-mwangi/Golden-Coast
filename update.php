<?php
include 'components/connect.php';

// Ensure user is logged in
if (isset($_COOKIE['user_id'])) {
    $user_id = filter_var($_COOKIE['user_id'], FILTER_SANITIZE_STRING);
} else {
    setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
    header('location:index.php');
    exit;
}

// Handle booking cancellation
if (isset($_POST['cancel'])) {
    $booking_id = filter_var($_POST['booking_id'], FILTER_SANITIZE_STRING);

    $verify_booking = $conn->prepare("SELECT * FROM `bookings` WHERE booking_id = ?");
    $verify_booking->execute([$booking_id]);

    if ($verify_booking->rowCount() > 0) {
        $delete_booking = $conn->prepare("DELETE FROM `bookings` WHERE booking_id = ?");
        $delete_booking->execute([$booking_id]);
        $success_msg[] = 'Booking cancelled successfully!';
    } else {
        $warning_msg[] = 'Booking already cancelled!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Booking section starts -->
<section class="bookings">
    <h1 class="heading">My Bookings</h1>

    <div class="box-container">
        <?php
        // Fetch bookings for the user
        $select_bookings = $conn->prepare("SELECT booking_id, name, email, number, check_in, check_out, rooms, adults, childs, total_price FROM `bookings` WHERE user_id = ?");
        $select_bookings->execute([$user_id]);

        if ($select_bookings->rowCount() > 0) {
            while ($fetch_booking = $select_bookings->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>Name: <span><?= htmlspecialchars($fetch_booking['name']); ?></span></p>
            <p>Email: <span><?= htmlspecialchars($fetch_booking['email']); ?></span></p>
            <p>Number: <span><?= htmlspecialchars($fetch_booking['number']); ?></span></p>
            <p>Check-in: <span><?= htmlspecialchars($fetch_booking['check_in']); ?></span></p>
            <p>Check-out: <span><?= htmlspecialchars($fetch_booking['check_out']); ?></span></p>
            <p>Rooms: <span><?= htmlspecialchars($fetch_booking['rooms']); ?></span></p>
            <p>Adults: <span><?= htmlspecialchars($fetch_booking['adults']); ?></span></p>
            <p>Children: <span><?= htmlspecialchars($fetch_booking['childs']); ?></span></p>
            <p>Total Price: <span>
                <?php
                if ($fetch_booking['total_price'] > 0) {
                    echo '$' . number_format($fetch_booking['total_price'], 2);
                } else {
                    echo 'N/A (Contact support)';
                }
                ?>
            </span></p>
            <p>Booking ID: <span><?= htmlspecialchars($fetch_booking['booking_id']); ?></span></p>
            <form action="" method="POST">
                <input type="hidden" name="booking_id" value="<?= htmlspecialchars($fetch_booking['booking_id']); ?>">
                <input type="submit" value="Cancel Booking" name="cancel" class="btn" onclick="return confirm('Cancel this booking?');">
            </form>
        </div>
        <?php
            }
        } else {
        ?>
        <div class="box" style="text-align: center;">
            <p style="padding-bottom: .5rem; text-transform:capitalize;">No bookings found!</p>
            <a href="index.php#reservation" class="btn">Book New</a>
        </div>
        <?php
        }
        ?>
    </div>
</section>
<!-- Booking section ends -->

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
