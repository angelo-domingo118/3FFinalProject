<div class="schedule-legend">
    <div class="legend-item">
        <div class="legend-color available"></div>
        <span>Available</span>
    </div>
    <div class="legend-item">
        <div class="legend-color unavailable"></div>
        <span>Unavailable</span>
    </div>
    <div class="legend-item">
        <div class="legend-color booked"></div>
        <span>Booked</span>
    </div>
</div>

<table class="schedule-calendar">
    <thead>
        <tr>
            <th>Time</th>
            <?php
            $weekStart = new DateTime('Monday this week');
            for ($i = 0; $i < 7; $i++) {
                $date = clone $weekStart;
                $date->modify("+$i days");
                echo "<th>" . $date->format('D n/j') . "</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        for ($hour = 9; $hour <= 18; $hour++) {
            echo "<tr>";
            echo "<td>" . sprintf("%02d:00", $hour) . "</td>";
            
            for ($day = 0; $day < 7; $day++) {
                $date = clone $weekStart;
                $date->modify("+$day days");
                $cellDate = $date->format('Y-m-d');
                $cellTime = sprintf("%02d:00", $hour);
                
                echo "<td class='time-slot' data-date='$cellDate' data-time='$cellTime'></td>";
            }
            echo "</tr>";
        }
        ?>
    </tbody>
</table>