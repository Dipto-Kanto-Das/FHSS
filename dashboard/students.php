<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';

// ১️⃣ Add Student
if(isset($_POST['add_student'])){
    $name = $_POST['name'];
    $class = $_POST['class'];
    $roll = $_POST['roll'];
    $dob = $_POST['dob'];
    $parent_name = $_POST['parent_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gpa = $_POST['gpa'];

    $stmt = $conn->prepare("INSERT INTO students (name,class,roll,dob,parent_name,phone,address,gpa) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssissssd",$name,$class,$roll,$dob,$parent_name,$phone,$address,$gpa);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Student Added Successfully</p>";
}

// ২️⃣ Delete Student
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM students WHERE id=$id");
    echo "<p style='color:red;'>Student Deleted</p>";
}

// ৩️⃣ Edit Student
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM students WHERE id=$id");
    $edit_row = $edit_result->fetch_assoc();
}

// ৪️⃣ Update Student
if(isset($_POST['update_student'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $class = $_POST['class'];
    $roll = $_POST['roll'];
    $dob = $_POST['dob'];
    $parent_name = $_POST['parent_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gpa = $_POST['gpa'];

    $stmt = $conn->prepare("UPDATE students SET name=?, class=?, roll=?, dob=?, parent_name=?, phone=?, address=?, gpa=? WHERE id=?");
    $stmt->bind_param("ssissssdi",$name,$class,$roll,$dob,$parent_name,$phone,$address,$gpa,$id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Student Updated Successfully</p>";
}

// ৫️⃣ Fetch all students
$result = $conn->query("SELECT * FROM students");
?>

<h2>Manage Students</h2>

<!-- ৬️⃣ Update / Add Form -->
<?php if(isset($_GET['edit'])){ ?>
<h3>Edit Student</h3>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
    Name: <input type="text" name="name" value="<?php echo $edit_row['name']; ?>" required><br>
    Class: <input type="text" name="class" value="<?php echo $edit_row['class']; ?>" required><br>
    Roll: <input type="number" name="roll" value="<?php echo $edit_row['roll']; ?>" required><br>
    DOB: <input type="date" name="dob" value="<?php echo $edit_row['dob']; ?>" required><br>
    Parent Name: <input type="text" name="parent_name" value="<?php echo $edit_row['parent_name']; ?>" required><br>
    Phone: <input type="text" name="phone" value="<?php echo $edit_row['phone']; ?>" required><br>
    Address: <input type="text" name="address" value="<?php echo $edit_row['address']; ?>" required><br>
    GPA: <input type="number" step="0.01" name="gpa" value="<?php echo $edit_row['gpa']; ?>" required><br>
    <input type="submit" name="update_student" value="Update Student">
</form>
<?php } else { ?>
<h3>Add New Student</h3>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Class: <input type="text" name="class" required><br>
    Roll: <input type="number" name="roll" required><br>
    DOB: <input type="date" name="dob" required><br>
    Parent Name: <input type="text" name="parent_name" required><br>
    Phone: <input type="text" name="phone" required><br>
    Address: <input type="text" name="address" required><br>
    GPA: <input type="number" step="0.01" name="gpa" required><br>
    <input type="submit" name="add_student" value="Add Student">
</form>
<?php } ?>

<!-- ৭️⃣ Students Table -->
<h3>Existing Students</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Name</th><th>Class</th><th>Roll</th><th>DOB</th>
        <th>Parent</th><th>Phone</th><th>Address</th><th>GPA</th><th>Action</th>
    </tr>
    <?php while($row=$result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['name'];?></td>
        <td><?php echo $row['class'];?></td>
        <td><?php echo $row['roll'];?></td>
        <td><?php echo $row['dob'];?></td>
        <td><?php echo $row['parent_name'];?></td>
        <td><?php echo $row['phone'];?></td>
        <td><?php echo $row['address'];?></td>
        <td><?php echo $row['gpa'];?></td>
        <td>
            <a href="students.php?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="students.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php include '../includes/footer.php'; ?>
