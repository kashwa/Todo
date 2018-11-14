<?php 

  $errors = '';

  try{
    // Connect to database. using PDO
    $db_connection = new PDO('mysql:host=localhost;dbname=todo', 'root', 'admin');
  
  } catch(PDOException $exception){
    $exception->getMessage();
  }

  // Get all tasks
  $tasks = $db_connection->query('SELECT * FROM tasks');


  try {
    if (isset($_POST['submit'])) {
      $task = $_POST["task"];
      if (empty($task)) {
        $errors = 'You must Fill in the blanks';
      } else {
        $statement = $db_connection->prepare("INSERT INTO tasks (task) values (?)");
        $statement->execute([$task]);
      }
    }
  } catch (Exception $e) {
    var_dump($e);
  }

  try{
    // Delete
    if (isset($_GET['delete'])) {
      $id = $_GET['delete'];

      $sql = "DELETE FROM tasks WHERE id = ?";
      $query = $db_connection->prepare($sql);

      $response = $query->execute(array($id));
    }
  } catch(Exception $e){
    echo $e->getMessage();
  }
  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo App (PHP & MySQL)</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <div class="heading">
      <h2>Todo App (PHP & MySQL)</h2>
    </div>

    <form method="POST">

      <?php if(isset($errors)) {?>
        <p style="color: red; margin: 0px;"><?php echo $errors; ?></p>
      <?php }?>
      
      <input type="text" name="task" class="task_input">
      <button type="submit" class="add_btn" name="submit">Add Task</button>
    </form>
    <table>
      <thead>
        <tr>
          <th>N</th>
          <th>Task</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $i =1; foreach($tasks as $task){ ?>
          <tr>
            <td><?php echo $i; ?></td>
            <td class="task"><?php echo $task['task']; ?></td>
            <td class="delete">
              <a href="index.php?delete=<?php echo $task['id']; ?>">X</a>
            </td>
          </tr>
        <?php $i++; } ?>
      </tbody>
    </table>
  </body>
</html>