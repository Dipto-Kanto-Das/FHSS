<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}
include '../includes/db.php';
include '../includes/header.php';

// Add Notice
if(isset($_POST['add_notice'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO notices (title,content) VALUES (?,?)");
    $stmt->bind_param("ss",$title,$content);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Notice Added Successfully</p>";
}

// Delete Notice
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM notices WHERE id=$id");
    echo "<p style='color:red;'>Notice Deleted</p>";
}

// Edit Notice
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM notices WHERE id=$id");
    $edit_row = $edit_result->fetch_assoc();
}

// Update Notice
if(isset($_POST['update_notice'])){
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("UPDATE notices SET title=?, content=? WHERE id=?");
    $stmt->bind_param("ssi",$title,$content,$id);
    $stmt->execute();
    $stmt->close();
    echo "<p style='color:green;'>Notice Updated Successfully</p>";
}

// Fetch all notices
$result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
?>

<h2>Manage Notices</h2>

<!-- Add / Edit Form -->
<?php if(isset($_GET['edit'])){ ?>
<h3>Edit Notice</h3>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $edit_row['id']; ?>">
    Title: <input type="text" name="title" value="<?php echo $edit_row['title']; ?>" required><br>
    Content: <textarea name="content" required><?php echo $edit_row['content']; ?></textarea><br>
    <input type="submit" name="update_notice" value="Update Notice">
</form>
<?php } else { ?>
<h3>Add New Notice</h3>
<form method="POST">
    Title: <input type="text" name="title" required><br>
    Content: <textarea name="content" required></textarea><br>
    <input type="submit" name="add_notice" value="Add Notice">
</form>
<?php } ?>

<!-- Notices Table -->
<h3>Existing Notices</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Title</th><th>Content</th><th>Created At</th><th>Action</th>
    </tr>
    <?php while($row=$result->fetch_assoc()){ ?>
    <tr>
        <td><?php echo $row['id'];?></td>
        <td><?php echo $row['title'];?></td>
        <td><?php echo $row['content'];?></td>
        <td><?php echo $row['created_at'];?></td>
        <td>
            <a href="notices.php?edit=<?php echo $row['id']; ?>">Edit</a> |
            <a href="notices.php?delete=<?php echo $row['id'];?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php include '../includes/footer.php'; ?>
