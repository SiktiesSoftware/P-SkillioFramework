<?php
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
  // Check if the lang is set
  if (isset($_POST["lang"])) 
  {
    //die($_POST["lang"]);
      $_SESSION["lang"] = $_POST["lang"];
      //die("ss".$_SESSION["lang"]);
      header("refresh: 0");
  }
}
?>

<!-- Manage the languages -->
<form id="form" action="<?=$_SERVER["REQUEST_URI"]?>" method="POST">
  <select name="lang" id="lang">
    <option><?= strtoupper($_SESSION["lang"]) ?></option>
    <?php
    // Display all the languages
    foreach (Lang::$languages as $key => $lang) 
    {
        // Define if the actual language is equal to the current lang on the list
        if($lang == $_SESSION["lang"]) continue;
    ?>
        <!-- Display the langs -->
        <option value="<?= $lang ?>"><?= strtoupper($lang) ?></option>
    <?php
    }
    ?>
  </select>
</form>

<script>
  // Get the select element
  const selectElement = document.getElementById('lang');

  // Add a change event listener to the select element
  selectElement.addEventListener('change', function() 
  {
    // Get the selected value
    const selectedValue = this.value;

    // Set the value as a hidden field in the form
    const hiddenField = document.createElement('input');
    hiddenField.type = 'hidden';
    hiddenField.name = 'lang'; 
    hiddenField.value = selectedValue;
    document.getElementById('form').appendChild(hiddenField);

    // Submit the form to send the selected value to the server
    document.getElementById('form').submit();
  });
</script>