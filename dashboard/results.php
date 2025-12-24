<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';

// Add Result
if(isset($_POST['add_result'])){
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];
    $grade = $_POST['grade'];

    $stmt = $conn->prepare("INSERT INTO results (student_id,subject,marks,grade) VALUES (?,?,?,?)");
    $stmt->bind_param("isis",$student_id,$subject,$marks,$grade);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Result Added Successfully</p>";
}

// Delete Result
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM results WHERE id=$id");
    echo "<p style='color:red;'>Result Deleted</p>";
}

// Edit Result
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_result_query = $conn->query("SELECT * FROM results WHERE id=$id");
    $edit_row = $edit_result_query->fetch_assoc();
}

// Update Result
if(isset($_POST['update_result'])){
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];
    $grade = $_POST['grade'];

    $stmt = $conn->prepare("UPDATE results SET student_id=?, subject=?, marks=?, grade=? WHERE id=?");
    $stmt->bind_param("isisi",$student_id,$subject,$marks,$grade,$id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Result Updated Successfully</p>";
}

// Fetch students for dropdown
$students = $conn->query("SELECT id,name FROM students");

// Fetch all results
$results = $conn->query("SELECT r.id, s.name AS student_name, r.subject, r.marks, r.grade 
                        FROM results r
                        JOIN students s ON r.student_id = s.id");
?>

<h2>Manage Results</h2>

<!-- Add / Edit Form -->
<?php if(isset($_GET['edit'])){ ?>
<h3>Edit Result</h3>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
    Student:
    <select name="student_id" required>
        <?php
        $students->data_seek(0);
        while($s=$students->fetch_assoc()){
            $selected = ($s['id']==$edit_row['student_id']) ? 'selected' : '';
            echo "<option value='{$s['id']}' $selected>{$s['name']}</option>";
        }
        ?>
    </select><br>
    Subject: <input type="text" name="subject" value="<?php echo $edit_row['subject']; ?>" required><br>
    Marks: <input type="number" name="marks" value="<?php echo $edit_row['marks']; ?>" required><br>
    Grade: <input type="text" name="grade" value="<?php echo $edit_row['grade']; ?>" required><br>
    <input type="submit" name="update_result" value="Update Result">
</form>
<?php } else { ?>
<h3>Add New Result</h3>
<form method="POST">
    Student:
    <select name="student_id" required>
        <option value="">Select Student</option>
        <?php while($s=$students->fetch_assoc()){ ?>
            <option value="<?php echo $s['id'];?>"><?php echo $s['name'];?></option>
        <?php } ?>
    </select><br>
    Subject: <input type="text" name="subject" required><br>
    Marks: <input type="number" name="marks" required><br>
    Grade: <input type="text" name="grade" required><br>
    <input type="submit" name="add_result" value="Add Result">
</form>
<?php } ?>

<!-- Results Table -->
<h3>Existing Results</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Student</th><th>Subject</th><th>Marks</th><th>Grade</th><th>Action</th>
    </tr>
    <?php while($row=$results->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['student_name'];?></td>
        <td><?php echo $row['subject'];?></td>
        <td><?php echo $row['marks'];?></td>
        <td><?php echo $row['grade'];?></td>
        <td>
            <a href="results.php?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="results.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php include '../includes/footer.php'; ?>
