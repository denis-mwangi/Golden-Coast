<div class="box">
            <p>total price <span>*</span></p>
            <input type="text" name="total_price" class="input" id="availability_price" readonly value="$0.00">
         </div>

<div class="box">
            <p>total price <span>*</span></p>
            <input type="text" name="total_price" class="input" id="reservation_price" readonly value="$0.00">
         </div>

<script>
function calculateNights(checkIn, checkOut) {
   if (!checkIn || !checkOut || isNaN(checkIn.getTime()) || isNaN(checkOut.getTime())) {
      console.log('Invalid or missing dates:', { checkIn, checkOut });
      return 0;
   }
   if (checkOut <= checkIn) {
      console.log('Check-out date is not after check-in date:', { checkIn, checkOut });
      return 0;
   }
   const timeDiff = checkOut - checkIn;
   const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
   console.log('Calculated nights:', nights);
   return nights;
}

function suggestRooms() {
   const adultsInput = document.getElementById('adults');
   const childsInput = document.getElementById('childs');
   const roomsSelect = document.getElementById('rooms');

   if (!adultsInput || !childsInput || !roomsSelect) {
      console.error('Missing input elements for suggestRooms:', { adultsInput, childsInput, roomsSelect });
      return;
   }

   const adults = parseInt(adultsInput.value) || 0;
   const childs = parseInt(childsInput.value) || 0;

   const roomsForAdults = Math.ceil(adults / 2);
   const roomsForChildren = Math.ceil(childs / 1);
   const suggestedRooms = Math.max(roomsForAdults, roomsForChildren);

   console.log('Suggesting rooms:', { adults, childs, suggestedRooms });

   const options = roomsSelect.options;
   for (let i = 0; i < options.length; i++) {
      if (parseInt(options[i].value) === suggestedRooms) {
         roomsSelect.selectedIndex = i;
         break;
      }
   }
   updateAvailabilityPrice();
}

function updateAvailabilityPrice() {
   const checkInInput = document.getElementById('check_in');
   const checkOutInput = document.getElementById('check_out');
   const roomsInput = document.getElementById('rooms');
   const priceInput = document.getElementById('availability_price');

   if (!checkInInput || !checkOutInput || !roomsInput || !priceInput) {
      console.error('Missing input elements for updateAvailabilityPrice:', { checkInInput, checkOutInput, roomsInput, priceInput });
      return;
   }

   const checkIn = new Date(checkInInput.value);
   const checkOut = new Date(checkOutInput.value);
   const rooms = parseInt(roomsInput.value) || 1;
   const pricePerRoomPerNight = 50; // $50 per room per night

   const nights = calculateNights(checkIn, checkOut);
   const totalPrice = rooms * nights * pricePerRoomPerNight;

   console.log('Updating availability price:', { rooms, nights, totalPrice });

   priceInput.value = `$${totalPrice.toFixed(2)}`;
}

function suggestReservationRooms() {
   const adultsInput = document.getElementById('res_adults');
   const childsInput = document.getElementById('res_childs');
   const roomsSelect = document.getElementById('res_rooms');

   if (!adultsInput || !childsInput || !roomsSelect) {
      console.error('Missing input elements for suggestReservationRooms:', { adultsInput, childsInput, roomsSelect });
      return;
   }

   const adults = parseInt(adultsInput.value) || 0;
   const childs = parseInt(childsInput.value) || 0;

   const roomsForAdults = Math.ceil(adults / 2);
   const roomsForChildren = Math.ceil(childs / 1);
   const suggestedRooms = Math.max(roomsForAdults, roomsForChildren);

   console.log('Suggesting reservation rooms:', { adults, childs, suggestedRooms });

   const options = roomsSelect.options;
   for (let i = 0; i < options.length; i++) {
      if (parseInt(options[i].value) === suggestedRooms) {
         roomsSelect.selectedIndex = i;
         break;
      }
   }
   updateReservationPrice();
}

function updateReservationPrice() {
   const checkInInput = document.getElementById('res_check_in');
   const checkOutInput = document.getElementById('res_check_out');
   const roomsInput = document.getElementById('res_rooms');
   const priceInput = document.getElementById('reservation_price');

   if (!checkInInput || !checkOutInput || !roomsInput || !priceInput) {
      console.error('Missing input elements for updateReservationPrice:', { checkInInput, checkOutInput, roomsInput, priceInput });
      return;
   }

   const checkIn = new Date(checkInInput.value);
   const checkOut = new Date(checkOutInput.value);
   const rooms = parseInt(roomsInput.value) || 1;
   const pricePerRoomPerNight = 50; // $50 per room per night

   const nights = calculateNights(checkIn, checkOut);
   const totalPrice = rooms * nights * pricePerRoomPerNight;

   console.log('Updating reservation price:', { rooms, nights, totalPrice });

   priceInput.value = `$${totalPrice.toFixed(2)}`;
}

// Initialize when DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
   const requiredIds = ['check_in', 'check_out', 'rooms', 'adults', 'childs', 'availability_price', 'res_check_in', 'res_check_out', 'res_rooms', 'res_adults', 'res_childs', 'reservation_price'];
   for (const id of requiredIds) {
      if (!document.getElementById(id)) {
         console.error(`Element with ID "${id}" not found in DOM`);
      }
   }

   const checkInInput = document.getElementById('check_in');
   const checkOutInput = document.getElementById('check_out');
   const roomsInput = document.getElementById('rooms');
   const adultsInput = document.getElementById('adults');
   const childsInput = document.getElementById('childs');
   const resCheckInInput = document.getElementById('res_check_in');
   const resCheckOutInput = document.getElementById('res_check_out');
   const resRoomsInput = document.getElementById('res_rooms');
   const resAdultsInput = document.getElementById('res_adults');
   const resChildsInput = document.getElementById('res_childs');

   if (checkInInput) checkInInput.addEventListener('change', updateAvailabilityPrice);
   if (checkOutInput) checkOutInput.addEventListener('change', updateAvailabilityPrice);
   if (roomsInput) roomsInput.addEventListener('change', updateAvailabilityPrice);
   if (adultsInput) adultsInput.addEventListener('change', suggestRooms);
   if (childsInput) childsInput.addEventListener('change', suggestRooms);
   if (resCheckInInput) resCheckInInput.addEventListener('change', updateReservationPrice);
   if (resCheckOutInput) resCheckOutInput.addEventListener('change', updateReservationPrice);
   if (resRoomsInput) resRoomsInput.addEventListener('change', updateReservationPrice);
   if (resAdultsInput) resAdultsInput.addEventListener('change', suggestReservationRooms);
   if (resChildsInput) resChildsInput.addEventListener('change', suggestReservationRooms);

   console.log('Initializing price updates');
   updateAvailabilityPrice();
   updateReservationPrice();
});
</script>
