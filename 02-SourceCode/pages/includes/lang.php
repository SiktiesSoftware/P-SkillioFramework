<!-- Manage the languages -->
<form id="languageForm" action="<?=$_SERVER["REQUEST_URI"]?>" method="GET">
  <select name="lang" id="lang">
    <option><?= strtoupper($_GET["lang"]) ?></option>
    <?php
    // Display all the languages
    foreach (Lang::$languages as $key => $lang)
    {
        // Define if the actual language is equal to the current lang on the list
        if($lang == $_GET["lang"]) continue;
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
    document.getElementById('languageForm').submit();
  });
</script>