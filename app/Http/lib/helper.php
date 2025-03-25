<?php

function getWeekStartAndEnd($date)
{
    $dt = new DateTime($date);

    // Trouver le lundi de la semaine
    $startOfWeek = clone $dt;
    $startOfWeek->modify('monday this week');

    // Trouver le dimanche de la semaine
    $endOfWeek = clone $dt;
    $endOfWeek->modify('sunday this week');

    return [
        'start' => $startOfWeek->format('Y-m-d'),
        'end' => $endOfWeek->format('Y-m-d')
    ];
}

function getMonthStartAndEnd($date)
{
    $dt = new DateTime($date);

    // Premier jour du mois
    $startOfMonth = clone $dt;
    $startOfMonth->modify('first day of this month');

    // Dernier jour du mois
    $endOfMonth = clone $dt;
    $endOfMonth->modify('last day of this month');

    return [
        'start' => $startOfMonth->format('Y-m-d'),
        'end' => $endOfMonth->format('Y-m-d')
    ];
}

function getYearStartAndEnd($date)
{
    $dt = new DateTime($date);

    // Premier jour de l'année
    $startOfYear = clone $dt;
    $startOfYear->modify('first day of January this year');

    // Dernier jour de l'année
    $endOfYear = clone $dt;
    $endOfYear->modify('last day of December this year');

    return [
        'start' => $startOfYear->format('Y-m-d'),
        'end' => $endOfYear->format('Y-m-d')
    ];
}

function dateFilter(String $date, String $timeSlot)
{
    if ($timeSlot === 'week') {
        return getWeekStartAndEnd($date);
    } else if ($timeSlot === 'month') {
        return getMonthStartAndEnd($date);
    } else if ($timeSlot === 'year') {
        return getYearStartAndEnd($date);
    }
}