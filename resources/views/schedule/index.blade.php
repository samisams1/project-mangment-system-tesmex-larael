@extends('layout')

@section('content')
    <h1>Task Management Schedule</h1>

    <style>
        .calendar {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        .calendar th, .calendar td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .calendar th {
            background-color: #f2f2f2;
        }

        .calendar .selected-month {
            background-color: #f2f2f2;
        }

        .calendar .today {
            background-color: #e6f2ff;
        }

        .calendar-day {
            position: relative;
            height: 80px;
            overflow-y: auto;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .schedule-color {
            width: 10px;
            height: 10px;
            margin-right: 5px;
            border-radius: 50%;
        }

        .schedule-title {
            flex-grow: 1;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .schedule-line {
            position: absolute;
            height: 2px;
            background-color: #ccc;
        }
    </style>

    @php
        $selectedMonth = request()->input('month', date('Y-m'));
        $startDate = new DateTime($selectedMonth . '-01');
        $endDate = new DateTime(date('Y-m-t', strtotime($selectedMonth)));
        $dateRange = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);
        $days = [];
        foreach ($dateRange as $date) {
            $days[] = $date->format('Y-m-d');
        }
    @endphp

    <form method="GET" class="shortcut">
        <label for="date">Select a date:</label>
        <input type="month" id="date" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()">
    </form>

    <table class="calendar">
        <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
        @php
            $weekIndex = 0;
        @endphp
        @foreach ($days as $day)
            @php
                $isCurrentMonth = (substr($day, 0, 7) === $selectedMonth);
                $isToday = ($day === date('Y-m-d'));
                $scheduleItems = [];
                foreach ($tasks as $task) {
                    if ($day >= $task->start_date && $day <= $task->due_date) {
                        $scheduleItems[] = [
                            'title' => $task->title,
                            'start' => $task->start_date,
                            'end' => $task->due_date,
                            'color' => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                        ];
                    }
                }
            @endphp
            @if ($weekIndex % 7 == 0)
                <tr>
            @endif
            <td @if ($isCurrentMonth) class="selected-month" @endif>
                <div class="calendar-day @if ($isToday) today @endif @if (count($scheduleItems) > 0) has-schedule @endif">
                    <div class="day-number">{{ date('j', strtotime($day)) }}</div>
                    @foreach ($scheduleItems as $scheduleItem)
                        <div class="schedule-item">
                            <div class="schedule-color" style="background-color: {{ $scheduleItem['color'] }};"></div>
                            <div class="schedule-title">{{ $scheduleItem['title'] }}</div>
                        </div>
                        @php
                            $startDate = strtotime($scheduleItem['start']);
                            $endDate = strtotime($scheduleItem['end']);
                            $dayDate = strtotime($day);
                            $totalDuration = $endDate - $startDate;
                        @endphp
                        @if ($totalDuration > 0)
                            <div class="schedule-line" style="
                                left: {{ ($dayDate - $startDate) / $totalDuration * 100 }}%;
                                width: {{ ($endDate - $dayDate) / $totalDuration * 100 }}%;
                                background-color: {{ $scheduleItem['color'] }};
                            "></div>
                        @endif
                    @endforeach
                </div>
            </td>
            @if ($weekIndex % 7 == 6)
                </tr>
            @endif
            @php
                $weekIndex++;
            @endphp
        @endforeach
    </table>
@endsection
