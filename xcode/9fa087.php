
<?php
$con=mysqli_connect("localhost","kylewilson","kw121889","login");
// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM users");

echo "<table border='1'>
<tr>
<th>User Name</th>
<th>Email</th>
<th>Code</th>
<th>Clientname</th>
<th>First Contact</th>
<th>First Phone Number</th>
<th>Second Contact</th>
<th>Second Phone Number</th>
<th>Third Contact</th>
<th>Third Phone Number</th>

</tr>";

while($row = mysqli_fetch_array($result))
{
    echo "<tr>";
    echo "<td>" . $row['user_name'] . "</td>";
    echo "<td>" . $row['user_email'] . "</td>";
    echo "<td>" . $row['user_code'] . "</td>";
    echo "<td>" . $row['clientname'] . "</td>";
    echo "<td>" . $row['fullname1'] . "</td>";
    echo "<td>" . $row['fullphone1'] . "</td>";
    echo "<td>" . $row['fullname2'] . "</td>";
    echo "<td>" . $row['fullphone2'] . "</td>";
    echo "<td>" . $row['fullname3'] . "</td>";
    echo "<td>" . $row['fullphone3'] . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>









