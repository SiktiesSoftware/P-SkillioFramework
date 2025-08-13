const selector = document.getElementById("language");
selector.addEventListener("change", function() 
{
    // Get the selected language from the dropdown
    const selectedLanguage = selector.value;
    const url = new URL(window.location.href);

    // Only update the first part of the url : from /en/about to /fr/about
    const pathParts = url.pathname.split('/');
    pathParts[1] = selectedLanguage; // Update the language part
    url.pathname = pathParts.join('/');

    // Redirect to the new URL
    window.location.href = url.href;
});