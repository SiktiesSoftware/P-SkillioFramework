<!-- Manage the languages -->
<select name="lang" id="lang">
  <option><?= strtoupper(Request::$params["lang"]) ?></option>
  <?php
  // Display all the languages
  foreach (Lang::$languages as $key => $lang)
  {
      // Define if the actual language is equal to the current lang on the list
      if($lang == Request::$params["lang"]) continue;
  ?>
      <!-- Display the langs -->
      <option value="<?= $lang ?>"><?= strtoupper($lang) ?></option>
  <?php
  }
  ?>
</select>

<script>
  // Get the select element
  const selectElement = document.getElementById('lang');

  // Get the path of the page
  const path = document.location.pathname.split('/').slice(2).join('/');

  // Add a change event listener and change the location of the page
  selectElement.addEventListener('change', () => document.location.href = `/${selectElement.value}/${path}`);
</script>