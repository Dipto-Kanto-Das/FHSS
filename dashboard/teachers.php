<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';

// Add Teacher
if(isset($_POST['add_teacher'])){
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO teachers (name,subject,phone,email) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$subject,$phone,$email);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Teacher Added Successfully</p>";
}

// Delete Teacher
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM teachers WHERE id=$id");
    echo "<p style='color:red;'>Teacher Deleted</p>";
}

// Edit Teacher
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM teachers WHERE id=$id");
    $edit_row = $edit_result->fetch_assoc();
}

// Update Teacher
if(isset($_POST['update_teacher'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE teachers SET name=?, subject=?, phone=?, email=? WHERE id=?");
    $stmt->bind_param("ssssi",$name,$subject,$phone,$email,$id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Teacher Updated Successfully</p>";
}

// Fetch all teachers
$result = $conn->query("SELECT * FROM teachers");
?>

<h2>Manage Teachers</h2>

<!-- Add / Edit Form -->
<?php if(isset($_GET['edit'])){ ?>
<h3>Edit Teacher</h3>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
    Name: <input type="text" name="name" value="<?php echo $edit_row['name']; ?>" required><br>
    Subject: <input type="text" name="subject" value="<?php echo $edit_row['subject']; ?>" required><br>
    Phone: <input type="text" name="phone" value="<?php echo $edit_row['phone']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $edit_row['email']; ?>"><br>
    <input type="submit" name="update_teacher" value="Update Teacher">
</form>
<?php } else { ?>
<h3>Add New Teacher</h3>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Subject: <input type="text" name="subject" required><br>
    Phone: <input type="text" name="phone" required><br>
    Email: <input type="email" name="email"><br>
    <input type="submit" name="add_teacher" value="Add Teacher">
</form>
<?php } ?>

<!-- Teachers Table -->
<h3>Existing Teachers</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Name</th><th>Subject</th><th>Phone</th><th>Email</th><th>Action</th>
    </tr>
    <?php while($row=$result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['name'];?></td>
        <td><?php echo $row['subject'];?></td>
        <td><?php echo $row['phone'];?></td>
        <td><?php echo $row['email'];?></td>
        <td>
            <a href="teachers.php?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="teachers.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php include '../includes/footer.php'; ?>
