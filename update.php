if($check_in < $today){
      $warning_msg[] = 'Check-in date cannot be in the past.';
   } elseif($check_out < $check_in){
      $warning_msg[] = 'Check-out date cannot be before check-in date.';
   } elseif($total_price <= 0){
      $warning_msg[] = 'Invalid price. Please select valid dates and rooms.';
   } else {
      // Room capacity validation
      $max_adults = $rooms * 2;
      $max_childs = $rooms * 1;

      if($adults > $max_adults || $childs > $max_childs){
         $warning_msg[] = 'Too many guests for selected rooms. Max 2 adults and 1 child per room allowed.';
      } else {

         $total_rooms = 0;

         $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
         $check_bookings->execute([$check_in]);

         while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
            $total_rooms += $fetch_bookings['rooms'];
         }

         if($total_rooms + $rooms > 30){
            $warning_msg[] = 'rooms are not available';
         } else {

            $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ? AND total_price = ?");
            $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs, $total_price]);

            if($verify_bookings->rowCount() > 0){
               $warning_msg[] = 'room booked already!';
            } else {
               $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs, total_price) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
               $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs, $total_price]);
               $success_msg[] = 'room booked successfully!';
            }

         }
      }
   }
}
