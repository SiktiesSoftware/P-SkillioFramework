const btnModifyTitle = document.getElementById("btnModifyTitle");
btnModifyTitle.addEventListener("click", function() 
{
    const titleElement = document.getElementById("title");
    console.log("Title element:", titleElement);
    titleElement.textContent = "New Title";
});