<div class="availability" ng-init="getCalendarData(<?php echo $unit_id; ?>)">
    <div class="availability-calendar" ng-model="availabilityCalendar" calendar show-days="renderCalendar(date, false)" update-modal-checkin="setModalCheckin(date)">
    </div>
</div>