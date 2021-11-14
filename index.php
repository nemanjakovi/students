<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "students");


function hasPassed($average, $school, $grades)
{
    if ($school === 'CSM') return $average >= 7;
    return max($grades) > 8;
}
function getAverage($gradesArray)
{
    $sum = array_sum($gradesArray);
    $grades_count = count($gradesArray);
    return $sum / $grades_count;
}
function getData($students_data)
{
    if ($students_data['school'] === 'CSM') return json_encode($students_data);
    $xml = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    array_walk_recursive($students_data, array($xml, 'addChild'));
    print $xml->asXML();
}
function student()
{
    $id = $_GET['id'];
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $results = mysqli_query($conn, "SELECT * FROM student WHERE id='$id' ");
    $students_data = array();
    while ($row = mysqli_fetch_assoc($results)) {
        $grades =  $row["grades"];
        $gradesArray = explode(',', $grades);
        $average = getAverage($gradesArray);
        $students_data = array(
            "id"     =>   $row["id"],
            "name"   =>   $row["name"],
            "grades" =>   $row["grades"],
            "school" =>   $row["school"],
            "average" =>   $average,
            "hasPassed" => hasPassed($average,  $row["school"], $gradesArray)
        );
    }
    return getData($students_data);
}
print_r(student());
